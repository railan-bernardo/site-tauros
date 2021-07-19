<?php

namespace Source\Core;

use Source\Support\Message;

/**
 * FSPHP | Class Model Layer Supertype Pattern
 *
 * @author Robson V. Leite <cursos@upinside.com.br>
 * @package Source\Models
 */
 class Save
{
   



    public function create($name, $id)
    {
 
      
            $stmt = Connect::getInstance()->prepare("INSERT INTO gallery_service (cover_img, idservice) VALUES (?, ?)");
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $id);
            $stmt->execute();

            return Connect::getInstance()->lastInsertId();
        
    }

    public function update($name, $idservice, $id)
    {
 
      
            $stmt = Connect::getInstance()->prepare("UPDATE gallery_service SET cover_img = ?, idservice = ? WHERE id = '$id'");
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $idservice);
            $stmt->execute();

            return Connect::getInstance()->lastInsertId();
        
    }

    public function createGallery($name, $id)
    {
 
      
            $stmt = Connect::getInstance()->prepare("INSERT INTO about_gallery (cover_img, id_about) VALUES (?, ?)");
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $id);
            $stmt->execute();

            return Connect::getInstance()->lastInsertId();
        
    }

    public function updateGallery($name, $idservice, $id)
    {
 
      
            $stmt = Connect::getInstance()->prepare("UPDATE about_gallery SET cover_img = ?, id_about = ? WHERE id = '$id'");
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $idservice);
            $stmt->execute();

            return Connect::getInstance()->lastInsertId();
        
    }



}