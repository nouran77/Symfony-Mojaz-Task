<?php
/**
 * Created by PhpStorm.
 * User: nourannayel
 * Date: 5/17/18
 * Time: 3:38 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\ListItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;


class ListController extends Controller
{

    /**
     * @Route("/api/list")
     * @Method("GET")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all Lists",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=ListItem::class))
     *     )
     * )
     * @SWG\Tag(name="Lists")
     * @Security(name="Bearer")
     */

    public function index()
    {

        $lists = $this->getDoctrine()->getRepository('AppBundle:ListItem')->findAll();

        $data = [
            'lists' => $lists
        ];

        return new JsonResponse($data);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @Route("/api/list/{id}")
     * @Method("GET")
     */

    public function getListItems($id)
    {

        $list = $this->getDoctrine()->getRepository('AppBundle:ListItem')->find($id);

        if (!$list) {

            return new JsonResponse("List is not found",Response::HTTP_NOT_FOUND);
        }

        $items = $list->getItems();   // Bug

        $response = [
            'message' => 'Items of List ID'.$id,
            'items' => $items
        ];

        return new JsonResponse($response ,Response::HTTP_OK);

    }


    /**
     * @Route("/api/list/create")
     * @Method("POST")
     */
    public function postList(Request $request)
    {

        $list = new ListItem();

        $validator = $this->get('validator');

        $errors = $validator->validate($list);

        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $title = $request->get('title');

        $list->setTitle($title);

        $em = $this->getDoctrine()->getManager();
        $em->persist($list);
        $em->flush();

        return new JsonResponse('List is Created',Response::HTTP_OK);
    }

    /**
     * @Route("/api/list/{id}/edit")
     * @Method("PUT")
     */
    public function updateList(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('AppBundle:ListItem')->find($id);

        if (!$list){
            return new JsonResponse("List is not found in the Database",Response::HTTP_NOT_FOUND);
        }

        $title = $request->get('list');

        $list->setTitle($title);

        $em->flush();

        return new JsonResponse("List Updated Successfully", Response::HTTP_OK);

    }

    /**
     * @param $id
     * @Route("/api/list/{id}/delete")
     * @Method("Delete")
     */
    public function deleteList($id)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('AppBundle:ListItem')->find($id);

        if (!$list){
            return new JsonResponse("List is not Found",Response::HTTP_NOT_FOUND);
        }
        $em->remove($list);

        $em->flush();

        return new JsonResponse("List ID ".$id."is Deleted",Response::HTTP_OK);
    }
}