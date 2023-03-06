<!----
session:

cookie:

superglobales:

requete http:

faille xss:
---->


<?php
    /*Ici, on retrouve le php qui traite le formulaire et construit les informations*/

    session_start();//ici, pas de header, nécessité de l'importer

    if(isset($_GET['action'])){
        /*quand on clique sur un élément html qui a un attribut action ou un href, on déclanche un if différencié par le switch-case
        les actions se lance grâce aux url*/


        switch($_GET['action']){
        //*-------------------AJOUTER UN PRODUIT---------------------
            case "add":
                //récupérer info form : ce if ne se déclenche que lorsqu'on submit le form
                if(isset($_POST['submit'])){

                   // Image pas obligatoire
                    if(isset($_FILES['file'])){
                       // Initialisation var:
                        $tmpName = $_FILES['file']['tmp_name'];
                        $fileName = $_FILES['file']['name'];
                        $size = $_FILES['file']['size'];
                        $error = $_FILES['file']['error'];

                        // Comment éviter attaque : Récupération de l'extension du fichier:
                        $tabExtension = explode('.', $fileName);
                        $extension = strtolower(end($tabExtension));
                        // Extensions autorisées :
                        $extensions = ['jpg', 'png', 'jpeg', "PNG", "JPG"];
                        // Max size: 40Mb
                        $maxSize = 400000;
                        //Création d'un tableau d'extensions authorisée (extension, taille et erreur)
                        if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){
                           // Php ajout d'un préfixe-id généré pour éviter écrasement (quand on ajoute un fichier avec le même nom)
                            //ET prend le nom du produit pour le nommage :
                            list($nameClean) = explode('.', $_POST["productName"]);
                            $uniqueName = uniqid($fileName, true);//uniqud, renvois un bool,
                            $fileNameValid = $uniqueName.".".$extension;

                            // Upload:
                            move_uploaded_file($tmpName, './upload/'.$fileNameValid);
                        }
                        else{
                            echo "Le fichier est trop volumineux (>40Mb) ou n'est ni un PNG ni un JPG";
                        }
                    }

                    //Création des autre champs de form
                    //Ici, filtre de nettoyage remplace les REGEX
                    $name = filter_input(INPUT_POST,"name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $price = filter_input(INPUT_POST,"price", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $qtt = filter_input(INPUT_POST,"qtt", FILTER_VALIDATE_INT);
                    $desc = filter_input(INPUT_POST, "desc", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    //ici, stocker nos données en session, en ajoutant celles-ci au tableau $_SESSION
                    if($name && $price && $qtt){ //NB: filtre renverra false ou null s'il échoue,vérifie si chaque variable contient une valeur jugée positive par PHP

                        $product = [
                            "name" => $name,
                            "price" => $price,
                            "qtt" => $qtt,
                            "desc" => $desc,
                             "imgPath" =>  $fileNameValid,
                        ];
                        // Enregistre le product de la session dans tableau A., indique la clé product, nouvelle entrée
                        $_SESSION['products'][] = $product;//Ici, ajoute nouveau product à session, var Product initialisé dans session
                        $_SESSION["message"] = "Panier crée";
                        echo '<script>alert("Votre produit est ajouté")</stript>';
                    }


                }

                header("Location:index.php");//redirige vers la page index.php
                break;
        //*-------------------VIDER LE PANIER---------------------
            case "clear":
                unset($_SESSION['products']);
                $_SESSION["message"] = "Panier supprimé";
                header("Location:recap.php");
                break;
        //*-------------------SUPPRIMER UN PRODUIT---------------------
            case "delete":
                /*On identifie le produit correspondant à l'id transité par l'URL
                On vérifie qu'il existe dans le tableau des produits*/
                if(isset($_GET["id"]) && isset($_SESSION["products"][$_GET["id"]])){
                  $deleteProduct = $_SESSION["products"][$_GET["id"]]; //designe le produit
                  unset($_SESSION["products"][$_GET["id"]]);//On le supprime de la session
                  //redirection
                  header("Location:recap.php");//On redirige vers le panier
                  die();//On die pour être sur que la propagation s'arrête
                }
                else $_SESSION["message"] = "Action impossible";
                break;


        //*-------------------AUGMENTER LA QUANTITÉ DU PANIER---------------------
            case "up-qtt":
                if(isset($_GET["id"]) && isset($_SESSION["products"][$_GET["id"]])){//$_GET est superglobale, accessible ds tt app
                    $upProduct = $_SESSION["products"][$_GET["id"]]; //designe le produit
                    //On le supprime de la session
                    $_SESSION["products"][$_GET["id"]]['qtt'] += 1; //j'ajoute 1
                    //redirection
                    header("Location:recap.php");//On redirige vers le panier
                    die();//On die pour être sur que la propagation s'arrête
                }
                else $_SESSION["message"] = "Ajout impossible";
                break;
        //*-------------------DIMINUER LA QUANTITÉ DU PANIER---------------------
            case "lower-qtt":
                if(isset($_GET["id"]) && isset($_SESSION["products"][$_GET["id"]])){
                    $lowerProduct = $_SESSION["products"][$_GET["id"]]; //designe le produit
                    $_SESSION["products"][$_GET["id"]]['qtt'] -= 1; //j'enlève 1
                    //On le supprime de la session (une fois arrivé à 0 seulement, car < et <= marche pas)
                    if(($_SESSION["products"][$_GET["id"]]['qtt'])==0){ //si qtt strictement sup à 0
                        unset($_SESSION["products"][$_GET["id"]]);//On le supprime de la session
                    }
                    //redirection
                    header("Location:recap.php");//On redirige vers le panier
                    die();//On die pour être sur que la propagation s'arrête
                }
                else $_SESSION["message"] = "Retrait impossible";
                break;
        //*-------------------AFFICHER LE DETAIL D'UN PRODUIT---------------------
            case "detail":
                if(isset($_GET["id"]) && isset($_SESSION["products"][$_GET["id"]])){

                    //Affichage
                    echo '
                    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
                    <!----icons--->
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
                    <!----css--->
                    <link href="styles.css" rel="stylesheet">
                
                    <!---description--->
                    <div class="description">
                        <div class="desc-content">
                            <a href="recap.php"><span id="close" class="material-symbols-outlined close">close</span></a>
                            <figure>
                                <img src= upload/'. $_SESSION["products"][$_GET["id"]]["imgPath"].' alt="img"/> 
                                <figcaption>
                                    <h2>Mon produit</h2>
                                
                                    <span><strong>Prix : </strong>'.number_format($_SESSION["products"][$_GET["id"]]['price'],2, ",", "&nbsp;"). ' &nbsp;€</span>
                                    <span>
                                        <strong>Description :</strong>
                                        '.$_SESSION["products"][$_GET["id"]]['desc'].'
                                    </span>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                    ';     
                }
                else $_SESSION["message"] = "Détail impossible à afficher";
                break;
        }
    }


    ?>


