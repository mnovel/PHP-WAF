<?php


class security
{

    function block()
    {

        header("HTTP/1.1 403 Forbidden");
        $html = "<title>Your Request Blocked</title>Your Request Blocked";
        return $html;
    }


    static function filter_user_agent()
    {

        $str = "google|facebook|opera|mozilla|safari|whatsapp|telegram|twitter|yahoo|bing";

        if (!preg_match("/$str/", strtolower($_SERVER['HTTP_USER_AGENT']))) {

            echo security::block();
            exit();
        }
    }

    static function parameters_filter()
    {

        $pattern = "/(union[\s\S]*?select|drop[\s\S]*?(database|table|view)|select[\s\S]*?(into|from)|insert[\s\S]*?into[\s\S]*?values|update[\s\S]*?set|delete[\s\S]*?from)/i";

        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if (preg_match($pattern, $_GET[$key])) {
                    echo security::block();
                    exit();
                }
            }
        } else if ($_POST) {
            foreach ($_POST as $key => $value) {
                if (preg_match($pattern, $_POST[$key])) {
                    echo security::block();
                    exit();
                }
            }
        }
    }
}

security::filter_user_agent();

if (!empty($_GET) || !empty($_POST)) {
    security::parameters_filter();
}
