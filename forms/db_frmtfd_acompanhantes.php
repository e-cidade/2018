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

//MODULO: TFD
$oDaotfd_acompanhantes->rotulo->label();
$oDaocgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tf08_i_codigo");
$clrotulo->label("tf01_i_codigo");
$clrotulo->label("tf01_i_cgsund");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("j13_codi");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf13_i_codigo?>">
       <?=@$Ltf13_i_codigo?>
    </td>
    <td> 
      <?
      db_input('tf13_i_codigo',10,$Itf13_i_codigo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf21_i_pedidotfd?>">
      <?=@$Ltf13_i_pedidotfd?>
    </td>
    <td> 
      <?
      db_input('tf13_i_pedidotfd',10,$Itf13_i_pedidotfd,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf01_i_cgsund?>">
      <?
      echo '<b>Paciente</b>';
      ?>
    </td>
    <td nowrap> 
      <?
      db_input('tf01_i_cgsund',10,$Itf01_i_cgsund,true,'text',3,'');
      db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf13_i_motivoacompanhamento?>">
       <?=@$Ltf13_i_motivoacompanhamento?>
    </td>
    <td> 
      <?
      $dDataAtual = date("'Y-m-d'", db_getsession('DB_datausu'));
      $aX = array();
      $sSql = $oDaotfd_motivoacompanhamento->sql_query_file(null, ' * ', ' tf08_c_descr ',
                                                            ' tf08_d_validade is null or tf08_d_validade >= '.$dDataAtual);
      $rs = $oDaotfd_motivoacompanhamento->sql_record($sSql);

      for($iCont = 0; $iCont < $oDaotfd_motivoacompanhamento->numrows; $iCont++) {

        $oDados = db_utils::fieldsmemory($rs, $iCont);
        $aX[$oDados->tf08_i_codigo] = $oDados->tf08_c_descr;

      }
      db_select('tf13_i_motivoacompanhamento',$aX,true,'');
      ?>
      <br>
    </td>
  </tr>
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf13_i_anulado?>">
      <?=@$Ltf13_i_anulado?>
    </td>
    <td> 
      <?
      db_input('tf13_i_anulado',10,$Itf13_i_anulado,true,'text',3,"")
      ?>
    </td>
  </tr>
</table>


<fieldset style='width: 92%;'> <legend><b>Dados do Acompanhante</b></legend>
  <table border="0" width="90%">
    <tr>
      <td nowrap title="<?=@$Ttf13_i_cgsund?>" colspan="2">
        <?
        db_ancora(@$Ltf13_i_cgsund,"js_pesquisatf13_i_cgsund(true);",$db_opcao);
        echo '&nbsp;&nbsp;&nbsp;';
        db_input('tf13_i_cgsund',10,$Itf13_i_cgsund,true,'text',$db_opcao," onchange='js_pesquisatf13_i_cgsund(false);'");
        db_input('z01_v_nome2',50,$Iz01_v_nome,true,'text',3,'');
        ?>
      </td>
    </tr>
    <tr>
      <td width="50%" valign="top">
        <fieldset style='width: 92%;'> <legend><b>Endereço</b></legend>
          <table width="100%">
            <tr>
              <td nowrap title="<?=@$Tz01_v_ender?>">
                <?db_ancora(@$Lz01_v_ender,"js_ruas();",$db_opcao);?>&nbsp;
              </td>
              <td nowrap>
                <?db_input('z01_v_ender',30,$Iz01_v_ender,true,'text',$db_opcao,'onchange="js_change();"');?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Tz01_v_bairro?>">
                <?db_ancora(@$Lz01_v_bairro,"js_bairro();",$db_opcao);?>&nbsp;
              </td>
              <td nowrap>
                <?db_input('j13_codi',10,$Ij13_codi,true,'hidden',$db_opcao);?>
                <?db_input('z01_v_bairro',30,$Iz01_v_bairro,true,'text',3,'onchange="js_change();"');?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_munic?>">
                <?=@$Lz01_v_munic?>&nbsp;
              </td>
              <td nowrap>
                <?db_input('z01_v_munic',30,$Iz01_v_munic,true,'text',$db_opcao,'onchange="js_change();"');?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_cep?>">
                <?
	              db_ancora(@$Lz01_v_cep,"js_cepcon(true);",$db_opcao);
	              ?>&nbsp;
              </td>
              <td nowrap>
                <?db_input('z01_v_cep',8,$Iz01_v_cep,true,'text',$db_opcao,'onchange="js_change();"');?>
                <input type="button" name="buscacep" value="Pesquisar" onclick="js_cepcon(false);">&nbsp;
                <?=@$Lz01_v_uf?>&nbsp;
                <?db_input('z01_v_uf',2,$Iz01_v_uf,true,'text',$db_opcao,'onchange="js_change();"');?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_email?>">
                <?=@$Lz01_v_email?>&nbsp;
              </td>
              <td nowrap>
                <?db_input('z01_v_email',30,$Iz01_v_email,true,'text',$db_opcao,'onchange="js_change();"');?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_telef?>">
                <?=@$Lz01_v_telef?>&nbsp;
              </td>
              <td nowrap>
                <?db_input('z01_v_telef',15,$Iz01_v_telef,true,'text',$db_opcao,'onchange="js_change();"');?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_telcel?>">
                <?=@$Lz01_v_telcel?>&nbsp;
              </td>
              <td nowrap>
                <?db_input('z01_v_telcel',15,$Iz01_v_telcel,true,'text',$db_opcao,'onchange="js_change();"');?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
      <td width="50%" valign="top">
        <fieldset style='width: 92%;'> <legend><b>Dados Pessoais</b></legend>
          <table width="100%">
            <tr>
              <td nowrap>
                <b>Cartao SUS:</b>&nbsp;
              </td>
              <td nowrap>
                <?
                $z01_i_cgsund2 = '';
                db_input('s115_i_codigo',1,'',true,'hidden',3);
                db_input('z01_i_cgsund2',1,'',true,'hidden',3);
           	    db_input('s115_c_cartaosus',14,@$Is115_c_cartaosus,true,'text',$db_opcao,'onchange="js_change();"');
                ?>
                <b>Tipo:</b>&nbsp;
                <?
		            $x = array("D"=>"Definitivo","P"=>"Provisório");
	        	    db_select('s115_c_tipo',$x,true,$db_opcao,'onchange="js_change();"');
	              ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Tz01_d_nasc?>">
                <?=$Lz01_d_nasc?>&nbsp;
              </td>
              <td nowrap title="<?=$Tz01_d_nasc?>">
                <?
                db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',$db_opcao,'onchange="js_change();"','','','parent.js_change();');
                ?>
              </td>
            </tr>
            <tr>
              <td title='<?=$Tz01_i_cgsund?>' nowrap>
                <?=@$Lz01_v_cgccpf?>&nbsp;
              </td>
              <td nowrap>
                <?
                db_input('z01_v_cgccpf',10,@$Iz01_v_cgccpf,true,'text',$db_opcao,"onblur='js_verificaCGCCPF(this);' onchange=\"js_change();\"");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?=@$Lz01_v_ident?>&nbsp;
              </td>
              <td nowrap>
                <?
                db_input('z01_v_ident',10,$Iz01_v_ident,true,'text',$db_opcao,'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title=<?=@$Tz01_v_mae?>>
                <?=@$Lz01_v_mae?>&nbsp;
              </td>
              <td nowrap title="<?=@$Tz01_v_mae?>">
                <?
                db_input('z01_v_mae',30,$Iz01_v_mae,true,'text',$db_opcao,'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title=<?=@$Tz01_v_pai?>>
                <?=@$Lz01_v_pai?>&nbsp;
              </td>
              <td nowrap title="<?=@$Tz01_v_pai?>">
                <?
                db_input('z01_v_pai',30,$Iz01_v_pai,true,'text',$db_opcao,'onchange="js_change();"');
                db_input('mudanca',1,'',true,'hidden',3);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</fieldset>

  <table border="0" width="90%">
    <tr>
      <td align="right">
        <input name="atualizar" type="button" id="atualizar" value="Atualizar CGS" onclick="js_atualizarCgs();" disabled>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
        <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_acompanhantes.hide();">
      </td>
    </tr>
  </table>

  <table border="0" width="100%">
    <tr>
      <td>
        <div id='grid_acompanhantes' style='width: 100%;'></div>
      </td>
    </tr>
  </table>


</center>
</form>
<script>

lPermissaoCgs = <? echo db_permissaomenu(db_getsession('DB_anousu'), 1000004, 1045411).';'; ?>

oDBGridAcompanhantes = js_cria_datagrid();
sUrl = 'tfd4_pedidotfd.RPC.php';
js_getAcompanhantesPedidoTfd();

function js_ajax(oParam, jsRetorno) {

	var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                          method    : 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: function(objAjax) {
                          				var evlJS = jsRetorno+'(objAjax);';
                                  return eval(evlJS);
                          			}
                         }
                        );

}


/**** Bloco de funções do grid início */
function js_cria_datagrid() {

        oDBGrid = new DBGrid('grid_acompanhantes');
        oDBGrid.nameInstance = 'oDBGridPedidostfd';
        oDBGrid.hasTotalizador = false;
        oDBGrid.setCellWidth(new Array('10%', '50%', '10%', '10%', '20%'));
        oDBGrid.setHeight(100);
        oDBGrid.allowSelectColumns(false);

        var aHeader = new Array();
        aHeader[0] = 'CGS';
        aHeader[1] = 'Acompanhante';
        aHeader[2] = 'RG';
        aHeader[3] = 'CPF';
        aHeader[4] = 'Opções';
        oDBGrid.setHeader(aHeader);

        var aAligns = new Array();
        aAligns[0] = 'center';
        aAligns[1] = 'center';
        aAligns[2] = 'center';
        aAligns[3] = 'center';
        aAligns[4] = 'center';
        
        oDBGrid.setCellAlign(aAligns);
        oDBGrid.show($('grid_acompanhantes'));
        oDBGrid.clearAll(true);

        return oDBGrid;

}

function js_getAcompanhantesPedidoTfd() {

  var oParam = new Object();
	oParam.exec = 'getAcompanhantesPedidoTfd';
	oParam.iPedido = $F('tf13_i_pedidotfd');

  if($F('tf13_i_pedidotfd') != '') {
    js_ajax(oParam, 'js_retornogetAcompanhantesPedidoTfd');
  }

}

function js_retornogetAcompanhantesPedidoTfd(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if(oRetorno.iStatus != 1) {
    return false;
  } else {

  oRetorno.oAcompanhantes.each(
    function (oAcompanhantes) {
        
      var aLinha = new Array();
      var sValor = '';

      if(oAcompanhantes.tf13_i_anulado == 2) {
        sValor = 'Anular';
      } else {
        sValor = 'Desanular';
      }

      aLinha[0] = oAcompanhantes.z01_i_cgsund;
      aLinha[1] = oAcompanhantes.z01_v_nome.urlDecode().substr(0, 40);
      aLinha[2] = '&nbsp;&nbsp;&nbsp;'+oAcompanhantes.z01_v_ident.urlDecode()+'&nbsp;&nbsp;&nbsp;';
      aLinha[3] = '&nbsp;&nbsp;&nbsp;'+oAcompanhantes.z01_v_cgccpf.urlDecode()+'&nbsp;&nbsp;&nbsp;';
      aLinha[4] = '<input type="button" value="'+sValor+'" '+
                  'onclick="js_anularAcompanhante('+oAcompanhantes.tf13_i_codigo+', this);">'+
                  '&nbsp;&nbsp;<input type="button" value="Excluir" '+
                  'onclick="js_excluirAcompanhante('+oAcompanhantes.tf13_i_codigo+', this);">';

      oDBGridAcompanhantes.addRow(aLinha);

    });
    oDBGridAcompanhantes.renderRows();

  }

}

oBotaoAnular = '';

function js_anularAcompanhante(iCodigo, oButton) {
  
  oButton.disabled = true;
  oBotaoAnular = oButton;

  if(confirm('Deseja '+oButton.value.toLowerCase()+' este acompanhante?')) {

    var oParam = new Object();
    oParam.exec = 'anularAcompanhante';
  	oParam.iCodigo = iCodigo;

    if(oButton.value == 'Anular') {
      oParam.lAnular = true;
    } else {
      oParam.lAnular = false;
    }

    js_ajax(oParam, 'js_retornoanularAcompanhante');

  }
  oBotaoAnular.disabled = false;

}

function js_retornoanularAcompanhante(oRetorno) {

  oRetorno = eval('('+oRetorno.responseText+')');

  if(oRetorno.iStatus != 1) {
    alert(oRetorno.sMessage);
  } else {

    alert('Acompanhante '+oBotaoAnular.value.substr(0, oBotaoAnular.value.length - 1).toLowerCase()+'do com sucesso.');

    if(oBotaoAnular.value == 'Anular') {
      oBotaoAnular.value = 'Desanular';
    } else {
      oBotaoAnular.value = 'Anular';
    }
  }

}

function js_excluirAcompanhante(iCodigo, oButton) {

  oButton.disabled = true;
  oBotaoAnular = oButton;

  if(confirm('Deseja excluir este acompanhante?')) {

    var oParam = new Object();
    oParam.exec = 'excluirAcompanhante';
  	oParam.iCodigo = iCodigo;

    js_ajax(oParam, 'js_retornoexcluirAcompanhante');

  }

}

function js_retornoexcluirAcompanhante(oRetorno) {

  oRetorno = eval('('+oRetorno.responseText+')');

  if(oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage);
    oBotaoAnular.disabled = false;

  } else {

    alert('Acompanhante excluído com sucesso.');
    oDBGridAcompanhantes.clearAll(true);
    js_getAcompanhantesPedidoTfd();

  }

}
/* Bloco de funções do grid fim *****/



/**** Bloco de funções dos dados do CGS início */
 function js_ruas() {
  js_OpenJanelaIframe('','db_iframe','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas(chave,chave1) {

   document.form1.z01_v_ender.value = chave1;
   db_iframe_ruas.hide();
   js_change();

 }

 function js_bairro() {
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
 }

 function js_preenchebairro(chave,chave1) {

  document.form1.j13_codi.value = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
  js_change();

 }

 function js_cepcon(abre) {

  if(abre == true) {
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_v_cep.value+'&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',false);
  }
}
function js_preenchecepcon(chave,chave1,chave2,chave3,chave4) {

  document.form1.z01_v_cep.value = chave;
  document.form1.z01_v_ender.value = chave1;
  document.form1.z01_v_munic.value = chave2;
  document.form1.z01_v_uf.value = chave3;
  document.form1.z01_v_bairro.value = chave4;
  db_iframe_cep.hide();
  js_change();

}
function js_preenchecepcon1(chave,chave1,chave2,chave3,chave4) {

  if(chave=="" && chave1 == "" && chave2 == "" && chave3=="" && chave4=="" && chave4=="") {

    alert('CEP não encontrado.');
    document.form1.z01_v_cep.focus();

  }

  document.form1.z01_v_cep.value = chave;
  document.form1.z01_v_ender.value = chave1;
  document.form1.z01_v_munic.value = chave2;
  document.form1.z01_v_uf.value = chave3;
  document.form1.z01_v_bairro.value = chave4;
  js_change();

}

function js_getInfoCgs() {

  var oParam = new Object();
	oParam.exec = "getInfoCgs";
	oParam.iCgs = $F('tf13_i_cgsund');

  if($F('tf13_i_cgsund') != '') {
    js_ajax(oParam, 'js_retornogetInfoCgs');
  }

}

function js_retornogetInfoCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if(oRetorno.z01_d_nasc != '') {

    aNasc = oRetorno.z01_d_nasc.split('-');
    dNasc = aNasc[2]+'/'+aNasc[1]+'/'+aNasc[0];

  } else {

    aNasc = new Array('','','');
    dNasc = '';
  }

  $('z01_v_ender').value = oRetorno.z01_v_ender.urlDecode(); 
  $('z01_v_bairro').value = oRetorno.z01_v_bairro.urlDecode();
  $('z01_v_munic').value = oRetorno.z01_v_munic.urlDecode();
  $('z01_v_cep').value = oRetorno.z01_v_cep.urlDecode();
  $('z01_v_uf').value = oRetorno.z01_v_uf.urlDecode();
  $('z01_v_email').value = oRetorno.z01_v_email.urlDecode();
  $('z01_v_telef').value = oRetorno.z01_v_telef.urlDecode();
  $('z01_v_telcel').value = oRetorno.z01_v_telcel.urlDecode();
  $('z01_d_nasc').value = dNasc;
  $('z01_d_nasc_dia').value = aNasc[2];
  $('z01_d_nasc_mes').value = aNasc[1];
  $('z01_d_nasc_ano').value = aNasc[0];
  $('z01_v_cgccpf').value = oRetorno.z01_v_cgccpf.urlDecode();
  $('z01_v_ident').value = oRetorno.z01_v_ident.urlDecode();
  $('z01_v_mae').value = oRetorno.z01_v_mae.urlDecode();
  $('z01_v_pai').value = oRetorno.z01_v_pai.urlDecode();
  $('s115_c_cartaosus').value = oRetorno.s115_c_cartaosus.urlDecode();
  $('s115_i_codigo').value = oRetorno.s115_i_codigo.urlDecode();

  if(oRetorno.z01_i_cgsund != '') { 

    $('db_opcao').disabled = false;
    $('z01_i_cgsund2').value = oRetorno.z01_i_cgsund;

  }

  if(oRetorno.s115_c_tipo == 'D') {
    $('s115_c_tipo').options[0].selected = true;
  } else {
    $('s115_c_tipo').options[1].selected = true;
  }

}

