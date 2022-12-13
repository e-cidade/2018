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

require_once(modification("model/caixa/arquivos/interfaces/iPagamentoFornecedorTXT.interface.php"));
require_once(modification("model/caixa/arquivos/PagamentoFornecedorTXTBase.model.php"));

/**
 * Processa retorno do banco do brasil(OBN)
 *
 * @package caixa
 * @subpackage arquivos
 */
class PagamentoFornecedorBancoDoBrasilOBN extends PagamentoFornecedorTXTBase implements iPagamentoFornecedorTXT {

  /**
   * Código do Banco
   * @var string
   */
  protected $iCodigoBanco  = "001";

  /**
   * Código do Layout cadastrado no dicionário de dados.
   * @var integer
   */
  protected $iCodigoLayout = 212;

  /**
   * Processa os dados do arquivo de Retorno
   * @return array
   */
  public function processarArquivoRetorno() {

    $this->setArquivoOBN(true);

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
    $aLinhasArquivo = $oLayoutReader->getLines();

    foreach ($aLinhasArquivo as $oArquivo) {
     
      switch (substr($oArquivo->tipo_linha, 0, 1)) {

        /**
         * Header de arquivo
         *    zeros          : ZEROS                          
         *    datagera       : DATA DE GERAÇÃO ARQUIVO        
         *    horageraarq    : HORA DE GERAÇÃO ARQUIVO        
         *    numremessa     : NUMERO DA REMESSA CONSECUTIVO  
         *    numsequearq    : NUMERO SEQUENCIAL NO ARQUIVO   
         *    demaisclientes : 20B001                         
         *    numcont        : NÚMERO DO CONTRATO             
         *    numretremessa  : NÚMERO DE RETORNO DA REMESSA   
         *    codretremessa  : CÓDIGO DE RETORNO DA REMESSA   
         *    motdevremessa  : MOTIVO DA DEVOLUÇÃO DA REMESSA 
         *    tipoarq        : TIPO ARQUIVO                   
         *    brancos        : BRANCOS                        
         */
        case '0':

          if ($oArquivo->tipoarq == "PREVIA") {
            throw new BusinessException("Arquivo de prévia, não será importado para o sistema.");
          }

          $oDadosRetorno->header->febraban     = $oArquivo->demaisclientes;
          $oDadosRetorno->header->uso_banco    = "";
          $oDadosRetorno->header->lote_servico = $oArquivo->numretremessa;
          $oDadosRetorno->header->codigo_banco = $this->iCodigoBanco;
          $oDadosRetorno->header->seq_arquivo  = $oArquivo->numsequearq;

        break;

        /**
         * Registro tipo 2
         *   codagbanc                     : CÓDIGO DA AGENCIA BANCÁRIA UG EMITENTE
         *   codug                         : CÓDIGO DA UG/GETAÕA EMITENTE DAS OBS      
         *   nummov                        : NÚMERO DA RELAÇÃO                         
         *   numob                         : NÚMERI DA OB                              
         *   datamov                       : DATA DE REFERENCIA DA RELAÇÃO             
         *   brancos                       : BRANCOS                                   
         *   codigooperacao                : CÓDIGO DA OPERAÇÃO                        
         *   zeros                         : ZEROS                                     
         *   valorliquido                  : VALOR LIQUIDO DA OB                       
         *   codigobancofavorecido         : CODIGO DO BANCO DO FAVORECIDO             
         *   agenciafavorecido             : CODIGO DA AGENCIA BANCARIA DO FAVORECIDO  
         *   digitoverificador             : DIGITO VERIFICADOR                        
         *   codigocontacorrentefavorecido : CONTA CORRENTE FAVORECIDO                 
         *   enderecofavorecido            : ENDERECO DO FAVORECIDO                    
         *   municipiofavorecido           : MUNICIPIO DO FAVORECIDO                   
         *   codigosiafi                   : CODIGO SIAFI                              
         *   cepfavorecido                 : CEP DO FAVORECIDO                         
         *   uffavorecido                  : UF DO FAVORECIDO                          
         *   observacaoob                  : OBSERVACAO DA OB                          
         *   indicadorpagamento            : INDICADOR DO TIPO DE PAGAMENTO            
         *   tipofavorecido                : TIPO DE FAVORECIDO                        
         *   codigofavorecido              : CODIGO DO FAVORECIDO                      
         *   prefixoagencia                : PREFIXO DA AGENCIA PARA DEBITO            
         *   contaconvenio                 : NUMERO DA CONTA DO CONVENIO               
         *   finalidadepagamento           : FINALIDADE DO PAGAMENTO - FUNDEF          
         *   brancos                       : BRANCOS                                   
         *   novefavorecido                : NOME DO FAVORECIDO                        
         *   codretornooperacao            : CODIGO DO RETORNO DA OPERACAO             
         *   sequencialarquivo             : NUMERO SEQUENCIAL NO ARQUIVO              
         */
        case '2' :

          $sValorEfetivado  = substr($oArquivo->valorliquido, 0, 15) . ".";
          $sValorEfetivado .= substr($oArquivo->valorliquido, 15, 2);
          
          $oRegistro = new stdClass();
          /* [Inicio plugin GeracaoArquivoOBN  - correcao codigo movimento] */          
          $oRegistro->codigo_movimento = trim((int) $oArquivo->nummov);
          /* [Fim plugin GeracaoArquivoOBN  - correcao codigo movimento] */
          $oRegistro->numero_lote      = "00";
          $oRegistro->mov_lote         = "0000"; 
          $oRegistro->numero_banco     = $oArquivo->codigosiafi; 
          $oRegistro->valor_efetivado  = (float) $sValorEfetivado; 
          $oRegistro->data_efetivacao  = date('Y-m-d');
           
          /*
           * Informa qual o tipo de retorno do banco. 
           * Para saber se o registro pode ser baixado no sistema.
           */
          $oRegistro->retorno_banco   = $oArquivo->codretornooperacao;
          $oRegistro->codigo_retorno  = $this->getCodigoErro( $oArquivo->codretornooperacao );

          /* [Inicio plugin GeracaoArquivoOBN  - Observacao ocorrencia] */
          /* [Fim plugin GeracaoArquivoOBN - Observacao ocorrencia] */

          $oDadosRetorno->registros[] = $oRegistro;

       break;

        /**
         * Registro tipo 4
         *   codopera                : CÓDIGO DE OPERAÇÃO               
         *   numseqlista             : NUMERO SEQUENCIAL DE LISTA       
         *   brancos                 : BRANCOS                          
         *   tipofatura              : TIPO DE FATURA                   
         *   brancos                 : BRANCOS                          
         *   valormorajuros          : VLOR MORA JUROS                  
         *   brancos                 : BRANCOS                          
         *   datavencimento          : DATA DO VENCIMENTO               
         *   valornominal            : VALOR NOMINAL                    
         *   valordescontoabatimento : VALOR DESCONTO ABATIMENTO        
         *   observacaoob            : OBSERVACAO OB                    
         *   numeroautenticacao      : NUMERO DA AUTENTICACAO           
         *   prefixoagenciadebito    : PREFIXO DA AGENCIA PARA DEBITO   
         *   numerocontaconvenio     : NUMERO DA CONTA CONVENIO         
         *   brancos                 : BRANCOS                          
         *   codigoretornooperacao   : CODIGO DE RETORNO DA OPERAÇÃO    
         *   seuquencialarquivo      : NUMERO SEQUENCIAL DO ARQUIVO     
         *   brancos                 : BRANCOS                          
         *   codigobarra             : CODIGO DE BARRA                  
         *   quatro                  : 4                                
         *   codug                   : CÓDIGO DA UG/GESTAO EMITENTE OBS 
         *   codrelac                : CÓDIGO RELAÇÃO                   
         *   dataref                 : DATA DE REFENCIAQ DA RELAÇÃO     
         *   valorliqui              : VALOR LIQUIDO DA OB              
         *   codagenbanca            : CODIGO AGENCIA BANCÁRIA UG       
         *   codob                   : CODIGO DA OB                     
         *   brancos                 : BRANCOS                          
         */
        case '4' :

        break;

      }

    }

    $this->oDadosArquivo = $oDadosRetorno;

    return true;
  }

}
