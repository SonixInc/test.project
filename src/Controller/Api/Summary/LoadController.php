<?php


namespace App\Controller\Api\Summary;


use App\Entity\Summary;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LoadController
 *
 * @package App\Controller\Api\Summary
 */
class LoadController extends AbstractController
{
    /**
     * Load summary list
     *
     * @Route("api/summary/load", name="api.summary.load")
     *
     * @param Request            $request   Http request
     * @param PaginatorInterface $paginator Knp paginator
     *
     * @return Response
     */
    public function load(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var Summary[] $summaries */
        $summaries = $paginator->paginate(
            $this->getDoctrine()->getRepository(Summary::class)->findAll(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('summary/_index_table.html.twig', [
            'summaries' => $summaries
        ]);
    }
}