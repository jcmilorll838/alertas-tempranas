<?php 
$I = new ApiTester($scenario);
$I->wantTo('update a patient');
$patient_id = $I->grabResponse();
$I->sendPut('/patients', array('id'=> $patient_id, 'name'=>'jhon', 'occupation'=>'doctor', 'gender'=>'M', 'password'=>'12345'));
$I->seeResponseIsJson();
//$I->seeResponseContains('conflict');
?>