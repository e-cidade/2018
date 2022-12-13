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


abstract class PagamentoFornecedorTXTBase {

  protected $iCodigoBanco;
  protected $iCodigoLayout; 
  protected $sCaminhoArquivo;
  protected $oDadosArquivo;
  protected $aDadosErro = array();
  
  /**
   * Retorna os dados processados do layout
   * @return array
   */  
  public function getDados() {
    return $this->oDadosArquivo;
  }
  
  /**
   * Retorna o codigo do banco do txt
   */
  public function getCodigoBanco() {
    return $this->iCodigoBanco;
  }
  
  /**
   * Define o caminho do arquivo para o processamento
   * @return PagamentoFornecedorCaixaEconomica
   */
  public function setArquivo($sCaminhoArquivo) {
    
    $this->sCaminhoArquivo = $sCaminhoArquivo;
    return $this;
  }
  
  /**
   * Processa os dados do arquivo de Retorno
   * @return array
   */
  public function processarArquivoRetorno() {

  }
  /**
   * Retorna o codigo de erro do banco cadastrado no sistema
   * @param string $sCodigoRetorno
   * @return array
   */
  protected function setDadosErroLinha($aCodigoRetorno) {
  	
  	/**
  	 * Montamos a lista de códigos a serem utilizados na consulta no banco de dados
  	 */
  	$sListaCodigosErro = '';
    foreach ($aCodigoRetorno as $sCodigo) {
    	$sListaCodigosErro .= "'".$sCodigo."', ";
    }
    $sListaCodigosErro = substr($sListaCodigosErro, 0, strlen($sListaCodigosErro) - 2);
    
    /**
     * efetuamos a consulta no banco de dados
     */
    $oDaoErroBanco = db_utils::getDao("errobanco");
    $sCamposBuscaRetornoBanco = " e92_sequencia as sequencia, e92_processa as processa, e92_coderro ";
    $sWhereBuscaRetornoBanco  = "     e92_coderro in ({$sListaCodigosErro})     ";
    $sWhereBuscaRetornoBanco .= " and e78_codban  = '{$this->getCodigoBanco()}' ";
    $sSqlRetornoBanco = $oDaoErroBanco->sql_query_banco(null, $sCamposBuscaRetornoBanco, null, $sWhereBuscaRetornoBanco);
    $rsRetornoBanco   = $oDaoErroBanco->sql_record($sSqlRetornoBanco);
      
    if ($oDaoErroBanco->numrows > 0) {
       
      /**
       * Percoremos os resultados da consulta e fazemos a composição 
       * do array de objetos que será utilizado no retorno da função
       */
    	for ($i = 0; $i < $oDaoErroBanco->numrows; $i++) {

      	$oOcorrencia = db_utils::fieldsMemory($rsRetornoBanco, $i);
      	$oOcorrencia->processa = $oOcorrencia->processa == 'f'?false:true;
        $this->aDadosErro[$oOcorrencia->e92_coderro] = $oOcorrencia;
      }
    } else {
      $sException  = " Problema na configuração de erros do banco :                    ";
      $sException .= " [{$this->getCodigoBanco()}]";
      throw new Exception($sException);
    }
    
    return $this->aDadosErro;
  }
  
  /**
   * Armaneza o código de erro do banco em um array evitando buscar o código na base de dados repetidamente
   * @param  string $sCodigoRetorno
   * @return object
   */
  protected function getCodigoErro($sCodigoRetorno) {
  	
    $aCodigosRetorno = array();
    $aCodigos        = array();
    for ($i = 0; $i < floor(strlen(trim($sCodigoRetorno)) / 2); $i++) {
      $aCodigos[] = substr($sCodigoRetorno, $i * 2, 2);
    }
    
    foreach ($aCodigos as $sCodigo) {
      if  (!key_exists($sCodigo, $this->aDadosErro)) {
        $this->setDadosErroLinha(array($sCodigo));
      }
    	$aCodigosRetorno[$sCodigo] = $this->aDadosErro[$sCodigo];
    }
    return $aCodigosRetorno;
  }
}
?>