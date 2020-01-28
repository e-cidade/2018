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

//MODULO: TFD
$oDaotfd_documentosentregues->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tf01_i_codigo");
$clrotulo->label("tf01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("tf07_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
  <tr>
    <td nowrap title="<?=@$Ttf01_i_codigo?>">
      <?=@$Ltf22_i_pedidotfd?>
    </td>
    <td> 
      <?
      db_input('tf01_i_codigo',10,$Itf01_i_codigo,true,'text',3);

      /**** Obtém os documentos do tipo de tratamento */
      $sSql = $oDaotfd_tipotratamentodoc->sql_query(null, '*', 'tf06_i_codigo', 
                                                    'tf06_i_tipotratamento = '.$tf01_i_tipotratamento);
      $rs = $oDaotfd_tipotratamentodoc->sql_record($sSql);

      $aX = array();
      for($iCont = 0; $iCont < $oDaotfd_tipotratamentodoc->numrows; $iCont++) {
        
        $oDados = db_utils::fieldsmemory($rs, $iCont);
        $aX[$oDados->tf07_c_descr] = $oDados->tf07_i_codigo.','.$oDados->tf06_i_obrigatorio;

      }
      db_select('select_documento',$aX,true,1,'style="display: none;"'); // select usado na renderização do grid
      /* fim ****/

      /**** Obtém os documentos entregues se o pedido já foi confirmado */
      if(isset($tf01_i_codigo) && !empty($tf01_i_codigo) && !isset($confirmar)) {

        $sSql = $oDaotfd_documentosentregues->sql_query(null, '*', 'tf22_i_codigo', 
                                                       'tf22_i_pedidotfd = '.$tf01_i_codigo);
        $rs = $oDaotfd_documentosentregues->sql_record($sSql);

        $entregues = '';
        for($iCont = 0; $iCont < $oDaotfd_documentosentregues->numrows; $iCont++) {
        
          $oDados = db_utils::fieldsmemory($rs, $iCont);
          $entregues .= $oDados->tf22_i_documento.','.formataData($oDados->tf22_d_dataentrega, 2).','.$oDados->tf22_c_numdoc.' ## ';

        }

      }
      db_input('entregues',10,'',true,'hidden',3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf01_i_cgsund?>">
      <?=@$Ltf01_i_cgsund?>
    </td>
    <td> 
      <?
      db_input('tf01_i_cgsund',10,$Itf01_i_cgsund,true,'text',3);
      db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <div id='grid_documentos' style="width:100%;"></div>
    </td>
  </tr>
</table>
</center>
<input name="confirmar" type="<?=(isset($tf01_i_codigo) ? 'submit' : 'button')?>" id="confirmar" value="Confirmar" onclick="return js_validaEntrega(<?=(isset($tf01_i_codigo) ? 'true' : 'false')?>);">
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_documentos.hide();">
</form>

<script>

/* Bloco de Funções que tratam do grid / select dos documentos (fim) *****/
oDBGridDocumentos = js_cria_datagrid();
js_renderizaGrid(<?=(isset($tf01_i_codigo) ? 'true' : 'false')?>);

js_init(<?=(isset($tf01_i_codigo) ? 'true' : 'false')?>);
function js_init(lAlterar) {
    
  if(!lAlterar) {
    sValor = parent.document.getElementById('entregues').value;
  } else {
    sValor = $F('entregues');
  }

  aValor = sValor.split(' ## ');
  
  for(i = 0; i < aValor.length - 1; i++) {

    aInfo = aValor[i].split(',');  // aInfo[0] => ID do documento, aInfo[1] => Data da entrega, aInfo[2] => N° do documento
    oCkbox = $('ckbox'+aInfo[0]);
    if(oCkbox != undefined) {

      oCkbox.checked = true;
      aInfo2 = oCkbox.value.split(','); // aInfo2[0] => ID do documento, aInfo2[1] => 1 ou 2 (obrigatório ou não), aInfo2[2] => N° da linha
      $('dt'+aInfo2[2]).value = aInfo[1];
      $('numdoc'+aInfo2[2]).value = aInfo[2];

    }

  }


}
function js_renderizaGrid(lAlterar) {

  var oF = $('select_documento');
  oDBGridDocumentos.clearAll(true);
  var aLinha = new Array();
  for(i = 0; i < oF.length; i++) {

    aInfo = oF.options[i].innerHTML.split(',');
    sDisabled = ''
    if(aInfo[1] == 1) {

      sSN = 'SIM';
      sDisabled = ' disabled checked ';

    } else {
      sSN = 'NÃO';
    }

    if(!lAlterar) {
      sDisabled = '';
    }

    aLinha[0]  = '<input type="checkbox"'+sDisabled+'name="ckbox" id="ckbox'+aInfo[0]+'" value="'+aInfo[0]+','+aInfo[1]+','+i+'">';
    aLinha[1]  = oF.options[i].value;
    aLinha[2]  = sSN; 
    aLinha[3]  = '<input size="8" maxlength="10" type="text" name="dt'+i+'" id="dt'+i+'" onfocus="js_validaEntrada(this);" onkeyup="return js_mascaraData(this,event);" onblur="js_validaDbData(this);">'; 
    aLinha[3]  += '<input type="hidden" name="dt'+i+'_dia" id="dt'+i+'_dia">'; 
    aLinha[3]  += '<input type="hidden" name="dt'+i+'_mes" id="dt'+i+'_mes">'; 
    aLinha[3]  += '<input type="hidden" name="dt'+i+'_ano" id="dt'+i+'_ano">'; 
    aLinha[4]  = '<input type="text" maxlength="20" name="numdoc'+i+'" id="numdoc'+i+'" size="10">'; 
    oDBGridDocumentos.addRow(aLinha);

  }
  oDBGridDocumentos.renderRows();

}

function js_cria_datagrid() {

        oDBGridDocumentos = new DBGrid('grid_documentos');
        oDBGridDocumentos.nameInstance = 'oDBGridDocumentos';
        oDBGridDocumentos.setCellWidth(new Array('5%','45%','5%','20%','25%'));
        oDBGridDocumentos.setHeight(300);

        //oDBGridDocumentos.setCheckbox(0);
        var aHeader = new Array();
        aHeader[0] = '<input type="button" value="M" id="marcarTodos" onclick="js_marcarTodos();">';
        aHeader[1] = 'Documentos';
        aHeader[2] = 'Obrigatório';
        aHeader[3] = 'Data da Entrega';
        aHeader[4] = 'Nro. Doc.';
        oDBGridDocumentos.setHeader(aHeader);
        //oDBGridDocumentos.aHeader[11].lDisplayed = false;
        oDBGridDocumentos.allowSelectColumns(true);
        var aAligns = new Array();
        aAligns[0] = 'center';
        aAligns[1] = 'center';
        aAligns[2] = 'center';
        aAligns[3] = 'center';
        aAligns[4] = 'center';
        
        oDBGridDocumentos.setCellAlign(aAligns);
        oDBGridDocumentos.allowSelectColumns(false);
        oDBGridDocumentos.show($('grid_documentos'));
        oDBGridDocumentos.clearAll(true);

        return oDBGridDocumentos;

}

function js_marcarTodos() {
    
  oElementos = document.getElementsByName('ckbox');
  if(document.getElementById('marcarTodos').value == 'M') {

    for(i = 0; i < oElementos.length; i++) {
     
      if(!oElementos[i].disabled) {
        oElementos[i].checked = true;
      }

    }
    document.getElementById('marcarTodos').value = 'D';

  } else {
 
    for(i = 0; i < oElementos.length; i++) {
     
      if(!oElementos[i].disabled) {
        oElementos[i].checked = false;
      }

    }
    document.getElementById('marcarTodos').value = 'M';

  }

}

function js_validaEntrega(lAlterar) {
    
  oElementos = document.getElementsByName('ckbox');
  oEntregues = parent.document.getElementById('entregues');
  oLentregues = parent.document.getElementById('lEntregues');
  sValor = '';
  lObrigatoriosEntregues = true;

  for(i = 0; i < oElementos.length; i++) {
    
    aInfo = oElementos[i].value.split(','); // aInfo[0] => ID do documento, aInfo[1] => 1 ou 2 (obrigatório ou não), aInfo[2] => N° da linha

    if(oElementos[i].checked) {
     
      dData = $F('dt'+aInfo[2]);
      iNumdoc = $F('numdoc'+aInfo[2]);

      if(dData == '' || iNumdoc == '') {

        alert('Preencha todas as informações dos documentos que foram entregues.');
        return false;

      }
      sValor += aInfo[0]+','+dData+','+iNumdoc+' ## ';

    } else {

      if(aInfo[1] == 1) { // o documento é obrigatório e não foi entregue
        lObrigatoriosEntregues = false;
      }

    }

  }

  if(!lObrigatoriosEntregues) {

    if(!confirm('Os documentos obrigatórios não foram todos entregues e sem eles não é possível confirmar o pedido.\n'+
                'Deseja continuar mesmo assim?')) {
      return false;
    }

  }

  if(lObrigatoriosEntregues) {
    oLentregues.value = 'true';
  } else {
    oLentregues.value = 'false';
  }
  
  if(lAlterar && $F('entregues') == sValor) {
    
    alert("Nada a ser alterado.");
    return false;

  }

  oEntregues.value = sValor;
  $('entregues').value = sValor;

  if(lAlterar) {
    document.form1.submit();
  } else {
    parent.db_iframe_documentos.hide();
  }

}
/* Bloco de Funções que tratam do grid / select dos documentos (fim) *****/

</script>