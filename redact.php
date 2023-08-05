<?php 
    // inclure la page de connexion
    include_once "connexion.php";
    // vérifier que les données sont envoyées
    if(isset($_POST['send'])){
        // vérifier que l'image et le texte ont été choisis
        if(!empty($_FILES['image']['name']) && isset($_POST['text']) && $_POST['text'] != ""){
            
            // On récupère d'abord le nom de l'image
            $img_nom = $_FILES['image']['name'];

            // Nous définissons un nom temporaire
            $tmp_nom = $_FILES['image']['tmp_name'];

            // On récupère l'heure actuelle
            $time = time();

            // On renomme l'image en utilisant cette formule : heure + nom de l'image (Pour avoir des images uniques)
            $nouveau_nom_img = $time.$img_nom;

            // on déplace l'image dans un dossier appelé "image_bdd"
            $deplacer_img = move_uploaded_file($tmp_nom, "image_bdd/".$nouveau_nom_img);

            if($deplacer_img){
                // si l'image a été mise dans le dossier 
                // insérons le texte et le nom de l'image dans la base de données
                $text = $_POST['text'];
                $req = mysqli_query($con, "INSERT INTO images VALUES (NULL, '$nouveau_nom_img', '$text')");
                // vérifier si la requête fonctionne
                if($req){
                    // si oui, faire une redirection vers la page liste.php
                    header("location: liste.php");
                    exit;
                }else {
                    // si non
                    $message = "Échec de l'ajout de l'image !";
                }
            }else {
                // si non
                $message = "Veuillez choisir une image avec une taille inférieure à 1 Mo !";
            }

        }else {
            // si les champs sont vides, afficher un message
            $message = "Veuillez remplir tous les champs !";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Rédaction d'un article</title>
</head>
<body>
    <a href="liste.php" class="link">Liste des photos</a>
    <p class="error">
        <?php 
            // afficher une erreur si la variable message existe
            if(isset($message)) echo $message;
        ?>
    </p>
    <div class="box">
        <form action="" method="POST" enctype="multipart/form-data"> 
            <span class="text-center">Rédaction d'un article</span>
           
            <div class="input-container">
                <input type="text" name="titre" placeholder="titre">
            </div>

            <div class="imgbox">
                <label>Ajouter une photo</label>
                <input type="file" name="image" placeholder="image">
                <label>Description</label>
            </div>

            <div class="input-container">		
                <input type="text" name="text" placeholder="contenu" class="arti">
            </div>

            <input type="submit" value="Ajouter" name="send" class="btn">
        </form>
    </div>
</body>
</html>
