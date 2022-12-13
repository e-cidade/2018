<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * COntrole de administra��o de Medicamentos do modulo Ambulatorial
 * Class AdministracaoMedicamento
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 */
class AdministracaoMedicamento {

  /**
   * codigo da Administra��o
   * @var integer
   */
  private $iCodigo;

  /**
   * Hora da administra��o
   * @var string
   */
  private $sHora;

  /**
   * Usuario que realizou a administra��o
   * @var UsuarioSistema
   */
  private $oUsuario;

  /**
   * Data da administra��o
   * @var DBDate
   */

  private $oData;
  /**
   * Medicamento Administrado
   * @var Medicamento
   */
  private $oMedicamento;

  /**
   * Unidade que foi administrada
   * @var UnidadeMaterial
   */
  private $oUnidade;

  /**
   * Quantidade que foi administrada
   * @var float
   */
  private $nQuantidadeAdministrada;

  /**
   * Codigo do usu�rio
   * @var integer
   */
  private $iCodigoUsuario;

  /**
   * Codigo do medicamento
   * @var integer
   */
  private $iCodigoMedicamento;

  /**
   * Quantidade que existente na embalagem.
   * Apenas utilizado para fins de convers�o de medidas
   * @var float
   */
  private $nQuantidadeMedicamentoEmbalagem;

  const ARQUIVO_MENSAGEM = 'saude.ambulatorial.AdministracaoMedicamento.';


  /**
   * Instancia os dados do medicamento
   * @param null $iCodigo
   * @throws BusinessException
   */
  public function __construct ( $iCodigo = null ) {

    if ( empty ($iCodigo) ) {
      return true;
    }

    $oDaoAdministracao   = new cl_administracaomedicamento();
    $oDadosAdministracao = db_utils::getRowFromDao($oDaoAdministracao, array($iCodigo) );

    if (empty ($oDadosAdministracao) ) {
       throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."administracao_nao_encontrada"));
    }

    $this->setCodigo($oDadosAdministracao->sd105_codigo);
    $this->setData(new DBDate($oDadosAdministracao->sd105_data));
    $this->setHora($oDadosAdministracao->sd105_hora);
    $this->setQuantidadeAdministrada($oDadosAdministracao->sd105_quantidade);
    $this->setQuantidadeMedicamentoEmbalagem($oDadosAdministracao->sd105_quantidadetotal);
    $this->setUnidade(UnidadeMaterialRepository::getByCodigo($oDadosAdministracao->sd105_unidadesaida));

    /**
     * controle para lazy loading
     */
    $this->iCodigoUsuario     = $oDadosAdministracao->sd105_usuario;
    $this->iCodigoMedicamento = $oDadosAdministracao->sd105_medicamento;
  }

  /**
   * @return mixed
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * C�digo da Administra��o
   * @param mixed $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a hora da administra��o
   * @return mixed
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * Hora da administra��o
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Retorna o Usu�rio que realizaou a administra��o
   * @return UsuarioSistema
   */
  public function getUsuario() {

    if (empty($this->oUsuario) && !empty($this->iCodigoUsuario)) {
      $this->oUsuario = new UsuarioSistema($this->iCodigoUsuario);
    }
    return $this->oUsuario;
  }

  /**
   * Usu�rio que realizou a administra��o
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {

    $this->iUsuario = $oUsuario->getCodigo();
    $this->oUsuario = $oUsuario;
  }

  /**
   * Data da Administra��o do medicamento
   * @return DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * Data da administra��o
   * @param DBDate $oData
   */
  public function setData(DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * Retorno o medicamento que foi administrado
   * @return Medicamento
   */
  public function getMedicamento() {

    if (empty($this->oMedicamento) && !empty($this->iCodigoMedicamento)) {
      $this->oMedicamento = new Medicamento($this->iCodigoMedicamento);
    }
    return $this->oMedicamento;
  }

  /**
   * @param Medicamento $oMedicamento
   */
  public function setMedicamento(Medicamento $oMedicamento) {

    $this->oMedicamento = $oMedicamento;
    $this->setQuantidadeMedicamentoEmbalagem($oMedicamento->getQuantidade());
  }

  /**
   * @return UnidadeMaterial
   */
  public function getUnidade() {
    return $this->oUnidade;
  }

  /**
   * @param UnidadeMaterial $oUnidade
   */
  public function setUnidade(UnidadeMaterial $oUnidade) {
    $this->oUnidade = $oUnidade;
  }

  /**
   * @return mixed
   */
  public function getQuantidadeAdministrada() {
    return $this->nQuantidadeAdministrada;
  }

  /**
   * @param mixed $nQuantidadeAdministrada
   */
  public function setQuantidadeAdministrada($nQuantidadeAdministrada) {
    $this->nQuantidadeAdministrada = $nQuantidadeAdministrada;
  }

  /**
   * @return mixed
   */
  public function getQuantidadeMedicamentoEmbalagem() {
    return $this->nQuantidadeMedicamentoEmbalagem;
  }

  /**
   * @param mixed $nQuantidadeMedicamentoEmbalagem
   */
  public function setQuantidadeMedicamentoEmbalagem($nQuantidadeMedicamentoEmbalagem) {
    $this->nQuantidadeMedicamentoEmbalagem = $nQuantidadeMedicamentoEmbalagem;
  }

  /**
   * Persiste os dados da Administra��o de medicamentos
   * @throws BusinessException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("sem transa��o com o banco de dados");
    }

    if (empty($this->oMedicamento)) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."medicamento_nao_informado"));
    }

    if (empty($this->oData)) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."data_da_administracao_nao_informada"));
    }

    if (empty($this->oUsuario)) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."usuario_nao_informado"));
    }

    if (empty($this->oUnidade)) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."unidade_nao_informada"));
    }

    $oDaoAdministracaoMedicamento                        = new cl_administracaomedicamento();
    $oDaoAdministracaoMedicamento->sd105_codigo          = $this->getCodigo();
    $oDaoAdministracaoMedicamento->sd105_data            = $this->getData()->getDate();
    $oDaoAdministracaoMedicamento->sd105_hora            = $this->getHora();
    $oDaoAdministracaoMedicamento->sd105_quantidade      = $this->getQuantidadeAdministrada();
    $oDaoAdministracaoMedicamento->sd105_quantidadetotal = $this->getQuantidadeMedicamentoEmbalagem();
    $oDaoAdministracaoMedicamento->sd105_unidadesaida    = $this->getUnidade()->getCodigo();
    $oDaoAdministracaoMedicamento->sd105_usuario         = $this->getUsuario()->getCodigo();
    $oDaoAdministracaoMedicamento->sd105_medicamento     = $this->getMedicamento()->getCodigo();
    if (empty($this->iCodigo)) {

     $oDaoAdministracaoMedicamento->incluir(null);
      $this->iCodigo = $oDaoAdministracaoMedicamento->sd105_codigo;
    } else {
      $oDaoAdministracaoMedicamento->alterar($this->iCodigo);
    }

    if ($oDaoAdministracaoMedicamento->erro_status == 0) {

      $oStdErro       = new stdClass();
      $oStdErro->erro = $oDaoAdministracaoMedicamento->erro_msg;
      throw new BusinessException(self::ARQUIVO_MENSAGEM."erro_ao_salvar_administracao", $oStdErro);
    }
  }

  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transa��o com o banco de dados");
    }
    if (!empty($this->iCodigo)) {

      $oDaoAdministracaoMedicamento = new cl_administracaomedicamento();
      $oDaoAdministracaoMedicamento->excluir($this->getCodigo());
    }
  }
}