function js_limpaInfoCgs() {

  $('z01_v_ender').value = '';
  $('z01_v_bairro').value = '';
  $('z01_v_munic').value = '';
  $('z01_v_cep').value = '';
  $('z01_v_uf').value = '';
  $('z01_v_email').value = '';
  $('z01_v_telef').value = '';
  $('z01_v_telcel').value = '';
  $('z01_d_nasc').value = '';
  $('z01_d_nasc_dia').value = '';
  $('z01_d_nasc_mes').value = '';
  $('z01_d_nasc_ano').value = '';
  $('z01_v_cgccpf').value = '';
  $('z01_v_ident').value = '';
  $('z01_v_mae').value = '';
  $('z01_v_pai').value = '';
  $('s115_c_cartaosus').value = '';
  $('s115_i_codigo').value = '';
  $('s115_c_tipo').options[0].selected = true;
  $('atualizar').disabled = true;
  $('db_opcao').disabled = true;
  $('mudanca').value = '';
  $('z01_v_nome2').value = '';
  $('z01_i_cgsund2').value = '';

}

function js_atualizarCgs() {

  oParam = new Object();

  oParam.exec = 'atualizarCgs';
  oParam.iCgs = $F('tf13_i_cgsund');
  oParam.iCgs = $F('z01_i_cgsund2');
  oParam.z01_v_ender = $F('z01_v_ender');
  oParam.z01_v_bairro = $F('z01_v_bairro');
  oParam.z01_v_munic = $F('z01_v_munic'); 
  oParam.z01_v_cep = $F('z01_v_cep'); 
  oParam.z01_v_uf = $F('z01_v_uf');
  oParam.z01_v_email = $F('z01_v_email'); 
  oParam.z01_v_telef = $F('z01_v_telef');
  oParam.z01_v_telcel = $F('z01_v_telcel');
  oParam.z01_d_nasc = $F('z01_d_nasc');
  oParam.z01_v_cgccpf = $F('z01_v_cgccpf');
  oParam.z01_v_ident = $F('z01_v_ident'); 
  oParam.z01_v_mae = $F('z01_v_mae'); 
  oParam.z01_v_pai = $F('z01_v_pai'); 
  oParam.s115_c_cartaosus = $F('s115_c_cartaosus');
  oParam.s115_c_tipo = $F('s115_c_tipo');
  oParam.s115_i_codigo = $F('s115_i_codigo');
  
  js_ajax(oParam, 'js_retornoatualizarCgs');

}

