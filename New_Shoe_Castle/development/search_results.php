<!DOCTYPE html>
<?php include("functions.php");?>
<html lang="en">
	
<head>
    <meta charset="utf-8">
    <title>Search Results</title>
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
    if(isset($_GET['add_to_cart'])) {
        $dblink=db_connect();
        $sql="select * from `product` where `product_id`='".$_GET['add_to_cart']."'";
        $search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
        $product=$search_result->fetch_array(MYSQLI_ASSOC);
        if ($product['QoH'] > 0)
            addToCart($_GET['add_to_cart']);
        else
	       echo "<script>alert('This item has no stock')</script>";
    }
    include("./header.php"); 
?>
	<div class="container-fluid" style="align-content:center;">
		<br>
		<?php
			if(isset($_GET['Sort'])){
			if($_GET['Sort']=="priceASC"){
				echo "<form class='d-flex'>
				<input type='submit' name='sort'>
				<select name='Sort' id='sort'>
					<option value='priceASC' selected>Sort by price (ascending)</option>
					<option value='priceDESC'>Sort by price (descending)</option>
					<option value='quantityASC'>Sort by quantity (ascending)</option>
					<option value='quantityDESC'>Sort by quantity (descending)</option>
				</select>
				</form>";
			} elseif($_GET['Sort']=="priceDESC"){
				echo "<form class='d-flex'>
				<input type='submit' name='sort'>
				<select name='Sort' id='sort'>
					<option value='priceASC'>Sort by price (ascending)</option>
					<option value='priceDESC' selected>Sort by price (descending)</option>
					<option value='quantityASC'>Sort by quantity (ascending)</option>
					<option value='quantityDESC'>Sort by quantity (descending)</option>
				</select>
				</form>";
			} elseif($_GET['Sort']=="quantityASC"){
				echo "<form class='d-flex'>
				<input type='submit' name='sort'>
				<select name='Sort' id='sort'>
					<option value='priceASC'>Sort by price (ascending)</option>
					<option value='priceDESC'>Sort by price (descending)</option>
					<option value='quantityASC' selected>Sort by quantity (ascending)</option>
					<option value='quantityDESC'>Sort by quantity (descending)</option>
				</select>
				</form>";
			} elseif($_GET['Sort']=="quantityDESC"){
				echo "<form class='d-flex'>
				<input type='submit' name='sort'>
				<select name='Sort' id='sort'>
					<option value='priceASC'>Sort by price (ascending)</option>
					<option value='priceDESC'>Sort by price (descending)</option>
					<option value='quantityASC'>Sort by quantity (ascending)</option>
					<option value='quantityDESC' selected>Sort by quantity (descending)</option>
				</select>
				</form>";
			}
			}else{
				echo "<form class='d-flex'>
				<input type='submit' name='sort'>
				<select name='Sort' id='sort'>
					<option value='priceASC' selected>Sort by price (ascending)</option>
					<option value='priceDESC'>Sort by price (descending)</option>
					<option value='quantityASC'>Sort by quantity (ascending)</option>
					<option value='quantityDESC'>Sort by quantity (descending)</option>
				</select>
				</form>";
			}
		?>
		<br>
		<div class="row px-4 align:center">
			<?php
				$dblink=db_connect();
				if(isset($_GET['Sort'])){
					$sort_type=$_GET['Sort'];
					if($sort_type=='priceASC'){
						$sql="select * from product order by price ASC";
					}
					if($sort_type=='priceDESC'){
						$sql="select * from product order by price DESC";
					}
					if($sort_type=='quantityASC'){
						$sql="select * from product order by QoH ASC";
					}
					if($sort_type=='quantityDESC'){
						$sql="select * from product order by QoH DESC";
					}
				} elseif(isset($_GET['search_data_product'])){
					$search_data=$_GET['search_data'];
					$sql="select * from product where title like '%$search_data%' or description like '%$search_data%' or category like '%$search_data%'";
				} else {
					$sql="select * from product";
				}
				$search_result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
				while($products=$search_result->fetch_array(MYSQLI_ASSOC)){
					$description=$products['description'];
					$brand=$products['brand'];
					$price=$products['price'];
					$QoH=$products['QoH'];
					$color=$products['color'];
					$model=$products['model'];
					$title=$products['title'];
                    $old_price=$products['old_price'];
					$product_id=$products['product_id'];
					echo "<div class='col-md-3'>
                        
                        <p style='color:#FFF;'>.</p>
						<div class='card' style='width: 80%;background-color:#f5f5f5;border-radius:25px;'>";
                    
                    $gif=false;
                    if (file_exists("./images/product_images/$product_id/gif.html")) {
                        echo include("./images/product_images/$product_id/gif.html");
                        $gif=true;
                    } else {
                        $image_path="./images/image_missing.png";
                        if (file_exists("./images/product_images/$product_id/1.png"))
                            $image_path="./images/product_images/$product_id/1.png";    
  					     echo "<img style='max-width:100%;display: block;margin-left: auto;margin-right: auto;border-radius:25px;' class='card-img-top' src='$image_path' alt='Card image cap'>";
                    }
                    echo "<div class='card-body' style='height:188'>
                    
	                           <div class='container-fluid'>
    					       <h5 class='card-title'>$title</h5>";
                    if ($gif==true)
                        echo "<p style='overflow-y: auto; height:60px;' class='card-text'>$description</p>";
                    else
                                if ($old_price!=NULL) {
                        echo "<p style='overflow-y: auto; height:50px;' class='card-text'>$description</p>";
                                    echo "<p class='card-text' style='text-decoration:line-through;'>Price Before: $".sprintf('%0.2f',$old_price)."</p><p>New Price: $".sprintf('%0.2f',$price)."</p>";
                                } else {
                        echo "<p style='overflow-y: auto; height:80px;' class='card-text'>$description</p>";
                                    echo "<p class='card-text'>Price: $".sprintf('%0.2f',$price)."<p>";
                                }
						       echo "<p class='card-text'>Available: $QoH Units</p>
                               <div style='text-align:center'>
    					       <a href='search_results.php?add_to_cart=$product_id' class='btn btn-primary'>Add to cart</a>
						       <a href='product.php?product=$product_id' class='btn btn-info'>Details</a>
                               <p style='color:#F3F3F3;'>.</p>
                               <br>

  						</div>
						</div>
						</div>
						</div>
						</div>";
				}
			?>
		</div>
	</div>
    
				
<?php 
include("./footer.php"); ?>
</body>
	
</html>