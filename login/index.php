<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link rel="stylesheet" href="style.css" />
  <title>Document</title>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
  <?php
  // ini_set('display_errors', 1);
  // ini_set('display_startup_errors', 1);
  // error_reporting(E_ALL);
  function paramCheck($params)
  {
    foreach ($params as $param) {
      // var_dump(!isset($param));
      if (!isset($param) || is_array($param) || $param === "") {
        return false;
      }
    }
    return true;
  }
  function checkPassword($passwd)
  {
    if (strlen($passwd) < 8) {
      return false;
    }
    $characters = str_split($passwd);
    $countOFNumbers = 0;
    foreach ($characters as $character) {
      if (is_numeric($character)) {
        $countOFNumbers += 1;
      }
    }
    if ($countOFNumbers < 2) {
      return false;
    }
    return true;
  }
  if (paramCheck(array($_POST["password"], $_POST["Name"], $_POST["gender"], $_POST["emailaddress"]))) {
    if (checkPassword($_POST["password"])) {
      $connection =  new mysqli("192.168.0.170", "facebook", "topSecret", "myFacebook", 5456);
      $command = $connection->prepare('SELECT * FROM User WHERE `Name` = ? OR `Email` = ?');
      $command->bind_param('ss', $_POST["Name"], $_POST["emailaddress"]);
      $command->execute();
      $command = $command->get_result();
      // var_dump($command);
      if ($command->num_rows > 0) {
        echo 'user Already exists';
      } else {
        echo 'creating user';
        $command->close();
        $command = $connection->prepare('INSERT INTO User (`Name`, `Email`, `HashPassword`, `Gender`) VALUES (?,?,?,?)');
        $command->bind_param('ssss', $_POST["Name"], $_POST["emailaddress"], password_hash($_POST["password"], PASSWORD_DEFAULT), strtolower($_POST["gender"]));
        $command->execute();
        $command->close();
      }
    } else {
      echo 'Password not strong anought';
    }
  } else {
    echo 'Fill in all fields.';
  }
  ?>
  <form action="index.php" class="container" method="POST">
    <div class="title center">Registration</div>
    <hr />
    <label class="input-container">
      <i class="fas fa-user-alt icon"></i>
      <input type="text" name="Name" class="field" />
    </label>
    <label class="input-container">
      <i class="fas fa-envelope icon"></i>
      <input type="email" name="emailaddress" class="field" />
    </label>
    <label class="input-container">
      <i class="fas fa-key icon"></i><input type="password" name="password" class="field" />
    </label>
    <div class="center">
      <span class="checkmark"></span><input type="radio" name="gender" value="Male" class="radio" checked /> Male
      <span class="checkmark"></span><input type="radio" name="gender" value="Female" class="radio" /> Female
    </div>
    <input type="submit" value="Submit" class="submit" />
  </form>
</body>

</html>