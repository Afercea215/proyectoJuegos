<?php
namespace App\Service;

use App\Service\TestService;

class TestService2
{
    private $a;

    function __construct(TestService $serv)
    {
        $this->a=$serv->testMesagge();
    }

    public function mensaje()
    {
        return $this->a." importado de TestService";
    }
}


?>