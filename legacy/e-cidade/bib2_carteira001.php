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
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_carteira_classe.php");

db_postmemory($HTTP_POST_VARS);

$clcarteira = new cl_carteira;
$depto      = db_getsession("DB_coddepto");
$sql        = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result     = pg_query($sql);;
$linhas     = pg_num_rows($result);

if ($linhas != 0) {
  db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite() {
  qtd = document.form1.alunos.length;
  lista = "";
  sep = "";
  for (i = 0; i < qtd; i++) {
    lista += sep+document.form1.alunos[i].value;
    sep    = ",";
  }
  if (document.form1.tipo[0].checked == true )
    filtro = 1;
  if (document.form1.tipo[1].checked == true)
    filtro = 2;

 if (filtro == 1) {
   jan = window.open('bib2_carteira002.php?lista=' + lista, '', 
		             'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
 } else {
   jan = window.open('bib2_carteira003.php?lista=' + lista, '', 
		             'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
 }
 jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<br>
<center>
<fieldset align="center" style="width:95%"><legend><b>Relatório de Carteiras</b></legend>
<table  align="center" border="0">
 <form name="form1" method="post" action="">
 <tr>
  <td>
   <?
   $sCampos = ' bi16_codigo, nomeleitor, identleitor, cpfleitor ';
   $sSql    = $clcarteira->sql_query_leitor('', $sCampos, 'lower(to_ascii(nomeleitor))',  '',
                                            " bi16_valida = 'S' AND bi17_codigo = $bi17_codigo"
                                           );
                                           
   $result  = $clcarteira->sql_record($sSql);
   ?>
   <b>Leitores:</b><br>
   <select name="alunospossib" id="alunospossib" size="10" onclick="js_desabinc()" 
           style="font-size:9px;width:330px;height:180px" multiple>
    <?
    
    if ($clcarteira->numrows > 0) {
    	    
      for ($i = 0; $i < $clcarteira->numrows; $i++) {
      	
        db_fieldsmemory($result,$i);
        echo "<option value='$bi16_codigo'>$nomeleitor - $cpfleitor</option>\n";
      }
    }
    ?>
   </select>
  </td>
  <td align="center">
   <br>
   <table border="0">
    <tr>
     <td>
      <input name="incluirum" title="Incluir" type="button" value=">" 
             onclick="js_alunospossib();" style="border:1px outset;border-top-color:#f3f3f3;
                                                 border-left-color:#f3f3f3;background:#cccccc;font-size:15px;
                                                 font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" 
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                    font-size:15px;font-weight:bold;width:30px;height:20px;">
     </td>
    </tr>
    <tr><td height="8"></td></tr>
    <tr>
     <td>
      <hr>
     </td>
    </tr>
    <tr><td height="8"></td></tr>
    <tr>
     <td>
      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                    font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" 
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                    font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
   </table>
  </td>
  <td>
   <b>Leitores para impressão:</b><br>
   <select name="alunos" id="alunos" size="10" onclick="js_desabexc()" 
           style="font-size:9px;width:330px;height:180px" multiple>
   </select>
  </td>
 </tr>
 <tr>
  <td colspan="3" align = "center">
   <br>
   <b>Selecione o modelo:</b>
   <br>
   <input type="radio" name="tipo" value="1" checked><label>Modelo 1 (Duas colunas)</label>
   <br>
   <input type="radio" name="tipo" value="2"><label>Modelo 2 (Uma Coluna)</label>&nbsp;&nbsp;&nbsp;
  </td>
 </tr>
 <tr>
  <td colspan="3" align = "center">
   <br>
   <input name="processar" id="processar" type="button" value="Processar" onclick="js_emite();" disabled>
  </td>
 </tr>
 </form>
</table>
</fieldset>
</center>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_alunospossib() {
	
  var Tam = document.form1.alunospossib.length;
  var F = document.form1;
  
  for (x = 0; x < Tam; x++) {
	  
    if (F.alunospossib.options[x].selected == true) {
         
      F.elements['alunos'].options[F.elements['alunos'].options.length] = new Option(F.alunospossib.options[x].text,
    	                                                                             F.alunospossib.options[x].value)
      F.alunospossib.options[x] = null;
      Tam--;
      x--;
    }
  }
  
  if (document.form1.alunospossib.length > 0) {
    document.form1.alunospossib.options[0].selected = true;
  } else {
	  
    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;
    
  }
  
  document.form1.processar.disabled    = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunospossib.focus();
  
}

function js_incluirtodos() {
	
  var Tam = document.form1.alunospossib.length;
  var F = document.form1;
  for (i = 0; i < Tam; i++) {
	  
    F.elements['alunos'].options[F.elements['alunos'].options.length] = new Option(F.alunospossib.options[0].text,
    	                                                                           F.alunospossib.options[0].value);
    F.alunospossib.options[0] = null;
    
  }
  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.processar.disabled    = false;
  document.form1.alunos.focus();
  
}

function js_excluir() {
	
  var F = document.getElementById("alunos");
  Tam   = F.length;
  
  for (x = 0; x < Tam; x++) {
	  
    if (F.options[x].selected == true) {
        
      document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[x].text,
    	                                                                                   F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
      
    }
  }
  if (document.form1.alunos.length > 0) {
    document.form1.alunos.options[0].selected = true;
  }
  
  if (F.length == 0) {
	  
    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    
  }
  
  document.form1.incluirtodos.disabled = false;
  document.form1.alunos.focus();
  
}

function js_excluirtodos() {
	
  var Tam = document.form1.alunos.length;
  var F   = document.getElementById("alunos");
  
  for (i = 0; i < Tam; i++) {
	  
    document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[0].text,
    	                                                                                 F.options[0].value);
    F.options[0] = null;
    
  }
  
  if (F.length == 0) {
	  
    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
    
  }
  
  document.form1.alunospossib.focus();
}

function js_desabinc() {
	
  for (i = 0; i < document.form1.alunospossib.length; i++) {
	  
    if (document.form1.alunospossib.length > 0 && document.form1.alunospossib.options[i].selected) {
        
      if (document.form1.alunos.length > 0) {
        document.form1.alunos.options[0].selected = false;
      }
      
      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
      
    }
  }
}

function js_desabexc() {
	
  for (i = 0; i < document.form1.alunos.length; i++) {
	  
    if (document.form1.alunos.length > 0 && document.form1.alunos.options[i].selected) {
         
      if (document.form1.alunospossib.length > 0) {
        document.form1.alunospossib.options[0].selected = false;
      }
      
      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
      
    }
  }
}
</script>