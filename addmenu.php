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

//acces au MenusManager
$menusManager = new MenusManager($db);
//acces au PlatsManager
$platsManager = new PlatsManager($db);


if (isset($_POST['creer']))
{
  // On crée un nouveau menu.
  $menu = new Menu(['nom' => $_POST['nom'], 'prix' => $_POST['prix']]);


  if ($menusManager->exists($menu->getNom()))
  {
    $message = 'Le nom du menu existe deja.';
    unset($menu);
  }
  else
  {
    $menusManager->add($menu);
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>TP : Restaurant Villa Plaza</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <p>Créer un Menu  : </p>

  <form action="" method="post" enctype="multipart/form-data">
  <p>
    Nom : <input type="text"  name="nom" maxlength="50" />
  </p>
  <p>
    <select name="idplat">
      <?php
      
        $objPlats = $platsManager->selectAllPlats();

        // var_dump($objPlats);

        if (empty($objPlats))
        {
          echo 'Pas de Plats Erreur !';
        }
        else{

          foreach($objPlats as $objPlat) {

            echo '<option value=' , $objPlat->getId() , '>' , $objPlat->getNom() , '</option>';
          }
        }
      ?>

    </select>
  </p>
  <p>
    <input type="submit" value="Créer un menu" name="creer" />
  </p>
</form>
</body>
</html>
