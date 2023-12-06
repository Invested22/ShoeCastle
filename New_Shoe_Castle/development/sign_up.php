<!doctype html>
<?php include("functions.php");?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Account Creation</title>
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
    <?php include("header.php"); ?>
  <div class="container-fluid">
  	  <div class="container">
    <form id="contact" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <!-- Email -->
        <div class="form-group">
        <label for="email">Email address</label>
        <input name="email" type="text" class="form-control" id="email" placeholder="joemama@email.com">
        <p id="emailStatus"></p>
        </div>
        
        <!-- First Name -->
        <div class="form-group">
        <label for="firstname">First Name</label>
        <input name="firstname" type="text" class="form-control" id="firstname" placeholder="Ben">
        <p id="firstNameStatus"></p>
        </div>
        
        <!-- Last Name -->
        <div class="form-group">
        <label for="lastname">Last Name</label>
        <input name="lastname" type="text" class="form-control" id="lastname" placeholder="Dover">
        <p id="lastNameStatus"></p>
        </div>
        
        <!-- Phone Number -->
        <div class="form-group">
        <label for="phone">Phone Number</label>
        <input name="phone" type="text" class="form-control" id="phone" placeholder="0123456789">
        <p id="phoneStatus"></p>
        </div>
        
        <!-- Password -->
        <div class="form-group">
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Create your password">
        <p id="passwordStatus"></p>
        </div>
        
        <!-- Password Confirmation -->
        <div class="form-group">
        <label for="passwordConfirmation">Password Confirmation</label>
        <input name="passwordConfirmation" type="password" class="form-control" id="passwordConfirmation" placeholder="Confirm your password">
        <p id="passwordConfirmationStatus"></p>
        </div>
        
        <button name="submit" type="submit" class="btn btn-basic">Submit</button>
    </form>
    </div></div>
    
    <?php
    if (isset($_POST["submit"])) { # User has pushed submit and this has been entered
            echo "<script>console.log('Form submitted');</script>";
            $email=$_POST['email'];
            $firstname=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $phone=$_POST['phone'];
            $password=$_POST['password'];
            $passwordConfirmation=$_POST['passwordConfirmation'];
            
            $nameRegex = '/^[a-zA-z-\']{2,32}$/';
            $emailRegex = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
            $phoneRegex = '/^[0-9]{10}$/';
            $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/';
            
            if (preg_match($emailRegex,$email)) {
                echo "<script>console.log('Email validated');</script>";
                # Here, I am wanting to confirm that the user does not already have an account with this email address.
                $emailClean=addslashes($email); # This is to protect the database from any Injection attacks
                $dblink=db_connect();
                $sql="select * from `customer` where `email`='$emailClean'";
                $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                $data=$result->fetch_array(MYSQLI_ASSOC);
                $dblink->close();
                if (!isset($data['email'])) { # Confirming that there is not a current email account associated
                    echo "<script>console.log('No previous email found');</script>";
                    if (preg_match($nameRegex,$firstname)) {
                        echo "<script>console.log('First name validated');</script>";
                        if (preg_match($nameRegex,$lastname)) {
                            echo "<script>console.log('Last name validated');</script>";
                            if (preg_match($phoneRegex,$phone)) {
                                echo "<script>console.log('Phone validated');</script>";
                                if (preg_match($passwordRegex,$password)) {
                                    echo "<script>console.log('Password validated');</script>";
                                    if ($password==$passwordConfirmation) {
                                        echo "<script>console.log('Passwords match');</script>";
                                        $firstnameClean=addslashes($firstname);
                                        $lastnameClean=addslashes($lastname);
                                        $phoneClean=addslashes($phone);
                                        $passwordClean=addslashes($password);
                                        
                                        # Creating customer
                                        $dblink=db_connect();
                                        $sql="insert into `customer` (`email`,`first_name`,`last_name`,`phone`,`password`) values ('$emailClean','$firstnameClean','$lastnameClean','$phoneClean','$passwordClean')"; 
                                        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                                        
                                        # Adding user's ip to their account
                                            # I need to grab the user's customer_id
                                        $sql="select * from `customer` where `email`='$emailClean'";
                                        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                                        $data=$result->fetch_array(MYSQLI_ASSOC);
                                        $dblink->close();
                                        $customerId=$data['customer_id'];
                                        
                                        addUserIPToAccountLoginIPs($customerId);
                                        
                                        redirect("account.php");
            }   }   }   }   }   }   }
    }
                ?>
    <?php include("footer.php"); ?>
  </body>
</html>
<script src="js/sign_up.js"></script>