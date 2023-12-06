<!DOCTYPE html>
<?php include("functions.php"); db_connect();?>
<html lang="en">
	
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>
    <!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/justified-nav.css" rel="stylesheet">
</head>
<?php include("./header.php"); ?>
<body>
<?php
    # Updating Quantity
    if (isset($_GET['update'])) {
        $customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
        $product_id=$_GET['update'];
        $quantity=$_GET[$product_id];
        $sql="update `cart_item` set `quantity`='$quantity' where `product_id`='$product_id' and `customer_id`='$customer_id'";
        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    } elseif (isset($_GET['remove'])) {
        $customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
        $product_id=$_GET['remove'];
        $sql="delete from `cart_item` where `product_id`='$product_id' and `customer_id`='$customer_id'";
        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    }
    
    # Going to checkout
    if (isset($_GET['go_to_checkout'])) {
        redirect("./checkout.php?discount=".$_GET['go_to_checkout']);
    }
?>
    <div class="container-fluid">
        <div class="container">
      
            <div>
                <h1 class="text-center" style="background-color: #333;color: #fff;text-align: center;padding: 20px;">My Shopping Cart</h5>
            </div>
<?php
    # Checking to see if anything is in the cart
    $customer_id=getCustomerIdFromIP($_SERVER['REMOTE_ADDR']);
    $sql="select * from `cart_item` where `customer_id`='$customer_id'";
    $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    if ($result->num_rows==0) {
        echo '<div class="jumbotron">You have no items in your cart</div></div></div>';
        include("./footer.php");
        echo '</body></html>';
        $dblink->close();
        return; # This prevents anything below from running
    }
?>
            <div class="table-responsive table-bordered">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td width="35%">Image</td>
                            <td>Name</td>
                            <td>Quantity</td>
                            <td>Total</td>
                            <td>Action</td>
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
        echo '<tr><td>';
        if (file_exists("./images/product_images/$product_id/gif.html")) {
            echo include("./images/product_images/$product_id/gif.html");
        } else {
            $image_path="./images/image_missing.png";
            if (file_exists("./images/product_images/$product_id/1.png"))
                $image_path="./images/product_images/$product_id/1.png";    
            echo "<img style='width:100%;display: block;margin-left: auto;margin-right: auto;border-radius:25px;' class='card-img-top' src='$image_path' title='".$product['title']."' alt='Product Image'>";
        }
        echo '</td>';
        
        # Product Name
        echo '<td><a style="text-decoration:none;" href="./product.php?product='.$product_id.'">'.$product['title'].'</td>';
        
        # Quantity
        echo '<td><input name="'.$product_id.'" value="'.$item['quantity'].'" type="number" min="1" max="'.$product['QoH'].'" placeholder="'.$item['quantity'].'">';
        echo '<button class="btn btn-info" name="update" value="'.$product_id.'">Update</button></td>';
        $total_quantity+=$item['quantity'];
        
        # Total
        $price=$item['quantity']*$product['price'];
        echo '<td>$'.sprintf('%0.2f',$price).'</td>';
        $subtotal+=$price;
        
        # Action
        echo '<td><button class="btn btn-danger" name="remove" value="'.$product_id.'">Remove from Cart</button></td></tr>';
    } 
?>
                        </form>
                    </tbody>
                </table>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Discount Code</td>
                            <td>Discount</td>
                            <td>Taxes</td>
                            <td>Total Items</td>
                            <td>Subtotal</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="" method="get">
                            <tr>
<?php
    
    # Discount
    $total_price=$subtotal;
    $discount_code="";
    $discount_enabled=false;
    if (isset($_GET['discount_code'])) {
        $sql="select * from `discount` where `code`='".$_GET['discount_code']."'";
        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $discount=$result->fetch_array(MYSQLI_ASSOC);
        if ($result->num_rows!=0) {
            $total_price*=$discount['percent'];
            $discount_code=$discount['code'];
            $discount_enabled=true;
        }
    }
    echo '<td><input name="discount_code" value="'.$discount_code.'" type="text"><button class="btn btn-basic" name="update_discount">Apply Discount</button></td>';
    
    # Discount Amount
    if ($discount_enabled)
        echo '<td style="color:#5CB85C;">-$'.sprintf('%0.2f',$subtotal-$total_price).'</td>';
    else
        echo '<td>$0</td>';
    
    # Taxes
    $taxes=$total_price;
    $total_price*=1.0825;				
    echo '<td>+$'.sprintf('%0.2f',$total_price-$taxes).'</td>';
    
    # Total Items
    echo '<td>'.$total_quantity.' Units</td>';
    
    # Total Price
    if ($discount_enabled)
        echo '<td><p style="text-decoration:line-through;color:#D9534F;">$'.sprintf('%0.2f',$subtotal).'</p><p>$'.sprintf('%0.2f',$total_price).'</p></td>';
    else
        echo '<td>$'.sprintf('%0.2f',$total_price).'</td>';
?>
                                <td>
                                    <button style="align=center" name="go_to_checkout" value="<?php echo $discount_code ?>" class="btn btn-success">Continue to Checkout</button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	<?php include("./footer.php"); ?>
</body>
</html>