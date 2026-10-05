<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Reports\Cell;
use App\Reports\Report;
use App\Reports\ReportRegistry;
use App\Support\Pdf;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

/**
 * PDF (letterhead, A4) and Excel exports of any report, with the same filters as the screen.
 */
class ReportController extends Controller
{
    public function pdf(Request $request, string $key, Settings $settings): Response
    {
        [$report, $f, $columns, $rows] = $this->run($request, $key);
        $logo = $settings->get('company.logo');

        return Pdf::inline('reports.pdf', [
            'company' => [
                'name' => $settings->get('company.name', config('app.name')),
                'phone' => $settings->get('company.phone'),
                'address' => $settings->get('company.address'),
                'logo' => $logo && Storage::disk('public')->exists($logo) ? Storage::disk('public')->path($logo) : null,
            ],
            'report' => $report,
            'f' => $f,
            'columns' => $columns,
            'rows' => $rows,
            'totals' => Cell::totals($columns, $rows),
            'notes' => $report->notes($request->user(), $f),
        ], $key.'.pdf', landscape: count($columns) > 6);
    }

    public function excel(Request $request, string $key): Response
    {
        [$report, $f, $columns, $rows] = $this->run($request, $key);

        return Excel::download(new ReportExport($report->title(), $columns, $rows), $key.'-'.now()->format('Ymd').'.xlsx');
    }

    /**
     * @return array{0: Report, 1: array<string, mixed>, 2: array<string, array{label: string, type: string, total?: bool}>, 3: list<array<string, mixed>>}
     */
    private function run(Request $request, string $key): array
    {
        $report = ReportRegistry::find($key);
        abort_if($report === null, 404);
        abort_unless($report->allows($request->user()), 403);

        $f = $report->resolve((array) $request->input('f', []));
        foreach ($report->required() as $required) {
            abort_if(empty($f[$required]), 422);
        }

        return [$report, $f, $report->columns($request->user(), $f), $report->rows($request->user(), $f)];
    }
}
