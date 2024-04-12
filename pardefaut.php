﻿<?php

$imgPerso ="";

$url_acces= "https://www.imj-prg.fr/spip.php?rubrique13";
$divers= ' ';

$adresse = "Institut de Mathématiques de Jussieu - Paris Rive Gauche (IMJ-PRG)<br><br>";
$dateDuJour =  date("Y-m-d");
// *****************************************************

$nom_fichier_base="https://www.imj-prg.fr/membres/";
//$nom_fichier_base="C:\wamp64\www\www\www.imj-prg.fr\membres\\";
$nom_fichier_json= $nom_fichier_base.'export-webservice.json';


$url =  explode ('/', $_SERVER['REQUEST_URI']); 

$email_debut=str_replace('~','', $url[1]);
// $email_debut="noemie.combe";

$rep= explode('.',$email_debut);

$prenom= ucfirst($rep[0]);
$nom= ucfirst($rep[1]);
$nom_minuscule = strtolower($nom);
$prenom_minuscule = strtolower($prenom);
$poste='';
$equipe='';
$tel='';
$lieu='';
$adresse='';
$l_fonction='';
$directeurThese='';



$streamContext = stream_context_create([
    'ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false
        ]
    ]);
$organisme='';
$grade='';   

$json = json_decode(file_get_contents($nom_fichier_json), true);

