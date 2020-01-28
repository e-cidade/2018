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
 * Classe Responsável pela geração dos dados necessários para o arquivo Programa Orçamento
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoProgramaOrcamento extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 114;
  protected $sNomeArquivo      = 'Programa';

  /**
  * Busca os dados para gerar o Arquivo do Programa do Orçamento
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */
    $oDbConfig        = new db_stdClass();
    $oDadoConfig      = $oDbConfig->getDadosInstit();

    $iInstituicaoSessao = db_getsession('DB_instit');
                      
    $clOrcPrograma    = db_utils::getDao('orcprograma');
    $sCampos          = " orcprograma.o54_programa, orcprograma.o54_descr, orcprograma.o54_anousu, orcprograma.o54_finali ";
    $sCampos2         = ", sum(coalesce(o58_valor, 0)) as total_dotacao ";
    $sWhereOrcUnidade = "o54_anousu = {$this->iAnoUso} and o58_instit = {$iInstituicaoSessao} ";
    $sSqlOrcUnidade   = $clOrcPrograma->sql_query_programaOrcamento($sCampos, $sCampos2, null, $sWhereOrcUnidade, $sCampos);
    
    $rsOrcPrograma  = $clOrcPrograma->sql_record($sSqlOrcUnidade);
  
    if ($clOrcPrograma->numrows > 0) {
  
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for($i = 0; $i < $clOrcPrograma->numrows; $i++) {
  
        $oDadosQuery = db_utils::fieldsMemory($rsOrcPrograma, $i);
        $oDados      = new stdClass();
        
        $oDados->dt_Ano          = $oDadosQuery->o54_anousu;
        $oDados->cd_SubPrograma  = str_pad($oDadosQuery->o54_programa,   4, " ", STR_PAD_LEFT);
        $oDados->cd_Unidade      = str_pad($this->sCodigoTribunal,       4, ' ', STR_PAD_LEFT);
        $oDados->vl_SubPrograma  = str_pad(number_format($oDadosQuery->total_dotacao, 2, "", "") , 16, '0', STR_PAD_LEFT);
        $oDados->de_SubPrograma  = str_pad(substr($oDadosQuery->o54_descr,  0,  50),  50, ' ', STR_PAD_RIGHT);
        $oDados->de_Objetivo     = str_pad(substr($oDadosQuery->o54_finali, 0, 120), 120, ' ', STR_PAD_RIGHT);
        $oDados->Reservado_TCE2  = '0';
        $oDados->Reservado_TCE1  = '0';
        
        $oDados->codigolinha     = 401;
  
        $this->aDados[] = $oDados;
      }
  
    } 
  }
}