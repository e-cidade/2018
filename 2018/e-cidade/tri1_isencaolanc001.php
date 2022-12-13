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
include("classes/db_isencaolanc_classe.php");
include("classes/db_isencao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clisencaolanc = new cl_isencaolanc;
$clisencao = new cl_isencao;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
}
$where = "";
/************************************************************/
if ($origem==1){
	$where = " and v12_numcgm = $valorigem ";
	$inner = " inner join isencaocgm on v12_isencao = v18_isencao ";
	$sqlin = " select distinct v18_cadtipoitem from isencaolanc $inner $where ";
}else	if ($origem==2){
	$where = " and v16_inscr = $valorigem ";
	$inner = " inner join isencaoinscr on v16_isencao = v18_isencao ";
	$sqlin = " select distinct v18_cadtipoitem from isencaolanc $inner $where ";
}else	if ($origem==3){
	$where = " and v15_matric = $valorigem ";
	$inner = " inner join isencaomatric on v15_isencao = v18_isencao ";
	$sqlin = " select distinct v18_cadtipoitem from isencaolanc $inner $where ";
}
if(isset($alterar)){
  $where .= " and v18_sequencial <> $v18_sequencial ";
}
$sql  = " select * ";
$sql .= "   from ( select distinct ";
$sql .= "	               v18_cadtipoitem, ";
$sql .= "							   v18_dtini, ";
$sql .= "							   v18_dtfim ";
$sql .= "			      from isencaolanc ";
$sql .= "						  	 $inner  ";
$sql .= "	         where v18_cadtipoitem = $v18_cadtipoitem $where ";
$sql .= "	       ) as x ";
$sql .= "	 where (v18_dtini::date,v18_dtfim::date) ";
$sql .= "	 overlaps ('".$v18_dtini_ano.'-'.$v18_dtini_mes.'-'.$v18_dtini_dia."'::date,'".$v18_dtfim_ano.'-'.$v18_dtfim_mes.'-'.$v18_dtfim_dia."'::date) ";
/*************************************************************/
if(isset($incluir)){
  $clisencaolanc->sql_record($sql);
	if($clisencaolanc->numrows > 0){
 	  $erro_msg = " Item ja cadastro para este período ! ";
		$sqlerro=true;
	}
  if($sqlerro==false){
    db_inicio_transacao();
    $clisencaolanc->incluir(null);
    $erro_msg = $clisencaolanc->erro_msg;
    if($clisencaolanc->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  $clisencaolanc->sql_record($sql);
	if($clisencaolanc->numrows > 0){
 	  $erro_msg = " Item ja cadastro para este período ! ";
		$sqlerro=true;
	}
  if($sqlerro==false){
    db_inicio_transacao();
    $clisencaolanc->alterar($v18_sequencial);
    $erro_msg = $clisencaolanc->erro_msg;
    if($clisencaolanc->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clisencaolanc->excluir($v18_sequencial);
    $erro_msg = $clisencaolanc->erro_msg;
    if($clisencaolanc->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clisencaolanc->sql_record($clisencaolanc->sql_query($v18_sequencial));
   if($result!=false && $clisencaolanc->numrows>0){
     db_fieldsmemory($result,0);
   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<? include("forms/db_frmisencaolanc.php"); ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$clisencaolanc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clisencaolanc->erro_campo.".focus();</script>";
    }
}
?>