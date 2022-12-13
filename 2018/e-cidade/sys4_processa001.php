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

$rstab = pg_exec("select d.nomemod,m.codarq,a.nomearq,a.tipotabela,a.naolibclass,a.naolibform,a.naolibfunc,a.naolibprog
                  from   db_sysarquivo a
                         inner join db_sysarqmod m on a.codarq = m.codarq
                         inner join db_sysmodulo d on d.codmod = m.codmod 
			 where ativo is true
	          order by nomemod,nomearq");

function db_fputs($variavel,$conteudo){

  $GLOBALS['fd'] .= $conteudo;

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
.tabela {border:1px solid black; top:25px; left:150}
.input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
.tdblack {

    border-bottom:1px solid black;

}
.cl_iframe {
   border: 1px solid #999999;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_executa(valor){
  tabelas.location.href = 'sys4_processa011.php?modulo_testa='+valor;
}
</script>
</head>
<body bgcolor=#CCCCCC  marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="js_trocacordeselect()">
<form method="post" name="form1" onsubmit="return valida_submit();" >
<table width=790 bgcolor="#CCCCCC">
  <tr>
     <td height=5>&nbsp;</td>
  </tr>		
</table>
<br><br>
<center>
<fieldset style="width:400px">
  <legend>
    <b>Gerador de Programas : </b>
  </legend>

<table width="80%" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc" style=''>
<tr> 
   <td width="20%" align='center' style=''><b>Módulos:</b></td>
   <td width="80%" align='center' style=''><b>Tabelas:</b></td>
</tr>
<tr>
<td valign="top" width="20%" style=''>
 <? 
   $rsmod = pg_exec("select m.codmod,m.nomemod 
     	                from   db_sysmodulo m
	               	       inner join db_sysarqmod s on s.codmod = m.codmod
			       where ativo is true
		        group by m.codmod,m.nomemod
   	                order by nomemod");
      echo  "<select  name='modulos' size='20' onchange=\"js_executa(document.form1.modulos.value);\">";
     for ($i = 0;$i < pg_numrows($rsmod); $i++) {
          echo "<option value='".trim(pg_result($rsmod,$i,"codmod"))."'>".trim(pg_result($rsmod,$i,"nomemod"))."</option>\n";  
      }
 ?>
  </select>
</td>
<td width="80%" valign="top" style=''>
  <iframe  style="" name="tabelas" src="sys4_processa011.php" id="tabelas" width="800" height="340"></iframe>
</td>
</tr>
</table>
</fieldset>
</center>
</form> 
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisaitemcad(chave,qmodulo){
  db_iframe.hide();
  tabelas.document.getElementById('caditem').value = chave;
  tabelas.document.getElementById('cadmodulo').value = qmodulo;
  tabelas.document.getElementById('g_item').checked = chave;
}

</script>