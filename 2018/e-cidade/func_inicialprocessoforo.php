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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_inicial_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clinicial = new cl_inicial;
$clinicial->rotulo->label("v50_inicial");
$clinicial->rotulo->label("v50_data");

$sWhere = '';
$sAnd   = '';
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
            <td width="4%" align="right" nowrap title="<?=$Tv50_inicial?>">
              <?=$Lv50_inicial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		            db_input("v50_inicial",10,$Iv50_inicial,true,"text",4,"","chave_v50_inicial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tv50_data?>">
              <?=$Lv50_data?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                db_inputdata('v50_data', @$v50_data_dia, @$v50_data_mes, @$v50_data_ano, true, 'text', 4, "", "chave_v50_data");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_inicial.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {
      	
        if (isset($campos) == false) {
        	
           if (file_exists("funcoes/db_func_inicialprocessoforo.php") == true) {
             include("funcoes/db_func_inicialprocessoforo.php");
           } else {
             $campos = "distinct inicial.*,(select Z01_NOME FROM inicialnomes inner join cgm on z01_numcgm = v58_numcgm where v58_inicial = v50_inicial limit 1) as z01_nome ";
           }
        }
        
				if (isset($oGet->verif_proc) && (trim($oGet->verif_proc) != "")) {
					
	        $sWhere  = " not exists ( select *                                                      ";
	        $sWhere .= "                from processoforoinicial                                    ";
	        $sWhere .= "               where processoforoinicial.v71_inicial = inicial.v50_inicial  ";
	        $sWhere .= "                 and processoforoinicial.v71_anulado is false )             ";
	        $sAnd    = " and ";
				}
				
				if (isset($chave_v50_inicial) && (trim($chave_v50_inicial) != "")) {
					
					$sWhere  = " {$sWhere} {$sAnd} inicial.v50_inicial = {$chave_v50_inicial} "; 
					$sWhere .= " and v50_situacao = 1                           ";
					$sWhere .= " and v50_instit   = ".db_getsession('DB_instit');
	        $sql     = $clinicial->sql_query($chave_v50_inicial,$campos,"v50_inicial", $sWhere);
        } else if (isset($chave_v50_data) && (trim($chave_v50_data) != "")) {
        	
        	$chave_v50_data = implode("-",array_reverse(explode("/",trim($chave_v50_data))));
        	$sWhere  = " {$sWhere} {$sAnd} inicial.v50_situacao = 1 "; 
        	$sWhere .= " and inicial.v50_instit   = ".db_getsession('DB_instit'); 
        	$sWhere .= " and inicial.v50_data = '{$chave_v50_data}' ";
	        $sql    = $clinicial->sql_query(null, $campos, "inicial.v50_data", $sWhere);
        } else {
        	
        	$sWhere = " {$sWhere} {$sAnd} inicial.v50_situacao = 1 and inicial.v50_instit = ".db_getsession('DB_instit'); 
          $sql    = $clinicial->sql_query(null, $campos, "inicial.v50_inicial", $sWhere);
        }
    
        db_lovrot($sql,15,"()","",$funcao_js);
      } else {
      	
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
        	
					if (isset($oGet->verif_proc) && (trim($oGet->verif_proc) != "")) {
						
	          $sWhere      = "     inicial.v50_situacao = 1                                                  ";
	          $sWhere     .= " and inicial.v50_instit   = ".db_getsession('DB_instit'); 
	          $sWhere     .= " and inicial.v50_inicial  = {$pesquisa_chave}                                  ";
	          $sWhere     .= " and not exists ( select *                                                     ";
	          $sWhere     .= "                    from processoforoinicial                                   ";
	          $sWhere     .= "                   where processoforoinicial.v71_inicial = inicial.v50_inicial ";
	          $sWhere     .= "                     and processoforoinicial.v71_anulado is false )            ";
						$sSqlInicial = $clinicial->sql_query($pesquisa_chave, "*", null, $sWhere);
						$result      = $clinicial->sql_record($sSqlInicial);
					} else {	
						
						$sWhere      = "     inicial.v50_situacao = 1                          "; 
						$sWhere     .= " and inicial.v50_inicial  = {$pesquisa_chave}          "; 
						$sWhere     .= " and inicial.v50_instit   = ".db_getsession('DB_instit');
						$sSqlInicial = $clinicial->sql_query($pesquisa_chave, "*", null, $sWhere);
						$result      = $clinicial->sql_record($sSqlInicial);
          } 
          
					if ($clinicial->numrows != 0) {
						
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v50_inicial',false);</script>";
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