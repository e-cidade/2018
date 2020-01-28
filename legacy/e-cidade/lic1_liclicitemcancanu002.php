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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("classes/db_liclicitem_classe.php");
require_once ("classes/db_liclicitemanu_classe.php");
require_once ("classes/db_pcorcamitemlic_classe.php");
require_once ("classes/db_pcorcam_classe.php");
require_once ("classes/db_pcorcamitem_classe.php");

$clliclicitem             = new cl_liclicitem;
$clliclicitemanu          = new cl_liclicitemanu;
$clpcorcamitemlic         = new cl_pcorcamitemlic;
$clpcorcam                = new cl_pcorcam;     
$clpcorcamitem            = new cl_pcorcamitem; 
$cliframe_seleciona_itens = new cl_iframe_seleciona;

$clliclicitemanu->rotulo->label();

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$action   = "lic1_liclicitemcancanu002.php";
$db_botao = false;

if (isset($confirmar) && trim(@$confirmar) != "") {
  
  $sqlerro  = false;
  $erro_msg = "";

  db_inicio_transacao();

  $vetor_itens = split(",",$coditens);
  
  for ($i = 0; $i < count($vetor_itens); $i++) {
    
    $clliclicitemanu->excluir(null,"l07_liclicitem in (".trim($vetor_itens[$i]).")");

    if ($clliclicitemanu->erro_status == "0") {
      
      $sqlerro  = true;
      $erro_msg = $clliclicitemanu->erro_msg;
      break;
    }

    if ($sqlerro == false) {
      
      $clliclicitem->l21_codigo   = trim($vetor_itens[$i]); 
      $clliclicitem->l21_situacao = "0";
      $clliclicitem->alterar(trim($vetor_itens[$i]));
      
      if ($clliclicitem->erro_status == "0") {
        
        $sqlerro  = true;
        $erro_msg = $clliclicitem->erro_msg;
        break;
      }
    }

    if ($sqlerro == false) {
      
      $res_pcorcam        = $clpcorcam->sql_record($clpcorcamitemlic->sql_query(null,"distinct pc20_codorc",null,"l20_codigo = $l20_codigo"));
      $res_pcorcamitemlic = $clpcorcamitemlic->sql_record($clpcorcamitemlic->sql_query(null,"l21_codigo","l21_codigo","pc26_liclicitem = ".trim($vetor_itens[$i])));
      
      if ($clpcorcamitemlic->numrows == 0 && $clpcorcam->numrows > 0) {
        
        db_fieldsmemory($res_pcorcam,0);                    

		    $clpcorcamitem->pc22_codorc = $pc20_codorc;
      	$clpcorcamitem->incluir(null);
		    $pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;
		    
		    if ($clpcorcamitem->erro_status == "0") {
		      
		      $sqlerro  = true;
		      $erro_msg = $clpcorcamitem->erro_msg;
          break;
		    }

		    if ($sqlerro == false) {
		      
		      $clpcorcamitemlic->pc26_orcamitem  = $pc22_orcamitem;
		      $clpcorcamitemlic->pc26_liclicitem = trim($vetor_itens[$i]);
	        $clpcorcamitemlic->incluir();
		      if ($clpcorcamitemlic->erro_status == "0") {
		        
		        $sqlerro  = true;
		        $erro_msg = $clpcorcamitemlic->erro_msg;
            break;
		      }
		    }
      }
    }
  }

  if ($sqlerro == false){
    $erro_msg = "Cancelamento feito com sucesso.";
  }

  db_fim_transacao($sqlerro);
  
  echo "<script>location.href='lic1_liclicitemcancanu002.php?l20_codigo=$l20_codigo&erro_msg=$erro_msg';</script>";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_confirmar() {
  
  var tamanho     = itens.document.form1.elements.length;
  var lista_itens = "";
  var virgula     = "";
  var contador    = 0;
  var erro        = true;

  for (var i = 0; i < tamanho; i++) {
    
    if (itens.document.form1.elements[i].type == "checkbox") {
      
      if (itens.document.form1.elements[i].checked == true) {
        
        lista_itens += virgula + itens.document.form1.elements[i].value;
        virgula      = ", ";
        contador++;
      }
    }
  }

  if (contador == 0) {
    
    alert("Selecione um item");
    erro = false;
  }

  if (erro == true) {
    
    document.form2.coditens.value = lista_itens;
    document.form2.submit();
  }    

  return erro;
}
</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.l20_codigo.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form2" method="post" action="<?=($action)?>">
<table border='0'>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
<?
db_input("l20_codigo",10,"",true,"hidden",3);
db_input("coditens", 500,"",true,"hidden",3);
?>
    <td colspan="2">
<?
     $campos        = "l21_codigo,pc01_descrmater,pc11_resum";
     $sql           = $clliclicitem->sql_query_anulados(null,"$campos","l21_codigo","l21_codliclicita=$l20_codigo and l08_altera is true");
     $sql_disabled  = $clliclicitem->sql_query_anulados(null,"$campos","l21_codigo","l21_codliclicita=$l20_codigo and l08_altera is true and l21_situacao = 0");
     $res_itens     = $clliclicitem->sql_record($sql);
     $numrows_itens = $clliclicitem->numrows;
     $res_itens_nao_anulados     = $clliclicitem->sql_record($sql_disabled);
     $numrows_itens_nao_anulados = $clliclicitem->numrows;

     if ($numrows_itens == 0 || $numrows_itens == $numrows_itens_nao_anulados){
       $db_botao = true;
     }

     $cliframe_seleciona_itens->campos        = $campos;
     $cliframe_seleciona_itens->legenda       = "&nbsp;&nbsp;Itens da Licitacao N".chr(176)." ".$l20_codigo."&nbsp;&nbsp;";
     $cliframe_seleciona_itens->sql           = $sql;
     $cliframe_seleciona_itens->sql_disabled  = $sql_disabled;
     $cliframe_seleciona_itens->iframe_height = "400";
     $cliframe_seleciona_itens->iframe_width  = "700";
     $cliframe_seleciona_itens->iframe_nome   = "itens";
     $cliframe_seleciona_itens->chaves        = "l21_codigo";
     $cliframe_seleciona_itens->js_marcador   = "";
     $cliframe_seleciona_itens->dbscript      = "";
     $cliframe_seleciona_itens->iframe_seleciona(1);
?>
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td colspan="2" align="center">
      <input name="confirmar" type="submit" onClick="return js_confirmar();" value="Confirmar" <?=($db_botao == true?"disabled":"")?>>
      <input name="voltar"    type="button" onClick="location.href='lic1_liclicitemcancanu001.php';" value="Voltar">
<?
   if (trim(@$erro_msg) != ""){
        db_msgbox($erro_msg);
   }
?>
    </td>
  </tr>
</table>
</form>
</center>
<? 
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
</script>
</body>
</html>