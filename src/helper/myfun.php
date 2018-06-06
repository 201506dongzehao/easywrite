<?php
/**
 * 打印函数
 */

function dump($str,$dump = false,$echo = true,$char = 'UTF-8')
{
	@ob_start();
	@header("Content-Type:text/html;charset=\"$char\"");
	echo '<pre><div style="text-align:left;">';
	if($dump)var_dump($str);else print_r($str);
	echo '</div></pre>';
	$out =  ob_get_contents();
	ob_end_clean();
	if($echo)
	{
		echo $out;
	}
	else
	{
		return $out;
	}
	return NULL;
}

?>