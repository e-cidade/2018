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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson                    = new services_json();
$oParam                   = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno                 = new stdClass();
$oRetorno->iStatus        = 1;
$oRetorno->sMessage       = '';
$oDaoCotaAtendimento      = new cl_limiteatendimento();
$oDaoCotaUsadoAtendimento = new cl_limiteatendimentousado();
$oDaoCotaExame            = new cl_limiteatendimentoexame();
$oDaoCotaExameUsado       = new cl_limiteatendimentoexameusado();
$sDataAtual               = date('Y-m-d');

define("ARQUIVO_MENSAGEM_COTAS", "saude.laboratorio.lab4_cotasatendimentoRPC.");

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Retorna a configuração de atendimento diário que cada laboratório realiza bem como o limite de cada exame.
     *
     * @return array
     *  aLaboratorios = [
     *     {
     *       iCodigoCota  : 1,
     *       iLaboratorio  : 1,
     *       sNome         : 'LABORATÓRIO MUNICIPAL',
     *       iLimiteDiario : 100,
     *       aExames       : [
     *         {
     *           iCodigoCotaExame : 1
     *           iExame            : 47,
     *           sExame            : 'ANTI HCV',
     *           iSetorExame       : 30
     *           iLimiteExame       : 30
     *         }
     *       ]
     *     },
     *     {
     *       iLaboratorio  : 2,
     *       sNome         : 'LABORATÓRIO ficticio',
     *       iLimiteDiario : '',
     *       aExames       : []
     *     }
     *  ]
     */
    case "buscarDadosLaboratorios":

      $oRetorno->aLaboratorios = array();
      $oDaoLaboratorio         = new cl_lab_laboratorio();
      $sCamposAtendimento      = " la45_sequencial as codigo_cota,  la45_quantidade as limite_diario,";
      $sCamposAtendimento     .= " la02_i_codigo as codigo_laboratorio, la02_c_descr as laboratorio";
      $sSqlLimite              = $oDaoLaboratorio->sql_query_laboratorio(null, $sCamposAtendimento, 'la02_c_descr', null);
      $rsLimite                = db_query($sSqlLimite);

      if ( !$rsLimite ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_limite_atendimento") );
      }

      $iLaboratorios = pg_num_rows($rsLimite);

      for ( $iLaboratorio = 0; $iLaboratorio < $iLaboratorios; $iLaboratorio++ ) {

        $oDadosLimite = db_utils::fieldsMemory( $rsLimite, $iLaboratorio );
        $oLaboratorio                = new stdClass();
        $oLaboratorio->iCodigoCota   = $oDadosLimite->codigo_cota;
        $oLaboratorio->iLaboratorio  = $oDadosLimite->codigo_laboratorio;
        $oLaboratorio->sNome         = $oDadosLimite->laboratorio;
        $oLaboratorio->iLimiteDiario = $oDadosLimite->limite_diario;
        $oLaboratorio->aExames       = array();

        $sCamposExame    = " la09_i_codigo as setor_exame, la08_i_codigo as codigo_exame, la08_c_descr as exame, ";
        $sCamposExame   .= " la46_sequencial as codigo_cota_exame, la46_quantidade as limite_exame";
        $sWhereExame     = "la24_i_laboratorio = {$oDadosLimite->codigo_laboratorio}";
        $sSqlLimiteExame = $oDaoCotaExame->sql_query_exames_limitados( $sCamposExame, 'la08_c_descr', $sWhereExame  );
        $rsLimiteExame   = db_query( $sSqlLimiteExame );

        if ( !$rsLimiteExame ) {
          throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_limite_exame") );
        }

        $iExames = pg_num_rows( $rsLimiteExame );

        for ( $iExame = 0; $iExame < $iExames; $iExame++ ) {

          $oDadosExame = db_utils::fieldsMemory( $rsLimiteExame, $iExame );
          $oExame                   = new stdClass();
          $oExame->iCodigoCotaExame = $oDadosExame->codigo_cota_exame;
          $oExame->iExame           = $oDadosExame->codigo_exame;
          $oExame->sExame           = $oDadosExame->exame;
          $oExame->iSetorExame      = $oDadosExame->setor_exame;
          $oExame->iLimiteExame     = $oDadosExame->limite_exame;
          $oLaboratorio->aExames[]  = $oExame;
        }

        $oRetorno->aLaboratorios[] = $oLaboratorio;
      }

      break;

    /**
     *  Salva a configuração do limite de atendimento de pacientes diário e a quantidade de exames que podem ser feitos
     *  no dia.
     *
     * @return stdClass
     *
     *     oCotasLaboratorio : {
     *      iCodigoCota   : 1,
     *      iLaboratorio  : 1,
     *      sNome         : 'LABORATÓRIO MUNICIPAL',
     *      iLimiteDiario : 100,
     *      aExames       : [
     *        {
     *          iCodigoCotaExame : 1
     *          iExame            : 47,
     *          sExame            : 'ANTI HCV',
     *          iSetorExame       : 30
     *          iLimiteExame       : 30
     *        }
     *      ]
     *    }
     */
    case "salvar":

      if ( count($oParam->aExames) > 0 ) {
        excluirCotasExames( $oParam->aExames, $oDaoCotaExame, $oDaoCotaExameUsado );
      }

      if ( !empty($oParam->iCodigoCota) ) {
        excluirCotasLaboratorio( $oParam->iCodigoCota, $oDaoCotaAtendimento, $oDaoCotaUsadoAtendimento );
      }

      salvarLimiteAtendimento( $oParam, $oDaoCotaAtendimento );
      salvarLimiteAtendimentoExame( $oParam, $oDaoCotaExame );
      migrarLimiteAtendimentoUsado( $oParam, $oDaoCotaUsadoAtendimento, $sDataAtual );
      migrarLimiteAtendimentoExameUsado( $oParam, $oDaoCotaExameUsado, $sDataAtual );

      $oRetorno->oCotasLaboratorio = $oParam;
      $oRetorno->sMessage          = _M(ARQUIVO_MENSAGEM_COTAS . "cotas_cadastras_sucesso");

      break;

    /**
     *  Remove a configuração de cotas, tanto de atendimentos quanto a de exames.
     */
    case 'excluir':


      $oRetorno->sMessage = _M(ARQUIVO_MENSAGEM_COTAS . "laboratorio_sem_cotas");
      if ( !empty($oParam->iCodigoCota) ) {

        excluirCotasExames( $oParam->aExames, $oDaoCotaExame, $oDaoCotaExameUsado );
        excluirCotasLaboratorio( $oParam->iCodigoCota, $oDaoCotaAtendimento, $oDaoCotaUsadoAtendimento );

        $oRetorno->oLaboratorioExcluido               = new stdClass();
        $oRetorno->oLaboratorioExcluido->iLaboratorio = $oParam->iLaboratorio;
        $oRetorno->sMessage                           = _M(ARQUIVO_MENSAGEM_COTAS . "cotas_excluidas_sucesso");
      }

      break;

    /**
     *  Remove a configuração de cotas de exames.
     */
    case 'excluirCotasExame':

      if ( empty($oParam->iCodigoCotaExame) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM_COTAS . "informe_codigo_cota_exame") );
      }

      if ( empty($oParam->iSetorExame) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM_COTAS . "informe_codigo_exame") );
      }

      excluirCotasExames( array($oParam), $oDaoCotaExame, $oDaoCotaExameUsado );

      $oRetorno->oExameRemovido                   = new stdClass();
      $oRetorno->oExameRemovido->iCodigoCotaExame = $oParam->iCodigoCotaExame;
      $oRetorno->sMessage = _M(ARQUIVO_MENSAGEM_COTAS . "exame_excluido_sucesso");

      break;

    case 'verificaUsoCotas':

      /**
       * 0 => Não utiliza
       * 1 => Utiliza controle Financeiro
       * 2 => Utiliza controle Atendimento diário (Pacientes por Dia)
       */
      $oRetorno->tipo = 0;

      $sSql  = " select 1 as tipo ";
      $sSql .= "   from lab_controlefisicofinanceiro ";
      $sSql .= " union ";
      $sSql .= " select 2 as tipo ";
      $sSql .= "   from limiteatendimento ";

      $rs = db_query($sSql);
      if (!$rs) {
        throw new Exception( _M( ARQUIVO_MENSAGEM_COTAS . "erro_verificar_uso_cotas") );
      }

      if ( pg_num_rows($rs) > 0 ) {
        $oRetorno->tipo = db_utils::fieldsMemory($rs, 0)->tipo;
      }

      break;

    case 'alteraCotas':

      $oRetorno = alteraQuantidadeCotas( $oRetorno, $oParam );
      break;

    case 'forcarInclusaoCota':

      /**
       * Forca a inclusão sem validar limite de cotas
       */

      $oRetorno = alteraQuantidadeCotas( $oRetorno, $oParam, true );

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);

