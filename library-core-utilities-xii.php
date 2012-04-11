<?php
/*
	Copyright © 2012, Akseli "Core Xii" Tarkkio <corexii@gmail.com>

	Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.

	THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
*/

if (!defined('jbgdhy92mASotQadhWPxw1jpAftYAb6NrwS0obtkzuuTcJrP9YYQ3V6uYv5gwczS'))
	{
	define('jbgdhy92mASotQadhWPxw1jpAftYAb6NrwS0obtkzuuTcJrP9YYQ3V6uYv5gwczS', null);
	
	if (get_magic_quotes_gpc())
		{
		$process = [&$_GET, &$_POST, &$_COOKIE, &$_REQUEST];
		while (list($key, $val) = each($process))
			{
			foreach ($val as $k => $v)
				{
				unset($process[$key][$k]);
				if (is_array($v))
					{
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
					}
				else
					{
					$process[$key][stripslashes($k)] = stripslashes($v);
					}
				}
			}
		unset($process);
		}
	
	mb_internal_encoding('utf-8');
	mb_regex_encoding('utf-8');
	mb_language('uni');
	mb_http_output('utf-8');
	}

function dump($what)
	{
	var_dump($what);
	die;
	}

function exit_early()
	{
	ignore_user_abort(false);
	if (connection_aborted())
		{
		exit;
		}
	}

function header_html_utf8()
	{
	header('Content-Type: text/html; charset=utf-8');
	}

function header_no_cache()
	{
	header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	}

function header_not_found()
	{
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
	}

function header_redirect_post($url)
	{
	header($_SERVER['SERVER_PROTOCOL'] . ' 303 See Other', true, 303);
	header('Location: ' . $url);
	}

function header_redirect($url)
	{
	header($_SERVER['SERVER_PROTOCOL'] . ' 307 Temporary Redirect', true, 307);
	header('Location: ' . $url);
	}

