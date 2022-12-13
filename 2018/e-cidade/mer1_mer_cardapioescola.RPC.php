<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_mer_cardapioescola_classe.php");
require_once("classes/db_mer_tipocardapio_classe.php");
require_once("classes/db_mer_tpcardapioturma_classe.php");
require_once("classes/db_escola_classe.php");
require_once("libs/JSON.php");
$clmer_tipocardapio       = new cl_mer_tipocardapio();
$clmer_tpcardapioturma    = new cl_mer_tpcardapioturma();
$clmer_cardapioescola     = new cl_mer_cardapioescola();
$clescola                 = new cl_escola();
$oJson                    = new services_json();
$oRetorno                 = new stdClass(); 
$oParam                   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->erro           = 0;
$oRetorno->erroexclusao   = "";
$oRetorno->aItensDptos    = array();
$oRetorno->aItensDptosSel = array();
$dtDataUsu                = date('Y-m-d',db_getsession("DB_datausu"));
$iEscola                  = db_getsession("DB_coddepto");
$nutricionista            = VerNutricionista(db_getsession("DB_id_usuario"));
switch ($oParam->exec) {

  case "listarEscola":
    
    $sCampos                  = " escola.ed18_i_codigo,escola.ed18_c_nome       ";
    $sWhere                   = " (limite is null or limite >= '{$dtDataUsu}')  "; 
    $sSqlEscola               = $clescola->sql_query_cardapioescola(null,
                                                                    $sCampos,
                                                                    "ed18_c_nome",
                                                                    $sWhere,
                                                                    $nutricionista    
                                                                   ); 
 
    $rsEscola                 = $clescola->sql_record($sSqlEscola);

    $sCampos                  = "distinct escola.ed18_i_codigo, escola.ed18_c_nome,me32_i_codigo,me32_i_ordem, ";
    $sCampos                 .= " case when (select count(*) from mer_tpcardapioturma "; 
    $sCampos                 .= " where me28_i_cardapioescola=me32_i_codigo)>0 then 1 else 0 end as cardapioturma";
    $sWhere                   = "  mer_cardapioescola.me32_i_tipocardapio = {$oParam->codcardapio}  ";
    $sSqlDeptoSel             =  $clmer_cardapioescola->sql_query(null,$sCampos,"ed18_c_nome",$sWhere); 
    $rsSqlDeptoSel            = $clmer_cardapioescola->sql_record($sSqlDeptoSel);      
    
    $oRetorno->aItensDptos    = db_utils::getColectionByRecord($rsEscola,false,false,true);
    $oRetorno->aItensDptosSel = db_utils::getColectionByRecord($rsSqlDeptoSel,false,false,true);    
    
  break; 
  
  case "atualizarEscola":

    $sqlerro = false;
    db_inicio_transacao();
    if ($sqlerro == false) {
    
      $sCampos            = " distinct escola.ed18_i_codigo,escola.ed18_c_nome, ";
      $sCampos           .= " mer_cardapioescola.me32_i_ordem,me32_i_codigo ";
      $sWhere             = " mer_cardapioescola.me32_i_tipocardapio = {$oParam->codcardapio}     ";
      $sSqlDeptoInstitUsu =  $clmer_cardapioescola->sql_query(null,$sCampos,"me32_i_ordem",$sWhere);
      $rsDeptoInstitUsu   = $clmer_cardapioescola->sql_record($sSqlDeptoInstitUsu);
      if ($clmer_cardapioescola->numrows > 0) {                                         
          
        $iNumRowsDepto = $clmer_cardapioescola->numrows;
        for ($i = 0; $i < $iNumRowsDepto; $i++) {
          	
          $oCardapioDeptoInstit                      = db_utils::fieldsMemory($rsDeptoInstitUsu, $i);  
          $sSqlCardapioTurma =  $clmer_tpcardapioturma->sql_query(null,"*","","me28_i_cardapioescola = ".$oCardapioDeptoInstit ->me32_i_codigo);
          $rsCardapioTurma   = $clmer_tpcardapioturma->sql_record($sSqlCardapioTurma);
          $iNumRowsCardapioTurma = $clmer_tpcardapioturma->numrows;
          if ($iNumRowsCardapioTurma == 0) {
            
            $clmer_cardapioescola->me32_i_escola       = $oCardapioDeptoInstit->ed18_i_codigo;
            $clmer_cardapioescola->me32_i_tipocardapio = $oParam->codcardapio;
            $clmer_cardapioescola->me32_i_codigo       = $oCardapioDeptoInstit ->me32_i_codigo;                         
            $clmer_cardapioescola->excluir($oCardapioDeptoInstit ->me32_i_codigo);    
            $erro_msg = $clmer_cardapioescola->erro_msg;    
            if ( $clmer_cardapioescola->erro_status == 0 ) {
                
              $sqlerro        = true;
              $oRetorno->erro = 1;
              
            }
            
          }else{
          	$oRetorno->erroexclusao .= $oCardapioDeptoInstit->ed18_i_codigo .",";  
          }
          
        }
        
      }
      
    }
    foreach ($oParam->aDptoSel as $oDptoSel) {
    	
      if ($sqlerro == false) {      	      	
        
        $sSqlCardapioTurma =  $clmer_tpcardapioturma->sql_query(null,
                                                                 "*",
                                                                 "",
                                                                 "me32_i_escola = ".$oDptoSel->iDptoSel. " and me32_i_tipocardapio =".$oParam->codcardapio  
                                                               );
        $rsCardapioTurma   = $clmer_tpcardapioturma->sql_record($sSqlCardapioTurma);
        $iNumRowsCardapioTurma = $clmer_tpcardapioturma->numrows;
        if ($iNumRowsCardapioTurma ==0) {
        	
          $clmer_cardapioescola->me32_i_escola       = $oDptoSel->iDptoSel;
          $clmer_cardapioescola->me32_i_tipocardapio = $oParam->codcardapio;
          $clmer_cardapioescola->me32_i_ordem        = $oDptoSel->iOrdem;               
          $clmer_cardapioescola->incluir(null);
          if ( $clmer_cardapioescola->erro_status == 0 ) {
   
            $sqlerro        = true;
            $oRetorno->erro = 1;
            break;
            
          } 
            
        }
        
      } 
          
    } 
    db_fim_transacao($sqlerro);

  break;
        
}
echo $oJson->encode($oRetorno);
?>