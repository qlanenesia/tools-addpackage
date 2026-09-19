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

$post_data1 = http_build_query([
    "command-0" => "reset_autossl_provider?provider=LetsEncrypt&x_terms_of_service_accepted=https%3A%2F%2Fletsencrypt.org%2Fdocuments%2FLE-SA-v1.5-February-24-2025.pdf",
    "command-1" => "get_autossl_check_schedule?",
    "command-2" => "get_autossl_providers?",
    "api.version" => 1
]);

$res1 = executeWhmApi($whm_host, $whm_user, $whm_pass, "/json-api/batch", $post_data1);
checkAuthFailure($res1);

if ($res1['status'] === 'error') {
    echo json_encode(["status" => "error", "message" => "Failed to connect: " . $res1['message']]);
    exit;
}

if ($res1['http_code'] != 200) {
    echo json_encode(["status" => "error", "message" => "Failed to enable Let's Encrypt!", "debug" => $res1['data']]);
    exit;
}

$post_data2 = http_build_query(["api.version" => 1]);
$res2 = executeWhmApi($whm_host, $whm_user, $whm_pass, "/json-api/start_autossl_check_for_all_users", $post_data2);
checkAuthFailure($res2);

if ($res2['http_code'] != 200) {
    echo json_encode(["status" => "error", "message" => "Failed to run AutoSSL!", "debug" => $res2['data']]);
    exit;
}

echo json_encode(["status" => "success", "message" => "AutoSSL (Let's Encrypt) has been successfully enabled & run!"]);
?>