/**
 * Exclui o limite dos exames que já foram configurados
 * @param array $aCotasExames
 * @param cl_limiteatendimentoexame $oDaoCotaExame
 * @param cl_limiteatendimentoexameusado $oDaoCotaExameUsado
 */
function excluirCotasExames( $aCotasExames, $oDaoCotaExame, $oDaoCotaExameUsado ) {

  foreach ( $aCotasExames as $oCotaExame ) {

    $sWhereExameUsado  = " la63_lab_setorexame = {$oCotaExame->iSetorExame}";
    $oDaoCotaExameUsado->excluir(null, $sWhereExameUsado);

    if ( $oDaoCotaExameUsado->erro_status == "0" ) {
      throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_excluir_cotas_exames_utilizadas") );
    }

    if(!empty($oCotaExame->iCodigoCotaExame)){

      $oDaoCotaExame->excluir($oCotaExame->iCodigoCotaExame);

      if ( $oDaoCotaExame->erro_status == "0" ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_excluir_cota_exames") );
      }
    }
  }
}

/**
 * Exclui o limite dos atendimentos por pacientes que já foram configurados
 * @param integer $iCodigoCota
 * @param cl_limiteatendimento $oDaoCotaAtendimento
 * @param cl_limiteatendimentousado $oDaoCotaUsadoAtendimento
 */
