<?php
namespace App\Service;

use App\Repository\UserRepository;
use ProxyManager\Inflector\Util\ParameterHasher;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService
{
    private $repo;
    private $params;

    function __construct(UserRepository $repo, ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->repo=$repo;
    }

    public function getParam($name)
    {
        return $this->params->get($name);
        // ...
    }

    public function getUsersName()
    {
        $aa=[];
        $users = $this->repo->findAll();
        foreach ($users as $key => $value) {
            $aa[]=$value->getEmail();
        }
        return $this->getParam('my_param1');
    }
}


?>