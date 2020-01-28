<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: saude
$clagendamentos->rotulo->label();
$clrotulo = new rotulocampo;

//Médico
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("z01_nome");

//Unidades
$clrotulo->label("sd02_i_codigo");
$clrotulo->label("sd02_c_centralagenda");
$clrotulo->label("descrdepto");

//Unidade / Medicos
$clrotulo->label("sd04_i_cbo");
//undmedhorario
$clundmedhorario->rotulo->label();
//especmedico
$clrotulo->label("sd27_i_codigo");

//Procedimento
$clrotulo->label("s125_i_procedimento");
$clrotulo->label ( "sd63_c_procedimento" );
$clrotulo->label ( "sd63_c_nome" );
//CBO
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
$clrotulo->label("s165_formatocomprovanteagend");

?>

<form name="form1" method="post">
  <table>
  <tr>
    <td>
      <fieldset><legend>Agendamento</legend>
      <table>
        <tr>
          <td valign="top">
          <fieldset>
          <legend>Profissional</legend>
            <table>
            <!-- UPS -->
            <tr>
              <td nowrap title="<?=@$Tsd02_i_codigo?>" >
                <? db_ancora (@$Lsd02_i_codigo, "js_pesquisasd02_i_codigo(true);", $db_opcao_cotas); ?>
            </td>
            <td>
              <? 
                db_input('sd02_i_codigo',10,$Isd02_i_codigo, true,'text',$db_opcao_cotas,
                         "onchange = 'js_pesquisasd02_i_codigo(false);'"
                        );
              ?>
            </td>
            <td colspan="2">
              <? 
                db_input('descrdepto',49,$Idescrdepto,true,'text',3,''); 
              ?>
              </td>
              </tr>
            <!-- CBO -->
            <tr>
              <td nowrap title="<?=@$Tsd04_i_cbo?>">
              <? db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true,2);",$db_opcao); ?>
            </td>
            <td>
              <? 
              db_input('sd02_c_centralagenda',1,$Isd02_c_centralagenda,true,'hidden',$db_opcao,"");
              db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'hidden',$db_opcao,"");
              db_input('upssolicitante',10,@$upssolicitante,true,'hidden',$db_opcao,"");
              db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
              db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao,
                       " onchange='js_pesquisasd04_i_cbo(false,2);' onFocus=\"nextfield='sd03_i_codigo'\""
                      );
              ?>
            </td>
            <td colspan="2">
              <? db_input('rh70_descr',49,$Irh70_descr,true,'text',3,''); ?>
              </td>
            </tr>
            <!-- PROFISSIONAL -->
            <tr>
              <? $db_opcaoprof = $sd02_c_centralagenda=="S"?3:$db_opcao ?>
            <td nowrap title="<?=@$Tsd03_i_codigo?>" >
              <? db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo2(true);",$db_opcaoprof); ?>
            </td>
            <td valing="top" align="top">
              <? 
                db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$db_opcaoprof,
                         " onchange='js_pesquisasd03_i_codigo2(false);' ". 
                         "onFocus=\"nextfield='sd23_d_consulta'\""
                        ); 
              ?>
            </td>
            <td colspan="2">
              <? db_input('z01_nome',49,$Iz01_nome,true,'text',3,''); ?>
              </td>
            </tr>
            <? if( $booProced ){ ?>
            <!-- PROCEDIMENTO -->
            <tr>
            <td nowrap title="<?=@$Tsd29_i_procedimento?>">
              <?
              db_ancora ( @$Ls125_i_procedimento, "js_pesquisas125_i_procedimento(true);", $db_opcao );
              ?>
              </td>
            <td nowrap>
              <?
              db_input ( 's125_i_procedimento', 10, $Is125_i_procedimento, true, 'hidden', $db_opcao, "");
              db_input ( 'sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text', $db_opcao, 
                         " onchange='js_pesquisas125_i_procedimento(false);' " 
                       );
              ?>
            <td>
              <?
              db_input ( 'sd63_c_nome', 49, $Isd63_c_nome, true, 'text', 3, '' );
              ?>       
            </td>
            </tr>
            <?} ?>
            <!-- Data Consulta -->
            <tr>
              <td nowrap title="<?=@$Tsd23_d_consulta?>"><?=@$Lsd23_d_consulta?></td>
            <td>
              <? db_inputdatasaude('document.form1.sd27_i_codigo.value','sd23_d_consulta',
                                   @$sd23_d_consulta_dia,@$sd23_d_consulta_mes,@$sd23_d_consulta_ano,
                                   true,
                                   'text',
                                   $db_opcao,
                                   " onchange=\"js_diasem()\" onFocus=\"nextfield='done'\" readonly ", 
                                   "", 
                                   "", 
                                   "parent.js_diasem(); "
                                  ); 
              ?>
            </td>
            <td>
              <? db_input('diasemana',49,@$diasemana,true,'text',3,''); ?>
              </td>
            </tr>
          </table>
          </fieldset>
          <fieldset>
            <legend>Agendamento na Grade de Horário do Dia</legend>
            <table style="width: 100%;">
              <tr>
              <td colspan="3">
              <iframe id="frameagendados" name="frameagendados"  src=""   
                      width="100%" height="250" scrolling="yes" frameborder="0">
              </iframe>
            </td>
            </tr>
          </table>
          </fieldset>
        </td>
        <td valign="top" height="100%">
          <fieldset>
            <legend>Calendário</legend>
          <iframe id="framecalendario" name="framecalendario"  
                  src="func_calendariosaude.php?nome_objeto_data=sd23_d_consulta"
                  width="100%" height="315" scrolling="no" frameborder="0">
            </iframe>
          </fieldset>            
          </td>
        </tr>
        <tr>
          <td colspan="2">
          <table width="100%">
            <tr>
            <td width="80%" nowrap title="<?=@$Tsd30_c_tipograde?>">
              <?
                echo $Lsd30_c_tipograde;
                $x = array('I'=>'Intervalo','P'=>'Período');
                db_input('sd30_c_tipograde', 10, $Isd30_c_tipograde, true, 'text', 3);
                echo "&nbsp;&nbsp;&nbsp;".$Ls165_formatocomprovanteagend;
                $aOpcoes = array("1"=>"PDF","2"=>"TXT");
                db_select('s165_formatocomprovanteagend',$aOpcoes,true,$db_opcao,"");
              ?>
            </td>
            <td>
              <fieldset>
                <legend>Total de Fichas no Dia</legend>
                <table>
                <tr>
                  <td nowrap title="<?=@$Tsd30_i_fichas?>" >
                  <?=@$Lsd30_i_fichas?>
                </td>
                <td valing="top" align="top">
                  <? db_input('sd30_i_fichas',10,$Isd30_i_fichas,true,'text',3) ?>
                </td>
                <td nowrap title="<?=@$Tsd30_i_reservas?>" >
                  <?=@$Lsd30_i_reservas?>
                </td>
                <td valing="top" align="top">
                  <? db_input('sd30_i_reservas',10,$Isd30_i_reservas,true,'text',3) ?>
                </td>
                <td nowrap title="Saldo disponível"><b>Saldo:</b></td>
                <td>
                  <? db_input('saldo',10,@$saldo,true,'text',3) ?>
                </td>
                </tr>
              </table>
              </fieldset>
              </td>
            </tr>
            </table>
          </td>
          </tr>
        </table>
        </fieldset>
       </td>
    </tr>
   </table>
