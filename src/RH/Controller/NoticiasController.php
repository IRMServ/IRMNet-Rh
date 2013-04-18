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
use RH\Entity\Noticias;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Debug\Debug;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Filter\File\Rename;
use \DateTime;
class NoticiasController extends AbstractActionController {

    protected $em;

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction() {
        $view = new ViewModel();


        $all = $this->getEntityManager()->getRepository('RH\Entity\Noticias')->findBy(array(), array('idnoticia' => 'desc'));

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

        $autor = $this->getServiceLocator()->get('Auth')->getStorage()->read();

        $setores = new Noticias();
        $id = $this->params()->fromRoute('id');
        $anf = new AnnotationBuilder($this->getEntityManager());
        $form = $anf->createForm($setores);
        $cat = $form->get('categorianoticia_fk');
        $cat->setEmptyOption('Escolha uma opção')->setValueOptions($this->getServiceLocator()->get('CategoriasNoticiasPair'));
        $aut = $form->get('autor');
        $aut->setValue($autor['displayname']);
        $pub = $form->get('publicacao');
        $pub->setFormat('d/m/Y');


        if ($id) {
            $setor = $this->getEntityManager()->find('RH\Entity\Noticias', $id);

            $setor->setSubmit('Enviar');
            $form->get('idnoticia')->setValue($setor->getIdnoticia());
            $form->get('titulo')->setValue($setor->getTitulo());
            $form->get('texto')->setValue($setor->getTexto());
            $form->get('fontes')->setValue($setor->getFontes());
            $form->get('data')->setValue($setor->getData());
            $form->get('publicacao')->setValue($setor->getPublicacao());
            $form->get('autor')->setValue($setor->getAutor());
            $form->get('destaque')->setValue($setor->getDestaque());
            $form->get('categorianoticia_fk')->setValue($setor->getCategorianoticia_fk());
        }

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {

                $data = (array) $form->getData();
                $categoria = $this->getEntityManager()->find('RH\Entity\CategoriaNoticia', $data['categorianoticia_fk']);

                $data['categorianoticia_fk'] = $categoria;

                if (!$data['idnoticia']) {
                    $File = $this->params()->fromFiles('fundo');

                    if ($File['size'] > 0) {
                        $data['fundo'] = $File['name'];


                        $size = new Size(array('max' => 5 * 1024 * 1024));
                        $adapter = new \Zend\File\Transfer\Adapter\Http();

                        $dir = \dirname(__DIR__);
                        $ex = \explode('intranet', $dir);
                        $exten = \explode('.', $File['name']);
                        $destino = $ex[0] . 'intranet\public\files\\' . md5(uniqid()) . '.' . $exten[1];

                        $rename = new Rename($destino);

                        $extension = new Extension(array('gif', 'jpg', 'bmp', 'png'));
                        $adapter->addFilter($rename);
                        $adapter->addValidator($extension)
                                ->addValidator($size);

                        if (!$adapter->isValid()) {
                            $dataError = $adapter->getMessages();
                            $error = array();
                            foreach ($dataError as $key => $row) {
                                $error[] = $row;
                            }
                            $form->setMessages(array('arquivo' => $error));
                            return array('form' => $form);
                        } else {
                            $setores->populate($data);
                            $dir = \dirname(__DIR__);
                            $ex = explode('intranet', $dir);
                            $destino = $ex[0] . 'intranet\public\files';
                            $adapter->setDestination($destino);
                            if ($adapter->receive()) {
                                $data['fundo'] = str_replace('\\', '/', end(explode('public', $adapter->getFileName())));
                                $setores->populate($data);
                            }
                        }
                    }
                    $setores->populate($data);

                    $this->getEntityManager()->persist($setores);
                    $this->getEntityManager()->flush();
                } else {
                    $File = $this->params()->fromFiles('fundo');

                    if ($File['size'] > 0) {
                        $data['fundo'] = $File['name'];


                        $size = new Size(array('max' => 5 * 1024 * 1024));
                        $adapter = new \Zend\File\Transfer\Adapter\Http();

                        $dir = \dirname(__DIR__);
                        $ex = \explode('intranet', $dir);
                        $exten = \explode('.', $File['name']);
                        $destino = $ex[0] . 'intranet\public\files\\' . md5(uniqid()) . '.' . $exten[1];

                        $rename = new Rename($destino);

                        $extension = new Extension(array('gif', 'jpg', 'bmp', 'png'));
                        $adapter->addFilter($rename);
                        $adapter->addValidator($extension)
                                ->addValidator($size);

                        if (!$adapter->isValid()) {
                            $dataError = $adapter->getMessages();
                            $error = array();
                            foreach ($dataError as $key => $row) {
                                $error[] = $row;
                            }
                            $form->setMessages(array('arquivo' => $error));
                            return array('form' => $form);
                        } else {
                            $setores->populate($data);
                            $dir = \dirname(__DIR__);
                            $ex = explode('intranet', $dir);
                            $destino = $ex[0] . 'intranet\public\files';
                            $adapter->setDestination($destino);
                            if ($adapter->receive()) {
                                $data['fundo'] = str_replace('\\', '/', end(explode('public', $adapter->getFileName())));
                                $setores->populate($data);
                            }
                        }
                    }
                    $setores->populate((array) $data);

                    $this->getEntityManager()->merge($setores);
                    $this->getEntityManager()->flush();
                }
                $this->flashMessenger()->addMessage('The Data are registred.');
                $this->redirect()->toRoute('rh/noticias');
            }
        }


        return array('form' => $form);
    }

    public function categoriaAction() {
          $data = new DateTime('now');
        $em = $this->getEntityManager();
        $not = $em->createQuery("SELECT Noticias FROM RH\Entity\Noticias Noticias where  Noticias.publicacao <= '{$data->format('Y-m-d')}' ORDER BY Noticias.idnoticia DESC");
        $noticias = $not->getResult();
        return new ViewModel(array('noticias' => $noticias));
    }

    public function verAction() {
        $id = $this->params()->fromRoute('id');
        $noticia = $this->getEntityManager()->find('RH\Entity\Noticias', $id);
        return new ViewModel(array('noticia' => $noticia));
    }

}
