<?php
header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit;
}

require_once '../../config.php';
require_once '../core.php';

$whm_host = $server_ip;
$whm_user = $username_root;
$whm_pass = $password_root;

$hosting_packages = [
    "cPanel Mini"    => ["quota" => 100, "bwlimit" => 312, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "cPanel Medium"  => ["quota" => 200, "bwlimit" => 412, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "cPanel Extra"   => ["quota" => 300, "bwlimit" => 512, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "cPanel Super"   => ["quota" => 500, "bwlimit" => 712, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Whm Mini"       => ["quota" => 2000, "bwlimit" => 4000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Whm Medium"     => ["quota" => 4000, "bwlimit" => 6000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Whm Extra"      => ["quota" => 6000, "bwlimit" => 8000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Whm Super"      => ["quota" => 8000, "bwlimit" => 12000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Mwhm Mini"      => ["quota" => 12000, "bwlimit" => 14000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Mwhm Medium"    => ["quota" => 14000, "bwlimit" => 16000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Mwhm Extra"     => ["quota" => 16000, "bwlimit" => 18000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Mwhm Super"     => ["quota" => 18000, "bwlimit" => 20000, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Admin Hosting"  => ["quota" => 10240, "bwlimit" => 1048576, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Ceo Hosting"    => ["quota" => 10240, "bwlimit" => 1048576, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4],
    "Deputy Founder" => ["quota" => 10240, "bwlimit" => 1048576, "maxpark" => 0, "maxaddon" => 0, "maxpassenger" => 4]
];

$results = [];
$first_run = true;

foreach ($hosting_packages as $name => $settings) {
    $endpoint = "/json-api/addpkg?api.version=1&name=" . urlencode($name) . "&quota=" . $settings['quota'] . "&bwlimit=" . $settings['bwlimit'] . "&maxpark=" . $settings['maxpark'] . "&maxaddon=" . $settings['maxaddon'] . "&maxpassenger=" . $settings['maxpassenger'];
    $res = executeWhmApi($whm_host, $whm_user, $whm_pass, $endpoint);

    if ($first_run) checkAuthFailure($res);

    $results[] = ["package" => $name, "response" => $res];
    $first_run = false;
}

echo json_encode(["status" => "success", "message" => "Successfully added root hosting packages.", "details" => $results]);
?>