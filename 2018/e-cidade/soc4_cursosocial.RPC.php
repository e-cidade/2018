<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/exceptions/BusinessException.php');
require_once('libs/exceptions/DBException.php');
require_once('libs/exceptions/FileException.php');
require_once('libs/exceptions/ParameterException.php');

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

try {

  switch($oParam->exec) {

    /**
     * Inclui ou altera um curso
     */
    case 'salvarCurso':

      $lPermiteAlteracao = true;
      $oCursoSocial      = new CursoSocial();

      if (!empty($oParam->iCodigoCurso)) {

        $oCursoSocial      = new CursoSocial($oParam->iCodigoCurso);
        $lPermiteAlteracao = $oCursoSocial->permiteAlteracaoDataCurso(new DBDate($oParam->sDtInicio), new DBDate($oParam->sDtFim));
      }

      if ($lPermiteAlteracao) {

        $oCursoSocial->setNome(db_stdClass::normalizeStringJsonEscapeString($oParam->sNomeCurso));
        $oCursoSocial->setDetalhamento(db_stdClass::normalizeStringJsonEscapeString($oParam->sDetalhamento));
        $oCursoSocial->setNumeroDeHorasAula($oParam->nHoraAula);
        $oCursoSocial->setDataInicio(new DBDate($oParam->sDtInicio));
        $oCursoSocial->setDataFim(new DBDate($oParam->sDtFim));
        $oCursoSocial->setMinistrante(CgmFactory::getInstanceByCgm($oParam->iMinistrante));
        $oCursoSocial->setResponsavel(CgmFactory::getInstanceByCgm($oParam->iResponsavel));
        $oCursoSocial->setCategoria(new CursoCategoria($oParam->iCategoria));

        foreach ($oParam->aDiaSemana as $iDiaSemana) {
          $oCursoSocial->adicionarDiaSemana($iDiaSemana);
        }

        db_inicio_transacao();
        $oCursoSocial->salvar();
        db_fim_transacao();

        $oRetorno->message      = "Curso salvo com sucesso.";
      } else {

        $sMensagem          = "Não é possível alterar a data do curso, pois existem aulas agendadas fora do novo";
        $sMensagem         .= " período informado.";
        $oRetorno->message  = $sMensagem;
      }

      $oRetorno->iCodigoCurso = $oCursoSocial->getCodigo();
      break;

    /**
     * Remove um curso
     */
    case 'removerCurso':

      db_inicio_transacao();
      $oCursoSocial = new CursoSocial($oParam->iCodigoCurso);
      $oCursoSocial->removerCurso();
      db_fim_transacao();

      $oRetorno->message = "Curso removido.";

      break;

    /**
     * Retorna os dados do curso
     */
    case 'getDadosCurso':

      $oCurso      = new stdClass();
      $oCursoSocial = new CursoSocial($oParam->iCodigoCurso);

      $oCurso->iCodigo       = $oCursoSocial->getCodigo();
      $oCurso->sNomeCurso    = urlencode($oCursoSocial->getNome());
      $oCurso->sDetalhamento = urlencode($oCursoSocial->getDetalhamento());
      $oCurso->nHoraAula     = $oCursoSocial->getNumeroDeHorasAula();
      $oCurso->dtInicio      = $oCursoSocial->getDataInicio()->convertTo(DBDate::DATA_PTBR);
      $oCurso->dtFim         = $oCursoSocial->getDataFim()->convertTo(DBDate::DATA_PTBR);
      $oCurso->iMinistrante  = $oCursoSocial->getMinistrante()->getCodigo();
      $oCurso->sMinistrante  = $oCursoSocial->getMinistrante()->getNome();
      $oCurso->iResponsavel  = $oCursoSocial->getResponsavel()->getCodigo();
      $oCurso->sResponsavel  = $oCursoSocial->getResponsavel()->getNome();
      $oCurso->iCategoria    = $oCursoSocial->getCategoria()->getCodigo();
      $oCurso->sCategoria    = $oCursoSocial->getCategoria()->getDescricao();

      $oCurso->aDiasSemana = array();

      foreach ($oCursoSocial->getDiasSemana() as $iDiaSemana) {
        $oCurso->aDiasSemana[] = $iDiaSemana;
      }

      $oRetorno->oCursoSocial = $oCurso;

      break;

    /**
     * Busca as categorias do curso
     */
    case 'buscaCategoriaCurso':

      $sCampos       = 'h02_descr, h02_codigo';
      $oDaoCategoria = new cl_tabcurritipo();
      $sSqlCategoria = $oDaoCategoria->sql_query_file(null, $sCampos, $sCampos);
      $rsCategoria   = $oDaoCategoria->sql_record($sSqlCategoria);
      $iLinhas       = $oDaoCategoria->numrows;

      $oRetorno->aCategorias = array();
      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {

          $oDados                  = db_utils::fieldsMemory($rsCategoria, $i);
          $oCategoria              = new stdClass();
          $oCategoria->iCodigo     = $oDados->h02_codigo;
          $oCategoria->sDescricao  = urlencode($oDados->h02_descr);
          $oRetorno->aCategorias[] = $oCategoria;
        }
      }

      break;

    /**
     * Gera a agenda do curso com base no período inicial e final
     */
    case 'gerarAgenda':

      $oCursoSocial = new CursoSocial($oParam->iCodigoCurso);

      $aDiasSemana = array();
      foreach ($oCursoSocial->getDiasSemana() as $iDiaSemana) {
        $aDiasSemana[] = $iDiaSemana-1;
      }

      $aDatas = DBDate::getDatasNoIntervalo($oCursoSocial->getDataInicio(),
                                            $oCursoSocial->getDataFim(),
                                            $aDiasSemana);

      db_inicio_transacao();
      foreach ($aDatas as $oData) {

        $oCursoSocial->adicionarDiaDeAula($oData);
      }
      db_fim_transacao();
      $oRetorno->message = "Dias de aula gerado com sucesso.";

      break;

    /**
     * Retorna todos os dia da agenda do curso (dias de aula)
     */
    case 'getDiasAulaCurso':

      $oCursoSocial    = new CursoSocial($oParam->iCodigoCurso);
      $oRetorno->aDias = array();
      foreach ($oCursoSocial->getDiasDeAula() as $oDiaAula) {

        $oData             = new stdClass();
        $oData->iCodigo    = $oDiaAula->iCodigo;
        $oData->dtAula     = $oDiaAula->oDataAula->convertTo(DBDate::DATA_PTBR);
        $oData->sDiaSemana = urlencode(DBDate::getLabelDiaSemana($oDiaAula->oDataAula->getDiaSemana()));
        $oRetorno->aDias[] = $oData;
      }

      break;

    /**
     * Remove um dia de aula da agenda
     */
    case 'removerDiaAula':

      $oCursoSocial = new CursoSocial($oParam->iCodigoCurso);
      db_inicio_transacao();

      $oCursoSocial->removerDiaDeAula($oParam->iCodigoDiaAula);

      db_fim_transacao();
      $oRetorno->message = "Dia removido com sucesso.";
      break;

    /**
     * Adiciona um dia de aula na agenda do curso
     */
    case 'adicionarDiaAula':

      $lTemDiaAgendado = false;
      $oCursoSocial    = new CursoSocial($oParam->iCodigoCurso);
      $oNovaData       = new DBDate($oParam->dtNova);

      db_inicio_transacao();
      $oCursoSocial->adicionarDiaDeAula($oNovaData);
      db_fim_transacao();
      $oRetorno->message = "Dia adicionado a agenda do curso com sucesso.";

      break;

    case "adicionarCidadaoCurso":

      if (empty($oParam->iCurso)) {
        throw new BusinessException('Curso não informado.');
      }

      if (empty($oParam->iCidadao)) {
        throw new BusinessException('Cidadão não informado.');
      }

      $oCursoSocial      = CursoSocialRepository::getCursoSocialByCodigo($oParam->iCurso);
      $oCidadao          = CidadaoRepository::getCidadaoByCodigo($oParam->iCidadao);
      $oCidadaoMatricula = new CidadaoMatriculaCursoSocial() ;
      $oCidadaoMatricula->setCursoSocial($oCursoSocial);
      $oCidadaoMatricula->setCidadao($oCidadao);
      $oCidadaoMatricula->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao));

      db_inicio_transacao();

      $oCidadaoMatricula->salvar();

      db_fim_transacao();
      $oRetorno->message = "Matrícula efetuada com sucesso.";

      CursoSocialRepository::removerCursoSocial($oCursoSocial);
      CidadaoRepository::removerCidadao($oCidadao);
      break;

    case "getCidadaoMatriculadoNoCurso":

      $oCursoSocial = new CursoSocial($oParam->iCurso);

      $oRetorno->aCidadaos = array();
      foreach ($oCursoSocial->getCidadaosMatriculados() as $oMatricula) {

        $oMatriculaDado             = new stdClass();
        $oMatriculaDado->sNome      = $oMatricula->getCidadao()->getNome();
        $oMatriculaDado->iMatricula = $oMatricula->getCodigo();
        $oRetorno->aCidadaos[]       = $oMatriculaDado;
      }

      break;

    case "removerCidadaoCurso":

      db_inicio_transacao();

      foreach ($oParam->aMatriculas as $iMatricula) {

        $oCidadaoMatricula = new CidadaoMatriculaCursoSocial($iMatricula);
        $oCidadaoMatricula->removerMatricula();
      }

      db_fim_transacao();
      $oRetorno->message = "Matrículas removidas com sucesso.";

      break;

    /**
     * Busca os Cursos/Oficinas cadastrados
     * @param integer $oParam->iTipoCurso: Codigo do tipo de curso
     * @return array $oRetorno->aCursos
     */
    case 'buscaCursos':

      $oRetorno->aCursos  = array();
      $sWhereCursoSocial  = '';
      $oDaoCursoSocial    = new cl_cursosocial();
      $sCamposCursoSocial = "as19_sequencial, as19_nome";

      if (!empty($oParam->iTipoCurso)) {
        $sWhereCursoSocial = "as19_tabcurritipo = {$oParam->iTipoCurso}";
      }

      $sSqlCursoSocial    = $oDaoCursoSocial->sql_query_file(null, $sCamposCursoSocial, null, $sWhereCursoSocial);
      $rsCursoSocial      = $oDaoCursoSocial->sql_record($sSqlCursoSocial);
      $iLinhasCursoSocial = $oDaoCursoSocial->numrows;

      if ($iLinhasCursoSocial > 0) {
        $oRetorno->aCursos = db_utils::getCollectionByRecord($rsCursoSocial, false, false, true);
      }
      break;

    case "getCursoMinistrante":

      $oRetorno->aCursos  = array();
      $sWhereCursoSocial  = "as19_ministrante = {$oParam->iMinistrante}";
      $oDaoCursoSocial    = new cl_cursosocial();
      $sCamposCursoSocial = "as19_sequencial, as19_nome";
      $sSqlCursoSocial    = $oDaoCursoSocial->sql_query_file(null, $sCamposCursoSocial, null, $sWhereCursoSocial);
      $rsCursoSocial      = $oDaoCursoSocial->sql_record($sSqlCursoSocial);
      $iLinhasCursoSocial = $oDaoCursoSocial->numrows;

      if ($iLinhasCursoSocial > 0) {
        $oRetorno->aCursos = db_utils::getCollectionByRecord($rsCursoSocial, false, false, true);
      }

      break;

    case "getMesesDeAbrangencia":

      $oCursoSocial          = new CursoSocial($oParam->iCurso);
      $oRetorno->iTotalAulas = count($oCursoSocial->getDiasDeAula());
      foreach ($oCursoSocial->getMesesDeAbrangencia() as $iAno => $aMeses) {

        foreach ($aMeses as $iMes => $sMes) {
          $oRetorno->aMeses[$iAno][$iMes] = urlencode($sMes);
        }
      }

      break;

    case "getDiasAula":

      $oCursoSocial    = new CursoSocial($oParam->iCurso);
      $oRetorno->aDias = array();

      foreach ($oCursoSocial->getDiasDeAula($oParam->iMes) as $oDiaAula) {

        $oDia              = new stdClass();
        $oDia->iCursoAula  = $oDiaAula->iCodigo;
        $oDia->dtAula      = $oDiaAula->oDataAula->convertTo(DBDate::DATA_PTBR);
        $oDia->iDia        = $oDiaAula->oDataAula->getDia();
        $oRetorno->aDias[] = $oDia;
      }

      break;

    case "getDiasAulaPorMes":

      $oCursoSocial    = new CursoSocial($oParam->iCurso);
      $oRetorno->aDias = array();
      foreach ($oCursoSocial->getDiasDeAulaPorMes($oParam->iMes) as $oDiaMes) {

        if ($oDiaMes->oDataAula->getAno() != $oParam->iAno) {
          continue;
        }

        $oDia              = new stdClass();
        $oDia->iCursoAula  = $oDiaMes->iCodigo;
        $oDia->dtAula      = $oDiaMes->oDataAula->convertTo(DBDate::DATA_PTBR);
        $oDia->iDia        = $oDiaMes->oDataAula->getDia();
        $oRetorno->aDias[] = $oDia;
      }


      break;

    case "getAlunosCurso":

      $oCursoSocial = new CursoSocial($oParam->iCurso);
      $aMatriculas  = $oCursoSocial->getCidadaosMatriculados();

      $oRetorno->aAlunos = array();
      if (count($aMatriculas) > 0) {

        foreach ($aMatriculas as $oMatriculaCurso) {

          $oMatricula             = new stdClass();
          $oMatricula->iMatricula = $oMatriculaCurso->getCodigo();
          $oMatricula->sNome      = $oMatriculaCurso->getCidadao()->getNome();
          $oMatricula->iCidadao   = $oMatriculaCurso->getCidadao()->getCodigo();

          $oMatricula->aAusencias = array();
          foreach ($oMatriculaCurso->getAusencias() as $oAusenciaCidadao) {

            $oAusencia              = new stdClass();
            $oAusencia->iCursoAula  = $oAusenciaCidadao->iCursoAula;
            $oAusencia->dtDia       = $oAusenciaCidadao->oDia->convertTo(DBDate::DATA_PTBR);
            $oAusencia->iMes        = (int) $oAusenciaCidadao->oDia->getMes();
            $oMatricula->aAusencias[] = $oAusencia;
          }

          $oRetorno->aAlunos[]    = $oMatricula;
        }
      }

      break;

    case "salvarFaltas":

      $oCursoSocial = new CursoSocial($oParam->iCurso);
      $aDiasDeFaltas = array();
      $oDaoCursoAula = new cl_cursosocialaula();

      db_inicio_transacao();
      foreach ($oParam->aAlunosFaltas as $oAluno) {

        $oCidadaoMatricula = new CidadaoMatriculaCursoSocial($oAluno->iMatricula);

        $oCidadaoMatricula->removeTodasAusencias();
        foreach ($oAluno->aFaltas as $iDiaSemana) {

          if (!array_key_exists($iDiaSemana, $aDiasDeFaltas)) {

            $sSql = $oDaoCursoAula->sql_query_file($iDiaSemana, "as21_dataaula");
            $rs   = $oDaoCursoAula->sql_record($sSql);

            $aDiasDeFaltas[$iDiaSemana] = db_utils::fieldsMemory($rs, 0)->as21_dataaula;
          }

          $oCidadaoMatricula->setAusencias($iDiaSemana, new DBDate($aDiasDeFaltas[$iDiaSemana]));
        }
        $oCidadaoMatricula->salvarAusencias();
      }

      db_fim_transacao();
      $oRetorno->message = "Lançamento de frequência salvo com sucesso.";

      break;

  }
}  catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);