function js_retornoatualizarCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if(oRetorno.iStatus != 1) {
    message_ajax(oRetorno.sMessage.urlDecode());
  } else {

    alert('Informações do CGS atualizadas com sucesso.');
    $('atualizar').disbled = true;

  }

}

function js_change() {

  if($F('z01_i_cgsund2') != '') {

    $('mudanca').value = true;
    if(lPermissaoCgs) {
      $('atualizar').disabled = false;
    }

  }

}

function js_pesquisatf13_i_cgsund(mostra) {

  if(mostra==true) {
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome','Pesquisa',true);
  } else {

    if(document.form1.tf13_i_cgsund.value != '') { 
      js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.tf13_i_cgsund.value+'&funcao_js=parent.js_mostracgs','Pesquisa',false);
    } else {

      document.form1.z01_v_nome2.value = ''; 
      js_limpaInfoCgs();

    }

  }

}
function js_mostracgs(chave,erro) {

  document.form1.z01_v_nome2.value = chave;
  if(erro==true) {

    document.form1.tf13_i_cgsund.focus(); 
    document.form1.tf13_i_cgsund.value = ''; 

  } else {
    js_getInfoCgs();
  }

}
function js_mostracgs1(chave1,chave2) {

  js_limpaInfoCgs();
  document.form1.tf13_i_cgsund.value = chave1;
  document.form1.z01_v_nome2.value = chave2;
  js_getInfoCgs();
  db_iframe_cgs_und.hide();

}



