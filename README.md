# Symfony_API

## Installation

To get started with test-restApi you need to clone project from git repository.

In your terminal:

```
git clone https://github.com/Wojtec/Symfony_API.git

```

## Run application

Open project in your code editor and install all dependencies

Make sure that you are in correct path `/Symfony_API$` in your terminal and write :

```
composer install
```

```
symfony serve
```

Server should be listening on `http://127.0.0.1:8000/`

To use application you will need some API testing tool for example `Postman` Available on [Postman](https://docs.api.getpostman.com/)

## Endpoints

#Get all machines

Retrieve all machines from ./dataBase.json.

```
POST /api/v1/getAllMachines
```

This endpoint will allow for the user retrieve all machines.

```csharp
  {
            "id": "1",
            "brand": "brand1",
            "model": "model_example",
            "manufacturer": "manufacturer_example",
            "price": 1000,
            "images": [
                {
                    "id": "02",
                    "type": "thumbnail",
                    "url": "https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Fully_automated_schiffli_embroidery_machine_by_Saurer.jpg/1024px-Fully_automated_schiffli_embroidery_machine_by_Saurer.jpg"
                },
                {
                    "id": "93",
                    "type": "lateral_view",
                    "url": "https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Fully_automated_schiffli_embroidery_machine_by_Saurer.jpg/1024px-Fully_automated_schiffli_embroidery_machine_by_Saurer.jpg"
                }
            ]
        },
```

In folder `/src/Controller/machineController.php` you can find controller to this endpoint.

```csharp
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
```

#Get machine by ID:

```
GET /api/v1/getAllMachines/{id}

```

This endpoint will allow for the user recive machine by ID.

In folder `/src/Controller/machineController.php` you can find controller to this endpoint.

```csharp
     /**
     * GET MACHINES BY ID NAME ROUTE
     * @Route("/api/v1/getAllMachines/{id}", name="getById", methods={"GET"})
     *
     */
    public function getById($id) {
        //I get data from DB.
        $data = file_get_contents($this->DATABASE);
        //I decoded data.
        $items = json_decode($data);
        $machines;
        //I check all data to find the matching id.
        foreach($items as $item) {
            if($item->id === $id)
            // if ID is mached I assigns it to variable.
            $machines = $item;

            continue;

         }
            //I return Json response.
         return $this -> json($machines);

    }
```

#Post new machine:

```
POST /api/v1/getAllMachines/add
```

This endpoint will allow for the user create new machine.

```csharp
{
    public $id = '';
    public $brand = '';
    public $model = '';
    public $manufacturer = '';
    public $price = 0;
    public $images = [];

}
```

In folder `/src/Controller/machineController.php` you can find controller to this endpoint.

```csharp
    /**
     * ADD NEW MACHINE ROUTE
     * @Route("/api/v1/getAllMachines/add", name="add_machines", methods={"POST"})
     *
     */
    public function add(Request $request): JsonResponse {
        // I get request from client.
        $data = json_decode($request->getContent(), true);
        // I create uniqe ID.
        $id = uniqid();

        // I create new object machine.
        $machine = new Machine();
        $machine->setId($id);
        $machine->setBrand($data["brand"]);
        $machine->setModel($data["model"]);
        $machine->setManufacturer($data['manufacturer']);
        $machine->setPrice($data["price"]);
        $machine->setImages($data['images']);
        // I get content from DB.
        $jsonData = file_get_contents($this->DATABASE);
        // I decode my data.
        $arrayData = json_decode($jsonData, true);
        // I push new machine to data array.
        array_push($arrayData, $machine);
        // I encode new data.
        $newData = json_encode($arrayData);
        // I store new data in ./dataBase.json
        file_put_contents($this->DATABASE, $newData);
        // I make validation if all filds are not empty.
        if (empty($data["brand"]) || empty($data["model"]) || empty($data['manufacturer']) || empty($data['manufacturer']) || empty($data['price']) || empty($data['images'])) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        // I return json response.
        return new JsonResponse(['status' => 'Customer created!', $machine], JsonResponse::HTTP_CREATED);
    }


```

#Update machine:

```
PATCH /api/v1/getAllMachines/{id}
```

This endpoint will allow for the user update data in database.

In folder `/src/Controller/machineController.php` you can find controller to this endpoint.

```csharp
     /**
     * UPDATE MACHINE ROUTE
     * @Route("/api/v1/getAllMachines/{id}", name="update_machines", methods={"PATCH"})
     *
     */
    public function update($id, Request $request): JsonResponse
    {
        //Get request data.
        $data = json_decode($request->getContent(), true);
        //Get data from DB.
        $dataJson = file_get_contents($this->DATABASE);
        //Decode data from DB.
        $items = json_decode($dataJson, true);
        //Loop data from database and find matching element by ID.
        foreach( $items as &$e ){
            if( $e['id'] == $id ) {
            //If element is matchet replace old data with data from request.
              $e['brand'] = $data["brand"];
              $e['model'] = $data["model"];
              $e['manufacturer'] = $data["manufacturer"];
              $e['price'] = $data["price"];
              $e['images'] = $data['images'];
            }
            continue;
          }
         //Store new element in database.
         file_put_contents($this->DATABASE, json_encode($items));

        //Chect if elements are not empty.
        if ( empty($data["brand"]) || empty($data["model"]) || empty($data['manufacturer']) || empty($data['manufacturer']) || empty($data['price']) || empty($data['images'])) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        //Return json response.
        return new JsonResponse(['status' => 'Customer created!', $items], JsonResponse::HTTP_CREATED);
    }
```

#Filter machines by brand name.

Get data by brand name.

```
Get /api/v1/brand/{brand}
```

In folder `/src/Controller/machineController.php` you can find controller to this endpoint.

```csharp
    /**
     * GET MACHINES BY BRAND NAME ROUTE
     * @Route("/api/v1/brand/{brand}", name="brand", methods={"GET"})
     *
     */
    public function getByBrand($brand) {
        //Get data from DB.
        $data = file_get_contents($this->DATABASE);
        //Decode data.
        $items = json_decode($data);
        $machines = array();
        //Loop data to find matching element.
        foreach($items as $item) {
            //If elements is matched push to array.
            if($item->brand === $brand)

            $machines[] = $item;

            continue;

         }
        //Return json response.
         return $this -> json($machines);
    }

```

#Filter machines by price:

Get machine's by price form, to.

```
Get /api/v1/price/{from}/{to}
```

In folder `/src/Controller/machineController.php` you can find controller to this endpoint.

```csharp
 /**
     * GET MACHINES BY PRICE ROUTE
     * @Route("/api/v1/price/{from}/{to}", name="price", methods={"GET"})
     *
     */
    public function getByPrice($from, $to) {
        //Get data from DB.
        $data = file_get_contents('../dataBase.json');
        //Decode data.
        $items = json_decode($data);
        $machines = array();
        //Loop data to find matching element.
        foreach($items as $item) {
        //If elements is matched push to array.
        if($item->price >= $from && $item->price <= $to)

        $machines[] = $item;

        continue;

         }
        //return response json.
         return $this -> json($machines);

    }
}
```
