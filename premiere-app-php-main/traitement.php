<!----
session:
Une session en PHP correspond à une façon de stocker des données différentes pour chaque utilisateur en utilisant un identifiant de session unique. (cookie PHPSESSID envoyé au navigateur).
Les sessions permettent de conserver des informations pour un utilisateur lorsqu’il navigue d’une page à une autre. 
De plus, les informations de session ne vont cette fois-ci pas être stockées sur les ordinateurs de 
vos visiteurs à la différence des cookies mais plutôt côté serveur ce qui fait que les sessions vont pouvoir être beaucoup plus sûres que les cookies.
- Une session démarre dès que la fonction session_start() est appelée et se termine en général dès que la fenêtre courante du navigateur est fermée.
- La superglobale $_SESSION est un tableau associatif qui va contenir toutes les données de session une fois la session démarrée.
source : https://www.pierre-giraud.com/php-mysql-apprendre-coder-cours/session-definition-utilisation/
https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql/4239476-conservez-des-donnees-grace-aux-sessions-et-aux-cookies

cookie:
Un cookie est un petit fichier texte qui ne peut contenir qu’une quantité limitée de données.Les cookies vont être stockés sur les ordinateurs de vos visiteurs. 
Ils sont utilisé pour faciliter la vie des utilisateurs en préenregistrant des données les concernant comme un nom d’utilisateur par exemple. 
Dès qu’un utilisateur connu demande à accéder à une page de notre site, les cookies vont également automatiquement être envoyées dans la requête de l’utilisateur.
- Un utilisateur peut lui même supprimer les cookies de son ordinateur à tous moment.
- On pourra définir la date d’expiration d’un cookie.
- Aucune maitrise ni aucun moyen de les sécuriser après le stockage (jamais de données sensibles sur les cookies).
- Pour récupérer la valeur d’un cookie, nous allons utiliser la variable superglobale $_COOKIE.
source: https://www.pierre-giraud.com/php-mysql-apprendre-coder-cours/cookie-creation-gestion/

superglobales:
Cette superglobale va nous permettre d’accéder à des variables définies dans l’espace global depuis n’importe où dans le script et notamment depuis un espace local (dans une fonction).
 Quand on la test avec isset(), renvois tjs quelque chose. 
Ces variables sont automatiquement stockée dans superglobale $GLOBALS, qui est un tableau associatif qui contient les noms des variables créées dans l’espace global en index et leurs valeurs en valeurs du tableau.

Ce tableau est un tableau associatif qui contient les noms des variables créées dans l’espace global en index et leurs valeurs en valeurs du tableau.
Il y en a 9:
$GLOBALS
$_SERVER
$_GET
$_POST
$_FILES
$_COOKIE
$_SESSION
$_REQUEST
$_ENV

requete http:
Le HTTP est le protocole qui permet d’échanger des pages web entre le client et le serveur. HTTP : le protocole qui permet d’échanger des pages web entre le client et le serveur.
GET,(utilisée pour demander une ressource)
POST, (envoyer de grandes quantités de données, par exemple des images, ou des données confidentielles de formulaires au serveur)
HEAD, (interroger l’en-tête de la réponse, sans que le fichier ne vous soit envoyé immédiatement.)
OPTIONS,(demander quelles méthodes le serveur supporte pour le fichier en question)
TRACE(utilisée pour tracer le chemin qu’une requête HTTP emprunte jusqu’au serveur puis jusqu’au client.)
Méthodes spécifiques( ne sont applicables que dans le cadre de configurations spécifiques, ex: PATCH, PROPFIND, PROPPATCH, MKCOL, COPY, MOVE, LOCK, UNLOCK )
source: https://www.ionos.fr/digitalguide/hebergement/aspects-techniques/requete-http/


faille xss:
Une faille xss (cross-site scripting) est une vulnérabilité permettant d'injecter du contentus dans une page 
pour prendre le contrôle de votre navigateur, le plus souvent grâce aux cookies ou au session. L'attaquant peut utiliser tous les languages pris en charge par le navigateur (Js, html, css) 
pour injecter un script malveillant.
Un exemple classique d'attaque est la redirection vers un autre site pour de l'hameçonnage, ou le vols de session grâce au cookie.
source : Openclassroom et https://fr.wikipedia.org/wiki/Cross-site_scripting

en php on utlise les filer input , filter_var, htmlentities, htmlspecialchars

ob_start() / temporisation de sortie:
PHP peut bloquer l’envoi des données au navigateur grâce à la fonction ob_start() qui enclenche une temporisation de sortie. 
Cela signifie que les données ne sont pas directement envoyées mais temporairement mises en tampon.
L’intérêt de la temporisation est de pouvoir travailler sur le contenu avant de l’envoyer au navigateur.
Le tempon, c'est une variables qui met en template une page.

---->


<?php
    /*Ici, on retrouve le php qui traite le formulaire et construit les informations*/

    session_start();//ici, pas de header, nécessité de l'importer pour demarrer la session

    if(isset($_GET['action'])){ //Si il y a le mot action dans l'URK
        /*quand on clique sur un élément html qui a un attribut action ou un href, on déclanche un if différencié par le switch-case
        les actions se lance grâce aux url*/


        switch($_GET['action']){
        //*-------------------AJOUTER UN PRODUIT---------------------
            case "add": // 
                //récupérer info form : ce if ne se déclenche que lorsqu'on submit le form
                if(isset($_POST['submit'])){

                   // Image pas obligatoire pour le submit, du coup, on conditionne son execution
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
                    header("Location:detail.php");
                    die();
                }
                else $_SESSION["message"] = "Détail impossible à afficher";
                break;
        }
    }


    ?>


