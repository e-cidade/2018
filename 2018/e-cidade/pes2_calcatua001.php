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
include("dbforms/db_classesgenericas.php");
$gform = new cl_formulario_rel_pes;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  js_gerar_consrel();
  qry  = "?" + document.form1.valores_campos_rel.value;
  qry += "&banco="+ document.form1.banco.value;
  js_OpenJanelaIframe('top.corpo','db_iframe_calcatua','pes2_calcatua002.php'+qry,'Gerando Arquivo',true);
}
function js_detectaarquivo(arquivo,arquivo1,arquivo2){
  //js_controlarodape(false);
  top.corpo.db_iframe_calcatua.hide();
  listagem  = arquivo+"#Download calc_ativos.txt|";
  listagem += arquivo1+"#Download calc_inativos.txt|";
  listagem += arquivo2+"#Download calc_pens.txt";
  js_montarlista(listagem,"form1");
}
function js_erro(msg){
  //js_controlarodape(false);
  top.corpo.db_iframe_calcatua.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_calcatua.hide();
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
<center>
<table border="0">
  <tr>
    <td nowrap colspan="2">
    <?
  $gform->selecao = true;
  $gform->desabam = false;
  $gform->manomes = true;
  $gform->gera_form(db_anofolha(),db_mesfolha());
  ?>
</table>
</center>
  <table  align="center">
      <tr>
        <td nowrap align="left" title="Bancos">
        </td>
        <td> 
          <?
          $x = array("1"=>"Banco do Brasil","2"=>"Caixa Federal");
          db_select('banco',$x,true,2,"");
          ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

    </table>
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>