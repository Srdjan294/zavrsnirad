<?php 

class Zanr
{

    public static function traziZanrove()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select sifra, naziv, opis
        where naziv like :uvjet 
        order by naziv limit 10
        ');
       
        $izraz->execute([
            'uvjet'=>'%' . $_GET['uvjet'] . '%',
        ]);
        return $izraz->fetchAll();
    }

    public static function ucitajSve($stranica,$uvjet)
    {
        $rps=App::config('rezultataPoStranici');
        $od = $stranica * $rps - $rps;
    
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select sifra, naziv, opis from zanr');
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->bindValue('od',$od,PDO::PARAM_INT);
        $izraz->bindValue('rps',$rps,PDO::PARAM_INT);
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ucitajSve2()
    {
        

        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select * from zanr 
        
        ');
        
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ukupnoStranica($uvjet){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
            select count(sifra) from zanr   
            where naziv
            like :uvjet;
        ');
        $izraz->execute(['uvjet'=>$uvjet]);
        return $izraz->fetchColumn();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select sifra, naziv, opis from zanr;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }

    public static function dodajNovi($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('insert into zanr 
        (naziv, opis)
        values (:naziv, :opis);');
        $izraz->execute([
            'naziv'=>$entitet['naziv'],
            'opis'=>$entitet['opis'],
            
        ]);
        
        $veza->commit();
    }

    public static function promjena($entitet){
        $veza = DB::getInstanca();
        

        $izraz = $veza->prepare('update zanr set
                    naziv=:naziv,
                    opis=:opis
                    where sifra=:sifra');
        $izraz->execute([
            'naziv'=>$entitet['naziv'],
            'opis'=>$entitet['opis'],
            'sifra'=>$entitet['sifra']
        ]);
        
        
        
    }

    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        
        
        $izraz = $veza->prepare('delete from zanr
        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
        
       
    }

}