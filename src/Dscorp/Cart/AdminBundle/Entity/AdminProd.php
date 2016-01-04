<?php

namespace Dscorp\Cart\AdminBundle\Entity;

/**
 * AdminProd
 */
class AdminProd
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $caracteristicas;

    /**
     * @var string
     */
    private $precioU;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set caracteristicas
     *
     * @param string $caracteristicas
     *
     * @return AdminProd
     */
    public function setCaracteristicas($caracteristicas)
    {
        $this->caracteristicas = $caracteristicas;

        return $this;
    }

    /**
     * Get caracteristicas
     *
     * @return string
     */
    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }

    /**
     * Set precioU
     *
     * @param string $precioU
     *
     * @return AdminProd
     */
    public function setPrecioU($precioU)
    {
        $this->precioU = $precioU;

        return $this;
    }

    /**
     * Get precioU
     *
     * @return string
     */
    public function getPrecioU()
    {
        return $this->precioU;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $itemCart;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemCart = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add itemCart
     *
     * @param \Dscorp\Cart\CartBundle\Entity\itemCart $itemCart
     *
     * @return AdminProd
     */
    public function addItemCart(\Dscorp\Cart\CartBundle\Entity\itemCart $itemCart)
    {
        $this->itemCart[] = $itemCart;

        return $this;
    }

    /**
     * Remove itemCart
     *
     * @param \Dscorp\Cart\CartBundle\Entity\itemCart $itemCart
     */
    public function removeItemCart(\Dscorp\Cart\CartBundle\Entity\itemCart $itemCart)
    {
        $this->itemCart->removeElement($itemCart);
    }

    /**
     * Get itemCart
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemCart()
    {
        return $this->itemCart;
    }
}
