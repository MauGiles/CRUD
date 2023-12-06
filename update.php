<?php
// Archivo de configuración
require_once "config.php";
 
$name = "";
$address = "";
$grade = "";
$name_err = "";
$address_err = "";
$grade_err = "";
 
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Por favor ingrese un nombre.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Por favor ingrese un nombre válido.";
    } else{
        $name = $input_name;
    }
    
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Por favor ingrese una dirección.";     
    } else{
        $address = $input_address;
    }
    
    $input_grade = trim($_POST["grade"]);
    if(empty($input_grade)){
        $grade_err = "Por favor ingrese la calificación.";     
    } elseif(!ctype_digit($input_grade)){
        $grade_err = "Por favor ingrese un valor positivo del 0 al 10.";
    } else{
        $grade = $input_grade;
    }
    
    if(empty($name_err) && empty($address_err) && empty($grade_err)){
        $sql = "UPDATE students SET name=?, address=?, grade=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_address, $param_grade, $param_id);
            
            $param_name = $name;
            $param_address = $address;
            $param_grade = $grade;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "Algo salió mal, intenta otra vez.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM students WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $name = $row["name"];
                    $address = $row["address"];
                    $grade = $row["grade"];
                } else{
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Algo salió mal, intenta otra vez.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
        mysqli_close($link);
    }  else{
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar</title>
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
                        <h2>Actualizar</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Direccion</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($grade_err)) ? 'has-error' : ''; ?>">
                            <label>Calificación</label>
                            <input type="text" name="grade" class="form-control" value="<?php echo $grade; ?>">
                            <span class="help-block"><?php echo $grade_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>