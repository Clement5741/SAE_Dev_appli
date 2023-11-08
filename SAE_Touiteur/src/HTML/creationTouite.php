<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
    <title>Créer un touite</title>
</head>
<body>
    <h1>Créer votre touite : </h1>
    <main>
        <?php
           echo '
             <form action="" method="post">
                <label for="contenu">Contenu : </label><br>
                <textarea name = "contenu" rows = "10" clos="40"></textarea><br><br>

                <input type="submit" value="Poster">

             </form>';

           $contenu = '';

           if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $contenu = filter_var($_POST['contenu'], FILTER_SANITIZE_STRING);
           }
        ?>
    </main>
</body>
</html>
