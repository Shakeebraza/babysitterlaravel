<?php

namespace Tests\Feature;

use App\Models\Enums\KidsType;
use App\Models\MultiLogin;
use App\Models\User;
use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
    }

    public function test_myRequests_no_valid_session_token()
    {
        $data = [
            "session_token" => "",
            "language" => ""
        ];

        $response = $this->post('/api/myRequests', $data);

        $response->assertStatus(200);

        $response->assertJson([
            'result' => "0",
            'message' => "The session token field is required."
        ]);
    }

    public function test_myRequests()
    {
        $user = User::where('email', 'thomas@hirschi.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $data = [
            "session_token" => $login->session_token,
            "language" => "en"
        ];

        $response = $this->post('/api/myRequests', $data);

        $response->assertStatus(200);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                [
                    "id" => 6,
                    "user_id" => $user->id,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "Dorfbachstrasse 12, 8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [
                    ],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                    "reawardedRequest" => [
                    ],
                    "accepted_request" => [
                    ]
                ],
                [
                    "id" => 3,
                    "user_id" => $user->id,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "group",
                    "address" => "Dorfbachstrasse 12, 8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [
                        [
                            "id" => 1,
                            "name" => "Family"
                        ]
                    ],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                    "reawardedRequest" => [
                    ],
                    "accepted_request" => [
                    ]
                ],
                [
                    "id" => 5,
                    "user_id" => $user->id,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "Dorfbachstrasse 12, 8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [
                    ],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                    "reawardedRequest" => [
                    ],
                    "accepted_request" => [
                    ]
                ],
                [
                    "id" => 2,
                    "user_id" => $user->id,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "group",
                    "address" => "Dorfbachstrasse 12, 8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [
                        [
                            "id" => 1,
                            "name" => "Family"
                        ]
                    ],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                    "reawardedRequest" => [
                    ],
                    "accepted_request" => [
                    ]
                ],
                [
                    "id" => 4,
                    "user_id" => $user->id,
                    "awarded" => 1,
                    "reawarded" => 1,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "Dorfbachstrasse 12, 8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [
                    ],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                    "reawardedRequest" => [
                        [
                            "id" => 2,
                            "request_status" => 1,
                            "status" => 1,
                            "description" => "No problem",
                            "payment_type" => 1,
                            "amount" => 0,
                            "user" => [
                                "id" => 2,
                                "first_name" => "Marianne",
                                "surname" => "Hunziker",
                                "identify" => 0,
                                "image" => "",
                                "email" => "marianne@hunziker.ch",
                                "phone" => null,
                                "date_of_birth" => null,
                                "address" => "8805 Richterswil",
                                "city" => "Richterswil",
                                "aboutme" => null
                            ]
                        ]
                    ],
                    "accepted_request" => [
                        [
                            "id" => 2,
                            "request_status" => 1,
                            "status" => 1,
                            "description" => "No problem",
                            "payment_type" => 1,
                            "amount" => 0,
                            "user" => [
                                "id" => 2,
                                "first_name" => "Marianne",
                                "surname" => "Hunziker",
                                "identify" => 0,
                                "image" => "",
                                "email" => "marianne@hunziker.ch",
                                "phone" => null,
                                "date_of_birth" => null,
                                "address" => "8805 Richterswil",
                                "city" => "Richterswil",
                                "aboutme" => null
                            ]
                        ]
                    ]
                ],
                [
                    "id" => 1,
                    "user_id" => $user->id,
                    "awarded" => 1,
                    "reawarded" => 1,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "group",
                    "address" => "Dorfbachstrasse 12, 8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [
                        [
                            "id" => 1,
                            "name" => "Family"
                        ]
                    ],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                    "reawardedRequest" => [
                        [
                            "id" => 1,
                            "request_status" => 1,
                            "status" => 1,
                            "description" => "No problem",
                            "payment_type" => 1,
                            "amount" => 0,
                            "user" => [
                                "id" => 2,
                                "first_name" => "Marianne",
                                "surname" => "Hunziker",
                                "identify" => 0,
                                "image" => "",
                                "email" => "marianne@hunziker.ch",
                                "phone" => null,
                                "date_of_birth" => null,
                                "address" => "8805 Richterswil",
                                "city" => "Richterswil",
                                "aboutme" => null
                            ]
                        ]
                    ],
                    "accepted_request" => [
                        [
                            "id" => 1,
                            "request_status" => 1,
                            "status" => 1,
                            "description" => "No problem",
                            "payment_type" => 1,
                            "amount" => 0,
                            "user" => [
                                "id" => 2,
                                "first_name" => "Marianne",
                                "surname" => "Hunziker",
                                "identify" => 0,
                                "image" => "",
                                "email" => "marianne@hunziker.ch",
                                "phone" => null,
                                "date_of_birth" => null,
                                "address" => "8805 Richterswil",
                                "city" => "Richterswil",
                                "aboutme" => null
                            ]
                        ]
                    ]
                ],
            ]
        ], $ignoreFields);
    }

    public function test_forMeRequests()
    {
        $user = User::where('email', 'marianne@hunziker.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $data = [
            "session_token" => $login->session_token,
            "language" => "en"
        ];

        $response = $this->post('/api/forMeRequests', $data);

        $response->assertStatus(200);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                [
                    "id" => 2,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "group",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ],
                [
                    "id" => 3,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "group",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    'requestAccepted' => 0,
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ]
            ]
        ], $ignoreFields);
    }

    public function test_requestDetails()
    {
        $user = User::where('email', 'thomas@hirschi.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $data = [
            "session_token" => $login->session_token,
            "request_id" => 1,
            "language" => "en"
        ];

        $response = $this->post('/api/requestDetails', $data);

        $response->assertStatus(200);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                "id" => 1,
                "user_id" => $user->id,
                "awarded" => 1,
                "reawarded" => 1,
                "title" => "First Request",
                "description" => "Test Description",
                "visibility" => "group",
                "address" => "Dorfbachstrasse 12, 8805 Richterswil",
                "city" => "Richterswil",
                "latitude" => "47.2064059",
                "longitude" => "8.7065606",
                "status" => "active",
                'requestAccepted' => 0,
                "kids" => [
                    [
                        "id" => 1,
                        "name" => "Max",
                        "month" => "1 months",
                        "year" => "6 years",
                    ]
                ],
                "groups" => [
                    [
                        "id" => 1,
                        "name" => "Family"
                    ]
                ]
            ]
        ], $ignoreFields);

        $user2 = User::where('email', 'marianne@hunziker.ch')->first();
        $login2 = MultiLogin::where('user_id', $user2->id)->first();

        $data = [
            "session_token" => $login2->session_token,
            "request_id" => 2,
            "language" => "en"
        ];

        $response = $this->post('/api/requestDetails', $data);

        $response->assertStatus(200);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                "id" => 2,
                "user_id" => 1,
                "awarded" => 0,
                "reawarded" => 0,
                "title" => "First Request",
                "description" => "Test Description",
                "visibility" => "group",
                "address" => "8805 Richterswil",
                "city" => "Richterswil",
                "latitude" => "47.2064059",
                "longitude" => "8.7065606",
                "status" => "active",
                'requestAccepted' => 0,
                "kids" => [
                    [
                        "id" => 1,
                        "name" => "Max",
                        "month" => "1 months",
                        "year" => "6 years",
                    ]
                ],
                "groups" => [],
            ]
        ], $ignoreFields);
    }

    public function test_myAppliedRequestList()
    {
        $user = User::where('email', 'marianne@hunziker.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $data = [
            "session_token" => $login->session_token,
            "language" => "en"
        ];

        $response = $this->post('/api/myAppliedRequestList', $data);

        $response->assertStatus(200);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myApplies" => [
                [
                    "id" => 2,
                    "status" => 1,
                    "request_status" => 1,
                    "request" => [
                        "id" => 4,
                        "user_id" => 1,
                        "awarded" => 1,
                        "reawarded" => 1,
                        "title" => "First Request",
                        "description" => "Test Description",
                        "visibility" => "public",
                        "address" => "8805 Richterswil",
                        "city" => "Richterswil",
                        "latitude" => "47.2064059",
                        "longitude" => "8.7065606",
                        "status" => "active",
                        "kids" => [
                            [
                                "id" => 1,
                                "name" => "Max",
                                "month" => "1 months",
                                "year" => "6 years",
                            ]
                        ],
                        "groups" => [],
                        "user" => [
                            'id' => 1,
                            'first_name' => 'Thomas',
                            'surname' => 'Hirschi',
                            'identify' => 0,
                            'image' => '',
                            'email' => 'thomas@hirschi.ch',
                            'phone' => null,
                            'date_of_birth' => null,
                            'address' => '8805 Richterswil',
                            "city" => "Richterswil",
                            'aboutme' => null,
                        ],
                    ],
                    "myRequests" => [
                        [
                            "id" => 4,
                            "user_id" => 1,
                            "awarded" => 1,
                            "reawarded" => 1,
                            "title" => "First Request",
                            "description" => "Test Description",
                            "visibility" => "public",
                            "address" => "8805 Richterswil",
                            "city" => "Richterswil",
                            "latitude" => "47.2064059",
                            "longitude" => "8.7065606",
                            "status" => "active",
                            "kids" => [
                                [
                                    "id" => 1,
                                    "name" => "Max",
                                    "month" => "1 months",
                                    "year" => "6 years",
                                ]
                            ],
                            "groups" => [],
                            "user" => [
                                'id' => 1,
                                'first_name' => 'Thomas',
                                'surname' => 'Hirschi',
                                'identify' => 0,
                                'image' => '',
                                'email' => 'thomas@hirschi.ch',
                                'phone' => null,
                                'date_of_birth' => null,
                                'address' => '8805 Richterswil',
                                "city" => "Richterswil",
                                'aboutme' => null,
                            ],
                        ]
                    ]
                ],
                [
                    "id" => 1,
                    "status" => 1,
                    "request_status" => 1,
                    "request" => [
                        "id" => 1,
                        "user_id" => 1,
                        "awarded" => 1,
                        "reawarded" => 1,
                        "title" => "First Request",
                        "description" => "Test Description",
                        "visibility" => "group",
                        "address" => "8805 Richterswil",
                        "city" => "Richterswil",
                        "latitude" => "47.2064059",
                        "longitude" => "8.7065606",
                        "status" => "active",
                        "kids" => [
                            [
                                "id" => 1,
                                "name" => "Max",
                                "month" => "1 months",
                                "year" => "6 years",
                            ]
                        ],
                        "groups" => [],
                        "user" => [
                            'id' => 1,
                            'first_name' => 'Thomas',
                            'surname' => 'Hirschi',
                            'identify' => 0,
                            'image' => '',
                            'email' => 'thomas@hirschi.ch',
                            'phone' => null,
                            'date_of_birth' => null,
                            'address' => '8805 Richterswil',
                            "city" => "Richterswil",
                            'aboutme' => null,
                        ],
                    ],
                    "myRequests" => [
                        [
                            "id" => 1,
                            "user_id" => 1,
                            "awarded" => 1,
                            "reawarded" => 1,
                            "title" => "First Request",
                            "description" => "Test Description",
                            "visibility" => "group",
                            "address" => "8805 Richterswil",
                            "city" => "Richterswil",
                            "latitude" => "47.2064059",
                            "longitude" => "8.7065606",
                            "status" => "active",
                            "kids" => [
                                [
                                    "id" => 1,
                                    "name" => "Max",
                                    "month" => "1 months",
                                    "year" => "6 years",
                                ]
                            ],
                            "groups" => [],
                            "user" => [
                                'id' => 1,
                                'first_name' => 'Thomas',
                                'surname' => 'Hirschi',
                                'identify' => 0,
                                'image' => '',
                                'email' => 'thomas@hirschi.ch',
                                'phone' => null,
                                'date_of_birth' => null,
                                'address' => '8805 Richterswil',
                                "city" => "Richterswil",
                                'aboutme' => null,
                            ],
                        ]
                    ],
                ],
            ]
        ], $ignoreFields);
    }

    public function test_nearMeRequests_emptyFilter()
    {
        $user = User::where('email', 'marianne@hunziker.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $data = [
            "session_token" => $login->session_token,
            "latitude" => 0,
            "longitude" => 0,
            //"distance" => 0,
            //"kids_type" => 0,
            //"price_range" => 0,
            "language" => "en"
        ];

        $response = $this->post('/api/nearMeRequests', $data);

        $response->assertStatus(200);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                [
                    "id" => 5,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ],
                [
                    "id" => 6,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ]
            ]
        ], $ignoreFields);
    }

    public function test_nearMeRequests_priceFilter()
    {
        $user = User::where('email', 'marianne@hunziker.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $dataHighPrice = [
            "session_token" => $login->session_token,
            "latitude" => 0,
            "longitude" => 0,
            //"distance" => 0,
            //"kids_type" => 0,
            "price_range" => 50,
            "language" => "en"
        ];

        $response = $this->post('/api/nearMeRequests', $dataHighPrice);

        $response->assertStatus(200);
        $response->assertJson([
            'result' => 0,
            'message' => 'Data not found'
        ]);

        $dataSmallPrice = [
            "session_token" => $login->session_token,
            "latitude" => 0,
            "longitude" => 0,
            //"distance" => 0,
            //"kids_type" => 0,
            "price_range" => 20,
            "language" => "en"
        ];

        $response = $this->post('/api/nearMeRequests', $dataSmallPrice);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                [
                    "id" => 5,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ],
                [
                    "id" => 6,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ]
            ]
        ], $ignoreFields);
    }

    public function test_nearMeRequests_distanceFilter()
    {
        $user = User::where('email', 'marianne@hunziker.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $dataInvalidRange = [
            "session_token" => $login->session_token,
            "latitude" => 0,
            "longitude" => 0,
            "distance" => 50,
            "language" => "en"
        ];

        $response = $this->post('/api/nearMeRequests', $dataInvalidRange);

        $response->assertStatus(200);
        $response->assertJson([
            'result' => 0,
            'message' => 'Data not found'
        ]);

        $dataRightRange = [
            "session_token" => $login->session_token,
            "latitude" => 47.2064059,
            "longitude" => 8.7065606,
            "distance" => 10,
            "language" => "en"
        ];

        $response = $this->post('/api/nearMeRequests', $dataRightRange);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                [
                    "id" => 5,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ],
                [
                    "id" => 6,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ]
            ]
        ], $ignoreFields);
    }

    public function test_nearMeRequests_kidsTypeFilter()
    {
        $user = User::where('email', 'marianne@hunziker.ch')->first();
        $login = MultiLogin::where('user_id', $user->id)->first();

        $dataTypeNotMatch = [
            "session_token" => $login->session_token,
            "latitude" => 0,
            "longitude" => 0,
            "kids_type" => KidsType::NEWBORN->value,
            "language" => "en"
        ];

        $response = $this->post('/api/nearMeRequests', $dataTypeNotMatch);

        $response->assertStatus(200);
        $response->assertJson([
            'result' => 0,
            'message' => 'Data not found'
        ]);

        $dataTypeMatch = [
            "session_token" => $login->session_token,
            "latitude" => 0,
            "longitude" => 0,
            "kids_type" => KidsType::SCHOOL->value,
            "language" => "en"
        ];

        $response = $this->post('/api/nearMeRequests', $dataTypeMatch);

        $ignoreFields = ['from_date', 'to_date', 'created_at', 'updated_at'];
        $this->assertJsonWithoutFields($response, [
            "result" => 1,
            "message" => "Success",
            "myRequests" => [
                [
                    "id" => 5,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ],
                [
                    "id" => 6,
                    "user_id" => 1,
                    "awarded" => 0,
                    "reawarded" => 0,
                    "title" => "First Request",
                    "description" => "Test Description",
                    "visibility" => "public",
                    "address" => "8805 Richterswil",
                    "city" => "Richterswil",
                    "latitude" => "47.2064059",
                    "longitude" => "8.7065606",
                    "status" => "active",
                    "kids" => [
                        [
                            "id" => 1,
                            "name" => "Max",
                            "month" => "1 months",
                            "year" => "6 years",
                        ]
                    ],
                    "groups" => [],
                    "user" => [
                        'id' => 1,
                        'first_name' => 'Thomas',
                        'surname' => 'Hirschi',
                        'identify' => 0,
                        'image' => '',
                        'email' => 'thomas@hirschi.ch',
                        'phone' => null,
                        'date_of_birth' => null,
                        'address' => '8805 Richterswil',
                        "city" => "Richterswil",
                        'aboutme' => null,
                    ],
                ]
            ]
        ], $ignoreFields);
    }

}
