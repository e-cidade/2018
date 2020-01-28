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
include("classes/db_rubricas_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrubricas = new cl_rubricas;
$clrotulo = new rotulocampo;
$clrubricas->rotulo->label("r06_anousu");
$clrubricas->rotulo->label("r06_mesusu");
$clrubricas->rotulo->label("r06_codigo");
$clrubricas->rotulo->label("r06_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
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
           <td align="right" nowrap title="Digite o Ano / Mes de competência" >
             <strong>Ano / Mês :&nbsp;&nbsp;</strong>
           </td>
           <td colspan='3'>
           <?
           if(!isset($chave_r06_anousu)){
           	 $chave_r06_anousu = db_anofolha();
           }
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,"",'chave_r06_anousu');
           ?>
           &nbsp;/&nbsp;
           <?
           if(!isset($chave_r06_mesusu)){
           	 $chave_r06_mesusu = db_mesfolha();
           }
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,"",'chave_r06_mesusu');
           ?>
           </td>
         </tr>
         <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr06_codigo?>">
              <?=$Lr06_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r06_codigo",4,$Ir06_codigo,true,"text",4,"","chave_r06_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr06_descr?>">
              <?=$Lr06_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r06_descr",30,$Ir06_descr,true,"text",4,"","chave_r06_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rubricas.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = " r06_mesusu = $chave_r06_mesusu ";
      $dbwhere.= " and r06_anousu = $chave_r06_anousu ";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rubricas.php")==true){
             include("funcoes/db_func_rubricas.php");
           }else{
           $campos = "rubricas.*";
           }
        }
        if(isset($chave_r06_codigo) && (trim($chave_r06_codigo)!="") ){
	         $sql = $clrubricas->sql_query(null,null,null,$campos,"r06_codigo"," $dbwhere and r06_codigo = '$chave_r06_codigo' ");
        }else if(isset($chave_r06_descr) && (trim($chave_r06_descr)!="") ){
	         $sql = $clrubricas->sql_query(null,null,null,$campos,"r06_descr"," $dbwhere and r06_descr like '$chave_r06_descr%' ");
        }else{
           $sql = $clrubricas->sql_query(null,null,null,$campos,"r06_codigo","$dbwhere");
        }
        //die($sql);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          //die($clrubricas->sql_query(null,null,null,"*",""," $dbwhere and r06_codigo = '$pesquisa_chave'"));
          $result = $clrubricas->sql_record($clrubricas->sql_query(null,null,null,"*",""," $dbwhere and r06_codigo = '$pesquisa_chave'"));
          if($clrubricas->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r06_descr',false);</script>";
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