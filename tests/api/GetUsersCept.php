<?php 
$I = new ApiTester($scenario);
$I->wantTo('obtain all the users');
$I->sendGet('users');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
//$I->seeResponseContains('OK');
?>