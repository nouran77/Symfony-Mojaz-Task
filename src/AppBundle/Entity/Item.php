<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message="Please add Item Title")
     */
    private $title;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ListItem", inversedBy="items")
     * @ORM\JoinColumn(name="list_id", referencedColumnName="id")
     */
    private $list;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Item
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set list
     *
     * @param \AppBundle\Entity\ListItem $list
     *
     * @return Item
     */
    public function setList(\AppBundle\Entity\ListItem $list = null)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * Get list
     *
     * @return \AppBundle\Entity\ListItem
     */
    public function getList()
    {
        return $this->list;
    }
}