/* Bloco de funções dados do CGS fim ****/



/*
function js_pesquisatf13_i_motivoacompanhamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tfd_motivoacompanhamento','func_tfd_motivoacompanhamento.php?funcao_js=parent.js_mostratfd_motivoacompanhamento1|tf08_i_codigo|tf08_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.tf13_i_motivoacompanhamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tfd_motivoacompanhamento','func_tfd_motivoacompanhamento.php?pesquisa_chave='+document.form1.tf13_i_motivoacompanhamento.value+'&funcao_js=parent.js_mostratfd_motivoacompanhamento','Pesquisa',false);
     }else{
       document.form1.tf08_i_codigo.value = ''; 
     }
  }
}
function js_mostratfd_motivoacompanhamento(chave,erro){
  document.form1.tf08_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.tf13_i_motivoacompanhamento.focus(); 
    document.form1.tf13_i_motivoacompanhamento.value = ''; 
  }
}
function js_mostratfd_motivoacompanhamento1(chave1,chave2){
  document.form1.tf13_i_motivoacompanhamento.value = chave1;
  document.form1.tf08_i_codigo.value = chave2;
  db_iframe_tfd_motivoacompanhamento.hide();
}
function js_pesquisatf13_i_pedidotfd(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tfd_pedidotfd','func_tfd_pedidotfd.php?funcao_js=parent.js_mostratfd_pedidotfd1|tf01_i_codigo|tf01_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.tf13_i_pedidotfd.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tfd_pedidotfd','func_tfd_pedidotfd.php?pesquisa_chave='+document.form1.tf13_i_pedidotfd.value+'&funcao_js=parent.js_mostratfd_pedidotfd','Pesquisa',false);
     }else{
       document.form1.tf01_i_codigo.value = ''; 
     }
  }
}
function js_mostratfd_pedidotfd(chave,erro){
  document.form1.tf01_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.tf13_i_pedidotfd.focus(); 
    document.form1.tf13_i_pedidotfd.value = ''; 
  }
}
function js_mostratfd_pedidotfd1(chave1,chave2){
  document.form1.tf13_i_pedidotfd.value = chave1;
  document.form1.tf01_i_codigo.value = chave2;
  db_iframe_tfd_pedidotfd.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tfd_acompanhantes','func_tfd_acompanhantes.php?funcao_js=parent.js_preenchepesquisa|tf13_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_acompanhantes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}*/
</script>