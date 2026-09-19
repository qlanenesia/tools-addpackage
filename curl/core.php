<?php
function executeWhmApi($host, $user, $pass, $endpoint, $post_data = null, $is_legacy = false) {
    $url = "https://$host:2087" . $endpoint;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");

    if ($is_legacy) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: text/html"]);
    }

    if ($post_data !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        if (is_array($post_data) || is_string($post_data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['status' => 'error', 'message' => "CURL Error: $curl_error"];
    }

    if ($http_code == 401 || $http_code == 403) {
        return ['status' => 'auth_failed'];
    }

    if (stripos($response, 'Access denied') !== false) {
        return ['status' => 'auth_failed'];
    }

    if (!$is_legacy) {
        $json = json_decode($response, true);
        if (isset($json['cpanelresult']['error']) && stripos($json['cpanelresult']['error'], 'Access denied') !== false) {
            return ['status' => 'auth_failed'];
        }
        return ['status' => 'success', 'data' => $json, 'http_code' => $http_code];
    }

    return ['status' => 'success', 'raw_response' => $response, 'http_code' => $http_code];
}

function checkAuthFailure($res) {
    if (isset($res['status']) && $res['status'] === 'auth_failed') {
        echo json_encode(["status" => "error", "message" => "Incorrect root password or access denied."]);
        exit;
    }
}
?>