@extends('print.layout')

@section('content')
    <h1>{{ $report->title() }}</h1>
    <p class="muted">
        @foreach ($report->filters() as $filter)
            @if (! empty($f[$filter]) && in_array($filter, ['from', 'to', 'as_of'], true))
                {{ __('reports.filters.'.$filter) }}: <span class="num">{{ $f[$filter] }}</span>&nbsp;&nbsp;
            @endif
        @endforeach
        — {{ __('reports.printed_at', ['date' => now()->format('Y-m-d H:i')]) }}
    </p>

    @include('reports.table', ['columns' => $columns, 'rows' => $rows, 'totals' => $totals])

    @foreach ($notes as $note)
        <p class="muted">{{ $note }}</p>
    @endforeach
@endsection
