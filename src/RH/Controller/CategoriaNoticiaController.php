<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RH\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use RH\Entity\CategoriaNoticia;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

class CategoriaNoticiaController extends AbstractActionController {

    protected $em;

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction() {
        $view = new ViewModel();


        $all = $this->getEntityManager()->getRepository('RH\Entity\CategoriaNoticia')->findAll();

        $paginator = new Paginator(new ArrayAdapter($all));
        $paginator->setDefaultItemCountPerPage(4);

        $messages = $this->flashMessenger()->getMessages();
        $page = (int) $this->params()->fromRoute('page', 1);
        if ($page)
            $paginator->setCurrentPageNumber($page);

        $view->setVariable('paginator', $paginator);
        $view->setVariable('messages', $messages);
        $view->setVariable('page', $page);
        
        return $view;
    }
    
     public function storeAction() {
        $setores = new CategoriaNoticia();
        $id = $this->params()->fromRoute('idcat');
        $anf = new AnnotationBuilder($this->getEntityManager());
        $form = $anf->createForm($setores);
     

        if ($id) {
            $setor = $this->getEntityManager()->find('RH\Entity\CategoriaNoticia', $id);

            $setor->setSubmit('Enviar');
            $form->bind($setor);
        }

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                if (!$data->getIdcategorianoticia()) {
                    $setores->populate($data);
                    $this->getEntityManager()->persist($setores);
                    $this->getEntityManager()->flush();
                } else {
                    $setores->populate((array)$data);
                   
                    $this->getEntityManager()->merge($setores);
                    $this->getEntityManager()->flush();
                }
                $this->flashMessenger()->addMessage('The Data are registred.');
                $this->redirect()->toRoute('rh/categoria-noticia');
            }
        }


        return array('form' => $form);
    }

}
