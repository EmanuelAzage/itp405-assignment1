<?php
  $pdo = new PDO('sqlite:chinook.db');

  $sql = "
    SELECT genres.GenreId
    From genres
    WHERE genres.Name=" . "'" . $_GET['genre'] . "'";

  $statement = $pdo->prepare($sql);
  var_dump($statement);
  $statement->execute();
  $genreId = $statement->fetchAll(PDO::FETCH_OBJ);
  $genreId = (int)$genreId;

  var_dump($genreId);

  $sql2 = "
    SELECT
      tracks.Name,
      tracks.Composer
    From tracks WHERE tracks.GenreId=" . $genreId
    . "
    LIMIT 15";

  $statement2 = $pdo->prepare($sql2);
  $statement2->execute();

  $tracks = $statement2->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Tracks</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
  <?php
    foreach ($tracks as $track) {
      echo $track->Name . "<br>";
    }
  ?>
</body>
</html>
