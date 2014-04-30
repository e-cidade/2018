<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_vac_aplica_classe.php");
include("classes/db_vac_aplicalote_classe.php");
include("classes/db_vac_vacinadoserestricao_classe.php");
include("classes/db_vac_sala_classe.php");
include("classes/db_vac_dependencia_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$clvac_aplica              = new cl_vac_aplica;
$clvac_sala                = new cl_vac_sala;
$clvac_dependencia         = new cl_vac_dependencia;
$clvac_aplicalote          = new cl_vac_aplicalote;
$clvac_vacinadoserestricao = new cl_vac_vacinadoserestricao;
$db_botao                  = true;
$sTrava                    = "";
$iDepartamento= db_getsession("DB_coddepto");

if (isset($incluir)) {
	
  db_inicio_transacao();
  $clvac_aplica->vc16_c_hora         = date("H:i");
  $clvac_aplica->vc16_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
  $clvac_aplica->vc16_i_usuario      = DB_getsession("DB_id_usuario");
  $clvac_aplica->vc16_i_departamento = $iDepartamento;
  $clvac_aplica->incluir(null);
  if ($clvac_aplica->erro_status != "0") {

    $clvac_aplicalote->vc17_i_aplica = $clvac_aplica->vc16_i_codigo;
    $clvac_aplicalote->incluir(null);
    if ($clvac_aplicalote->erro_status == "0") {
      
      $clvac_aplica->erro_status = 0;
      $clvac_aplica->erro_sql    = $clvac_aplicalote->erro_sql;
      $clvac_aplica->erro_campo  = $clvac_aplicalote->erro_campo;
      $clvac_aplica->erro_banco  = $clvac_aplicalote->erro_banco;
      $clvac_aplica->erro_msg    = $clvac_aplicalote->erro_msg;
 
    }
  }
  db_fim_transacao();
}

if (isset($alterar)) {
	
  db_inicio_transacao();
  $clvac_aplica->alterar($vc16_i_codigo);
  if ($clvac_aplica->erro_status != "0") {

    //seleciona o codigo da aplicação
    $sSql = $clvac_aplicalote->sql_query_file("","vc17_i_codigo",""," vc17_i_aplica=$vc16_i_codigo ");
    $rsAplicaLote = $clvac_aplicalote->sql_record($sSql);
    if ($clvac_aplicalote->numrows > 0) {

      $oAplicaLote                     = db_utils::fieldsmemory($rsAplicaLote,0);
      $clvac_aplicalote->vc17_i_aplica = $vc16_i_codigo;
      $clvac_aplicalote->vc17_i_codigo = $oAplicaLote->vc17_i_codigo;
      $clvac_aplicalote->alterar($oAplicaLote->vc17_i_codigo);
      if ($clvac_aplicalote->erro_status == "0") {

        $clvac_aplica->erro_status = 0;
        $clvac_aplica->erro_sql    = $clvac_aplicalote->erro_sql;
        $clvac_aplica->erro_campo  = $clvac_aplicalote->erro_campo;
        $clvac_aplica->erro_banco  = $clvac_aplicalote->erro_banco;
        $clvac_aplica->erro_msg    = $clvac_aplicalote->erro_msg;

      }

    }
  }
  db_fim_transacao();
}
if (isset($chavepesquisa)) {

  $db_opcao = 2;
  $sSubSqlAplicadas    = ' select coalesce(sum(vc16_n_quant),0) from vac_aplicalote ';
  $sSubSqlAplicadas   .= '    inner join vac_aplica on vc16_i_codigo = vc17_i_aplica';
  $sSubSqlAplicadas   .= ' where vc17_i_matetoqueitemlote = matestoqueitemlote.m77_sequencial ';
  $sSubSqlAplicadas   .= ' and not exists (select * from vac_aplicaanula where vc18_i_aplica=vc17_i_aplica) ';
  $sSubSqlDescartadas  = ' select coalesce(sum(vc19_n_quant),0) from vac_descarte ';
  $sSubSqlDescartadas .= ' where vc19_i_matetoqueitemlote=matestoqueitemlote.m77_sequencial';
  $sSubSql  = " (matestoqueitem.m71_quant*vc29_i_dose) - (($sSubSqlAplicadas)+($sSubSqlDescartadas))";
  
  $sCampos  = " vac_aplica.*,";
  $sCampos .= " matunid.*,";
  $sCampos .= " vac_vacinadose.*,";
  $sCampos .= " vac_sala.*,";
  $sCampos .= " matestoqueitemlote.*,";
  $sCampos .= " cgs_und.z01_v_nome,";
  $sCampos .= " ($sSubSql) as saldo";
  $sSql     = $clvac_aplica->sql_query2($chavepesquisa,$sCampos);
  $result   = $clvac_aplica->sql_record($sSql);
  //echo " SQL = $sSql; ";
  if ($clvac_aplica->numrows > 0) {
  	
    db_fieldsmemory($result,0);
    $db_botao = false;
    $db_opcao = 3;
    
  }else{
  	
  	$sTrava = "disabled";
  	db_msgbox("Erro - Aplicação não encontrada!");
  	
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<table width="435" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="220" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
  <?
  include("forms/db_frmvac_aplica_iframe.php");
  ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1", "vc16_i_dosevacina", true, 1, "vc16_i_dosevacina", true);
</script>
<?
if ((isset($incluir)) || (isset($alterar))) {
  if ($clvac_aplica->erro_status == "0") {

    $clvac_aplica->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clvac_aplica->erro_campo != "") {

      echo "<script> document.form1.".$clvac_aplica->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvac_aplica->erro_campo.".focus();</script>";

    }
  } else {

    $clvac_aplica->erro(true,false);
    echo"<script>parent.js_fechaAplica(1);</script>";

  }
}
?>