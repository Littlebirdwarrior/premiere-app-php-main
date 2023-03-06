<?php
/*header.php construit et affiche le header grace à la session*/


session_start(); /*Importé dans le header car sera présente partout,
deux utilités : démarrer une session sur le serveur
pour l'utilisateur courant, ou récupérer la session de ce même utilisateur s'il en avait déjà une. (cookie PHPSESSID dans le navigateur client)
, cela permet de recupérer la session avec tout son centnu  ,
NB: si soucis avec la session, utiliser session_destroy().*/


//**Créer un panier de produit
//compter le nombre d'élément du panier
function getWholeQuantity(){
    $productCount = 0;
    if(isset($_SESSION['products'])){//si la session existe
        foreach($_SESSION['products'] as $index => $product){//pour chaque produit de la session, incrementé
            $productCount += $product["qtt"];
        }

    }
    return $productCount;//return en dehors de la boucle, sinon boucle casse !
}

function displayHeader() {
    echo '<header>
            <nav>
                <ul>
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="recap.php" target="_self">Voir le panier
                         <span class="wholeQuantity">'.getWholeQuantity().'</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </header>';
}

?>