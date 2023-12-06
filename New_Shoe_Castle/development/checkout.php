<!DOCTYPE html>
<?php include("functions.php"); session_start();?>
<html lang="en">
	
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/justified-nav.css" rel="stylesheet">
</head>
<?php include("./header.php"); ?>
<body>
	<?php
		$dblink=db_connect();
		$customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
        $sql="select * from `customer` where `customer_id`='$customer_id'";
        $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $customer=$search_result->fetch_array(MYSQLI_ASSOC);
        
        if (isset($_GET['discount']) and $_GET['discount']!="") {
            $_SESSION['discount']=$_GET['discount'];
        } else {
            echo "<script>console.log('".$_SESSION['discount']."');</script>";
        }
        
        if (isset($_GET['pay'])) {
            # Creating user_order
            $sql="select * from `cart_item` where `customer_id`='$customer_id'";
            $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
            $total_price=0;
            $total_quantity=0;
            while($item=$search_result->fetch_array(MYSQLI_ASSOC)) {
                $sql="select * from `product` where `product_id`='".$item['product_id']."'";
                $product_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                $product=$product_result->fetch_array(MYSQLI_ASSOC);
                $total_quantity+=$item['quantity'];
                $total_price+=$product['price']*$item['quantity'];
            }
            # Creating order
            $order_id=rand(1,1000000);
            while(1) { # Creating an unique id
                $sql="select * from `user_order` where `order_id`='$order_id'";
                $result_A=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                if ($result_A->num_rows!=0)
                    $order_id=rand(1,1000000);
                else  
                    break;
            }
            if (isset($_SESSION['discount']) and $_SESSION['discount']!="") {
        		$sql="select * from `discount` where `code`='".$_SESSION['discount']."'";
        		$result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        		$discount=$result->fetch_array(MYSQLI_ASSOC);
				$discount_price=$total_price*$discount['percent'];
                $discount_price+=$_GET['shipping_option'];
                $discount_price*=1.0825;
				$sql="insert into `user_order` (`order_id`,`total_price`,`total_bought`,`shipping_status`,`customer_id`,`date`,`discount_code`,`discount_percent`,`price_after_discount`) values ('$order_id','$total_price','$total_quantity','Waiting to be Shipped','$customer_id','".date("Y/m/d")."','".$_SESSION['discount']."','".$discount['percent']."','$discount_price')";
			}else{
				$sql="insert into `user_order` (`order_id`,`total_price`,`total_bought`,`shipping_status`,`customer_id`,`date`) values ('$order_id','$total_price','$total_quantity','Waiting to be Shipped','$customer_id','".date("Y/m/d")."')"; 
			}
            $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
            
            # Creating order_item
            $sql="select * from `cart_item` where `customer_id`='$customer_id'";
            $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
            while($item=$search_result->fetch_array(MYSQLI_ASSOC)) {
                $sql="select * from `product` where `product_id`='".$item['product_id']."'";
                $product_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                $product=$product_result->fetch_array(MYSQLI_ASSOC);
                
                # Deincrementing product quantities
                $new_quantity=$product['QoH']-$item['quantity'];
                $sql="update `product` set `QoH`='$new_quantity' where `product_id`='".$item['product_id']."'";
                $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                
                # Adding to database
                $sql="insert into `order_item` (`amount_bought`,`product_id`,`order_id`,`price`,`title`) values ('".$item['quantity']."','".$item['product_id']."','$order_id','".$item['quantity']*$product['price']."','".$product['title']."')"; 
                $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
            }
            
            # Deleting everything from cart
            $sql="delete from `cart_item` where `customer_id`='$customer_id'";
            $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
            
            redirect("order_confirmation.php?order_id=$order_id");
        }
        
        if (isset($_GET['save_address'])) {
            $sql="insert into `address` (`address`,`city`,`state`,`zip`,`country`,`customer_id`,`name`) values ('".$_GET['address']."','".$_GET['city']."','".$_GET['state']."','".$_GET['zip']."','".$_GET['country']."','$customer_id','".$_GET['name']."')"; 
            $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        }
        
        if (isset($_GET['save_payment'])) {
            $sql="insert into `payment` (`card_number`,`expiration`,`cvv`,`customer_id`) values ('".$_GET['number']."','".$_GET['expiration']."','".$_GET['cvv']."','$customer_id')"; 
            $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        }
    ?>

    <div class="container-fluid">
        <div class="container"><form method="get" action="">
      
            <div>
                <h1 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 20px;">Checkout</h5>
            </div>
            
            <div>
                <h3 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 10px;">Address</h3>
            </div>
            <tr><form method="get" action="">
                <select name="load_address">
                    <option value="">--Select Saved Address Option--</option>
