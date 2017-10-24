<?php

namespace AllocatorBundle\Controller;

use AllocatorBundle\Entity\Locker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Locker controller.
 *
 * @Route("locker")
 */
class LockerController extends Controller
{

    /**
     * Lists all locker entities.
     * @Route("/page/{page}", name="paginate" )
     * @Method("GET")
     */
    public function lockerPaginationAction($page)
    {
        $em = $this->getDoctrine()->getManager();


        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 30;

        // On récupère notre objet Paginator
        $lockers = $em->getRepository('AllocatorBundle:Locker')->findAllPagine($page, $nbPerPage);

        // On calcule le nombre total de pages grâce au count($lockers) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($lockers) / $nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue
        return $this->render('locker/indexPaginate.html.twig', array(
            'lockers' => $lockers,
            'nbPages'     => $nbPages,
            'page'        => $page,
        ));
    }

    /**
     * Lists all locker entities.
     *
     * @Route("/", name="locker_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('paginate', array('page' => 1));

        $em = $this->getDoctrine()->getManager();

        /*
        $lockers = $em->getRepository('AllocatorBundle:Locker')->findAll();

        return $this->render('locker/index.html.twig', array(
            'lockers' => $lockers,
        ));
        */
    }

    /**
     * Creates a new locker entity.
     *
     * @Route("/new", name="locker_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $locker = new Locker();
        $form = $this->createForm('AllocatorBundle\Form\LockerType', $locker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($locker);
            $em->flush();

            return $this->redirectToRoute('locker_show', array('id' => $locker->getId()));
        }

        return $this->render('locker/new.html.twig', array(
            'locker' => $locker,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a locker entity.
     *
     * @Route("/{id}", name="locker_show")
     * @Method("GET")
     */
    public function showAction(Locker $locker)
    {
        $deleteForm = $this->createDeleteForm($locker);

        return $this->render('locker/show.html.twig', array(
            'locker' => $locker,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing locker entity.
     *
     * @Route("/{id}/edit", name="locker_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Locker $locker)
    {
        $deleteForm = $this->createDeleteForm($locker);
        $editForm = $this->createForm('AllocatorBundle\Form\LockerType', $locker);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('locker_edit', array('id' => $locker->getId()));
        }

        return $this->render('locker/edit.html.twig', array(
            'locker' => $locker,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a locker entity.
     *
     * @Route("/{id}", name="locker_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Locker $locker)
    {
        $form = $this->createDeleteForm($locker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($locker);
            $em->flush();
        }

        return $this->redirectToRoute('locker_index');
    }

    /**
     * Creates a form to delete a locker entity.
     *
     * @param Locker $locker The locker entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Locker $locker)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('locker_delete', array('id' => $locker->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
