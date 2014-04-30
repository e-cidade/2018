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
include("classes/db_tipoproc_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltipoproc = new cl_tipoproc;
$cltipoproc->rotulo->label("p51_codigo");
$cltipoproc->rotulo->label("p51_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tp51_codigo?>">
              <?=$Lp51_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p51_codigo",3,$Ip51_codigo,true,"text",4,"","chave_p51_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp51_descr?>">
              <?=$Lp51_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p51_descr",60,$Ip51_descr,true,"text",4,"","chave_p51_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = " p51_instit = ".db_getsession("DB_instit");
      
      if(isset($grupo) && $grupo == 1){
      	$where .= " and p51_tipoprocgrupo = $grupo";
      }else if(isset($grupo) && $grupo == 2){
      	$where .= " and p51_tipoprocgrupo = $grupo";
      }      
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "tipoproc.*";
        }
        if(isset($chave_p51_codigo) && (trim($chave_p51_codigo)!="") ){
	         $sql = $cltipoproc->sql_query(null,$campos,"p51_codigo","p51_codigo=$chave_p51_codigo  and $where");
        }else if(isset($chave_p51_descr) && (trim($chave_p51_descr)!="") ){
	         $sql = $cltipoproc->sql_query("",$campos,"p51_descr"," p51_descr like '$chave_p51_descr%' and $where");
        }else{
           //$sql = $cltipoproc->sql_query("",$campos,"p51_descr");
           $sql = $cltipoproc->sql_query("",$campos,"p51_descr",$where);
        }
	//die($sql);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        $result = $cltipoproc->sql_record($cltipoproc->sql_query(null,"*","p51_codigo","p51_codigo=$chave_p51_codigo $where"));
        if($cltipoproc->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$p51_descr',false);</script>";
        }else{
	       echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
document.form2.chave_p51_descr.focus();
document.form2.chave_p51_descr.select();
  </script>
  <?
}
?>