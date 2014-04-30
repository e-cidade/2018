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
include("classes/db_solicita_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcprocitem_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamtroca_classe.php");
include("classes/db_empautitempcprocitem_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);db_postmemory($HTTP_GET_VARS,2);
$clsolicita = new cl_solicita;
$clsolicitem = new cl_solicitem;
$clpcprocitem = new cl_pcprocitem;
$clempautitempcprocitem = new cl_empautitempcprocitem;
$clpcproc = new cl_pcproc;
$clpcorcam = new cl_pcorcam;
$clpcorcamforne = new cl_pcorcamforne;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcorcamval = new cl_pcorcamval;
$clpcorcamjulg = new cl_pcorcamjulg;
$clpcorcamtroca = new cl_pcorcamtroca;
$db_opcao=33;
$db_botao=false;
if(isset($pc80_codproc) && trim($pc80_codproc)!=""){
  $db_opcao=3;
  $db_botao=true;
}
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();  
  $result_orcamitem = $clpcprocitem->sql_record($clpcprocitem->sql_query_orcam(null,"distinct pc22_codorc as pc20_codorc","pc22_codorc","pc81_codproc=$pc80_codproc"));
  $numrows_orcamitem = $clpcprocitem->numrows;
  for($i=0;$i<$numrows_orcamitem;$i++){
    db_fieldsmemory($result_orcamitem,$i);
    if($sqlerro==false){
      $clpcorcamtroca->excluir(null,"pc25_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.")");
      if($clpcorcamtroca->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpcorcamtroca->erro_msg;
      }
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
    if($sqlerro==false){
      $clpcorcamitemproc->excluir(null,null,"pc31_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.")");
      if($clpcorcamitemproc->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpcorcamitemproc->erro_msg;
      }
    }
    if($sqlerro==false){
      $clpcorcamitem->excluir(null," pc22_codorc=$pc20_codorc ");
      $erro_msg = $clpcorcamitem->erro_msg;
      if($clpcorcamitem->erro_status==0){
        $sqlerro=true;
      }
    }
    if($sqlerro==false){
      $clpcorcamforne->excluir(null,"pc21_codorc=$pc20_codorc ");
      if($clpcorcamforne->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpcorcamforne->erro_msg;
      }
    }
    if($sqlerro==false){
      $clpcorcam->excluir($pc20_codorc);
      $erro_msg = $clpcorcam->erro_msg;
      if($clpcorcam->erro_status==0){
        $sqlerro=true;
      }
    }
  }
  /**
   * exclui ligacao do processo de compras com a autorizacao de empenho 
   */
  $sWhereEmpautitem  = "e73_pcprocitem in (";
  $sWhereEmpautitem .= "                   select distinct pc81_codprocitem ";
  $sWhereEmpautitem .= "                     from pcprocitem ";
  $sWhereEmpautitem .= "                    where pc81_codproc={$pc80_codproc}";
  $sWhereEmpautitem .= "                   )";
  $clempautitempcprocitem->excluir(null, $sWhereEmpautitem);
  $clpcprocitem->excluir(null,"pc81_codproc=".$pc80_codproc);
  if($clpcprocitem->erro_status==0){
    $erro_msg   = $clpcprocitem->erro_msg; 
    $sqlerro=true;
  } 
  if($sqlerro==false){
    $clpcproc->excluir($pc80_codproc);
    $erro_msg   = $clpcproc->erro_msg; 
    if($clpcproc->erro_status==0){
      $sqlerro=true;
    } 
  }  
  db_fim_transacao($sqlerro);
}
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="450" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmexcpcproc.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
</script>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clpcproc->erro_campo!=""){
      echo "<script> document.form1.".$clpcproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcproc->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox("Processo de Compras excluído com sucesso");
    echo "<script>top.corpo.location.href='com1_pcproc003.php'</script>";
  }
}
if($db_opcao==33){
  echo "<script>";
  echo "  document.form1.pesquisar.click();";
  echo "</script>";
}
?>