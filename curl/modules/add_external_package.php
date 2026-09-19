<?php
header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit;
}

require_once '../../config.php';
require_once '../core.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['qlane_target_user']) || empty($data['qlane_target_user'])) {
    echo json_encode(["status" => "error", "message" => "Required field: username"]);
    exit;
}

$username = $data['qlane_target_user'];
$whm_host = $server_ip;
$whm_user = $username_root;
$whm_pass = $password_root;

$res = executeWhmApi($whm_host, $whm_user, $whm_pass, "/json-api/accountsummary?api.version=1&user=$username");
checkAuthFailure($res);

if ($res['status'] === 'error') {
    echo json_encode(["status" => "error", "message" => $res['message']]);
    exit;
}

$package_data = $res['data'];

if (isset($package_data['metadata']['result']) && $package_data['metadata']['result'] == 0) {
    $reason = isset($package_data['metadata']['reason']) ? $package_data['metadata']['reason'] : 'Unknown Error';
    
    $debug_info = [
        "request" => [
            "endpoint" => "https://" . $whm_host . ":2087/json-api/accountsummary?api.version=1&user=" . $username,
            "method" => "GET",
            "auth" => [
                "user" => $whm_user,
                "password" => $whm_pass
            ]
        ],
        "response" => $res
    ];

    if (stripos($reason, 'Account does not exist') !== false) {
        echo json_encode([
            "status" => "error", 
            "message" => "Username '$username' tidak ditemukan.",
            "_debug" => $debug_info 
        ]);
        exit;
    }
    
    echo json_encode([
        "status" => "error", 
        "message" => "WHM Error: $reason",
        "_debug" => $debug_info
    ]);
    exit;
}

if (isset($package_data['data']['acct'][0]['plan'])) {
    $current_package = $package_data['data']['acct'][0]['plan'];
} else {
    echo json_encode(["status" => "error", "message" => "Failed to read account data."]);
    exit;
}

$reseller_configs = [
    "Whm Mini"       => ["accs" => 15,    "l_res" => 1, "disk" => 2000,  "bw" => 4000,    "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super"]],
    "Whm Medium"     => ["accs" => 25,    "l_res" => 1, "disk" => 4000,  "bw" => 6000,    "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super"]],
    "Whm Extra"      => ["accs" => 35,    "l_res" => 1, "disk" => 6000,  "bw" => 8000,    "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super"]],
    "Whm Super"      => ["accs" => 50,    "l_res" => 1, "disk" => 8000,  "bw" => 12000,   "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super"]],
    "Mwhm Mini"      => ["accs" => 50,    "l_res" => 1, "disk" => 12000, "bw" => 14000,   "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super","Whm Mini","Whm Medium","Whm Extra","Whm Super"]],
    "Mwhm Medium"    => ["accs" => 60,    "l_res" => 1, "disk" => 14000, "bw" => 16000,   "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super","Whm Mini","Whm Medium","Whm Extra","Whm Super"]],
    "Mwhm Extra"     => ["accs" => 65,    "l_res" => 1, "disk" => 16000, "bw" => 18000,   "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super","Whm Mini","Whm Medium","Whm Extra","Whm Super"]],
    "Mwhm Super"     => ["accs" => 80,    "l_res" => 1, "disk" => 18000, "bw" => 20000,   "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super","Whm Mini","Whm Medium","Whm Extra","Whm Super"]],
    "Admin Hosting"  => ["accs" => 666,   "l_res" => 0, "disk" => 0,     "bw" => 0,       "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super","Whm Mini","Whm Medium","Whm Extra","Whm Super","Mwhm Mini","Mwhm Medium","Mwhm Extra","Mwhm Super"]],
    "Ceo Hosting"    => ["accs" => 999,   "l_res" => 0, "disk" => 10240, "bw" => 1048576, "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super","Whm Mini","Whm Medium","Whm Extra","Whm Super","Mwhm Mini","Mwhm Medium","Mwhm Extra","Mwhm Super","Admin Hosting"]],
    "Wakil Founder"  => ["accs" => 99999, "l_res" => 0, "disk" => 10240, "bw" => 1048576, "pkgs" => ["cPanel Extra","cPanel Medium","cPanel Mini","cPanel Super","Whm Mini","Whm Medium","Whm Extra","Whm Super","Mwhm Mini","Mwhm Medium","Mwhm Extra","Mwhm Super","Admin Hosting","Ceo Hosting"]]
];

if (array_key_exists($current_package, $reseller_configs)) {
    $cfg = $reseller_configs[$current_package];
    $post_res = http_build_query(["res" => $username, "cowner" => 0]);
    $res1 = executeWhmApi($whm_host, $whm_user, $whm_pass, "/scripts/addres", $post_res, true);
    checkAuthFailure($res1);
    
    if ($res1['status'] === 'error' || $res1['http_code'] != 200) {
        echo json_encode(["status" => "error", "message" => "Failed to convert account to reseller!"]);
        exit;
    }
    
    $edit_data = [
        "limits_number_of_accounts" => 1,
        "resnumlimitamt" => $cfg['accs'],
        "limits_resources" => $cfg['l_res'],
        "rslimit-disk" => $cfg['disk'],
        "rsolimit-disk" => 1,
        "rslimit-bw" => $cfg['bw'],
        "rsolimit-bw" => 1,
        "limits_preassigned_packages" => 1,
        "res" => $username,
        "acl-acct-summary" => 1,
        "acl-basic-system-info" => 1,
        "acl-basic-whm-functions" => 1,
        "acl-list-accts" => 1,
        "acl-create-acct" => 1,
        "acl-kill-acct" => 1,
        "acl-suspend-acct" => 1,
        "acl-upgrade-account" => 1,
        "acl-list-pkgs" => 1,
        "acl-add-pkg" => 1,
        "acl-edit-pkg" => 1,
        "acl-manage-dns-records" => 1,
        "acl-ssl" => 1,
        "acl-edit-mx" => 1,
        "acl-passwd" => 1,
        "acl-file-restore" => 1,
        "acl-manage-api-tokens" => 1,
        "acl-track-email" => 1,
        "acl-create-dns" => 1,
        "acl-kill-dns" => 1,
        "acl-park-dns" => 1,
        "acl-edit-dns" => 1,
        "acl-status" => 1,
        "acl-stats" => 1,
        "acl-edit-account" => 1,
        "save_as" => "on"
    ];
    
    foreach ($cfg['pkgs'] as $p) {
        $edit_data["respkg-" . $p] = 1;
    }
    
    $post_edit = http_build_query($edit_data);
    $res2 = executeWhmApi($whm_host, $whm_user, $whm_pass, "/scripts2/editressv", $post_edit, true);
    checkAuthFailure($res2);
    
    if ($res2['status'] === 'error' || $res2['http_code'] != 200) {
        echo json_encode(["status" => "error", "message" => "Failed to update reseller account!"]);
        exit;
    }
    
    echo json_encode([
        "status" => "success",
        "message" => "Sukses Addpackage!",
        "username" => $username,
        "current_package" => $current_package
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "cPanel account package could not be changed (package not registered).",
        "username" => $username,
        "current_package" => $current_package
    ]);
}
?>