function excluirCotasLaboratorio( $iCodigoCota, $oDaoCotaAtendimento, $oDaoCotaUsadoAtendimento ) {

  $sWhereCotaAtendimentoUsado  = " la62_limiteatendimento = {$iCodigoCota}";
  $oDaoCotaUsadoAtendimento->excluir(null, $sWhereCotaAtendimentoUsado);

  if ( $oDaoCotaUsadoAtendimento->erro_status == "0" ) {
    throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_excluir_cotas_atendimento_utilizadas") );
  }

  $oDaoCotaAtendimento->excluir($iCodigoCota);

  if ( $oDaoCotaAtendimento->erro_status == "0" ) {
    throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_excluir_cotas_atendimento") );
  }
}

/**
 * Inclui o limite de atendimento diário configurado
 * @param stdClass &$oParam
 * @param cl_limiteatendimento $oDaoCotaAtendimento
 */
function salvarLimiteAtendimento( &$oParam, $oDaoCotaAtendimento ) {

  if(empty($oParam->iLimiteDiario) || !is_numeric($oParam->iLimiteDiario)){
    throw new ParameterException( _M(ARQUIVO_MENSAGEM_COTAS . "informe_limite_diario") );
  }

  if(empty($oParam->iLaboratorio) || !is_numeric($oParam->iLaboratorio)){
    throw new ParameterException( _M(ARQUIVO_MENSAGEM_COTAS . "informe_laboratorio") );
  }

  $oDaoCotaAtendimento->la45_sequencial = null;
  $oDaoCotaAtendimento->la45_quantidade = $oParam->iLimiteDiario;
  $oDaoCotaAtendimento->la45_lab_laboratorio = $oParam->iLaboratorio;
  $oDaoCotaAtendimento->incluir(null);

  if($oDaoCotaAtendimento->erro_status == 0){
    throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_incluir_cotas_atendimento") );
  }

  $oParam->iCodigoCota = $oDaoCotaAtendimento->la45_sequencial;
}

