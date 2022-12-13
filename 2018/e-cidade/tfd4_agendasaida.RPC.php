<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

define("MENSAGEM_AGENDASAIDA", "saude.tfd.tfd4_agendasaidaRPC.");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

$oDaoAgendaSaida       = new cl_tfd_agendasaida();
$oDaoPassageiroVeiculo = new cl_tfd_passageiroveiculo();
$oDaoVeiculoDestino    = new cl_tfd_veiculodestino();
$oDaoPrestadora        = new cl_tfd_prestadoracentralagend();

try {

  switch($oParam->sExecucao) {

    /**
     * Busca os dados do pedido referentes ao agendamento de saída
     */
    case 'getDadosPedido':

      if ( !isset($oParam->iPedido) || empty($oParam->iPedido) ) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "informe_pedido") );
      }

      $sCamposPedido  = " distinct tf01_i_cgsund, z01_v_nome as nome_paciente, tf25_i_destino, tf03_c_descr, ";
      $sCamposPedido .= " cgmprest.z01_nome as nome_prestadora, tf16_d_dataagendamento, tf16_c_horaagendamento,";
      $sCamposPedido .= " tf17_d_datasaida, tf17_c_horasaida, tf17_c_localsaida, tf18_d_dataretorno, tf18_c_horaretorno";
      $sCamposPedido .= ", tf18_i_veiculo, ve01_placa, tf18_i_motorista, cgm_motorista.z01_nome as nome_motorista";
      $sCamposPedido .= ", tf10_i_centralagend, tf17_i_codigo, ve01_quantcapacidad, tf19_i_fica, tf16_i_codigo";
      $sCamposPedido .= ", tf17_tiposaida, tf10_i_prestadora, tf37_valor";

      $sWhere = " tf01_i_codigo = {$oParam->iPedido} ";
      if ( !empty($oParam->iCgs) ) {
        $sWhere .= " and tf01_i_cgsund = {$oParam->iCgs} ";
      }

      $oDaoPedido     = new cl_tfd_pedidotfd();
      $sSqlPedido     = $oDaoPedido->sql_query_pedido_saida( null, $sCamposPedido, "", $sWhere );
      $rsPedido       = db_query( $sSqlPedido );

      if ( !$rsPedido ) {
        throw new DBException( _M( MENSAGEM_AGENDASAIDA . "nao_possivel_buscar_dados_pedido") );
      }

      if ( pg_num_rows($rsPedido) == 0 ) {
        throw new DBException( _M( MENSAGEM_AGENDASAIDA . "nenhum_pedido_encontrado") );
      }

      $oDadosPedido      = db_utils::fieldsMemory( $rsPedido, 0);

      $oPedido                      = new stdClass();
      $oPedido->iCgs                = $oDadosPedido->tf01_i_cgsund;
      $oPedido->sCgs                = urlencode($oDadosPedido->nome_paciente);
      $oPedido->iCentralAgendamento = $oDadosPedido->tf10_i_centralagend;
      $oPedido->iPrestadora         = $oDadosPedido->tf10_i_prestadora;
      $oPedido->sPrestadora         = urlencode($oDadosPedido->nome_prestadora);
      $oPedido->iDestino            = $oDadosPedido->tf25_i_destino;
      $oPedido->sDestino            = urlencode($oDadosPedido->tf03_c_descr);
      $oPedido->iCodigoAgenda       = $oDadosPedido->tf17_i_codigo;

      $sDataAgendamento = '';
      if ( !empty($oDadosPedido->tf16_d_dataagendamento) ) {

        $oAgendamento     = new DBDate($oDadosPedido->tf16_d_dataagendamento);
        $sDataAgendamento = $oAgendamento->convertTo(DBDate::DATA_PTBR);
      }

      $oPedido->dtAgendamento    = $sDataAgendamento;
      $oPedido->sHoraAgendamento = $oDadosPedido->tf16_c_horaagendamento;

      $sDataSaida = '';
      if ( !empty($oDadosPedido->tf17_d_datasaida) ) {

        $oDataSaida = new DBDate($oDadosPedido->tf17_d_datasaida);
        $sDataSaida = $oDataSaida->convertTo(DBDate::DATA_PTBR);
      }

      $oPedido->dtSaida          = $sDataSaida;
      $oPedido->sHoraSaida       = $oDadosPedido->tf17_c_horasaida;
      $oPedido->sLocalSaida      = urlencode($oDadosPedido->tf17_c_localsaida);

      $sDataRetorno = '';

      if ( !empty($oDadosPedido->tf18_d_dataretorno) ) {

        $oDataRetorno = new DBDate($oDadosPedido->tf18_d_dataretorno);
        $sDataRetorno = $oDataRetorno->convertTo(DBDate::DATA_PTBR);
      }

      $oPedido->dtRetorno        = $sDataRetorno;
      $oPedido->sHoraRetorno     = $oDadosPedido->tf18_c_horaretorno;
      $oPedido->iVeiculo         = $oDadosPedido->tf18_i_veiculo;
      $oPedido->iLotacaoVeiculo  = $oDadosPedido->ve01_quantcapacidad;
      $oPedido->sPlaca           = urlencode($oDadosPedido->ve01_placa);
      $oPedido->iMotorista       = $oDadosPedido->tf18_i_motorista;
      $oPedido->sMotorista       = urlencode($oDadosPedido->nome_motorista);
      $oPedido->iFica            = $oDadosPedido->tf19_i_fica;

      // PK da tabela tfd_agendamentoprestadora
      $oPedido->iCodigoAgendamentoPrestadora = $oDadosPedido->tf16_i_codigo;
      $oPedido->iTipoSaida                   = $oDadosPedido->tf17_tiposaida;
      $oPedido->sValorUnitario               = $oDadosPedido->tf37_valor;

      if( $oPedido->iTipoSaida == 2 ) {

        $sCamposAgendaSaidaPassagemDestino  = "tf38_valorunitario, tf38_cgs, tf38_fica";
        $sWhereAgendaSaidaPassagemDestino   = "     tf38_agendasaida = {$oDadosPedido->tf17_i_codigo}";
        $sWhereAgendaSaidaPassagemDestino  .= " AND tf38_cgs = {$oParam->iCgs}";

        $oDaoAgendaSaidaPassagemDestino = new cl_agendasaidapassagemdestino();
        $sSqlAgendaSaidaPassagemDestino = $oDaoAgendaSaidaPassagemDestino->sql_query_file(
                                                                                           null,
                                                                                           $sCamposAgendaSaidaPassagemDestino,
                                                                                           null,
                                                                                           $sWhereAgendaSaidaPassagemDestino
                                                                                         );
        $rsAgendaSaidaPassagemDestino = db_query( $sSqlAgendaSaidaPassagemDestino );

        if( !is_resource( $rsAgendaSaidaPassagemDestino ) ) {

          $oErro        = new stdClass();
          $oErro->sErro = pg_last_error();

          throw new DBException( _M( MENSAGEM_AGENDASAIDA . "erro_buscar_agenda_saida", $oErro ) );
        }

        if( pg_num_rows( $rsAgendaSaidaPassagemDestino ) > 0 ) {

          $oDadosAgendaSaida       = db_utils::fieldsMemory( $rsAgendaSaidaPassagemDestino, 0 );
          $oPedido->iFica          = $oDadosAgendaSaida->tf38_fica == 't' ? 1 : 2;
          $oPedido->sValorUnitario = $oDadosAgendaSaida->tf38_valorunitario;
        }
      }

      $oRetorno->oPedido = $oPedido;

      break;

    /**
     * Retorna se o agendamento está utilizando a Grade de Horários ou não.
     */
    case 'getParametros':

      $oDaoTfdParametros = new cl_tfd_parametros();
      $sSqlTfdParametros = $oDaoTfdParametros->sql_query_file( null, "tf11_i_utilizagradehorario", null, null );
      $rsTfdParametros   = db_query( $sSqlTfdParametros );

      if ( !$rsTfdParametros ) {
        throw new DBException(  _M( MENSAGEM_AGENDASAIDA . "nao_possivel_buscar_parametros") );
      }

      if ( pg_num_rows($rsTfdParametros) == 0 ) {
        throw new DBException(  _M( MENSAGEM_AGENDASAIDA . "nenhum_parametro_encontrado") );
      }

      $oRetorno->lUtilizaGradeHorario = db_utils::fieldsMemory( $rsTfdParametros, 0)->tf11_i_utilizagradehorario == 1;

      break;

    /**
     * Retorna os horários de acordo com o configurado na Grade de Horários, verificando a data e o destino informado
     */
    case 'getHorasGradeHorario':

      if ( !isset($oParam->dtSaida) || empty($oParam->dtSaida) ) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "informe_data_saida") );
      }

      if ( !isset($oParam->iDestino) || empty($oParam->iDestino) ) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "informe_destino") );
      }

      $oDataSaida = new DBDate( $oParam->dtSaida );
      $sDataSaida = $oDataSaida->convertTo(DBDate::DATA_EN);

      $oDaoGradeHorarios   = new cl_tfd_gradehorarios();


      $sWhereGradeHorarios  = " tf02_d_validadeini <= '{$sDataSaida}' and tf02_i_destino = {$oParam->iDestino}";
      $sWhereGradeHorarios .= " and case when tf02_d_validadefim is not null then tf02_d_validadefim >= '2014-11-05' else true end ";
      $sWhereGradeHorarios .= " and tf02_i_destino = {$oParam->iDestino}";

      $sCampos             = "distinct tf02_c_horario";
      $sSqlGradeHorarios   = $oDaoGradeHorarios->sql_query_file(null, $sCampos, "tf02_c_horario", $sWhereGradeHorarios);
      $rsGradeHorarios     = db_query( $sSqlGradeHorarios );

      if ( !$rsGradeHorarios ) {
        throw new DBException(  _M( MENSAGEM_AGENDASAIDA . "nao_possivel_buscar_horarios") );
      }

      $oRetorno->aHorarios = array();
      $iLinhas             = pg_num_rows($rsGradeHorarios);

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {
        $oRetorno->aHorarios[] = db_utils::fieldsMemory( $rsGradeHorarios, $iContador )->tf02_c_horario;
      }

      break;

    /**
     * Busca os acompanhantes vinculados ao pedido do paciente
     */
    case 'getAcompanhantes':

      if ( !isset($oParam->iPedido) || empty($oParam->iPedido) ) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "informe_pedido") );
      }

      $oDaoAcompanhantes   = new cl_tfd_acompanhantes();
      $sWhereAcompanhantes = "tf13_i_pedidotfd = {$oParam->iPedido}";

      $sCamposAcompanhantes  = " distinct tf13_i_cgsund, z01_v_nome, ";
      $sCamposAcompanhantes .= " case when tf19_i_codigo is not null then true else false end as vinculado_carro, ";
      $sCamposAcompanhantes .= " case when tf38_sequencial is not null then true else false end as possui_passagem, ";
      $sCamposAcompanhantes .= " case when tf38_fica is true or tf19_i_fica = 1 then true else false end as acompanhante_fica ";

      $sSqlAcompanhantes = $oDaoAcompanhantes->sql_query_acompanhantes_cgs(
                                                                            null,
                                                                            $sCamposAcompanhantes,
                                                                            null,
                                                                            $sWhereAcompanhantes
                                                                          );

      $rsAcompanhantes = db_query( $sSqlAcompanhantes );

      if ( !$rsAcompanhantes ) {
        throw new DBException(  _M( MENSAGEM_AGENDASAIDA . "nao_possivel_buscar_acompanhantes") );
      }

      $oRetorno->aAcompanhantes = array();
      $iLinhas                  = pg_num_rows($rsAcompanhantes);

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

        $oDadosAcompanhante = db_utils::fieldsMemory( $rsAcompanhantes, $iContador );

        $oAcompanhante                  = new stdClass();
        $oAcompanhante->iAcompanhante   = $oDadosAcompanhante->tf13_i_cgsund;
        $oAcompanhante->sAcompanhante   = urlencode($oDadosAcompanhante->z01_v_nome);
        $oAcompanhante->lVinculadoCarro = $oDadosAcompanhante->vinculado_carro == 't' ? true : false;
        $oAcompanhante->lPossuiPassagem = $oDadosAcompanhante->possui_passagem == 't' ? true : false;
        $oAcompanhante->iFica           = $oDadosAcompanhante->acompanhante_fica == 't' ? 1 : 2;

        $oRetorno->aAcompanhantes[] = $oAcompanhante;
      }

      break;
    case 'getVagasVeiculo':


      if (    empty($oParam->dtSaida)
           && empty($oParam->horaSaida)
           && empty($oParam->iVeiculo)
           && empty($oParam->iDestino) ) {

        throw new Exception(_M( MENSAGEM_AGENDASAIDA . "parametros_sao_obrigatorios"));
      }

      $oDtSaida   = new DBDate($oParam->dtSaida);


      $aWhere   = array();
      $aWhere[] = " tf18_d_datasaida   = '" . $oDtSaida->convertTo(DBDate::DATA_EN) . "' ";
      $aWhere[] = " tf18_c_horasaida   = '{$oParam->horaSaida}' ";
      $aWhere[] = " tf18_i_veiculo     = {$oParam->iVeiculo} ";
      $aWhere[] = " tf19_i_valido      = 1 ";

      if( isset( $oParam->dtRetorno ) && !empty( $oParam->dtRetorno ) ) {

        $oDtRetorno = new DBDate($oParam->dtRetorno);
        $aWhere[] = " tf18_d_dataretorno = '" . $oDtRetorno->convertTo(DBDate::DATA_EN) . "' ";
      }

      if( isset( $oParam->horaRetorno ) && !empty( $oParam->horaRetorno ) ) {
        $aWhere[] = " tf18_c_horaretorno = '{$oParam->horaRetorno}' ";
      }

      $sWhere   = implode(" and ", $aWhere);
      $sCampos  = " max(tf18_i_codigo) as veiculo_destino, tf19_i_cgsund, tf19_i_pedidotfd, tf19_i_tipopassageiro, tf19_i_fica";
      $sGroupBy = " group by tf19_i_cgsund, tf19_i_pedidotfd, tf19_i_tipopassageiro, tf19_i_fica ";

      $oDaoPassageiroVeiculo = new cl_tfd_passageiroveiculo();
      $sSqlPassageiros       = $oDaoPassageiroVeiculo->sql_query_passageiro_veiculo(null, $sCampos, null, $sWhere . $sGroupBy);
      $rsPassageiros         = db_query($sSqlPassageiros);

      $oVagas = new stdClass();
      $oVagas->iPassageiros   = 0;
      $oVagas->iAcompanhantes = 0;
      $oVagas->iTotal         = 0;

      if ($rsPassageiros && pg_num_rows($rsPassageiros) > 0) {

        $iLinhas = pg_num_rows($rsPassageiros);
        for ($i = 0; $i < $iLinhas; $i++) {

          $oDados          = db_utils::fieldsMemory($rsPassageiros, $i);
          $oVagas->iTotal += 1;

          if ($oDados->tf19_i_tipopassageiro == 1) {
            $oVagas->iPassageiros += 1;
          } else {
            $oVagas->iAcompanhantes += 1;
          }
        }

      }
      $oRetorno->oVagas = $oVagas;

      break;

    case 'removerAgendamento':

      if (empty($oParam->iPedidoTFD)) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "informe_pedido") );
      }
      db_inicio_transacao();

      removePassageirosVeiculo($oParam->iPedidoTFD);
      removeAgendaSaida($oParam->iPedidoTFD);

      $oRetorno->sMensagem = urlencode( _M( MENSAGEM_AGENDASAIDA . "agenda_removida_com_sucesso") );
      db_fim_transacao(false);

      break;

    case 'salvar' :

      /* Parâmetros recebidos
          $oParam->iCodigoAgenda
          $oParam->iPedidoTFD
          $oParam->iCgsPaciente
          $oParam->iCodigoPrestadora
          $oParam->iCodigoCentral
          $oParam->iVinculoCentralPrestadora
          $oParam->iDestinoPrestadora
          $oParam->dtAgendamentoPrestadora
          $oParam->horaAgendamentoPrestadora
          $oParam->dtSaida
          $oParam->horaSaida
          $oParam->sLocalSaida
          $oParam->dtRetorno
          $oParam->horaRetorno
          $oParam->iCodigoVeiculo
          $oParam->iCodigoMotorista
          $oParam->iCodigoAgendamentoPrestadora
          $oParam->iTipoSaida
          $oParam->aPassageiros
      */

      if( empty( $oParam->dtSaida ) ) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "informe_data_saida") );
      }

      if( empty( $oParam->horaSaida ) ) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "hora_saida_nao_informada") );
      }

      if( empty( $oParam->dtAgendamentoPrestadora ) ) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "data_agendamento_nao_informada") );
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGEM_AGENDASAIDA . "agenda_realizada_com_sucesso") );
      $oDtSaida            = new DBDate($oParam->dtSaida);
      $oDtAgendamento      = new DBDate($oParam->dtAgendamentoPrestadora);

      /*aqui vai uma validacao para o plugin o Plugin SMSTFD*/

      db_inicio_transacao();

      removePassageirosVeiculo($oParam->iPedidoTFD);
      removeAgendaSaida($oParam->iPedidoTFD);
      alterarDadosPrestadora($oParam->iCodigoAgendamentoPrestadora, $oParam->iVinculoCentralPrestadora, $oDtAgendamento,
                             $oParam->horaAgendamentoPrestadora);

      /**
       * Inclui Agenda novamente
       */
      $oDaoAgendaSaida->tf17_i_codigo      = null;
      $oDaoAgendaSaida->tf17_i_pedidotfd   = $oParam->iPedidoTFD;
      $oDaoAgendaSaida->tf17_d_datasaida   = $oDtSaida->convertTo(DBDate::DATA_EN);
      $oDaoAgendaSaida->tf17_c_horasaida   = $oParam->horaSaida;
      $oDaoAgendaSaida->tf17_c_localsaida  = db_stdClass::normalizeStringJsonEscapeString($oParam->sLocalSaida);
      $oDaoAgendaSaida->tf17_i_login       = db_getsession('DB_id_usuario');
      $oDaoAgendaSaida->tf17_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
      $oDaoAgendaSaida->tf17_c_horasistema = date('H:i');
      $oDaoAgendaSaida->tf17_tiposaida     = $oParam->iTipoSaida;
      $oDaoAgendaSaida->incluir(null);

      if ($oDaoAgendaSaida->erro_status == 0) {

        $oErroMsg           = new stdClass();
        $oErroMsg->sMsgErro = utf8_encode($oDaoAgendaSaida->erro_msg);
        throw new Exception(_M( MENSAGEM_AGENDASAIDA . "erro_incluir_agenda", $oErroMsg));
      }

      $oParam->iCodigoAgenda   = $oDaoAgendaSaida->tf17_i_codigo;
      $oRetorno->iCodigoAgenda = $oDaoAgendaSaida->tf17_i_codigo;

      if( $oParam->iTipoSaida == 1 ) {

        if( !empty( $oParam->iCodigoVeiculo ) && empty( $oParam->dtRetorno ) ) {
          throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "data_retorno_nao_informada") );
        }

        if( !empty( $oParam->iCodigoVeiculo ) && empty( $oParam->horaRetorno ) ) {
          throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "hora_retorno_nao_informada") );
        }

        if( !empty( $oParam->iCodigoVeiculo ) ) {
          salvarPassageiroVeiculo( $oParam );
        }
      }

      if( $oParam->iTipoSaida == 2 ) {
        salvarPassagens( $oParam );
      }

      db_fim_transacao(false);

      /*aqui vai o Plugin SMSTFD*/

      break;

    case "removerPassageirosVeiculo":

      if (empty($oParam->iPedidoTFD)) {
        throw new ParameterException( _M( MENSAGEM_AGENDASAIDA . "informe_pedido") );
      }
      db_inicio_transacao();

      removePassageirosVeiculo($oParam->iPedidoTFD);
      $oRetorno->sMensagem = urlencode( _M( MENSAGEM_AGENDASAIDA . "passageiros_removidos_com_sucesso") );
      db_fim_transacao(false);

      break;
  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

