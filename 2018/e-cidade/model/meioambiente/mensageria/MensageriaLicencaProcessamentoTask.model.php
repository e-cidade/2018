<?php

require_once modification("model/configuracao/Task.model.php");
require_once modification("interfaces/iTarefa.interface.php");
require_once modification("integracao_externa/mensageria/DBSeller/Mensageria/Library/Cliente.php");

use \DBseller\Mensageria\Library\Cliente as MensageriaCliente;

class MensageriaLicencaProcessamentoTask extends Task implements iTarefa {

  /**
   * Licencas que irao vencer
   *
   * @var Licenca[]
   */
  private $aLicencas = array();

  /**
   * Datas de vencimento para buscar acordos com data de terminio menor ou igual
   *
   * @var Array
   */
  private $aDataVencimento = array();

  /**
   * Codigo dos usuarios que serao notificados
   * - usado para verificar os usuarios que ja foram notificados
   *
   * @var Array
   */
  private $aCodigoMensageriaLicencaUsuario = array();

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
      $this->processarLicenca();
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
   * - $this->aCodigoMensageriaLicencaUsuario : usuarios para notificar
   * - $this->aDataVencimento                : datas para buscar acordos
   * - $this->aCodigoDepartamentoUsuario     : departamentos com seus usuarios
   *
   * @return bool
   */
  private function processarParametros() {

    /**
     * Busca os usuarios definidos para notificar quando acordo ira vencer, ordenando pelo dia
     */
    $oDaoMensageriaUsuario = db_utils::getDao('mensagerialicenca_db_usuarios');
    $sSqlUsuarios          = $oDaoMensageriaUsuario->sql_query_usuariosNotificar('am16_sequencial', 'am16_dias');
    $rsUsuarios            = db_query($sSqlUsuarios);
    $iTotalUsuarios        = pg_num_rows($rsUsuarios);

    if ($iTotalUsuarios == 0) {
      throw new Exception("Nenhum usuário para notificar.");
    }

    /**
     * Percorre os usuarios e define propriedades necessarias para buscar acordos
     */
    for ($iIndiceUsuario = 0; $iIndiceUsuario < $iTotalUsuarios; $iIndiceUsuario++) {

      /**
       * Codigo do usuario para notificar: mensageriaacordodb_usuario.ac52_sequencial
       */
      $iCodigoMensageriaLicencaUsuario = db_utils::fieldsMemory($rsUsuarios, $iIndiceUsuario)->am16_sequencial;

      /**
       * Codigo dos usuarios que serao notificados
       * - usado para verificar os usuarios que ja foram notificados
       * - mensageriaacordodb_usuario.ac52_sequencial
       */
      $this->aCodigoMensageriaLicencaUsuario[] = $iCodigoMensageriaLicencaUsuario;

      /**
       * Usuario para notificar
       * - mensageriaacordodb_usuario
       */
      $oMensageriaLicencaUsuario = MensageriaLicencaUsuarioRepository::getPorCodigo($iCodigoMensageriaLicencaUsuario);

      /**
       * Codigo do usuario do sistema
       * - db_usuarios.id_usuario
       */
      $iCodigoUsuario = $oMensageriaLicencaUsuario->getUsuario()->getCodigo();

      /**
       * Data de vencimento:
       * - Soma data atual com dias definidos na rotina de parametros de mensageria
       */
      $iDias = $oMensageriaLicencaUsuario->getDias();
      $this->aDataVencimento[] = date('Y-m-d', strtotime('+ ' . $iDias . ' days'));
    }

    return true;
  }

