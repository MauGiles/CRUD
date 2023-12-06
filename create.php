<?php
// Archivo de configuración
require_once "config.php";
 
// Definición de variables
$name = "";
$address = "";
$grade = "";
$name_err = "";
$address_err = "";
$grade_err = "";
 
// Procesamiento del formulario al enviar
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Verificar el nombre
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Por favor ingrese el nombre.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Por favor ingrese un nombre válido.";
    } else{
        $name = $input_name;
    }
    
    // Verificar dirección
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Por favor ingrese una dirección.";     
    } else{
        $address = $input_address;
    }
    
    // Verificar calificación
    $input_grade = trim($_POST["grade"]);
    if(empty($input_grade)){
        $grade_err = "Por favor ingrese la calificación.";
    } elseif(!ctype_digit($input_grade)){
        $grade_err = "Por favor ingrese un valor correcto del 0 al 10.";
    } else{
        $grade = $input_grade;
    }
    
    // Revisar errores antes de insertar en db
    if(empty($name_err) && empty($address_err) && empty($grade_err)){
        
        $sql = "INSERT INTO students (name, address, grade) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_address, $param_grade);
            
            // Asignamos los parametros
            $param_name = $name;
            $param_address = $address;
            $param_grade = $grade;
            
            // Ejecuta la instancia preparada
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "Algo salio mal, intente de nuevo.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agregar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Agregar</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Dirección</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($grade_err)) ? 'has-error' : ''; ?>">
                            <label>Calificación</label>
                            <input type="text" name="grade" class="form-control" value="<?php echo $grade; ?>">
                            <span class="help-block"><?php echo $grade_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>