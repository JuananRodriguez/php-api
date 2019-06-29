<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once(__ROOT__."/src/models/product.php");
require_once(__ROOT__."/src/models/checklist.php");
require_once(__ROOT__."/src/models/auth.php");



class CheckListCtl {

    public static function getAll($response)
    {
        $sql = "
            SELECT lists.id, lists.name, lists.created, lists.updated, COUNT(products.list_id) AS total_products 
            FROM lists LEFT OUTER JOIN products
            ON products.list_id = lists.id 
            GROUP BY lists.id
            ORDER BY lists.updated
        ";
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
        $sql = "
            SELECT lists.id, lists.name, lists.created, lists.updated, COUNT(products.list_id) AS total_products 
            FROM lists LEFT OUTER JOIN products
            ON products.list_id = lists.id
            WHERE lists.id =:id
            GROUP BY lists.id
            ORDER BY lists.updated
        ";        $id = $request->getAttribute('id');

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
        $moment = date("Y-m-d H:i:s");
        $sql = "INSERT INTO lists (name, created, updated) VALUES (:name, :created, :updated)";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("name", $emp->name);
            $stmt->bindParam("created", $moment);
            $stmt->bindParam("updated", $moment);
            $stmt->execute();

            $emp->created = $moment;
            $emp->updated = $moment;
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
        $sql = "UPDATE lists SET name=:name, updated=:updated WHERE id=:id";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("name", $emp->name);
            $stmt->bindParam("updated", time());
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
        $sql = "DELETE FROM lists WHERE id=:id";
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
