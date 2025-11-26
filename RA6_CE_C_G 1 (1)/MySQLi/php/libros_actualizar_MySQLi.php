<!DOCTYPE html>
<html>
<head>
	<title>Ejercicio 8</title>
	<link rel="stylesheet" media="screen" href="css/estilo.css" >
</head>
<body>

	<?php
	   ini_set("display_errors",true);
		require_once 'funcionesBaseDatos.php';
		session_start();
		if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"]==null){
		    header("Location: login_MySQLi.php");
		}
		if(isset($_POST["actualizar"]))
		{
		    //echo "Actualizando...";
		    $librosanyos = $_POST["librosanyos"];
			$anyo_edicion = $_POST["anyo_edicion"];
			/*echo "----------------------s";
			print_r($librosanyos);
			print_r($anyo_edicion);
			echo "----------------------------";*/
			modificarLibroAnyo_MySQLi($librosanyos, $anyo_edicion);
			echo "<div class='aviso'>Actualizados los anyos</div>";
		}
		else{
		   //echo "NO recibo campo 'actualizar'";
		}
	?>

	<form class="formulario" action="" method="post" name="formulario">
	    <ul>
		    <li>
		         <h2>Libros que se van a actualizar</h2>
		         <span class="mensaje_obligatorio">* Campo obligatorio</span>
		    </li>

		    <li>
		        <label for="libro">Libros:*</label>
		        <select name="libro">
		            <?php
						$libros = getLibrosTitulo_MySQLi();
						foreach ($libros as $libro) 
						{
						    echo "<option value='$libro'";
						    //Si se ha recibido el libro y coincide con el que estamos mostrando
						    //ponemos selected a true
						    if (isset($_POST['libro']) && $libro == $_POST['libro'])
                        	    echo " selected='true'";

						    echo ">$libro</option>";
						}
		    		?>
		        </select>
		    </li>

		    <li>
		        <button class="submit" type="submit" name="mostrar">Mostrar</button>
		    </li>
		</ul>
	</form>

	
		<?php
			// Comprobamos si tenemos que mostrar los libros
		    if (isset($_POST['mostrar'])) 
		    {
		?>
		<form id="actualizar" method="post" action="">
		<table class="tabla">
		<thead>
			<tr>
				<th>Titulo</th>
				<th>Anyo Edicion</th>
			</tr>
		</thead>
		<tbody>
			<?php
		        
		        $libro = $_POST['libro'];
		        //Obtiene un array con toda la información del libro que coincide con el título
		        $librosanyos = getLibrosAnyo_MySQLi($libro);
		        foreach ($librosanyos as $libroanyo) 
		        {
		            //Para que se mantenga el valor del libro al recargar la pÃ¡gina
		        	echo "<input type='hidden' name='libro' value='{$_POST['libro']}'>"; 
		        	//Para que se mantenga el número de ejemplar al dar F5
		        	echo "<tr>"."<input type='hidden' name='librosanyos[]' value='{$libroanyo['numero_ejemplar']}'>";
		        	echo "<td>".$libroanyo["titulo"]."</td>";
		        	//Aquí el valor del campo anyo es el que tenía el libro y al pulsar en submit se modifica.
		        	echo "<td><input type='text' size='4' name='anyo_edicion[]' value='{$libroanyo['anyo_edicion']}'> Anyos </td></tr>";
		        }
			?>
		</tbody>
	</table>
		<button class="submit actualizar" type="submit" name="actualizar">Actualizar</button>
	</form>
	
		<?php
		    }
		?>		
		<br>
	<a href="indice_MySQLi.php">Volver</a>
	
</body>
</html>