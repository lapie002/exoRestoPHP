<?php

class MenusManager {

  private $_db;

  public function setDb(PDO $db)
  {
     $this->_db = $db;
  }

  public function __construct($db)
  {
    $this->setDb($db);
  }

  public function add(Menu $menu)
  {
    // Préparation de la requête d'insertion.
    // Assignation des valeurs pour le Menu.
    // Exécution de la requête.

   $q = $this->_db->prepare('INSERT INTO Menus(NOMMENU,PRIXMENU) VALUES(:nom,:prix)');

   $q->bindValue(':nom',$menu->getNom());
   $q->bindValue(':prix',$menu->getPrix());

   //$q->bindValue(':nom',$menu->getNom(),PDO::PARAM_STR);
   // $q->bindValue(':prix',0.0);
   // $q->bindValue(':image','hello.jpg');

   $q->execute();

  // $q = bindValue(':degats',$perso->_degats,PDO::PARAM_INT);
  //  $q = bindValue(':degats',0);


    // Hydratation du Menu passé en paramètre avec assignation de son identifiant et du prix initial.
    $menu->hydrate(
      ['id'    => $this->_db->lastInsertId(),
      'nom'    => $menu->getNom(),
      'prix'   => $menu->getPrix()]
    );

  }

  public function count()
  {
    // Exécute une requête COUNT() et retourne le nombre de résultats retourné.
    $q = $this->_db->query('SELECT count(*) FROM Menus')->fetchColumn();

    return $q;

  }

  public function delete(Menu $menu)
  {
    // Exécute une requête de type DELETE.
   $this->_db->exec('DELETE FROM Menus WHERE IDMENU = '.$menu->getId());
  }

  public function exists($info)
  {
    // Si le paramètre est un entier, c'est qu'on a fourni un identifiant.
    if(is_int($info)){
      // On exécute alors une requête COUNT() avec une clause WHERE, et on retourne un boolean.
      return (bool) $this->_db->query('SELECT COUNT(*) FROM Menus WHERE IDMENU = '.$info)->fetchColumn();
    }
    // Sinon c'est qu'on a passé un nom.
    else{
      // Exécution d'une requête COUNT() avec une clause WHERE, et retourne un boolean.
      $q = $this->_db->prepare('SELECT COUNT(*) FROM Menus WHERE NOMMENU = :nom');
      $q->execute([':nom' => $info]);

      return (bool) $q->fetchColumn();
    }
  }

  public function update(Menu $menu)
  {
    // Prépare une requête de type UPDATE.
    $q = $this->_db->prepare('UPDATE Menus SET NOMMENU = :nom, PRIXMENU = :prix WHERE IDMENU = :id');
    // Assignation des valeurs à la requête.
    $q->bindValue(':nom',$menu->getNom());
    $q->bindValue(':prix',$menu->getPrix());

    // Exécution de la requête.
    $q->execute();
  }

}
