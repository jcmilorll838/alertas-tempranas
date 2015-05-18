<?php 
$I = new ApiTester($scenario);
$I->wantTo('perform actions and see result');
$I->sendGet('diseases');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
?>