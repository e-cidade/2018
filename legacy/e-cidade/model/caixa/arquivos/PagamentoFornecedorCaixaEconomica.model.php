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

require_once("model/caixa/arquivos/interfaces/iPagamentoFornecedorTXT.interface.php");
require_once("model/caixa/arquivos/PagamentoFornecedorTXTBase.model.php");
/**
 *Interface para pagamento de fornecedores de txt dos bancos
 * @package caixa
 * @subpackage arquivos
 */
class PagamentoFornecedorCaixaEconomica extends PagamentoFornecedorTXTBase implements iPagamentoFornecedorTXT {
  
  protected $iCodigoBanco  = '104';
  protected $iCodigoLayout = 102; 
 
  /**
   * Processa os dados do arquivo de Retorno
   * @return array
   */
  public function processarArquivoRetorno() {

    /**
     * processa os dados do arquivo atraves do cadastro de layout; 
     */
    $oLayoutReader = new DBLayoutReader($this->iCodigoLayout, $this->sCaminhoArquivo, false, false);
    $oLayoutReader->processarArquivo(0, false, true);
     
    $oDadosRetorno            = new stdClass();
    $oDadosRetorno->header    = new stdClass();
    $oDadosRetorno->registros = array();
    /**
     * processa cada linha do arquivo, conforme o tipo de linha.
     */
    foreach ($oLayoutReader->getLines() as $iInd => $oArquivo) {
      
      switch ($oArquivo->codigo_registro) {
        case '0':
         
          /**
           * Header de arquivo
           */
          $oDadosRetorno->header->febraban       = $oArquivo->exclusivo_febraban;  // e75_febraban
          $oDadosRetorno->header->uso_banco      = $oArquivo->uso_banco;           // e75_arquivoret
          $oDadosRetorno->header->codigo_banco   = $oArquivo->codigo_banco;        // e75_codfebraban
          $oDadosRetorno->header->seq_arquivo    = "0";                            // e75_seqarq
          $oDadosRetorno->header->lote_servico   = $oArquivo->lote_servico;
         
         break;
         
        case '1':
          
          /*
           * Codigo do lote
           */
          $sNumeroLote = $oArquivo->lote_servico;
          break;
          
        case '3' :
           
          /**
           * Registros de cada lote. é separado em dois segmentos.
           * Segmento A: Informações sobre o pagamento do registro no banco.
           */
          if ($oArquivo->codigo_segmento == "A") {

            $sValorEfetivado  = substr($oArquivo->vlr_real_efetivado,  0, 13).".";
            $sValorEfetivado .= substr($oArquivo->vlr_real_efetivado,  13,2);
            $sDataEfetivacao  = substr($oArquivo->data_efetivacao,     4, 4);
            $sDataEfetivacao .= "-".substr($oArquivo->data_efetivacao, 2, 2);
            $sDataEfetivacao .= "-".substr($oArquivo->data_efetivacao, 0, 2);
            if ($sDataEfetivacao == '0000-00-00' ||$sDataEfetivacao == '00000000') {
              $sDataEfetivacao  = date("Y-m-d", db_getsession("DB_datausu"));
            }
            $oRegistro = new stdClass();
            $oRegistro->codigo_movimento = trim($oArquivo->documento_empresa);         // e76_codmov     
            $oRegistro->numero_lote      = trim($oArquivo->lote_servico);              // e76_lote       
            $oRegistro->mov_lote         = "0";                                        // e76_movlote    
            $oRegistro->numero_banco     = trim($oArquivo->num_documento_banco);       // e76_numbanco   
            $oRegistro->valor_efetivado  = (float)$sValorEfetivado;                    // e76_valorefet  
            $oRegistro->data_efetivacao  = $sDataEfetivacao;                           // e76_dataefet   
                                                                                       
            /**
             * Informa qual o tipo de retorno do banco. 
             * Para saber se o registro pode ser baixado no sistema.
             */
            $oArquivo->ocorrencias       = trim($oArquivo->ocorrencias);
            $oRegistro->retorno_banco    = $oArquivo->ocorrencias; 
            $oRegistro->codigo_retorno   = $this->getCodigoErro($oRegistro->retorno_banco); // e76_errobanco
            $oDadosRetorno->registros[]  = $oRegistro; 
          }
          break;
      }
    }
    
    $this->oDadosArquivo = $oDadosRetorno;
    return true;
  }
}