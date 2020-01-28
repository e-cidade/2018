<?php

require_once modification("model/configuracao/Task.model.php");
require_once modification("interfaces/iTarefa.interface.php");
require_once modification("integracao_externa/mensageria/DBSeller/Mensageria/Library/Cliente.php");

use \DBseller\Mensageria\Library\Cliente as MensageriaCliente;

class MensageriaAcordoProcessamentoTask extends Task implements iTarefa {

  /**
   * Acordos que irao vencer
   *
   * @var Acordo[]
   */
  private $aAcordos = array();

  /**
   * Datas de vencimento para buscar acordos com data de terminio menor ou igual
   *
   * @var Array
   */
  private $aDataVencimento = array();

  /**
   * Codigo dos usuarios que serao notificados
   * - usado para verificar os usuarios que ja foram notificados
   * - mensageriaacordodb_usuario.ac52_sequencial
   *
   * @var Array
   */
  private $aCodigoMensageriaAcordoUsuario = array();

  /**
   * Codigo do departamento com os codigo dos usuarios que tem permissao nele
   * - conforme tabela: db_depusu
   *
   * @var Array
   */
  private $aCodigoDepartamentoUsuario = array();

  /**
   * Inicia Execucao da Tarefa
   *
   * @return void
   */
  public function iniciar() {

    parent::iniciar(); 

    try {

      /**
       * Variaveis necessarias para usar as bibliotecas padroes 
       */
      global $HTTP_SERVER_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS, $_SESSION, $conn;
      $HTTP_SERVER_VARS = $_SESSION;
      $HTTP_POST_VARS = $_POST;
      $HTTP_GET_VARS = $_GET;

      require_once modification("libs/db_conn.php");
      require_once modification("libs/db_stdlib.php");
      require_once modification("libs/db_utils.php");
      require_once "libs/db_autoload.php";
      require_once modification("dbforms/db_funcoes.php");

      /**
       * Conecta no banco com variaveis definidas no 'libs/db_conn.php'
       */
      if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
        throw new Exception('Erro ao conectar ao banco.');
      } 

      /**
       * Desativa log de alteracoes nas classes de dao
       */
      db_putsession('DB_desativar_account', true);

      db_inicio_transacao();

      $this->processarParametros();
      $this->processarAcordos();
      $this->processarNotificacoes(); 

      db_fim_transacao();

    } catch (Exception $oErro) {

      db_fim_transacao(true);
      $this->log("Erro na execução:\n{$oErro->getMessage()}");
    }

