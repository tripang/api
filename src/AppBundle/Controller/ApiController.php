<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\User;

/**
* @Route("/api")
*/
class ApiController extends Controller
{
    /**
     * @Route("/user", name="apiUser")
     */
    public function UserAction(Request $request)
    {
        $getId = $request->query->get('id');
        $postId = $request->request->get('id');
        $search = $request->query->get('search');

        if($getId) {
            $res = $this->read($getId);
        } elseif($postId) {
            $nick = $request->request->get('nick');
            $email = $request->request->get('email');
            $res = $this->update($postId, $nick, $email);
        } elseif ($search) {
            $res = $this->search($search);
        } else {
            $res = [];
        }

        // set api type
        if ($request->query->get('type') === 'xml'
            || $request->request->get('type') === 'xml'
        ) {
            $response = new Response(self::getXml($res));
            $response->headers->set('Content-Type', 'text/xml');
        } else {
            $response = new Response(json_encode($res));
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;
    }

    private function read($id) {
        $res = [];
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if ($user) {
            $res = [
                'id' => $user->getId(),
                'nick' => $user->getNick(),
                'login' => $user->getLogin(),
                'email' => $user->getEmail()
            ];
        }
        return $res;
    }

    private function update($id, $nick, $email) {
        $res = [];
        if ($nick && $email) {
            $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findOneById($id);

            if ($user &&
                $user->getNick() !== $nick || $user->getEmail() !== $email
            ) {
                $user->setNick($nick);
                $user->setEmail($email);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
        }
        return $res;
    }

    private function search($search) {
        $res = [];
        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->createQueryBuilder('u')
            ->where('u.nick = :nick')
            ->orWhere("u.login = :login")
            ->orWhere('u.email = :email')
            ->setParameters([
                'nick'=> $search,
                'login' => $search,
                'email' => $search,
            ])
            ->getQuery()
            ->getResult();
        foreach ($users as $user) {
            $res[] = [
                'nick' => $user->getNick(),
                'login' => $user->getLogin(),
                'email' => $user->getEmail()
            ];
        }
        return $res;
    }

    private static function getXml($array) {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><user></user>");
        // function call to convert array to xml
        self::array_to_xml($array, $xml);
        return $xml->asXML();
    }

    private static function array_to_xml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    self::array_to_xml($value, $subnode);
                } else {
                    $subnode = $xml->addChild("item$key");
                    self::array_to_xml($value, $subnode);
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
