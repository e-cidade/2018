<?php
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
require("libs/db_app.utils.php");
require_once ("libs/db_utils.php");
require("classes/db_caracter_classe.php");


$comboCaracter = new cl_arquivo_auxiliar();


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
db_app::load('estilos.css, grid.style.css');
?>
<script type="text/javascript">

function js_emite_relatorio() {

  var lCaracter = document.form1.lCaracter;
  var virgula  = "";
  var lista    = "";

  for(var i = 0; i < lCaracter.length; i++) {

    lista  += virgula+lCaracter.options[i].value;
    virgula = ",";
  
  }

  window.open('agu2_relimoveiscar002.php?lista='+lista,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="POST" action="" onsubmit="return js_importar_dados()" enctype="multipart/form-data">
<? 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>

<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" width="700">
<?php 
      $comboCaracter->cabecalho      = "<strong>Caracter&iacute;sticas</strong>";
      $comboCaracter->codigo         = "j31_codigo"; //chave de retorno da func
      $comboCaracter->descr          = "j31_descr"; //chave de retorno
      $comboCaracter->nomeobjeto     = "lCaracter";
      $comboCaracter->funcao_js      = "js_mostra";
      $comboCaracter->funcao_js_hide = "js_mostra1";
      $comboCaracter->func_arquivo   = "func_caracter.php"; //func a executar
      $comboCaracter->nomeiframe     = "db_iframe_caracter";
      $comboCaracter->db_opcao       = 1;
      $comboCaracter->tipo           = 2;
      $comboCaracter->top            = 0;
      $comboCaracter->linhas         = 10;
      $comboCaracter->vwidth         = 420;
      $comboCaracter->passar_query_string_para_func = "&iGrupo=80"; 
      $comboCaracter->funcao_gera_formulario ();

?>
</table>

<table align="center" width="350">

<tr>
  <td colspan="2" align ="center">
    <input type="button" value="Gerar Relatorio" onclick="js_emite_relatorio()" />
  </td>
</tr>

</table>

</form>

</body>
</html>