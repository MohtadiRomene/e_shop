<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PagesController extends AbstractController
{
    #[Route('/pages', name: 'app_pages')]
    public function index(): Response
    {
        $pages = [
            [
                'title' => 'À Propos de Nous',
                'slug' => 'about',
                'description' => 'Découvrez l\'histoire et les valeurs de Kaira Fashion Store'
            ],
            [
                'title' => 'Conditions Générales',
                'slug' => 'terms',
                'description' => 'Nos conditions générales de vente et d\'utilisation'
            ],
            [
                'title' => 'Politique de Confidentialité',
                'slug' => 'privacy',
                'description' => 'Comment nous protégeons vos données personnelles'
            ],
            [
                'title' => 'Livraison et Retours',
                'slug' => 'shipping',
                'description' => 'Informations sur nos options de livraison et retours'
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'description' => 'Questions fréquemment posées'
            ],
        ];

        return $this->render('pages/index.html.twig', [
            'pages' => $pages,
        ]);
    }

    #[Route('/pages/{slug}', name: 'app_pages_show')]
    public function show(string $slug): Response
    {
        $pagesContent = [
            'about' => [
                'title' => 'À Propos de Nous',
                'content' => '<p>Bienvenue chez <strong>Kaira Fashion Store</strong>, votre destination de prédilection pour la mode élégante et intemporelle.</p>
                <p>Depuis notre création, nous nous engageons à offrir des vêtements, chaussures et accessoires de qualité supérieure qui allient style, confort et durabilité.</p>
                <h3>Notre Mission</h3>
                <p>Notre mission est de rendre la mode accessible à tous, tout en respectant les valeurs éthiques et environnementales. Nous croyons que chacun mérite de se sentir bien dans ses vêtements.</p>
                <h3>Nos Valeurs</h3>
                <ul>
                    <li><strong>Qualité</strong> : Nous sélectionnons uniquement les meilleurs produits</li>
                    <li><strong>Durabilité</strong> : Nous privilégions les matériaux durables et éthiques</li>
                    <li><strong>Service Client</strong> : Votre satisfaction est notre priorité</li>
                    <li><strong>Innovation</strong> : Nous suivons les dernières tendances tout en restant intemporels</li>
                </ul>'
            ],
            'terms' => [
                'title' => 'Conditions Générales de Vente',
                'content' => '<h3>1. Acceptation des Conditions</h3>
                <p>En accédant et en utilisant ce site, vous acceptez d\'être lié par les présentes conditions générales de vente.</p>
                <h3>2. Produits</h3>
                <p>Nous nous efforçons d\'afficher avec précision les couleurs et les images de nos produits. Cependant, les couleurs peuvent varier selon votre écran.</p>
                <h3>3. Prix</h3>
                <p>Tous les prix sont indiqués en euros (€) et incluent la TVA. Nous nous réservons le droit de modifier nos prix à tout moment.</p>
                <h3>4. Paiement</h3>
                <p>Nous acceptons les principales cartes de crédit et les paiements sécurisés via notre plateforme.</p>
                <h3>5. Livraison</h3>
                <p>Les délais de livraison sont indiqués lors de la commande. Nous ne sommes pas responsables des retards causés par les transporteurs.</p>'
            ],
            'privacy' => [
                'title' => 'Politique de Confidentialité',
                'content' => '<h3>1. Collecte des Données</h3>
                <p>Nous collectons les informations que vous nous fournissez lors de la création de compte, de la commande ou de l\'inscription à notre newsletter.</p>
                <h3>2. Utilisation des Données</h3>
                <p>Vos données sont utilisées pour :</p>
                <ul>
                    <li>Traiter vos commandes</li>
                    <li>Améliorer notre service client</li>
                    <li>Vous envoyer des communications marketing (avec votre consentement)</li>
                </ul>
                <h3>3. Protection des Données</h3>
                <p>Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos informations personnelles.</p>
                <h3>4. Vos Droits</h3>
                <p>Vous avez le droit d\'accéder, de modifier ou de supprimer vos données personnelles à tout moment.</p>'
            ],
            'shipping' => [
                'title' => 'Livraison et Retours',
                'content' => '<h3>Options de Livraison</h3>
                <ul>
                    <li><strong>Livraison Standard</strong> : 5-7 jours ouvrables - Gratuite pour les commandes supérieures à 50€</li>
                    <li><strong>Livraison Express</strong> : 2-3 jours ouvrables - 9,90€</li>
                    <li><strong>Livraison Point Relais</strong> : 4-6 jours ouvrables - 4,90€</li>
                </ul>
                <h3>Politique de Retour</h3>
                <p>Vous disposez de <strong>30 jours</strong> à compter de la réception pour retourner un article non porté, non lavé et dans son emballage d\'origine.</p>
                <h3>Processus de Retour</h3>
                <ol>
                    <li>Connectez-vous à votre compte</li>
                    <li>Accédez à vos commandes</li>
                    <li>Sélectionnez l\'article à retourner</li>
                    <li>Imprimez l\'étiquette de retour</li>
                    <li>Envoyez le colis</li>
                </ol>'
            ],
            'faq' => [
                'title' => 'Questions Fréquemment Posées',
                'content' => '<h3>Comment passer une commande ?</h3>
                <p>Ajoutez les articles souhaités à votre panier, puis procédez au paiement. Vous recevrez un email de confirmation.</p>
                <h3>Quels modes de paiement acceptez-vous ?</h3>
                <p>Nous acceptons les cartes bancaires (Visa, Mastercard), PayPal et les virements bancaires.</p>
                <h3>Puis-je modifier ma commande ?</h3>
                <p>Vous pouvez modifier votre commande avant qu\'elle ne soit expédiée. Contactez notre service client.</p>
                <h3>Comment suivre ma commande ?</h3>
                <p>Un email avec le numéro de suivi vous sera envoyé dès l\'expédition de votre commande.</p>
                <h3>Que faire si un article est défectueux ?</h3>
                <p>Contactez-nous immédiatement. Nous vous proposerons un échange ou un remboursement.</p>'
            ],
        ];

        if (!isset($pagesContent[$slug])) {
            throw $this->createNotFoundException('Page non trouvée');
        }

        return $this->render('pages/show.html.twig', [
            'page' => $pagesContent[$slug],
        ]);
    }
}
