<?php

class Korisnik
{

    public static function traziKorisnike()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select sifra, ime, prezime, datum_rodenja, korisnicko_ime, adresa, email, broj_mobitela
        where concat(ime, \' \', prezime, \' \') like :uvjet 
        order by prezime limit 10
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
        
        select sifra, ime, prezime, datum_rodenja, korisnicko_ime, adresa, email, broj_mobitela from korisnik 
        where concat(ime, \' \', prezime, \' \')
        like :uvjet
        group by sifra, ime, prezime, datum_rodenja, korisnicko_ime, adresa, email, broj_mobitela limit :od,:rps;

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
        
        select * from korisnik 
        
        ');
        
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ukupnoStranica($uvjet){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
            select count(sifra) from korisnik   
            where concat(ime, \' \', prezime, \' \')
            like :uvjet;
        ');
        $izraz->execute(['uvjet'=>$uvjet]);
        return $izraz->fetchColumn();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select sifra, ime, prezime, datum_rodenja, korisnicko_ime, adresa, email, broj_mobitela from korisnik where sifra=:sifra;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }




    public static function dodajNovi($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('insert into korisnik 
        (ime, prezime, datum_rodenja, korisnicko_ime, adresa, email, broj_mobitela)
        values (:ime, :prezime, :datum_rodenja, :korisnicko_ime, :adresa, :email, :broj_mobitela);');
        $izraz->execute([
            'ime'=>$entitet['ime'],
            'prezime'=>$entitet['prezime'],
            'datum_rodenja'=>$entitet['datum_rodenja'],
            'korisnicko_ime'=>$entitet['korisnicko_ime'],
            'adresa'=>$entitet['adresa'],
            'email'=>$entitet['email'],
            'broj_mobitela'=>$entitet['broj_mobitela']
        ]);
        
        $veza->commit();
    }


    public static function promjena($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();

        $izraz = $veza->prepare('
        
        select sifra, ime, prezime, datum_rodenja, korisnicko_ime, adresa, email, broj_mobitela from korisnik where sifra=:sifra ;

        ');
        $izraz->execute(['sifra'=>$entitet['sifra']]);
        $sifraKorisnik = $izraz->fetchColumn();

        $izraz = $veza->prepare('update korisnik set
                    ime=:ime,
                    prezime=:prezime,
                    datum_rodenja=:datum_rodenja,
                    korisnicko_ime=:korisnicko_ime,
                    adresa=:adresa,
                    email=:email,
                    broj_mobitela=:broj_mobitela
                    where sifra=:sifra');
        $izraz->execute([
            'ime'=>$entitet['ime'],
            'prezime'=>$entitet['prezime'],
            'datum_rodenja'=>$entitet['datum_rodenja'],
            'korisnicko_ime'=>$entitet['korisnicko_ime'],
            'adresa'=>$entitet['adresa'],
            'email'=>$entitet['email'],
            'broj_mobitela'=>$entitet['broj_mobitela'],
            'sifra'=>$sifraKorisnik
        ]);
        
        
        $veza->commit();
    }


    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        
        $izraz = $veza->prepare('delete from korisnik
        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
        
       
    }

}