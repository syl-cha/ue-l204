<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tests PHP</title>
</head>

<body>
  <p>
    <?php
    $password = '123456';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $hashed_from_db = '$2y$12$8lEF4mLbx1nmmCQtPkVf.OiP146XEotYWBIPPWnZCmp2HyIMbwqBq';
    var_dump($hashed_password);
    echo "<br>Verify : " . password_verify($password, $hashed_password);
    echo "<br>Verify : " . password_verify($password, $hashed_from_db);
    ?>
  </p>
</body>

</html>