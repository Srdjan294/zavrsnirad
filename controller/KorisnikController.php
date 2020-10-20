<?php

class KorisnikController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'korisnik'
    . DIRECTORY_SEPARATOR;


    public function trazikorisnik()
    {
        header('Content-Type: application/json');
        echo json_encode(Korisnik::traziKorisnike());
    }

    public function index()
    {

        if(isset($_GET['uvjet'])){
            $uvjet='%' . $_GET['uvjet'] . '%';
            $uvjetView=$_GET['uvjet'];
        }else{
            $uvjet='%';
            $uvjetView='';
        }

        if(isset($_GET['stranica'])){
            $stranica=$_GET['stranica'];
        }else{
            $stranica=1;
        }

        if($stranica==1){
            $prethodna=1;
        }else{
            $prethodna=$stranica-1;
        }

        $brojKorisnika=Korisnik::ukupnoStranica($uvjet);
        $ukupnoStranica=ceil($brojKorisnika/App::config('rezultataPoStranici'));

        if($stranica==$ukupnoStranica){
            $sljedeca=$ukupnoStranica;
        }else{
            $sljedeca=$stranica+1;
        }

        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Korisnik::ucitajSve($stranica,$uvjet),
            'trenutna'=>$stranica,
            'prethodna'=>$prethodna,
            'sljedeca'=>$sljedeca,
            'uvjet'=>$uvjetView,
            'ukupnoStranica'=>$ukupnoStranica
        ]);
    }

    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $entitet=new stdClass();
            $entitet->ime='';
            $entitet->prezime='';
            $entitet->datum_rodenja='';
            $entitet->korisnicko_ime='';
            $entitet->adresa='';
            $entitet->email='';
            $entitet->broj_mobitela='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'novoView')){return;};
        Korisnik::dodajNovi($_POST);
        $_GET['uvjet']=$entitet->prezime;
        $this->index();
       
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $_SESSION['stranicaKorisnik']=$_GET['stranica'];
            $this->promjenaView('Promjenite željene podatke',
            Korisnik::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'promjenaView')){return;};
        Korisnik::promjena($_POST);
        $_GET['stranica']=$_SESSION['stranicaKorisnik'];
        $this->index();
        
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Korisnik::brisanje($_GET['sifra']);
        $this->index();
        
    }









    private function novoView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'entitet' => $entitet
        ]);
    }

    private function promjenaView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'trenutna'=>$_SESSION['stranicaKorisnik']
        ]);
    }


    private function kontrolaIme($entitet, $view)
    {
        if(strlen(trim($entitet->ime))===0){
            $this->$view('Obavezno unos imena',$entitet);
            return false;
        }

        if(strlen(trim($entitet->ime))>50){
            $this->$view('Dužina imena prevelika',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }


}   