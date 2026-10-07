<?php

return [
    'return_entry' => 'Showroom costs of a consignment car returned to its owner :number — :vin',
    'contribution' => 'Partner share of the cost of :vin',
    'errors' => [
        'partners_total' => 'The partners\' shares must add up to less than 100% (the rest is the showroom\'s).',
        'entry_status' => 'Invalid entry status for the vehicle.',
        'percent' => 'The showroom commission percent must be above zero and below 100.',
        'amount' => 'The agreed amount must be above zero.',
        'owner_row' => 'Each owner needs a party and a share above zero.',
        'owner_twice' => 'The same owner cannot be listed twice.',
        'no_owners' => 'Add at least one owner.',
        'owner_inactive' => 'One of the owners does not exist or is inactive.',
        'shares_total' => 'The owners\' shares must add up to :total%.',
        'not_active' => 'This vehicle is not an active consignment in the showroom.',
        'cannot_return' => 'The vehicle cannot be returned to its owner while ":status".',
        'owner_already_paid' => ':name has already been paid more than would be left to them from this car; recover it with a receipt voucher first.',
    ],
];
