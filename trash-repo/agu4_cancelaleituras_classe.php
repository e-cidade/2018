<?
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


require_once ('libs/db_utils.php');
require_once ('dbforms/db_funcoes.php');
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

class cl_cancelaleituras {
  
  /**
   * Codigo leitura - agualeitura 
   * @var integer
   */
  protected $iCodLeitura;
  
  /**
   * Codigo da exportacao - aguacoletorexporta
   * @var integer
   */
  protected $iCodExportacao;
  
  /**
   * Motivo do Cancelamento
   * @var String
   */
  protected $sMotivo;
  
  /**
   * Status da leitura (1 - ativo, 2 - inativo, 3 - cancelado)
   * @var integer;
   */
  protected $iStatus;
  
  /**
   * Ano da exportacação - aguacoletorexporta
   * @var Integer
   */
  protected $iAno;
  
  /**
   * Mes da Exportação - aguacoletorexporta
   * @var Integer
   */
  protected $iMes;
  
  /**
   * id do usuario da sessao
   * @var integer
   */
  public $iUsuario;
  
  /**
   * data do sistema
   * @var Date
   */
  public $dData;
  
  /**
   * Hora do sistema
   * @var Date
   */
  public $dHora;
  
  /**
   * Status de erro (0 -Sim, 1 - Não)
   * @var integer
   */
  public $iErroStatus = 1;
  
  /**
   * Mensagem de erro
   * @var string
   */
  public $sErroMsg = "Cancelamento de Exportação efetuado com sucesso.";
  
  /**
   * Pagina de retorno (refresh)
   * @var String
   */
  public $pagina_retorno = null;
  
  /**
   * Funcao para mudar o status da leitura em agualeitura 
   * @param $iCodLeitura Chave primaria da tabela agualeitura
   * @param $iStatus (iStatus 1 - Ativo, 2 - Inativo, 3 - Cancelada)
   */
  public function mudaStatusLeitura($iCodLeitura, $iStatus) {
    
    require_once ('classes/db_agualeitura_classe.php');
    require_once ('classes/db_agualeituracancela_classe.php');
    
    $clAguaLeitura = new cl_agualeitura ();
    
    $this->iCodLeitura = $iCodLeitura;
    $this->iStatus = $iStatus;
    
    $rsAguaLeitura = $clAguaLeitura->sql_record ( $clAguaLeitura->sql_query_file ( $this->iCodLeitura ) );
    
    if ($clAguaLeitura->numrows > 0) {
      
      $oAguaLeitura = db_utils::fieldsMemory ( $rsAguaLeitura, 0 );
      
      $clAguaLeitura->x21_codleitura    = $oAguaLeitura->x21_codleitura;
      $clAguaLeitura->x21_codhidrometro = $oAguaLeitura->x21_codhidrometro;
      $clAguaLeitura->x21_exerc         = $oAguaLeitura->x21_exerc;
      $clAguaLeitura->x21_mes           = $oAguaLeitura->x21_mes;
      $clAguaLeitura->x21_situacao      = $oAguaLeitura->x21_situacao;
      $clAguaLeitura->x21_numcgm        = $oAguaLeitura->x21_numcgm;
      $clAguaLeitura->x21_dtleitura     = $oAguaLeitura->x21_dtleitura;
      $clAguaLeitura->x21_usuario       = $oAguaLeitura->x21_usuario;
      $clAguaLeitura->x21_dtinc         = $oAguaLeitura->x21_dtinc;
      $clAguaLeitura->x21_leitura       = $oAguaLeitura->x21_leitura;
      $clAguaLeitura->x21_consumo       = $oAguaLeitura->x21_consumo;
      $clAguaLeitura->x21_excesso       = $oAguaLeitura->x21_excesso;
      $clAguaLeitura->x21_virou         = $oAguaLeitura->x21_virou;
      $clAguaLeitura->x21_tipo          = $oAguaLeitura->x21_tipo;
      $clAguaLeitura->x21_status        = $this->iStatus;
      
      $clAguaLeitura->alterar ( $clAguaLeitura->x21_codleitura );
      
      if ($clAguaLeitura->erro_status == "0") {
        $this->iErroStatus = 0;
        $this->sErroMsg = "Alteração não realizada. Operação abortada.<br>(Agua Leitura - $clAguaLeitura->erro_msg)";
        return false;
      
      } elseif ($this->iStatus == 3) {
        $clAguaLeituraCancela = new cl_agualeituracancela ();
        
        $clAguaLeituraCancela->x47_agualeitura = $this->iCodLeitura;
        $clAguaLeituraCancela->x47_usuario = $this->iUsuario;
        $clAguaLeituraCancela->x47_data = $this->dData;
        $clAguaLeituraCancela->x47_hora = $this->dHora;
        $clAguaLeituraCancela->x47_motivo = $this->sMotivo;
        $clAguaLeituraCancela->incluir ( null );
        
        if ($clAguaLeituraCancela->erro_status == "0") {
          $this->iErroStatus = 0;
          $this->sErroMsg = "Inclusão não efetuada. Operação Abortada.<br>(Agua Leitura Cancela - $clAguaLeituraCancela->erro_msg)";
          return false;
        }
      
      }
    
    }
  
  }
  
