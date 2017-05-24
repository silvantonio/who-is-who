<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use GraphAware\Neo4j\Client\ClientBuilder;

class WebsiteController extends FOSRestController
{
    public function createAction(Request $request)
    {
        $data = $this->get('serializer')->decode($request->getContent(), 'json');

        $client = ClientBuilder::create()
            ->addConnection(
                $this->container->getParameter('graph_db_connection_protocol'),
                $this->container->getParameter('graph_db_connection_url')
            )
            ->build();

        //dump($data);

        foreach ($data as $row) {
            $url = trim($row['url']);
            $websiteID = 'w_'.preg_replace('/[^A-Za-z0-9]/', '_', $url);
            $emails = explode(',', $row['email']);
            foreach ($emails as $email) {
                $email = trim($email);
                $emailID = 'e_'.preg_replace('/[^A-Za-z0-9]/', '_', $email);

                // check if website exists
                $format = "MATCH (website:Website {url:'%s'}) RETURN website";
                $query = sprintf($format, $url);
                //dump($query);
                $result = $client->run($query);
                $size = $result->size();
                //dump($size);

                // create website
                if ($size <= 0) {
                    $format = "CREATE (%s:Website {url:'%s'})";
                    $query = sprintf($format, $websiteID, $url);
                    //dump($query);
                    $result = $client->run($query);
                }

                // check if website exists
                $format = "MATCH (email:Email {email:'%s'}) RETURN email";
                $query = sprintf($format, $email);
                //dump($query);
                $result = $client->run($query);
                $size = $result->size();
                //dump($size);

                // create email
                if ($size <= 0) {
                    // create email
                    $format = "CREATE (%s:Email {email:'%s'})";
                    $query = sprintf($format, $emailID, $email);
                    //dump($query);
                    $result = $client->run($query);
                }

                // check if website exists
                $format = "MATCH (w:Website {url:'%s'})-[r:HAS_EMAIL]-(e:Email {email:'%s'}) RETURN r";
                $query = sprintf($format, $url, $email);
                //dump($query);
                $result = $client->run($query);
                $size = $result->size();
                //dump($size);

                // create relation
                if ($size <= 0) {
                    $format = "MATCH (w:Website {url:'%s'}), (e:Email {email:'%s'}) CREATE (w)-[:HAS_EMAIL]->(e)";
                    $query = sprintf($format, $url, $email);
                    //dump($query);
                    $result = $client->run($query);
                }
            }
        }

        return JsonResponse::create([
                'success' => true,
                'data' => null,
                'error' => null
            ]);
    }

//    public function getAction($id)
//    {
//
//    }
//
//    public function searchAction(Request $request)
//    {
//
//    }
//
//    public function deleteAction($id)
//    {
//MATCH (n) DETACH DELETE n
//    }
//
//    public function updateAction()
//    {
//
//    }
}
