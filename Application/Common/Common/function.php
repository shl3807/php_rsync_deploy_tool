<?php
	/**
	 * 转码
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	function ensepchtml(&$str){
		$html_to_char = C('HTML_TO_CHAR');
		foreach ($html_to_char as $key => $value) {
			$str = str_replace($value,$key,$str);
		}
		return $str;
	}

	/**
	 * 反转码
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	function desepchtml(&$str){
		$html_to_char = C('HTML_TO_CHAR');
		foreach ($html_to_char as $key => $value) {
			$str = str_replace($key,$value,$str);
		}
		return $str;
	}
?>