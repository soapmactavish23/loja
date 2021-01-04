<?php

class Pedido extends ConnectionDB {

	public function listar () {
		$sql = "SELECT * FROM pedido";
		return $this->read($sql);
	}

	public function salvar(){
		$this->usuario_id = $_REQUEST['usuario_id'];
		$this->produto_id = $_REQUEST['produto_id'];
		return $this->create();
	}

}
?>