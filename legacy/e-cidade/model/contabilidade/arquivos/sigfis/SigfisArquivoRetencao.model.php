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
require_once ("model/contabilidade/arquivos/sigfis/SigfisVinculoReceita.model.php");

/**
 * Classe que processa as informações para serem inseridas no
 * arquivo Retencao.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoRetencao extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
  protected $iCodigoLayout = 207;
  
	protected $sNomeArquivo  = 'retencao';

	public function gerarDados() {
		
		$oDaoEmpempenho     = db_utils::getDao('empempenho');
		$iInstituicaoSessao = db_getsession('DB_instit');
		$oDadosInstit       = db_stdClass::getDadosInstit();

	  $this->setCodigoLayout(207);
    if( $iAnoSessao < 2013 ){
  	  $this->setCodigoLayout(130);
    }
		
    $sSqlBuscaRetencoes  = " select k12_data,                                                                  ";
//    $sSqlBuscaRetencoes .= "        e50_codord,                                                                ";
    $sSqlBuscaRetencoes .= "        e60_codemp,                                                                ";
    $sSqlBuscaRetencoes .= "        e60_anousu,                                                                ";
    $sSqlBuscaRetencoes .= "        c61_codcon,                                                                ";
    $sSqlBuscaRetencoes .= "        c60_estrut,                                                                ";
//    $sSqlBuscaRetencoes .= "        k02_codigo,                                                                ";
    $sSqlBuscaRetencoes .= "        o58_orgao,                                                                 ";
    $sSqlBuscaRetencoes .= "        o58_unidade,                                                               ";
    $sSqlBuscaRetencoes .= "        to_char(corrente.k12_data, 'YYYYmm') as competencia,                       ";
    $sSqlBuscaRetencoes .= "        round(sum(e23_valorretencao),2) as e23_valorretencao                       ";
    $sSqlBuscaRetencoes .= "   from retencaoreceitas retencao                                                  ";
    $sSqlBuscaRetencoes .= " inner join retencaopagordem         on e20_sequencial      = e23_retencaopagordem ";
    $sSqlBuscaRetencoes .= " inner join pagordem                 on e50_codord          = e20_pagordem         ";
    $sSqlBuscaRetencoes .= " inner join pagordemele              on e50_codord          = e53_codord           ";
    $sSqlBuscaRetencoes .= " inner join empempenho               on e60_numemp          = e50_numemp           ";
    $sSqlBuscaRetencoes .= " inner join orcdotacao               on e60_coddot          = o58_coddot           ";
    $sSqlBuscaRetencoes .= "                                    and e60_anousu          = o58_anousu           ";
    $sSqlBuscaRetencoes .= " inner join retencaotiporec          on e21_sequencial      = e23_retencaotiporec  ";
    $sSqlBuscaRetencoes .= " inner join tabrec                   on e21_receita         = k02_codigo           ";
    $sSqlBuscaRetencoes .= " left  join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial       ";
    $sSqlBuscaRetencoes .= " left  join corgrupocorrente         on k105_sequencial     = e47_corgrupocorrente ";
    $sSqlBuscaRetencoes .= " left  join corrente                 on k105_id             = corrente.k12_id      ";
    $sSqlBuscaRetencoes .= "                                    and k105_autent         = corrente.k12_autent  ";
    $sSqlBuscaRetencoes .= "                                    and k105_data           = corrente.k12_data    ";
    $sSqlBuscaRetencoes .= " left  join conplanoreduz            on corrente.k12_conta  = c61_reduz            ";
    $sSqlBuscaRetencoes .= "                                    and c61_anousu          = {$this->iAnoUso}     ";
    $sSqlBuscaRetencoes .= " left  join conplano                 on c60_codcon          = c61_codcon           ";
    $sSqlBuscaRetencoes .= "                                    and c60_anousu          = c61_anousu           ";
    $sSqlBuscaRetencoes .= "      where e23_ativo is true                                                      ";
    $sSqlBuscaRetencoes .= "        and e60_instit = {$iInstituicaoSessao}                                     ";
    $sSqlBuscaRetencoes .= "        and e60_anousu = {$this->iAnoUso}                                          ";
    $sSqlBuscaRetencoes .= "        and corrente.k12_estorn is false                                           ";
    $sSqlBuscaRetencoes .= "        and corrente.k12_data between '{$this->dtDataInicial}'                     ";
    $sSqlBuscaRetencoes .= "                                       and '{$this->dtDataFinal}'                  ";
    $sSqlBuscaRetencoes .= "        and e23_recolhido is true                                                  ";
    if(substr($this->dtDataInicial,5,2)+0 == 12 && substr($this->dtDataInicial,0,4)+0 == 2012){
      $sSqlBuscaRetencoes .= "        and e60_codemp not in ('309','304','301','291','302')                                           ";
    }
    $sSqlBuscaRetencoes .= " group by k12_data, e60_codemp, e60_anousu, c61_codcon, c60_estrut,     ";
    $sSqlBuscaRetencoes .= "          o58_orgao, o58_unidade, corrente.k12_data                                ";
    $rsSqlBuscaRetencoes = $oDaoEmpempenho->sql_record($sSqlBuscaRetencoes);
// die($sSqlBuscaRetencoes);
    if ($oDaoEmpempenho->numrows > 0) {
    	
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for ($iRetencao = 0; $iRetencao < $oDaoEmpempenho->numrows; $iRetencao++) {
      	
      	$oRetencao = db_utils::fieldsMemory($rsSqlBuscaRetencoes, $iRetencao);
      	
      	/**
      	 * manipulamos a data de vencimento para o formato que o tribunal espera receber
      	 */
      	$aData = explode('-', $oRetencao->k12_data);
      	$iAnoVencimento = $aData[0];
      	$iAnoMesVencimento = $aData[0].$aData[1];
      	$iDataVencimento = $aData[2].$aData[1].$aData[0];
      	
      	/**
      	 * forçando as casas decimais onde o valor da retenção retorna como inteiro
      	 */
      	$fValorDecimal      = db_formatar($oRetencao->e23_valorretencao, 'p');
      	$iValorSemSeparador = str_replace('.', '', $fValorDecimal);
      	
      	/**
      	 * buscamos a conta contábil no XML de configuração
      	 */
      	if ($oContaContabil = SigfisVinculoReceita::getVinculoReceita($oRetencao->c61_codcon)) {
      		$iReceitaTCE = $oContaContabil->receitatce;
      	}
      	
      	$oDadosLinha = new stdClass();
      	$oDadosLinha->cd_Unidade              = str_pad($this->sCodigoTribunal,   4, ' ', STR_PAD_LEFT);
