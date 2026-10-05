<?php

use App\Actions\Imports\ImportOpeningBalances;
use App\Actions\Imports\ImportOpeningStock;
use App\Actions\Journals\PostManualJournal;
use App\Actions\OpeningStock\PostOpeningStock;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\BusinessRuleException;
use App\Exports\ImportTemplate;
use App\Imports\CellParser;
use App\Imports\InvalidCell;
use App\Livewire\Imports\Index;
use App\Models\Brand;
use App\Models\ManualJournal;
use App\Models\OpeningStock;
use App\Models\Party;
use App\Models\Vehicle;
use App\Services\Currency\ExchangeRateService;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

/** A real spreadsheet built from the import's own template, with the given rows. */
function sheet(string $kind, array $rows, string $extension = 'xlsx', ?array $columns = null): UploadedFile
{
    $columns ??= app(Index::KINDS[$kind])->columns();
    $content = Excel::raw(new ImportTemplate($columns, $rows), $extension === 'csv' ? ExcelType::CSV : ExcelType::XLSX);

    return UploadedFile::fake()->createWithContent("data.{$extension}", $content);
}

test('cells are read from Excel floats, Arabic digits, dates and labels', function () {
    expect((string) CellParser::amount(45000.5, 'cost'))->toBe('45000.500')
        ->and((string) CellParser::amount('٤٥٬٠٠٠', 'cost'))->toBe('45000.000')
        ->and((string) CellParser::amount('1,250.75', 'cost'))->toBe('1250.750')
        ->and(CellParser::date(46023, 'received_at')->toDateString())->toBe('2026-01-01')
        ->and(CellParser::date('31/01/2026', 'received_at')->toDateString())->toBe('2026-01-31')
        ->and(CellParser::date('2026-2-3', 'received_at')->toDateString())->toBe('2026-02-03')
        ->and(CellParser::enum(VehicleStatus::class, 'في الطريق', 'status', 'enums.vehicle_status'))->toBe(VehicleStatus::InTransit)
        ->and(CellParser::enum(VehicleStatus::class, 'Available', 'status', 'enums.vehicle_status'))->toBe(VehicleStatus::Available)
        ->and(CellParser::integer('2019', 'year', 1950, 2030))->toBe(2019);

    expect(fn () => CellParser::amount('12.3456', 'cost'))->toThrow(InvalidCell::class)
        ->and(fn () => CellParser::amount('-5', 'cost'))->toThrow(InvalidCell::class)
        ->and(fn () => CellParser::date('31/02/2026', 'received_at'))->toThrow(InvalidCell::class)
        ->and(fn () => CellParser::enum(VehicleStatus::class, 'sold', 'status', 'enums.vehicle_status', VehicleStatus::entryStatuses()))->toThrow(InvalidCell::class);
});

test('parties: errors block the whole file, then a clean file imports once and re-imports nothing', function () {
    $this->actingAs(userWithRole('accountant'));
    Party::factory()->create(['name' => 'موجود سابقًا', 'national_id' => '119900001111']);

    $rows = [
        ['name' => 'سالم علي', 'type' => 'عميل', 'phone' => '0912345678', 'national_id' => '119800002222', 'credit_limit' => 5000],
        ['name' => 'شركة الأمل', 'type' => 'supplier', 'phone' => '0213334444'],
        ['name' => 'موجود سابقًا', 'type' => 'عميل', 'national_id' => '119900001111'],
        ['name' => 'سالم علي', 'type' => 'عميل', 'national_id' => '119800002222'],  // duplicate of row 2
        ['name' => 'بلا نوع'],                                                     // missing type
    ];

    Livewire::test(Index::class)
        ->set('kind', 'parties')
        ->set('file', sheet('parties', $rows))
        ->call('check')
        ->assertSet('preview.errors.0.row', 5)
        ->assertSet('preview.errors.1.row', 6)
        ->call('import')
        ->assertHasErrors('file');
    expect(Party::query()->count())->toBe(1);

    $clean = array_slice($rows, 0, 3);
    Livewire::test(Index::class)->set('kind', 'parties')->set('file', sheet('parties', $clean))
        ->call('check')->assertSet('preview.errors', [])
        ->call('import')->assertHasNoErrors();

    expect(Party::query()->count())->toBe(3)
        ->and(Party::query()->where('national_id', '119800002222')->value('credit_limit'))->toBe('5000.000');

    Livewire::test(Index::class)->set('kind', 'parties')->set('file', sheet('parties', $clean, 'csv'))
        ->call('import')->assertHasNoErrors();
    expect(Party::query()->count())->toBe(3);
});

