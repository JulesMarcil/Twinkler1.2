<?php

namespace Tk\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tk\UserBundle\Entity\User;

class FriendsController extends Controller
{
    public function inviteFriendsAction()
    {    
        return $this->render('TkUserBundle:Friends:inviteFriends.html.twig');  
    }

    public function inviteFacebookAction()
    {    
        $friends = $this->getFacebookFriendsAction();

        return $this->render('TkUserBundle:Friends:inviteFacebook.html.twig', array(
          'twinkler_facebook_friends' => $friends[0],
        ));  
    }

    private function getFacebookFriendsAction()
    {
        // Get Facebook friends
        $user = $this->get('security.context')->getToken()->getUser();
        $fbk = $this->get('fos_facebook.api');
        $facebook_friends_extract = $fbk->api('/me/friends');
        $facebook_friends = $facebook_friends_extract['data'];

        // Split Facebook friends between Twinkler users and not
        $twinkler_facebook = array();
        $other_facebook_friends = array();

        $em = $this->getDoctrine()->getEntityManager();
        foreach($facebook_friends as $facebook_friend){
            $id = $facebook_friend['id'];
            $query = $em->createQuery(
                        'SELECT u FROM TkUserBundle:User u WHERE u.facebookId = :id')
                      ->setParameter('id', $id);
            $u = $query->getResult();
            if($u){
                $twinkler_facebook[] = $facebook_friend;
            }else{
                $other_facebook_friends[] = $facebook_friend;
            }
        }

        // create an array with friends ids
        $friends_id = array();
        foreach($user->getFriends() as $friend){
            $friends_id[] = $friend->getFacebookId();
        }

        // remove facebook friends already in twinkler friends
        $twinkler_facebook_friends = array();
        foreach($twinkler_facebook as $tf){
            if(!in_array($tf['id'], $friends_id)){
                $twinkler_facebook_friends[] = $tf;
            }
        }
        return array($twinkler_facebook_friends, $other_facebook_friends);
    }

    public function inviteFacebookFormAction()
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('message', 'textarea')
            ->getForm();

            if ($request->isMethod('POST')) {
                $form->bind($request);

                // data is an array with "name", "email", and "message" keys
                $data = $form->getData();

                return $this->redirect($this->generateUrl('tk_group_add_members'));
            }

        return $this->render('TkUserBundle:Friends:inviteFacebookForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}