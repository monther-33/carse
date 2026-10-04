<?php

namespace App\Enums;

/**
 * Vehicle lifecycle (spec 4.1) plus two states the spec leaves implicit:
 *  - pending: the vehicle only exists on a draft purchase invoice (not in stock yet);
 *  - returned_to_supplier: the vehicle left stock through a purchase return or cancellation.
 */
enum VehicleStatus: string
{
    case Pending = 'pending';
    case InTransit = 'in_transit';
    case InCustoms = 'in_customs';
    case InPreparation = 'in_preparation';
    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'sold';
    case Returned = 'returned';
    case ReturnedToSupplier = 'returned_to_supplier';

    /**
     * Statuses a purchase (or trade-in) may put a vehicle in.
     *
     * @return list<self>
     */
    public static function entryStatuses(): array
    {
        return [self::InTransit, self::InCustoms, self::InPreparation, self::Available];
    }

    /**
     * Transitions a user may trigger by hand; everything else happens through documents.
     *
     * @return list<self>
     */
    public function manualTargets(): array
    {
        return match ($this) {
            self::InTransit => [self::InCustoms],
            self::InCustoms => [self::InPreparation],
            self::InPreparation => [self::Available],
            self::Returned => [self::Available],
            default => [],
        };
    }

    /**
     * Every transition the state machine accepts (manual or document-driven).
     *
     * @return list<self>
     */
    public function allowedTargets(): array
    {
        return match ($this) {
            self::Pending => self::entryStatuses(),
            self::InTransit => [self::InCustoms, self::ReturnedToSupplier],
            self::InCustoms => [self::InPreparation, self::ReturnedToSupplier],
            self::InPreparation => [self::Available, self::ReturnedToSupplier],
            self::Available => [self::Reserved, self::Sold, self::ReturnedToSupplier],
            self::Reserved => [self::Available, self::Sold],
            self::Sold => [self::Returned, ...self::entryStatuses()],
            self::Returned => [self::Available],
            self::ReturnedToSupplier => self::entryStatuses(),
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->allowedTargets(), true);
    }

    /** The vehicle is physically ours and its cost sits in a stock account. */
    public function isInStock(): bool
    {
        return in_array($this, [self::InTransit, self::InCustoms, self::InPreparation, self::Available, self::Reserved, self::Returned], true);
    }

    /** Cost sits in "vehicles in transit / customs" rather than "vehicle inventory". */
    public function isInTransit(): bool
    {
        return in_array($this, [self::InTransit, self::InCustoms], true);
    }

    public function stockRole(): AccountRole
    {
        return $this->isInTransit() ? AccountRole::InTransit : AccountRole::Inventory;
    }

    public function label(): string
    {
        return __('enums.vehicle_status.'.$this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::InTransit, self::InCustoms, self::InPreparation => 'yellow',
            self::Available => 'green',
            self::Reserved => 'blue',
            self::Sold, self::ReturnedToSupplier => 'gray',
            self::Returned => 'red',
        };
    }
}