</form>
<script>

function js_comprovante (sd23_i_codigo) {

  if (document.form1.s165_formatocomprovanteagend.value == 1) {

    x = 'sau2_agendamento004.php';
    x += '?sd23_i_codigo='+sd23_i_codigo;
    x += '&diasemana='+document.form1.diasemana.value;

    jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  } else {

    // Arquivo que gerava o TXT ->  sau2_agendamento005.php
    var oParam           = new Object();
    oParam.exec          = 'gerarComprovanteTXT';
    oParam.sd23_i_codigo = sd23_i_codigo;
    oParam.diasemana     = document.form1.diasemana.value;

    js_webajax(oParam, 'js_retornoComprovante', 'sau4_ambulatorial.RPC.php');

  }

}
function js_retornoComprovante(oAjax) {
  oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    iTop    = 20;
    iLeft   = 5;
    iHeight = screen.availHeight-210;
    iWidth  = screen.availWidth-35;
    sChave = 'sSessionNome='+oRetorno.sSessionNome;

    js_OpenJanelaIframe ('top.corpo', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave, 
                         'Visualisador', true, iTop, iLeft, iWidth, iHeight
                        );

  }

}

function js_agendados(){
  
   obj = document.form1;
    obj.saldo.value='';
    obj.sd30_i_fichas.value='';
    obj.sd30_i_reservas.value='';
    obj.sd30_c_tipograde.value='';
   sd23_d_consulta = document.getElementById('sd23_d_consulta').value;
    a =  sd23_d_consulta.substr(6,4);
  m = (sd23_d_consulta.substr(3,2))-1;
  d =  sd23_d_consulta.substr(0,2);
  data = new Date(a,m,d);
  dia= data.getDay()+1;
   
  if( sd23_d_consulta != "" && obj.sd02_c_centralagenda.value == "N" ){
     x  = 'sau4_agendamento002.php';
      x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
      x += '&chave_diasemana='+dia;
        x += '&sd23_d_consulta='+sd23_d_consulta;
      x += '&sd02_i_codigo='+$('sd02_i_codigo').value;
      x += '&rh70_estrutural='+$('rh70_estrutural').value;
      
    }else if( obj.sd02_c_centralagenda.value == "S" ){
     x  = 'sau4_agendamento004.php';
      x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
    x += '&sd27_i_rhcbo='+obj.rh70_sequencial.value;
      x += '&chave_diasemana='+dia;
        x += '&sd23_d_consulta='+sd23_d_consulta;    
    }
    
    //Verifica Procedimento
    if( $('sd63_c_procedimento') != undefined && $F('sd63_c_procedimento') == '' ){
      alert('Procedimento Obrigatório.');
      $('sd63_c_procedimento').focus();
    }else{
      iframe = document.getElementById('frameagendados');
      iframe.src = x;
    }
}

