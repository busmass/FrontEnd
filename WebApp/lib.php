<?php


//Database=as_eb778c54b5aa1fa;Data Source=us-cdbr-azure-central-a.cloudapp.net;User Id=b0a941f833069a;Password=41561c96


function coBdd(){
	$dbName= "as_eb778c54b5aa1fa";
	$userName = "b0a941f833069a";
	$passName = "41561c96";
	$hostname = "us-cdbr-azure-central-a.cloudapp.net" ;
	try{
		//echo "<script>alert('Avant essai de connexion')</script>";
		$bdd = new PDO('mysql:host='.$hostname.';
		dbname='.$dbName.';', $userName, $passName);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $bdd;

	}
	catch (Exception $e){
		//echo "<script>alert('Erreur de connexion')</script>";
		die('Erreur : '. $e->getMessage());
	}
}

function recupData($ligne, $path, $bdd){
	$stack=array();
	$requete = $bdd->query('SELECT nomArret FROM arrets WHERE id_ligne='.$ligne.'');
	$arrets = $requete->fetchAll();
	$requete = $bdd->query('SELECT * FROM bus WHERE id_ligne='.$ligne.' AND path='.$path.'');
	$bus = $requete->fetchAll();
	$nb = count($arrets);
	$match = false;

	for ($i = 0; $i < $nb; $i++) {
		$match = false;
		print "
		<tr>
		<td class='destination'>".$arrets[$i]['nomArret']." </td>";
		for ($j = 0; $j < count($bus); $j++) {
			if ($bus[$j]['arret'] == $i) {
				$match = true;
				$poids = $bus[$j]['poids'];
				$poids /= 10;
				$poids -= 100;
				$poids = (int)abs($poids);

				if ($poids >= 75) {
					$image = "img/barre_rouge.png";
				} else if ($poids > 50 && $poids <= 75) {
					$image = "img/barre_orange.png";
				} else if ($poids > 25 && $poids <= 50) {
					$image = "img/barre_jaune.png";
				} else if ($poids <= 25) {
					$image = "img/barre_verte.png";
				}
				$icon = "<i class='fa fa-circle fa-3x'></i>";
				$poids .= "% full";

				print "<td class='bubble'>".$icon."</td>
				<td class='destination'><img src='".$image."' /></td>
				<td class='destination'>".$poids."</td>";
				break;
			}
		}
		if (!$match) {
			print "<td class='bubble'><i class='fa fa-circle-o fa-3x'></i></td>
			<td class='destination'></td>
			<td class='destination'></td>";
		}
		print "</tr>";
	}
}

function viewChoiceLine($bdd){
	echo 'Choose your line :';
	$requete = $bdd->query('SELECT * FROM Ligne ');
	$stack = array( );




	while ($donnees = $requete->fetch()){
		array_push($stack, $donnees['num_ligne'].' '.$donnees['Chemin']);
	}
	echo '<form method="post" action="emakina.php">';

	// Variable qui ajoutera l'attribut selected de la liste déroulante
	$selected = '';

	// Parcours du tableau
	echo '<select name="line" action="emakina.php">',"n";
	foreach($stack as $valeurHexadecimale => $lignePath)
	{

		// Affichage de la ligne
		echo "\t",'<option value="', $lignePath ,'"', $selected ,'>', $ligne ,'</option>',"\n";
		// Remise à zéro de $selected
		$selected='';
	}
	echo '</select>',"\n";
	echo '
	<input type="submit" value="Submit"/>
	</form> ';
}

function viewChoiceLine2($bdd){
	echo 'Choose your line :';
	$requete = $bdd->query('SELECT * FROM Ligne ');
	$stack = array();

	echo '<form method="post" action="emakina.php">';
	echo '<select name="line" action="emakina.php">',"n";
	while ($donnees = $requete->fetch()){
		echo '<option value="'.$donnees['idLigne'].'">'.$donnees['num_ligne'].'</option>',"\n";
	}
	echo '</select>';
	echo '
	<input type="submit" value="Submit"/>
	</form> ';
}

function viewChoicePath($bdd, $line){
	echo 'Choose your path :';
	$requete = $bdd->query('SELECT * FROM Ligne WHERE idLigne = '.$line.' ');
	$stack = array();

	echo '<form method="post" action="emakina.php?line='.$line.'">';
	echo '<select name="path" action="emakina.php?line='.$line.'">',"n";
	while ($donnees = $requete->fetch()){
		echo '<option value=0>'.$donnees['depart'].' - '.$donnees['terminus'].'</option>',"\n";
		echo '<option value=1>'.$donnees['terminus'].' - '.$donnees['depart'].'</option>',"\n";
	}
	echo '</select>';
	echo '
	<input type="submit" value="Submit"/>
	</form> ';
}


function getLines($bdd){
	$requete = $bdd->query('SELECT * FROM Ligne ');
	$lines = array();
	while ($donnees = $requete->fetch()){
		$lines[] = $donnees;
	}
	return $lines;
}

function viewLines($lines){
	$nb=count($lines);
	echo '<form method="post" action="emakina.php">';
	echo '<select name="line" action="emakina.php">',"n";
	for ($i=0; $i < $nb ; $i++) {
		echo '<option value="'.$lines[$i]['idLigne'].'">'.$lines[$i]['num_ligne'].'</option>',"\n";
	}
	echo '</select>';
	echo '
	<input type="submit" value="Submit"/>
	</form> ';
}

function viewLines2($lines){
	$nb=count($lines);
	for ($i=0; $i < $nb ; $i++) {
		$mod=$i % 5;
		$mod++;
		$class = ($i & 1) ? "buttonLine" : "buttonLine2";

		echo '	<tr>
		<td class="number'.$mod.'" >'.$lines[$i]['num_ligne'].'</td>
		<td class="'.$class.'" data-toggle="collapse" data-target="#demo'.$i.'">'.$lines[$i]['depart'].'  -<span class="chevron"><i class="chevron fa fa-chevron-right"></i></span></br> '.$lines[$i]['terminus'].'</td>
		</tr>';
		echo '<tr id="demo'.$i.'" class="collapse ">
		<td></td>
		<td>
		<table>
		<tr>
		<td class="under_buttonLine" ><a href="index.php?line='.$lines[$i]['idLigne'].'&path=0">'.$lines[$i]['depart'].'</td>

		</tr>
		<tr>
		<td class="under_buttonLine " ><a href="index.php?line='.$lines[$i]['idLigne'].'&path=1">'.$lines[$i]['terminus'].'</td>
		</tr>
		</table>
		</tr>';
	}
	//<td class="btn btn-default btn-block destination" onclick="function_js_ligne('.json_encode($lines[$i]).')">'.$lines[$i]['depart'].'  -</br> '.$lines[$i]['terminus'].'</td>

}

function getPaths($bdd, $line){
	$requete = $bdd->query('SELECT * FROM Ligne WHERE idLigne = '.$line.' ');
	$donnee = $requete->fetch();
	$path = array();
	array_push($path, $donnee['depart']);
	array_push($path, $donnee['terminus']);

	return $path;
}

function viewPat($path, $line){
	//$nb=count($lines);
	echo 'Choose your path :';
	echo '<form method="post" action="index.php?line='.$line.'">';
	echo '<select name="path" action="index.php?line='.$line.'">',"n";

	echo '<option value=0>'.$path[0].' - '.$path[1].'</option>',"\n";
	echo '<option value=1>'.$path[1].' - '.$path[0].'</option>',"\n";

	echo '</select>';
	echo '
	<input type="submit" value="Submit"/>
	</form> ';

}

function viewPath($path, $line){
	echo '<tr>
	<td class="buttonLine" ><a href="index.php?line='.$line.'&path=0">'.$path[0].'  -</br> '.$path[1].'</a></td>
	<td class="buttonLine" ><a href="index.php?line='.$line.'&path=1">'.$path[1].'  -</br> '.$path[0].'</a></td>
	</tr>';
}




function viewConection(){
	echo" <form method='post' action='emakina.php'>
	<label for='pseudo'>Pseudo* : </label>";
	echo "<label for='pass'>Mot de passe* : </label>
	<input type='password' name='pass' id='pass' placeholder='&#9679;&#9679;&#9679;&#9679;&#9679;'' size='30' maxlength='15' required />
	<br/>	";
	echo "input type='submit' value='Login'/>
	</form>";
}

function conection($bdd,$pseudo, $pass){
	$requete = $bdd->query('SELECT * FROM Users WHERE pseudo = '.$pseudo.' ');
	if ($pass==$requete['pass']) {
		return true;
	}
	else{
		return false;
	}
}

function signUp($bdd,$pseudo, $pass, $mail){
	$requete = $bdd->query('SELECT * FROM Users ');
	while ($donnees = $requete->fetch()){
		if ($donnees['pseudo']==$pseudo) {
			return false;
		}
	}
	$pass=md5($pass);
	$requete=$bdd->prepare('INSERT INTO Users SET pseudo=?, pass=? , mail=?');
	$requete->execute(array($pseudo,$pass,$mail));
	return true;

}


function likeCom($bdd, $pseudo){
	$requete = $bdd->query('SELECT score FROM Users WHERE pseudo='.$pseudo.' ');
	$donnee = $requete->fetch();
	$newScore= $donnee['score'] + 1;
	$requete=  $bdd->query('UPDATE `as_eb778c54b5aa1fa`.`Users` SET `score`='.$id.' WHERE `pseudo`= '. $pseudo . '');
}

function getScore($bdd, $pseudo){
	$requete = $bdd->query('SELECT score FROM Users WHERE pseudo='.$pseudo.' ');
	$donnee = $requete->fetch();
	return $donnee['score'];
}

?>
