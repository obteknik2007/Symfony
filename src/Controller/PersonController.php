<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use function dump;

/**
     * @Route("/person")
     */

class PersonController extends Controller
{
    /**
     * //Le chemin complet de la route est /person/list
     * @Route("/list")
     */
    public function index()
    {
        //récupérartion de l'entity manager // gestionnaire d'entités de Doctrine
        $em = $this->getDoctrine()->getManager();
        dump(Person::class);
        $personRepository = $em->getRepository(Person::class);
        
        //select * sur la table Person - ne renvoit pas comme pdo un tableau, mais un tableau d'objets person (3 instances
        $persons = $personRepository->findAll();
        
        dump($persons);
        return $this->render('person/index.html.twig', 
            [
            'persons' => $persons
        ]);
    }
    
     /**
     * L'id doit être un nombre (\d+ en expression regex)
     * @Route("/{id}",requirements={"id":"\d+"}) //annotation
     * @param int $id //commentaire de documentation
     */
    public function detail($id)
    {
        //ns reprenons les 2 lignes de la méthode précédente
        $em = $this->getDoctrine()->getManager();
        $personRepository = $em->getRepository(Person::class);
        
        //méthode find()-retourne un objet Person par la clé primaire
        //ou null si absence de résultat
        $person = $personRepository->find($id);
        
        //exception - s'il n'y a pas de personne avec l'id reçu dans l'url
        // on jette une 404
        if(is_null($person)) {
            throw new NotFoundHttpException();
        }
        
        //si pas null, on passe à la vue
        return $this->render('person/detail.html.twig', 
        [
            'person' => $person
        ]);        
    }
    
    /**
     * @Route("/search") //annotation
     * 
     */
    public function search(Request $request)
    {
        $person = null; //pour éviter d'avoir un undefined variable à l'affichage page
        
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $personRepository = $em->getRepository(Person::class);
        
            $person = $personRepository->findOneBy([ // renvoie 1 ou 0 résultat
                'email' => $request->request->get('email')
            ]); //on pourait rajouter d'autres champs et la clause va être construite en adéquation
        }
        
        return $this->render('person/search.html.twig', 
        [
            'person' => $person
        ]);        
    }
        /**
        * @Route("/search/lastname") 
        */
        public function searchByLastname(Request $request)
    {
        $persons = []; //il peut y avoir des noms égaux
        
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $personRepository = $em->getRepository(Person::class);
        
            $persons = $personRepository->findBy([ // renvoie 1 ou 0 résultat
                'lastname' => $request->request->get('lastname')
            ]); 
        }
        
        return $this->render('person/search_by_lastname.html.twig', 
        [
            'persons' => $persons
        ]);        
    }
    
}
