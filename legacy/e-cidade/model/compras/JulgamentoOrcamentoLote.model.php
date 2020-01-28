<?php
require_once ("model/compras/iJulgamentoOrcamento.interface.php");
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
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
 *
 *
 * Class JulgamentoOrcamentoLote
 * @author $Author: dbjeferson.belmiro $
 * @version $Revision: 1.2 $
 */

class JulgamentoOrcamentoLote implements iJulgamentoOrcamento {


  const ARQUIVO_MENSAGEM  = "patrimonio.compras.JulgamentoOrcamentoLote.";

  /**
   * Lotes do orcamento
   * @var array
   */
  private $aLotes = array();

  /**
   * Or�amento que ser� julgado
   * @var OrcamentoCompra
   */
  private $oOrcamento;

  /**
   * Este m�todo vai julgar os vencedores do or�amento
   *
   * @param OrcamentoCompra $oOrcamentoCompra
   */
  public function julgar(OrcamentoCompra $oOrcamentoCompra) {

    $this->oOrcamento = $oOrcamentoCompra;
    $this->agruparItensPorLote();

    $aFornecedores = $this->oOrcamento->getFornecedores();

    /**
     * Verficamos quais Fornecedores Cotaram todos os itens
     * Regras:
     *   1 - Ter Cotado em todos os itens do Lote
     */
    foreach ($aFornecedores as $oFornecedor) {

      foreach ($this->aLotes as $oLote) {

        $iTotalItensNoLote = count($oLote->itens);
        $aCotacoes         = $this->getCotacoesValidasDoFornecedorNoLote($oFornecedor, $oLote->itens);

        if ($iTotalItensNoLote != count($aCotacoes)) {
          continue;
        }

        $oCotacoesFornecedor             = new stdClass();
        $oCotacoesFornecedor->cotacoes   = $aCotacoes;
        $oCotacoesFornecedor->fornecedor = $oFornecedor;
        $oCotacoesFornecedor->valorTotal = 0;

        $oLote->fornecedores[]           = $oCotacoesFornecedor;
      }
    }

    /**
     * Para decidir o Vencedor, vence quem tem o menor valor na soma de todos os itens do lote
     */
    $this->verificarGanhadores();
    $this->remover();
    $this->salvar();
  }

  /**
   * Agrupa os Itens por Lote
   */
  private function agruparItensPorLote() {

    $aItens =  $this->oOrcamento->getItens();
    foreach ($aItens as $oItem) {

      $oLote = $oItem->getLote();
      if (!isset($this->aLotes[$oLote->getCodigo()])) {

        $oDadosLote                        = new stdClass();
        $oDadosLote->itens                 = array();
        $oDadosLote->fornecedores          = array();
        $oDadosLote->nome                  = $oLote->getNome();
        $this->aLotes[$oLote->getCodigo()] = $oDadosLote;
      }
      $this->aLotes[$oLote->getCodigo()]->itens[] = $oItem;
    }
  }

  /**
   * Retorna todas as cotacoes validas do Fornecedor no lote
   * Uma cotacao valida �:
   *   - ter cotado todos os itens solicitados,
   *   - e o valor cotado ser maior que 0
   * @param CgmBase $oFornecedor
   * @param  ItemOrcamento[] $aItens
   * @return ItemOrcamento[]
   */
  private function getCotacoesValidasDoFornecedorNoLote (CgmBase $oFornecedor, array $aItens) {

    $aCotacoes = array();
    foreach ($aItens as $oItem) {

      if ($oCotacao = $oItem->getCotacaoDoFornecedor($oFornecedor)) {

        /**
         * Validamos a primeira Regra
         */
        if ($oCotacao->getQuantidade() != $oItem->getItemSolicitacao()->getQuantidade()) {
          continue;
        }

        /**
         * Validamos a regra 2
         */
        if ($oCotacao->getValorTotal() <= 0) {
          continue;
        }
        $aCotacoes[] = $oCotacao;
      }
    }
    return $aCotacoes;
  }

