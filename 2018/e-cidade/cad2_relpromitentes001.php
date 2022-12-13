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
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form method="post" name="form1" target="relatpropri" action="cad2_relpromitentes002.php">
<table width="60%">
    <tr>
   	   <td align="center"><br><br><font face="Arial, Helvetica, sans-serif"><strong>Relatório de Promitentes</strong></font></td>
    </tr>
    <tr>
      <td><table width="100%">
  <tr>
    <td nowrap title="">
      <fieldset>
      <legend><strong>Opções: </strong></legend>
      <select name="promi" onChange="js_some(this.value)" > 
        <option value ="pp">promitentes principais</option>
        <option value="ps">promitentes secundários</option>
        <option value="ts" selected>todos os promitentes</option>
      </select>
      </legend>
    </td>
  </tr>
  <script>
  function js_some(valor){
    if(valor == 'pp'){
      document.form1.ordem.options[2] = null
    }else{
      document.form1.ordem.options[2] = new Option('quantidade de secundários','qs',true,false);
      if(valor == "ps"){
        document.form1.ordem.options[1] = null;
      }else{
	document.form1.ordem.options[1] = new Option('quantidade de principais','qp',true,false);
      }
    }
    if(valor == "ts"){
      document.form1.ordem.options[1] = null
      document.form1.ordem.options[2] = null
      document.form1.ordem.options[1] = new Option('quantidade de principais','qp',true,false);
      document.form1.ordem.options[2] = new Option('quantidade de secundários','qs',true,false);
    }
  }
  </script>
  <tr>
    <td nowrap width="100%"> 
      <fieldset>
      <legend><strong>Ordem: </strong></legend>
      <select name="ordem">
        <option value="mt">matrículas</option>
        <option value="qp">quantidade de principais</option>
        <option value="qs">quantidade de secundários</option>
      </select>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap width="100%"> 
      <fieldset>
      <legend><strong>Modo: </strong></legend>
      <select name="order">
        <option value="as">ascendente</option>
        <option value="ds">descendente</option>
      </select>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap width="100%"> 
      <fieldset>
      <legend><strong>Tipo: </strong></legend>
      <select name="resumido">
        <option value="t">Resumido</option>
        <option value="f">Completo</option>
      </select>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap width="100%"> 
      <fieldset>
      <legend><strong>Contrato: </strong></legend>
      <select name="contrato">
        <option value="ts">Todos</option>
        <option value="f">Sem contrato</option>
        <option value="t">Com contrato</option>
      </select>
      </fieldset>
    </td>
  </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center"><input name="relatorio" type="submit" value="Relatório" onClick="return js_rel()">
      </td>
      </tr>
  </table>
</form>
<script>
function js_rel(){
  jan = window.open('cad2_relpromitentes002.php','relatpropri','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  return true;
}
</script>
</center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>