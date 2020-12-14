<?php
	include 'functies.php';

	$conn = maakConnectie();

$arrData = maakArray1($conn);

	$arrEig = maakArray($conn);
	function maakArray($conn){
		//data selecteren
		$sql = "SELECT * FROM eigenaars";
		$result = $conn->query($sql);
		$arrEig = array();
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$arrEig[$row["ID"]]['fullname'] = $row["fullname"];
				$arrEig[$row["ID"]]['adres'] = $row["adres"];
				$arrEig[$row["ID"]]['telefoonnummer'] = $row["telefoonnummer"];
				$arrEig[$row["ID"]]['email_adres'] = $row["email_adres"];
			}	
		} else {
			echo "0 results";
		}
		return $arrEig;

	}

	$idCurrentDier = NULL;
	if(isset($_GET['idCurrentDier'])){
		$idCurrentDier = $_GET['idCurrentDier'];
	}

	function kiesEigenaar($arrEig,$idCurrentEigenaar){
		$returnString = "<div class='row'>
				<div class='col-12'>
					<div class='form-group'>
						<label for='idCurrentEigenaar'>Kies een eigenaar</label>
						<select class='form-control' id='idCurrentEigenaar' name='idCurrentEigenaar' onchange='this.form.submit()'>
							<option value=''>---EIGENAAR---</option>";
		foreach($arrEig as $key => $value){
			$selected = NULL;
			if($key == $idCurrentEigenaar){
				$selected = "SELECTED";
			}
			  $returnString .="
							<option value='$key' $selected >{$value['eigenaar_naam']}</option>";
		}
		$returnString .= "
						</select>
					</div>
				</div>
			</div>
			<hr>";
		return $returnString;
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
	<form method="GET" action="dieren.php">
		<input type="hidden" name="actie" value="">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1>DIER</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<h2>Dier</h2>
				</div>
				<div class="col-6">
					<div class="form-group">
						<?php print kiesData($arrData,$idCurrentDier); ?>
						<?php print kiesEigenaar($arrEig,$idCurrentEigenaar); ?>
					</div>
					<div class="btn-group">
					  <a href="wijzigDier.php" class="btn btn-primary"><i class='fa fa-plus'></i> Voeg nieuw dier</a>
					  <a href="wijzigEig.php" class="btn btn-primary"><i class='fa fa-plus'></i> Voeg nieuw eigenaar</a>
					  <a href="index.php" class="btn btn-primary"> overzicht dier</a>
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