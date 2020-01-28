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

define("ARQUIVO_MENSAGEM_BASECURRICULAR", "educacao.escola.BaseCurricular.");
/**
 * Base Curricular de ensino
 * @package educacao
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 * @version $Revision: 1.10 $
 */
class BaseCurricular {

  /**
   * Codigo sequencial da base curricular
   * @var integer
   */
  private $iCodigoSequencial;

  /**
   * Descricao da base curricular
   * @var string
   */
  private $sDescricao;

  /**
   * Instancia de ensino
   * @var Ensino
   */
  private $oCurso;

  /**
   * Regime de Matricula da base curricular
   * @var RegimeMatricula
   */
  private $oRegimeMatricula = null;

  /**
   * Codigo do regime de matricula
   * @var integer
   */
  private $iCodigoRegime;

  /**
   * Base curricular de continuacao do curso
   * @var BaseCurricular
   */
  private $oBaseContinuacao = null;

  /**
   * Codigo da base curricular de continuacao
   * @var integer
   */
  private $iCodigoBaseContinuacao = null;

  /**
   * Disciplina que compoem a Etapa de uma Base curricular
   * @var array
   */
  private $aDisciplinas = array();

  /**
   * ultima etapa a ser cursada na base
   * @var Etapa
   */
  private $oEtapaFinal = null;

  /**
   * Define se a base curriuclar encerra o curso do aluno
   * @var boolean
   */
  private $lEncerraCurso = false;

  /**
   * Tipo de frequencia da base curricular
   * P - Periodos
   * D - Dias Letivos
   * @var string
   */
  private $sFrequencia;

  /**
   * Forma de controle de frequencia da base curricular
   * I - Individual
   * G - Globalizada
   * @var string
   */
  private $sControleFrequencia;

  /**
   * Turno na qual a base é utilizada
   * @var String
   */
  private $sTurno;

  /**
   * Etapa inicial da base
   * @var Etapa
   */
  private $oEtapaInicial = null;

  /**
   * Controla se a base curriclar está ativa
   * @var boolean
   */
  private $lAtiva = false;

  /**
   * Observação da base curricular
   * @var string
   */
  private $sObservacao = "";

  /**
   * Cria uma base curricular
   * Caso for informado o parametro $iCodigoSequencial, os dados da base serão carregados nessa instancia.
   * @param integer $iCodigoSequencial codigo da base curricular
   */
  public function __construct($iCodigoSequencial = null) {

    if (!empty($iCodigoSequencial)) {

      $oDaoBaseCurricular   = new cl_base();
      $sWhere               = "ed31_i_codigo = {$iCodigoSequencial}";
      $sSqlBaseCurricular   = $oDaoBaseCurricular->sql_query_file(null, "*", null, $sWhere);
      $rsBaseCurricular     = $oDaoBaseCurricular->sql_record($sSqlBaseCurricular);
      $iTotalBaseCurricular = $oDaoBaseCurricular->numrows;

      if ($iTotalBaseCurricular > 0) {

        $oDadosBaseCurricular         = db_utils::fieldsMemory($rsBaseCurricular, 0);
        $this->iCodigoSequencial      = $oDadosBaseCurricular->ed31_i_codigo;
        $this->sDescricao             = $oDadosBaseCurricular->ed31_c_descr;
        $this->oCurso                 = new Curso($oDadosBaseCurricular->ed31_i_curso);
        $this->iCodigoRegime          = $oDadosBaseCurricular->ed31_i_regimemat;
        $this->lEncerraCurso          = $oDadosBaseCurricular->ed31_c_conclusao == 'S' ? true : false;
        $this->sControleFrequencia    = $oDadosBaseCurricular->ed31_c_contrfreq;
        $this->sFrequencia            = $oDadosBaseCurricular->ed31_c_medfreq;
        $this->sTurno                 = $oDadosBaseCurricular->ed31_c_turno;
        $this->lAtiva                 = $oDadosBaseCurricular->ed31_c_ativo == 'S' ? true : false;
        $this->sObservacao            = $oDadosBaseCurricular->ed31_t_obs;
        unset($oDadosBaseCurricular);
      }
    }
  }

  /**
   * Retorna o codigo sequencial da base curricular
   * @return integer
   */
  public function getCodigoSequencial() {
    return $this->iCodigoSequencial;
  }

  /**
   * Retorna a descricao da base curricular
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descricao da base curricular
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna uma instancia de Curso
   * @return Curso
   */
  public function getCurso() {
    return $this->oCurso;
  }

  /**
   * Atribui uma instancia de Curso
   * @param Curso $oCurso
   */
  public function setCurso(Curso $oCurso) {
    $this->oCurso = $oCurso;
  }

  /**
   * Regime de matricula da base curricular
   * @return RegimeMatricula Regime de Matricula
   */
  public function getRegimeMatricula() {

    if (empty($this->oRegimeMatricula) && !empty($this->iCodigoRegime)) {
      $this->oRegimeMatricula = new RegimeMatricula($this->iCodigoRegime);
    }
    return $this->oRegimeMatricula;
  }

  /**
   * Retorna a base de continuacao da base curricular
   * Caso a base curricular possua base de continuar, retorna a base de continuacao, ao contrario retorna null
   * @return BaseCurricular|null base de continuacao da Base Curricular
   */
  public function getBaseDeContinuacao() {

    if ( !empty($this->iCodigoSequencial) && empty($this->iCodigoBaseContinuacao) ) {

      $oDaoBaseEscola   = new cl_escolabase();
      $sWhereBaseEscola = " ed77_i_base = {$this->iCodigoSequencial} ";
      $sSqlBaseEscola   = $oDaoBaseEscola->sql_query_file( null, 'ed77_i_basecont', null, $sWhereBaseEscola);
      $rsBaseEscola     = db_query( $sSqlBaseEscola );

      if ( !$rsBaseEscola ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_BASECURRICULAR . "erro_buscar_vinculo_escola" ) );

      }

