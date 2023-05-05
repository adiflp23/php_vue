<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$servername = "db4free.net"; 
$username = "chabola";
$password = "lachabola";
$dbname = "vue_project"; 

// Crear la conexión a la base de datos
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión a la base de datos
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

if (isset($_GET["referencia"])){
    $sql = mysqli_query($conn,"SELECT * FROM producto WHERE referencia='".$_GET["referencia"]."'");
    if(mysqli_num_rows($sql) > 0){
        $productos = mysqli_fetch_all($sql,MYSQLI_ASSOC);
        echo json_encode($productos);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}

// Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
if (isset($_GET["consultar"])){
    $sql = mysqli_query($conn,"SELECT P.referencia, P.nombre, P.tipo, T.talla, P.precio, P.cantidad FROM producto P, talla T WHERE P.talla = T.talla");
    if(mysqli_num_rows($sql) > 0){
        $productos = mysqli_fetch_all($sql,MYSQLI_ASSOC);
        echo json_encode($productos);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//borrar pero se le debe de enviar una clave ( para borrado )
if (isset($_GET["borrar"])){
    $sql = mysqli_query($conn,"DELETE FROM producto WHERE referencia=".$_GET["borrar"]);
    if($sql){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//Inserta un nuevo registro y recepciona en método post los datos de nombre y correo
if(isset($_GET["insertar"])){
    $insertar = json_decode(file_get_contents("php://input"));
    $reference=$insertar->referencia;
    $name=$insertar->nombre;
    $tall=$insertar->talla;
    $price=$insertar->precio;
    $quantity=$insertar->cantidad;
        if(($reference!="")){
            
            $sql = "INSERT INTO producto(referencia, nombre, img, talla, precio, cantidad) VALUES('$reference','$name', '',$tall, $price, $quantity)";

            if (mysqli_query($conn, $sql)){
                echo "Producto creado";
            } else {
                echo "Error";
            }

            mysqli_close($conn);
        }
    exit();
}
// Actualiza datos pero recepciona datos de nombre, correo y una clave para realizar la actualización
if(isset($_GET["actualizar"])){

    $editar = new stdClass;
    
    $editar = json_decode(file_get_contents("php://input"));
    var_dump("editar");
    var_dump($editar);
    var_dump($editar->referencia);
    var_dump($editar->precio);
    var_dump($editar->cantidad);

    $reference=(isset($editar->referencia))?$editar->referencia:$_GET["actualizar"];
    $price=$editar->precio;
    $quantity=$editar->cantidad;
    
    $sql = "UPDATE producto SET precio=$price, cantidad=$quantity WHERE referencia='".$reference."'";

    if (mysqli_query($conn, $sql)){
                echo "Producto actualizado";
            } else {
                echo "Error";
            }
    mysqli_close($conn);
    exit();
}
// Consulta todos los registros de la tabla empleados
$sqlEmpleaados = mysqli_query($conn,"SELECT * FROM producto ");
if(mysqli_num_rows($sqlEmpleaados) > 0){
    $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
    echo json_encode($empleaados);
}
else{ echo json_encode([["success"=>0]]); }
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>