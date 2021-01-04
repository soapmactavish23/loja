<?php

class Helper {
    public function removerCaracteres($valor){
		$valor = trim($valor);
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",", "", $valor);
		$valor = str_replace("-", "", $valor);
		$valor = str_replace("/", "", $valor);
		$valor = str_replace("(", "", $valor);
		$valor = str_replace(")", "", $valor);
		$valor = str_replace(" ", "", $valor);
		return $valor;
	}
}


?>