<?php

namespace Dscorp\Cart\CartBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dscorp\Cart\CartBundle\Entity\itemCart;
use Dscorp\Cart\CartBundle\Form\itemCartType;

use Dscorp\Cart\CartBundle\Entity\cart;
use Dscorp\Cart\CartBundle\Form\cartType;

/**
 * itemCart controller.
 *
 */
class itemCartController extends Controller
{

    /**
     * Lists all itemCart entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CartBundle:itemCart')->findAll();

        return $this->render('CartBundle:itemCart:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new itemCart entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new itemCart();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('itemcart_show', array('id' => $entity->getId())));
        }

        return $this->render('CartBundle:itemCart:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a itemCart entity.
     *
     * @param itemCart $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(itemCart $entity)
    {
        $form = $this->createForm(new itemCartType(), $entity, array(
            'action' => $this->generateUrl('itemcart_create'),
            'method' => 'POST',
        ));


        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }


    // funcion de agregar a carrito
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!session_id()) {
            session_start();
        }
        $sessionID = session_id();
        if ($user == 'anon.') {
            $entityCart= $em->getRepository('CartBundle:cart')->findOneBy(array('llave'=>$sessionID,'estatus'=>1));
            if(!$entityCart){
                $entityCart = new cart();
                //$usuario = em ->getRepository('Busqueda de usuario ')
                $entityCart->setLlave($sessionID);
                $entityCart->setestatus(1);
                $entityCart->setDateCart(new \DateTime("now"));
                // $entityCart->setUsuario($usuario)
                $em->persist($entityCart);
                $em->flush();
        }else{
            //$usuario= $em->getRepository('UserCarritoBundle:Usuario')->find($user->getId());
            $entityCart= $em->getRepository('CartBundle:cart')->findOneBy(array(/*'Usuario'=>$usuario*/'estatus'=>1));
            if(!$entityCart){
                $entityCart = new carrito();             
                $entityCart->setLlave($sessionID);
                $entityCart->setEstatus(1);
                $entityCart->setDateCart(new \DateTime("now"));
                $entityCart->setUsuario($usuario);               
                $em->persist($entityCart);
                $em->flush();
        }       
    }
    $id= $this->get('request')->request->get('txtProducto');
     if($id>0){
      $producto = $em->getRepository('AdminBundle:AdminProd')->find($id);
      if($this->get('request')->request->get('txtCantidad',1)=='' ){
        $cantidad=1;
      }else{
         $cantidad=$this->get('request')->request->get('txtCantidad',1);
      }        
        $entity = new itemCart();
        $entity->setCantidad($cantidad);
        $total=$producto->getPrecio()*$cantidad;
        /*$entity->setCantidadPeriodo(1);
        $entity->setRenta($renta);
        $entity->setPeriodo($periodo);*/
        $entity->setprecioU($total);
        $entity->setCarrito($entityCarrito);
        $entity->setAdmonProd($producto);
        $entity->setDate(new \DateTime("now"));        
        $em->persist($entity);
        $em->flush();
    }
            return $this->redirect($this->generateUrl('cart_show', array('id' => $entityCart->getId())));
        }
    }
    /**
     * Displays a form to create a new itemCart entity.
     *
     */

    public function newAction()
    {
        $entity = new itemCart();
        $form   = $this->createCreateForm($entity);

        return $this->render('CartBundle:itemCart:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a itemCart entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CartBundle:itemCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find itemCart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CartBundle:itemCart:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemCart entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CartBundle:itemCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find itemCart entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CartBundle:itemCart:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a itemCart entity.
    *
    * @param itemCart $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(itemCart $entity)
    {
        $form = $this->createForm(new itemCartType(), $entity, array(
            'action' => $this->generateUrl('itemcart_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing itemCart entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CartBundle:itemCart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find itemCart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('itemcart_edit', array('id' => $id)));
        }

        return $this->render('CartBundle:itemCart:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a itemCart entity.
     *
     */
    public function deleteAllAction(Request $request, $id)
    {
        /*
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CartBundle:itemCart')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find itemCart entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('itemcart')); */
         $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $sessionID = session_id();
        if($user=='anon.'){            
            $entityCarrito= $em->getRepository('CartBundle:cart')->findOneBy(array('llave'=>$sessionID,'estatus'=>1));
        }else{
            //$usuario= $em->getRepository('UserCarritoBundle:Usuario')->find($user->getId());
            $entityCarrito= $em->getRepository('CartBundle:cart')->findOneBy(array('usuario'=>$usuario,'id'=>$id));
        }
        if ($entityCarrito) {
            $line=$em->getRepository('CartBundle:itemCart')->findBy(array('cart'=>$entityCarrito));
            foreach ($line as $key ) {               
                if (!$key) {
                    throw $this->createNotFoundException('Unable to find itemCart entity.');
                }

                $em->remove($key);
                $em->flush();
            }
        }
        $entity = $em->getRepository('CartBundle:itemCart')->find($id);
        $line=$em->getRepository('AdminBundle:admonProd')->findAllBycart($entity/*->getId()*/);
        return $this->render('CartBundle:cart:Cart_show.html.twig', array(
            'entity'      => $entity,
            'carrito' => $entity,
            'cartLines'   =>$line,
            'error'   =>'',
            'totalItemNumber'=>count($line),
            'totalitems'=>count($line),
        ));
    }

    /**
     * Creates a form to delete a itemCart entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemcart_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