  /**
   * Cancela uma exportacao
   * @param $iCodExportacao
   */
  public function cancelaExportacao($iCodExportacao) {
    
    require_once ("classes/db_aguacoletorexporta_classe.php");
    
    $clAguaColetorExporta = new cl_aguacoletorexporta ();
    
    $this->iCodExportacao = $iCodExportacao;
    
    $rsAguaColetorExporta = $clAguaColetorExporta->sql_record ( $clAguaColetorExporta->sql_query_file ( $this->iCodExportacao ) );
    
    if ($clAguaColetorExporta->numrows > 0) {
      
      $oAguaColetorExporta = db_utils::fieldsMemory ( $rsAguaColetorExporta, 0 );
      
      $clAguaColetorExporta->x49_sequencial = $oAguaColetorExporta->x49_sequencial;
      $clAguaColetorExporta->x49_aguacoletor = $oAguaColetorExporta->x49_aguacoletor;
      $clAguaColetorExporta->x49_instit = $oAguaColetorExporta->x49_instit;
      $clAguaColetorExporta->x49_anousu = $oAguaColetorExporta->x49_anousu;
      $clAguaColetorExporta->x49_mesusu = $oAguaColetorExporta->x49_mesusu;
      $clAguaColetorExporta->x49_situacao = 3;
      
      $this->iAno = $oAguaColetorExporta->x49_anousu;
      $this->iMes = $oAguaColetorExporta->x49_mesusu;
      
      $clAguaColetorExporta->alterar ( $clAguaColetorExporta->x49_sequencial );
      
      if ($clAguaColetorExporta->erro_status == "0") {
        $this->iErroStatus = 0;
        $this->sErroMsg = "Alteração não realizada. Operação Abortada.<br>(Agua Coletor Exporta - $clAguaColetorExporta->erro_msg)";
        return false;
      } else {
        $this->registraSituacao ( 3 );
      }
    
    }
  
  }
  
