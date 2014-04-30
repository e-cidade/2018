<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


class modeloEtiqueta{
	private $sXml;
	private $codigo;
	/**
	 * Recebe por parametro o cѓdigo do arquivo
	 * que vem da tabela bensmodeloetiqueta	 
	 * 
	 * @param integer $iCodigo
	 */ 
	
	function __construct($iCodigo) {
		$this->codigo = (int)$iCodigo;
		$this->sXml = '';	
	}

	/**
	 * Mщtodo que retorna uma string contendo
	 * o caminho e nome do arquivo gerado para consulta.
	 * Em caso de erro gera uma Excessуo.
	 * 
	 * @return string 
	 */ 
		
	public function leArquivoXml(){
		if(!is_int($this->codigo) && $this->codigo == 0){
			throw new Exception('Codigo do arquivo nуo informado ou invсlido !');
		}
		
		$clBensModeloEtiqueta = new cl_bensmodeloetiqueta();
		$rsXml = $clBensModeloEtiqueta->sql_record($clBensModeloEtiqueta->sql_query($this->codigo));
		if($clBensModeloEtiqueta->erro_status == "0"){
			throw new Exception($clBensModeloEtiqueta->erro_msg);		
		}
		
		$oXml = db_utils::fieldsMemory($rsXml,0);
		
		$this->sXml = $oXml->t71_strxml;
		
		return $this->geraArquivoXmlTemp();		
		
	}

	/**
	 * Mщtodo utilizado para salvar o arquivo xml modificado
	 * recebe por parametro o conteudo xml e a descriчуo do memso
	 * @param string $sDescr, string $sXml
	 * @return Retorna string de alteraчуo com sucesso ou gera Excessуo em caso de erro
	 */
	
	public function gravaArquivoXml($sXml="",$sDescr=""){
    if(!is_int($this->codigo) && $this->codigo == 0){
      throw new Exception('Codigo do arquivo nуo informado ou invсlido !');
    }
    
    $clBensModeloEtiqueta = new cl_bensmodeloetiqueta();
    
    $clBensModeloEtiqueta->t71_sequencial = $this->codigo;
    
    if($sDescr != ""){
      $clBensModeloEtiqueta->t71_descr = $sDescr;
    }
    
    if($sXml != ""){
      $clBensModeloEtiqueta->t71_strxml = $sXml;
    }
    
    $clBensModeloEtiqueta->alterar($this->codigo);
       
    if ( $clBensModeloEtiqueta->erro_status == "0" ) {

    	throw new Exception($clBensModeloEtiqueta->erro_msg);   
    
    }else{
    	
      return $clBensModeloEtiqueta->erro_msg;
    
    }
        
  }
	
	/**
	 * Mщtodo que gera um arquivo temporсrio
	 * com o conteњdo armazenado na propriedade
	 * $this->sXml.
	 * Retorna uma string com o arquivo gerado 
	 * para o metodo que chamou. 
	 * @return String
	 */
	private function geraArquivoXmlTemp(){
		
		if($this->sXml == ""){
			throw new Exception("String Xml vazia !");
		}
		$fileName = "tmp/xmlTemp".date("YmdHis").".xml";
		
		$fileRetorno = file_put_contents  ( $fileName  ,  $this->sXml);
		
		if($fileRetorno == false){
			throw new Exception("Erro ao abrir arquivo Xml !");
		}else{
			return $fileName;
		}
		
	}

}

?>