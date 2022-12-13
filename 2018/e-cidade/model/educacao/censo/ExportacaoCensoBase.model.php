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
 * Base para a Exportação do Censo
 * @author André Mello
 * @package Educacao
 * @subpackage Censo
 */
abstract class ExportacaoCensoBase {

  protected $iCodigoLayout;

  /**
   * Código da escola
   * @var integer
   */
  protected $iCodigoEscola;

  /**
   * Ano do Censo
   * @var integer
   */
  protected $iAnoCenso;

  /**
   * Data do Censo
   * @var date
   */
  protected $dtBaseCenso;

  /**
   * Objeto com os dados da escola
   * @var object
   */
  protected $oDadosEscola;

  /**
   * Departamento
   * @var string
   */
  protected $sEscola;

  /**
   * Array com as turmas
   * @var Array
   */
  protected $aDadosCensoTurma = array();

  /**
   * Array com os dados dos docentes
   * @var Array
   */
  protected $aDadosCensoDocente = array();

  protected $rsArquivoLog;

  /**
   * Nome do arquivo de log
   * @var string
   */
  protected $sNomeArquivoLog;
  /**
   * Array com os dados dos aluno
   * @var array
   */
  protected $aAlunos = array();

  /**
   * Constantes para indexar os erros encorados ao escrever o arquivo do censo.
   */
  const LOG_ESCOLA  = 1;
  const LOG_TURMA   = 2;
  const LOG_DOCENTE = 3;
  const LOG_ALUNO   = 4;

  private $aLogCenso = array();

  /**
   * Método construtor
   *
   */
  public function __construct($iCodigoEscola, $iAnoCenso) {

    $this->iCodigoEscola     = $iCodigoEscola;
    $this->iAnoCenso         = $iAnoCenso;
  }

 /* Retorna o Ano do censo
  */
  public function getAnoCenso() {
    return $this->iAnoCenso;
  }
  
  /**
   * Método que busca os dados do censo da escola
   * @return stdClass
   */
  protected function getDadosCensoEscola() {

    $this->oDadosEscola = new stdClass();
    $oDadosCensoEscola  = new DadosCensoEscola($this->iCodigoEscola,
                                               $this->iAnoCenso,
                                               $this->dtBaseCenso
                                             );
    $this->oDadosEscola->registro00 = $oDadosCensoEscola->getDadosIdentificacao();
    $this->oDadosEscola->registro10 = $oDadosCensoEscola->getDadosInfraEstrutura();
    return $this->oDadosEscola;
  }

  /**
   * Método que busca as turmas da escola
   */
  protected function getDadosCensoTurma() {

    $oDaoTurma     = db_utils::getDao("turma");
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

      $iCodigosTurma            = db_utils::fieldsMemory($rsResultTurma, $iTurma)->ed57_i_codigo;
      $oTurmaCenso              = new DadosCensoTurma($iCodigosTurma);
      $this->aDadosCensoTurma[] = $oTurmaCenso->getDados();
    }

    $oDaoTurmaAc     = db_utils::getDao("turmaac");
    $sCamposTurmaAc  = " ed268_i_codigo ";
    $sWhereTurmaAc   = "     ed268_i_escola = {$this->iCodigoEscola} AND ed52_i_ano = '{$this->iAnoCenso}' ";
    $sWhereTurmaAc  .= " AND exists(select * from turmaacmatricula  ";
    // $sWhereTurmaAc  .= "                          inner join matricula on ed60_i_codigo = ed269_i_matricula ";
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