    parent::terminar(); 
  }

  public function cancelar() {}
  public function abortar() {}

  /**
   * Processa os parametros configurados para mensagaria e definindo propriedades:
   * - $this->aCodigoMensageriaAcordoUsuario : usuarios para notificar          
   * - $this->aDataVencimento                : datas para buscar acordos        
   * - $this->aCodigoDepartamentoUsuario     : departamentos com seus usuarios  
   *
   * @return bool
   */
  private function processarParametros() {

    /**
     * Busca os usuarios definidos para notificar quando acordo ira vencer, ordenando pelo dia
     */
    $oDaoMensageriaUsuario = new cl_mensageriaacordodb_usuario();
    $sSqlUsuarios = $oDaoMensageriaUsuario->sql_query_usuariosNotificar('ac52_sequencial', 'ac52_dias');
    
    $rsUsuarios = db_query($sSqlUsuarios);
    $iTotalUsuarios = pg_num_rows($rsUsuarios);

    if ($iTotalUsuarios == 0) {
      throw new Exception("Nenhum usuário para notificar.");
    }

    /**
     * Codigo do departamento com seus usuarios
     */
    $aCodigoDepartamentoUsuario = array(); 

    /**
     * Percorre os usuarios e define propriedades necessarias para buscar acordos
     */
    for ($iIndiceUsuario = 0; $iIndiceUsuario < $iTotalUsuarios; $iIndiceUsuario++) {

      /**
       * Codigo do usuario para notificar: mensageriaacordodb_usuario.ac52_sequencial
       */
      $iCodigoMensageriaAcordoUsuario = db_utils::fieldsMemory($rsUsuarios, $iIndiceUsuario)->ac52_sequencial;

      /**
       * Codigo dos usuarios que serao notificados
       * - usado para verificar os usuarios que ja foram notificados
       * - mensageriaacordodb_usuario.ac52_sequencial 
       */
      $this->aCodigoMensageriaAcordoUsuario[] = $iCodigoMensageriaAcordoUsuario;

      /**
       * Usuario para notificar
       * - mensageriaacordodb_usuario
       */
      $oMensageriaAcordoUsuario = MensageriaAcordoUsuarioRepository::getPorCodigo($iCodigoMensageriaAcordoUsuario);

      /**
       * Codigo do usuario do sistema
       * - db_usuarios.id_usuario
       */
      $iCodigoUsuario = $oMensageriaAcordoUsuario->getUsuario()->getCodigo();

      /**
       * Data de vencimento:
       * - Soma data atual com dias definidos na rotina de parametros de mensageria
       */
      $iDias = $oMensageriaAcordoUsuario->getDias();
      $this->aDataVencimento[] = date('Y-m-d', strtotime('+ ' . $iDias . ' days')); 

      /**
       * Departamentos que o usuario tem permisao
       * @var DBDepartamento[]
       */
      $aDepartamentos = $oMensageriaAcordoUsuario->getUsuario()->getDepartamentos();

      /**
       * Percorre os departamentos e guarda os usuarios que tem permisao nele
       * - $this->aCodigoDepartamentoUsuario
       */
      foreach ($aDepartamentos as $oDepartamento) {

        if (!isset($aCodigoDepartamentoUsuario[$oDepartamento->getCodigo()])) {
          $aCodigoDepartamentoUsuario[$oDepartamento->getCodigo()] = array();
        }
       
        if (!in_array($iCodigoUsuario, $aCodigoDepartamentoUsuario[$oDepartamento->getCodigo()])) {
          $aCodigoDepartamentoUsuario[$oDepartamento->getCodigo()][] = $iCodigoUsuario;
        }
      }
    }

    $this->aCodigoDepartamentoUsuario = $aCodigoDepartamentoUsuario;
    return true;
  }

  /**
   * Processa acordos 
   * - buscando os acordos com data de vencimento menor ou igual as datas da propridade $this->aDataVencimento
   * - define os acordos que terao os usuarios notificados
   *
   * @return bool
   */
  private function processarAcordos() {

    $sDataAtual = date('Y-m-d');
    $oDaoAcordo = new cl_acordo();
    $sCodigoDepartamentos = implode(', ', array_keys($this->aCodigoDepartamentoUsuario));

    foreach ($this->aDataVencimento as $sDataVencimento) {
    
      $sWhereAcordo  = "ac16_coddepto in($sCodigoDepartamentos) ";
      $sWhereAcordo .= "and ac16_datafim >= '$sDataAtual' and ac16_datafim <= '$sDataVencimento'";
      $sSqlAcordos = $oDaoAcordo->sql_query_file(null, 'ac16_sequencial', 'ac16_sequencial', $sWhereAcordo);
      $rsAcordos = db_query($sSqlAcordos);

      if (!$rsAcordos) {
        throw new Exception("Erro ao buscar acordos a vencer.");
      }

      $iTotalAcordos = pg_num_rows($rsAcordos);

      if ($iTotalAcordos == 0) {
        continue;
      }

      for ($iIndiceAcodo = 0; $iIndiceAcodo < $iTotalAcordos; $iIndiceAcodo++) {

        $iAcordo = db_utils::fieldsMemory($rsAcordos, $iIndiceAcodo)->ac16_sequencial;
        $this->aAcordos[$iAcordo] = AcordoRepository::getByCodigo($iAcordo);
      } 
    }

    return true;
  }

  /**
   * Processa notificacoes
   * - Busca propriedades necessarias para criar mensagem  
   *   e no final usa metodo $this->enviarNotificacao()
   *
   * @return bool
   */
  private function processarNotificacoes() {

    /**
     * Objeto assunto e mensagem padrao
     */
    $oMensageriaAcordo = new MensageriaAcordo();

    /**
     * Define o sistema para mensageria, pelo nome do municipio
     * - db_config.munic
     */
    $oInstituicao = new Instituicao();
    $oPrefeitura = @$oInstituicao->getDadosPrefeitura();
    $sSistema = 'e-cidade.' . strtolower($oPrefeitura->getMunicipio());

    foreach ($this->aAcordos as $oAcordo) {

      $aVariaveisAcordo = array(
        '[numero]' => $oAcordo->getNumeroAcordo(),
        '[ano]' => $oAcordo->getAno(),
        '[data_inicial]' => $oAcordo->getDataInicial(),
        '[data_final]' => $oAcordo->getDataFinal(),
      );

      $sAssuntoAcordo = strtr($oMensageriaAcordo->getAssunto(), $aVariaveisAcordo);
      $sMensagemAcordo = strtr($oMensageriaAcordo->getMensagem(), $aVariaveisAcordo);
      $oDBDateAtual = new DBDate(date('Y-m-d'));
      $iDiasVencimento = DBDate::calculaIntervaloEntreDatas(new DBDate($oAcordo->getDataFinal()), $oDBDateAtual, 'd');
      $aDestinatarios = array();
      $aUsuarioNotificado = array();

      foreach ($this->aCodigoMensageriaAcordoUsuario as $iCodigoMensageriaAcordoUsuario) {

        $oMensageriaAcordoUsuario = MensageriaAcordoUsuarioRepository::getPorCodigo($iCodigoMensageriaAcordoUsuario);
        $oUsuarioSistema = $oMensageriaAcordoUsuario->getUsuario();

        /**
         * Dias para vencer maior que os dias configurados
         */
        if ($iDiasVencimento > $oMensageriaAcordoUsuario->getDias() ) {
          continue;
        }

        /**
         * Verifica se o usuario tem permisao no departamento do acordo
         */
        if (!empty($this->aCodigoDepartamentoUsuario[$oAcordo->getDepartamento()])) {

          $iUsuario = $oUsuarioSistema->getCodigo();
          if (!in_array($iUsuario, $this->aCodigoDepartamentoUsuario[$oAcordo->getDepartamento()])) {
            continue;
          }
        }

        /**
         * Salva acordo como ja notificado para usuario e dia
         * mensageriaacordoprocessados
         */
        $this->salvarAcordoProcessado($iCodigoMensageriaAcordoUsuario, $oAcordo->getCodigo()); 
        
        /**
         * Usuario ja notificado com dia menor que o atual 
         */
        if (in_array($oUsuarioSistema->getCodigo(), $aUsuarioNotificado)) {
          continue;
        }

        $aDestinatarios[] = array('sLogin' => $oUsuarioSistema->getLogin(), 'sSistema' => $sSistema);
        $aUsuarioNotificado[] = $oUsuarioSistema->getCodigo();
      }

      /**
       * Acordo sem usuarios para notificar
       */
      if (empty($aDestinatarios)) {
        continue;
      }

      $sAssunto = str_replace('[dias]', $iDiasVencimento, $sAssuntoAcordo);
      $sMensagem = str_replace('[dias]', $iDiasVencimento, $sMensagemAcordo);
      $this->enviarNotificacao($sAssunto, $sMensagem, $sSistema, $aDestinatarios); 
    }
  
    return true;
  }

  /**
   * Salva acordo como ja notificado para usuario e dias
   * mensageriaacordoprocessados
   * 
   * @return bool
   */
  private function salvarAcordoProcessado($iCodigoMensageriaAcordoUsuario, $iAcordo) {

    $oDaomensageriaacordoprocessados = new cl_mensageriaacordoprocessados();
    $oDaomensageriaacordoprocessados->ac53_mensageriaacordodb_usuarios = $iCodigoMensageriaAcordoUsuario;
    $oDaomensageriaacordoprocessados->ac53_acordo = $iAcordo;
    $oDaomensageriaacordoprocessados->incluir(null);

    if ($oDaomensageriaacordoprocessados->erro_status == 0) {
      throw new Exception("Erro ao salvar mensageria do acordo como já processada.");
    }

    return true;
  }

  /**
   * Envia notifiacao para o servidor
   *
   * @param string $sAssunto
   * @param string $sMensagem
   * @param string $sSistema
   * @param array $aDestinatarios
   * @return bool
   */
  private function enviarNotificacao($sAssunto, $sMensagem, $sSistema, Array $aDestinatarios) {

    $aMensagem = array(
      'iTipo' => MensageriaCliente::TIPO_NOTIFICACAO,
      'sAssunto' => $sAssunto,
      'sConteudo' => $sMensagem,
      'aDestinatarios' => $aDestinatarios
    );

    $lEnviado = MensageriaCliente::enviar($sSistema, $sSistema, $aMensagem);  

    if (!$lEnviado) {
      throw new Exception("Erro ao enviar notificação para servidor de mensageria.");
    }

    return true;
  }

} 
