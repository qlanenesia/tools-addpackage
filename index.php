<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'><path d='M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 4.854l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708z'/></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #0d6efd 0%, #4a90e2 100%);
            height: 100vh; 
            height: 100dvh; 
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin: 0;
        }
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #ffffff 0%, #e6f2ff 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.4s ease-out, visibility 0s linear 0s; 
        }
        body.loaded #preloader {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease-out, visibility 0s linear 0.4s;
        }
        .loading-dots {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .dot {
            width: 18px; 
            height: 18px;
            margin: 0 6px;
            background-color: #0d6efd;
            border-radius: 50%;
            opacity: 0.6;
            animation: pulse 1.4s infinite ease-in-out both;
        }
        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }
        .dot:nth-child(3) { animation-delay: 0s; }
        @keyframes pulse {
            0%, 80%, 100% { transform: scale(0); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }
        .navbar {
            background-color: #0d6efd !important;
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 100;
            flex-shrink: 0;
        }
        .navbar-brand { font-weight: 700; color: white !important; }
        .navbar-brand i { margin-right: 5px; }
        .btn-custom-menu {
            border-radius: 30px;
            padding: 6px 20px;
            border: 2px solid white;
            background-color: transparent;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .btn-custom-menu:focus { box-shadow: none; }
        .btn-custom-menu:hover, .btn-custom-menu.show {
            background-color: white;
            color: #0d6efd;
            border-color: white;
        }
        .dropdown-menu {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-top: 10px !important;
            overflow: hidden;
            min-width: 220px;
        }
        .dropdown-item {
            padding: 10px 20px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #555;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s;
            cursor: pointer; 
        }
        .dropdown-item:last-child { border-bottom: none; }
        .dropdown-item:hover { background-color: #f8f9fa; color: #0d6efd; }
        .dropdown-item i { margin-right: 8px; color: #0d6efd; width: 20px; text-align: center; display: inline-block; }
        .dropdown-item.text-danger { color: #dc3545 !important; }
        .dropdown-item.text-danger i { color: #dc3545 !important; }
        .dropdown-item.text-danger:hover { background-color: #fff5f5; color: #dc3545 !important; }
        .dropdown-item.text-danger:hover i { color: #dc3545 !important; }
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            text-align: center;
            color: white;
            width: 100%;
            padding: 0; 
        }
        .custom-shape-divider-bottom {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
            z-index: 1;
        }
        .custom-shape-divider-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 140px; 
        }
        .custom-shape-divider-bottom .shape-fill { fill: #FFFFFF; }
        .hero-content-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: 0 20px;
        }
        .tool-title {
            font-size: 3.5rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2rem;
            text-shadow: 0 4px 10px rgba(0,0,0,0.2);
            position: relative;
            display: inline-block;
        }
        .tool-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 8px;
            background-color: #ffc107;
            margin: 10px auto 0;
            border-radius: 10px;
        }
        .search-container {
            background: #0a58ca;
            padding: 8px;
            border-radius: 50px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); 
            display: flex;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .form-control-custom {
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 1rem;
            background-color: transparent;
            color: white;
            width: 100%;
        }
        .form-control-custom::placeholder { color: rgba(255, 255, 255, 0.6); }
        .form-control-custom:focus { box-shadow: none; background-color: transparent; color: white; }
        .btn-search {
            border-radius: 40px;
            padding: 10px 35px;
            background-color: white;
            border: none;
            color: #0d6efd;
            font-weight: 700;
            white-space: nowrap; 
            cursor: pointer;
        }
        .btn-search:active {
            transform: scale(0.95);
        }
        .modal-content {
            border-radius: 25px;
            border: none;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
            animation: zoomInLight 0.3s ease-out forwards;
        }
        @keyframes zoomInLight {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }
        .modal-title {
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }
        .btn-close-white {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }
        .btn-close-white:hover { opacity: 1; }
        .modal-body {
            padding: 2rem;
            background-color: white;
        }
        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #8898aa;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .input-group-text-custom {
            background-color: #eff6ff; 
            border: 1px solid #dee2e6;
            border-right: none;
            color: #0d6efd; 
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            padding-left: 15px;
            padding-right: 15px;
        }
        .form-control-readonly {
            background-color: #eff6ff !important;
            border: 1px solid #dee2e6;
            border-left: none;
            color: #495057;
            font-weight: 600;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            opacity: 1;
        }
        .input-group-text-pass {
            background-color: white;
            border: 1px solid #dee2e6;
            border-right: none;
            color: #0d6efd;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }
        .form-control-pass {
            border: 1px solid #dee2e6;
            border-left: none;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: 12px;
            font-size: 1rem;
        }
        .form-control-pass:focus { box-shadow: none; border-color: #0d6efd; }
        .form-control-pass:focus + .input-group-text-pass,
        .input-group-text-pass:has(+ .form-control-pass:focus) { border-color: #0d6efd; }
        .modal-footer {
            border-top: none;
            padding: 0 2rem 2rem 2rem;
            justify-content: center;
        }
        .btn-modal-action {
            background: linear-gradient(180deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 0;
            font-weight: 700;
            width: 100%;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-modal-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
            background: linear-gradient(180deg, #0b5ed7 0%, #0a58ca 100%);
        }
        .success-icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .success-icon {
            font-size: 5rem;
            animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }
        @keyframes bounceIn {
            0% { transform: scale(0); opacity: 0; }
            60% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }
        .success-title {
            color: #333;
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 5px;
        }
        .success-details {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            margin: 15px 0 25px 0;
            border: 1px solid #e9ecef;
        }
        .result-list-wrapper {
            max-height: 200px;
            overflow-y: auto;
            text-align: left;
            padding: 5px 10px;
            background-color: #fff;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .result-list-wrapper::-webkit-scrollbar {
            display: none;
        }
        .package-item {
            padding: 10px 5px;
            border-bottom: 1px dashed #eee;
            line-height: 1.3;
        }
        .package-item:last-child {
            border-bottom: none;
        }
        footer {
            background-color: #ffffff;
            padding: 15px 0;
            text-align: center;
            color: #666;
            font-size: 0.85rem;
            position: relative;
            z-index: 5;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .navbar { padding: 0.8rem 0; }
            .navbar-brand { font-size: 1.1rem; }
            .btn-custom-menu { padding: 5px 15px; font-size: 0.8rem; border-width: 1.5px; }
            .tool-title { font-size: 1.8rem; margin-bottom: 1.5rem; letter-spacing: 1px; }
            .tool-title::after { width: 50px; height: 4px; margin-top: 5px; }
            .hero-content-wrapper { transform: translateY(-20px); }
            .search-container { flex-direction: column; background: transparent; box-shadow: none; border: none; padding: 0; gap: 15px; }
            .form-control-custom { background-color: #0a58ca; box-shadow: 0 5px 10px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.1); text-align: center; padding: 10px 20px; }
            .btn-search { width: 100%; padding: 10px; box-shadow: 0 5px 10px rgba(0,0,0,0.1); }
            .custom-shape-divider-bottom svg { height: 70px; }
        }
    </style>
</head>
<body>

    <div id="preloader">
        <div class="loading-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-cloud-check-fill"></i> Loading...
            </a>
            <div class="dropdown ms-auto">
                <button class="btn btn-custom-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-list me-2"></i> Menu
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item trigger-modal" href="#" data-menu-name="Addpackage Root"><i class="bi bi-box-seam"></i> Addpackage Root</a></li>
                    <li><a class="dropdown-item trigger-modal" href="#" data-menu-name="Setup SMTP"><i class="bi bi-envelope-paper"></i> Setup SMTP</a></li>
                    <li><a class="dropdown-item trigger-modal" href="#" data-menu-name="Run AutoSSL"><i class="bi bi-shield-lock"></i> Run AutoSSL</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger trigger-modal" href="#" data-menu-name="Delete Package Default"><i class="bi bi-archive text-danger"></i> Delete Package Default</a></li>
                    <li><a class="dropdown-item text-danger trigger-modal" href="#" data-menu-name="Delete All Package"><i class="bi bi-trash-fill text-danger"></i> Delete All Package</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container hero-content-wrapper animate-up">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-12">
                    <h1 class="tool-title">TOOLS ADDPACKAGE</h1>
                    <form action="#" method="POST" id="searchForm">
                        <div class="search-container">
                            <input type="text" id="usernameInput" class="form-control form-control-custom" placeholder="Enter Username..." aria-label="Username" required>
                            <button class="btn btn-search" type="submit">
                                <i class="bi bi-search me-1"></i> Search Username
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="custom-shape-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </header>

    <footer>
        <div class="container"><div class="row"><div class="col-12"><div class="mt-1"></div></div></div></div>
    </footer>

    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Konfirmasi Akses</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="actionForm">
                        <div class="mb-3">
                            <label class="form-label-custom">Username</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control form-control-readonly" id="inputUser" value="root" readonly>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-custom text-primary">Password Root</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-pass"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control form-control-pass" id="inputPass" placeholder="Enter Root Password..." required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label-custom">IP Address</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom"><i class="bi bi-hdd-network-fill"></i></span>
                                <input type="text" class="form-control form-control-readonly" id="inputIP" value="***.***.***.***" readonly>
                            </div>
                        </div>

                        <div id="smtpContainer" style="display: none; border-top: 2px dashed #eee; margin-top: 20px; padding-top: 15px;">
                            <h6 class="text-primary fw-bold mb-3"><i class="bi bi-envelope-paper"></i> Konfigurasi SMTP</h6>
                            <div class="mb-3">
                                <label class="form-label-custom">SMTP Host</label>
                                <input type="text" class="form-control form-control-pass" id="smtpHost" placeholder="mail.domain.com">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label-custom">SMTP Port</label>
                                    <input type="number" class="form-control form-control-pass" id="smtpPort" value="587">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label-custom">SMTP User</label>
                                    <input type="text" class="form-control form-control-pass" id="smtpUser" placeholder="user@domain.com">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-custom">SMTP Password</label>
                                <input type="text" class="form-control form-control-pass" id="smtpPass" placeholder="Password Email...">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-modal-action">JALANKAN</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-3">
                <div class="modal-body">
                    <div class="success-icon-container">
                        <i id="resultIcon" class="bi bi-check-circle-fill success-icon"></i>
                    </div>
                    <h2 id="resultTitle" class="success-title">Success!</h2>
                    <div class="success-details">
                        <div id="resultContent"></div>
                    </div>
                    <button type="button" class="btn btn-primary btn-modal-action w-75 mx-auto" data-bs-dismiss="modal">
                        DONE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let qlane_active_menu = ''; 

        function toggleLoading(show) {
            if (show) {
                document.body.classList.remove('loaded');
            } else {
                setTimeout(() => {
                    document.body.classList.add('loaded');
                }, 100);
            }
        }

        window.addEventListener('load', function() {
            fetch('curl/index.php', { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.site_name) {
                        document.title = "" + data.site_name;
                        const brandEl = document.querySelector('.navbar-brand');
                        brandEl.innerHTML = `<i class="bi bi-cloud-check-fill"></i> ${data.site_name}`;
                    }
                })
                .catch(err => {
                    document.querySelector('.navbar-brand').innerHTML = `<i class="bi bi-cloud-check-fill"></i> CloudSky`;
                })
                .finally(() => {
                    toggleLoading(false);
                });
        });

        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const qlane_target_user = document.getElementById('usernameInput').value;
                qlane_do_api_request({ qlane_target_user: qlane_target_user, qlane_action: 'qlane_search_user' });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('authModal'));
            var modalTitle = document.getElementById('authModalLabel');
            var menuItems = document.querySelectorAll('.trigger-modal');
            var runBtn = document.querySelector('.btn-modal-action'); 
            var smtpContainer = document.getElementById('smtpContainer');

            menuItems.forEach(function (item) {
                item.addEventListener('click', function (e) {
                    e.preventDefault(); 
                    var menuName = this.getAttribute('data-menu-name');
                    
                    qlane_active_menu = menuName; 
                    modalTitle.innerHTML = menuName;
                    document.getElementById('inputPass').value = ''; 

                    if (qlane_active_menu === 'Setup SMTP') {
                        smtpContainer.style.display = 'block';
                    } else {
                        smtpContainer.style.display = 'none';
                    }
                    
                    myModal.show();
                    
                    setTimeout(function() {
                        document.getElementById('inputPass').focus();
                    }, 500);
                });
            });

            runBtn.addEventListener('click', function() {
                const qlane_access_key = document.getElementById('inputPass').value;

                if (!qlane_access_key || qlane_access_key.trim() === '') {
                    alert("Please enter the Root Password first!");
                    document.getElementById('inputPass').focus();
                    return;
                }

                let qlane_payload_data = {
                    qlane_access_key: qlane_access_key
                };

                if (qlane_active_menu === 'Addpackage Root') {
                    qlane_payload_data.qlane_action = 'qlane_add_package_root';
                } else if (qlane_active_menu === 'Run AutoSSL') {
                    qlane_payload_data.qlane_action = 'qlane_run_autossl';
                } else if (qlane_active_menu === 'Delete Package Default') {
                    qlane_payload_data.qlane_action = 'qlane_delete_default_package';
                } else if (qlane_active_menu === 'Delete All Package') {
                    qlane_payload_data.qlane_action = 'qlane_delete_all_package';
                } else if (qlane_active_menu === 'Setup SMTP') {
                    qlane_payload_data.qlane_action = 'qlane_setup_smtp';
                    const sHost = document.getElementById('smtpHost').value;
                    const sPort = document.getElementById('smtpPort').value;
                    const sUser = document.getElementById('smtpUser').value;
                    const sPass = document.getElementById('smtpPass').value;

                    if(!sHost || !sUser || !sPass) {
                        alert("Please complete the SMTP details (Host, User, and Password)!");
                        return;
                    }

                    qlane_payload_data.qlane_smtp_host = sHost;
                    qlane_payload_data.qlane_smtp_port = sPort;
                    qlane_payload_data.qlane_smtp_user = sUser;
                    qlane_payload_data.qlane_smtp_pass = sPass;
                }

                myModal.hide();
                qlane_do_api_request(qlane_payload_data);
            });
        });

        function qlane_do_api_request(qlane_payload_data) {
            toggleLoading(true);

            const safetyTimeout = setTimeout(() => {
                if (!document.body.classList.contains('loaded')) {
                    toggleLoading(false);
                    alert("Timeout! The server did not respond within 1 minute.");
                }
            }, 60000);

            fetch('curl/index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(qlane_payload_data)
            })
            .then(response => response.json())
            .then(data => {
                clearTimeout(safetyTimeout);

                const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                const iconEl = document.getElementById('resultIcon');
                const titleEl = document.getElementById('resultTitle');
                const contentEl = document.getElementById('resultContent');

                iconEl.className = 'bi success-icon';

                if (data.status === 'success') {
                    iconEl.classList.add('bi-check-circle-fill', 'text-success');
                    titleEl.innerText = "Success!";
                    titleEl.className = "success-title text-dark";

                    if (qlane_payload_data.qlane_action === 'qlane_add_package_root' || qlane_payload_data.qlane_action === 'qlane_delete_all_package' || qlane_payload_data.qlane_action === 'qlane_delete_default_package') {
                        
                        if (qlane_payload_data.qlane_action === 'qlane_delete_all_package') titleEl.innerText = "All Packages Deleted Successfully";
                        else if (qlane_payload_data.qlane_action === 'qlane_delete_default_package') titleEl.innerText = "Default Package Deleted Successfully";
                        else titleEl.innerText = "Addpackage Root Successful";
                        
                        titleEl.className = "success-title text-success";

                        let detailsHtml = '<div class="result-list-wrapper">';
                        if (data.details && Array.isArray(data.details)) {
                            data.details.forEach(item => {
                                const pkgName = item.package || item.user || "Package";
                                let reason = "Success";
                                let isExist = false;
                                let isError = false;

                                let rawStr = JSON.stringify(item).toLowerCase();
                                
                                if (rawStr.includes("already exists") || rawStr.includes("exists")) {
                                    reason = "Package already exists";
                                    isExist = true;
                                } else if (item.response && item.response.status === 'error') {
                                    reason = item.response.message || "Processing failed";
                                    isError = true;
                                } else if (rawStr.includes("failed") || rawStr.includes("error")) {
                                    reason = "Processing failed";
                                    isError = true;
                                } else if (item.message) {
                                    reason = item.message;
                                }

                                const colorClass = isExist ? 'text-warning' : (isError ? 'text-danger' : 'text-success');
                                const iconClass = isExist ? 'bi-exclamation-circle-fill' : (isError ? 'bi-x-circle-fill' : 'bi-check-circle-fill');

                                detailsHtml += `
                                    <div class="package-item small">
                                        <div class="d-flex align-items-start">
                                            <i class="bi ${iconClass} ${colorClass} me-2 mt-1"></i>
                                            <div>
                                                <strong class="text-dark d-block mb-1">${pkgName}</strong>
                                                <span class="${colorClass}">${reason}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            detailsHtml += `<p class="text-success fw-bold">${data.message || 'Process completed successfully.'}</p>`;
                        }
                        detailsHtml += '</div>';
                        contentEl.innerHTML = detailsHtml;

                    } else if (qlane_payload_data.qlane_action === 'qlane_search_user') {
                        contentEl.innerHTML = `
                            <p class="mb-1 text-muted small">Username : ${data.username || qlane_payload_data.qlane_target_user}</p>
                            <p class="mb-1 text-muted small">Package : ${data.current_package || '-'}</p>
                            <hr class="my-2">
                            <p class="text-success fw-bold mb-0 small"><i class="bi bi-check2-all me-1"></i> ${data.message || 'Package added successfully'}</p>
                        `;
                    } else {
                         contentEl.innerHTML = `<p class="text-success fw-bold">${data.message || 'Process completed successfully.'}</p>`;
                    }
                } else {
                    iconEl.classList.add('bi-x-circle-fill', 'text-danger'); 
                    titleEl.innerText = "Failed!";
                    titleEl.className = "success-title text-danger";
                    contentEl.innerHTML = `<p class="text-danger fw-bold">${data.message || 'A system error occurred.'}</p>`;
                }
                
                resultModal.show();
            })
            .catch(error => {
                clearTimeout(safetyTimeout);
                alert('A network or server error occurred.');
            })
            .finally(() => {
                toggleLoading(false);
            });
        }
    </script>
</body>
</html>