<?php
require_once("lib/php-sdk/src/facebook.php");

class FbUser {
	var $name = '';
	var $id = '';
	var $albums = array();

	public function __construct($user_id, $name) {
		$this->id = $user_id;
		$this->name = $name;
	}

	public function getAlbums($facebook) {
		$query = '';
		$ret = $facebook->api(array('method' => 'fql.query', 'query' => $query));
	}

}

class FbAlbum {
	var $name = '';
	var $photos = array();
}

class FbPhoto {
	var $src = '';
}

function connect($app_id, $app_secret) {
	$config = array(
		'appId' => $app_id,
		'secret' => $app_secret,
	);

	$facebook = new Facebook($config);
	$access_token = 'DUMMY';
	$facebook->setAccessToken($access_token);

	return $facebook;
}

function getFriends($user_id, $facebook) {
	$friends = array();

	$query_friend = 'SELECT uid2 FROM friend WHERE uid1 = ' . $user_id;
	$query_profile = 'SELECT id, name FROM profile WHERE id IN(' . $query_friend . ')';
	$ret = $facebook->api(array('method' => 'fql.query', 'query' => $query_profile));

	foreach ($ret as $user) {
		$user = new FbUser($user['id'], $user['name']);
		$friends[] = $user;
	}

	return $friends;
}

function selectFriend($facebook) {
	$user_id = $facebook->getUser();
	$friends = getFriends($user_id, $facebook);

	$sec = 0;
	foreach ($friends as $friend) {
		$sec++;
		print('[' . strval($sec) . '] ' . $friend->name . "\n");
	}
}

function init() {
	$app_id = 'DUMMY';
	$app_secret = 'DUMMY';
	$facebook = connect($app_id, $app_secret);
	return $facebook;
}

function main() {
	$facebook = init();
	selectFriend($facebook);
}

main();
