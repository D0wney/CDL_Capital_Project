<?php

require 'libs/database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$table = "proctor_gamble";
    $db = new database();
    $headers = getallheaders();
    /* if(isset($_POST['avg'])){
      $avg=$_POST['avg'];
      echo $avg;
      } */
    //if ($_POST['upload']){
    if ($headers["Content-Type"] == 'application/json') { //upload csv file
        $data = json_decode(file_get_contents('php://input'), true);
        //$data=json_decode($_POST['avg'],true);
        //print_r($data);
        $filename = basename($data[0][0], ".csv") . PHP_EOL;
        $filename = trim($filename);
        $result = $db->create($filename);
        if ($result != true) {
            $result = $db->query("Truncate table $filename");
        }
        $result = $db->upload($data, $filename);
        $result = $db->select("select * from companies where company='$filename'");
        if ($result != false) {
            $sql = "update companies set upload_date=NOW() where company='$filename'";
            $result = $db->query($sql);
        } else {
            $sql = "insert into companies(company) values('$filename')";
            $result = $db->query($sql);
        }
        echo $result;
        //  }
    } else if ($_POST['login']) {
        $username = htmlspecialchars($_POST['name']);
        $password = htmlspecialchars($_POST['password']);
        //echo $username. "<br>";
        //echo $password. "<br>";
        $result = $db->select("select * from users where login='$username' and password='$password'");
        echo $result;
    } else if ($_POST['register']) {
        $name = htmlspecialchars($_POST['name']);
        $password = htmlspecialchars($_POST['password']);
        //echo $name ."<br>";
        //echo $password;
        $result = $db->query("insert into users(login,password) values('$name','$password')");
        echo $result;
    } else if ($_POST["c"]) {

        $c = $_POST["c"];
        $t = $_POST["t"];
        $dbhost = "sql1.njit.edu";
        $dbuser = "kc343";
        $dbpass = "njitdb123";
        $dbname = "kc343";
        $dbh = mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to connect to MySQL");
        $selected = mysql_select_db($dbname, $dbh);

        $index = mysql_query("select * from (select * from {$c} order by date desc)A limit {$t}");
        $num_rows = mysql_num_rows($index);

        $json_2d = array();

        //$index_array = array();
        //$closing_array = array();

        function sort_by_time($a, $b) {
            return $a[0] > $b[0];
        }

        $i = 0;
        while ($row_array = mysql_fetch_array($index, MYSQL_BOTH)) {
//$index_array[] = $row_array[0];
            //$closing_array[] = $row_array[5];
            $json_2d[] = array(
                strtotime($row_array[1]) * 1000,
                $row_array[7]
            );
        }

        usort($json_2d, 'sort_by_time');
        echo json_encode($json_2d);

        mysql_close($dbh);
    }else if ($_POST["dc"]){ //delete company
            $company=  htmlspecialchars($_POST["dc"]);
            $result=$db->query("delete from companies where company='$company'");
            $result=$db->query("drop table $company");
            echo $result;
        }
    else if ($_POST['u']){ //display companies
        $dbhost = "sql1.njit.edu";
        $dbuser = "kc343";
        $dbpass = "njitdb123";
        $dbname = "kc343";
        $dbh = mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to connect to MySQL");
        $selected = mysql_select_db($dbname, $dbh);
        //$result=$db->select("select company from companies");
        $result=mysql_query("select company from companies");
        $result2=array();
        while ($row = mysql_fetch_array($result)){
              $result2[]=$row['company'];
              }
        $result2=json_encode($result2);
        echo $result2;
    }else if ($_POST['upload2']){ //yql update query
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $comp_name=$_POST['comp_name'];
        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
        $yql_query = "env 'store://datatables.org/alltableswithkeys'; select * from yahoo.finance.historicaldata where symbol in ('$comp_name') and startDate = '$start_date' and endDate = '$end_date'";
        $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
        // Make call with cURL
        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        $json = curl_exec($session);
        // Convert JSON to PHP object
        $obj2 =  json_decode($json);
        //print_r($obj2);
        //$result=$db->update($comp_name, $obj2);
        if($obj2->query->count!=0){
            $result = $db->create($comp_name);
            if ($result != true) {
                $result = $db->query("Truncate table $comp_name");
            }
            for($i=0; $i<count($obj2->query->results->quote); $i++){
                //var_dump($obj2->query->results->quote[$i]);
                for($k=0; $k<count($obj2->query->results->quote[$i]); $k++){
                    $Date = date('Y-m-d', strtotime($obj2->query->results->quote[$i]->Date));
                    $Open = $obj2->query->results->quote[$i]->Open;
                    $High = $obj2->query->results->quote[$i]->High;
                    $Low = $obj2->query->results->quote[$i]->Low;
                    $Close = $obj2->query->results->quote[$i]->Close;
                    $Volume = $obj2->query->results->quote[$i]->Volume;
                    $Adj_Close = $obj2->query->results->quote[$i]->Adj_Close;
                    $result=$db->query("insert into $comp_name (Date, Open, High, Low, Close, Volume, Adj_Close) value ('$Date','$Open','$High','$Low','$Close','$Volume','$Adj_Close')");
                    //echo($result);
                }
            }
            $result = $db->select("select * from companies where company='$comp_name'");
            if ($result != false) {
                $sql = "update companies set upload_date=NOW() where company='$comp_name'";
                $result = $db->query($sql);
            } else {
                $sql = "insert into companies(company) values('$comp_name')";
                $result = $db->query($sql);
            }
        }   
        //return $result;
        //echo $result;
    }
    else if($_POST['updt']){
        $companies=$_POST['updt'];
        $companies_array=json_decode($companies); //turns string into associate array
        $end_date='2016-10-27';
        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
        $count= count($companies_array);
        //echo $count;
        for ($x=0; $x<$count; $x++){
            //$comp_name=$companies_array[2];
            //var_dump($comp_name);
           $comp_name=$companies_array[$x];
           $start_date=$db->select("select max(Date) from $comp_name");
           $start_date=$start_date[0]["max(Date)"];       
           //var_dump($start_date);
           $yql_query = "env 'store://datatables.org/alltableswithkeys'; select * from yahoo.finance.historicaldata where symbol in ('$comp_name') and startDate = '$start_date' and endDate='$end_date'";
           $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
           // Make call with cURL
           $session = curl_init($yql_query_url);
           curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
           $json = curl_exec($session);
           $obj2= json_decode($json);
           //print_r($obj2);
           for($i=0; $i<$obj2->query->count; $i++){
                for($k=0; $k<count($obj2->query->results->quote[$i]); $k++){
                    $Date = date('Y-m-d', strtotime($obj2->query->results->quote[$i]->Date));
                    $Open = $obj2->query->results->quote[$i]->Open;
                    $High = $obj2->query->results->quote[$i]->High;
                    $Low = $obj2->query->results->quote[$i]->Low;
                    $Close = $obj2->query->results->quote[$i]->Close;
                    $Volume = $obj2->query->results->quote[$i]->Volume;
                    $Adj_Close = $obj2->query->results->quote[$i]->Adj_Close;
                    $result=$db->query("insert into $comp_name (Date, Open, High, Low, Close, Volume, Adj_Close) value ('$Date','$Open','$High','$Low','$Close','$Volume','$Adj_Close')");
                }
            }
            if ($result!=false){
                $sql = "update companies set upload_date=NOW() where company='$comp_name'";
                $result = $db->query($sql);
            }
        }
    }
}
?>