  /**
   * Método que busca os docentes da escola
   */
  protected function getDadosCensoDocente() {

    $oDaoDocentes     = new cl_rechumano();
    $sCamposDocentes  = "  distinct (case when cgmrh.z01_numcgm is not null";
    $sCamposDocentes .= "             then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end) as numcgm, ed20_i_codigo ";
    $sWhereDocentes   = "       ed18_i_codigo = {$this->iCodigoEscola} ";
    $sWhereDocentes  .= "  and (ed75_i_saidaescola is null OR ed75_i_saidaescola  >= '{$this->dtBaseCenso}') ";
    $sWhereDocentes  .= "  and (ed01_funcaoatividade in (2,3,4) or  (ed01_funcaoatividade = 1 and ed01_c_regencia = 'S'))";

    $sSqlDocentes     = $oDaoDocentes->sql_query_censo("", $sCamposDocentes, "", $sWhereDocentes);

    $sSqlDocentes     = "select distinct on(numcgm) numcgm,ed20_i_codigo from ({$sSqlDocentes}) as x ";
    $sResultDocentes  = $oDaoDocentes->sql_record($sSqlDocentes);
    $iTotalDocentes   = $oDaoDocentes->numrows;

    for ($i = 0; $i < $iTotalDocentes; $i++) {

      $iCodigoDocente        = db_utils::fieldsMemory($sResultDocentes, $i)->ed20_i_codigo;
      $oDocenteCenso         = new DadosCensoDocente($iCodigoDocente);
      $oDocenteCenso->setAnoCenso($this->iAnoCenso);
      $oDocenteCenso->setCodigoEscola($this->iCodigoEscola);
      $oDataCenso = new DBDate( $this->dtBaseCenso );
      $oDocenteCenso->setDataCenso( $oDataCenso );

      $this->oDadosDocente         = new stdClass;
      $this->oDadosDocente->registro30 = $oDocenteCenso->getDadosIdentificacao();
      $this->oDadosDocente->registro40 = $oDocenteCenso->getEnderecoDocumento();
      $this->oDadosDocente->registro50 = $oDocenteCenso->getDadosFormacao();
      $this->oDadosDocente->registro51 = $oDocenteCenso->getDadosDocencia();
      $this->aDadosCensoDocente[]      = $this->oDadosDocente;
    }
    return $this->aDadosCensoDocente;
  }

  /**
   * Método para setar a data de pesquisa do Censo
   */
  public function setDataCenso($dtBaseCenso) {
    $this->dtBaseCenso = $dtBaseCenso;
  }

  /**
   * Método para armazenar os erros em um arquivo de logs
   */
  public function logErro($MensagemLog, $iTipoLog) {

    $this->aLogCenso[$iTipoLog][] = urlencode($MensagemLog);
  }

  /**
   * Preenche a lista de alunos do censo.
   */
  protected function getDadosAluno() {

    $oDaoMatricula    = db_utils::getDao("matricula");
    $sCamposAluno     = " DISTINCT ed47_i_codigo, ed47_v_nome ";
    $sWhereMatricula  = " turma.ed57_i_escola = {$this->iCodigoEscola} ";
    $sWhereMatricula .= "  AND calendario.ed52_i_ano = {$this->iAnoCenso} ";
    $sWhereMatricula .= "  AND ed60_d_datamatricula <= '{$this->dtBaseCenso}' ";
    $sWhereMatricula .= "  AND ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
    $sWhereMatricula .= "       OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$this->dtBaseCenso}'))";
    $sSqlMatricula    = $oDaoMatricula->sql_query("", $sCamposAluno, "", $sWhereMatricula);
    $sSql             =  "select * from ( ";
    $sSql            .= $sSqlMatricula;
    $sSql            .= " )  as t order by ed47_v_nome ";
    $rsMatricula      = $oDaoMatricula->sql_record($sSql);
    $iTotalAlunos     = $oDaoMatricula->numrows;
    for ($iAluno = 0; $iAluno < $iTotalAlunos; $iAluno++) {

      $oAluno       = db_utils::fieldsMemory($rsMatricula, $iAluno);
      $oDadosAluno = new stdClass();
      $oAlunoCenso = new DadosCensoAluno($oAluno->ed47_i_codigo, $this->iCodigoEscola);
      $oAlunoCenso->setAnoCenso($this->iAnoCenso);
      $oAlunoCenso->setDataCenso($this->dtBaseCenso);
      $oDadosAluno->registro60 = $oAlunoCenso->getDadosIdentificacao();
      $oDadosAluno->registro70 = $oAlunoCenso->getDadosEnderecoDocumento();
      $oDadosAluno->registro80 = $oAlunoCenso->getDadosMatricula();
      $this->aAlunos[]         = $oDadosAluno;
      unset($oAluno);
    }
    return $this->aAlunos;
  }

