<?php

// PHP-AntiSpam-Funktion "secure_email", Version 3.1 of 2022-01-09
// by Daniel Marschall [www.daniel-marschall.de], ViaThinkSoft
// License: Apache 2.0 License

class VtsAntiSpam3 {

	private function alas_js_crypt($text)
	{
		$tmp = '';
		for ($i=0; $i<strlen($text); $i++)
		{
			$tmp .= 'document.write("&#'.ord(substr($text, $i, 1)).';");';
		}
		return $tmp;
	}

	private function alas_js_write($text)
	{
		$text = str_replace('\\', '\\\\', $text);
		$text = str_replace('"', '\"', $text);
		$text = str_replace('/', '\/', $text); // W3C Validation </a> -> <\/a>
		return 'document.write("'.$text.'");';
	}

	public function secure_email($email, $linktext, $crypt_linktext, $css_class='')
	{
		// No new lines to avoid a JavaScript error!
		$linktext = str_replace("\r", ' ', $linktext);
		$linktext = str_replace("\n", ' ', $linktext);

		$aus = '';
		if ($email != '')
		{
			$aus .= '<script><!--'."\n"; // type="text/javascript" is not necessary in HTML5
			$aus .= $this->alas_js_write('<a ');
			if ($css_class != '') $aus .= $this->alas_js_write('class="'.$css_class.'" ');
			$aus .= $this->alas_js_write('href="');
			$aus .= $this->alas_js_crypt('mailto:'.$email);
			$aus .= $this->alas_js_write('">');
			$aus .= $crypt_linktext ? $this->alas_js_crypt($linktext) : $this->alas_js_write($linktext);
			$aus .= $this->alas_js_write('</a>').'// --></script>';
		}

		return $aus.'<noscript>Please enable JavaScript to display this email address.</noscript>';
	}

}

# ------------------------------------------------------------------------------

function secure_email($email, $linktext, $crypt_linktext, $css_class='') {
	$antispam = new VtsAntiSpam3();
	$res = $antispam->secure_email($email, $linktext, $crypt_linktext);
	return $res;
}

