<?php
	include 'functies.php';

	$conn = maakConnectie();

	$arrDier = maakArray($conn);

	$idCurrentDier = NULL;
	if(isset($_GET['idCurrentDier'])){
		$idCurrentDier = $_GET['idCurrentDier'];
	}

		if(isset($_GET['naam'])){
            $naam = $_GET['naam'];
        }else{
            $naam = "";
        }
        if(isset($_GET['adres'])){
            $adres = $_GET['adres'];
        }else{
            $adres = "";
        }
        if(isset($_GET['telefoonnummer'])){
            $telefoonnummer = $_GET['telefoonnummer'];
        }else{
            $telefoonnummer = "";
        }
        if(isset($_GET['mail'])){
            $mail = $_GET['mail'];
        }else{
            $mail = "";
        }

        $sql = "INSERT INTO eigenaars (fullname,adres,telefoonnummer,email_adres) VALUES ('$naam','$adres','$telefoonnummer','$mail')";
        $result = $conn->query($sql);
	
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

    <title>Eigenaars</title>
  </head>
  <body>
	<form method="GET" action="eigenaars.php">
		<input type="hidden" name="actie" value="">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1>EIGENAAR</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<h2>Eigenaar</h2>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label for="naam">voor- en achternaam</label>
						<input type="text" class="form-control" id="naam" name="naam">
						<label for="adres">adres</label>
						<input type="text" class="form-control" id="adres" name="adres">
						<label for="telefoonnummer">telefoonnummer</label>
						<input type="text" class="form-control" id="telefoonnummer" name="telefoonnummer">
						<label for="mail">mail</label>
						<input type="text" class="form-control" id="mail" name="mail">
					</div>
					<div class="d-grid gap-2 col-6 mx-auto">
					  <button class="btn btn-primary" type="submit"><i class='fa fa-plus'></i> Voeg nieuw eigenaar</button>
					</div>
				</div>
			</div><hr>
		</div>
	</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>