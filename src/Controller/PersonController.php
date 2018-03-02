<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    
    
}
