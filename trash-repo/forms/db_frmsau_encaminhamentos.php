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

//MODULO: Ambulatorial
$oClsau_procencaminhamento->rotulo->label();
$oClsau_encaminhamentos->rotulo->label();
$oClrotulo = new rotulocampo;
$oClrotulo->label("s142_i_codigo");
$oClrotulo->label("sd63_c_nome");
$oClrotulo->label("z01_i_cgsund");
$oClrotulo->label("sd03_i_codigo");
$oClrotulo->label("sd24_i_codigo");
$oClrotulo->label("rh70_descr");
$oClrotulo->label("s110_i_codigo");
?>

<form name="form1" method="post" action="">
  <center>
    <fieldset style="width:97%"><legend><b>Encaminhamento</b></legend>
      <table border="0" width='100%' cellspacing='5'>
        <tr>
          <td nowrap title="<?=@$Ts142_i_codigo?>">
            <?=@$Ls142_i_codigo?>
          </td>
          <td colspan='3'> 
            <?
            db_input('s142_i_codigo',5,$Is142_i_codigo,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts142_i_profsolicitante?>">
            <?
            db_ancora(@$Ls142_i_profsolicitante,"js_pesquisas142_i_profsolicitante(true);",$db_opcao,'','ancora_profissionalsol');
            ?>
          </td>
          <td> 
            <?
            db_input('s142_i_profsolicitante',5,$Is142_i_profsolicitante,true,'text',$db_opcao," onchange='js_pesquisas142_i_profsolicitante(false);'")
            ?>
            <?
            db_input('nome_profsolicitante',40,$Isd03_i_codigo,true,'text',3,'')
            ?>
          </td>
          <td nowrap title="<?=@$Ts142_d_encaminhamento?>" align='right'>
            <?=@$Ls142_d_encaminhamento?>
          </td>
          <td nowrap> 
            <?
            if(!isset($s142_d_encaminhamento_dia)) {

              $s142_d_encaminhamento_dia = $aData_atual[0];
              $s142_d_encaminhamento_mes = $aData_atual[1];
              $s142_d_encaminhamento_ano = $aData_atual[2];

            }
            db_inputdata('s142_d_encaminhamento',@$s142_d_encaminhamento_dia,@$s142_d_encaminhamento_mes,@$s142_d_encaminhamento_ano,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>
          <td nowrap title="<?=@$Ts142_i_prontuario?>">
            <?
            db_ancora(@$Ls142_i_prontuario,"js_pesquisas142_i_prontuario(true);",$db_opcao,'','ancora_prontuario');
            ?>
          </td>
          <td> 
            <?
            db_input('s142_i_prontuario',5,$Is142_i_prontuario,true,'text',$db_opcao," onchange='js_pesquisas142_i_prontuario(false); js_verificaFaaVazio();;'")
            ?>
          </td> 
          <td nowrap title="<?=@$Ts142_d_validade?>" align='right'>
            <?=@$Ls142_d_validade?>
          </td>
          <td nowrap> 
            <?
            db_inputdata('s142_d_validade',@$s142_d_validade_dia,@$s142_d_validade_mes,@$s142_d_validade_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts142_i_cgsund?>">
            <?
            db_ancora('<b>CGS:<b>',"js_pesquisas142_i_cgsund(true);",$db_opcao,'','ancora_cgs');
            ?>
          </td>
          <td nowrap> 
            <?
            db_input('s142_i_cgsund',5,$Is142_i_cgsund,true,'text',$db_opcao," onchange='js_pesquisas142_i_cgsund(false);'")
            ?>
            <?
            db_input('z01_i_cgsund',40,$Iz01_i_cgsund,true,'text',3,'')
            ?>
          </td>
          <td nowrap title="<?=@$Ts142_d_retorno?>" align='right'>
            <?=@$Ls142_d_retorno?>
          </td>
          <td nowrap> 
            <?
            db_inputdata('s142_d_retorno',@$s142_d_retorno_dia,@$s142_d_retorno_mes,@$s142_d_retorno_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts142_i_prestadora?>">
            <?
            db_ancora(@$Ls142_i_prestadora,"js_pesquisas142_i_prestadora(true);",$db_opcao);
            ?>
          </td>
          <td> 
            <?
            db_input('s142_i_prestadora',5,$Is142_i_prestadora,true,'text',$db_opcao," onchange='js_pesquisas142_i_prestadora(false);'")
            ?>
            <?
            db_input('s110_i_codigo',40,$Is110_i_codigo,true,'text',3,'')
            ?>
          </td>
          <td nowrap title="<?=@$Ts142_i_tipo?>" align='right'>
            <?=@$Ls142_i_tipo?>
          </td>
          <td>
            <?
            $x = array("1"=>"Consulta","2"=>"Exame");
            db_select('s142_i_tipo',$x,true,$db_opcao,"");
            ?>
          </td>
        </tr>
        <tr>
          <td rowspan='2' colspan='2'>
            <fieldset style="width:97%"><legend><b>Profissional de Destino</b></legend>
              <table width='100%' border='0'>
                <tr>
                  <td nowrap title="<?=@$Ts142_i_profissional?>">
                    <?
                    db_ancora(@$Ls142_i_profissional,"js_pesquisas142_i_profissional(true);",$db_opcao,'','ancora_profissional');
                    ?>
                  </td>
                  <td> 
                    <?
                    db_input('s142_i_profissional',5,$Is142_i_profissional,true,'text',$db_opcao," onchange='js_pesquisas142_i_profissional(false);'")
                    ?>
                    <?
                    db_input('sd03_i_codigo',40,$Isd03_i_codigo,true,'text',3,'')
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ts142_i_unidade?>">
                    <?=@$Ls142_i_unidade?>
                  </td>
                  <td> 
                    <?
                    $x = array(""=>"");
                    db_select('s142_i_unidade',$x,true,$db_opcao," onchange=\"js_verificacoesTrocaMedico();\"");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ts142_i_rhcbo?>">
                    <?
                    db_ancora(@$Ls142_i_rhcbo,"js_pesquisas142_i_rhcbo(true);",$db_opcao);
                    ?>
                  </td>
                  <td> 
                    <?
                    db_input('rh70_estrutural',5,'',true,'text',$db_opcao," onchange=\"if(this.value.trim() == '')
                             { $('s142_i_rhcbo').value = $('rh70_descr').value = '';
                             $('sd63_c_procedimento').value = $('sd63_c_nome').value = $('s143_i_procedimento').value = '';
                             js_esvaziaProcedimentos(); js_renderizaGrid();}
                             js_pesquisas142_i_rhcbo(false);\"")
                    ?>
                    <?
                    db_input('s142_i_rhcbo',5,$Is142_i_rhcbo,true,'hidden',$db_opcao,"")
                    ?>    
                    <?
                    db_input('rh70_descr',40,$Irh70_descr,true,'text',3,'')
                    ?>
                    <?
                    db_input('s142_i_rhcboAux',5,$Is142_i_rhcbo,true,'hidden',$db_opcao,"")
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
          <td nowrap title="<?=@$Ts142_t_dadosclinicos?>" colspan='2' align='center' valign='bottom'>
            <?=@$Ls142_t_dadosclinicos?>
          </td>
        </tr>
        <tr>
          <td colspan='2' valign='top' align='center'> 
            <?
            db_textarea('s142_t_dadosclinicos',3,30,$Is142_t_dadosclinicos,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap colspan='4'>
            <fieldset style="width:97%"><legend><b>Lan&ccedil;a Exames / Consultas</b></legend>
              <table width='100%' border='0'>
                <tr>
                  <td nowrap title="<?=@$Ts143_i_procedimento?>">
                    <?
                    db_ancora(@$Ls143_i_procedimento," if(\$F('s142_i_rhcbo').trim() != '') js_pesquisas143_i_procedimento(true);
                    else alert('Selecione uma especialidade primeiro!');",$db_opcao);
                    ?>
                  </td>
                  <td nowrap> 
                    <?
                    db_input('sd63_c_procedimento',10,'',true,'text',$db_opcao," onchange='js_pesquisas143_i_procedimento(false);'")
                    ?>
                    <?
                    db_input('s143_i_procedimento',10,$Is143_i_procedimento,true,'hidden',$db_opcao,"")
                    ?>
                    <?
                    db_input('sd63_c_nome',40,$Isd63_c_nome,true,'text',3,'')
                    ?>
                    <?
                    if(!isset($lSucesso) || $lSucesso == 'true' || !isset($lProcedimentosAlterados)) {
                      $lProcedimentosAlterados = 'false';
                    }
                    db_input('lProcedimentosAlterados',10,'',true,'hidden',3,"");
                    ?>

                    &nbsp;&nbsp;&nbsp;
                    <input name="lancar_procedimento" type="button" id="lancar_procedimento" value="Incluir" onclick="js_lanca_procedimento();">
                  </td>
                </tr> 
                <tr>
                  <td colspan='2'>
                    <center>
                      <table border="0" width='100%' cellspacing='5'>
                        <tr>
                          <td>
                            <select multiple  name='select_procedimento[]' id='select_procedimento' style="width: 0px; height: 0px; display: none;">
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div id='grid_procedimentos' style='width: 100%;-moz-user-select:none'> 
                            </div>
                          </td>
                        </tr>
                      </table>
                    </center>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
  </center>
  <input onclick="return js_validaEnvio();" name="<?=($db_opcao==1?"confirmar":"alterar")?>" type="submit" id="enviar" value="<?=($db_opcao==1?"Confirmar":"Alterar")?>">
  <input onclick="js_cancelar(true);" name="cancela" type="button" id="cancela" value="Cancelar" <?=($db_opcao==1 ? 'style="display: none;"' : '')?>>
  <input onclick="js_novoEncaminhamento();" name="novo_encaminhamento" type="button" id="novo_encaminhamento" value="Novo Encaminhamento" <?=($db_opcao==1 ? 'style="display: none;"' : '')?>>
  <input onclick="js_imprimirEncaminhamento(<?=@$s142_i_tipo?>);" name="imprimir_encaminhamento" type="button" id="imprimir_encaminhamento" value="Imprimir" <?=($db_opcao==1 ? 'style="display: none;"' : '')?>>
  <input name="encaminhamentos" type="button" id="encaminhamentos" value="Encaminhamentos" onclick="js_pesquisa_encaminhamento(true);" <?=(isset($lAba) ? 'style="display: none;"' : '')?> >
  <div id='div_cancelamento' style="display: none;">
  <br><b>Obs / Motivo do Cancelamento:</b><br>
  <?
  db_textarea('s149_t_obs',4,60,'',true,'text',$db_opcao,"");
  ?>
  <br>
  <input name="cancelar" type="submit" id="cancelar" value="Confirma cancelamento">
  <input name="nao_cancela" type="button" id="nao_cancela" value="Anula cancelamento" onclick="js_cancelar(false);">
  </div>
  </center>
</form>

<script>
strURL = 'sau4_sau_encaminhamentos.RPC.php';
$('s142_i_rhcboAux').value = '';
oDBGridProcedimentos = js_cria_datagrid();
if(<?=$db_opcao?> != 1) {
  js_init();
}
<?
if(isset($lAba) && $lAba) {
  echo "js_aba();";
}
?>

function js_aba() {

  $('s142_i_prontuario').style.backgroundColor =  "rgb(222, 184, 135)";
  $('s142_i_prontuario').readOnly = true;
  $('ancora_prontuario').onclick = '';
  $('s142_i_cgsund').style.backgroundColor =  "rgb(222, 184, 135)";
  $('s142_i_cgsund').readOnly = true;
  $('ancora_cgs').onclick = '';
  
  js_pesquisas142_i_profsolicitante(false);
  if(document.form1.s142_i_prontuario.value != '') { 
    js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?pesquisa_chave='+document.form1.s142_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa FAA',false);
  }

  var olAba = document.createElement("INPUT");
  olAba.type = 'hidden';
  olAba.name = 'lAba';
  olAba.id = 'lAba';
  olAba.value = 'true';
  document.form1.appendChild(olAba);
  
}

function js_validaEnvio() {

  if($F('enviar') == 'Confirmar' || $F('enviar') == 'Alterar') {

    if($F('s142_i_profsolicitante').trim() == '') {

      alert('O campo Solicitante deve ser preenchido.');
      $('s142_i_profsolicitante').focus();
      return false;

    }
    if($F('s142_i_cgsund').trim() == '') {

      alert('O campo CGS deve ser preenchido.');
      $('s142_i_cgsund').focus();
      return false;

    }
    if($F('s142_i_rhcbo').trim() == '') {

      alert('O campo Especialidade deve ser preenchido.');
      $('rh70_estrutural').focus();
      return false;
    
    }
    if($F('s142_i_prestadora').trim() == '' && ($('s142_i_unidade') == undefined || $F('s142_i_unidade') == null || $F('s142_i_unidade').trim() == '')) {

      alert('O campo Prestadora deve ser preenchido.');
      $('s142_i_prestadora').focus();
      return false;
    
    }
    if($F('s142_d_encaminhamento').trim() == '') {

      alert('O campo Data do Encaminhamento deve ser preenchido.');
      $('s142_d_encaminhamento').focus();
      return false;
    
    }
    if($F('s142_d_validade').trim() == '') {

      alert('O campo Data de Validade deve ser preenchido.');
      $('s142_d_validade').focus();
      return false;
    
    }
    if($F('s142_d_retorno').trim() == '') {

      alert('O campo Data de Retorno deve ser preenchido.');
      $('s142_d_retorno').focus();
      return false;
    
    }
    if(!js_verificaDatas()) {
      return false;
    }
    if($('select_procedimento').length <= 0) {

      alert('Selecione pelo menos 1 procedimento.');
      $('sd63_c_procedimento').focus();
      return false;
    
    }
    if($F('s142_i_prontuario').trim() == '') {

      if(confirm('O encaminhamento ficara sem vinculo com FAA. Tem certeza de que nao ira informar uma FAA?')) {
        return true;
      } else {
        return false
      }
    }

    return true;

  }

}

function js_init() {
  
  if($F('s142_i_profissional').trim() != '') {
    js_getUnidades();
  }
  
  js_pesquisas142_i_profsolicitante(false);
  js_pesquisas142_i_rhcbo(false);
  js_pesquisas142_i_prestadora(false);
  if($F('s142_i_prontuario').trim() != '') {

    $('s142_i_cgsund').readOnly = true;
    $('s142_i_cgsund').style.backgroundColor =  "rgb(222, 184, 135)";
    $('ancora_cgs').onclick = '';

  }
  js_getProcedimentosEncaminhamento();

}

function js_selecionaUnidade() {
  
  sel = $('s142_i_unidade');
  for(i = 0; i < sel.length; i++) {
    //alert(sel.options[i].value);
    if(sel.options[i].value == <?=isset($s142_i_unidade) && !empty($s142_i_unidade) ? $s142_i_unidade : -1?>) {

      sel.options[i].selected = true;
      break;

    }

  }

}

function js_novoEncaminhamento() {

 if(confirm('Quer fazer um novo encaminhamento?')) {
  
    <?
    if(!isset($lAba)) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
    } else {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?lAba=true&s142_i_prontuario='+".
           "\$F('s142_i_prontuario')+'&s142_i_cgsund='+\$F('s142_i_cgsund')+'&s142_i_profsolicitante=".$s142_i_profsolicitante."'";
    }
    ?>

 }

}

function js_cancelar(lCancela) {
  
  $('cancela').disabled = lCancela;
  $('enviar').disabled = lCancela;
  $('novo_encaminhamento').disabled = lCancela;
  $('encaminhamentos').disabled = lCancela;
  if(lCancela) {

    $('div_cancelamento').style.display = 'block';
    $('s149_t_obs').focus();

  } else {
    $('div_cancelamento').style.display = 'none';
  }

}

function js_renderizaGrid() {

  var F = $('select_procedimento');
  oDBGridProcedimentos.clearAll(true);
  var aLinha = new Array();
  for(i = 0; i < F.length; i++) {

    aLinha[0]  = F.options[i].value;
    aLinha[1]  = F.options[i].innerHTML.substr(0,72);
    aLinha[2]  = "<span onclick=\"js_excluir_item_procedimento("+F.options[i].value+");\""+
    " style=\"color: blue; text-decoration: underline; cursor: pointer;\"><b>E</b></span>"; 
    oDBGridProcedimentos.addRow(aLinha);

  }
  oDBGridProcedimentos.renderRows();

}

function js_excluir_item_procedimento(iVal) {
 
  var F = $("select_procedimento");
  for(i = 0; i < F.length; i++) {
    
    if(F.options[i].value == iVal) {

      F.options[i] = null;
      $('lProcedimentosAlterados').value = 'true';
      break;

    }

  }
  js_renderizaGrid();

}

function js_imprimirEncaminhamento(iTipo) {

  if(iTipo == undefined) {
    iTipo = 1;
  }
  if($F('s142_i_codigo').trim() != '') {

    sEncaminhamento ='iEncaminhamento='+$F('s142_i_codigo');
    if(iTipo == 1) { // ficha de consulta
      oJan = window.open('sau2_sau_encaminhamentos002.php?'+sEncaminhamento,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    } else { // ficha de exame
      oJan = window.open('sau2_sau_encaminhamentos003.php?'+sEncaminhamento,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }
    oJan.moveTo(0,0);

  } else {
    alert('Nenhum encaminhamento selecionado!');
  }

}

function js_lanca_procedimento() {

  valor = $F('s143_i_procedimento');
  texto = $F('sd63_c_nome');
  if(valor != '' && texto.trim() != '') {

    var F = $('select_procedimento');
    var valor_default_novo_option = F.length;
    var testa = false;
    /*
    * testa se o elemento ja foi inserido no select
    */
    for(var x = 0; x < F.length; x++) {

      if(F.options[x].value == valor) {

        testa = true;
        break;

      }

    }

    if(testa == false) {
      /*
      * Cria o novo option no select hidden que armazena os procedimentos
      */
      $('lProcedimentosAlterados').value = 'true';
      var aLinha = new Array();
      F.options[valor_default_novo_option] = new Option(texto,valor);
      F.options[valor_default_novo_option].selected = true;
      js_renderizaGrid();

    }

  }
  texto = $('s143_i_procedimento').value = '';
  valor = $('sd63_c_nome').value = '';
  $('sd63_c_procedimento').value = '';

}

function js_cria_datagrid() {

        oDBGridProcedimentos = new DBGrid('grid_procedimentos');
        oDBGridProcedimentos.nameInstance = 'oDBGridProcedimentos';
        oDBGridProcedimentos.hasTotalizador = true;
        oDBGridProcedimentos.setCellWidth(new Array('10%','80%','10%'));
        oDBGridProcedimentos.setHeight(38);

        //oDBGridProcedimentos.setCheckbox(0);
        var aHeader = new Array();
        aHeader[0] = 'C&oacute;digo';
        aHeader[1] = 'Procedimentos';
        aHeader[2] = 'Excluir';
        oDBGridProcedimentos.setHeader(aHeader);
        //oDBGridProcedimentos.aHeader[11].lDisplayed = false;
        oDBGridProcedimentos.allowSelectColumns(true);
        var aAligns = new Array();
        aAligns[0] = 'center';
        aAligns[1] = 'center';
        aAligns[2] = 'center';
        
        oDBGridProcedimentos.setCellAlign(aAligns);
        oDBGridProcedimentos.allowSelectColumns(false);
        oDBGridProcedimentos.show($('grid_procedimentos'));
        oDBGridProcedimentos.clearAll(true);

        return oDBGridProcedimentos;

}

function js_getProcedimentosEncaminhamento() {
  //Pesquisa o CGS de acordo com a FAA selecionada
	var objParam             = new Object();
	objParam.exec            = "getProcedimentosEncaminhamento";
	objParam.iEncaminhamento   = $F('s142_i_codigo');
	
	js_ajax( objParam,'js_retornogetProcedimentosEncaminhamento');

}
function js_retornogetProcedimentosEncaminhamento(objRetorno) {

  cont = 0;
  objRetorno = eval("("+objRetorno.responseText+")");

  if(objRetorno.iStatus == 1) {

    objRetorno.oProcedimentos.each(
    function (oProcedimento) {

      $('select_procedimento').options[cont] = new Option(oProcedimento.sDescr.urlDecode(),oProcedimento.iCodigo);
      $('select_procedimento').options[cont].selected = true;
      cont++;

    });
    js_renderizaGrid();

  } else {
    alert('Nao foi possivel obter os procedimentos relacionados a este encaminhamento!');
  }

}

function js_getUnidades() {
  //Pesquisa o CGS de acordo com a FAA selecionada
	var objParam             = new Object();
	objParam.exec            = "getUnidadesMedico";
	objParam.iMedico   = $F('s142_i_profissional');
	
	js_ajax( objParam,'js_retornogetUnidades');

}
function js_verificacoesTrocaMedico() {
 
  cont = $('s142_i_unidade').options.length;
  if($F('s142_i_profissional').trim() == '') {
   for(i = 0; i < cont; i++) { // for para remover todos os options

    $('s142_i_unidade').options[0] = null

   }
  } else {

    //$('s142_i_cgsund').value = '';
    //$('z01_i_cgsund').value = '';
    //$('s142_i_prontuario').value = '';
    js_verificaFaaVazio();
    $('s142_i_rhcboAux').value = $F('s142_i_rhcbo');
    $('rh70_estrutural').value = '';
    $('s142_i_rhcbo').value = '';
    $('rh70_descr').value = '';
    js_pesquisas142_i_rhcbo(true);
    //js_esvaziaProcedimentos();
    //js_renderizaGrid();
    
  }

}

function js_esvaziaProcedimentos() {

  sel = $('select_procedimento');
  while(sel.length > 0) {
    sel.options[0] = null;
  }

}

function js_retornogetUnidades(objRetorno) {

  cont = $('s142_i_unidade').options.length;
  for(i = 0; i < cont; i++) { // for para remover todos os options
    $('s142_i_unidade').options[0] = null
  }
  cont = 0;
  objRetorno = eval("("+objRetorno.responseText+")");

  if(objRetorno.iStatus == 1) {

    objRetorno.oUnidades.each(
    function (oUnidade) {

      $('s142_i_unidade').options[cont] = new Option(oUnidade.sDescr.urlDecode().substr(0,43),oUnidade.iCodigo);
      cont++;

    });
    js_selecionaUnidade();

  } else {
    alert('Nao foi possivel encontrar unidades para o profissional indicado!');
  }
}



function js_verificaFaaVazio() {

  if($F('s142_i_prontuario').trim() == '') {

    $('s142_i_cgsund').readOnly = false;
    $('s142_i_cgsund').style.backgroundColor = '';
    $('ancora_cgs').onclick = function onclick(event) {
                                js_pesquisas142_i_cgsund(true);
                              }

  }

}

function js_retornogetCgsFaa(objRetorno) {

  objRetorno = eval("("+objRetorno.responseText+")");
  if(objRetorno.iStatus == 1) {

    $('s142_i_cgsund').value = objRetorno.iCgs;
    $('z01_i_cgsund').value =  objRetorno.sNome;

  } else {

    $('s142_i_cgsund').value = '';
    $('z01_i_cgsund').value =  'Nenhum CGS encontrado para esta FAA';

  }

}

function js_ajax( oParam, jsRetorno ){

	var objAjax = new Ajax.Request(
                         strURL, 
                         {
                          method    : 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax );';
                          				//js_removeObj('msgbox');
                                  return eval( evlJS );
                          			}
                         }
                        );

}

function js_getCgsFaa() {
  //Pesquisa o CGS de acordo com a FAA selecionada
  $('s142_i_cgsund').readOnly = true;
  $('s142_i_cgsund').style.backgroundColor =  "rgb(222, 184, 135)";
  $('ancora_cgs').onclick = '';
	var objParam             = new Object();
	objParam.exec            = "getCgsFaa";
	objParam.iFaa   = $F('s142_i_prontuario');
	
	js_ajax( objParam,'js_retornogetCgsFaa');

}

function js_pesquisas142_i_cgsund(mostra){
 
  chave_espec = '';
  if($F('s142_i_profissional') != undefined && $F('s142_i_profissional').trim() != ''
     && $F('s142_i_unidade') != undefined && $F('s142_i_unidade').trim() != '') {
    chave_espec = '&chave_profissional='+$F('s142_i_profissional')+'&chave_unidade='+$F('s142_i_unidade');
  }

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome'+chave_espec,'Pesquisa CGS',true);
  }else{
     if(document.form1.s142_i_cgsund.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+$F('s142_i_cgsund')+'&funcao_js=parent.js_mostracgs_und'+chave_espec,'Pesquisa CGS',false);
     }else{
       document.form1.z01_i_cgsund.value = ''; 
     }
  }
}
function js_mostracgs_und(chave,erro){
  document.form1.z01_i_cgsund.value = chave; 
  if(erro==true){ 
    document.form1.s142_i_cgsund.focus(); 
    document.form1.s142_i_cgsund.value = ''; 
  }
}
function js_mostracgs_und1(chave1,chave2){
  document.form1.s142_i_cgsund.value = chave1;
  document.form1.z01_i_cgsund.value = chave2;
  db_iframe_cgs_und.hide();
}
function js_pesquisas142_i_profissional(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?prof_ativo=1&funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa Profissional',true);
  }else{
     if(document.form1.s142_i_profissional.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?prof_ativo=1&pesquisa_chave='+document.form1.s142_i_profissional.value+'&funcao_js=parent.js_mostramedicos','Pesquisa Profissional',false);
     }else{

       document.form1.sd03_i_codigo.value = '';
       js_verificacoesTrocaMedico();

     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.sd03_i_codigo.value = chave;
  if(erro==true){ 
    document.form1.s142_i_profissional.focus(); 
    document.form1.s142_i_profissional.value = ''; 
  } else {
    js_getUnidades();
  }
  js_verificacoesTrocaMedico();
}
function js_mostramedicos1(chave1,chave2){
  document.form1.s142_i_profissional.value = chave1;
  document.form1.sd03_i_codigo.value = chave2;
  db_iframe_medicos.hide();
  js_getUnidades();
  js_verificacoesTrocaMedico();
}
function js_pesquisas142_i_prontuario(mostra){
  chave_espec = 'chave_profissional='+document.getElementById('s142_i_profissional').value+'&chave_unidade='+document.getElementById('s142_i_unidade').value;
  /*if(document.getElementById('s142_i_profissional').value.trim() != ''){
    chave_espec += '&chave_z01_v_nome=';
  }*/
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?'+chave_espec+'&funcao_js=parent.js_mostraprontuarios1|sd24_i_codigo','Pesquisa FAA',true);
  }else{
     if(document.form1.s142_i_prontuario.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?'+chave_espec+'&pesquisa_chave='+document.form1.s142_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa FAA',false);
     }
  }
}
function js_mostraprontuarios(chave,erro){
  if(erro==true){ 
    document.form1.s142_i_prontuario.focus(); 
    document.form1.s142_i_prontuario.value = '';
    js_verificaFaaVazio();
    alert('FAA nao encontrada.');
  } else {
    js_getCgsFaa();
  }
}
function js_mostraprontuarios1(chave1,chave2){
  document.form1.s142_i_prontuario.value = chave1;
  db_iframe_prontuarios.hide();
  js_getCgsFaa();
}
function js_pesquisas142_i_rhcbo(mostra){
//  if($('s142_i_rhcbo') )
  if($F('s142_i_profissional') != undefined && $F('s142_i_profissional').trim() != ''
     && $F('s142_i_unidade') != undefined && $F('s142_i_unidade').trim() != '') {

    chave_espec = 'chave_sd04_i_medico='+$F('s142_i_profissional')+'&chave_sd04_i_unidade='+$F('s142_i_unidade');
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_rhcbo','func_especmedico.php?'+chave_espec+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_rhcbo|rh70_descr|rh70_estrutural','Pesquisa Especialidade',true);
    } else {

      if($F('rh70_estrutural').trim() != '') {

        var objParam                 = new Object();
  			objParam.exec                = "getEspecialidadeMedico";
        objParam.iCodMedico          = $F('s142_i_profissional');
        objParam.iCodUnidade          = $F('s142_i_unidade');
	  		objParam.sEspecialidade      = $F('rh70_estrutural');
			  js_ajax( objParam, 'js_mostrarhcbo' );

      }

    }

  } else {

    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_rhcbo','func_cboups.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_descr|rh70_estrutural','Pesquisa Especialidade',true);
    } else{

      if($F('rh70_estrutural').trim() != '') {

        var objParam                 = new Object();
  			objParam.exec                = "getEspecialidade";
    		objParam.sEspecialidade      = $F('rh70_estrutural');
		    js_ajax( objParam, 'js_mostrarhcbo' );

       } else {
         $('rh70_descr').value = '';
         $('s142_i_rhcbo').value = '';
       }
    }

  }

}
function js_mostrarhcbo(objRetorno){
  
  objRetorno = eval("("+objRetorno.responseText+")");
  $('rh70_descr').value = objRetorno.sDescrEspecialidade.urlDecode();
  $('s142_i_rhcbo').value = objRetorno.iCodEspecialidade; 
  $('rh70_estrutural').value = objRetorno.sEspecialidade; 
  if(objRetorno.iStatus == 2) {
    ('rh70_estrutural').focus(); 
  }
  
  if($F('s142_i_rhcboAux') != $F('s142_i_rhcbo') || $F('s142_i_rhcbo').trim() == '') {

    js_esvaziaProcedimentos();
    js_renderizaGrid();
    $('sd63_c_procedimento').value = $('sd63_c_nome').value = $('s143_i_procedimento').value = '';

  }
  $('s142_i_rhcboAux').value = $F('s142_i_rhcbo');
 
}
function js_mostrarhcbo1(chave1,chave2,chave3){

  document.form1.s142_i_rhcbo.value = chave1;
  document.form1.rh70_descr.value = chave2;
  document.form1.rh70_estrutural.value = chave3;
  db_iframe_rhcbo.hide();
  if($F('s142_i_rhcboAux') != $F('s142_i_rhcbo') || $F('s142_i_rhcbo').trim() == '') {

    js_esvaziaProcedimentos();
    js_renderizaGrid();
    $('sd63_c_procedimento').value = $('sd63_c_nome').value = $('s143_i_procedimento').value = '';

  }
  $('s142_i_rhcboAux').value = $F('s142_i_rhcbo');

}
function js_pesquisas142_i_prestadora(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_prestadores','func_sau_prestadores.php?funcao_js=parent.js_mostrasau_prestadores1|s110_i_codigo|z01_nome','Pesquisa Prestadora',true);
  }else{
     if(document.form1.s142_i_prestadora.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_prestadores','func_sau_prestadores.php?pesquisa_chave='+document.form1.s142_i_prestadora.value+'&funcao_js=parent.js_mostrasau_prestadores','Pesquisa Prestadora',false);
     }else{
       document.form1.s110_i_codigo.value = ''; 
     }
  }

}
function js_mostrasau_prestadores(chave,erro){
  document.form1.s110_i_codigo.value = chave; 
  if(erro===true){ 
    document.form1.s142_i_prestadora.focus(); 
    document.form1.s142_i_prestadora.value = ''; 
  }
}
function js_mostrasau_prestadores1(chave1,chave2){
  document.form1.s142_i_prestadora.value = chave1;
  document.form1.s110_i_codigo.value = chave2;
  db_iframe_sau_prestadores.hide();
}

