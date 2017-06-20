<?php
// remplacer par autoload :
//include_once 'connection.php';

// On enregistre notre autoload.
function chargerClasse($classname)
{
  require $classname.'.php';
}

spl_autoload_register('chargerClasse');

// On appelle session_start() APRÈS avoir enregistré l'autoload.
session_start();

if (isset($_GET['deconnexion']))
{
  session_destroy();
  header('Location: .');
  exit();
}

$db = Db::getInstance();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

$platsManager = new PlatsManager($db);

if (isset($_POST['creer'])) // Si on a voulu créer un personnage.
{
  $plat = new Plat(['nom' => $_POST['nom'], 'prix' => $_POST['prix']]); // On crée un nouveau personnage.

  // il faudra traiter l insertion de l image ici.
  //.... avec $plat


  if ($platsManager->exists($plat->getNom()))
  {
    $message = 'Le nom du personnage est déjà pris.';
    unset($plat);
  }
  else
  {
    $platsManager->add($plat);
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>TP : Mini jeu de combat</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <p>Créer un plat  : </p>

  <form action="" method="post" enctype="multipart/form-data">
  <p>
    Nom : <input type="text"  name="nom" maxlength="50" />
  </p>
  <p>
    Prix : <input type="text" name="prix" />
  </p>
  <p>
    Image : <input type="file" name="image" id="fileToUpload">
  </p>
  <p>
    <input type="submit" value="Créer un plat" name="creer" />
  </p>
</form>
</body>
</html>
