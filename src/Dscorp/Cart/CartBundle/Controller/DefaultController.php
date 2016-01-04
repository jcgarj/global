<?php

namespace Dscorp\Cart\CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function cartAction()
    {
        $em = $this->getDoctrine()->getManager();
             
           //$entity = new usuario();
          //$form   = $this->CreateFormUser($entity);
           if(!session_id()) {
               session_start();
            }

           $sessionID = session_id();
           //session_start();
           $carrito = $em->getRepository('CartBundle:cart')->findOneBy(array('llave'=>$sessionID)); 
           $line=$em->getRepository('AdminBundle:AdminProd')->findAllBycart($carrito/*->getId()*/);
           return $this->render('CartBundle:cart:ViewCart.html.twig', array(
                //'entity' => $entity,
                //'form'   => $form->createView(),//'id'=>$entity->getId(),
                'carrito' => '',
                'error' => '', 
                'carrito' => $carrito,
                'totalitems' => count($line),'cartLines'=>$line,                
           ));         
       
    }

    

}
