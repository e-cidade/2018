<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_propricemit_classe.php"));
include(modification("classes/db_itenserv_classe.php"));
include(modification("classes/db_txossoariojazigo_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clitenserv         = new cl_itenserv;
$clpropricemit      = new cl_propricemit;
$cltxossoariojazigo = new cl_txossoariojazigo;
$cltaxaservval      = new cl_taxaservval;

$db_opcao = 1;
$db_botao = true;
$clitenserv->cm10_i_usuario = db_getsession("DB_id_usuario");

if(isset($incluir)){

  //gera o numpre
  db_inicio_transacao();

  $result_numpre = db_query("select nextval('numpref_k03_numpre_seq')");
  db_fieldsmemory($result_numpre,0);
  $clitenserv->cm10_i_numpre    = $nextval;
  $clitenserv->cm10_d_dtlanc    = date("Y-m-d", db_getsession("DB_datausu"));
  $cm10_f_valortaxa             = str_replace(",","",$cm10_f_valortaxa);
  $clitenserv->cm10_f_valortaxa = str_replace(",",".",$cm10_f_valortaxa);

  //cadastra
  $clitenserv->incluir($cm10_i_codigo);

  //txossoariojazio
  $cltxossoariojazigo->cm30_i_ossoariojazigo = $cm28_i_ossoariojazigo;
  $cltxossoariojazigo->cm30_i_itenserv       = $clitenserv->cm10_i_codigo;
  $cltxossoariojazigo->incluir(null);
  db_fim_transacao();

  if($clitenserv->numrows_incluir != 0){  //!= 0
   db_inicio_transacao();
   //gera arrecad
   $result_arrecad=db_query("select fc_cemitarrecad(1,$nextval,true) as retorno") or die("Erro ao incluir em arrecad.");
   db_fieldsmemory( $result_arrecad, 0 );
   if( substr( $retorno, 0, 1 ) != '9' ){
     db_msgbox($retorno);
   }
   db_fim_transacao();
  }

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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <br><br>
     <?
     include(modification("forms/db_frmitenserv111.php"));
     ?>
    </center>
     </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clitenserv->erro_status=="0"){
    $clitenserv->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clitenserv->erro_campo!=""){
      echo "<script> document.form1.".$clitenserv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitenserv->erro_campo.".focus();</script>";
    };
  }else{

    db_msgbox( $clitenserv->erro_msg );
    echo "<script>";
    echo " parent.document.formaba.a2.disabled=true; ";
    echo " parent.document.formaba.a3.disabled=true; ";
    echo " (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href='cem1_propricemit001.php?db_opcao=1';";
    echo " parent.mo_camada('a1'); ";
    echo "</script>";

  };
};
?>