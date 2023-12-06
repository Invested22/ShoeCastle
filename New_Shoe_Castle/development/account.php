<!DOCTYPE html>
<?php include("functions.php");?>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account</title>
    <!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/justified-nav.css" rel="stylesheet">
  </head>
  <body>
<?php include("header.php"); ?>
  <?php
    # Checking to see if user is logged in, if not then they get redirected to login
    $user_ip=$_SERVER['REMOTE_ADDR'];
    $dblink=db_connect();
    $sql="select * from user_ips where user_ip='$user_ip'"; 
    $ip_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    $ip_data=$ip_result->fetch_array(MYSQLI_ASSOC);
    if (!isset($ip_data)) # Not logged in
        redirect("./login.php");
        
    # Checking on an action
    if (isset($_POST['set_payment'])) {
        $payment_id=$_POST['set_payment'];
        $customer_id=$ip_data['customer_id'];
        $sql="update customer set default_payment_id='$payment_id' where customer_id='$customer_id'"; 
        $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    }
    if (isset($_POST['set_address'])) {
        $address_id=$_POST['set_address'];
        $customer_id=$ip_data['customer_id'];
        $sql="update customer set default_address_id='$address_id' where customer_id='$customer_id'"; 
        $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    }
    if (isset($_POST['remove_payment'])) {
        $payment_id=$_POST['remove_payment'];
        $sql="delete from payment where payment_id='$payment_id'"; 
        $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        # Checking and deleting if the payment was a default payment
        $sql="update customer set default_payment_id=NULL where default_payment_id='$payment_id'"; 
        $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    }
    if (isset($_POST['remove_address'])) {
        $address_id=$_POST['remove_address'];
        $sql="delete from address where address_id='$address_id'"; 
        $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        # Checking and deleting if the address was a default address
        $sql="update customer set default_address_id=NULL where default_address_id='$address_id'"; 
        $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    }
    $dblink->close();
  ?>
