<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("classes/db_prescricaoanula_classe.php");
require_once("classes/db_prescricaoanulareg_classe.php");

$oJson           = new services_json();
$oParam          = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
//$oParamFiltros   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["jsonFiltros"])));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro = false;
$sMensagem = "";
$oPrescricaolista = new cl_prescricaolista();
$oPrescricaoanula = new cl_prescricaoanula();
$oPrescricaoanulareg = new cl_prescricaoanulareg();

switch($oParam->exec) {

  case 'Consulta' :
    
    $oRetorno->dados = array(); 

    if ($oParam->sCgm != "") {
      $sInner = "inner join arrenumcgm on arrenumcgm.k00_numpre = divida.v01_numpre";
      $sWhere = "k00_numcgm = {$oParam->sCgm} and ";
    }
    if ($oParam->sMatricula != "") {
      $sInner = "inner join arrematric on arrematric.k00_numpre = divida.v01_numpre";
      $sWhere = "k00_matric = {$oParam->sMatricula} and ";
    }
    if ($oParam->sInscricao != "") {
      $sInner = "inner join arreinscr on arreinscr.k00_numpre = divida.v01_numpre";
      $sWhere = "k00_inscr = {$oParam->sInscricao} and ";
    }
    if (isset($oParam->sListaExercicios) && $oParam->sListaExercicios != "") {
      $sWhere .= "v01_exerc in ({$oParam->sListaExercicios}) and ";
    }

    $sSql  = "   select distinct v01_exerc,                                                    "; 
    $sSql .= "          v01_numpre,                                                            ";
    $sSql .= "          v01_numpar,                                                            ";
    $sSql .= "          k02_codigo,                                                            ";
    $sSql .= "          k02_descr,                                                             ";
    $sSql .= "          k02_drecei,                                                            ";
    $sSql .= "          k30_valor,                                                             ";
    $sSql .= "          k30_vlrcorr,                                                           ";
    $sSql .= "          k30_vlrjuros,                                                          ";
    $sSql .= "          k30_multa,                                                             ";
    $sSql .= "          k30_desconto,                                                          ";
    $sSql .= "         (k30_vlrcorr+k30_vlrjuros+k30_multa-k30_desconto) as total              ";
    $sSql .= "    from arreprescr                                                              "; 
    $sSql .= "         inner join tabrec     on tabrec.k02_codigo     = arreprescr.k30_receit  "; 
    $sSql .= "         inner join divida     on divida.v01_numpre     = arreprescr.k30_numpre  ";
    $sSql .= "                              and divida.v01_numpar     = arreprescr.k30_numpar  ";
    $sSql .= "         inner join arreinstit on arreinstit.k00_numpre = arreprescr.k30_numpre  ";
    $sSql .= "  {$sInner}                                                                      ";
    $sSql .= " where arreinstit.k00_instit = ".db_getsession('DB_instit')." and                ";
    $sSql .= "  {$sWhere}                                                                      ";
    $sSql .= "  k30_anulado is false    order by  v01_exerc,  v01_numpre, v01_numpar           ";    

    
    $rsDivida = db_query($sSql);

    $aDivida = db_utils::getCollectionByRecord($rsDivida);
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
                                                                               k30_numpar = {$oDebito->numpar} and
                                                                               k30_receit = {$oDebito->receita}
                                                                               "));   
      
      $aArreprescr = db_utils::getCollectionByRecord($rsArreprescr);
      foreach ($aArreprescr as $oDeb){
       
        $oArreprescratualiza = new cl_arreprescr();
        $oArreprescratualiza->k30_sequencial = $oDeb->k30_sequencial; 
        $oArreprescratualiza->k30_numpre = $oDeb->k30_numpre; 
        $oArreprescratualiza->k30_numpar = $oDeb->k30_numpar; 
        $oArreprescratualiza->k30_numcgm = $oDeb->k30_numcgm; 
        $oArreprescratualiza->k30_dtoper = $oDeb->k30_dtoper; 
        $oArreprescratualiza->k30_receit = $oDeb->k30_receit; 
        $oArreprescratualiza->k30_hist = $oDeb->k30_hist; 
        $oArreprescratualiza->k30_valor = $oDeb->k30_valor; 
        $oArreprescratualiza->k30_dtvenc = $oDeb->k30_dtvenc; 
        $oArreprescratualiza->k30_numtot = $oDeb->k30_numtot; 
        $oArreprescratualiza->k30_numdig = $oDeb->k30_numdig; 
        $oArreprescratualiza->k30_tipo = $oDeb->k30_tipo; 
        $oArreprescratualiza->k30_tipojm = $oDeb->k30_tipojm; 
        $oArreprescratualiza->k30_prescricao = $oDeb->k30_prescricao; 
        $oArreprescratualiza->k30_vlrcorr = $oDeb->k30_vlrcorr; 
        $oArreprescratualiza->k30_vlrjuros = $oDeb->k30_vlrjuros; 
        $oArreprescratualiza->k30_multa = $oDeb->k30_multa; 
        $oArreprescratualiza->k30_desconto = $oDeb->k30_desconto; 
        $oArreprescratualiza->k30_anulado = "t";
        $oArreprescratualiza->alterar($oDeb->k30_sequencial);

        if ( $oArreprescratualiza->erro_status == 0 ) {
          $lErro     = true;     
          $sMensagem = $oArreprescratualiza->erro_msg."\\n linha 118";
        }
        if ($lErro) {
          $iStatus = 2;
        }else{
          $iStatus = 1;
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
        $oAcantExcluir->excluir_arrecant($oDebito->numpre,$oDebito->numpar,$oDeb->k30_receit);
        if ( $oAcantExcluir->erro_status == 0 ) {
         $lErro     = true;     
         $sMensagem = $oAcantExcluir->erro_msg."\\n linha 143";
        }
        if ($lErro) {
          $iStatus = 2;
        }else{
          $iStatus = 1;
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
      
      $rsPrescricaoCodigo = db_query( "
        select prescricao.k31_codigo from prescricao 
        inner join prescricaolista  on prescricaolista.k122_prescricao = prescricao.k31_codigo 
        inner join lista            on lista.k60_codigo                = prescricaolista.k122_lista 
        inner join listadeb         on listadeb.k61_codigo             = lista.k60_codigo 
        where listadeb.k61_numpre = {$oDebito->numpre} and listadeb.k61_numpar = {$oDebito->numpar} 
      ");
      
      $aPrescricaoCodigo = db_utils::getCollectionByRecord($rsPrescricaoCodigo);
      
      foreach ($aPrescricaoCodigo as $oPrescricaoCodigo) {
        
        $sWherePrescricao  = "     k31_codigo = k30_prescricao and k30_prescricao = $oPrescricaoCodigo->k31_codigo ";
        $sWherePrescricao .= " and k30_numpre = {$oDebito->numpre} and k30_numpar = {$oDebito->numpar}";
        
        $sSqlPrescricao  = "update prescricao";
        $sSqlPrescricao .= "   set k31_situacao = {$iStatusSituacao}"; 
        $sSqlPrescricao .= "  from arreprescr";
        $sSqlPrescricao .= " where {$sWherePrescricao}"; 
        $lUpdate = db_query($sSqlPrescricao);
      }
      
      if ( pg_last_error() != "" ) {
        
         $lErro     = true;     
         $sMensagem = "Erro ao atualiazar a tabela prescricao:".pg_last_error()."\\n linha 185";
         
      }
      if ($lErro) {
        $iStatus = 2;
      }else{
        $iStatus = 1;
      }
      
      
      /*
       * Inseri um registro na tabela prescricaoanula com o campo Observaчуo do formulario
       * Usa o objeto $oPrescricaoanula da classe cl_prescricaoanula
       * Mщtodo Incluir 
       */
       $oPrescricaoanula->k120_id_usuario = db_getsession("DB_id_usuario"); 
       $oPrescricaoanula->k120_instit = db_getsession("DB_instit"); 
       $oPrescricaoanula->k120_obs = $oParam->obs; 
       $oPrescricaoanula->k120_data = date("Y-m-d",db_getsession('DB_datausu')); 
       $oPrescricaoanula->k120_hora = date("H:i"); 
       $oPrescricaoanula->incluir( null );
             
       if ( $oPrescricaoanula->erro_status == 0 ) {
        
         $lErro     = true;     
         $sMensagem = $oPrescricaoanula->erro_msg."\\n linha 212";;
         
       }
       if ($lErro) {
         $iStatus = 2;
       }else{
         $iStatus = 1;
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
          
           $lErro     = true;     
           $sMensagem = $oPrescricaoanulareg->erro_msg."\\n linha 241";
           break;
           
         }
       }
       
       if ($lErro){
        break;
       }
       
    }
    
    db_fim_transacao($lErro);
    if ($lErro) {
      $iStatus = 2;
    }else{
      $iStatus = 1;
    }
    
    $oRetorno->message = $sMensagem;
    $oRetorno->status  = $iStatus;
      
  }
  

echo $oJson->encode($oRetorno);   

?>