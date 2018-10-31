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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->sendEmail('raphael.eros.bonini@gmail.com', $product);

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
            ->setBody(\sprintf('the product %s was correctly created.', $product->getName()))
        ;

        $this->get('mailer')->send($message);
    }
}
