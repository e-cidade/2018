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

/**
 * Factory para decidir qual regra deve aplicar com base no documento contábil.
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.56 $
 */
class RegraLancamentoContabilFactory {

  public function __construct() {}

  /**
   * Metodo para retornar o objeto da regra, pesquisando pelo codigo do documento
   * @param integer $iDocumento
   * @return RegraLancamentoContabil
   */
  private function getInstanciaPorDocumento ($iDocumento) {

    $aRegras = array(     
     1   => "RegraLancamentoEmpenhoFinanceiro"
    ,2   => "RegraLancamentoEmpenhoFinanceiro" 
    ,3   => "RegraLancamentoLiquidacaoEmpenho" 
    ,4   => "RegraLancamentoLiquidacaoEmpenho"
    ,23  => "RegraLancamentoLiquidacaoEmpenho"    
    ,24  => "RegraLancamentoLiquidacaoEmpenho"
    ,33  => "RegraLancamentoLiquidacaoEmpenho"
    ,34  => "RegraLancamentoLiquidacaoEmpenho"
    ,80  => "RegraInscricaoPassivoSemSuporteOrcamentario"
    ,81  => "RegraBaixaInscricaoPassivoSemSuporteOrcamentario"
    ,82  => "RegraEmpenhoPassivoSemSuporteOrcamentario"
    ,84  => "RegraLiquidacaoEmpenhoPassivoSemSuporteOrcamentario"
    ,85  => "RegraLiquidacaoEmpenhoPassivoSemSuporteOrcamentario"
    ,90  => "RegraLancamentoDevolucaoAdiantamento"
    ,91  => "RegraLancamentoDevolucaoAdiantamento"
    ,92  => "RegraLancamentoEmpenhoPrestacaoConta"
    ,100 => "RegraArrecadacaoReceita"
    ,101 => "RegraArrecadacaoReceita"
    ,105 => "RegraReconhecimentoReceitaFatoGerador"
    ,106 => "RegraReconhecimentoReceitaFatoGerador"
    ,107 => "RegraArrecadacaoReceita"
    ,108 => "RegraArrecadacaoReceita"
    ,109 => "RegraArrecadacaoReceita"
    ,110 => "RegraArrecadacaoReceita"
    ,111 => "RegraArrecadacaoReceita"
    ,112 => "RegraArrecadacaoReceita"
    ,113 => "RegraArrecadacaoReceita"
    ,114 => "RegraArrecadacaoReceita"
    ,115 => "RegraArrecadacaoReceita"
    ,116 => "RegraArrecadacaoReceita"
    ,117 => "RegraArrecadacaoReceita"
    ,118 => "RegraArrecadacaoReceita"
    ,120 => "RegraPagamentoSlip"
    ,121 => "RegraAnulacaoSlip"
    ,130 => "RegraPagamentoSlip"
    ,131 => "RegraAnulacaoSlip"
    ,140 => "RegraPagamentoSlip"
    ,141 => "RegraAnulacaoSlip"
    ,150 => "RegraPagamentoSlip"
    ,151 => "RegraPagamentoSlip"
    ,152 => "RegraAnulacaoSlip"
    ,153 => "RegraAnulacaoSlip"
    ,160 => "RegraPagamentoSlip"
    ,161 => "RegraPagamentoSlip"
    ,162 => "RegraAnulacaoSlip"
    ,163 => "RegraAnulacaoSlip"
    ,200 => "RegraEmLiquidacao"
    ,201 => "RegraEmLiquidacao"
    ,202 => "RegraLancamentoLiquidacaoEmpenho"
    ,203 => "RegraLancamentoLiquidacaoEmpenho"
    ,204 => "RegraLancamentoLiquidacaoEmpenho"
    ,205 => "RegraLancamentoLiquidacaoEmpenho"
    ,206 => "RegraLancamentoLiquidacaoEmpenho"
    ,207 => "RegraLancamentoLiquidacaoEmpenho"
    ,208 => "RegraLancamentoEmLiquidacaoMaterialPermanente"
    ,209 => "RegraLancamentoEmLiquidacaoMaterialPermanente"
    ,210 => "RegraLancamentoEmLiquidacaoMaterialConsumo"
    ,211 => "RegraLancamentoEmLiquidacaoMaterialConsumo"
    ,300 => "RegraLancamentoProvisaoFerias"
    ,301 => "RegraLancamentoProvisaoFerias"
    ,302 => "RegraLancamentoProvisaoDecimoTerceiro"
    ,303 => "RegraLancamentoProvisaoDecimoTerceiro"
    ,304 => "RegraLancamentoProvisaoFerias"
    ,305 => "RegraLancamentoProvisaoFerias"
    ,306 => "RegraLancamentoProvisaoFerias"
    ,307 => "RegraLancamentoProvisaoFerias"
    ,308 => "RegraLancamentoProvisaoDecimoTerceiro"
    ,309 => "RegraLancamentoProvisaoDecimoTerceiro"
    ,310 => "RegraLancamentoProvisaoDecimoTerceiro"
    ,311 => "RegraLancamentoProvisaoDecimoTerceiro"
    ,400 => "RegraMovimentacaoEstoqueSaida"
    ,401 => "RegraMovimentacaoEstoqueSaida"
    ,402 => "RegraLancamentoEntradaEstoque"
    ,403 => "RegraLancamentoEntradaEstoque"
    ,404 => "RegraMovimentacaoEstoqueSaida"
    ,410 => "RegraLancamentoEmpenhoFinanceiro"
    ,411 => "RegraLancamentoEmpenhoFinanceiro"
    ,412 => "RegraLancamentoLiquidacaoEmpenho" 
    ,413 => "RegraLancamentoLiquidacaoEmpenho" 
    ,414 => "RegraLancamentoDevolucaoAdiantamento"
    ,415 => "RegraLancamentoDevolucaoAdiantamento"
    ,416 => "RegraLancamentoDevolucaoAdiantamento"
    ,417 => "RegraLancamentoDevolucaoAdiantamento"
    ,418 => "RegraArrecadacaoReceita"
    ,419 => "RegraArrecadacaoReceita"
    ,500 => "RegraLancamentoEmpenhoFinanceiro"
    ,502 => "RegraLancamentoLiquidacaoEmpenho" 
    ,503 => "RegraLancamentoLiquidacaoEmpenho" 
    ,504 => "RegraLancamentoEmpenhoFinanceiro"
    ,506 => "RegraLancamentoEmpenhoFinanceiro"
    ,507 => "RegraLancamentoEmpenhoFinanceiro"
    ,508 => "RegraLancamentoReconhecimentoContabil"
    ,509 => "RegraLancamentoReconhecimentoContabil"
    ,510 => "RegraLancamentoReconhecimentoContabil"
    ,511 => "RegraLancamentoReconhecimentoContabil"
    ,513 => "RegraLancamentoReconhecimentoContabil"
    ,514 => "RegraLancamentoReconhecimentoContabil"
    ,600 => "RegraLancamentoReavaliacaoBem"
    ,601 => "RegraLancamentoReavaliacaoBem"
    ,602 => "RegraLancamentoReavaliacaoBem"
    ,603 => "RegraLancamentoReavaliacaoBem"
    ,604 => "RegraLancamentoContaDepreciacao"
    ,605 => "RegraLancamentoContaDepreciacao"
    ,700 => "RegraLancamentoIncorporacaoBem"
    ,701 => "RegraLancamentoIncorporacaoBem"
    ,702 => "RegraLancamentoIncorporacaoBem"
    ,703 => "RegraLancamentoAjusteBaixaBem"
    ,900 => "RegraLancamentoAcordo"
    ,901 => "RegraLancamentoAcordo"
    ,903 => "RegraLancamentoAcordo"
    ,904 =>  "RegraLancamentoAcordo"
    ,2001 => "RegraLancamentoAberturaExercicio"
    ,2002 => "RegraLancamentoAberturaExercicio"
    ,2003 => "RegraLancamentoAberturaExercicio"
    ,2004 => "RegraLancamentoAberturaExercicio"
    ,2005 => "RegraLancamentoRestosAPagar"
    ,2006 => "RegraLancamentoRestosAPagar");

    if (! array_key_exists($iDocumento, $aRegras) ) {
      throw new BusinessException("ERRO TÉCNICO : Não foi possivel localizar a regra para o documento {$iDocumento}.");
    }

    return new $aRegras[$iDocumento];
  }
  /**
   * Deve retornar a conta contabil para efetuar o lançamento
   * @param integer $iDocumento
   * @param integer $iLancamento
   * @param stdClass $oObjetoVariaveis
   * @return RegraLancamentoContabil
   */
  public static function getRegraLancamento($iDocumento,
                                            $iLancamento,
                                            ILancamentoAuxiliar $oLancamentoAuxiliar ) {

    $oRegra  = self::getInstanciaPorDocumento($iDocumento);

    /**
     * @see mapearLancamentoAuxliarPorDocumento
     */
    $lMapeamento = self::mapearLancamentoAuxliarPorDocumento($iDocumento, $oLancamentoAuxiliar);

    return $oRegra->getRegraLancamento($iDocumento, $iLancamento, $oLancamentoAuxiliar);

  }