/**
 * Altera os dados de agendamento com a prestadora
 *
 * @param        $iAgendamentoPrestadora
 * @param        $iCentralPrestadora
 * @param DBDate $oDtAgendamento
 * @param        $sHora
 * @return bool
 * @throws Exception
 * @throws ParameterException
 */
function alterarDadosPrestadora($iAgendamentoPrestadora, $iCentralPrestadora, DBDate $oDtAgendamento, $sHora) {

  $oDaoPrestadora                           = new cl_tfd_agendamentoprestadora();
  $oDaoPrestadora->tf16_i_codigo            = $iAgendamentoPrestadora;
  $oDaoPrestadora->tf16_i_prestcentralagend = $iCentralPrestadora;
  $oDaoPrestadora->tf16_d_dataagendamento   = $oDtAgendamento->convertTo(DBDate::DATA_EN);
  $oDaoPrestadora->tf16_c_horaagendamento   = $sHora;
  $oDaoPrestadora->alterar($iAgendamentoPrestadora);

  if ( $oDaoPrestadora->erro_status == 0 ) {

    $oErroMsg           = new stdClass();
    $oErroMsg->sMsgErro = utf8_encode($oDaoPrestadora->erro_msg);
    throw new Exception(_M( MENSAGEM_AGENDASAIDA . "erro_atualizar_prestadora", $oErroMsg));
  }

  return true;
}

