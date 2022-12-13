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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/**
 *
 * Classe Responsável pela geração dos dados necessários para o arquivo Especialidades
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoEspecialidade extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 136;
  protected $sNomeArquivo      = 'restosap';
  
  /**
  * Busca os dados para gerar o Arquivo de Especialidades
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */
    $oDadoConfig    = db_stdClass::getDadosInstit();

    $iInstituicaoSessao = db_getsession('DB_instit');

    $sCampos  = "empempenho.e60_codemp, orcdotacao.o58_orgao, orcdotacao.o58_unidade,   			                     ";
    $sCampos .= "conlancam.c70_data, 																								                               ";
    $sCampos .= "(case when conlancamdoc.c71_coddoc in(31,32)       then 'C' 			                                 ";  
    $sCampos .= "      when conlancamdoc.c71_coddoc in(35,36,37,38) then 'P' end) as tipo_movimento,	             ";
    $sCampos .= "to_char(conlancam.c70_data,'YYYYmm') as competencia,                        											 "; 
    $sCampos .= "empempenho.e60_anousu as ano,                                              											 "; 
    $sCampos .= "(case when conlancamdoc.c71_coddoc in(31,35) then c70_valor																       ";
    $sCampos .= "      when conlancamdoc.c71_coddoc = 36 then (c70_valor * -1) else 0 end) as valor_processado,    ";
    $sCampos .= "(case when conlancamdoc.c71_coddoc in(32,37) then c70_valor																       ";
    $sCampos .= "      when conlancamdoc.c71_coddoc = 38 then (c70_valor * -1) else 0 end) as valor_nao_processado ";
    
    $sWhere  = " empresto.e91_anousu  = {$this->iAnoUso} and empempenho.e60_instit = {$iInstituicaoSessao} ";
    $sWhere .= " and conlancamdoc.c71_coddoc in(31,32,35,36,37,38)                     ";
    $sWhere .= " and conlancam.c70_data between cast('{$this->dtDataInicial}' as date) ";
    $sWhere .= "                            and cast('{$this->dtDataFinal}' as date) 	 ";

    $clEmpResto  = db_utils::getDao('empresto');

    $sSqlEmpResto  = $clEmpResto->sql_query_restosPag(null, null, $sCampos, null, $sWhere);
    $sSqlEmpResto = "select e60_codemp, 
                            o58_orgao, 
                            o58_unidade, 
                            c70_data, 
                            tipo_movimento, 
                            competencia, 
                            ano, 
                            sum(valor_processado) as valor_processado, 
                            sum(valor_nao_processado) as valor_nao_processado 
                     from ( $sSqlEmpResto ) as x 
                     group by e60_codemp, 
                            o58_orgao, 
                            o58_unidade, 
                            c70_data, 
                            tipo_movimento, 
                            competencia, 
                            ano";
    $rsEmpResto    = $clEmpResto->sql_record($sSqlEmpResto);
    
    $this->addLog("=====Arquivo".$this->getNomeArquivo()." Erros:\n");
    
    if ($clEmpResto->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for ($i = 0; $i < $clEmpResto->numrows; $i++) {
        $oDadosQuery = db_utils::fieldsMemory($rsEmpResto, $i);
          
        $oDados      = new stdClass();
//        $UnidadeOrcamentaria  = str_pad($oDadosQuery->o58_orgao, 2, '0', STR_PAD_LEFT);
        $UnidadeOrcamentaria = str_pad($oDadosQuery->o58_unidade, 4, ' ', STR_PAD_LEFT);
     


        if ( $oDadosQuery->valor_processado < 0){
           continue;
        }
        if ( $oDadosQuery->valor_nao_processado < 0 ){
           continue;
        }

        $oDados->Cd_Unidade              = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
        $oDados->Dt_Ano                  = $oDadosQuery->ano;
        $oDados->Nu_Empenho              = str_pad($oDadosQuery->e60_codemp, 10, ' ', STR_PAD_RIGHT);
        $oDados->cd_UnidadeOrcamentaria  = str_pad($UnidadeOrcamentaria,      4, ' ', STR_PAD_LEFT);
        $oDados->cd_Orgao                = str_pad($oDadosQuery->o58_orgao,   4, ' ', STR_PAD_LEFT);
        $oDados->Dt_Mov                  = str_pad($this->formataData($oDadosQuery->c70_data), 8, '0', STR_PAD_LEFT);
        $oDados->dt_AnoMes               = str_pad($oDadosQuery->competencia, 6, ' ', STR_PAD_RIGHT);
        $oDados->tp_Mov                  = $oDadosQuery->tipo_movimento;
        $oDados->VL_PAGCANC_RPP          = str_pad($this->formataValor($oDadosQuery->valor_processado), 16, '0', STR_PAD_LEFT);
        $oDados->VL_PAGCANC_RPNP         = str_pad($this->formataValor($oDadosQuery->valor_nao_processado), 16, '0', STR_PAD_LEFT);
          
        $oDados->codigolinha     = 423;

        $this->aDados[] = $oDados;


      }
    } 
    
    $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");
  }
}