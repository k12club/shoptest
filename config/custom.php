<?php

return [
    'timezone' => 'America/Los_Angeles',
    'tax' => 8,
    'states' => [
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',
    ],
    'order_status' => [
        'processing'       => 'Processing',
        'shipped'          => 'Shipped',
        'delivered'        => 'Delivered',
        'cancel_requested' => 'Cancel Requested',
        'canceled'         => 'Canceled',
        'refunded'         => 'Refunded'
    ],
    'payment_status' => [
        'not_paid' => 'Not Paid',
        'paid'     => 'Paid',
        'refunded' => 'Refunded'
    ],
    'payment_method' => [
        'cash' => 'Cash',
        'card' => 'Card',
    ],
    'checkout' => [
        'cash_on_delivery' => true,
        'pay_later' => true,
        'shipping' => [
            'carriers' => [
                'usps' => [
                    'name' => 'USPS',
                    'plans' => [
                        'standard' => [
                            'name' => 'Standard',
                            'plan' => 'USPS Priority Mail 1-2 Day',
                            'fee' => 6.80,
                        ],
                        'express'  => [
                            'name' => 'Express',
                            'plan'  => 'USPS Priority Mail Express 1-Day',
                            'fee' => 22.95
                        ]
                    ]
                ],
                'ups' => [
                    'name' => 'UPS',
                    'plans' => [
                        'standard' => [
                            'name' => 'Standard',
                            'plan' => 'UPS Ground',
                            'fee' => 10.44
                        ],
                        'express'  => [
                            'name' => 'Express',
                            'plan'  => 'UPS Next Day Air',
                            'fee' => 79.82
                        ]
                    ]
                ]
            ],
            'default' => ['usps', 'standard']
        ],
    ],
    'emails' => [
        'templates' => [
            'shipping_confirmation' => 'www-shopdemo-com-shipping-confirmation',
            'order_confirmation'    => 'www-shopdemo-com-order-confirmation',
            'new_order'             => 'www-shopdemo-com-new-order'
        ]
    ]
];