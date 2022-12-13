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

/**
 * Model que armazena um conjunto de regras de um documento (Ex: Empenho)
 * 
 * @author  matheus felini
 * @package contabilidade
 * @version $Revision: 1.3 $
 */
class DocumentoContabilConjuntoRegra {
	
	/**
   * Conjunto de regras (SQL) de um documento contábil
   * @var array
	 */
	protected $aRegras = array();
	
	/**
	 * Adiciona as regras do documento no array de Regras
	 * @param integer $iCodigoDocumento
	 */
	public function __construct($iCodigoDocumento) {
		
		$oDaoHistDocRegra = db_utils::getDao('conhistdocregra');
		$sSqlBuscaRegra   = $oDaoHistDocRegra->sql_query(null, "*", null, "c53_tipo = {$iCodigoDocumento}");
		$rsBuscaRegras    = $oDaoHistDocRegra->sql_record($sSqlBuscaRegra);
		$iLinhasRegras    = $oDaoHistDocRegra->numrows;
		if ($iLinhasRegras > 0) {
			
			for ($iRowRegra = 0; $iRowRegra < $iLinhasRegras; $iRowRegra++) {
				
				$iSequencialHistDoc      = db_utils::fieldsMemory($rsBuscaRegras, $iRowRegra)->c92_sequencial;
				$oDocumentoContabilRegra = new DocumentoContabilRegra($iSequencialHistDoc);
				$this->adicionarRegra($oDocumentoContabilRegra);
			}
		}
		return true;
	}
	
	/**
	 * Retorna o código o documento que deve ser executado
	 * Ex.: Empenho Normal ou Empenho Inscricao Passivo
	 * @param  array $aVariavelDocumento
	 * @throws Exception
	 * @return integer Código do Documento (conhistdoc)
	 */
	public function getCodigoDocumento($aVariavelDocumento) {
		
		$aRegrasDocumento = $this->getRegras();
		/*
		 * Percorremos o array de regras executando a query cadastrada na base de dados
		 * para descobrirmos o tipo de documento que deve ser executado 
		 */
		foreach ($aRegrasDocumento as $oDocumentoRegra) {

			if ($oDocumentoRegra->validaRegra($aVariavelDocumento)) {
				return $oDocumentoRegra->getCodigoDocumento();
			}
		}
		throw new Exception("Não foi possível localizar a regra a ser utilizada pelo documento.");		
	}
	
	/**
	 * Adiciona um objeto do tipo DocumentoContabilRegra ao array de Regras
	 * @param DocumentoContabilRegra $oDocumentoContabilRegra
	 */
	public function adicionarRegra(DocumentoContabilRegra $oDocumentoContabilRegra) {
		$this->aRegras[] = $oDocumentoContabilRegra;
	}
	/**
	 * Retorna uma colecao de objeto do tipo DocumentoContabilRegra
	 * @return array
	 */
	public function getRegras() {
		return $this->aRegras;
	}
}
?>