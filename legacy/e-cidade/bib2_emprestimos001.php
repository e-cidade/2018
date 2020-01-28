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
require_once ("classes/db_tipoitem_classe.php");

db_postmemory($HTTP_POST_VARS);
$cltipo   = new cl_tipoitem;
$clrotulo = new rotulocampo;
$clrotulo->label("bi18_carteira");
$clrotulo->label("z01_nome");
$clrotulo->label("bi06_seq");
$clrotulo->label("bi06_titulo");
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
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
<fieldset align="center" style="width:95%"><legend><b>Relatório de Ítens Emprestados</b></legend>
<table  align="center">
 <tr>
  <td>
   <table align="center">
    <form name="form1" method="post" action="">
    <tr>
     <td align="left" nowrap title="Período">
     <strong>Período:&nbsp;&nbsp;</strong>
     </td>
     <td>
      <?db_inputdata('data1', @$dia1, @$mes1, @$ano1, true, 'text', 1, "")?>
      até
      <?db_inputdata('data2', @$dia2, @$mes2, @$ano2, true, 'text', 1, "")?>
     </td>
    </tr>
    <tr>
     <td align="left" nowrap title="Período">
     <strong>Filtro:&nbsp;&nbsp;</strong>
     </td>
     <td>
      <input type="radio" name="filtro" value="1" checked> Todos &nbsp;&nbsp;
      <input type="radio" name="filtro" value="2"> Devolvidos &nbsp;&nbsp;
      <input type="radio" name="filtro" value="3"> Em aberto &nbsp;&nbsp;
      <input type="radio" name="filtro" value="4"> Em Atraso &nbsp;&nbsp;
     </td>
    </tr>
    <tr>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
    </tr>
    <tr>
     <td colspan="2" align="center"><b>Opcionais:</b></td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tbi18_carteira?>">
      <?db_ancora(@$Lbi18_carteira,"js_pesquisabi18_carteira(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('bi18_carteira', 10, $Ibi18_carteira, true, 'text', $db_opcao, " onchange='js_pesquisabi18_carteira(false);' onKeyPress='tab(event,12)'")?>
      <?db_input('ov02_nome', 50, @$ov02_nome, true, 'text', 3, "")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tbi06_seq?>">
      <?db_ancora(@$Lbi06_seq, "js_pesquisabi06_seq(true);", $db_opcao);?>
     </td>
     <td>
      <?db_input('bi06_seq', 10, @$Ibi06_seq, true, 'text', $db_opcao, " onchange='js_pesquisabi06_seq(false);'")?>
      <?db_input('bi06_titulo', 50, @$bi06_titulo, true, 'text', 3, "")?>
     </td>
    </tr>
    <tr>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
    </tr>
    <tr>
     <td colspan="2" align = "center">
      <input name="emite" id="emite" type="button" value="Processar" onclick="js_emite();" >
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
</table>
</fieldset>
</center>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_emite() {
  
  obj   = document.form1;
  count = 0;
  
  if (   (obj.data1_dia.value != '') 
      && (obj.data1_mes.value != '') 
      && (obj.data1_ano.value != '') 
      && (obj.data1_dia.value != '') 
      && (obj.data2_mes.value != '') 
      && (obj.data2_ano.value != '')) {
    
    query = "data1="+obj.data1_ano.value+"-"+obj.data1_mes.value+"-"+obj.data1_dia.value
          +"&data2="+obj.data2_ano.value+"-"+obj.data2_mes.value+"-"+obj.data2_dia.value;
    count = 1;
  } else {
    
    alert("Preencha a data corretamente!");
    return false;
  }
  
  if (obj.filtro[0].checked == true) {
    filtro = 1;
  }
  if (obj.filtro[1].checked == true) {
    filtro = 2;
  }
  if (obj.filtro[2].checked == true) {
    filtro = 3;
  }
  if (obj.filtro[3].checked == true) {
    filtro = 4;
  }
  if (count == 0 ) {
    alert("Preencha os Campos Corretamente!");
  } else {

    jan = window.open('bib2_emprestimos002.php?'+query
                                                +'&filtro='+filtro
                                                +'&leitor='+obj.bi18_carteira.value
                                                +'&acervo='+obj.bi06_seq.value,
                      '',
                      'width='+(screen.availWidth-5)
                     +',height='+(screen.availHeight-40)
                     +',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}

function js_pesquisabi18_carteira(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_leitor',
                        'func_leitorproc.php?funcao_js=parent.js_mostraleitor1|bi16_codigo|ov02_nome',
                        'Pesquisa',
                        true);
  } else {
    
    if (document.form1.bi18_carteira.value != '') {
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_leitor',
                          'func_leitorproc.php?pesquisa_chave='+document.form1.bi18_carteira.value
                                            +'&funcao_js=parent.js_mostraleitor',
                          'Pesquisa',
                          false);
    } else {
      document.form1.ov02_nome.value = '';
    }
  }
}

function js_mostraleitor(chave, erro) {
  
  document.form1.ov02_nome.value = chave;
  if (erro == true) {
    
    document.form1.bi18_carteira.focus();
    document.form1.bi18_carteira.value = '';
  }
}

function js_mostraleitor1(chave1, chave2) {
  
  document.form1.bi18_carteira.value = chave1;
  document.form1.ov02_nome.value     = chave2;
  db_iframe_leitor.hide();
}

function js_pesquisabi06_seq(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_acervo',
                        'func_acervo.php?funcao_js=parent.js_mostraacervo1|bi06_seq|bi06_titulo',
                        'Pesquisa',
                        true);
  } else {
    
    if (document.form1.bi06_seq.value != '') {
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acervo',
                          'func_acervo.php?pesquisa_chave3='+document.form1.bi06_seq.value
                                       +'&funcao_js=parent.js_mostraacervo',
                          'Pesquisa',
                          false);
    } else {
      document.form1.bi06_titulo.value = '';
    }
  }
}

function js_mostraacervo(chave, erro) {
  
  document.form1.bi06_titulo.value = chave;
  if (erro == true) {
    
    document.form1.bi06_seq.focus();
    document.form1.bi06_seq.value = '';
  }
}

function js_mostraacervo1(chave1, chave2) {
  
  document.form1.bi06_seq.value    = chave1;
  document.form1.bi06_titulo.value = chave2;
  db_iframe_acervo.hide();
}
</script>