function js_diasem(){

  obj = document.form1;
  
  a =  obj.sd23_d_consulta_ano.value;
  m = (obj.sd23_d_consulta_mes.value)-1;
  d =  obj.sd23_d_consulta_dia.value;
  data = new Date(a,m,d);
  dia= data.getDay();
  semana=new Array(6);
  semana[0]='Domingo';
  semana[1]='Segunda-Feira';
  semana[2]='Terça-Feira';
  semana[3]='Quarta-Feira';
  semana[4]='Quinta-Feira';
  semana[5]='Sexta-Feira';
  semana[6]='Sábado';
  document.form1.diasemana.value = semana[dia];
  
  
  js_agendados();
  
}

function js_calend(){

  obj = document.form1;
  a =  obj.sd23_d_consulta_ano.value;
  m = (obj.sd23_d_consulta_mes.value)-1;
  d =  obj.sd23_d_consulta_dia.value;
  data = new Date(a,m,d);
  dia= data.getDay()+1;
  sd23_d_consulta = document.getElementById('sd23_d_consulta').value;
  if( $('s125_i_procedimento') != undefined && $F('s125_i_procedimento') == ''  ){
    //alert('Pereenchimento obirgatório do procedimento.');
    $('sd63_c_procedimento').focus();
  }else{
    
    x  = 'func_calendariosaude2.php';
    x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
    x += '&upssolicitante='+obj.upssolicitante.value;
    x += '&upsprestadora='+obj.sd02_i_codigo.value;
    x += '&mescomp='+sd23_d_consulta.substr(3,2);
    x += '&anocomp='+sd23_d_consulta.substr(6,4);
    x += '&sd27_i_rhcbo='+obj.rh70_sequencial.value;
    x += '&sd27_i_rhcbo_estrutural='+obj.rh70_estrutural.value;
    x += '&sd02_c_centralagenda='+obj.sd02_c_centralagenda.value;
    x += '&nome_objeto_data=sd23_d_consulta';
    x += '&shutdown_function=parent.js_agendados()';
    iframe = document.getElementById('framecalendario');
    iframe.src = x;
  }  
  
}


function js_pesquisasd04_i_cbo(mostra,chama){
  if( $('s125_i_procedimento') != undefined ){
    
      document.form1.sd63_c_procedimento.value = '';
      document.form1.sd63_c_nome.value = '';
      document.form1.s125_i_procedimento.value = '';

    }
    if ($('sd02_i_codigo').value == '') {
        
      alert("Informe uma unidade prestadora antes de selecionar a especialidade.");
      return;
       
    }
    if (<? echo $db_opcao_cotas;?> == 1) {
        
        var sCamposcotas  = '&lApenasCotas=1&iUpssolicitante=<?=$upssolicitante?>'
        sCamposcotas     += '&iUpsprestadora='+$('sd02_i_codigo').value;
        
    } else {
        var sCamposcotas = '';
    }
    if(mostra==true){
      if( document.form1.sd02_c_centralagenda.value == "S" ){
         js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|'
                                 +'rh70_estrutural|rh70_descr','Pesquisa',true
                            );
      }else{
         js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|'
                                 +'rh70_estrutural|rh70_descr&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value
                                 +sCamposcotas,'Pesquisa',true
                            );
      }
    }else{
       if(document.form1.rh70_estrutural.value != ''){ 
          if( document.form1.sd02_c_centralagenda.value == "S" ){
           js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?chave_rh70_estrutural='
                                   +document.form1.rh70_estrutural.value
                                   +'&funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_estrutural|rh70_descr'
                                   ,'Pesquisa',false
                              );
          }else{
           js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?chave_rh70_estrutural='
                                   +document.form1.rh70_estrutural.value
                                   +'&funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_estrutural|' 
                                   +'rh70_descr&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+sCamposcotas,
                                   'Pesquisa',false
                              );
          }
          document.form1.rh70_estrutural.value = '';
          document.form1.rh70_descr.value = '';
       }else{
         document.form1.rh70_estrutural.value = '';
       }
    }
}