  /**
   * Cria o arquivo txt.
   * retorna o caminho do arquivo gerado.
   * @return string
   */
  public function escreverArquivo() {

    $sNomeArquivo = "censo_escola_{$this->iCodigoEscola}_{$this->iAnoCenso}.txt";
    $oLayout      = new db_layouttxt($this->iCodigoLayout, "tmp/{$sNomeArquivo}");

    $this->getDadosCensoEscola();
    $this->getDadosCensoTurma();
    $this->getDadosCensoDocente();
    $this->getDadosAluno();
    $this->oDadosEscola->registro00->tipo_registro = "00";
    $this->oDadosEscola->registro00->pipe  = "";
    $this->oDadosEscola->registro10->tipo_registro = "10";
    $this->oDadosEscola->registro10->pipe  = "";

    /**
     * Validados os dados da escola.
     */
    $lTodosDadosValidos = true;
    if (!DadosCensoEscola::validarDados($this)) {
      $lTodosDadosValidos = false;
    }
    if (!DadosCensoTurma::validarDados($this)) {
      $lTodosDadosValidos = false;
    }
    if (!DadosCensoDocente::validarDados($this)) {
      $lTodosDadosValidos = false;
    }
    if (!DadosCensoAluno::validarDados($this)) {
      $lTodosDadosValidos = false;
    }

    if (!$lTodosDadosValidos) {

      $oJson          = new services_json();
      $sNomeArquivo  = "tmp/censo_".$this->iCodigoEscola."_".db_getsession("DB_id_usuario")."_{$this->iAnoCenso}_logerro.json";
      $sJson         = $oJson->encode($this->aLogCenso);

      $this->sNomeArquivoLog = $sNomeArquivo;
      $this->rsArquivoLog    = fopen($sNomeArquivo, "w");
      fwrite($this->rsArquivoLog, "{$sJson}\n");
      throw new Exception("Existem dados inconsistentes no censo. Verifique o arquivo de log");
    }

    $oLayout->setByLineOfDBUtils($this->oDadosEscola->registro00, 3 , "00");
    $oLayout->setByLineOfDBUtils($this->oDadosEscola->registro10, 3 , "10");
    /**
     * montamos o registro da Turma
     */
    foreach ($this->aDadosCensoTurma as $oTurma) {

      $oTurma->tipo_registro      = 20;
      $oTurma->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oTurma->pipe       = "";
      $oLayout->setByLineOfDBUtils($oTurma, 3 , "20");
    }

    /**
     * Dados dos Docentes escrevemos os dados do registro 30
     */
    foreach ($this->aDadosCensoDocente as $oDocente) {

      $oDocente->registro30->tipo_registro      = 30;
      $oDocente->registro30->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oDocente->registro30->pipe       = '';
      $oLayout->setByLineOfDBUtils($oDocente->registro30, 3, "30");

      $oDocente->registro40->tipo_registro      = 40;
      $oDocente->registro40->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oDocente->registro40->pipe       = '';
      $oLayout->setByLineOfDBUtils($oDocente->registro40, 3, "40");

      $oDocente->registro50->tipo_registro      = 50;
      $oDocente->registro50->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oDocente->registro50->pipe       = '';
      $oLayout->setByLineOfDBUtils($oDocente->registro50, 3, "50");

      foreach ($oDocente->registro51 as $oDocencia) {

        $oDocencia->tipo_registro      = 51;
        $oDocencia->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
        $oDocencia->pipe       = '';
        $oLayout->setByLineOfDBUtils($oDocencia, 3, "51");
      }
    }
    /**
     * geramos os dados dos alunos
     */
    foreach ($this->aAlunos as $oAluno) {

      $oAluno->registro60->tipo_registro      = 60;
      $oAluno->registro60->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oAluno->registro60->pipe       = '';
      $oLayout->setByLineOfDBUtils($oAluno->registro60, 3, "60");

      $oAluno->registro70->tipo_registro      = 70;
      $oAluno->registro70->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oAluno->registro70->pipe       = '';
      $oLayout->setByLineOfDBUtils($oAluno->registro70, 3, "70");

      foreach ($oAluno->registro80 as $oMatricula) {

        $oMatricula->tipo_registro      = 80;
        $oMatricula->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
        $oMatricula->pipe               = '';
        $oLayout->setByLineOfDBUtils($oMatricula, 3, "80");
      }

    }
    return $sNomeArquivo;

  }

  public function getDadosProcessadosEscola() {
    return $this->oDadosEscola;
  }

  public function getDadosProcessadosTurma() {
    return $this->aDadosCensoTurma;
  }

  public function getDadosProcessadosDocente() {
    return $this->aDadosCensoDocente;
  }

  public function getDadosProcessadosAluno() {
    return $this->aAlunos;
  }

  /**
   * Retorna o nome do arquivo de log
   * @return string
   */
  public function getNomeArquivoLog() {
    return $this->sNomeArquivoLog;
  }

  public function getDataCenso() {
    return $this->dtBaseCenso;
  }

  /**
   * Método destrutor
   */
  public function __destruct() {

    if ($this->rsArquivoLog != null) {
      fclose($this->rsArquivoLog);
    }
  }
}