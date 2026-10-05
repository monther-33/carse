{{-- Shared by the report screen and the PDF. Expects $columns, $rows, $totals. --}}
<table class="table-base grid">
    <thead>
    <tr>
        @foreach ($columns as $column)
            <th>{{ $column['label'] }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
    @forelse ($rows as $row)
        @php($style = $row['_style'] ?? null)
        <tr @class([
                'bg-gray-50 font-semibold' => $style === 'heading' || $style === 'subtotal',
                'bg-brand-50 font-bold' => $style === 'total',
                'total-row' => in_array($style, ['subtotal', 'total'], true),
            ])>
            @foreach ($columns as $key => $column)
                @php($numeric = in_array($column['type'], ['money', 'int', 'rate'], true))
                <td @class(['num' => $numeric, 'whitespace-nowrap' => $numeric || $column['type'] === 'date'])
                    @if ($loop->first && ! empty($row['_indent'])) style="padding-inline-start: {{ 0.75 + $row['_indent'] * 1.25 }}rem; padding-right: {{ 0.75 + $row['_indent'] * 1.25 }}rem" @endif>
                    {{ \App\Reports\Cell::format($row[$key] ?? null, $column['type']) }}
                </td>
            @endforeach
        </tr>
    @empty
        <tr><td colspan="{{ count($columns) }}" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
    @endforelse
    @if ($totals && $rows)
        <tr class="bg-brand-50 font-bold total-row">
            @foreach ($columns as $key => $column)
                <td class="num whitespace-nowrap">
                    @if ($loop->first && ! array_key_exists($key, $totals))
                        {{ __('documents.total') }}
                    @elseif (array_key_exists($key, $totals))
                        {{ \App\Reports\Cell::format($totals[$key], $column['type']) }}
                    @endif
                </td>
            @endforeach
        </tr>
    @endif
    </tbody>
</table>
