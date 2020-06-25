<?php
class model {
    protected $db;
    public $tabela;
    public $lastId;

    public function __construct() {
        try{
            $this->db = new \PDO('mysql:host=localhost;dbname=test;', 'root', '', array(\PDO::ATTR_PERSISTENT => true));
        }catch(PDOException $e){
            echo 'Falha ao conectar no banco de dados: '.$e->getMessage();
            die;
        }
    }

    public function insert(Array $dados){
        foreach ($dados as $inds => $vals) {
            $campos[] = $inds;
            $valores[] = utf8_decode($vals);
        }
        $campos = implode(", ", array_keys($dados));
        $valores = "'".implode("','", array_values($valores))."'";
        $inserir = $this->db->query("INSERT INTO `{$this->_tabela}` ({$campos}) VALUES ({$valores})");
        $this->lastId = $this->db->lastInsertId();
        return $inserir;
    }
    
    public function read($where = null, $limit = null, $offset = null, $orderby = null, $groupby = null){
        $where = ($where != null ? "WHERE {$where}" : "");
        $limit = ($limit != null ? "LIMIT {$limit}" : "");
        $offset = ($offset != null ? "OFFSET {$offset}" : "");
        $orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");
        $groupby = ($groupby != null ? "GROUP BY {$groupby}" : "");
        
        $q = $this->db->query("SELECT * FROM `{$this->_tabela}` {$where} {$groupby} {$orderby} {$limit} {$offset}");
        
        $q->setFetchMode(\PDO::FETCH_ASSOC);
        return $q->fetchAll();
    }
    
    public function update(Array $dados, $where){
        foreach ($dados as $ind =>$val){
            $val_decoder = utf8_decode($val);
            $campos[] = "{$ind} = '{$val_decoder}'";
        }
        $campos = implode(", ", $campos);
        return $this->db->query("UPDATE `{$this->_tabela}` SET {$campos} WHERE {$where}");
    }
    
    public function delete($where){
        $this->db->query("DELETE FROM `{$this->_tabela}` WHERE {$where}");
    }

}