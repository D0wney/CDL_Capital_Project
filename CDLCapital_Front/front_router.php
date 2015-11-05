<?php
    // FRONT ROUTER
    require('curl.php');

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $headers = getallheaders();
        $middle_url = 'https://web.njit.edu/~al356/CDLCapital/index.php';

        if($_POST["m"]){ // Math

        	$c=$_POST["c"];
        	$t=$_POST["t"];
        	$m=$_POST["m"];
        	$myvars = 'c=' . $c . '&t=' . $t . '&m=' . $m;
            $obj= new Curl();
            $obj->execute($middle_url, $myvars, 1);

        }else if($_POST["g"]){ // Graph
   	 
        	$c=$_POST["c"];
        	$t=$_POST["t"];
        	$g=$_POST["g"];
        	$myvars = 'c=' . $c . '&t=' . $t . '&g=' . $g;
        	$obj= new Curl();
            $obj->execute($middle_url, $myvars, 1);
	
        }else if ($_POST['login']) {
    
            $login = $_POST['login'];
            $name = htmlspecialchars($_POST['name']);
            $password  = htmlspecialchars($_POST['password']);
            $myvars = 'name=' . $name . '&password=' . $password . '&login=' . $login;
            $obj= new Curl();
            $result=$obj->execute($middle_url, $myvars, 0);

            if($result != false){
                header('Location: https://web.njit.edu/~kad34/CDLCapital/home.php');
            }else{
                header('Location: https://web.njit.edu/~kad34/CDLCapital/login_view.php');
            }
        
        }else if($_POST['register']){
        
            $register = $_POST['register'];
            $name = htmlspecialchars($_POST['name']);
            $password  = htmlspecialchars($_POST['password']);
            $myvars = 'name=' . $name . '&password=' . $password . '&register=' . $register;
            $obj= new Curl();
            $result=$obj->execute($middle_url, $myvars, 0);

            if($result == true){
                header('Location: https://web.njit.edu/~kad34/CDLCapital/home.php');
            }else{
                header('Location: https://web.njit.edu/~kad34/CDLCapital/register.php');
            }
        
        }else if ($_POST['upload']){ // CSV Upload
        
            $upload = $_POST['upload'];
            $date = "";
            $open = "";
            $high = "";
            $low = "";
            $close = "";
            $volume = "";
            $adj_close = "";
            $row = 0;
            $stock_3D_array = array();
            
            if(($handle = fopen($_FILES["file"]["tmp_name"], "r")) !== FALSE) 
            {
                while(($line = fgetcsv($handle,9999,",")) !== FALSE)
                {
                    if($row == 0){
                        $date = $line[0];
                        $open = $line[1];
                        $high = $line[2];
                        $low = $line[3];
                        $close = $line[4];
                        $volume = $line[5];
                        $adj_close = $line[6];
                        $row = 1;
                        $fname = [$_FILES["file"]["name"]];
                        array_push($stock_3D_array, $fname);
                        continue;
                    }
                    array_push($stock_3D_array, $line);       
                }
            }
            fclose($handle);
    	
            $obj= new Curl();
            $result=$obj->execute_json($middle_url, $myvars, 0);

            if($result){
                header('Location: https://web.njit.edu/~kad34/CDLCapital/home.php');
            }else{
                header('Location: https://web.njit.edu/~kad34/CDLCapital/upload.php');
            }
        
        }else if ($_POST["sp"]){ // Call Option Form

            $sp=$_POST["sp"];
            $n=$_POST["n"];
            $c=$_POST["c"];
            $myvars = 'sp=' . $sp . '&n=' . $n . '&c='. $c;
            $obj= new Curl();
            $obj->execute($middle_url, $myvars, 1);

        }else if ($_POST['u']){ // Showing Companies

            $u=$_POST['u'];
            $myvars= 'u=' . $u;
            $obj= new Curl();
            $obj->execute($middle_url, $myvars, 1);

        }else if ($_POST['upload2']){ // YQL Upload

            $upload2=$_POST['upload2'];
            $start_date=$_POST['start_date'];
            $end_date=$_POST['end_date'];
            $comp_name=$_POST['comp_name'];
            $myvars = 'start_date=' . $start_date . '&end_date=' . $end_date . '&comp_name='. $comp_name . '&upload2='. $upload2;
            $obj= new Curl();
            $obj->execute($middle_url, $myvars, 1);
        
        }
    
}

?>