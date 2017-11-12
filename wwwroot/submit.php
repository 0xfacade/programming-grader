<?php

require_once('../backend/vendor/autoload.php');
require_once('../backend/mail.php');
$config = require('../config.php');

use JsonSchema\Validator;

header('Content-Type: application/json');
function respond($message, $status_code = 400) {
    http_response_code($status_code);
    die(json_encode(['message' => $message]));
}

function countSubmissions($uploadDir) {
    $pattern = $uploadDir . '*/*/*/';
    $results = glob($pattern, GLOB_ONLYDIR);
    return count($results);
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond('Only post requests are supported.');
}

if(countSubmissions($config['uploadDirectory']) > $config['maxSubmissions']) {
    respond('Too many submissions already.');
}

$data = json_decode(file_get_contents('php://input'));
//$data = json_decode(file_get_contents('../schema/sample.json'));
if($data == null) {
    respond('Could not parse that as JSON.');
}

$validator = new JsonSchema\Validator;
$validator->validate($data, (object)['$ref' => 'file://' . realpath('../schema/submission.json')]);

if(!$validator->isValid()) {
    $error = "JSON does not validate. Violations:\n";
    foreach ($validator->getErrors() as $error) {
        $error .= sprintf("[%s] %s\n", $error['property'], $error['message']);
    }
    respond($error);
}

$tutorial_okay = false;
foreach($config['tutorials'] as $tutorial) {
    if ($tutorial['id'] == $data->tutorial) {
        $tutorial_okay = true;
        break;
    }
}
if (!$tutorial_okay) {
    respond('Unknown tutorial.');
}

if(count($data->exercises) != count($config['exercises'])) {
    respond("There must be exactly one submission for every exercise.");
}
foreach($data->exercises as $submitted) {
    $id_okay = false;
    foreach($config['exercises'] as $expected) {
        if($expected['id'] == $submitted->id) {
            $id_okay = true;
            break;
        }
    }
    if (!$id_okay) {
        respond("Unknown exercise id: $submitted->id");
    }
}

$matriculations = $data->matriculations;
sort($matriculations);
$timestamp = date("Y-m-d_H:i:s");
$dir_path = $config['uploadDirectory']
    . 't' . $data->tutorial
    . '/' . implode('_', $matriculations) .
    '/' . $timestamp . '/';

$zip_path = $dir_path . 'submission_' . implode('_', $matriculations) . '.zip';
$zip = new ZipArchive();

if ($zip->open($zip_path, ZipArchive::CREATE)!==TRUE) {
    respond('Cannot create zip for tutor.');
}

foreach($data->exercises as $exercise) {
    $ex_dir = $dir_path . 'ex' . $exercise->id . '/';
    if (!is_dir($ex_dir)) {
        if (!mkdir($ex_dir, 0777, true)) {
            respond('Could not create directories.');
        }
    }
    foreach($exercise->files as $file) {
        $full_name = $ex_dir . $file->name;
        if(!file_put_contents($full_name, $file->code)) {
            respond('Could not write to file.');
        }
        $zip->addFile($full_name,'ex' . $exercise->id . '/' . $file->name);
    }
}

$zip->close();

if(notifyTutor($data, $zip_path) !== true) {
    respond('Could not notify tutor.');
}

respond('Thank you for your submission.', 201);


