<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/

//GET
//USUARIO: obtener lista de usuarios
$app->get('/usuario', function($request, $response, $args){

  $query = $this->db->prepare("SELECT * FROM usuario");
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  $response = $response->withStatus(200);
  $response = $response->withHeader("Content-Type","application/json");

  $body = $response->getBody();
  $body->write(json_encode($result));

  return $response;

});

//USUARIO: obtener usuario por id
$app->get('/usuario/{id}', function($request, $response, $args){

  $query = $this->db->prepare("SELECT * FROM usuario WHERE idUsuario=:id");
  $query->execute(array(':id'=>$args["id"]));

  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  if(count($result)>0){
      $response = $response->withStatus(200);
      $body = $response->getBody();
      $body->write(json_encode($result[0]));
  }else{
      $response = $response->withStatus(404);
  }

  $response = $response->withHeader("Content-Type","application/json");


  return $response;

});

//POST
//USUARIO: Insertar Usuario

$app->post('/usuario',function($request,$response,$args){

  $body = $request->getBody();
  $obj = json_decode($body);

  $query = $this->db->prepare("INSERT INTO usuario (nombre,apellido,cedula,fecha_nacimiento, imagen) VALUES (:n,:a,:c,:f,:i)");
  $state = $query->execute(array(':n'=>$obj->nombre, ':a'=>$obj->apellido
    , ':c'=>$obj->cedula, ':f'=>$obj->fechaNacimiento,':i'=>$obj->imagen));

  $rta = "";
  if($state){
    $response = $response->withStatus(200);
    $rta = json_encode(array('state'=>'ok'));
  } else{
    $response = $response->withStatus(500);
    $rta = json_encode(array('state'=>'fail'));
  }
  $body = $response->getBody();
  $body->write($rta);

  $response = $response->withHeader("Content-Type","application/json");

  return $response;

});