function js_pesquisa_encaminhamento(mostra){

  chave_espec = '';
  if($F('s142_i_profissional') != undefined && $F('s142_i_profissional').trim() != ''
     && $F('s142_i_unidade') != undefined && $F('s142_i_unidade').trim() != '') {
    chave_espec = '&chave_profissional='+$F('s142_i_profissional')+'&chave_unidade='+$F('s142_i_unidade');
  }

  js_OpenJanelaIframe('','db_iframe_sau_encaminhamentos',
                      'func_sau_encaminhamentos.php?funcao_js=parent.js_preencheencaminhamento|s142_i_codigo|'+
                      's142_i_profissional|z01_nome|s142_i_unidade|s142_i_cgsund|z01_v_nome|s142_i_prontuario|'+
                      'rh70_estrutural|s142_i_prestadora|s142_i_tipo|s142_t_dadosclinicos|s142_d_encaminhamento|'+
                      's142_d_validade|s142_d_retorno|s142_i_profsolicitante'+chave_espec,
                      'Pesquisa Encaminhamento',mostra);

}

function js_preencheencaminhamento(cod, codmed, descrmed, unid, cgs, nome, faa, espec, prest, tipo, dados, data, val, ret, solic){
  if(tipo == 'Consulta') {
    tipo = 1;
  } else {
    tipo = 2;
  }
  data = data.split('-');
  val = val.split('-');
  ret = ret.split('-');
  posts = 's142_i_codigo='+cod+'&s142_i_profissional='+codmed+'&sd03_i_codigo='+descrmed+'&s142_i_unidade='+unid+
          '&s142_i_cgsund='+cgs+'&z01_i_cgsund='+nome+'&s142_i_prontuario='+faa+'&rh70_estrutural='+espec+
          '&s142_i_prestadora='+prest+'&s142_i_tipo='+tipo+'&s142_t_dadosclinicos='+dados+
          '&s142_d_encaminhamento_dia='+data[2]+'&s142_d_encaminhamento_mes='+data[1]+'&s142_d_encaminhamento_ano='+data[0]+
          '&s142_d_validade_dia='+val[2]+'&s142_d_validade_mes='+val[1]+'&s142_d_validade_ano='+val[0]+
          '&s142_d_retorno_dia='+ret[2]+'&s142_d_retorno_mes='+ret[1]+'&s142_d_retorno_ano='+ret[0]+'&s142_i_profsolicitante='+
          solic+'&db_opcao=2';
  db_iframe_sau_encaminhamentos.hide();
  //alert(posts);
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?'+posts";
  ?>
}

