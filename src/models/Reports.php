<?php
	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Message;
	use Phalcon\Mvc\Model\Validator\Uniqueness;
	use Phalcon\Mvc\Model\Validator\InclusionIn;

	class Reports extends Model
	{
		public function validation()
		{
//			$this->validate(new InclusionIn(
//				array(
//					"field" => "type",
//					"domain" => array("droid", "mechanical", "virtual")
//				)
//			));

			$this->validate(new Uniqueness(
				array(
					"field" => "place",
					"message" => "In this place now there is other patient"
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