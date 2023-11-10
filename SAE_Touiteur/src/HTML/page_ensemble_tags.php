<!DOCTYPE html>
<html>
<head>
    <title>Détail du Tweet</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/affichage_tweet.css">
</head>
<body>
<div class="tweet-container">
    <a href="page_base_CONNECTER.php" class="back-button">&#8592;</a> <!---&#8592 represent the arrow-->
    <main>
        <?php
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: accueil.html');
        }

        use Touite\GestionTag;

        require_once '../Touite/GestionTag.php';

        $res = GestionTag::getTags();

        echo'
        <br>
        <form action="" method="post">
        <label for="Chercher">Chercher un tag : </label><br>
        <input type="text" name="Chercher" id="Chercher" required>
        <input type="submit" value="Chercher">
        </form>';

        if (isset($_POST['Chercher'])) {
            $searchTerm = $_POST['Chercher'];
            $results = GestionTag::searchTag($searchTerm);

            echo '<p>Résultats de la recherche : </p>';
            echo '<div class="tags-container">';
            if ($results == null) {
                echo 'Aucun résultat';
            }
            foreach ($results as $result) {
                echo "<a href=\"touiteTag.php?tag=" . $result['labelTag'] ."&page=enstag\">#" . $result['labelTag'] . "</a>".'<br>';
            }
            echo '</div>';
        }

        echo '<p><strong>Liste des tags : </strong></p>';
        echo '<div class="tags-container">';
        foreach ($res as $val){
            print($val['idTag']);
            echo': ';
            print("<a href=\"touiteTag.php?tag=" . $val['labelTag'] ."&page=enstag\">#" . $val['labelTag'] . "</a>");
            echo '<br>';
        }
        echo '</div>';

        ?>
    </main>
</div>
</body>
</html>
