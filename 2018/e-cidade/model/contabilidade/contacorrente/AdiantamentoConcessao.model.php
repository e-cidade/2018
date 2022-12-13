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

require_once(modification("interfaces/IContaCorrente.interface.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));

/**
 * Model responsavel pelo adiantamento de concessao
 * @author     Rafael Lopes <rafael.lopes@dbseller.com.br>
 * @package    contabilidade
 * @version    1.0 $
 */
class AdiantamentoConcessao extends ContaCorrenteBase implements IContaCorrente {

  /**
   * Constante para o objeto conta corrente Adiantamento de Concessao
   * @var interger
   */
  const CONTA_CORRENTE = 19;

  /**
   * objeto com dados do empenho
   * @var Object
   */
  private $oEmpenho;

  /**
   * propriedade com dados da unidade
   * @var object
   */
  private $oUnidade;

  /**
   * propriedade  com dados do orgao
   * @var objeto
   */
  private $oOrgao;

  /**
   * propriedade com dados do cgm
   * @var object
   */
  private $oCredor;

 /**
  * O construtor apenas seta as propriedades que iremos utilizar durante o processamento da conta bancaria
  * @param integer $iCodigoLancamento
  * @param integer $iCodigoReduzido
  * @param ILancamentoAuxiliar $oLancamentoAuxiliar
  */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    parent::__construct($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);

    $iAnoSessao         = db_getsession('DB_anousu');
    $this->setEmpenho(new EmpenhoFinanceiro($oLancamentoAuxiliar->getNumeroEmpenho()));
    $iCodigoOrgao = $this->getEmpenho()->getDotacao()->getOrgao();
    $this->setCredor($this->getEmpenho()->getCgm());
    $this->setOrgao(new Orgao($iCodigoOrgao, $iAnoSessao));
    $this->setUnidade(new Unidade($iAnoSessao, $iCodigoOrgao, $this->getEmpenho()->getDotacao()->getUnidade()));
    $this->oContaCorrente = ContaCorrenteRepository::getContaCorrenteByCodigo(self::CONTA_CORRENTE);
    return true;
  }


  /**
   * metodo que incluira registros na contacorrentedetalhe
   * relativos ao  CC19
   */
  public function salvar($dtLancamento = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException("ERRO [1] - Nгo foi encontrado transaзгo com o banco de dados. Procedimento abortado.");
    }

    /**
     * Verificamos se jб existem os dados na tabela contacorrentedetalhe
     */
    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");
    $sWhereContaCorrente  = "     c19_contacorrente       = " . self::CONTA_CORRENTE;
    $sWhereContaCorrente .= " and c19_conplanoreduzanousu = {$this->getContaPlano()->getAno()}";
    $sWhereContaCorrente .= " and c19_reduz               = {$this->iCodigoReduzido}";
    $sWhereContaCorrente .= " and c19_instit              = {$this->getInstituicao()->getSequencial()}";
    $sWhereContaCorrente .= " and c19_numcgm              = {$this->getCredor()->getCodigo()}";
    $sWhereContaCorrente .= " and c19_numemp              = {$this->getEmpenho()->getNumero()}";
    $sWhereContaCorrente .= " and c19_orcunidadeanousu    = {$this->getUnidade()->getAno()}";
    $sWhereContaCorrente .= " and c19_orcunidadeorgao     = {$this->getUnidade()->getOrgao()->getCodigoOrgao()}";
    $sWhereContaCorrente .= " and c19_orcunidadeunidade   = {$this->getUnidade()->getCodigoUnidade()}";
    $sWhereContaCorrente .= " and c19_orcorgaoanousu      = {$this->getOrgao()->getAno()}";
    $sWhereContaCorrente .= " and c19_orcorgaoorgao       = {$this->getOrgao()->getCodigoOrgao()}";
    $sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_file(null, "c19_sequencial", null, $sWhereContaCorrente);
    $rsContaCorrenteDetalhe   = $oDaoContaCorrenteDetalhe->sql_record($sSqlContaCorrenteDetalhe);

    if ($oDaoContaCorrenteDetalhe->numrows > 0) {

      $iContaCorrenteDetalheSequencial = db_utils::fieldsMemory($rsContaCorrenteDetalhe, 0)->c19_sequencial;
      $sTipoLancamento                 = $this->atualizarSaldo($iContaCorrenteDetalheSequencial, $dtLancamento);
      $this->vincularLancamentos($iContaCorrenteDetalheSequencial, $sTipoLancamento);
    } else {

      /**
       * Se nгo, incluнmos na contacorrentedetalhe e em seguida fazemos o vнnculo com a
       * contacorrentedetalheconlancamval
       */
      $oDaoContaCorrenteDetalhe->c19_sequencial          = null;
      $oDaoContaCorrenteDetalhe->c19_contacorrente       = self::CONTA_CORRENTE;
      $oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $this->getContaPlano()->getAno();
      $oDaoContaCorrenteDetalhe->c19_instit              = $this->getInstituicao()->getSequencial();
      $oDaoContaCorrenteDetalhe->c19_numcgm              = $this->getCredor()->getCodigo();
      $oDaoContaCorrenteDetalhe->c19_numemp              = $this->getEmpenho()->getNumero();
      $oDaoContaCorrenteDetalhe->c19_orcunidadeanousu    = $this->getUnidade()->getAno();
      $oDaoContaCorrenteDetalhe->c19_orcunidadeorgao     = $this->getUnidade()->getOrgao()->getCodigoOrgao();
      $oDaoContaCorrenteDetalhe->c19_orcunidadeunidade   = $this->getUnidade()->getCodigoUnidade();
      $oDaoContaCorrenteDetalhe->c19_orcorgaoanousu      = $this->getOrgao()->getAno();
      $oDaoContaCorrenteDetalhe->c19_orcorgaoorgao       = $this->getOrgao()->getCodigoOrgao();
      $oDaoContaCorrenteDetalhe->c19_reduz               = $this->iCodigoReduzido;
      $oDaoContaCorrenteDetalhe->incluir(null);

      if ($oDaoContaCorrenteDetalhe->erro_status == "0") {
        throw new DBException("ERRO [2] - Nгo foi possнvel salvar os dados detalhados da conta corrente. {$oDaoContaCorrenteDetalhe->erro_msg}" );
      }
      $sTipoLancamento = $this->atualizarSaldo($oDaoContaCorrenteDetalhe->c19_sequencial, $dtLancamento);
      $this->vincularLancamentos($oDaoContaCorrenteDetalhe->c19_sequencial, $sTipoLancamento);
    }
    return true;
  }

  /**
   * Retorna o objeto do Empenho
   * @return EmpenhoFinanceiro
   */
  public function getEmpenho()  {
    return $this->oEmpenho;
  }

  /**
   * Seta o Empenho Financeiro
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenho(EmpenhoFinanceiro $oEmpenho) {
    $this->oEmpenho = $oEmpenho;
  }

  /**
   * Retorna o objeto do Unidade
   * @return Unidade
   */
  public function getUnidade()  {
    return $this->oUnidade;
  }

  /**
   * Seta a unidade da conta corrente
   * @param Unidade $oUnidade
   */
  public function setUnidade(Unidade $oUnidade) {
    $this->oUnidade = $oUnidade;
  }

  /**
   * Retorna o objeto do Orgao
   * @return Orgao
   */
  public function getOrgao()  {
    return $this->oOrgao;
  }

  /**
   * Recebe o objeto Orgao
   * @param Orgao
   */
  public function setOrgao(Orgao $oOrgao) {
    $this->oOrgao = $oOrgao;
  }

  /**
   * Retorna o objeto do Cgm
   * @return CgmBase
   */
  public function getCredor()  {
    return $this->oCredor;
  }

  /**
   * Recebe o objeto Cgm
   * @param CgmBase $oCgm
   */
  public function setCredor(CgmBase $oCgm) {
    $this->oCredor = $oCgm;
  }
}
?>