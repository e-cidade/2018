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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');

$oRotulo = new rotulocampo;
$oRotulo->label('fa04_i_cgsund');
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
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<div class='container'>
  <fieldset style='width: 100%;'> 
    <legend>Reemissão do Comprovante de Retirade de Medicamentos</legend>
    <table border="0" width="90%">
      <tr>
        <td nowrap="nowrap">
          <?
          db_ancora($Lfa04_i_cgsund, 'js_pesquisafa04_i_cgsund(true);', 1);
          ?>
        </td>
        <td nowrap="nowrap"> 
          <?
          db_input('fa04_i_cgsund', 10, $Ifa04_i_cgsund, true, 'text', 1,
                   ' onchange="js_pesquisafa04_i_cgsund(false);"'
                  );
          db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value="Pesquisar" onclick="js_getRetiradasCgs();">
</div>
<div class='container' style="width: 1000px;">
  <fieldset style='width: 100%;'> 
    <legend>Retiradas</legend> 
    <div id='grid_retiradas' style='width: 100%;'></div>
  </fieldset>
</div>
<?
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
?>

<script>

oDBGridRetiradas = js_criaDataGrid();

function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'far4_farmacia.RPC.php';
  }

  if (lAsync == undefined) {
    lAsync = false;
  }
	
  var oAjax = new Ajax.Request(sUrl, 
                               {
                                 method: 'post', 
                                 asynchronous: lAsync,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax) {
                                    
                                               var evlJS    = jsRetorno+'(oAjax);';
                                               return mRetornoAjax = eval(evlJS);
                                               
                                           }
                              }
                             );

  return mRetornoAjax;

}

function js_formataData(dData) {
  
  if (dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8, 2)+'/'+dData.substr(5, 2)+'/'+dData.substr(0, 4);

}

function js_getRetiradasCgs() {

  if ($F('fa04_i_cgsund') == '') {

    alert('Informe um CGS.');
	  return false;

  }
  
  var oParam  = new Object();
	oParam.exec = 'getRetiradasCgs';
	oParam.iCgs = $F('fa04_i_cgsund');

  js_ajax(oParam, 'js_retornoGetRetiradasCgs');

}

function js_retornoGetRetiradasCgs(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert('Nenhuma retirada de medicamentos encontrada.');
    return false;

  } else {

    var iTam = oRetorno.aRetiradas.length;
    for (var iCont = 0; iCont < iTam; iCont++) {

      with (oRetorno.aRetiradas[iCont]) {

        var aLinha = new Array();
        
        aLinha[0]  = fa04_i_codigo.urlDecode();
        aLinha[1]  = js_formataData(fa04_d_data);
        aLinha[2]  = fa04_i_unidades+' - '+descrdepto.urlDecode();
        aLinha[3]  = '<input type="button" title="Reemitir" value="Reemitir" ';
        aLinha[3] += 'onclick="js_emitirComprovante('+fa04_i_codigo+', '+fa04_tiporetirada+');">';
        if (oRetorno.lUtilizaImpressaoTermica) {
        
          aLinha[3] += '<input type="button" title="Reimpressão Comprovante" value="Reimp. Comp." ';
          aLinha[3] += 'onclick="js_emitirComprovanteTermica('+fa04_i_codigo+', '+fa04_tiporetirada+');">';
        }        
        oDBGridRetiradas.addRow(aLinha);

      }

    }
    oDBGridRetiradas.renderRows();

  }

}

/**** Bloco de funções do grid início */
function js_criaDataGrid() {

  oDBGrid                = new DBGrid('grid_retiradas');
  oDBGrid.nameInstance   = 'oDBGridRetiradas';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('10%', '10%', '60%', '20%'));
  oDBGrid.setHeight(250);

  var aHeader = new Array();
  aHeader[0] = 'Retirada';
  aHeader[1] = 'Data';
  aHeader[2] = 'UPS';
  aHeader[3] = 'Opção';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0] = 'left';
  aAligns[1] = 'left';
  aAligns[2] = 'left';
  aAligns[3] = 'center';
  oDBGrid.setCellAlign(aAligns);

  oDBGrid.show($('grid_retiradas'));
  oDBGrid.clearAll(true);
  
  return oDBGrid;

}


function js_emitirComprovante(iRetirada, iTipoRetirada) {
	   
  sGet = 'nvias=1&ini='+iRetirada+'&fim='+iRetirada+'&iTipoRetirada='+iTipoRetirada;
  oJan = window.open('far1_atendretira001.php?'+sGet, '',
                     'width='+(screen.availWidth - 5)+',height='+(screen.availHeight - 40)+
                     ',scrollbars=1,location=0 '
                    );
  oJan.moveTo(0, 0);
	 
}

function js_pesquisafa04_i_cgsund(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostracgs1|'+
                        'z01_i_cgsund|z01_v_nome', 'Pesquisa', true
                       );

  } else {

     if ($F('fa04_i_cgsund') != '') { 

       js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?pesquisa_chave='+
                           $F('fa04_i_cgsund')+
                           '&funcao_js=parent.js_mostracgs', 'Pesquisa', false
                          );

     } else {

       $('z01_v_nome').value = ''; 
       js_limpaInfoCgs();

     }

  }

}
function js_mostracgs(chave, erro){
  
  js_limpaInfoCgs();
  $('z01_v_nome').value    = chave; 
  if (erro == true){ 

    $('fa04_i_cgsund').focus(); 
    $('fa04_i_cgsund').value = ''; 

  }

}
function js_mostracgs1(chave1, chave2){

  js_limpaInfoCgs();
  $('fa04_i_cgsund').value = chave1;
  $('z01_v_nome').value    = chave2;
  db_iframe_cgs_und.hide();

}

function js_limpaInfoCgs() {
  oDBGridRetiradas.clearAll(true);
}

function js_emitirComprovanteTermica(iRetirada, iTipoRetirada) {
  
  var oParam           = new Object();
  oParam.exec          = 'impressaoComprovante';
  oParam.iRetirada     = iRetirada;
  oParam.iTipoRetirada = iTipoRetirada;
  
  js_divCarregando('Aguarde, imprimindo', 'msgBox');
  var oAjax = new Ajax.Request('far4_impressaoComprovante.RPC.php', 
                              {method:'post',
                              asynchronous:false,
                              parameters:'json='+Object.toJSON(oParam),
                              onComplete:js_retornoCupom
                              })
}

function js_retornoCupom(oAjax) {
  js_removeObj('msgBox');
}
</script>
</body>
</html>