<?php

namespace RH\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @ORM\Entity 
 * @ORM\Table(name="CategoriaNoticia")
 * */
class CategoriaNoticia {

    /**
     * @Annotation\AllowEmpty(true)
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue
     * @ORM\OneToMany(targetEntity="RH\Entity\Noticias", mappedBy="categorianoticia_fk")
     */
    public $idcategorianoticia;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":"1"}})
     * @Annotation\Options({"label":"Categoria : "})
     * @ORM\Column(type="string")
     */
    public $categorianome;

    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\AllowEmpty(true)
     * @Annotation\Options({"label":"Descrição: "})
     * @ORM\Column(type="text")
     */
    public $descricao;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Enviar","class":"btn btn-success"})
     */
    public $submit;

    public function getIdcategorianoticia() {
        return $this->idcategorianoticia;
    }

    public function setIdcategorianoticia($idcategorianoticia) {
        $this->idcategorianoticia = $idcategorianoticia;
    }

    public function getCategorianome() {
        return $this->categorianome;
    }

    public function setCategorianome($categorianome) {
        $this->categorianome = $categorianome;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getSubmit() {
        return $this->submit;
    }

    public function setSubmit($submit) {
        $this->submit = $submit;
    }

    public function populate(array $data) {
        $this->setCategorianome($data['categorianome']);
        $this->setDescricao($data['descricao']);
        $this->setIdcategorianoticia($data['idcategorianoticia']);
    }

}

?>
