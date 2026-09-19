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

$packages_to_delete = [
    "cPanel Mini", "cPanel Medium", "cPanel Extra", "cPanel Super",
    "Whm Mini", "Whm Medium", "Whm Extra", "Whm Super",
    "Mwhm Mini", "Mwhm Medium", "Mwhm Extra", "Mwhm Super",
    "Admin Hosting", "Ceo Hosting", "Wakil Founder"
];

$results = [];
$first_run = true;

foreach ($packages_to_delete as $pkg) {
    $endpoint = "/scripts/killpkg?pkg=" . urlencode($pkg) . "&submit-domain=Delete";
    $res = executeWhmApi($whm_host, $whm_user, $whm_pass, $endpoint, null, true);

    if ($first_run) checkAuthFailure($res);

    $results[] = [
        "package" => $pkg,
        "result" => $res['status'] === 'success' ? "Processed (HTTP " . $res['http_code'] . ")" : $res['message']
    ];

    $first_run = false;
}

echo json_encode(["status" => "success", "message" => "Proses penghapusan paket selesai.", "details" => $results]);
?>