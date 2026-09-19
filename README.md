# Tools Addpackage

A web-based (PHP) tool that helps WHM and cPanel server admins manage their servers quickly from a single dashboard, without needing to log into WHM directly for every action.

## Features

- Automatic Hosting Package Creation: instantly create new hosting packages in WHM
- Reseller Account Creation: automate the creation of reseller hosting accounts
- External SMTP Setup: configure an external SMTP relay for the server
- Run AutoSSL: trigger an AutoSSL check for all users at once
- Delete Default / Unused Packages: clean up default packages or accounts you don't need
- User Lookup: check a cPanel account summary by username

## Preview

<img width="1920" height="827" alt="Preview" src="https://github.com/user-attachments/assets/e44baa58-5368-4b7a-a05e-48a3ee2bfe17" />


## Requirements

- PHP 7.4+ with the `curl` and `json` extensions enabled
- A web server (Apache, Nginx, or LiteSpeed) - it can also run directly on the cPanel/WHM server itself
- Root WHM access (username and password) to the server you want to manage

## Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/qlanenesia/tools-addpackage.git
   cd tools-addpackage
   ```

2. Open `config.php` and fill in your WHM server details:
   ```php
   $username_root = "root";           // WHM root username
   $password_root = "your_password";  // WHM root password
   $server_ip     = "192.168.1.1";    // WHM server IP address
   $title         = "My WHM Server";  // display name
   ```

3. Upload this folder to your hosting/server, then open `index.php` in your browser.

Important: `config.php` will contain your real WHM root password once you edit it. Be careful not to commit your changes back to a public fork or repo with real credentials still inside. If you plan to push further changes, consider keeping your live credentials only on the server and reverting `config.php` to placeholder values before committing.

## Usage

1. Open the dashboard in your browser
2. Enter the Root Password for WHM when prompted for sensitive actions (add package, AutoSSL, SMTP, delete accounts, etc.)
3. Choose the action you want from the available menu
4. The tool communicates directly with your WHM server's API

## Security Notes

This tool has root access to your WHM server, so:
- Don't deploy it on a public server without additional protection (HTTP Auth, IP whitelisting, VPN, etc.)
- Always use HTTPS
- Never commit a `config.php` that contains real credentials
- Consider disabling or restricting access to this folder once you're done using it

## Contributing

Contributions are welcome. Fork this repo, create a new branch, and open a Pull Request. For larger changes, please open an Issue first to discuss what you'd like to change.

## License

This project is licensed under the [MIT License](LICENSE). Free to use, modify, and redistribute.

## Author

Created by qlanenesia
