<?php

class ZanrController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'zanr'
    . DIRECTORY_SEPARATOR;


    public function trazizanr()
    {
        header('Content-Type: application/json');
        echo json_encode(Zanr::traziZanrove());
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

        $brojZanrova=Zanr::ukupnoStranica($uvjet);
        $ukupnoStranica=ceil($brojZanrova/App::config('rezultataPoStranici'));

        if($stranica==$ukupnoStranica){
            $sljedeca=$ukupnoStranica;
        }else{
            $sljedeca=$stranica+1;
        }

        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Zanr::ucitajSve($stranica,$uvjet),
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
            $entitet->naziv='';
            $entitet->opis='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'novoView')){return;};
        Zanr::dodajNovi($_POST);
        $_GET['uvjet']=$entitet->naziv;
        $this->index();
       
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $_SESSION['stranicaZanr']=$_GET['stranica'];
            $this->promjenaView('Promjenite željene podatke',
            Zanr::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'promjenaView')){return;};
        Zanr::promjena($_POST);
        $_GET['stranica']=$_SESSION['stranicaZanr'];
        $this->index();
        
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Zanr::brisanje($_GET['sifra']);
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
            'trenutna'=>$_SESSION['stranicaZanr']
        ]);
    }


    private function kontrolaNaziv($entitet, $view)
    {
        if(strlen(trim($entitet->naziv))===0){
            $this->$view('Obavezno unos naziva',$entitet);
            return false;
        }

        if(strlen(trim($entitet->naziv))>50){
            $this->$view('Dužina naziva prevelika',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }


} 