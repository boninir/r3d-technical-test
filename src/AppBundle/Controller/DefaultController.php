<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $email = $form->get('email')->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->sendEmail($email, $product);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Votre produit à bien été enregistré, un mail récapitulatif vous a été envoyé.')
            ;

            return $this->redirect($this->generateUrl('homepage'));

        }

        return $this->render(
            'default/index.html.twig',
            array('form' => $form->createView())
        );
    }

    private function sendEmail(string $email, Product $product)
    {
        $message = (new Swift_Message('New Product Created'))
            ->setFrom('no-reply@example.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'emails/product.html.twig',
                    array('product' => $product)
                ),
                'text/html'
            )
        ;

        try {
            $this->get('mailer')->send($message);
        } catch (\Exception $e) {
            throw new \Swift_SwiftException(\sprintf('An error occurred during sending mail : %s', $e->getMessage()));
        }

    }
}
