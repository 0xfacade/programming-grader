<?php

return [
    'smtp' => [
        'host' => 'smtp.google.com',
        'user' => 'smtp_user_name',
        'pass' => 'a8s7d6f8sd7f',
        'from' => 'no-reply@somehost.com',
    ],
    'maxSubmissions' => 1000,
    'uploadDirectory' => '/some/absolute/path/',  // This should be outside the wwwroot, and the webserver needs to have write access.
    'tutorials' => [
        [
            'id' => 11,
            'description' => '11 - Mittwoch 08:30 - Florian Behrens',
            'tutor' => 'Florian Behrens',
            'mail' => 'florian.behrens@rocketmail.com',
        ],
        [
            'id' => 12,
            'description' => '12 - Mittwoch 12:30 - Radu Coanda',
            'tutor' => 'Radu Coanda',
            'mail' => 'radu@somemailservice.com',
        ],
    ],
    'exercises' => [
        [
            'id' => 4,
            'name' => 'Blatt 3 - Aufgabe 4',
            'allowAdditionalFiles' => true,
            'files' => [
                [
                    'name' => 'Main.java',
                    'isGiven' => false,
                    'mode' => 'text/x-java',
                    'code' => '// Hier Code für Main.java einfügen.',
                ]
            ]
        ]
    ],
];