<?php
namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Entity\Report;
use App\Form\ReportType;
use App\Entity\Rating;
use App\Form\RatingType;
use App\Entity\User;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\DetecteurMotsInappropries;

#[Route('/forum')]
class ForumController extends AbstractController
{
    private $detecteurMotsInappropries;

    // Injection du service via le constructeur
    public function __construct(DetecteurMotsInappropries $detecteurMotsInappropries)
    {
        $this->detecteurMotsInappropries = $detecteurMotsInappropries;
    }

    #[Route('/', name: 'forum_index', methods: ['GET'])]
    public function index(
        ForumRepository $forumRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        // Récupérez tous les forums approuvés
        $query = $forumRepository->createQueryBuilder('f')
            ->where('f.isApproved = :isApproved')
            ->setParameter('isApproved', true)
            ->orderBy('f.Date', 'DESC')
            ->getQuery();

        // Paginez les résultats
        $forums = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        // Calculez la moyenne des notes pour chaque forum
        foreach ($forums as $forum) {
            $ratings = $forum->getRatings();
            $totalStars = 0;
            $ratingCount = count($ratings);

            foreach ($ratings as $rating) {
                $totalStars += $rating->getStars();
            }

            $averageRating = $ratingCount > 0 ? $totalStars / $ratingCount : 0;
            $forum->averageRating = $averageRating; // Ajoutez la moyenne au forum
        }

        return $this->render('forum/index.html.twig', [
            'forums' => $forums,
        ]);
    }

    #[Route('/new', name: 'forum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le titre ou la description contient des mots inappropriés
            $titre = $forum->getTitle();
            $description = $forum->getDescription();

            // Vérifier le titre
            if ($this->detecteurMotsInappropries->detecter($titre)) {
                $this->addFlash('error', 'Le titre de votre publication contient des mots inappropriés et ne peut pas être publié.');
                return $this->redirectToRoute('forum_new');
            }

            // Vérifier la description
            if ($this->detecteurMotsInappropries->detecter($description)) {
                $this->addFlash('error', 'La description de votre publication contient des mots inappropriés et ne peut pas être publiée.');
                return $this->redirectToRoute('forum_new');
            }

            // Si aucun mot inapproprié n'est détecté, enregistrer le forum
            $forum->setIsApproved(false); // Par défaut, le forum n'est pas approuvé
            $forum->setUser($this->getUser());
            $forum->setDate(new \DateTime());

            $entityManager->persist($forum);
            $entityManager->flush();

            $this->addFlash('success', 'Votre publication a été soumise avec succès et est en attente d\'approbation.');
            return $this->redirectToRoute('forum_new');
        }

        return $this->render('forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'forum_show', methods: ['GET'])]
    public function show(Forum $forum, Request $request, PaginatorInterface $paginator): Response
    {
        // Récupérer les réponses paginées
        $responses = $paginator->paginate(
            $forum->getForomRep(),
            $request->query->getInt('page', 1),
            3
        );

        // Récupérer les notations (ratings) associées au forum
        $ratings = $forum->getRatings();

        // Calculer la moyenne des notes
        $totalStars = 0;
        $ratingCount = count($ratings);

        foreach ($ratings as $rating) {
            $totalStars += $rating->getStars();
        }

        $averageRating = $ratingCount > 0 ? $totalStars / $ratingCount : 0;

        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
            'responses' => $responses,
            'ratings' => $ratings,
            'averageRating' => $averageRating,
        ]);
    }

    #[Route('/{id}/edit', name: 'forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le titre ou la description contient des mots inappropriés
            $titre = $forum->getTitle();
            $description = $forum->getDescription();

            // Vérifier le titre
            if ($this->detecteurMotsInappropries->detecter($titre)) {
                $this->addFlash('error', 'Le titre de votre publication contient des mots inappropriés et ne peut pas être modifié.');
                return $this->redirectToRoute('forum_edit', ['id' => $forum->getId()]);
            }

            // Vérifier la description
            if ($this->detecteurMotsInappropries->detecter($description)) {
                $this->addFlash('error', 'La description de votre publication contient des mots inappropriés et ne peut pas être modifiée.');
                return $this->redirectToRoute('forum_edit', ['id' => $forum->getId()]);
            }

            // Si aucun mot inapproprié n'est détecté, enregistrer les modifications
            $entityManager->flush();

            $this->addFlash('success', 'Votre publication a été modifiée avec succès.');
            return $this->redirectToRoute('forum_show', ['id' => $forum->getId()]);
        }

        return $this->render('forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'forum_delete', methods: ['POST'])]
    public function delete(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_index');
    }

    #[Route('/{id}/rate', name: 'forum_rate', methods: ['GET', 'POST'])]
    public function rate(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $rating = new Rating();
        $rating->setForum($forum);
        $rating->setUser($this->getUser());

        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rating);
            $entityManager->flush();

            $this->addFlash('success', 'Votre notation a été enregistrée avec succès.');
            return $this->redirectToRoute('forum_show', ['id' => $forum->getId()]);
        }

        return $this->render('forum/rate.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forum/report/{id}', name: 'forum_report', methods: ['GET', 'POST'])]
    public function report(Forum $forum, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifiez si l'utilisateur a déjà signalé ce forum
        if ($forum->isReportedByUser($user)) {
            $this->addFlash('warning', 'Vous avez déjà signalé ce forum.');
            return $this->redirectToRoute('forum_show', ['id' => $forum->getId()]);
        }

        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $report->setForum($forum);
            $report->setReportedBy($user);

            $entityManager->persist($report);
            $entityManager->flush();

            $entityManager->refresh($forum);

            // Vérifiez si le forum a été signalé 3 fois
            if ($forum->isReportedThreeTimes()) {
                // Bannir l'utilisateur qui a créé le forum
                $forumCreator = $forum->getUser();
                $forumCreator->setBannedUntil(new \DateTime('+2 minute')); // Bannir pour 2 minutes (pour tester)

                // Supprimer le forum
                $entityManager->remove($forum);

                // Enregistrer les modifications
                $entityManager->flush();

                $this->addFlash('warning', 'Le forum a été supprimé et l\'utilisateur a été banni en raison de 3 signalements.');
                return $this->redirectToRoute('forum_index'); // Rediriger vers la liste des forums
            }

            $this->addFlash('success', 'Le forum a été signalé.');
            return $this->redirectToRoute('forum_show', ['id' => $forum->getId()]);
        }

        return $this->render('forum/report.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }
}