/**
 * Inclui o limite de exames diários configurado
 * @param stdClass &$oParam
 * @param cl_limiteatendimentoexame $oDaoCotaExame
 */
function salvarLimiteAtendimentoExame( &$oParam, $oDaoCotaExame ) {

  foreach ($oParam->aExames as &$oExame) {
    if(empty($oExame->iLimiteExame) || !is_numeric($oExame->iLimiteExame)){

      $oErro        = new stdClass();
      $oErro->sErro = $oExame->sExame;
      throw new ParameterException( _M(ARQUIVO_MENSAGEM_COTAS . "informe_limite_exame", $oErro) );
    }

    if(empty($oExame->iSetorExame) || !is_numeric($oExame->iSetorExame)){

      $oErro        = new stdClass();
      $oErro->sErro = $oExame->sExame;
      throw new ParameterException( _M(ARQUIVO_MENSAGEM_COTAS . "informe_setor_exame", $oErro) );
    }

    $oDaoCotaExame->la46_sequencial = null;
    $oDaoCotaExame->la46_quantidade = $oExame->iLimiteExame;
    $oDaoCotaExame->la46_lab_setorexame = $oExame->iSetorExame;
    $oDaoCotaExame->incluir(null);

    if($oDaoCotaExame->erro_status == 0){
      throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_incluir_cotas_exame") );
    }

    $oExame->iCodigoCotaExame = $oDaoCotaExame->la46_sequencial;
  }
}

/**
 * Busca todos as requisições com exames de data igual ou supeior a data autal e popula a estrutura de atendimentos
 * por dia
 *
 * @param std $oParam
 * @param cl_limiteatendimentousado $oDaoCotaUsadoAtendimento
 * @param string $sDataAtual
 */
function migrarLimiteAtendimentoUsado( $oParam, $oDaoCotaUsadoAtendimento, $sDataAtual ) {

  $oDaoRequisicao    = new cl_lab_requiitem();
  $sCamposRequisicao = " distinct la21_i_requisicao, la21_d_data";
  $sWhereRequisicao  = " la21_d_data >= '{$sDataAtual}' and la24_i_laboratorio = {$oParam->iLaboratorio} ";
  $sSqlRequisicao    = $oDaoRequisicao->sql_query(null, $sCamposRequisicao, null, $sWhereRequisicao );

  $sSql  = " select count(*) as quantidade, la21_d_data ";
  $sSql .= "   from ({$sSqlRequisicao}) as x ";
  $sSql .= " group by la21_d_data";

  $rsRequisicao = db_query( $sSql );

  if ( !$rsRequisicao ) {
    throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_requisicoes") );
  }

  $iTotalRequisicoes = pg_num_rows( $rsRequisicao );
  for ( $iContador = 0; $iContador < $iTotalRequisicoes; $iContador++ ) {

    $oDadosAtendimentos = db_utils::fieldsMemory( $rsRequisicao, $iContador );
    $oDaoCotaUsadoAtendimento->la62_sequencial        = null;
    $oDaoCotaUsadoAtendimento->la62_quantidade        = $oDadosAtendimentos->quantidade;
    $oDaoCotaUsadoAtendimento->la62_data              = $oDadosAtendimentos->la21_d_data;
    $oDaoCotaUsadoAtendimento->la62_limiteatendimento = $oParam->iCodigoCota;
    $oDaoCotaUsadoAtendimento->incluir(null);

    if($oDaoCotaUsadoAtendimento->erro_status == 0){
      throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_incluir_quantidade_requisicoes") );
    }
  }
}

/**
 * Busca todos os exames com data igual ou superior a data atual e verifica se há limite de cotas pra algum deles.
 * Caso haja, é somado o total deste exame por dia e populado a estrutura de exames por dia.
 *
 * @param stdClass $oParam
 * @param cl_limiteatendimentoexameusado $oDaoCotaExameUsado
 * @param string $sDataAtual
 */
