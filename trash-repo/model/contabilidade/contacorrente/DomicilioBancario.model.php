<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("interfaces/IContaCorrente.interface.php");
/**
 * Classe para a Conta Corrente 2 - Domicilio Bancбrio
 * @author Acбcio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.7 $
 * @package contabilidade
 * @subpackage contacorrente
 */
class DomicilioBancario extends ContaCorrenteBase implements IContaCorrente{

  /**
   * Objeto ContaBancaria
   * @var ContaBancaria
   */
  private $oContaBancaria;

  /**
   * Registro na tabela contacorrente
   * @var integer
   */
  const CONTA_CORRENTE = 2;

  /**
   * Seta os atributos necessбrios para o funcionamento da classe
   * @param integer $iCodigoLancamento               - Cуdigo do Lanзamento (conlancamval)
   * @param integer $iCodigoReduzido                 - Cуdigo reduzido da conta no plano de contas PCASP
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar - Objeto de Lanзamento Auxiliar
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    parent::__construct($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);
    $this->oContaCorrente      = ContaCorrenteRepository::getContaCorrenteByCodigo(self::CONTA_CORRENTE);
    return true;
  }

  /**
   * Salva os dados na tabela contacorrentedetalhe
   * e se jб estiver lб simplesmente vincula com a tabela contacorrentedetalheconlancamval
   */
  public function salvar($dtLancamento = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Nгo foi encontrada transaзгo com o banco de dados. Procedimento abortado.");
    }

    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");

    $iAnoUsuConplano = $this->getContaPlano()->getAno();
    $iReduzido       = $this->getContaPlano()->getReduzido();
    $iContaBancaria  = $this->getContaBancaria()->getSequencialContaBancaria();
    $iInstituicao    = $this->getInstituicao()->getSequencial();

    /**
     * Verificamos se jб existem os dados na tabela contacorrentedetalhe
     */
    $sWhere  = "     c19_contacorrente       = " . self::CONTA_CORRENTE;
    $sWhere .= " and c19_instit              = {$iInstituicao}";
    $sWhere .= " and c19_reduz               = {$iReduzido}";
    $sWhere .= " and c19_conplanoreduzanousu = {$iAnoUsuConplano}";
    $sWhere .= " and c19_contabancaria       = {$iContaBancaria}";

    $sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_file(null, "c19_sequencial", null, $sWhere);
    $rsContaCorrenteDetalhe   = $oDaoContaCorrenteDetalhe->sql_record($sSqlContaCorrenteDetalhe);

    /**
     * Caso jб exista sу precisamos fazer o vнnculo com a tabela contacorrentedetalheconlancamval
     */
    if ($oDaoContaCorrenteDetalhe->numrows == 1) {

      $iContaCorrenteDetalheSequencial = db_utils::fieldsMemory($rsContaCorrenteDetalhe, 0)->c19_sequencial;
      $sTipoLancamento                 = $this->atualizarSaldo($iContaCorrenteDetalheSequencial, $dtLancamento);
      $this->vincularLancamentos($iContaCorrenteDetalheSequencial, $sTipoLancamento);
    } else {
      /**
       * Se nгo, incluнmos na contacorrentedetalhe e em seguida fazemos o vнnculo com a
       * contacorrentedetalheconlancamval
       */

      $oDaoContaCorrenteDetalhe->c19_contacorrente       = self::CONTA_CORRENTE;
      $oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      $oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsuConplano;
      $oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      $oDaoContaCorrenteDetalhe->c19_contabancaria       = $iContaBancaria;
      $oDaoContaCorrenteDetalhe->incluir(null);

      if ($oDaoContaCorrenteDetalhe->erro_status == 0) {
        throw new DBException("Nгo foi possнvel inserir os dados da conta corrente {$this->oContaCorrente->getDescricao()}\n " . $oDaoContaCorrenteDetalhe->erro_msg);
      }

      $sTipoLancamento = $this->atualizarSaldo($oDaoContaCorrenteDetalhe->c19_sequencial, $dtLancamento);
      $this->vincularLancamentos($oDaoContaCorrenteDetalhe->c19_sequencial, $sTipoLancamento);
    }
  }

  /**
   * Retorna o objeto Conta Corrente
   * @return ContaCorrente
   */
  public function getContaCorrente() {
    return $this->oContaCorrente;
  }

  /**
   * Seta o Objeto Conta Bancaria
   * @param ContaBancaria $oContaBancaria
   */
  public function setContaBancaria(ContaBancaria $oContaBancaria) {
    $this->oContaBancaria = $oContaBancaria;
  }

  /**
   * Retorna o Objeto Conta Bancaria
   * @return ContaBancaria
   */
  public function getContaBancaria() {

    if (!$this->oContaBancaria instanceof ContaBancaria) {

      $iAnoSessao        = db_getsession("DB_anousu");
      $oDaoConplanoReduz = db_utils::getDao("conplanoreduz");
      $iReduzido         = $this->getContaPlano()->getReduzido();

      $sWhere                  = "     conplanoreduz.c61_reduz = {$iReduzido}";
      $sWhere                 .= " and conplanoreduz.c61_anousu = {$iAnoSessao}";
      $sSqlBuscaContaBancaria  = $oDaoConplanoReduz->sql_query_contabancaria(null, null, "contabancaria.db83_sequencial", null, $sWhere);
      $rsBuscaContaBancaria    = $oDaoConplanoReduz->sql_record($sSqlBuscaContaBancaria);

      if ($oDaoConplanoReduz->numrows == 0) {
        throw new BusinessException("Conta bancбria nгo encontrada para o reduzido {$iReduzido} e ano {$iAnoSessao}.");
      }

      $iSequencialContaBancaria = db_utils::fieldsMemory($rsBuscaContaBancaria, 0)->db83_sequencial;
      $this->oContaBancaria     = new ContaBancaria($iSequencialContaBancaria);
    }
    return $this->oContaBancaria;
  }

}
?>