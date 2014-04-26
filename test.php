<?php
http://stackoverflow.com/questions/8620721/store-all-get-requests-in-a-single-variable-php
echo http_build_query($_GET).'<br/>';
if(count($_GET)>0){
	echo 'true';
}else{
	echo 'false';
}
?>