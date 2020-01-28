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


require_once ('interfaces/iPadArquivoTxtBase.interface.php');
require_once ('model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php');
/**
 * Classe para geraçao dos dados de decretos, suplementacoes.
 * @author iuri@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 * @version  $Revision: 1.7 $
 */
class SigfisArquivoAlteracaoOrcamentaria extends SigfisArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout = 121;
  
  protected $sNomeArquivo = "AltOrc";
  /**
   * 
   * @see iPadArquivoBase::gerarDados()
   */
  public function gerarDados() {

    if (empty($this->sCodigoTribunal)) {
      throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
    }
    
    $this->addLog("==== Iniciando geraçao dos dados de {$this->getNomeArquivo()}\n");
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $sSqlDecreto  = " select o45_numlei    as num_lei,                                                                ";
    $sSqlDecreto .= "        o45_dataini   as data_lei, 				                                                      ";
    $sSqlDecreto .= "        o45_descr     as descricao_lei,	                                                        ";
    $sSqlDecreto .= "        o39_numero    as num_decreto, 			                                                      ";    
    $sSqlDecreto .= "        o39_data      as data_decreto, 		                                                      ";
    $sSqlDecreto .= "        o39_descr     as descricao,    		                                                      ";
    $sSqlDecreto .= "        o46_codsup    as codsup, 					                                                      ";
    $sSqlDecreto .= "        o46_tiposup   as tipo_credito, 		                                                      ";
    $sSqlDecreto .= "        o46_obs       as sinopse, 				                                                        ";
    $sSqlDecreto .= "        o58_coddot    as dotacao, 				                                                        ";
    $sSqlDecreto .= "        o58_projativ  as projativ,					                                                      ";
    $sSqlDecreto .= "        o55_tipo      as tipo_projeto,			                                                      ";
    $sSqlDecreto .= "        o58_funcao    as funcao,						                                                      ";
    $sSqlDecreto .= "        o58_orgao     as orgao, 																																  ";
    $sSqlDecreto .= "        o58_subfuncao as subfuncao,				                                                      ";
    $sSqlDecreto .= "        o58_unidade   as unidade,					                                                      ";
    $sSqlDecreto .= "        o56_elemento  as elemento,					                                                      ";
    $sSqlDecreto .= "        o58_codigo    as recurso,					                                                      ";
    $sSqlDecreto .= "        o58_programa  as programa,					                                                      ";
    $sSqlDecreto .= "        o47_valor     as valor_suplementado,		                                                  ";
    $sSqlDecreto .= "        to_char(o49_data,'YYYYmm') as competencia,                                               ";
    $sSqlDecreto .= "        o49_data      as data_alteracao                                                          ";
    $sSqlDecreto .= "   from orcprojeto  												                                                      ";
    $sSqlDecreto .= "        inner join orclei              on o45_codlei              = o39_codlei 								  ";
    $sSqlDecreto .= "        inner join orcsuplem as suplem on o46_codlei              = orcprojeto.o39_codproj 		  ";
    $sSqlDecreto .= "        inner join orcsuplemval        on o47_codsup              = o46_codsup                   ";
    $sSqlDecreto .= "        inner join orcdotacao d        on o58_coddot              = o47_coddot                   ";
    $sSqlDecreto .= "                                      and o58_anousu              =".db_getsession("DB_anousu"); 
    $sSqlDecreto .= "        inner join orcprojativ         on o55_projativ            = o58_projativ                 ";
    $sSqlDecreto .= "                                      and o55_anousu              = o58_anousu					          ";                                            
    $sSqlDecreto .= "        inner join orcsuplemlan        on orcsuplemlan.o49_codsup = o46_codsup                   ";
    $sSqlDecreto .= "        inner join orcelemento         on o56_codele              = o58_codele                   ";
    $sSqlDecreto .= "                                      and o56_anousu              = o58_anousu                   ";
    $sSqlDecreto .= "        left join orcsuplemretif       on o48_retificado          = orcprojeto.o39_codproj       ";
    $sSqlDecreto .= "  where o39_anousu=".db_getsession("DB_anousu") . " and o58_instit in ({$sListaInstit})          ";
    $sSqlDecreto .= "    and o49_data between '{$this->dtDataInicial}' and '{$this->dtDataFinal}'										  ";
    $sSqlDecreto .= "  order by o45_numlei,o45_dataini,o39_numero,o39_data 																					  ";

//    die( $sSqlDecreto );
    
    $rsDecretos   = db_query($sSqlDecreto);
    
    for ($iSuplementacao = 0; $iSuplementacao < pg_num_rows($rsDecretos); $iSuplementacao++) {
      
      $oDadosSuplementacao = db_utils::fieldsMemory($rsDecretos, $iSuplementacao);
      
      $iTipoSuplementacao = 0;
      if ( $this->formataValor($oDadosSuplementacao->valor_suplementado ) < 0 ) {
        $iTipoSuplementacao = 19;
      } else {

        switch ($oDadosSuplementacao->tipo_credito) {
          
          case 1001:
            
            $iTipoSuplementacao = 6;
            break;
            
          case 1002:
            
            $iTipoSuplementacao = 15;
            break;

          case 1003:
            
            $iTipoSuplementacao = 12;
            break;
            
          case 1004:
            
            $iTipoSuplementacao = 9;
            break;
                
          case 1005:
            
            $iTipoSuplementacao = 18;
            break;

         case 1006:
            
            $iTipoSuplementacao = 4;
            break;   
            
         case 1007:
            
            $iTipoSuplementacao = 13;
            break;    
            
         case 1008:
            
            $iTipoSuplementacao = 10;
            break;

         case 1009:
            
            $iTipoSuplementacao = 7;
            break;
                
         case 1010:
            
            $iTipoSuplementacao = 16;
            break;
               
         case 1011:
            
            $iTipoSuplementacao = 5;
            break;   
            
         case 1012:
            
            $iTipoSuplementacao = 21;
            break;

         case 1013:
            
            $iTipoSuplementacao = 22;
            break;

         case 1014:
            
            $iTipoSuplementacao = 20;
            break;

          case 1015:
            
            $iTipoSuplementacao = 20;
            break;
            
          case 1016:
            
            $iTipoSuplementacao = 20;
            break;   
        }

      }
      
      if ($iTipoSuplementacao != '0') { 
        
        if ($oVinculo = SigfisVinculoRecurso::getVinculoRecurso($oDadosSuplementacao->recurso)) {
        
          $oSuplementacao                          = new stdClass();
          
          $sDescricao           = str_replace("\n", " ", $oDadosSuplementacao->descricao);
          $sDescricao           = str_replace("\r", " ", $sDescricao);
//          $UnidadeOrcamentaria  = str_pad($oDadosSuplementacao->orgao, 2, '0', STR_PAD_LEFT);
          $UnidadeOrcamentaria = str_pad($oDadosSuplementacao->unidade, 4, ' ', STR_PAD_LEFT);
          
          $dtDataLei            = $this->formataData($oDadosSuplementacao->data_lei);
          $dtDataDecreto        = $this->formataData($oDadosSuplementacao->data_decreto);
          $dtDataAlteracao      = $this->formataData($oDadosSuplementacao->data_alteracao);
          
          $nValorSuplementacao  = abs( $this->formataValor($oDadosSuplementacao->valor_suplementado) );
          
          $oSuplementacao->codigolinha             = 408;
          $oSuplementacao->cd_Unidade              = str_pad($this->sCodigoTribunal,             4, '0', STR_PAD_LEFT); 
          $oSuplementacao->nu_ProjetoAtividade     = str_pad($oDadosSuplementacao->projativ,     4, "0", STR_PAD_LEFT);
          $oSuplementacao->tp_ProjetoAtividade     = str_pad($oDadosSuplementacao->tipo_projeto, 1, "0", STR_PAD_LEFT);
          $oSuplementacao->tp_Fundamento           = 1;
          $oSuplementacao->tp_Alteracao            = str_pad($iTipoSuplementacao, 2, ' ', STR_PAD_LEFT);
          $oSuplementacao->nu_Fundamento           = str_pad(substr($sDescricao,0,16), 16, ' ', STR_PAD_RIGHT);
          $oSuplementacao->cd_UnidadeOrcamentaria  = str_pad($UnidadeOrcamentaria,4, ' ', STR_PAD_LEFT);
          $oSuplementacao->cd_Elemento             = str_pad(substr($oDadosSuplementacao->elemento, 1, 8), 8, ' ', 
                                                             STR_PAD_LEFT); 
          $oSuplementacao->cd_FonteRecurso         = str_pad($oDadosSuplementacao->recurso,   4, ' ', STR_PAD_LEFT);
          $oSuplementacao->cd_Funcao               = str_pad($oDadosSuplementacao->funcao,    2, ' ', STR_PAD_LEFT);
          $oSuplementacao->dt_Ano                  = $this->iAnoUso; 
          $oSuplementacao->cd_Programa             = str_pad($oDadosSuplementacao->subfuncao, 4, ' ', STR_PAD_LEFT);
          $oSuplementacao->cd_SubPrograma          = str_pad($oDadosSuplementacao->programa,  4, ' ', STR_PAD_LEFT);
          $oSuplementacao->dt_Alteracao            = $dtDataAlteracao;
          $oSuplementacao->vl_Alteracao            = str_pad($nValorSuplementacao,  16, '0', STR_PAD_LEFT);
          $oSuplementacao->dt_AnoMes               = $oDadosSuplementacao->competencia;
          $oSuplementacao->dt_Fundamento           = $dtDataDecreto;
          $oSuplementacao->de_LeisAutorizativas    = str_pad(substr($oDadosSuplementacao->descricao_lei,0,100), 100, ' ',
                                                             STR_PAD_RIGHT);
          $oSuplementacao->nu_leiautorizativa      = str_pad($oDadosSuplementacao->num_lei, 10, ' ', STR_PAD_RIGHT);
          $oSuplementacao->dt_leiautorizativa      = $dtDataLei;
          $oSuplementacao->cd_Orgao                = str_pad($oDadosSuplementacao->orgao, 4, ' ', STR_PAD_LEFT);
          $oSuplementacao->nu_DiarioOficial        = $dtDataAlteracao;
  
          $this->aDados[] = $oSuplementacao;
        } else {
          
          $sErroLog  = "Dotação ". str_pad($oDadosSuplementacao->dotacao, 6, ' ', STR_PAD_LEFT) ;
          $sErroLog .= " de Recurso ". str_pad($oDadosSuplementacao->recurso, 6, ' ', STR_PAD_LEFT) ;
          $sErroLog .= " não possui vínculo com os recursos do Sigfis. \n";
          $this->addLog($sErroLog);
        }
      } else {
        
        $sErroLog  = "Tipo de crédito {$oDadosSuplementacao->tipo_credito} sem vinculo com Sigfs \n";
        $this->addLog($sErroLog);
      }
    }                                                          
  }                                                            
}                                                              
                                                               
?>