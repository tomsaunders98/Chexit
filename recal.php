<?php

/**
 * XLS parsing uses php-excel-reader from http://code.google.com/p/php-excel-reader/
 */


  header('Content-Type: text/plain');

  if (isset($argv[1]))
  {
    $Filepath = $argv[1];
  }
  elseif (isset($_GET['File']))
  {
    $Filepath = $_GET['File'];
  }
  else
  {
    if (php_sapi_name() == 'cli')
    {
      echo 'Please specify filename as the first argument'.PHP_EOL;
    }
    else
    {
      echo 'Please specify filename as a HTTP GET parameter "File", e.g., "/test.php?File=test.xlsx"';
    }
    exit;
  }

  // Excel reader from http://code.google.com/p/php-excel-reader/
  require('spreadsheet/php-excel-reader/excel_reader2.php');
  require('spreadsheet/SpreadsheetReader.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$servername = "";
$username = "";
$password = "";
$dbname = "";
try {
  $mysqli = new mysqli($servername, $username, $password, $dbname);
  $mysqli->set_charset("utf8mb4");
} catch(Exception $e) {
  error_log($e->getMessage());
  exit('Error connecting to database'); //Should be a message a typical user could understand
}
  date_default_timezone_set('UTC');

  $StartMem = memory_get_usage();
  echo '--------------------------------------------'.PHP_EOL;
  echo 'Starting memory: '.$StartMem.PHP_EOL;
  echo '--------------------------------------------'.PHP_EOL;
  try
  {
    $Spreadsheet = new SpreadsheetReader($Filepath);
    $BaseMem = memory_get_usage();

    $Sheets = $Spreadsheet -> Sheets();

    echo '--------------------------------------------'.PHP_EOL;
    echo 'Spreadsheets:'.PHP_EOL;
    print_r($Sheets);
    echo '--------------------------------------------'.PHP_EOL;
    echo '--------------------------------------------'.PHP_EOL;
    $numnum = 0;
    $TY = 0;
    $TTY = 0;
    $oldrow = "";
    foreach ($Sheets as $Index => $Name)
    {
      $Time = microtime(true);

      $Spreadsheet -> ChangeSheet($Index);
      foreach ($Spreadsheet as $Key => $Row)
      {
        if ($Row)
        {
          $numnum++;
          $vote = $Row[3];
          $column =  substr($Filepath, 0, strpos($Filepath, "."));
          $column = str_replace("votes/","",$column);
          $const = str_replace(",", '', $Row[2]);
          if ($numnum < 11){
            continue;
          }
            $stmt = $mysqli->prepare("SELECT * FROM mastervotes WHERE Constituency = ?");
            $stmt->bind_param("s", $const);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows === 0){
              echo "----------------------------------------".PHP_EOL;
              echo"Couldn't Find ";
              echo "$Row[0] who voted $Row[3] ".PHP_EOL;    
              echo "Dumping Data: ".PHP_EOL;          
              echo "----------------------------------------".PHP_EOL;
            }

            while($row = $result->fetch_assoc()) {

              $i = $row['MPid'];
              $FistName = $row['FirstName'];
              $Lastname = $row['Lastname'];
              $party = $row['Party'];
              $Constituency = $row['Constituency'];
              $name = "$FistName $Lastname";
              $vote1 = $row[$column];
              if ($result->num_rows  > 1){
                if ($name != $Row[0]){
                  continue;//Same constituency different MP
                }
              }
              if ($party === "Sinn Féin" || $party === "Speaker" || $name==="Eleanor Laing"||$name==="Lindsay Hoyle"||$name==="Rosie Winterton"){
                $votem = "NA";
                echo "adding Speaker/Sinn Fein vote";
                $stmt = $mysqli->prepare("UPDATE mastervotes SET $column = ? WHERE MPid = ?");
                    $stmt->bind_param("si", $votem, $i);
                    $stmt->execute();
              }
              if ($vote1 != $Row[3]){
                if ($Row[3] === "No Vote Recorded" && $vote1 === "ABS" || $Row[3] === "No Vote Recorded" && $vote1 === "NA"){
                  continue;
                }elseif ($Row[3] === "Teller - Ayes" && $vote1 === "TelY"){
                  $TY++;
                  continue;
                }elseif ($Row[3] === "Teller - Noes" && $vote1 === "TelN"){
                  $TY++;
                  continue;
                }elseif (($Row[3] === "Aye" && $vote1 === "ABP") ||($Row[3] === "No" && $vote1 === "ABP") ){
                  continue;
                }elseif ($Row[3] === "Teller - Ayes"){
                    $votem = "TelY";
                    echo "Adding Teller Vote for " . $name.PHP_EOL;
                    $stmt = $mysqli->prepare("UPDATE mastervotes SET $column = ? WHERE MPid = ?");
                    $stmt->bind_param("si", $votem, $i);
                    $stmt->execute();
                }elseif ($Row[3] === "Teller - Noes"){
                    $votem = "TelN";
                    $stmt = $mysqli->prepare("UPDATE mastervotes SET $column = ? WHERE MPid = ?");
                    $stmt->bind_param("si", $votem, $i);
                    $stmt->execute();
                }else{

                    echo $Row[0] . " has wrong info".PHP_EOL;
                    echo "Vote registered as : " . $vote1.PHP_EOL;
                    echo "When actual vote is : " . $Row[3].PHP_EOL;

                    if ($Row[2] === $oldrow){
                      $votem = "ABP";
                      echo "ABP!";
                    }elseif ($Row[3] === "No Vote Recorded"){
                      $votem = "ABS";
                    }else{
                      $votem = $Row[3];
                    }
                    $stmt = $mysqli->prepare("UPDATE mastervotes SET $column = ? WHERE MPid = ?");
                    $stmt->bind_param("si", $votem, $i);
                    $stmt->execute();
                    $oldrow = $Row[2];         
                }
            }
          }  
        }
        else
        {
          var_dump($Row);
        }
        $CurrentMem = memory_get_usage();
      }
      echo $TY;
      echo PHP_EOL.'---------------------------------------------'.PHP_EOL;
      echo 'Time: '.(microtime(true) - $Time);
      echo PHP_EOL;

      echo '---------------------------------------------'.PHP_EOL;
      echo 'MasterVotes Updated With '.$Name.' '.PHP_EOL;
      echo '---------------------------------------------'.PHP_EOL;
    }
    
  }
  catch (Exception $E)
  {
    echo $E -> getMessage();
  }
  $stmt->close();
?>