      if ( pg_num_rows($rsBaseEscola) > 0 ) {

        $this->iCodigoBaseContinuacao = db_utils::fieldsMemory( $rsBaseEscola, 0 )->ed77_i_basecont;
        $this->oBaseContinuacao = new BaseCurricular($this->iCodigoBaseContinuacao);
      }
    }

    return $this->oBaseContinuacao;
  }

  /**
   * Retorna um array com a instancia das disciplinas de uma etapa da base curricular
   * @todo Refatorar metodo. Está faltando figuras da disciplina da da base curricular.
   * @return Disciplina:
   */
  public function getDisciplina(Etapa $oEtapa) {

    $this->aDisciplinas = array();

    $oDaoBaseMPS = db_utils::getDao('basemps');

    $sWhere  = "     ed34_i_base = {$this->iCodigoSequencial}";
    $sWhere .= " and ed34_i_serie = " . $oEtapa->getCodigo();

    $sSqlBaseMPS = $oDaoBaseMPS->sql_query_file(null, "ed34_i_disciplina", "ed34_i_ordenacao", $sWhere);
    $rsBaseMPS   = $oDaoBaseMPS->sql_record($sSqlBaseMPS);
    $iTotalMPS   = $oDaoBaseMPS->numrows;

    if ($iTotalMPS > 0) {

      for ($iContador = 0; $iContador < $iTotalMPS; $iContador++) {

        $iCodigoDisciplina    = db_utils::fieldsMemory($rsBaseMPS, $iContador)->ed34_i_disciplina;
        $this->aDisciplinas[] = DisciplinaRepository::getDisciplinaByCodigo($iCodigoDisciplina);
        unset($iCodigoDisciplina);
      }
    }
    return $this->aDisciplinas;
  }

  /**
   * Verifica se a base curricular encerra o curso do aluno
   * @return boolean
   */
  public function encerraCurso() {
    return $this->lEncerraCurso;
  }

  /**
   * Retorna a última etapa da base curricular
   * @return Etapa
   */
  public function getEtapaFinal() {

    if (empty($this->oEtapaFinal)) {

      $oDaoBaseSerie = db_utils::getDao("baseserie");
      $sSqlEtapas    = $oDaoBaseSerie->sql_query_file($this->iCodigoSequencial);
      $rsUltimaEtapa = $oDaoBaseSerie->sql_record($sSqlEtapas);
      if ($rsUltimaEtapa && $oDaoBaseSerie->numrows > 0) {

        $oDadosEtapa         = db_utils::fieldsMemory($rsUltimaEtapa, 0);
        $this->oEtapaInicial = EtapaRepository::getEtapaByCodigo($oDadosEtapa->ed87_i_serieinicial);
        $this->oEtapaFinal   = EtapaRepository::getEtapaByCodigo($oDadosEtapa->ed87_i_seriefinal);
      }
    }
    return $this->oEtapaFinal;
  }

  /**
   * Retorna o tipo de frequencia da base curricular
   * @return string
   */
  public function getFrequencia() {
    return $this->sFrequencia;
  }

  /**
   * Seta o tipo de frequencia da base curricular
   * @param string $sFrequencia
   */
  public function setFrequencia($sFrequencia) {
    $this->sFrequencia = $sFrequencia;
  }

  /**
   * Retorna a forma de controle de frequencia da base
   * @return string
   */
  public function getControleFrequencia() {
    return $this->sControleFrequencia;
  }

  /**
   * Seta a forma de controle de frequencia da base
   * @param string
   */
  public function setControleFrequencia($sControleFrequencia) {
    $this->sControleFrequencia = $sControleFrequencia;
  }

  /**
   * Retorna o turno
   * @return string
   */
  public function getTurno() {
    return $this->sTurno;
  }

  /**
   * Define o turno da base
   * @param string $sTurno
   * @return string
   */
  public function setTurno( $sTurno ) {
    $this->sTurno = $sTurno;
  }

  /**
   * Retorna a etapa inicial da base curricular
   * @return Etapa
   */
  public function getEtapaInicial() {

    if ( empty($this->oEtapaInicial) ) {

      $oDaoBaseSerie = new cl_baseserie();
      $sSqlEtapas    = $oDaoBaseSerie->sql_query_file($this->iCodigoSequencial);
      $rsEtapas      = db_query( $sSqlEtapas );

      if ( !$rsEtapas ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_BASECURRICULAR . "erro_buscar_etapa_inicial" ) );
      }

      if ( pg_num_rows($rsEtapas) > 0 ) {

        $oDadosEtapa         = db_utils::fieldsMemory($rsEtapas, 0);
        $this->oEtapaInicial = EtapaRepository::getEtapaByCodigo($oDadosEtapa->ed87_i_serieinicial);
        $this->oEtapaFinal   = EtapaRepository::getEtapaByCodigo($oDadosEtapa->ed87_i_seriefinal);
      }
    }
    return $this->oEtapaInicial;
  }

  /**
   * Retorna se a base curricular está ativa
   * @return boolean
   */
  public function isAtiva() {
    return $this->lAtiva;
  }

  /**
   * Retorna a observação
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

}
