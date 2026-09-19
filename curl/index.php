<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'status' => 'success',
        'site_name' => $title
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed."]);
    exit;
}

$qlane_raw_input = file_get_contents('php://input');
$qlane_incoming_data = json_decode($qlane_raw_input, true);
$qlane_action = isset($qlane_incoming_data['qlane_action']) ? $qlane_incoming_data['qlane_action'] : 'qlane_search_user';

$qlane_sensitive_actions = [
    'qlane_add_package_root',
    'qlane_run_autossl',
    'qlane_setup_smtp',
    'qlane_delete_all_package',
    'qlane_delete_default_package'
];

if (in_array($qlane_action, $qlane_sensitive_actions)) {
    $qlane_password_input = isset($qlane_incoming_data['qlane_access_key']) ? trim($qlane_incoming_data['qlane_access_key']) : '';
    if ($qlane_password_input !== $password_root) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Incorrect root password! Access denied.'
        ]);
        exit;
    }
}

$module_map = [
    'qlane_search_user' => 'add_external_package.php',
    'qlane_add_package_root' => 'create_hosting_packages.php',
    'qlane_run_autossl' => 'enable_autossl.php',
    'qlane_delete_default_package' => 'delete_default_accounts.php',
    'qlane_delete_all_package' => 'remove_packages.php',
    'qlane_setup_smtp' => 'setup_smtp.php'
];

if (!isset($module_map[$qlane_action])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    exit;
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$module_url = $protocol . '://' . $host . $path . '/modules/' . $module_map[$qlane_action];

$qlane_curl_session = curl_init($module_url);
curl_setopt($qlane_curl_session, CURLOPT_POST, 1);
curl_setopt($qlane_curl_session, CURLOPT_POSTFIELDS, $qlane_raw_input);
curl_setopt($qlane_curl_session, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($qlane_curl_session, CURLOPT_RETURNTRANSFER, true);
curl_setopt($qlane_curl_session, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($qlane_curl_session, CURLOPT_SSL_VERIFYHOST, false);

$qlane_response = curl_exec($qlane_curl_session);

function qlane_strip_debug_keys($qlane_data) {
    if (is_array($qlane_data)) {
        foreach ($qlane_data as $qlane_key => $qlane_value) {
            if ($qlane_key === '_debug') {
                unset($qlane_data[$qlane_key]);
                continue;
            }
            if (is_array($qlane_value)) {
                $qlane_data[$qlane_key] = qlane_strip_debug_keys($qlane_value);
            }
        }
    }
    return $qlane_data;
}

if ($qlane_response === false) {
    echo json_encode(['status' => 'error', 'message' => 'Internal Forwarding Error: ' . curl_error($qlane_curl_session)]);
} else {
    $qlane_decoded_response = json_decode($qlane_response, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($qlane_decoded_response)) {
        $qlane_filtered_response = qlane_strip_debug_keys($qlane_decoded_response);
        echo json_encode($qlane_filtered_response);
    } else {
        echo $qlane_response;
    }
}
curl_close($qlane_curl_session);
?>
