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

/**
 * Repositoy para as progressoes parciais dos alunos
* @package    Educacao
* @subpackage progressaoparcial
* @author     Andrio Costa - andrio.costa@dbseller.com.br
* @version    $Revision: 1.31 $
*/
class ProgressaoParcialAlunoRepository {

  const MSG_PROGRESSAOPARCIALALUNOREPOSITORY = "educacao.escola.ProgressaoParcialAlunoRepository.";

  /**
   * Array com instancias de ProgressaoParcialAluno
   * @var array
   */
  private $aProgressaoParcialAluno = array();
  private static $oInstance;

  private function __construct() {

  }

  private function __clone(){

  }

  /**
   * Retorna a instancia do Repositorio
   * @return ProgressaoParcialAlunoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new ProgressaoParcialAlunoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a Progressao possui instancia, se nao instancia e retorna a instancia de ProgressaoParcialAluno
   * @param integer $iCodigoProgressao
   * @return ProgressaoParcialAluno
   */
  public static function getProgressaoParcialAlunoByCodigo($iCodigoProgressao) {

    if (!array_key_exists($iCodigoProgressao, ProgressaoParcialAlunoRepository::getInstance()->aProgressaoParcialAluno)) {
      ProgressaoParcialAlunoRepository::getInstance()
                                        ->aProgressaoParcialAluno[$iCodigoProgressao] = new ProgressaoParcialAluno($iCodigoProgressao);
    }

    return ProgressaoParcialAlunoRepository::getInstance()->aProgressaoParcialAluno[$iCodigoProgressao];

  }

  /**
   * Retorna uma colecao de ProgressaoParcialAluno que nao foram vinculado a nenhuma turma
   *
   * @param Regencia $oRegencia
   * @param Etapa    $oEtapa
   * @param Escola   $oEscola
   * @internal param \Turma $oTurma
   * @return ProgressaoParcialAluno[] Colecao de ProgressaoParcialAluno
   */
  public static function getAlunosComProgressaoParcialSemVinculoComTurma(Regencia $oRegencia, Etapa $oEtapa, Escola $oEscola, $lSomenteAlunosEscola) {

    $oDaoProgressaoAluno = new cl_progressaoparcialaluno();

    $aListaEtapa[]       = $oEtapa->getCodigo();
    $aEtapasEquivalentes = $oEtapa->buscaEtapaEquivalente();
    foreach($aEtapasEquivalentes as $oEtapaEquivalente) {
      $aListaEtapa[] = $oEtapaEquivalente->getCodigo();
    }

    $oTurmaRegencia = $oRegencia->getTurma();
    $iAnoCalendario = $oTurmaRegencia->getCalendario()->getAnoExecucao();
    $sWhere         = "     ed12_i_caddisciplina = " . $oRegencia->getDisciplina()->getCodigoDisciplinaGeral();
    $sWhere        .= " and ed114_serie      in(".implode(",", $aListaEtapa).")";
    $sWhere        .= " and ed114_situacaoeducacao  = " . ProgressaoParcialAluno::ATIVA;

    /**
     * Valida se o ano que o aluno ficou em progressão é menor/igual ao ano do calendário da turma que ele será
     * vinculado
     */
    $sWhere .= " and exists( select 1 ";
    $sWhere .= "               from progressaoparcialaluno progressao_aluno ";
    $sWhere .= "              where progressao_aluno.ed114_aluno = progressaoparcialaluno.ed114_aluno ";
    $sWhere .= "                and progressao_aluno.ed114_ano <= {$iAnoCalendario} )";

    $sWhere .= " and not exists (select 1";
    $sWhere .= "                   from progressaoparcialalunomatricula matricula";
    $sWhere .= "                  where matricula.ed150_progressaoparcialaluno  = ed114_sequencial ";
    $sWhere .= "                    and ed150_encerrado is false)";

    if ( $lSomenteAlunosEscola ) {
      $sWhere .= " and ed114_escola = " . $oEscola->getCodigo();
    }

    $sSqlProgressao = $oDaoProgressaoAluno->sql_query_alunos_turma(null, "ed114_sequencial", "ed47_v_nome", $sWhere);
    $rsProgressao   = $oDaoProgressaoAluno->sql_record($sSqlProgressao);
    $iRegistros     = $oDaoProgressaoAluno->numrows;
    for ($i = 0; $i < $iRegistros; $i++) {

      $iCodigoProgressao = db_utils::fieldsMemory($rsProgressao, $i)->ed114_sequencial;
      ProgressaoParcialAlunoRepository::adicionarProgressaoParcialAluno(new ProgressaoParcialAluno($iCodigoProgressao));
    }

    return ProgressaoParcialAlunoRepository::getInstance()->aProgressaoParcialAluno;
  }

