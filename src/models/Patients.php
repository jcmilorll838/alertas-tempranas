<?php
	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Message;
	use Phalcon\Mvc\Model\Validator\Uniqueness;
	use Phalcon\Mvc\Model\Validator\InclusionIn;

	class Patients extends Model
	{
		public function validation()
		{
			$this->validate(new InclusionIn(
				array(
					"field" => "gender",
					"domain" => array("Femenino", "Masculino")
				)
			));

			$this->validate(new Uniqueness(
				array(
					"field" => "name",
					"message" => "This name already exist in our db"
				)
			));
	

			if($this->validationHasFailed() == true){
				return false;
			}		
		}	

	}

?>