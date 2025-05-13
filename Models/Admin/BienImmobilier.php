<?php

namespace Models\Admin;

use Exception;
use Models\DBConnexion;
use Models\Proprio\Image;

require_once '../../vendor/autoload.php';

class BienImmobilier extends DBConnexion
{
    private Image $images;

    function __construct() {
        $this->images = new Image();
    }

    function get_bien_immobilier(): array
    {
        $sql = "select * from bien_immobilier where statut = 'Disponible' order by id_bien desc";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }

    function get_one(string $column, int|string $value): array
    {
        $sql = "select * from bien_immobilier where {$column} = ? order by id_bien desc";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute([$value]);

        return $req->fetchAll();
    }

    function get_max(string $column): string
    {
        $sql = "select matricule from bien_immobilier where {$column} = (select max({$column}) from bien_immobilier)";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchColumn();
    }

    function create(int $id_location, int $id_user, int $id_type, string|null $nom, string $desc, int $prix): bool
    {

        [$begin, $end] = explode("_", $this->get_max("id_bien"));
        
        $sql = $nom !== null ? "insert into bien_immobilier(id_localisation, iduser, id_type, matricule, intitule, description, prix) values(?, ?, ?, ?, ?, ?, ?)" : "insert into bien_immobilier(id_localisation, iduser, id_type, matricule, description, prix) values(?, ?, ?, ?, ?, ?)";

        try {
            DBConnexion::getConnexion()->beginTransaction();
            
            $req = DBConnexion::getConnexion()->prepare($sql);
            $nom !== null ? $req->execute([$id_location, $id_user, $id_type, "BIEN_" . $end + 1, $nom, $desc, $prix]) : $req->execute([$id_location, $id_user, $id_type, "BIEN_" . $end + 1, $desc, $prix]);
            
            $_SESSION["id_bien"] = DBConnexion::getConnexion()->lastInsertId();

            DBConnexion::getConnexion()->commit();
            return true;
        } catch (Exception $e) {
            DBConnexion::getConnexion()->rollBack();
            die("Erreur !!<br>" . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }

    function update(int $id_location, int $id_user, int $id_type, string|null $nom, string $desc, int $prix, int $id_bien): bool
    {

        [$begin, $end] = explode("_", $this->get_max("id_bien"));
        
        $sql = $nom !== null ? "update bien_immobilier set id_localisation = ?, iduser = ?, id_type = ?, matricule = ?, intitule = ?, description = ?, prix = ? where id_bien = ?" : "update bien_immobilier set id_localisation = ?, iduser = ?, id_type = ?, matricule = ?, description = ?, prix = ? where id_bien = ?";

        try {
            DBConnexion::getConnexion()->beginTransaction();
            
            $req = DBConnexion::getConnexion()->prepare($sql);
            $nom !== null ? $req->execute([$id_location, $id_user, $id_type, "BIEN_" . $end + 1, $nom, $desc, $prix, $id_bien]) : $req->execute([$id_location, $id_user, $id_type, "BIEN_" . $end + 1, $desc, $prix, $id_bien]);

            DBConnexion::getConnexion()->commit();
            return true;
        } catch (Exception $e) {
            DBConnexion::getConnexion()->rollBack();
            die("Erreur !!<br>" . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }

    function updateForReservation(int $id): bool
    {
        
        $sql = "update bien_immobilier set statut = ? where id_bien = ?";

        try {
            DBConnexion::getConnexion()->beginTransaction();
            
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute(["Indisponible", $id,]);

            DBConnexion::getConnexion()->commit();
            return true;
        } catch (Exception $e) {
            DBConnexion::getConnexion()->rollBack();
            die("Erreur !!<br>" . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }
    
    function delete(int $id): bool {
        $sql = "delete from bien_immobilier where id_bien = ?";
        
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id]);
            return true;
        }
        catch(Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne". $e->getLine());
        }
    }
}
