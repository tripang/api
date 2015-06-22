<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        // add default client
        $clientes = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findAll();
        if ($clientes) {
            $client = $clientes[0];
        } else {
            $em = $this->getDoctrine()->getManager();
            $client = new Client();
            $client->setIsAuthorized(true);
            $em->persist($client);
            $em->flush();
        }

        // add users by default
        $usersFromBase = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();
        if (!$usersFromBase) {
            $em = $this->getDoctrine()->getManager();

            $users = json_decode('[
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

            foreach ($users as $userParam) {
                $user = new User();
                $user->setNick($userParam->nick);
                $user->setLogin($userParam->login);
                $user->setEmail($userParam->email);
                $em->persist($user);
            }
            $em->flush();
        }

        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('default/index.html.twig', [
            'users' => $users,
            'client' => $client->getId()
        ]);
    }

}
