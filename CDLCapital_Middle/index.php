<?php
require('curl.php');

// ======================= THE MIDDLE ================================

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $headers = getallheaders();
    $backend_url = 'https://web.njit.edu/~kc343/CDLCapital/index.php';
    
    if($_POST["m"]){

        // POST TO BACK END
    	$c=$_POST["c"];
    	$t=$_POST["t"];
    	$myvars = 'c=' . $c . '&t=' . $t;
    	/*$ch = curl_init( 'https://web.njit.edu/~kc343/CDLCapital/index.php' );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);*/
        $obj= new Curl();
        $result = $obj->execute($backend_url, $myvars, 0);
        $data = json_decode($result);
        $r = shell_exec('python py_test.py ' . escapeshellarg(json_encode($data)));
        echo $r;

    }else if($_POST["g"]){

    	$c=$_POST["c"];
    	$t=$_POST["t"];
    	$myvars = 'c=' . $c . '&t=' . $t;
    	$obj = new Curl();
        $obj->execute($backend_url, $myvars, 1);

    }else if($headers["Content-Type"] == 'application/json'){
    	  
    	$data = json_decode(file_get_contents('php://input'), true);
        $obj = new Curl();
	    $obj->execute_json($backend_url, $data, 1);

    }else if($_POST['login']){
    
        $login  = htmlspecialchars($_POST['login']);
        $name = htmlspecialchars($_POST['name']);
        $password  = htmlspecialchars($_POST['password']);
    	$myvars = 'name=' . $name . '&password=' . $password . '&login=' . $login;
    	$obj = new Curl();
    	$obj->execute($backend_url, $myvars, 1);
	
    }else if($_POST['register']){
    
        $register  = htmlspecialchars($_POST['register']);
        $name = htmlspecialchars($_POST['name']);
        $password  = htmlspecialchars($_POST['password']);
        $myvars = 'name=' . $name . '&password=' . $password . '&register=' . $register;
    	$obj = new Curl();
    	$obj->execute($backend_url, $myvars, 1);
	
    }else if ($_POST['dc']){

        $dc = htmlspecialchars($_POST["dc"]);
        $myvars = 'dc=' . $dc;
        $obj= new Curl();
        $obj->execute($backend_url,$myvars, 1);

    }else if($_POST["sp"]){

        // POST TO BACK END
        $c=$_POST["c"];
	    $e=$_POST["n"];
	    $sp=$_POST["sp"];
        $t=365;
	    $myvars = 'c=' . $c . '&t=' . $t;
	    /*$ch = curl_init( 'https://web.njit.edu/~kc343/CDLCapital/index.php' );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);*/
        $obj= new Curl();
        $result = $obj->execute($backend_url, $myvars, 0);
	    $data = json_decode($result);
        $r = shell_exec('python py_blackscholes.py ' . escapeshellarg(json_encode($data)) . ' ' . escapeshellarg($e) . ' '  . escapeshellarg($sp));
        echo $r;

    }else if ($_POST['u']){

          $u=$_POST['u'];
          $myvars= 'u=' . $u;
          $obj= new Curl();
          $obj->execute($backend_url, $myvars, 1);

    }else if ($_POST['upload2']){

        $upload2=$_POST['upload2'];
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $comp_name=$_POST['comp_name'];
        $myvars = 'start_date=' . $start_date . '&end_date=' . $end_date . '&comp_name='. $comp_name . '&upload2='. $upload2;
        $obj= new Curl();
        $obj->execute($backend_url, $myvars, 1);

    }
    
} 
?>