function header_redirect_permanent($url)
	{
	header($_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently', true, 301);
	header('Location: ' . $url);
	}

if (!function_exists('http_parse_headers'))
	{
	function http_parse_headers($headers)
		{
		$result = [];
		$fields = explode("\r\n", preg_replace('{\x0D\x0A[\x09\x20]+}', ' ', $headers));
		foreach ($fields as $field)
			{
			if (preg_match('{([^:]+): (.+)}m', $field, $matches))
				{
				$matches[1] = preg_replace('{(?<=^|[\x09\x20\x2D]).}e', 'strtoupper("\0")', strtolower(trim($matches[1])));
				if (isset($result[$matches[1]]))
					{
					$result[$matches[1]] = [$result[$matches[1]], $matches[2]];
					}
				else
					{
					$result[$matches[1]] = trim($matches[2]);
					}
				}
			}
		return $result;
		}
	}

function html_compress($html_string)
	{
	return preg_replace(['{>\s{2,}<}', '{^\s+}', '{\s+$}D'], ['><'], $html_string);
	}

function ob_start_compress_html()
	{
	ob_implicit_flush(1);
	ob_start('html_compress');
	}

function html_encode($string, $nl2br = false, $nbsp = false)
	{
	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8', true);
	if ($nbsp)
		{
		$string = str_replace("\t", '    ', $string);
		$string = str_replace(' ', '&nbsp;', $string);
		}
	if ($nl2br)
		{
		return nl2br_uni($string);
		}
	return $string;
	}

/**
	PHP's built-in nl2br() only works with LF newlines. This version also works with CR+LF, CR and RS
*/
function nl2br_uni($string)
	{
	return preg_replace('{\\r?\\n|\\r|\\x1e}', '<br />', $string);
	}

if (!function_exists('exif_imagetype'))
	{
	function exif_imagetype($file_name)
		{
		if ((list($width, $height, $type, $attr) = getimagesize($file_name)) !== false)
			{
			return $type;
			}
		return false;
		}
	}

/**
	Extract, concatenate and/or glue substrings.
	
	Each directive can be a string or an array(int start[, int length]).
		If it's a string, concatenate it as glue into the output.
		If it's an array, concatenate substr($string, start, length) into output.
			The length can be omitted as with substr(); the rest of the string is copied.
	
	Example:
		$string = '2010-01-01';
		$string = substring_extract($string, [[0, 4], [5, 2], [7]]); // omitted last length (2)
		assert($string === '20100101');
		$string = substring_extract($string, [[6, 2], '/', [4, 2], '/', [0, 4]]);
		assert($string === '01/01/2010');
*/
function substring_extract($string, array $directives)
	{
	$aggregate = '';
	foreach ($directives as $directive)
		{
		if (is_array($directive))
			{
			if (isset($directive[1]))
				{
				$aggregate .= mb_substr($string, $directive[0], $directive[1]);
				}
			else
				{
				$aggregate .= mb_substr($string, $directive[0]);
				}
			}
		else if (is_string($directive))
			{
			$aggregate .= $directive;
			}
		else
			{
			throw new Exception("Directive must be either a string or an array(int start[, int length]).");
			}
		}
	return $aggregate;
	}

function string_pad_left($string, $character, $length)
	{
	return str_pad($string, $length, $character, STR_PAD_LEFT);
	}

function string_pad_zero($string, $length)
	{
	return str_pad($string, $length, '0', STR_PAD_LEFT);
	}

function string_random($characters, $length)
	{
	$string = '';
	for ($max = mb_strlen($characters) - 1, $i = 0; $i < $length; ++ $i)
		{
		$string .= mb_substr($characters, mt_rand(0, $max), 1);
		}
	return $string;
	}

/**
	Like implode(), but the glue can be an array of strings. Once the elements of glue are exhausted, the remaining matches are concatenated without glue.
*/
function implode_array($glue, array $pieces)
	{
	if (!is_array($glue))
		{
		$glue = [$glue];
		}
	$string = '';
	foreach ($pieces as $piece)
		{
		$string .= $piece . array_shift($glue);
		}
	return $string;
	}

function number_format_compact($number, $decimals = 0, $decimal_point = '.', $thousands_separator = ' ' /* U+202F: Narrow No-Break Space */)
	{
	if (round($number, $decimals) === 0)
		{
		return '0';
		}
	$string = number_format($number, $decimals, $decimal_point, $thousands_separator);
	if (mb_strpos($string, $decimal_point) !== false)
		{
		return rtrim(ltrim($string, '0'), '0');
		}
	return $string;
	}

/**
	Interlaces one or more arrays' values (not preserving keys).
	
	Usage example:
		$array_1 = [1, 2, 3];
		$array_2 = ['a', 'b', 'c'];
		$array_3 = array_interlace([$array_1, $array_2]);
		assert($array_3 === [1, 'a', 2, 'b', 3, 'c']);
*/
function array_interlace(array $arrays)
	{
	$result = array();
	$max = 0;
	foreach ($arrays as $array)
		{
		if (($n = count($array)) > $max)
			{
			$max = $n;
			}
		}
	for ($i = 0; $i < $max; ++ $i)
		{
		foreach ($arrays as $array)
			{
			if (($val = array_shift($array)) !== null)
				{
				$result[] = $val;
				}
			}
		}
	return $result;
	}

function hexadecimal_to_binary_string($hexadecimal_string)
	{
	$binary_string = '';
	for ($l = strlen($hexadecimal_string), $i = 0; $i < $l; $i += 2)
		{
		$binary_string .= chr(hexdec(substr($hexadecimal_string, $i, 2)));
		}
	return $binary_string;
	}

function binary_string_to_hexadecimal($binary_string)
	{
	$hexadecimal_string = '';
	for ($l = strlen($binary_string), $i = 0; $i < $l; ++ $i)
		{
		$hexadecimal_string .= str_pad(dechex(ord($binary_string[$i])), 2, '0', STR_PAD_LEFT);
		}
	return $hexadecimal_string;
	}

/**
	Recursively returns all the items in the given directory alphabetically as keys in an array, optionally in descending order.
	If a key has an array value it's a directory and its items are in the array in the same manner.
*/
function scandir_tree($directory_name, $sort_order = SCANDIR_SORT_ASCENDING, $_recursed = false)
	{
	if (!$_recursed || is_dir($directory_name))
		{
		$items = array_diff(scandir($directory_name, int($sort_order)), ['.', '..']);
		$tree = [];
		foreach ($items as $item)
			{
			$tree[$item] = scandir_tree($directory_name . $item, $sort_order, true);
			}
		return $tree;
		}
	return $directory_name;
	}

/**
	Returns the files (only, not directories) in the given directory alphabetically as an array, optionally in descending order.
*/
function scandir_files($directory_name, $sort_order = SCANDIR_SORT_ASCENDING)
	{
	$items = array_diff(scandir($directory_name, int($sort_order)), ['.', '..']);
	$files = [];
	foreach ($items as $item)
		{
		if (is_file($directory_name . $item))
			{
			$files[] = $item;
			}
		}
	return $files;
	}

/**
	Returns the directories (only, not files) in the given directory alphabetically as an array, optionally in descending order.
*/
function scandir_directories($directory_name, $sort_order = SCANDIR_SORT_ASCENDING)
	{
	$items = array_diff(scandir($directory_name, int($sort_order)), array('.', '..'));
	$dirs = array();
	foreach ($items as $item)
		{
		if (is_dir($directory_name . $item))
			{
			$dirs[] = $item;
			}
		}
	return $dirs;
	}

/**
	Deletes the given directory and everything in it.
*/
function directory_delete($directory_name)
	{
	foreach (scandir_directories($directory_name) as $next)
		{
		if (!directory_delete($directory_name . $next))
			{
			return false;
			}
		}
	return unlink($directory_name);
	}

function clamp($value, $min, $max)
	{
	return max(min($value, $max), $min);
	}

/**
	Returns the distance (hypotenuse length) between two points (x1, y1) and (x2, y2).
*/
function point_distance($x1, $y1, $x2, $y2)
	{
	if ($x1 === $x2 && $y1 === $y2)
		{
		return 0;
		}
	if ($x1 === $x2)
		{
		return $y2 - $y1;
		}
	if ($y1 === $y2)
		{
		return $x2 - $x1;
		}
	return sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
	}

/**
	Returns the angle in degrees between two points (x1, y1) and (x2, y2).
*/
function point_direction($x1, $y1, $x2, $y2)
	{
	if ($x1 === $x2 && $y1 === $y2)
		{
		return 0;
		}
	$dist = sqrt(pow($lenX = $x2 - $x1, 2) + pow($y2 - $y1, 2));
	return rad2deg(acos($lenX / $dist));
	}

/**
	Returns the [x, y] component of a (length, degrees angle) vector.
*/
function length_direction($length, $direction)
	{
	$x = $length * cos($angle = deg2rad($direction));
	$y = $length * sin($angle);
	return [$x, $y];
	}

/**
	Returns the x component of a (length, degrees angle) vector.
*/
function length_direction_x($length, $direction)
	{
	return $length * cos(deg2rad($direction));
	}

/**
	Returns the y component of a (length, degrees angle) vector.
*/
function length_direction_y($length, $direction)
	{
	return $length * sin(deg2rad($direction));
	}

function sine($val)
	{
	return 2 - (sin(deg2rad(90 + ($val * 180))) + 1);
	}

function sine_start($val)
	{
	return 1 - sin(deg2rad(90 + ($val * 90)));
	}

function sine_end($val)
	{
	return sin(deg2rad($val * 90));
	}

function int($var)
	{
	return (int) $var;
	}

function bool($var)
	{
	return (bool) $var;
	}

function float($var)
	{
	return (float) $var;
	}

function string($var)
	{
	return (string) $var;
	}