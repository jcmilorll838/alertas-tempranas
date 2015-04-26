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

		//routes here

		//retrieves all reports
		$app->get('/api/reports', function() use($app){
			$phql = "SELECT * FROM Reports";
			$reports = $app->modelsManager->executeQuery($phql);

			$data = array();
			foreach ($reports as $report ) {
				# code...
				$data[]= array(
						'id' => $report->id,
						'place' => $report->place,
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
						'name' => $user->name,
						'user' => $user->user,
						'rol'=> $user->rol,
					);
			}
			echo json_encode($data);
		});

		//por ahora hasta aqui
		//searches for robots with $name
		$app->get('/api/robots/search/{name}', function($name) use($app){

			$phql = "SELECT * FROM Robots WHERE name like :name: ORDER BY name";
			$robots = $app->modelsManager->executeQuery($phql, array(
				'name' => '%'.$name.'%'
			));

			$data = array();
			foreach ($robots as $robot ) {
				# code...
				$data[]= array(
						'id' => $robot->id,
						'name' => $robot->name,
					);
			}
			echo json_encode($data);
		});

		//retrieves robots based on primary key
		$app->get('/api/robots/{id:[0-9]+}', function($id) use ($app){
			$phql = "SELECT * FROM Robots WHERE id = :id:";
			$robot = $app->modelsManager->executeQuery($phql, array(
				'id' => $id
			))->getFirst();

			$response = new Response();

			if($robot == false) {
				$response->setJsonContent(array('status' => 'NOT FOUND'));
			} else {
				$response->setJsonContent(array(
						'status' => 'FOUND',
						'data' => array(
							'id' => $robot->id,
							'name' => $robot->name	
						)
					));
			}
			return $response;
		});

		//adds a new user 
		$app->post('/api/users', function() use($app){
			$user = $app->request->getJsonRawBody();

			$phql = "INSERT INTO Users (name, rol, user, password) VALUES (:name:, :rol:, :user:, :password:)";

			$status = $app->modelsManager->executeQuery($phql, array(
					'name' => $user->name,
					'rol' => $user->rol,
					'user' => $user->user,
					'password'=> $user->password
			));

			$response = new Response();

			if($status->success() == true) {

				$response->setStatusCode(201, "Created");

				$user->id = $status->getModel()->id;

				$response->setJsonContent(array('status'=>'OK', 'data'=>$robot));

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

		//updates robots based on primary key
		$app->put('/api/robots/{id:[0-9]+}', function() {

		});

		//deletes robots based on primary key
		$app->delete('/api/robots/{id:[0-9]+}', function() {

		});


	$app->handle();
?>