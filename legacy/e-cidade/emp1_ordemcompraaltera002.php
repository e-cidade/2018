<?
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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

require("libs/db_utils.php");
include("classes/db_cgm_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemanu_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_matestoqueitemoc_classe.php");

$clmatordem					= new cl_matordem;
$clmatparam					= new cl_matparam;
$cldb_almoxdepto		= new cl_db_almoxdepto;
$clmatordemanu			= new cl_matordemanu;
$clmatordemitem			= new cl_matordemitem;
$clempempenho				= new cl_empempenho;
$clcgm							= new cl_cgm;
$cldbdepart 				= new cl_db_depart;
$clmatestoqueitemoc = new cl_matestoqueitemoc;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if (isset($altera)){
  db_inicio_transacao();
  $valor_total=0;
  $sqlerro = false;
  $dados=split("quant_","$valores");
  $valordoitem=split("valor_","$val");
  for ($i=1;$i<sizeof($dados);$i++){
    if ($sqlerro==false){
      $numero=split("_",$dados[$i]);
      $numemp=$numero[0];
      $sequen=$numero[1];
      $quanti=$numero[3];
      $vlsoitem=split("_",$valordoitem[$i]);
      $vl_soma_item=$vlsoitem[1];
      if(strpos(trim($vl_soma_item),',')!=""){
	        $vl_soma_item=str_replace('.','',$vl_soma_item);
	        $vl_soma_item=str_replace(',','.',$vl_soma_item);
      }
      $valor = "";
      for($x=0; $x < strlen($vl_soma_item); $x++){
           if(is_numeric($vl_soma_item[$x])||$vl_soma_item[$x]=="."){
               $valor .= $vl_soma_item[$x];
           }
      }

      $vl_soma_item = $valor*1;
      $valor_total += $vl_soma_item;
   }
}

$prazoentrega=$m51_prazoent;
$result_ordem=$clmatordem->sql_record($clmatordem->sql_query_file("","*","","m51_codordem=$m51_codordem"));

db_fieldsmemory($result_ordem,0);

 $coddepto1=$coddepto;
 $obs1=$obs;


if ($sqlerro==false){
  if (strpos(trim($valor_total),',')!=""){
	 $valor_total=str_replace('.','',$valor_total);
	 $valor_total=str_replace(',','.',$valor_total);
  }
  $clmatordem->m51_codordem = $m51_codordem;
  $clmatordem->m51_data = $m51_data;
  $clmatordem->m51_depto = $coddepto1;
  $clmatordem->m51_numcgm = $m51_numcgm;
  $clmatordem->m51_obs = $obs1;
  $clmatordem->m51_valortotal = $valor_total;
  $clmatordem->m51_prazoent = $prazoentrega;
  $clmatordem->alterar($m51_codordem);
  $erro_msg = $clmatordem->erro_msg;
  if($clmatordem->erro_status==0){
    $sqlerro=true;
  }
}

for ($i=1;$i<sizeof($dados);$i++){
  if ($sqlerro==false){
    $numero=split("_",$dados[$i]);
    $numemp=$numero[0];
    $sequen=$numero[1];
    $quanti=$numero[3];
    $vlsoitem=split("_",$valordoitem[$i]);
    $vl_soma_item=$vlsoitem[1];

    $result_item=$clmatordemitem->sql_record($clmatordemitem->sql_query_file(null,"*",null,"m52_codordem = $m51_codordem and  m52_numemp = $numemp and m52_sequen = $sequen "));
    db_fieldsmemory($result_item,0);

    if ($quanti == 0 || str_replace(',','.', str_replace('.', '', $vl_soma_item)) == 0){
      $clmatordemitem->excluir(null,"m52_codlanc=$m52_codlanc");
      $erroex = $clmatordemitem->erro_msg;
      if ($clmatordemitem->erro_status==0){
        $sqlerro=true;
      }
   }else{
   	if (strpos(trim($vl_soma_item),',')!=""){
	    $vl_soma_item=str_replace('.','',$vl_soma_item);
	    $vl_soma_item=str_replace(',','.',$vl_soma_item);
	}
	if (strpos(trim($quanti),',')!=""){
	    $quanti=str_replace('.','',$quanti);
	    $quanti=str_replace(',','.',$quanti);
	}
    $clmatordemitem->m52_codlanc = $m52_codlanc;
    $clmatordemitem->m52_codordem = $m51_codordem;
    $clmatordemitem->m52_numemp = $numemp;
    $clmatordemitem->m52_sequen = $sequen;
    $clmatordemitem->m52_quant = $quanti;
    $clmatordemitem->m52_valor = $vl_soma_item;
    $clmatordemitem->alterar($m52_codlanc);
     $erro = $clmatordemitem->erro_msg;
    if ($clmatordemitem->erro_status==0){
      $sqlerro=true;
    }
    }
  }
  }

//  $sqlerro = true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table style="padding-top:15px;" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" valign="top" bgcolor="#CCCCCC">
      <center>
       <?include("forms/db_frmmatordemaltera.php");?>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($altera)){
    db_msgbox($erro_msg);
    if($clmatordem->erro_campo!=""){
      echo "<script> document.form1.".$clmatordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatordem->erro_campo.".focus();</script>";
    }else{
      echo"<script>top.corpo.location.href='emp1_ordemcompraaltera001.php';</script>";
    }
}
?>
</body>
</html>