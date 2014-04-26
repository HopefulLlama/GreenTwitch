<?php
function redirect($url){
	if(count($_GET)>0){
		$url=$url.'?'.http_build_query($_GET);
	}
	return $url;
}
?>