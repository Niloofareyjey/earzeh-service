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
use Ellie\UI\Element\Text;
use Ellie\UI\Element\Textarea;
use Ellie\UI\Element\CheckBox;
use Ellie\UI\Set\TabSet;
use Ellie\UI\Set\FieldSet;
use Application\Entity\Service;
use Application\Entity\ServiceLang;

class ManagementController extends  AbstractActionController
    implements ControllerInterface
{

    protected $doctrineService;
    //***
    protected $request;

    //***
    protected $eventHandler;

    //

    public function __construct($services,$eventHandler)
    {
        $this->doctrineService = $services["doctrine"];
        $this->request = $this->getRequest();
        $this->eventHandler = $eventHandler;
    }
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $layout = $this->layout();
        $layout->setTemplate('layout/master');
        $layout->setVariables(['menu' => $this->getServiceLocator()->get('Config')['menu']]);
//        echo "<pre>";
//        var_dump($this->getServiceLocator()->get('Config')['menu']);die();
        return parent::onDispatch($e);
    }
    public function createAction()
    {

        if($this->request->isPost())
            {
                $submitedData = (array) $this->request->getPost();

                $serviceEntity = new Service();
                if(!empty($submitedData["parent"])){

                    $parent = $this->doctrineService->find('Application\Entity\Service', $submitedData["parent"]);
                    $serviceEntity->setParent($parent);
                }
                $this->doctrineService->persist($serviceEntity);

                $languages = $this->doctrineService->getRepository('Application\Entity\Language')->findAll();
                foreach($languages as $lang){
                    $attributes = json_decode($lang->getAttribute());
                    $serviceTemp =  new ServiceLang();
                    $serviceTemp->setEnable((isset($submitedData["enable"][$attributes->code]))?1:0);
                    $serviceTemp->setName($submitedData["name"][$attributes->code]);
                    $serviceTemp->setDescription($submitedData["description"][$attributes->code]);
                    $serviceTemp->setOrder(0);
                    $serviceTemp->setLanguage($lang);
                    $serviceTemp->setService($serviceEntity);
                    $this->doctrineService->persist($serviceTemp);
                }
                $this->doctrineService->flush();

                $this->layout()->message = [
                    'type' => 'success',
                    'text' => 'new user is created successfully.'
                ];

            }

        $services = $this->getForTree();
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

        $form     = new Form(['header' => 'Service Management','action' => $this->url()->fromRoute("service",array("controller"=>"management","action"=>"create")),'name'=>'test']);

        $tab = new TabSet();

        $fieldsetFa = new FieldSet(['name' => 'serviceFa','header' => 'Add A New Service' , 'label' => 'Fa']);
        $serviceNameFa = new Text([
            'name' => 'name[fa]',
            'placeholder' => 'Service Name',
            'type' => 'text',
            'value' => '',
            'label' => 'Service Name',
        ]);

        $descriptionFa = new Textarea([
            'name' => 'description[fa]',
            'placeholder' => 'Description ...',
            'label' => 'Description',
        ]);

        $enablCheckboxFa = new CheckBox(['name' => 'enable[fa]', 'label' => 'Enable' ,'checked'=>0,'option'=>'']);

        $fieldsetFa->addChild($serviceNameFa, 'serviceNameFa');
        $fieldsetFa->addChild($descriptionFa, 'username');
        $fieldsetFa->addChild($enablCheckboxFa);



        $fieldsetEn = new FieldSet(['name' => 'serviceEn','header' => 'Add A New Service' , 'label' => 'En']);
        $serviceNameEn = new Text([
            'name' => 'name[en]',
            'placeholder' => 'Service Name',
            'value' => '123',
            'type' => 'text',
            'label' => 'Service Name',
        ]);
        $descriptionEn = new Textarea([
            'name' => 'description[en]',
            'placeholder' => 'Description ...',
            'label' => 'Description',
        ]);
        $enablCheckboxEn = new CheckBox(['name' => 'enable[en]', 'label' => 'Enable','checked'=>0,'option'=>'']);

        $fieldsetEn->addChild($serviceNameEn);
        $fieldsetEn->addChild($descriptionEn, 'username');
        $fieldsetEn->addChild($enablCheckboxEn);


        $submit = new Button();

        $fieldsetCat = new FieldSet(['name' => 'parent','label' => 'Parent', 'header' => 'Choose Parent Service']);

        $treeSelect = new TreeSelect([
            "title"=>"choose category of your service",
            "services"=>$services,
            "name" => "parent"
        ]);
        $fieldsetCat->addChild($treeSelect);


        $tab->addChild($fieldsetFa, 'fieldsetFa');
        $tab->addChild($fieldsetEn, 'fieldsetEn');
        $tab->addChild($fieldsetCat,'fieldsetCat');
        $form->addChild($tab);
        $form->addChild($submit, 'submit');

        return $form;



        return $form;

    }

    public function getForTree($parent = null,$language_id = 1){
        $result = array();
        $childObjs = $this->doctrineService->getRepository('Application\Entity\Service')->findBy(array("parent"=>$parent));
        //die(var_dump($childObjs));
        foreach($childObjs as $childObj){
            $childArray = $this->createArray($childObj,$language_id);
            array_push($result,$childArray);
        }

        return $result;
    }

    public function createArray(Service $serviceObj,$language_id){
        $serviceLangObj = $this->doctrineService->getRepository('Application\Entity\ServiceLang')->findOneBy(array("language"=>$language_id,"service"=>$serviceObj->getId()));
        return array(
            "id"=>$serviceObj->getId(),
            "label"=> $serviceLangObj->getName(),
            "childList" => $this->getForTree($serviceObj->getId(),$language_id)
        );
    }
}