  /**
   * Apura os vencedores, e persite os dados do julgamento
   */
  protected function verificarGanhadores() {

    foreach ($this->aLotes as $oLote) {

      $nMenorValorLote                 = null;
      $oLote->classificao_fornecedores = array();

      foreach ($oLote->fornecedores as $oFornecedor) {

        $oFornecedor->valorTotal           = $this->calcularValorFornecedorNoLote($oFornecedor->cotacoes);
        $oLote->classificao_fornecedores[] = $oFornecedor;
      }
      uasort($oLote->classificao_fornecedores, function($oFornecedorAtual, $oProximoFornecedor) {
        return $oFornecedorAtual->valorTotal > $oProximoFornecedor->valorTotal;
      });
    }
  }

  /**
   * Persiste os dados do julgamento
   */
  protected function salvar() {

    $aCodigosFornecedores    = $this->getCodigosDosFornecedoresOrcamento();
    $oDaoOrcamentoJulgamento = new cl_pcorcamjulg();
    foreach ($this->aLotes as $oLote) {

      $iClassificacao = 1;
      foreach ($oLote->classificao_fornecedores as $oFornecedoresLote ) {

        foreach ($oFornecedoresLote->cotacoes as $oCotacao) {

          $iCodigoFornecedor = $aCodigosFornecedores[$oCotacao->getFornecedor()->getCodigo()];

          $oDaoOrcamentoJulgamento->pc24_orcamitem  = $oCotacao->getItem()->getCodigo();
          $oDaoOrcamentoJulgamento->pc24_pontuacao  = $iClassificacao;
          $oDaoOrcamentoJulgamento->pc24_orcamforne = $iCodigoFornecedor;
          $oDaoOrcamentoJulgamento->incluir($oDaoOrcamentoJulgamento->pc24_orcamitem, $oDaoOrcamentoJulgamento->pc24_orcamforne);

          if ($oDaoOrcamentoJulgamento->erro_status == "0") {
            throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_salvar_julgamento_ganhador"));
          }
        }
        $iClassificacao++;
      }
    }
  }

  /**
   * @throws BusinessException
   */
  protected function remover() {

    $oDaoOrcamentoJulgamaneto = new cl_pcorcamjulg();
    $sWhere                   = "pc24_orcamitem in (
                                  select pc22_orcamitem
                                  from pcorcamitem
                                  where pc22_codorc = {$this->oOrcamento->getCodigo()}
                                 )";

    $oDaoOrcamentoJulgamaneto->excluir(null,  null, $sWhere);

    if ($oDaoOrcamentoJulgamaneto->erro_status == "0") {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_excluir_todos_julgamento_orcamento"));
    }

  }


  /**
   *
   * @param CotacaoItem[] $aCotacoes
   * @return float|int
   */
  private function calcularValorFornecedorNoLote(array $aCotacoes) {

    $nValorCotacao  = 0;
    foreach ($aCotacoes as $oCotacao) {
      $nValorCotacao += $oCotacao->getValorTotal();
    }
    return $nValorCotacao;
  }

  /**
   * Retorna os codigos dos fornecedores do Orcamento
   * @return array
   */
  private function getCodigosDosFornecedoresOrcamento() {

    $aCodigos                  = array();
    $oDaoFornecedoresOrcamento = new cl_pcorcamforne();
    $sQueryFornecedores        = $oDaoFornecedoresOrcamento->sql_query_file(null,
                                                                            "pc21_orcamforne, pc21_numcgm",
                                                                            null,
                                                                            "pc21_codorc = {$this->oOrcamento->getCodigo()}"
                                                                           );
    $rsFornecedores            = $oDaoFornecedoresOrcamento->sql_record($sQueryFornecedores);
    $aDadosOrcamentos          = db_utils::getCollectionByRecord($rsFornecedores);
    foreach ($aDadosOrcamentos as $oFornecedor) {
      $aCodigos[$oFornecedor->pc21_numcgm] = $oFornecedor->pc21_orcamforne;
    }
    return $aCodigos;
  }

}