/**
 * Remove os passageiros de um pedido do veículo
 * Pode remover um ou mais passageiros
 * Se não sobrar passageiros no veículo, remove a agenda do veículo (tfd_veiculodestino)
 *
 * @throws Exception
 *
 * @param  integer $iPedido      Obrigatório - Código do pedido tfd
 * @param  array   $aPassageiros Opcional - lista de cgs
 * @return boolean
 */
function removePassageirosVeiculo($iPedido, $aPassageiros = array()) {

  $sWhereRemoverPassageiro = " tf19_i_pedidotfd = {$iPedido}";
  if ( is_array($aPassageiros) &&  count($aPassageiros) > 0) {
    $sWhereRemoverPassageiro .= " tf19_i_cgsund in (". implode(", ", $aPassageiros) .") ";
  }

  $oDaoPassageiroVeiculo = new cl_tfd_passageiroveiculo();
  $sSqlPassageiros       = $oDaoPassageiroVeiculo->sql_query_file(null, "tf19_i_veiculodestino", null, $sWhereRemoverPassageiro);
  $rsPassageiroVeiculo   = db_query($sSqlPassageiros);

  $iVeiculoDestino = null;
  if ( $rsPassageiroVeiculo && pg_num_rows($rsPassageiroVeiculo) > 0 ) {
    $iVeiculoDestino = db_utils::fieldsMemory($rsPassageiroVeiculo, 0)->tf19_i_veiculodestino;
  }

  $oDaoPassageiroVeiculo->excluir(null, $sWhereRemoverPassageiro);
  if ( $oDaoPassageiroVeiculo->erro_status == 0 ) {

    $oErroMsg           = new stdClass();
    $oErroMsg->sMsgErro = utf8_encode($oDaoPassageiroVeiculo->erro_msg);
    throw new Exception(_M( MENSAGEM_AGENDASAIDA . "erro_passageiros_veiculo", $oErroMsg));
  }

  /**
   * Se não encontrar mais nenhum passageiro viculado ao carro, remove a agenda do carro
   */
  if ( !empty($iVeiculoDestino) ) {

    $sWhereValida      = "tf19_i_veiculodestino = {$iVeiculoDestino}";
    $sSqlTemPassageiro = $oDaoPassageiroVeiculo->sql_query_file(null, "1", null, $sWhereValida);
    $rsTemPassageiro   = db_query($sSqlTemPassageiro);
    if ($rsTemPassageiro && pg_num_rows($rsTemPassageiro) == 0) {
      removeAgendaVeiculo( $iVeiculoDestino );
    }
  }

  return true;
}

