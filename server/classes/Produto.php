<?php

class Produto extends ConnectionDB {

	public function listar () {
		$sql = "SELECT * FROM produto";
		return $this->read($sql);
	}

	public function salvar(){
		$this->id = $_REQUEST['id'];
		$this->nome = addslashes(@ $_REQUEST['nome']);
        $this->descricao = addslashes(@ $_REQUEST['descricao']);
        
        if($this->id){
            $this->dt_update = date('Y-m-d H:i:s');
            $this->update();
        }else{
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