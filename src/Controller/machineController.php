<?php

namespace App\Controller;

use App\Entity\Machine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class machineController extends AbstractController
{

    public $DATABASE = '../dataBase.json';

    /**
     * INDEX
     * @Route("/", name="index")
     * 
     */
    public function index() {

        return new Response ("<div><h1>Hello</h1></div>");
     }

     /**
     * UPDATE MACHINE ROUTE
     * @Route("/api/v1/getAllMachines/{id}", name="update_machines", methods={"PUT"})
     * 
     */
    public function update($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dataJson = file_get_contents($this->DATABASE);
        $items = json_decode($dataJson, true);
    
        foreach( $items as &$e ){
            if( $e['id'] == $id ) {
              $e['brand'] = $data["brand"];
              $e['model'] = $data["model"];
              $e['manufacturer'] = $data["manufacturer"];
              $e['price'] = $data["price"];
              $e['images'] = $data['images'];
            }
            continue;
          }

         file_put_contents($this->DATABASE, json_encode($items));


        if (empty($data["id"]) || empty($data["brand"]) || empty($data["model"]) || empty($data['manufacturer']) || empty($data['manufacturer']) || empty($data['price']) || empty($data['images'])) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }


        return new JsonResponse(['status' => 'Customer created!', $items], JsonResponse::HTTP_CREATED);
    }



    /**
     * ADD NEW MACHINE ROUTE
     * @Route("/api/v1/getAllMachines/add", name="add_machines", methods={"POST"})
     * 
     */
    public function add(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $machine = new Machine();

        $machine->setId($data["id"]);
        $machine->setBrand($data["brand"]);
        $machine->setModel($data["model"]);
        $machine->setManufacturer($data['manufacturer']);
        $machine->setPrice($data["price"]);
        $machine->setImages($data['images']);

        $jsonData = ($this->DATABASE);
        $arrayData = json_decode($jsonData);
        array_push($arrayData, $machine);
        $newData = json_encode($arrayData);
        file_put_contents($this->DATABASE, $newData);

        if (empty($data["id"]) || empty($data["brand"]) || empty($data["model"]) || empty($data['manufacturer']) || empty($data['manufacturer']) || empty($data['price']) || empty($data['images'])) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        return new JsonResponse(['status' => 'Customer created!', $machine], JsonResponse::HTTP_CREATED);
    }

    /**
     * GET ALL MACHINES ROUTE
     * @Route("/api/v1/getAllMachines", name="machines", methods={"GET"})
     * 
     */
    public function getAllMachines() {
       $data = file_get_contents($this->DATABASE);
       $items = json_decode($data);
     
 
       return new JsonResponse($items, JsonResponse::HTTP_CREATED);
    }

     /**
     * GET MACHINES BY ID NAME ROUTE
     * @Route("/api/v1/getAllMachines/{id}", name="getById")
     * 
     */
    public function getById($id) {
        $data = file_get_contents($this->DATABASE);
        $items = json_decode($data);
        $machines = array();

        foreach($items as $item) {
            if($item->id === $id)

            $machines[] = $item;

            continue;

         }

         return $this -> json($machines);

    }

    /**
     * GET MACHINES BY BRAND NAME ROUTE
     * @Route("/api/v1/getAllMachines/brand/{brand}", name="brand") 
     * 
     */
    public function getByBrand($brand) {
        $data = file_get_contents($this->DATABASE);
        $items = json_decode($data);
        $machines = array();

        foreach($items as $item) {
            if($item->brand === $brand)

            $machines[] = $item;

            continue;

         }

         return $this -> json($machines);

    }

    /**
     * GET MACHINES BY PRICE ROUTE
     * @Route("/api/v1/getAllMachines/price/{price}", name="price")
     * 
     */
    public function getByPrice($price) {
        $data = file_get_contents('../dataBase.json');
        $items = json_decode($data);
        $machines = array();

        foreach($items as $item) {
            if($item->price == $price)

            $machines[] = $item;

            continue;

         }

         return $this -> json($machines);

    }
}