<?php
	
	//functions.php
	require("../../config.php");
	//alustan sessiooni, et saaks kasutada
	//$_SESSSION muutujaid
	session_start();
	
	//********************
	//****** SIGNUP ******
	//********************
	//$name = "epals";
	//var_dump($GLOBALS);
	
	$database = "if16_epals";
	
	function signup ($email, $password) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("ss", $email, $password);
		
		if ($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	
	function login($email, $password) {
		
		$error = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("
			SELECT id, email, password, created 
			FROM user_sample
			WHERE email = ?
		");
		echo $mysqli->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran tupladele muutujad
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//küsin rea andmeid
		if($stmt->fetch()) {
			//oli rida
		
			// võrdlen paroole
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb) {
				
				echo "kasutaja ".$id." logis sisse";
				
				
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDb;
				
				//suunaks uuele lehele
				header("Location: data.php");
				
			} else {
				$error = "parool vale";
			}
			
		
		} else {
			//ei olnud 
			
			$error = "sellise emailiga ".$email." kasutajat ei olnud";
		}
		
		
		return $error;
		
		
	}
	
	

	function savePeople ($gender, $color) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO clothingOnTheCampus (gender, color) VALUES (?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("ss", $gender, $color);
		
		if ($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	function getAllPeople () {
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		$stmt = $mysqli->prepare("
			SELECT id, gender, color, created
			FROM clothingOnTheCampus
		");
		echo $mysqli->error;
		
		$stmt->bind_result($id, $gender, $color, $created);
		$stmt->execute();
		//php massiv on array("Elise", "R")
		$result = array();
		//seni kuni on üks rinda andmeid saada (10 rida = 10 korda)
		while ($stmt->fetch()) {
			
			$person = new StdClass();
			$person->id = $id;
			$person->gender = $gender;
			$person->color = $color;
			$person->created = $created;
			//echo $color."<br>";
			array_push($result, $person);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	
	
	
	
	
	
	
	/*function sum ($x, $y) {
		
		return $x + $y;
		
	}
	
	function hello ($firstname, $lastname) {
		
		return "Tere tulemast ".$firstname." ".$lastname."!";
		
	}
	
	echo sum(5476567567,234234234);
	echo "<br>";
	$answer = sum(10,15);
	echo $answer;
	echo "<br>";
	echo hello ("Elise", "E.");
	*/
	

?>