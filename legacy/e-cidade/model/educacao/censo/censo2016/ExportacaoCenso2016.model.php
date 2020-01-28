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
 * Classe responsável por gerar o censo 2016
 *
 * REGISTROS
 *  00 -> CADASTRO DE ESCOLA - IDENTIFICAÇÃO
 *  10 -> CADASTRO DE ESCOLA - CARACTERIZAÇÃO E INFRAESTRUTURA
 *  20 -> CADASTRO DE TURMA
 *  30 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - IDENTIFICAÇÃO
 *  40 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - DOCUMENTOS E ENDEREÇO
 *  50 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - DADOS VARIÁVEIS
 *  51 -> CADASTRO DE PROFISSIONAL ESCOLAR EM SALA DE AULA - DADOS DE DOCÊNCIA
 *  60 -> CADASTRO DE ALUNO - IDENTIFICAÇÃO
 *  70 -> CADASTRO DE ALUNO - DOCUMENTOS E ENDEREÇO
 *  80 -> CADASTRO DE ALUNO - VÍNCULO (MATRÍCULA)
 *  99 -> FIM DE ARQUIVO
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package    educacao
 * @subpackage censo
 * @subpackage censo2016
 *
 * @version   $Revision: 1.13 $
 */
class ExportacaoCenso2016 extends ExportacaoCenso2015 implements IExportacaoCenso {

  public function __construct($iCodigoEscola, $iAnoCenso) {

    $this->iCodigoEscola = $iCodigoEscola;
    $this->iAnoCenso     = $iAnoCenso;
    $this->iCodigoLayout = 255;
  }

  public function escreverArquivo() {

    $sNomeArquivo = "censo_esc_{$this->iCodigoEscola}_{$this->iAnoCenso}.txt";
    $oLayout      = new db_layouttxt($this->iCodigoLayout, "tmp/{$sNomeArquivo}");

    $this->getDadosCensoEscola();
    $this->getDadosCensoTurma();
    $this->getDadosCensoDocente();
    $this->getDadosAluno();

    $this->validarDadosArquivo();

    $oLayout->setByLineOfDBUtils($this->oDadosEscola->registro00, 3 , "00");
    $oLayout->setByLineOfDBUtils($this->oDadosEscola->registro10, 3 , "10");

    foreach ($this->aDadosCensoTurma as $oTurma) {

      $oTurma->tipo_registro      = 20;
      $oTurma->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oTurma, 3 , "20");
    }

    foreach ($this->aDadosCensoDocente as $oDocente) {

      $oDocente->registro30->tipo_registro      = 30;
      $oDocente->registro30->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oDocente->registro30, 3, "30");

