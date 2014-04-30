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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cairetordem_classe.php");
$oGet              = db_utils::postMemory($_GET);
$clcairetordem     = new cl_cairetordem();
(boolean)$lRetorno = false;
(float)$nValor     = 0;
(string)$sTipo = null;
if (isset($oGet->iNumpre) && $oGet->iNumpre != null){
  
  $sSQlArrePaga  = "select sum(k00_valor) as k00_valor";
  $sSQlArrePaga .="   from arrepaga";
  $sSQlArrePaga .="  where k00_numpre = {$oGet->iNumpre} having sum(k00_valor) > 0"; 
  $rsArrePaga    = $clcairetordem->sql_record($sSQlArrePaga);
  if ($clcairetordem->numrows > 0){
          
     $oArrePaga  = db_utils::fieldsMemory($rsArrePaga,0);      
     $lRetorno = true;
     $nValor   = $oArrePaga->k00_valor;
     $sTipo    = "Arrecadação Paga";
  }
  if (!$lRetorno){
  
    $sSQlReciboPaga  = "select sum(k00_valor) as k00_valor";
    $sSQlReciboPaga .="   from recibopaga";
    $sSQlReciboPaga .="  where k00_numnov = {$oGet->iNumpre} having sum(k00_valor) > 0"; 
    $rsReciboPaga    = $clcairetordem->sql_record($sSQlReciboPaga);
    if ($clcairetordem->numrows > 0){
          
       $oReciboPaga  = db_utils::fieldsMemory($rsReciboPaga,0);      
       $lRetorno = true;
       $nValor   = $oReciboPaga->k00_valor;
       $sTipo    = "Recibo CGF";
    }
  }
  if (!$lRetorno){

     $sSQlRecibo  = "select sum(k00_valor) as k00_valor";
     $sSQlRecibo .="   from recibo";
     $sSQlRecibo .="  where k00_numpre = {$oGet->iNumpre} having sum(k00_valor) > 0"; 
     $rsRecibo    = $clcairetordem->sql_record($sSQlRecibo);
     if ($clcairetordem->numrows > 0){
     
       $oRecibo  = db_utils::fieldsMemory($rsRecibo,0);      
       $lRetorno = true;
       $nValor   = $oRecibo->k00_valor;
       $sTipo    = "Recibo Avulso ";

  }
  
  }
  if (!$lRetorno){
  
    $sSQlArrecad  = "select sum(k00_valor) as k00_valor";
    $sSQlArrecad .="   from arrecad";
    $sSQlArrecad .="  where k00_numpre = {$oGet->iNumpre} having sum(k00_valor) > 0"; 
    $rsArrecad    = $clcairetordem->sql_record($sSQlArrecad);
    if ($clcairetordem->numrows > 0){
          
       $oArrecad  = db_utils::fieldsMemory($rsArrecad,0);      
       $lRetorno = true;
       $nValor   = $oArrecad->k00_valor;
       $sTipo    = "Arrecadação Em aberto";
    }
  }
  if ($lRetorno){

      echo "<script>\n";
      echo "   {$oGet->funcao_js}(true,{$nValor},'{$sTipo}');\n";
      echo "</script>\n";

  }else{
      
      echo "<script>\n";
      echo "   {$oGet->funcao_js}(false,'','');\n";
      echo "</script>\n";
      
  }
}

?>