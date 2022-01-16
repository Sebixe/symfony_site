<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Entity\Content;
use App\Repository\ContentRepository;
use App\Form\ContentType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Form\LevelType;
use App\Entity\Usercontent;
use App\Repository\UsercontentRepository;
use App\Entity\Images;
use App\Repository\ImagesRepository;
use App\Form\ImagesType;

use Symfony\Component\HttpKernel\Profiler;
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Intl\Languages;

use App\Search\Search;
use App\Search\SearchFullType;
use App\Search\SearchType;
use App\Search\SearchCompleteType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie; 
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
     /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    
        
    /**
     * @Route("/", name="home")
     * Page principale sur lequel l'utilisateur aura accès à la plupart des outils disponibles, le controller donne accès : 
     * 1. A la modification du niveau depuis le profil utilisateur
     * 2. Une présentation dynamique des contenus disponible en fonction du niveau du joueur
     * 3. L'instanciation des activités journa/hebdo faites par l'utilisateur dans sa session
     */
    public function home(Request $request, EntityManagerInterface $em, ImagesRepository $imagesRepository, UsercontentRepository $usercontentRepository, ContentRepository $contentRepository): Response
    {
        $user = $this->security->getUser();
        
        /* Récupère les images uploadées par l'admin, et de les envoyer à l'utilisateur afin qu'il change son image de fond si il possède le niveau requis*/
        
        $images = $imagesRepository->findBy(array(),array('level'=>'asc'));
        
        /* Permet de vérifier le niveau du joueur pour lui proposer des contenus adapté à son niveau, sans pour autant ne pas en afficher certains dans le cas où il serait très haut niveau. */
        
        if(!is_null($user)){
        $level = $user->getLevel();
        $levels = [($level),($level-1),($level-2),($level-3),($level-4),($level-5),($level-6),($level-7),($level-8),($level-9),($level-10),($level-11),($level-12),($level-13),($level-14),($level-15),($level-16),($level-17),($level-18),($level-19),($level-20),($level-21),($level-22),($level-23),($level-24),($level-25),($level-26),($level-27),($level-28),($level-29),($level-30),($level-31),($level-32),($level-33),($level-34),($level-35),($level-36),($level-37),($level-38),($level-39),($level-40),($level-41),($level-42),($level-43),($level-44),($level-45),($level-46),($level-47),($level-48),($level-49),($level-50),($level-51),($level-52),($level-53),($level-54),($level-55),($level-56),($level-57),($level-58),($level-59),($level-60),($level-61),($level-62),($level-63),($level-64),($level-65),($level-66),($level-67),($level-68),($level-69),($level-70),($level-71),($level-72),($level-73),($level-74),($level-75),($level-76),($level-77),($level-78),($level-79),($level-80),($level-81),($level-82),($level-83),($level-84),($level-85),($level-86)];
            
        /* Permet de ne sélectionner que les contenus de l'utilisateur actuellement connecté */
        $userid = $user->getId();       
        /* Permet de ne sélectionner que les contenus qui n'ont pas été coché */    
        $state= $usercontentRepository->findBy(['user_id' => $userid, 'state'=> false]);
          
        /* Tri des contenus en 4 catégories, les Donjons, les Défis, les Raids et le reste */    
        $dungeons = $contentRepository->findBy(array('type' => 'dungeon','level'=>$levels,'id'=>$state),array('level'=> 'Desc'),5,0);
        $trials = $contentRepository->findBy(array('type' => 'trial','level'=>$levels,'id'=>$state),array('level'=> 'Desc'),5,0);
        $raids = $contentRepository->findBy(array('type' => 'raid','level'=>$levels,'id'=>$state),array('level'=> 'Desc'),5,0);
        $others = $contentRepository->findBy(array('type' => 'other','level'=>$levels,'id'=>$state),array('level'=> 'asc'),5,0);
        
            
        $session = new Session();   
        /* Récupération des données des cases déjà cochées dans la rubrique "Que faire ?" en les chargeant depuis la session */    
        $quest = $session->get('quest');
        
        /* Form pour le niveau dans l'onglet "Profil" */    
        $form = $this->createForm(LevelType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('home');
                }
            return $this->render('pages/home.html.twig', ['LevelForm' => $form->createView(),'user'=>$user, 'dungeons'=>$dungeons, 'trials'=>$trials, 'raids'=>$raids, 'others'=>$others, 'quest'=>$quest, 'images'=>$images]);
        }
        return $this->render('pages/home.html.twig', ['user'=>$user]);
    }

      /**
     * @Route("/job/{id}", name="job")
     * Pages de Job divisées en 5 onglet diffèrent en fonction du rôle du Job utilisé, utilise les informations de la DB pour savoir quoi charger comme page
     */
    public function job(int $id, JobRepository $jobRepository): Response
    {
        $user = $this->security->getUser();
        $job = $jobRepository->find($id);
        if ($job === null) {
            throw new NotFoundHttpException("Job inexistant");
        }else{if($id === 21|$id === 1|$id === 2|$id === 3|$id === 4 ){
                return $this->render('pages/job/tank.html.twig', ['job' => $job, 'user' => $user]);
             }else{if($id === 22|$id ===5|$id ===6|$id ===7|$id ===8 ){
                    return $this->render('pages/job/heal.html.twig', ['job' => $job, 'user' => $user]);
                  }else{if($id === 23|$id ===9|$id ===10|$id ===11|$id ===12|$id ===13 ){
                        return $this->render('pages/job/melee.html.twig', ['job' => $job, 'user' => $user]);
                        }else{if($id === 24|$id ===14|$id ===15|$id ===16 ){
                            return $this->render('pages/job/ranged.html.twig', ['job' => $job, 'user' => $user]);
                              }else{if($id === 25|$id ===17|$id ===18|$id ===19|$id ===20 ){
                                return $this->render('pages/job/caster.html.twig', ['job' => $job, 'user' => $user]);
                                    }else{return $this->render('pages/jobs.html.twig', ['job' => $job, 'user' => $user]);
                                        }
                                    }
                             }
                        }
                  }
             }
    }
    
  /**
    * @Route("/changethewall", name="changethewall")
    * Permet de changer le wallpaper de l'utilisateur en postant l'un de ceux disponible dans la liste et en l'enregistrant
    * dans la table User sous la colonne "Wallpaper"
    */
    
    public function changethewall (Request $request, EntityManagerInterface $em): Response
        {
               $user = $this->security->getUser();
               $wallpaper = $request->request->get('wallpaper');
            
                    $user->setWallpaper($wallpaper);
                    $em->persist($user);
                    $em->flush();
                                     
            return $this->redirectToRoute('home');
    }  
    
    
    /**
    * @Route("/session", name="session")
    * Sauvegarde des cases cochées des activités dans la session de l'utilisateur
    */
    
    public function session (Request $request): Response
        {
                $this->get('session')->remove('quest');
                $activities= $request->request->all();
                $sessionVal[] = $activities;
                $this->get('session')->set('quest', $sessionVal);
                           
            return $this->redirectToRoute('home');
    }
    
    /**
    * @Route("/savecontent", name="save")
    * Bouton qui permet après avoir cochés les cases des contenus effectués de modifier correctement la DB, le bouton est disponible sur la Home Page et sur les diffèrentes pages Content
    */
    
    public function savecontent (Request $request, EntityManagerInterface $em,UsercontentRepository $usercontentRepository): Response
        {
                $user = $this->security->getUser();
                $content = $request->request->all();
                    
                        foreach($content as $id=>$value ){
                            $usercontent = $usercontentRepository->findOneBy(['user_id' => $user, 'content_id' => $id]);   
                            /* Permet de passer l'information en DB de False à True */
                            if($value == 1) {
                                $usercontent->setState(1);
                            } else {
                                $usercontent->setState(0);
                            }
                            $em->persist($usercontent);
                            $em->flush();
                        }  
            return $this->redirectToRoute('home');
    }
    
    /**
    * @Route("/refreshcontent", name="refresh")
    * Bouton pour réinitialiser l'usercontent de l'utilisateur, c'est à dire le décocher tous les contenus effectué par l'utilisteur, au cas où cela est nécessaire
    */
    
    public function refreshcontent (Request $request, EntityManagerInterface $em,UsercontentRepository $usercontentRepository): Response
        {
                $user = $this->security->getUser();
                $usercontent = $usercontentRepository->findBy(['user_id' => $user]);
                    
                        foreach($usercontent as $usercontent){
                                $usercontent->setState(0);
                            }
                            $em->persist($usercontent);
                            $em->flush();
                         
            return $this->redirectToRoute('home');
    }
    
 
    /**
    * @Route("/arr", name="arr")
    * Page qui affiche les contenus en fonction de l'extension, ici A Realm Reborn, et si l'utilisateur est connecté, de donner accès à chaque case cochables.
    */
    public function arr (UsercontentRepository $usercontentRepository, Request $request, ContentRepository $contentRepository, EntityManagerInterface $em): Response
     {
        $user = $this->security->getUser();
        $dungeons = $contentRepository->findBy(['type' => 'dungeon', 'extension' => 'arr']);
        $trials = $contentRepository->findBy(['type' => 'trial', 'extension' => 'arr']);
        $raids = $contentRepository->findBy(['type' => 'raid', 'extension' => 'arr']);
          
        /* Checkbox dans le cas où l'utilisateur est connecté, une par type de contenu */
        if(!is_null($user)){
            $sdun = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $dungeons]);
            $stri = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $trials]);
            $srai = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $raids]);
                        
            return $this->render('pages/content/arr.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user, 'sdun'=>$sdun, 'stri'=>$stri, 'srai'=>$srai]);
        } 
        /* Si l'utilisateur n'est pas connecté, ne demande pas d'envoyer les informations sdun, stri et srai */
                return $this->render('pages/content/arr.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user]);
    }

     /**
        * @Route("/hw", name="hw")
        * Même chose qu'ARR, mais pour l'extension Heavensward
        */
        public function hw (UsercontentRepository $usercontentRepository, Request $request, ContentRepository $contentRepository, EntityManagerInterface $em): Response
         {
            $user = $this->security->getUser();
            $dungeons = $contentRepository->findBy(['type' => 'dungeon', 'extension' => 'hw']);
            $trials = $contentRepository->findBy(['type' => 'trial', 'extension' => 'hw']);
            $raids = $contentRepository->findBy(['type' => 'raid', 'extension' => 'hw']);
            
            if(!is_null($user)){
            $sdun = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $dungeons]);
            $stri = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $trials]);
            $srai = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $raids]);
                        
            return $this->render('pages/content/hw.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user, 'sdun'=>$sdun, 'stri'=>$stri, 'srai'=>$srai]);
        } 

                    return $this->render('pages/content/hw.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user]);
        }

     /**
        * @Route("/sb", name="sb")
        * Même chose qu'ARR, mais pour l'extension Stormblood
        */
        public function sb (UsercontentRepository $usercontentRepository, Request $request, ContentRepository $contentRepository, EntityManagerInterface $em): Response
         {
            $user = $this->security->getUser();
            $dungeons = $contentRepository->findBy(['type' => 'dungeon', 'extension' => 'sb']);
            $trials = $contentRepository->findBy(['type' => 'trial', 'extension' => 'sb']);
            $raids = $contentRepository->findBy(['type' => 'raid', 'extension' => 'sb']);
            
            if(!is_null($user)){
            $sdun = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $dungeons]);
            $stri = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $trials]);
            $srai = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $raids]);
                        
            return $this->render('pages/content/sb.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user, 'sdun'=>$sdun, 'stri'=>$stri, 'srai'=>$srai]);
        } 

                    return $this->render('pages/content/sb.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user]);
        }

     /**
        * @Route("/shb", name="shb")
        * Même chose qu'ARR, mais pour l'extension Shadowbringer
        */
        public function shb (UsercontentRepository $usercontentRepository, Request $request, ContentRepository $contentRepository, EntityManagerInterface $em): Response
          {
            $user = $this->security->getUser();
            $dungeons = $contentRepository->findBy(['type' => 'dungeon', 'extension' => 'shb']);
            $trials = $contentRepository->findBy(['type' => 'trial', 'extension' => 'shb']);
            $raids = $contentRepository->findBy(['type' => 'raid', 'extension' => 'shb']);
            
            if(!is_null($user)){
            $sdun = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $dungeons]);
            $stri = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $trials]);
            $srai = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $raids]);
                        
            return $this->render('pages/content/shb.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user, 'sdun'=>$sdun, 'stri'=>$stri, 'srai'=>$srai]);
        } 

                    return $this->render('pages/content/shb.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user]);
        }

     /**
        * @Route("/ew", name="ew")
        * Même chose qu'ARR, mais pour l'extension Endwalker
        */
        public function ew (UsercontentRepository $usercontentRepository, Request $request, ContentRepository $contentRepository, EntityManagerInterface $em): Response
          {
            $user = $this->security->getUser();
            $dungeons = $contentRepository->findBy(['type' => 'dungeon', 'extension' => 'ew']);
            $trials = $contentRepository->findBy(['type' => 'trial', 'extension' => 'ew']);
            $raids = $contentRepository->findBy(['type' => 'raid', 'extension' => 'ew']);
            
            if(!is_null($user)){
            $sdun = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $dungeons]);
            $stri = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $trials]);
            $srai = $usercontentRepository->findBy(['user_id' => $user, 'content_id' => $raids]);
                        
            return $this->render('pages/content/ew.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user, 'sdun'=>$sdun, 'stri'=>$stri, 'srai'=>$srai]);
        } 

                    return $this->render('pages/content/ew.html.twig', ['dungeons' => $dungeons, 'trials' => $trials, 'raids' => $raids, 'user' => $user]);
        }
    
     /**
     * @Route("/admin/user", name="user")
     * Sur la Page d'Admin, permet d'observer tous les utilisateur enregistrés, n'est accessible que pour quelqu'un qui possède en DB l'information [ROLE_ADMIN]
     */
     public function user(UserRepository $userRepository): Response
     {
         $user = $this->security->getUser();
         $users = $userRepository->findAll();
         return $this->render('pages/admin/user.html.twig', ['users' => $users, 'user' => $user]);
     }
    
        /**
         * @Route("/admin/viewcontent", name="viewcontent")
         * Sur la Page d'Admin, permet d'observer tous les contenus enregistrés, d'en ajouter de nouveau et de modifier ceux existants
         */
        public function viewcontent(ContentRepository $contentRepository): Response
        {
            $user = $this->security->getUser();
            $contents = $contentRepository->findAll();
            return $this->render('pages/admin/view-content.html.twig', ['contents' => $contents, 'user' => $user]);
        }


     /**
          * @Route("/admin/newcontent", name="createcontent")
          * Page de création de nouveaux contenus, accessibles depuis le viewcontent
          */

         public function createContent(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
         {
             $user = $this->security->getUser();
             $users = $userRepository->findAll();
             
             /* Création d'un Form new Content() */
             $content = new Content();
             $form = $this->createForm(ContentType::class, $content);
             $form->handleRequest($request);
             $em->persist($content);
             
             /* Au moment où le contenu est envoyé un DB, un usercontent est également créé pour chaque utilisateur, afin d'ajouter la case False pour chacun d'entre eux, et ainsi afficher ce nouveau contenu sur leur HomePage */
             
             if ($form->isSubmitted() && $form->isValid()) {
                       foreach($users as $users){
                        $usercontent= new Usercontent();               
                        $usercontent->setUserId($users);
                        $usercontent->setContentId($content);
                        $usercontent->setState(0);
                        $em->persist($usercontent);
                }
                
                 $em->flush();
                 return $this->redirectToRoute('viewcontent' , ['user' => $user]);
             }
             return $this->render('pages/admin/create-content.html.twig', ['contentForm' => $form->createView(), 'user' => $user]);
         }


    /**
     * @Route("/admin/editcontent/{id<\d+>}", name="editcontent")
     * Page pour éditer le contenu en fonction de l'id sélectionnée
     */
    public function editContent(int $id, Request $request, ContentRepository $contentRepository, EntityManagerInterface $em): Response
    {
        $user = $this->security->getUser();
        $content = $contentRepository->find($id);
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($content);
            $em->flush();
            return $this->redirectToRoute('viewcontent', ['user'=>$user]);
        }
        return $this->render('pages/admin/create-content.html.twig', ['contentForm' => $form->createView(), 'user' => $user]);

    }
    
     /**
      * @Route("/admin/viewimage", name="viewimage")
      * Sur la Page d'Admin, permet d'observer les images ajoutées
      */
        public function viewimage(ImagesRepository $imagesRepository): Response
        {
            $user = $this->security->getUser();
            $images = $imagesRepository->findAll();
            return $this->render('pages/admin/view-image.html.twig', ['images' => $images, 'user' => $user]);
        }

     

     /**
          * @Route("/admin/newimage", name="createimage")
          * Page pour ajouter de nouvelles images
          */

         public function createimage(Request $request, EntityManagerInterface $em): Response
         {
             $user = $this->security->getUser();
             
             /* Création d'une nouvelles images en utilisant Forms */
             
                 $images = new Images();
                 $form = $this->createForm(ImagesType::class, $images);
                 $form->handleRequest($request);

                 if ($form->isSubmitted() && $form->isValid()) {
    
                     /* Code appris en classe afin d'uploader un fichier 
                      * Récupération d'un objet de type Uploadfile tout en récupérant le champ data du form
                      * Sanitization et récupération du nom originale de l'image
                      * Ajout d'un identifiant unique afin d'éviter les doublons puis on remet son extension
                      * On déplace le nouvel élément dans un dossier choisis et on nomme corectement l'url dans la Db( Ici Location)
                     */
                    $imageFile = $form->get('location')->getData();
                        if ($imageFile) {
                            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                            
                            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                        try {
                            $imageFile->move(
                            $this->getParameter('images_path'),
                            $newFilename
                            );
                    } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }
                    $form->getData()->setLocation($newFilename);
                        
                        }
                        $em->persist($images);
                        $em->flush();
                  
                 return $this->redirectToRoute('viewimage' , ['user' => $user]);
             }
             return $this->render('pages/admin/create-image.html.twig', ['imagesForm' => $form->createView(), 'user' => $user]);
         }


    /**
    * @Route("/admin/deleteimage/{id<\d+>}", name="deleteimage")
    * Suppression de l'image dans la DB
    */
    
    public function deleteimage(int $id, Request $request, ImagesRepository $imagesRepository, EntityManagerInterface $em): Response
        {
            $user = $this->security->getUser();
            $images = $imagesRepository->find($id);
            if (!$content) {
                throw $this->createNotFoundException('No content found for id '.$id);
            }
            $em->remove($content);
            $em->flush();
            return $this->redirectToRoute('viewimage', ['user'=>$user]);
        }
    
    
     /**
     * @Route("/search", name="search")
     * Page de recherche, utilisant la recherche avec les deux filtres Extension et Types
     */
    public function search(Request $request, ContentRepository $contentRepository) {
        
        $user = $this->security->getUser();
        $search = new Search();
        $form = $this->createForm(SearchCompleteType::class, $search);
        $form->handleRequest($request);
        $result = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $contentRepository->findBySearch($search);
        }
        return $this->render('pages/search.html.twig', ['searchCompleteForm' => $form->createView(),'contents' => $result, 'user'=>$user]);
    }
    
    
}