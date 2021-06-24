<?php
session_start();
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		function get_data() {
			$uri = 'http://api.football-data.org/v2/competitions/EC/matches/?matchday=2';
			$reqPrefs['http']['method'] = 'GET';
			$reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
			$stream_context = stream_context_create($reqPrefs);
			$response = file_get_contents($uri, false, $stream_context);
			$matches = json_decode($response);
			$cont=0;
			$vettore = array();
			foreach ($matches->matches as $match){
			$cont++;
			if(isset($_POST["match_".$cont])){
			array_push($vettore, $_POST["match_".$cont]);
			}
			else{
				array_push($vettore, "?");
			}
		}
		return json_encode($vettore);
 }
}

		$name = $_SESSION["username"];
		$file_name ='../privato/risultati/' . $name . '.json';

		if(file_put_contents("$file_name", get_data())) {
				header("location: /privato/index.php");
			}
		else {
			echo 'Errore, contattare Federico';
		}
