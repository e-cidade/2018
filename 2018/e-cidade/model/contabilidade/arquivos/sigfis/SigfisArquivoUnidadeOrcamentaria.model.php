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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/**
 *
 * Classe Responsável pela geração dos dados necessários para o arquivo Unidade Orçamentária
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoUnidadeOrcamentaria extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 113;
  protected $sNomeArquivo      = 'UnidOrca';

  /**
  * Busca os dados para gerar o Arquivo de Unidade Orçamentária
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */
    $oDbConfig    = new db_stdClass();
    $oDadoConfig  = $oDbConfig->getDadosInstit();
    
    $clOrcUnidade   = db_utils::getDao('orcunidade');
    $sCampos        = "orcunidade.o41_anousu, orcunidade.o41_unidade, orcunidade.o41_descr, orcunidade.o41_orgao";
    $sWhere         = "     o41_instit = " .db_getsession("DB_instit");
    $sWhere        .= " and o41_anousu = {$this->iAnoUso}";
    $sSqlOrcUnidade = $clOrcUnidade->sql_query_file(null, null, null, $sCampos, null, $sWhere);
    $rsOrcUnidade   = $clOrcUnidade->sql_record($sSqlOrcUnidade);
    
    if ($clOrcUnidade->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for($i = 0; $i < $clOrcUnidade->numrows; $i++) {
        
        $oDadosQuery = new stdClass();
        $oDadosQuery = db_utils::fieldsMemory($rsOrcUnidade, $i);
        $oDados      = new stdClass();
        
        $oDados->dt_Ano                   = $oDadosQuery->o41_anousu;
        $oDados->cd_Unidade               = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
        $oDados->cd_UnidadeOrcamentaria   = str_pad($oDadosQuery->o41_unidade, 4, ' ', STR_PAD_LEFT);
        $oDados->de_UnidadeOrcamentaria   = str_pad($oDadosQuery->o41_descr,  50, ' ', STR_PAD_RIGHT);
        $oDados->Reservado_TCE            = str_pad('0',                       6, '0', STR_PAD_LEFT); 
        $oDados->cd_Orgao                 = str_pad($oDadosQuery->o41_orgao,   4, ' ', STR_PAD_LEFT);
        $oDados->codigolinha              = 400;
        
        $this->aDados[] = $oDados;
      }
      
    } 
  }
}