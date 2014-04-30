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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_mer_cardapioescola_classe.php");
require_once("classes/db_mer_tipocardapio_classe.php");
require_once("classes/db_mer_tpcardapioturma_classe.php");
require_once("classes/db_mer_consumocardapio_classe.php");
require_once("classes/db_mer_consumoescola_classe.php");
require_once("classes/db_mer_cardapiodia_classe.php");
require_once("classes/db_escola_classe.php");
require_once("libs/JSON.php");

$clmer_tipocardapio       = new cl_mer_tipocardapio();
$clmer_tpcardapioturma    = new cl_mer_tpcardapioturma();
$clmer_cardapioescola     = new cl_mer_cardapioescola();
$clmer_consumocardapio    = new cl_mer_consumocardapio();
$clmer_consumoescola      = new cl_mer_consumoescola();
$clmer_cardapiodia        = new cl_mer_cardapiodia();
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

switch ($oParam->exec) {

  case "listarEscola":
    
    $sCampos                  = " escola.ed18_i_codigo,escola.ed18_c_nome       ";
    $sSqlEscola               = $clescola->sql_query_consumoescola(null,
                                                                   $sCampos,
                                                                   "ed18_c_nome",
                                                                   "me32_i_tipocardapio=".$oParam->codtipocardapio,
                                                                   $oParam->codtipocardapio
                                                                  ); 
    $sSqlEscola;
    $rsEscola                 = $clescola->sql_record($sSqlEscola);

    $sCampos                  = "distinct escola.ed18_i_codigo, escola.ed18_c_nome,me38_i_codigo,me38_i_ordem ";    
    $sWhere                   = "  mer_consumoescola.me38_i_tipocardapio = {$oParam->codtipocardapio}  ";
    $sSqlDeptoSel             =  $clmer_consumoescola->sql_query(null,$sCampos,"ed18_c_nome",$sWhere); 
    $rsSqlDeptoSel            = $clmer_consumoescola->sql_record($sSqlDeptoSel);      
    
    $oRetorno->aItensDptos    = db_utils::getColectionByRecord($rsEscola,false,false,true);
    $oRetorno->aItensDptosSel = db_utils::getColectionByRecord($rsSqlDeptoSel,false,false,true);    
    
    break; 
    
  case "atualizarEscola":

    $sqlerro = false;
    db_inicio_transacao();
    if ($sqlerro == false) {
    
      $sCampos            = " distinct escola.ed18_i_codigo,escola.ed18_c_nome, ";
      $sCampos           .= " me38_i_ordem,me38_i_codigo,me38_i_tipocardapio ";
      $sWhere             = " mer_consumoescola.me38_i_tipocardapio = {$oParam->codtipocardapio}";
      $sSqlDeptoInstitUsu =  $clmer_consumoescola->sql_query(null,$sCampos,"me38_i_ordem",$sWhere);
      $rsDeptoInstitUsu   = $clmer_consumoescola->sql_record($sSqlDeptoInstitUsu);
      if ($clmer_consumoescola->numrows > 0) {                                         
          
        $iNumRowsDepto = $clmer_consumoescola->numrows;          
        for ($i = 0; $i < $iNumRowsDepto; $i++) {
          	
          $oCardapioDeptoInstit  = db_utils::fieldsMemory($rsDeptoInstitUsu, $i);  
          $sSqlCardapioTurma     =  $clmer_cardapiodia->sql_query(null,"me38_i_codigo","","me27_i_codigo =".$oCardapioDeptoInstit->me38_i_tipocardapio);
          $rsCardapioTurma       = $clmer_cardapiodia->sql_record($sSqlCardapioTurma);
          $iNumRowsCardapioTurma = $clmer_cardapiodia->numrows;
          if ($iNumRowsCardapioTurma == 0) {            	                            
            $clmer_consumoescola->excluir($clmer_consumoescola->me38_i_codigo);    
          }
          
        }
        
      }     
                  
    }
    $clmer_consumocardapio->me37_i_tipocardapio = $oParam->codtipocardapio;     
    $clmer_consumocardapio->incluir(null);  
    foreach ($oParam->aDptoSel as $oDptoSel) {
    	
      if ($sqlerro == false) {      	      	
        
        $iNumRows = $clmer_consumoescola->numrows;
        if ($iNumRows == 0) {
        	
          $clmer_consumoescola->me38_i_cardapioescola = $oParam->codcardapioescola;
          $clmer_consumoescola->me38_i_tipocardapio   = $oParam->codtipocardapio;
          $clmer_consumoescola->me38_i_ordem          = $oDptoSel->iOrdem;               
          $clmer_consumoescola->incluir(null);
          
        }
        
      } 
          
    } 
    db_fim_transacao();
    
    break;
        
}
echo $oJson->encode($oRetorno);
?>