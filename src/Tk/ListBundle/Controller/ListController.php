<?php

namespace Tk\ListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tk\ListBundle\Entity\Lists;
use Tk\ListBundle\Form\ListsType;

class ListController extends Controller
{
    public function indexAction()
    {
        $session = $this->get('session');
    	
        if(!$session->get('list_id')){
            $list = $this->getUser()->getCurrentMember()->getTGroup()->getLists()->first();
        	$session->set('list_id', $list->getId());
        	$session->set('list_name', $list->getName());
        }

        return $this->redirect($this->generateUrl('tk_list_details', array('id' => $session->get('list_id'))));
    }

    public function detailsAction($id)
    {
    	$session = $this->get('session');
    	$list = $this->getDoctrine()->getRepository('TkListBundle:Lists')->find($id);
    	$session->set('list_id', $id);
    	$session->set('list_name', $list->getName());

    	return $this->render('TkListBundle::index.html.twig');
    }

    public function newAction()
    {
        $list = new Lists();
        $list->setGroup($this->getUser()->getCurrentMember()->getTGroup());

        $form = $this->createForm(new ListsType(), $list);

        $request = $this->get('request');

        if ($request->isMethod('POST')) {

            $form->bind($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($list);
                $em->flush();

                return $this->redirect($this->generateUrl('tk_list_homepage'));
            }
        }

        return $this->render('TkListBundle:List:new.html.twig', array(
            'form' => $form->createView(),
            ));        
    }
}