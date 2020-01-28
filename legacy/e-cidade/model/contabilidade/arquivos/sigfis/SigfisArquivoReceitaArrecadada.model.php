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
 * Classe Responsável pela geração dos dados necessários para o arquivo Receita Arrecadada
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoReceitaArrecadada extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 122;
  protected $sNomeArquivo      = 'RecLanc';
  
  /**
  * Busca os dados para gerar o Arquivo do Receita Arrecadada
  */
  public function gerarDados() {
  
    /**
     * Busca os dados da db_config
     */
    $oDbConfig        = new db_stdClass();
    $oDadoConfig      = $oDbConfig->getDadosInstit();
                     
    $iInstituicaoSessao = db_getsession('DB_instit');

    $clConLanCamRec   = db_utils::getDao('conlancamrec');

    $sCampos          = "conlancam.c70_valor, extract(month from c70_data) as mes, orcfontes.o57_fonte, c74_codlan, ";
    $sCampos         .= "orcfontes.o57_codfon, "; 
    $sCampos         .= "case conlancamdoc.c71_coddoc when 100 then 1 ";
    $sCampos         .= "                             when 101 then 2 ";
    $sCampos         .= "                             else 0 end as codigo_documento ";
    $sOrder           = "conlancamrec.c74_codlan";
    $sWhere           = "conlancamrec.c74_anousu = {$this->iAnoUso} and orcreceita.o70_instit = {$iInstituicaoSessao} ";
    $sWhere          .= "and conlancamrec.c74_data between '{$this->dtDataInicial}' and '{$this->dtDataFinal}'";
    $sSqlConLanCamRec = $clConLanCamRec->sql_query_conPlanoCodDoc(null, $sCampos, $sOrder, $sWhere);

    $sSqlConLanCamRec = "select sum(c70_valor)as c70_valor
                                , mes, 
                                case when substr(o57_fonte,1,1) = '9'
                                     then '99'||substr(o57_fonte,3,13)
                                     else o57_fonte
                                end as o57_fonte, 
                                o57_codfon, 
                                case when substr(o57_fonte,1,1) = '9' and codigo_documento = 1 
                                     then 2
                                     else case  when substr(o57_fonte,1,1) = '9' and codigo_documento = 2
                                       then 1 else codigo_documento
                                     end
                                end as codigo_documento
                         from ( $sSqlConLanCamRec ) as x 
                         group by mes, o57_fonte, o57_codfon, o57_fonte, codigo_documento 
                         order by o57_fonte";

    $rsConLanCamRec   = $clConLanCamRec->sql_record($sSqlConLanCamRec);
//    die("$sSqlConLanCamRec");
    
    $this->addLog("=====Arquivo: ".$this->getNomeArquivo()." Erros:\n");
    if ($clConLanCamRec->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for ($i = 0; $i < $clConLanCamRec->numrows; $i++) {
        
        $oDadosQuery = new stdClass();
        $oDadosQuery = db_utils::fieldsMemory($rsConLanCamRec, $i);
        $oDados      = new stdClass();
        
        if ($oVinculo = SigfisVinculoReceita::getVinculoReceita($oDadosQuery->o57_codfon)) {
        
          $oDados->tp_AtualizacaoReceitaLancada = $oDadosQuery->codigo_documento;
          $oDados->cd_unidade                   = str_pad($this->sCodigoTribunal,              4, ' ', STR_PAD_LEFT);
//          $oDados->cd_ItemReceita               = str_pad(substr($oVinculo->receitatce, 0, 8), 8, ' ', STR_PAD_LEFT);
          $oDados->cd_ItemReceita               = str_pad(substr($oDadosQuery->o57_fonte, 1, 8), 8, ' ', STR_PAD_LEFT);
          $oDados->dt_AnoMes                    = $this->iAnoUso . str_pad($oDadosQuery->mes,    2, '0', STR_PAD_LEFT);
          $oDados->vl_Lancamento                = str_pad(number_format($oDadosQuery->c70_valor, 2, '',''),   16, ' ', STR_PAD_LEFT); 
          
          $oDados->codigolinha     = 409;
          
          $this->aDados[] = $oDados;  
        } else {
        
          $sErroLog  = "Receita {$oDadosQuery->o57_fonte} do ano de {$this->iAnoUso} ";
          $sErroLog .= "não tem vinculo com Receita Sigfis.\n";
          $this->addLog($sErroLog);
        }
      } 
    }
    
    $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");
  }
}