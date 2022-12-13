<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

class BuscaDadosAlunos2016 {

  protected $aDados = array();

  /**
   * Busca os dados dos alunos de acordo com o ano do CENSO, Escola e alunos anteriores ou posteriores a data do CENSO.
   * @param CensoEscolar $oCenso
   * @param Escola $oEscola
   * @param boolean $lMatriculasAposCenso
   * @return array
   */
  function buscarAlunos( $oCenso, $oEscola, $aCondicoes ) {

    $sCampos  = "  distinct ";
    $sCampos .= "  ed18_c_codigoinep               as codigo_escola_inep  ";
    $sCampos .= " ,ed57_i_codigo                   as codigo_turma_escola  ";
    $sCampos .= " ,ed57_i_codigoinep               as codigo_turma_inep  ";
    $sCampos .= " ,ed47_c_codigoinep               as codigo_aluno_inep  ";
    $sCampos .= " ,ed47_i_codigo                   as codigo_aluno_escola  ";
    $sCampos .= " ,ed280_i_matcenso                as codigo_matricula_inep  ";
    $sCampos .= " ,ed10_mediacaodidaticopedagogica as mediacao_didatico_pedagogico  ";
    $sCampos .= " ,ed10_i_tipoensino               as modalidade  ";
    $sCampos .= " ,ed133_censoetapa                as etapa  ";
    $sCampos .= " ,ed60_i_codigo                   as codigo_matricula  ";
    $sCampos .= " ,ed132_censoetapa                as etapa_turma  ";

    $aSituacoesMatriculaNaoPermitidas = array('MATRICULA INDEVIDA', 'MATRICULA INDEFERIDA');

    $aWhere   = array();
    $aWhere[] = " ed60_c_situacao not in ('". implode("', '", $aSituacoesMatriculaNaoPermitidas)."') ";
    $aWhere[] = " escola.ed18_i_codigo  = {$oEscola->getCodigo()}";
    $aWhere[] = " calendario.ed52_i_ano = {$oCenso->getAno()} ";
    $aWhere[] = " ed221_c_origem = 'S' ";

    $aWhere = array_merge($aWhere, $aCondicoes);
    $sWhere = implode(" and ", $aWhere);

    $oDaoMatricula = new \cl_matricula();
    $sSqlMatricula = $oDaoMatricula->sql_query_censo_situacao_aluno( null, $sCampos, 'ed57_i_codigo', $sWhere );
    $rsMatricula   = db_query( $sSqlMatricula );

    if ( !$rsMatricula ) {
      throw new \DBException("Erro ao buscar dados dos alunos.");
    }

    $this->aDados = \db_utils::getCollectionByRecord( $rsMatricula );
  }
}
