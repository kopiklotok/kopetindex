<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");
date_default_timezone_set('Asia/Jakarta');

if(isset($_GET['domain'])){

	$res = [
		'domain' => $_GET['domain'],
		'web'	=> cekindex($_GET['domain'],'web'),
		'web24'	=> cekindex($_GET['domain'],'web24'),
		'img'	=> cekindex($_GET['domain'],'img'),
		'img24'	=> cekindex($_GET['domain'],'img24'),
		'date' => date('Y-m-d'),
		'datetime' => date('Y-m-d H:i:s')
	];
		
}else{
	$res = [
	  'status' => 'good',
	  "name"              => str_replace('.herokuapp.com','',$_SERVER['HTTP_HOST']),
	  "node"              => gethostname(),
	  "server_name"       => $_SERVER['HTTP_HOST'],
	  "server_software"   => $_SERVER['SERVER_SOFTWARE'],
	  "remote_addr"       => $_SERVER['REMOTE_ADDR']
	];
}

echo json_encode($res);

function cekindex($domain,$type){

	if($type=='web'){
		$r = file_get_contents('https://www.google.co.id/search?dcr=0&ei=oTcOWo6FOIj1vATrgLkY&q=site%3A'.$domain.'&oq=site%3A'.$domain.'&gs_l=');
	}elseif($type=='web24'){
		$r = file_get_contents('https://www.google.co.id/search?q=site:'.$domain.'&dcr=0&source=lnt&tbs=qdr:d&sa=X&biw=1920&bih=987');	
	}elseif($type=='img'){
		$r = file_get_contents('https://www.google.co.id/search?q=site:'.$domain.'&dcr=0&biw=1920&bih=987&sout=1&tbm=isch&tbas=0&source=lnt&sa=X&ved=');
	}elseif($type=='img24'){
		$r = file_get_contents('https://www.google.co.id/search?q=site:'.$domain.'&dcr=0&biw=1920&bih=987&sout=1&tbas=0&tbm=isch&source=lnt&tbs=qdr:d&sa=X&ved=');
	}

	$domknt = new domDocument();
	@$domknt->loadHTML($r);
	$dodknt = $domknt->getElementsByTagName('div');
	foreach($dodknt as $c){
	  $d = $c->getAttribute('id');
	  if(strpos($d,'resultStats')!==false){
	  	$s = explode('(',$c->nodeValue);
	  	$s = $s[0];
	    $e = ucwords(preg_replace("/[^0-9]/",'',$s));
			return $e;
	  } 
	}
}

