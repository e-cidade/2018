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

//MODULO: Vacinas
$clvac_aplica->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc07_i_codigo");
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_v_mae");
$clrotulo->label("z01_d_nasc");
$clrotulo->label("z01_v_sexo");
$clrotulo->label("s115_c_cartaosus");
?>
<fieldset style='width: 70%;'> <legend><b>Paciente</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts115_c_cartaosus?>">
      <?=$Ls115_c_cartaosus?>
    </td>
    <td>
      <?db_input('s115_c_cartaosus',15,$Is115_c_cartaosus,true,'text',$db_opcao,
                  " onchange=\"js_getCgsCns();\"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc16_i_cgs?>">
      <?db_ancora(@$Lvc16_i_cgs,"js_pesquisavc16_i_cgs(true);",$db_opcao);?>
    </td>
    <td> 
      <?
      db_input('vc16_i_cgs',10,$Ivc16_i_cgs,true,'text',$db_opcao,
               " onchange='js_pesquisavc16_i_cgs(false);'");
      db_input('z01_v_nome',40,$Iz01_v_nome,true,'text',3,'');
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
      <strong><b>Nome da Mãe</b></strong>
    </td>
    <td>
      <?db_input('z01_v_mae',40,$Iz01_v_mae,true,'text',3,'');?>
    </td>
  </tr>
</table>
</center>
<input name = "consultar" type = "button" id = "consultar" value = "Consultar" onClick = "js_getDadosVacinas()">
<input name = "limpar" type = "button" id = "limpar" value = "Limpar" onClick = "js_limpar()">
<input name = "inprimir" type = "button" id = "inprimir" value = "Imprimir Ficha" onClick = "js_imprimir()">
</form>
</fieldset>

<table border="0" width="100%">
  <tr>
    <td>
      <div id='grid_vacinas' style='width: 100%;'></div>
    </td>
  </tr>
</table>

<script>
sUrl = 'vac4_vacinas.RPC.php';

function js_imprimir() {

  if($F('vc16_i_cgs') == '') {

    alert('Informe um CGS.');
    return false;

  }
 
  sChave = 'iCgs='+$F('vc16_i_cgs');
  oJan   = window.open('vac2_ficha002.php?'+sChave, '', 'width='+(screen.availWidth - 5)+',height='+
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

  $('vc16_i_cgs').value = oRetorno.z01_i_cgsund;
  $('z01_v_nome').value = oRetorno.z01_v_nome.urlDecode();
  js_pesquisavc16_i_cgs(false);
 
}

function js_ajax(oParam, jsRetorno) {

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

function js_pesquisavc16_i_cgs(mostra) {
	
	oDBGrid.clearAll(true);
	
  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostra_cgs|z01_i_cgsund|'+
                        'z01_v_nome|z01_v_sexo|z01_d_nasc|z01_v_mae','Pesquisa',true
                       );

  } else {

    if (document.form1.vc16_i_cgs.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostra_cgs|z01_i_cgsund|'+
                           'z01_v_nome|z01_v_sexo|z01_d_nasc|z01_v_mae&chave_z01_i_cgsund='+
                           document.form1.vc16_i_cgs.value+'&nao_mostra=true', 'Pesquisa', false
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

  document.form1.vc16_i_cgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  document.form1.z01_d_nasc.value = js_formataData(nasc);
  document.form1.z01_v_sexo.value = sexo;
  document.form1.z01_v_mae.value  = mae;
  
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
    document.form1.vc16_i_cgs.value = '';
    document.form1.z01_v_nome.value = '';
    document.form1.z01_d_nasc.value = '';
    document.form1.z01_v_sexo.value = '';
    document.form1.z01_v_mae.value  = '';
    document.form1.iIdade.value     = '';

  }

}

function js_formataData(dData) {
  
  if(dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);

}

function js_limpar() {

  document.form1.vc16_i_cgs.value       = '';
  document.form1.z01_v_nome.value       = '';
  document.form1.z01_d_nasc.value       = '';
  document.form1.z01_v_sexo.value       = '';
  document.form1.z01_v_mae.value        = '';
  document.form1.iIdade.value           = '';
  document.form1.s115_c_cartaosus.value = '';
  oDBGrid.clearAll(true);

}


//Programação do grid


oDBGridVacinas = js_criaDatagrid();
iUsuarioSessao = '<?=db_getsession('DB_id_usuario')?>';

js_criaDatagrid()

function js_criaDatagrid() {

	  oDBGrid                = new DBGrid('grid_pedidostfd');
	  oDBGrid.nameInstance   = 'oDBGridPedidostfd';
	  oDBGrid.hasTotalizador = false;
	  oDBGrid.setCellWidth(new Array('20%','20%', '20%', '10%' , '10%', '20%'));
	  oDBGrid.setHeight(255);
	  oDBGrid.allowSelectColumns(false);

	  var aHeader = new Array();
	  aHeader[0]  = 'Calendário';
	  aHeader[1]  = 'Vacina';
	  aHeader[2]  = 'Dose';
	  aHeader[3]  = 'Período de Aplicação';
	  aHeader[4]  = 'Aplicação';
	  aHeader[5]  = 'Observação';
	  oDBGrid.setHeader(aHeader);

	  var aAligns = new Array();
	  aAligns[0]  = 'center';
	  aAligns[1]  = 'center';
	  aAligns[2]  = 'center';
	  aAligns[3]  = 'center';
	  aAligns[4]  = 'center';
	  aAligns[5]  = 'center';
	  
	  oDBGrid.setCellAlign(aAligns);
	  oDBGrid.show($('grid_vacinas'));
	  oDBGrid.clearAll(true);

	  return oDBGrid;

	}

function js_formataData(dData, lFormatoBanco) {
	  
	  if (dData == undefined || dData.length != 10) {
	    return dData;
	  }

	  if (lFormatoBanco != true) {
	    return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);
	  }  else {
	    return dData.substr(6,4)+'-'+dData.substr(3,2)+'-'+dData.substr(0,2);
	  }


	}

	function js_renderizaGrid() {

	  var aLinha    = new Array();
	  var sDisabled = '';
	  var sOnchange = '';
	  for (iCont = 0; iCont < aDadosVacinas.length; iCont++) {
	   
	    
	    if (aDadosVacinas[iCont].foraRede == 'false' || aDadosVacinas[iCont].passouinicio == 'false') {
	      sDisabled = 'disabled';
	    }
	    
	    if (aDadosVacinas[iCont].foraRede == 'true' && iUsuarioSessao != aDadosVacinas[iCont].vc16_i_usuario) {
	      sOnchange = '  onchange="js_denyUsuario(\''+aDadosVacinas[iCont].login+'\')"'; 
	    } else {
	      sOnchange = '  onchange="js_setAlterado('+aDadosVacinas[iCont].vc07_i_codigo+')"';
	    }

	    if (aDadosVacinas[iCont].foraRede.urlDecode() == 'true') {
	      sAltInc = 'alterar';
	    } else {
	      sAltInc = 'incluir';
	    }

	    aLinha[0]  = aDadosVacinas[iCont].vc05_c_descr.urlDecode();
	    aLinha[1]  = aDadosVacinas[iCont].vc07_c_nome.urlDecode();
	    aLinha[2]  = aDadosVacinas[iCont].vc03_c_descr.urlDecode();
	    aLinha[3]  = aDadosVacinas[iCont].periodo.urlDecode();
	    aLinha[4]  = js_formataData(aDadosVacinas[iCont].dataAplicacao); 
	    aLinha[5]  = aDadosVacinas[iCont].obsAplicacao.urlDecode(); 

	    oDBGridVacinas.addRow(aLinha);
	    sDisabled = '';

	  }
	  oDBGridVacinas.renderRows();

	}
function js_getDadosVacinas() {

  if ($F('vc16_i_cgs') == '') {
	  
    alert('Entre com o CGS!');
    return false;
    
  }
  oParam      = new Object();
  oParam.exec = 'getDadosVacinas';
  oParam.iCgs = $F('vc16_i_cgs');
  js_ajax(oParam, 'js_retornoGetDadosVacinas');

}

function js_retornoGetDadosVacinas(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
 
  if (oRetorno.iStatus == 1) {

  aDadosVacinas = oRetorno.aDadosVacinas;
    js_renderizaGrid();

  } else {
    alert(oRetorno.sMessage.urlDecode());
  }

}
</script>