  /**
   * Adiciona uma ProgressaoParcialAluno ao repositorio
   * @param ProgressaoParcialAluno $oProgressaoAluno
   * @return boolean
   */
  public static function adicionarProgressaoParcialAluno(ProgressaoParcialAluno $oProgressaoAluno) {

    ProgressaoParcialAlunoRepository::getInstance()->
                                      aProgressaoParcialAluno[$oProgressaoAluno->getCodigoProgressaoParcial()] = $oProgressaoAluno;
    return true;
  }

  /**
   * Remove uma ProgressaoParcialAluno do repositorio
   * @param ProgressaoParcialAluno $oProgressaoAluno
   * @return boolean
   */
  public static function removerProgressaoParcialAluno(ProgressaoParcialAluno $oProgressaoAluno) {

    if (array_key_exists($oProgressaoAluno->getCodigoProgressaoParcial(),
                         ProgressaoParcialAlunoRepository::getInstance()->aProgressaoParcialAluno)) {
      unset(ProgressaoParcialAlunoRepository::getInstance()
                                              ->aProgressaoParcialAluno[$oProgressaoAluno->getCodigoProgressaoParcial()]);
    }
    return true;
  }

  /**
   * Retorna todas as progressoes do aluno que estejam reprovadas MESMO as concluídas
   * @param Aluno $oAluno Aluno
   * @return ProgressaoParcialAluno[] Coleção de ProgressaoParcialAluno
   */
  public static function getProgressoesReprovadas(Aluno $oAluno) {

    $aProgressoes               = array();
    $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();
    $sWhere                     = "ed114_aluno = {$oAluno->getCodigoAluno()}";
    $sWhere                    .= " and ed114_situacaoeducacao  <> " . ProgressaoParcialAluno::INATIVA;
    $sWhere                    .= " and ed121_resultadofinal = 'R'";
    $sSqlProgressoes            = $oDaoProgressaoParcialAluno->sql_query_resultado_final(null,
                                                                                         "ed114_sequencial",
                                                                                         null,
                                                                                         $sWhere
                                                                                        );
    $rsProgressoes = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressoes);

