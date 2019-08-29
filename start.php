<?php
set_time_limit(0);
include 'InstaBot.php';

$insta = new instagram();

$settings = array(
    'username' => 'Login', //Логин от аккаунта
    'password' => 'Paroli', //Пароль от аккаунта
    'comments' => array('omg', //Комментарии
                        'cool', 
                        'wow'),
    'tag'      => array('музыка', //Хэштэги
                        'програмирование',
                        'музыкант',
                        'instagram')
    );



$response = $insta->Login($settings['username'], $settings['password']);

$search = $settings['tag'][array_rand($settings['tag'])];
$res = $insta->SearchTag($search);
$decode = json_decode($res[1], true); 
if ($response == "ok") {
	echo "Успешно авторизовали аккаунт {$settings['username']}<br>";

foreach($decode['items'] as $data)  {

	$d = explode("_", $data['id']);
	$media_id = $d[0];
	$user_id = $d[1];
	$like_count = $data['like_count'];
	$comment_count = $data['comment_count'];
	$username = $data['user']['username'];
	$haslike = $data['has_liked'];	        
	$fres=$insta->IsFriend($user_id);
	$friend = json_decode($fres[1], true); 

   
    if (!$friend['following'] && !$friend['is_private'] && !$friend['outgoing_request'])  {

				$follow = $insta->UserFollow($user_id);
				 if($follow == "200") {
 		         echo "<br>Аккаунт => {$settings['username']} успешно подписались на => {$username}<br>";
 	           }else{
 	           	 echo  "<br>Аккаунт => {$settings['username']} не смог подписаться  на => {$username}<br>";
 	           }

				if (!$haslike) {
					
					sleep(rand(3,10));
					$res=$insta->UserLike($media_id);
					if ($res = "ok") {
						echo "<br> Аккаунт => {$settings['username']} Поставил лайк на пост => {$media_id} аккаунта => {$username}";
					}else{
						echo "<br> Аккаунт => {$settings['username']} Ошибка,не лайк на пост => {$media_id} аккаунта => {$username}";
				        }
						
					sleep(rand(3,10));
					$comment = '@'.$username . '))' . $settings['comments'][array_rand($settings['comments'])];
					$com=$insta->UserComment($comment, $media_id);
					if ($com == "ok") {
						echo "<br>Аккаунт => {$settings['username']} Оставил комментарий => {$comment} под постом =>{$media_id} аккаунта => {$username}";
					}else{
						echo "<br>Аккаунт => {$settings['username']} не смог оставить комментарий => {$comment} под постом =>{$media_id} аккаунта => {$username}";
					}
                    sleep(rand(3,10));	
                    $unfolow = $insta->UserUnFollow($user_id);
                    echo "<br>Аккаунт => {$settings['username']} успешно отписался от => {$username}<br>";
				}				
			  } 				
			}
		  }else{
            echo "<br>Ошибка  авторизовации аккаунта {$settings['username']}<br>";

		  }	
	unset($res);	

?>

