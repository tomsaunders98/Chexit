<?php
include("header1.php");
if(isset($_POST['searchtext'])){
	$MPtext = $_POST['searchtext'];
}else{
	$MPtext = "";
}

function KeyToVote($var){
	switch ($var) {
		case 0:$word = "first vote";break;
		case 1:$word = "second voteid1";break;
		case 2:$word = "third vote";break;
		case 3:$word = "fourth vote";break;
		case 4:$word = "fifth vote";break;
		case 5:$word = "sixth vote";break;
	}
	return $word;
}
function FindVal($array){
	if (($key = array_search("VAL", $array)) !== false) {
		return $key;
	}else{
		return false;
	}
}
function ReadVote($var){
	if ($var === "Aye" || $var === "TelY"){
		$var = "Y";
	}
	if ($var === "ABS" || $var === "ABP"){
		$var = "A";
	}
	if ($var === "No" || $var === "TelN"){
		$var = "N";
	}
	return $var;
}
function EvaluateStatus($vars, $party){
	// Essentially Idea as so: start out from 2 base points, 2nd referendum votes and No Deal votes, th
	$i = 0;
	$bugt = "";
	foreach ($vars as $key => $value) {
		$switc = FindVal($value);
		if ($key === 0){
			foreach ($value as $key => $V) {// Meaningful Votes, -3Y, 0A, 0N 
				if ($key > $switc){
					if($V === "Y"){
						$i = $i - 3;
					}if ($V === "A"){
						$i = $i + 0;
					}if ($V === "N"){
						$i = $i + 0;
						//Keep on 0
					}

				}

			}
			$bugt =  $bugt . ":" . $i;
		}if ($key === 1){
			foreach ($value as $key => $V) {// No Deal Votes, -10Y, 0A, 2N 
				if ($key > $switc){
					if($V === "Y"){
						$i = $i - 10; // Keep on 0
					}if ($V === "A"){
						$i = $i + 0; 
					}if ($V === "N"){
						$i = $i + 2;
					}
				}
			}
			$bugt =  $bugt . ":" . $i;
		}if ($key === 2){
			foreach ($value as $key => $V) {// Labour Votes, -2Y, 0A, 0N 
				if ($key > $switc){
					if($V === "Y"){
						$i = $i - 3; // Keep on 0
					}if ($V === "A"){
						$i = $i + 0; 
					}if ($V === "N"){
						continue;
					}
				}
			}
			$bugt =  $bugt . ":" . $i;
		}if ($key === 3){
			foreach ($value as $key => $V) {// SNP Votes, 8Y, 0A, -1N 
				if ($key > $switc){
					if($V === "Y"){
						if ($party === "Labour"){//Labour whipped here to avoid no deal this easier
							continue;
						}
						$i = $i + 8; // Keep on 0
					}if($V === "A"){
						if ($party === "Labour"){//Labour whipped here to avoid no deal this easier
							continue;
						}
						
						//$i = $i + 6; 
					}if ($V === "N"){
						$i = $i - 1; 
					}
				}
			}
			$bugt =  $bugt . ":" . $i;
		}if ($key === 4){
			foreach ($value as $key => $V) {// Against/Avoid No Deal Votes, 3Y, 0A, -5N 
				if ($key > $switc){
					if($V === "Y"){
						$i = $i + 1;// Keep on 0
					}if($V === "A"){
						continue;
					}if ($V === "N"){
						$i = $i - 1;
					}
				}
			}
			$bugt = $bugt . ":" . $i;
		}if ($key === 5){
			foreach ($value as $key => $V) {// Against Brexit, 6Y, 0A, 0N 
				if ($key > $switc){
					if (($key == 11) || ($key == 13)){// Extra hit for revoke
						if($V === "Y"){
						 	$i = $i + 8;
						 	continue;
						 }
					}
					if($V === "Y"){
						 $i = $i + 6;
					}if ($V === "A"){
						if ($party === "Conservative"){// Conservatives who abstained indicative votes
							continue;
						}
						continue; //Turns out basically all people who abstained were whipped anyways
					}if ($V === "N"){
						continue;
					}
				}
			}
			$bugt = $bugt . ":" .  $i;
		}if ($key === 6){
			foreach ($value as $key => $V) {// Soft Brexit, 4Y, 1A, 3N 
				if ($key > $switc){
					if($V === "Y"){
						$i = $i - 3; // Keep on 0
					}if ($V === "A"){
						if ($party === "Conservative"){// Conservatives who abstained indicative votes
							continue;
						}
						$i = $i + 1; 
					}if ($V === "N"){
						continue;
					}
				}
			}
			$bugt = $bugt . ":" .  $i;
		}
	}
	if ($i < -50 ){
		return "Probably an <b><span class='redfont'Red> Extra-Hot</span></b> Brexiteer";
	}
	if (($i < -45) && ($i > -50) ){
		return "Probably a <b><span class='orangeredfont'> Hot</span></b> Brexiteer";
	}
	if (($i > -45) && ($i < -20)){
		return "Probably a <b><span class='orangefont'> Medium Hot</span></b> Brexiteer";
	}
	if (($i > -20) && ($i < 0)){
		return "Probably a <b><span class='greenfont'>Mild</span></b> Brexiteer";
	}
	if ($i > 40 ){
		return "Probably an <b><span class='redfont'Red> Extra-Hot</span></b> Remainer";
	}
	if (($i > 30) && ($i < 40) ){
		return "Probably a <b><span class='orangeredfont'>Hot</span></b> Remainer";
	}
	if (($i < 30) && ($i > 10)){
		return "Probably a <b><span class='orangefont'>Medium Hot</span></b> Remainer";
	}
	if (($i < 10) && ($i > 0)){
		return "Probably a <b><span class='greenfont'>Mild</span></b> Remainer";
	}
	return "";
}
function CalculateVotes($var,$voteType){
	// Tally Up Votes
	$i = 0;
	$Votes = $var;
	$FinalVote = end($Votes);
	foreach ($Votes as $key => $vote) {
		$x = NumericalVote($vote);
		$i = NumericalVote($vote) + $i;

	}
	//If there are no votes for it:
		if ($i === 0){ 
			$var =  "N $voteType everytime.";
		}
	//Else
		$var = "";
		foreach ($Votes as $key => $vote) {
			$votestage = KeyToVote($key);
			if ($key === 0){
				$var = $vote . " " . $voteType . " on the " . $votestage;
			}else{
				$var = $var . " and " . $vote . " " . $voteType . " on the " . $votestage;
			}
		}
	return $var;
}
function CalculateTable($var){	
	$Votes = $var;
	if (($key = array_search("VAL", $Votes)) !== false) {
		$switch = $key;
	}
	$FinalVote = count($Votes)-1;
	$forbid = array("/",":","(",")"," ",".");
	$vars = "<div class='table-responsive text-center spacebit'>".PHP_EOL;
	foreach ($Votes as $key => $vote) {
		if ($key === 0){
			$voteid1 = str_replace($forbid, '', $vote);
			//$voteid1 = $voteid1 . "";
			$votetiny = "tiny" . $voteid1;
			$vars = $vars . "<h4><a href='#' id='$voteid1' class='hoverhelp $voteid1'>$vote<sup id='$votetiny' class='tiny'>(?)</sup></a></h4>".PHP_EOL;
			$vars = $vars . "<table class='table votes'>".PHP_EOL;
			$vars = $vars . "<thead>".PHP_EOL;
			$vars = $vars . "<tr>".PHP_EOL;
 		}elseif ($key < $switch){
 			$voteid = str_replace($forbid, '', $vote);
 			$votetiny = "tiny" . $voteid;
 			$vars = $vars . "<th scope='col'><a href='#' class='hoverhelp $voteid1' id='$voteid'>$vote<sup id='$votetiny' class='tiny'>(?)</sup></a></th>".PHP_EOL;
 		}elseif ($key === $switch) {
 			$vars = $vars . "</tr>".PHP_EOL;
 			$vars = $vars . "</thead>".PHP_EOL;
 			$vars = $vars . "<tbody>".PHP_EOL;
 			$vars = $vars . "<tr>".PHP_EOL;
 		}elseif ($key > $switch) {
 			if ($vote === "N"){
 				if (($key - $switch) === 1){
 					$vars = $vars . "<td class='red rightborder'></td>".PHP_EOL;
 				}elseif ($key === $FinalVote) {
 					$vars = $vars . "<td class='red leftborder'></td>".PHP_EOL;
 				}else{
 					$vars = $vars . "<td class='red fullborder'></td>".PHP_EOL;
 				}	
			}
			if ($vote === "A"){
				if (($key - $switch) === 1){
 					$vars = $vars . "<td class='grey rightborder'></td>".PHP_EOL;
 				}elseif ($key === $FinalVote ) {
 					$vars = $vars . "<td class='grey leftborder'></td>".PHP_EOL;
 				}else{
 					$vars = $vars . "<td class='grey fullborder'></td>".PHP_EOL;
 				}
			}
			if ($vote === "Y"){
				if (($key - $switch) === 1){
 					$vars = $vars . "<td class='green rightborder'></td>".PHP_EOL;
 				}elseif ($key === $FinalVote ) {
 					$vars = $vars . "<td class='green leftborder'></td>".PHP_EOL;
 				}else{
 					$vars = $vars . "<td class='green fullborder'></td>".PHP_EOL;
 				}
			}
 		}
	}
	$vars = $vars . "</tr>".PHP_EOL;
	$vars = $vars . "</tbody>".PHP_EOL;
	$vars = $vars . "</table>".PHP_EOL;
	$vars = $vars . "</div>".PHP_EOL;
	$voteid2 = $voteid1 . "deet";
	$voteid3 = $voteid1 . "hid";
	$vars = $vars . "<div id ='$voteid3'>".PHP_EOL;
	$vars = $vars . "<div class='hidebut'><a href='#'  class='hidebut' id='z" . $voteid1 . "'>
					<div class='text-right' >
					<img src='img/upchevron.svg'  height='30px' width='30px'  />
					</div>
					</a></div></div>";
	$vars = $vars . "<div id ='$voteid2'></div>".PHP_EOL;
	return $vars;
}
if(isset($_GET['id'])){
	include("header.php");
	echo '<div class="text-center searchfield">
		<form method="post" action="search.php">
			<input class="searchbox"  name="searchtext" placeholder="Enter an MPs name or constituency ...">
		<a clas="searchlink" href="#"">
			<input type="image" class="searchglass img-fluid" src="img/search.png" alt="Submit Form" />
		</a>
		</form>
	</div>
	<div class="mainbody">';
}

