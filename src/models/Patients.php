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
					"domain" => array("F", "M")
				)
			));

			$this->validate(new Uniqueness(
				array(
					"field" => "name",
					"message" => "This name already exist in our db"
				)
			));

//			if($this->date < 0) {
//				$this->appendMessage(new Message("The date cannot be less than zero"));
//			}	

			if($this->validationHasFailed() == true){
				return false;
			}		
		}	

	}

?>