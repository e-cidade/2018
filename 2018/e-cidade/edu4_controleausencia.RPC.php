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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_cursoedu_classe.php"));

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;
$oRetorno->message  = '';

$iEscola            = db_getsession("DB_coddepto");
if (isset($oParam->iEscola) && !empty($oParam->iEscola)) {
  $iEscola = $oParam->iEscola;
}

$oUsuario = new UsuarioSistema(db_getsession("DB_id_usuario"));

$iModuloEscola      = 1100747;

try {

  switch ($oParam->exec) {

    case 'pesquisaTiposAusencia':

      $oDaoAusencia = new cl_tipoausencia();
      $sSqlAusencia = $oDaoAusencia->sql_query_file();
      $rsAusencia   = $oDaoAusencia->sql_record($sSqlAusencia);
      $iRegistros   = $oDaoAusencia->numrows;

      if ($iRegistros == 0) {

        $sMsg  = "Não há tipos de ausência cadastrada.";
        $sMsg .= "Cadastre no Menu: Secretaria da Educação > Cadastros > Tipo de Ausência > Inclusão";

        $oRetorno->message = $sMsg;
        $oRetorno->status  = 2;
      }

      for($i = 0; $i < $iRegistros; $i++) {

        $oDados               = db_utils::fieldsMemory($rsAusencia, $i);
        $oAusencia            = new stdClass();
        $oAusencia->codigo    = $oDados->ed320_sequencial;
        $oAusencia->descricao = urlencode($oDados->ed320_descricao);
        $oRetorno->dados[]    = $oAusencia;
      }

      break;
    case 'salvarDadosAusencia':

      $iCgmRecHumano = $oParam->iCGM;
      $sObservacao   = '';
      $sMsgSucesso   = "Ausência salva com sucesso.";

      if (!empty($oParam->sObservacao)) {
        $sObservacao = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
      }

      $oRetorno->iCodigoAusenciaDocente = "";

      db_inicio_transacao();

      $oTipoAusencia = new TipoAusencia($oParam->iAusencia);
      $oDataInicial  = new DBDate($oParam->dtInicio);

      $oEscola    = EscolaRepository::getEscolaByCodigo ( $iEscola );
      $oDataFinal = null;
      if (!empty($oParam->dtFinal)) {
        $oDataFinal = new DBDate($oParam->dtFinal);
      }

      $oRecursoHumanoAusente = new RecursoHumanoAusente();
      $oProfissionalEscola   = ProfissionalEscolaRepository::getUltimoVinculoByRecHumanoEscola( $oParam->iRecHumano, $oEscola );
      $oRecursoHumanoAusente->setProfissionalEscola($oProfissionalEscola);
      if (!empty($oParam->iCodigoAusenciaRechumano)) {
        $oRecursoHumanoAusente = new RecursoHumanoAusente($oParam->iCodigoAusenciaRechumano);
      }

      $oRecursoHumanoAusente->setTipoAusencia($oTipoAusencia);
      $oRecursoHumanoAusente->setObservacao($sObservacao);
      $oRecursoHumanoAusente->setUsuarioSistema($oUsuario);
      $oRecursoHumanoAusente->setDataInicio($oDataInicial);
      $oRecursoHumanoAusente->setDataFim($oDataFinal);
      $oRecursoHumanoAusente->salvar();

      if ( $oRecursoHumanoAusente->isDocente() ) {

        $oAusencia     = new AusenciaDocente();
        if (!empty($oParam->iCodigoAusenciaDocente)) {

          $oAusencia = new AusenciaDocente($oParam->iCodigoAusenciaDocente);
          $sMsgSucesso = "Ausência alterada com sucesso.";
        }
        $oAusencia->setDataInicial($oDataInicial);
        $oAusencia->setDataFinal($oDataFinal);
        $oAusencia->setTipoAusencia($oTipoAusencia);
        $oAusencia->setObservacao($sObservacao);
        $oAusencia->setDocente(new ProfessorVO($oParam->iRecHumano, $iCgmRecHumano));
        $oAusencia->setUsuario($oUsuario);
        $oAusencia->setEscola($oProfissionalEscola->getEscola());

        $oAusencia->salvar();
        $oRetorno->iCodigoAusenciaDocente = $oAusencia->getCodigo();
      }

      db_fim_transacao();

      $oRetorno->message = $sMsgSucesso;
      $oRetorno->iCodigoAusenciaRechumano = $oRecursoHumanoAusente->getCodigo();

      unset($oAusencia);

      break;

    case 'carregaDadosAusencia':

      if (empty($oParam->iCodigo)) {
        throw new BusinessException('Houve um erro no carregamento do código da ausencia.\n Contate o Suporte.');
      }

      $oAusencia     = new RecursoHumanoAusente($oParam->iCodigo);
      $oProfissional = $oAusencia->getProfissionalEscola();
      $oCgm          = CgmFactory::getInstance(null, $oParam->iCgm);

      $oDadosAusencia              = new stdClass();
      $oDadosAusencia->iCodigo     = $oAusencia->getCodigo();
      $oDadosAusencia->iRecHumano  = $oProfissional->getCodigoProfissional();
      $oDadosAusencia->sNome       = urlencode($oCgm->getNome());
      $oDadosAusencia->iCgm        = $oParam->iCgm;
      $oDadosAusencia->iAusencia   = $oAusencia->getTipoAusencia()->getCodigo();
      $oDadosAusencia->dtInicio    = urlencode($oAusencia->getDataInicio()->getDate(DBDate::DATA_PTBR));
      $oDadosAusencia->dtFinal     = null;
      $oDadosAusencia->lDocente    = $oAusencia->isDocente();
      $oDadosAusencia->sObservacao = urlencode($oAusencia->getObservacao());

      $dtFinal = $oAusencia->getDataFim();
      if (!empty($dtFinal)) {
        $oDadosAusencia->dtFinal = urlencode($dtFinal->getDate(DBDate::DATA_PTBR));
      }

      $oRetorno->oAusencia = $oDadosAusencia;

      unset($oAusencia);

      break;
    case 'excluirDadosAusencia':

      db_inicio_transacao();


      $oProfissional = new RecursoHumanoAusente($oParam->iCodigoAusenciaRechumano);
      $oProfissional->excluir();

      if ( !empty($oParam->iCodigoAusenciaDocente) ) {

        $oAusencia = new AusenciaDocente($oParam->iCodigoAusenciaDocente);
        $oAusencia->excluir();
      }

      $oRetorno->message = "Ausência Excluída com sucesso.";
      db_fim_transacao();

      break;

    case 'turmasDocente':

      if( !isset( $oParam->sDataInicial ) || empty( $oParam->sDataInicial ) ) {

        $sMensagem = "É necessário informar a data inicial de ausência do docente na aba 'Ausências e Substituições'.";
        throw new ParameterException( $sMensagem );
      }

      $oDataInicial = new DBDate( $oParam->sDataInicial );

      $oDaoRegencia = new cl_regenciahorario();
      $sWhere       = "     ed58_i_rechumano = {$oParam->iRecHumano}";
      $sWhere      .= " and ed57_i_escola    = {$iEscola}";
      $sWhere      .= " and ed52_i_ano       = " . $oDataInicial->getAno();
      $sWhere      .= " and ed58_ativo is true";
      $sWhere      .= " and ed33_ativo is true";

      $sCampos    = "distinct ed57_i_codigo, trim(ed57_c_descr) as ed57_c_descr ";
      $sSqlTurmas = $oDaoRegencia->sql_query_diario_classe_periodo(null, $sCampos, null, $sWhere);
      $rsTurma    = $oDaoRegencia->sql_record($sSqlTurmas);
      $iRegistros = $oDaoRegencia->numrows;

      if ($iRegistros == 0) {

        $oRetorno->status  = 2;
        $oRetorno->message = "Nenhuma turma encontrada para o professor ausente.";
      }

      for($i = 0; $i < $iRegistros; $i++) {

        $oDadosTurma       = db_utils::fieldsMemory($rsTurma, $i);
        $oTurma            = new stdClass();
        $oTurma->codigo    = $oDadosTurma->ed57_i_codigo;
        $oTurma->descricao = urlencode($oDadosTurma->ed57_c_descr);

        $oRetorno->dados[] = $oTurma;
      }

      break;

    case 'disciplinasTurma':

      $oTurma               = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oDocenteAusente      = new AusenciaDocente($oParam->iCodigo);
      $aRegenciasLecionadas = $oDocenteAusente->getDisciplinasLecionadaTurma($oTurma);

      if (count($aRegenciasLecionadas) > 0) {

        foreach ($aRegenciasLecionadas as $oRegencia) {

          $aSubstitutoRegencia    = $oDocenteAusente->getDocenteSubstitutoPorRegencia($oRegencia);
          $oDisciplina            = new stdClass();
          $lNaoIncluirDisciplina  = false;

          if (count($aSubstitutoRegencia) > 0) {

            foreach ($aSubstitutoRegencia as $oSubstituto) {

              if ($oSubstituto->getRegencia()->getCodigo() == $oRegencia->getCodigo()
                  && $oSubstituto->getTipoVinculo() == 2) {

                $lNaoIncluirDisciplina = true;
                break;
              }
            }
          }

          if ($lNaoIncluirDisciplina) {
            continue;
          }

          $oDisciplina->regencia  = $oRegencia->getCodigo();
          $oDisciplina->descricao = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());

          $oRetorno->dados[]      = $oDisciplina;
        }
      }

      break;
    case 'buscaTipoVinculo':

      $oDaoVinculo = db_utils::getDao('tipovinculo');
      $sSqlVinculo = $oDaoVinculo->sql_query_file();
      $rsVinculo   = $oDaoVinculo->sql_record($sSqlVinculo);
      $iRegistros  = $oDaoVinculo->numrows;

      for($i = 0; $i < $iRegistros; $i++) {

        $oDados              = db_utils::fieldsMemory($rsVinculo, $i);
        $oVinculo            = new stdClass();
        $oVinculo->codigo    = $oDados->ed324_sequencial;
        $oVinculo->descricao = urlencode($oDados->ed324_descricao);

        $oRetorno->dados[]   =  $oVinculo;
      }

      break;

    case 'buscaDocentesSubstitutos':

      /**
       * Utilizado para preencher a Grid com os Docentes Substitutos
       */
      $oDocenteAusente = new AusenciaDocente($oParam->iCodigo);
      $aSubstitutos    = $oDocenteAusente->getDocentesSubstitutos();

      if (count($aSubstitutos) > 0) {

        foreach ($aSubstitutos as $oSubstituto) {

          $oDadosSubstituto = new stdClass();
          $oDadosSubstituto->iSubstituto = $oSubstituto->getCodigo();
          $oDadosSubstituto->iRecHUmano  = $oSubstituto->getProfessorSubstituto()->getMatricula();
          $oDadosSubstituto->sNome       = urlencode($oSubstituto->getProfessorSubstituto()->getProfessor()->getNome());
          $oDadosSubstituto->iTurma      = $oSubstituto->getRegencia()->getTurma()->getCodigo();
          $oDadosSubstituto->sTurma      = urlencode($oSubstituto->getRegencia()->getTurma()->getDescricao());
          $oDadosSubstituto->iRegencia   = $oSubstituto->getRegencia()->getCodigo();
          $oDadosSubstituto->sRegencia   = urlencode($oSubstituto->getRegencia()->getDisciplina()->getNomeDisciplina());
          $oDadosSubstituto->iTipo       = $oSubstituto->getTipoVinculo();
          $oDadosSubstituto->sTipo       = urlencode($oSubstituto->getTipoVinculo() == 1 ? "TEMPORÁRIO" : "PERMANENTE");
          $oDadosSubstituto->dtInicio    = urlencode($oSubstituto->getPeriodoInicial()->getDate(DBDate::DATA_PTBR));
          $oDadosSubstituto->dtFinal     = '';

          $oDtFinal = $oSubstituto->getPeriodoFinal();
          if (!empty($oDtFinal)) {
            $oDadosSubstituto->dtFinal    = urlencode($oDtFinal->getDate(DBDate::DATA_PTBR));
          }

          $oRetorno->dados[] = $oDadosSubstituto;
        }
      }
      unset($aSubstitutos);
      unset($oDocenteAusente);
      break;

    case 'vincularDocenteSubstituto':

      /**
       * Realiza o vínculo de um docente substituto a um Docente Ausente
       */
      $oDocenteAusente   = new AusenciaDocente($oParam->iAusente);

      $iCodigoSubstituto = !empty($oParam->iSubstituto) ? $oParam->iSubstituto : null;
      $oSubstituto       = new DocenteSubstituto($iCodigoSubstituto);

      $oSubstituto->setProfessorSubstituto(new ProfessorVO($oParam->iRecHumanoSubstituto, $oParam->iCgmSubstituto));
      $oSubstituto->setRegencia(RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia));

      $iTipoVinculo = $oParam->iTipoVinculo == 1 ? DocenteSubstituto::TEMPORARIO : DocenteSubstituto::PERMANENTE;

      $oSubstituto->setTipovinculo($iTipoVinculo);
      $oSubstituto->setPeriodoInicial(new DBDate($oParam->dtInicial));

      $oSubstituto->removePeriodoFinal();
      if (!empty($oParam->dtFinal)) {
        $oSubstituto->setPeriodoFinal(new DBDate($oParam->dtFinal));
      }

      $oSubstituto->setUsuario($oUsuario);

      db_inicio_transacao();
      $oDocenteAusente->vincularSubstituto($oSubstituto);

      $sMsg  = "Professor substituto cadastrado com sucesso!";

      $oRetorno->message = $sMsg;

      db_fim_transacao();

      break;

    case 'carregaDadosDocenteSubstituto':

      $oDocenteSubstituto                = new DocenteSubstituto($oParam->iSubstituto);

      $oRetorno->iSubstituto          = $oDocenteSubstituto->getCodigo();
      $oRetorno->iRecHumanoSubstituto = $oDocenteSubstituto->getProfessorSubstituto()->getMatricula();
      $oRetorno->iCgmSubstituto       = $oDocenteSubstituto->getProfessorSubstituto()->getProfessor()->getCodigo();
      $oRetorno->sNome                = urlencode($oDocenteSubstituto->getProfessorSubstituto()->getProfessor()->getNome());
      $oRetorno->iTurma               = $oDocenteSubstituto->getRegencia()->getTurma()->getCodigo();
      $oRetorno->iRegencia            = $oDocenteSubstituto->getRegencia()->getCodigo();
      $oRetorno->sRegencia            = $oDocenteSubstituto->getRegencia()->getDisciplina()->getNomeDisciplina();
      $oRetorno->iTipoVinculo         = $oDocenteSubstituto->getTipoVinculo();
      $oRetorno->dtInicial            = urlencode($oDocenteSubstituto->getPeriodoInicial()->getDate(DBDate::DATA_PTBR));


      $oRetorno->dtFinal = '';
      $oDtFinal          = $oDocenteSubstituto->getPeriodoFinal();
      if (!empty($oDtFinal)) {
        $oRetorno->dtFinal = urlencode($oDocenteSubstituto->getPeriodoFinal()->getDate(DBDate::DATA_PTBR));
      }

      break;

    case 'removerVinculoDocenteSubstituto':

      $oDocenteAusente    = new AusenciaDocente($oParam->iAusente);
      $oDocenteSubstituto = new DocenteSubstituto($oParam->iSubstituto);

      db_inicio_transacao();

      $oDocenteAusente->desvincularSubstituto($oDocenteSubstituto);
      $oRetorno->message = "Substituição excluída com sucesso.";

      db_fim_transacao();

      unset($oDocenteAusente);

      break;
  }

} catch (Exception $oErro) {

  $oRetorno->message = $oErro->getMessage();
  $oRetorno->status  = 2;
  db_fim_transacao(true);
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);