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
$oDaoVacDevolucao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc06_i_codigo");
$clrotulo->label("vc06_c_descr");
$clrotulo->label("m77_sequencial");
$clrotulo->label("m77_lote");
$clrotulo->label("vc01_i_unidade");
$clrotulo->label("descrdepto");
?>

<form name="form1" method="post" action="">
<center>
<fieldset style='width: 60%;'> <legend><b>Vacina Baixada</b></legend>
<table border="0" width="100%">
    <tr>
    <td nowrap title="Sala de Vacinação">
      <b>Unidade:</b>
    </td>
    <td nowrap="nowrap">
      <? db_input('vc01_i_unidade',5,$Ivc01_i_unidade,true,'text',3);?>
      <? db_input('descrdepto',30,$Idescrdepto,true,'text',3);?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc06_i_vacina?>">
     <? db_ancora("<b>Vacina:</b>","js_pesquisavc06_i_codigo(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('vc06_i_codigo',5,$Ivc06_i_codigo,true,'text',$db_opcao,
                " onchange='js_pesquisavc06_i_codigo(false);'")?>
     <?db_input('vc06_c_descr',40,$Ivc06_c_descr,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm77_sequencial?>">
     <? db_ancora("<b>Lote:</b>","js_pesquisam77_sequencial(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('m77_sequencial',5,$Im77_sequencial,true,'text',$db_opcao,
                " onchange='js_pesquisam77_sequencial(false);'")?>
     <?db_input('m77_lote',20,$Im77_lote,true,'text',3,'')?>
    </td>
  </tr>
</table>
</fieldset>
<br>
<input name="pesquisar" value="Pesquisar" type="button" onClick="js_CarregaLotes()">
<input name="limpar" value="Limpar" type="button" onClick="js_limpar()">
<br><br>
<fieldset style='width: 90%;'> <legend><b>Lotes Baixados</b></legend>
<div id="GridLotes" name="Gridlotes"></div>
<input type="text" name="codretirada" id="codretirada" value="" style="display: none">
<input type="text" name="motivos" id="motivos" value="" style="display: none">
</fieldset>
<br>
<input name="confirma" id="confirma" type  = "submit"  value = "confirma" onclick="return js_motivos();" disabled >
</center>
</form>
<script>
oGridLotes = new DBGrid('GridLotes');
js_init();

//GridExames
function js_init() {

	oGridLotes.setCellWidth(new Array('10%', '25%', '15%', '15%', '30%','5%'));
  var arrHeader = new Array (" Cod. Retirada ",  
                             " Lote ",
                             " Validade ",
                             " Qtd Atendida ",
                             " Motivo",
                             " Devolver");
  oGridLotes.nameInstance = 'GridLotes';
  oGridLotes.setHeader( arrHeader );
  var aAligns = new Array();
  aAligns[0] = 'center';
  aAligns[1] = 'center';
  aAligns[2] = 'center';
  aAligns[3] = 'center';
  aAligns[4] = 'center';
  aAligns[5] = 'center';
  oGridLotes.setCellAlign(aAligns);
  oGridLotes.setHeight(80);
  
  oGridLotes.show($('GridLotes')); 

}

function js_CarregaLotes() {
	
  if ($F('m77_sequencial') == '') {

    alert('Selecione um Lote!');
    oGridLotes.clearAll(true);
    return false;

  }
  var oParam        = new Object();
  oParam.exec       = 'getGridDevolucao';
  oParam.iLote      = $F('m77_sequencial');
  oParam.iUnidade   = $F('vc01_i_unidade');
  js_ajax( oParam, 'js_RetornoCarregaLotes' );

}

function js_RetornoCarregaLotes(oAjax) {
  oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.iStatus == 1) {

    oGridLotes.clearAll(true);
    iTam = oRetorno.aItens.length;
    for (iX = 0; iX < iTam; iX++) {

       alinha    = new Array();
       alinha[0] = oRetorno.aItens[iX][0];
       alinha[1] = oRetorno.aItens[iX][1];
       alinha[2] = oRetorno.aItens[iX][2];
       alinha[3] = oRetorno.aItens[iX][3];
       alinha[4] = '<input type="text" size="20" id="motiv'+oRetorno.aItens[iX][0]+'">';
       alinha[5] = '<input type="checkbox" onclick="js_addlote('+oRetorno.aItens[iX][0]+',this.checked);" >';
       oGridLotes.addRow(alinha);

    }
    oGridLotes.renderRows();

  } else {

    oGridLotes.clearAll(true);
    alert(oRetorno.sMessage.urlDecode());

  }

}
function js_addlote(iCod,bAdd) {
	
  if (bAdd == true) {

    sSep = '';
    if ($('codretirada').value != '') {
      sSep = ',';
    }
    $('codretirada').value += sSep+iCod;

  } else {

    aRetirada = $F('codretirada').split(',');
    aRetiradaNew = new Array();
    for (iX = 0; iX < aRetirada.length; iX++) {
        
      if (aRetirada[iX] != iCod) {
        aRetiradaNew[aRetiradaNew.length] = aRetirada[iX];
      }
    }
    $('codretirada').value = aRetiradaNew.join(',');

  }
  if ($('codretirada').value == '') {
    $('confirma').disabled = true;
  } else {
    $('confirma').disabled = false;
  }
}

