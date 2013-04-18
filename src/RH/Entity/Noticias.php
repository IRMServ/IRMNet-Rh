<?php

namespace RH\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use \Datetime;
/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @ORM\Entity 
 * @ORM\Table(name="Noticias")
 * */
class Noticias {

    /**
     * @Annotation\AllowEmpty(true)
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue
     */
    public $idnoticia;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":"1"}})
     * @Annotation\Options({"label":"Título *: "})
     * @ORM\Column(type="string")
     */
    public $titulo;

    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Required({"required":"true" })
     * @Annotation\Validator({"name":"StringLength", "options":{"min":"1"}})
     * @Annotation\Options({"label":"Texto *: "})
     * @ORM\Column(type="text")
     */
    public $texto;

    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\AllowEmpty(true)
     * @Annotation\Options({"label":"Fontes: "})
     * @ORM\Column(type="text")
     */
    public $fontes;

    /**
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\AllowEmpty(true)
     * @ORM\Column(type="date",nullable=true)
     */
    public $data;

    /**
     * @Annotation\Type("Zend\Form\Element\Date")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Data da publicação: "}) 
     * @Annotation\AllowEmpty(true)
     * @ORM\Column(type="date",nullable=true)
     */
    public $publicacao;

    /**
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @Annotation\AllowEmpty(true)

     * @Annotation\Filter({"name":"StripTags"}) 
     * @ORM\Column(type="string")
     */
    public $autor;

    /**
     * @Annotation\AllowEmpty(true)    
     * @Annotation\Type("Zend\Form\Element\File")
     * @Annotation\Options({"label":"Fundo: "})    
     * @ORM\Column(type="text",nullable=true,length=245)
     */
    public $fundo;

    /**
     * @Annotation\AllowEmpty(true)    
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({"label":"Destaque: "})    
     * @ORM\Column(type="boolean",nullable=true)
     */
    public $destaque;

    /**
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Options({"label":"Categoria *: "})
     * @Annotation\Required({"required":"true" }) 
     * @ORM\ManyToOne(targetEntity="RH\Entity\CategoriaNoticia", inversedBy="idcategorianoticia")
     * @ORM\JoinColumn(name="categorianoticia_fk", referencedColumnName="idcategorianoticia")
     */
    public $categorianoticia_fk;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Enviar","class":"btn btn-success"})
     */
    public $submit;

    public function getIdnoticia() {
        return $this->idnoticia;
    }

    public function setIdnoticia($idnoticia) {
        $this->idnoticia = $idnoticia;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function getFontes() {
        return $this->fontes;
    }

    public function setFontes($fontes) {
        $this->fontes = $fontes;
    }

    public function getData() {
        return $this->data->format('d/m/Y') ;
    }

    public function setData($data) {
        $this->data = new DateTime('now');
    }

    public function getPublicacao() {
        return $this->publicacao->format('d/m/Y') ;
    }

    public function setPublicacao($publicacao) {

        $this->publicacao =  new DateTime(implode('-',array_reverse(explode('/',$publicacao))));
    }

    public function getAutor() {
        return $this->autor;
    }

    public function setAutor($autor) {
        $this->autor = $autor;
    }

    public function getFundo() {
        return $this->fundo;
    }

    public function setFundo($fundo) {
        $this->fundo = $fundo;
    }

    public function getDestaque() {
        return $this->destaque;
    }

    public function setDestaque($destaque) {
        $this->destaque = $destaque;
    }

    public function getCategorianoticia_fk() {
        return $this->categorianoticia_fk;
    }

    public function setCategorianoticia_fk($categorianoticia_fk) {
        $this->categorianoticia_fk = $categorianoticia_fk;
    }

    public function populate(array $data) {
        $this->setAutor($data['autor']);
        $this->setCategorianoticia_fk($data['categorianoticia_fk']);
        $this->setData($data['data']);
        $this->setDestaque($data['destaque']);
        $this->setFontes($data['fontes']);
        $this->setFundo($data['fundo']);
        $this->setIdnoticia($data['idnoticia']);
        $this->setPublicacao($data['publicacao']);
        $this->setTexto($data['texto']);
        $this->setTitulo($data['titulo']);
    }

    public function getSubmit() {
        return $this->submit;
    }

    public function setSubmit($submit) {
        $this->submit = $submit;
    }

}

?>