function js_pesquisas143_i_procedimento(mostra) {
  
  if($F('s142_i_rhcbo') == '') {
    
    alert('Selecione uma especialidade primeiro!');
    $('sd63_c_nome').value = '';
    $('s143_i_procedimento').value = '';
    $('sd63_c_procedimento').value = '';

    return false;

  }
  chave_espec = 'chave_rh70_sequencial='+$F('s142_i_rhcbo');
  if($('s142_i_unidade') == undefined || $F('s142_i_unidade') == null || $F('s142_i_unidade').trim() == '') {
    chave_espec += '&intUnidade=-1&lNaoFiltrar=true';
  } else {
    chave_espec += '&intUnidade='+$F('s142_i_unidade');
  }
  if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?'+chave_espec+'&funcao_js=parent.js_mostrasau_proccbo1|sd96_i_procedimento|sd63_c_nome|sd63_c_procedimento','Pesquisa Procedimento',true);
  }else{

   if( $F('sd63_c_procedimento') != '') {

			var objParam                 = new Object();
			objParam.exec                = "getProcedimento";
			objParam.iEspecialidade      = $F('s142_i_rhcbo');
			objParam.sProcedimento       = $F('sd63_c_procedimento');

      if($('s142_i_unidade') != undefined && $F('s142_i_unidade') != null && $F('s142_i_unidade').trim() != '') {
        objParam.iUnidade       = $F('s142_i_unidade');
      }

			js_ajax( objParam, 'js_mostrasau_proccbo' );

		} else {     
			$('sd63_c_nome').value = '';
      $('s143_i_procedimento').value = '';
		}

	}
 
}
function js_mostrasau_proccbo(objRetorno) {

  objRetorno = eval("("+objRetorno.responseText+")");
  $('sd63_c_nome').value = objRetorno.sDescrProcedimento.urlDecode();
  $('s143_i_procedimento').value = objRetorno.iCodProcedimento; 
  $('sd63_c_procedimento').value = objRetorno.sProcedimento; 
  if(objRetorno.iStatus == 2){

    document.form1.s143_i_procedimento.focus(); 
    document.form1.s143_i_procedimento.value = '';

  }

}
function js_mostrasau_proccbo1(chave1,chave2,chave3){
  $('s143_i_procedimento').value = chave1;
  $('sd63_c_nome').value = chave2;
  $('sd63_c_procedimento').value = chave3;
  
  db_iframe_sau_proccbo.hide();
}

