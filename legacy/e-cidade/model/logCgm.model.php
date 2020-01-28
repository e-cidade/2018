<?
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

class logcgm {

  /**
   * Cуdigo da Inscriзгo municipal (issbase.q02_inscr);
   * 
   * @var integer;
   */
  private $iInscricao = null;
  
  /**
   * Data de inclusao do registro;
   * 
   * @var date;
   */
  private $dtDataAtual = null;
  
  /**
   * Hora da inclusao do registro;
   * 
   * @var char;
   */
  private $sHora = null;
  
  /**
   * Origem da inclusao (1-automatica, 2-manual);
   * 
   * @var integer;
   */
  private $iOrigem = null;
  
  /**
   * Observacao sobre a arigem de inclusao;
   * 
   * @var string;
   */
  private $sObservacao = null;
  
  /**
   * Tipos de log;
   * 
   * @var array;
   */
  private $aIssBaseLog = array();
  
  public function __construct(){
    
    $this->dtDataAtual  = date("Y-m-d", db_getsession("DB_datausu"));
    $this->sHora        = db_hora();
    $this->iOrigem      = 1;
  }
  
  /**
   * Seta valor de mensagem padrao
   * 1 - inclusao 2 - alteracao 3 - exclusao
   * 
   * @param object $oIssBaseLog
   */
  private function setObservacao($oIssBaseLog) {

    switch ($oIssBaseLog->iOpcao) {
      
      case 2:
      	
        if (isset($oIssBaseLog->issbaselogtipo) && $oIssBaseLog->issbaselogtipo == 2) {
          $this->sObservacao = "Alterado endereзo de {$oIssBaseLog->enderecoantigo} para {$oIssBaseLog->endereconovo}";
        } if (isset($oIssBaseLog->issbaselogtipo) && $oIssBaseLog->issbaselogtipo == 8) {
        	$this->sObservacao = "Alterado nome de {$oIssBaseLog->nomeantigo} para {$oIssBaseLog->nomenovo}";
        }        
        break;
    }
  }
  
  /**
   * Cria array de objetos para verificar tipo de log 
   *
   * @param integer $iInscricao codigo da inscricao
   * @param integer $iOpcao codigo da opcao de menu
   * @param integer $iLogTipo codigo do tipo de log
   * @param string  $sNomeNovo nome novo no cadastro do CGM
   * @param string  $sNomeAntigo nome antigo no cadastro do CGM
   * @param string  $sEnderecoNovo endereco novo no cadastro de CGM
   * @param string  $sEnderecoAntigo endereco antigo no cadastro de CGM
   */
  public function identificaAlteracao($iInscricao = null, $iOpcao, $iLogTipo = null, $sNomeNovo = "", $sNomeAntigo = "",  
                                      $sEnderecoNovo = "", $sEnderecoAntigo = "") {
    
    if ($iInscricao == null) {
      throw  new Exception("Erro: Inscriзгo nгo informada.");
    }
    
    if ($iLogTipo == null) {
      throw  new Exception("Erro: Tipo de Log nгo informado.");
    }
    
    $oTipoAlteracao = new stdClass();
    $oTipoAlteracao->iInscricao     = $iInscricao;
    $oTipoAlteracao->iOpcao         = $iOpcao;
    $oTipoAlteracao->issbaselogtipo = $iLogTipo;
    $oTipoAlteracao->nomenovo       = $sNomeNovo;
    $oTipoAlteracao->nomeantigo     = $sNomeAntigo;
    $oTipoAlteracao->endereconovo   = $sEnderecoNovo;
    $oTipoAlteracao->enderecoantigo = $sEnderecoAntigo;
    $this->aIssBaseLog[]            = $oTipoAlteracao;  
  }
  
  /**
   * Grava log na tabela issbaselog
   *
   * @param void
   */
  public function gravarLog() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Erro: Nгo existe Transaзгo Ativa.");
    }
    
    foreach ($this->aIssBaseLog as $oIssBaseLog) {

      $this->setObservacao($oIssBaseLog);
      
      $oDaoIssBaseLog = db_utils::getDao("issbaselog");
      $oDaoIssBaseLog->q102_inscr          = $oIssBaseLog->iInscricao;
      $oDaoIssBaseLog->q102_data           = $this->dtDataAtual;
      $oDaoIssBaseLog->q102_hora           = $this->sHora;
      $oDaoIssBaseLog->q102_origem         = $this->iOrigem;
      $oDaoIssBaseLog->q102_obs            = $this->sObservacao;
      $oDaoIssBaseLog->q102_issbaselogtipo = $oIssBaseLog->issbaselogtipo;
      $oDaoIssBaseLog->incluir(null);
      if ($oDaoIssBaseLog->erro_status == '0') {
        throw  new Exception($oDaoIssBaseLog->erro_msg);
      }
    }
  }
}
?>