$resultno = 1;
$names = explode(" ", $MPtext);
if(isset($_POST['searchtext'])){
	$CF = 0;
	$asde = 0;
	$isres = 0;
	for ($i=0; $i < 650; $i++) { 
		$stmt = $mysqli->prepare("SELECT * FROM mastervotes WHERE MPid = ?");
		$stmt->bind_param("i", $i);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			$id = $row['MPid'];
			$FistName = $row['FirstName'];
			$Lastname = $row['Lastname'];
			$Constituency = $row['Constituency'];
			$PhotoID = $row['PhotoID'];
			$name = "$FistName $Lastname";
			$namelow = strtolower($name);
			$photourl = InsertPicture($PhotoID);
			$url = 'search.php?id=' . $id;
			if ($namelow === strtolower($MPtext)){// Matching name then redirect
				$isres = 1;
				header("Location:" . $url);
				exit();
			}
		}
	} //Move to checking if similair
	include("header.php");
	echo '<div class="text-center searchfield">
		<form method="post" action="search.php">
			<input class="searchbox"  name="searchtext" placeholder="Enter an MPs name or constituency ...">
		<a clas="searchlink" href="#"">
			<input type="image" class="searchglass img-fluid" src="img/search.png" alt="Submit Form" />
		</a>
		</form>
	</div>
	<div class="mainbody">';
	if ($isres === 0){
		if ($CF === 0){
			echo "<div class='brextables text-center'><h1> Couldn't find <b> " . $MPtext. "</b></h1></div>";
			$CF = 1;
		}
		for ($i=0; $i < 650; $i++) { 
		$stmt = $mysqli->prepare("SELECT * FROM mastervotes WHERE MPid = ?");
		$stmt->bind_param("i", $i);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			$id = $row['MPid'];
			$FistName = $row['FirstName'];
			$Lastname = $row['Lastname'];
			$Constituency = $row['Constituency'];
			$PhotoID = $row['PhotoID'];
			$name = "$FistName $Lastname";
			$namelow = strtolower($name);
			$photourl = InsertPicture($PhotoID);
			$url = 'search.php?id=' . $id;	

			$simName = similar_text($MPtext, $name, $percName);
			$simCon = similar_text($MPtext, $Constituency, $perc);
			if ($percName > 50){
				$asde++;
				if ($asde === 1){
					echo"<h4><b>Did you mean: </b></h4>";
				}
				echo "<div class='Didyou'><a href=" . $url . "  ><div class='container'><div class='col-md-8 details text-left align-middle'><br /><h4 class='middle'>" . $name . " </h4></div><div class='col-md-4 details text-right'><img src='" . $photourl . "' class='img-fluid' height='100px' width='100px'/></div></div></a></div>";
				
			}
			 if ($perc > 50){
			 	$asde++;
			 	if ($asde === 1){
					echo"<h4><b>Did you mean: </b></h4>";
				}
				echo "<div class='Didyou'><a href=" . $url . "  ><div class='container'><div class='col-md-8 details text-left align-middle'><br /><h4 class='middle'>" . $Constituency . " </h4></div><div class='col-md-4 details text-right'><img src='" . $photourl . "' class='img-fluid' height='100px' width='100px'/></div></div></a></div>";
				
			}
			}
		}
	echo "</div></div>";
	die();	
	}
}else{
	$id = intval($_GET['id']);
	$stmt = $mysqli->prepare("SELECT * FROM mastervotes WHERE MPid = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {	
		$id = $row['MPid'];			
		$FistName = $row['FirstName'];
		  $Lastname = $row['Lastname'];
		  $party = $row['Party'];
		  $photoID = $row['PhotoID'];
		  $Constituency = $row['Constituency'];
		  $TheyWorkURL = $row['TheyWorkURL'];
		  $Grieve13Dec = ReadVote($row['Grieve13Dec']);//Added
		  $Grieve12June = ReadVote($row['Grieve12June']);//Added
		  $Grieve20June = ReadVote($row['Grieve20June']);//Added
		  $Grieve9Jan = ReadVote($row['Grieve9Jan']);//Added
		  $Grieve4Dec = ReadVote($row['Grieve4Dec']);//Added
		  $MV1 = ReadVote($row['MV1']);//Added
		  $Labour29Jan = ReadVote($row['Labour29Jan']);//Added
		  $SNP29Jan = ReadVote($row['SNP29Jan']);//Added
		  $Grieve29Jan = ReadVote($row['Grieve29Jan']);//Added
		  $Cooper29Jan = ReadVote($row['Cooper29Jan']);//Added
		  $Reeves29Jan = ReadVote($row['Reeves29Jan']);//Added
		  $Spellman29Jan = ReadVote($row['Spellman29Jan']);//Added
		  $Brady29Jan = ReadVote($row['Brady29Jan']);//Added
		  $GovMotion14Feb = ReadVote($row['GovMotion14Feb']);//Added
		  $Labour14Feb = ReadVote($row['Labour14Feb']);//Added
		  $SNP14Feb = ReadVote($row['SNP14Feb']);//Added
		  $Labour27Feb = ReadVote($row['Labour27Feb']);//Added
		  $SNP27Feb = ReadVote($row['SNP27Feb']);//Added
		  $Cooper27Feb = ReadVote($row['Cooper27Feb']); //Added
		  $MV2 = ReadVote($row['MV2']);//Added
		  $Spellman13March = ReadVote($row['Spellman13March']);//Added
		  $Malthouse13March = ReadVote($row['Malthouse13March']);//Added
		  $NoDeal13March = ReadVote($row['NoDeal13March']);//Added
		  $Extend5014March = ReadVote($row['Extend5014March']);
		  $Labour14March = ReadVote($row['Labour14March']);//Added
		  $Benn14March = ReadVote($row['Benn14March']);//Added
		  $Powell14March = ReadVote($row['Powell14March']);//Added
		  $Wollaston14March = ReadVote($row['Wollaston14March']);//Added
		  $Letwin25March = ReadVote($row['Letwin25March']);
		  $Beckett25March = ReadVote($row['Beckett25March']);
		  $Fysh27March = ReadVote($row['Fysh27March']);//Added
		  $Beckett27March = ReadVote($row['Beckett27March']);//Added
		  $Cherry27March = ReadVote($row['Cherry27March']);//Added
		  $Labour27March = ReadVote($row['Labour27March']);//Added
		  $Clarke27March = ReadVote($row['Clarke27March']);//added
		  $Eustice27March = ReadVote($row['Eustice27March']);
		  $Boles27March = ReadVote($row['Boles27March']);//added
		  $Baron27March = ReadVote($row['Baron27March']);//Added
		  $MV3 = ReadVote($row['MV3']);//Added
		  $Cherry1April = ReadVote($row['Cherry1April']);//Added
		  $Kyle1April = ReadVote($row['Kyle1April']);//Added
		  $Clarke1April = ReadVote($row['Clarke1April']);//Added
		  $Boles1April = ReadVote($row['Boles1April']);//Added
		  $CooperLetwin3April = ReadVote($row['CooperLetwin3April']);//Added
		  $Ind3April = ReadVote($row['Ind3April']);
		  $CooperLetwinGov3April = ReadVote($row['CooperLetwinGov3April']);
		  $CooperLetwinEustice3April = ReadVote($row['CooperLetwinEustice3April']);
		  $CooperLetwinMain3April = ReadVote($row['CooperLetwinMain3April']);
		  $Labour12June = ReadVote($row['Labour12June']);
		  $Grieve9JulyP1 = ReadVote($row['Grieve9JulyP1']);
		  $Grieve9JulyP2 = ReadVote($row['Grieve9JulyP2']);
		  $Benn18July = ReadVote($row['Benn18July']);
		  $Letwin3Sep = ReadVote($row['Letwin3Sep']);
		  $Benn4Sep = ReadVote($row['Benn4Sep']);
		  $BorisTimetable22October = ReadVote($row['BorisTimetable22October']);
		  $BorisBill22October = ReadVote($row['BorisBill22October']);
		  $Letwin19October = ReadVote($row['Letwin19October']);
		}				
}