foreach($json as $personne){

for($n=0; $n<count($personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'] ); $n++){
		// debug
	/*if($personne['nomUsage']=='BARILARI'){
		echo $personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['codeUnite']."!<br>";
		echo $personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['dateDebut'] ." < ". $dateDuJour."!<br>";
		 echo $personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['dateFin'] ." > ". $dateDuJour ."!<br>";
		 echo "!".$personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['pisteAudit']['dateSuppressionLogique']."!<br>";
	}*/
	// fin debug
	if($personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['codeUnite'] == 'UMR7586' && 
		$personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['dateDebut'] < $dateDuJour   && 
	( $personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['dateFin']=='' 
		|| $personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['dateFin'] > $dateDuJour )&&
		!isset($personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['pisteAudit']['dateSuppressionLogique'])
	){	
	
		if(strtolower($personne['nomUsage']) == $nom_minuscule && strtolower(suppr_accents($personne['prenom'],'utf-8')) == $prenom_minuscule ) {
			//recup equipeLu
			$equipe = "à définir";
		
		   for($m=0; $m<count($personne['personnelUniteCNRS'][0]['rattachementSousStructure']['rattachementSousStructure'] ); $m++){
			
			if($personne['personnelUniteCNRS'][0]['rattachementSousStructure']['rattachementSousStructure'][$m]['dateDebut'] < $dateDuJour   && 
			( $personne['personnelUniteCNRS'][0]['rattachementSousStructure']['rattachementSousStructure'][$m]['dateFin']=='' 
				|| $personne['personnelUniteCNRS'][0]['rattachementSousStructure']['rattachementSousStructure'][$m]['dateFin'] > $dateDuJour )
			){	
				$equipe = $personne['personnelUniteCNRS'][0]['rattachementSousStructure']['rattachementSousStructure'][$m]['libelleSousStructure'];
			
				break;
			}
		  }
		
			$typePoste = $personne['personnelUniteCNRS'][0]['derniereCategoriePersonnel']['code'];
			$poste= $personne['personnelUniteCNRS'][0]['derniereCategoriePersonnel']['libelle'];
			$statutPoste = $personne['personnelUniteCNRS'][0]['dernierStatutPersonnel']['code'];
			$etat=$personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['telecopie'];
			$raisonProlongation= $personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['motifProlongationDroits']  ;
			if(isset($personne['formation']['theses']['these'][0]['codeEtatThese'])){
				$etatThese= $personne['formation']['theses']['these'][0]['codeEtatThese'];
			}
			if(isset($personne['formation']['theses']['these'][0]['intituleDeLaThese'])){
				$intituleThese= $personne['formation']['theses']['these'][0]['intituleDeLaThese'];
			}
			if(isset($personne['formation']['theses']['these'][0]['directeurDeThese'])){
				$directeurThese= $personne['formation']['theses']['these'][0]['directeurDeThese'];
			}
			if(isset($personne['formation']['theses']['these'][0]['dateSoutenanceThese'])){
				$dateSoutenanceThese= $personne['formation']['theses']['these'][0]['dateSoutenanceThese'];
			}
			$email=$personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['courrielProfessionnel'];
			$dateDebut=$personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['dateDebut'];
			$tel=$personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['telephoneDirect'];
			$bureau=$personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['batimentBureau'];
			$nbCarriere= count($personne['personnelUniteCNRS'][0]['carriereAdministrative']['carriereAdministrative']);
			$organisme= $personne['personnelUniteCNRS'][0]['carriereAdministrative']['carriereAdministrative'][$nbCarriere-1]['libelleOrganismeEmployeur'];
			
			$emerite= $personne['personnelUniteCNRS'][0]['lienImplantations']['lienImplantations'][$n]['emerite'];

			$l_fonction = recup_fonction($typePoste ,$statutPoste, $etat, $raisonProlongation, $etatThese,$emerite);
			
			$grade='';
		
			if($l_fonction =="Permanents"){	
				$grade= $personne['personnelUniteCNRS'][0]['carriereAdministrative']['carriereAdministrative'][$nbCarriere-1]['grade']['code'];
				$code = substr($grade,0,2);
			
					if($code==  'PU'  ) {
						$grade= 'Professeur des universités';
					}else if($code==  'MC') {
						$grade= 'Maître de conférences';
					}else if($code==  'CR') {
						$grade= 'Chargé de recherche';
					}else if($code==  'DR') {
						$grade= 'Directeur de recherche';
					}
					
				}
	
		
		}
	}
   } //fin for
}
		
		
		


	
	
$adr =explode('/', $bureau);

if(  strtolower(trim($adr[0]))== 'jussieu'){
	$adr[0] = "4 place Jussieu,<br>Boite Courrier 247<br>75252 Paris Cedex 5<br>";
}
if(  strtolower(trim($adr[0]))== 'sophie germain'){
	$adr[0] = "UP7D - Campus des Grands Moulins<br>Boite Courrier 7012<br>Bâtiment Sophie Germain<br>8 Place Aurélie Nemours,<br>- 75205 PARIS Cedex 13<br>";
	}
if(!isset($adr[1])){
	$adr[1]='';
}
$adresse ="Institut de Mathématiques de Jussieu - Paris Rive Gauche (IMJ-PRG)<br>" .  $adr[1] ."<br>".$adr[0];
$web = "http://webusers.imj-prg.fr/~$email_debut/";
$poste="$l_fonction<br><br>";

if($l_fonction  == "Jeunes docteurs" || $l_fonction  == "Doctorants"){

			$poste.= "Directeur de thèse  : ". $directeurThese ."<br>".
			" ". $intituleThese ."<br>"	 ;
	//	if($l_fonction  == "Doctorants"){
		//	$poste.= "Date de début  : ". $champ_dateDeb ."<br>";
		//}		
		if($l_fonction  == "Jeunes docteurs"){
			$poste.= "Date soutenance: ".$dateSoutenanceThese."<br>"; 
		}
	}

if($equipe !="Informatique" && $equipe !="Administration"){
	$poste.= " $grade   $organisme <br><br>";
}
$poste .="Equipe : ".$equipe."<br>";


	
 
$code_acces = '<p><a class="btn btn-default" href="'.$url_acces.'" role="button">Accès</a></p>';

$email='<script type="text/javascript">
<!--
var tg="<";             
var nom="' . $email_debut.'";
var arob="@";
 
var hote1="imj";
var hote2="-prg.fr";       

document.write(tg+"a hr"+"ef=mai"+"lto:"+nom);
document.write(arob+hote1+hote2+">"+nom+arob+hote1+hote2+tg+"/a>");
-->
</script>
';



	//********************************************
	function recupEquipe($fichierAnnuaireRatach, $nom, $prenom, $streamContext){
		$champ_equipe = 6; 
		$champ_nom_ratachement = 12; 
		$champ_prenom_ratachement = 14; 	

		if (($handleRatach = fopen($fichierAnnuaireRatach, "r",false, $streamContext)) !== FALSE) {
			while (($ligneRatach= fgetcsv($handleRatach, 1000, ";")) !== FALSE) {
			
				if((utf8_encode($ligneRatach[$champ_nom_ratachement]) ==$nom) && (strtoupper(suppr_accents(utf8_encode($ligneRatach[$champ_prenom_ratachement]),'utf-8')) == $prenom)){
					$e=explode( '-' ,$ligneRatach[$champ_equipe]  ) ;
					fclose($handleRatach);
					return array(utf8_encode($e[1]),  $e[0]);
				}			
			}
		}
		
	}
	//********************************************
	function recupImplantation($fichierAnnuaireImpl, $nom, $prenom, $streamContext){
		$champ_nom = 6; 
		$champ_prenom = 8; 	
		$champ_email = 30;
		$champ_tel=32;
		$champ_bat=36;
		
		if (($handle = fopen($fichierAnnuaireImpl, "r",false, $streamContext)) !== FALSE) {
			while (($ligne= fgetcsv($handle, 1000, ";")) !== FALSE) {
				if((strtoupper(utf8_encode($ligne[$champ_nom])) == $nom) && (strtoupper(suppr_accents(utf8_encode($ligne[$champ_prenom]),'utf-8')) == $prenom)){
					$implantation = array($ligne[$champ_email], $ligne[$champ_tel], $ligne[$champ_bat]);
					fclose($handle);
					return $implantation ;
				}			
			}
		}	
		
	}
		//********************************************
	function recupCarriere($fichierAnnuaireCarriere, $nom, $prenom, $streamContext){
		$champ_nom = 6; 
		$champ_prenom = 8; 	
		$champ_organisme = 22;
		$champ_type_poste = 28;
		
		if (($handle = fopen($fichierAnnuaireCarriere, "r",false, $streamContext)) !== FALSE) {
			while (($ligne= fgetcsv($handle, 1000, ";")) !== FALSE) {
				if((strtoupper(utf8_encode($ligne[$champ_nom])) == $nom) && (strtoupper(suppr_accents(utf8_encode($ligne[$champ_prenom]),'utf-8')) == $prenom)){
					$s  = explode(' - ', $ligne[$champ_organisme]);
					$typePoste = explode(' - ',  $ligne[$champ_type_poste]);
					fclose($handle);
					if($s[1] != "INCONNU"){
						return  array($s[1],utf8_encode($typePoste[1]));
					}
					else {
						return array('','');
					}
				}			
			}
		}	
		
	}
		//********************************************
	function suppr_accents($str, $encoding='utf-8'){

	$str = htmlentities($str, ENT_NOQUOTES, $encoding);
	$str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
	$str = preg_replace('#&[^;]+;#', '', $str);
	return $str;
}

	// ***************************************************************
function recup_fonction($type_poste ,$statut_poste,$etat,  $raison_prolongation, $these_etat , $emerite){
	
		//echo is_numeric(substr($etat,0,1)) ." $type_poste ,$statut_poste,$etat,  $raison_prolongation, $these_etat, $emerite<br>";
		if( $type_poste =='POST_DOCT' && $etat!='A' ){
			if( strtolower($raison_prolongation) == "jeune docteur" || strtoupper($raison_prolongation) == "JD"){
				return "Jeunes docteurs";
			} else {
				return "Post-doctorants";
			}
		}

		
		// doctorants
		if( $type_poste =='DOCT'){
			if($these_etat == 'Obtenue'){
				return "Jeunes docteurs";
			}else {
				return "Doctorants";
			}
				
		}
		//Associés / Détachements
		if($etat=='A'||$etat=='I'){
			
			return "Associés";
		}
		if($etat=='D'){
			return "Détachements";
		}
	
		// Emerites Benevole
			
		if( $etat=='C' || $etat=='CB' ){
			return "Bénévoles";
		}
		
		if( $emerite||  $etat=='E' ){
			return "Emérites";
		}
		//permanents
		if( $type_poste =='CH' && ($etat==''|| is_numeric(substr($etat,0,1)) )&& $statut_poste!= "CDD"){
			return "Permanents";
		}
		if( $type_poste =='ENS_CH'&& ($etat==''|| is_numeric(substr($etat,0,1)) )){
			return "Permanents";
		}
		if(  $etat=='Ext'|| $etat=='P'){
			return "Permanents";
		}
		
		
		// postdoct et jeune docteur
		if($etat=='POSTE ROUGE'){
			return "POSTE ROUGE";
		}
		if($etat=='PD'){
			return "Post-doctorants";
		}
		if( $type_poste =='CH' && $etat=='' && $statut_poste== "CDD"&& $etat!='A' ){
			if($raison_prolongation == "chercheur invite"){
					return "Permanents";
			}else {
				return "Post-doctorants";
			}
		}
		

		// Administration
		if( $type_poste =='IT'){
			return "Administration";
		}
		
		return "pas trouvé";

}
	
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://www.imj-prg.fr/favicon.ico">

    <title><?php echo "$prenom $nom"; ?> </title>

    <!-- Bootstrap core CSS -->
    <link href="https://www.imj-prg.fr/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://www.imj-prg.fr/css/perso.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="http://www.imj-prg.fr/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="https://www.imj-prg.fr/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="https://www.imj-prg.fr/assets/js/ie10-viewport-bug-workaround.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $web;?>"><?php echo "$prenom $nom";?> </a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="<?php echo $web;?>">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
	  <div class="row">
	  <div class="col-md-3"><img src="http://www.imj-prg.fr/images/logo-imj-prg-250.png">
        
		</div>
			<div class="col-md-3">
				<?php
					if( !is_null($imgPerso)  && $imgPerso != "") {
						echo "<img src=\"$imgPerso\">";
					}
				?>
			</div>
        <div class="col-md-6">
		
			<h1><?php echo "$prenom $nom";?> </h1>
			<p><br><?php echo $poste;?><br><br>
			UMR 7586 CNRS-Université Pierre et Marie Curie-Université Paris Diderot </p>
	
		</div>
	
		</div>
	  </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Contact</h2>
          <p>E-mail:  <?php echo $email;?><br>
URL: <a href="<?php echo $web;?>"><?php echo $web;?></a><br>
Tel: <?php echo $tel;?><br>
		  </p>
        
        </div>
        <div class="col-md-4">
          <h2>Adresse</h2>
          <p><br>
		   <div class="row">
        
<?php 
echo '<div class="col-md-12">'.$adresse. "</div>";


?>
		</div>
			
       </div>
        <div class="col-md-4">
			<?php echo $divers?>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; IMJ-PRG </p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://www.imj-prg.fr/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>