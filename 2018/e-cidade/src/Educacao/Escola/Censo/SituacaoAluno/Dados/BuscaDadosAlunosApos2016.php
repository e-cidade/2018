<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;


/**
 * Busca os dados dos alunos que entraram após a data do censo
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.2 $
 */
class BuscaDadosAlunosApos2016 extends BuscaDadosAlunos2016 {

  public function __construct($oCenso, $oEscola, $aAlunosAntesCenso ) {

    $aCondicoes   = array();
    $aCondicoes[] = " matricula.ed60_d_datamatricula > '" . $oCenso->getDataCenso()->getDate(). "'";
    $aCondicoes[] = " matricula.ed60_i_aluno not in (" . implode(', ', $aAlunosAntesCenso) . ") ";

    $this->buscarAlunos($oCenso, $oEscola, $aCondicoes);
  }

  /**
   * Retorna os dados dos alunos com matrículas após data do CENSO.
   * @return stdClass[]
   */
  public function getDados() {
    return $this->aDados;
  }

}