  /**
   * Processa acordos
   * - buscando os acordos com data de vencimento menor ou igual as datas da propridade $this->aDataVencimento
   * - define os acordos que terao os usuarios notificados
   *
   * @return bool
   */
  private function processarLicenca() {

    $sDataAtual = date('Y-m-d');
    $oDaoLicencaEmpreendimento = db_utils::getDao('licencaempreendimento');

    foreach ($this->aDataVencimento as $sDataVencimento) {

      $sWhere      = " am08_datavencimento between '$sDataAtual' and '$sDataVencimento'";
      $sSqlLicenca = $oDaoLicencaEmpreendimento->sql_query_licenca_parecer('am13_sequencial, am08_sequencial, am08_tipolicenca', $sWhere);
      $rsLicenca   = $oDaoLicencaEmpreendimento->sql_record($sSqlLicenca);

      $iTotalLincecas = pg_num_rows($rsLicenca);

      if ($iTotalLincecas == 0) {
        continue;
      }

      for ($iIndiceLicenca = 0; $iIndiceLicenca < $iTotalLincecas; $iIndiceLicenca++) {

        $oLicencaValida = db_utils::fieldsMemory($rsLicenca, $iIndiceLicenca);

        $iCodigoLicencaEmpreendimento = $oLicencaValida->am13_sequencial;

        $this->aLicencas[$iCodigoLicencaEmpreendimento] = LicencaEmpreendimentoRepository::getByCodigo($iCodigoLicencaEmpreendimento);
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
    $oMensageriaLicenca = new MensageriaLicenca();

    /**
     * Define o sistema para mensageria, pelo nome do municipio
     * - db_config.munic
     */
    $oInstituicao = new Instituicao();
    $oPrefeitura  = @$oInstituicao->getDadosPrefeitura();
    $sSistema     = 'e-cidade.' . strtolower($oPrefeitura->getMunicipio());

    foreach ($this->aLicencas as $oLicenca) {

      $iCodigoLicencaEmpreendimento = $oLicenca->getSequencial();

      if ($oLicenca->getParecerTecnico()->getTipoLicenca()->getSequencial() != 3) {

        $iCodigoLicencaEmpreendimento = $oLicenca->getParecerTecnico()->getCodigoLicencaAnterior();
        if (is_null($iCodigoLicencaEmpreendimento)) {
          $iCodigoLicencaEmpreendimento = $oLicenca->getSequencial();
        }
      }

      $oDataVencimento = new DBDate( $oLicenca->getParecerTecnico()->getDataVencimento() );

      $aVariaveisLicenca = array(

        '[nome_emp]'   => $oLicenca->getParecerTecnico()->getEmpreendimento()->getNome(),
        '[codigo_emp]' => $oLicenca->getParecerTecnico()->getEmpreendimento()->getSequencial(),
        '[numero]'     => $iCodigoLicencaEmpreendimento,
        '[tipo]'       => $oLicenca->getParecerTecnico()->getTipoLicenca()->getDescricao(),
        '[data]'       => $oDataVencimento->getDate( DBDate::DATA_PTBR ),
        '[processo]'   => $oLicenca->getParecerTecnico()->getProtProcesso()
      );


      $sAssuntoLicenca    = strtr($oMensageriaLicenca->getAssunto(), $aVariaveisLicenca);
      $sMensagemLicenca   = strtr($oMensageriaLicenca->getMensagem(), $aVariaveisLicenca);
      $oDBDateAtual       = new DBDate(date('Y-m-d'));
      $iDiasVencimento    = DBDate::calculaIntervaloEntreDatas(new DBDate($oLicenca->getParecerTecnico()->getDataVencimento()), $oDBDateAtual, 'd');
      $aDestinatarios     = array();
      $aUsuarioNotificado = array();

      foreach ($this->aCodigoMensageriaLicencaUsuario as $iCodigoMensageriaLicencaUsuario) {

        $oMensageriaLicencaUsuario = MensageriaLicencaUsuarioRepository::getPorCodigo($iCodigoMensageriaLicencaUsuario);
        $oUsuarioSistema           = $oMensageriaLicencaUsuario->getUsuario();

        /**
         * Dias para vencer maior que os dias configurados
         */
        if ($iDiasVencimento > $oMensageriaLicencaUsuario->getDias() ) {
          continue;
        }

        /**
         *  Salva acordo como ja notificado para usuario e dia
         *  mensagerialicencaprocessados
         */
        $this->salvarLicencaProcessado($iCodigoMensageriaLicencaUsuario, $oLicenca->getSequencial());

        /**
         * Usuario ja notificado com dia menor que o atual
         */
        if (in_array($oUsuarioSistema->getCodigo(), $aUsuarioNotificado)) {
          continue;
        }

        $aDestinatarios[]     = array('sLogin' => $oUsuarioSistema->getLogin(), 'sSistema' => $sSistema);
        $aUsuarioNotificado[] = $oUsuarioSistema->getCodigo();
      }

      /**
       * Licenca sem usuarios para notificar
       */
      if (empty($aDestinatarios)) {
        continue;
      }

      $sAssunto  = str_replace('[dias]', $iDiasVencimento, $sAssuntoLicenca);
      $sMensagem = str_replace('[dias]', $iDiasVencimento, $sMensagemLicenca);
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
  private function salvarLicencaProcessado($iCodigoMensageriaLicencaUsuario, $iLicenca) {

    $oDaoMensageriaLicencaProcessado = db_utils::getDao('mensagerialicencaprocessado');
    $oDaoMensageriaLicencaProcessado->am15_mensagerialicencadb_usuarios = $iCodigoMensageriaLicencaUsuario;
    $oDaoMensageriaLicencaProcessado->am15_licencaempreendimento        = $iLicenca;
    $oDaoMensageriaLicencaProcessado->incluir(null);

    if ($oDaoMensageriaLicencaProcessado->erro_status == 0) {
      throw new Exception("Erro ao salvar mensageria da linceça como já processada.". $oDaoMensageriaLicencaProcessado->erro_msg);
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
      'iTipo'          => MensageriaCliente::TIPO_NOTIFICACAO,
      'sAssunto'       => $sAssunto,
      'sConteudo'      => $sMensagem,
      'aDestinatarios' => $aDestinatarios
    );

    $lEnviado = MensageriaCliente::enviar($sSistema, $sSistema, $aMensagem);

    if (!$lEnviado) {
      throw new Exception("Erro ao enviar notificação para servidor de mensageria.");
    }

    return true;
  }

}
