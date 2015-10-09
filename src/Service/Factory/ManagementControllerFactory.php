<?php

/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/7/15
 * Time: 8:22 PM
 */

namespace Service\Factory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Service\Controller\ManagementController;

class ManagementControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $eventHandler = null;
        $doctrineService = $realServiceLocator->get('Doctrine\ORM\EntityManager');
        $services = array("doctrine"=>$doctrineService);
        return new ManagementController($services,$eventHandler);
    }
}