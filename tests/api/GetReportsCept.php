<?php 
$I = new ApiTester($scenario);
$I->wantTo('perform actions and see result');
$I->sendGet('reports');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
?>