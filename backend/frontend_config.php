<?php

function getFrontendConfigAsJSonString() {
    $config = require('../config.php');
    $tutorials = [];
    foreach($config['tutorials'] as $tutorial) {
        $tutorials[] = [
            'id' => $tutorial['id'],
            'description' => $tutorial['description'],
        ];
    }

    $exercises = [];
    // for safety reasons, we don't just use the $config['exercises'] array
    // but copy only the values we want
    foreach($config['exercises'] as $exercise) {
        $e = [
            'id' => $exercise['id'],
            'name' => $exercise['name'],
        ];
        $files = [];
        foreach($exercise['files'] as $file) {
            $f = [
                'name' => $file['name'],
                'isGiven' => $file['isGiven'],
                'mode' => $file['mode'],
                'comment' => isset($file['comment']) ? $file['comment'] : null,
                'code' => isset($file['code']) ? $file['code'] : null,
            ];
            $files[] = $f;
        }
        $e['files'] = $files;
        $exercises[] = $e;
    }

    return json_encode([
        'ui' => $config['ui'],
        'tutorials' => $tutorials,
        'exercises' => $exercises,
    ]);
}