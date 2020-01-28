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

require_once("model/caixa/arquivos/interfaces/iPagamentoFornecedorTXT.interface.php");
require_once("model/caixa/arquivos/PagamentoFornecedorTXTBase.php");
/**
 *Interface para pagamento de fornecedores de txt dos bancos
 * @package caixa
 * @subpackage arquivos
 */
class PagamentoFornecedorBanrisul extends PagamentoFornecedorTXTBase implements iPagamentoFornecedorTXT {
  
  protected $iCodigoBanco  = '041';
  protected $iCodigoLayout = 104; 
 
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
          $oDadosRetorno->header->febraban            = $oArquivo->exclusivo_febraban;  // e75_febraban
          $oDadosRetorno->header->uso_banco           = $oArquivo->uso_banco;           // e75_arquivoret
          $oDadosRetorno->header->codigo_banco        = $oArquivo->codigo_banco;        // e75_codfebraban
          $oDadosRetorno->header->seq_arquivo         = $oArquivo->seq_arquivo;         // e75_seqarq
          $oDadosRetorno->header->lote_servico        = $oArquivo->lote_servico;
          $oDadosRetorno->header->reserv_banc_remessa = $oArquivo->reserv_banc_remessa;  
          
         break;
         
        case '1':
          
          /*
           * Codigo do lote
           */
          $sNumeroLote = $oArquivo->lote_serv;
          break;
          
        case '3' :

          /**
           * Registros de cada lote. é separado em dois segmentos.
           * Segmento A: Informações sobre o pagamento do registro no banco.
           */
          if ($oArquivo->codigo_segmento == "A") {
            
            $sValorEfetivado  = substr($oArquivo->vlr_real_efetivado,  0, 13).".";
            $sValorEfetivado .= substr($oArquivo->vlr_real_efetivado, 13, 2);
            $sDataEfetivacao  = substr($oArquivo->data_efetiva_cred,     4, 4);
            $sDataEfetivacao .= "-".substr($oArquivo->data_efetiva_cred, 2, 2);
            $sDataEfetivacao .= "-".substr($oArquivo->data_efetiva_cred, 0, 2);
            if ($sDataEfetivacao == '0000-00-00') {
              $sDataEfetivacao  = date("Y-m-d", db_getsession("DB_datausu"));
            }
            $oRegistro = new stdClass();
            $oRegistro->codigo_movimento = $this->verificaCodigoMovimento(trim($oArquivo->num_documento));
            if (empty($oRegistro->codigo_movimento)) {
              continue;
            }
            $oRegistro->numero_lote      = trim($sNumeroLote);
            $oRegistro->mov_lote         = trim($oArquivo->num_registro_lote);
            $oRegistro->numero_banco     = trim($oArquivo->num_documento_atribuido);
            $oRegistro->valor_efetivado  = (float)$sValorEfetivado;
            $oRegistro->data_efetivacao  = $sDataEfetivacao;
             
            /**
             * Informa qual o tipo de retorno do banco. 
             * Para saber se o registro pode ser baixado no sistema.
             */
            $oRegistro->retorno_banco    = trim($oArquivo->ocorrencias);
            $oRegistro->codigo_retorno   = $this->getCodigoErro($oRegistro->retorno_banco); // e76_errobanco
            $oDadosRetorno->registros[]  = $oRegistro;
          }
          break;
      }
    }
    
    $this->oDadosArquivo = $oDadosRetorno;
    return true;
  }
  
	/**
 	 * Este método busca somente o código do movimento. É uma rotina específica do Banrisul.
 	 * O banco quando envia o código do movimento, envia EX: 000222 o Banrisul, envia esse código desta forma: 222000
   * @param string $sCodigoMovimento
   */
  function verificaCodigoMovimento($sCodigoMovimento) {
    
    $sSqlMovimento  = "  select e81_codmov																																				  ";
    $sSqlMovimento .= "    from empageconfgera 																																		  ";
    $sSqlMovimento .= "         inner join empagemov      on empagemov.e81_codmov   = empageconfgera.e90_codmov 		";
    $sSqlMovimento .= "         inner join empagegera     on empagegera.e87_codgera = empageconfgera.e90_codgera		"; 
    $sSqlMovimento .= "         inner join empage         on empage.e80_codage      = empagemov.e81_codage					";
    $sSqlMovimento .= "         left join empempenho      on empempenho.e60_numemp  = empagemov.e81_numemp				  ";
    $sSqlMovimento .= "         left join empagemovforma  on e97_codmov             = e81_codmov										";
    $sSqlMovimento .= "         left join empord          on e82_codmov             = e81_codmov 										";
    $sSqlMovimento .= "         left join pagordemele     on e82_codord             = e53_codord										";
    $sSqlMovimento .= "         left join empagemovslips  on k107_empagemov         = e81_codmov  									";
    $sSqlMovimento .= "         left join empageslip      on e89_codmov             = e81_codmov 										";
    $sSqlMovimento .= "         left join slip            on e89_codigo             = k17_codigo										";
    $sSqlMovimento .= "   where e80_instit = " . db_getsession("DB_instit") . "																			";
    $sSqlMovimento .= "     and rpad(e81_codmov,15,0) = '{$sCodigoMovimento}'																				"; 
    $sSqlMovimento .= "     and e97_codforma = 3 																																		";
    $sSqlMovimento .= "     and e90_cancelado is false																														";
    $sSqlMovimento .= "     and ( case when e82_codord is not null 																									";
    $sSqlMovimento .= "                then round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)							"; 
    $sSqlMovimento .= "                else case when k17_dtaut is not null																					"; 
    $sSqlMovimento .= "                          then 0 else 1																										  "; 
    $sSqlMovimento .= "                      end 																																		"; 
    $sSqlMovimento .= "            end ) > 0 																																				";
  
    $rsCodigoMovimento     = db_query($sSqlMovimento);
    $iTotalLinhasMovimento = pg_num_rows($rsCodigoMovimento);
    if ($iTotalLinhasMovimento > 0) {
    	
      if ($iTotalLinhasMovimento > 1) {
      	
      	$sStrMsg  = "AVISO:\\nDurante o processamento do retorno foram encontradas inconsistências no movimento {$sCodigoMovimento}.\\n";
      	$sStrMsg .= "Provavelmente o código deste movimento conflita com algum outro já existente.";
     	$sStrMsg .= "Deste modo o sistema não continuará o processamento.\\n\\nPara resolver o problema contate o Suporte."; 
      	throw new Exception($sStrMsg); 
      }
      return db_utils::fieldsMemory($rsCodigoMovimento, 0)->e81_codmov;
    }
  }
}