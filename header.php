<?php
//echo "<h1>" .  . "</h1>";
include "config.php";
$page = basename($_SERVER['PHP_SELF']);
switch ($page) {
  case "single.php":
    if (isset($_GET['id'])) {
      $sql_title = "SELECT * FROM post WHERE post_id = {$_GET['id']}";
      $result_title = mysqli_query($conn, $sql_title) or die("Tile Query Failed");
      $row_title = mysqli_fetch_assoc($result_title);
      $page_title = $row_title['title'];
    } else {
      $page_title = "No Post Found";
    }
    break;
  case "category.php":
    if (isset($_GET['cid'])) {
      $sql_title = "SELECT * FROM category WHERE category_id = {$_GET['cid']}";
      $result_title = mysqli_query($conn, $sql_title) or die("Tile Query Failed");
      $row_title = mysqli_fetch_assoc($result_title);
      $page_title = $row_title['category_name'] . " News";
    } else {
      $page_title = "No Post Found";
    }
    break;
  case "author.php":
    if (isset($_GET['aid'])) {
      $sql_title = "SELECT * FROM user WHERE user_id = {$_GET['aid']}";
      $result_title = mysqli_query($conn, $sql_title) or die("Tile Query Failed");
      $row_title = mysqli_fetch_assoc($result_title);
      $page_title = "News By " . $row_title['first_name'] . " " . $row_title['last_name'];
    } else {
      $page_title = "No Post Found";
    }
    break;
  case "search.php":
    if (isset($_GET['search'])) {

      $page_title = $_GET['search'];
    } else {
      $page_title = "No Search Result Found";
    }
    break;
  default:
    $sql_title = "SELECT websitename FROM settings";
    $result_title = mysqli_query($conn, $sql_title) or die("Tile Query Failed");
    $row_title = mysqli_fetch_assoc($result_title);
    $page_title = $row_title['websitename'];
    break;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title><?php echo $page_title; ?></title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <!-- Font Awesome Icon -->
  <link rel="stylesheet" href="css/font-awesome.css">
  <!-- Custom stlylesheet -->
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      cursor: url('/images/cursor.jpg'), auto; 
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
      background-color: white;
    }

    .left-icons {
      display: flex;
      align-items: center;
    }

    .left-icons img {
      border-radius: 50%;
      width: 50px;
      height: 50px;
      margin-right: -10px;
      /* Overlap the icons */
      transition: transform 0.6s ease, top 2.6s ease;
      position: relative;
    }

    .left-icons img:hover {
      top: -10px;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: black;
    }

    .logo span {
      color: #00A562;
    }

    .right-section {
      display: flex;
      align-items: center;
    }

    .right-section a {
      margin-left: 20px;
      text-decoration: none;
      color: black;
      font-size: 14px;
      width: 135px;
      padding: 12px;
    }

    .support-button {
      background-color: #00A562;
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 14px;
    }

    #logo img {
      width: 40%;
      margin-left: 55%;
      filter: invert(1);
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <div class="header">
    <div class="left-icons">
      <img src="https://storage.googleapis.com/a1aa/image/xiNtI5v9kN4ULx9o4Ksilm660oaum0OgzbZWMeKkJ0eFkXkTA.jpg" alt="LOL icon">
      <img src="https://storage.googleapis.com/a1aa/image/gT9nr7fJSHR8SqyMhkrQthe8Q63ieEVcwhazMBHhHHLIIvInA.jpg" alt="WTF icon">
      <img src="https://storage.googleapis.com/a1aa/image/wlZVReslO61VcKBNCBoyVNfOfsINowAJIfIGN4hUzwncQeicC.jpg" alt="OMG icon">
      <img src="https://storage.googleapis.com/a1aa/image/mxEK4aGgv3b4OFhFGGlRWJL83UFKK6bO08HydbYMjCBC5F5E.jpg" alt="BuzzFeed icon">
    </div>
    <div class="logo">
      <?php
      include "config.php";

      $sql = "SELECT * FROM settings";

      $result = mysqli_query($conn, $sql) or die("Query Failed.");
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          if ($row['logo'] == "") {
            if ($row['websitename'] == true) {
              echo '<a href="index.php"><h1>' . $row['websitename'] . '</h1></a>';
            } else {
              echo '<h1>sds</h1>';
            }
          } else {
            echo '<a href="index.php" id="logo"><img src="admin/images/' . $row['logo'] . '"></a>';
          }
        }
      }
      ?>
    </div>
    <div class="right-section">
      <a class="support-button" href="#">SUPPORT US</a>
    </div>
  </div>


  <!-- /HEADER -->
  <!-- Menu Bar -->
  <div id="menu-bar">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <?php
          include "config.php";

          if (isset($_GET['cid'])) {
            $cat_id = $_GET['cid'];
          }

          $sql = "SELECT * FROM category WHERE post > 0";
          $result = mysqli_query($conn, $sql) or die("Query Failed. : Category");
          if (mysqli_num_rows($result) > 0) {
            $active = "";
          ?>
            <ul class='menu'>
              <li><a href='<?php echo $hostname; ?>'>Home</a></li>
              <?php while ($row = mysqli_fetch_assoc($result)) {
                if (isset($_GET['cid'])) {
                  if ($row['category_id'] == $cat_id) {
                    $active = "active";
                  } else {
                    $active = "";
                  }
                }
                echo "<li><a class='{$active}' href='category.php?cid={$row['category_id']}'>{$row['category_name']}</a></li>";
              } ?>
            </ul>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <!-- /Menu Bar -->