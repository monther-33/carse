<?php

return [
    'errors' => [
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
    ],
];
