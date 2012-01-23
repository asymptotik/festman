<?php

function endsWithBreak($str)
{
    if(preg_match("/<[bB][rR]\s*\/?>\s*$/", $str))
    {
    	return true;
    }
    else
    {
    	return false;
    }
}

function appendLine($str1, $str2)
{
    if(strlen($str1) > 0 && strlen($str2) > 0 && endsWithBreak($str1) == false)
    {
    	$str1 .= "<br/>";
    }
    
    return $str1 . $str2;
}

function getQuotedStringOrNull($str)
{
	if(strlen($str) == 0)
	{
		return 'NULL';
	}
	else
	{
		return "'" . $str . "'";
	}
}

function getPost($value)
{
	if(get_magic_quotes_gpc())
		return stripslashes($value);
	else
		return $value;
}

function queryValue($value)
{
	global $fm_conn;
	return mysql_real_escape_string($value, $fm_conn);
}
?>