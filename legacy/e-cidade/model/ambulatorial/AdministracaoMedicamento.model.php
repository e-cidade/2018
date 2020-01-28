<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
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


/**
 * COntrole de administração de Medicamentos do modulo Ambulatorial
 * Class AdministracaoMedicamento
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 */
class AdministracaoMedicamento {

  /**
   * codigo da Administração
   * @var integer
   */
  private $iCodigo;

  /**
   * Hora da administração
   * @var string
   */
  private $sHora;

  /**
   * Usuario que realizou a administração
   * @var UsuarioSistema
   */
  private $oUsuario;

  /**
   * Data da administração
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
   * Codigo do usuário
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
   * Apenas utilizado para fins de conversão de medidas
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
   * Código da Administração
   * @param mixed $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a hora da administração
   * @return mixed
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * Hora da administração
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Retorna o Usuário que realizaou a administração
   * @return UsuarioSistema
   */
  public function getUsuario() {

    if (empty($this->oUsuario) && !empty($this->iCodigoUsuario)) {
      $this->oUsuario = new UsuarioSistema($this->iCodigoUsuario);
    }
    return $this->oUsuario;
  }

  /**
   * Usuário que realizou a administração
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {

    $this->iUsuario = $oUsuario->getCodigo();
    $this->oUsuario = $oUsuario;
  }

  /**
   * Data da Administração do medicamento
   * @return DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * Data da administração
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
   * Persiste os dados da Administração de medicamentos
   * @throws BusinessException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("sem transação com o banco de dados");
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
      throw new DBException("Sem transação com o banco de dados");
    }
    if (!empty($this->iCodigo)) {

      $oDaoAdministracaoMedicamento = new cl_administracaomedicamento();
      $oDaoAdministracaoMedicamento->excluir($this->getCodigo());
    }
  }
}