$stmt->close();
$name = "$FistName $Lastname";
if ($party === "Sinn Féin"){
	echo"<div class='brextables text-center'><h4> <b>Sinn Féin </b> follow a policy of abstentionism and do not take their seats in the UK Parliament. Because of this, they haven't voted on any Brexit legislation in the UK Parliament.</h4>";
	die();
}
if ($party === "Speaker"){
	echo"<div class='brextables text-center'><h4> As the Speaker <b>" . $name . "</b> remains impartial and does not vote on any legislation so we don't have a profile for him.</h4>";
	die();
}
if ($name==="Eleanor Laing"||$name==="Lindsay Hoyle"||$name==="Rosie Winterton"){
	echo"<div class='brextables text-center'><h4> As a Deputy-Speaker <b>" . $name . "</b> remains impartial and does not vote on any legislation so we don't have a profile for her.</h4>";
	die();
}
if ($name==="Ruth Jones"){
	echo"<div class='brextables text-center'><h4><b>" . $name . "</b> has not been able to vote in a Brexit legislation so far as she was only assume office on the 5th April 2019</h4>";
	die();
}

$MVVotes = array("Brexit Deal", "MV1", "Brady Amendment","Support for Brexit Deal", "MV2", "MV3", "Boris Bill", "Expedited Brexit Timetable",  "VAL", $MV1, $Brady29Jan, $GovMotion14Feb, $MV2, $MV3, $BorisBill22October, $BorisTimetable22October);
$NoDealVotes = array("No Deal", "Against Lords Amendment", "Malthouse", "Ind Managed No Deal", "Ind No Deal","Short extension (Cooper-Letwin)", "VAL",$Grieve12June, $Malthouse13March, $Fysh27March, $Baron27March, $CooperLetwinEustice3April);
$LabourVotes = array("Labour Amendments", "Custums Union/Remain", "Amend Plan B","Labour Brexit", "A different Approach", "Ind Labour Brexit", "VAL", $Labour29Jan, $Labour14Feb, $Labour27Feb, $Labour14March, $Labour27March);
$SNPVotes = array("SNP Amendments", "Scottish Remain", "Delay 3 Months", "Avoid No Deal", "VAL", $SNP29Jan, $SNP14Feb, $SNP27Feb);
$AgainstNoDeal = array("Avoiding No Deal", "Grieve:Avoid No Deal", "Spelman(1)", "Cooper-Boles", "Reeves:Request Extension", "Spelman(2)", "Reject No Deal", "Extend article 50", "Extension:June 30","Beckett:No Deal", "Cooper-Letwin", "Labour:No Deal", "Grieve: Prorogue(1)", "Grieve: Prorogue(2)", "Benn: Prorogue", "Benn Act", "VAL", $Grieve20June,  $Spellman29Jan, $Cooper29Jan, $Reeves29Jan, $Spellman13March, $NoDeal13March, $Extend5014March, $Powell14March, $Beckett25March, $CooperLetwin3April, $Labour12June, $Grieve9JulyP1, $Grieve9JulyP2, $Benn18July, $Benn4Sep);
$MoreParl = array("Parliament Involvement", "Vote on any Deal", "Control over Plan B", "3 Days","More time in Parliament", "Business of the House", "Indicative Votes","More Indicative Votes", "Intro Benn Act", "Letwin 2nd Amendment", "VAL", $Grieve13Dec, $Grieve4Dec, $Grieve9Jan, $Grieve29Jan, $Benn14March, $Letwin25March, $Ind3April, $Letwin3Sep, $Letwin19October);
$AgainstBrexit = array("2nd Referendum/Revoke", "2nd Referendum", "Indicative 2nd Referendum", "Indicative Revoke", "Indicative 2nd Referendum (2)", "Indicative Revoke (2)", "VAL", $Wollaston14March, $Beckett27March, $Cherry27March, $Kyle1April, $Cherry1April);
$SoftBrexit = array("Votes on Soft Brexit", "Ind Customs Union", "Ind Common Market 2.0", "Ind EFTA", "Ind Customs Union (2)", "Ind Common Market 2.0 (2)", "VAL", $Clarke27March, $Boles27March, $Eustice27March, $Clarke1April, $Boles1April);
$assort = array($MVVotes,$NoDealVotes,$LabourVotes,$SNPVotes,$AgainstNoDeal,$AgainstBrexit,$SoftBrexit,$MoreParl);
$ParlStatus = array($MVVotes,$NoDealVotes,$LabourVotes,$SNPVotes,$AgainstNoDeal,$AgainstBrexit,$SoftBrexit);

