<?php

class NadzornaplocaController extends AutorizacijaController
{

    private $viewDir = 'privatno' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'Nadzornaploca');
    }

    

    public function profil(){
        $this->view->render($this->viewDir . 'profil',[
            'entitet'=>$_SESSION['autoriziran'],
            'poruka'=>''
        ]);
    }


    public function profilpromjena(){

        if(!$_POST || !isset($_POST['lozinka']) || 
            !isset($_POST['lozinkaponovo'])){
            return;
            exit;
        }

        if($_POST['lozinka']=='' || $_POST['lozinkaponovo']==''){
            $this->view->render($this->viewDir . 'profil',[
                'entitet'=>$_SESSION['autoriziran'],
                'poruka'=>'Lozinka i lozinka ponovo moraju biti unesene'
            ]);
            exit;
        }

        if($_POST['lozinka']!=$_POST['lozinkaponovo']){
            $this->view->render($this->viewDir . 'profil',[
                'entitet'=>$_SESSION['autoriziran'],
                'poruka'=>'Lozinka i lozinka ponovo ne odgovaraju'
            ]);
            exit;
        }

        Operater::promjenaprofil([
            'lozinka'=>password_hash($_POST['lozinka'], PASSWORD_BCRYPT),
            'sifra'=>$_SESSION['autoriziran']->sifra
        ]);

        $this->index();

        
    }


} 