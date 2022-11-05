<?php

/*
 * ViaThinkSoft Anti-Spam Script for PHP
 * (C) 2009-2022 ViaThinkSoft
 * Revision: 2022-11-05 (Version 4.1.1)
 * License: Apache 2.0 License
 */

class VtsAntiSpam4 {

	public $garbageLength = 5;

	public function __construct() {
		self::randomize();
	}

	private static function randomize() {
		// Anfagswert über aktuelle Mikrosekunde setzen
		// http://de2.php.net/manual/de/function.srand.php
		list($usec, $sec) = explode(' ', microtime());
		$seed = (int)((int)$sec + ((float)$usec * 100000));
		srand($seed);
	}

	private function RandomString($len) {
		// http://www.jonasjohn.de/snippets/php/rand-str.htm
		$randstr = '';
		//srand((double)microtime()*1000000);
		for($i=0;$i<$len;$i++) {
			$n = rand(48,120);
			while (($n >= 58 && $n <= 64) || ($n >= 91 && $n <= 96)) {
				$n = rand(48,120);
			}
			$randstr .= chr($n);
		}
		return $randstr;
	}

	private function js_randombreaks() {
		$len = rand(0, $this->garbageLength);
		$r = '';
		$one_line_comment = false;
		for($i=0;$i<$len;$i++) {
			$m = rand(0, 3);
			if ($m == 0) {
				$r .= ' ';
			} else if ($m == 1) {
				$r .= '//';
				$r .= $this->RandomString($i);
				$one_line_comment = true;
			} else if ($m == 2) {
				$r .= "\r\n";
				$one_line_comment = false;
			} else {
				$r .= "\t";
			}
		}
		if ($one_line_comment) $r .= "\r\n";
		return $r;
	}

	private function alas_js_crypt($text) {
		$tmp = '';
		for ($i=0; $i<strlen($text); $i++) {
			$tmp .= $this->js_randombreaks();
			$tmp .= 'document.write("&#'.ord(substr($text, $i, 1)).';");';
			$tmp .= $this->js_randombreaks();
		}
		$tmp = $this->js_randombreaks().$tmp.$this->js_randombreaks();
		return $tmp;
	}

	private function alas_noscript_crypt($text){
		$tmp = '';
		for ($i=0; $i<strlen($text); $i++) {
			$tmp .= '<span style="display:inline;">&#'.ord(substr($text, $i, 1)).';</span>';
			$tmp .= '<!--'.$this->js_randombreaks().'-->';
			$tmp .= '<span style="display:none;">'.$this->RandomString(rand(0, $this->garbageLength)).'</span>';
		}
		return $tmp;
	}

	private function alas_js_write($text) {
		$text = str_replace('\\', '\\\\', $text);
		$text = str_replace('"', '\"', $text);
		$text = str_replace('/', '\/', $text); // W3C Validation </a> -> <\/a>

		$ret  = '';
		$ret .= $this->js_randombreaks();
		$ret .= 'document.write("'.$text.'");';
		$ret .= $this->js_randombreaks();

		return $ret;
	}

	public function secure_email($email, $linktext, $crypt_linktext)
	{
		// No new lines to avoid a JavaScript error!
		$linktext = str_replace("\r", ' ', $linktext);
		$linktext = str_replace("\n", ' ', $linktext);

		$aus = '';
		if ($email != '') {
			$zid  = 'ALAS-4.0-'.DecHex(crc32($email)).'-'.DecHex(crc32($linktext)).'-'.($crypt_linktext ? 'S' : 'L');
			$title = 'ViaThinkSoft "ALAS" Anti-Spam';

			$aus .= "<!-- BEGIN $title [ID $zid] -->\r\n";
			$aus .= '<script language="JavaScript" type="text/javascript"><!--'."\n";
			$aus .= $this->alas_js_write('<a href="');
			$aus .= $this->alas_js_crypt('mailto:'.$email);
			$aus .= $this->alas_js_write('">');
			$aus .= $crypt_linktext ? $this->alas_js_crypt($linktext) : $this->alas_js_write($linktext);
			$aus .= $this->alas_js_write('</a>').'// --></script>';

			$aus .= '<noscript>';
			if ($linktext != $email) $aus .= ($crypt_linktext ? $this->alas_noscript_crypt($linktext) : $linktext).' ';
			$aus .= $this->alas_noscript_crypt("[ $email ]");
			$aus .= '</noscript>';
			$aus .= "\r\n<!-- END $title [ID $zid] -->\r\n";
		}

		return $aus;
	}

	public function secure_email_autodetect($email, $linktext) {
		// Automatisch erkennen, ob der $linktext für Spambots interessant ist oder nicht
		$pos = strpos($linktext, '@');

		return $this->secure_email($email, $linktext, $pos !== false);
	}

	public function secure_email_identical_text($email) {
		return $this->secure_email_autodetect($email, $email);
	}

}

# ------------------------------------------------------------------------------

function secure_email($email, $linktext, $crypt_linktext, $css_class='') {
	if (!empty($css_class)) {
		// TODO
		throw new Exception("CSSClass is not yet implemented in AntiSpam v4");
	}

	$antispam = new VtsAntiSpam4();
	$res = $antispam->secure_email($email, $linktext, $crypt_linktext);
	return $res;
}

function secure_email_autodetect($email, $linktext) {
	$antispam = new VtsAntiSpam4();
	$res = $antispam->secure_email_autodetect($email, $linktext);
	return $res;
}

function secure_email_identical_text($email) {
	$antispam = new VtsAntiSpam4();
	$res = $antispam->secure_email_identical_text($email);
	return $res;
}
