<?php
include 'Model.php';

class ProductInfo extends Model
{
    public $productInfoData;
    public $imgURL;
    public $formatedPrice;
    public $lastDateModifiedData;
    public $lastMonthAmount;
    public function __construct()
    {
        if(isset($_GET['userId']) && isset($_GET['productId'])){
            $this->productInfoData = $this->getSpecificProductInfo($_GET['userId'],$_GET['productId']);

            if(!$this->productInfoData){
                header('Location: perfil.php');
            }

            $unformatedDate =  $this->getLastModifyDate($this->productInfoData[0]['id']);
            
            $day = $unformatedDate[0]['ultimaModificacao'][8]
                  .$unformatedDate[0]['ultimaModificacao'][9];

            $month = $unformatedDate[0]['ultimaModificacao'][5].
                     $unformatedDate[0]['ultimaModificacao'][6];

            $year = $unformatedDate[0]['ultimaModificacao'][0]
                   .$unformatedDate[0]['ultimaModificacao'][1]
                   .$unformatedDate[0]['ultimaModificacao'][2]
                   .$unformatedDate[0]['ultimaModificacao'][3];

            $this->lastDateModifiedData = $day.'/'.$month.'/'.$year;

            $this->lastMonthAmount = $this->getLastMonthAmount($_GET['productId']);
            
            if(!$this->productInfoData){
                header('Location: perfil.php');
            }

            else{
                $this->formatedPrice = str_replace(".", ",", $this->productInfoData[0]['preco']);
    
                $Image = $this->productInfoData[0]['img'];
    
                $this->imgURL = 'data:'.$this->productInfoData[0]['tipoImagem'].';base64,'. $Image;
            }
        }


        else if($_GET['userId'] !== $_SESSION['idUser']){
            header('Location: perfil.php');
        }

        else{
            header('Location: perfil.php');
        }
    }
}
$ProdutoInfo = new ProductInfo();
?>