function js_mostrarhcbo1(chave1,chave2,chave3){
  db_iframe_cboups.hide();
  document.form1.rh70_sequencial.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  

  
   js_OpenJanelaIframe('','db_iframe_cboups','func_cboups2.php?chave_sd04_i_medico=0&funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome|sd27_i_codigo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_rh70_estrutural='+document.form1.rh70_estrutural.value,'Pesquisa',true);

  document.form1.sd03_i_codigo.value = '';
  document.form1.z01_nome.value = '';
  

  if( document.form1.sd02_c_centralagenda.value == "S" ){
    js_calend();
  }
  
  iframe = document.getElementById('frameagendados');
  iframe.src = '';
  document.getElementById('framecalendario').src = '';

}

function js_mostramedicos1(chave1,chave2,chave3){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.sd27_i_codigo.value = chave3;

  document.getElementById('sd23_d_consulta').value = '';
  document.form1.diasemana.value = '';

  db_iframe_cboups.hide();
  
  iframe = document.getElementById('frameagendados');
  iframe.src = '';
  
  document.getElementById('framecalendario').src = '';
  js_calend();
  
  js_getProcedimentoPadraoProfissional();
 

}

function js_mostrarhcbo11(chave1, chave2, chave3, chave4) {

  document.form1.sd27_i_codigo.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;

  db_iframe_especmedico.hide();

  if(chave2 == ''){

    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 

  } else {
    js_getProcedimentoPadraoProfissional();
  }
  js_calend();

}

/*
 * BUSCAR UPS 
 */
function js_pesquisasd02_i_codigo(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe_unidades',
                        'func_unidades.php?iCotas=1&funcao_js=parent.js_mostraunidade|sd02_i_codigo|descrdepto', 'Pesquisa',
                        true
                       );
  } else {

    if(document.form1.sd02_i_codigo.value != ''){
      js_OpenJanelaIframe('', 'db_iframe_unidades',
                          'func_unidades.php?iCaotas=1&pesquisa_chave='+document.form1.sd02_i_codigo.value+
                          '&funcao_js=parent.js_mostraunidade_2', 'Pesquisa', false
                         );
    }else{
      document.form1.descrdepto.value = '';
    }
  }

}

/*
 * MOSTRAR UPS
 */ 
function js_mostraunidade(chave1, chave2) {
  
  $('sd02_i_codigo').value = chave1;
  $('descrdepto').value    = chave2;
  db_iframe_unidades.hide();
  js_limpar();
  $('rh70_estrutural').focus();
  
}

/*
 * MOSTRAR UPS
 */ 
function js_mostraunidade_2(chave1, status) {

  if (status == false) {
    $('descrdepto').value = chave1;
  } else {
  $('descrdepto').value = '';
  }
  js_limpar();
  $('rh70_estrutural').focus();

}

function js_limpar() {

  $('rh70_estrutural').value = '';
  $('rh70_sequencial').value = '';
  $('rh70_descr').value = ''; 
  $('sd03_i_codigo').value = '';
  $('z01_nome').value = '';
  $('sd23_d_consulta').value = '';
  $('diasemana').value = '';  
  $('frameagendados').src = '';
  $('framecalendario').src = '';

}