test('columns are matched by their English names in any order, and missing required columns are refused', function () {
    $this->actingAs(userWithRole('admin'));
    $file = UploadedFile::fake()->createWithContent('p.csv', "Phone,Type,Name\n0911111111,customer,Ali\n");

    Livewire::test(Index::class)->set('kind', 'parties')->set('file', $file)->call('import')->assertHasNoErrors();
    expect(Party::query()->where('name', 'Ali')->value('phone'))->toBe('0911111111');

    $file = UploadedFile::fake()->createWithContent('p.csv', "Phone,Name\n0911111111,Ali\n");
    Livewire::test(Index::class)->set('kind', 'parties')->set('file', $file)->call('check')->assertHasErrors('file');
});

test('current vehicles become an opening stock that posts to inventory and opening balances', function () {
    $this->actingAs(userWithRole('accountant'));

    $rows = [
        ['vin' => 'jtdbr32e530012345', 'brand' => 'تويوتا', 'model' => 'كامري', 'year' => 2019, 'cost' => 45000.5,
            'received_at' => today()->subDays(100)->format('d/m/Y'), 'asking_price' => 52000, 'color' => 'أبيض لؤلؤي'],
        ['vin' => 'LB37622Z0NX000001', 'brand' => 'جيلي', 'model' => 'كولراي', 'year' => 2024, 'cost' => '61000',
            'status' => 'في الطريق', 'condition' => 'جديدة'],
    ];

    Livewire::test(Index::class)->set('kind', 'vehicles')->set('file', sheet('vehicles', $rows))
        ->call('check')
        ->assertSet('preview.errors', [])
        ->assertSee('جيلي')                       // new brand listed in the summary
        ->call('import')->assertHasNoErrors();

    $stock = OpeningStock::query()->with('items')->sole();
    expect($stock->status)->toBe(DocumentStatus::Draft)
        ->and(Vehicle::query()->where('vin', 'JTDBR32E530012345')->value('status'))->toBe(VehicleStatus::Pending)
        ->and(Brand::query()->where('name', 'جيلي')->exists())->toBeTrue();

    Livewire::test(Index::class)->call('approve', $stock->id)->assertHasNoErrors();

    $camry = Vehicle::query()->where('vin', 'JTDBR32E530012345')->sole();
    expect($stock->fresh()->number)->toStartWith('OS-')
        ->and($camry->status)->toBe(VehicleStatus::Available)
        ->and($camry->total_cost)->toBe('45000.500')
        ->and($camry->received_at->toDateString())->toBe(today()->subDays(100)->toDateString())
        ->and((string) baseBalance('14'))->toBe('45000.500')
        ->and((string) baseBalance('15'))->toBe('61000.000')
        ->and((string) baseBalance('34'))->toBe('-106000.500')
        ->and(ledgerIsBalanced())->toBeTrue();

    // The same VIN cannot come in twice while in stock.
    $preview = app(ImportOpeningStock::class)->analyse([2 => $rows[0]], ['date' => today()->toDateString()]);
    expect($preview->errors)->toHaveCount(1);
});

test('an opening stock cancels only while its vehicles are untouched, and a cancelled VIN can be imported again', function () {
    $this->actingAs(userWithRole('admin'));
    $row = ['vin' => 'KMHD841CBMU000777', 'brand' => 'هيونداي', 'model' => 'إلنترا', 'year' => 2021, 'cost' => '38000'];

    app(ImportOpeningStock::class)->import([2 => $row], ['date' => today()->toDateString()]);
    $stock = app(PostOpeningStock::class)->handle(OpeningStock::query()->sole());

    app(PostOpeningStock::class)->cancel($stock, 'خطأ في التكلفة');
    expect(baseBalance('14')->isZero())->toBeTrue()
        ->and(Vehicle::query()->sole()->status)->toBe(VehicleStatus::ReturnedToSupplier);

    // Re-imported with the right cost: same vehicle row.
    app(ImportOpeningStock::class)->import([2 => ['cost' => '36500'] + $row], ['date' => today()->toDateString()]);
    $second = app(PostOpeningStock::class)->handle(OpeningStock::query()->where('status', 'draft')->sole());
    expect(Vehicle::query()->count())->toBe(1)
        ->and((string) baseBalance('14'))->toBe('36500.000');

    sell(Vehicle::query()->sole(), ['price' => '42000']);
    expect(fn () => app(PostOpeningStock::class)->cancel($second, 'x'))->toThrow(BusinessRuleException::class);
});

