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

/**
 * Classe que possui as configurações referentes ao processamento do arquivo de envio OBN
 * @author Bruno Silva bruno.silva@dbseller.com.br
 * @package caixa
 */
class ConfiguracaoArquivoObn {

  //Código padrão usado no layout de linha
  const CODIGO_PADRAO_INSTITUICAO = "00000100001";

  //Tipo de operação
  const OPERACAO_DOC           = 31;
  const OPERACAO_TED           = 31;
  const OPERACAO_DEP           = 32;
  const OPERACAO_CODIGO_BARRAS = 38;

  /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte1] */
  /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte1] */

  //Tipo de pagamento
  const PAGAMENTO_CONTA_BB      = 1;
  const PAGAMENTO_CAIXA         = 3;
  const PAGAMENTO_OUTROS_BANCOS = 4;

  //Tipo de favorecido
  const TIPO_CNPJ    = 1;
  const TIPO_CPF     = 2;
  const CPF_TAMANHO  = 11;

  //Tipo de Layout Linha
  const LAYOUT4      = 4;
  const LAYOUT3      = 3;
  const LAYOUT2      = 2;

  //Valor que determinate para tipar operações em TED ou DOC
  const VALOR_DETERMINANTE_DOC_TED = 1000;


  /**
   * Verifica o Tipo de operação da movimentação, retornando o código correspondente de acordo com layout do arquivo
   * @param  stdClass $oDadosMovimentacao
   * @throws BusinessException
   * @return integer
   */
  public static function verificarTipoOperacao(MovimentoArquivoTransmissao $oDadosMovimentacao) {

    // Movimentação com código de barras
    $sCodigoBarras = $oDadosMovimentacao->getCodigoBarra();
    if (!empty ($sCodigoBarras)) {
      return ConfiguracaoArquivoObn::OPERACAO_CODIGO_BARRAS;
    }

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte2] */
    /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte2] */

    // Contas do mesmo banco, operação tipo DEP
    if ($oDadosMovimentacao->getCodigoBancoFavorecido() == $oDadosMovimentacao->getCodigoBancoPagador()) {

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte3] */
      /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte3] */

      return ConfiguracaoArquivoObn::OPERACAO_DEP;
    }

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte4] */
    /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte4] */

    //Operação tipo DOC
    if ($oDadosMovimentacao->getValor() <= ConfiguracaoArquivoObn::VALOR_DETERMINANTE_DOC_TED) {
      return ConfiguracaoArquivoObn::OPERACAO_DOC;
    }

    //Operação tipo TED
    if ($oDadosMovimentacao->getValor() > ConfiguracaoArquivoObn::VALOR_DETERMINANTE_DOC_TED) {
      return ConfiguracaoArquivoObn::OPERACAO_TED;
    }

    throw new BusinessException("Erro técnico: Erro ao verificar o tipo de operação.");
  }


  /**
   * Verifica o tipo do favorecido, retornando o valor correspondente de acordo com layout arquivo
   * @param  stdClass $iTamanhoCpf
   * @return integer
   */
  public static function verificaTipoFavorecido($iTamanhoCpf) {

    $iTipoFavorecido = ConfiguracaoArquivoObn::TIPO_CNPJ;

    if ($iTamanhoCpf <= ConfiguracaoArquivoObn::CPF_TAMANHO) {
      $iTipoFavorecido = ConfiguracaoArquivoObn::TIPO_CPF;
    }
    return $iTipoFavorecido;
  }


  /**
   * Verifica o tipo de operação, retornando o valor correspondente de acordo com layout arquivo
   * @param  stdClass $iTamanhoCpf
   * @return integer
   */
  public static function verificaTipoPagamento($iTipoOperacao) {

    switch ($iTipoOperacao) {

      case ConfiguracaoArquivoObn::OPERACAO_CODIGO_BARRAS:
        return ConfiguracaoArquivoObn::PAGAMENTO_CAIXA;
      break;

      case ConfiguracaoArquivoObn::OPERACAO_DEP:
        return ConfiguracaoArquivoObn::PAGAMENTO_CONTA_BB;
      break;

      case ConfiguracaoArquivoObn::OPERACAO_DOC|| ConfiguracaoArquivoObn::OPERACAO_TED:
        return ConfiguracaoArquivoObn::PAGAMENTO_OUTROS_BANCOS;
      break;

      default:
        throw new BusinessException("Erro técnico: Impossível verificar tipo de pagamento");
      break;
    }
  }


  /**
   * Função que verifica o layout em que a linha será gerada
   * Caso não exista cadastro de detalhe (código de barras) será o LAYOUT3
   * Senão tipo LAYOUT4
   * @return Integer
   */
  public static function verificaTipoLinha($sCodigoDeBarras) {

    if (empty($sCodigoDeBarras)){
      return ConfiguracaoArquivoObn::LAYOUT2;
    }
    return ConfiguracaoArquivoObn::LAYOUT4;
  }


  /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte5] */
  /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte5] */
  
}
