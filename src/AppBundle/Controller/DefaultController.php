<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        // add users by default
        $usersFromBase = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findAll();
        if (!$usersFromBase) {
            $em = $this->getDoctrine()->getManager();

            $newusers = json_decode('[
                {
                    "nick": "ivanov",
                    "login": "ivan",
                    "email": "vasiliy_ivanov@mail.ru"
                },
                {
                    "nick": "ivan",
                    "login": "ivanov",
                    "email": "ivanov@mail.ru"
                },
                {
                    "nick": "petrov",
                    "login": "petr",
                    "email": "petrov@mail.ru"
                },
                {
                    "nick": "sidorov",
                    "login": "sid",
                    "email": "sidorov@mail.ru"
                }
            ]');

            foreach ($newusers as $newUser) {
                $user = new User();
                $user->setNick($newUser->nick);
                $user->setLogin($newUser->login);
                $user->setEmail($newUser->email);
                $em->persist($user);
            }
            $em->flush();
        }

        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('default/index.html.twig', ['users' => $users]);
    }

}
