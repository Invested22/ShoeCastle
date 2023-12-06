<!DOCTYPE html>
<?php include("functions.php");?>
<html lang="en">
	
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
    <!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">
</head>
<?php include("./header.php"); ?>

<body>
  <div class="container-fluid">
  	  <div class="container">
	<div>
        <h1 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 20px;">Order Confirmation</h1>
    </div>

    <div class="confirmation-container">
        <h2 class="text-center">Thank you for your order!</h2>
        <p class="text-center">Your order has been successfully placed.</p>
		
		<?php
		$dblink=db_connect();
		$user_ip=$_SERVER['REMOTE_ADDR'];
		$customer_id=getCustomerIdFromIP($user_ip);
		$order=$_GET['order_id'];
		$sql="select * from user_order where order_id = '$order'";
		$search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
		while($data=$search_result->fetch_array(MYSQLI_ASSOC)){
			$order_id=$data['order_id'];
			$order_date=$data['date'];
            $order_price=$data['total_price'];
            $discount_set=false;
            if (isset($data['price_after_discount'])) {
			     $after_discount=$data['price_after_discount'];
                 $discount_code=$data['discount_code'];
                 $discount_amount=$data['discount_percent'];
                 $discount_set=true;
            }
		}
        echo "<div class='container' style='text-align: center;width: 250px;border: 1px solid black'>
            	<h3>Order Details</h3>
            	<ul>
                	<p>Order Number: #$order_id</p>
                	<p>Order Date: ".(new DateTime($order_date))->format('m/d/y')."</p>";
                    if ($discount_set) {
                        echo '<p style="text-decoration:line-through;color:#D9534F;">Before Discount: $'.sprintf('%0.2f',$order_price).'</p>';
                        echo '<p>After Discount: $'.sprintf('%0.2f',$after_discount).'</p>';
                        echo '<p style="color:#5CB85C;"><i>'.$discount_code.' ('.($discount_amount*100).'%)</i></p>';
                    } else
                	   echo "<p>Total Price: $$order_price</p>";
                    echo "
            	</ul>
        	</div>";
		?>
    </div>
          <div style="text-align: center">
          <img width="50%" src="./images/order_confirmation.jpg">
          </div>
    </div>
    </div>
	<?php include("./footer.php"); ?>
</body>
</html>