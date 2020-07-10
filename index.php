<?php
session_start();
require_once "db/pdo.php";
if(isset($_SESSION['logged_in'])){
  if(isset($_SESSION['success'])){
    echo '<span style="color:green;text-align:center;">'.$_SESSION['success'].'</span>';
    unset($_SESSION['success']);
  }
  if(isset($_SESSION['error'])){
    echo '<span style="color:red;text-align:center;">'.$_SESSION['error'].'</span>';
    unset($_SESSION['error']);
  }
  ?>
  <!DOCTYPE html>
  <html>
    <head>
      <title>Rahul Raheja's Resume Registry</title>
    </head>
    <body>
      <h1>Rahul Raheja's Resume Registry</h1>
      <p>
        <a href="logout.php">Logout</a>
        <a href="add.php">Add New Entry</a>
      </p>
      <table border="1">
        <?php
        $stmt = $pdo->prepare("SELECT first_name, headline FROM profile WHERE user_id = :user_id");
        $stmt->execute(array(
          ":user_id" => $_SESSION['user_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("SELECT * FROM profile WHERE user_id = :user_id");
        $stmt->execute(array(':user_id' => $_SESSION['user_id']));
        echo "<tr>
                <th>Name</th>
                <th>Headline</th>
                <th>Action</th>
              </tr>";
        foreach($stmt as $row)
            {
              echo '<tr>
                <td><a href="view.php?profile_id='.$row['profile_id'].'">'.$row["first_name"].'</a></td>
                <td>'.$row["headline"].'</td>
                <td>'.
                '<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>'." / ".
                '<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>'.
                '</td>
              </tr>';
            }

            ?>
      </table>
    </body>
  </html>

<?php
} else{
  ?>

  <!DOCTYPE html>
  <html>
    <head>
      <title>Rahul Raheja's Resume Registry</title>
    </head>
    <body>
      <h1>Rahul Raheja's Resume Registry</h1>
      <p>
      <a href="login.php">Please log in</a>
      </p>
    </body>
  </html>

<?php
}
?>
