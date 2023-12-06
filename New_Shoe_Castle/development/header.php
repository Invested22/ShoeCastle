<link rel="stylesheet" href="css/nav.css">
<div style="postition:sticky"><div class="nav">
            <img src="./images/dark-logo.png" class="brand-logo" alt="">
            <div class="nav-items">
            <form action="search_results.php" method="get">
                <div class="search">
                    <input name="search_data" type="text" class="search-box" placeholder="search brand, product">
                    <button name="search_data_product" class="search-btn">search</button>
                </div></form>
                <a href="./account.php"><img src="./images/user.png" alt=""></a>
                <a href="shopping_cart.php"><img src="<?php 
                    $user_ip=$_SERVER['REMOTE_ADDR'];
                    $customer_id=getCustomerIdFromIP($user_ip);
                    if ($customer_id!=false) {
                    $dblink=db_connect();
                    $sql="select * from `cart_item` where `customer_id`='$customer_id'";
                    $result=$dblink->query($sql) or die("<p>Something went wrong with: $sql</p>".$dblink->error);
                    
                    $item_count=0;
                    while ($data=$result->fetch_array(MYSQLI_ASSOC))
                        $item_count+=1;
                    
                    echo "./images/cart/cart_";
                    switch($item_count) {
                        case 0:
                            echo "0";
                            break;
                        case 1:
                            echo "1";
                            break;
                        case 2:
                            echo "2";
                            break;
                        case 3:
                            echo "3";
                            break;
                        case 4:
                            echo "4";
                            break;
                        case 5:
                            echo "5";
                            break;
                        case 6:
                            echo "6";
                            break;
                        case 7:
                            echo "7";
                            break;
                        case 8:
                            echo "8";
                            break;
                        case 9:
                            echo "9";
                            break;
                        default:
                            echo "10";
                            break;
                    }} else
                        echo './images/cart/cart_0';
                echo '.png" alt="">';
                    ?>
                    </a>
            </div>
        </div>
        <ul class="links-container">
            <li class="link-item"><a href="index.html" class="link">home</a></li>
            <li class="link-item"><a href="./search_results.php?search_data=women&search_data_product=" class="link">women</a></li>
            <li class="link-item"><a href="./search_results.php?search_data=me&search_data_product=" class="link">men</a></li>
            <li class="link-item"><a href="./search_results.php?search_data=kids&search_data_product=" class="link">kids</a></li>
            <li class="link-item"><a href="https://ec2-18-189-26-34.us-east-2.compute.amazonaws.com/dbadmin/" class="link">admin</a></li>
        </ul></div>

