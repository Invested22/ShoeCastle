<?php

function db_connect() {
    $database="content";
    $username="webuser";
    $password="Lp73w!]Uala0sQXH";
    $hostname="localhost";
    $dblink=new mysqli($hostname,$username,$password,$database);
    if (mysqli_connect_errno()) {
        die("Error connecting to database: ".mysqli_connect_error());
    }
    return $dblink;
}

function redirect($uri) {
?>
    <script type="text/javascript">
        document.location.href="<?php echo $uri; ?>";
    </script>
    <?php die;
}

# Checks to see if the user's ip is associated with a customer account
function getCustomerIdFromIP($user_ip) {
    $dblink=db_connect();
    $sql="select * from `user_ips` where `user_ip`='$user_ip'";
    $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    $ip=$result->fetch_array(MYSQLI_ASSOC);
    if (isset($ip['customer_id'])) {
        return $ip['customer_id'];
        /* $customer_id=$data_ip['customer_id'];
        $sql="select * from `customer` where `customer_id`='$customer_id'";
        $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $data_customer=$result->fetch_array(MYSQLI_ASSOC);
        if (isset($data_customer['customer_id'])) # Valid customer
            return $data_customer['customer_id'];*/
    }
    echo "<script>console.log('Failed to get customer id from user ip');</script>";
    return false;
}

function addUserIPToAccountLoginIPs($customer_id) {
    $dblink=db_connect();
    $user_ip=$_SERVER['REMOTE_ADDR'];
    $sql="insert into `user_ips` (`user_ip`,`customer_id`) values ('$user_ip','$customer_id')";
    $dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
}

function addToCart($product_id) {
	$dblink=db_connect();
	$user_ip=$_SERVER['REMOTE_ADDR'];
	if (getCustomerIdFromIP($user_ip)) {
		$customer_id=getCustomerIdFromIP($user_ip);
	}
	else {
		$customer_id=0;
	}
	$sql="select * from cart_item where customer_id='$customer_id'";
	$result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
	$sql="insert into cart_item (quantity, product_id, customer_id) values ('1', '$product_id', '$customer_id')";
	while($products=$result->fetch_array(MYSQLI_ASSOC)){
		if($products['product_id']==$product_id){
			$sql="update cart_item set quantity = quantity + 1 where customer_id = '$customer_id' and product_id = '$product_id'";
		}
	}
	$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
	echo "<script>alert('Item added to cart')</script>";
}
?>