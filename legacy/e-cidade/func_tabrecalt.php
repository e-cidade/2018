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
include("classes/db_tabrec_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltabrec = new cl_tabrec;
$cltabrec->rotulo->label("k02_codigo");
$cltabrec->rotulo->label("k02_descr");
$cltabrec->rotulo->label("k02_drecei");

$clrotulo = new rotulocampo;

$clrotulo->label("o70_codrec");
$clrotulo->label("c61_reduz");
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
            <td width="4%" align="right" nowrap title="<?=$Tk02_codigo?>"><?=$Lk02_codigo?></td>
            <td width="96%" align="left" nowrap> 
              <? db_input("k02_codigo",4,$Ik02_codigo,true,"text",4,"","chave_k02_codigo");  ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tk02_descr?>"><?=$Lk02_descr?></td>
            <td width="96%" align="left" nowrap> 
              <? db_input("k02_descr",15,$Ik02_descr,true,"text",4,"","chave_k02_descr"); ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap><b> Estrutural </b> </td>
            <td width="96%" align="left" nowrap> 
              <? db_input("k02_estorc",15,'',true,"text",4,"","chave_k02_estorc"); ?>
            </td>
 
            <td width="4%" align="right" nowrap title="<?=$Tk02_drecei ?>"><?=$Lk02_drecei ?></td>
            <td width="96%" align="left" nowrap> 
              <? db_input("k02_drecei",40,$Ik02_drecei,true,"text",4,"","chave_k02_drecei"); ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap><strong> Extra-Orcamentário: </strong> </td>
            <td width="96%" align="left" nowrap> 
              <? db_input("c61_reduz",8,$Ic61_reduz,true,"text",2,"","chave_c61_reduz"); ?>
            </td>
 
            <td width="4%" align="right" nowrap ><strong>Orçamentário:</strong></td>
            <td width="96%" align="left" nowrap> 
              <? db_input("o70_codrec",8,$Io70_codrec,true,"text",2,"","chave_o70_codrec"); ?>
            </td>
          </tr>
 
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tabrec.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
			$where=" 1=1 ";
			if (isset($listar)&&$listar=="e"){
				$where = "c61_reduz<>0 and o70_codrec=0 ";
			}else	if (isset($listar)&&$listar=="o"){
				$where = "c61_reduz=0 and o70_codrec<>0 ";
			}
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "tabrec.*";
        }
        if(isset($chave_k02_codigo) && (trim($chave_k02_codigo)!="") ){
	   $sql = $cltabrec->sql_query_inst($chave_k02_codigo,$campos,"k02_codigo","k02_codigo = $chave_k02_codigo and $where");
        }else if(isset($chave_k02_descr) && (trim($chave_k02_descr)!="") ){
	   $sql = $cltabrec->sql_query_inst("",$campos,"k02_descr"," upper(k02_descr) like '$chave_k02_descr%' and $where ");
        }else if(isset($chave_k02_drecei) && (trim($chave_k02_drecei)!="") ){
	   $sql = $cltabrec->sql_query_inst("",$campos,"k02_drecei"," upper(k02_drecei) like '$chave_k02_drecei%' and $where ");
        }else if(isset($chave_c61_reduz) && (trim($chave_c61_reduz)!="") ){
	   $sql = $cltabrec->sql_query_inst("",$campos,"k02_drecei"," c61_reduz = $chave_c61_reduz and $where ");
        }else if(isset($chave_o70_codrec) && (trim($chave_o70_codrec)!="") ){
	   $sql = $cltabrec->sql_query_inst("",$campos,"k02_drecei"," o70_codrec = $chave_o70_codrec and $where ");
       
	}else if(isset($chave_k02_estorc) && (trim($chave_k02_estorc)!="") ){
	   $sql = $cltabrec->sql_query_inst("",$campos,"k02_estorc"," k02_estorc like '$chave_k02_estorc%' and $where ");

        }else{
           $sql = $cltabrec->sql_query_inst("",$campos,"k02_codigo",$where);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        $result = $cltabrec->sql_record($cltabrec->sql_query_inst(null,"*",null,"k02_codigo = $pesquisa_chave and $where"));
        if($cltabrec->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$k02_drecei',false);</script>";
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
document.form2.chave_k02_codigo.focus();
document.form2.chave_k02_codigo.select();
  </script>
  <?
}
?>