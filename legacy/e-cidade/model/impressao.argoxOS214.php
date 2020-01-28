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

final class impressaoOS214 extends impressao {
	
	function __construct($sIp, $sPorta){
		
		parent::setIp($sIp);
		parent::setPorta($sPorta);
		
	}
	
	/**
	 * Método para escrever o testo em uma linha
	 *
	 * @param string $sTexto
	 */
	
	function imprimeLinha($sTexto,$sX,$sY){
		$sLinha = db_removeAcentuacao($sTexto);
		$sLinha = substr($sLinha,0,23);
		$sLinha = str_pad($sLinha,23,' ',STR_PAD_BOTH);
    $sLinha = 'A'.$sX.','.$sY.',0,3,1,1,N,"'.$sLinha.'"';
		parent::addComando($sLinha);
		
	}
	
	/**
	 * Método para escrever o código de barras
	 *
	 * @param string $sTexto
	 */
	
	function imprimeCodigoBarras($sCodigo,$sX,$sY,$sType){
		
    $sLinha = str_pad(trim($sCodigo),7,'0',STR_PAD_LEFT);
    $sCodigoBarra = 'B'.$sX.','.$sY.',0,9,'.$sType.',3,41,B,"'.$sLinha.'"';
		parent::addComando($sCodigoBarra);
		
	}
	
	/**
	 * Método para iniciar a impressao de uma etiqueta
	 *
	 * @param string $sTexto
	 */
	function inicializa(){
		$sTexto = "N";
		parent::addComando($sTexto);
		
	}
	
	/**
	 * Método para finalizar a impressao
	 *
	 * @param string $sTexto
	 */
	function finaliza(){
		$sTexto = "P1";
		parent::addComando($sTexto);		
	}
	
	/**
	 * Método para finalizar os comandos
	 *
	 * @param string $sFinalizadorCoamando
	 */
	
	function rodarComandos(){
		$sFinalizadorComando = "\n";
		parent::rodarComandos($sFinalizadorComando);
	
	}
	
//	function resetComandos(){
//       echo w jfilaflkjçsdjf
//       $t = afksjdhkjhf shf;
//		parent::resetComandos();
//	}
	
}

?>