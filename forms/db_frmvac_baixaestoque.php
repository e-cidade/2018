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
$oDaoVacFechamento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc01_i_unidade");
$clrotulo->label("descrdepto");

?>

<form name="form1" method="post" action="">
<center>
<fieldset style='width: 40%;'> <legend><b>Baixar Doses Aplicadas</b></legend>
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
    <td nowrap >
      <b>Período:</b>
    </td>
    <td nowrap >
      <? db_inputdata('vc20_d_dataini',@$vc20_d_dataini_dia,@$vc20_d_dataini_mes,@$vc20_d_dataini_ano,true,'text',
                      $db_opcao,""
                     );?>
      Á
      <? db_inputdata('vc20_d_datafim',@$vc20_d_datafim_dia,@$vc20_d_datafim_mes,@$vc20_d_datafim_ano,true,'text',
                      $db_opcao,""
                     );?>
    </td>
  </tr>
</table>
</fieldset>
<br>
<input name="pesquisar" value="Pesquisar" type="button" onClick="js_CarregaLotes()">
<input name="limpar" value="Limpar" type="button" onClick="js_limpar()">
<br><br>
<fieldset style='width: 75%;'> <legend><b>Doses Aplicadas</b></legend>
<div id="GridLotes" name="Gridlotes"></div>
<input type="text" name="codlotes" id="codlotes" value="" style="display: none">
</fieldset>
<br>
<input name="confirma" id="confirma" type  = "submit"  value = "confirma" disabled >
</center>
</form>
<script>
oGridLotes = new DBGrid('GridLotes');
js_init();

//GridExames
function js_init() {

  var arrHeader = new Array (" Vacina ",  
                             " Lote ",
                             " Validade ",
                             " Quant Disp do Lote ",
                             " Doses por unidade do Lote",
                             " Doses Aplicadas ",
                             " Doses Descartadas ",
                             " Qtd a Baixar do Lote",
                             " Baixar");
  var aAligns = new Array();
  aAligns[0] = 'left';
  aAligns[1] = 'left';
  aAligns[2] = 'center';
  aAligns[3] = 'center';
  aAligns[4] = 'center';
  aAligns[5] = 'center';
  aAligns[6] = 'center';
  aAligns[7] = 'center';
  aAligns[8] = 'center';        
  
  oGridLotes.setCellAlign(aAligns);
  oGridLotes.nameInstance = 'GridLotes';
  oGridLotes.setHeader( arrHeader );
  oGridLotes.setHeight(80);
  
  oGridLotes.show($('GridLotes')); 

}

function js_CarregaLotes() {
	
  sDataini  = $F('vc20_d_dataini');
  sDatafim  = $F('vc20_d_datafim');
  if ((sDataini == '' || sDatafim == '') || 
      (sDatafim.split('/').reverse().join() < sDataini.split('/').reverse().join())) {

    alert('Entre com a data de inicio e fim do Fechamento!');
    oGridLotes.clearAll(true);
    return false;

  }
  var oParam        = new Object();
  oParam.exec       = 'getGridBaixa';
  oParam.iUnidade   = $F('vc01_i_unidade');
  oParam.iDataini   = sDataini.split('/').reverse().join('-');
  oParam.iDatafim   = sDatafim.split('/').reverse().join('-');
  js_ajax( oParam, 'js_RetornoCarregaLotes' );

}

function js_RetornoCarregaLotes(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 1) {

    oGridLotes.clearAll(true);
    iTam = oRetorno.aItens.length;
    for (iX = 0; iX < iTam; iX++) {

       alinha= new Array();
       alinha[0] = oRetorno.aItens[iX][0].urlDecode();
       alinha[1] = oRetorno.aItens[iX][1];
       alinha[2] = oRetorno.aItens[iX][2];
       alinha[3] = oRetorno.aItens[iX][3];
       alinha[4] = oRetorno.aItens[iX][4];
       alinha[5] = oRetorno.aItens[iX][5];
       alinha[6] = oRetorno.aItens[iX][6];
       sBloqueio = '';
       if (oRetorno.aItens[iX][7].split('.').length > 1) {

         iValor    = 'Falta Descartar Doses';
         sBloqueio = 'disabled';

       } else {

         iValor    = oRetorno.aItens[iX][7];
         sBloqueio = '';

       }
       alinha[7]  = iValor;
       alinha[8]  = '<input name="baixar'+iX+'" type="checkbox" '+sBloqueio;
       alinha[8] += ' onclick="js_addlote('+oRetorno.aItens[iX][8]+',this.checked)">';
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
    if ($('codlotes').value != '') {
      sSep = ',';
    }
    $('codlotes').value += sSep+iCod;

  } else {

    aLotes    = $F('codlotes').split(',');
    aLotesNew = new Array();
    for (iX = 0; iX < aLotes.length; iX++) {
        
      if (aLotes[iX] != iCod) {
        aLotesNew[aLotesNew.length] = aLotes[iX];
      }
    }
    $('codlotes').value = aLotesNew.join(',');

  }
  
  if ($('codlotes').value == '') {
    $('confirma').disabled = true;
  } else {
	  $('confirma').disabled = false;
  }
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

  $('vc20_d_dataini').value = '';
  $('vc20_d_datafim').value = '';
  oGridLotes.clearAll(true);

}

</script>