<?php

function fmGetVar($var) {

    $val = '';

    if (empty($_POST[$var])) {
        if (!empty($_GET[$var])) {
            $val = $_GET[$var];
        }
    } else {
        $val = $_POST[$var];
    }

    // negate magic quotes, if necessary
    // note that if php magic quotes are not enabled then wordpress magic quotes are, thus
    // we always strip the vals
    // magic quotes is evil since it assumes a data usage and a proper way and what to quote
    $val = stripslashes_deep($val);

    return $val;
}

function fmGetIndexedVar($var, $index) {

    $val = '';

    if (empty($_POST[$var][$index])) {
        if (!empty($_GET[$var][$index])) {
            $val = $_GET[$var][$index];
        }
    } else {
        $val = $_POST[$var][$index];
    }

    // negate magic quotes, if necessary
    // note that if php magic quotes are not enabled then wordpress magic quotes are, thus
    // we always strip the vals
    // magic quotes is evil since it assumes a data usage and a proper way and what to quote
    $val = stripslashes_deep($val);

    return $val;
}

function fmGetVars($vars) {
    $ret = array();

    for ($i = 0; $i < count($vars); $i += 1) {
        $var = $vars[$i];

        if (empty($_POST[$var])) {
            if (empty($_GET[$var])) {
                $val = '';
            } else {
                $val = $_GET[$var];
            }
        } else {
            $val = $_POST[$var];
        }

        // negate magic quotes, if necessary
        // note that if php magic quotes are not enabled then wordpress magic quotes are, thus
        // we always strip the vals
        // magic quotes is evil since it assumes a data usage and a proper way and what to quote
        $ret[$var] = stripslashes_deep($val);
    }

    return $ret;
}

function fmEndsWithBreak($str) {
    if (preg_match("/<[bB][rR]\s*\/?>\s*$/", $str)) {
        return true;
    } else {
        return false;
    }
}

function fmAppendLine($str1, $str2) {
    if (strlen($str1) > 0 && strlen($str2) > 0 && fmEndsWithBreak($str1) == false) {
        $str1 .= "<br/>";
    }

    return $str1 . $str2;
}

function fmGetQuotedStringOrNull($str) {
    if (strlen($str) == 0) {
        return 'NULL';
    } else {
        return "'" . $str . "'";
    }
}

function fmGetPost($value) {
    return stripslashes($value);
}

function fmQueryValue($value) {
    global $wpdb;
    return mysql_real_escape_string($value, $wpdb->dbh);
}

?>