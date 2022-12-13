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
 * Processa o arquivo de retorno do Bradesco no formato PagFor
 */
class PagamentoFornecedorBradescoPagFor extends PagamentoFornecedorTXTBase implements iPagamentoFornecedorTXT {

  /**
   * Indica que a linha é do tipo cabeçalho (header)
   */
  const LINHA_CABECALHO = '0';

  /**
   * Indica que a linha é do tipo transação
   */
  const LINHA_TRANSACAO = '1';

  /**
   * Código do banco
   *
   * @var integer
   */
  protected $iCodigoBanco = GeradorArquivoPagFor::CODIGO_BANCO_BRADESCO;

  /**
   * Código do layout cadastrado
   *
   * @var integer
   */
  protected $iCodigoLayout = 257;

  /**
   * Caso a transação tenha algum dos códigos nessa lista ela será ignorada.
   *
   * @var array
   */
  private static $aCodigosAgendamento = array('BD', 'FS', 'GU', 'HA', 'HF', 'JH', 'KS', 'LA', 'ME', 'TR');

  /**
   * @return boolean
   */
  public function processarArquivoRetorno() {

    $this->setTipo(TipoTransmissao::PAGFOR);

    $oArquivo = new DBLayoutReader($this->iCodigoLayout, $this->sCaminhoArquivo, false, false);
    $oArquivo->processarArquivo(0, false, true);
    $aLinhas = $oArquivo->getLines();

    $oDados = new stdClass();
    $oDados->header = new stdClass();
    $oDados->registros = array();

    foreach ($aLinhas as $oLinha) {

      switch ($oLinha->identificacao_registro) {

        case self::LINHA_CABECALHO:

          $oDados->header->febraban     = '';                                           // e75_febraban
          $oDados->header->uso_banco    = $oLinha->numero_retorno;                      // e75_arquivoret
          $oDados->header->codigo_banco = GeradorArquivoPagFor::CODIGO_BANCO_BRADESCO;  // e75_codfebraban
          $oDados->header->seq_arquivo  = '0';                                          // e75_seqarq

          break;

        case self::LINHA_TRANSACAO;

          $oRegistro = new stdClass;
          $oRegistro->codigo_movimento = ltrim($oLinha->numero_pagamento, '0');                    // e76_codmov
          $oRegistro->numero_lote      = '0';                                                      // e76_lote
          $oRegistro->mov_lote         = '0';                                                      // e76_movlote
          $oRegistro->numero_banco     = '0';                                                      // e76_numbanco
          $oRegistro->valor_efetivado  = $this->formatarValorMonetario($oLinha->valor_pagamento);  // e76_valorefet
          $oRegistro->data_efetivacao  = $this->formatarData($oLinha->data_efetivacao_pagamento);  // e76_dataefet
          $oRegistro->codigo_retorno   = $this->getCodigoErro($oLinha->informacao_retorno);        // e76_errobanco - array com os códigos de erro

          $oDados->registros[] = $oRegistro;

          break;
      }

    }
    $this->oDadosArquivo = $oDados;

    return true;
  }

  /**
   * Transforma uma string retornada pelo banco em float.
   *
   * Exemplo:
   *   Entrada: 000000000010055
   *   Retorno: 100.55
   *
   * @param  string  $sValor
   * @param  integer $iDigitos       Quantidade de dígitos no campo retornado pelo banco
   * @param  integer $iCasasDecimais Quantidade de casas decimais
   * @return float
   */
  private function formatarValorMonetario($sValor, $iDigitos = 15, $iCasasDecimais = 2) {

    $sValorReais    = substr($sValor, 0, $iDigitos - $iCasasDecimais);
    $sValorCentavos = substr($sValor, $iDigitos - $iCasasDecimais, $iCasasDecimais);

    return (float) ($sValorReais . '.' . $sValorCentavos);
  }

  /**
   * Transforma uma string retornada pelo banco em data ISO.
   *
   * Exemplo: 20160708
   *
   * @param  string $sData
   * @return string Data no formato ISO (yyyy-mm-dd)
   */
  private function formatarData($sData) {

    $sAno = substr($sData, 0, 4);
    $sMes = substr($sData, 4, 2);
    $sDia = substr($sData, 6, 2);

    return "{$sAno}-{$sMes}-{$sDia}";
  }

  public static function getCodigosAgendamento() {
    return self::$aCodigosAgendamento;
  }

}