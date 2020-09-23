<?php 

class Dobavljac
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select * from dobavljac');
        $izraz->execute();
        return $izraz->fetchAll();
    }


}