$url = "search.php?id=" . $id;
$ParlStatus1 = EvaluateStatus($ParlStatus, $party);
$photourl = InsertPicture($photoID);
?> 

<div class="container">
    	<div class="col-md-8 details text-left">
    	<h1> <b><?php echo $name; ?></b> 
    	<br />
    	<small><?php echo $party; ?></small> 
    	</h1>
    	<h5><?php echo $Constituency; ?></h5>
    	<a href="<?php echo $url; ?>" id="linkbutton"><button type="button"  class="btn btn-primary btn-md black"> Copy <?php echo $FistName; ?>'s Profile</button></a>
    	<a href="<?php echo $TheyWorkURL; ?>"><button type="button" class="btn btn-primary btn-md theywork"><?php echo $FistName; ?>'s Other Votes</button></a>

    	
    	<br />
    	<br />
    	<h5> <?php 
    	echo $ParlStatus1; ?> </h5>
    	<h3><small>Key</small></h3>
    	<table class='table-borderless votes table-responsive'>
    		<tr>
    			<td class='green box'>
    			</td>
    			<td class='descbox'>
    				   Voted For
    			</td>
    			<td class='red box'>
    			</td>
    			<td class='descbox'>
    				    Voted Aginst
    			</td>
    			<td class='grey box'>
    			</td>
    			<td class='descbox'>    Abstained From
    			</td>
    		</tr>
    	</table>
 
    	</div>
    	<div class="col-md-4 details text-center">
    		<div class='contentphoto'><img src="<?php echo $photourl; ?>" height='100px' width='100px'  class="img-fluid" /></div>
    	</div>
    	</div>
    	
    	<div class="text-center brextables">
    	
    	<?php
    	foreach ($assort as $value) {
			$table = CalculateTable($value);
			echo $table;
		}		
    	?>
    	</div>

</div>
<?php include("footer.php"); ?>	