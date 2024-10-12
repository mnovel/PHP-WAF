# PHPWAF

**PHPWAF** (PHP Web Application Firewall) is a lightweight, security-enhanced solution designed to protect PHP web applications from common web-based attacks, including SQL injection, Cross-Site Scripting (XSS), and XML External Entity (XXE) injection attempts. It filters incoming requests based on a set of customizable rules, ensuring your application is shielded from various threats.

## Features

- **User Agent Filtering**: Blocks requests from suspicious or malicious user agents.
- **SQL Injection Protection**: Detects and blocks SQL injection attempts in GET and POST parameters.
- **XSS Protection**: Identifies and mitigates potential Cross-Site Scripting (XSS) attacks.
- **XXE Protection**: Filters XML input for possible XXE injection attempts.
- **Request Logging**: Logs all blocked requests to a file for future review and auditing.

## Installation

### Prerequisites

- PHP >= 7.4
- Web server (Apache, Nginx, etc.)

### Steps

1. **Clone the repository**:

   ```bash
   git clone https://github.com/mnovel/PHPWAF.git
   cd PHPWAF
   ```

2. **Include PHPWAF in your project**:
   - Include the `Security.php` file in your project where you handle incoming requests.

3. **Integrate with your project**:
   - Call the `Security` class at the top of your PHP scripts to initiate request filtering:
   
   ```php
   <?php
   require 'Security.php';

   // Run security filters
   Security::filterUserAgent();
   Security::parametersFilter();
   ?>
   ```

## Usage

1. **User Agent Filtering**:
   - PHPWAF checks the `User-Agent` header to block requests from known bots or potentially harmful sources. If the user agent does not match the allowed list, the request will be blocked, and a log entry will be created.

2. **Parameter Filtering**:
   - GET and POST parameters are scanned for potential SQL injection attempts, XSS payloads, and XXE injections. If any suspicious patterns are detected, the request will be blocked.

3. **Logging**:
   - All blocked requests are logged into the `blocked_requests.log` file, including details about the blocked attempt and the reason for blocking.

## Example Log Entry

```plaintext
[2024-10-12 14:30:05] SQL Injection Attempt: union select * from users
[2024-10-12 14:32:12] XSS Attempt: <script>alert('XSS')</script>
```

Logs will help you monitor potential attack attempts and adjust your rules as necessary.

## Contribution

We welcome contributions! Feel free to open issues or submit pull requests to improve PHPWAF. Whether you want to add new filtering techniques or enhance the existing features, we would love your input.

## License

This project is licensed under the [MIT License](LICENSE).
