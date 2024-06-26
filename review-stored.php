<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" , initial-scale="1.0">
    <title>UNITOP/Receipt</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" 
        rel="stylesheet"
    />
    <link rel="stylesheet" href="css/home-page.css"/>
    <link rel="stylesheet" href="css/write-review.css">
    <link rel="shortcut icon" type="icon" href="assests/Banners/logo.png"/>
</head>

<body>
    <!--Header - brand logo and navigation bar-->
    <header>
        <!--LOGO-->
        <div class="navbar">
            <img src="assests/Navbar/UT-new-logo.png" width="100px" alt="UNITOP logo">
            
            <!--Search bar - products to be searched through by name-->
            <?php include('php/search.php'); ?>
            
            <!--NAVIGATION BAR-->
            <div class="links">
                <nav>
                    <div class="img-links">
                        <a href="index.php"><img src="assests/Navbar/home_4991416.png" class="home-icon"></a>
                        <a href="accounts.php"><img src="assests/Navbar/avatar_9892372.png" class="account-icon"></a>
                        <a href="basket.php"><img src="assests/Navbar/checkout_4765148.png" class="basket-icon"></a>
                        <a href="admin_pin.php"><img src="assests/Navbar/staffpic.png" class="staff-icon"></a>
                    </div>
                    <div class="page-links">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="accounts.php">Account</a></li>
                            <li><a href="basket.php">Basket</a></li>
                            <li><a href="admin_pin.php">Staff login</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!--Menu with the categories based on degrees of students-->
    <div class="menu">
        <?php 
        include('php/category.php');
        $categories = getCategories($db);
        /*print_r($categories);*/
        foreach ($categories as $category){
            echo "<a href='products-page.php?category={$category['category_id']}'>{$category['category']}></a>";
        }
        ?>
    </div>

<?php

if(isset($_POST['rating'])){
    require_once('php/connectdb.php');

    echo "<div id='review-store-wrapper'>";
    //collecting form + session data for database
    $prod_id = $_GET['id'];
    $customer_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review-text'];
    //$review = str_replace("'","\'",$review);

    //if user has reloaded this form to overwrite review
    if(isset($_POST['overwrite'])){
        $prev_id = $_POST['rev_id']; //id for past duplicate review sent to be deleted
        
        try{
            $query = "DELETE FROM reviews WHERE review_id =?";
            $deleteReview = $db->prepare($query);
            $deleteReview->execute([$prev_id]);
            echo"<h2>Review has been successfully overwritten</h2>";
        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    $query = "SELECT review_id, review_text, rating FROM reviews WHERE customer_id = ? AND product_id = ?";
    $selectReview = $db->prepare($query);
    $selectReview->execute([$customer_id,$prod_id]);
    $pastReview=$selectReview->fetch();
    if($pastReview && !(isset($_POST['overwrite']))){
        echo"<h2>You have already written a review for this product:</h2> 
        <h3>Rating: ".$pastReview['rating']."/5.0<br>Comment: ".$pastReview['review_text']."";
        echo "<form action='review-stored.php?id=".$prod_id."' method='post'>
        <input type='hidden' id='rating' name='rating' value='".$rating."'>";?>
        <input type='hidden' id='review-text' name='review-text' value="<?php echo htmlentities($review);?>">
        <?php
        echo "<input type='hidden' id='overwrite' name='overwrite' value='overwrite'>
        <input type='hidden' id='rev_id' name='rev_id' value='".$pastReview['review_id']."'>
        <button type='submit'>Overwrite review</button>
        </form>  
        <a href='index.php'><button class='cancel'>Cancel</button></a>";
    }

    //entering review in review table
    if(!$pastReview || isset($_POST['overwrite'])){  //can't store if previous duplicate review has been spotted without user consent for overwriting
        try{
            $query = "INSERT INTO reviews(customer_id,product_id,review_text,rating)  VALUES (?,?,?,?)";
            $enterReview = $db->prepare($query);
            $enterReview->execute([$customer_id,$prod_id,$review,$rating]);
            echo "<h2>Thank you for leaving a review!</h2>
                <a href='index.php'><button>Head back to Homepage</button></a>
                <a href='accounts.php'><button>View your orders</button></a>";
        }catch(PDOException $ex){
            echo "<h2>Review failed to send. Error: </h2>";
            echo $ex->getMessage();
            echo "<a href='accounts.php'><button>Back to Homepage</button></a>";
        }
    }
    echo"</div>";
} else{header('Location:index.php');}
?>

    <!--FOOTER-->
    <footer>
        <div class="footer">
            <div class="footer-box">
                <img src="assests/Navbar/logo-no-slogan.png">
                <h3>UNITOP</h3>
                <p>Educate with UNITOP!</p>
                <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
                <a href="login.php" class="button">Log In</a>
                <?php endif; ?>
            </div>
            <div class="footer-box">
                <h3>Follow Us</h3>
                <div class="socials">
                    <img src="assests/Footer/instagram.png">
                    <img src="assests/Footer/facebook.png">
                    <img src="assests/Footer/linkedin.png">
                </div>
            </div>
            <div class="footer-box">
                <h3>About Us</h3>
                <ul>
                    <li><a href="about-us.html">Who We Are</a></li>
                    <br>
                    <li><a href="about-us.html">Our Mission</a></li>
                    <br>
                    <li><a href="about-us.html">The Team</a></li>
                </ul>
            </div>
            <div class="footer-box">
                <h3>Useful Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <br>
                    <li><a href="contact.php">Contact Us</a></li>
                    <br>
                    <li><a href="about-us.html">About Us</a></li>
                </ul>
            </div>
        </div>
        <div class="line">
            <p>Terms and Conditions apply* | UNITOP Limited</p>
        </div>
    </footer>

</body>
</html>