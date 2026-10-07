{{-- "Consignment" / "Partnership" badge for a car that is not (only) the showroom's. Load the vehicle's ownership first. --}}
@props(['vehicle'])
@if ($vehicle->ownership_id !== null && $vehicle->ownership)
    <x-ui.badge :color="$vehicle->ownership->isConsignment() ? 'purple' : 'blue'" {{ $attributes }}>{{ $vehicle->ownership->kind->label() }}</x-ui.badge>
@endif
