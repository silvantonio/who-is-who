<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use GraphAware\Neo4j\Client\ClientBuilder;

class WebsiteController extends FOSRestController
{
    public function websiteAction(Request $request)
    {
        $client = ClientBuilder::create()
            ->addConnection(
                $this->container->getParameter('graph_db_connection_protocol'),
                $this->container->getParameter('graph_db_connection_url')
                )
            ->build();

        $format = "MATCH (e:Email)<-[r:HAS_EMAIL]-(w:Website) WITH e, count(r) as rel_cnt WHERE rel_cnt > 1 RETURN e;";
        $query = sprintf($format);

        //dump($query);

        $result = $client->run($query);

        return JsonResponse::create([
                'success' => true,
                'data' => null,
                'error' => null
            ]);
    }

}
