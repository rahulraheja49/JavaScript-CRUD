<?php
require_once "db/pdo.php";
session_start();

if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

if (!isset($_GET['profile_id'])){
  $_SESSION['error'] = "Missing profile_id";
  header("Location: index.php");
  return;
}

if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
  if( strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 ||strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1){
    $_SESSION['error'] = "Missing data";
    header("Location: edit.php?profile_id=".$_GET['profile_id']);
    return;
  } else {
    $_SESSION['first_name'] = $_POST['first_name'];
      $_SESSION['last_name'] = $_POST['last_name'];
      $_SESSION['email'] = $_POST['email'];
      $_SESSION['headline'] = $_POST['headline'];
      $_SESSION['summary'] = $_POST['summary'];
      $_SESSION['profile_id'] = $_GET['profile_id'];
      $_SESSION['update'] = 'True';
      header("Location: edit.php?profile_id=".$_GET['profile_id']);
      return;
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Edit page</title>
  </head>
  <body>
    <?php
    if(isset($_SESSION['update'])){
      $sql = "UPDATE profile SET first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE profile_id = :profile_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':first_name' => htmlentities($_SESSION['first_name']),
        ':last_name' => htmlentities($_SESSION['last_name']),
        ':email' => htmlentities($_SESSION['email']),
        ':headline' => htmlentities($_SESSION['headline']),
        ':summary' => htmlentities($_SESSION['summary']),
        ':profile_id' => htmlentities($_GET['profile_id'])
      ));
      $_SESSION['success'] = "Record updated";
      unset($_SESSION['update']);
      unset($_SESSION['first_name']);
      unset($_SESSION['last_name']);
      unset($_SESSION['email']);
      unset($_SESSION['headline']);
      unset($_SESSION['summary']);
      unset($_SESSION['profile_id']);
      header("Location: index.php");
      return;
    }

    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(
      ":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for profile_id';
      header( 'Location: index.php' ) ;
      return;
    }
    $fn = $row['first_name'];
    $ln = $row['last_name'];
    $em = $row['email'];
    $hl = $row['headline'];
    $su = $row['summary']
    ?>
    <form method="post">
      <label for="first_name">First Name</label>
      <input type="text" name="first_name" value="<?= $fn ?>" id="fn">
      <br>
      <label for="last_name">Last Name</label>
      <input type="text" name="last_name" value="<?= $ln ?>" id="ln">
      <br>
      <label for="email">Email</label>
      <input type="text" name="email" value="<?= $em ?>" id="em">
      <br>
      <label for="headline">Headline</label>
      <input type="text" name="headline" value="<?= $hl ?>" id="hl">
      <br>
      <label for="summary">Summary</label>
      <input type="text" name="summary" value="<?= $su ?>" id="su">
      <br>
      <input type="submit" onclick="return Check();" value="Save">
      <a href="index.php">Cancel</a>
    </form>
    <script type="text/javascript">
      function Check() {
        console.log('Checking...');
        try{
          fn = document.getElementById('fn').value;
          ln = document.getElementById('ln').value;
          em = document.getElementById('em').value;
          hl = document.getElementById('hl').value;
          su = document.getElementById('su').value;
          if (fn == null || fn == "" || ln == null || ln == "" || em == null || em == "" || hl == null || hl == "" || su == null || su == "") {
            alert("All fields must be filled out");
            return false;
          }
          if(em.indexOf('@') == -1){
            alert("Email address invalid");
            return false;
          }
          return true;
        } catch(e) {
          return false;
        }
        return false;
      }
    </script>
  </body>
</html>
