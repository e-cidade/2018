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
include("classes/db_pcorcam_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamtroca_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcorcam = new cl_pcorcam;
$clpcparam = new cl_pcparam;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamitemsol = new cl_pcorcamitemsol;
$clpcorcamforne = new cl_pcorcamforne;
$clpcorcamval = new cl_pcorcamval;
$clpcorcamjulg = new cl_pcorcamjulg;
$clpcorcamtroca = new cl_pcorcamtroca;
$db_opcao = 33;
$db_botao = false;
$db_open  = false;

if(isset($excluir) || isset($retornoexcluival)){
  $excluival = true;
  if(!isset($retornoexcluival)){
    $result_item = $clpcorcam->sql_record($clpcorcam->sql_query_vallancados($pc20_codorc,"count(*) as valoreslancados"));
    db_fieldsmemory($result_item,0);
    if($valoreslancados>0){
      $excluival = false;
    }
  }else{
    $pc20_codorc=$retornoexcluival;
  }
  if($excluival==true){
    $sqlerro = false;
    db_inicio_transacao();
    if($sqlerro==false && isset($retornoexcluival)){
      $clpcorcamtroca->excluir(null,"pc25_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.")");
      if($clpcorcamtroca->erro_status==0){
	$sqlerro=true;
	$erro_msg = $clpcorcamtroca->erro_msg;
      }  
      if($sqlerro==false){
	$clpcorcamjulg->excluir(null,null,"pc24_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.") and pc24_orcamforne in (select pc21_orcamforne from pcorcamforne where pc21_codorc=".$pc20_codorc.")");
	if($clpcorcamjulg->erro_status==0){
	  $sqlerro=true;
	  $erro_msg = $clpcorcamjulg->erro_msg;
	}  
      }
      if($sqlerro==false){
	$clpcorcamval->excluir(null,null,"pc23_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.") and pc23_orcamforne in (select distinct pc21_orcamforne from pcorcamforne where pc21_codorc=".$pc20_codorc.")");
	if($clpcorcamval->erro_status==0){
	  $sqlerro=true;
	  $erro_msg = $clpcorcamval->erro_msg;
	}  
      }
    }
    if($sqlerro==false){
      $clpcorcamforne->excluir(null,"pc21_codorc=".$pc20_codorc);
      if($clpcorcamforne->erro_status==0){
	$sqlerro=true;
	$erro_msg = $clpcorcamforne->erro_msg;
      }  
    }
    if($sqlerro==false){
      $clpcorcamitemsol->excluir(null,null,"pc29_orcamitem in(".$clpcorcamitem->sql_query_file(null,"pc22_orcamitem","","pc22_codorc=".$pc20_codorc).")");
      if($clpcorcamitemsol->erro_status==0){
	$sqlerro=true;
	$erro_msg = $clpcorcamitemsol->erro_msg;
      }  
    }
    if($sqlerro==false){
      $clpcorcamitem->excluir(null,"pc22_codorc=".$pc20_codorc);
      if($clpcorcamitem->erro_status==0){
	$sqlerro=true;
	$erro_msg = $clpcorcamitem->erro_msg;
      }  
    }
    if($sqlerro==false){
      $clpcorcam->excluir($pc20_codorc);
      if($clpcorcam->erro_status==0){
	$sqlerro=true;
      }  
      $erro_msg = $clpcorcam->erro_msg;
    }
    db_fim_transacao($sqlerro);

    $pc20_codorc      = "";
    $pc_hrate         = "";
    $pc20_dtate       = "";
  }
}else if(isset($retorno)){
  $db_opcao = 3;
  $db_botao = true;
  $result_clpcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file($retorno,"pc20_codorc,pc20_hrate,pc20_dtate,pc20_obs"));
  $numrows_clpcorcam= $clpcorcam->numrows;
  if($numrows_clpcorcam > 0){
    db_fieldsmemory($result_clpcorcam,0);
  }
}else if(isset($chavepesquisa)){
  $db_opcao = 3;
  $db_botao = true;
  $db_open  = true;
}
$db_chama = "excluir";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
	include("forms/db_frmorcamento.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir) || isset($retornoexcluival)){
  if($excluival==false){
    echo "
    <script>
      if(confirm('ATENÇÃO: \\nValor de um ou mais itens deste orçamento foi lançado! \\n\\nDeseja excluir valores lançados?')){
	document.location.href = 'com1_orcamento006.php?retornoexcluival=$pc20_codorc';
      }
    </script>
    ";
  } 
  if($sqlerro==true){
    db_msgbox(str_replace("\n","\\n",$erro_msg));
    if($clpcorcam->erro_campo!=""){
      echo "<script> document.form1.".$clpcorcam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcorcam->erro_campo.".focus();</script>";
    };
  }else if($sqlerro==false){
    echo "
     <script>
       function js_db_tranca(){
         top.corpo.iframe_orcam.location.href='com1_orcamento006.php';
       }\n
       js_db_tranca();
     </script>\n
    ";
  }
}
if(isset($retorno)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.fornec.disabled=false;
	 top.corpo.iframe_fornec.location.href='com1_fornec001.php?solic=true&db_opcaoal=33&pc21_codorc=$retorno';
      }\n
    js_db_libera();
  </script>\n
 ";
}
if($db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
}
if($db_open==true){
  $result_itemsol = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_solicitem(null,null,"distinct pc11_numero",""," pc22_codorc=$chavepesquisa and pc81_solicitem is null"));
  if($clpcorcamitemsol->numrows>0){
    db_fieldsmemory($result_itemsol,0);
    echo "<script>
            top.corpo.iframe_orcam.location.href = 'com1_orcamento006.php?retorno=$chavepesquisa&pc10_numero=$pc11_numero';
          </script>
         ";
  }else{
    $result_pcorcamitem = $clpcorcam->sql_record($clpcorcam->sql_query_solproc(null,"pc20_codorc","","pc20_codorc=$chavepesquisa and pc22_codorc is null"));
    if($clpcorcam->numrows!=0){
    echo "<script>
            top.corpo.iframe_orcam.location.href = 'com1_orcamento006.php?retorno=$chavepesquisa';
          </script>
          ";
    }else    
    echo "<script>
            alert('Usuário:\\n\\nOrçamento inexistente ou solicitação incluída em processo de compras.\\n\\nAdministrador.');
            top.corpo.iframe_orcam.location.href = 'com1_orcamento006.php';
	  </script>
	 ";
  }
}
?>