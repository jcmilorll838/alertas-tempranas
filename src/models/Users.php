<?php
	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Message;
	use Phalcon\Mvc\Model\Validator\Uniqueness;
	use Phalcon\Mvc\Model\Validator\InclusionIn;

	class Users extends Model
	{
		public function validation()
		{

			$this->validate(new Uniqueness(
				array(
					"field" => "user",
					"message" => "This user already exist"
				)
			));	

			if($this->validationHasFailed() == true){
				return false;
			}		
		}	

	}

?>