<?php
if (isset($_GET['logout'])) { # User has pushed submit and this has been entered
    echo "<script>console.log('Submit pressed');</script>";
    # Logging user out
    $user_ip=$_SERVER['REMOTE_ADDR'];
    $dblink=db_connect();
    $sql="delete from user_ips where user_ip='$user_ip'"; 
    $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    $dblink->close();
    redirect("./login.php");
}
?>
  <div class="container-fluid">
  	  <div class="container">
        <div>
	        <h1 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 20px;">My Account</h5>
        </div>
        <div class="table-responsive table-bordered">
            <table class="table table-striped">
                <thead><tr>
                    <td>Email</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Phone</td>
                </tr></thead>
                <tbody><tr><form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <?php
                        $user_ip=$_SERVER['REMOTE_ADDR'];
                        $dblink=db_connect();
                        $sql="select * from `user_ips` where `user_ip`='$user_ip'";
                        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                        $ip=$result->fetch_array(MYSQLI_ASSOC);
                        $sql="select * from customer where customer_id='".$ip['customer_id']."'";
                        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                        $customer=$result->fetch_array(MYSQLI_ASSOC);
                        $dblink->close();
                        
                        # Masking/Blurring out the user's email
                        $chars_to_be_saved=4;
                        list($email,$domain)=explode('@',$customer['email']);
                        $masked=str_repeat('*',strlen($email) - $chars_to_be_saved);
                        $saved=substr($email,strlen($email) - $chars_to_be_saved,strlen($email));
                        $combined=$masked.$saved.'@'.$domain;
                        
                        echo '<td>'.$combined.'</td>';
                        echo '<td>'.$customer['first_name'].'</td>';
                        echo '<td>'.$customer['last_name'].'</td>';
                        echo '<td>'.'(' . substr($customer['phone'], 0, 3) . ') ' . substr($customer['phone'], 3, 3) . '-' . substr($customer['phone'], 6).'</td>';
                        echo '<td><button name="logout" type="submit" class="btn btn-warning" style="color:#000000;">Logout</button></td>';
                    ?>
                </form></tr></tbody>
            </table>
        </div>
	    <br>
  	    <div id="accordion1" role="tablist">
  	      <div class="card">
  	        <!--
  	        <div class="card-header" role="tab" id="headingTwo1">
  	          <h5 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseTwo1" role="button" aria-expanded="false" aria-controls="collapseTwo1">Payment Options</a></h5>
            </div>
            -->
  	        <div id="collapseTwo1" class="collapse show" role="tabpanel" aria-labelledby="headingTwo1" data-parent="#accordion1">
  	          <div class="card-body">
              <?php
                # Supports American Express, Discover, Visa, Mastercard, JCB
                function printIssuer($card_number) {
                    $len4 = (int)substr($card_number,0,4);
                    $len3 = (int)substr($card_number,0,3);
                    $len2 = (int)substr($card_number,0,2);
                    
                    $path;
                    $alt;
                    $card_height=20;
                    $card_ratio=1.586; # the ratio of bank cards
                    if ($len2==34 or $len2==37) { # American Express 34, 37
                        $path='./images/payment_cards/card_amex.jpg';
                        $alt='American Express Card';
                    } elseif ($len4==6011 or (644<=$len3 and $len3<=649) or $len2==65) { # Discover 6011, 644-649, 65
                        $path='./images/payment_cards/card_discover.png';
                        $alt='Discover Card';
                    } elseif (3528<=$len4 and $len4<=3589) { # JCB 3528–3589
                        $path='./images/payment_cards/card_jbc.png';
                        $alt='JCB Card';
                    } elseif ((2221<=$len4 and $len4<=2729) or (51<=$len2 and $len2<=55)) { # Mastercard 2221–2720 51–55
                        $path='https://imageio.forbes.com/blogs-images/steveolenski/files/2016/07/Mastercard_new_logo-1200x865.jpg?height=512&width=711&fit=bounds';
                        $alt='Mastercard';
                    } elseif ((int)substr($card_number,0,1)==4) { # Visa 4
                        $path='./images/payment_cards/card_visa.jpg';
                        $alt='Visa Card';
                    } else {
                        $path='.images/missing_image.png';
                        $alt='Unknown Issuer Card';
                    }
                    echo '<image style="width:'.$card_height*$card_ratio.'px;height:'.$card_height.'px;" src="'.$path.'" alt="'.$alt.'">';
                }
                
                # grabbing user's payments
                $customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
                $dblink=db_connect();
                $sql="select * from payment where customer_id='$customer_id'";
                $payment_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                if ($payment_result->num_rows==0)
                    echo '<tr><td>You have no payment options saved</tr></td></tbody></table></div>';
                else {
                    echo '<div class="table-responsive table-bordered"><table class="table table-striped"><thead>';
                    echo '<tr><td>Issuer</td><td>Card Number</td><td>Expiration</td><td>CVV</td></tr></thead><tbody>';
                    while ($payment_data=$payment_result->fetch_array(MYSQLI_ASSOC)) {
                        echo '<tr><td>';
                        # An image of the card issuer
                        printIssuer($payment_data['card_number']);
                        
                        # Masking/Blurring user's card number
                        echo '</td><td>'.substr_replace($payment_data['card_number'], str_repeat('*', 12), 0, 12).'</td><td>'.(new DateTime($payment_data['expiration']))->format('m/y').'</td><td>***</td>';
                        
                        ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <?php
                        
                        $payment_id=$payment_data['payment_id'];
                        echo '<td><button name="set_payment" type="submit" value="'.$payment_id.'"';
                        
                        $sql="select * from customer where default_payment_id='$payment_id'";
                        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                        if ($result->num_rows!=0) # This is the default payment
                            echo ' disabled class="btn btn-default"';
                        else
                            echo 'class="btn btn-basic"';
                        echo '>Set Default Payment</button></td><td><button name="remove_payment" type="submit" value="'.$payment_id.'" class="btn btn-danger">Remove</buttom></td></form></tr>';
                    }
                    echo '</tbody></table></div>';
                }
                $dblink->close();
              ?>
              </div>
            </div>
          </div>
          <br>
  	      <div class="card">
  	        <!--
  	        <div class="card-header" role="tab" id="headingThree1">
  	          <h5 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseThree1" role="button" aria-expanded="false" aria-controls="collapseThree1">Addresses</a> </h5>
            </div>
            -->
  	        <div id="collapseThree1" class="collapse show" role="tabpanel" aria-labelledby="headingThree1" data-parent="#accordion1">
  	          <div class="card-body">
              <?php
                $customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
                $dblink=db_connect();
                $sql="select * from address where customer_id='$customer_id'";
                $address_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                if ($address_result->num_rows==0)
                    echo '<tr><td>You have no addresses saved</td></tr></tbody></table></div>';
                else {
                    echo '<div class="table-responsive table-bordered"><table class="table table-striped"><thead><tr><td>Address</td><td>City</td><td>State</td><td>Zip</td><td>Country</td></tr></thead><tbody>';
                    while ($address_data=$address_result->fetch_array(MYSQLI_ASSOC)) {
                        echo '<tr><td>'.$address_data['address'].'</td><td>'.$address_data['city'].'</td><td>'.$address_data['state'].'</td><td>'.$address_data['zip'].'</td><td>'.$address_data['country'].'</td>';
                        ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <?php
                        $address_id=$address_data['address_id'];
                        echo '<td><button name="set_address" type="submit" value="'.$address_id.'"';
                        
                        $sql="select * from customer where default_address_id='$address_id'";
                        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                        if ($result->num_rows!=0) # This is the default address
                            echo ' disabled class="btn btn-default"';
                        else
                            echo'class="btn btn-basic"';
                        echo '>Set Default Address</button></td><td><button name="remove_address" type="submit" value="'.$address_id.'" class="btn btn-danger">Remove</buttom></td></form></tr>';
                        echo '</tr>';
                    }
                    echo '</tbody></table></div>';
                }
              ?>
              </div>
            </div>
          </div>
          <br>
          <div class="card">
  	        <!--
            <div class="card-header" role="tab" id="headingOne1">
  	          <h5 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseOne1" role="button" aria-expanded="true" aria-controls="collapseOne1">My Orders</a> </h5>
            </div>
            -->
  	        <div id="collapseOne1" class="collapse show" role="tabpanel" aria-labelledby="headingOne1" data-parent="#accordion1">
                <div class="card-body">
              <?php
                function createItem($dblink,$item) {
                    echo '<tr><td>';
                    if ($item['price_after_discount']!=NULL) {
                        echo '<p style="text-decoration:line-through;color:#D9534F;">$'.sprintf('%0.2f',$item['total_price']).'</p>';
                        echo '<p>$'.sprintf('%0.2f',$item['price_after_discount']).'</p>';
                        echo '<p style="color:#5CB85C;"><i>'.$item['discount_code'].' ('.($item['discount_percent']*100).'%)</i></p>';
                    } else
                        echo $item['total_price'];
                    echo '</td><td>'.$item['total_bought'].'</td><td>'.$item['shipping_status'].'</td><td>'.(new DateTime($item['date']))->format('m/d/y').'</td></tr>';
                    echo '<tr><table class="table table-striped"><thead><tr><td>Image</td><td>Title</td><td>Total Price</td><td>Quantity</td></tr></thead><tbody>';
                    # grabbing the individual details
                    $id=$item['order_id'];
                    $sql="select * from order_item where order_id='$id'";
                    $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                    while ($entry=$result->fetch_array(MYSQLI_ASSOC)) {
                        echo '<tr><td width=40%><a href="./product.php?product='.$entry['product_id'].'">';
                        $product_id=$entry['product_id'];
                        # Product image might not exist
                        if (file_exists("./images/product_images/$product_id/gif.html")) {
                            echo include("./images/product_images/$product_id/gif.html");
                        } else {
                            $image_path="./images/image_missing.png";
                            if (file_exists("./images/product_images/$product_id/1.png")) {
                                $image_path="./images/product_images/$product_id/1.png";
                            }
                            echo "<img style='width:100%;display: block;margin-left: auto;margin-right: auto;border-radius:25px;' class='card-img-top' src='$image_path' title='".$entry['title']."' alt='Product Image'>";
                        }
                        echo '</a></td><td><a <a href="./product.php?product='.$entry['product_id'].'">'.$entry['title'].'</a></td><td>$';
                        echo sprintf('%0.2f',$entry['price']);
                        echo '</td><td>'.$entry['amount_bought'].'</td></tr>';
                    }
                    echo '</tbody></tr>';
                }
              
                # grabbing user's orders
                $customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
                
                $dblink=db_connect();
                $sql="select * from user_order where customer_id='$customer_id'";
                $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                if ($result->num_rows==0)
                    echo '<tr><td>You have no orders</tr></td></tbody></table></div>';
                else {
                    echo '<div class="table-responsive table-bordered"><table class="table table-striped"><thead>';
                    echo '<tr><td>Total Price</td><td>Quantity</td><td>Shipping Status</td><td>Date</td></tr></thead><tbody>';
                    while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
                        createItem($dblink,$data);
                    }
                    echo '</tbody></table></div>';
                }
                
                $dblink->close();
              ?>
              </div>
            </div>
          </div>
        </div>
    </div>
  	</div>
    
    <?php
        include("footer.php");
    ?>
    
  </body>
</html>