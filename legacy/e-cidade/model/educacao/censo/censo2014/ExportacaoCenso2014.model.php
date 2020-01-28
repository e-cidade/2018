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
 * Classe para retorno dos dados de exportação do censo 2014
 * @author     andrio.costa@dbseller.com.br
 * @package    Educacao
 * @subpackage Censo
 */

define("MSG_EXPORTACAO_CENSO_2014", "educacao.escola.ExportacaoCenso2014.");

class ExportacaoCenso2014 extends ExportacaoCensoBase implements IExportacaoCenso {

  /**
   * Coleção das turmas únicas ( turmas vinculadas na tabela turmacenso)
   * @var array
   */
  private $aTurmasUnicas          = array();
  
  /**
   * Coleção das turmas que não devem ir no arquivo do censo pois compartilham a mesma sala e não são a turma principal.
   * @var array
   */
  private $aTurmasNaoVaiNoArquivo = array();

  public function __construct($iCodigoEscola, $iAnoCenso) {

    parent::__construct($iCodigoEscola, $iAnoCenso);
    $this->iCodigoLayout = 219;
  }

  protected function getTurmasUnicas() {

    $sCampo  = " ed134_censoetapa, ed343_turma, ed343_principal, ed342_nome, ed342_sequencial";
    $sWhere  = "     ed52_i_ano = {$this->iAnoCenso} ";
    $sWhere .= " and ed57_i_escola = {$this->iCodigoEscola} ";
    $sWhere .= " and ed134_ano = {$this->iAnoCenso}";

    $oDaoTurmaUnica  = new cl_turmacensoturma();
    $sSqlTurmaUnicia = $oDaoTurmaUnica->sql_query2(null, $sCampo, null, $sWhere);
    $rsTurmaUnica    = db_query($sSqlTurmaUnicia);

    if ( !$rsTurmaUnica ) {

      $oErro = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException ( _M(MSG_EXPORTACAO_CENSO_2014."erro_buscar_turmas_unicas", $oErro) );
    }

    $iLinhas = pg_num_rows( $rsTurmaUnica );

    for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

      $oDadosTurmas = db_utils::fieldsMemory( $rsTurmaUnica, $iContador );

      if ( $oDadosTurmas->ed343_principal == 't' ) {

        $this->aTurmasUnicas[ $oDadosTurmas->ed343_turma ] = $oDadosTurmas;
        continue;
      }

      $this->aTurmasNaoVaiNoArquivo[ $oDadosTurmas->ed343_turma ] = $oDadosTurmas;
    }

  }


  /**
   * Método que busca as turmas da escola
   */
  protected function getDadosCensoTurma() {

    $this->getTurmasUnicas();

    $oDaoTurma     = new cl_turma();
    $sCamposTurma  = "ed57_i_codigo, ed57_c_descr";
    $sWhereTurma   = "     ed57_i_escola = {$this->iCodigoEscola} AND ed52_i_ano = '{$this->iAnoCenso}' ";
    $sWhereTurma  .= " AND exists(select * from matricula where ed60_i_turma = ed57_i_codigo ";
    $sWhereTurma  .= "            AND ed60_d_datamatricula <= '{$this->dtBaseCenso}'  ";
    $sWhereTurma  .= "            AND ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
    $sWhereTurma  .= "            OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$this->dtBaseCenso}')))";

    $sSqlTurma     = $oDaoTurma->sql_query("",$sCamposTurma,"ed57_c_descr",$sWhereTurma);
    $rsResultTurma = $oDaoTurma->sql_record($sSqlTurma);

    $iTotalTurmas  = $oDaoTurma->numrows;
    for ($iTurma = 0; $iTurma < $iTotalTurmas; $iTurma++) {

      $iCodigosTurma = db_utils::fieldsMemory($rsResultTurma, $iTurma)->ed57_i_codigo;

      if ( isset($this->aTurmasNaoVaiNoArquivo[$iCodigosTurma]) ) {
        continue;
      }

      $oTurmaCenso      = new DadosCensoTurma($iCodigosTurma);

      if ( isset($this->aTurmasUnicas[$iCodigosTurma]) ) {

        $oTurmaCenso->setCodigoTurmaCenso($this->aTurmasUnicas[$iCodigosTurma]->ed342_sequencial);
        $oTurmaCenso->setEtapaTurmaCenso($this->aTurmasUnicas[$iCodigosTurma]->ed134_censoetapa);
        $oTurmaCenso->setNomeTurmaCenso($this->aTurmasUnicas[$iCodigosTurma]->ed342_nome);

      }
      $oDadosTurmaCenso = $oTurmaCenso->getDados();
      $this->aDadosCensoTurma[] = $oDadosTurmaCenso;
    }

    $oDaoTurmaAc     = db_utils::getDao("turmaac");
    $sCamposTurmaAc  = " ed268_i_codigo ";
    $sWhereTurmaAc   = "     ed268_i_escola = {$this->iCodigoEscola} AND ed52_i_ano = '{$this->iAnoCenso}' ";
    $sWhereTurmaAc  .= " AND exists(select * from turmaacmatricula  ";
    $sWhereTurmaAc  .= "                          inner join aluno on ed47_i_codigo = ed269_aluno ";
    $sWhereTurmaAc  .= "                          where ed269_i_turmaac = ed268_i_codigo AND ";
    $sWhereTurmaAc  .= "                                ed269_d_data <= '{$this->dtBaseCenso}')";
    $sSqlTurmaAc     = $oDaoTurmaAc->sql_query("",$sCamposTurmaAc,"ed268_c_descr",$sWhereTurmaAc);
    $sResultTurmaAc  = $oDaoTurmaAc->sql_record($sSqlTurmaAc);
    $iTotalTurmasAc  = $oDaoTurmaAc->numrows;

    for ($iTurmaAc = 0; $iTurmaAc < $iTotalTurmasAc; $iTurmaAc++) {

      $iCodigosTurmaAc            = db_utils::fieldsMemory($sResultTurmaAc, $iTurmaAc)->ed268_i_codigo;
      $oTurmaCensoAc              = new DadosCensoTurmaEspecial($iCodigosTurmaAc);
      $this->aDadosCensoTurma[]   = $oTurmaCensoAc->getDados();
    }
    return $this->aDadosCensoTurma;
  }

}