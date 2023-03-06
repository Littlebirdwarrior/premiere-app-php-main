<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif des produits</title>

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
    /*recap.php devra nous permettre d'afficher
    de manière organisée et exhaustive la liste des produits présents en session (T.A de t.a produits).
     Elle doit également présenter le total de l'ensemble de ceux- ci. (dans un tableau)*/

    //Mes imports
    require ('header.php'); //importe le session start

    //Mes fonctions
     function displayCart(){
         echo "<h1>Mon panier</h1>";

         //**Afficher le panier
         /*Soit la clé "products" du tableau de session $_SESSION n'existe pas : !isset()
            Soit cette clé existe mais ne contient aucune donnée : empty(), ordre important*/
         if((!isset($_SESSION['products']))|| empty($_SESSION['products'])){
             echo "<div class='null'><p>Aucun produit en session...</p></div>";
         }
         else{
             echo "<table>
                     <thead>
                        <tr>
                          <th>Index</th>
                          <th>Name</th>
                          <th>Prix unitaire</th>
                          <th>Quantité</th>
                          <th>Total</th>
                          <th>Action</th>
                        </tr>
                     </thead>
                      <tbody>";
             //calcul du total
             $totalAllCart = 0;
             foreach($_SESSION['products'] as $index => $product){ //BOUCLE ITÉRATIVE,  affiche les produits qui sont des T.A
                 $total = $product["price"]*$product["qtt"];
                 echo "<tr>
                          <td>".$index."</td>
                          <td><a href='traitement.php?action=detail&id=$index'>".$product['name']."</a></td>
                          <td>".number_format($product['price'],2, ",", "&nbsp;"). "&nbsp;€</td>
                          <td>
                                <a class='up-qtt' href='traitement.php?action=up-qtt&id=$index'>+</a>
                                " .$product['qtt']. "
                                <a class='lower-qtt' href='traitement.php?action=lower-qtt&id=$index'>-</a>
                          </td>
                          <td>" .number_format($total,2, ",", "&nbsp;")."&nbsp;€</td>
                          <td><a class='delete-qtt' href='traitement.php?action=delete&id=$index'>supprimer</a></td>
                          <!--on fait passer l'identifiant (index) du produit à 
                          l'URL pour qu'on puisse le récupérer dans traitement.php 
                          afin de cibler le bon produit pour pouvoir le supprimer en finalité--->
                       </tr>";
                 $totalAllCart += $total;
             }
                 echo  "  <tr>
                            <th colspan='4'>Total général : </th>
                            <td><strong>".number_format($totalAllCart,2, ",", "&nbsp;")."&nbsp;€</strong></td>
                        </tr>
                        </tbody>
                     </table>";
            }

     }

     function displayControls(){
         echo '
            <div class="control">
                <a class="button clear" href="traitement.php?action=clear">Vider le panier</a>
                <a class="button light" href="#">Valider</a>
            </div>
         ';
     }




    //*Affichage dans le HTML
    displayHeader();
    displayCart();
    displayControls();
    ?>


</body>
</html>

