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
 * Classe para retorno dos dados de exportação do censo 2015
 * @author     andrio.costa@dbseller.com.br
 * @package    Educacao
 * @subpackage Censo
 * @subpackage Censo2015
 */

define("MSG_EXPORTACAO_CENSO_2015", "educacao.escola.ExportacaoCenso2015.");

class ExportacaoCenso2015 implements IExportacaoCenso {

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

    $this->iCodigoEscola = $iCodigoEscola;
    $this->iAnoCenso     = $iAnoCenso;
    $this->iCodigoLayout = 226;
  }

  /**
   * Retorna o Ano do Censo
   * @return integer
   */
  public function getAnoCenso() {
    return $this->iAnoCenso;
  }

  /**
   * Método para setar a data de pesquisa do Censo
   * @param date $dtBaseCenso
   */
  public function setDataCenso($dtBaseCenso) {
    $this->dtBaseCenso = $dtBaseCenso;
  }

  public function getDataCenso() {
    return $this->dtBaseCenso;
  }

  /**
   * Método para armazenar os erros em um arquivo de logs
   * @param string  $MensagemLog
   * @param integer $iTipo
   */
  public function logErro($MensagemLog, $iTipoLog) {
    $this->aLogCenso[$iTipoLog][] = urlencode($MensagemLog);
  }

  /**
   * Método que busca os dados do censo da escola
   * @return stdClass
   */
  protected function getDadosCensoEscola() {

    $this->oDadosEscola             = new stdClass();
    $oDadosCensoEscola              = new DadosCensoEscola2015($this->iCodigoEscola, $this->iAnoCenso, $this->dtBaseCenso);
    $this->oDadosEscola->registro00 = $oDadosCensoEscola->getDadosRegistro00();
    $this->oDadosEscola->registro10 = $oDadosCensoEscola->getDadosRegistro10();
    return $this->oDadosEscola;
  }

  /**
   * Retorna os dados processados referêntes a Escola
   * @return stdClass
   */
  public function getDadosProcessadosEscola() {
    return $this->oDadosEscola;
  }

  /**
   * Retorna os dados processados referêntes a Turma
   * @return array
   */
  public function getDadosProcessadosTurma() {
    return $this->aDadosCensoTurma;
  }

  /**
   * Retorna os dados processados referêntes ao Docente
   * @return array
   */
  public function getDadosProcessadosDocente() {
    return $this->aDadosCensoDocente;
  }

  /**
   * Retorna os dados processados referêntes ao Aluno
   * @return array
   */
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


  /**
   * Método destrutor
   */
  public function __destruct() {

    if ($this->rsArquivoLog != null) {
      fclose($this->rsArquivoLog);
    }
  }

  /**
   * Cria o arquivo txt. Retorna o caminho do arquivo gerado.
   * @return string
   * @throws Exception
   */
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
    /**
     * montamos o registro da Turma
     */
    foreach ($this->aDadosCensoTurma as $oTurma) {

      $oTurma->tipo_registro      = 20;
      $oTurma->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oTurma, 3 , "20");
    }

    /**
     * Dados dos Docentes escrevemos os dados do registro 30
     */
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

    /**
     * geramos os dados dos alunos
     */
    foreach ($this->aAlunos as $oAluno) {

      $oAluno->registro60->tipo_registro      = 60;
      $oAluno->registro60->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oAluno->registro60, 3, "60");

      $oAluno->registro70->tipo_registro      = 70;
      $oAluno->registro70->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
      $oLayout->setByLineOfDBUtils($oAluno->registro70, 3, "70");

      foreach ($oAluno->registro80 as $oMatricula) {

        $oMatricula->tipo_registro      = 80;
        $oMatricula->codigo_escola_inep = $this->oDadosEscola->registro00->codigo_escola_inep;
        $oLayout->setByLineOfDBUtils($oMatricula, 3, "80");
      }
    }

    return $sNomeArquivo;
  }

   /**
   * Método que busca as turmas da escola
   */
  protected function getDadosCensoTurma() {

    $this->getTurmasUnicas();

    $oDaoTurma     = new cl_turma();
    $sCamposTurma  = "ed57_i_codigo, ed57_c_descr";
    $sWhereTurma   = "     ed57_i_escola = {$this->iCodigoEscola} AND ed52_i_ano = '{$this->iAnoCenso}'";
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

      $oTurmaCenso = new DadosCensoTurma2015($iCodigosTurma);
      $oTurmaCenso->setDataCenso( $this->dtBaseCenso );

      if ( isset($this->aTurmasUnicas[$iCodigosTurma]) ) {

        $oTurmaCenso->setCodigoTurmaCenso($this->aTurmasUnicas[$iCodigosTurma]->ed342_sequencial);
        $oTurmaCenso->setEtapaTurmaCenso($this->aTurmasUnicas[$iCodigosTurma]->ed134_censoetapa);
        $oTurmaCenso->setNomeTurmaCenso($this->aTurmasUnicas[$iCodigosTurma]->ed342_nome);
        $oTurmaCenso->setTurmaUnificada(true);
      }

      $this->aDadosCensoTurma[] = $oTurmaCenso->getDados();
    }

    $oDaoTurmaAc     = new cl_turmaac();
    $sCamposTurmaAc  = "distinct ed268_i_codigo, ed268_c_descr, ed268_i_tipoatend";
    $sWhereTurmaAc   = "     ed268_i_escola = {$this->iCodigoEscola}";
    $sWhereTurmaAc  .= " AND ed52_i_ano = '{$this->iAnoCenso}'";
    $sSqlTurmaAc     = $oDaoTurmaAc->sql_query( "", $sCamposTurmaAc, "ed268_c_descr", $sWhereTurmaAc );
    $sResultTurmaAc  = db_query( $sSqlTurmaAc );
    if ( !$sResultTurmaAc ) {
      throw new DBException("Erro ao buscar Turmas AC. \n" . pg_last_error());
    }

    $iTotalTurmasAc  = pg_num_rows($sResultTurmaAc);

    $iTurmasACAEEMatriculasAntesDataCenso = 0;
    for ($i = 0; $i < $iTotalTurmasAc; $i++) {

      $oDadosTurmaAc        = db_utils::fieldsMemory($sResultTurmaAc, $i);
      $oDaoTurmaACMatricula = new cl_turmaacmatricula();

      $aWhereMatriculas   = array();
      $aWhereMatriculas[] = " ed269_i_turmaac = {$oDadosTurmaAc->ed268_i_codigo}";
      $aWhereMatriculas[] = " ed269_d_data <= '{$this->dtBaseCenso}'";

      /**
       * Quando turma é de atividade complementar, aluno deve ter matrícula ativa na escola
       */
      if ($oDadosTurmaAc->ed268_i_tipoatend == 4 ) {

        // Monta uma query para garantir que o aluno vinculado nas Turmas AC possui uma matricula em turmas normais
        $aFiltro            = array('ed60_i_aluno = ed269_aluno');
        $oDaoMatricula      = new cl_matricula();
        $sWhereMatricula    = $this->montarWhereAlunosMatriculados($aFiltro, false);

        $sSqlAlunoMatricula = $oDaoMatricula->sql_query_cancelaravalmatricula(null, " 1 ", null, $sWhereMatricula);

        $aWhereMatriculas[] = " exists ( {$sSqlAlunoMatricula} ) ";
      }
      $sWhereTurmaACMatricula  = implode( ' and ', $aWhereMatriculas);
      $sSqlTurmaACMatricula    = $oDaoTurmaACMatricula->sql_query_file( null, '1', null, $sWhereTurmaACMatricula );
      $rsTurmaACMatricula      = db_query( $sSqlTurmaACMatricula );

      if( !$rsTurmaACMatricula ) {
        throw new DBException( "Erro ao validar matrículas de turmas AC/AEE.\n" . pg_last_error() );
      }

      if( pg_num_rows( $rsTurmaACMatricula ) == 0 ) {
        continue;
      }

      $oTurmaCensoAc            = new DadosCensoTurmaEspecial($oDadosTurmaAc->ed268_i_codigo);
      $this->aDadosCensoTurma[] = $oTurmaCensoAc->getDados();

      $iTurmasACAEEMatriculasAntesDataCenso++;
    }


    if( $iTurmasACAEEMatriculasAntesDataCenso == 0 ) {

      $this->oDadosEscola->registro10->atividade_complementar                = '0';
      $this->oDadosEscola->registro10->atendimento_educacional_especializado = '0';
    }

    return $this->aDadosCensoTurma;
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

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException ( _M( MSG_EXPORTACAO_CENSO_2015 . 'erro_buscar_turmas_unicas', $oErro ) );
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
      $oDocenteCenso         = new DadosCensoDocente2015( $iCodigoDocente );
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
   * Preenche a lista de alunos do censo.
   */
  protected function getDadosAluno() {

    $rsMatricula  = $this->buscaAlunos();
    $iTotalAlunos = pg_num_rows($rsMatricula);
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

  protected function buscaAlunos() {

    $oDaoMatricula    = new cl_matricula();
    $sCamposAluno     = " DISTINCT ed47_i_codigo, ed47_v_nome ";
    $sWhereMatricula  = $this->montarWhereAlunosMatriculados();
    $sSqlMatricula    = $oDaoMatricula->sql_query("", $sCamposAluno, "", $sWhereMatricula);
    $sSql             =  "select * from ( ";
    $sSql            .= $sSqlMatricula;
    $sSql            .= " )  as t order by ed47_v_nome ";

    $rsAlunosMatriculados = db_query($sSql);
    if ( !$rsAlunosMatriculados ) {
      throw new DBException("Erro ao buscar os alunos.\n" . pg_last_error());
    }
    return $rsAlunosMatriculados;
  }

  protected function montarWhereAlunosMatriculados( $aOutrosFiltros = array(), $lFiltraEscola = true ) {

    $aWhere   = array();
    if ( $lFiltraEscola ) {
      $aWhere[] = " turma.ed57_i_escola   = {$this->iCodigoEscola} ";
    }
    $aWhere[] = " calendario.ed52_i_ano = {$this->iAnoCenso} ";
    $aWhere[] = " ed60_d_datamatricula <= '{$this->dtBaseCenso}' ";

    $sSituacaoMatricula  = "    (ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null)  ";
    $sSituacaoMatricula .= " or (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$this->dtBaseCenso}') ";

    $aWhere[] = "  ( {$sSituacaoMatricula} )";

    foreach ($aOutrosFiltros as $sFiltro) {
      $aWhere[] = $sFiltro;
    }

    return implode( ' and ', $aWhere);
  }


  /**
   * Realiza as validações dos dados de acordo com o layout do censo
   *
   * @throws Exception se encontrou erro
   * @return boolean
   */
  protected function validarDadosArquivo() {

    $lTodosDadosValidos = true;
    if (!$this->validarDadosEscola()) {
      $lTodosDadosValidos = false;
    }

    if (!$this->validarDadosTurma()) {
      $lTodosDadosValidos = false;
    }

    if (!$this->validarDadosDocente()) {
      $lTodosDadosValidos = false;
    }

    if (!$this->validarDadosAluno()) {
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
    return true;
  }


  /**
   * Valida os dados da Escola
   * @return boolean
   */
  protected function validarDadosEscola() {

    if ( !DadosCensoEscola2015::validarDados($this)) {
      return false;
    }
    return true;
  }

  /**
   * Valida os dados da Turma
   * @return boolean
   */
  protected function validarDadosTurma() {

    if ( !DadosCensoTurma2015::validarDados($this)) {
      return false;
    }
    return true;
  }

  /**
   * Valida os dados do Docente
   * @return boolean
   */
  protected function validarDadosDocente() {

    if (!DadosCensoDocente2015::validarDados($this)) {
      return false;
    }
    return true;
  }

  /**
   * Valida os dados dos Alunos
   * @return boolean
   */
  protected function validarDadosAluno() {

    if (!DadosCensoAluno2015::validarDados($this)) {
      return false;
    }
    return true;
  }
}

/**
 * Usado no array reduce
 * em um array de booleans, reduz a um único booleano;
 */
function validaVerdadeiro( $a, $b ) {

  if (is_null($a) ) {
    return $b;
  }
  return $a && $b;
}