<?php
require_once "db/pdo.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <title>View profile</title>
  </head>
  <body>
    <h1>Profile Information</h1>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(
      ":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for profile_id';
      header( 'Location: index.php' ) ;
      return;
    }
    echo "<p>
      First Name: ".$row['first_name'].
    "</p>
    <p>
    Last Name: ".$row['last_name'].
    "</p>
    <p>
    Email: ".$row['email'].
    "</p>
    <p>
    Headline: ".$row['headline'].
    "</p>
    <p>
    Summary: ".$row['summary'].
    "</p>";
    ?>
    <a href="index.php">Done</a>
  </body>
</html>
