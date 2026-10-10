<?php

use App\Livewire\Reports\Viewer;
use App\Livewire\Sales\Index as SalesIndex;
use App\Models\Party;
use App\Models\SalesInvoice;
use App\Models\Vehicle;
use App\Support\UrlToken;
use Livewire\Attributes\Url;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
});

it('encodes ids reversibly, differently per record type, never as the plain number', function () {
    foreach ([1, 2, 12, 999, 123456, 4294967295] as $id) {
        $code = UrlToken::encodeId(SalesInvoice::class, $id);

        expect($code)->toMatch('/^[0-9A-Za-z]{6}$/')
            ->and($code)->not->toBe(str_pad((string) $id, 6, '0', STR_PAD_LEFT))
            ->and(UrlToken::decodeId(SalesInvoice::class, $code))->toBe($id)
            ->and(UrlToken::encodeId(Vehicle::class, $id))->not->toBe($code);
    }

    // Consecutive records get unrelated codes.
    expect(UrlToken::encodeId(SalesInvoice::class, 1))->not->toBe(UrlToken::encodeId(SalesInvoice::class, 2))
        ->and(UrlToken::decodeId(SalesInvoice::class, 'abc'))->toBeNull()
        ->and(UrlToken::decodeId(SalesInvoice::class, '12'))->toBeNull();
});

it('puts codes, not ids, in links and opens the record from its code only', function () {
    $vehicle = purchaseVehicle();
    $url = route('vehicles.show', $vehicle);

    expect($url)->not->toEndWith('/vehicles/'.$vehicle->id)
        ->and($url)->toEndWith('/vehicles/'.$vehicle->getRouteKey());

    $this->get($url)->assertOk();
    $this->get('/vehicles/'.$vehicle->id)->assertNotFound();

    // The same code for another kind of record finds nothing.
    $party = Party::factory()->create();
    $this->get('/parties/'.UrlToken::encodeId(Vehicle::class, $party->id).'/statement')->assertNotFound();
    $this->get(route('parties.statement', $party))->assertOk();
});

it('keeps report filters encrypted in the link and ignores altered ones', function () {
    $state = UrlToken::encodeState(['as_of' => '2026-01-15', 'side' => 'receivable']);

    expect($state)->not->toContain('2026-01-15')->not->toContain('as_of');

    Livewire::withQueryParams(['s' => $state])
        ->test(Viewer::class, ['key' => 'aging'])
        ->assertSet('filters.as_of', '2026-01-15');

    expect(UrlToken::decodeState($state.'x'))->toBe([])
        ->and(UrlToken::decodeState('not-encrypted'))->toBe([]);
    Livewire::withQueryParams([]);
});

it('does not put search text in the address bar', function () {
    Livewire::test(SalesIndex::class)
        ->set('search', '0913345566')
        ->assertSet('search', '0913345566');

    $reflection = new ReflectionProperty(SalesIndex::class, 'search');
    expect($reflection->getAttributes(Url::class))->toBe([]);
});
