<?php

namespace Dscorp\Cart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dscorp\Cart\AdminBundle\Entity\AdminProd;
use Dscorp\Cart\AdminBundle\Form\AdminProdType;

/**
 * AdminProd controller.
 *
 */
class AdminProdController extends Controller
{

    /**
     * Lists all AdminProd entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:AdminProd')->findAll();

        return $this->render('AdminBundle:AdminProd:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new AdminProd entity.
     *
     */
   
    public function prodAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:AdminProd')->find($id);
        // replace this example code with whatever you need
        return $this->render('CartBundle:cart:prod.html.twig', array(
            'entity' => $entity,
            ));

    }
     
    public function createAction(Request $request)
    {
        $entity = new AdminProd();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminprod_show', array('id' => $entity->getId())));
        }

        return $this->render('AdminBundle:AdminProd:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a AdminProd entity.
     *
     * @param AdminProd $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdminProd $entity)
    {
        $form = $this->createForm(new AdminProdType(), $entity, array(
            'action' => $this->generateUrl('adminprod_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AdminProd entity.
     *
     */
    public function newAction()
    {
        $entity = new AdminProd();
        $form   = $this->createCreateForm($entity);

        return $this->render('AdminBundle:AdminProd:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminProd entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:AdminProd')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminProd entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AdminBundle:AdminProd:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminProd entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:AdminProd')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminProd entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AdminBundle:AdminProd:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a AdminProd entity.
    *
    * @param AdminProd $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AdminProd $entity)
    {
        $form = $this->createForm(new AdminProdType(), $entity, array(
            'action' => $this->generateUrl('adminprod_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing AdminProd entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:AdminProd')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminProd entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adminprod_edit', array('id' => $id)));
        }

        return $this->render('AdminBundle:AdminProd:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a AdminProd entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:AdminProd')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AdminProd entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adminprod'));
    }

    /**
     * Creates a form to delete a AdminProd entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminprod_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
