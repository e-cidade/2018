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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo;
$oRotulo->label('s115_c_cartaosus');
$oRotulo->label('z01_i_cgsund');
$oRotulo->label('z01_v_nome');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<br><br>
  <table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
        <fieldset style='width: 40%;'> <legend><b>Consulta Geral da Saúde</b></legend>
          <form name='form1'>
            <table>
              <tr>
                <td nowrap title="<?=@$Ts115_c_cartaosus?>">
                  <?=@$Ls115_c_cartaosus?>
                </td>
                <td nowrap> 
                  <?
                  db_input('s115_c_cartaosus', 14, $Is115_c_cartaosus, true, 
                           'text', 1, ' onchange="js_getCgsCns();"'
                          );
                  ?>
                </td>
              </tr>
	            <tr>
	              <td title="<?=@$Tz01_i_cgsund?>">
                  <? 
                  db_ancora($Lz01_i_cgsund, 'js_pesquisaz01_i_cgsund(true);', 1);
                  ?>
                </td>
		            <td>
                  <?
                  db_input('z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', 1, 
                           'onchange="js_pesquisaz01_i_cgsund(false);"'
                          );
                  ?> 
                </td>
	            </tr>
              <tr>
                <td>
                  <b>Nome:</b>
                </td>
                <td>
		              <?
                  db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 1, '');
                  ?>
                </td>
              </tr>
            </table>
            <table>
              <tr>
                <td>
                  <input name="confirmar" id="confirmar" type="button" value="Confirmar" onclick="js_confirmar();">
                </td>
                <td>
                  <input name="limpar" id="limpar" type="button" value="Limpar" onclick="js_limpar();">
                </td>
              </tr>
            </table>
          </form>
        </fieldset>
      </td>
    </tr>
  </table>
</center>
<?
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'),
        db_getsession('DB_anousu'), db_getsession('DB_instit')
       );
?>
</body>
</html>

<script>
 
function js_ajax(oParam, jsRetorno, sUrl) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_ambulatorial.RPC.php';
  }
	var objAjax = new Ajax.Request(sUrl, 
                                 {
                                  method: 'post',
                                  asynchronous: false,
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {
                                  				      var evlJS = jsRetorno+'(oAjax);';
                                                return mRetornoAjax = eval(evlJS);
                                  		        }
                                 }
                                );

  return mRetornoAjax;

}

function js_confirmar() {

  if ($F('z01_i_cgsund') != '') {

	  if ((screen.width >= 900) && (screen.height >= 700)) {
	    iLinhas = 8;
	  } else {
		  iLinhas = 5;
	  }
	  iTop    = 20;
	  iLeft   = 5;
	  iHeight = screen.availHeight-210;
	  iWidth  = screen.availWidth-35;
	  
    sChave  = 'z01_i_cgsund='+$F('z01_i_cgsund');
    sChave += '&iLinhas='+iLinhas;
    js_OpenJanelaIframe('', 'db_iframe_consulta', 'sau3_consultasaude002.php?'+sChave, 
                        'Consulta Geral da Saúde', true, iTop, iLeft, iWidth, iHeight
                       );

  }

}

function js_limpar() {

  $('s115_c_cartaosus').value = '';
	$('z01_i_cgsund').value     = '';
  $('z01_v_nome').value       = '';

}

function js_pesquisaz01_i_cgsund(mostra) {

  if(mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostracgs1|'+
                        'z01_i_cgsund|z01_v_nome', 'Pesquisa', true
                       );

  } else {
     if($F('z01_i_cgsund') != '') {

        js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?pesquisa_chave='+$F('z01_i_cgsund')+
                            '&funcao_js=parent.js_mostracgs', 'Pesquisa', false
                           );

     } else {
       $('z01_v_nome').value = ''; 
     }

  }

}
function js_mostracgs(chave, erro) {
  
  $('z01_v_nome').value = chave; 
  if(erro == true) {

    $('z01_i_cgsund').focus(); 
    $('z01_i_cgsund').value = '';

  }

}
function js_mostracgs1(chave1, chave2) {

  $('z01_i_cgsund').value = chave1;
  $('z01_v_nome').value   = chave2;
  db_iframe_cgs_und.hide();

}

function js_getCgsCns() {

  if ($F('s115_c_cartaosus') == '') {
    return false;
  }
  if ($F('s115_c_cartaosus').length != 15 || isNaN($F('s115_c_cartaosus'))) {

    alert('Número de CNS inválido para busca.');
    $('s115_c_cartaosus').value = '';
    return false;

  }

  var oParam  = new Object();
  oParam.exec = "getCgsCns";
  oParam.iCns = $F('s115_c_cartaosus');

  js_ajax(oParam, 'js_retornogetCgsCns');

}
function js_retornogetCgsCns(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.z01_i_cgsund == '') {

    alert('CNS não encontrado.');
    return false;

  }
  $('z01_i_cgsund').value = oRetorno.z01_i_cgsund;
  $('z01_v_nome').value   = oRetorno.z01_v_nome.urlDecode();
  return true;
 
}

// Autocomplete do CGS
oAutoComplete = new dbAutoComplete($('z01_v_nome'), 'sau4_pesquisanome.RPC.php?tipo=1');
oAutoComplete.setTxtFieldId($('z01_i_cgsund'));
oAutoComplete.setHeightList(390);
oAutoComplete.show();

</script>