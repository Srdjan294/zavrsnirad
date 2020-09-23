<?php 

class Zanr
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select * from zanr');
        $izraz->execute();
        return $izraz->fetchAll();
    }


}