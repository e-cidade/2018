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

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$oRetorno->dtAtual = date( 'd/m/Y', db_getsession( "DB_datausu" ) );
$oRetorno->sMedico = '';
$oRetorno->iMedico = '';
$iDepartamento     = db_getsession( "DB_coddepto" );

$oErro = new stdClass();

buscaProfissionalSaude( $oRetorno );

define("ARQUIVO_MENSAGEM", "saude.ambulatorial.sau4_triagem.");

try {

  db_inicio_transacao();

  switch($oParam->exec) {

    /**
     * Retorna os procedimentos de triagem configurados
     * @return array $oRetorno->aProcedimentos
     */
    case 'getProcedimentosConfigurados':

      $oRetorno->aProcedimentos   = array();
      $oDaoProcedimentoTriagem    = db_utils::getDao("parametroprocedimentotriagem");
      $sCamposProcedimentoTriagem = "s166_sau_procedimento, sd63_c_procedimento, sd63_c_nome";
      $sSqlProcedimentoTriagem    = $oDaoProcedimentoTriagem->sql_query(
                                                                        null,
                                                                        $sCamposProcedimentoTriagem
                                                                       );
      $rsProcedimentoTriagem      = $oDaoProcedimentoTriagem->sql_record($sSqlProcedimentoTriagem);
      $iTotalProcedimentoTriagem  = $oDaoProcedimentoTriagem->numrows;

      if ( $iTotalProcedimentoTriagem > 0 ) {

        for ( $iContador = 0; $iContador < $iTotalProcedimentoTriagem; $iContador++ ) {

          $oDadosProcedimentosTriagem                 = db_utils::fieldsMemory($rsProcedimentoTriagem, $iContador);
          $oRetornoProcedimentoTriagem                = new stdClass();
          $oRetornoProcedimentoTriagem->iCodigo       = $oDadosProcedimentosTriagem->s166_sau_procedimento;
          $oRetornoProcedimentoTriagem->iProcedimento = urlencode($oDadosProcedimentosTriagem->sd63_c_procedimento);
          $oRetornoProcedimentoTriagem->sDescricao    = urlencode($oDadosProcedimentosTriagem->sd63_c_nome);
          $oRetorno->aProcedimentos[]                 = $oRetornoProcedimentoTriagem;
          unset($oRetornoProcedimentoTriagem);
        }
      }
      break;

    /**
     * Salva os procedimentos de triagem configurados
     * @param integer $oParam->iProcedimento
     */
    case 'salvarProcedimentos':

      $oDaoProcedimentoTriagem   = new cl_parametroprocedimentotriagem();
      $sWhereProcedimentoTriagem = "s166_sau_procedimento = {$oParam->iProcedimento}";
      $sSqlProcedimentoTriagem   = $oDaoProcedimentoTriagem->sql_query(
                                                                        null,
                                                                        "s166_sequencial, sd63_c_nome",
                                                                        null,
                                                                        $sWhereProcedimentoTriagem
                                                                      );
      $rsProcedimentoTriagem     = $oDaoProcedimentoTriagem->sql_record($sSqlProcedimentoTriagem);
      $oRetorno->message         = urlencode( _M(ARQUIVO_MENSAGEM . 'procedimento_salvo_parametros') );

      if ( $oDaoProcedimentoTriagem->numrows > 0 ) {

        $oDadosProcedimentosTriagem = db_utils::fieldsMemory($rsProcedimentoTriagem, 0);
        $oRetorno->status           = 2;
        $oErro                      = new stdClass();
        $oErro->sErro               = $oDadosProcedimentosTriagem->sd63_c_nome;
        $oRetorno->message          = urlencode( _M(ARQUIVO_MENSAGEM . 'procedimento_ja_cadastrado', $oErro) );
        unset($oDadosProcedimentosTriagem);
      } else {

        $oDaoProcedimentoTriagem->s166_sau_procedimento = $oParam->iProcedimento;
        $oDaoProcedimentoTriagem->incluir(null);

        if ( $oDaoProcedimentoTriagem->erro_status == "0" ) {
          throw new DBException($oDaoProcedimentoTriagem->erro_msg);
        }
      }

      break;

    /**
     * Exclui um ou mais procedimentos de triagem
     * @param array $oParam->aProcedimentos
     */
    case 'excluirProcedimentos':

      if ( isset($oParam->aProcedimentos) && count($oParam->aProcedimentos) > 0 ) {

        $oDaoProcedimentoTriagem   = new cl_parametroprocedimentotriagem();
        $sWhereProcedimentoTriagem = "s166_sau_procedimento in (" . implode(", ", $oParam->aProcedimentos) . ")";

        $oDaoProcedimentoTriagem->excluir(null, $sWhereProcedimentoTriagem);

        if ( $oDaoProcedimentoTriagem->erro_status == "0" ) {
          throw new DBException($oDaoProcedimentoTriagem->erro_msg);
        }

        $oRetorno->message = urlencode( _M(ARQUIVO_MENSAGEM . 'procedimento_excluido') );
      }

      break;

    /**
     * Salva ou altera uma Triagem
     */
    case 'salvarTriagem':

      validaDados( $oParam );
      buscaProfissionalSaude( $oRetorno );

      $iCbosProfissional = verificaCbosProfissional( $oParam );

      if ( empty($iCbosProfissional) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM . 'profissional_sem_cbos') );
      }

      $oDataSistema   = new DBDate( date("Y-m-d", db_getSession("DB_datausu")) );
      $oTriagemAvulsa = new TriagemAvulsa( $oParam->iTriagem );
      $oTriagemAvulsa->setCboProfissional($iCbosProfissional);
      $oTriagemAvulsa->setCgsUnd($oParam->iCgsUnd);
      $oTriagemAvulsa->setLogin(db_getSession("DB_id_usuario"));
      $oTriagemAvulsa->setPressaoSistolica($oParam->iPressaoSistolica);
      $oTriagemAvulsa->setPressaoDiastolica($oParam->iPressaoDiastolica);
      $oTriagemAvulsa->setCintura($oParam->iCintura);
      $oTriagemAvulsa->setPeso($oParam->nPeso);
      $oTriagemAvulsa->setAltura($oParam->iAltura);
      $oTriagemAvulsa->setGlicemia($oParam->iGlicemia == '' ? '0' : $oParam->iGlicemia);
      $oTriagemAvulsa->setAlimentacaoExameGlicose($oParam->iMomentoColeta);
      $oTriagemAvulsa->setDataConsulta(new DBDate( $oParam->dtDataConsulta ));
      $oTriagemAvulsa->setDataSistema($oDataSistema);
      $oTriagemAvulsa->setHoraSistema(date("H:i"));
      $oTriagemAvulsa->setTemperatura($oParam->nTemperatura);
      $oTriagemAvulsa->setObjetivo(db_stdClass::normalizeStringJsonEscapeString( $oParam->sTextObjetivo ));
      $oTriagemAvulsa->setPerimetroCefalico($oParam->iPerimetroCefalico);
      $oTriagemAvulsa->setFrequenciaRespiratoria($oParam->iFrequenciaRespiratoria);
      $oTriagemAvulsa->setFrequenciaCardiaca($oParam->iFrequenciaCardiaca);
      $oTriagemAvulsa->setUltimaMenstruacao($oParam->dtDUM);
      $oTriagemAvulsa->setSaturacao($oParam->iSaturacao);
      $oTriagemAvulsa->setSubjetivo(db_stdClass::normalizeStringJsonEscapeString($oParam->sTextSubjetivo));
      $oTriagemAvulsa->salvar();

      if ( !empty($oParam->iPrioridade) && !empty($oParam->iProntuario) ) {

        $oDaoProntuariosRisco   = new cl_prontuariosclassificacaorisco();
        $sWhereProntuariosRisco = "sd101_prontuarios = {$oParam->iProntuario}";
        $sSqlProntuariosRisco   = $oDaoProntuariosRisco->sql_query_file(null, 'sd101_codigo', null, $sWhereProntuariosRisco);
        $rsProntuariosRisco     = db_query( $sSqlProntuariosRisco );

        if ( !$rsProntuariosRisco ) {

          $oErro->sErro = pg_last_error();
          throw new DBException(  _M(ARQUIVO_MENSAGEM . "erro_buscar_classificacao_risco", $oErro) );
        }

        $oDaoProntuariosRisco->sd101_classificacaorisco = $oParam->iPrioridade;

        if ( pg_num_rows( $rsProntuariosRisco ) > 0 ) {

          $iProntuariosRisco                  = db_utils::fieldsMemory( $rsProntuariosRisco, 0)->sd101_codigo;
          $oDaoProntuariosRisco->sd101_codigo = $iProntuariosRisco;
          $oDaoProntuariosRisco->alterar($iProntuariosRisco);
        } else {

          $oDaoProntuariosRisco->sd101_codigo      = null;
          $oDaoProntuariosRisco->sd101_prontuarios = $oParam->iProntuario;
          $oDaoProntuariosRisco->incluir(null);
        }

      }

      $oRetorno->iTriagemAvulsa = $oTriagemAvulsa->getCodigo();
      $oRetorno->message        = _M(ARQUIVO_MENSAGEM . 'triagem_salva');
      break;

    /**
     * Verifica se o CGS tem triagem mas ainda não consultou e retornar os dados da triagem
     */
    case 'buscaTriagemValida':

      $oRetorno->lTemTriagem     = false;
      $oRetorno->lSomenteTriagem = false;

      if ( !isset($oParam->iCgsUnd) || $oParam->iCgsUnd == "" ) {
        throw new DBException( _M( ARQUIVO_MENSAGEM . "informe_cgs") );
      }

      $oCgs                 = new Cgs( $oParam->iCgsUnd );
      $oRetorno->sSexo      = $oCgs->getSexo();
      $oRetorno->iCgsUnd    = $oCgs->getCodigo();


      $sCartaoSus = "";
      if ( !isset( $oParam->iCartaoSus ) || empty( $oParam->iCartaoSus ) ) {

        $aCartoesSus = $oCgs->getCartaoSus();
        foreach ($aCartoesSus as $oCartaoSus ) {

          $sCartaoSus = $oCartaoSus->sCartaoSus;

          if ( $oCartaoSus->sTipoCartaoSus == 'D') {
            break;
          }
        }
      } else {
        $sCartaoSus = $oParam->iCartaoSus;
      }

      $oRetorno->sCartaoSus = $sCartaoSus;

      $iUsuarioLogado = db_getSession("DB_id_usuario");
      $dtDataSistema  = date("Y-m-d", db_getSession("DB_datausu"));

      $oDaoTriagemAvulsa    = new cl_sau_triagemavulsa();
      $sCamposTriagem       = "s152_i_codigo, s155_i_codigo, sd29_i_codigo, sd101_classificacaorisco";
      $sOrderBy             = "1 desc limit 1";
      $sWhereTriagem        = "     s152_i_cgsund      = {$oParam->iCgsUnd} ";
      $sWhereTriagem       .= " and s152_i_login       = {$iUsuarioLogado} ";
      $sWhereTriagem       .= " and s152_d_datasistema = '{$dtDataSistema}' ";

      if ( isset($oParam->iProntuario) && !empty($oParam->iProntuario)) {
        $sWhereTriagem = " s155_i_prontuario = {$oParam->iProntuario}";
      }

      if ( isset($oParam->iTriagem) && !empty($oParam->iTriagem) ) {
        $sWhereTriagem = " s152_i_codigo = {$oParam->iTriagem} ";
      }

      $sSqlTriagemConsulta  = $oDaoTriagemAvulsa->sql_query_consulta(null, $sCamposTriagem, $sOrderBy, $sWhereTriagem);
      $rsTriagemConsulta    = db_query( $sSqlTriagemConsulta );

      if ( !$rsTriagemConsulta ) {

        $oErro = new stdClass();
        $oErro->sErro = $oDaoTriagemAvulsa->erro_msg;
        throw new DBException( _M( ARQUIVO_MENSAGEM . "erro_buscar_triagem", $oErro) );
      }

      if ( pg_num_rows($rsTriagemConsulta) > 0 ) {

        $oRetorno->lTemTriagem = true;
        $oDadosRetorno         = db_utils::fieldsMemory($rsTriagemConsulta, 0);
        $oTriagemAvulsa        = new TriagemAvulsa( $oDadosRetorno->s152_i_codigo );

        $oRetorno->iCodigo                  = $oTriagemAvulsa->getCodigo();
        $oRetorno->iCbosProfissional        = $oTriagemAvulsa->getCboProfissional();
        $oRetorno->iLogin                   = $oTriagemAvulsa->getLogin();
        $oRetorno->iPressaoSistolica        = $oTriagemAvulsa->getPressaoSistolica();
        $oRetorno->iPressaoDiastolica       = $oTriagemAvulsa->getPressaoDiastolica();
        $oRetorno->iCintura                 = $oTriagemAvulsa->getCintura();
        $oRetorno->nPeso                    = $oTriagemAvulsa->getPeso() != null ? intval ($oTriagemAvulsa->getPeso() ) : null;
        $oRetorno->iAltura                  = $oTriagemAvulsa->getAltura();
        $oRetorno->iGlicemia                = $oTriagemAvulsa->getGlicemia() == 0 ? '' : $oTriagemAvulsa->getGlicemia();
        $oRetorno->iAlimentacaoExameGlicose = $oTriagemAvulsa->getAlimentacaoExameGlicose();
        $oRetorno->dtDataConsulta           = urlencode($oTriagemAvulsa->getDataConsulta());
        $oRetorno->dtDataSistema            = urlencode($oTriagemAvulsa->getDataSistema());
        $oRetorno->dtHoraSistema            = urlencode($oTriagemAvulsa->getHoraSistema());
        $oRetorno->sObjetivo                = urlencode($oTriagemAvulsa->getObjetivo());
        $oRetorno->iPerimetroCefalico       = urlencode($oTriagemAvulsa->getPerimetroCefalico());
        $oRetorno->iFrequenciaRespiratoria  = urlencode($oTriagemAvulsa->getFrequenciaRespiratoria());
        $oRetorno->iFrequenciaCardiaca      = urlencode($oTriagemAvulsa->getFrequenciaCardiaca());
        $oRetorno->dtDUM                    = urlencode($oTriagemAvulsa->getUltimaMenstruacao());
        $oRetorno->iSaturacao               = urlencode($oTriagemAvulsa->getSaturacao());
        $oRetorno->sSubjetivo               = urlencode($oTriagemAvulsa->getSubjetivo());

        $nTemperatura = $oTriagemAvulsa->getTemperatura() == '' ? $oTriagemAvulsa->getTemperatura()
                                                                : intval ($oTriagemAvulsa->getTemperatura() );
        $oRetorno->nTemperatura   = $nTemperatura;
        $oMedico                  = $oTriagemAvulsa->getMedico();
        $oRetorno->iMedico        = $oMedico->getCodigo();
        $oRetorno->sMedico        = urlencode($oMedico->getNome());

        if( empty( $oDadosRetorno->s155_i_codigo ) ) {
          $oRetorno->lSomenteTriagem = true;
        }

        $oRetorno->iClassificacaoRisco = $oDadosRetorno->sd101_classificacaorisco;
      }

      break;

    case 'buscaCBOS':

      $oRetorno->aCbos = array();
      $oDaoFarCbos     = new cl_far_cbos();
      $sSqlFarCbos     = $oDaoFarCbos->sql_query_file();
      $rsFarCbos       = db_query( $sSqlFarCbos );

      if( !$rsFarCbos ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_last_error( $rsFarCbos );
        throw new DBException( _M( 'ARQUIVO_MENSAGEM' . 'erro_buscar_cbos', $oMensagem ) );
      }

      if( pg_num_rows( $rsFarCbos ) > 0 ) {

        $iTotalLinhas = pg_num_rows( $rsFarCbos );
        for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

          $oDadosRetorno           = db_utils::fieldsMemory( $rsFarCbos, $iContador );
          $oDadosCbos              = new stdClass();
          $oDadosCbos->iCbos       = $oDadosRetorno->fa53_i_codigo;
          $oDadosCbos->sCbos       = urlencode( $oDadosRetorno->fa53_c_descr );
          $oDadosCbos->sEstrutural = urlencode( $oDadosRetorno->fa53_c_estrutural );
          $oRetorno->aCbos[]       = $oDadosCbos;
        }
      }

      break;

    case 'dadosDepartamento':

      $oDepartamento           = new DBDepartamento( $iDepartamento );
      $oRetorno->iDepartamento = $oDepartamento->getCodigo();
      $oRetorno->sDepartamento = urlencode( $oDepartamento->getNomeDepartamento() );

      break;

    case 'dadosProfissional':

      if( !isset( $oParam->iMedico ) || empty( $oParam->iMedico ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . 'medico_nao_informado' ) );
      }

      $oRetorno->iUnidadeMedicos = '';
      $oRetorno->iCbos           = '';

      $oDaoUnidadeMedicos    = new cl_unidademedicos();
      $sWhereUnidadeMedicos  = "sd04_i_unidade = {$iDepartamento} and sd04_i_medico = {$oParam->iMedico}";
      $sCamposUnidadeMedicos = "distinct sd04_i_codigo, fa54_i_cbos";
      $sSqlUnidadeMedicos    = $oDaoUnidadeMedicos->sql_query_cbos(
                                                                    null,
                                                                    $sCamposUnidadeMedicos,
                                                                    null,
                                                                    $sWhereUnidadeMedicos
                                                                  );
      $rsUnidadeMedicos = db_query( $sSqlUnidadeMedicos );

      if( !$rsUnidadeMedicos ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error( $rsUnidadeMedicos );
        throw new DBException( _M( ARQUIVO_MENSAGEM . 'erro_buscar_cbos', $oErro ) );
      }

      $oRetorno->aEspecialidades = array();

      if( pg_num_rows( $rsUnidadeMedicos ) > 0 ) {

        $oDadosCbos                = db_utils::fieldsMemory( $rsUnidadeMedicos, 0 );
        $oRetorno->iUnidadeMedicos = $oDadosCbos->sd04_i_codigo;
        $oRetorno->iCbos           = $oDadosCbos->fa54_i_cbos;

        $oDaoEspecmedico = new cl_especmedico();
        $sListaCampos    = "sd27_i_codigo as codigo, rh70_sequencial as codigo_especialidade, rh70_estrutural as estrutural, rh70_descr as descricao, sd27_b_principal as principal";
        $sWhereEspecialidades = "sd27_i_undmed = {$oDadosCbos->sd04_i_codigo} AND sd27_c_situacao = 'A'";
        $sSqlEspecMedico = $oDaoEspecmedico->sql_query_especmedico(null, $sListaCampos, "rh70_descr", $sWhereEspecialidades);

        $rsEspecialidadeMedico = $oDaoEspecmedico->sql_record($sSqlEspecMedico);

        if ( $oDaoEspecmedico->numrows > 0) {

          for ( $iEspecialidade = 0; $iEspecialidade < $oDaoEspecmedico->numrows; $iEspecialidade++ ) {

            $oEspecialidade              = db_utils::fieldsMemory($rsEspecialidadeMedico, $iEspecialidade, false, false, true);
            $oEspecialidade->principal   = $oEspecialidade->principal == 't';
            $oRetorno->aEspecialidades[] = $oEspecialidade;
          }
        }
      }

      break;

    /**
    * Buscamos todos os procedimentos de triagem configurados, para incluir um novo registro para cada na tabela
    * prontproced, e armazenamos em um array com os codigos
    */
    case 'buscaProcedimentosTriagem':

      $oRetorno->aProcedimentosTriagem = array();
      $oDaoProcedimentoTriagem         = new cl_parametroprocedimentotriagem();
      $sSqlProcedimentoTriagem         = $oDaoProcedimentoTriagem->sql_query(null, "s166_sau_procedimento");
      $rsProcedimentoTriagem           = db_query( $sSqlProcedimentoTriagem );
      $iTotalProcedimentoTriagem       = pg_num_rows( $rsProcedimentoTriagem );

      if ($iTotalProcedimentoTriagem  > 0 ) {

        for ( $iContador = 0; $iContador < $iTotalProcedimentoTriagem; $iContador++ ) {
          $oRetorno->aProcedimentosTriagem[] = db_utils::fieldsMemory($rsProcedimentoTriagem, $iContador)->s166_sau_procedimento;
        }
      }


      break;

    case 'salvarEspecialidadeProcedimentos':

      if( !empty($oParam->iEspecialidade) ) {


        $oDaoProntproced = new cl_prontproced();

        foreach ( $oParam->aProcedimentosTriagem as $iProcedimento ) {

          $sWhere  = "     sd29_i_prontuario = {$oParam->iProntuario} ";
          $sWhere .= " and sd29_i_procedimento = $iProcedimento";

          $rsExisteProcedimento = db_query($oDaoProntproced->sql_query_file(null, "1", null, $sWhere));
          if ($rsExisteProcedimento && pg_num_rows($rsExisteProcedimento) > 0) {
            continue;
          }

          $oDaoProntproced->sd29_i_prontuario   = $oParam->iProntuario;
          $oDaoProntproced->sd29_i_procedimento = $iProcedimento;
          $oDaoProntproced->sd29_i_profissional = $oParam->iEspecialidade;
          $oDaoProntproced->sd29_i_usuario      = DB_getsession("DB_id_usuario");
          $oDaoProntproced->sd29_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
          $oDaoProntproced->sd29_c_hora         = db_hora();
          $oDaoProntproced->sd29_d_cadastro     = date("Y-m-d");
          $oDaoProntproced->sd29_c_cadastro     = date("H:i");
          $oDaoProntproced->sd29_t_diagnostico  = '';
          $oDaoProntproced->sd29_sigilosa       = "false";
          $oDaoProntproced->incluir( null );

          if( $oDaoProntproced->erro_status == '0' ) {

            $oErro->sErro = $oDaoProntproced->erro_msg;
            throw new DBException( _M(ARQUIVO_MENSAGEM . "erro_incluir_procedimento", $oErro) );
          }
        }

        $oRetorno->message = _M( ARQUIVO_MENSAGEM . "procedimento_salvo" );
      }
      break;

    case 'buscaCgs':

      if( !isset( $oParam->iCgs ) || empty( $oParam->iCgs ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "informe_cgs" ) );
      }

      $oCgs           = new Cgs( $oParam->iCgs );
      $oRetorno->iCgs = $oCgs->getCodigo();
      $oRetorno->sCgs = urlencode( $oCgs->getNome() );

      $oDaoTriagem   = new cl_sau_triagemavulsa();
      $sWhereTriagem = "s152_i_cgsund = {$oParam->iCgs} AND s152_dum is not null";
      $sSqlTriagem   = $oDaoTriagem->sql_query_file(null, 's152_dum', 's152_i_codigo desc', $sWhereTriagem);
      $rsTriagem     = db_query($sSqlTriagem);

      if(!$rsTriagem) {
        throw new DBException(_M(ARQUIVO_MENSAGEM . 'erro_buscar_dum'));
      }

      if(pg_num_rows($rsTriagem) == 0) {
        $oRetorno->dtUltimaDUM = null;
      }

      if(!empty($oRetorno->dtUltimaDUM)) {

        $oDataDUM              = new DBDate(db_utils::fieldsMemory($rsTriagem, 0)->s152_dum);
        $oRetorno->dtUltimaDUM = $oDataDUM->getDate(DBDate::DATA_PTBR);
      }

      break;

    case 'salvarTriagemProntuario':

      if( !isset( $oParam->iTriagem ) || empty( $oParam->iTriagem ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "triagem_nao_encontrado" ) );
      }

      if( !isset( $oParam->iProntuario ) || empty( $oParam->iProntuario ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "prontuario_nao_encontrado" ) );
      }

      $oDaoTriagemProntuario                       = new cl_sau_triagemavulsaprontuario();
      $oDaoTriagemProntuario->s155_i_triagemavulsa = $oParam->iTriagem;
      $oDaoTriagemProntuario->s155_i_prontuario    = $oParam->iProntuario;
      $oDaoTriagemProntuario->incluir( null );

      if ( $oDaoTriagemProntuario->erro_status == '0' ) {

        $oErro->sErro = $oDaoTriagemProntuario->erro_msg;
        throw new DBException( _M(ARQUIVO_MENSAGEM . "erro_incluir_triagem_prontuario", $oErro) );
      }

      break;

    case 'buscaEspecialidade' :

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "prontuario_nao_encontrado" ) );
      }

      $oRetorno->iEspecialidade = null;
      $oRetorno->sEspecialidade = '';
      $oDaoProntProced          = new cl_prontproced();
      $sCamposProntProced       = "rh70_sequencial, rh70_descr";
      $sWhereProntProced        = "sd29_i_prontuario = {$oParam->iProntuario}";
      $sSqlProntProced          = $oDaoProntProced->sql_query_especialidade( null, $sCamposProntProced, null, $sWhereProntProced );
      $rsProntProced            = db_query( $sSqlProntProced );

      if ( !$rsProntProced ) {

        $oErro->sErro = pg_last_error( $rsProntProced );
        throw new DBException( _M(ARQUIVO_MENSAGEM . "erro_buscar_especialidade_medico", $oErro) );
      }

      if( pg_num_rows( $rsProntProced ) > 0 ) {

        $oDadosEspecialidade      = db_utils::fieldsMemory( $rsProntProced, 0);
        $oRetorno->iEspecialidade = $oDadosEspecialidade->rh70_sequencial;
        $oRetorno->sEspecialidade = urlencode( $oDadosEspecialidade->rh70_descr );
      }

      break;

    case 'buscaPrioridadesAtendimento':

      $oDaoClassificacaoRisco  = new cl_classificacaorisco();
      $sSqlClassificacaoRisco  = $oDaoClassificacaoRisco->sql_query_file(null, '*', 'sd78_peso desc', null);
      $rsClassificacaoRisco    = db_query( $sSqlClassificacaoRisco );

      if ( !$rsClassificacaoRisco ) {

        $oErro->sErro = pg_last_error();
        throw new DBException(  _M(ARQUIVO_MENSAGEM . "erro_buscar_classificacao_risco", $oErro) );
      }

      $oRetorno->aClassificacoesRisco = array();

      $iTotalClassificacaoRisco = pg_num_rows( $rsClassificacaoRisco );
      for( $iContador = 0; $iContador < $iTotalClassificacaoRisco;  $iContador++ ) {

        $oDadosClassificacaoRisco        = db_utils::fieldsMemory( $rsClassificacaoRisco, $iContador );
        $oClassificacaoRisco             = new stdClass();
        $oClassificacaoRisco->iCodigo    = $oDadosClassificacaoRisco->sd78_codigo;
        $oClassificacaoRisco->sDescricao = urlencode( $oDadosClassificacaoRisco->sd78_descricao );
        $oClassificacaoRisco->sCor       = $oDadosClassificacaoRisco->sd78_cor;

        $oRetorno->aClassificacoesRisco[] = $oClassificacaoRisco;
      }
  }

  db_fim_transacao();
} catch ( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

/**
 * Valida os dados enviados por parâmetros
 * @param  Object $oParam
 */
function validaDados( $oParam ) {

  /**
   * Valida quantidade de números decimais do peso informado
   */
  $aPeso = explode(".", $oParam->nPeso);
  if ( count($aPeso) == 2 ) {

    if ( count($aPeso[1]) > 3) {
      throw new Exception( _M( ARQUIVO_MENSAGEM . "peso_acima_casa_decimais") );
    }
  }

  if ( $oParam->nPeso > 999.999 ) {
    throw new Exception( _M( ARQUIVO_MENSAGEM . "peso_superior") );
  }

  if ( $oParam->iAltura > 250 ) {
    throw new Exception( _M( ARQUIVO_MENSAGEM . "altura_superior") );
  }

  if ( !isset($oParam->iProfissional) || $oParam->iProfissional == '' ) {
    throw new Exception( _M( ARQUIVO_MENSAGEM . "selecione_profissional") );
  }

  if ( !isset($oParam->iCbos) || $oParam->iCbos == '' ) {
    throw new Exception( _M( ARQUIVO_MENSAGEM . "selecione_cbos" ) );
  }

  if ( !isset($oParam->dtDataConsulta) || $oParam->dtDataConsulta == '' ) {
    throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_data_consulta" ) );
  }
}

/**
 * Verifica se profissional logado é um profissional da saude e retorna o código, o nome do médico e uma flag
 * dizendo que é um profissional da saude
 * @param stdClass $oRetorno
 */
function buscaProfissionalSaude( $oRetorno ) {

  $oRetorno->lProfissionalSaude = false;

  $oDaoMedicos     = new cl_medicos();
  $sCamposMedicos  = "z01_nome, sd03_i_codigo";
  $sWhereMedicos   = " sd02_i_codigo = ".db_getsession("DB_coddepto");
  $sWhereMedicos  .= " and db_usuacgm.id_usuario = ".db_getsession("DB_id_usuario");
  $sSqlMedicos     = $oDaoMedicos->sql_query_profissional_saude(null, $sCamposMedicos, null, $sWhereMedicos);
  $rsMedicos       = db_query( $sSqlMedicos );

  if ( !$rsMedicos ) {

    $oErro        = new stdClass();
    $oErro->sErro = $oDaoMedicos->erro_msg;
    throw new Exception( _M( ARQUIVO_MENSAGEM . "erro_buscar_medico", $oErro ) );
  }

  if ( pg_num_rows($rsMedicos) > 0 ) {

    $oProfissional     = db_utils::fieldsmemory($rsMedicos, 0);
    $oRetorno->sMedico = urlencode($oProfissional->z01_nome);
    $oRetorno->iMedico = $oProfissional->sd03_i_codigo;
    $oRetorno->lProfissionalSaude = true;
  }

  return $oRetorno;
}

/**
 * Verifica se existe CBO Profissional através da unidademedicos e cbos informados.
 * Retorna o código do CBO Profissional.
 * @param  Object $oParam
 * @return integer
 */
function verificaCbosProfissional( $oParam ) {

  $oDaoCbosProfissional    = new cl_far_cbosprofissional();
  $sWhereCbosProfissional  = "     fa54_i_unidademedico = {$oParam->iUnidadeMedicos}";
  $sWhereCbosProfissional .= " and fa54_i_cbos = {$oParam->iCbos}";
  $sSqlCbosProfissional    = $oDaoCbosProfissional->sql_query_file(null, "fa54_i_codigo", null, $sWhereCbosProfissional);
  $rsCbosProfissional      = db_query( $sSqlCbosProfissional );
  $iCbosProfissional       = null;

  if ( !$rsCbosProfissional ) {

    $oErro        = new stdClass();
    $oErro->sErro = $oDaoCbosProfissional->erro_msg;
    throw new Exception( _M( ARQUIVO_MENSAGEM . "erro_buscar_cbos_profissional", $oErro ) );
  }

  if ( pg_num_rows($rsCbosProfissional) > 0 ) {
    $iCbosProfissional = db_utils::fieldsmemory($rsCbosProfissional, 0)->fa54_i_codigo;
  }

  return $iCbosProfissional;
}
echo $oJson->encode($oRetorno);
