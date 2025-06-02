<?php
	
	
	function conectarMysql(){
		try{
			$idCone=mysqli_connect("localhost","root","", "proyecto");
			return $idCone;
		}
		catch(mysqli_sql_exception $e){
			die("Error de conexión ".mysqli_connect_errno()." Motivo: " .mysqli_connect_error());
		}
		catch(exception $e2){
			die("Excepción general desconocida ".$e2->getMessage());
		}		
		catch(error $e3){
			die("Error general desconocido ".$e3->getMessage());
		}
	}
	
?>
