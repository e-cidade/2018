<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once ('model/PadArquivoEscritor.model.php');
require_once ('dbforms/db_layouttxt.php');

class PadArquivoEscritorTXT extends PadArquivoEscritor {
	
  protected $aCacheLinhas = array();
	/**
	 * Transforma os dados passados para TXT
	 * @param iPadArquivoTXTBase $oArquivo
	 * @return caminho do arquivo
	 */
	public function  criarArquivo(iPadArquivoTXTBase $oArquivo) {
		
		/**
		 * Instância dos objetos que serão utilizados para
		 * escrever o arquivo txt 
		 */
		
		$iCodigoLayout    = $oArquivo->getCodigoLayout();
		$sNomeArquivo     = $oArquivo->getNomeArquivo();
		$oLayoutTxt       = new db_layouttxt($iCodigoLayout, "tmp/{$sNomeArquivo}.txt");
		
		/**
		 * Busca as linhas do layout e as percorre buscando os seus
		 * respectivos campos
		 */
				
		foreach ($oArquivo->getDados() as $oLinha) {

			/**
			 * Busca os campos de cada linha 
			 */
			$aCamposLinha = $this->getCamposLinha($oLinha->codigolinha);
			$oLayoutTxt->setCampoTipoLinha($aCamposLinha[0]->db51_tipolinha);				
			foreach ($aCamposLinha as $oCampo) {
				
				if (isset($oLinha->{$oCampo->db52_nome})) {
	        $oLayoutTxt->setCampo($oCampo->db52_nome, $oLinha->{$oCampo->db52_nome});
				} else {
					throw new Exception("A propriedade {$oCampo->db52_nome} não foi setada. Favor verificar.");
				}
			}
			$oLayoutTxt->geraDadosLinha();
		}
		$oLayoutTxt->fechaArquivo();
		return "tmp/{$sNomeArquivo}.txt";
	} // fim do método
	
	/**
	 * retorna os campos da linha 
	 * @param integer $iCodigoLinha codigo da linha
	 * @return array
	 */
	protected function getCamposLinha($iCodigoLinha) {
	  
	  if (!isset($this->aCacheLinhas[$iCodigoLinha])) {
	    
	    $oDaoLayoutCampos = db_utils::getDao('db_layoutcampos');
	    $sWhereBuscaCamposLinha = " db52_layoutlinha = {$iCodigoLinha} ";
      $sSqlBuscaCamposLinha   = $oDaoLayoutCampos->sql_query(null, "*", null, $sWhereBuscaCamposLinha);
      $rsSqlBuscaCamposLinha  = $oDaoLayoutCampos->sql_record($sSqlBuscaCamposLinha);
      if ($oDaoLayoutCampos->numrows == 0) {
        throw new Exception("Linha {$iCodigoLinha} não cadastrada no layout {$iCodigoLayout}.");
      }
      $this->aCacheLinhas[$iCodigoLinha] = db_utils::getColectionByRecord($rsSqlBuscaCamposLinha);
	  }
	  return $this->aCacheLinhas[$iCodigoLinha];
	}
} // fim da classe
?>