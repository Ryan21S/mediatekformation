<?php



namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




/**
 * Description of AdminFormationsController
 *
 * @author Salom
 */
class AdminFormationsController extends AbstractController {
         private const PAGEFORMATIONS = "admin/formations.html.twig";

    /**
     *
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;

    /**
     * 
     * @param FormationRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(FormationRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }

    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->repository->findAll();
        return $this->render("admin/admin.formations.html.twig", [
            'formations' => $formations
        ]);
    }
    
    /**
     * @Route("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response 
     */
    public function suppr(Formation $formation): Response{
        $this->om->remove($formation);
        $this->om->flush();
        return $this->redirectToRoute('admin.formations');
    }
    
    /**
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response{
        $formFormation =$this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    
    /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation =$this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->om->persist($formation);
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.ajout.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }

    /**
     * @Route("/admin/formations/tri/{champ}/{ordre}", name="adminformations.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render("admin/admin.formations.html.twig", [
           'formations' => $formations
        ]);
    }   
        
    /**
     * @Route("/admin/formations/recherche/{champ}", name="adminformations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $formations = $this->repository->findByContainValue($champ, $valeur);
            return $this->render("admin/admin.formations.html.twig", [
                'formations' => $formations
            ]);
        }
        return $this->redirectToRoute("formations");
    }  
    
    /**
     * @Route("/admin/formation/{id}", name="adminformations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->repository->find($id);
        return $this->render("admin/admin.formation.html.twig", [
            'formation' => $formation
        ]);        
    }
}