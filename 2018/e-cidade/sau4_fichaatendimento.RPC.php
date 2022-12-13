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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$iUnidade = db_getsession('DB_coddepto');

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oErro               = new stdClass();
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
define( "MENSAGENS_FICHAATENDIMENTORPC", "saude.ambulatorial.sau4_fichaatendimento_RPC.");

try {

  db_inicio_transacao();

  switch($oParam->sExecucao) {

    /**
     * Busca a observação da última movimentação realizada no prontuário, caso o mesmo esteja encaminhado para o setor
     * da tela de origem informada.
     */
    case 'buscaUltimaObservacaoDaMovimentacao':

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( MENSAGENS_FICHAATENDIMENTORPC . 'prontuario_nao_informado' ) );
      }

      if ( !isset($oParam->iTelaOrigem) || empty($oParam->iTelaOrigem) ) {
        throw new ParameterException( _M( MENSAGENS_FICHAATENDIMENTORPC . 'tela_nao_informada' ) );
      }

      $oMovimentacaoDao    = new cl_movimentacaoprontuario();
      $sCamposMovimentacao = " sd102_observacao, sd102_situacao, setorambulatorial.sd91_codigo as setor, sd102_codigo";
      $sWhereMovimentacao  = " sd102_prontuarios = {$oParam->iProntuario} and setorambulatorial.sd91_local = {$oParam->iTelaOrigem}";

      if(isset($oParam->lAtestado) && $oParam->lAtestado === false) {
        $sWhereMovimentacao .= " AND sd102_situacao <> " . MovimentacaoFichaAtendimento::SITUACAO_ATESTADO_EM_BRANCO;
      }

      $sOrderMovimentacao  = " sd102_codigo desc limit 1";
      $sSqlMovimentacao    = $oMovimentacaoDao->sql_query( null, $sCamposMovimentacao, $sOrderMovimentacao, $sWhereMovimentacao );
      $rsMovimentacao      = db_query( $sSqlMovimentacao );

      if ( !$rsMovimentacao ) {

        $oErro->sErro = pg_last_error();
        throw new DBException(  _M( MENSAGENS_FICHAATENDIMENTORPC . "erro_buscar_ultima_observacao", $oErro) );
      }

      $oRetorno->sObservacao   = '';
      $oRetorno->iMovimentacao = '';

      if ( pg_num_rows($rsMovimentacao) > 0 ) {

        $oDadosRetorno                = db_utils::fieldsMemory( $rsMovimentacao, 0 );
        $oRetorno->sObservacao        = urlencode($oDadosRetorno->sd102_observacao);
        $oRetorno->iMovimentacao      = $oDadosRetorno->sd102_codigo;
        $oRetorno->iSituacao          = $oDadosRetorno->sd102_situacao;
        $oRetorno->iSetorAmbulatorial = $oDadosRetorno->setor;

        if(isset($oParam->lAtestado) && $oParam->lAtestado === true && $oDadosRetorno->sd102_situacao != 4) {

          $oRetorno->sObservacao   = '';
          $oRetorno->iMovimentacao = '';
        }
      }

    break;

    case 'buscaSetoresUnidade':

      $aWhere = array();
      if ($oParam->lFiltrarUnidadeLogada) {
        $aWhere[] = " sd91_unidades = " . db_getsession('DB_coddepto');
      }
      if ( isset ($oParam->aExcluirLocais) && count($oParam->aExcluirLocais) > 0) {
        $aWhere[] = " sd91_local not in (" . implode(", ", $oParam->aExcluirLocais). ")" ;
      }

      $oDaoSetorAmbulatorial = new cl_setorambulatorial();

      $sWhere      = implode(" and ", $aWhere);
      $sSqlSetores = $oDaoSetorAmbulatorial->sql_query_file(null, "*", "sd91_local", $sWhere);
      $rsSetores   = db_query($sSqlSetores);
      if ( !$rsSetores ) {

        $oErro->sErro = pg_last_error();
        throw new DBException(  _M( MENSAGENS_FICHAATENDIMENTORPC . "erro_buscar_setores", $oErro) );
      }

      $aLocais[1] = "RECEPÇÃO";
      $aLocais[2] = "TRIAGEM";
      $aLocais[3] = "CONSULTA MÉDICA";
      $aLocais[4] = "EXTERNO";

      $oRetorno->aSetores = array();
      if ( pg_num_rows($rsSetores) > 0 ) {

        $iLinhas = pg_num_rows($rsSetores);
        for ( $i = 0; $i < $iLinhas; $i++) {

          $oDados             = db_utils::fieldsMemory($rsSetores, $i);
          $oSetor             = new stdClass();
          $oSetor->iCodigo    = $oDados->sd91_codigo;
          $oSetor->iUnidade   =  $oDados->sd91_unidades;
          $oSetor->sDescricao = urlencode($oDados->sd91_descricao);
          $oSetor->iLocal     = $oDados->sd91_local;
          $oSetor->sLocal     = urlencode($aLocais[$oDados->sd91_local]);

          $oRetorno->aSetores[] = $oSetor;
        }
      }

    break;

    case 'encaminharProntuario':

      $oDaoProntuarios  = new cl_prontuarios();
      $oDaoMovimentacao = new cl_movimentacaoprontuario();

      if ( empty($oParam->iProntuario ) ) {
        throw new ParameterException( _M( MENSAGENS_FICHAATENDIMENTORPC . 'prontuario_nao_informado' ) );
      }

      if ( empty($oParam->iSetorDestino) ) {
        throw new ParameterException( _M( MENSAGENS_FICHAATENDIMENTORPC . 'setor_destino_nao_informado' ) );
      }

      $oDaoProntuarios->sd24_i_codigo          = $oParam->iProntuario;
      $oDaoProntuarios->sd24_setorambulatorial = $oParam->iSetorDestino;
      $oDaoProntuarios->alterar($oParam->iProntuario);

      if ( $oDaoProntuarios->erro_status == 0 ) {

        $oErro->sErro = $oDaoProntuarios->erro_msg;
        throw new DBException(  _M( MENSAGENS_FICHAATENDIMENTORPC . "erro_alterar_setor_destino", $oErro) );
      }

      $oMovimentacao = new MovimentacaoFichaAtendimento();
      $oMovimentacao->setFichaAtendimento($oParam->iProntuario);
      $oMovimentacao->setUsuarioSistema(UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario')));
      $oMovimentacao->setSetorAmbulatorial(SetorAmbulatorialRepository::getPorCodigo($oParam->iSetorDestino));
      $oMovimentacao->setData(new DBDate(date("Y-m-d")));
      $oMovimentacao->setHora(date("H:i"));
      $oMovimentacao->setObservacao( db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao) );
      $oMovimentacao->setSituacao(MovimentacaoFichaAtendimento::SITUACAO_ENCAMINHADA);
      $oMovimentacao->salvar();

      /**
       * Altera o médico que atende a FAA
       * Só acontece quando um médico encaminha para outro médico
       */
      if ( !empty( $oParam->iMedico ) || !empty( $oParam->iEspecialidade ) ) {

        $iEspecialidadeMedicoUnidade = 'null';

        if ( !empty($oParam->iMedico) ) {
          $iEspecialidadeMedicoUnidade = buscaVinculoMedicoEspecialidade($oParam->iMedico, $oParam->iEspecialidade, $iUnidade);
        }

        $sWhere             = " s104_i_prontuario = {$oParam->iProntuario} ";
        $oDaoProntProfAtend = new cl_prontprofatend();
        $sSqlProntProfAtend = $oDaoProntProfAtend->sql_query_file(null, "s104_i_codigo", null, $sWhere);
        $rsProntProfAtend   = db_query($sSqlProntProfAtend);

        $iCodigoProntProfAtend = null;
        if ( $rsProntProfAtend && pg_num_rows($rsProntProfAtend) == 1 ) {
          $iCodigoProntProfAtend = db_utils::fieldsMemory( $rsProntProfAtend, 0 )->s104_i_codigo;
        }

        $oDaoProntProfAtend->s104_i_prontuario   = $oParam->iProntuario;
        $oDaoProntProfAtend->s104_i_profissional = $iEspecialidadeMedicoUnidade;
        $oDaoProntProfAtend->s104_rhcbo          = empty($oParam->iEspecialidade) ? 'null' : $oParam->iEspecialidade;

        if ( empty($iCodigoProntProfAtend) ) {

          $oDaoProntProfAtend->s104_i_codigo = null;
          $oDaoProntProfAtend->incluir(null);
        } else {

          $oDaoProntProfAtend->s104_i_codigo = $iCodigoProntProfAtend;
          $oDaoProntProfAtend->alterar($iCodigoProntProfAtend);
        }

        if ( $oDaoProntProfAtend->erro_status == 0 ) {

          $oErro->sErro = $oDaoProntProfAtend->erro_sql;
          throw new DBException(  _M( MENSAGENS_FICHAATENDIMENTORPC . "erro_vincular_profissional", $oErro) );
        }
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_FICHAATENDIMENTORPC . "encaminhado_sucesso" ) );

    break;

    /**
     * Busca os dados do paciente de determinado prontuário
     */
    case 'buscarDadosPaciente':

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( MENSAGENS_FICHAATENDIMENTORPC . 'prontuario_nao_informado' ) );
      }

      $oProtuario = new Prontuario($oParam->iProntuario);
      $oPaciente  = $oProtuario->getCGS();

      $oDataAtual      = new DBDate( date("Y-m-d") );
      $iQuantidadeDias = DBDate::calculaIntervaloEntreDatas( $oDataAtual, $oPaciente->getDataNascimento(), 'd' );

      $oRetorno->oDadosPaciente                 = new stdClass();
      $oRetorno->oDadosPaciente->iCgs           = $oPaciente->getCodigo();
      $oRetorno->oDadosPaciente->sNome          = urlencode( $oPaciente->getNome() );
      $oRetorno->oDadosPaciente->sSexo          = urlencode( $oPaciente->getSexo() );
      $oRetorno->oDadosPaciente->dtNascimento   = $oPaciente->getDataNascimento()->convertTo(DBDate::DATA_PTBR);
      $oRetorno->oDadosPaciente->sIdadeCompleta = urlencode( DBDate::getIdadeCompleta($iQuantidadeDias) );
      $oRetorno->oDadosPaciente->sNomeMae       = urlencode( $oPaciente->getNomeMae() );
      $oRetorno->oDadosPaciente->sEndereco      = urlencode( $oPaciente->getEndereco() );
      $oRetorno->oDadosPaciente->iNumero        = $oPaciente->getNumero();
      $oRetorno->oDadosPaciente->sComplemento   = urlencode( $oPaciente->getComplemento() );
      $oRetorno->oDadosPaciente->sBairro        = urlencode( $oPaciente->getBairro() );
      $oRetorno->oDadosPaciente->sCep           = urlencode( $oPaciente->getCep() );
      $oRetorno->oDadosPaciente->sMunicipio     = urlencode( $oPaciente->getMunicipio() );
      $oRetorno->oDadosPaciente->sUF            = urlencode( $oPaciente->getUF() );
      $oRetorno->oDadosPaciente->sEstadoCivil   = urlencode( $oPaciente->getEstadoCivil() );

    break;

    /**
     * Busca todas as movimentações realizadas em determinado prontuário
     */
    case 'buscarMovimentacoes':

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( MENSAGENS_FICHAATENDIMENTORPC . 'prontuario_nao_informado' ) );
      }

      $aMovimentacoes = MovimentacaoFichaAtendimentoRepository::getMovimentacoesPorProntuario( $oParam->iProntuario );
      $oRetorno->aMovimentacoes = array();

      foreach ( $aMovimentacoes as $oMovimentacao ) {

        $oDadosMovimentacao = new stdClass();
        $oDadosMovimentacao->sUsuario           = urlencode( $oMovimentacao->getUsuarioSistema()->getNome() );
        $oDadosMovimentacao->sSetorAmbulatorial = urlencode( $oMovimentacao->getSetorAmbulatorial()->getDescricao() );
        $oDadosMovimentacao->dtMovimentacao     = $oMovimentacao->getData()->convertTo(DBDate::DATA_PTBR);
        $oDadosMovimentacao->sHoraMovimentacao  = urlencode( $oMovimentacao->getHora() );
        $oDadosMovimentacao->sObservacao        = urlencode( $oMovimentacao->getObservacao() );
        $oDadosMovimentacao->sSituacao          = urlencode( $oMovimentacao->getDescricaoSituacao() );
        $oDadosMovimentacao->iCodigo            = $oMovimentacao->getCodigo();

        $oRetorno->aMovimentacoes[] = $oDadosMovimentacao;
      }

    break;

    /**
     * Retorna todos os cartões sus de um determinado paciente
     */
    case 'buscarCartoesSus':

      if ( !isset($oParam->iPaciente) || empty($oParam->iPaciente) ) {
        throw new ParameterException( _M( MENSAGENS_FICHAATENDIMENTORPC . 'paciente_nao_informado' ) );
      }

      $oCgs         = new Cgs( $oParam->iPaciente );
      $aCartaoesSus = $oCgs->getCartaoSus();

      for ( $iContador = 0; $iContador < count($aCartaoesSus); $iContador++ ) {

        $aCartaoesSus[$iContador]->sCartaoSus     = urlencode( $aCartaoesSus[$iContador]->sCartaoSus );
        $aCartaoesSus[$iContador]->sTipoCartaoSus = urlencode( $aCartaoesSus[$iContador]->sTipoCartaoSus );
      }

      $oRetorno->aCartoesSus = $aCartaoesSus;

    break;


    case 'getProcedimentos':

      if ( empty($oParam->iProntuario) ) {
        throw new Exception( _M(MENSAGENS_FICHAATENDIMENTORPC . "prontuario_nao_informado") );
      }

      $sQuery = "sql_query_nolote_ext";

      /**
       * Verifica caso exista o parâmetro para utilizar a query que retorna todos os procedimentos, incluindo os que
       * estão no lote
       */
      if ( isset($oParam->lBuscaProcedimentosLote) ) {
        $sQuery = "sql_query_ext";
      }

      $iUsuario = db_getsession("DB_id_usuario");

      $sCampos  = "  sd29_i_codigo, sd29_d_data, sd29_c_hora, sd63_c_procedimento, sd63_c_nome, sd29_i_usuario, z01_nome, sd70_i_codigo";
      $sCampos .= " ,s135_i_codigo, sd70_c_nome, sd29_t_tratamento, sd29_i_procedimento, sd70_c_cid, sd29_sigilosa, sd27_i_rhcbo, z01_numcgm ";
      $sCampos .= " ,rh70_sequencial ,rh70_estrutural, rh70_descr, sd29_i_profissional ";
      $sWhere   = " sd29_i_prontuario = {$oParam->iProntuario} ";

      $oDaoProntProced  = new cl_prontproced_ext();
      $sSqlProcedimento = $oDaoProntProced->$sQuery( null, $sCampos, "sd29_i_codigo", $sWhere );
      $rsProcedimento   = db_query( $sSqlProcedimento );

      if ( !$rsProcedimento ) {

        $oErro->sErro  = pg_last_error();
        throw new Exception( _M(MENSAGENS_FICHAATENDIMENTORPC . "erro_buscar_procedimentos", $oErro) );
      }

      $oRetorno->aProcedimentos = array();

      $iLinhas  = pg_num_rows( $rsProcedimento );
      for ( $i = 0; $i < $iLinhas; $i++) {

        $oDados        = db_utils::fieldsMemory( $rsProcedimento, $i );
        $oProcedimento = new stdClass();

        $oData         = new DBDate($oDados->sd29_d_data);
        $lSigilosa     = $oDados->sd29_sigilosa == 't';
        $lMesmoUsuario = $oDados->sd29_i_usuario == $iUsuario;
        $sTratamento   = $oDados->sd29_t_tratamento;

        if ( $lSigilosa && !empty($sTratamento) ) {

          if ( !$lMesmoUsuario ) {

            $oUsuarioSistema           = UsuarioSistemaRepository::getPorCodigo( $iUsuario );
            $oCgm                      = $oUsuarioSistema->getCGM();
            $oEspecialidadeMedico      = new cl_especmedico();
            $sWhereEspecialidadeMedico = "sd27_i_rhcbo = {$oDados->sd27_i_rhcbo} and a.z01_numcgm = {$oCgm->getCodigo()}";
            $sSqlEspecialidadeMedico   = $oEspecialidadeMedico->sql_query( "", 1, "", $sWhereEspecialidadeMedico );
            $rsEspecialidadeMedico     = db_query($sSqlEspecialidadeMedico);

            if( !$rsEspecialidadeMedico ) {

              $oErro->sErro  = pg_last_error();
              throw new Exception( _M(MENSAGENS_FICHAATENDIMENTORPC . "erro_buscar_especialidades", $oErro) );
            }

            if( pg_num_rows($rsEspecialidadeMedico) == 0 ) {
              $sTratamento = "SIGILOSA";
            }
          }
        }

        $oProcedimento->iVinculoProcedimento = $oDados->sd29_i_codigo;
        $oProcedimento->sData                = $oData->convertTo( DBDate::DATA_PTBR );
        $oProcedimento->sHora                = $oDados->sd29_c_hora;
        $oProcedimento->iProcedimento        = $oDados->sd29_i_procedimento;
        $oProcedimento->sProcedimento        = $oDados->sd63_c_procedimento;
        $oProcedimento->sNomeProcedimento    = urlencode($oDados->sd63_c_nome);
        $oProcedimento->sProfissional        = urlencode($oDados->z01_nome);
        $oProcedimento->lPermiteManutencao   = $lMesmoUsuario;
        $oProcedimento->iCid                 = $oDados->sd70_i_codigo;
        $oProcedimento->sCid                 = urlencode($oDados->sd70_c_cid);
        $oProcedimento->sNomeCid             = urlencode($oDados->sd70_c_nome);
        $oProcedimento->sTratamento          = urlencode($sTratamento);
        $oProcedimento->lSigilosa            = $lSigilosa;

        $oProcedimento->iCodigoEspecialidade       = $oDados->rh70_sequencial;
        $oProcedimento->iEstruturalEspecialidade   = $oDados->rh70_estrutural;
        $oProcedimento->sEspecialidade             = urlencode($oDados->rh70_descr);
        $oProcedimento->iProfissionalEspecialidade = $oDados->sd29_i_profissional;

        $oRetorno->aProcedimentos[] = $oProcedimento;
      }

      break;

    case 'excluirProcedimento':

      if ( empty($oParam->iProntuario) ) {
        throw new Exception( _M(MENSAGENS_FICHAATENDIMENTORPC . "prontuario_nao_informado") );
      }

      $oDaoProntuarios = new cl_prontuarios();
      $sSqlProntuario  = $oDaoProntuarios->sql_query_file($oParam->iProntuario, "sd24_c_digitada");
      $rsProntuario    = db_query($sSqlProntuario);

      if ( !$rsProntuario || pg_num_rows($rsProntuario) == 0) {
        throw new Exception( _M(MENSAGENS_FICHAATENDIMENTORPC . "prontuario_nao_encontrado") );
      }

      if ( db_utils::fieldsMemory($rsProntuario, 0)->sd24_c_digitada == 'S') {
        throw new Exception( _M(MENSAGENS_FICHAATENDIMENTORPC . "prontuario_encerrado_sem_manutencao") );
      }

      $oDaoProntProcedCid = new cl_prontprocedcid();
      $oDaoProntProced    = new cl_prontproced();

      $oDaoProntProcedCid->excluir(null, "s135_i_prontproced = {$oParam->iCodigoProcedimento}");
      $oDaoProntProced->excluir($oParam->iCodigoProcedimento);

      $oRetorno->sMensagem = urlencode( _M(MENSAGENS_FICHAATENDIMENTORPC . "procedimento_excluido") );

    break;

    case 'buscarTriagemProntuario':

      if ( empty($oParam->iProntuario) ) {
        throw new Exception( _M(MENSAGENS_FICHAATENDIMENTORPC . "prontuario_nao_informado") );
      }

      $aTriagens = array();

      $oProntuario   = new Prontuario($oParam->iProntuario);
      $aProfissional = array();
      foreach ( $oProntuario->getTriagens() as $oTriagem ) {

        $oTriagemFaa = new stdClass();

        $oData                = new DBDate($oTriagem->getDataSistema());
        $oTriagemFaa->sData   = $oData->convertTo(DBDate::DATA_PTBR);
        $oTriagemFaa->sHora   = $oTriagem->getHoraSistema();
        $oTriagemFaa->iCodigo = $oTriagem->getCodigo();

        if ( !array_key_exists($oTriagem->getCboProfissional(), $aProfissional) ) {

          $sWhere  = " s152_i_cbosprofissional  = {$oTriagem->getCboProfissional()} ";
          $sWhere .= " and s152_i_codigo = {$oTriagem->getCodigo()} ";
          $sCampos = " sd03_i_codigo, z01_numcgm, z01_nome ";

          $oDaoTriagemAvulsa = new cl_sau_triagemavulsa();
          $sSqlProfissional  = $oDaoTriagemAvulsa->sql_query_grid( null, $sCampos, null, $sWhere );
          $rsProfissional    = db_query($sSqlProfissional);
          if ($rsProfissional && pg_num_rows($rsProfissional) > 0 ) {

            $oDados = db_utils::fieldsMemory( $rsProfissional, 0 );
            $aProfissional[ $oTriagem->getCboProfissional() ] = $oDados->z01_nome;
          }
        }

        $sProfissional = isset( $aProfissional[$oTriagem->getCboProfissional()] ) ? $aProfissional[ $oTriagem->getCboProfissional() ] : '';
        $oTriagemFaa->sProfissional = urlencode( $sProfissional );
        $oRetorno->aTriagem[]       = $oTriagemFaa;

      }

    break;

  }
  db_fim_transacao();

} catch ( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);


function buscaVinculoMedicoEspecialidade($iMedico, $iEspecialidade, $iUnidade) {

  $sWhere  = "     sd27_i_rhcbo    = {$iEspecialidade} ";
  $sWhere .= " and sd04_i_unidade  = {$iUnidade} ";
  $sWhere .= " and sd04_i_medico   = {$iMedico} ";
  $sWhere .= " and sd27_c_situacao = 'A' ";

  $oDao = new cl_especmedico();
  $sSql = $oDao->sql_query_especmedico(null, " sd27_i_codigo ", null, $sWhere);
  $rs   = db_query($sSql);

  if ( !$rs || pg_num_rows($rs) == 0) {
    return 'null';
  }

  return db_utils::fieldsMemory($rs, 0)->sd27_i_codigo ;
}