<?php

namespace App\Controller;


use App\Entity\Activiteit;
use App\Entity\Soortactiviteit;
use App\Form\ActiviteitType;
use App\Form\SoortactiviteitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class MedewerkerController extends AbstractController
{
    /**
     * @Route("/admin", name="admin/index")
     */
    public function index()
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/activiteiten", name="admin/activiteiten")
     */
    public function activiteiten()
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/activiteiten/activiteiten.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/activiteiten/{id}", name="admin/activiteit")
     */

    /**
     * public function activiteit(Activiteit $activiteit)
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        $deelnemers=$this->getDoctrine()
            ->getRepository('App:User')
            ->getDeelnemers($activiteit->getId());

        return $this->render('medewerker/details.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteit'=>$soortactiviteiten,

            'activiteit'=>$activiteit,
            'deelnemers'=>$deelnemers,
            'aantal'=>count($activiteiten)
        ]);
    }
     * /

    /**
     * @Route("/admin/activiteiten/add", name="admin/activiteiten/add")
     */
    public function newActiviteit(Request $request)
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        // create a user and a contact
        $a=new Activiteit();

        $form = $this->createForm(ActiviteitType::class, $a);
        $form->add('save', SubmitType::class, array('label'=>"voeg toe"));
        //$form->add('reset', ResetType::class, array('label'=>"reset"));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();

            $this->addFlash(
                'notice',
                'activiteit toegevoegd!'
            );
            return $this->redirectToRoute('beheer');
        }

        return $this->render('medewerker/add.html.twig',[
            'form'=>$form->createView(),
            'naam'=>'toevoegen',
            'aantal'=>count($activiteiten),
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/activiteiten/show/{id}", name="admin/activiteiten/show")
     */
    public function showActiviteit()
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/beheer.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/activiteiten/update/{id}", name="admin/activiteiten/update")
     */
    public function editActiviteit($id,Request $request)
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        $a=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->find($id);

        $form = $this->createForm(ActiviteitType::class, $a);
        $form->add('save', SubmitType::class, array('label'=>"aanpassen"));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the contact (no queries yet)
            $em->persist($a);


            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            $this->addFlash(
                'notice',
                'activiteit aangepast!'
            );
            return $this->redirectToRoute('admin/activiteiten');
        }

        return $this->render('medewerker/add.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten,
            'form'=>$form->createView(),
            'naam'=>'aanpassen',
            'aantal'=>$activiteiten,
        ]);
    }

    /**
     * @Route("/admin/activiteiten/delete/{id}", name="admin/activiteiten/delete")
     */
    public function deleteActiviteit($id)
    {
        $em=$this->getDoctrine()->getManager();
        $a= $this->getDoctrine()
            ->getRepository('App:Activiteit')->find($id);
        $em->remove($a);
        $em->flush();

        $this->addFlash(
            'notice',
            'activiteit verwijderd!'
        );
        return $this->redirectToRoute('admin/activiteiten');

    }




    /**
     * @Route("/admin/soortactiviteiten", name="admin/soortactiviteiten", methods={"GET"})
     */
    public function soortActiviteiten(): Response
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/soortactiviteiten/index.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/soortactiviteiten/new", name="admin/soortactiviteiten/new", methods={"GET", "POST"})
     */
    public function newSoortActiviteit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $soortactiviteit = new Soortactiviteit();
        $form = $this->createForm(SoortactiviteitType::class, $soortactiviteit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($soortactiviteit);
            $entityManager->flush();

            return $this->redirectToRoute('admin/soortactiviteiten', [], Response::HTTP_SEE_OTHER);
        }

        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/soortactiviteiten/new.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/soortactiviteiten/{id}", name="admin/soortactiviteiten/show", methods={"GET"})
     */
    public function showSoortActiviteit(Soortactiviteit $soortactiviteit): Response
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        return $this->render('medewerker/soortactiviteiten/show.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten,
            'soortactiviteit'=>$soortactiviteit
        ]);
    }

    /**
     * @Route("/admin/soortactiviteiten/{id}/edit", name="admin/soortactiviteiten/edit", methods={"GET", "POST"})
     */
    public function editSoortActiviteit(Request $request, Soortactiviteit $soortactiviteit, EntityManagerInterface $entityManager): Response
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        $form = $this->createForm(SoortactiviteitType::class, $soortactiviteit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin/soortactiviteiten', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medewerker/soortactiviteiten/edit.html.twig', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten,
            'soortactiviteit'=>$soortactiviteit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/soortactiviteiten/{id}", name="admin/soortactiviteiten/delete", methods={"POST"})
     */
    public function deleteSoortActiviteit(Request $request, Soortactiviteit $soortactiviteit, EntityManagerInterface $entityManager): Response
    {
        $activiteiten=$this->getDoctrine()
            ->getRepository('App:Activiteit')
            ->findAll();
        $soortactiviteiten=$this->getDoctrine()
            ->getRepository('App:Soortactiviteit')
            ->findAll();

        if ($this->isCsrfTokenValid('delete' . $soortactiviteit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($soortactiviteit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin/soortactiviteiten', [
            'activiteiten'=>$activiteiten,
            'soortactiviteiten'=>$soortactiviteiten
        ], Response::HTTP_SEE_OTHER);
    }
}
