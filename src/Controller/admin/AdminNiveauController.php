<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Niveau;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminNiveauController
 *
 * @author Salom
 */
class AdminNiveauController extends AbstractController {
    private const PAGEFORMATIONS = "admin/formations.html.twig";

    /**
     *
     * @var NiveauRepository
     */
    private $repository;
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;

    /**
     * 
     * @param NiveauRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(NiveauRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }

    /**
     * @Route("/admin/niveau", name="admin.niveau")
     * @return Response
     */
    public function index(): Response{
        $niveau = $this->repository->findAll();
        return $this->render("admin/admin.niveau.html.twig", [
            'niveau' => $niveau
        ]);
    }
    
    /**
     * @Route("/admin/niveau/suppr/{id}", name="admin.niveau.suppr")
     * @param Niveau $niveau
     * @return Response 
     */
    public function suppr(Niveau $niveau): Response{
        $this->om->remove($niveau);
        $this->om->flush();
        return $this->redirectToRoute('admin.niveau');
    }
    
    /**
     * @Route("/admin/niveau/ajout", name="admin.niveau.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $nomNiveau=$request->get("nom");
        $niveau = new Niveau();
        $niveau->setNom($nomNiveau);
        $this->om->persist($niveau);
        $this->om->flush();
        return $this->redirectToRoute('admin.niveau');
        }
        

}
