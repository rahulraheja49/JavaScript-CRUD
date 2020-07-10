<?php
session_start();
require_once "db/pdo.php";
if(isset($_SESSION['error'])){
  echo '<span style="color:red;text-align:center;">'.$_SESSION['error'].'</span>';
  unset($_SESSION['error']);
}

if(isset($_POST['add'])){
  if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
    if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 || strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1){
      $_SESSION['error'] = "All values are required";
      header("Location: add.php");
      return;
    } else {
      $_SESSION['first_name'] = $_POST['first_name'];
      $_SESSION['last_name'] = $_POST['last_name'];
      $_SESSION['email'] = $_POST['email'];
      $_SESSION['headline'] = $_POST['headline'];
      $_SESSION['summary'] = $_POST['summary'];
      $_SESSION['add'] = "Set";
      header("Location: add.php");
      return;
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Add Profile</title>
  </head>
  <body>
    <form method="post">
      <label for="first_name">First Name</label>
      <input type="text" name="first_name" id="fn">
      <br>
      <label for="last_name">Last Name</label>
      <input type="text" name="last_name" id="ln">
      <br>
      <label for="email">Email</label>
      <input type="text" name="email" id="em">
      <br>
      <label for="headline">Headline</label>
      <input type="text" name="headline" id="hl">
      <br>
      <label for="summary">Summary</label>
      <input type="text" name="summary" id="su">
      <br>
      <input type="submit" name="add" value="Add">
      <a href="index.php">Cancel</a>
    </form>
    <?php
    if(isset($_SESSION['add'])){
      $stmt = $pdo->prepare('INSERT INTO profile (user_id, first_name, last_name, email, headline, summary)
      VALUES ( :uid, :fn, :lastName, :em, :he, :su)');

      $stmt->execute(array(
        ':uid' => htmlentities($_SESSION['user_id']),
        ':fn' => htmlentities($_SESSION['first_name']),
        ':lastName' => htmlentities($_SESSION['last_name']),
        ':em' => htmlentities($_SESSION['email']),
        ':he' => htmlentities($_SESSION['headline']),
        ':su' => htmlentities($_SESSION['summary'])
      ));
      unset($_SESSION['add']);
      $_SESSION['success'] = "Succesfully added";
      header("Location: index.php");
      return;
    }
    ?>
  </body>
</html>