//    	$oDadosLinha->cd_UnidadeOrcamentaria  = str_pad($oRetencao->o58_orgao,    2, '0', STR_PAD_LEFT);
      	$oDadosLinha->cd_UnidadeOrcamentaria  = str_pad($oRetencao->o58_unidade,  4, ' ', STR_PAD_LEFT);
      	$oDadosLinha->nu_Empenho              = str_pad($oRetencao->e60_codemp,  10, ' ', STR_PAD_RIGHT);
      	$oDadosLinha->dt_Ano                  = $iAnoVencimento;
      	$oDadosLinha->dt_AnoCriacao           = $oRetencao->e60_anousu;
      	$oDadosLinha->dt_PagamentoEmpenho     = $iDataVencimento;
      	$oDadosLinha->cd_ContaContabil        = str_pad($oRetencao->c60_estrut, 34, ' ', STR_PAD_RIGHT);
      	$oDadosLinha->vl_Retencao             = str_pad($iValorSemSeparador,     16, ' ', STR_PAD_LEFT);
      	$oDadosLinha->dt_AnoMes               = $iAnoMesVencimento;
      	$oDadosLinha->cd_Orgao                = str_pad($oRetencao->o58_orgao,    4, ' ', STR_PAD_LEFT);
    //	$oDadosLinha->nu_EmpenhoSup           = str_pad(str_repeat(' ', 10),  10, ' ', STR_PAD_LEFT);
      	$oDadosLinha->nu_EmpenhoSup             = str_pad($oRetencao->e60_codemp,  10, ' ', STR_PAD_RIGHT);
        $oDadosLinha->cd_ContaCorrente        = str_pad(str_repeat(' ', 30),  30, ' ', STR_PAD_LEFT);
        if(db_getsession('DB_anousu') < 2013 ){
          $oDadosLinha->codigolinha             = 417;
        }else{
          $oDadosLinha->codigolinha             = 672;
        }
      	$this->aDados[] = $oDadosLinha;
      }
    }
    
    return $this->aDados;
	}
}
?>