<?php 

class Gledanje
{
    public static function traziGledanje()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.vrijeme_gledanja, b.naziv as film, b.ime_redatelja, b.prezime_redatelja, b.ime_glavnog_glumca,
        b.prezime_glavnog_glumca, b.trajanje, b.godina_izlaska, b.zanr, b.dobavljac, 
        c.ime, c.prezime, c.datum_rodenja, c.korisnicko_ime as korisnik, c.adresa, c.email, c.broj_mobitela
        from gledanje a inner join film b on a.film = b.sifra
        inner join korisnik c on a.korisnik = c.sifra
        where a.film like :uvjet 
        order by a.film limit 10
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
        
        select a.sifra, a.vrijeme_gledanja, b.naziv as film, b.ime_redatelja, b.prezime_redatelja, b.ime_glavnog_glumca,
        b.prezime_glavnog_glumca, b.trajanje, b.godina_izlaska, b.zanr, b.dobavljac, 
        c.ime, c.prezime, c.datum_rodenja, c.korisnicko_ime as korisnik, c.adresa, c.email, c.broj_mobitela
        from gledanje a inner join film b on a.film = b.sifra
        inner join korisnik c on a.korisnik = c.sifra
        where a.film
        like :uvjet
        group by a.film  limit :od,:rps;
        ');
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->bindValue('od',$od,PDO::PARAM_INT);
        $izraz->bindValue('rps',$rps,PDO::PARAM_INT);
        $izraz->execute();
        return $izraz->fetchAll();
    }


    public static function ukupnoStranica($uvjet){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
            select count(sifra) from gledanje  
            where film
            like :uvjet;
            
        ');
        $izraz->execute(['uvjet'=>$uvjet]);
        return $izraz->fetchColumn();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.vrijeme_gledanja, b.naziv as film, b.ime_redatelja, b.prezime_redatelja, b.ime_glavnog_glumca,
        b.prezime_glavnog_glumca, b.trajanje, b.godina_izlaska, b.zanr, b.dobavljac, 
        c.ime, c.prezime, c.datum_rodenja, c.korisnicko_ime as korisnik, c.adresa, c.email, c.broj_mobitela
        from gledanje a inner join film b on a.film = b.sifra
        inner join korisnik c on a.korisnik = c.sifra
        where a.sifra=sifra;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }




    public static function dodajNovi($entitet){
        $veza = DB::getInstanca();
        
        $izraz = $veza->prepare('insert into gledanje 
        (film, korisnik, vrijeme_gledanja)
        values (:film, :korisnik, :vrijeme_gledanja);');
        $izraz->execute([
            'film'=>$entitet['film'],
            'korisnik'=>$entitet['korisnik'],
            'vrijeme_gledanja'=>$entitet['vrijeme_gledanja']
            
        ]);
        
        
    }

    public static function promjena($entitet){
        $veza = DB::getInstanca();
       

        $izraz = $veza->prepare('update gledanje set
                    film=:film,
                    korisnik=:korisnik,
                    vrijeme_gledanja=:vrijeme_gledanja
                    where sifra=:sifra');
        $izraz->execute([
            'film'=>$entitet['film'],
            'korisnik'=>$entitet['korisnik'],
            'vrijeme_gledanja'=>$entitet['vrijeme_gledanja']
        ]);
        
        
        
    }

    public static function brisanje($sifra){
        $veza = DB::getInstanca();
      

       

        $izraz = $veza->prepare('delete from gledanje
        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
        

    }

    

}