function migrarLimiteAtendimentoExameUsado( $oParam, $oDaoCotaExameUsado, $sDataAtual ) {

  $aExames = array();

  foreach ( $oParam->aExames as $oExame ) {
    $aExames[] = $oExame->iSetorExame;
  }

  // Se não houver exames configurados, não é preciso verificar a migração por exames.
  if ( empty($aExames) ) {
    return;
  }

  $oDaoRequisicao = new cl_lab_requiitem();
  $aRequisicoes   = buscarRequisicoes( $oParam, $oDaoRequisicao, $sDataAtual );

  // Se não houver requisições com 1 exame, não é necessário realizar a migração.
  if ( empty($aRequisicoes) ) {
    return;
  }

  $aCamposExames = " count(*) as quantidade, la21_i_setorexame, la21_d_data";
  $sWhereExames  = " la21_i_requisicao in (" . implode(",", $aRequisicoes) . ")";
  $sWhereExames .= " AND la21_i_setorexame in (" . implode(",", $aExames) . ")";
  $sWhereExames .= " group by la21_i_setorexame, la21_d_data";
  $sSqlExames    = $oDaoRequisicao->sql_query_file(null, $aCamposExames, null, $sWhereExames);
  $rsExames      = db_query( $sSqlExames );

  if ( !$rsExames ) {
    throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_exames") );
  }

  $iTotalExames = pg_num_rows($rsExames);

  for( $iExames = 0; $iExames < $iTotalExames; $iExames++ ) {

    $oDadosExames = db_utils::fieldsMemory( $rsExames, $iExames );

    $oDaoCotaExameUsado->la63_sequencial     = null;
    $oDaoCotaExameUsado->la63_quantidade     = $oDadosExames->quantidade;
    $oDaoCotaExameUsado->la63_data           = $oDadosExames->la21_d_data;
    $oDaoCotaExameUsado->la63_lab_setorexame = $oDadosExames->la21_i_setorexame;
    $oDaoCotaExameUsado->incluir(null);

    if($oDaoCotaExameUsado->erro_status == 0){
      throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_incluir_quantidade_exames") );
    }
  }
}

/**
 * Busca todos as requisições que possuem apenas um exame
 *
 * @param stdClass $oParam
 * @param cl_lab_requiitem $oDaoRequisicao
 * @param string $sDataAtual
 * @return array
 */
