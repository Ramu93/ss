<?php session_start();
if(isset($_SESSION['login'])){
    header('Location: index.php');
    exit();
}
require('dbconfig.php');
$errmsg = '';
if(isset($_POST['login_button'])){
    $loginid = mysqli_real_escape_string($dbc,trim($_POST['username']));
    $password = md5(mysqli_real_escape_string($dbc,trim($_POST['password'])));
    $query = "SELECT * FROM employee_master WHERE loginid = '$loginid'";
    $result = mysqli_query($dbc, $query);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        if($password == $row['password']){
            $_SESSION['login'] = 'yes';
            $_SESSION['userid'] = $row['employee_master_id'];
            $_SESSION['loginid'] = $loginid;
            $_SESSION['loginname'] = ucfirst($row['employee_name']);
            $_SESSION['rolename'] = $row['rolename'];
            header('Location: index.php');exit();
        }else{
            $errmsg = "Login ID / Password mismatch";
        }
    }else{
        $errmsg = "Login ID not available";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Sai Baba Business Solutions</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/matrix-login.css" />
        <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="img/favicon.png" />
        <style type="text/css">
            .error{
                color:red;
            }
        </style>
    </head>
    <body>
        <div id="loginbox">            
            <form id="loginform" class="form-vertical" action="login.php" method="POST">
				 <div class="control-group normal_text"> <h3><img src="img/logo.png" alt="Logo" /></h3></div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lg"><i class="icon-user"> </i></span><input type="text" placeholder="Username" name="username" id="username" required value="<?php echo isset($loginid)?$loginid:''; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password" placeholder="Password" name="password" required />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <!--span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span>
                    <span class="pull-right"><a type="submit" href="index.html" class="btn btn-success" /> Login</a></span-->
                    <span class="pull-left error"><h4><?php echo $errmsg; ?></h4></span>
                    <span class="pull-right"><button type="submit" class="btn btn-success" name="login_button">Login</button></span>
                </div>
            </form>
            <form id="recoverform" action="#" class="form-vertical">
				<p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><a class="btn btn-info"/>Recover</a></span>
                </div>
            </form>
        </div>
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/matrix.login.js"></script> 
        <script type="text/javascript">
        $(window).bind("load", function() {
            $('#username').focus();
        });
        </script>
    </body>

</html>