    if ($oDaoProgressaoParcialAluno->numrows > 0) {

      for ($i = 0; $i < $oDaoProgressaoParcialAluno->numrows; $i++) {

         $iCodigoProgressao = db_utils::fieldsMemory($rsProgressoes, $i)->ed114_sequencial;
         $oProgressao       = new ProgressaoParcialAluno($iCodigoProgressao);
         ProgressaoParcialAlunoRepository::adicionarProgressaoParcialAluno($oProgressao);
         $aProgressoes[]  = $oProgressao;
      }
    }
    return $aProgressoes;
  }

  /**
   * Retorna todas as progressoes do aluno que nao foram encerradas
   * @param Aluno $oAluno Aluno
   * @return ProgressaoParcialAluno[] Coleção de ProgressaoParcialAluno
   */
  public static function getProgressoesNaoEncerradasDoAluno(Aluno $oAluno) {

    $aProgressoes               = array();
    $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();
    $sWhere                     = "ed114_aluno = {$oAluno->getCodigoAluno()}";
    $sWhere                    .= " and (ed150_encerrado is false or ed150_sequencial is null)";
    $sSqlProgressoes            = $oDaoProgressaoParcialAluno->sql_query_matricula(null,
                                                                                   "ed114_sequencial",
                                                                                   null,
                                                                                   $sWhere
                                                                                   );

    $rsProgressoes = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressoes);
    if ($oDaoProgressaoParcialAluno->numrows > 0) {

      for ($i = 0; $i < $oDaoProgressaoParcialAluno->numrows; $i++) {

        $iCodigoProgressao = db_utils::fieldsMemory($rsProgressoes, $i)->ed114_sequencial;
        $oProgressao       = new ProgressaoParcialAluno($iCodigoProgressao);
        ProgressaoParcialAlunoRepository::adicionarProgressaoParcialAluno($oProgressao);
        $aProgressoes[]  = $oProgressao;
      }
    }
    return $aProgressoes;
  }

  /**
   * Retorna as progressoes concluidas na Etapa
   * @param Aluno $oAluno Aluno para pesquisar as progressoes
   * @param Etapa $oEtapa Etapa
   * @return ProgressaoParcialAluno
   */
  public static function getProgressoesAprovadasNaEtapa(Aluno $oAluno, Etapa $oEtapa) {

    $aProgressoes               = array();
    $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();
    $sWhere                     = "ed114_aluno = {$oAluno->getCodigoAluno()}";
    $sWhere                    .= " and ed114_situacaoeducacao  = " . ProgressaoParcialAluno::CONCLUIDA;
    $sWhere                    .= " and ed114_serie = {$oEtapa->getCodigo()} ";
    $sSqlProgressoes            = $oDaoProgressaoParcialAluno->sql_query_resultado_final(null,
                                                                                         "ed114_sequencial",
                                                                                         null,
                                                                                         $sWhere
                                                                                        );
    $rsProgressoes = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressoes);
    if ($oDaoProgressaoParcialAluno->numrows > 0) {

      for ($i = 0; $i < $oDaoProgressaoParcialAluno->numrows; $i++) {

        $iCodigoProgressao = db_utils::fieldsMemory($rsProgressoes, $i)->ed114_sequencial;
        $oProgressao       = new ProgressaoParcialAluno($iCodigoProgressao);
        ProgressaoParcialAlunoRepository::adicionarProgressaoParcialAluno($oProgressao);

        $aProgressoes[] = $oProgressao;
      }
    }
    return $aProgressoes;
  }

  /**
   * Retorna todas as progressoes do aluno que estejam reprovadas na Disciplina
   *
   * @param Aluno     $oAluno Disciplina
   * @param Disciplina $oDisciplina
   * @return ProgressaoParcialAluno
   */
  public static function getProgressaoDoAlunoReprovadaNaDisciplina(Aluno $oAluno, Disciplina $oDisciplina) {

    $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();
    $sWhere                     = "ed114_aluno = {$oAluno->getCodigoAluno()}";
    $sWhere                    .= " and ed114_situacaoeducacao <> " . ProgressaoParcialAluno::INATIVA;
    $sWhere                    .= " and ed114_disciplina     = {$oDisciplina->getCodigoDisciplina()}";
    $sWhere                    .= " and ed121_resultadofinal = 'R'";
    $sSqlProgressoes            = $oDaoProgressaoParcialAluno->sql_query_resultado_final(null,
                                                                                         "ed114_sequencial",
                                                                                         null,
                                                                                         $sWhere
                                                                                        );

    $rsProgressoes = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressoes);

    if ( !$rsProgressoes || $oDaoProgressaoParcialAluno->numrows == 0 ) {
      return null;
    }

    /**
     * ATENCAO: PLUGIN ParametroProgressaoParcial - Código será substituido para retornar um array com as progressões da
     * mesma disciplina.
     */
    $iCodigoProgressao = db_utils::fieldsMemory($rsProgressoes, 0)->ed114_sequencial;
    $oProgressao       = new ProgressaoParcialAluno($iCodigoProgressao);
    ProgressaoParcialAlunoRepository::adicionarProgressaoParcialAluno($oProgressao);
    return $oProgressao;
  }

  /**
   * Retorna todas as progressoes do aluno que estejam ativas
   * @param Aluno $oAluno Aluno
   * @return ProgressaoParcialAluno[] Coleção de ProgressaoParcialAluno
   */
  public static function getProgressoesAtivas(Aluno $oAluno) {

    $aProgressoes               = array();
    $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();
    $sWhere                     = "ed114_aluno = {$oAluno->getCodigoAluno()}";
    $sWhere                    .= " and ed114_situacaoeducacao = " . ProgressaoParcialAluno::ATIVA;
    $sSqlProgressoes            = $oDaoProgressaoParcialAluno->sql_query_matricula(null,
                                                                                  "ed114_sequencial",
                                                                                    null,
                                                                                   $sWhere
                                                                                   );
    $rsProgressoes = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressoes);

    if ($oDaoProgressaoParcialAluno->numrows > 0) {

      for ($i = 0; $i < $oDaoProgressaoParcialAluno->numrows; $i++) {

         $iCodigoProgressao = db_utils::fieldsMemory($rsProgressoes, $i)->ed114_sequencial;
         $oProgressao       = new ProgressaoParcialAluno($iCodigoProgressao);
         ProgressaoParcialAlunoRepository::adicionarProgressaoParcialAluno($oProgressao);
         $aProgressoes[]  = $oProgressao;
      }
    }
    return $aProgressoes;
  }

  /**
   * Retorna todas as progressoes do aluno que estejam ativas anteriores a matriucla
   *
   * @param Matricula $oMatricula Matricula
   * @return ProgressaoParcialAluno[] Coleção de ProgressaoParcialAluno
   */
  public static function getProgressoesAtivasNaMatricula(Matricula $oMatricula) {

    $aProgressoes               = array();
    $oDaoProgressaoParcialAluno = new cl_progressaoparcialaluno();
    $sWhere                     = "ed114_aluno = {$oMatricula->getAluno()->getCodigoAluno()}";
    $sWhere                    .= " and (ed114_ano < {$oMatricula->getTurma()->getCalendario()->getAnoExecucao()})";
    $sWhere                    .= " and ed114_situacaoeducacao = " . ProgressaoParcialAluno::ATIVA;
    $sSqlProgressoes            = $oDaoProgressaoParcialAluno->sql_query_file(null, "ed114_sequencial",  null, $sWhere);
    $rsProgressoes = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressoes);

    if ($oDaoProgressaoParcialAluno->numrows > 0) {

      for ($i = 0; $i < $oDaoProgressaoParcialAluno->numrows; $i++) {

        $iCodigoProgressao = db_utils::fieldsMemory($rsProgressoes, $i)->ed114_sequencial;
        $oProgressao       = new ProgressaoParcialAluno($iCodigoProgressao);
        ProgressaoParcialAlunoRepository::adicionarProgressaoParcialAluno($oProgressao);
        $aProgressoes[]  = $oProgressao;
      }
    }
    return $aProgressoes;
  }

  /**
   * Retorna os alunos vinculados na regencia
   * @param  Regencia $oRegencia
   * @param  integer  $iFiltrar  0 - não filtra se a matricula esta encerrada ou não
   *                             1 - filtra somente matricula encerrada
   *                             2 - filtra somente matricula aberta
   * @return ProgressaoParcialAluno[]
   */
  public static function getProgressoesVinculadasRegencia(Regencia $oRegencia, $iFiltrar = 0) {

    $sWhere = " ed115_regencia = " . $oRegencia->getCodigo();
    switch ($iFiltrar) {
      case 1:
        $sWhere .= " and ed150_encerrado is true ";
        break;
      case 2:
        $sWhere .= " and ed150_encerrado is false ";
        break;
    }

    $oDaoProgressoes = new cl_progressaoparcialaluno();
    $sSqlProgressoes = $oDaoProgressoes->sql_query_alunos_turma(null, 'ed114_sequencial', null, $sWhere);
    $rsProgressoes   = db_query( $sSqlProgressoes );

    $oMsgErro = new stdClass();
    if ( !$rsProgressoes ) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MSG_PROGRESSAOPARCIALALUNOREPOSITORY . "erro_bucar_progressoes_regencia", $oMsgErro) );
    }

    $aProgressoes = array();
    $iLinhas      = pg_num_rows($rsProgressoes);
    for ( $i = 0; $i < $iLinhas; $i++) {

      $iCodigo        = db_utils::fieldsMemory($rsProgressoes, $i)->ed114_sequencial;
      $aProgressoes[] = ProgressaoParcialAlunoRepository::getProgressaoParcialAlunoByCodigo($iCodigo);
    }
    return $aProgressoes;
  }

  /**
   * Remove todas instancias de progressões parciais
   * @return boolean
   */
  public static function removeAll() {

    unset(ProgressaoParcialAlunoRepository::getInstance()->aProgressaoParcialAluno);
    ProgressaoParcialAlunoRepository::getInstance()->aProgressaoParcialAluno = array();
    return true;
  }

}