<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma première application web en PHP</title>
    <!----Style---->
    <!----police---->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <!----icons--->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!----css--->
    <link href="styles.css" rel="stylesheet">


</head>
<body>
    <?php
    //Mes imports
    require ('header.php');

    //Mes fonction d'affichage
    displayHeader();
    ?>
    <!----->
    <h1>Ma première application web en PHP 🐘</h1>
    <!----->

    <form action="traitement.php?action=add" method="post" enctype="multipart/form-data"> <!---ici, php, action : cible du form en php, fichier a atteindre lors du post http (method), envois variable ds autre page, ici, T.A--->
        <h2>Ajouter un produit</h2>
        <p>
            <!---attribut "name", ce qui va permettre à la requête de classer le contenu de la saisie dans des clés portant le nom choisi.---->
            <label>
                Nom du produit :
                <input type="text" name="name">
            </label>
        </p>
        <p>
            <label>
                Prix du produit :
                <input type="number" set="any" name="price">
            </label>
        </p>
        <p>
            <label>
                Quantité désiré :
                <input type="number" name="qtt" value="1">
            </label>
        </p>
        <p>
            <label class="ajout-img">
                Image du produit :
                <span>
                <input type="file" name="file">
                <input type="submit" name="submit" value="Ajouter">
                </span>
            </label>
        </p>
        <p>
            <label>
                Description :
                <textarea name="desc" rows="5"></textarea>
            </label>
        </p>
        <p>
            <!----attribut "name" qui permettra de vérifier côté serveur que le formulaire a bien été validé par l'utilisateur.------>
            <input class="button light" type="submit" name="submit" value="Ajouter le produit">
        </p>

    </form>
    <div class="control">
    <a class="button" href="recap.php">Voir le panier</a>
    </div>

</body>
</html>