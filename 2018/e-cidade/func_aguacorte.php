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
include("dbforms/db_funcoes.php");
include("classes/db_aguacorte_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claguacorte = new cl_aguacorte;
$claguacorte->rotulo->label("x40_codcorte");
$claguacorte->rotulo->label("x40_dtinc");

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
            <td width="10%" align="right" nowrap title="<?=$Tx40_codcorte?>">
              <?=$Lx40_codcorte?>
            </td>
            <td width="90%" align="left" nowrap> 
              <?
		       db_input("x40_codcorte",10,$Ix40_codcorte,true,"text",4,"","chave_x40_codcorte");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="10%" align="right" nowrap title="<?=$Tx40_dtinc?>">
              <?=$Lx40_dtinc?>
            </td>
            <td width="90%" align="left" nowrap> 
              <?
		       //    db_input("x40_dtinc",10,$Ix40_dtinc,true,"text",4,"","chave_x40_dtinc");
           db_inputdata('x40_dtinc',@$x40_dtinc_dia,@$x40_dtinc_mes,@$x40_dtinc_ano,true,'text',4,"", "chave_x40_dtinc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aguacorte.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_aguacorte.php")==true){
             include("funcoes/db_func_aguacorte.php");
           }else{
           $campos = "aguacorte.*";
           }
        }
        if(isset($chave_x40_codcorte) && (trim($chave_x40_codcorte)!="") ){
	         $sql = $claguacorte->sql_query($chave_x40_codcorte,$campos,"x40_codcorte desc");
        }else if(isset($chave_x40_dtinc) && (trim($chave_x40_dtinc)!="") ){
           list($x40_dtinc_dia, $x40_dtinc_mes, $x40_dtinc_ano) = split("/", $chave_x40_dtinc);
           $dtinc = "$x40_dtinc_ano-$x40_dtinc_mes-$x40_dtinc_dia";
	         $sql = $claguacorte->sql_query("",$campos,"x40_dtinc, x40_codcorte desc"," x40_dtinc = '$dtinc' ");
        }else{
           $sql = $claguacorte->sql_query("",$campos,"x40_codcorte desc","");
        }
        //echo $sql;
        $repassa["chave_x40_codcorte"] = @$chave_x40_codcorte;
        $repassa["chave_x40_dtinc"]    = @$chave_x40_dtinc;
        $repassa["x40_dtinc_ano"]      = @$x40_dtinc_ano;
        $repassa["x40_dtinc_mes"]      = @$x40_dtinc_mes;
        $repassa["x40_dtinc_dia"]      = @$x40_dtinc_dia;
        $repassa["x40_dtinc"]          = @$x40_dtinc;
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa, false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $claguacorte->sql_record($claguacorte->sql_query($pesquisa_chave));
          if($claguacorte->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$x40_dtinc',false);</script>";
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
  <script>
  </script>
  <?
}
?>