  /**
   * Método que guarda em um arquivo CSV o documento executado e qual lançamento auxiliar foi usado.
   * 
   * Esta rotina foi criada para conseguirmos mapear o documento e que lancamento auxiliar ele pode utilizar, depois
   * do mapeamento realizado, refatoraremos os lançamentos auxiliares limpando métodos e informações duplicadas.
   *
   * @param $iCodigoDocumento - Código do Documento que está sendo executado
   * @param $oLancamentoAuxiliar - Lancamento auxiliar utilizado para executar o lançamento contábil
   * @return boolean - true
   */
  public static function mapearLancamentoAuxliarPorDocumento($iCodigoDocumento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    if ( !file_exists("cache") ) {
      mkdir("cache", 0777);
    }

    $sNomeArquivo    = "cache/mapeamento_lancamentoauxiliar_documento.csv";
    $sLinhaArquivo   = "{$iCodigoDocumento},".get_class($oLancamentoAuxiliar)."\n";
    $aDadosArquivo   = array();
    if ( file_exists($sNomeArquivo) ) {
      $aDadosArquivo = file($sNomeArquivo);
    }
    if ( !in_array($sLinhaArquivo, $aDadosArquivo) ) {
        
      $hAbreArquivo    = fopen($sNomeArquivo, "a");
      $hEscreveArquivo = fwrite($hAbreArquivo, $sLinhaArquivo);
      $hEscreveArquivo = fclose($hAbreArquivo);
    }
    return true;
  }
}
?>