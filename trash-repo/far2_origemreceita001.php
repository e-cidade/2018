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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<br><br>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table>
<form name="form1" method="post" action="">
  <center>
    <table width='70%'>
      <tr>
        <td>
          <fieldset style="width:70%"><legend align='left'><b>Origem</b></legend>
            <table  border="0"  align="center" width='100%'>
              <tr>
                <td align="right" style="padding-bottom: 8px;"> 
                  <b>Período:</b>
                </td>
                <td style="padding-bottom: 8px;"> 
                  <?db_inputdata('dataini',@$dataini_dia,@$dataini_mes,@$dataini_ano,true,'text',1,"")?>
                  <b>Até:</b>
                  <?db_inputdata('datafim',@$datafim_dia,@$datafim_mes,@$datafim_ano,true,'text',1,"")?>
                </td>
              </tr>
              <tr>
                <td width='15%' align='right'>
                  <?
                  db_ancora('<b>Origem:</b>', 'js_pesquisaorigem(true);', '');
                  ?>
                </td>
                <td nowrap>
                  <?
                  db_input('fa40_i_codigo',10,'',true,'text',1," onchange='js_pesquisaorigem(false);'");
                  db_input('fa40_c_descr',50,'',true,'text',3,'');
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='button' value='Lan&ccedil;ar' name='lancar_origem' id='lancar_origem'>
                </td>
              </tr>

              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                 <select multiple size='8' name='select_origem[]' id='select_origem' style="width: 80%;" onDblClick="js_excluir_item_origem();">
                  </select>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
  </center>

  <center>
    <br>
    <input  name="emite" id="emite" type="button" value="Processar" onclick="js_mandadados();" >
  </center>


  </center>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<script>

function js_incluir_item_origem() {

  var texto=document.form1.fa40_c_descr.value;
  var valor=document.form1.fa40_i_codigo.value;
  if(texto != "" && valor != "") {

    var F = document.getElementById("select_origem");
    var valor_default_novo_option = F.length;
    var testa = false;
    for(var x = 0; x < F.length; x++) {

      if(F.options[x].value == valor) {

        testa = true;
        break;

      }
    }

    if(testa == false) {

      F.options[valor_default_novo_option] = new Option(texto,valor);
      for(i=0;i<F.length;i++) {
        F.options[i].selected = false;
      }
      F.options[valor_default_novo_option].selected = true;

    }

  }
  texto = document.form1.fa40_i_codigo.value = '';
  valor = document.form1.fa40_c_descr.value = '';
  document.form1.lancar_origem.onclick = '';

}

function js_excluir_item_origem() {

  var F = document.getElementById("select_origem");
  if(F.length == 1) {
    F.options[0].selected = true;
  }
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;
    if(SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }

  }

}
  
function js_pesquisaorigem(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_far_origemreceita','func_far_origemreceita.php?'+
                        'funcao_js=parent.js_mostraorigem1|fa40_i_codigo|fa40_c_descr',
                        'Pesquisa',true);
  } else {

    if(document.form1.fa40_i_codigo.value != '') { 

      js_OpenJanelaIframe('top.corpo','db_iframe_far_origemreceita','func_far_origemreceita.php?pesquisa_chave='+
                          document.form1.fa40_i_codigo.value+'&funcao_js=parent.js_mostraorigem',
                          'Pesquisa',false);

    } else {
      document.form1.fa40_c_descr.value = ''; 
    }

  }

}

function js_mostraorigem(chave, erro) {

  document.form1.fa40_c_descr.value = chave; 
  if(erro == true) {

    document.form1.fa40_i_codigo.focus(); 
    document.form1.fa40_i_codigo.value = '';

  } else {
    
    document.form1.fa40_c_descr.value = chave;
    document.form1.lancar_origem.onclick = js_incluir_item_origem;

  }

}

function js_mostraorigem1(chave1, chave2) {

  document.form1.fa40_i_codigo.value = chave1;
  document.form1.fa40_c_descr.value = chave2;
  db_iframe_far_origemreceita.hide();
  document.form1.lancar_origem.onclick = js_incluir_item_origem;

}

function js_validaEnvio() {

  if(document.form1.dataini.value == '' || document.form1.datafim.value == '') {

	  alert('O período deve ser preenchido.');
		return false;

	}

  aIni = document.form1.dataini.value.split('/');
  aFim = document.form1.datafim.value.split('/');
  dIni = new Date(aIni[2], aIni[1], aIni[0]);
  dFim = new Date(aFim[2], aFim[1], aFim[0]);

	if(dFim < dIni) {
				
	  alert("Data final não pode ser menor que a data inicial.");
	  document.form1.datafim.value = '';
		document.form1.datafim.focus();
	  return false;

	}	
	return true;						

}

function js_mandadados() {
 
  if(js_validaEnvio()) {

    sVir = '';
    sOrigens = 'sOrigens=';
    sNomesOrigens = '&sNomesOrigens='
    sDatas = '&dDatas='+document.form1.dataini.value+','+document.form1.datafim.value;
 
    for(x = 0; x < document.form1.select_origem.length; x++) {

      sOrigens += sVir + document.form1.select_origem.options[x].value;
      sNomesOrigens += sVir + document.form1.select_origem.options[x].value + ' - ' + document.form1.select_origem.options[x].innerHTML;
      sVir = ',';

    }
    
    oJan = window.open('far2_origemreceita002.php?'+sOrigens+sNomesOrigens+sDatas, '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                      ',scrollbars=1,location=0 ');
    oJan.moveTo(0,0);

  }

}

</script>