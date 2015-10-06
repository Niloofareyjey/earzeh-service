<?php
namespace User;

return array(
  

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(

        )
    ),

    'controllers' => array(
        'factories' => array(
        // for passing variables
        ),
        'invokables' =>array(
            //without passing variable controlers
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'Ellie\Service\Log' => 'Ellie\Service\Log\LogServiceFactory',
           // 'Ellie\Service\Authentication' => 'Ellie\Service\Authentication\ServiceFactory',
        )
    ),

    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Define a new route called "post"
            'user' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'segment',
                // Configure the route itself
                'options' => array(
                    // Listen to "/blog" as uri
                    'route'    => '/services[/:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',

                    ),
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'index',
                        'action'     => 'index',
                    )
                )
            )
        )
    ),

//    'menu'  => [
//        'User Management' => [
//            'Create New User' => 'manage/create',
//            'Company List' => 'manage/list',
//            'Manager List' => 'user/manage/',
//            'Operator List' => 'user/manage/',
//            'Unregisted List' => 'user/manage/',
//        ]
//    ]
);