/**
 * Remove a agenda de um veículo (tfd_veiculodestino)
 *
 * @throws Exception
 *
 * @param  integer $iVeiculoDestino
 * @return boolean
 */
function removeAgendaVeiculo( $iVeiculoDestino ) {

  $oDaoVeiculoDestino = new cl_tfd_veiculodestino();
  $oDaoVeiculoDestino->excluir($iVeiculoDestino);

  if ( $oDaoVeiculoDestino->erro_status == 0 ) {

    $oErroMsg           = new stdClass();
    $oErroMsg->sMsgErro = utf8_encode($oDaoVeiculoDestino->erro_msg);
    throw new Exception(_M( MENSAGEM_AGENDASAIDA . "erro_remover_agenda_veiculo", $oErroMsg));
  }

  return true;
}

/**
 * Remove a agenda do paciente e o vínculo com passagemdestino, caso exista
 *
 * @param null|integer $iPedido
 * @throws DBException
 * @throws Exception
 */
function removeAgendaSaida($iPedido = null) {

  if ( empty($iPedido) ) {
    throw new Exception(_M( MENSAGEM_AGENDASAIDA . "informe_pedido"));
  }

  $oDaoAgendaSaida = new cl_tfd_agendasaida();
  $sSqlAgendaSaida = $oDaoAgendaSaida->sql_query_file( null, 'tf17_i_codigo', null, "tf17_i_pedidotfd = {$iPedido}" );
  $rsAgendaSaida   = db_query( $sSqlAgendaSaida );

  if( !is_resource( $rsAgendaSaida ) ) {

    $oErro        = new stdClass();
    $oErro->sErro = pg_last_error();

    throw new DBException( _M( MENSAGEM_AGENDASAIDA . "erro_buscar_agenda_saida", $oErro ) );
  }

  if( pg_num_rows( $rsAgendaSaida ) > 0 ) {

    $iAgendaSaida = db_utils::fieldsMemory( $rsAgendaSaida, 0 )->tf17_i_codigo;

    $oDaoAgendaSaidaPassagemDestino = new cl_agendasaidapassagemdestino();
    $oDaoAgendaSaidaPassagemDestino->excluir( null, "tf38_agendasaida = {$iAgendaSaida}" );

    if ( $oDaoAgendaSaidaPassagemDestino->erro_status == 0 ) {

      $oErro        = new stdClass();
      $oErro->sErro = utf8_encode( $oDaoAgendaSaidaPassagemDestino->erro_msg );

      throw new Exception( _M( MENSAGEM_AGENDASAIDA . "erro_remover_agenda_saida_destino", $oErro ) );
    }

    $oDaoAgendaSaida->excluir( $iAgendaSaida );
    if ( $oDaoAgendaSaida->erro_status == 0 ) {

      $oErroMsg = new stdClass();
      $oErroMsg->sMsgErro = utf8_encode($oDaoAgendaSaida->erro_msg);
      throw new Exception(_M( MENSAGEM_AGENDASAIDA . "erro_remover_agenda", $oErroMsg));
    }
  }
}

