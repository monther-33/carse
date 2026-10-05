<?php

namespace App\Reports\Inventory;

use App\Enums\VehicleStatus;
use App\Models\User;
use App\Models\Vehicle;
use App\Reports\Report;
use App\Support\Money;

/**
 * Vehicles currently in stock. Cost columns only with vehicles.view_cost.
 */
class InventoryReport extends Report
{
    public static function key(): string
    {
        return 'inventory';
    }

    public function group(): string
    {
        return 'inventory';
    }

    public function permissions(): array
    {
        return ['reports.inventory', 'vehicles.view'];
    }

    public function filters(): array
    {
        return ['branch_id', 'brand_id', 'status'];
    }

    public function options(): array
    {
        return ['status' => collect(VehicleStatus::cases())->filter(fn ($s) => $s->isInStock())
            ->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()];
    }

    public function columns(User $user, array $f): array
    {
        $columns = [
            'vin' => ['label' => __('vehicles.vin'), 'type' => 'text'],
            'vehicle' => ['label' => __('vehicles.vehicle'), 'type' => 'text'],
            'color' => ['label' => __('vehicles.color'), 'type' => 'text'],
            'status' => ['label' => __('app.fields.status'), 'type' => 'text'],
            'location' => ['label' => __('vehicles.location'), 'type' => 'text'],
            'days' => ['label' => __('reports.days_in_stock'), 'type' => 'int'],
            'asking' => ['label' => __('vehicles.asking_price'), 'type' => 'money', 'total' => true],
        ];

        if ($user->can('vehicles.view_cost')) {
            $columns['cost'] = ['label' => __('vehicles.total_cost'), 'type' => 'money', 'total' => true];
        }

        return $columns;
    }

    public function rows(User $user, array $f): array
    {
        return Vehicle::query()
            ->with(['brand', 'carModel', 'color', 'location'])
            ->inStock()
            ->when(! empty($f['branch_id']), fn ($q) => $q->where('branch_id', $f['branch_id']))
            ->when(! empty($f['brand_id']), fn ($q) => $q->where('brand_id', $f['brand_id']))
            ->when(! empty($f['status']), fn ($q) => $q->where('status', $f['status']))
            ->orderBy('received_at')
            ->get()
            ->map(fn (Vehicle $v) => [
                'vin' => $v->vin,
                'vehicle' => $v->title(),
                'color' => $v->color?->name,
                'status' => $v->status->label(),
                'location' => $v->location?->name,
                'days' => $v->daysInStock(),
                'asking' => $v->asking_price !== null ? Money::of($v->asking_price) : null,
                'cost' => Money::of($v->total_cost),
            ])->all();
    }
}
