<?php 

class Connection{
	private $server = "mysql:host=localhost;dbname=teste";

	private $user = "root";

	private $pass = "";

	private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

	private static $con;

	public function Conectar(){
		try{
			if(is_null(self::$con)){
				self::$con = new PDO($this->server, $this->user, $this->pass, $this->options);

			}
			return self::$con;	
		}
		catch (PDOException $ex) {
			echo $ex->getMessage();
	} 

}
}

 ?>