function js_pesquisasd03_i_codigo2(mostra,depara){

  if ($('sd02_i_codigo').value == '') {
          
    alert("Informe uma unidade prestadora antes de selecionar a especialidade.");
      return;
           
  }
  if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_cboups','func_medicos.php?prof_ativo=1&funcao_js=parent.js_mostramedicos_21|z01_nome|sd03_i_codigo|sd27_i_codigo&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',true);
  }else{
    if(document.form1.sd03_i_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_cboups','func_medicos.php?prof_ativo=1&pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos_21&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostramedicos_21(chave1,chave2,chave3){
  document.form1.z01_nome.value = chave1;
  if(! (chave2 === true) ){
    if( chave2 != false ){
      document.form1.sd03_i_codigo.value = chave2;
    }
    document.form1.sd27_i_codigo.value = chave3;
    
    db_iframe_cboups.hide();
    
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?iFiltroHorario=1&funcao_js=parent.js_mostrarhcbo11|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);

    document.getElementById('sd23_d_consulta').value = '';
    document.form1.diasemana.value = '';
    if( $('s125_i_procedimento') != undefined ){
    document.form1.sd63_c_procedimento.value = '';
    document.form1.sd63_c_nome.value = '';
    document.form1.s125_i_procedimento.value = '';
    }
    document.form1.rh70_estrutural.value = '';
    document.form1.rh70_descr.value = '';
      document.form1.rh70_sequencial.value = '';     
  
    
    iframe = document.getElementById('frameagendados');
    iframe.src = '';
    document.getElementById('framecalendario').src = '';
  }
}
/**
 * set url do arquivo RPC
 */

/**
 * Ajax
 */
function js_ajax( objParam, strCarregando, jsRetorno, strURL ){ 

  if (strURL == undefined) {
    strURL = 'sau1_sau_individualprocedRPC.php';
  }

  var objAjax = new Ajax.Request(
                         strURL, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(objParam),
                          onCreate  : function(){
                                  js_divCarregando( strCarregando, 'msgbox');
                                },
                          onComplete: function(objAjax){
                                  var evlJS = jsRetorno+'( objAjax )';
                                  js_removeObj('msgbox');
                                  eval( evlJS );
                                }
                         }
                        );
}
/**
 * Pesquisa Procedimento
 */
function js_pesquisas125_i_procedimento(mostra){

  if ($F('sd27_i_codigo') == '') {

    alert('Selecione um profissional e uma especialidade primeiro.');
    return false;

  }

  if ($F('sd02_i_codigo') == '') {

    alert('Selecione uma unidade primeiro.');
    return false;

  }

  var strParam = '';
  strParam += 'func_sau_proccbo.php';
  strParam += '?chave_rh70_sequencial='+$F('rh70_sequencial');
  strParam += '&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
  strParam += '&campoFoco=sd63_c_procedimento&lFiltrarPadroes=true&lBotaoMostrarTodos=true';
  strParam += '&lControleOutrasRotinas=true';
  strParam += '&iEspecMed='+$F('sd27_i_codigo');
     
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',true);
  }else{
    if($F('sd63_c_procedimento') != ''){

      strParam += '&chave_sd63_c_procedimento='+$F('sd63_c_procedimento')+'&chave_nao_mostra=true';
      strParam += '&lAutomatico=true';
      js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',false);

    }else{     
      $('sd63_c_nome').value = ''; 
    }
  }
  $('sd63_c_procedimento').focus(); 
}
function js_mostraprocedimentos1(chave1,chave2,chave3){
  if(chave1==''){
    alert('CBO não tem ligação com procedimento');
  }
  $('s125_i_procedimento').value = chave1;
  $('sd63_c_procedimento').value = chave2;
  $('sd63_c_nome').value         = chave3;
  db_iframe_sau_proccbo.hide();
  js_calend();
}

function js_getProcedimentoPadraoProfissional() {

  <?
  if(!$booProced) { 
   echo 'return false;';
  }
  ?>

  if ($F('rh70_sequencial') == '' || $F('rh70_estrutural') == '' || $F('sd03_i_codigo') == '' 
      || $F('sd27_i_codigo') == '') {
    return false;
  }

  var oParam       = new Object();
  oParam.exec      = 'getProcedimentosPadraoProfissional';
  oParam.iEspecMed = $F('sd27_i_codigo');

  js_ajax(oParam, 'Procurando procedimento padrão', 'js_retornoGetProcedimentosPadraoProfissional', 'sau4_agendamento.RPC.php');

}
function js_retornoGetProcedimentosPadraoProfissional(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) { // Possui procedimentos padrão

    iTam = oRetorno.aProcedimentos.length;
    if (iTam == 1) { // Se tiver apenas um procedimento padrão vinculado

      $('s125_i_procedimento').value = oRetorno.aProcedimentos[0].sd63_i_codigo;
      $('sd63_c_procedimento').value = oRetorno.aProcedimentos[0].sd63_c_procedimento.urlDecode();
      $('sd63_c_nome').value         = oRetorno.aProcedimentos[0].sd63_c_nome.urlDecode();
      js_calend();

    }
    
  } // else { Não possui procedimento vinculado

}

</script>