function buscaCodigoVeiculoDestino($oDtSaida, $oDtRetorno, $sHoraRetorno, $sHoraSaida, $iCodigoVeiculo, $iDestinoPrestadora, $iCodigoMotorista) {

  $oErroMsg              = new stdClass();
  $oDaoPassageiroVeiculo = new cl_tfd_passageiroveiculo();
  $oDaoVeiculoDestino    = new cl_tfd_veiculodestino();

  $aWhere   = array();
  $aWhere[] = " tf18_d_datasaida   = '" . $oDtSaida->convertTo(DBDate::DATA_EN) . "' ";
  $aWhere[] = " tf18_d_dataretorno = '" . $oDtRetorno->convertTo(DBDate::DATA_EN) . "' ";
  $aWhere[] = " tf18_c_horaretorno = '{$sHoraRetorno}' ";
  $aWhere[] = " tf18_c_horasaida   = '{$sHoraSaida}' ";
  $aWhere[] = " tf18_i_veiculo     = {$iCodigoVeiculo} ";
  $aWhere[] = " tf18_i_destino     = {$iDestinoPrestadora} ";
  $aWhere[] = " tf19_i_valido      = 1 ";

  $sWhere  = implode(" and ", $aWhere);
  $sCampos = " max(tf18_i_codigo) as veiculo_destino";

  $sSqlPassageiros       = $oDaoPassageiroVeiculo->sql_query_passageiro_veiculo(null, $sCampos, null, $sWhere);
  $rsPassageiros         = db_query($sSqlPassageiros);

  $iCodigoVeiculoDestino = null;

  /**
   * Verifica se encontrou o veículo destino,
   * - se encontrou retorna o código
   * - se não inclui um veiculo destino e retorna o código
   */
  if (   $rsPassageiros && pg_num_rows($rsPassageiros) > 0
      && db_utils::fieldsMemory($rsPassageiros, 0)->veiculo_destino != '') {

    $iCodigoVeiculoDestino = db_utils::fieldsMemory($rsPassageiros, 0)->veiculo_destino;

    if ( !empty($iCodigoMotorista) ) {

      $oDaoVeiculoDestino->tf18_i_motorista = $iCodigoMotorista;
      $oDaoVeiculoDestino->tf18_i_codigo    = $iCodigoVeiculoDestino;
      $oDaoVeiculoDestino->alterar($iCodigoVeiculoDestino);

      if ($oDaoVeiculoDestino->erro_status == 0) {

        $oErroMsg->sMsgErro = utf8_encode($oDaoVeiculoDestino->erro_msg);
        throw new Exception(_M( MENSAGEM_AGENDASAIDA . "erro_incluir_alterar_veiculo_destino", $oErroMsg));
      }
    }

  } else {

    $oDaoVeiculoDestino->tf18_i_codigo      = null;
    $oDaoVeiculoDestino->tf18_i_veiculo     = $iCodigoVeiculo;
    $oDaoVeiculoDestino->tf18_i_destino     = $iDestinoPrestadora;
    $oDaoVeiculoDestino->tf18_d_datasaida   = "{$oDtSaida->convertTo(DBDate::DATA_EN)} ";
    $oDaoVeiculoDestino->tf18_d_dataretorno = "{$oDtRetorno->convertTo(DBDate::DATA_EN)} ";
    $oDaoVeiculoDestino->tf18_c_horasaida   = "{$sHoraSaida} ";
    $oDaoVeiculoDestino->tf18_c_horaretorno = "{$sHoraRetorno} ";

    if ( !empty($iCodigoMotorista)) {
      $oDaoVeiculoDestino->tf18_i_motorista = $iCodigoMotorista;
    }

    $oDaoVeiculoDestino->incluir(null);
    if ($oDaoVeiculoDestino->erro_status == 0) {

      $oErroMsg->sMsgErro = utf8_encode($oDaoVeiculoDestino->erro_msg);
      throw new Exception(_M( MENSAGEM_AGENDASAIDA . "erro_incluir_alterar_veiculo_destino", $oErroMsg));
    }

    $iCodigoVeiculoDestino = $oDaoVeiculoDestino->tf18_i_codigo;
  }
  return $iCodigoVeiculoDestino;
}

