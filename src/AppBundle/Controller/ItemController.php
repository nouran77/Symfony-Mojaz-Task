<?php
/**
 * Created by PhpStorm.
 * User: nourannayel
 * Date: 5/19/18
 * Time: 3:19 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemController extends Controller
{

    /**
     * @param Request $request
     * @param $list_id
     * @Route("/api/list/{id}/item")
     * @Method("POST")
     */
    public function addItem(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('AppBundle:ListItem')->find($id);

        if (!$list){
            return new JsonResponse(['message' => 'List is not Found in the Database'], Response::HTTP_NOT_FOUND);
        }

        $item = new Item();

        $title = $request->get('title');

        $item->setTitle($title);

        $item->setList($list);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($item);

        $entityManager->flush();

        return new JsonResponse("Item is Created", Response::HTTP_OK);

    }


    /**
     * @param Request $request
     * @param $list_id
     * @param $id
     * @return JsonResponse
     * @Route("/api/list/{list_id}/item/{id}/edit")
     * @Method("PUT")
     */
    public function updateItem(Request $request, $list_id, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('AppBundle:ListItem')->find($list_id);

        $entityManger = $this->getDoctrine()->getManager();

        $item = $entityManger->getRepository('AppBundle:Item')->find($id);

        if (!$list){
            return new JsonResponse(['message' => 'List is not Found in the Database'], Response::HTTP_NOT_FOUND);
        }
        elseif (!$list->getItems()){ // Bug
            return new JsonResponse(['message' => 'Item is not Found in This List'], Response::HTTP_NOT_FOUND);
        }

        $title = $request->get('title');

        $item->setTitle($title);

        $entityManger->flush();

        return new JsonResponse("Item is Updated Successfully",Response::HTTP_OK);

    }


    /**
     * @Route("/api/list/{list_id}/item/{id}/delete")
     * @Method("DELETE")
     */

    public function deleteItemInList($list_id,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('AppBundle:ListItem')->find($list_id);

        $entityManger = $this->getDoctrine()->getManager();

        $item = $entityManger->getRepository('AppBundle:Item')->find($id);

        if (!$list){
            return new JsonResponse("List is not Found",Response::HTTP_NOT_FOUND);
        }
        elseif (!$item){
            return new JsonResponse("Item is not Found",Response::HTTP_NOT_FOUND);
        }

        $em->remove($item);

        $em->flush();

        return new JsonResponse("Item ID  ".$id."is Deleted",Response::HTTP_OK);
    }


}