function js_motivos() {

  aRetirada = $F('codretirada').split(',');
  aMotivos  = new Array();
  for (iX = 0; iX < aRetirada.length; iX++) {
    if ($('motiv'+aRetirada[iX]).value == '') {
      alert('Entre com o motivo da retirada '+aRetirada[iX]+'!')
      return false;
    } else {
      aMotivos[aMotivos.length] = $('motiv'+aRetirada[iX]).value;
    }
  }
  $('motivos').value = aMotivos.join('##');
  return true;
}

function js_ajax( objParam,jsRetorno ) {
    var objAjax = new Ajax.Request(
                           'vac4_vacinas.RPC.php', 
                           {
                            method    : 'post', 
                            parameters: 'json='+Object.toJSON(objParam),
                            onComplete: function(objAjax){
                                    var evlJS = jsRetorno+'( objAjax );';
                                    eval( evlJS );
                                  }
                           }
                          );
}

function js_limpar() {


  $('vc06_i_codigo').value  = '';
  $('vc06_c_descr').value   = '';
  $('m77_sequencial').value = '';
  $('m77_lote').value       = '';
  oGridLotes.clearAll(true);

}

function js_pesquisavc06_i_codigo(mostra) {

	  if (mostra == true) {

	    js_OpenJanelaIframe('top.corpo',
	                        'db_iframe_vac_vacina',
	                        'func_vac_vacina.php?funcao_js=parent.js_mostravac_vacina1|vc06_i_codigo|vc06_c_descr',
	                        'Pesquisa',
	                        true
	                       );

	  } else {

	    if (document.form1.vc13_i_vacina.value != '') {  

	      js_OpenJanelaIframe('top.corpo',
	                          'db_iframe_vac_vacina',
	                          'func_vac_vacina.php?pesquisa_chave='+document.form1.vc13_i_vacina.value+
	                          '&funcao_js=parent.js_mostravac_vacina',
	                          'Pesquisa',
	                          false
	                         );

	    } else {
	      document.form1.vc06_c_descr.value = ''; 
	     }
	  }
	}

	function js_mostravac_vacina(chave,erro) {

	  document.form1.vc06_c_descr.value = chave; 
	  if (erro == true) {  

	    document.form1.vc06_i_codigo.focus(); 
	    document.form1.vc06_i_codigo.value = ''; 

	  }
	  js_CarregaBoletim();
	}

	function js_mostravac_vacina1(chave1,chave2) {

	  document.form1.vc06_i_codigo.value = chave1;
	  document.form1.vc06_c_descr.value = chave2;
	  db_iframe_vac_vacina.hide();

	}

	function js_pesquisam77_sequencial(mostra) {

		  oFormFrame = document.form1; 
		  if (oFormFrame.vc06_i_codigo.value == '') {
			  
        alert('Escolha uma Vacina!');
        return false;
        
		  }
		  
		  if (mostra == true) {
			  
		    js_OpenJanelaIframe('', 'db_iframe_vac_vacinalote', 'func_vac_vacinalote.php?chave_vacina='+
		                        oFormFrame.vc06_i_codigo.value+'&funcao_js=parent.js_mostravac_vacinalote|m77_sequencial|'+
		                        'm77_lote', 'Pesquisa', true
		                       );

		  } else {

		     if (oFormFrame.m77_sequencial.value != '') {
			      
		       js_OpenJanelaIframe('', 'db_iframe_vac_vacinalote', 'func_vac_vacinalote.php?chave_vacina='+
		                           oFormFrame.vc06_i_codigo.value+'&chave_m77_sequencial='+oFormFrame.m77_sequencial.value+
		                           '&nao_mostra=true&funcao_js=parent.js_mostravac_vacinalote|m77_sequencial|'+
		                           'm77_lote', 'Pesquisa', false
		                          );

		     } else {
		       oFormFrame.vc01_c_nome.value = '';
		     }

		  }

		}
		function js_mostravac_vacinalote(sequencial, lote) {
			 
			oFormFrame = document.form1;
		  if (sequencial == '') {

		    oFormFrame.m77_sequencial.value = '';
		    oFormFrame.m77_lote.value                 = '';

		  } else {

		    oFormFrame.m77_sequencial.value           = sequencial;
		    oFormFrame.m77_lote.value                 = lote;

		  }
		  db_iframe_vac_vacinalote.hide();

		}
</script>