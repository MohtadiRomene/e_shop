<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        // Articles de blog statiques pour l'instant
        // Dans une vraie application, on utiliserait une entité Article avec un repository
        $articles = [
            [
                'id' => 1,
                'title' => 'Les Tendances Mode de l\'Été 2024',
                'excerpt' => 'Découvrez les dernières tendances mode qui vont dominer cet été. Des couleurs vives aux matières légères, explorez les styles qui vous feront briller.',
                'image' => 'post-image1.jpg',
                'date' => new \DateTime('2024-01-15'),
                'author' => 'Kaira Fashion',
                'category' => 'Tendances'
            ],
            [
                'id' => 2,
                'title' => 'Comment Choisir la Taille Parfaite',
                'excerpt' => 'Guide complet pour trouver la taille idéale. Nos conseils d\'experts pour un ajustement parfait et un confort optimal.',
                'image' => 'post-image2.jpg',
                'date' => new \DateTime('2024-01-10'),
                'author' => 'Kaira Fashion',
                'category' => 'Conseils'
            ],
            [
                'id' => 3,
                'title' => 'Entretien et Soin de vos Vêtements',
                'excerpt' => 'Apprenez à prendre soin de vos vêtements pour qu\'ils durent plus longtemps. Techniques de lavage, séchage et stockage.',
                'image' => 'post-image3.jpg',
                'date' => new \DateTime('2024-01-05'),
                'author' => 'Kaira Fashion',
                'category' => 'Conseils'
            ],
            [
                'id' => 4,
                'title' => 'Style Minimaliste : L\'Art de la Simplicité',
                'excerpt' => 'Le minimalisme dans la mode : comment créer des looks élégants avec moins. Découvrez les pièces essentielles de votre garde-robe.',
                'image' => 'post-image4.jpg',
                'date' => new \DateTime('2023-12-28'),
                'author' => 'Kaira Fashion',
                'category' => 'Style'
            ],
            [
                'id' => 5,
                'title' => 'Accessoires qui Font la Différence',
                'excerpt' => 'Les accessoires peuvent transformer n\'importe quelle tenue. Découvrez comment choisir et porter les accessoires parfaits.',
                'image' => 'post-image5.jpg',
                'date' => new \DateTime('2023-12-20'),
                'author' => 'Kaira Fashion',
                'category' => 'Accessoires'
            ],
            [
                'id' => 6,
                'title' => 'Mode Durable : L\'Avenir de la Fashion',
                'excerpt' => 'L\'importance de la mode durable et éthique. Comment faire des choix conscients pour un avenir meilleur.',
                'image' => 'post-image6.jpg',
                'date' => new \DateTime('2023-12-15'),
                'author' => 'Kaira Fashion',
                'category' => 'Durabilité'
            ],
        ];

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/blog/{id}', name: 'app_blog_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        // Dans une vraie application, on récupérerait l'article depuis la base de données
        // Pour l'instant, on simule avec des données statiques
        $article = [
            'id' => $id,
            'title' => 'Les Tendances Mode de l\'Été 2024',
            'content' => '<p>L\'été 2024 apporte avec lui une nouvelle vague de tendances mode qui allient confort, style et durabilité. Cette saison, les designers mettent l\'accent sur des pièces intemporelles qui peuvent être portées année après année.</p>
            <p>Les couleurs vives sont à l\'honneur cette année. Du jaune soleil au bleu océan, en passant par le vert menthe, ces teintes apportent fraîcheur et énergie à votre garde-robe estivale.</p>
            <p>Les matières légères et respirantes comme le lin, le coton bio et la soie sont privilégiées pour leur confort et leur durabilité. Ces textiles permettent de rester au frais tout en restant élégant.</p>
            <p>Les silhouettes fluides et aérées dominent les collections. Les robes longues, les pantalons larges et les blouses amples offrent à la fois style et confort pour les journées chaudes.</p>
            <p>N\'oubliez pas les accessoires ! Les chapeaux à larges bords, les sacs en paille et les sandales élégantes complètent parfaitement votre look estival.</p>',
            'image' => 'post-large-image1.jpg',
            'date' => new \DateTime('2024-01-15'),
            'author' => 'Kaira Fashion',
            'category' => 'Tendances'
        ];

        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }
}
