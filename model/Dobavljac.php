<?php 

class Dobavljac
{
    public static function traziDobavljace()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select naziv, homepage
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
        $izraz = $veza->prepare('
        
        select * from dobavljac 
        where naziv
        like :uvjet
        group by naziv, homepage limit :od,:rps;
        ');
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
        
        select * from dobavljac 
        
        ');
        
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ukupnoStranica($uvjet){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
            select count(sifra) from dobavljac   
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
        
        select sifra, naziv, homepage from dobavljac;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }




    public static function dodajNovi($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('insert into dobavljac 
        (naziv, homepage)
        values (:naziv, :homepage);');
        $izraz->execute([
            'naziv'=>$entitet['naziv'],
            'homepage'=>$entitet['homepage'],
            
        ]);
        
        $veza->commit();
    }

    public static function promjena($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();

        $izraz = $veza->prepare('
        
        select sifra, naziv, homepage from dobavljac where sifra=:sifra ;

        ');
        $izraz->execute(['sifra'=>$entitet['sifra']]);
        $sifraDobavljac = $izraz->fetchColumn();

        $izraz = $veza->prepare('update zanr set
                    naziv=:naziv,
                    homepage=:homepage
                    where sifra=:sifra');
        $izraz->execute([
            'naziv'=>$entitet['naziv'],
            'homepage'=>$entitet['homepage'],
            'sifra'=>$sifraDobavljac
        ]);
        
        
        $veza->commit();
    }

    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $veza->beginTransaction();

        $izraz = $veza->prepare('
        
        select sifra, naziv, homepage from dobavljac where sifra=:sifra ;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraDobavljac = $izraz->fetchColumn();

        $izraz = $veza->prepare('delete from dobavljac
        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
        
        $veza->commit();
    }

    

}