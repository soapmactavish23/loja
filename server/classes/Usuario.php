<?php

class Usuario extends ConnectionDB {

	public function autenticar () {
		// if ( ! recaptcha() ) throw new Exception('reCAPTCHA invalido!');
		$sql = "SELECT idusuario, nome, permissao, cpf, email, contato, if(md5(email)=senha,true,false) mudasenha
		FROM usuario
		WHERE binary email='".addslashes($_REQUEST['login'])."' and binary senha ='".md5(addslashes($_REQUEST['password']))."' and ativado='S'
		LIMIT 1";

		if ( $rows = $this->read($sql) ) {
			$row = array_shift($rows);
			return array('token' => createJWT ($row, 36000));
		}
	}

	public function obter(){
		$sql = "SELECT * FROM usuario WHERE idusuario = ".$_REQUEST['idusuario'];
		return $this->read($sql);
	}

	public function obterTodos () {
		$_user = validateJWT( @ $_REQUEST['token']);
		$sql = "SELECT * FROM usuario";
		return $this->read($sql);
	}

	public function obterTodosAtivados(){
		$_user = validateJWT(@ $_REQUEST['token']);
		$sql = "SELECT * FROM usuario WHERE ativado = 'S'";
		return $this->read($sql);
	}

	public function obterNomeAtivados(){
		$_user = validateJWT(@ $_REQUEST['token']);
		$sql = "SELECT idusuario, nome FROM usuario WHERE ativado = 'S'";
		return $this->read($sql);
	}

	public function obterNome(){
		$_user = validateJWT(@ $_REQUEST['token']);
		$sql = "SELECT nome FROM usuario WHERE idusuario = ".$_REQUEST['idusuario'];
		return $this->read($sql);
	}

	public function obterCidades(){
		$sql = "SELECT idcidade, nome FROM cidade WHERE estado_id = ".$_REQUEST['estado_id'];
		return $this->read($sql);
	}

	public function obterEstados(){
		$sql = "SELECT idestado, concat(nome, ' - ', sigla) as estado FROM estado";
		return $this->read($sql);
	}

	public function salvar(){
		$_user = validateJWT(@ $_REQUEST['token']);
		$this->idusuario = @ $_REQUEST['idusuario'];
		$this->nome = addslashes($_REQUEST['nome']);
		$this->email = addslashes($_REQUEST['email']);
		
		require_once('Helper.php');
		$_helper = new Helper();

		$this->contato = $_helper->removerCaracteres(addslashes(@ $_REQUEST['contato']));
		$this->cpf = $_helper->removerCaracteres(addslashes(@ $_REQUEST['cpf']));
		$this->dt_nascimento = @ $_REQUEST['dt_nascimento'];
		$this->estado_id = @ $_REQUEST['estado_id'];
		$this->cidade_id = @ $_REQUEST['cidade_id'];
		$this->cep = $cep;
		$this->bairro = addslashes(@ $_REQUEST['bairro']);
		$this->logradouro = addslashes(@ $_REQUEST['logradouro']);
		$this->permissao = implode(',', @ $_REQUEST['permissao']);

		if ( @ $_REQUEST['ativado'] ) $this->ativado = 'S';
		else $this->ativado = 'N';

		if ( $this->idusuario ) {
			$this->dt_update = date('Y-m-d H:i:s');
			$this->update();
		} else {
			$this->senha = md5($this->email);
			$this->idusuario = $this->create();
		}
		
		return array ( 'idusuario' => $this->idusuario );
	}

	public function cadastrar(){
		require_once('Helper.php');
		$_helper = new Helper();
		$cpf = $_helper->removerCaracteres(addslashes(@ $_REQUEST['cpf']));
		$contato = $_helper->removerCaracteres(addslashes(@ $_REQUEST['contato']));
		$cep = $_helper->removerCaracteres(addslashes(@ $_REQUEST['cep']));

		$this->nome = addslashes($_REQUEST['nome']);
		$this->email = addslashes($_REQUEST['email']);
		$this->contato = $cpf;
		$this->cpf = $contato;
		$this->dt_nascimento = @ $_REQUEST['dt_nascimento'];
		$this->estado_id = @ $_REQUEST['estado_id'];
		$this->cidade_id = @ $_REQUEST['cidade_id'];
		$this->cep = $cep;
		$this->bairro = addslashes(@ $_REQUEST['bairro']);
		$this->logradouro = addslashes(@ $_REQUEST['logradouro']);
		$this->numero = addslashes(@ $_REQUEST['numero']);
		$this->senha = md5(addslashes(@ $_REQUEST['senha']));
		$this->permissao = "painel-controle,usuario";

		return $this->create();

	}

	public function atualizarPerfil(){
		$_user = validateJWT( @ $_REQUEST['token']);
		$this->idusuario = @ $_REQUEST['idusuario'];
		$this->nome = addslashes(@ $_REQUEST['nome']);
		$this->cpf = addslashes(@ $_REQUEST['cpf']);
		$this->email = addslashes(@ $_REQUEST['email']);
		$this->contato = addslashes(@ $_REQUEST['contato']);
		return $this->update();
	}

	public function renovarSenha() {
		$_user = validateJWT( @ $_REQUEST['token']);

		if ( ! @ $_REQUEST['idusuario'] || ! @ $_REQUEST['email'] ) throw new Exception("parametros inexistentes");

		$this->idusuario = $_REQUEST['idusuario'];
		$this->senha = md5( addslashes($_REQUEST['email']) );
		return $this->update();
	}

	public function excluir() {
		$_user = validateJWT( @ $_REQUEST['token']);
		if ( ! @ $_REQUEST['idusuario'] ) throw new Exception("parametro inexistente");
		$this->idusuario = $_REQUEST['idusuario'];
		return $this->delete();
	}

	public function mudarSenha() {
		$_user = validateJWT( @ $_REQUEST['token']);
		
		$sql = "SELECT idusuario, nome, permissao
		FROM usuario
		WHERE binary idusuario='".$_user->idusuario."' and binary senha='".md5( addslashes($_REQUEST['senha']) )."' 
		LIMIT 1";
		$rows = $this->read($sql);
		$row = array_shift($rows);

		$this->idusuario = $_user->idusuario;
		$this->senha = md5( addslashes ($_REQUEST['novasenha']) );
		$this->update();

		$row['mudasenha'] = false;
		return array('token' => createJWT ($row, 36000));
	}
}
?>