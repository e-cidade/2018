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

class SigfisArquivoFolha extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 200;
  protected $sNomeArquivo      = 'FolhaPgt';
  protected $aMovimentoContabil = array();
  
  /**
  * Busca os dados para gerar o Arquivo Diversos
  */
  public function gerarDados() {

		$iInstituicaoSessao = db_getsession('DB_instit');

    $oDadoConfig    = db_stdClass::getDadosInstit();
    $clConLanCamEmp = db_utils::getDao('conlancamemp');

    $sCampos  = " case when e50_obs ilike '%janeiro%'   then 1    ";
    $sCampos .= "      when e50_obs ilike '%fevereiro%' then 2      ";
    $sCampos .= "      when e50_obs ilike '%março%'     then 3      ";
    $sCampos .= "      when e50_obs ilike '%abril%'     then 4      ";
    $sCampos .= "      when e50_obs ilike '%maio%'      then 5      ";
    $sCampos .= "      when e50_obs ilike '%junho%'    or e50_obs ilike'%6/2012%'  then 6      ";
    $sCampos .= "      when e50_obs ilike '%julho%'    or e50_obs ilike'%7/2012%'  then 7      ";
    $sCampos .= "      when e50_obs ilike '%agosto%'   or e50_obs ilike'%8/2012%'  then 8      ";
    $sCampos .= "      when e50_obs ilike '%setembro%' or e50_obs ilike'%9/2012%'  then 9      ";
    $sCampos .= "      when e50_obs ilike '%outubro%'  or e50_obs ilike'%10/2012%' then 10     ";
    $sCampos .= "      when e50_obs ilike '%novembro%' or e50_obs ilike'%11/2012%' then 11     ";
    $sCampos .= "      when e50_obs ilike '%dezembro%' or e50_obs ilike'%12/2012%' then 12     ";
    $sCampos .= "      else 0 end as mes ,";
    $sCampos .= "(case length(z01_cgccpf) when 14 then 2 when 11 then 1 end ) as tipo_pessoa, ";
    $sCampos .= "pagordem.e50_obs, z01_cgccpf, z01_nome, e50_data,         ";
    $sCampos .= "e60_codemp, e60_anousu, c70_data, orcdotacao.o58_orgao,           ";
    $sCampos .= "min(pagordem.e50_codord) as e50_codord, to_char(max(conlancam.c70_data),'YYYYmm') as competencia, orcdotacao.o58_unidade,   	         ";
    $sCampos .= "sum(case c53_tipo when 30 then conlancam.c70_valor  																	                         ";
    $sCampos .= "							 when 31 then (conlancam.c70_valor * -1) end) as valor_pago                         ";
    
    $sWhere   = " conlancam.c70_anousu = {$this->iAnoUso} and empempenho.e60_instit = {$iInstituicaoSessao}                    ";
    $sWhere  .= " and empempenho.e60_anousu = {$this->iAnoUso}                                                                 ";
    $sWhere  .= " and conhistdoc.c53_tipo in (30,31)                                                                           ";
    $sWhere  .= " and z01_numcgm in (200711)                                                                                   ";
    $sWhere  .= " and conlancam.c70_data between cast('{$this->dtDataInicial}' as date) and cast('{$this->dtDataFinal}' as date) ";
    $sWhere  .= " group by e60_codemp, z01_numcgm, z01_cgccpf, z01_nome, e50_data, pagordem.e50_obs, empempenho.e60_anousu, c70_data, orcdotacao.o58_orgao, ";
    $sWhere  .= " orcdotacao.o58_unidade";

    //$sWhere  .= " having max(conlancam.c70_data) between cast('{$this->dtDataInicial}' as date) and cast('{$this->dtDataFinal}' as date) ";
    
    $sOrdem   = "e60_codemp";
    
    $sSqlConLanCamEmp = $clConLanCamEmp->sql_query_pagamentoEmpenho(null , $sCampos, $sOrdem, $sWhere);

// die($sSqlConLanCamEmp);

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

          $mesReferencia = ( $oDadosQuery->mes == 0 ? substr($oDadosQuery->competencia,4,2) : $oDadosQuery->mes );
  //        $mesReferencia = $oDadosQuery->mes;
          $anoReferencia = substr($oDadosQuery->competencia,0,4);
          
          $oDados->cd_Unidade             = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
          $oDados->cd_UnidadeOrcamentaria = str_pad($sUnidadeOrcamentaria,     4, ' ', STR_PAD_LEFT); 
          $oDados->nu_Empenho             = str_pad($oDadosQuery->e60_codemp, 10, ' ', STR_PAD_RIGHT);
          $oDados->dt_PagamentoEmpenho    = $dtPagamento;
          $oDados->dt_AnoReferencia       = $anoReferencia;
          $oDados->dt_MesReferencia       = str_pad($mesReferencia,2,'0',STR_PAD_LEFT);
          $oDados->dt_Ano                 = $oDadosQuery->e60_anousu;
          $oDados->de_Folha               = str_pad(substr($sObServacao, 0, 120) , 120, ' ', STR_PAD_RIGHT);
          $oDados->vl_Folha               = str_pad($this->formataValor($oDadosQuery->valor_pago), 16, ' ', STR_PAD_LEFT);
          $oDados->dt_AnoMes              = $oDadosQuery->competencia;
          $oDados->cd_Orgao               = str_pad($oDadosQuery->o58_orgao,   4, ' ', STR_PAD_LEFT);
/*

          $oDados->nm_Emitente             = str_pad(substr($oDadosQuery->z01_nome, 0, 30), 100, ' ', STR_PAD_RIGHT);
          $oDados->tp_PessoaEmitente       = $oDadosQuery->tipo_pessoa;
          $oDados->nm_Documento            = str_pad(substr($oDadosQuery->e50_codord,0,50), 50, ' ', STR_PAD_RIGHT);
          $oDados->dt_Emissao              = $dtEmissao;
          $oDados->dt_AnoMes              = $oDadosQuery->competencia;
          $oDados->nu_EmpenhoSup           = str_pad(str_repeat(' ', 10), 10, ' ', STR_PAD_LEFT);
          
          $oDados->dt_AnoMes              = $oDadosQuery->competencia;
          $oDados->cd_Orgao               = str_pad($oDadosQuery->o58_orgao,   4, ' ', STR_PAD_LEFT);
          $oDados->nu_EmpenhoSup          = str_pad(str_repeat(' ', 10), 10, ' ', STR_PAD_LEFT);
          $oDados->Reservado_tce          = str_repeat(' ', 41);
   //       $oDados->Cd_ContaCorrente1      = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_LEFT);
   //       $oDados->Cd_ContaCorrente2      = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_LEFT);
   //       $oDados->Cd_ContaCorrente3      = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_LEFT);
*/
          $oDados->codigolinha            = 646;
  
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