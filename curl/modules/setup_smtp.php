<?php
header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit;
}

require_once '../../config.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['qlane_smtp_user'], $data['qlane_smtp_pass'], $data['qlane_smtp_host'], $data['qlane_smtp_port'])) {
    echo json_encode(["status" => "error", "message" => "Required fields: smtp_user, smtp_pass, smtp_host, smtp_port"]);
    exit;
}

$whm_host = $server_ip;
$whm_user = $username_root;
$whm_pass = $password_root;

$smtp_user = $data['qlane_smtp_user'];
$smtp_pass = $data['qlane_smtp_pass'];
$smtp_host = $data['qlane_smtp_host'];
$smtp_port = $data['qlane_smtp_port'];

$postData = http_build_query([
    "hasdata" => "1",
    "AUTH" => "excrelay_login:\n"."driver = plaintext\n"."public_name = LOGIN\n"."client_send = : $smtp_user : $smtp_pass",
    "ROUTERSTART" => "send_via_excrelay:\n"."driver = manualroute\n"."domains = ! +local_domains\n"."transport = excrelay_smtp\n"."route_list = * $smtp_host\n"."host_find_failed = defer\n"."no_more",
    "TRANSPORTSTART" => "excrelay_smtp:\n"."driver = smtp\n"."port = $smtp_port\n"."hosts = $smtp_host\n"."hosts_require_auth = $smtp_host"
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$whm_host:2087/scripts2/saveeximconf");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERPWD, "$whm_user:$whm_pass");

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo json_encode(["status" => "error", "message" => "Failed to connect to WHM server"]);
    exit;
}

if ($httpCode == 401 || $httpCode == 403 || stripos($response, 'Access denied') !== false) {
    echo json_encode(["status" => "error", "message" => "Incorrect root password or access denied."]);
    exit;
}

if ($httpCode !== 200) {
    echo json_encode(["status" => "error", "message" => "Failed to contact WHM API. HTTP Code: $httpCode"]);
    exit;
}

echo json_encode(["status" => "success", "message" => "SMTP setup completed successfully!"]);
?>