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
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_procura(){
	var tipo = document.form1.tipo_filtro.value;
	var cod = document.form1.cod_filtro.value;
	var datainicial =  document.form1.datainicial_ano.value+'-'+document.form1.datainicial_mes.value+'-'+document.form1.datainicial_dia.value;
	var datafinal =  document.form1.datafinal_ano.value+'-'+document.form1.datafinal_mes.value+'-'+document.form1.datafinal_dia.value;
	//alert('t = '+tipo+' cod = '+cod);
	location.href='cai3_gerfinanc023.php?tipo_filtro='+tipo+'&cod_filtro='+cod+'&datainicial='+datainicial+'&datafinal='+datafinal;

}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<br><br>
<form name="form1" method="post" >
<input name="tipo_filtro" type="hidden" value="<?=$tipo_filtro?>">
<input name="cod_filtro"  type="hidden" value="<?=$cod_filtro?>">

    <table width="36%" border="0" cellspacing="0" cellpadding="0" >
      <tr> 
        <td width="12%"  nowrap><strong>Data Inicial:</strong></td>
        <td width="88%" > 
          <?
	  db_inputdata("datainicial",'','','',true,'text',2);
	  ?>
        </td>
      </tr>
      <tr> 
        <td  nowrap><strong>Data Final:</strong></td>
        <td > 
          <?
	  db_inputdata("datafinal",date('d',db_getsession("DB_datausu")),date('m',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu")),true,'text',2);
	  ?>
        </td>
      </tr>
      <tr> 
        <td class="tabs" nowrap>&nbsp;</td>
        <td height="30" >
           <input name="procurar" type="button" id="procurar" value="Procurar" onclick="js_procura();">
        </td>
      </tr>
    </table>
</form>

<?

if(isset($DB_ERRO)) {
  ?>
  <script>
    alert('<?=$DB_ERRO?>');
    parent.document.getElementById('processando').style.visibility = 'visible';
	history.back();
  </script>
  <?
}
?>