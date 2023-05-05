<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,PATCH");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "db4free.net"; $usuario = "chabola"; $contrasenia = "lachabola"; $nombreBaseDatos = "vue_project";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);


// Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
if (isset($_GET["id_usuario"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM usuarios WHERE id=".$_GET["id_usuario"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//borrar pero se le debe de enviar una clave ( para borrado )
if (isset($_GET["borrar"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"DELETE FROM usuarios WHERE id=".$_GET["borrar"]);
    if($sqlEmpleaados){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//Inserta un nuevo registro y recepciona en método post los datos de nombre y correo
if(isset($_GET["insertar"])){
    echo "INSERTAR";
    $crear = json_decode(file_get_contents("php://input"));
    var_dump($crear);
    
    $userName=$crear->usuario;
    $name=$crear->nombre;
    $lastname=$crear->apellido;
    $mail=$crear->email;
    $tlf=$crear->telefono;
    $city=$crear->ciudad;
    $address=$crear->direccion;
    $number=$crear->numero;
    $zip=$crear->codigo_postal;

    var_dump($userName);
    
    if(($userName!="")){
        $stmt = mysqli_prepare( $conexionBD, "INSERT INTO usuarios (usuario, nombre, apellido, email, telefono, ciudad, direccion, numero, codigo_postal)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssissii", $userName, $name, $lastname, $mail, $tlf, $city, $address, $number, $zip);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexionBD);
        }
        
        // Close the database connection
        mysqli_close($conexionBD);

        /*echo "LLeno\n";
        $sqlEmpleaados = mysqli_query($conexionBD,"INSERT INTO usuarios (usuario) VALUES('$userName') ");
        var_dump($sqlEmpleaados);
        echo json_encode(["success"=>1]);*/
        
    }else{
        echo "EMPTY";

    }
    exit();
}
// Actualiza datos pero recepciona datos de nombre, correo y una clave para realizar la actualización
if(isset($_GET["actualizar"])) {
    
    echo "Actualizar";
    $editar = json_decode(file_get_contents("php://input"));
    var_dump($editar);
    $id=(isset($editar->id))?$editar->id:$_GET["actualizar"];
    $userNamee=$editar->usuario;
    $namee=$editar->nombre;
    $lastnamee=$editar->apellido;
    $maill=$editar->email;
    $tlff=$editar->telefono;
    $cityy=$editar->ciudad;
    $addresss=$editar->direccion;
    $numberr=$editar->numero;
    $zipp=$editar->codigo_postal;
    
    var_dump($userName);
    
    if(($maill!="")&&($tlff!="")&&($addresss!="")&&($numberr!="")){
        $sql = "UPDATE usuarios SET usuario='$userNamee', nombre='$namee', apellido='$lastnamee', email='$maill', telefono=$tlff, ciudad='$cityy', direccion='$addresss', numero=$numberr, codigo_postal=$zipp WHERE id= '$id'";
        
        if (mysqli_query($conexionBD, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexionBD);
        }
         // Close the database connection
         mysqli_close($conexionBD);
        }else{
            echo "EMPTY";
    
    exit();
}
}

// Consulta todos los registros de la tabla empleados
$sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM usuarios");
if(mysqli_num_rows($sqlEmpleaados) > 0){
    $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
    echo json_encode($empleaados);
}
else{ echo json_encode([["success"=>0]]); }
?>