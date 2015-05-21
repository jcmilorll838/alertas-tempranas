<?php
	use Phalcon\Mvc\Micro;
	use Phalcon\Loader;
	use Phalcon\DI\FactoryDefault;
	use Phalcon\Http\Response;
	use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

	$loader= new Loader();

	$loader->registerDirs(array(
		__DIR__ . '/models/'
	))->register();

	$di= new FactoryDefault();

	$di->set('db', function() {
		return new PdoMysql(array(
				"host"=> "localhost",
				"username"=> "root",
				"password"=> "",
				"dbname"=> "alerts"
			));
	});

	$app = new Micro($di);

	
	// SEARCHES ALL 

		//retrieves all reports
		$app->get('/api/reports', function() use($app){
			$phql = "SELECT Reports.id AS rep_id, Reports.place AS rep_place, Diseases.name AS dis_name FROM Reports JOIN Diseases WHERE Reports.disease_id = Diseases.id";
			$reports = $app->modelsManager->executeQuery($phql);

			$data = array();
			foreach ($reports as $report ) {
				# code...
				$data[]= array(
						'id' => $report->rep_id,
						'place' => $report->rep_place,
						'disease' => $report->dis_name
					);
			}
			echo json_encode($data);
		});

		//retrieves all users
		$app->get('/api/users', function() use($app){
			$phql = "SELECT * FROM Users ORDER BY name";
			$users = $app->modelsManager->executeQuery($phql);

			$data = array();
			foreach ($users as $user ) {
				# code...
				$data[]= array(
						'id' => $user->id,
						'user' => $user->user,
						'password'=> $user->password
					);
			}
			echo json_encode($data);
		});

		//retrieves all patients
		$app->get('/api/patients', function() use($app){
			$phql = "SELECT * FROM Patients ORDER BY name";
			$patients = $app->modelsManager->executeQuery($phql);

			$data = array();
			foreach ($patients as $patient) {
				# code...
				$data[]= array(
						'id' => $patient->id,
						'name' => $patient->name,
						'occupation' => $patient->occupation,
						'gender'=> $patient->gender,
					);
			}
			echo json_encode($data);
		});

		//retrieves all diseases
		$app->get('/api/diseases', function() use($app){
			$phql = "SELECT * FROM Diseases ORDER BY name";
			$diseases = $app->modelsManager->executeQuery($phql);

			$data = array();
			foreach ($diseases as $disease) {
				# code...
				$data[]= array(
						'id' => $disease->id,
						'name' => $disease->name,
					);
			}
			echo json_encode($data);
		});

	
	//SEARCHES DIFFERENT

		//searches for guidelines of one disease $name
		$app->get('/api/guidelines/search/{name}', function($name) use($app){

			$phql = "SELECT Guidelines.id AS gui_id, Guidelines.guideline AS gui_guideline, Diseases.name AS dis_name FROM Guidelines JOIN Diseases WHERE Guidelines.disease_id = Diseases.id AND Diseases.name LIKE :name:";
			$guidelines = $app->modelsManager->executeQuery($phql, array(
				'name' => '%'.$name.'%'
			));

			$data = array();
			foreach ($guidelines as $guideline ) {
				# code...
				$data[]= array(
						'id' => $guideline->gui_id,
						'name' => $guideline->dis_name,
						'guideline' => $guideline->gui_guideline,
					);
			}
			echo json_encode($data);
		});

		//retrieves users by user
		$app->get('/api/users/search/{user}', function($user) use($app){

			$phql = "SELECT * FROM Users WHERE user LIKE :user:";
			$users = $app->modelsManager->executeQuery($phql, array(
				'user' => '%'.$user.'%'
			));

			$data = array();
			foreach ($users as $user ) {
				# code...
				$data[]= array(
						'id' => $user->id,
						'user' => $user->user,
						'password' => $user->password,
					);
			}
			echo json_encode($data);
		});

	//SEARCHES FIND BY KEY

		//retrieves reports based on primary key
		$app->get('/api/reports/{id:[0-9]+}', function($id) use ($app){
			$phql = "SELECT * FROM Reports WHERE id = :id:";
			$report = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			))->getFirst();

			$response = new Response();

			if($report == false) {
				$response->setJsonContent(array('status' => 'NOT FOUND'));
			} else {
				$response->setJsonContent(array(
						'status' => 'FOUND',
						'data' => array(
							'id' => $report->id,
							'place' => $report->place	
						)
					));
			}
			return $response;
		});

		//retrieves users based on primary key
		$app->get('/api/users/{id:[0-9]+}', function($id) use ($app){
			$phql = "SELECT * FROM Users WHERE id = :id:";
			$user = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			))->getFirst();

			$response = new Response();

			if($user == false) {
				$response->setJsonContent(array('status' => 'NOT FOUND'));
			} else {
				$response->setJsonContent(array(
						'status' => 'FOUND',
						'data' => array(
							'id' => $user->id,
							'name' => $user->name,
							'role' => $user->role,
							'user' => $user->user
						)
					));
			}
			return $response;
		});

		//retrieves patients based on primary key
		$app->get('/api/patients/{id:[0-9]+}', function($id) use ($app){
			$phql = "SELECT * FROM Patients WHERE id = :id:";
			$patient = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			))->getFirst();

			$response = new Response();

			if($patient == false) {
				$response->setJsonContent(array('status' => 'NOT FOUND'));
			} else {
				$response->setJsonContent(array(
						'status' => 'FOUND',
						'data' => array(
							'id' => $patient->id,
							'name' => $patient->name,
							'occupation'=> $patient->occupation,
							'gender'=> $patient->gender
						)
					));
			}
			return $response;
		});

		//retrieves diseases based on primary key
		$app->get('/api/diseases/{id:[0-9]+}', function($id) use ($app){
			$phql = "SELECT * FROM Diseases WHERE id = :id:";
			$disease = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			))->getFirst();

			$response = new Response();

			if($disease == false) {
				$response->setJsonContent(array('status' => 'NOT FOUND'));
			} else {
				$response->setJsonContent(array(
						'status' => 'FOUND',
						'data' => array(
							'id' => $disease->id,
							'name' => $disease->name	
						)
					));
			}
			return $response;
		});


	//INSERTS

		//adds a new user 
		$app->post('/api/users', function() use($app){
			$user = $app->request->getJsonRawBody();

			$phql = "INSERT INTO Users (name, role, user, password) VALUES (:name:, :role:, :user:, :password:)";

			$status = $app->modelsManager->executeQuery($phql, array(
					'name' => $user->name,
					'role' => $user->role,
					'user' => $user->user,
					'password'=> $user->password
			));

			$response = new Response();

			if($status->success() == true) {

				$response->setStatusCode(201, "Created");

				$user->id = $status->getModel()->id;

				$response->setJsonContent(array('status'=>'OK', 'data'=>$user));

			} else {
				$response->setStatusCode(409, "Conflict");
				
				$errors = array();
				foreach ($status->getMessages() as $message ) {
						# code...
					$errors[] = $message->getMessage();
				}	

				$response->setJsonContent(array('status'=>'ERROR', 'messages'=> $errors));
			}
			return $response;
		});

		//adds a new patient
		$app->post('/api/patients', function() use($app){
			$patient = $app->request->getJsonRawBody();

			$phql = "INSERT INTO Patients (name, occupation, gender) VALUES (:name:, :occupation:, :gender:)";
			
			$status = $app->modelsManager->executeQuery($phql, array(
					'name' => $patient->name,
					'occupation' => $patient->occupation,
					'gender' => $patient->gender
			));

			$response = new Response();

			if($status->success() == true) {

				$response->setStatusCode(201, "Created");

				$patient->id = $status->getModel()->id;

				$response->setJsonContent(array('status'=>'OK', 'data'=>$patient));

			} else {
				$response->setStatusCode(409, "Conflict");
				
				$errors = array();
				foreach ($status->getMessages() as $message ) {
						# code...
					$errors[] = $message->getMessage();
				}	
				
				$response->setJsonContent(array('status'=>'ERROR', 'messages'=> $errors));
			}
			return $response;
		});

		//adds a new report 
		$app->post('/api/reports', function() use($app){
			$report = $app->request->getJsonRawBody();

			$phql = "INSERT INTO Reports (place, date, user_id, patient_id, disease_id) VALUES (:place:, :date:, :user:, :patient:, :disease:)";

			$status = $app->modelsManager->executeQuery($phql, array(
					'place' => $report->place,
					'date' => $report->date,
					'user' => $report->user_id,
					'patient'=> $report->patient_id,
					'disease' => $report->disease_id
			));

			$response = new Response();

			if($status->success() == true) {

				$response->setStatusCode(201, "Created");

				$report->id = $status->getModel()->id;

				$response->setJsonContent(array('status'=>'OK', 'data'=>$report));

			} else {
				$response->setStatusCode(409, "Conflict");
				
				$errors = array();
				foreach ($status->getMessages() as $message ) {
						# code...
					$errors[] = $message->getMessage();
				}	

				$response->setJsonContent(array('status'=>'ERROR', 'messages'=> $errors));
			}
			return $response;
		});
	
		//adds a new disease 
		$app->post('/api/diseases', function() use($app){
			$disease = $app->request->getJsonRawBody();

			$phql = "INSERT INTO Diseases (name, cod) VALUES (:name:, :cod:) ";
			
			$status = $app->modelsManager->executeQuery($phql, array(
				'name'=> $disease->name,
				'cod' => $disease->cod
			));

			$response = new Response();

			if($status->success() == true) {

				$response->setStatusCode(201, "Created");

				$disease->id = $status->getModel()->id;

				$response->setJsonContent(array('status'=>'OK', 'data'=>$disease));

			} else {
				$response->setStatusCode(409, "Conflict");
				
				$errors = array();
				foreach ($status->getMessages() as $message ) {
						# code...
					$errors[] = $message->getMessage();
				}	

				$response->setJsonContent(array('status'=>'ERROR', 'messages'=> $errors));
			}
			return $response;
		});

		//adds a new guideline 
		$app->post('/api/guidelines', function() use($app){
			$guideline = $app->request->getJsonRawBody();

			$phql = "INSERT INTO Guidelines (guideline, disease_id) VALUES (:guideline:, :disease:)";

			$status = $app->modelsManager->executeQuery($phql, array(
					'guideline' => $guideline->guideline,
					'disease' => $guideline->disease_id
			));

			$response = new Response();

			if($status->success() == true) {

				$response->setStatusCode(201, "Created");

				$guideline->id = $status->getModel()->id;

				$response->setJsonContent(array('status'=>'OK', 'data'=>$guideline));

			} else {
				$response->setStatusCode(409, "Conflict");
				
				$errors = array();
				foreach ($status->getMessages() as $message ) {
						# code...
					$errors[] = $message->getMessage();
				}	

				$response->setJsonContent(array('status'=>'ERROR', 'messages'=> $errors));
			}
			return $response;
		});


	//UPDATES

		//updates users based on user
		$app->put('/api/users/{usuario}', function($usuario) use($app) {
			$user = $app->request->getJsonRawBody();

			$phql = "UPDATE Users SET name = :name:, role = :role:, password = :password: WHERE user = :usuario:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'name' => $user->name,
				'role' => $user->role,
				'usuario' => $usuario,
				'password' => $user->password
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status' => 'OK'));
				$response->setStatusCode(204,"Updated");
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

		//updates patients based on primary key
		$app->put('/api/patients/{id:[0-9]+}', function($id) use($app) {
			$patient = $app->request->getJsonRawBody();

			$phql = "UPDATE Patients SET name = :name:, occupation = :occupation:, gender = :gender: WHERE id = :id:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'id' => $id,
				'name' => $patient->name,
				'occupation' => $patient->occupation,
				'gender' => $patient->gender
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status' => 'OK'));
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

		//updates diseases based on primary key
		$app->put('/api/diseases/{id:[0-9]+}', function($id) use($app) {
			$disease = $app->request->getJsonRawBody();

			$phql = "UPDATE Diseases SET name = :name:, cod = :cod: WHERE id = :id:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'id' => $id,
				'name' => $disease->name,
				'cod' => $disease->cod
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status' => 'OK'));
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

		//updates guidelines based on primary key
		$app->put('/api/guidelines/{id:[0-9]+}', function($id) use($app) {
			$guideline = $app->request->getJsonRawBody();

			$phql = "UPDATE Guidelines SET guideline = :guideline:, disease_id = :disease_id: WHERE id = :id:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'id' => $id,
				'guideline' => $guideline->guideline,
				'disease_id' => $guideline->disease_id
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status' => 'OK'));
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});


	//DELETES

		//deletes users based on user (usuario, cedula)
		$app->delete('/api/users/{usuario}', function($usuario) use($app){
			$phql = "DELETE FROM Users WHERE user = :usuario:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'usuario' => $usuario
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status'=> 'OK'));
				$response->setStatusCode(202, "Deleted");
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

		//deletes patients based on primary key
		$app->delete('/api/patients/{id:[0-9]+}', function($id) use($app){
			$phql = "DELETE FROM Patients WHERE id = :id:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status'=> 'OK'));
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

		//deletes reports based on primary key
		$app->delete('/api/reports/{id:[0-9]+}', function($id) use($app){
			$phql = "DELETE FROM Reports WHERE id = :id:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status'=> 'OK'));
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

		//deletes diseases based on primary key
		$app->delete('/api/diseases/{id:[0-9]+}', function($id) use($app){
			$phql = "DELETE FROM Diseases WHERE id = :id:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status'=> 'OK'));
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

		//deletes guidelines based on primary key
		$app->delete('/api/guidelines/{id:[0-9]+}', function($id) use($app){
			$phql = "DELETE FROM Guidelines WHERE id = :id:";
			$status = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			));

			$response = new Response();

			if($status->success() == true){
				$response->setJsonContent(array('status'=> 'OK'));
			} else {

				$response->setStatusCode(409, "Conflict");

				$errors = array();
				foreach ($status->getMessages() as $message) {
					# code...
					$errors[] = $message->getMessage();
				}

				$response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
			}

			return $response;
		});

	$app->handle();
?>