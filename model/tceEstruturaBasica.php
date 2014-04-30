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


abstract class tceEstruturaBasica {
  
	public $oTxtLayout = null;
	
  /**
   * Metodo construtor
   *
   * @param integer  $iCodArquivo   Codigo do layout arquivo no cadastro de layouts 
   * @param string   $sArqName      Nome do arquivo a ser gerado
   */
	function __construct($iCodArquivo,$sArqName) {
  	
  	if(class_exists("db_layouttxt")){
  	  $this->oTxtLayout = new db_layouttxt($iCodArquivo, "tmp/".$sArqName, "");
  	}else{
  		throw new Exception("Classe db_layouttxt nao encontrada");
  	}

  }
  
  /**
   * Metodo para processar o cabecalho padrao dos arquivos do TCE
   *
   * @param  integer      $iInstit       codigo da instituicao
   * @param  string       $sDataini      string no formato do banco (AAAA-MM-DD) com a data inicial do periodo a ser gerado o arquivo
   * @param  string       $sDatafim      string no formato do banco (AAAA-MM-DD) com a data final do periodo a ser gerado o arquivo
   * @param  integer      $iCodRemessa   codigo da remessa do pacote dos arquivos  
   * @return db_utils                    retorna objeto do tipo db_utils/stdClass com os atributos/campos do cabecalho
   */
  public function cabecalhoPadrao($iInstit, $sDataini, $sDatafim, $iCodRemessa) {

    $sqlCabecalho = "  select codigo, ";
    $sqlCabecalho .= "         nomeinst         as nomesetorgoverno, ";
    $sqlCabecalho .= "         cgc              as cnpjsetorgoverno, ";
    $sqlCabecalho .= "         '{$sDataini}'    as datainicialinformacao, ";
    $sqlCabecalho .= "         '{$sDatafim}'    as datafinalinformacao, ";
    $sqlCabecalho .= "         '" . date('Y-m-d', db_getsession('DB_datausu')) . "' as datageracaoarquivo,";
    $sqlCabecalho .= "         '{$iCodRemessa}' as codigoremessa";
    $sqlCabecalho .= "    from db_config  ";
    $sqlCabecalho .= "   where codigo = {$iInstit} ";
    $rsCabecalho = pg_query($sqlCabecalho);
    $oCabecalho = db_utils::fieldsMemory($rsCabecalho, 0);
    
    return $oCabecalho;
  
  }
  
   /**
    * Metodo para processar rodape padrao dos arquivos do TCE
    *
    * @param integer    $iTotalRegistros   quantidade total de registros gerados 
    * @param string     $sDescricao        descricao livre, padrao e a expressao 'FINALIZADOR' 
    * @return unknown
    */  
  public function rodapePadrao($iTotalRegistros = 0, $sDescricao = 'FINALIZADOR') {

    $obj = new stdClass();
    $obj->descricao      = $sDescricao;
    $obj->totalregistros = $iTotalRegistros;
    return $obj;
  
  }
  
}

?>