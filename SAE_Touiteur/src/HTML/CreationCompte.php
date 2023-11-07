<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
</head>
<body>

<?php

use Compte\Test;

require_once '../Compte/Test.php';

$t = new Test();
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo $t->methode_GET();
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo $t->methode_POST();

}
?>

</body>
</html>
