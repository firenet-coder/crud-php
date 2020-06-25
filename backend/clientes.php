<?php
function utf8_converter($array) {
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });
    return $array;
}


require 'model.php';
$model = new model();

$metodo = '';
if(!empty($_GET)){
    $metodo = $_GET['_method'];
}

if($metodo=='POST'){
    if(!empty($_POST)){
        //Create
        $model->_tabela = "clientes";
        $model->insert(array(
            "nome" => $_POST['nome'],
            "endereco" => $_POST['endereco'],
            "endereco_numero" => $_POST['endereco_numero'],
            "telefone" => $_POST['telefone'],
            "credito" => $_POST['credito']
        ));
        $id_cliente = $model->lastId;
        $resultado = 'cliente com id: '.$id_cliente.' registrado com sucesso';

        $array_geral = array(
            'resultado' => $resultado,
        );
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(utf8_converter($array_geral));
        
    }

}else if($metodo=='GET'){
    //Read
    $where = '';
    $id_cliente = '';
    if(!empty($_GET['id'])){
        $id_cliente = $_GET['id'];
        $where = "id='".$id_cliente."'";
    }
    $model->_tabela = "clientes";
    $abrir_cliente = $model->read(
        $where, //where
        '', //limite
        '', //offset
        '', //orderby
        '' //groupby
    );
    $resultado = 'cliente com id: '.$id_cliente.' localizado com sucesso';
    
    $array_geral = array(
        'resultado' => $resultado,
        'abrir_cliente' => $abrir_cliente,
    );
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(utf8_converter($array_geral));

}else if($metodo=='PUT'){
    if(!empty($_GET['id'])){
        //Update
        $where = "id='1'";
        $model->_tabela = "clientes";
        $model->update(array(
            "nome" => "Juliano Santos1",
            "endereco" => "Rua Fulando de Tal",
            "endereco_numero" => "100",
            "telefone" => "(XX) XXXXX-XXXX",
            "credito" => 150.85
        ), $where);
        $id_cliente = $model->lastId;
        $resultado = 'cliente com id: '.$id_cliente.' editado com sucesso';
        
        $array_geral = array(
            'resultado' => $resultado,
        );
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(utf8_converter($array_geral));
    }
    
}else if($metodo=='DELETE'){
    if(!empty($_GET['id'])){
        $model->delete($where);
        $resultado = 'cliente com id: '.$_GET['id'].' deletado com sucesso';
        
        $array_geral = array(
            'resultado' => $resultado,
        );
    }
}
