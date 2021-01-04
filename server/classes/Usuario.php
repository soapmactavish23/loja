<?php

class Usuario extends ConnectionDB {

	public function listar () {
		$sql = "SELECT * FROM usuario";
		return $this->read($sql);
	}

	public function salvar(){
		$this->id = @ $_REQUEST['id'];
		$this->nome = addslashes($_REQUEST['nome']);
		$this->email = addslashes($_REQUEST['email']);
		
		require_once('Helper.php');
		$_helper = new Helper();

		$this->contato = $_helper->removerCaracteres(addslashes(@ $_REQUEST['contato']));
		$this->cpf = $_helper->removerCaracteres(addslashes(@ $_REQUEST['cpf']));

		if ( $this->id ) {
			$this->dt_update = date('Y-m-d H:i:s');
			$this->update();
		} else {
			$this->id = $this->create();
		}
		
		return $this->id;
	}

	public function deletar() {
		if ( ! @ $_REQUEST['id'] ) throw new Exception("parametro inexistente");
		$this->id = $_REQUEST['id'];
		return $this->delete();
	}
}
?>