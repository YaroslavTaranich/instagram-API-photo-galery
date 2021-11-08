<?
$accessToken = "в эту переменную нужно поместить токен";
$url = "https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=" . $accessToken;

$instagramCnct = curl_init(); // инициализация cURL подключения
curl_setopt($instagramCnct, CURLOPT_URL, $url); // адрес запроса
curl_setopt($instagramCnct, CURLOPT_RETURNTRANSFER, 1); // просим вернуть результат
$response = json_decode(curl_exec($instagramCnct)); // получаем и декодируем данные из JSON
curl_close($instagramCnct); // закрываем соединение

// // обновляем токен и дату его создания в базе

$accessToken = $response->access_token; // обновленный токен

$url = "https://graph.instagram.com/me/media?fields=id,media_type,media_url,caption,timestamp,thumbnail_url,permalink&access_token=" . $accessToken;
 $instagramCnct = curl_init(); // инициализация cURL подключения
 curl_setopt($instagramCnct, CURLOPT_URL, $url); // адрес запроса
 curl_setopt($instagramCnct, CURLOPT_RETURNTRANSFER, 1); // просим вернуть результат
 $jsonMedia = curl_exec($instagramCnct);
 $media = json_decode(curl_exec($instagramCnct)); // получаем и декодируем данные из JSON
 curl_close($instagramCnct); // закрываем соединение

//var_dump ($media);
//echo ($media);

$url = "https://graph.instagram.com/me/media?fields=id,media_type,media_url,caption,timestamp,thumbnail_url,permalink,children{fields=id,media_url,thumbnail_url,permalink}&limit=50&access_token=" . $accessToken;
$instagramCnct = curl_init(); // инициализация cURL подключения
curl_setopt($instagramCnct, CURLOPT_URL, $url); // подключаемся
curl_setopt($instagramCnct, CURLOPT_RETURNTRANSFER, 1); // просим вернуть результат
$media = json_decode(curl_exec($instagramCnct)); // получаем и декодируем данные из JSON
curl_close($instagramCnct); // закрываем соединение

// $instaFeed = array();
// var_dump($media);
foreach ($media->data as $mediaObj) {
  if (!empty($mediaObj->children->data)) {
    foreach ($mediaObj->children->data as $children) {
      $instaFeed[$children->id]['img'] = $children->thumbnail_url ?: $children->media_url;
      $instaFeed[$children->id]['link'] = $children->permalink;
    }
  } else {
    $instaFeed[$mediaObj->id]['img'] = $mediaObj->thumbnail_url ?: $mediaObj->media_url;
    $instaFeed[$mediaObj->id]['link'] = $mediaObj->permalink;
  }
}

// var_dump($instaFeed);
echo (json_encode($instaFeed));