test('opening balances become a manual journal; the difference goes to the opening balances account', function () {
    $this->actingAs(userWithRole('accountant'));
    app(ExchangeRateService::class)->setRate(usd(), today(), '5.000000');
    $customer = Party::factory()->create(['name' => 'عميل مدين', 'national_id' => '120000000001']);
    $supplier = Party::factory()->create(['name' => 'مورّد دائن']);

    $rows = [
        2 => ['account' => cashbox('خزينة دينار')->account->code, 'debit' => '25000'],
        3 => ['account' => cashbox('خزينة دولار')->account->code, 'currency' => 'usd', 'debit' => '1000'],      // rate of the day: 5
        4 => ['account' => '13', 'party' => '120000000001', 'debit' => '12000'],
        5 => ['account' => '21', 'party' => 'مورّد دائن', 'currency' => 'USD', 'credit' => '2000', 'rate' => '4.8'],
        6 => ['account' => '31', 'credit' => '20000'],
    ];

    $preview = app(ImportOpeningBalances::class)->analyse($rows, ['date' => today()->toDateString()]);
    expect($preview->errors)->toBe([])
        ->and($preview->summary[1])->toContain('12,400.000');   // 25,000 + 5,000 + 12,000 − 9,600 − 20,000

    app(ImportOpeningBalances::class)->import($rows, ['date' => today()->toDateString()]);
    $journal = ManualJournal::query()->sole();
    app(PostManualJournal::class)->handle($journal);

    expect((string) baseBalance('13'))->toBe('12000.000')
        ->and((string) baseBalance('21'))->toBe('-9600.000')
        ->and((string) baseBalance('34'))->toBe('-12400.000')
        ->and(ledgerIsBalanced())->toBeTrue();
    expect($journal->lines()->where('party_id', $customer->id)->exists())->toBeTrue()
        ->and($journal->lines()->where('party_id', $supplier->id)->value('rate'))->toBe('4.800000');
});

test('opening balances refuse stock accounts, group accounts, unknown accounts and missing parties', function () {
    $this->actingAs(userWithRole('admin'));

    $preview = app(ImportOpeningBalances::class)->analyse([
        2 => ['account' => '14', 'debit' => '1000'],
        3 => ['account' => '11', 'debit' => '1000'],
        4 => ['account' => '9999', 'debit' => '1000'],
        5 => ['account' => '13', 'debit' => '1000'],
        6 => ['account' => '31', 'party' => 'أحد', 'credit' => '1000'],
        7 => ['account' => '31', 'debit' => '5', 'credit' => '5'],
    ], ['date' => today()->toDateString()]);

    expect(array_column($preview->errors, 'row'))->toBe([2, 3, 4, 5, 6, 7]);
    expect(fn () => app(ImportOpeningBalances::class)->import([2 => ['account' => '14', 'debit' => '1']], ['date' => today()->toDateString()]))
        ->toThrow(BusinessRuleException::class);
    expect(ManualJournal::query()->count())->toBe(0);
});

test('only users allowed to import reach the screen, and each kind follows its permissions', function () {
    $this->actingAs(userWithRole('cashier'))->get(route('imports.index'))->assertForbidden();
    $this->actingAs(userWithRole('sales'))->get(route('imports.index'))->assertForbidden();
    $this->actingAs(userWithRole('accountant'))->get(route('imports.index'))->assertOk()->assertSee(__('imports.kinds.balances'));

    $this->actingAs(userWithRole('accountant'));
    Livewire::test(Index::class)->set('kind', 'parties')->call('template')->assertFileDownloaded();
});
