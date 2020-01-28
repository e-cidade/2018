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

$oRotulo = new rotulocampo;
$oRotulo->label('s115_c_cartaosus');
$oRotulo->label('z01_i_cgsund');
$oRotulo->label('z01_v_nome');
$oRotulo->label('z01_v_mae');
$oRotulo->label('z01_d_nasc');
$oRotulo->label('z01_v_sexo');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<form name="form1" method="post" action="">
  <center>
    <br><br><br>
    <fieldset style='width: 10%;'> <legend><b>Gerar Ficha de Vacinação</b></legend>
      <table border="0"> 
        <tr>
          <td nowrap title="<?=$Ts115_c_cartaosus?>">
            <?=$Ls115_c_cartaosus?>
          </td>
          <td nowrap> 
            <?
            db_input('s115_c_cartaosus', 15,$Is115_c_cartaosus, true, 'text', 1, 'onchange="js_getCgsCns();"');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tz01_i_cgsund?>">
            <?
            db_ancora(@$Lz01_i_cgsund,"js_pesquisaz01_i_cgsund(true);", 1);
            ?>
          </td>
          <td nowrap> 
            <?
            db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'text', 1," onchange='js_pesquisaz01_i_cgsund(false);'");
            db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tz01_d_nasc?>">
            <?=$Lz01_d_nasc?>
          </td>
          <td>
            <?
            db_input('z01_d_nasc',10,$Iz01_d_nasc,true,'text',3,"");
            echo"<b>Idade:</b>";
            db_input('iIdade',23,"",true,'text',3,"");
            echo"<b>Sexo:</b>";
            db_input('z01_v_sexo',1,$Iz01_v_sexo,true,'text',3,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tz01_v_mae?>">
            <strong><b>Nome da Mãe:</b></strong>
          </td>
          <td>
            <?
            db_input('z01_v_mae', 50, $Iz01_v_mae, true, 'text' ,3, '');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="gerar" type="button" id="gerar" value="Imprimir Ficha" onClick="return js_mandaDados();">
    <input type="button" value="Limpar" onclick="window.location.href = 'vac2_ficha001.php';">
  </center>
</form>
<script>
js_tabulacaoforms("form1", "s115_c_cartaosus", true, 1, "s115_c_cartaosus", true);


function js_ajax(oParam, jsRetorno, sUrl) {

  if(sUrl == undefined) {
    sUrl = 'vac4_vacinas.RPC.php';
  }

	var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                          method: 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: 
                                     function(objAjax) {
                          				   
                                       var evlJS = jsRetorno+'(objAjax);';
                                       return eval(evlJS);

                          			     }
                         }
                        );

}

function js_mandaDados() {

  if($F('z01_i_cgsund') == '') {

    alert('Informe um CGS.');
    return false;

  }
 
  sChave = 'iCgs='+$F('z01_i_cgsund');
  oJan = window.open('vac2_ficha002.php?'+sChave, '', 'width='+(screen.availWidth - 5)+',height='+
                     (screen.availHeight - 40)+',scrollbars=1,location=0 ');
  oJan.moveTo(0, 0);

}

function js_getCgsCns() {
	
  if($F('s115_c_cartaosus') == '') {
    return false;
  }
  if($F('s115_c_cartaosus').length != 15 || isNaN($F('s115_c_cartaosus'))) {
    
    alert('Número de CNS inválido para busca.');
    $('s115_c_cartaosus').value = '';
    return false;

  }

  var oParam  = new Object();
	oParam.exec = 'getCgsCns';
	oParam.iCns = $F('s115_c_cartaosus');

	js_ajax(oParam, 'js_retornogetCgsCns', 'tfd4_pedidotfd.RPC.php');

}
function js_retornogetCgsCns(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if(oRetorno.z01_i_cgsund == '') {

    alert('CNS não encontrado.');
    return false;

  }

  $('z01_i_cgsund').value = oRetorno.z01_i_cgsund;
  $('z01_v_nome').value   = oRetorno.z01_v_nome.urlDecode();
  js_pesquisaz01_i_cgsund(false);
 
}

function js_pesquisaz01_i_cgsund(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostra_cgs|z01_i_cgsund|'+
                        'z01_v_nome|z01_v_sexo|z01_d_nasc|z01_v_mae','Pesquisa',true
                       );

  } else {

    if (document.form1.z01_i_cgsund.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostra_cgs|z01_i_cgsund|'+
                          'z01_v_nome|z01_v_sexo|z01_d_nasc|z01_v_mae&chave_z01_i_cgsund='+
                           document.form1.z01_i_cgsund.value+'&nao_mostra=true', 'Pesquisa', false
                         );

    } else {

      document.form1.z01_v_nome.value = ''; 
      document.form1.iIdade.value     = '';
      document.form1.z01_d_nasc.value = '';
      document.form1.z01_v_sexo.value = '';
      document.form1.z01_v_mae.value  = '';

    }

  }

}

function js_mostra_cgs(chave1, chave2, sexo, nasc, mae) {

  if (chave1 == '') {

    sexo                        = '';
    nasc                        = '';
    mae                         = '';
    document.form1.iIdade.value = '';

  }

  document.form1.z01_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value   = chave2;
  document.form1.z01_d_nasc.value   = js_formataData(nasc);
  document.form1.z01_v_sexo.value   = sexo;
  document.form1.z01_v_mae.value    = mae;
  
  db_iframe_cgs_und.hide();

  if (chave1 != '') {

    oParam            = new Object();
    oParam.exec       = 'getIdadeDiaMesAno';
    oParam.z01_d_nasc = nasc;
    oParam.iCgs       = chave1;
    js_ajax(oParam, 'js_retornoIdade');

  }

}

function js_retornoIdade(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
 
  if (oRetorno.iStatus == 1) {
    $('iIdade').value = oRetorno.iAnos+' anos, '+oRetorno.iMeses+' meses e '+oRetorno.iDias+' dias.';
  } else {

    alert(oRetorno.sMessage.urlDecode());
    js_limpaInfoCgs();

  }

}

function js_limpaInfoCgs() {

  document.form1.z01_i_cgsund.value = '';
  document.form1.z01_v_nome.value = '';
  document.form1.z01_d_nasc.value = '';
  document.form1.z01_v_sexo.value = '';
  document.form1.z01_v_mae.value  = '';
  document.form1.iIdade.value     = '';

}

function js_formataData(dData) {
  
  if(dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);

}

</script>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>