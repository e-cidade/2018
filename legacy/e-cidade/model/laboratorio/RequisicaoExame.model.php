<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

define("MENSAGEM_REQUISICAO_EXAME", "saude.laboratorio.RequisicaoExame.");
/**
 * Exame de uma Requisicao
 * Class RequisicaoExame
 */
class RequisicaoExame {

  private $iCodigo = null;

  /**
   * Paciente da Solicitacao;
   * @var Cgs
   */
  private $oSolicitante = null;

  /**
   * Cpdigo do Exame que deverá ser executado
   * @var integer
   */
  protected $iCodigoExame;

  /**
   * Exame que deverá ser realizado
   * @var Exame
   */
  protected $oExame;

  /**
   * Situacao do Exame
   * @var string
   */
  protected $sSituacao = '';

  /**
   * Exame nao digitado
   * @var string
   */
  const NAO_DIGITADO = '1 - Nao Digitado';

  /**
   * Valores do exame foi lançando
   * @var string
   */
  const LANCADO = '2 - Lancado';

  /**
   * Exame FOi entrege ao solicitante
   * @var string
   */
  const ENTREGUE = '3 - Entregue';

  /**
   * Amostrados do exame foram coletadas
   */
  const COLETADO = '6 - Coletado';

  /**
   * Exame foi conferido e está pronto para a entrega
   */
  const CONFERIDO = '7 - Conferido';

  /**
   * Exame autorizado
   */
  const AUTORIZADO = '8 - Autorizado';

  const FALTA_MATERIAL = 'f - falta material';

  private static $aSituacoes = array(self::NAO_DIGITADO   => "Não Digitado",
                                     self::LANCADO        => "Lançado",
                                     self::ENTREGUE       => "Entregue",
                                     self::COLETADO       => "Coletado",
                                     self::CONFERIDO      => "Conferido",
                                     self::AUTORIZADO     => "Autorizado",
                                     self::FALTA_MATERIAL => "Falta de Material"
                                    );

  /**
   * Observação lançada para o exâme
   * @var string
   */
  private $sObservacao;

  /**
   * Medicamentos utilizados para realizar o resultado
   * @var MedicamentoLaboratorio[]
   */
  private $aMedicamentos = array();

  private $oCID = null;

  /**
   * Data do agendamento
   * @var DBDate
   */
  private $oData;

  /**
   * Código da requisição
   * @var integer
   */
  private $iCodigoRequisicao;


  /**
   * Instancia o Exame
   * @param $iCodigo
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoRequisicao      = new cl_lab_requiitem();
      $sSqlExameRequisicao = $oDaoRequisicao->sql_query($iCodigo);
      $rsExameRequisicao   = $oDaoRequisicao->sql_record($sSqlExameRequisicao);
      if ($rsExameRequisicao && $oDaoRequisicao->numrows > 0) {

        $oDadosRequisicao        = db_utils::fieldsMemory($rsExameRequisicao, 0);
        $this->oSolicitante      = CgsRepository::getByCodigo($oDadosRequisicao->la22_i_cgs);
        $this->iCodigo           = $oDadosRequisicao->la21_i_codigo;
        $this->iCodigoExame      = $oDadosRequisicao->la08_i_codigo;
        $this->sSituacao         = trim($oDadosRequisicao->la21_c_situacao);
        $this->sObservacao       = trim($oDadosRequisicao->la21_observacao);
        $this->oData             = DBDate::create($oDadosRequisicao->la21_d_data);
        $this->iCodigoRequisicao = $oDadosRequisicao->la21_i_requisicao   ;
      }
    }
  }

  /**
   * Retorna o codigo do item da requisicao
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o solicitante da requisicao
   * @param Cgs $oSolicitante Solictante da requisicao
   */
  public function setSolicitante(Cgs $oSolicitante) {
    $this->oSolicitante = $oSolicitante;
  }

  /**
   * Retorna o solicitante da Requisicao
   * @return Cgs
   */
  public function getSolicitante() {
    return $this->oSolicitante;
  }

  /**
   * Exame que deverá ser Realizado
   * @return Exame
   */
  public function getExame() {

    if (empty($this->oExame) && !empty($this->iCodigoExame)) {
      $this->oExame = ExameRepository::getByCodigo($this->iCodigoExame);
    }
    return $this->oExame;
  }

  /**
   * Retorna o Resultado do exame
   * @return ResultadoExame
   */
  public function getResultado() {
    return new ResultadoExame($this);
  }

  /**
   * Define a situacao do exame
   *
   * @param string $sSituacao Sitaucao do Exame
   */
  public function setSituacao($sSituacao) {
    $this->sSituacao = $sSituacao;
  }

  /**
   * Retorna a situacao do exame
   * @return string
   */
  public function getSituacao() {
    return $this->sSituacao;
  }

  /**
   * Persiste os dados do exame
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoItemExame = new cl_lab_requiitem();
    if (!empty($this->iCodigo)) {

      $oDaoItemExame->la21_i_codigo   = $this->iCodigo;
      $oDaoItemExame->la21_c_situacao = $this->getSituacao();
      $oDaoItemExame->la21_observacao = $this->sObservacao;
      $oDaoItemExame->alterar($this->iCodigo);
      if ($oDaoItemExame->erro_status == 0) {
        throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_salvar" ) );
      }
    }
  }

  /**
   * Retorna o setor no qual o exame está vinculado
   * @return Setor
   */
  public function getLaboratorioSetor() {

    $oDaoRequiItem = new cl_lab_requiitem();
    $sWhere        = "la21_i_codigo = {$this->iCodigo}";
    $sSqlRequiItem = $oDaoRequiItem->sql_query('', 'la24_i_setor', '', $sWhere);
    $rsRequiItem   = db_query( $sSqlRequiItem );

    if ( !$rsRequiItem ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsRequiItem );
      throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_buscar_setor", $oMensagem ) );
    }

