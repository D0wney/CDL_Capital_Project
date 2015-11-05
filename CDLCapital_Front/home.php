<!DOCTYPE html>
<html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

  <!-- <link rel="stylesheet" type="text/css" href="stylesheet.css"> -->
  <script type="text/javascript" src="dygraph-combined.js"></script>
  <script src="jquery.min.js"></script>
  <script src="front_end_functions.js"></script>
</head>

<body>

  <div class="container">

    <div class="jumbotron">
      <h1>CDL Capital</h1>
      <p>Stock Market Data Analyzer</p> 
      <a href="https://web.njit.edu/~kad34/CDLCapital/home.php">Home</a>
      <a href="https://web.njit.edu/~kad34/CDLCapital/upload.php">Upload</a>
      <a href="https://web.njit.edu/~kad34/CDLCapital/login_view.php">Logout</a>
    </div>

    <!-- FIRST ROW -->
    <div class="row">

      <div class="col-md-4">
        <!-- Company Dropdown List -->
        <h3>Select Company: </h3>
        <form>
          <select id="select_company" name="users" onchange="display_graph_stats(select_company.value, 365)">
            <option value="">Companies</option>
              <?php
                require('curl.php');
                $front_url = 'https://web.njit.edu/~kad34/CDLCapital/front_router.php';
                $u='post';
                $myvars= 'u=' . $u;
                $obj= new Curl();
                $result = $obj->execute($front_url, $myvars, 0);
                $company_array = json_decode($result);
            
                for($i=0; $i<count($company_array); $i++) {
                  echo "<option value='" . $company_array[$i] . "'>". $company_array[$i] ."</option>";
                }   
              ?>
          </select>
        </form>

        <!-- Date Dropdown List -->
        <form>
          <select id="Days" onchange="display_graph_stats(select_company.value, Days.value)">
            <option value="">Time Frame</option>
            <option value="365">365 Days</option>
            <option value="180">180 Days</option>
            <option value="90">90 Days</option>
            <option value="30">30 Days</option>
            <option value="14">14 Days</option>
            <option value="7">7 Days</option>
          </select>
        </form>

        <!-- Update/Delete Graph Button -->
        <input id="update" type="button" value="Update Graph" onclick="update_graphs(companies);" />
        <input id="delete" type="button" value="Delete Graph" onclick="del_graph(select_company.value);" />
      </div>

      <div class="col-md-4">
        <h3>Upload Company</h3>        
        <form action="front_router.php" method="post" >
            <label>Company Name </label><input type="text" name="comp_name" /><br/>
            <label>Start Date</label><input type="text" name="start_date" /><br/>
            <label>End Date </label><input type="text" name="end_date" /><br/>
            <label></label><input type="submit" value="Upload" name="upload2"><br/>
        </form>
      </div>

      <div class="col-md-4">
        <h3>Call Option Simulator</h3>
        
        <!-- Option Calculator Form -->
        <form>
            <label>Strike Price </label><input id="strike_price" type="text" name="strike_price"/><br/>
            <label>Expiration Days </label><input id="num_days" type="text" name="num_days"/><br/>
            <input id="calc" type="button" value="Calculate" onclick="calc_python(select_company.value);"/> 
        </form>
      </div>

    </div>

    <br/><br/>

    <!-- SECOND ROW -->
    <div class="row">

    <div class="col-md-8">
        <div id="graph_div" style="width:700px; height:400px;"></div>
    </div>

    <div class="col-md-4">
       <!-- Math Output -->
      <div id="data_div">
        <br/>
        <input type="checkbox" id="linear_regression_checkbox" name="linear_reg" onclick="linear_regression()" >Linear Regression<br>
        <!-- <button id="linReg" onclick="linear_regression()">Linear Regression</button> -->
        <!-- <button id="clear_linReg" onclick="clear_lines()">Clear Regression</button><br/> -->
        <div id="max_div"></div>
        <div id="min_div"></div>
        <div id="avg_div"></div>
        <div id="sd_div"></div>
        <br/>
        <div id="py_test3"></div>
        <div id="py_test"></div>
        <div id="py_test2"></div>
      </div>

    </div>

  </div>

  </div>

  <!-- GLOBAL COMPANY VARIABLE -->
  <script type="text/javascript"> var companies= '<?php echo $result; ?>'; </script>

</body>

</html>