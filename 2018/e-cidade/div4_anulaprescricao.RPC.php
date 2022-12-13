<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("classes/db_prescricaolista_classe.php");
require_once("classes/db_arrecant_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arreprescr_classe.php");
require_once("classes/db_prescricao_classe.php");
require_once("classes/db_prescricaoanula_classe.php");
require_once("classes/db_prescricaoanulareg_classe.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();

$oRetorno->status  = 1;
$oRetorno->message = 1;

$lErro     = false;
$sMensagem = "";

$oPrescricaolista    = new cl_prescricaolista();
$oPrescricaoanula    = new cl_prescricaoanula();
$oPrescricaoanulareg = new cl_prescricaoanulareg();
$clPrescricao        = new cl_prescricao();

try {
  
  switch($oParam->exec) {
  
    case 'Consulta' :
      
      $oRetorno->dados = array(); 
      
      $sCampos  = "v01_exerc,v01_numcgm, v01_numpre,v01_numpar,k02_descr,k02_drecei,k30_valor,k30_vlrcorr,k30_vlrjuros,k30_multa,k30_desconto";
      $sCampos .= ",(k30_vlrcorr+k30_vlrjuros+k30_multa-k30_desconto) as total";
      
      $rsDivida = $oPrescricaolista->sql_record($oPrescricaolista->sql_query_divida("", 
                                                                                    $sCampos,
                                                                                    "",
                                                                                    "k61_codigo = {$oParam->k60_codigo} 
                                                                                    and k30_anulado is false"
                                                                                     )
                                                 );
  
      $aDivida = db_utils::getColectionByRecord($rsDivida);
      $oRetorno->dados = $aDivida;	
        
      
      break;
      
      
    case 'Anulacao' :
      
    	/*
    	 * Seta variaveis e objetos
    	 */
      $aObjdeb = $oParam->debitos;
      
      $oArreprescr = new cl_arreprescr();
      $oArrecant   = new cl_arrecant();
      $oArrecad    = new cl_arrecad();
  
      /*
       * Passa por todos as dividas passada pelos checkbox da grid
       * Executa as seguintes aчѕes:
       *   Primeiro antes do foreach, iniciamos a transaчуo;
       *   Alterar o campo k30_anulado da tabela arreprecr para "true" de todos os registros da prescriчуo encontrada;
       *   Move os registros da tabela arrecant  para o arrecad ;
       *   Alterar o campo k31_situacao da tabela prescricao para "2" se todos os checkbox forem selecionados e "3" se for parcial;
       *   Gerar um registro na tabela prescricaoanula;
       *   Gerar o registro na tabela prescricaoanulareg com k30_sequencial e k120_sequencial;
       * 
       * OBS : Nуo deve ser excluэdo os registros da tabela arreprescr;
       */
      
      db_inicio_transacao();
      
      foreach ($aObjdeb as $oDebito){
         
      	
      	/*
      	 * Alterar o campo k30_anulado da tabela arreprecr para "true" de todos os registros da prescriчуo encontrada;
      	 * Seleciona os Arreprescr e depois atualiza o campo k30_anulado para true
      	 */
      	
        $rsArreprescr = $oArreprescr->sql_record($oArreprescr->sql_query_file("","*","","
                                                                                 k30_numpre = {$oDebito->numpre} and 
                                                                                 k30_numpar = {$oDebito->numpar}  
                                                                                 "));   
                                                                                 
        $aArreprescr = db_utils::getColectionByRecord($rsArreprescr);
      
        foreach ($aArreprescr as $oDeb) {
          
          $oArreprescratualiza = new cl_arreprescr();
          
  			  $oArreprescratualiza->k30_sequencial = $oDeb->k30_sequencial; 
  			  $oArreprescratualiza->k30_numpre     = $oDeb->k30_numpre; 
  			  $oArreprescratualiza->k30_numpar     = $oDeb->k30_numpar; 
  			  $oArreprescratualiza->k30_numcgm     = $oDeb->k30_numcgm; 
  			  $oArreprescratualiza->k30_dtoper     = $oDeb->k30_dtoper; 
  			  $oArreprescratualiza->k30_receit     = $oDeb->k30_receit; 
  			  $oArreprescratualiza->k30_hist       = $oDeb->k30_hist; 
  			  $oArreprescratualiza->k30_valor      = $oDeb->k30_valor; 
  			  $oArreprescratualiza->k30_dtvenc     = $oDeb->k30_dtvenc; 
  			  $oArreprescratualiza->k30_numtot     = $oDeb->k30_numtot; 
  			  $oArreprescratualiza->k30_numdig     = $oDeb->k30_numdig; 
  			  $oArreprescratualiza->k30_tipo       = $oDeb->k30_tipo; 
  			  $oArreprescratualiza->k30_tipojm     = $oDeb->k30_tipojm; 
  			  $oArreprescratualiza->k30_prescricao = $oDeb->k30_prescricao; 
  			  $oArreprescratualiza->k30_vlrcorr    = $oDeb->k30_vlrcorr; 
  			  $oArreprescratualiza->k30_vlrjuros   = $oDeb->k30_vlrjuros; 
  			  $oArreprescratualiza->k30_multa      = $oDeb->k30_multa; 
  			  $oArreprescratualiza->k30_desconto   = $oDeb->k30_desconto; 
          $oArreprescratualiza->k30_anulado    = "t";
          $oArreprescratualiza->alterar($oDeb->k30_sequencial);
  
  		    if ( $oArreprescratualiza->erro_status == 0 ) {
  		      throw new Exception($oArreprescratualiza->erro_msg);
  		    }
  		    
        }
        
        
        
        /*
         * Move os registros da tabela arrecant  para o arrecad
         * Usa o mщtodo excluir_arrecant da classe cl_arrecant que faz o seguite:
         *   1- Seleciona os registro da arrecant que tem numpre e numpar
         *   2- Depois inclui um registro na arrecad 
         *   3- Deleta o registro arrecant
         */
          $oAcantExcluir = new cl_arrecant();
          $oAcantExcluir->excluir_arrecant($oDebito->numpre,$oDebito->numpar);
          
          if ( $oAcantExcluir->erro_status == 0 ) {
            throw new Exception($oAcantExcluir->erro_msg);
          }
          
        /*
         * Alterar o campo k31_situacao da tabela prescricao para "2" se todos os checkbox forem selecionados e "3" se 
         * a seleceчуo dos checkbox for parcial;
         * Primeiro define o status da movimentaчуo
         * Depois seleciona o codigo da prescricao que tem o numpre e numpar selecionados
         * Depois atualiza eles com status da movimentacao
         * Status da movimentaчуo:
         *   2 - Anulado (Todos os registros selecionados)
         *   3 - Anulado parcial (Alguns registros selecionados)
         */
        
         $iStatusSituacao = $oParam->linhas == sizeof($aObjdeb)  ? 2 : 3 ;
        
         $sSqlPrescricao  = " select prescricao.k31_codigo 
                                from prescricao 
                                     inner join prescricaolista  on prescricaolista.k122_prescricao = prescricao.k31_codigo 
                                     inner join lista            on lista.k60_codigo                = prescricaolista.k122_lista 
                                     inner join listadeb         on listadeb.k61_codigo             = lista.k60_codigo 
                               where listadeb.k61_numpre = {$oDebito->numpre} 
                                 and listadeb.k61_numpar = {$oDebito->numpar} ";
        
         $rsPrescricaoCodigo = db_query($sSqlPrescricao);
         $aPrescricaoCodigo = db_utils::getColectionByRecord($rsPrescricaoCodigo); 
        
         foreach ($aPrescricaoCodigo as $oPrescricaoCodigo) {
  
           $clPrescricao->k31_codigo   = $oPrescricaoCodigo->k31_codigo;
           $clPrescricao->k31_situacao = $iStatusSituacao;
           $clPrescricao->alterar($oPrescricaoCodigo->k31_codigo);
          
           if ($clPrescricao->erro_status == 0) {
             throw new Exception($clPrescricao->erro_msg);
           }
          
         }        
  	    
  	    /*
  	     * Inseri um registro na tabela prescricaoanula com o campo Observaчуo do formulario
  	     * Usa o objeto $oPrescricaoanula da classe cl_prescricaoanula
  	     * Mщtodo Incluir 
  	     */
  		   $oPrescricaoanula->k120_id_usuario = db_getsession("DB_id_usuario"); 
  		   $oPrescricaoanula->k120_instit     = db_getsession("DB_instit"); 
  		   $oPrescricaoanula->k120_obs        = $oParam->obs; 
  		   $oPrescricaoanula->k120_data       = date("Y-m-d"); 
  		   $oPrescricaoanula->k120_hora       = date("H:i"); 
  	     $oPrescricaoanula->incluir(null);
         	     
         if ( $oPrescricaoanula->erro_status == 0 ) {
         	 throw new Exception($oPrescricaoanula->erro_msg);
         }
  	     
        /*
         * Insere um registro para cada linha da arrecres selecionada na tabela de 
         * ligacao prescricaoanulareg (arrepresc x prescricaoanula)
         * Os dados sуo
         *   k30_sequencial: registros da tabela arreprescr selecionados nos checkbox
         *   k120_sequencial: registro recem inserido        
         */
  	     
  	     foreach ($aArreprescr as $oDeb) {
  	       
  		     $oPrescricaoanulareg->k121_prescricaoanula = $oPrescricaoanula->k120_sequencial;
  		     $oPrescricaoanulareg->k121_arreprescr      = $oDeb->k30_sequencial ;
  		     $oPrescricaoanulareg->incluir(null);
  		     
  	       if ( $oPrescricaoanulareg->erro_status == 0 ) {
  	       	 throw new Exception($oPrescricaoanulareg->erro_msg);
  	       }
  	     }
      }
      
      db_fim_transacao(false);
      
      $oRetorno->message = "Anulaчуo feita com sucesso!";
      
      break;
      
      
    case 'AnulacaoLista' :
      
      
      $oArreprescr = new cl_arreprescr();
      $oArrecant   = new cl_arrecant();
      $oArrecad    = new cl_arrecad();
  
      $sCampos     = "distinct v01_numpre as numpre, v01_numpar as numpar ";
      $rsDivida    = $oPrescricaolista->sql_record($oPrescricaolista->sql_query_divida(null, 
                                                                                       $sCampos,
                                                                                       null,
                                                                                       "    k61_codigo = {$oParam->iCodLista} 
                                                                                        and k30_anulado is false "));
  
      $aDebitosDivida = db_utils::getColectionByRecord($rsDivida);
      
      if (count($aDebitosDivida) == 0 ) {
        throw new Exception('Nenhum registro encontrado!');
      }
      
      /*
       * Passa por todos os dщbitos da lista informada
       * Executa as seguintes aчѕes:
       *   Primeiro antes do foreach, iniciamos a transaчуo;
       *   Alterar o campo k30_anulado da tabela arreprecr para "true" de todos os registros da prescriчуo encontrada;
       *   Move os registros da tabela arrecant  para o arrecad ;
       *   Alterar o campo k31_situacao da tabela prescricao para "2" se todos os checkbox forem selecionados e "3" se for parcial;
       *   Gerar um registro na tabela prescricaoanula;
       *   Gerar o registro na tabela prescricaoanulareg com k30_sequencial e k120_sequencial;
       * 
       * OBS : Nуo deve ser excluэdo os registros da tabela arreprescr;
       */
      
      db_inicio_transacao();
      
      
      foreach ( $aDebitosDivida as $oDebito ) {
        
        /*
         * Alterar o campo k30_anulado da tabela arreprecr para "true" de todos os registros da prescriчуo encontrada;
         * Seleciona os Arreprescr e depois atualiza o campo k30_anulado para true
         */
        
        $rsArreprescr = $oArreprescr->sql_record($oArreprescr->sql_query_file("","*","","
                                                                                 k30_numpre = {$oDebito->numpre} and 
                                                                                 k30_numpar = {$oDebito->numpar}  
                                                                                 "));   
                                                                                 
        $aArreprescr = db_utils::getColectionByRecord($rsArreprescr);
      
        foreach ($aArreprescr as $oDeb){
          
          $oArreprescratualiza = new cl_arreprescr();
          $oArreprescratualiza->k30_sequencial = $oDeb->k30_sequencial; 
          $oArreprescratualiza->k30_numpre     = $oDeb->k30_numpre; 
          $oArreprescratualiza->k30_numpar     = $oDeb->k30_numpar; 
          $oArreprescratualiza->k30_numcgm     = $oDeb->k30_numcgm; 
          $oArreprescratualiza->k30_dtoper     = $oDeb->k30_dtoper; 
          $oArreprescratualiza->k30_receit     = $oDeb->k30_receit; 
          $oArreprescratualiza->k30_hist       = $oDeb->k30_hist; 
          $oArreprescratualiza->k30_valor      = $oDeb->k30_valor; 
          $oArreprescratualiza->k30_dtvenc     = $oDeb->k30_dtvenc; 
          $oArreprescratualiza->k30_numtot     = $oDeb->k30_numtot; 
          $oArreprescratualiza->k30_numdig     = $oDeb->k30_numdig; 
          $oArreprescratualiza->k30_tipo       = $oDeb->k30_tipo; 
          $oArreprescratualiza->k30_tipojm     = $oDeb->k30_tipojm; 
          $oArreprescratualiza->k30_prescricao = $oDeb->k30_prescricao; 
          $oArreprescratualiza->k30_vlrcorr    = $oDeb->k30_vlrcorr; 
          $oArreprescratualiza->k30_vlrjuros   = $oDeb->k30_vlrjuros; 
          $oArreprescratualiza->k30_multa      = $oDeb->k30_multa; 
          $oArreprescratualiza->k30_desconto   = $oDeb->k30_desconto; 
          $oArreprescratualiza->k30_anulado    = "t";
          $oArreprescratualiza->alterar($oDeb->k30_sequencial);
  
          if ( $oArreprescratualiza->erro_status == 0 ) {
            throw new Exception($oArreprescratualiza->erro_msg);
          }
        }
        
        
        
        /*
         * Move os registros da tabela arrecant  para o arrecad
         * Usa o mщtodo excluir_arrecant da classe cl_arrecant que faz o seguite:
         *   1- Seleciona os registro da arrecant que tem numpre e numpar
         *   2- Depois inclui um registro na arrecad 
         *   3- Deleta o registro arrecant
         */
        $oAcantExcluir = new cl_arrecant();
        $oAcantExcluir->excluir_arrecant($oDebito->numpre,$oDebito->numpar);
          
        if ( $oAcantExcluir->erro_status == 0 ) {
          throw new Exception($oAcantExcluir->erro_status);
        }
        
        /*
         * Alterar o campo k31_situacao da tabela prescricao para "2" se todos os checkbox forem selecionados e "3" se 
         * a seleceчуo dos checkbox for parcial;
         * Primeiro define o status da movimentaчуo
         * Depois seleciona o codigo da prescricao que tem o numpre e numpar selecionados
         * Depois atualiza eles com status da movimentacao
         * Status da movimentaчуo:
         *   2 - Anulado (Todos os registros selecionados)
         *   3 - Anulado parcial (Alguns registros selecionados)
         */
        
         $sSqlPrescricao  = " select prescricao.k31_codigo 
                                from prescricao 
                                     inner join prescricaolista  on prescricaolista.k122_prescricao = prescricao.k31_codigo 
                                     inner join lista            on lista.k60_codigo                = prescricaolista.k122_lista 
                                     inner join listadeb         on listadeb.k61_codigo             = lista.k60_codigo 
                               where listadeb.k61_numpre = {$oDebito->numpre} 
                                 and listadeb.k61_numpar = {$oDebito->numpar} ";
        
         $rsPrescricaoCodigo = db_query($sSqlPrescricao);
         $aPrescricaoCodigo = db_utils::getColectionByRecord($rsPrescricaoCodigo); 
        
         foreach ($aPrescricaoCodigo as $oPrescricaoCodigo) {
  
           $clPrescricao->k31_codigo   = $oPrescricaoCodigo->k31_codigo;
           $clPrescricao->k31_situacao = 2;
           $clPrescricao->alterar($oPrescricaoCodigo->k31_codigo);
          
           if ($clPrescricao->erro_status == 0) {
             throw new Exception($clPrescricao->erro_msg);
           }
          
         }
        
        /*
         * Inseri um registro na tabela prescricaoanula com o campo Observaчуo do formulario
         * Usa o objeto $oPrescricaoanula da classe cl_prescricaoanula
         * Mщtodo Incluir 
         */
         $oPrescricaoanula->k120_id_usuario = db_getsession("DB_id_usuario"); 
         $oPrescricaoanula->k120_instit     = db_getsession("DB_instit"); 
         $oPrescricaoanula->k120_obs        = $oParam->obs; 
         $oPrescricaoanula->k120_data       = date("Y-m-d"); 
         $oPrescricaoanula->k120_hora       = date("H:i"); 
         $oPrescricaoanula->incluir(null);
               
         if ( $oPrescricaoanula->erro_status == 0 ) {
           throw new Exception($oPrescricaoanula->erro_msg);
         }
         
        /*
         * Insere um registro para cada linha da arrecres selecionada na tabela de 
         * ligacao prescricaoanulareg (arrepresc x prescricaoanula)
         * Os dados sуo
         *   k30_sequencial: registros da tabela arreprescr selecionados nos checkbox
         *   k120_sequencial: registro recem inserido        
         */
         
         foreach ($aArreprescr as $oDeb){
          
  
           $oPrescricaoanulareg->k121_prescricaoanula = $oPrescricaoanula->k120_sequencial;
           $oPrescricaoanulareg->k121_arreprescr = $oDeb->k30_sequencial ;
           $oPrescricaoanulareg->incluir(null);
           
           if ( $oPrescricaoanulareg->erro_status == 0 ) {
             throw new Exception($oPrescricaoanulareg->erro_msg);
           }
         }
         
      }
      
      db_fim_transacao(false);
      
      $oRetorno->message = "Anulaчуo da prescriчѕes da lista {$oParam->iCodLista} feita com sucesso!";
      
      break;    
      	
  }

} catch (Exception $eException) {
  
  if ( db_utils::inTransaction() ) {
    db_fim_transacao(true);
  }
  
  $oRetorno->status  = 0;
  $oRetorno->message = $eException->getMessage();
  
}

  
echo $oJson->encode($oRetorno);
   
?>