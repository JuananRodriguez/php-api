<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once(__ROOT__."/src/models/product.php");
require_once(__ROOT__."/src/models/auth.php");



function workingPage () {

//    $product = new Product();
//    $product->set('name', 'new name');
//    return json_encode( $product->getData() );


    $usuario = 'eduardo';
    $password = '123456';
    if ($usuario  === 'eduardo' && $password === '123456') {
        echo Auth::SignIn([
            'id' => 1,
            'name' => 'Eduardo'
        ]);
    }

}

function workingPage2(Request $request, Response $response, array $args){
    $token = $request->getAttribute('token');

    echo Auth::Check($token);

}


class ProductCtl {

    public static function getAll($response)
    {
        $sql = "SELECT * FROM products";
        try {
            $stmt = getConnection()->query($sql);
            $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            return json_encode($employees);
        } catch (PDOException $e) {
            return '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    public static function get(Request $request, Response $response, array $args)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $id = $request->getAttribute('id');

        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $db = null;
            $result = $stmt->fetchObject();

            if (!$result)
                return json_encode([]);
            return json_encode([$result]);

        } catch (PDOException $e) {
            return '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    public static function create(Request $request, Response $response, array $args)
    {
        $emp = json_decode($request->getBody());
        $sql = "INSERT INTO products (name, quantity, list_id) VALUES (:name, :quantity, :list_id)";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("name", $emp->name);
            $stmt->bindParam("quantity", $emp->quantity);
            $stmt->bindParam("list_id", $emp->list);
//            $stmt->bindParam("edad", $emp->edad);
            $stmt->execute();
            $emp->id = $db->lastInsertId();
            $db = null;
            return json_encode($emp);
        } catch (PDOException $e) {
            return '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    public static function update(Request $request, Response $response, array $args)
    {
        $emp = json_decode($request->getBody());
        $id = $request->getAttribute('id');
        $sql = "UPDATE products SET name=:name, quantity=:quantity, list_id=:list_id WHERE id=:id";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("name", $emp->name);
            $stmt->bindParam("quantity", $emp->quantity);
            $stmt->bindParam("list_id", $emp->list);

//            $stmt->bindParam("edad", $emp->edad);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $db = null;
            return json_encode($emp);
        } catch (PDOException $e) {
            return '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    public static function delete(Request $request, Response $response, array $args)
    {
        $id = $request->getAttribute('id');
        $sql = "DELETE FROM products WHERE id=:id";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $db = null;
            echo 'product removed';
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }


}


?>
