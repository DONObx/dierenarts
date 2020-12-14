<?php

	function maakConnectie(){
		//connectie met databank
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "dierenarts";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $database);

		// Check connection
		if ($conn -> connect_error) {
			die("Connection failed: " . $conn -> connect_error);
		}
		return $conn;
	}

	//Array van dier maken
	function maakArray1($conn){
		//data selecteren
		$sql = "SELECT * FROM dieren";
		$result = $conn->query($sql);
		$arrData = array();
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$arrData[$row["ID"]]['naam'] = $row["naam"];
				$arrData[$row["ID"]]['geboortedatum'] = $row["geboortedatum"];
				$arrData[$row["ID"]]['diersoort'] = $row["diersoort"];
				$arrData[$row["ID"]]['ras'] = $row["ras"];
				$sqlEigenaar = "SELECT 
				eigenaars.ID as id_eigenaar,
				eigenaars.fullname as eigenaar_naam
				FROM dier_eigenaar
				INNER JOIN eigenaars 
				ON dier_eigenaar.id_eigenaar=eigenaars.ID
				WHERE dier_eigenaar.id_dier =".$row["ID"];

				$sqlAandoening = "SELECT 
				aandoeningen.ID as id_aandoening,
				aandoeningen.ziekte as ziekte,
				aandoeningen.beschrijving as beschrijving_ziekte
				FROM dier_aandoening
				INNER JOIN aandoeningen
				ON dier_aandoening.id_aandoening=aandoeningen.ID
				WHERE dier_aandoening.id_dier =".$row["ID"];

				$sqlBehandeling = "SELECT
				behandelingen.ID as id_behandeling,
				behandelingen.datum as datum_behandeling,
				behandelingen.behandeling as behandeling
				FROM aandoeningen
				INNER JOIN behandelingen
				ON aandoeningen.ID=behandelingen.id_ziekte
				WHERE behandelingen.id_dier =".$row["ID"];
				
				$rstEigenaar = $conn->query($sqlEigenaar);
				if ($rstEigenaar->num_rows > 0) {
					while($rowEigenaar = $rstEigenaar->fetch_assoc()) {
						$arrData[$row["ID"]]['eigenaars'][$rowEigenaar["id_eigenaar"]] = array(
							"eigenaar_naam" => $rowEigenaar["eigenaar_naam"]);
					}
				}

				$rstAandoening = $conn->query($sqlAandoening);
				if ($rstAandoening->num_rows > 0) {
					while($rowAandoening = $rstAandoening->fetch_assoc()) {
						$arrData[$row["ID"]]['ziektes'][$rowAandoening["id_aandoening"]] = array(
							"ziekte" => $rowAandoening["ziekte"],
							"beschrijving_ziekte" => $rowAandoening["beschrijving_ziekte"]);
					}
				}

				$rstBehandeling = $conn->query($sqlBehandeling);
				if ($rstBehandeling->num_rows > 0) {
					while($rowBehandeling = $rstBehandeling->fetch_assoc()) {
						$arrData[$row["ID"]]['behandelingen'][$rowBehandeling["id_behandeling"]] = array(
							"datum_behandeling" => $rowBehandeling["datum_behandeling"],
							"behandeling" => $rowBehandeling["behandeling"]);
					}
				}
			}
			
		} else {
			echo "0 results";
		}
		return $arrData;

	}

	//Dropdown om mijn dier te selecteren
	function kiesData($arrData,$idCurrentDier){
		$returnString = "<div class='row'>
				<div class='col-12'>
					<div class='form-group'>
						<label for='idCurrentDier'>Kies een dier</label>
						<select class='form-control' id='idCurrentDier' name='idCurrentDier' onchange='this.form.submit()'>
							<option value=''>---NIEUW DIER---</option>";
		foreach($arrData as $key => $value){
			$selected = NULL;
			if($key == $idCurrentDier){
				$selected = "SELECTED";
			}
			  $returnString .="
							<option value='$key' $selected >{$value['naam']}</option>";
		}
		$returnString .= "
						</select>
					</div>
				</div>
			</div>
			<hr>";
		return $returnString;
	}

	//Dromdown met de consult van mijn dier
	function formData($arrData,$idCurrentDier){
		$returnString = NULL;
		if($idCurrentDier != NULL){
			$returnString = PHP_EOL . "
			<div class='row'>
				<div class='col-12'>
					<h2>Dier</h2>
				</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='naam'>naam</label>
						<input type='text' class='form-control' id='naam' name='naam' value='{$arrData[$idCurrentDier]['naam']}'>
					</div>
					<div class='form-group'>
						<label for='geboortedatum'>geboortedatum</label>
						<input type='date' class='form-control' id='geboortedatum' name='geboortedatum' value='{$arrData[$idCurrentDier]['geboortedatum']}'>
					</div>
				</div>	
				<div class='col-6'>
					<div class='form-group'>
						<label for='diersoort'>diersoort</label>
						<input type='text' class='form-control' id='diersoort' name='diersoort' value='{$arrData[$idCurrentDier]['diersoort']}'>
					</div>
					<div class='form-group'>
						<label for='ras'>ras</label>
						<input type='text' class='form-control' id='ras' name='ras' value='{$arrData[$idCurrentDier]['ras']}'>
					</div>
				</div>
				<div class='col-12'>
					<h2>Eigenaars</h2>
				</div>
				<div class='col-6'>
					<div class='form-group'>";
					foreach ($arrData[$idCurrentDier]['eigenaars'] as $idEigenaar => $gegvensEigenaar) {
						$returnString .= "<label for='eigenaar_naam'>naam</label>
						<input type='text' class='form-control' id='eigenaar$idEigenaar' name='eigenaar$idEigenaar' value='{$gegvensEigenaar['eigenaar_naam']}'>";
					};
				$returnString .= "</div>
				</div>
				<div class='col-12'>
					<h2>Ziekte</h2>
				</div>
				<div class='col-6'>
					<div class='form-group'>";
					foreach ($arrData[$idCurrentDier]['ziektes'] as $idZiekte => $gegvensZiekte) {
						$returnString .= "<label for='ziekte'>aandoening</label>
						<input type='text' class='form-control' id='aandoening$idZiekte' name='aandoening$idZiekte' value='{$gegvensZiekte['ziekte']}'>
						<label for='beschrijving_ziekte'>beschrijving</label>
						<input type='text' class='form-control' id='beschrijving$idZiekte' name='beschrijving$idZiekte' value='{$gegvensZiekte['beschrijving_ziekte']}'>";
					};
					$returnString .= "</div>
				</div>
				<div class='col-12'>
					<h2>Behandeling</h2>
				</div>
				<div class='col-10'>
					<div class='form-group'>";
					foreach ($arrData[$idCurrentDier]['behandelingen'] as $idBehandeling => $gegvensBehandeling) {
						$returnString .= "<label for='datum_behandeling'>datum</label>
						<input type='date' class='form-control' id='datum$idBehandeling' name='datum$idBehandeling' value='{$gegvensBehandeling['datum_behandeling']}'>
						<label for='behandeling'>behandeling</label>
						<input type='text' class='form-control' id='behandeling$idBehandeling' name='behandeling$idBehandeling' value='{$gegvensBehandeling['behandeling']}'>";
					};
					$returnString .= "</div>
				</div>
			</div><hr>";
			
		}else{
			$returnString = PHP_EOL . "<div class='row'>
			<div class='col-12'>
				<h2>Dier</h2>
			</div>
			<div class='col-6'>
				<div class='form-group'>
					<label for='naam'>naam</label>
					<input type='text' class='form-control' id='naam' name='naam' value=''>
				</div>
				<div class='form-group'>
					<label for='geboortedatum'>geboortedatum</label>
					<input type='date' class='form-control' id='geboortedatum' name='geboortedatum' value=''>
				</div>
			</div>	
			<div class='col-6'>
				<div class='form-group'>
					<label for='diersoort'>diersoort</label>
					<input type='text' class='form-control' id='diersoort' name='diersoort' value=''>
				</div>
				<div class='form-group'>
					<label for='ras'>ras</label>
					<input type='text' class='form-control' id='ras' name='ras' value=''>
				</div>
			</div>
			<div class='col-12'>
				<h2>Eigenaar</h2>
			</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='eigenaar_naam'>naam</label>
						<input type='text' class='form-control' id='eigenaar' name='eigenaar' value=''>
					</div>
				</div>
			<div class='col-12'>
				<h2>Ziekte</h2>
			</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='ziekte'>aandoening</label>
						<input type='text' class='form-control' id='aandoening' name='aandoening' value=''>
						<label for='beschrijving_ziekte'>beschrijving</label>
						<input type='text' class='form-control' id='beschrijving' name='beschrijving' value=''>
					</div>
				</div>
			<div class='col-12'>
				<h2>Behandeling</h2>
			</div>
				<div class='col-10'>
					<div class='form-group'>
					<label for='datum_behandeling'>datum</label>
						<input type='date' class='form-control' id='datum' name='datum' value=''>
						<label for='behandeling'>behandeling</label>
						<input type='text' class='form-control' id='behandeling' name='behandeling' value=''>
					</div>
				</div>
		</div><hr>";
		}
		return $returnString;
	}

	//Maak de knoppen onderaan het formulier
	function buttonBar($idCurrentDier){
		$returnString = NULL;
		if($idCurrentDier==NULL){
			//Knoppen voor een nieuw dier
			$returnString .="
			<div class='row'>
				<div class='col-md-12 text-center'>
					<div class='btn-group' role='group'>
					  <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='newDier'; this.form.submit()\"><i class='fa fa-plus'></i> Maak nieuw dier</button>
					  <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
					</div>
				</div>
			</div>";
		}else{
			//Knoppen voor een bestaand dier
			$returnString .="
			<div class='row'>
				<div class='col-md-12 text-center'>
					<div class='btn-group' role='group'>
					  <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='updateDier'; this.form.submit()\"><i class='fa fa-check'></i> Gegevens actualiseren</button>
					  <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
					</div>
				</div>
			</div>";
		}
		return $returnString;
	}

?>