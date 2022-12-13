<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$clrotulo = new rotulocampo;
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");

$db_botao = true;
$db_opcao = 1;
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
<script>

function js_emite() {
  var idusuario = document.form1.id_usuario.value;
  var depto     = document.form1.coddepto.value;
  var listar    = document.form1.listar.value;
  query = 'listar='+listar;
  if (idusuario!='') {
    query+='&id_usuario='+idusuario;
  }
  if (depto!='') {
    query+='&depto='+depto;
  }

  jan = window.open('con2_departusu002.php?'+query ,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}


</script>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
<form name='form1'>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
   	 <center>
<table> 
  <tr>
    <td nowrap title="<?=@$Tid_usuario?>">
       <?
       db_ancora(@$Lnome,"js_usu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('id_usuario',7,$Iid_usuario,true,'text',$db_opcao," onchange='js_usu(false);'");
db_input('nome',40,$Inome,true,'text',3,'');
?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcoddepto?>">
       <?
       db_ancora(@$Lcoddepto,"js_coddepto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('coddepto',7,$Icoddepto,true,'text',$db_opcao," onchange='js_coddepto(false);'");
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
?>
    <td>
  </tr>

  <tr>
    <td nowrap title="Situcão Usuários"><b>Listar Usuários:</b></td>
    <td> 
      <?
        $listar = 1; // Por padrão Listar Ativos
        $x = array( 
          "0" => "Todos",
          "1" => "Ativos",
          "2" => "Inativos",
          "3" => "Bloqueados",
          "4" => "Aguardando Ativação"
        );
        db_select('listar',$x,true,$db_opcao,"");
      ?>
    <td>
  </tr>

  <tr>
    <td colspan='2' align='center'>
     <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
    </td>
  </tr>
</table>  
    </center>
    </td>
  </tr>
</form>

<script>
function js_usu(mostra) {
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario','func_db_usuariosalt.php?funcao_js=parent.js_mostrausu1|id_usuario|nome','Pesquisa',true);
  } else {
    usu= document.form1.id_usuario.value;
    if (usu!="") {
      js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario','func_db_usuariosalt.php?pesquisa_chave='+usu+'&funcao_js=parent.js_mostrausu','Pesquisa',false);
    } else {
      document.form1.nome.value='';
    }
  }
}

function js_mostrausu1(chave1,chave2) {
  document.form1.id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuario.hide();
}

function js_mostrausu(chave,erro) {
  document.form1.nome.value = chave;
  if (erro==true) {
    document.form1.id_usuario.focus();
    document.form1.id_usuario.value = '';
  }
}

function js_coddepto(mostra) {
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostracoddepto1|coddepto|descrdepto','Pesquisa',true);
  } else {
    coddepto = document.form1.coddepto.value;
    if (coddepto!="") {
      js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+coddepto+'&funcao_js=parent.js_mostracoddepto','Pesquisa',false);
    } else {
      document.form1.descrdepto.value='';
    }
  }
}

function js_mostracoddepto1(chave1,chave2) {
  document.form1.coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}

function js_mostracoddepto(chave,erro) {
  document.form1.descrdepto.value = chave;
  if (erro==true) {
    document.form1.coddepto.focus();
    document.form1.coddepto.value = '';
  }
}
document.form1.id_usuario.focus();
</script>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
