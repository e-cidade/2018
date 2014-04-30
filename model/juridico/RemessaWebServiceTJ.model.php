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


db_app::import("configuracao.RemessaWebService");
/**
 * Classe de Geração e Envio de Remessas para o TJ 
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbrafael.nery $
 * @version $Revision: 1.1 $
 */
class RemessaWebServiceTJ extends RemessaWebService {

  /**
   * Itens da Remessa
   * @var array
   */
  private $aItensRemessa;

  /**
   * Construtor da Classe
   * @param integer $iCodigoRemessa
   */
  public function __construct($iCodigoRemessa = null) {

    parent::__construct($iCodigoRemessa);

    if ( !empty( $iCodigoRemessa ) ) {

      $oDaoPartilhaRemessaWebService = db_utils::getDao('partilharemessawebservice');
      $sSqlRemessa                   = $oDaoPartilhaRemessaWebService->sql_query_file(null, "v89_numnov", null, "v89_db_remessawebservice = {$iCodigoRemessa}");
      $rsRemessa                     = $oDaoPartilhaRemessaWebService->sql_record($sSqlRemessa);

      if ( $oDaoPartilhaRemessaWebService->numrows > 0 ) {

        $aDadosRemessa = db_utils::getCollectionByRecord($rsRemessa);

        foreach ( $aDadosRemessa as $oDadoRemessa ) {
          $this->adicionarRecibo(new recibo(null, null, null, $oDadoRemessa->v89_numnov) );
        }
      }
    }
  }

  /**
   * Adiciona um recibo a remessa
   * @param integer $iNumpreRecibo
   */
  public function adicionarRecibo( recibo $oRecibo ) {

    $this->aItensRemessa[$oRecibo->getNumpreRecibo()] = $oRecibo;
  }

  /**
   * Salva Remessa
   */
  public function salvar(){

    parent::salvar();

    db_utils::getDao('partilharemessawebservice', false);
    foreach ( $this->aItensRemessa as $oRecibo ) {

      $oDaoPartilhaRemessaWebService = new cl_partilharemessawebservice();
      $oDaoPartilhaRemessaWebService->v89_db_remessawebservice = $this->getCodigoRemessa();
      $oDaoPartilhaRemessaWebService->v89_numnov               = $oRecibo->getNumpreRecibo();
      $oDaoPartilhaRemessaWebService->v89_resposta             = '';
      $oDaoPartilhaRemessaWebService->incluir(null);

      if ( $oDaoPartilhaRemessaWebService->erro_status == "0" ) {
        throw new DBException("[4]Erro ao Incluir Daddos na Remessa \nRecibo: ".$oRecibo->getNumpreRecibo()."\n".$oDaoPartilhaRemessaWebService->erro_msg);
      }
    }
    return true;
  }

  /**
   * Processa Remessa
   */
  public function processar(){

    parent::processar();
        
    /**
     * Importanto e instanciando cliente do webservice e recibo
     */
    db_app::import('juridico.ClienteWebServiceTribunalJusticaRJ');
    db_app::import('recibo');
    
    $oIntegracao = new ClienteWebServiceTribunalJusticaRJ();

    /**
     * Percorrendo os dados da query enviado ao webservice
     */
    foreach ( $this->aItensRemessa as $oRecibo ) {

      $oIntegracao->setRecibo($oRecibo);
      $aRetornoWebService = $oIntegracao->enviarDadosProcesso();
      
      
      $oDaoPartilhaRemessaWebService = db_utils::getDao('partilharemessawebservice');
      $sSqlDadosPartilha             = $oDaoPartilhaRemessaWebService->sql_query_file(null,"*",null," v89_db_remessawebservice = ".$this->getCodigoRemessa()." and v89_numnov = ".$oRecibo->getNumpreRecibo());
      $rsDadosPartilha               = $oDaoPartilhaRemessaWebService->sql_record($sSqlDadosPartilha);
      
      
      if ( $oDaoPartilhaRemessaWebService->erro_status == "0" ) {
        throw new Exception("Erro ao Gravar Respota do WebService para o Recibo ".$oRecibo->getNumpreRecibo());
      }
      
      $oDadosPartilha                                          = db_utils::fieldsMemory($rsDadosPartilha,0);
      
      $oDaoPartilhaRemessaWebService->v89_sequencial           = $oDadosPartilha->v89_sequencial;
      $oDaoPartilhaRemessaWebService->v89_resposta             = implode($aRetornoWebService, ", ");
      $oDaoPartilhaRemessaWebService->alterar( $oDadosPartilha->v89_sequencial );
      
      if ( $oDaoPartilhaRemessaWebService->erro_status == "0" ) {
        throw new Exception("Erro ao Gravar Respota do WebService para o Recibo ".$oRecibo->getNumpreRecibo());        
      }
      
    }
  }

  /**
   * Retorna remessas conforme o Tipo de Processamento
   * 
   * 
   * 
   * @param  integer $iTipoProcessamento 
   *                 |0 - Todas   
   *                 |1 - Não Processadas
   *                 |2 - Processadas      
   * @throws DBException Quando houver erro de query para buscar Dados
   * @return RemessaWebServiceTJ[] 
   */
  public static function getRemessas( $iTipoProcessamento = 0 ) {

    $oDaoDBRemessaWebService = db_utils::getDao('db_remessawebservice');
    $sWhereProcessamento = "";

    if ( $iTipoProcessamento == 1 ) { // Não Processadas
      $sWhereProcessamento = " and db127_processada is false ";
    }

    if ( $iTipoProcessamento == 2 ) { // Processadas
      $sWhereProcessamento = " and db127_processada is true ";
    }


    $sSqlRemessas = $oDaoDBRemessaWebService->sql_query_file(null, "db127_sequencial \n", null, " \n db127_sistemaexterno = 2 \n{$sWhereProcessamento}");
    $rsRemessas   = db_query($sSqlRemessas);
    $aRemessas    = array();

    if ( !$rsRemessas ) {
      throw new DBException("Erro ao Retornar remessas!");
    }

    foreach ( db_utils::getCollectionByRecord($rsRemessas) as $oDadosRemessa ) {
      $aRemessas[] = new RemessaWebServiceTJ( $oDadosRemessa->db127_sequencial );
    }

    return $aRemessas;
  }
}