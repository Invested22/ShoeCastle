<!DOCTYPE html>
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
<body>
    <?php
    include("functions.php"); // Include the functions.php file
    include("header.php");
        
    if (isset($_GET['quantity'])){
        addToCart($_GET['product']);
        }
    ?>
  <div class="container-fluid">
  	  <div class="container">
      <?php

    $product_id=$_GET['product'];
          
    echo "<script>console.log($product_id);</script>";
    // Call a function from functions.php, assuming you have a function called db_connect() in functions.php
    $dblink = db_connect();

    // Fetch products from the database
    $sql = "select * from product where product_id='$product_id'";
    $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
    $product=$result->fetch_array(MYSQLI_ASSOC);
          
    echo "<title>".$product['title']."</title>";
    $dblink->close();
          ?>
          <div class="row">
                <div class="col-md-5">
                    <!-- Getting image -->
                    <?php
                    $gif=false;
                    if (file_exists("./images/product_images/$product_id/gif.html")) {
                        echo include("./images/product_images/$product_id/gif.html");
                        $gif=true;
                    } else {
                        $image_path="./images/image_missing.png";
                        if (file_exists("./images/product_images/$product_id/1.png"))
                            $image_path="./images/product_images/$product_id/1.png";    
  					     echo "<img style='max-width:100%;display: block;margin-left: auto;margin-right: auto;' class='card-img-top' src='$image_path' alt='Product image'>";
                    }
                    ?>
              </div>
              <div class="col-md-7">
                    <?php
                  echo "<h1>".$product['title']."</h1>";
                  if (isset($product['brand']))
                      echo "<h3>".$product['brand']."</h3>";
                  echo "<p>$".sprintf('%0.2f',$product['price'])."</p>";
                  echo "<p>Availability: ".$product['QoH']." Units</p>";
                  echo "<p style='text-transform: capitalize;'>".$product['category']."</p>";
                      echo "<p>".$product['description']."</p>";
                  echo "<form method='get' action=''>Quantity:<input name='quantity' type='number' min='1' max=".$product['QoH']." value='1'>&nbsp;Units<br><br><button name='product' value=".$_GET['product']." class='btn btn-primary'>Add to cart</button></form>";
                  ?>
              </div>
          </div>
</div>
    </div>
          <?php
          include("./footer.php");
 ?>
</body>
</html>