<?php
    # Loading default option
    # Default address
    $sql="select * from `address` where `address_id`='".$customer['default_address_id']."'";
    $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    $address=$search_result->fetch_array(MYSQLI_ASSOC);
    $selected_address=$address['address_id'];
    # If there is a selected address (this takes precedence)
    if (isset($_GET['load_address']) and $_GET['load_address']!="")
        $selected_address=$_GET['load_address'];
    # Getting all addresses under user
    $sql="select * from `address` where `customer_id`='$customer_id'";
    $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    while ($address=$search_result->fetch_array(MYSQLI_ASSOC)) {
        if ($address['address_id']==$selected_address)
            echo '<option value="'.$address['address_id'].'" selected>'.$address['address'].', '.$address['city'].'</option>';
        else
            echo '<option value="'.$address['address_id'].'">'.$address['address'].', '.$address['city'].'</option>';
    }
?>
                </select>
                <button class="btn btn-basic" name="load_address_submit">Load</button>
            </tr>
            <div class="table-responsive table-bordered">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Name</td>
                            <td>Address</td>
                            <td>City</td>
                            <td>State</td>
                            <td>Country</td>
                            <td>Zip</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<?php
    # Name
    $address_to_load=$customer['default_address_id'];
    if (isset($_GET['load_address_submit']) and $_GET['load_address']!="")
        $address_to_load=$_GET['load_address'];
    if ($address_to_load!=NULL) {
        $sql="select * from `address` where `address_id`='".$address_to_load."'";
        $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $address=$search_result->fetch_array(MYSQLI_ASSOC);
        echo '<td><input name="name" value="'.$address['name'].'"></td>';
        echo '<td><input name="address" value="'.$address['address'].'"></td>';
        echo '<td><input name="city" value="'.$address['city'].'"></td>';
        echo '<td><input name="state" value="'.$address['state'].'"></td>';
        echo '<td><input name="country" value="'.$address['country'].'"></td>';
        echo '<td><input name="zip" value="'.$address['zip'].'"></td>';
    } else {
        echo '<td><input name="name"></td>';
        echo '<td><input name="address"></td>';
        echo '<td><input name="city"></td>';
        echo '<td><input name="state"></td>';
        echo '<td><input name="country"></td>';
        echo '<td><input name="zip"></td>';
    }
    echo '<td><input id="save_address" name="save_address" type="checkbox"><label for="save_address">Save as new address</label></td>';
?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <h3 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 10px;">Payment</h3>
            </div>
            <tr>
                <select name="load_payment">
                    <option value="">--Select Saved Payment Option--</option>
<?php
    # Loading default option
    # Default payment
    $sql="select * from `payment` where `payment_id`='".$customer['default_payment_id']."'";
    $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    $payment=$search_result->fetch_array(MYSQLI_ASSOC);
    $selected_payment=$payment['payment_id'];
    # If there is a selected payment (this takes precedence)
    if (isset($_GET['load_payment']) and $_GET['load_payment']!="")
        $selected_payment=$_GET['load_payment'];
    # Getting all payments under user
    $sql="select * from `payment` where `customer_id`='$customer_id'";
    $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    while ($payment=$search_result->fetch_array(MYSQLI_ASSOC)) {
        if ($payment['payment_id']==$selected_payment)
            echo '<option value="'.$payment['payment_id'].'" selected>'.$payment['card_number'].'</option>';
        else
            echo '<option value="'.$payment['payment_id'].'">'.$payment['card_number'].'</option>';
    }
?>
                </select>
                <button class="btn btn-basic" name="load_payment_submit">Load</button>
            </tr>
            <div class="table-responsive table-bordered">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Card Number</td>
                            <td>Expiration</td>
                            <td>Name on Card</td>
                            <td>CVV</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<?php
    # Name
    $payment_to_load=$customer['default_payment_id'];
    if (isset($_GET['load_payment_submit']) and $_GET['load_payment']!="")
        $payment_to_load=$_GET['load_payment'];
    if ($payment_to_load!=NULL) {
        $sql="select * from `payment` where `payment_id`='".$payment_to_load."'";
        $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $payment=$search_result->fetch_array(MYSQLI_ASSOC);
        echo '<td><input name="number" type="number" value="'.$payment['card_number'].'"></td>';
        echo '<td><input name="expiration" value="'.(new DateTime($payment['expiration']))->format('m/y').'"></td>';
        echo '<td><input name="name" value="'.$customer['first_name'].' '.$customer['last_name'].'"></td>';
        echo '<td><input name="cvv" value="'.$payment['cvv'].'"></td>';
    } else {
        echo '<td><input name="number" type="number"></td>';
        echo '<td><input name="expiration" placeholder="mm/yy></td>';
        echo '<td><input name="name"></td>';
        echo '<td><input name="cvv"></td>';
    }
    echo '<td><input id="save_payment" name="save_payment" type="checkbox"><label for="save_payment">Save as new payment</label></td>';
?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <h3 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 10px;">Shipping Options</h3>
            </div>
            <select name="shipping_option">
