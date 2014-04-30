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

class documentoTemplate  {
 
	private $sAquivoTemplate = '';
	private $lControle       = false; 
  	
	/*
	 * Este model busca o arquivo correspondente pelo tipo informado
	 * na criaчуo do objeto.
	 * Busca pelo documento na DocumentoTemplate
	 * se nуo enontarar busca o documento na DocumentoTemplatePadrao
	 */
  public function __construct($iTipo='', $iCodDocumento=null, $sCaminhoArquivo='', $lTemTransacaoAtiva = false) {
		
  	global $conn;
  	
  	require_once('dbforms/db_funcoes.php');
  	
  	if((int)$iTipo == "" || (int)$iTipo == 0) {
			throw new Exception('Valor do tipo Informado nуo vсlido!');
		}
		
  	if ( trim($sCaminhoArquivo) == '' ) {
  		$sArquivoSxw      = "docTemplate".date("YmdHis").db_getsession("DB_id_usuario").".sxw";
	    $sCaminhoTemplate = "tmp/".$sArquivoSxw;
  	}else{
  		$sCaminhoTemplate = $sCaminhoArquivo;
  	}
		
		$db82_templatetipo = (int) $iTipo;
		
		$oDaoDocumentoTemplate    = db_utils::getDao('db_documentotemplate');
		$sWhereDocumentoTemplate  = "     db82_templatetipo = {$db82_templatetipo}";
		
		if ( isset($iCodDocumento) && trim($iCodDocumento) != '' ) {
			$sWhereDocumentoTemplate .= " and db82_sequencial   = {$iCodDocumento}    ";
		}

		$resDocumentoTemplate		  = $oDaoDocumentoTemplate->sql_record($oDaoDocumentoTemplate->sql_query_file(null,"db82_arquivo",null,$sWhereDocumentoTemplate));

  	if($oDaoDocumentoTemplate->numrows == 1) {
			
			$oArquivoSxw = db_utils::fieldsMemory($resDocumentoTemplate,0);
			
			if ( !$lTemTransacaoAtiva ) {
				db_inicio_transacao();
			}
			
	    $lGeraSxw = pg_lo_export($oArquivoSxw->db82_arquivo,$sCaminhoTemplate, $conn);
	    
	    if ( !$lTemTransacaoAtiva ) {
	    	db_fim_transacao();
	    }
	    
	    if (!$lGeraSxw) {   	 	
	      throw new Exception ("Erro ao gerar aquivo Sxw!");
	    }
	    $this->sAquivoTemplate = $sCaminhoTemplate;
	         
			$this->lControle = true;
		} else if($oDaoDocumentoTemplate->numrows == 0) {
			$this->lControle = false;				
		} else {
			$this->lControle = true;
			throw new Exception('Exitem mais de uma template cadastrada!!!');
		}
		
		if(!$this->lControle) {
			
			$oDaoDocumentoTemplatePadrao 	= db_utils::getDao('db_documentotemplatepadrao');
			$resDocumentoTemplatePadrao 	= $oDaoDocumentoTemplatePadrao->sql_record($oDaoDocumentoTemplatePadrao->sql_query_file(null,"db81_nomearquivo",null,"db81_templatetipo = $db82_templatetipo"));
		
	  	if($oDaoDocumentoTemplatePadrao->numrows == 1) {
			
				$oArquivoSxw = db_utils::fieldsMemory($resDocumentoTemplatePadrao,0);
				if(file_exists($oArquivoSxw->db81_nomearquivo)) {
					$this->sAquivoTemplate = $oArquivoSxw->db81_nomearquivo;
				}
				
			} else if($oDaoDocumentoTemplatePadrao->numrows == 0) {
				throw new Exception('Nenhum arquivo template padrуo cadastrado!!!');			
			} else {
				throw new Exception('Exitem mais de uma template padrуo cadastrada!!!');
			}
		}
  }
  
  public function getArquivoTemplate(){
    return $this->sAquivoTemplate; 	
  }
  
}

?>