function buscarRequisicoes( $oParam, $oDaoRequisicao, $sDataAtual ) {

  $aRequisicoes      = array();
  $sCamposRequisicao = " count(la21_i_setorexame), la22_i_codigo";
  $sWhereRequisicao  = " la21_d_data >= '{$sDataAtual}' AND la24_i_laboratorio = {$oParam->iLaboratorio}";
  $sWhereRequisicao .= " group by la22_i_codigo having count(*) = 1;";
  $sSqlRequisicao    = $oDaoRequisicao->sql_query(null, $sCamposRequisicao, null, $sWhereRequisicao );
  $rsRequisicao      = db_query( $sSqlRequisicao );

  if ( !$rsRequisicao ) {
    throw new DBException( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_requisicoes") );
  }

  $iTotalRequisicoes = pg_num_rows( $rsRequisicao );

  for ( $iRequisicoes = 0; $iRequisicoes < $iTotalRequisicoes; $iRequisicoes++ ) {
    $aRequisicoes[] = db_utils::fieldsMemory( $rsRequisicao, $iRequisicoes)->la22_i_codigo;
  }

  return $aRequisicoes;
}

/**
 * Inclui a cota de atendimento por dia.
 * @param integer $iCodigoLimiteAtendimento
 * @param string $sData
 * @return boolean
 */
function incluirLimiteAtendimentoUsado( $iCodigoLimiteAtendimento, $sData ) {

  $oDaoLimiteUsado                         = new cl_limiteatendimentousado();
  $oDaoLimiteUsado->la62_sequencial        = null;
  $oDaoLimiteUsado->la62_quantidade        = 1;
  $oDaoLimiteUsado->la62_data              = $sData;
  $oDaoLimiteUsado->la62_limiteatendimento = $iCodigoLimiteAtendimento;
  $oDaoLimiteUsado->incluir(null);

  if ( $oDaoLimiteUsado->erro_status == 0 ) {
    throw new DBException(ARQUIVO_MENSAGEM_COTAS . "erro_atualizar_atendimentos_usados");
  }

  return true;
}

/**
 * Adiciona ou remove a quantidade de cotas de atendimento por dia.
 * @param stdClass $oDadosUsado
 * @param integer  $iLimiteDiario
 * @param stdClass $oRetorno
 * @param integer  $iValor
 * @return stdClass
 */
function alterarLimiteAtendimentoUsado($oDadosUsado, $iLimiteDiario, $oRetorno, $iValor, $lForcarInclusao) {

  $oDadosUsado->la62_quantidade += $iValor;
  if ( $oDadosUsado->la62_quantidade > $iLimiteDiario && $iValor > 0 && !$lForcarInclusao) {

    $oMsgErro                     = new stdClass();
    $oMsgErro->iLimiteDiario      = $iLimiteDiario;
    $oRetorno->sMessage           =  _M(ARQUIVO_MENSAGEM_COTAS . "cota_diaria_atingida", $oMsgErro) ;
    $oRetorno->lAtingiuCotaDiaria = true;
    return $oRetorno;
  }

  $oDadosUsado->la62_quantidade = ( $oDadosUsado->la62_quantidade < 0 ) ? 0 : $oDadosUsado->la62_quantidade;

  $oDaoLimiteUsado                         = new cl_limiteatendimentousado();
  $oDaoLimiteUsado->la62_quantidade        = $oDadosUsado->la62_quantidade;
  $oDaoLimiteUsado->la62_sequencial        = $oDadosUsado->la62_sequencial;
  $oDaoLimiteUsado->la62_data              = $oDadosUsado->la62_data;
  $oDaoLimiteUsado->la62_limiteatendimento = $oDadosUsado->la62_limiteatendimento;

  $oDaoLimiteUsado->alterar($oDadosUsado->la62_sequencial);
  if ( $oDaoLimiteUsado->erro_status == 0 ) {
    throw new DBException(ARQUIVO_MENSAGEM_COTAS . "erro_atualizar_atendimentos_usados");
  }

  return $oRetorno;
}

/**
 * Adiciona ou remove quantidade de exames por dia.
 * @param integer $iSetorExame
 * @param string  $sData
 * @param integer $iValor
 * @param stdClas $oRetorno
 * @param boolean $lForcarInclusao
 * @return boolean
 */
function controlaSaldoExame( $iSetorExame, $sData, $iValor, $oRetorno, $lForcarInclusao ) {

  $oDaoLimiteExame = new cl_limiteatendimentoexame();
  $sSqlLimiteExame = $oDaoLimiteExame->sql_query_file(null, '*', null, " la46_lab_setorexame = {$iSetorExame} ");
  $rsLimiteExame   = db_query($sSqlLimiteExame);

  if ( !$rsLimiteExame ) {
    throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_limite_exame") );
  }

  // não tem cota para o exame
  if ( pg_num_rows($rsLimiteExame) == 0 ) {
    return true;
  }

  $oDadosCotaExame = db_utils::fieldsMemory($rsLimiteExame, 0);

  $sWhere               = " la63_lab_setorexame = {$iSetorExame} and la63_data = '{$sData}' ";
  $oDaoLimiteExameUsado = new cl_limiteatendimentoexameusado();
  $sSqlLimiteExameUsado = $oDaoLimiteExameUsado->sql_query_file(null, "*", null, $sWhere);
  $rsLimiteExameUsado   = db_query($sSqlLimiteExameUsado);
  if ( !$rsLimiteExameUsado ) {
    throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_limite_exame") );
  }

  $oDaoLimiteExameUsado->la63_quantidade     = 1;
  $oDaoLimiteExameUsado->la63_data           = $sData;
  $oDaoLimiteExameUsado->la63_lab_setorexame = $iSetorExame;

  // se for o primeiro agendamento do dia e ainda não atingiu a cota diária
  if ( pg_num_rows($rsLimiteExameUsado) == 0 && !$oRetorno->lAtingiuCotaDiaria) {

    $oDaoLimiteExameUsado->la63_sequencial     = null;
    $oDaoLimiteExameUsado->incluir(null);
    if ( $oDaoLimiteExameUsado->erro_status == 0 ) {
      throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "erro_atualizar_exames_usados") );
    }
  }

  if ( pg_num_rows($rsLimiteExameUsado) > 0) {

    $oDadosCotasUsadas = db_utils::fieldsMemory($rsLimiteExameUsado, 0);

    $oDadosCotasUsadas->la63_quantidade += $iValor;
    $oDadosCotasUsadas->la63_quantidade  = ( $oDadosCotasUsadas->la63_quantidade < 0 ) ? 0 :$oDadosCotasUsadas->la63_quantidade;

    if ( $oDadosCotasUsadas->la63_quantidade > $oDadosCotaExame->la46_quantidade) {

      $oMsgErro = buscaNomeExame($iSetorExame);
      throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "exame_atingiu_cota", $oMsgErro) );
    }

    if ( $oRetorno->lAtingiuCotaDiaria && !$lForcarInclusao ) {
      return true;
    }

    $oDaoLimiteExameUsado->la63_sequencial = $oDadosCotasUsadas->la63_sequencial;
    $oDaoLimiteExameUsado->la63_quantidade = $oDadosCotasUsadas->la63_quantidade;
    $oDaoLimiteExameUsado->alterar($oDadosCotasUsadas->la63_sequencial);
    if ( $oDaoLimiteExameUsado->erro_status == 0 ) {
      throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "erro_atualizar_exames_usados") );
    }
  }

  return true;
}