/**
 * Salva o vínculo dos passageiros com um veículo
 *
 * @param $oDadosParametro
 * @throws Exception
 */
function salvarPassageiroVeiculo( $oDadosParametro ) {

  $oDaoPassageiroVeiculo = new cl_tfd_passageiroveiculo();
  $oDtSaida              = new DBDate( $oDadosParametro->dtSaida );
  $oDtRetorno            = new DBDate( $oDadosParametro->dtRetorno );

  /**
   * Localiza Veículo de destino
   */
  $iCodigoVeiculoDestino = buscaCodigoVeiculoDestino(
                                                      $oDtSaida,
                                                      $oDtRetorno,
                                                      $oDadosParametro->horaRetorno,
                                                      $oDadosParametro->horaSaida,
                                                      $oDadosParametro->iCodigoVeiculo,
                                                      $oDadosParametro->iDestinoPrestadora,
                                                      $oDadosParametro->iCodigoMotorista
                                                    );
  /**
   * Inclui os passageiros
   */
  foreach ($oDadosParametro->aPassageiros as $oPassageiro) {

    $oDaoPassageiroVeiculo->tf19_i_codigo         = null;
    $oDaoPassageiroVeiculo->tf19_i_cgsund         = $oPassageiro->iCgs;
    $oDaoPassageiroVeiculo->tf19_i_veiculodestino = $iCodigoVeiculoDestino;
    $oDaoPassageiroVeiculo->tf19_i_pedidotfd      = $oDadosParametro->iPedidoTFD;
    $oDaoPassageiroVeiculo->tf19_i_valido         = 1;
    $oDaoPassageiroVeiculo->tf19_i_tipopassageiro = $oPassageiro->lPaciente ? 1 : 2;
    $oDaoPassageiroVeiculo->tf19_i_colo           = 2;
    $oDaoPassageiroVeiculo->tf19_i_fica           = $oPassageiro->lFica ? 1 : 2;

    $oDaoPassageiroVeiculo->incluir(null);

    if ($oDaoPassageiroVeiculo->erro_status == 0) {

      $oErroMsg           = new stdClass();
      $oErroMsg->sMsgErro = utf8_encode($oDaoPassageiroVeiculo->erro_msg);

      throw new Exception( _M( MENSAGEM_AGENDASAIDA . "erro_incluir_passageiro_veiculo", $oErroMsg));
    }
  }
}

