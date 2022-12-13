<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 20/02/14
 * Time: 15:49
 */

/**
 * Resultado de Um exame Clinico
 * Class ResultadoExame
 */
class ResultadoExame {

  const FONTE_MSG = 'saude.laboratorio.ResultadoExame.';

  /**
   * Requisicao do Exame
   * @var RequisicaoExame
   */
  private $oRequisicao;


  /**
   * Codigo do Resultado
   * @var integer
   */
  private $iResultado;

  /**
   * Diagnostico do Exame
   * @var string
   */
  private $sConsideracao = '';

  /**
   * @var ResultadoExameAtributo[]
   */
  private $aResultadoAtributos = array();

  /**
   * Data do resultado
   * @var DBDate
   */
  private $oData;

  /**
   * Resultados do exame anterior
   * @var ResultadoExameAtributo[]
   */
  private $aResultadoDoExameAnterior = array();

  /**
   * Requisição do exame anterior
   * @var RequisicaoExame
   */
  private $oRequisicaoAnterior;

  /**
   * Instancia um novo Resultado
   * @param RequisicaoExame $oRequisicao
   */
  public function __construct(RequisicaoExame $oRequisicao) {

    $this->oRequisicao  = $oRequisicao;
    $oDaoResultadoExame = new cl_lab_resultado();
    $sWhere             = "la52_i_requiitem = {$this->oRequisicao->getCodigo()}";
    $sSqlResultado      = $oDaoResultadoExame->sql_query_file(null, "*",  null, $sWhere );
    $rsResultado        = $oDaoResultadoExame->sql_record($sSqlResultado);
    if ($rsResultado && $oDaoResultadoExame->numrows > 0) {

      $oDados              = db_utils::fieldsMemory($rsResultado, 0);
      $this->iResultado    = $oDados->la52_i_codigo;
      $this->sConsideracao = $oDados->la52_diagnostico;
      $this->oData         = DBDate::create( $oDados->la52_d_data );
    }
  }

  /**
   * Retorna todos Atributos com seus Resultados
   * @return ResultadoExameAtributo[]
   */
  public function getResultadoDosAtributos() {

    if (count($this->aResultadoAtributos) == 0 && !empty($this->iResultado)) {
      $this->aResultadoAtributos = $this->buscarResultadosAtributo($this->iResultado);
    }
    return $this->aResultadoAtributos;
  }

  /**
   * Retorna os resultados dos atributos
   * @param  integer $iCodigoResultado código do resultado de um exame
   * @return ResultadoExameAtributo[]
   */
  private function buscarResultadosAtributo($iCodigoResultado) {

    $aResultados = array();

    $sCampos  = "la39_i_atributo,";
    $sCampos .= "la39_i_codigo,";
    $sCampos .= "la39_titulacao,";
    $sCampos .= "case when la41_f_valor is not null then cast(la41_f_valor as varchar)";
    $sCampos .= "     when la40_i_valorrefsel is not null then cast(la40_i_valorrefsel as varchar)";
    $sCampos .= "     when la40_c_valor <> '' then la40_c_valor  else '' end as valor,";
    $sCampos .= "la41_valorpercentual as valorpercentual,";
    $sCampos .= "la41_faixaescolhida as faixautilizada";
    $sWhere   = "la39_i_resultado = {$iCodigoResultado}";

    $oDaoResultaoItem       = new cl_lab_resultadoitem();
    $sSqlResultadoAtributos = $oDaoResultaoItem->sql_query_resultado_valores(null, $sCampos, null, $sWhere);
    $rsResultadoAtributo    = $oDaoResultaoItem->sql_record($sSqlResultadoAtributos);
    if ($rsResultadoAtributo && $oDaoResultaoItem->numrows > 0) {

      for ($iItem = 0; $iItem < $oDaoResultaoItem->numrows; $iItem++) {

        $oDadosResultado    = db_utils::fieldsMemory($rsResultadoAtributo, $iItem);
        $oResultadoAtributo = new ResultadoExameAtributo($oDadosResultado->la39_i_codigo);
        $oResultadoAtributo->setAtributo(AtributoExameRepository::getByCodigo($oDadosResultado->la39_i_atributo));
        $oResultadoAtributo->setValorAbsoluto($oDadosResultado->valor);
        $oResultadoAtributo->setValorPercentual($oDadosResultado->valorpercentual);
        $oResultadoAtributo->setTitulacao($oDadosResultado->la39_titulacao);
        if (!empty($oDadosResultado->faixautilizada)) {
           $oResultadoAtributo->setFaixaUtilizada(new AtributoValorReferenciaNumerico($oDadosResultado->faixautilizada));
        }
        $aResultados[$oResultadoAtributo->getAtributo()->getCodigo()] = $oResultadoAtributo;
      }
    }

    return $aResultados;
  }