/**
 * Busca o nome do exame
 * @param integer $iSetorExame
 * @return stdClass
 */
function buscaNomeExame($iSetorExame) {

  $oDaoExame = new cl_lab_setorexame();
  $sSqlExame = $oDaoExame->sql_query_setorexame(null, "la08_c_descr", null, " la09_i_codigo = {$iSetorExame} ");
  $rsExame   = db_query($sSqlExame);

  if ( !$rsExame ) {
    throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_nome_exame") );
  }

  $oMsgErro        = new stdClass();
  $oMsgErro->exame = db_utils::fieldsMemory($rsExame, 0)->la08_c_descr;
  return $oMsgErro;
}

/**
 * Retorna os laboratórios que possuem os exames passados por parâmetro
 * @param array $aCodigoDoSetorExame
 * @return resource
 */
function buscaLimiteAtendimentoPorExame( $aCodigoDoSetorExame ) {

  $sWhere   = " la09_i_codigo in ( " . implode(', ', array_keys($aCodigoDoSetorExame)) . ")";
  $sCampos  = " distinct limiteatendimento.*, la09_i_codigo ";
  $oDaoLimiteAtendimento = new cl_limiteatendimento;
  $sSqlLaboratorios      = $oDaoLimiteAtendimento->sql_limiteLaboratorio( $sCampos, null, $sWhere);

  $rsLaboratorios = db_query($sSqlLaboratorios);

  if ( !$rsLaboratorios ) {
    throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_laboratorio") );
  }

  return $rsLaboratorios;
}

/**
 * Organiza os exames por dia e laboratório.
 * @param resource $rsLaboratorios
 * @param array $aCodigoDoSetorExame
 * @return array
 */
