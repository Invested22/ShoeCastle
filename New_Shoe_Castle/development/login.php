<!doctype html>
<?php include("functions.php");?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Account Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="icon" href="../../favicon.ico">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/justified-nav.css" rel="stylesheet">
</head>
<body>
    <?php
        # Checking to see if user's session is still open
        $user_ip=$_SERVER['REMOTE_ADDR'];
        $dblink=db_connect();
        $sql="select * from `user_ips` where `user_ip`='$user_ip'";
        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $data=$result->fetch_array(MYSQLI_ASSOC);
        $dblink->close();
        if (isset($data['customer_id'])) # Found session
            redirect("search_results.php");
    ?>
    
    <?php include("header.php"); ?>
  <div class="container-fluid">
  	  <div class="container">
    <form id="login" autocomplete="on" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        
        <!-- Email -->
        <div class="form-group">
        <label for="email">Email address</label>
        <?php
            if (isset($_GET['loginErr']))
                echo '<input name="email" type="text" class="form-control" id="email" placeholder="joemama@email.com" value="'.$_GET['loginErr'].'">';
            else
                echo '<input name="email" type="text" class="form-control" id="email" placeholder="joemama@email.com">';
        ?>
        </div>
        
        <!-- Password -->
        <div class="form-group">
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Create your password">
        </div>
        
        <!-- Status -->
        <?php
            if (isset($_GET['loginErr']))
                echo '<p class="alert alert-danger">Entered email or password was wrong</p>';
        ?>
        
        
        <a href="sign_up.php" class="btn btn-basic">Don't have an account? Click here&nbsp;</a>
        
        <button name="submit" class="btn btn-primary" type="submit">Submit</button>
    </form>
    </div></div>
    <?php include("footer.php"); ?>
    
    <?php
    if (isset($_POST["submit"])) { # User has pushed submit and this has been entered
        $email=$_POST['email'];
        $password=$_POST['password'];
        
        $emailClean=addslashes($email); # This is to protect the database from any Injection attacks
        $passwordClean=addslashes($password);
        
        $dblink=db_connect();
        $sql="select * from `customer` where `email`='$emailClean'";
        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $data=$result->fetch_array(MYSQLI_ASSOC);
        $dblink->close();
        if (isset($data['email'])) { # Found account
            if ($data['password'] == $passwordClean) {
                # Log into account
                addUserIPToAccountLoginIPs($data['customer_id']);
                redirect("search_results.php");
        }   }
        redirect("login.php?loginErr=".$email);
    }
    ?>
  </body>
</html>