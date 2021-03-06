<?php

class FilmController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'film'
    . DIRECTORY_SEPARATOR;


    public function trazifilmove()
    {
        header('Content-Type: application/json');
        echo json_encode(Film::traziFilmove());
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

        $brojFilmova=Film::ukupnoStranica($uvjet);
        $ukupnoStranica=ceil($brojFilmova/App::config('rezultataPoStranici'));

        if($stranica==$ukupnoStranica){
            $sljedeca=$ukupnoStranica;
        }else{
            $sljedeca=$stranica+1;
        }

        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Film::ucitajSve($stranica,$uvjet),
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
            $entitet->zanr=0;
            $entitet->ime_redatelja='';
            $entitet->prezime_redatelja='';
            $entitet->ime_glavnog_glumca='';
            $entitet->prezime_glavnog_glumca='';
            $entitet->trajanje='';
            $entitet->dobavljac=0;
            $entitet->godina_izlaska='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'novoView')){return;};
        Film::dodajNovi($_POST);
        $_GET['uvjet']=$entitet->naziv;
        $this->index();
       
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $_SESSION['stranicaFilm']=$_GET['stranica'];
            $this->promjenaView('Promjenite željene podatke',
            Film::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'promjenaView')){return;};
        Film::promjena($_POST);
        $_GET['stranica']=$_SESSION['stranicaFilm'];
        $this->index();
        
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Film::brisanje($_GET['sifra']);
        $this->index();
        
    }

    private function novoView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'zanrovi' => Zanr::ucitajSve2(),
            'dobavljaci' => Dobavljac::ucitajSve2()
        ]);
    }

    private function promjenaView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'zanrovi' => Zanr::ucitajSve2(),
            'dobavljaci' => Dobavljac::ucitajSve2(),
            'trenutna'=>$_SESSION['stranicaFilm']
        ]);
    }


    private function kontrolaNaziv($entitet, $view)
    {
        if(strlen(trim($entitet->naziv))===0){
            $this->$view('Obavezno unos naziva',$entitet);
            return false;
        }

        if(strlen(trim($entitet->naziv))>50){
            $this->$view('Dužina imena naziva',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }


}   