<?php
function ReadVote($var){
	if ($var === "Aye"){
		$var = "Y";
	}
	if ($var === "ABS"){
		$var = "A";
	}
	if ($var === "No"){
		$var = "N";
	}
	if ($var === "ABP"){
		$var = "B";
	}
	return $var;
}
function NumericalVote($var){
	if ($var === "Y"){
		$var = 1;
	}
	if ($var === "A"){
		$var = 0;
	}
	if ($var === "N"){
		$var = 1;
	}
	return $var;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "aclearv2_admin";
$password = "-!-LYceum98-!-";
$dbname = "aclearv2_mpvotes";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
  $mysqli = new mysqli($servername, $username, $password, $dbname);
  $mysqli->set_charset("utf8mb4");
} catch(Exception $e) {
  error_log($e->getMessage());
  exit('Error connecting to database'); //Should be a message a typical user could understand
}
$q = $_GET['q'];

function containTwice($var, $q){
	$i = 0;
    foreach($var as $word) {
        if ($q === $word){
        	$i++;
        }
    }
    if ($i > 1){
    	return true;
    }else{
    	return false;
    }
}

function DivName($var,$q){
	if (($key = array_search($q, $var)) !== false) {
		if ($key === 0){
			$DivName = $var[0];
			return $DivName;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function CalculateChart($var,$q){
	if (containTwice($var,$q)){
		return $q;
	}
	if (DivName($var, $q) != false){
		$IsTable = false;
		$DivName = DivName($var, $q);
		return $DivName;
	}
	$length = count($var)-2;
	if (($key = array_search("VAL", $var)) !== false) {
		$val = $key;
	}
	
	if (($key = array_search($q, $var)) !== false) {
		$tableno = $key;
		$sort = $val + $tableno;
		return $var[$sort];
	}else{
		return false;
	}
}


$MVVotes = array("BrexitDeal","MV1","BradyAmendment","SupportforBrexitDeal","MV2","MV3", "BorisBill", "ExpeditedBrexitTimetable", "VAL","MV1","Brady29Jan","GovMotion14Feb","MV2","MV3", "BorisBill22October", "BorisTimetable22October");
$NoDealVotes = array("NoDeal","AgainstLordsAmendment","Malthouse","IndManagedNoDeal","IndNoDeal","ShortextensionCooper-Letwin","VAL","Grieve12June", "Malthouse13March","Fysh27March","Baron27March","CooperLetwinEustice3April");
$LabourVotes = array("LabourAmendments","CustumsUnionRemain","AmendPlanB","LabourBrexit","AdifferentApproach","IndLabourBrexit","VAL","Labour29Jan","Labour14Feb","Labour27Feb","Labour14March","Labour27March");
$SNPVotes = array("SNPAmendments","ScottishRemain","Delay3Months","AvoidNoDeal","VAL","SNP29Jan","SNP14Feb","SNP27Feb");
$AgainstNoDeal = array("AvoidingNoDeal", "GrieveAvoidNoDeal", "Spelman1","Cooper-Boles","ReevesRequestExtension","Spelman2","RejectNoDeal","Extendarticle50","ExtensionJune30", "BeckettNoDeal", "Cooper-Letwin", "LabourNoDeal", "GrieveProrogue1", "GrieveProrogue2", "BennProrogue", "BennAct", "VAL", "Grieve20June", "Spellman29Jan", "Cooper29Jan","Reeves29Jan","Spellman13March","NoDeal13March","Extend5014March","Powell14March","Beckett25March", "CooperLetwin3April",  "Labour12June", "Grieve9JulyP1", "Grieve9JulyP2", "Benn18July", "Benn4Sep");
$MoreParl = array("ParliamentInvolvement","VoteonanyDeal", "ControloverPlanB", "3Days","MoretimeinParliament","BusinessoftheHouse","IndicativeVotes","MoreIndicativeVotes", "IntroBennAct", "Letwin2ndAmendment", "VAL","Grieve13Dec", "Grieve4Dec","Grieve9Jan","Grieve29Jan","Benn14March","Letwin25March","Ind3April", "Letwin3Sep", "Letwin19October");
$AgainstBrexit = array("2ndReferendumRevoke","2ndReferendum","Indicative2ndReferendum","IndicativeRevoke","Indicative2ndReferendum2","IndicativeRevoke2","VAL","Wollaston14March","Beckett27March","Cherry27March","Kyle1April","Cherry1April");
$SoftBrexit = array("VotesonSoftBrexit","IndCustomsUnion","IndCommonMarket20","IndEFTA","IndCustomsUnion2","IndCommonMarket202","VAL","Clarke27March","Boles27March","Eustice27March","Clarke1April","Boles1April");
$assort = array($MVVotes,$NoDealVotes,$LabourVotes,$SNPVotes,$AgainstNoDeal,$AgainstBrexit,$SoftBrexit,$MoreParl);
$IsTable = true;
foreach ($assort as $value) {
	if (CalculateChart($value, $q) != false){
		if (DivName($value, $q) != false){
			$DivName = DivName($value, $q);
			$IsTable = false;
		}else{
			$column = CalculateChart($value, $q);
			$DivName = $value[0];	
		}
	}
}
$chartName = $DivName . "chart";
if ($IsTable === false){
	die("NoTable");
}
if (isset($_GET['vp'])){
	if ($_GET['vp']==="mb"){
		$bartype = "bar";
		$heightsize = "4";
		$widthsize = "1";
		$wrappertype = "wrapper3";
	}else{
		$bartype = "horizontalBar";
		$heightsize = "1";
		$widthsize = "4";
		$wrappertype = "wrapper2";
	}
}
$sql = "SELECT $column, party FROM mastervotes";
$stmt = $mysqli->prepare($sql);
$stmt->execute() or die("error");
$result = $stmt->get_result();
$Cyes = 0;
$Cno = 0;
$CAb = 0;
$Lyes = 0;
$Lno = 0;
$LAb = 0;
$SNPyes = 0;
$SNPno = 0;
$SNPab = 0;
$LDyes = 0;
$LDno = 0;
$LDab = 0;
$DUPyes = 0;
$DUPno = 0;
$DUPab = 0;
$IY = 0;
$Ino =0;
$Iab = 0;
$PCY = 0;
$PCno = 0;
$PCab = 0;
$GY = 0;
$Gno = 0;
$Gab = 0;
$TY = 0;
$TN = 0;
$TA = 0; 
$OY = 0;
$Ono = 0;
$Oab = 0;
while($row = $result->fetch_assoc()) {
	$vote = ReadVote($row[$column]);
	if ($vote === "Y"){
		$TY++;
	}
	if ($vote === "N"){
		$TN++;
	}
	if ($vote === "A"){
		$TA++;
	}

	if ($vote === "B"){
		$TY= $TY + 1;
		$TN = $TN + 1;
	}
	$party = $row['party'];
	if ($party === "Conservative"){
		if ($vote === "Y"){
				$Cyes++;
		}
		if ($vote === "N"){
				$Cno++;
		}
		if ($vote === "A"){
				$CAb++;
		}
		if ($vote === "B"){
			$Cyes++;
			$Cno++;
		}

	}
	elseif (($party === "Labour/Co-operative") || ($party === "Labour")){
		if ($vote === "Y"){
				$Lyes++;
		}
		if ($vote === "N"){
				$Lno++;
		}
		if ($vote === "A"){
				$LAb++;
		}
		if ($vote === "B"){
			$Lyes++;
			$Lno++;
		}

	}
	elseif ($party === "Scottish National Party"){
		if ($vote === "Y"){
				$SNPyes++;
		}
		if ($vote === "N"){
				$SNPno++;
		}
		if ($vote === "A"){
				$SNPab++;
		}
		if($vote === "B"){
				$SNPyes++;
				$SNPno++;
		}
	}
	elseif ($party === "Liberal Democrat"){
		if ($vote === "Y"){
				$LDyes++;
		}
		if ($vote === "N"){
				$LDno++;
		}
		if ($vote === "A"){
				$LDab++;
		}
		if ($vote === "B"){
			$LDyes++;
			$LDno++;
		}
	}
	elseif ($party === "DUP"){
		if ($vote === "Y"){
				$DUPyes++;
		}
		if ($vote === "N"){
				$DUPno++;
		}
		if ($vote === "A"){
				$DUPab++;
		}
		if ($vote === "B"){
			$DUPyes++;
			$DUPno++;
		}
	}
	elseif ($party === "Independent"){
		if ($vote === "Y"){
				$IY++;
		}
		if ($vote === "N"){
				$Ino++;
		}
		if ($vote === "A"){
				$Iab++;
		}
		if ($vote === "B"){
			$IY++;
			$Ino++;
		}
	}
	elseif ($party === "Plaid Cymru"){
		if ($vote === "Y"){
				$PCY++;
		}
		if ($vote === "N"){
				$PCno++;
		}
		if ($vote === "A"){
				$PCab++;
		}
		if ($vote === "B"){
			$PCY++;
			$PCno++;
		}
	}
	elseif ($party === "Green"){
		if ($vote === "Y"){
				$GY++;
		}
		if ($vote === "N"){
				$Gno++;
		}
		if ($vote === "A"){
				$Gab++;
		}
		if ($vote === "B"){
			$GY++;
			$Gno++;
		}
	}else{
		if ($vote === "Y"){
				$OY++;
		}
		if ($vote === "N"){
				$Ono++;
		}
		if ($vote === "A"){
				$Oab++;
		}
		if ($vote === "B"){
			$OY++;
			$Ono++;
		}
	}
		
}
 //Get rid of Sinn Fein plus Speaker
$headName = $DivName . "head";
if ($TY > $TN){
	$message = "<h5 class='deets' id= '" . $headName . "'>Vote <b><span class='greenfont'>Won</span></b>, Yes: <b>" . $TY . "</b> No: <b>" . $TN . "</b> Abstensions: <b>" . $TA . "</b></h5>";
}
if ($TY < $TN){
	$message = "<h5 class='deets' id='" . $headName . "'>Vote <b><span class='redfont'>Lost</span></b>, Yes: <b>" . $TY . "</b> No: <b>" . $TN . "</b> Abstensions: <b>" . $TA . "</b></h5>";
}
if ($TY === $TN){
	$message = "<h5 class='deets' id='" . $headName . "'>Vote Drawn, Yes: <b>" . $TY . "</b> No: <b>" . $TN . "</b> Abstensions: <b>" . $TA . "</b></h5>";
}

	$DivName = $DivName . "hid";

	$colours = array("'rgba(255, 25, 25, 0.5)'", "'rgba(0, 138, 244, 0.5)'", "'rgba(234, 226, 0, 0.5)'", "'rgba(234, 0, 78, 0.5)'", "'rgba(229, 153, 0, 0.5)'", "'rgba(126, 118, 103, 0.5)'", "'rgba(14, 86, 10, 0.5)'", "'rgba(85, 227, 78, 0.5)'" );
	$Labcolour = "rgba(255, 25, 25, 0.5)";
	$Concolour = "rgba(0, 138, 244, 0.5)";
	$SNPcolour = "rgba(234, 226, 0, 0.5)";
	$DUPcolour = "rgba(234, 0, 78, 0.5)";
	$parties = array("'Labour'", "'Conservative'", "'Scottish National Party'", "'DUP'", "'Liberal Democrat'","'Independent'", "'Plaid Cymru'", "'Green'" );
	$barchart = "
	var ctx = document.getElementById('" . $chartName . "');
	var myChart = new Chart(ctx, {
	  type: '". $bartype . "',
	  data: {
	    labels: ['Yes: $TY', 'No: $TN', 'Did Not Vote: $TA'],
	    datasets: [{
	        label: 'Conservative',
	        data: [$Cyes, $Cno, $CAb],
	        backgroundColor: ['rgba(0, 138, 244, 0.5)', 'rgba(0, 138, 244, 0.5)', 'rgba(0, 138, 244, 0.5)'],
	        borderColor: ['rgba(0, 138, 244, 0.5)', 'rgba(0, 138, 244, 0.5)', 'rgba(0, 138, 244, 0.5)'],
	        borderWidth: 2
	      },
	      {
	        label: 'Labour',
	        data: [$Lyes, $Lno, $LAb],
	        backgroundColor: ['rgba(255, 25, 25, 0.5)', 'rgba(255, 25, 25, 0.5)', 'rgba(255, 25, 25, 0.5)'],
	        borderColor: ['rgba(255, 25, 25, 0.5)', 'rgba(255, 25, 25, 0.5)', 'rgba(255, 25, 25, 0.5)'],
	        borderWidth: 2
	      },
		  {
	        label: 'SNP',
	        data: [$SNPyes, $SNPno, $SNPab],
	        backgroundColor: ['rgba(234, 226, 0, 0.5)', 'rgba(234, 226, 0, 0.5)', 'rgba(234, 226, 0, 0.5)'],
	        borderColor: ['rgba(234, 226, 0, 0.5)', 'rgba(234, 226, 0, 0.5)', 'rgba(234, 226, 0, 0.5)'],
	        borderWidth: 2
	      },
	      {
	        label: 'DUP',
	        data: [$DUPyes, $DUPno, $DUPab],
	        backgroundColor: ['rgba(234, 0, 78, 0.5)', 'rgba(234, 0, 78, 0.5)', 'rgba(234, 0, 78, 0.5)'],
	        borderColor: ['rgba(234, 0, 78, 0.5)', 'rgba(234, 0, 78, 0.5)', 'rgba(234, 0, 78, 0.5)'],
	        borderWidth: 2      	
	      },
	      {
	        label: 'Liberal Democrat',
	        data: [$LDyes, $LDno, $LDab],
	        backgroundColor: ['rgba(229, 153, 0, 0.5)', 'rgba(229, 153, 0, 0.5)', 'rgba(229, 153, 0, 0.5)'],
	        borderColor: ['rgba(229, 153, 0, 0.5)', 'rgba(229, 153, 0, 0.5)', 'rgba(229, 153, 0, 0.5)'],
	        borderWidth: 2      	
	      },
	      {
	        label: 'Independent',
	        data: [$IY, $Ino, $Iab],
	        backgroundColor: ['rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)'],
	        borderColor: ['rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)'],
	        borderWidth: 2      	
	      },
	      {
	        label: 'Plaid Cymru',
	        data: [$PCY, $PCno, $PCab],
	        backgroundColor: ['rgba(14, 86, 10, 0.5)', 'rgba(14, 86, 10, 0.5)', 'rgba(14, 86, 10, 0.5)'],
	        borderColor: ['rgba(14, 86, 10, 0.5)', 'rgba(14, 86, 10, 0.5)', 'rgba(14, 86, 10, 0.5)'],
	        borderWidth: 2      		
	      },
	      {
	        label: 'Green Party',
	        data: [$GY, $Gno, $Gab],
	        backgroundColor: ['rgba(85, 227, 78, 0.5)', 'rgba(85, 227, 78, 0.5)', 'rgba(85, 227, 78, 0.5)'],
	        borderColor: ['rgba(85, 227, 78, 0.5)', 'rgba(85, 227, 78, 0.5)', 'rgba(85, 227, 78, 0.5)'],
	        borderWidth: 2      		
	      },
	      {
	        label: 'Others',
	        data: [$OY, $Ono, $Oab],
	        backgroundColor: ['rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)'],
	        borderColor: ['rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)', 'rgba(126, 118, 103, 0.5)'],
	        borderWidth: 2      	
	      },
	    ]
	  },
	  options: {
	    scales: {
	      yAxes: [{
	        stacked: true,
	        ticks: {
	          beginAtZero: true
	        }
	      }],
	      xAxes: [{
	        stacked: true,
	        ticks: {
	          beginAtZero: true
	        }
	      }]

	    }

	  }
	  });";

	$stmt->close();
		echo '
		<div class="chartWrapper text-center deets">

		<div class="' . $wrappertype . ' ">
		<canvas id="' . $chartName . '" height="' . $heightsize . '" width="' . $widthsize . '"></canvas></div></div>
		<script type="text/javascript">' . $barchart . '</script>' . $message;

?>