/**
 * Salva os passageiros que receberam passagem
 *
 * @param $oDadosParametro
 * @throws DBException
 */
function salvarPassagens( $oDadosParametro ) {

  $oDaoAgendaSaidaPassagemDestino = new cl_agendasaidapassagemdestino();

  foreach( $oDadosParametro->aPassageiros as $oPassageiro ) {

    $oDaoAgendaSaidaPassagemDestino->tf38_agendasaida   = $oDadosParametro->iCodigoAgenda;
    $oDaoAgendaSaidaPassagemDestino->tf38_valorunitario = DBNumber::toCurrency( $oDadosParametro->sValorUnitario );
    $oDaoAgendaSaidaPassagemDestino->tf38_cgs           = $oPassageiro->iCgs;
    $oDaoAgendaSaidaPassagemDestino->tf38_fica          = $oPassageiro->lFica ? 'true' : 'false';
    $oDaoAgendaSaidaPassagemDestino->incluir(null);

    if( $oDaoAgendaSaidaPassagemDestino->erro_status == 0 ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoAgendaSaidaPassagemDestino->erro_msg;

      throw new DBException( _M( MENSAGEM_AGENDASAIDA . "erro_incluir_passageiro_destino", $oErro ) );
    }
  }
}

echo $oJson->encode($oRetorno);