      $oDocente->registro40->tipo_registro      = 40;
      $oDocente->registro40->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oDocente->registro40, 3, "40");

      $oDocente->registro50->tipo_registro      = 50;
      $oDocente->registro50->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oDocente->registro50, 3, "50");

      foreach ($oDocente->registro51 as $oDocencia) {

        $oDocencia->tipo_registro      = 51;
        $oDocencia->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
        $oLayout->setByLineOfDBUtils($oDocencia, 3, "51");
      }
    }

    foreach ($this->aAlunos as $oAluno) {

      $oAluno->registro60->tipo_registro      = 60;
      $oAluno->registro60->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oAluno->registro60, 3 , "60");

      $oAluno->registro70->tipo_registro      = 70;
      $oAluno->registro70->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oAluno->registro70, 3, "70");

      foreach ($oAluno->registro80 as $oMatricula) {

        $oMatricula->tipo_registro      = 80;
        $oMatricula->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
        $oLayout->setByLineOfDBUtils($oMatricula, 3, "80");
      }
    }

    // fim de arquivo
    $oRegistro99 = new stdClass();
    $oRegistro99->tipo_registro = '99|';
    $oLayout->setByLineOfDBUtils($oRegistro99, 3, "99");

    return $sNomeArquivo;
  }

  /**
   * Método que busca os docentes da escola
   */
  protected function getDadosCensoDocente() {

    $oDaoDocentes     = new cl_rechumano();
    $sCamposDocentes  = "  distinct (case when cgmrh.z01_numcgm is not null";
    $sCamposDocentes .= "             then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end) as numcgm, ed20_i_codigo ";
    $sWhereDocentes   = "       ed18_i_codigo = {$this->iCodigoEscola} ";
    $sWhereDocentes  .= "    and ed75_d_ingresso <= '{$this->dtBaseCenso}' \n";
    $sWhereDocentes  .= "    and ( ed75_i_saidaescola is null or ed75_i_saidaescola > '{$this->dtBaseCenso}' ) \n";
    $sWhereDocentes  .= "    and (    ed321_inicio is null or ed321_inicio > '{$this->dtBaseCenso}' \n";
    $sWhereDocentes  .= "          or ( ed321_inicio < '{$this->dtBaseCenso}' and ed321_final < '{$this->dtBaseCenso}' ) ) \n";
    $sWhereDocentes  .= "  and (ed01_funcaoatividade in (2,3,4) or (ed01_funcaoatividade = 1 and ed01_c_regencia = 'S'))";
    $sSqlDocentes     = $oDaoDocentes->sql_query_censo_2015("", $sCamposDocentes, "", $sWhereDocentes);
    $sSqlDocentes     = "select distinct on(numcgm) numcgm,ed20_i_codigo from ({$sSqlDocentes}) as x ";
    $sResultDocentes  = $oDaoDocentes->sql_record($sSqlDocentes);
    $iTotalDocentes   = $oDaoDocentes->numrows;

    for ($i = 0; $i < $iTotalDocentes; $i++) {

      $iCodigoDocente        = db_utils::fieldsMemory($sResultDocentes, $i)->ed20_i_codigo;
      $oDocenteCenso         = new DadosCensoDocente2016( $iCodigoDocente );
      $oDocenteCenso->setAnoCenso($this->iAnoCenso);
      $oDocenteCenso->setCodigoEscola($this->iCodigoEscola);
      $oDataCenso = new DBDate( $this->dtBaseCenso );
      $oDocenteCenso->setDataCenso( $oDataCenso );
      $oDocenteCenso->turmasCenso( $this->aDadosCensoTurma );

      $this->oDadosDocente             = new stdClass;
      $this->oDadosDocente->registro30 = $oDocenteCenso->getDadosIdentificacao();
      $this->oDadosDocente->registro40 = $oDocenteCenso->getEnderecoDocumento();
      $this->oDadosDocente->registro50 = $oDocenteCenso->getDadosFormacao();
      $this->oDadosDocente->registro51 = $oDocenteCenso->getDadosDocencia();
      $this->aDadosCensoDocente[]      = $this->oDadosDocente;
    }

    return $this->aDadosCensoDocente;
  }

  /**
   * Busca os alunos matrículados no ano de 2016 separando as informações dos registros 60, 70 e 80
   */
  protected function getDadosAluno() {

    $rsMatricula  = $this->buscaAlunos();
    $iTotalAlunos = pg_num_rows($rsMatricula);

    $aAlunosComMatriculas = array();
    for ($i = 0; $i < $iTotalAlunos; $i++) {

      $oAluno      = db_utils::fieldsMemory($rsMatricula, $i);
      $oDadosAluno = new stdClass();
      $oAlunoCenso = new DadosCensoAluno2016($oAluno->ed47_i_codigo, $this->iCodigoEscola);
      $oAlunoCenso->setAnoCenso($this->iAnoCenso);
      $oAlunoCenso->setDataCenso($this->dtBaseCenso);

      $oDadosAluno->registro60 = $oAlunoCenso->getDadosIdentificacao();
      $oDadosAluno->registro70 = $oAlunoCenso->getDadosEnderecoDocumento();
      $oDadosAluno->registro80 = $oAlunoCenso->getDadosMatricula();
      $this->aAlunos[]         = $oDadosAluno;

      $aAlunosComMatriculas[]  = $oAluno->ed47_i_codigo;

      unset($oAluno);
    }

    $this->getDadosAlunosTurmasAEE($aAlunosComMatriculas);

    return $this->aAlunos;
  }

  /**
   * Busca todos alunos matrículados em turmas AEE que não tenham matrículas na escola
   * @param array $aAlunosComMatriculas lista dos alunos com matrículas
   */
  protected function getDadosAlunosTurmasAEE($aAlunosComMatriculas = array()) {

    $sCampos  = " DISTINCT ed47_i_codigo, ed47_v_nome ";
    $aWhere   = array();
    $aWhere[] = " ed268_i_tipoatend = 5 ";
    $aWhere[] = " ed269_d_data  <= '{$this->dtBaseCenso}'";
    $aWhere[] = " ed52_i_ano     = {$this->iAnoCenso} ";
    $aWhere[] = " ed268_i_escola = {$this->iCodigoEscola} ";

    if ( !empty($aAlunosComMatriculas) ) {
      $aWhere[] = " ed47_i_codigo not in (". implode(', ', $aAlunosComMatriculas) . ")";
    }

    $oDaoTurmaAEE  = new cl_turmaacmatricula();
    $sSqlAlunosAEE = $oDaoTurmaAEE->sql_query_turma(null, $sCampos, "ed47_v_nome", implode( ' and ', $aWhere) );
    $rsAlunosAEE   = db_query($sSqlAlunosAEE);

    if ( !$rsAlunosAEE ) {
      throw new DBException("Erro ao buscar os alunos de turmas AEE.\n" . pg_last_error());
    }

    $iLinhas = pg_num_rows($rsAlunosAEE);
    for ($i = 0; $i < $iLinhas; $i++) {

      $oAluno      = db_utils::fieldsMemory($rsAlunosAEE, $i);
      $oDadosAluno = new stdClass();
      $oAlunoCenso = new DadosCensoAluno2016($oAluno->ed47_i_codigo, $this->iCodigoEscola);
      $oAlunoCenso->setAnoCenso($this->iAnoCenso);
      $oAlunoCenso->setDataCenso($this->dtBaseCenso);

      $oDadosAluno->registro60 = $oAlunoCenso->getDadosIdentificacao(false);
      $oDadosAluno->registro70 = $oAlunoCenso->getDadosEnderecoDocumento(false);
      $oDadosAluno->registro80 = $oAlunoCenso->getDadosMatricula(false);
      $this->aAlunos[]         = $oDadosAluno;

      unset($oAluno);
    }
  }
}
