<?php
include 'Class/Model.php';

class Api extends Model
{
    public function __construct()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $rawBody = file_get_contents('php://input');
            $data = json_decode($rawBody, true);
            $urlParams = $_GET;

            if (!$data || !isset($urlParams["userId"]) || !isset($urlParams["productId"])) {
                http_response_code(400);
                echo json_encode(['error' => 'JSON inválido, verifique se foi informado o produtoId e o userId']);
                return;
            }

            if ($data["action"] === "removeProduct") {
                $operation = $this->decreaseProductAmount($urlParams["userId"], $urlParams["productId"]);

                if ($operation === "produtoInvalido") {
                    http_response_code(404);
                    echo json_encode(["error" => "O produto informado não está mais em uso ou o usuário não tem acesso à este produto"]);

                    return null;
                } else if ($operation === "produtoSemEstoque") {
                    http_response_code(409);
                    echo json_encode(["error" => "O produto informado não possui estoque disponível para esta operação"]);
                    return null;
                }

                http_response_code(200);
                echo json_encode(["newAmount" => $operation]);
            }

            if ($data["action"] === "addProduct") {
                $operation = $this->increaseProductAmount($urlParams["userId"], $urlParams["productId"]);

                if ($operation === "produtoInvalido") {
                    http_response_code(404);
                    echo json_encode(["error" => "O produto informado não está mais em uso ou o usuário não tem acesso à este produto"]);

                    return null;
                }


                http_response_code(200);
                echo json_encode(["newAmount" => $operation]);
            }

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $urlParams = $_GET;

            if (!isset($urlParams["userId"])) {
                http_response_code(400);
                echo json_encode(['error' => 'Faltam parâmetros na URL, verifique se o userId foi informado']);
                return;
            }

            $productData = $this->getProducts($urlParams["userId"]);

            if ($productData === "noProducts") {
                http_response_code(404);
                echo json_encode(['error' => 'Não foram encontrados produtos para este usuário']);
                return;
            }

            echo json_encode($productData);
        }
    }

    protected  function decreaseProductAmount($userId, $productId)
    {
        $productInfo = $this->getSpecificProductInfo($userId, $productId);

        if (!$productInfo) {
            return "produtoInvalido";
        }

        if ($productInfo[0]["quantidade"] < 1) {
            return "produtoSemEstoque";
        }

        $newAmount = $productInfo[0]["quantidade"] - 1;

        $changeAmount = $this->updateAmount($productId, $newAmount);

        return $newAmount;
    }

    protected function increaseProductAmount($userId, $productId)
    {
        $productInfo = $this->getSpecificProductInfo($userId, $productId);

        if (!$productInfo) {
            return "produtoInvalido";
        }

        $newAmount = $productInfo[0]["quantidade"] + 1;

        $changeAmount = $this->updateAmount($productId, $newAmount);

        return $newAmount;
    }

    protected function getProducts($userId)
    {
        $productsInfo = $this->getProductUserInfo($userId);

        if (!$productsInfo) {
            return "noProducts";
        }

        return $productsInfo;
    }
}

$Api = new Api();