    if ( pg_num_rows( $rsRequiItem ) == 0 ) {
      throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "setor_nao_encontrado") );
    }

    $iCodigoExame = db_utils::fieldsMemory( $rsRequiItem, 0 )->la24_i_setor;

    return new Setor( $iCodigoExame );
  }

  /**
   * Retorna a observação lançada para o exâme
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define uma observação ao exame
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Adiciona o medicamento utilizado no resultado
   * @param  MedicamentoLaboratorio $oMedicamento
   */
  public function adicionarMedicamento(MedicamentoLaboratorio $oMedicamento) {

    $this->aMedicamentos[] = $oMedicamento;
  }

  /**
   * Salva os medicamentos utilizados para realizar resultado
   * @throws DBException
   * @return boolean
   */
  public function salvarMedicamento() {

    $oDaoMedicamentoExame = new cl_medicamentoslaboratoriorequiitem();
    $oDaoMedicamentoExame->excluir(null, " la44_requiitem = {$this->iCodigo} ");

    foreach ($this->aMedicamentos as $oMedicamento) {

      $oDaoMedicamentoExame->la44_sequencial              = null;
      $oDaoMedicamentoExame->la44_medicamentoslaboratorio = $oMedicamento->getCodigo();
      $oDaoMedicamentoExame->la44_requiitem               = $this->iCodigo;
      $oDaoMedicamentoExame->incluir(null);

      if ( $oDaoMedicamentoExame->erro_status == 0 ) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_salvar_medicamento", $oMsgErro ) );
      }
    }
    return true;
  }

  /**
   * Remove o medicamento do exame da requisição
   * @param  MedicamentoLaboratorio $oMedicamento
   * @return boolean
   */
  public function removerMedicamento(MedicamentoLaboratorio $oMedicamento) {

    $sWhere  = "     la44_requiitem = {$this->iCodigo} ";
    $sWhere .= " and la44_medicamentoslaboratorio = {$oMedicamento->getCodigo()} ";

    $oDaoMedicamentoExame = new cl_medicamentoslaboratoriorequiitem();
    $oDaoMedicamentoExame->excluir(null, $sWhere);

    $oMsgErro        = new stdClass();
    $oMsgErro->sErro = pg_last_error();
    if ( $oDaoMedicamentoExame->erro_status == 0 ) {
      throw new DBException( _M(MENSAGEM_REQUISICAO_EXAME . "erro_excluir_medicamento_exame", $oMsgErro) );
    }

    $iChave = array_search($oMedicamento, $this->aMedicamentos);
    unset($this->aMedicamentos[$iChave]);
    return true;
  }

  /**
   * Retorna os medicamentos vinculados ao exame
   * @throws DBException
   * @return MedicamentoLaboratorio[]
   */
  public function getMedicamentos() {

    if ( count($this->aMedicamentos) == 0) {

      $sWhere               = " la44_requiitem = {$this->iCodigo} ";
      $oDaoMedicamentoExame = new cl_medicamentoslaboratoriorequiitem();
      $sSqlMedicamentos     = $oDaoMedicamentoExame->sql_query_file(null, "la44_medicamentoslaboratorio", null, $sWhere);
      $rsMedicamentos       = db_query($sSqlMedicamentos);

      if ( !$rsMedicamentos ) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_buscar_medicamentos", $oMsgErro ) );
      }

      $iLinhas = pg_num_rows($rsMedicamentos);
      for($iContador = 0; $iContador < $iLinhas; $iContador++) {

        $iMedicamento = db_utils::fieldsMemory($rsMedicamentos, $iContador)->la44_medicamentoslaboratorio;
        $this->adicionarMedicamento( new MedicamentoLaboratorio($iMedicamento) );
      }
    }

    return $this->aMedicamentos;
  }

  /**
   * Retorna o CID vínculado a conferência do Exame
   * @throws DBException
   * @return CID
   */
  public function getCID() {

    if ( empty($this->iCodigo) ) {
      return null;
    }

    if ( !empty($this->oCID) ) {
      return $this->oCID;
    }

    $oDaoConferencia   = new cl_lab_conferencia();
    $sWhereConferencia = " la47_i_requiitem = {$this->iCodigo} and la47_i_cid is not null";
    $sOrderConferencia = " la47_i_codigo desc";
    $sSqlConferencia   = $oDaoConferencia->sql_query_file( null, 'la47_i_cid', $sOrderConferencia, $sWhereConferencia );
    $rsConferencia     = db_query( $sSqlConferencia );

    if ( !$rsConferencia ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new DBException(_M( MENSAGEM_REQUISICAO_EXAME . "erro_bucar_cid", $oMsgErro ));
    }

    if ( pg_num_rows($rsConferencia) > 0 ) {

      $iCID = db_utils::fieldsMemory( $rsConferencia, 0)->la47_i_cid;
      $this->oCID = new CID( $iCID );
    }

    return $this->oCID;
  }

  /**
   * Retorna a descrição sem erro de português e sem o código
   * @return string
   */
  public function getDescricaoSituacao() {

    return self::$aSituacoes[$this->getSituacao()];
  }

  /**
   * Define data de agendamento
   * @param DBDate $oData
   */
  public function setData($oData) {
    $this->oData = $oData;
  }
  /**
   * Retorna data de agendamento
   * @return DBDate
   */
  public function getData() {
    return $this->oData;
  }

  public function getCodigoRequisicao() {
    return $this->iCodigoRequisicao;
  }
}