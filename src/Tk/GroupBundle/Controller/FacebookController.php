<?php

namespace Tk\GroupBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tk\GroupBundle\Entity\TGroup;
use Tk\GroupBundle\Form\TGroupType;
use Tk\UserBundle\Entity\Member;


class FacebookController extends Controller
{
    public function friendsAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $fbk = $this->get('fos_facebook.api');
        
        $user_friends = $fbk->api('/me/friends');

        return $this->render('TkGroupBundle:Creation:facebook.html.twig', array(
          'friends' => $user_friends['data'],
        ));  
    }

}
