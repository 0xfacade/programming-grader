<?php

function getFrontendConfigAsJSonString() {
    $config = require_once('../config.php');
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
            ];
            if (!$file['isGiven']) {
                $f['mode'] = $file['mode'];
                $f['code'] = $file['code'];
            }
            $files[] = $f;
        }
        $e['files'] = $files;
        $exercises[] = $e;
    }

    return json_encode([
        'tutorials' => $tutorials,
        'exercises' => $exercises,
    ]);
}