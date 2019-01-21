<?php
  $pdo = new PDO('sqlite:chinook.db');

  if (isset($GET["genre"])){

    $sql = "
      SELECT genres.GenreId
      From genres
      WHERE genres.Name = :name";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(':name', $_GET['genre'], PDO::PARAM_STR, strlen($_GET['genre']));
    $statement->execute();

    $genreIdObj = $statement->fetchAll(PDO::FETCH_OBJ);
    $genreId = $genreIdObj[0]->GenreId;

    $sql2 = "
      SELECT
        tracks.Name,
        tracks.UnitPrice,
        albums.Title,
        artists.artistId
      From tracks
      INNER JOIN albums
      ON tracks.albumId = albums.albumId
      INNER JOIN artists
      ON albums.artistId = artists.artistId
      WHERE tracks.GenreId = :genreId";

    $statement2 = $pdo->prepare($sql2);
    $statement2->bindParam(':genreId', $genreId, PDO::PARAM_INT);
    $statement2->execute();

    $tracks = $statement2->fetchAll(PDO::FETCH_OBJ);

  }
  else {
    $sql = "
      SELECT
        tracks.Name,
        tracks.UnitPrice,
        albums.Title,
        artists.artistId
      From tracks
      INNER JOIN albums
      ON tracks.albumId = albums.albumId
      INNER JOIN artists
      ON albums.artistId = artists.artistId";

    $statement = $pdo->prepare($sql);
    $statement->execute();

    $tracks = $statement->fetchAll(PDO::FETCH_OBJ);
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Tracks</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
  <table class='table'>
    <tr>
      <th>Track Name</th>
      <th>Artist Name</th>
      <th>Album Title</th>
      <th>Price</th>
    </tr>
    <?php foreach ($tracks as $track) : ?>
      <tr>
        <td>
          <?php echo $track->Name ?>
        </td>
        <td>
          <?php
            // there is probably a better way of doing this. TODO Figure out better way.
              $sql3 = "
                SELECT artists.Name
                From artists
                WHERE artists.artistId = ?
              ";
              $statement3 = $pdo->prepare($sql3);
              $statement3->bindParam(1, $track->ArtistId, PDO::PARAM_INT);
              $statement3->execute();
              $artistName = $statement3->fetchALL(PDO::FETCH_OBJ);
              echo $artistName[0]->Name;
            ?>
        </td>
        <td>
          <?php echo $track->Title ?>
        </td>
        <td>
          <?php echo $track->UnitPrice ?>
        </td>
      </tr>
    <?php endforeach ?>
  </table>
</body>
</html>
