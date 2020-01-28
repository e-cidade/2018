<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
include_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("classes/db_cursoedu_classe.php"));
db_app::import("exceptions.*");
db_app::import("educacao.*");
db_app::import("educacao.progressaoparcial.*");
/* ATENCAO: PLUGIN ParametroProgressaoParcial - Requires - INSTALADO AQUI - NAO REMOVER */


$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->status   = 1;
$oRetorno->message  = "";

$iDepartamentoLogado = db_getsession("DB_coddepto");
$iModulo             = db_getsession("DB_modulo");

try {

  /**
   * Validar se a secretaria habilitou a escola para editar os parametros ou se ela resguardou esse direito
   * somente para a Secretaria
   */
  $oDaoSecretaria = new cl_sec_parametros();
  $sSqlSecretaria = $oDaoSecretaria->sql_query_file(null, "ed290_controleprogressaoparcial");
  $rsSecretaria   = $oDaoSecretaria->sql_record($sSqlSecretaria);

  if ($oDaoSecretaria->numrows == 0) {

    throw new Exception('Parâmetro não configurado.');
  }

  $iControleProgressaoParcial = db_utils::fieldsMemory($rsSecretaria, 0)->ed290_controleprogressaoparcial;
  $lHabilitadoSecretaria     = $iControleProgressaoParcial == 1 ? true : false;


  /**
   * Validar se nenhuma das escolas do municipio esta com turma encerrada
   * Se estiver configurado para escola
   *   |- Validar se a escola não esta com turmas encerradas
   */
  $sWhereParamentros  = "     ed52_i_ano = " . db_getsession("DB_anousu");
  $sWhereParamentros .= " and ed59_c_encerrada = 'S' ";
  if (!$lHabilitadoSecretaria) {
    $sWhereParamentros .= " and ed112_escola = {$iDepartamentoLogado}";
  }
  $oDaoParamProgressao = db_utils::getDao("parametroprogressaoparcial");
  $sSqlParamentros     = $oDaoParamProgressao->sql_query_dados_regencia(null, "1", null, $sWhereParamentros);
  $rsParametros        = $oDaoParamProgressao->sql_record($sSqlParamentros);

  $lTurmaEncerradas    = false;
  if ($oDaoParamProgressao->numrows > 0) {
    $lTurmaEncerradas    = true;
  }

  switch ($oParam->exec) {

    case 'getDados':
      /**
       * Ao buscar os dados do parametro de progressao parcial devemos:
       * 1o Validar se a secretaria habilitou a escola para editar os parametros ou se ela resguardou esse direito
       *    somente para a Secretaria
       * 2o Validar se o Parametro esta ativado
       *    Se sim trazer todos os dados
       * 3o Ao buscar os cursos devemos verificar em qual departamento estamos logado.
       *    Se secretaria devemos trazer todos os cursos
       *    Se escola devemos trazer os cursos que a escola oferece
       */


      /**
       * Comessamos a validar o 2o caso
       * Validar se o Parametro esta ativado
       */
      $sWhereParamentros = null;
      if (!$lHabilitadoSecretaria) {
        $sWhereParamentros = "ed112_escola = {$iDepartamentoLogado}";
      }
      $oDaoParamProgressao = db_utils::getDao("parametroprogressaoparcial");
      $sSqlParamentros     = $oDaoParamProgressao->sql_query_file(null, "*", null, $sWhereParamentros);
      $rsParametros        = $oDaoParamProgressao->sql_record($sSqlParamentros);

      /**
       * Configuracao padrao dos parametros da tela
       */
      $lHabilitado                   = false;
      $iNumeroDisciplina             = null;
      $iFormaControle                = 2;
      $lControlaFrequencia           = false;
      $lDisciplinaEliminaDependencia = false;
      $sJustificativa                = "";

      $oProgressaoParcial            = null;
      $aEtapasConfiguradas           = array();
      /* ATENCAO: PLUGIN ParametroProgressaoParcial - INICIALIZANDO PARAMETRO PROGRESSAO MESMA DISCIPLINA - INSTALADO AQUI - NAO REMOVER */
      if ($oDaoParamProgressao->numrows > 0) {

        $iCodigoEscola                 = db_utils::fieldsMemory($rsParametros, 0)->ed112_escola;
        $oProgressaoParcial            = new ProgressaoParcialParametro($iCodigoEscola);
        $lHabilitado                   = $oProgressaoParcial->isHabilitada();
        $iNumeroDisciplina             = $oProgressaoParcial->getQuantidadeDisciplina();
        $iFormaControle                = $oProgressaoParcial->getFormaControle();
        $lControlaFrequencia           = $oProgressaoParcial->temControleFrequencia();
        $lDisciplinaEliminaDependencia = $oProgressaoParcial->disciplinaAprovadaEliminaProgressao();
        $sJustificativa                = $oProgressaoParcial->getJustificativa();
        $aEtapasConfiguradas           = $oProgressaoParcial->getEtapas();
        /* ATENCAO: PLUGIN ParametroProgressaoParcial - DEFININDO PARAMETRO PROGRESSAO MESMA DISCIPLINA - INSTALADO AQUI - NAO REMOVER */
      }

      /**
       * Observacao
       * Aqui validamos o modulo logado. O codigo do modulo secretaria eh 7159 e o de escola eh 1100747.
       * Por default presumimos que estamos na escola, portanto iniciamos $lModuloSecretaria como false
       */
      $lModuloSecretaria  = false;
      if ($iModulo == 7159) {
        $lModuloSecretaria = true;
      }

      $oDados                                = new stdClass();
      $oDados->lHabilitadoSecretaria         = $lHabilitadoSecretaria;
      $oDados->lModuloAcessoSecretaria       = $lModuloSecretaria;
      $oDados->lHabilitado                   = $lHabilitado;
      $oDados->iNumeroDisciplina             = $iNumeroDisciplina;
      $oDados->iFormaControle                = $iFormaControle;
      $oDados->lControlaFrequencia           = $lControlaFrequencia;
      $oDados->lDisciplinaEliminaDependencia = $lDisciplinaEliminaDependencia;
      $oDados->sJustificativa                = urlencode($sJustificativa);
      /* ATENCAO: PLUGIN ParametroProgressaoParcial - RETORNANDO PARAMETRO PROGRESSAO MESMA DISCIPLINA - INSTALADO AQUI - NAO REMOVER */

      /**
       * Comessamos a validar o 3o caso
       * Buscamos os cursos cadastrados e suas etapas
       */
      $sWhere = null;
      if ($iModulo == 1100747) {
        $sWhere = " ed71_i_escola = {$iDepartamentoLogado}";
      }
      $aCursos    = array();
      $oDaoCurso  = new cl_curso();
      $sSqlCurso  = $oDaoCurso->sql_query_cursoescola(null, "distinct ed29_i_codigo, ed29_c_descr",
                                                      "ed29_c_descr", $sWhere);
      $rsCurso    = $oDaoCurso->sql_record($sSqlCurso);
      $iRegistros = $oDaoCurso->numrows;

      if ($iRegistros > 0) {

        for ($i = 0; $i < $iRegistros; $i++) {

          $oCurso          = new Curso(db_utils::fieldsMemory($rsCurso, $i)->ed29_i_codigo);
          $aEtapas         = $oCurso->getEtapas();

          $oCursoDados                  = new stdClass();
          $oCursoDados->iCodigoCurso    = $oCurso->getCodigo();
          $oCursoDados->sDescricaoCurso = urlencode($oCurso->getNome());
          $oCursoDados->aEtapas         = array();

          foreach ($aEtapas as $oEtapa) {

            $oEtapaDados                    = new stdClass();
            $oEtapaDados->iCodigoEtapa      = $oEtapa->getCodigo();
            $oEtapaDados->iCodigoCurso      = $oCurso->getCodigo();
            $oEtapaDados->lConfigurada      = false;
            $oEtapaDados->sDescricaoEtapa   = urlencode($oEtapa->getNome());
            $oEtapaDados->lBloqueiaTreeView = false;

            /**
             * Valida se esta com turmas encerradas ou se o parametro esta habilitado para secretaria e esta logado na escola
             */
            if (($lHabilitadoSecretaria && !$lModuloSecretaria)) {
              $oEtapaDados->lBloqueiaTreeView = true;
            } else if ((!$lHabilitadoSecretaria && $lModuloSecretaria)) {
              $oEtapaDados->lBloqueiaTreeView = true;
            }

            /**
             * Verificamos se a etapa listada ja esta configurada
             */
            foreach ($aEtapasConfiguradas as $oEtapaConfigurada) {

              if ($oEtapa->getCodigo() == $oEtapaConfigurada->getCodigo()) {

                $oEtapaDados->lConfigurada = true;
                break;
              }
            }

            $oCursoDados->aEtapas[]       = $oEtapaDados;
          }

          $aCursos[] = $oCursoDados;
        }

        unset($oCurso);
        unset($aEtapas);
      }

      $oDados->aCursos = $aCursos;
      $oRetorno->dados = $oDados;

      break;

    case 'salvar':

      /**
       * Ao salvar os Dados devemos cuidar:
       * 1o Se estiver estiver configurado para secretaria
       *     |- Validar se nenhuma das escolas do municipio esta com turma encerrada
       *    Se estiver configurado para escola
       *     |- Validar se a escola não esta com turmas encerradas
       */

      if ($lTurmaEncerradas) {

        $sMsgErro = "Você não pode alterar os parâmetros.\nExistem Escolas com turmas encerradas.";
        throw new BusinessException($sMsgErro);
      }


      $aEscolas = array();
      $oEscola  = new stdClass();
      $oEscola->ed18_i_codigo = $iDepartamentoLogado;
      $aEscolas[] = $oEscola;

      if ($iModulo == 7159) {

        $oDaoEscola  = db_utils::getDao('escola');
        $sSqlEscolas = $oDaoEscola->sql_query_file(null, " ed18_i_codigo ");
        $rsEscolas   = $oDaoEscola->sql_record($sSqlEscolas);
        $aEscolas    = db_utils::getCollectionByRecord($rsEscolas);
      }

      db_inicio_transacao();

      /* ATENCAO: PLUGIN ParametroProgressaoParcial - Verifica Escola tem Progressao - INSTALADO AQUI - NAO REMOVER */

      foreach ($aEscolas as $oEscola) {

        $sJustificativa = db_stdClass::normalizeStringJson($oParam->sJustificativa);
        $oProgressaoParcial = new ProgressaoParcialParametro($oEscola->ed18_i_codigo);
        $oProgressaoParcial->setControleFrequencia($oParam->lControlaFrequencia);
        $oProgressaoParcial->setQuantidadeDisciplina($oParam->iNumeroDisciplina);
        $oProgressaoParcial->setDisciplinaAprovadaEliminaProgressao($oParam->lDisciplinaEliminaDependencia);
        $oProgressaoParcial->setFormaControle($oParam->iFormaControle);
        $oProgressaoParcial->setHabilitada($oParam->lHabilitado);
        $oProgressaoParcial->setJustificativa($sJustificativa) ;
        $oProgressaoParcial->removerEtapa();
        foreach ($oParam->aEtapas as $iCodigoEtapa) {

          $oEtapa = EtapaRepository::getEtapaByCodigo($iCodigoEtapa);
          $oProgressaoParcial->adicionarEtapa($oEtapa);
        }
        /* ATENCAO: PLUGIN ParametroProgressaoParcial - Setando Dependência Mesma Disciplina - INSTALADO AQUI - NAO REMOVER */
        $oProgressaoParcial->salvar();
        unset ($oProgressaoParcial);
      }
      db_fim_transacao();
      $oRetorno->message = "Parâmetros configurados com sucesso.";
      break;

    case 'verificaAlunoEmProgressao':

      $oRetorno->lTemEscolaProgressaoParcial = false;
      $oDaoProgressaoParcialAluno            = db_utils::getDao("progressaoparcialaluno");
      $sSqlProgressaoParcialAluno            = $oDaoProgressaoParcialAluno->sql_query();
      $rsProgressaoParcialAluno              = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressaoParcialAluno);

      if ($oDaoProgressaoParcialAluno->numrows > 0) {

        $oRetorno->lTemEscolaProgressaoParcial  = true;
        $oRetorno->message                      = "AVISO: o Controle de Progressão Parcial encontra-se desabilitado, ";
        $oRetorno->message                     .= "pois já existem escolas com Progressão Parcial configurada e alunos ";
        $oRetorno->message                     .= "vinculados, não sendo permitido alterar para Secretária. ";
      }
      break;
  }
} catch (BusinessException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
} catch (ParameterException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
} catch (DBException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);