function quantificaExamesPorDia( $rsLaboratorios, $aCodigoDoSetorExame ) {

  $iLinhas      = pg_num_rows($rsLaboratorios);
  $aLaboratorio = array();
  for ($i = 0; $i < $iLinhas; $i++) {

    $oDadoLaboratorio = db_utils::fieldsMemory($rsLaboratorios, $i);

    if ( !array_key_exists($oDadoLaboratorio->la45_lab_laboratorio, $aLaboratorio) ) {

      $oLaboratorio                              = new stdClass();
      $oLaboratorio->iCodigoLimiteAtendimento    = $oDadoLaboratorio->la45_sequencial;
      $oLaboratorio->iLimite                     = $oDadoLaboratorio->la45_quantidade;
      $oLaboratorio->iLaboratorio                = $oDadoLaboratorio->la45_lab_laboratorio;
      $oLaboratorio->aNumeroExamesDia            = array();
      $oLaboratorio->aExameAgendado              = array();
      $aLaboratorio[$oLaboratorio->iLaboratorio] = $oLaboratorio;
    }

    $sDataExame = $aCodigoDoSetorExame[$oDadoLaboratorio->la09_i_codigo];

    if ( !array_key_exists($sDataExame, $aLaboratorio[$oLaboratorio->iLaboratorio]->aNumeroExamesDia) ) {
      $aLaboratorio[$oLaboratorio->iLaboratorio]->aNumeroExamesDia[$sDataExame] = 0;
    }
    $aLaboratorio[$oLaboratorio->iLaboratorio]->aNumeroExamesDia[$sDataExame] += 1;


    if ( !array_key_exists($sDataExame, $aLaboratorio[$oLaboratorio->iLaboratorio]->aExameAgendado) ) {
      $aLaboratorio[$oLaboratorio->iLaboratorio]->aExameAgendado[$sDataExame] = array();
    }

    $aLaboratorio[$oLaboratorio->iLaboratorio]->aExameAgendado[$sDataExame][] = $oDadoLaboratorio->la09_i_codigo;
  }

  return $aLaboratorio;
}

/**
 * Valida se deve ser incluido ou alterado as cotas de atendimento e de exames.
 * @param stdClass $oRetorno
 * @param stdClass $oParam
 * @return stdClass
 */
function alteraQuantidadeCotas( $oRetorno, $oParam, $lForcarInclusao = false ) {

  $aLaboratorios = validaCotas( $oParam );

  $oRetorno->lAtingiuCotaDiaria = false;

  $oDaoLimiteUsado = new cl_limiteatendimentousado();
  foreach ($aLaboratorios as $oLaboratorio ) {

    foreach ($oLaboratorio->aNumeroExamesDia as $sDataAgenda => $iTotalExames ) {

      $sData = implode('-', array_reverse(explode('/', $sDataAgenda)));

      $sWhereLimiteDia  = " la62_limiteatendimento = {$oLaboratorio->iCodigoLimiteAtendimento} ";
      $sWhereLimiteDia .= " and la62_data = '{$sData}' ";
      $sSqlLimiteUsado  = $oDaoLimiteUsado->sql_query_file(null, "*", null, $sWhereLimiteDia);
      $rsLimiteUsado    = db_query($sSqlLimiteUsado);

      if (!$rsLimiteUsado) {
        throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "erro_buscar_cotas_usadas") );
      }

      $iValor = 1;
      if ( !$oParam->lAdicionar ) {
        $iValor = -1;
      }

      if ( pg_num_rows($rsLimiteUsado) == 0 ) {
        incluirLimiteAtendimentoUsado($oLaboratorio->iCodigoLimiteAtendimento, $sData);
      } else {
        $oRetorno = alterarLimiteAtendimentoUsado(db_utils::fieldsMemory($rsLimiteUsado, 0), $oLaboratorio->iLimite, $oRetorno, $iValor, $lForcarInclusao);
      }

      if ( $iTotalExames == 1 ) {
        controlaSaldoExame( $oLaboratorio->aExameAgendado[$sDataAgenda][0], $sData, $iValor, $oRetorno, $lForcarInclusao );
      }
    }
  }

  return $oRetorno;
}

/**
 * Valida se deve ou não ser alterado as cotas de agendamento e de exames.
 * @param stdClass $oParam
 * @return array
 */
function validaCotas( $oParam ) {

  if ( empty($oParam->aExamesAgendados) ) {
    throw new Exception( _M(ARQUIVO_MENSAGEM_COTAS . "exames_nao_informados") );
  }

  $aCodigoDoSetorExame = array();
  foreach ($oParam->aExamesAgendados as $oExame) {
    $aCodigoDoSetorExame[$oExame->iSetorExame] = $oExame->dataAgenda;
  }

  $rsLaboratorios = buscaLimiteAtendimentoPorExame( $aCodigoDoSetorExame );

  if ( pg_num_rows($rsLaboratorios) == 0 )  {
    return  array();
  }

  return quantificaExamesPorDia( $rsLaboratorios, $aCodigoDoSetorExame );
}