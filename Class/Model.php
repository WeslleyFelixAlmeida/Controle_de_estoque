<?php
class Model
{
    private $dataBaseHost = "localhost";
    private $dataBaseUser = "root";
    private $dataBasePass = "";
    private $dataBaseName = "SH_database";

    protected function createAccount($user, $email, $password)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $passwordCryptografied = $this->passwordCryptography($password);

        $SQL_comand = "INSERT INTO usuarios VALUES(DEFAULT, '$user','$email','$passwordCryptografied', '', '', 'Ativa')";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function testExitence($email)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT * FROM usuarios where email = '$email'";

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }

        if ($values == null || $values == "") {
            return false;
        } else {
            return true;
        }
    }

    protected function passwordCryptography($senha)
    {
        $opcoes = [
            'cost' => 9,
        ];

        try {
            $hashSenha = password_hash("$senha", PASSWORD_BCRYPT, $opcoes);
            return $hashSenha;
        } catch (Exception $e) {
        }
    }

    protected function testHash($senha, $hash)
    {
        try {
            return password_verify($senha, $hash);
        } catch (Exception $e) {
        }
    }

    protected function loginRequest($email, $password)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT * FROM usuarios WHERE email = '$email'";

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }

        if (!$values) {
            return false;
        } 

        else {
            $testPassword = $this->testHash($password, $values[0]['senha']);

            if ($testPassword) {
                return true;
            } 
            else {
                return false;
            }
        }
    }

    protected function getUserInfo($email)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT * FROM usuarios WHERE email = '$email'";

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }

        return $values;
    }

    
    protected function getUserInfoByID($userId)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT * FROM usuarios WHERE id = $userId";

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }

        return $values;
    }

    protected function addProductOnDB($img, $nome, $preco, $descricao, $quantidade, $idUsuario, $imgType)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "INSERT INTO produtos VALUES(DEFAULT, '$img', '$nome', $preco, '$descricao', $quantidade, $idUsuario, '$imgType', NOW(), 'Disponivel');";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function makeRegister($lastAmount, $currentyAmount, $lastDesc, $currentyDesc, $lastSituation, $currentySituation,$lastPrice, $currentyPrice, $productId, $lastImg, $currentyImg, $lastName, $currentyName, $procedure){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "INSERT INTO alteracoes VALUES (DEFAULT, NOW(), $lastAmount, $currentyAmount, '$lastDesc', '$currentyDesc', '$lastSituation','$currentySituation', $lastPrice, $currentyPrice, $productId, '$lastImg', '$currentyImg', '$lastName','$currentyName', '$procedure');";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function getLastAddProductId($idUsuario){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT max(id) as id FROM produtos WHERE idUsuario = $idUsuario";      
        
        $values = array();

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }

        return $values;
    }

    protected function getProductUserInfo($idUsuario)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT id, img, nome, preco, descricao, quantidade, tipoImagem, dataAdicao, situacaoAtual FROM produtos WHERE idUsuario = '$idUsuario'";

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            if($lines['situacaoAtual'] !== 'Deletado'){
                $values[] = $lines;
            }
        }

        return $values;
    }

    protected function getSpecificProductInfo($idUsuario, $idProduto)
    {
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT * FROM produtos WHERE idUsuario = '$idUsuario' and id = $idProduto";

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }

        if($values[0]['situacaoAtual'] === 'Deletado'){
            $values = '';
            $values = array();
        }

        return $values;
    }

    protected function getLastModifyDate($idProduto){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "SELECT max(dataModificacao) AS ultimaModificacao FROM alteracoes WHERE idProduto = $idProduto;";

        $result = mysqli_query($conectionString, $SQL_comand);

        $values = array();

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }
        
        return $values;
    }

    protected function getLastMonthAmount($idProduto){
        $month = date('m');
        if($month == '01'){
            $lastMonth = '12';
        }

        else{
            $lastMonth = $month - 1;
        }

        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );  

        $SQL_comand = "SELECT QNTD_atual, QNTD_antiga FROM alteracoes WHERE month(dataModificacao) = $lastMonth and idProduto = $idProduto and procedimento = 'retiradaProduto';";

        $result = mysqli_query($conectionString, $SQL_comand);
        $values = array();
        $totalAmountUpdated = 0;

        while ($lines = mysqli_fetch_assoc($result)) {
            $values[] = $lines;
        }
        for ($i = 0; $i < count($values); $i++){
            $totalAmountUpdated += $values[$i]['QNTD_antiga'] - $values[$i]['QNTD_atual'];
        }

        return $totalAmountUpdated;
    }

    protected function updateImage($idProduto, $newImg, $imgType){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand_1 = "UPDATE produtos SET img = '$newImg' WHERE id = $idProduto;";
        $SQL_comand_2 = "UPDATE produtos SET tipoImagem = '$imgType' WHERE id = $idProduto;";

        mysqli_query($conectionString, $SQL_comand_1);
        mysqli_query($conectionString, $SQL_comand_2);
    }

    protected function updateName($idProduto, $newName){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE produtos SET nome = '$newName' WHERE id = $idProduto;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function updatePrice($idProduto, $newPrice){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE produtos SET preco = '$newPrice' WHERE id = $idProduto;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function updateDesc($idProduto, $newDesc){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE produtos SET descricao = '$newDesc' WHERE id = $idProduto;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function updateAmount($idProduto, $newAmount){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE produtos SET quantidade = '$newAmount' WHERE id = $idProduto;";

        if($newAmount === 0){
            $this->updateCurrentySituation($idProduto, "Indisponivel");
        }

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function updateCurrentySituation($idProduto, $newSituation){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE produtos SET situacaoAtual = '$newSituation' WHERE id = $idProduto;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function deleteProduct($idProduto){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE produtos SET 
        img = '',
        nome = '',
        preco = 0.00,
        descricao = '',
        quantidade = 0,
        tipoImagem = '',
        situacaoAtual = 'Deletado'
        WHERE id = $idProduto;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function updateUsername($idUsuario, $newName){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE usuarios SET usuario = '$newName' WHERE id = $idUsuario;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function updateUserPassword($idUsuario, $newPassword){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE usuarios SET senha = '$newPassword' WHERE id = $idUsuario;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function updateUserPerfilImage($idUsuario, $newImage, $imgType){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE usuarios SET 
        imagemPerfil = '$newImage',
        tipoImagem = '$imgType'
        WHERE id = $idUsuario;";

        mysqli_query($conectionString, $SQL_comand);
    }

    protected function registerUserPerfilChange($email, $lastUsername, $currentyUserName, $lastPassword, $currentyPassword, $lastImage, $currentyImage, $proccess, $userId){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );
        
        $SQL_comand = "INSERT INTO alteracoes_usuarios VALUES(DEFAULT, NOW(), '$email', '$lastUsername', '$currentyUserName', '$lastPassword', '$currentyPassword', '$lastImage', '$currentyImage', '$proccess', $userId)";

        mysqli_query($conectionString, $SQL_comand);       
    }

    protected function deleteUserAccount($userId){
        $conectionString = mysqli_connect(
            $this->dataBaseHost,
            $this->dataBaseUser,
            $this->dataBasePass,
            $this->dataBaseName
        );

        $SQL_comand = "UPDATE usuarios SET 
        senha = '' ,
        usuario	= '',
        email = '',
        senha = '',
        imagemPerfil = '',
        tipoImagem = '',
        situacaoConta = 'Desativada'
        WHERE id = $userId;";

        mysqli_query($conectionString, $SQL_comand); 
    }
}
