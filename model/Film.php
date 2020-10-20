<?php

class Film
{

    public static function traziFilmove()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.naziv, a.ime_redatelja, a.prezime_redatelja, a.ime_glavnog_glumca,
        a.prezime_glavnog_glumca, a.trajanje, a.godina_izlaska, 
        b.naziv as dobavljac, b.homepage, c.naziv as zanr, c.opis from film a
        inner join dobavljac b on a.dobavljac = b.sifra
        inner join zanr c on a.zanr = c.sifra
        where a.naziv like :uvjet 
        order by a.naziv limit 10
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
        
        select a.sifra, a.naziv, a.ime_redatelja, a.prezime_redatelja, a.ime_glavnog_glumca,
        a.prezime_glavnog_glumca, a.trajanje, a.godina_izlaska, 
        b.naziv as dobavljac, b.homepage, c.naziv as zanr, c.opis from film a
        inner join dobavljac b on a.dobavljac = b.sifra
        inner join zanr c on a.zanr = c.sifra
        where a.naziv
        like :uvjet
        group by a.naziv, a.ime_redatelja, a.prezime_redatelja, a.ime_glavnog_glumca,
        a.prezime_glavnog_glumca, a.trajanje, a.godina_izlaska, 
        b.naziv, b.homepage, c.naziv, c.opis limit :od,:rps;

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
        
        select * from film 
        
        ');
        
        $izraz->execute();
        return $izraz->fetchAll();
    }


    public static function ukupnoStranica($uvjet){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
            select count(sifra) from film   
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
        
        select a.sifra, a.naziv, a.ime_redatelja, a.prezime_redatelja, a.ime_glavnog_glumca,
        a.prezime_glavnog_glumca, a.trajanje, a.godina_izlaska, 
        a.dobavljac, a.zanr from film a
         where a.sifra=:sifra;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }




    public static function dodajNovi($entitet){
        $veza = DB::getInstanca();
        
        $izraz = $veza->prepare('insert into film 
        (naziv, zanr, ime_redatelja, prezime_redatelja, ime_glavnog_glumca, prezime_glavnog_glumca,   
        trajanje, dobavljac, godina_izlaska)
        values (:naziv, :zanr, :ime_redatelja, :prezime_redatelja, :ime_glavnog_glumca, :prezime_glavnog_glumca,   
        :trajanje, :dobavljac, :godina_izlaska);');
        $izraz->execute([
        'naziv'=>$entitet['naziv'],
        'zanr'=>$entitet['zanr'],
        'ime_redatelja'=>$entitet['ime_redatelja'],
        'prezime_redatelja'=>$entitet['prezime_redatelja'],
        'ime_glavnog_glumca'=>$entitet['ime_glavnog_glumca'],
        'prezime_glavnog_glumca'=>$entitet['prezime_glavnog_glumca'],
        'trajanje'=>$entitet['trajanje'],
        'dobavljac'=>$entitet['dobavljac'],
        'godina_izlaska'=>$entitet['godina_izlaska']
        ]);
        
        
    }


    public static function promjena($entitet){
        $veza = DB::getInstanca();
        

        

        $izraz = $veza->prepare('update film set
                        naziv=:naziv,
                        zanr=:zanr,
                        ime_redatelja=:ime_redatelja,
                        prezime_redatelja=:prezime_redatelja,
                        ime_glavnog_glumca=:ime_glavnog_glumca,
                        prezime_glavnog_glumca=:prezime_glavnog_glumca,
                        trajanje=:trajanje,
                        dobavljac=:dobavljac,
                        godina_izlaska=:godina_izlaska
                        where sifra=:sifra');
        $izraz->execute([
                            
                            'naziv'=>$entitet['naziv'],
                            'zanr'=>$entitet['zanr'],
                            'ime_redatelja'=>$entitet['ime_redatelja'],
                            'prezime_redatelja'=>$entitet['prezime_redatelja'],
                            'ime_glavnog_glumca'=>$entitet['ime_glavnog_glumca'],
                            'prezime_glavnog_glumca'=>$entitet['prezime_glavnog_glumca'],
                            'trajanje'=>$entitet['trajanje'],
                            'dobavljac'=>$entitet['dobavljac'],
                            'godina_izlaska'=>$entitet['godina_izlaska'],
                            'sifra'=>$entitet['sifra']
                        
                        ]);
        
    }


    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        

        

        $izraz = $veza->prepare('delete from film
        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
        
       
    }

}