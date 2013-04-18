<?php

namespace RH;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            // This defines the hostname route which forms the base
            // of each "child" route
            'rh' => array(
                'type' => 'literal',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/rh',
                    'defaults' => array(
                        'controller' => 'RH\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(
                    'categoria-noticia' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/categoria-noticia',
                            'defaults' => array(
                                'action' => 'index',
                                'controller' => 'RH\Controller\CategoriaNoticia',
                            ),
                        ),
                        'child_routes' => array(
                            'store' => array(
                                'type' => 'Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/store[/:idcat]',
                                    'defaults' => array(
                                        'action' => 'store',
                                        'controller' => 'RH\Controller\CategoriaNoticia',
                                        'idcat' => 0
                                    ),
                                ),
                            ),
                        )
                    ),
                    'noticias' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/noticias',
                            'defaults' => array(
                                'action' => 'index',
                                'controller' => 'RH\Controller\Noticias',
                            ),
                        ),
                        'child_routes' => array(
                            'store' => array(
                                'type' => 'Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/store[/:id]',
                                    'defaults' => array(
                                        'action' => 'store',
                                        'controller' => 'RH\Controller\Noticias',
                                        'id' => 0
                                    ),
                                ),
                            ),
                            'ver' => array(
                                'type' => 'Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/ver/:id',
                                    'defaults' => array(
                                        'action' => 'ver',
                                        'controller' => 'RH\Controller\Noticias',
                                        'id' => 0
                                    ),
                                ),
                            ),
                            'categoria' => array(
                                'type' => 'Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/categoria/:id',
                                    'defaults' => array(
                                        'action' => 'categoria',
                                        'controller' => 'RH\Controller\Noticias',
                                        'id' => 0
                                    ),
                                ),
                                'child_routes' => array(
                                    'page' => array(
                                        'type' => 'Segment',
                                        'may_terminate' => true,
                                        'options' => array(
                                            'route' => '/page[/:page]',
                                            'defaults' => array(
                                                'action' => 'store',
                                                'controller' => 'RH\Controller\Noticias',
                                                'page' => 0
                                            ),
                                        ),
                                    )
                                ),
                            ),
                            'noticias-page' => array(
                                'type' => 'Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/page[/:page]',
                                    'constraints' => array(
                                        'page' => '[0-9]+'
                                    ),
                                    'defaults' => array(
                                        'action' => 'index',
                                        'page' => 1
                                    ),
                                ),
                            ),
                        )
                    ),
                )
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'CategoriasNoticiasPair' => function($sm) {
                $em = $sm->get('doctrine.entitymanager.orm_default');
                $categorias = $em->getRepository('RH\Entity\CategoriaNoticia')->findAll();
                $categoriaarray = array();
                foreach ($categorias as $c) {
                    $categoriaarray[$c->getIdcategorianoticia()] = $c->getCategorianome();
                }
                return $categoriaarray;
            }
        ),
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'RH\Controller\Index' => 'RH\Controller\IndexController',
            'RH\Controller\Noticias' => 'RH\Controller\NoticiasController',
            'RH\Controller\CategoriaNoticia' => 'RH\Controller\CategoriaNoticiaController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../../Base/view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../../Base/view/error/404.phtml',
            'error/index' => __DIR__ . '/../../Base/view/error/index.phtml',
            'partials/navigation' => __DIR__ . '/../view/partials/navigation.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
