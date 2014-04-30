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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issbaselogtipo_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clissbaselogtipo = new cl_issbaselogtipo;
$clissbaselogtipo->rotulo->label("q103_sequencial");
$clissbaselogtipo->rotulo->label("q103_sequencial");

$iDataUsu = date("Y-m-d",db_getsession("DB_datausu"));
$sWhere   = "";
$sAnd     = "";
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
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tq103_sequencial?>">
              <?=$Lq103_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		            db_input("q103_sequencial",10,$Iq103_sequencial,true,"text",4,"","chave_q103_sequencial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" 
                     onClick="parent.db_iframe_issbaselogtipo.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere .= "issbaselogtipo.q103_automatico is false";
      $sAnd    = " and ";
      if (isset($lPeriodo) && $lPeriodo == 'true') {
      	
        $sWhere .= "{$sAnd} case                                                                         ";
        $sWhere .= "           when issbaselogtipo.q103_dataini is not null then                         "; 
      	$sWhere .= "            case                                                                     ";
      	$sWhere .= "              when '{$iDataUsu}'::date >= issbaselogtipo.q103_dataini then true      ";
      	$sWhere .= "              else false                                                             ";
      	$sWhere .= "             end                                                                     ";
      	$sWhere .= "          else true                                                                  ";
      	$sWhere .= "        end                                                                          ";
        $sWhere .= "   and  case                                                                         ";
        $sWhere .= "          when issbaselogtipo.q103_datafin is not null then                          "; 
      	$sWhere .= "            case                                                                     ";
      	$sWhere .= "              when '{$iDataUsu}'::date <= issbaselogtipo.q103_datafin then true      ";
      	$sWhere .= "              else false                                                             ";
      	$sWhere .= "            end                                                                      ";
      	$sWhere .= "           else true                                                                 ";
      	$sWhere .= "        end                                                                          ";
      	$sAnd    = " and ";
      	
      }
      
      if (isset($lAtivos) && $lAtivos == 'true') {

        $sWhere .= "{$sAnd} issbaselogtipo.q103_ativo is true";
        $sAnd    = " and ";     	
      }
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issbaselogtipo.php")==true){
             include("funcoes/db_func_issbaselogtipo.php");
           }else{
           $campos = "issbaselogtipo.*";
           }
        }
        if (isset($chave_q103_sequencial) && (trim($chave_q103_sequencial) != "")) {
	         $sql     = $clissbaselogtipo->sql_query($chave_q103_sequencial,$campos,"q103_sequencial",$sWhere);
        } else if (isset($chave_q103_sequencial) && (trim($chave_q103_sequencial) != "")) {
        	
        	 $sWhere .= "{$sAnd} issbaselogtipo.q103_sequencial like '$chave_q103_sequencial%' ";
	         $sql     = $clissbaselogtipo->sql_query("",$campos,"q103_sequencial",$sWhere);
        } else {
           $sql     = $clissbaselogtipo->sql_query("",$campos,"q103_sequencial",$sWhere);
        }
        
        $repassa = array();
        if (isset($chave_q103_sequencial)) {
          $repassa = array("chave_q103_sequencial" => $chave_q103_sequencial);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

        	$sWhere            .= "{$sAnd} issbaselogtipo.q103_sequencial = {$pesquisa_chave}";
        	$sSqlIssBaseLogTipo = $clissbaselogtipo->sql_query(null,"*",null,$sWhere);
          $rsIssBaseLogTipo   = $clissbaselogtipo->sql_record($sSqlIssBaseLogTipo);
          if ($clissbaselogtipo->numrows != 0) {
            db_fieldsmemory($rsIssBaseLogTipo,0);
            echo "<script>".$funcao_js."('$q103_descricao',false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
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
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_q103_sequencial",true,1,"chave_q103_sequencial",true);
</script>