<?php
	include 'functies.php';

	$conn = maakConnectie();

	//Array van eigenaar maken
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

	//Dropdown om mijn eigenaar te selecteren
	function kiesEig($arrEig,$idCurrentEig){
		$returnString = "<div class='row'>
				<div class='col-12'>
					<div class='form-group'>
						<label for='idCurrentEig'>Kies een eigenaar</label>
						<select class='form-control' id='idCurrentEig' name='idCurrentEig' onchange='this.form.submit()'>
							<option value=''>---NIEUW EIGENAAR---</option>";
		foreach($arrEig as $key => $value){
			$selected = NULL;
			if($key == $idCurrentEig){
				$selected = "SELECTED";
			}
			  $returnString .="
							<option value='$key' $selected >{$value['fullname']}</option>";
		}
		$returnString .= "
						</select>
					</div>
				</div>
			</div>
			<hr>";
		return $returnString;
	}

		//Dromdown met de gegevens van mijn eigenaar
	function formEig($arrEig,$idCurrentEig){
		$returnString = NULL;
		if($idCurrentEig != NULL){
			$returnString = PHP_EOL . "
			<div class='row'>
				<div class='col-12'>
					<h2>Eigenaar</h2>
				</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='fullname'>voor- en achternaam</label>
						<input type='text' class='form-control' id='fullname' name='fullname' value='{$arrEig[$idCurrentEig]['fullname']}'>
					</div>
					<div class='form-group'>
						<label for='adres'>adres</label>
						<input type='text' class='form-control' id='adres' name='adres' value='{$arrEig[$idCurrentEig]['adres']}'>
					</div>
				</div>	
				<div class='col-6'>
					<div class='form-group'>
						<label for='telefoonnummer'>telefoonnummer</label>
						<input type='text' class='form-control' id='telefoonnummer' name='telefoonnummer' value='{$arrEig[$idCurrentEig]['telefoonnummer']}'>
					</div>
					<div class='form-group'>
						<label for='email_adres'>email adres</label>
						<input type='text' class='form-control' id='email_adres' name='email_adres' value='{$arrEig[$idCurrentEig]['email_adres']}'>
					</div>
				</div>
			</div><hr>";
			
		}else{
			$returnString = PHP_EOL . "<div class='row'>
				<div class='col-12'>
					<h2>Eigenaar</h2>
				</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='fullname'>voor- en achternaam</label>
						<input type='text' class='form-control' id='fullname' name='fullname' value=''>
					</div>
					<div class='form-group'>
						<label for='adres'>adres</label>
						<input type='text' class='form-control' id='adres' name='adres' value=''>
					</div>
				</div>	
				<div class='col-6'>
					<div class='form-group'>
						<label for='telefoonnummer'>telefoonnummer</label>
						<input type='text' class='form-control' id='telefoonnummer' name='telefoonnummer' value=''>
					</div>
					<div class='form-group'>
						<label for='email_adres'>email adres</label>
						<input type='text' class='form-control' id='email_adres' name='email_adres' value=''>
					</div>
				</div>
			</div><hr>";
		}
		return $returnString;
	}

	//Maak de knoppen onderaan het formulier
	function buttonBarEig($idCurrentEig){
		$returnString = NULL;
		if($idCurrentEig==NULL){
			//Knoppen voor een nieuw eigenaar
			$returnString .="
			<div class='row'>
				<div class='col-md-12 text-center'>
					<div class='btn-group' role='group'>
					  <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='newEig'; this.form.submit()\"><i class='fa fa-plus'></i> Maak nieuw eigenaar</button>
					  <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
					</div>
				</div>
			</div>";
		}else{
			//Knoppen voor een bestaand eigenaar
			$returnString .="
			<div class='row'>
				<div class='col-md-12 text-center'>
					<div class='btn-group' role='group'>
					  <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='updateEig'; this.form.submit()\"><i class='fa fa-check'></i> Gegevens actualiseren</button>
					  <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
					</div>
				</div>
			</div>";
		}
		return $returnString;
	}

$conn = maakConnectie();

$arrEig = maakArray($conn);

$idCurrentEig = NULL;
if(isset($_GET['idCurrentEig'])){
	$idCurrentEig = $_GET['idCurrentEig'];
}

$actie = NULL;

if(isset($_GET['actie'])){
	$actie = $_GET['actie'];
	$_GET['actie'] = NULL;
}

if($idCurrentEig != NULL && $actie=="updateEig"){
	$sql = "UPDATE eigenaars SET 
	fullname = '{$_GET['fullname']}', 
	adres = '{$_GET['adres']}', 
	telefoonnummer = '{$_GET['telefoonnummer']}', 
	email_adres = '{$_GET['email_adres']}'	
	WHERE ID = $idCurrentEig";
	if ($conn->query($sql) === TRUE) {
	  $arrEig = maakArray($conn);
	} else {
	  echo "Error updating record: " . $conn->error;
	}
}elseif(isset($_GET['fullname']) && $actie=="newEig"){
	$sql = "INSERT INTO eigenaars (fullname, adres, telefoonnummer, email_adres)
VALUES ('{$_GET['fullname']}', '{$_GET['adres']}', '{$_GET['telefoonnummer']}', '{$_GET['email_adres']}')";

	if ($conn->query($sql) === TRUE) {
	  $idCurrentEig = $conn->insert_id;
	  $arrEig = maakArray($conn);
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

    <title>Eigenaar</title>
  </head>
  <body>
	<form method="GET">
		<input type="hidden" name="actie" value="">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1>EIGENAAR</h1>
				</div>
			</div>
			<hr>
			<?php print kiesEig($arrEig,$idCurrentEig); ?>
			<?php print formEig($arrEig,$idCurrentEig); ?>
			<?php print buttonBarEig($idCurrentEig) ?>
		</div>
	</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>