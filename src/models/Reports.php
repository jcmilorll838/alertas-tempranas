<?php
	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Message;
	use Phalcon\Mvc\Model\Validator\Uniqueness;
	use Phalcon\Mvc\Model\Validator\InclusionIn;

	class Reports extends Model
	{
		public function validation()
		{
			$this->validate(new Uniqueness(
				array(
					"field" => "patient_id",
					"message" => "This patient is already reported"
				)
			));

			$this->validate(new Uniqueness(
				array(
					"field" => "place",
					"message" => "In this place now there is other patient"
				)
			));

			if($this->validationHasFailed() == true){
				return false;
			}		
		}	

	}

?>