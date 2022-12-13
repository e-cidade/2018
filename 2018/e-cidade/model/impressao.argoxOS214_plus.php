<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once 'model/impressao.model.php';
final class impressaoOS214_plus extends impressao {

	public function __construct($sIp, $sPorta){

		parent::setIp($sIp);
		parent::setPorta($sPorta);
	}

	/**
	 * Método para escrever o testo em uma linha
	 *
	 * @param string $sTexto
	 */
	public function imprimeLinha($sTexto, $sX, $sY) {

		$sLinha = db_removeAcentuacao($sTexto);
		$sLinha = substr($sLinha,0,23);
		$sLinha = str_pad($sLinha,23,' ',STR_PAD_BOTH);

		$sX = str_pad($sX,4,'0',STR_PAD_LEFT);
		$sY = str_pad($sY,4,'0',STR_PAD_LEFT);

    $sLinha = "1211000{$sY}{$sX} {$sLinha}";
		parent::addComando($sLinha);
	}

	/**
	 * Método para escrever o código de barras
	 *
	 * @param string $sTexto
	 */
	public function imprimeCodigoBarras($sCodigo, $sX, $sY, $sType) {

    $sLinha = str_pad(trim($sCodigo),7,'0',STR_PAD_LEFT);
    $sCodigo = str_pad($sCodigo,10,'0',STR_PAD_LEFT);
    $sX = str_pad($sX,4,'0',STR_PAD_LEFT);
    $sY = str_pad($sY,4,'0',STR_PAD_LEFT);

    $sCodigoBarra = "1{$sType}20025{$sY}{$sX}A {$sCodigo}";
		parent::addComando($sCodigoBarra);
	}

	/**
	 * Método para iniciar a impressao de uma etiqueta
	 *
	 * @param string $sTexto
	 */
	public function inicializa() {

    $sTexto  = chr(2)."KI<7".chr(13);
    $sTexto .= chr(2)."L".chr(13);
    $sTexto .= "D11".chr(13);
		parent::addComando($sTexto);
	}

	/**
	 * Método para finalizar a impressao
	 *
	 * @param string $sTexto
	 */
	public function finaliza(){

		$sTexto = "E";
		parent::addComando($sTexto);
	}

	/**
	 * Método para finalizar os comandos
	 * @param string $sFinalizadorCoamando
	 */
	public function rodarComandos(){
		$sFinalizadorComando = chr(13);
		parent::rodarComandos($sFinalizadorComando);

	}
}
?>