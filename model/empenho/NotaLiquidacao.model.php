<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Classe para controle de notas de liquidacao
 * @link www.dbseller.com.br
 */

/**
 *
 * Nota de liquidacao de um empenho.
 * Classe utilizado para Controle de liquidacoes de um empenho.
 * Cada liquidacao deve ser a partir de uma nota fiscal
 *
 * @package contabilidade
 * @subpackage lancamento
 * @author Iuri Gunchnigg iuri@dbseller.com.br
 * @author Rafael Lopes rafael.lopes@dbseller.com.br
 */
class NotaLiquidacao {

  /**
   * codigo da nota
   * @var integer
   */
  private $iCodigoNota;

  /**
   * Código do desdobramento
   * @var integer
   */
  private $iDesdobramento;

  /**
   * Ano de lancamento da nota
   * @var unknown
   */
  private $iAnoUsu;

  /**
   * Empenho da nota
   * @var EmpenhoFinanceiro
   */
  private $oEmpenho;


  /**
   * Número da  nota fiscal
   * @var string
   */

  private $sNumeroNota;
  /**
   * Valor da nota
   * @var float
   */
  private $nValorNota = 0;

  /**
   * Valor liquidado
   * @var float
   */
  private $nValorLiquidado = 0;

  /**
   * Valor anulado
   * @var float
   */
  private $nValorAnulado = 0;

  /**
   * Itens da nota
   * @var NotaLiquidacaoItem[]
   */
  private $aItens = array();

  /**
   * Instancia o objeto da classe nota
   * @param integer $iCodigoNota (codigo da nota)
   */
  public function __construct($iCodigoNota) {

    if (!empty($iCodigoNota)) {

      $oDaoEmpNotaEle = db_utils::getDao("empnotaele");
      $sWhere         = "e69_Codnota = {$iCodigoNota}";
      $sSqlNota       = $oDaoEmpNotaEle->sql_query(null, null, "empnota.*, empnotaele.*", null, $sWhere);
      $rsNota         = $oDaoEmpNotaEle->sql_record($sSqlNota);

      if ($oDaoEmpNotaEle->numrows > 0) {

        $oDadosNota        = db_utils::fieldsMemory($rsNota, 0);
        $this->iCodigoNota = $iCodigoNota;
        $this->setAnoUsu($oDadosNota->e69_anousu);
        $this->setDesdobramento($oDadosNota->e70_codele);
        $this->setEmpenho(new EmpenhoFinanceiro($oDadosNota->e69_numemp));
        $this->setValorAnulado($oDadosNota->e70_vlranu);
        $this->setValorLiquidado($oDadosNota->e70_vlrliq);
        $this->setNumeroNota($oDadosNota->e69_numero);
        $this->setValorNota($oDadosNota->e70_valor);
        unset($oDadosNota);
      }
    }

  }

  /**
   * Retorna o codigo da nota no sistema
   * @return integer
   */
  public function getCodigoNota() {
      return $this->iCodigoNota;
  }

  /**
   * Retorna o desdobramento da nota
   * @return
   */
  public function getDesdobramento() {
      return $this->iDesdobramento;
  }

  /**
   * Define o desdobramento da nota
   * @param $iDesdobramento
   */
  public function setDesdobramento($iDesdobramento) {
      $this->iDesdobramento = $iDesdobramento;
  }

  /**
   *retorna o ano da nota
   * @return integer
   */
  public function getAnoUsu() {
      return $this->iAnoUsu;
  }

  /**
   * define o ano da nota
   * @param integer $iAnoUsu ano da nota
   */
  protected function setAnoUsu($iAnoUsu) {
    $this->iAnoUsu = $iAnoUsu;
  }

  /**
   * Retorna um objeto empenho relacionado a nota
   * @return EmpenhoFinanceiro
   */
  public function getEmpenho() {
      return $this->oEmpenho;
  }

  /**
   * Define o objeto emprenho relacionado a nota
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenho(EmpenhoFinanceiro $oEmpenho) {
      $this->oEmpenho = $oEmpenho;
  }

  /**
   * Retorna o Valor da nota
   * @return
   */
  public function getValorNota() {
      return $this->nValorNota;
  }

  /**
   * Define o valor da nota
   * @param $nValorNota
   */
  public function setValorNota($nValorNota) {
      $this->nValorNota = $nValorNota;
  }

  /**
   * Retorna o valor liquidado da nota
   * @return
   */
  public function getValorLiquidado() {
      return $this->nValorLiquidado;
  }

  /**
   * Define o valor liquidado da nota
   * @param $nValorLiquidado
   */
  public function setValorLiquidado($nValorLiquidado) {
      $this->nValorLiquidado = $nValorLiquidado;
  }

  /**
   * Retorna o valor anulado da nota
   * @return
   */
  public function getValorAnulado() {
      return $this->nValorAnulado;
  }

  /**
   * define o valor anulado da nota
   * @param $nValorAnulado
   */
  public function setValorAnulado($nValorAnulado) {
      $this->nValorAnulado = $nValorAnulado;
  }

  /**
   * Define o numero da nota fiscal
   * @param string $sNumeroNotaFiscal numero da nota fiscal
   */
  public function  setNumeroNota($sNumeroNotaFiscal) {
    $this->sNumeroNota = $sNumeroNotaFiscal;
  }

  /**
   * Retorna o número da nota Fiscal
   * @return string
   */
  public function getNumeroNota() {
    return $this->sNumeroNota;
  }

  /**
   * Retorna os itens da nota
   * Retorna os itens que foram adquiridos na nota fiscal,
   * @return NotaLiquidacaoItem[]
   */
  public function getItens() {

    if (empty($this->aItens) && !empty($this->iCodigoNota)) {

      $oDaoEmpnotaItem = db_utils::getDao("empnotaitem");
      $sSqlDadosNota   = $oDaoEmpnotaItem->sql_query_file(null,
                                                          "e72_sequencial",
                                                          "e72_sequencial",
                                                          "e72_codnota = {$this->getCodigoNota()}"
                                                         );

      $rsItensNota    = $oDaoEmpnotaItem->sql_record($sSqlDadosNota);
      if ($oDaoEmpnotaItem->numrows > 0) {

        for ($iNota = 0; $iNota < $oDaoEmpnotaItem->numrows; $iNota++) {
          $this->aItens[] = new NotaLiquidacaoItem(db_utils::fieldsMemory($rsItensNota, $iNota)->e72_sequencial);
        }
      }
    }
    return $this->aItens;
  }
}
?>