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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

define("URL_MSG_EDU4_ALUNO_RPC", "educacao.escola.edu4_aluno_rpc.");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    /**
     * Buscamos os recursos para avaliacao do INEP setados para o aluno
     */
    case 'getRecursosAvaliacaoInep':

      $oRetorno->aRecursosAvaliacaoInep = array();
      $aCodigosRecursos                 = array();

      if (isset($oParam->iAluno) && !empty($oParam->iAluno)) {

        $oAluno = AlunoRepository::getAlunoByCodigo($oParam->iAluno);
        if (count($oAluno->getRecursosAvaliacao()) > 0) {

          foreach ($oAluno->getRecursosAvaliacao() as $oRecursoAvaliacao) {
            $aCodigosRecursos[] = $oRecursoAvaliacao->iCodigo;
          }
        }

        $oRetorno->aNecessidades     = $oAluno->getNecessidadesEspeciais();
        $oDaoRecursosAvaliacaoInep   = db_utils::getDao("recursosavaliacaoinep");
        $sWhereRecursosAvaliacaoInep = "ed326_sequencial <> 110";
        $sSqlRecursosAvaliacaoInep   = $oDaoRecursosAvaliacaoInep->sql_query(
                                                                             null,
                                                                             "*",
                                                                             'ed326_descricao',
                                                                             $sWhereRecursosAvaliacaoInep
                                                                            );
        $rsRecursosAvaliacaoInep     = $oDaoRecursosAvaliacaoInep->sql_record($sSqlRecursosAvaliacaoInep);
        $iTotalRecursosAvaliacaoInep = $oDaoRecursosAvaliacaoInep->numrows;

        if ($iTotalRecursosAvaliacaoInep > 0) {

          for ($iContador = 0; $iContador < $iTotalRecursosAvaliacaoInep; $iContador++) {

            $oDadosRecursosAvaliacaoInep         = db_utils::fieldsMemory($rsRecursosAvaliacaoInep, $iContador);
            $oRecursosAvaliacaoInep              = new stdClass();
            $oRecursosAvaliacaoInep->lTemRecurso = false;
            $oRecursosAvaliacaoInep->iCodigo     = $oDadosRecursosAvaliacaoInep->ed326_sequencial;

            if (in_array($oRecursosAvaliacaoInep->iCodigo, $aCodigosRecursos)) {
              $oRecursosAvaliacaoInep->lTemRecurso = true;
            }

            $oRecursosAvaliacaoInep->sDescricao  = urlencode($oDadosRecursosAvaliacaoInep->ed326_descricao);
            $oRetorno->aRecursosAvaliacaoInep[]  = $oRecursosAvaliacaoInep;
          }
        }


        AlunoRepository::removerAluno($oAluno);
      }
      break;

    /**
     * Salvamos os recursos para avaliacao do INEP selecionados. Primeiramente, verificamos se o aluno ja possui algum
     * configurado, excluimos e em seguida incluimos todos que foram passados
     */
    case 'salvarRecursosAvaliacao':

      if (isset($oParam->iAluno) && !empty($oParam->iAluno)) {

        db_inicio_transacao();

        $oAluno = AlunoRepository::getAlunoByCodigo($oParam->iAluno);
        if (count($oAluno->getRecursosAvaliacao()) > 0) {

          $oDaoExclusaoAlunoRecursosAvaliacao = db_utils::getDao("alunorecursosavaliacaoinep");
          $oDaoExclusaoAlunoRecursosAvaliacao->excluir(null, "ed327_aluno = {$oParam->iAluno}");

          if ($oDaoExclusaoAlunoRecursosAvaliacao->erro_status == "0") {
            throw new BusinessException($oDaoExclusaoAlunoRecursosAvaliacao->erro_msg);
          }
          unset($oDaoExclusaoAlunoRecursosAvaliacao);
        }

        /**
         * Verificamos se algum recurso foi selecionado para o aluno, percorrendo cada um deles e salvando. Caso nao
         * tenha sido selecionado nenhum, apenas salvamos a opcao "Nenhum", oculta
         */
        $lRemoverOpcaoNenhum = count($oParam->aRecursosSelecionados) > 1 ? true : false;

        if (count($oParam->aRecursosSelecionados) > 0) {

          foreach ($oParam->aRecursosSelecionados  as $iCodigoRecurso) {

            if ($lRemoverOpcaoNenhum && $iCodigoRecurso == 110) {
              continue;
            }

            $oDaoInclusaoAlunoRecursosAvaliacao                              = db_utils::getDao("alunorecursosavaliacaoinep");
            $oDaoInclusaoAlunoRecursosAvaliacao->ed327_aluno                 = $oParam->iAluno;
            $oDaoInclusaoAlunoRecursosAvaliacao->ed327_recursosavaliacaoinep = $iCodigoRecurso;
            $oDaoInclusaoAlunoRecursosAvaliacao->incluir(null);

            if ($oDaoInclusaoAlunoRecursosAvaliacao->erro_status == "0") {
              throw new BusinessException($oDaoInclusaoAlunoRecursosAvaliacao->erro_msg);
            }
            unset($oDaoInclusaoAlunoRecursosAvaliacao);
          }
        } else {

          $oDaoInclusaoAlunoRecursosAvaliacao                              = db_utils::getDao("alunorecursosavaliacaoinep");
          $oDaoInclusaoAlunoRecursosAvaliacao->ed327_aluno                 = $oParam->iAluno;
          $oDaoInclusaoAlunoRecursosAvaliacao->ed327_recursosavaliacaoinep = 110;
          $oDaoInclusaoAlunoRecursosAvaliacao->incluir(null);

          if ($oDaoInclusaoAlunoRecursosAvaliacao->erro_status == "0") {
            throw new BusinessException($oDaoInclusaoAlunoRecursosAvaliacao->erro_msg);
          }
          unset($oDaoInclusaoAlunoRecursosAvaliacao);
        }

        db_fim_transacao();
      }
      break;

    /**
     * Verificamos se o aluno possui necessidade especial
     */
    case 'temNecessidade':

      if (isset($oParam->iAluno) && !empty($oParam->iAluno)) {

        $oRetorno->lTemNecessidade = false;
        $oAluno = AlunoRepository::getAlunoByCodigo($oParam->iAluno);

        if (count($oAluno->getNecessidadesEspeciais()) > 0) {
          $oRetorno->lTemNecessidade = true;
        }
      }
      break;

    case 'buscaAlunos':

      $aFiltros = array();

      /**
       *  Aceitar os filtros : ed47_v_nome, ed47_v_mae, ed47_d_nasc
       */
      if (empty($oParam->sNomeAluno)) {
        throw new BusinessException(_M('educacao.escola.edu4_aluno.especifique_nome_aluno'));
      }
      $aFiltros[] = " ed47_v_nome ilike '" . db_stdClass::normalizeStringJsonEscapeString($oParam->sNomeAluno) . "%'";

      if (isset($oParam->sNomeMae) && !empty($oParam->sNomeMae)) {
        $aFiltros[] = " ed47_v_mae ilike '" . db_stdClass::normalizeStringJsonEscapeString($oParam->sNomeMae). "%'";
      }

      if (isset($oParam->dtNascimento) && !empty($oParam->dtNascimento)) {

        $oData      = new DBDate($oParam->dtNascimento);
        $aFiltros[] = " ed47_d_nasc = '" . $oData->convertTo(DBDate::DATA_EN) . "'";
      }

      $oDaoAluno = db_utils::getDao('aluno');
      $sWhere    = implode(" and ", $aFiltros);
      $sCampos   = " ed47_i_codigo, ed47_v_nome, ed47_v_mae, ed47_d_nasc";
      $sSqlAluno = $oDaoAluno->sql_query_file(null, $sCampos, null, $sWhere);
      $rsAluno   = $oDaoAluno->sql_record($sSqlAluno);
      $iLinhas   = $oDaoAluno->numrows;

      if ($iLinhas == 0) {
        throw new BusinessException(_M('educacao.escola.edu4_aluno.nenhum_aluno_encontrado'));
      }

      $oRetorno->aAlunos = array();
      for ($i = 0; $i < $iLinhas; $i++) {
        $oRetorno->aAlunos[] = db_utils::fieldsMemory($rsAluno, $i, false, false, true);
      }

      break;

    /**
     * Retorna 2 stdClass: um para os dados do pai e outros para os dados da mãe, caso estes possuam cadastro de cidadão
     * @return stdClass oPai
     *                  integer iCodigo
     *                  string  sCpf
     *                  string  sNome
     *         stdClass oMae
     *                  integer iCodigo
     *                  string  sCpf
     *                  string  sNome
     */
    case 'cidadaosFiliacao':

      if (!empty($oParam->iAluno)) {

        $oCidadao = CidadaoRepository::getCidadaoPeloCodigoAluno($oParam->iAluno);

        if (!empty($oCidadao)) {

          $oRetorno->oPai = null;

          if ($oCidadao->getPai() != null) {

            $oPai                    = $oCidadao->getPai()->getCidadao();
            $oRetorno->oPai          = new stdClass();
            $oRetorno->oPai->iCodigo = $oPai->getCodigo();
            $oRetorno->oPai->sCpf    = urlencode( $oPai->getCpfCnpj() );
            $oRetorno->oPai->sNome   = urlencode( $oPai->getNome() );
          }

          $oRetorno->oMae = null;

          if ($oCidadao->getMae() != null) {

            $oMae                    = $oCidadao->getMae()->getCidadao();
            $oRetorno->oMae          = new stdClass();
            $oRetorno->oMae->iCodigo = $oMae->getCodigo();
            $oRetorno->oMae->sCpf    = urlencode( $oMae->getCpfCnpj() );
            $oRetorno->oMae->sNome   = urlencode( $oMae->getNome() );
          }
        }
      }

      break;

    /**
     * Retorna um stdClass do responsável pelo aluno, caso exista um cadastro de cidadão e vínculo
     * @return stdClass oResponsavel
     *         stdClass oResponsavel
     *                  integer iCodigo
     *                  string  sCpf
     *                  string  sNome
     */
    case 'cidadaoResponsavel':

      if (!empty($oParam->iAluno)) {

        $oAluno = AlunoRepository::getAlunoByCodigo($oParam->iAluno);

        $oRetorno->oResponsavel = null;
        if ($oAluno->getCidadaoResponsavel() != null) {

          $oCidadaoResponsavel             = $oAluno->getCidadaoResponsavel();
          $oRetorno->oResponsavel          = new stdClass();
          $oRetorno->oResponsavel->iCodigo = $oCidadaoResponsavel->getCodigo();
          $oRetorno->oResponsavel->sCpf    = urlencode( $oCidadaoResponsavel->getCpfCnpj() );
          $oRetorno->oResponsavel->sNome   = urlencode( $oCidadaoResponsavel->getNome() );
        }
      }
      break;

    /**
     * Retorna um stdClass do contato do aluno, caso exista um cadastro de cidadão e vínculo
     * @return stdClass oContato
     *         stdClass oContato
     *                  integer iCodigo
     */
    case 'cidadaoContato':

      if (!empty($oParam->iAluno)) {

        $oAluno = AlunoRepository::getAlunoByCodigo($oParam->iAluno);

        $oRetorno->oContato = null;
        if ($oAluno->getCidadaoContato() != null) {

          $oCidadaoContato             = $oAluno->getCidadaoContato();
          $oRetorno->oContato          = new stdClass();
          $oRetorno->oContato->iCodigo = $oCidadaoContato->getCodigo();
        }
      }

      break;

    /**
     * Retornar os dados do aluno um stdClass
     * - codigo_aluno;
     * - nome_aluno;
     * - codigo_turma;
     * - descricao_turma;
     * - descricao_turno
		 * - codigo_curso
     * - codigo_calendario;
     * - nome_calendario;
     * - ano_calendario
     * - codigo_etapa;
     * - descricao_etapa;
     * - situacao_aluno;
     * - data matricula;
     */
    case 'buscaDadosAluno':

      if ( !isset($oParam->iAluno) || empty($oParam->iAluno) ) {
      	throw new BusinessException( _M(URL_MSG_EDU4_ALUNO_RPC."codigo_aluno_nao_informado") );
      }

      $oDadosAluno = new stdClass();

      $oAluno     = AlunoRepository::getAlunoByCodigo($oParam->iAluno);
      $oMatricula = MatriculaRepository::getUltimaMatriculaAluno($oAluno);

      $oDadosAluno->codigo_aluno      = $oAluno->getCodigoAluno();
      $oDadosAluno->nome_aluno        = urlencode($oAluno->getNome());
      $oDadosAluno->codigo_turma      = $oMatricula->getTurma()->getCodigo();
      $oDadosAluno->descricao_turma   = urlencode($oMatricula->getTurma()->getDescricao());
      $oDadosAluno->descricao_turno   = urlencode($oMatricula->getTurma()->getTurno()->getDescricao());
      $oDadosAluno->codigo_curso      = $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getCodigo();
      $oDadosAluno->codigo_base       = $oMatricula->getTurma()->getBaseCurricular()->getCodigoSequencial();
      $oDadosAluno->codigo_calendario = $oMatricula->getTurma()->getCalendario()->getCodigo();
      $oDadosAluno->nome_calendario   = urlencode($oMatricula->getTurma()->getCalendario()->getDescricao());
      $oDadosAluno->ano_calendario    = $oMatricula->getTurma()->getCalendario()->getAnoExecucao();
      $oDadosAluno->codigo_etapa      = $oMatricula->getEtapaDeOrigem()->getCodigo();
      $oDadosAluno->descricao_etapa   = urlencode($oMatricula->getEtapaDeOrigem()->getNome());
      $oDadosAluno->situacao_aluno    = urlencode($oMatricula->getSituacao());
      $oDadosAluno->data_matricula    = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
      $oDadosAluno->tipo_matricula    = urlencode($oMatricula->getTipo());

      $oRetorno->oDadosAluno = $oDadosAluno;

      break;

  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>