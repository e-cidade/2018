<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empnotaord_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempnotaord = new cl_empnotaord;
$clempnotaord->rotulo->label("m72_codnota");
$clempnotaord->rotulo->label("m72_codordem");
$clempnotaord->rotulo->label("m72_codordem");

$rotulo = new rotulocampo();
$rotulo->label("e69_numero");
$rotulo->label("e69_dtnota");

$depart= db_getsession("DB_coddepto");
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
            <td width="4%" align="right" nowrap title="<?=$Tm72_codnota?>">
              <strong>Sequencial da Nota</strong>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m72_codnota",10,$Im72_codnota,true,"text",4,"","chave_m72_codnota");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te69_numero?>">
              <?=$Le69_numero?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       			db_input("e69_numero",10,$Ie69_numero,true,"text",4,"","chave_e69_numero");
		       		?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tm72_codordem?>">
              <strong>Ordem de Compra</strong>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m72_codordem",10,$Im72_codordem,true,"text",4,"","chave_m72_codordem");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te69_dtnota?>">
              <?=$Le69_dtnota?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       			db_inputdata('e69_dtnota', null, null, null, true, 'text', 1, '', 'chave_e69_dtnota');
		       		?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empnotaord.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
      $where_instit = " e60_instit = ".db_getsession("DB_instit");
      
      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){

           if(file_exists("funcoes/db_func_empnotaord.php")==true){

             include("funcoes/db_func_empnotaord.php");
             
           }else{

           	$campos = "empnotaord.*";
           
           }
        }
        
        $sWhere = " and (e70_vlranu = 0 or e70_vlranu is null)";
        
        if(isset($chave_m72_codnota) && (trim($chave_m72_codnota)!="") ){

	         $sql = $clempnotaord->sql_query_elemento(null,$chave_m72_codordem,$campos,"m72_codnota","empnotaord.m72_codnota = $chave_m72_codnota and matordem.m51_depto=$depart {$sWhere} and {$where_instit}");
	         
        }else if(isset($chave_e69_numero) && (trim($chave_e69_numero)!="") ){

	         $sql = $clempnotaord->sql_query_elemento("","",$campos,"m72_codordem"," e69_numero like '$chave_e69_numero%' and  matordem.m51_depto=$depart {$sWhere} and {$where_instit}");
	         
        }else if(isset($chave_m72_codordem) && (trim($chave_m72_codordem)!="") ){

	         $sql = $clempnotaord->sql_query_elemento("","",$campos,"m72_codordem"," m72_codordem like '$chave_m72_codordem%' and  matordem.m51_depto=$depart {$sWhere} and {$where_instit}");
	         
        }else if(isset($chave_e69_dtnota) && (trim($chave_e69_dtnota)!="") ){

	         	$sql = $clempnotaord->sql_query_elemento("","",$campos,"m72_codordem"," e69_dtnota = '$chave_e69_dtnota%' and  matordem.m51_depto=$depart {$sWhere} and {$where_instit}");
	         
        }else{

           $sql = $clempnotaord->sql_query_elemento("","",$campos,"m72_codnota#m72_codordem","matordem.m51_depto=$depart {$sWhere} and {$where_instit}");
           
        }
        
        db_lovrot($sql,15,"()","",$funcao_js);
        
      } else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clempnotaord->sql_record($clempnotaord->sql_query_elemento($pesquisa_chave." and ".$where_instit));
          
          if($clempnotaord->numrows!=0){

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m72_codordem',false);</script>";
            
          }else{

	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
	         
          }
          
        } else{

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