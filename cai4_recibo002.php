<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_sql.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
db_app::load("scripts.js, strings.js, prototype.js");
db_app::load("estilos.css, grid.style.css");
?>
<script>
function js_removelinha(linha) {

  var tab = (document.all)?document.all.tab:document.getElementById('tab');
  for (i = 0; i < tab.rows.length; i++) {
  
    if(linha == tab.rows[i].id) {
    
      tab.deleteRow(i);
	    break;
	  }
  }
}
</script>
<style type="text/css">
.table-box {
  padding:1px;
  border-right:1px inset black;
  border-bottom:1px inset black;
  border-bottom:1px outset white;
  border-right:1px outset white;           
  background-color:#FFFFFF;    
  cursor: default;  
  empty-cells: show;
}
</style>
</head>
<body bgcolor=#CCCCCC bgcolor="#AAAF96" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form name="form1" method="post" action="">		   
  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="tab" align="center" class="table-box">
    <tr class="table_header"> 
      <th class="linhagrid" width="46" align="center" nowrap>Receita</th>
      <th class="linhagrid" width="240" align="center" nowrap>Descri&ccedil;&atilde;o</th>
      <th class="linhagrid" width="15" align="center" nowrap>Rec</th>
      <th class="linhagrid" width="36" align="center" nowrap>Taxa</th>
      <th class="linhagrid" width="177" align="center" nowrap>Descri&ccedil;&atilde;o</th>
      <th class="linhagrid" width="109" align="center" nowrap>Valor</th>
      <th class="linhagrid" width="109" align="center" nowrap>CP/CA</th>
      <th class="linhagrid" width="67" align="center" nowrap>Cancela</th>
    </tr>
  </table>
  <input name="carregado" type="hidden" id="" value="">
  </form>
</center>			
</body>
</html>