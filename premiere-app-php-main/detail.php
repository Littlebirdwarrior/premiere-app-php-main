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
    displayHeader(); //demarre session


    //Affichage
    foreach($_SESSION['products'] as $index => $product){
        echo '
        <!---description--->
        <div class="description">
            <div class="desc-content">
                <a href="recap.php"><span id="close" class="material-symbols-outlined close">close</span></a>
                <figure>
                    <img src= upload/'. $product["imgPath"].' alt="img"/> 
                    <figcaption>
                        <h2>Mon produit</h2>
                    
                        <span><strong>Prix : </strong>'.number_format($product['price'],2, ",", "&nbsp;"). ' &nbsp;€</span>
                        <span>
                            <strong>Description :</strong>
                            '.$product['desc'].'
                        </span>
                    </figcaption>
                </figure>
            </div>
        </div>
     '; 
}
        
?>

</body>
</html>