<?php
	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Message;
	use Phalcon\Mvc\Model\Validator\Uniqueness;
	use Phalcon\Mvc\Model\Validator\InclusionIn;

	class Diseases extends Model
	{
		public function validation()
		{


			$this->validate(new Uniqueness(
				array(
					"field" => "cod",
					"message" => "Other disease use this cod"
				)
			));

			$this->validate(new Uniqueness(
				array(
					"field" => "name",
					"message" => "The disease name must be unique"
				)
			));


			if($this->validationHasFailed() == true){
				return false;
			}		
		}	

	}

?>