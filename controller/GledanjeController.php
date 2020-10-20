<?php

class GledanjeController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'gledanje'
    . DIRECTORY_SEPARATOR;


    public function trazigledanje()
    {
        header('Content-Type: application/json');
        echo json_encode(Gledanje::traziGledanje());
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

        $brojGledanja=Gledanje::ukupnoStranica($uvjet);
        $ukupnoStranica=ceil($brojGledanja/App::config('rezultataPoStranici'));

        if($stranica==$ukupnoStranica){
            $sljedeca=$ukupnoStranica;
        }else{
            $sljedeca=$stranica+1;
        }

        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Gledanje::ucitajSve($stranica,$uvjet),
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
            $entitet->film=0;
            $entitet->korisnik=0;
            $entitet->vrijeme_gledanja='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaFilm($entitet,'novoView')){return;};
        Gledanje::dodajNovi($_POST);
        $_GET['uvjet']=$entitet->film;
        $this->index();
       
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $_SESSION['stranicaGledanje']=$_GET['stranica'];
            $this->promjenaView('Promjenite željene podatke',
            Gledanje::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaFilm($entitet,'promjenaView')){return;};
        Gledanje::promjena($_POST);
        $_GET['stranica']=$_SESSION['stranicaGledanje'];
        $this->index();
        
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Gledanje::brisanje($_GET['sifra']);
        $this->index();
        
    }

    private function novoView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'filmovi' => Film::ucitajSve2(),
            'korisnici' => Korisnik::ucitajSve2()
        ]);
    }

    private function promjenaView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'trenutna'=>$_SESSION['stranicaGledanje']
        ]);
    }


    private function kontrolaFilm($entitet, $view)
    {
        if(strlen($entitet->film)===0){
            $this->$view('Obavezno unos filma',$entitet);
            return false;
        }

        
        // na kraju uvijek vrati true
        return true;
    }


} 