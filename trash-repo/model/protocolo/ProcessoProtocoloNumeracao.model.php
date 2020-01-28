<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Classe singleton para controle de numeraчуo dos processos de procotolo
 * @package protocolo
 * @static 
 */
class  ProcessoProtocoloNumeracao {
  
  static private $oInstance = null;
  
  private $iTipoControle = null;
  
  private $proximoNumero = null;
  /**
   * classe construtora marcada como private
   */
  private function __construct() {
    
    /**
     * pesquisa a forma de numeracao do modulo protocolo.
     * os tipos disponiveis sуo 1 - Forma global - a a Numeracao щ sequencial para todas as instituicoes
     *                          2 - Numeracao Anual Cada instituiчуo tem seus proprios nњmeros de protocolo.
     *                              a numeracao щ reiniciada a cada ano.
     */
    $oDaoParametrosGlobais = db_utils::getDao("protparamglobal");
    $sSqlDadosParametros   = $oDaoParametrosGlobais->sql_query_file();
    $rsDadosParametros     = $oDaoParametrosGlobais->sql_record($sSqlDadosParametros);
    
    if ($oDaoParametrosGlobais->numrows == 0) {
      throw new Exception('Parametros Globais do mѓdulo protocolo nуo configurados.');
    }
    $this->iTipoControle = db_utils::fieldsMemory($rsDadosParametros, 0)->p06_tipo;
  }
  
  /**
   * marcamos a funчуo clone como private, para nуo podermos ter um anova instancia atraves de clone
   *
   */
  private function __clone() {
    
  }
  
  /**
   * retorna o proximo nњmero de protocolo
   *
   * @return integer
   */
  public function getProximoNumero() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception('Para utilizaчуo desse mщtodo щ necessсrio uma transaчуo com o Banco de Dados.');
    }
    $oInstancia           = self::getInstance();
    $oInstancia->bloqueiaControleNumeracao();
    $oInstancia->proximoNumero = $oInstancia->getNumeroUtilizar();
    $oInstancia->updateNumeracao(); 
    return $oInstancia->proximoNumero; 
  }
  
  /**
   * Retorna o nњmero que deve ser Utilizado
   *
   */
  private function getNumeroUtilizar() {
    
    $oInstancia = self::getInstance();
    $sWhere     = $oInstancia->getRegraNumeracao();   
    $oDaoProtocoloNumeracao = db_utils::getDao("protprocessonumeracao");
    $sSqlNumeracao  = $oDaoProtocoloNumeracao->sql_query_file(null, 
                                                              "max(p07_proximonumero) as numeroutilizar",
                                                              null,
                                                              $sWhere
                                                              );
    $rsNumeracao = $oDaoProtocoloNumeracao->sql_record($sSqlNumeracao);
    return db_utils::fieldsMemory($rsNumeracao, 0)->numeroutilizar; 
  }
  /**
   * retorna a instancia da classe
   *
   * @return ProcessoProtocoloNumeracao
   */
  private function getInstance() {
    
    if (self::$oInstance == null) {
      self::$oInstance = new ProcessoProtocoloNumeracao();
    }
    return self::$oInstance;
  }
  /**
   * Atualiza os dados da numeraчуo, conforme regra configurada
   *
   */
  private function updateNumeracao() {
    
    $oInstancia             = self::getInstance();
    $sWhere                 = $oInstancia->getRegraNumeracao();
    $oDaoProtocoloNumeracao = db_utils::getDao("protprocessonumeracao");
    /**
     * Atualizamos a numeraчуo  conforme regra
     */
    $sSqlNumeracao  = $oDaoProtocoloNumeracao->sql_query_file(null, 
                                                              "p07_sequencial",
                                                              null,
                                                              $sWhere
                                                              );
    $rsNumeracao = $oDaoProtocoloNumeracao->sql_record($sSqlNumeracao);
    $aNumeracoes = db_utils::getColectionByRecord($rsNumeracao);
    foreach ($aNumeracoes as $oNumeracao) {

      $oDaoProtocoloNumeracao->p07_sequencial    = $oNumeracao->p07_sequencial;
      $oDaoProtocoloNumeracao->p07_proximonumero = $this->getInstance()->proximoNumero+1;
      $oDaoProtocoloNumeracao->alterar($oNumeracao->p07_sequencial);
      if ($oDaoProtocoloNumeracao->erro_status == 0) {
        throw new Exception('Erro ao atualziar numeraчуo do Protocolo!');
      }
    }
  }
  
  /**
   * Cria um Lock nas numeraчѕes conforme a Regra do controle
   *
   */
  private function bloqueiaControleNumeracao() {
    
    $sWhere = self::getInstance()->getRegraNumeracao();
    $oDaoProtocoloNumeracao = db_utils::getDao("protprocessonumeracao");
    $sSqlNumeracao  = $oDaoProtocoloNumeracao->sql_query_file(null, "*", null, $sWhere);
    $sSqlNumeracao .= " for update";
    $oDaoProtocoloNumeracao->sql_record($sSqlNumeracao);
  }
  
  /**
   * Retorna a regra de numeracao conforme Configuraчуo
   * 
   * @return string clausula Where
   */
  private function getRegraNumeracao () {
    
    $sWhere = " p07_ano = ".db_getsession("DB_anousu");
    $oInstancia = self::getInstance();
    switch ($oInstancia->iTipoControle) {
      
      case 2:
        
        $sWhere .= " and p07_instit = ".db_getsession("DB_instit");
        break;
    }
    return $sWhere;
  }
}
?>