String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,"");
}

   
function js_verificaDatas() {
  
  dData1 = $F('s142_d_encaminhamento').split('/');
  dData2 = $F('s142_d_validade').split('/');
  dData3 = $F('s142_d_retorno').split('/');
  dData1 = new Date (dData1[2], dData1[1] - 1, dData1[0]);
  dData2 = new Date (dData2[2], dData2[1] - 1, dData2[0]);
  dData3 = new Date (dData3[2], dData3[1] - 1, dData3[0]);
  if((dData2 - dData1) < 0) {
 
    alert('A data de validade nao pode ser menor que a data de encaminhamento.');
    $('s142_d_validade').value = '';
    $('s142_d_validade').focus();

    return false;

  }
  if((dData3 - dData1) < 0) {
 
    alert('A data de retorno nao pode ser menor que a data de encaminhamento.');
    $('s142_d_retorno').value = '';
    $('s142_d_retorno').focus();

    return false;

  }
  return true;

}

function js_pesquisas142_i_profsolicitante(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_solicitante','func_medicos.php?prof_ativo=1&funcao_js=parent.js_mostrasolicitante1|sd03_i_codigo|z01_nome','Pesquisa Profissional Solicitante',true);
  }else{
     if(document.form1.s142_i_profsolicitante.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_solicitante','func_medicos.php?prof_ativo=1&pesquisa_chave='+document.form1.s142_i_profsolicitante.value+'&funcao_js=parent.js_mostrasolicitante','Pesquisa Profissional Solicitante',false);
     }else{
       document.form1.nome_profsolicitante.value = '';
     }
  }
}
function js_mostrasolicitante(chave,erro){
  document.form1.nome_profsolicitante.value = chave;
  if(erro==true){ 
    document.form1.s142_i_profsolicitante.focus(); 
    document.form1.s142_i_profsolicitante.value = '';
  }
}
function js_mostrasolicitante1(chave1,chave2){
  document.form1.s142_i_profsolicitante.value = chave1;
  document.form1.nome_profsolicitante.value = chave2;
  db_iframe_solicitante.hide();
}


</script>