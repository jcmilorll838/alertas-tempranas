<?php
	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Message;
	use Phalcon\Mvc\Model\Validator\Uniqueness;
	use Phalcon\Mvc\Model\Validator\InclusionIn;

	class Diseases extends Model
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
					"field" => "name",
					"message" => "The disease name must be unique"
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