  /**
   * Registra um andamento da exportacao
   * @param $iSituacao - 1 exportada, 2 Importada, 3 Cancelada
   */
  public function registraSituacao($iSituacao = 3) {
    
    require_once ("classes/db_aguacoletorexportasituacao_classe.php");
    
    $clAguaColetorExportaSituacao = new cl_aguacoletorexportasituacao ();
    
    $clAguaColetorExportaSituacao->x48_aguacoletorexporta = $this->iCodExportacao;
    $clAguaColetorExportaSituacao->x48_usuario = $this->iUsuario;
    $clAguaColetorExportaSituacao->x48_data = $this->dData;
    $clAguaColetorExportaSituacao->x48_hora = $this->dHora;
    $clAguaColetorExportaSituacao->x48_motivo = $this->sMotivo;
    $clAguaColetorExportaSituacao->x48_situacao = $iSituacao;
    
    $clAguaColetorExportaSituacao->incluir ( null );
    
    if ($clAguaColetorExportaSituacao->erro_status == "0") {
      $this->iErroStatus = 1;
      $this->sErroMsg = "Inclusão não efetuada. Operação abortada.<br>(Agua Coletor Exporta Situacao - $clAguaColetorExportaSituacao->erro_msg)";
      return false;
    }
  }
  
  /**
   * Busca leituras que foram criadas pela exportacao
   */
  public function cancelaLeiturasExportadas() {
    
    require_once ("classes/db_aguacoletorexportadadosleitura_classe.php");
    
    $clAguaColetorExportaDadosLeitura = new cl_aguacoletorexportadadosleitura ();
    
    $sWhere = "x49_sequencial = $this->iCodExportacao and x49_anousu = $this->iAno and x49_mesusu = $this->iMes and x21_tipo = 2 and x21_status = 2";
    $sSqlAguaColetorExportaDadosLeitura = $clAguaColetorExportaDadosLeitura->sql_query ( null, "x51_agualeitura", null, $sWhere );
    $rsAguaColetorExportaDadosLeitura = $clAguaColetorExportaDadosLeitura->sql_record ( $sSqlAguaColetorExportaDadosLeitura );
    
    $this->sMotivo = "Leitura Cancelada Automaticamente pelo procedimento \"Cancelar Exportação de Dados Para o Coletor\"";
    
    for($i = 0; $i < $clAguaColetorExportaDadosLeitura->numrows; $i ++) {
      
      $oAguaColetorExportaDadosLeitura = db_utils::fieldsMemory ( $rsAguaColetorExportaDadosLeitura, $i );
      
      $this->mudaStatusLeitura ( $oAguaColetorExportaDadosLeitura->x51_agualeitura, 3 );
    
    }
  
  }
  
  /**
   * Busca leituras que foram exportadas para o coletor
   * @return unknown_type
   */
  public function ativaLeiturasExportadas() {
    
    require_once ("classes/db_aguacoletorexportadadosleitura_classe.php");
    
    $clAguaColetorExportaDadosLeitura = new cl_aguacoletorexportadadosleitura ();
    
    $sWhere = "x49_sequencial = $this->iCodExportacao and x21_status <> 3";
    $sSqlAguaColetorExportaDadosLeitura = $clAguaColetorExportaDadosLeitura->sql_query ( null, "x51_agualeitura", null, $sWhere );
    $rsAguaColetorExportaDadosLeitura = $clAguaColetorExportaDadosLeitura->sql_record ( $sSqlAguaColetorExportaDadosLeitura );
    
    for($i = 0; $i < $clAguaColetorExportaDadosLeitura->numrows; $i ++) {
      
      $oAguaColetorExportaDadosLeitura = db_utils::fieldsMemory ( $rsAguaColetorExportaDadosLeitura, $i );
      
      $this->mudaStatusLeitura ( $oAguaColetorExportaDadosLeitura->x51_agualeitura, 1 );
    
    }
  
  }
  
  /**
   * Cria classe atribuindo valores
   * @return unknown_type
   */
  public function __construct() {
    
    $this->iUsuario = db_getsession ( "DB_id_usuario" );
    $this->dData = date ( "d/m/Y" );
    $this->dHora = date ( "H:i" );
    $this->pagina_retorno = basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] );
  
  }
  
  /**
   * Motivo de um cancelamento
   * @param $sMotivo
   */
  public function setMotivo($sMotivo) {
    
    $this->sMotivo = $sMotivo;
  
  }

}

?>