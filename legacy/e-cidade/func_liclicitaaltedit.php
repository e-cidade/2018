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
include("classes/db_liclicita_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clliclicita = new cl_liclicita;
$clliclicita->rotulo->label("l20_codigo");
$clliclicita->rotulo->label("l20_numero");
$clrotulo = new rotulocampo;
$clrotulo->label("l03_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tl20_codigo?>">
              <?=$Ll20_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("l20_codigo",10,$Il20_codigo,true,"text",4,"","chave_l20_codigo");
		       ?>
            </td>
            </tr> 
            <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tl20_numero?>">
              <?=$Ll20_numero?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("l20_numero",10,$Il20_numero,true,"text",4,"","chave_l20_numero");
		       ?>
            </td>
          </tr>
          <tr> 
          <td width="4%" align="right" nowrap title="<?=$Tl03_descr?>">
              <?=$Ll03_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("l03_descr",60,$Il03_descr,true,"text",4,"","chave_l03_descr");
		       ?>
            </td>
          </tr>          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_liclicita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where="";
      if (isset($cnpj)&&$cnpj!=""){
      	$where = " l28_cnpj='$cnpj' ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "distinct liclicita.l20_codigo,liclicita.l20_codtipocom,pctipocompra.pc50_descr,liclicita.l20_numero,liclicita.l20_id_usucria,liclicita.l20_datacria,liclicita.l20_horacria,liclicita.l20_dataaber,liclicita.l20_dtpublic,liclicita.l20_horaaber,liclicita.l20_local,liclicita.l20_objeto";
        }
        if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
        	if ($where!=""){
        		$where = " and ".$where;
        	}
	         $sql = $clliclicita->sql_query_baixa($chave_l20_codigo,$campos,"l20_codigo"," l20_codigo = $chave_l20_codigo $where");
	    }else if(isset($chave_l20_numero) && (trim($chave_l20_numero)!="") ){
	    	if ($where!=""){
        		$where = " and ".$where;
        	}
	         $sql = $clliclicita->sql_query_baixa(null,$campos,"l20_codigo","l20_numero=$chave_l20_numero $where");
	    }else if(isset($chave_l03_descr) && (trim($chave_l03_descr)!="") ){
	    	if ($where!=""){
        		$where = " and ".$where;
        	}
	         $sql = $clliclicita->sql_query_baixa(null,$campos,"l20_codigo","l03_descr like '$chave_l03_descr%' $where");
         }else if(isset($chave_l03_codigo) && (trim($chave_l03_codigo)!="") ){
         	if ($where!=""){
        		$where = " and ".$where;
        	}
	         $sql = $clliclicita->sql_query_baixa(null,$campos,"l20_codigo","l03_codigo=$chave_l03_codigo $where");        
        }else{
           $sql = $clliclicita->sql_query_baixa("",$campos,"l20_codigo",$where);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
      	if ($where!=""){
        		$where = " and ".$where;
        	}
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clliclicita->sql_record($clliclicita->sql_query_baixa(null,"*",null,"l20_codigo=$pesquisa_chave $where "));
          if($clliclicita->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$l20_codigo',false);</script>";
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