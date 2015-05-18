<?php 
$I = new ApiTester($scenario);
$I->wantTo('update a user');
$user_id = $I->grabResponse();
$I->sendPut('users', array('id'=> $user_id, 'name'=>'Camilo', 'role'=>'doctor', 'user'=>'milo1', 'password'=>'12345'));
$I->seeResponseIsJson();
//$I->seeResponseCodeIs(409);
//$I->seeResponseContains('ok');
?>