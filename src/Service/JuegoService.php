<?php
namespace App\Service;

use App\Repository\JuegoRepository;
use App\Service\TestService;

class JuegoService
{
    private $jp;

    function __construct(JuegoRepository $jp)
    {
        $this->jp=$jp;
    }

    public function getImages()
    {
        $imgs=[];
        $juegos = $this->jp->findAll();
        
        foreach ($juegos as $key => $value) {
            $imgs[]=$value->getImg();
        }

        return $imgs;
    }

    public function getFondoPantalla(){
        $imgs=$this->getImages();
        $imgsFondo=[];
        for ($i=0; $i <80 ; $i++) {
            $n = rand(0,sizeof($imgs)-1); 

            $imgsFondo[]=$imgs[$n];
        }
        return $imgsFondo;
    } 
}


?>