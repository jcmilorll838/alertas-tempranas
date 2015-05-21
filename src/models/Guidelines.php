<?php
	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Message;
	use Phalcon\Mvc\Model\Validator\Uniqueness;
	use Phalcon\Mvc\Model\Validator\InclusionIn;

	class Guidelines extends Model
	{
		public function validation()
		{

			$this->validate(new Uniqueness(
				array(
					"field" => "id",
					"message" => "id must be unique"
				)
			));

			if($this->validationHasFailed() == true){
				return false;
			}		
		}	

	}

?>