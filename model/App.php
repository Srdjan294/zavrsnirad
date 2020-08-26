<?php

class App 
{
    public static function start()
    {
        $ruta = Request::getRuta();

        $djelovi=explode('/',$ruta);

        $klasa='';
        if(!isset($djelovi[1]) || $djelovi[1]===''){
            $klasa='Index';
        }else{
            $klasa=ucfirst($djelovi[1]);
        }

        $klasa .= 'Controller';

        $funkcija='';
        if(!isset($djelovi[2]) || $djelovi[2]===''){
            $funkcija='index';
        }else{
            $funkcija=$djelovi[2];
        }

        if(class_exists($klasa) && method_exists($klasa,$funkcija)){
            $instanca = new $klasa();
            $instanca->$funkcija();
        }else{
            echo 'Kreirati funkciju unutar klase ' . $klasa . '-&gt;' . $funkcija;
        }
    }
}