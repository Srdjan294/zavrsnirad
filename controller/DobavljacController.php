<?php

class DobavljacController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'dobavljac'
    . DIRECTORY_SEPARATOR;


    public function trazidobavljac()
    {
        header('Content-Type: application/json');
        echo json_encode(Dobavljac::traziDobavljace());
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

        $brojDobavljaca=Dobavljac::ukupnoStranica($uvjet);
        $ukupnoStranica=ceil($brojDobavljaca/App::config('rezultataPoStranici'));

        if($stranica==$ukupnoStranica){
            $sljedeca=$ukupnoStranica;
        }else{
            $sljedeca=$stranica+1;
        }

        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Dobavljac::ucitajSve($stranica,$uvjet),
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
            $entitet->homepage='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'novoView')){return;};
        Dobavljac::dodajNovi($_POST);
        $_GET['uvjet']=$entitet->naziv;
        $this->index();
       
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $_SESSION['stranicaDobavljac']=$_GET['stranica'];
            $this->promjenaView('Promjenite željene podatke',
            Dobavljac::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'promjenaView')){return;};
        Dobavljac::promjena($_POST);
        $_GET['stranica']=$_SESSION['stranicaDobavljac'];
        $this->index();
        
    }

   

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Dobavljac::brisanje($_GET['sifra']);
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
            'trenutna'=>$_SESSION['stranicaDobavljac']
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