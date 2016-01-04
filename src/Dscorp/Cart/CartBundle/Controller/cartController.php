<?php

namespace Dscorp\Cart\CartBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dscorp\Cart\CartBundle\Entity\cart;
use Dscorp\Cart\CartBundle\Form\cartType;

use Dscorp\Cart\CartBundle\Entity\itemCart;
use Dscorp\Cart\CartBundle\Form\itemCartType;

/**
 * cart controller.
 *
 */
class cartController extends Controller
{

    /**
     * Lists all cart entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CartBundle:cart')->findAll();

        return $this->render('CartBundle:cart:ViewCart.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new cart entity.
     *
     */

    public function revisarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user !='anon.'){
            if (!session_id()) {
               session_start();
            }
           $sessionID = session_id();
           $carrito = $em->getRepository('CartBundle:cart')->findOneBy(array('llave'=>$sessionID));        
           $line=$em->getRepository('AdminBundle:AdminProd')->findAllBycart($carrito/*->getId()*/);
           return $this->render('CartBundle:cart:cart_show.html.twig', array(
                'entity' => $entity,'id'=>$entity->getId(),
                //'form'   => $form->createView(),'id'=>$entity->getId(),
                'error' => '', 
                'carrito' => $carrito,
                'totalItemNumber'=>count($line),
                'totalitems' => count($line),'cartLines'=>$line,   
                      
           ));
    }else{              

            return $this->redirect($this->generateUrl('login'));

        }  
}

 public function registroAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
                
        if($user=='anon.'){
           $entity = new usuario();
           $form   = $this->CreateFormUser($entity);
           if (!session_id()) {
               session_start();
            }
           $sessionID = session_id();
           $carrito = $em->getRepository('CartBundle:cart')->findOneBy(array('llave'=>$sessionID));        
           $line=$em->getRepository('AdminBundle:AdminProd:prod')->findAllBycart($carrito->getId());
           return $this->render('AdminBundle:show.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),'id'=>$id,
                'carrito' => '',
                'error' => '', 
                'carrito' => $carrito,
                'totalitems' => count($line),'cartLines'=>$line,                
           ));
          
        }else{              

            return $this->redirect($this->generateUrl('perfil_usuario_direccion', array('id' => $id)));
        }
    }

    public function adduserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        //print_r($user);
        if($user=='anon.'){
          return $this->render('CartBundle:cart:vacio.html.twig', array(                
                //'delete_form' => $deleteForm->createView(),
            ));
          
        }else{
          //$usuario= $em->getRepository('UserCarritoBundle:Usuario')->find($user->getId());
          $entity = $em->getRepository('CartBundle:cart')->find($id);
          $carritoanterior = $em->getRepository('CartBundle:cart')->findOneBy(array('status'=>1/*,'usuario'=>$usuario*/));
            if (!$carritoanterior) {
                $elid=$entity->getId();
                //$entity->setUsuario($usuario);    
                $em->persist($entity);
                $em->flush();
                $elid=$entity->getId();

            }else{
                $line=$em->getRepository('CartBundle:itemCart')->findBy(array('carrito'=>$entity));
                foreach ($line as $key ) {                   
                        $key->setCarrito($carritoanterior);
                        $em->persist($key);
                        $em->flush();                   
                }
                $em->remove($entity);
                $em->flush();
                $elid=$carritoanterior->getId();
            }
            
            return $this->redirect($this->generateUrl('perfil_usuario_direccion', array('id' => $elid)));
                
        }
    }

    public function createAction(Request $request)
    {
        $entity = new cart();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cart_show', array('id' => $entity->getId())));
        }

        return $this->render('CartBundle:cart:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a cart entity.
     *
     * @param cart $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(cart $entity)
    {
        $form = $this->createForm(new cartType(), $entity, array(
            'action' => $this->generateUrl('cart_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new cart entity.
     *
     */
    public function newAction()
    {
        $entity = new cart();
        $form   = $this->createCreateForm($entity);

        return $this->render('CartBundle:cart:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cart entity.
     *
     */
    public function showAction($id)
    {
        
        /*$em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CartBundle:cart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CartBundle:cart:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));*/

        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
       
        
        if($user=='anon.'){ 
            if (!session_id()) {
               session_start();
            }
            $sessionID = session_id();
            $entity = $em->getRepository('CartBundle:cart')->findOneBy(array('id'=>$id,'llave'=>$sessionID));        
            $line=$em->getRepository('AdminBundle:AdminProd')->findAllBycart($entity/*->getId()*/);
            return $this->render('CartBundle:cart:Cart_show.html.twig', array(
                'entity'      => $entity,
                'carrito' => $entity,
                'cartLines'   =>$line,
                'error'   =>'',
                'totalItemNumber'=>count($line),
                'totalitems'=>count($line),
                //'delete_form' => $deleteForm->createView(),
            ));
        }else{
             //$usuario= $em->getRepository('UserCarritoBundle:Usuario')->find($user->getId());
             $entity = $em->getRepository('CartBundle:cart')->findOneBy(array('status'=>1/*,'usuario'=>$usuario*/));        
             $line=$em->getRepository('AdminBundle:AdminProd')->findAllBycart($entity->getId());
             $masked = "C|$id|hola|".$user->getId();
             $masked = base64_encode($masked);
             $masked = urlencode($masked);
             $masked = preg_replace('/=$/','',$masked);
             $masked = preg_replace('/=$/','',$masked);
             return $this->render('CartBundle:cart:cart_show.html.twig', array(
                'entity'      => $entity,
                'cartLines'   =>$line,
                'carrito' => $entity,
                'error'   =>'',
                'totalItemNumber'=>count($line),'masked'=>$masked,
                'totalitems'=>count($line),
                //'delete_form' => $deleteForm->createView(),
            ));     

        }
    }

    /**
     * Displays a form to edit an existing cart entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CartBundle:cart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cart entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CartBundle:cart:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a cart entity.
    *
    * @param cart $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(cart $entity)
    {
        $form = $this->createForm(new cartType(), $entity, array(
            'action' => $this->generateUrl('cart_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing cart entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CartBundle:cart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cart_edit', array('id' => $id)));
        }

        return $this->render('CartBundle:cart:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a cart entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CartBundle:cart')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find cart entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cart'));
    }

    /**
     * Creates a form to delete a cart entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cart_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
