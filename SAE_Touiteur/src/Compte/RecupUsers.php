<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <title>Projet test</title>
</head>
<body>
<h1>Test</h1>

<?php

echo "<p>Connection</p>";
try {
    $connexion = new PDO('mysql:host=localhost;port=3306;dbname=projet_dev_appli', 'root', '');
    echo "<p>Connected successfully</p>";
} catch (Exception $e) {

    echo "<p>Not connected</p>";
    die('Erreur : ' . $e->getMessage());
}


$sql = "select * from users";


$resultset = $connexion->prepare($sql);
$resultset->execute();

echo "<table border='1'>";
echo "<tr>";
echo "<th>idUser</th>";
echo "<th>username</th>";
echo "<th>name</th>";
echo "<th>firstname</th>";
echo "<th>email</th>";
echo "<th>password_hash</th></tr>";


while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['idUser'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['firstname'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['password_hash'] . "</td></tr>";
}
echo "</table>";

?>
</body>
</html>