<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clpcorcam          = new cl_pcorcam();
$clpcorcamitem      = new cl_pcorcamitem();
$clsolicitem        = new cl_solicitem();
$clpcorcamitemproc  = new cl_pcorcamitemproc();
$clpcprocitem       = new cl_pcprocitem();
$clempautitem       = new cl_empautitem();
$clpcorcamtroca     = new cl_pcorcamtroca();
$clpcorcamval       = new cl_pcorcamval();
$clpcorcamjulg      = new cl_pcorcamjulg();
$oDaoProcessoCompra = new cl_pcproc();

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;
$txt_guardaitem = "";
$vir_guardaitem = "";

if (isset($incluir) || isset($alterar)) {

  unset($autoinsert);

  if($sqlerro==false){
    db_inicio_transacao();
    if((isset($alterar) && trim($valores)!="") || isset($incluir)){
      $arr_dad = split(",",$valores);

      for($i=0;$i<sizeof($arr_dad);$i++){
      	if(isset($alterar)){
      	  $result_exist = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null,"pc22_codorc,pc22_orcamitem,pc31_orcamitem",null," pc31_pcprocitem=".$arr_dad[$i]." and pc22_codorc=$pc22_codorc"));
      	  $incluir = "incluir";
      	  $txt_guardaitem .= $vir_guardaitem.$arr_dad[$i];
      	  $vir_guardaitem  = ",";
      	  if($clpcorcamitemproc->numrows>0){
      	    unset($incluir);
      	  }
      	}
      	if(isset($incluir)){
      	  if($sqlerro==false){
      	    $clpcorcamitem->pc22_codorc    = $pc22_codorc;
      	    $clpcorcamitem->incluir(null);
      	    $pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;
      	    $erro_msg = $clpcorcamitem->erro_msg;
      	    if($clpcorcamitem->erro_status==0){
      	      $sqlerro=true;
      	      break;
      	    }
      	  }
      	  if($sqlerro==false){
      	    $clpcorcamitemproc->pc31_orcamitem = $pc22_orcamitem;
      	    $clpcorcamitemproc->pc31_pcprocitem = $arr_dad[$i];
      	    $clpcorcamitemproc->incluir($pc22_orcamitem,$arr_dad[$i]);
      	    if($clpcorcamitemproc->erro_status==0){
      	      $erro_msg = $clpcorcamitemproc->erro_msg;
      	      $sqlerro=true;
      	      break;
      	    }
      	  }
      	}
      }

      $clpcorcam->pc20_prazoentrega=$pc20_prazoentrega;
      $clpcorcam->pc20_validadeorcamento=$pc20_validadeorcamento;
      $clpcorcam->pc20_codorc=$pc22_codorc;
      $clpcorcam->alterar($pc22_codorc);

      if($clpcorcam->erro_status==0){
          $erro_msg = $clpcorcam->erro_msg;
          $sqlerro=true;
      }
    }

    if((isset($alterar) && trim($txt_guardaitem)!="") || (isset($alterar) && trim($valores)=="")){

      $where_guardaitem = "";
      if(trim($txt_guardaitem)!=""){
	      $where_guardaitem = " pc81_codprocitem not in ($txt_guardaitem) and ";
      }
      $result_itensexcluir = $clpcprocitem->sql_record($clpcprocitem->sql_query_orcam(null,"pc22_orcamitem","pc22_orcamitem","$where_guardaitem  pc22_codorc=$pc22_codorc "));
      $numrows_itensexcluir = $clpcprocitem->numrows;

      for($i=0;$i<$numrows_itensexcluir;$i++){
      	db_fieldsmemory($result_itensexcluir,$i);
      	if($sqlerro==false){
      	  $clpcorcamtroca->excluir(null,"pc25_orcamitem = $pc22_orcamitem");
      	  if($clpcorcamtroca->erro_status==0){
      	    $erro_msg = $clpcorcamtroca->erro_msg;
      	    $sqlerro=true;
      	    break;
      	  }
      	}
      	if($sqlerro==false){
      	  $clpcorcamval->excluir(null,$pc22_orcamitem);
      	  if($clpcorcamval->erro_status==0){
      	    $erro_msg = $clpcorcamval->erro_msg;
      	    $sqlerro=true;
      	    break;
      	  }
      	}
      	if($sqlerro==false){
      	  $clpcorcamjulg->excluir($pc22_orcamitem,null);
      	  if($clpcorcamjulg->erro_status==0){
      	    $erro_msg = $clpcorcamjulg->erro_msg;
      	    $sqlerro=true;
      	    break;
      	  }
      	}
      	if($sqlerro==false){
      	  $clpcorcamitemproc->excluir($pc22_orcamitem,null);
      	  if($clpcorcamitemproc->erro_status==0){
      	    $erro_msg = $clpcorcamitemproc->erro_msg;
      	    $sqlerro=true;
      	    break;
      	  }
      	}
      	if($sqlerro==false){
      	  $clpcorcamitem->excluir($pc22_orcamitem);
      	  if($clpcorcamitem->erro_status==0){
      	    $erro_msg = $clpcorcamitem->erro_msg;
      	    $sqlerro=true;
      	    break;
      	  }
      	}
      }
    }

    db_fim_transacao($sqlerro);
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
  	<?php
  	  include "forms/db_frmitensproc.php";
  	?>
  </body>
</html>
<?php

if(isset($alterar) || isset($excluir) || isset($incluir)){
  if($sqlerro==true){
    if(isset($alterar)){
      $erro_msg = str_replace("Inclusao","Alteracao",$erro_msg);
      $erro_msg = str_replace("Exclusão","Alteracao",$erro_msg);
    }
    db_msgbox($erro_msg);
  }
}
?>