<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8">
</head>
<body>
<?php
	ini_set('max_execution_time', '0');
    include_once("class.filter.php");
	include_once("class.database.php");

	$data_base = new DataBase();

	for($it = 1; $it <= 35; $it++){
		$file = "Input_file/1 (".$it.").xml";
		echo $it." *** ";
		//Obiekt do parsowania XML
		$dom = new DOMDocument();
		//Wczytaj XML
		$dom->load($file);
		$myfile = fopen("Output_file/1 (".$it.").txt", "w");

		// tekst, który nas interesuje znajduję się pomiędzy znacznikami body
		$article = $dom->getElementsByTagName("body")->item(0);
		if(empty($article)){
			fclose($myfile);
			continue;
		}
		$counter = 0; // licznik cytowań
		foreach ($article->getElementsByTagName("xref") as $xref) {
			if ($xref->getAttribute("ref-type") == "bibr") {
				$counter++;  // inkrementacja liczby cytowań
				$text = $xref->nodeValue;
				$xref->nodeValue = "CYTOWANIE";
				//echo $text."\r\n"; // pomocnicze wyświetlam cytowanie
			}
		}	


		// jeżeli mamy jakiekolwiek cytowanie to wykonujemy poniższy kod, w przeciwnym wypadku zajmujemy się innym plikem
		if($counter > 0){
			// wyrzucamy media - czyli odnośniki do nagrań itp.
			// w przeciwnym wypadku wyświetla nam się komunikat - Click here for additional data file.
			$media = $article->getElementsByTagName("media");
			foreach($media as $m) {
				$text = $m->nodeValue;
				$m->nodeValue = "";
			}

			// pomiędzy znacznikami p znajduję się czysty tekst bez grafik, wykresów, tabel itp.
			$p = $article->getElementsByTagName("p");
			foreach($p as $i) {
				$text = $i->nodeValue;
				$wx = new Filter();
				if($wx->countDigits($text) < 50){
					$text = trim(preg_replace('/\s\s+/', ' ', $text)); // usuwamy przejście do nowej linni
					//echo $text; // podląd w przeglądarce zapisywanego tekstu
					fwrite($myfile, $text);
				}
			}
			// zamykamy plik w którym dokonaliśmy zapisu 
			fclose($myfile);
			$myfile = "Output_file/1 (".$it.").txt";
			if ($handle = fopen($myfile, "r")) {
				$data = fread($handle, filesize($myfile));
				try {
					$pdo = new PDO($data_base->getDSN(), $data_base->getUser(), $data_base->getPassword());
					$statement = $pdo->prepare("INSERT INTO test (text,howManyQuotes) values (?,$counter)");
            		$statement->execute([$data]);
				} catch (PDOException $ex) {
					echo "Błąd";
				}
			}
		}
	}
?>
</body>
</html>

