<?php

return [
    'new' => 'New reservation',
    'reserve' => 'Reserve',
    'created' => 'Reservation created.',
    'cancelled' => 'Reservation cancelled.',
    'sell' => 'Sell',
    'expires_at' => 'Expires on',
    'deposit_pending' => 'Deposit awaiting approval',
    'hint' => 'The car is reserved at once; the deposit is a receipt voucher on customer deposits.',
    'cancel_hint' => 'The car becomes available again. A posted deposit stays as the customer\'s credit in customer deposits, to be refunded with a payment voucher or applied to a later sale.',
    'deposit_description' => 'Reservation deposit :number — :vin',
    'expired_note' => 'Reservation period ended',

    'errors' => [
        'not_available' => 'Vehicle :vin is not available for reservation.',
        'deposit' => 'The deposit must be greater than zero.',
        'expiry' => 'The expiry date cannot be before the reservation date.',
        'not_active' => 'The reservation is not active.',
    ],
];
