<?php

/**
 * Created by PhpStorm.
 * User: pooria
 * Date: 10/6/15
 * Time: 3:19 PM
 */
namespace Service\Controller;

use Zend\Mvc\Controller\AbstractActionController;// for run in zend's MVC
use Ellie\Interfaces\ControllerInterface;
use Zend\View\Model\ViewModel;
use Ellie\UI\Form;
use Ellie\UI\Element\TreeSelect;
use Ellie\UI\Element\Button;

class ManagementController extends  AbstractActionController
    implements ControllerInterface
{

    protected $doctrineService;
    //***
    protected $request;

    //***
    protected $eventHandler;

    public function __construct($services,$eventHandler)
    {
        $this->doctrineService = $services["doctrine"];
        $this->request = $this->getRequest();
        $this->eventHandler = $eventHandler;
    }
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {

        //*****
        $this->base = $this->getRequest()->getBasePath();
        $layout = $this->layout();
        $layout->setTemplate('layout/master');
        $layout->setVariables(['menu' => $this->getServiceLocator()->get('Config')['menu']]);
//        echo "<pre>";
//        var_dump($this->getServiceLocator()->get('Config')['menu']);die();
        return parent::onDispatch($e);
    }
    public function createAction()
    {
        $services = array(
            array("id" => 1,
                "label" => "L1",
                "childList" => array(
                    array(
                        "id" => 2,
                        "label" => "L1-1",
                        "childList" => array()
                    ),
                    array(
                        "id" => 3,
                        "label" => "L1-2",
                        "childList" => array()
                    )
                )
            ),
            array("id" => 4,
                "label" => "L2",
                "childList" => array(
                    array(
                        "id" => 5,
                        "label" => "L2-1",
                        "childList" => array(
                            array(
                                "id" => 6,
                                "label" => "L2-1-1",
                                "childList" => array()
                            )
                        )
                    )
                )
            )
        );
        $this->layout()->message = [
            'type' => 'warning',
            'text' => 'create service is under construction'
        ];
        return $this->getCreateServiceForm($services);
    }

    public function editAction()
    {
        // TODO: Implement editAction() method.
    }

    public function deleteAction()
    {
        // TODO: Implement deleteAction() method.
    }

    public function listAction()
    {
        // TODO: Implement listAction() method.
    }


    protected function getCreateServiceForm($services){
        $form     = new Form(['header' => 'User Management','action' => '','name'=>'test']);
        $treeSelect = new TreeSelect([
            "title"=>"choose category of your service",
            "services"=>$services
        ]);
        $form->addChild($treeSelect);
        $submit = new Button();

        $form->addChild($submit, 'submit');


        return $form;

    }
}