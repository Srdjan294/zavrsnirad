<?php

class KorisnikController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'korisnik'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
         // manje loše rješenje dovlačenja podataka iz baze je da ovdje se spojimo
        // i dovučemo podatke
        $this->view->render($this->viewDir . 'index',[
            'korisnici'=>Korisnik::ucitajSve()
        ]);
    }

} 