<?php

	/* Conectar a una base de datos ODBC invocando al controlador */
	$datosDB = 'mysql:dbname=blogs;host:127.0.0.1';
	$usuario = 'bloguser';
	$password = 'blog_252';
	
	try {
		$gbd = new PDO($datosDB, $usuario, $password);
	} catch (PDOException $e){
		echo 'Fall� la conexi�n: ' . $e->getMessage();
	}
	
	/*---------------------------*/
	/*
	Cuenta el n�mero de filas afectadas por una sentencia DELETE sin cl�usula WHERE.
	*/
	$dbh = new PDO('odbc:sample', 'db2inst1', 'ibmdb2');

	/* Borra todas las filas de la tabla FRUIT */
	$count = $dbh->exec("DELETE FROM fruit WHERE colour = 'red'");

	/* Devuelve el n�mero de filas borradas */
	print("Deleted $count rows.\n");
	/*
	El resultado del ejemplo ser�a:

	Deleted 1 rows.
	*/
	/*---------------------------*/
	
	/*
	Valores devueltos

	Una llamada con �xito devuelve el valor del atributo PDO solicitado. Una llamada fallida devuelve null.

	Ejemplos

	Ejemplo #1 Obtiene los atributos de la conexi�n a la base de datos
	*/
	
	$conn = new PDO('odbc:sample', 'db2inst1', 'ibmdb2');
	$attributes = array(
		"AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
		"ORACLE_NULLS", "PERSISTENT", "PREFETCH", "SERVER_INFO", "SERVER_VERSION",
		"TIMEOUT"
	);

	foreach ($attributes as $val) {
		echo "PDO::ATTR_$val: ";
		echo $conn->getAttribute(constant("PDO::ATTR_$val")) . "\n";
	}
	
	/*---------------------------*/
	
	/*
	Ejemplo #1 Demostraci�n de PDO::query

	Una buena caracter�stica de PDO::query() es que permite iterar sobre el conjunto de filas devueltos por una ejecuci�n de una sentencia SELECT con �xito.
	*/
	function getFruit($conn) {
		$sql = 'SELECT name, color, calories FROM fruit ORDER BY name';
		foreach ($conn->query($sql) as $row) {
			print $row['name'] . "\t";
			print $row['color'] . "\t";
			print $row['calories'] . "\n";
		}
	}
	/*
	El resultado del ejemplo ser�a:

	apple   red     150
	banana  yellow  250
	kiwi    brown   75
	lemon   yellow  25
	orange  orange  300
	pear    green   150
	watermelon      pink    90
	*/
	
	/*---------------------------*/
	
	/*
	
	Ejemplo #1 Revertir una transacci�n

	El siguiente ejemplo comienza una transacci�n y ejecuta dos sentencias que modifican la base de datos antes de revertir los cambios. En MySQL, sin embargo, la sentencia DROP TABLE consigna autom�ticamente la transacci�n, por lo que no son revertidos ninguno de los cambios en la transacci�n.
	*/
	
	/* Comenzar una transacci�n, desactivando el modo 'autocommit' */
	$gbd->beginTransaction();

	/* Cambiar el esquema y los datos de la base de datos */
	$sth = $gbd->exec("DROP TABLE fruit");
	$sth = $gbd->exec("UPDATE dessert
		SET name = 'hamburger'");

	/* Reconocer el error y revertir los cambios */
	$gbd->rollBack();

	/* Ahora la conexi�n a la base de datos a vuelto al modo 'autocommit' */
	
	
	/*---------------------------*/
	
	/*
	Ejemplo #1 Devolver el n�mero de filas eliminadas

	PDOStatement::rowCount() devuelve el n�mero de filas afectadas por una sentencia DELETE, INSERT, o UPDATE.
	*/
	
	/* Borrar todas las filas de la tabla FRUIT */
	$del = $gbd->prepare('DELETE FROM fruit');
	$del->execute();

	/* Devolver el n�mero de filas que fueron eliminadas */
	print("Devolver el n�mero de filas que fueron eliminadas:\n");
	$cuenta = $del->rowCount();
	print("Eliminadas $cuenta filas.\n");
	/*
	El resultado del ejemplo ser�a:

	Devolver el n�mero de filas que fueron eliminadas:
	Eliminadas 9 filas.
	*/
	
	/*---------------------------*/
	
	/*
	Ejemplo #2 Contar las filas devueltas por una sentencia SELECT

	Para muchas bases de datos, PDOStatement::rowCount() no devuelve el n�mero de filas afectadas por una sentencia SELECT. En su lugar, use PDO::query() para ejecutar una sentencia SELECT COUNT(*) con las mismas declaraciones que la sentencia SELECT pretendida, luego use PDOStatement::fetchColumn() para recuperar el n�mero de filas que ser�n devueltas. La aplicaci�n podr� entonces realizar la acci�n correcta.

	*/
	$sql = "SELECT COUNT(*) FROM fruit WHERE calories > 100";
	if ($resultado = $conexi�n->query($sql)) {

		/* Comprobar el n�mero de filas que coinciden con la sentencia SELECT */
		if ($resultado->fetchColumn() > 0) {

			/* Ejecutar la sentencia SELECT real y trabajar con los resultados */
			 $sql = "SELECT name FROM fruit WHERE calories > 100";
		   foreach ($conexi�n->query($sql) as $fila) {
			   print "Nombre: " .  $fila['NAME'] . "\n";
			 }
		}
		/* No coincide ningua fila -- hacer algo en consecuencia */
	  else {
		  print "Ninguna fila coincide con la consulta.";
		}
	}

	$resultado = null;
	$conexi�n = null;
	/*
	El resultado del ejemplo ser�a:

	apple
	banana
	orange
	pear
	*/
	
	/*---------------------------*/
	
	/*
	Ejemplo #1 Ejecutar una sentencia preparada con variables vinculadas

	*/
	/* Ejecutar una sentencia preparada vinculando variables de PHP */
	$calor�as = 150;
	$color = 'red';
	$gsent = $gbd->prepare('SELECT name, colour, calories
		FROM fruit
		WHERE calories < :calories AND colour = :colour');
	$gsent->bindParam(':calories', $calor�as, PDO::PARAM_INT);
	$gsent->bindParam(':colour', $color, PDO::PARAM_STR, 12);
	$gsent->execute();
	/*
	Ejemplo #2 Ejecutar una sentencia preparada con un array de valores de inserci�n (par�metros con nombre)

	*/
	/* Ejecutar una sentencia preparada proporcionando un array de valores de inserci�n */
	$calor�as = 150;
	$color = 'red';
	$gsent = $gbd->prepare('SELECT name, colour, calories
		FROM fruit
		WHERE calories < :calories AND colour = :colour');
	$gsent->execute(array(':calories' => $calor�as, ':colour' => $color));
	/*
	Ejemplo #3 Ejecutar una sentencia preparada con un array de valores de inserci�n (par�metros de sustituci�n)

	*/
	/* Ejecutar una sentencia preparada proporcionando un array de valores de inserci�n */
	$calor�as = 150;
	$color = 'red';
	$gsent = $gbd->prepare('SELECT name, colour, calories
		FROM fruit
		WHERE calories < ? AND colour = ?');
	$gsent->execute(array($calor�as, $color));
	/*
	Ejemplo #4 Ejecutar una senetencia preparada con par�metros de sustituci�n de signos de interrogaci�n

	*/
	/* Ejecutar una sentencia preparada vinculando variables de PHP */
	$calor�as = 150;
	$color = 'red';
	$gsent = $gbd->prepare('SELECT name, colour, calories
		FROM fruit
		WHERE calories < ? AND colour = ?');
	$gsent->bindParam(1, $calor�as, PDO::PARAM_INT);
	$gsent->bindParam(2, $color, PDO::PARAM_STR, 12);
	$gsent->execute();
	/*
	Ejemplo #5 Ejecutar una sentencia preparada usando un array para la cl�usula IN

	*/
	/* Ejecutar una sentencia preparada usando un array de valores para una cl�usula IN */
	$par�metros = array(1, 21, 63, 171);
	/* Crear una cadena para los par�metros de sustituci�n rellenados con el n�mero de par�metros */
	$place_holders = implode(',', array_fill(0, count($par�metros), '?'));

	/*
		Esto prepara una sentencia con suficientes par�metros de sustituci�n sin nombre para
		cada valor del array $par�metros. Los valores del array $par�metros son vinculados a
		los par�metros de sustituci�n de la sentencia preparada cuando �sta sea ejecutada.
		Esto no es lo mismo que usar PDOStatement::bindParam(), pues esto �ltimo
		requiere una referencia a la variable. En su lugar, PDOStatement::execute() s�lo
		vincula por valor.
	*/
	$gsent = $gbd->prepare("SELECT id, name FROM contacts WHERE id IN ($place_holders)");
	$gsent->execute($par�metros);
	
	
	
	/*---------------------------*/
	
	/*
	Ejemplo #1 Contar columnas

	Este ejemplo demuestra c�mo PDOStatement::columnCount() opera con y sin un conjunto de resultados.

	*/
	$gbd = new PDO('odbc:sample', 'db2inst1', 'ibmdb2');

	$gsent = $gbd->prepare("SELECT name, colour FROM fruit");

	/* Cuenta el n�mero de columnas del (no existente) conjunto de resultados */
	$cuenta_col = $gsent->columnCount();
	print("Antes de execute(), el conjunto de resultados tiene $cuenta_col columnas (deber�a ser 0)\n");

	$gsent->execute();

	/* Cuenta el n�mero de columnas del conjunto de resultados */
	$cuenta_col = $gsent->columnCount();
	print("Despu�s de execute(), el conjunto de resultados tiene $cuenta_col columnas (deber�a ser 2)\n");

	/*
	El resultado del ejemplo ser�a:

	Antes de execute(), el conjunto de resultados tiene 0 columnas (deber�a ser 0)
	Despu�s de execute(), el conjunto de resultados tiene 2 columnas (deber�a ser 2)
	*/
	
	/*---------------------------*/
	
	/*
	
	*/
	
	/*---------------------------*/
	
	/*
	
	*/
	
	/*---------------------------*/
	
	/*
	
	*/
	
?>