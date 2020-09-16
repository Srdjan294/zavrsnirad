<?php 

class Korisnik
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select * from korisnik');
        $izraz->execute();
        return $izraz->fetchAll();
    }


}