  /**
   * Retorna o resultado do Atributo Informado
   * @param AtributoExame $oAtributo
   * @return ResultadoExameAtributo|null
   */
  public function getValorDoAtributo(AtributoExame $oAtributo) {

    $aAtributos = $this->getResultadoDosAtributos();
    if (isset($aAtributos[$oAtributo->getCodigo()])) {
      return $aAtributos[$oAtributo->getCodigo()];
    }
    return null;
  }

  /**
   * Adiciona um valor do atributo ao exame
   * @param ResultadoExameAtributo $oResultadoAtributo
   * @return bool
   */
  public function adicionarResultadoParaAtributo(ResultadoExameAtributo $oResultadoAtributo) {

    $oAtributoJaLancado = $this->getValorDoAtributo($oResultadoAtributo->getAtributo());
    if (!empty($oAtributoJaLancado)) {
      return false;
    }
    $this->aResultadoAtributos[$oResultadoAtributo->getAtributo()->getCodigo()] = $oResultadoAtributo;
    return true;
  }

  /**
   * Define o Diagnostico do exame
   * @param string $sConsideracao
   */
  public function setConsideracao($sConsideracao) {
    $this->sConsideracao = $sConsideracao;
  }

  /**
   * @return string
   */
  public function getConsideracao() {
    return $this->sConsideracao;
  }


  /**
   * Salva os dados do resultado para o exame
   *
   */
  public function salvar() {

    $oDaoResultadoExame = new cl_lab_resultado();
    $oDaoResultadoExame->la52_diagnostico = $this->getConsideracao();
    if (empty($this->iResultado)) {

      $oDaoResultadoExame->la52_c_hora      = db_hora();
      $oDaoResultadoExame->la52_d_data      = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoResultadoExame->la52_i_requiitem = $this->oRequisicao->getCodigo();
      $oDaoResultadoExame->la52_i_usuario   = db_getsession("DB_id_usuario");
      $oDaoResultadoExame->la52_t_motivo    = '';
      $oDaoResultadoExame->incluir(null);
      $this->iResultado = $oDaoResultadoExame->la52_i_codigo;
      $this->oRequisicao->setSituacao(RequisicaoExame::LANCADO);
      $this->oRequisicao->salvar();
    } else {

      $oDaoResultadoExame->la52_i_codigo = $this->iResultado;
      $oDaoResultadoExame->alterar($this->iResultado);
    }

    if ($oDaoResultadoExame->erro_status == 0) {
      throw new BusinessException("Erro ao salvar do exame");
    }

    foreach ($this->getResultadoDosAtributos() as $oResultadosAtributos) {
      $oResultadosAtributos->salvar($this->iResultado);
    }
  }


  /**
   * Retorna os resultados anteriores
   * @return ResultadoExameAtributo[]
   */
  public function getResultadoAnterior() {

    if ( !empty($this->aResultadoDoExameAnterior) ) {
      return $this->aResultadoDoExameAnterior;
    }

    $oDaoResultadoExame = new cl_lab_resultado();

    $sCampos  = "la52_i_codigo, la21_i_codigo ";
    $sCampos .= ",(la52_d_data||' '||la52_c_hora)::timestamp as time";
    $sWhere   = "     la08_i_codigo = " . $this->oRequisicao->getExame()->getCodigo();
    $sWhere  .= " and la22_i_cgs = " . $this->oRequisicao->getSolicitante()->getCodigo();
    $sWhere  .= " and la21_i_requisicao < " . $this->oRequisicao->getCodigoRequisicao();
    $sWhere  .= " and la52_d_data <= '{$this->oData->getDate()}'";
    $sWhere  .= " and trim(la21_c_situacao) in ('3 - Entregue', '7 - Conferido') ";
    $sOrdem   = " time desc limit 1";
    $sSql     = $oDaoResultadoExame->sql_query_exames(null, $sCampos, $sOrdem, $sWhere );
    $rs       = db_query($sSql);

    if ( !$rs ) {
      throw new Exception( _M(self::FONTE_MSG . "erro_buscar_resultado_anterior" ) );
    }

    if ( pg_num_rows($rs) == 0 ) {
      return array();
    }

    $oDados = db_utils::fieldsMemory($rs, 0);

    $this->oRequisicaoAnterior       = new \RequisicaoExame($oDados->la21_i_codigo);
    $this->aResultadoDoExameAnterior = $this->buscarResultadosAtributo($oDados->la52_i_codigo);
    return $this->aResultadoDoExameAnterior;
  }

  /**
   * Retorna o resultado do Atributo Informado
   * @param  AtributoExame $oAtributo
   * @return ResultadoExameAtributo|null
   */
  public function getValorDoAtributoResultadoAnterior(AtributoExame $oAtributo) {

    $aAtributos = $this->getResultadoAnterior();
    if (isset($aAtributos[$oAtributo->getCodigo()])) {
      return $aAtributos[$oAtributo->getCodigo()];
    }
    return null;
  }


  /**
   * Retorna a data da requisição anterior
   * @return DBDate|null
   */
  public function getDataResultadoAnterior() {

    if ( is_null($this->oRequisicaoAnterior) ) {
      return null;
    }

    return $this->oRequisicaoAnterior->getData();
  }
}