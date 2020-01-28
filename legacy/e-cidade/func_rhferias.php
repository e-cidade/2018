<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhferias_classe.php");
require_once("std/DBDate.php");

$oGet = db_utils::postMemory($_GET);
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhferias = new cl_rhferias();
$clrhferias->rotulo->label("rh109_sequencial");

if (isset($chave_rh109_sequencial) && !DBNumber::isInteger($chave_rh109_sequencial)) {
  $chave_rh109_sequencial = '';
}

if (isset($chave_rh109_sequencial) && !DBNumber::isInteger($chave_rh109_sequencial)) {
  $chave_rh109_sequencial = '';
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
      <table width="204px" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Trh109_sequencial; ?>">
              <?php echo $Lrh109_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("rh109_sequencial", 10, $Irh109_sequencial, true, "text", 4, "", "chave_rh109_sequencial");
		          ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onclick="return js_valida(arguments[0]);"/>
              <input name="limpar" type="reset" id="limpar" value="Limpar" />
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhferias.hide();"/>
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      $sWhere = ' 1=1 ';

      if (!empty($rh109_regist)) {
      	$sWhere  .= ' and rh109_regist = ' . $rh109_regist;
      }

      if (!empty($lFeriasLancadas)) {
      	$sWhere .= ' and not exists( select 1 from rhferiasperiodo where rhferiasperiodo.rh110_rhferias = rhferias.rh109_sequencial)';
      }
      
      if(!isset($pesquisa_chave)) {

        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhferias.php")==true){
             include("funcoes/db_func_rhferias.php");
           }else{
           $campos = "rhferias.*";
           }
        }

        if(isset($chave_rh109_sequencial) && (trim($chave_rh109_sequencial)!="") ){
           $sWhere .= ' and rhferias.rh109_sequencial = ' . $chave_rh109_sequencial;
	         $sql     = $clrhferias->sql_query("",$campos,"rh109_sequencial", $sWhere);
        }else if(isset($chave_rh109_sequencial) && (trim($chave_rh109_sequencial)!="") ){
	         $sql = $clrhferias->sql_query("",$campos,"rh109_sequencial"," rh109_sequencial like '$chave_rh109_sequencial%' and " . $sWhere);
        }else{
           $sql = $clrhferias->sql_query("",$campos,"rh109_sequencial",$sWhere);
        }
        
        $repassa = array();
        if(isset($chave_rh109_sequencial)){
          $repassa = array("chave_rh109_sequencial"=>$chave_rh109_sequencial,"chave_rh109_sequencial"=>$chave_rh109_sequencial);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $sWhere .= ' and rhferias.rh109_sequencial = ' . $pesquisa_chave;
          
          $sSql = $clrhferias->sql_query(null,"*",null,$sWhere);
          
          $result = $clrhferias->sql_record($sSql);
          if($clrhferias->numrows!=0){
            db_fieldsmemory($result,0);
            
            echo "<script>".$funcao_js."('$rh109_sequencial', false, '$rh109_periodoaquisitivoinicial', '$rh109_periodoaquisitivofinal');</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script type="text/javascript">
    function js_valida(event) {

      document.getElementById('chave_rh109_sequencial').onkeyup = event;
      return true;
    }
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_rh109_sequencial",true,1,"chave_rh109_sequencial",true);
</script>