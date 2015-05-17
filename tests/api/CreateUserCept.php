<?php 
$I = new ApiTester($scenario);
$I->wantTo('create a user ');
$I->amHttpAuthenticated('milo','milo12345');
$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
$I->sendPOST('users', ['name'=>'Camilo', 'role'=>'doctor', 'user'=>'milo1', 'password'=>'12345']);
$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();
$I->seeResponseContains('ok');
?>
