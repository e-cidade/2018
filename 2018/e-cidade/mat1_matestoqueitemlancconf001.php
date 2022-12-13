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
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_transmater_classe.php");
include("classes/db_empempitem_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinimei = new cl_matestoqueinimei;
$cldb_depart = new cl_db_depart;
$cltransmater = new cl_transmater;
$clempempitem = new cl_empempitem;
$db_opcao = 1;
$db_botao = true;
$passou=false;
if(isset($incluir)){
  if(isset($m60_codmater) && trim($m60_codmater)!=""){
    $sqlerro = false;
    db_inicio_transacao();
    $result_matestoque = $clmatestoque->sql_record($clmatestoque->sql_query_file(null,"m70_codigo,m70_quant,m70_valor","","m70_codmatmater=$m60_codmater and m70_coddepto=$coddepto"));
    if($clmatestoque->numrows>0){
      db_fieldsmemory($result_matestoque,0);
      $quant = 0;
      $valor = 0;
      $quant = $m70_quant+$m71_quant;
      if ($quant > 0){
	   $valor = $m70_valor+$m71_valor;
      }
      $clmatestoque->m70_valor = "$valor";
      $clmatestoque->m70_quant = "$quant";
      $clmatestoque->m70_codigo= $m70_codigo;
      $clmatestoque->alterar($m70_codigo);
      if($clmatestoque->erro_status==0){
        $sqlerro=true;
      }
      $erro_msg = $clmatestoque->erro_msg;
    }else{
      $clmatestoque->m70_codmatmater = $m60_codmater;
      $clmatestoque->m70_coddepto    = $coddepto;
      $clmatestoque->m70_valor       = $m71_valor;
      $clmatestoque->m70_quant       = $m71_quant;
      $clmatestoque->incluir(null);
      if($clmatestoque->erro_status==0){
        $sqlerro=true;
      }
      $m70_codigo = $clmatestoque->m70_codigo;
      $erro_msg   = $clmatestoque->erro_msg;
    }
    if($sqlerro == false){
      $clmatestoqueini->m80_login          = db_getsession("DB_id_usuario");
      $clmatestoqueini->m80_data           = date("Y-m-d",db_getsession("DB_datausu"));
      $clmatestoqueini->m80_hora           = date('H:i:s');
      $clmatestoqueini->m80_obs            = $m80_obs;
      $clmatestoqueini->m80_codtipo        = $m80_codtipo;
      $clmatestoqueini->m80_coddepto       = $coddepto;
      $clmatestoqueini->incluir(@$m80_codigo);
      if($clmatestoqueini->erro_status==0){
        $sqlerro=true;
      }
      $m82_matestoqueini = $clmatestoqueini->m80_codigo;
      $erro_msg = $clmatestoqueini->erro_msg;
    }
    if($sqlerro == false){
      if(isset($m70_codigo) && trim($m70_codigo)!=""){	
	$clmatestoqueitem->m71_codmatestoque = $m70_codigo;
	$clmatestoqueitem->m71_data          = date("Y-m-d",db_getsession("DB_datausu"));
	$clmatestoqueitem->m71_valor         = $m71_valor;
	$clmatestoqueitem->m71_quant         = $m71_quant;
	$clmatestoqueitem->m71_quantatend    = '0';
	$clmatestoqueitem->incluir(null);
	if($clmatestoqueitem->erro_status==0){
	  $sqlerro=true;
	}
        $m80_matestoqueitem = $clmatestoqueitem->m71_codlanc;
        $erro_msg           = $clmatestoqueitem->erro_msg;
      }
      if($sqlerro == false){
	$clmatestoqueinimei->m82_matestoqueitem = $m80_matestoqueitem;
	$clmatestoqueinimei->m82_matestoqueini  = $m82_matestoqueini;
	$clmatestoqueinimei->m82_quant          = $m71_quant;
	$clmatestoqueinimei->incluir(@$m82_codigo);
        if($clmatestoqueinimei->erro_status==0){
          $erro_msg = $clmatestoqueiniimei->erro_msg;
	  $sqlerro=true;
	}
      }
    }
    if ($sqlerro==false){
      $passou=true;
    }
    db_fim_transacao($sqlerro);
//    exit;
  }else{
    $sqlerro = true;
    $erro_msg = "Usuário: \\n\\nCódigo do material não informado.\\n\\nAdministrador:";
  }
}
if (!isset($coddepto)||$coddepto==""){
  $result_departamento = $cldb_depart->sql_record($cldb_depart->sql_query_file(db_getsession("DB_coddepto"),"coddepto,descrdepto"));
  db_fieldsmemory($result_departamento,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.m60_codmater.focus();" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form action="" name="form1" method="POST">
<table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td>
    <center>
    <?
    include "mat1_matestoqueitemlancconf_iframe.php";
    ?>
    </center>
  </td>
</tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_valores(){
  itens = document.form1;
  qtd   = document.form1.length;
  for(x=0;x < qtd ;x++){
    if(itens[x].type=='radio' &&  itens[x].checked==true){
      alert(itens[x].name);
      alert(itens[x].value);
      if(x > 5){
        break;
      }
    }
  }
  return false;
}
</script>