<?php
    $shipping_option="14.99";
    if (isset($_GET['shipping_option']) and $_GET['shipping_option']!="")
        $shipping_option=$_GET['shipping_option'];
    switch($shipping_option) {
        case "69.99":
            echo '
                <option value="14.99">$14.99 - 3-5 Business Day Shipping</option>
                <option value="39.99">$39.99 - 1-3 Business Day Shipping</option>
                <option value="69.99" selected>$69.99 - 24hr Overnight Shipping</option>';
                break;
        case "39.99":
            echo '
                <option value="14.99">$14.99 - 3-5 Business Day Shipping</option>
                <option value="39.99" selected>$39.99 - 1-3 Business Day Shipping</option>
                <option value="69.99">$69.99 - 24hr Overnight Shipping</option>';
                break;
        default:
            echo '
                <option value="14.99" selected>$14.99 - 3-5 Business Day Shipping</option>
                <option value="39.99">$39.99 - 1-3 Business Day Shipping</option>
                <option value="69.99">$69.99 - 24hr Overnight Shipping</option>';
                break;
    }
?>
            </select>
            <button class="btn btn-basic" name="load_shipping_submit">Select Shipping Option</button>
            
            <div>
                <h1 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 20px;">Order Review</h1>
            </div>
            <div class="table-responsive table-bordered">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td style="text-align:center;">Image</td>
                            <td>Name</td>
                            <td>Quantity</td>
                            <td>Total</td>
                        </tr
                    </thead>
                    <tbody>
                        <form method="get" action="">
<?php
    $customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
    $sql="select * from `cart_item` where `customer_id`='$customer_id'";
    $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    $subtotal=0;
    $total_quantity=0;
    echo "<script>console.log('$result->num_rows');</script>";
    while($item=$result->fetch_array(MYSQLI_ASSOC)) {
        echo "<script>console.log('ran');</script>";
        # Getting the product and image
        $product_id=$item['product_id'];
        $sql="select * from `product` where `product_id`='$product_id'";
        $get_product=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $product=$get_product->fetch_array(MYSQLI_ASSOC);
        echo '<tr><td width=40%>';
        if (file_exists("./images/product_images/$product_id/gif1.html")) {
            echo include("./images/product_images/$product_id/gif1.html");
        } else {
            $image_path="./images/image_missing.png";
            if (file_exists("./images/product_images/$product_id/1.png")) {
                $image_path="./images/product_images/$product_id/1.png";
            }
            echo "<img style='width:20%;display: block;margin-left: auto;margin-right: auto;border-radius:25px;' class='card-img-top' src='$image_path' title='".$product['title']."' alt='Product Image'>";
        }
        echo '</td>';
        
        # Product Name
        echo '<td><a style="text-decoration:none;" href="./product.php?product='.$product_id.'">'.$product['title'].'</td>';
        
        # Quantity
        echo '<td>'.$item['quantity'].' Units</td>';
        
        # Total
        $price=$item['quantity']*$product['price'];
        echo '<td>$'.sprintf('%0.2f',$price).'</td>';
        $subtotal+=$price;
    } 
?>
                        </form>
                    </tbody>
                </table>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Subtotal</td>
                            <td>Discount</td>
                            <td>Shipping</td>
                            <td>Taxes</td>
                            <td>Total</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
<?php
    # Subtotal
    echo '<td>$'.sprintf('%0.2f',$subtotal).'</td>';
    
    # Discount
    $price=$subtotal;
    $total_price=$subtotal;
    $discount_enabled=false;
    if (isset($_SESSION['discount']) and $_SESSION['discount']!="") {
        $sql="select * from `discount` where `code`='".$_SESSION['discount']."'";
        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $discount=$result->fetch_array(MYSQLI_ASSOC);
        if ($result->num_rows==0) # User could mess with url giving invalid discount
            echo '<td>$0.00</td>';
        else {
            $total_price*=$discount['percent'];
            $discount_enabled=true;
            echo '<td style="color:#5CB85C;">-$'.sprintf('%0.2f',$price-$total_price).' ('.$_SESSION['discount'].')</td>';
        }
    } else {
        echo '<td>$0.00</td>';
    }
    
    # Shipping
    $shipping_cost=14.99;
    if (isset($_GET['shipping_option']) and $_GET['shipping_option']!="")
        $shipping_cost=$_GET['shipping_option'];
    $total_price+=$shipping_cost;
    echo '<td>+$'.$shipping_cost.'</td>';
    
    # Taxes
    $taxes=$total_price;
    $total_price*=1.0825;
    echo '<td>+$'.sprintf('%0.2f',$total_price-$taxes).'</td>';
    
    # Total
    if ($discount_enabled)
        echo '<td><p style="text-decoration:line-through;color:#D9534F;">$'.sprintf('%0.2f',$subtotal).'</p><p>$'.sprintf('%0.2f',$total_price).'</p></td>';
    else
        echo '<td>$'.sprintf('%0.2f',$total_price).'</td>';
        
    # Action
?>
                        <td><button name="pay" value="true" class="btn btn-success">Confirm</button></td>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

	<?php include("./footer.php"); ?>
</body>
</html>