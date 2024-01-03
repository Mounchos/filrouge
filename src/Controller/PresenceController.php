<?php

namespace App\Controller;

use App\Entity\Presence;
use App\Form\PresenceType;
use App\Repository\CoursRepository;
use App\Repository\PresenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/presence')]
#[IsGranted('ROLE_USER')]
class PresenceController extends AbstractController
{
    /**
     * Fonction pour afficher la liste des cours
     *
     * @param CoursRepository $coursRepository
     * @return Response
     */
    #[Route('/', name: 'app_presence_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('presence/listeCours.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }
/**
     * fonction pour afficher la liste des inscrits à un cours particulier
     *
     * @param PresenceRepository $presenceRepository
     * @return Response
     */
    
    #[Route('/list/{id}', name: 'app_presence_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function indexPresence(PresenceRepository $presenceRepository): Response
    {
        return $this->render('presence/index.html.twig', [
            'presences' => $presenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_presence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PresenceRepository $presenceRepository): Response
    {
        $presence = new Presence();
        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presenceRepository->save($presence, true);

            return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('presence/new.html.twig', [
            'presence' => $presence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_presence_show', methods: ['GET'])]
    public function show(Presence $presence): Response
    {
        return $this->render('presence/show.html.twig', [
            'presence' => $presence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_presence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presence $presence, PresenceRepository $presenceRepository): Response
    {
        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presenceRepository->save($presence, true);

            return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('presence/edit.html.twig', [
            'presence' => $presence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_presence_delete', methods: ['POST'])]
    public function delete(Request $request, Presence $presence, PresenceRepository $presenceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presence->getId(), $request->request->get('_token'))) {
            $presenceRepository->remove($presence, true);
        }

        return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
    }
}
