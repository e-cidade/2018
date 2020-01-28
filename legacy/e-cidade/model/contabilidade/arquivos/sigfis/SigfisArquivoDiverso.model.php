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
 * Classe Responsável pela geração dos dados necessários para o arquivo Diversos 
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoDiverso extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 132;
  protected $sNomeArquivo      = 'DocDiver';
  protected $aMovimentoContabil = array();
  
  
  /**
  * Busca os dados para gerar o Arquivo Diversos
  */
  public function gerarDados() {

		$iInstituicaoSessao = db_getsession('DB_instit');

    $oDadoConfig    = db_stdClass::getDadosInstit();
    $clConLanCamEmp = db_utils::getDao('conlancamemp');

    $sCampos  = "(case length(cgm.z01_cgccpf) when 14 then 2 when 11 then 1 end ) as tipo_pessoa,                                  ";
    $sCampos .= "max(pagordem.e50_obs) as e50_obs, z01_cgccpf, z01_nome, max(e50_data) as e50_data,                                                                 ";
    $sCampos .= "empempenho.e60_codemp, empempenho.e60_anousu, max(conlancam.c70_data) as c70_data, orcdotacao.o58_orgao,                           ";
    $sCampos .= "min(pagordem.e50_codord) as e50_codord, to_char(max(conlancam.c70_data),'YYYYmm') as competencia, orcdotacao.o58_unidade, ";
    $sCampos .= "sum(case c53_tipo when 30 then conlancam.c70_valor  																	                             ";
    $sCampos .= "							 when 31 then (conlancam.c70_valor * -1) end) as valor_pago                                          ";
    
    $sWhere   = " conlancam.c70_anousu = {$this->iAnoUso} and empempenho.e60_instit = {$iInstituicaoSessao}                        ";
    $sWhere  .= " and empempenho.e60_anousu = {$this->iAnoUso}                                                                     ";
    $sWhere  .= " and conhistdoc.c53_tipo in (30,31)                                                                               ";
    $sWhere  .= " and z01_numcgm in (160078, 202939, 203563, 200638, 10852, 201001, 201979, 203021, 162394, 92713, 201995,         ";
    $sWhere  .= " 200335, 92809, 201489, 161799, 202301, 210788, 201300, 200301, 161857,201668, 201640, 161795, 12435, 161850,     ";
    $sWhere  .= " 211961, 202895, 205391, 213061, 201970, 161861, 210811, 201627, 200933, 11670, 200521, 201732, 201733, 162130)   ";
    $sWhere  .= " and conlancam.c70_data between cast('{$this->dtDataInicial}' as date) and cast('{$this->dtDataFinal}' as date)   ";
    $sWhere  .= " group by empempenho.e60_codemp, z01_numcgm, z01_cgccpf, z01_nome,      ";
    $sWhere  .= " empempenho.e60_anousu, orcdotacao.o58_orgao, ";
    $sWhere  .= " orcdotacao.o58_unidade";

    //$sWhere  .= " having max(conlancam.c70_data) between cast('{$this->dtDataInicial}' as date) and cast('{$this->dtDataFinal}' as date) ";
    
    $sOrdem   = "e60_codemp, c70_data";
    
    $sSqlConLanCamEmp = $clConLanCamEmp->sql_query_pagamentoEmpenho(null , $sCampos, $sOrdem, $sWhere);

//    $sSqlConlanCamEmp = "select tipo_pessoa, z01_cgccpf , z01_nome, max(e50_data) as e50_data, e60_codemp, e60_anousu, 
//                                max(e50_obs) as e50_obs,  max(c70_data) as c70_data, o58_orgao , 
//                                max(e50_codord) as e50_codord, competencia, o58_unidade, sum(valor_pago) as valor_pago 
//                         from ($sSqlConLanCamEmp1) as xyxy 
//                         group by tipo_pessoa, z01_cgccpf , z01_nome, e50_data, e60_codemp, e60_anousu, o58_orgao, competencia, o58_unidade ";

//die($sSqlConLanCamEmp);

    // = $clConLanCamEmp->sql_query_pagamentoEmpenho(null , $sCampos, $sOrdem, $sWhere);
    $rsConLanCamEmp    = $clConLanCamEmp->sql_record($sSqlConLanCamEmp);
    
    $this->addLog("=====Arquivo".$this->getNomeArquivo()." Erros:\n");
    
    if ($clConLanCamEmp->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for ($i = 0; $i < $clConLanCamEmp->numrows; $i++) {
        
        $oDadosQuery = new stdClass();
        $oDadosQuery = db_utils::fieldsMemory($rsConLanCamEmp, $i);
        
        if ($oDadosQuery->valor_pago == 0 ){
           continue;
        }

        /**
         * Verifica se a Conta retornada possui vinculo com a conta do Sigfis
         */
//        if ($oVinculo = SigfisVinculoConta::getVinculoConta($oDadosQuery->c61_codcon)) {
          
          $sObServacao = str_replace("\n", " ", $oDadosQuery->e50_obs);
          $sObServacao = str_replace("\r", " ", $sObServacao);
          
          $oDados                = new stdClass();
//          $sUnidadeOrcamentaria  = str_pad($oDadosQuery->o58_orgao, 2, '0', STR_PAD_LEFT);
          $sUnidadeOrcamentaria = str_pad($oDadosQuery->o58_unidade,4, ' ', STR_PAD_LEFT);
          
          $dtPagamento           = $this->formataData($oDadosQuery->c70_data);
          $dtEmissao   = $this->formataData($oDadosQuery->e50_data);
          
          $oDados->Cd_Unidade             = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
          $oDados->Cd_UnidadeOrcamentaria = str_pad($sUnidadeOrcamentaria,     4, ' ', STR_PAD_LEFT); 
          $oDados->Nu_Empenho             = str_pad($oDadosQuery->e60_codemp, 10, ' ', STR_PAD_RIGHT);
          $oDados->Dt_PagamentoEmpenho    = $dtPagamento;
          $oDados->Nu_Documento           = str_pad($oDadosQuery->e60_codemp, 10, ' ', STR_PAD_RIGHT);
          $oDados->Dt_Ano                 = $oDadosQuery->e60_anousu;
          $oDados->cd_CICEmitente          = str_pad($oDadosQuery->z01_cgccpf, 14, ' ', STR_PAD_RIGHT);
          $oDados->nm_Emitente             = str_pad(substr($oDadosQuery->z01_nome, 0, 30), 100, ' ', STR_PAD_RIGHT);
          $oDados->Tp_PessoaEmitente       = $oDadosQuery->tipo_pessoa;
          $oDados->de_ObjetoDocumento      = str_pad(substr($sObServacao, 0, 100) , 200, ' ', STR_PAD_RIGHT);
          $oDados->nm_Documento            = str_pad(substr($oDadosQuery->e50_codord,0,50), 50, ' ', STR_PAD_RIGHT);
          $oDados->dt_Emissao              = $dtEmissao;
          $oDados->vl_Documento           = str_pad($this->formataValor($oDadosQuery->valor_pago), 16, ' ', STR_PAD_LEFT);
          $oDados->dt_AnoMes              = $oDadosQuery->competencia;
          $oDados->cd_Orgao               = str_pad($oDadosQuery->o58_orgao,   4, ' ', STR_PAD_LEFT);
          $oDados->nu_EmpenhoSup           = str_pad(str_repeat(' ', 10), 10, ' ', STR_PAD_LEFT);
          
          $aContas = array_unique((explode(',',$oDadosQuery->c60_estrut)));

          $aContasNovas = array();
          foreach ($aContas as $a) {
            if (!empty($a)) {            
              $aContasNovas[] = $a;
            }
          }
          $aContas = $aContasNovas;

          $oDados->cd_ContaContabil1      = str_pad($aContas[0], 34, ' ', STR_PAD_RIGHT);
          $oDados->cd_ContaContabil2      = str_pad($aContas[1], 34, ' ', STR_PAD_RIGHT); // str_repeat(' ', 34); // Não usado no e-cidada
          $oDados->cd_ContaContabil3      = str_pad($aContas[2], 34, ' ', STR_PAD_RIGHT); // str_repeat(' ', 34); // Não usado no e-cidada


          
          $oDados->dt_AnoMes              = $oDadosQuery->competencia;
          $oDados->cd_Orgao               = str_pad($oDadosQuery->o58_orgao,   4, ' ', STR_PAD_LEFT);
          $oDados->nu_EmpenhoSup          = str_pad(str_repeat(' ', 10), 10, ' ', STR_PAD_LEFT);
          $oDados->Reservado_tce          = str_repeat(' ', 41);
   //       $oDados->Cd_ContaCorrente1      = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_LEFT);
   //       $oDados->Cd_ContaCorrente2      = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_LEFT);
   //       $oDados->Cd_ContaCorrente3      = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_LEFT);
          $oDados->codigolinha            = 419;
  
          $this->aDados[] = $oDados;
/*
        } else {
          $sErroLog  = "Estrutural {$oDadosQuery->c60_estrut} - Conta{$oDadosQuery->e50_codord} -> ";
          $sErroLog .= "sem Vinculo com plano do SIGFIS - Conta *NÃO* Adicionada ao Arquivo.\n";
          $this->addLog($sErroLog);
        } */
      }
    }
    
    $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");
  }





}