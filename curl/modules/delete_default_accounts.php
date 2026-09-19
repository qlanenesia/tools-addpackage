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

$res = executeWhmApi($whm_host, $whm_user, $whm_pass, "/json-api/listaccts?api.version=1");
checkAuthFailure($res);

if ($res['status'] === 'error') {
    echo json_encode(["status" => "error", "message" => "Failed to connect to WHM: " . $res['message']]);
    exit;
}

$deleted_count = 0;
$deleted_users = [];
$skipped_count = 0;

if (isset($res['data']['data']['acct'])) {
    foreach ($res['data']['data']['acct'] as $acct) {
        if ($acct['plan'] === 'default') {
            $user_to_delete = $acct['user'];
            $res_del = executeWhmApi($whm_host, $whm_user, $whm_pass, "/json-api/removeacct?api.version=1&user=$user_to_delete");
            if ($res_del['status'] === 'success') {
                $deleted_count++;
                $deleted_users[] = $user_to_delete;
            }
        } else {
            $skipped_count++;
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve account list."]);
    exit;
}

echo json_encode([
    "status" => "success",
    "message" => "Proses selesai.",
    "summary" => ["deleted_count" => $deleted_count, "deleted_users" => $deleted_users, "skipped_count" => $skipped_count]
]);
?>