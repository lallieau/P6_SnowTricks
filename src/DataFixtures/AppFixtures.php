<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // USER FIXTURES

        $user = new User();
        $user
            ->setUsername('Lola')
            ->setEmail('lola@gmail.com');
        $password = $this->encoder->encodePassword($user, 'pass_lola');
        $user->setPassword($password);

        $manager->persist($user);


        $user1 = new User();
        $user1
            ->setUsername('Pierre')
            ->setEmail('pierre@gmail.com');
        $password = $this->encoder->encodePassword($user1, 'pass_pierre');
        $user1->setPassword($password);

        $manager->persist($user1);


        $user2 = new User();
        $user2
            ->setUsername('Camille')
            ->setEmail('camille@gmail.com');
        $password = $this->encoder->encodePassword($user2, 'pass_camille');
        $user2->setPassword($password);

        $manager->persist($user2);

        // CATEGORY FIXTURES

        $category1 = new Category();
        $category1
            ->setName('Grab');
        $manager->persist($category1);

        $category2 = new Category();
        $category2
            ->setName('Slide');
        $manager->persist($category2);

        $category3 = new Category();
        $category3
            ->setName('Flip');
        $manager->persist($category3);

        // TRICKS FIXTURES

        $trick1 = new Trick();
        $trick1
            ->setUser($user2)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Mute')
            ->setCategory($category1)
            ->setDescription('La main avant saisit le bord des orteils soit entre les orteils, soit devant le pied avant. [1] Les variations incluent le Mute Stiffy, dans lequel une prise de sourdine est exécutée tout en redressant les deux jambes, ou alternativement, certains snowboarders saisiront le muet et feront pivoter l\'avant de la planche de 90 degrés.');

        $manager->persist($trick1);

        $trick2 = new Trick();
        $trick2
            ->setUser($user1)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Method')
            ->setCategory($category1)
            ->setDescription('Un truc fondamental réalisé en pliant les genoux pour soulever la planche derrière le dos du rider et en saisissant le bord du talon du snowboard avec la main de tête. Les variations de la méthode comprennent: Méthode de puissance, os croisé ou méthode de Palmer; Effectué en saisissant le bord du talon avec la main de tête et en repliant la planche tout en éjectant le pied arrière de telle sorte que la base de la planche soit tournée vers l\'avant. Dérivé du snowboarder Chris Roach de Grass Valley, CA. Parmi les autres riders notables qui ont popularisé cet air, citons les snowboarders Jamie Lynn, Shaun Palmer , Terry Kidwell et les skateurs Steve Caballero et Christian Hosoi.
            Valise : Une méthode dans laquelle les genoux sont pliés de sorte que la main avant puisse saisir le bord des orteils et tenir la planche «comme une valise».');

        $manager->persist($trick2);

        $trick3 = new Trick();
        $trick3
            ->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Frontside grab/indy')
            ->setCategory($category1)
            ->setDescription('Une astuce fondamentale réalisée en saisissant le bord des orteils entre les fixations avec la main arrière. Cette astuce est appelée une prise frontside sur un air droit, ou lors d\'une rotation frontside. Lors de l\'exécution d\'une rotation aérienne arrière ou arrière, cette pince est appelée Indy. Le frontside air a été popularisé par le skateur Tony Alva .');

        $manager->persist($trick3);

        $trick4 = new Trick();
        $trick4
            ->setUser($user2)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Bloody Dracula')
            ->setCategory($category1)
            ->setDescription('Un truc dans lequel le rider attrape la queue de la planche à deux mains. La main arrière attrape la planche comme elle le ferait lors d\'un tail-grab normal mais la main avant atteint aveuglément la planche derrière les riders.');

        $manager->persist($trick4);

        $trick5 = new Trick();
        $trick5
            ->setUser($user2)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Tailslide')
            ->setCategory($category2)
            ->setDescription('Similaire à un boardslide ou à un lipslide, mais seule la queue de la planche est sur la fonction. Des glissades correctes sont effectuées avec la fonction directement sous le pied arrière ou plus loin vers la queue. [1]');

        $manager->persist($trick5);

        $trick6 = new Trick();
        $trick6
            ->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Boardslide')
            ->setCategory($category2)
            ->setDescription('Une glissade effectuée lorsque le pied menant du cavalier passe au-dessus du rail à l\'approche, avec leur snowboard se déplaçant perpendiculairement le long du rail ou d\'un autre obstacle. [1] Lors de l\'exécution d\'un boardlide frontside, le snowboardeur est face à la montée. Lors d\'un boardlide arrière, un snowboardeur fait face à la descente. Ceci est souvent déroutant pour les nouveaux riders qui apprennent le truc, car avec un boardlide frontside vous reculez et avec un boardlide backside vous avancez.');

        $manager->persist($trick6);

        $trick7 = new Trick();
        $trick7
            ->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Noseblunt')
            ->setCategory($category2)
            ->setDescription('Une glissade effectuée là où le pied arrière du coureur passe au-dessus du rail à l\'approche, avec son snowboard se déplaçant perpendiculairement et son pied avant directement au-dessus du rail ou d\'un autre obstacle (comme une glissade nasale). Lors de l\'exécution d\'un noseblunt frontside, le snowboarder est face à la descente. Lors de l\'exécution d\'un noseblunt arrière, le snowboardeur est face à la montée.');

        $manager->persist($trick7);

        $trick8 = new Trick();
        $trick8
            ->setUser($user1)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Frontside Rodeo')
            ->setCategory($category3)
            ->setDescription('Le rodéo avant de base est tous ensemble un 540. Il tombe essentiellement dans une zone grise entre un frontside hors axe 540 et un frontside 180 avec un flip arrière mélangé. Le choix de la saisie et les différents facteurs de ligne et de pop peuvent le rendre plus flipy ou plus d\'un spin hors axe. Le rodéo frontal peut être fait sur les talons ou les orteils et avec un peu plus de rotation sur l\'axe Z peut aller à 720 ou 900. Il est possible de le faire à un 1080 mais, s\'il y a trop de retournement dans la rotation, il peut être difficile de ne pas trop basculer en dépassant 720 et 900. Plus la rotation de l\'axe Z est grande, plus la partie inversée de la rotation doit être tardive. La prise de contrôle sur les rodéos à grande rotation peut conduire à un double bouchon ou à une deuxième rotation de flip dans la vrille, si le cavalier a développé un niveau de confort avec des doubles flips sur le clochard ou un autre environnement de gymnastique.; Rodeo flip; rodéo avant: Une rotation frontale retournée vers l\'avant effectuée sur le bord des orteils. Le plus souvent réalisé avec une rotation de 540 °, mais aussi réalisé en 720 °, 900 °, etc.');

        $manager->persist($trick8);

        $trick9 = new Trick();
        $trick9
            ->setUser($user1)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Frontside Misty')
            ->setCategory($category3)
            ->setDescription('Le Frontside misty finit par ressembler un peu à un rodéo avant au milieu du pli, mais au décollage, le cavalier utilise un type de mouvement plus avant pour démarrer le pli. Le frontside Misty ne peut être fait que sur les orteils et le cavalier se retournera pour tourner le frontside, puis cliquera son épaule arrière vers son pied avant et l\'épaule de tête se relâchera vers le ciel. comme ils se déroulent au décollage. Habituellement, saisissant Indy, le cavalier suit l\'épaule de tête tout au long de la rotation à 540, 720 et même 900.');

        $manager->persist($trick9);

        $trick10 = new Trick();
        $trick10
            ->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Sato flip')
            ->setCategory($category3)
            ->setDescription('Tour de demi-lune réalisé par Rob Kingwill ( Sato est le mot japonais pour le sucre ). C\'est quelque chose comme un McTwist frontal. Le cavalier monte la transition du tuyau comme s\'il faisait un frontside 540 °, saute dans les airs et attrape l\'avant, puis jette la tête, les épaules et les hanches vers le bas.');

        $manager->persist($trick10);

        $manager->flush();
    }
}
