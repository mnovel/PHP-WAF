<?php

class Security
{
    // Block request function
    function block()
    {
        header("HTTP/1.1 403 Forbidden");
        $html = "<title>Your Request Blocked</title>Your Request Blocked";
        echo $html;
        exit();
    }

    // User agent filter function
    static function filterUserAgent()
    {
        // Define allowed user agents
        $allowedAgents = [
            'google',
            'facebook',
            'opera',
            'mozilla',
            'safari',
            'whatsapp',
            'telegram',
            'twitter',
            'yahoo',
            'bing',
        ];

        // Check if the user agent is in the allowed list
        foreach ($allowedAgents as $agent) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $agent) !== false) {
                return; // Valid user agent found
            }
        }

        // Block if none of the allowed user agents matched
        self::logBlockedRequest('User Agent Blocked: ' . $_SERVER['HTTP_USER_AGENT']);
        self::block();
    }

    // Parameter filtering function
    static function parametersFilter()
    {
        // Define patterns for SQL injection
        $sqlPattern = "/(union[\s\S]*?select|drop[\s\S]*?(database|table|view)|select[\s\S]*?(into|from)|insert[\s\S]*?into[\s\S]*?values|update[\s\S]*?set|delete[\s\S]*?from|exec[\s\S]*?sp_)/i";

        // Filter GET and POST parameters
        $inputData = array_merge($_GET, $_POST);
        foreach ($inputData as $key => $value) {
            if (preg_match($sqlPattern, $value)) {
                self::logBlockedRequest('SQL Injection Attempt: ' . $value);
                self::block();
            }

            // XSS Filtering
            if (self::containsXSS($value)) {
                self::logBlockedRequest('XSS Attempt: ' . $value);
                self::block();
            }

            // XXE Filtering (for XML input)
            if (self::containsXXE($value)) {
                self::logBlockedRequest('XXE Attempt: ' . $value);
                self::block();
            }
        }
    }

    // Check for XSS attempts
    static function containsXSS($value)
    {
        return preg_match("/<script.*?>.*?<\/script>|<.*?on\w*=\s*['\"].*?['\"]/i", $value);
    }

    // Check for XXE attempts
    static function containsXXE($value)
    {
        return preg_match("/<!DOCTYPE\s+.*?[\s\n\r]*<!ENTITY\s+[^\s]+[\s\n\r]+/i", $value);
    }

    // Logging function for blocked requests
    static function logBlockedRequest($message)
    {
        $logFile = 'blocked_requests.log';
        $currentDateTime = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$currentDateTime] $message\n", FILE_APPEND);
    }
}

// Run the filters
Security::filterUserAgent();
Security::parametersFilter();
