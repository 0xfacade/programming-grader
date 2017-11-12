<?php

return [
    'ui' => [
        'title' => 'Abgabe Programmierung',
        'subtitle' => 'Vorlesung WS 2017/18 an der RWTH Aachen bei Prof. Giesl',
        'info' => '<p>Hier können Sie den Programmierteil ihrer Abgabe hochladen. Bitte beachten Sie, dass
nicht alle Tutoren an der Online-Abgabe teilnehmen und Sie daher nur hier abgeben können, wenn ihr Tutorium
in der Liste der Tutoren aufgeführt wird.</p>
<p>Da es sich um ein neues System handelt, bitten wir Sie, ihre Abgabe <b>unbedingt aufzubewahren</b>, falls es 
zu technischen Schwierigkeiten kommt und Ihre Abgabe nicht gespeichert werden kann. Bitte melden Sie eventuelle Probleme
 <a href="mailto:florian.behrens@rocketmail.com">hier</a>.</p>',
    ],
    // This is used to notify the tutors about new submissions.
    'smtp' => [
        'host' => 'smtp.google.com',
        'user' => 'smtp_user_name',
        'pass' => 'a8s7d6f8sd7f',
        'from' => 'no-reply@somehost.com',
    ],
    // After this many submissions have been uploaded, no more
    // submissions will be accepted (to prevent DoS attacks etc.)
    'maxSubmissions' => 1000,
    // The directory to which the submissions will be uploaded.
    // Make sure that the web server serving the application has
    // write access to that directory. Also, make sure
    // that the upload directory is _not_ contained in the wwwroot
    // directory! Otherwise, submissions will be available for
    // anyone to download.
    // Paths can be specified absolutely or relative to the wwwroot.
    // The path must end in /
    'uploadDirectory' => '../submissions/',
    // List of tutorials.
    'tutorials' => [
        [
            // The id must be unique.
            'id' => 11,
            // Only the description is made public.
            'description' => '11 - Mittwoch 08:30 - Florian Behrens',
            // Name and mail address are used only to notify the tutors.
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
    // The exercises for which submissions are possible.
    'exercises' => [
        [
            // Id must be unique.
            'id' => 4,
            // The name of the exercise as displayed to the user.
            'name' => 'Blatt 3 - Aufgabe 4',
            // Specify what files can be uploaded.
            'files' => [
                [
                    // Name of the file
                    'name' => 'Main.java',
                    // Determines whether the user can change this file.
                    // For example, you might have code that supplies helper
                    // functionality, which you don't want every student to upload.
                    // Then you can mark that file as given, and users will see
                    // that that file is supplied to them.
                    'isGiven' => false,
                    // When displaying the file, what mode should the CodeMirror
                    // editor use for displaying that code? (basically, that's
                    // the language of the file)
                    'mode' => 'text/x-java',
                    // The contents of the file (the user can of course adapt this).
                    'code' => '// Hier Code für Main.java einfügen.',
                ],
                [
                    'name' => 'SimpleIO.java',
                    // When set to true, users will still see the file, but will
                    // be unable to edit it.
                    'isGiven' => true,
                    'mode' => 'text/x-java',
                    'code' => '// The contents of SimpleIO.java',
                ],
            ]
        ]
    ],
];