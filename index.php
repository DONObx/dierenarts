<?php

include 'functies.php';

$conn = maakConnectie();

$arrData = maakArray1($conn);
//print_r($arrData);

$idCurrentDier = NULL;
if(isset($_GET['idCurrentDier'])){
	$idCurrentDier = $_GET['idCurrentDier'];
}

$actie = NULL;

if(isset($_GET['actie'])){
	$actie = $_GET['actie'];
	$_GET['actie'] = NULL;
}

if($idCurrentDier != NULL && $actie=="updateDier"){
	$sql = "UPDATE dieren, eigenaars, aandoeningen, behandelingen SET 
	dieren.naam = '{$_GET['naam']}', 
	eigenaars.eigenaar_naam = '{$_GET['eigenaar']}', 
	aandoeningen.ziekte = '{$_GET['aandoening']}', 
	aandoeningen.beschrijving_ziekte = '{$_GET['beschrijving']}',
	behandelingen.datum_behandeling =' {$_GET['datum']}',
	behandelingen.behandeling =' {$_GET['behandeling']}'
	WHERE id = $idCurrentDier";
	if ($conn->query($sql) === TRUE) {
	  $arrData = maakArray1($conn);
	} else {
	  echo "Error updating record: " . $conn->error;
	}
}elseif(isset($_GET['naam']) && $actie=="newDier"){
	$sql = "INSERT INTO dieren (naam) VALUES ('{$_GET['naam']}')
			INSERT INTO eigenaars (fullname) VALUES ('{$_GET['eigenaar']}')
			INSERT INTO aandoeningen (ziekte, beschrijving_ziekte) VALUES ('{$_GET['aandoening']}', '{$_GET['beschrijving']}')
			INSERT INTO behandelingen (datum_behandeling, behandeling) VALUES ('{$_GET['datum']}', '{$_GET['behandeling']}')";

	if ($conn->query($sql) === TRUE) {
	  $idCurrentDier = $conn->insert_id;
	  $arrData = maakArray1($conn);
	} else {
	  echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

?>
<!doctype html>
<html lang="nl">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/styles.css">

    <title>Dier</title>
  </head>
  <body>
	<form method="GET">
		<input type="hidden" name="actie" value="">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1>DIER</h1>
				</div>
			</div>
			<hr>
			<?php print kiesData($arrData,$idCurrentDier); ?>
			<?php print formData($arrData,$idCurrentDier); ?>
			<div class="btn-group">
			  <a href="wijzigDier.php" class="btn btn-primary"><i class='fa fa-plus'></i> Voeg nieuw dier</a>
			  <a href="wijzigEig.php" class="btn btn-primary"><i class='fa fa-plus'></i> Voeg nieuw eigenaar</a>
			  <a href="dier_eigenaar.php" class="btn btn-primary"><i class='fa fa-plus'></i> combineer dier en eigenaar</a>
			</div>
		</div>
	</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>