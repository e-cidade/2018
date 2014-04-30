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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clprontproced->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd27_i_codigo");
$clrotulo->label("sd20_i_codigo");
$clrotulo->label("sd29_i_codigo");
$clrotulo->label("sd29_i_profissional");
$clrotulo->label("sd29_i_procedimento");
$clrotulo->label("sd29_c_hora");
$clrotulo->label("sd16_i_codigo");
$clrotulo->label("sd14_i_codigo");
$clrotulo->label("sd14_c_descr");
$clrotulo->label("sd05_i_codigo");
$clrotulo->label("sd05_c_descr");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd04_i_cbo");
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");

$clrotulo->label("z01_nome");
$clrotulo->label("sd63_c_procedimento");
$clrotulo->label("sd63_c_nome");

//Triagem
$clrotulo->label("nome");
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_i_cgsund");

$clrotulo->label("sd24_i_codigo");
$clrotulo->label("sd24_i_unidade");
$clrotulo->label("sd24_v_motivo");
$clrotulo->label("sd24_v_pressao");
$clrotulo->label("sd24_f_peso");
$clrotulo->label("sd24_f_temperatura");
$clrotulo->label("sd24_i_profissional");
$clrotulo->label("sd24_c_digitada");

//Cid
$clrotulo->label ( "sd70_i_codigo" );
$clrotulo->label ( "sd70_c_cid" );
$clrotulo->label ( "sd70_c_nome" );

if(isset($cgs)){
  $z01_i_cgsund=$cgs;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
   <td align="center">
    <fieldset><legend><b>Procedimento</b></legend>
    <center>
    <table border="0" width="85%">
      <tr>
        <td nowrap title="<?=@$Tsd29_i_codigo?>" align="right">
           <?=@$Lsd29_i_codigo?>
        </td>
        <td colspan="3"> 
          <?
            db_input('sd29_i_codigo',10,$Isd29_i_codigo,true,'text',3,"");
            //db_input('sd24_i_unidade',10,$Isd24_i_unidade,true,'hidden',3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tsd24_i_codigo?>" align="right">
           <?=@$Lsd24_i_codigo?>
        </td>
        <td colspan=3>
         <?
         db_input('sd24_i_codigo',10,$Isd24_i_codigo,true,'text',3,"");
       db_input('z01_v_nome',68,$Iz01_v_nome,true,'text',3);
       db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'hidden',3,'');         
         ?>
        </td>
      </tr>
      <!-- UPS -->
      <tr>
          <td nowrap title="<?=@$Tsd24_i_unidade?>" align="right">
             <?
               db_ancora(@$Lsd24_i_unidade,"js_pesquisasd24_i_unidade(true);",3);
             ?>
          </td>
          <td colspan=3>
           <?
           db_input('sd24_i_unidade',10,$Isd24_i_unidade,true,'text',3," onchange=alert('aquiii')");
           @db_input('descrdepto',68,$Idescrdepto,true,'text',3,"");
           db_input('sd24_c_digitada',10,$Isd24_c_digitada,true,'hidden',3);
           ?>
           
          </td>
      </tr>
      <!-- PROFISSIONAL -->
      <tr>
        <td nowrap title="<?=@$Tsd03_i_codigo?>" align="right">
           <?
           $iOpcProf = 1;
           if (isset($lProfSaude)) {
             if ($lProfSaude == true) {
               $iOpcProf = 3;
             }
           }
           db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true);",$iOpcProf);
           ?>
        </td>
        <td valing="top" align="top" colspan="4">
           <?
              db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$iOpcProf," onchange='js_pesquisasd03_i_codigo(false);'")
           ?>
           <?
              db_input('z01_nome',68,$Iz01_nome,true,'text',3,'')
           ?>
        </td>
      </tr>
    
      <!-- CBO -->
           <tr>
             <td nowrap title="<?=@$Tsd04_i_cbo?>" align="right">
                <?
                db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true);",$db_opcao);
                ?>
             </td>
             <td colspan="4">
              <?
              db_input('sd29_i_profissional',10,$Isd29_i_profissional,true,'hidden',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
              db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
              db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
              db_input('rh70_descr',68,$Irh70_descr,true,'text',3,'');
              ?>
             </td>
           </tr>
      
      <!-- PROCEDIMENTO -->
      <tr>
        <td nowrap title="<?=@$Tsd29_i_procedimento?>" align="right">
          <?
          db_ancora(@$Lsd29_i_procedimento,"js_pesquisasd29_i_procedimento(true);",$db_opcao);
          ?>
        </td>
        <td valign="top" colspan="2">
          <?
          db_input('sd29_i_procedimento',10,$Isd29_i_procedimento,true,'hidden',$db_opcao," onchange='js_pesquisasd29_i_procedimento(false);'"); 
          db_input('sd63_c_procedimento',10,$Isd63_c_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd29_i_procedimento(false);'");
          db_input('sd63_c_nome',68,$Isd63_c_nome,true,'text',3,'')
          ?>       
        </td>
      </tr>
      <!-- CID -->
      <tr>
        <td nowrap title="<?=@$Tsd70_c_cid?>" valign="top" align="right">
          <?
          db_ancora(@$Lsd70_c_cid,"js_pesquisasd70_c_cid(true); \" onFocus='js_foco(this, \"sd24_t_diagnostico\");' ",$db_opcao);
          ?>
        </td>
        <td valign="top" align="top" colspan=4>
          <?
          db_input('sd70_i_codigo',10,$Isd70_i_codigo,true,'hidden',$db_opcao);
          db_input('sd70_c_cid',10,$Isd70_c_cid,true,'text',$db_opcao,"onchange='js_pesquisasd70_c_cid(false);' onFocus='js_foco(this, \"sd29_d_data\");' onblur='js_validacid(this);'");
          db_input('sd70_c_nome',68,$Isd70_c_nome,true,'text',3,"tabIndex='0' ");
          ?>
        </td>
      </tr>
    
        <tr>
          <td nowrap title="<?=@$Tsd29_d_data?>" align="right">
             <?=@$Lsd29_d_data?>
          </td>
          <td> 
             <?
             db_inputdata('sd29_d_data',@$sd29_d_data_dia,@$sd29_d_data_mes,@$sd29_d_data_ano,true,'text',$db_opcao,"");
             ?>
          </td>
          <td align="right" nowrap title="<?=@$Tsd29_c_hora?>">
             <?=@$Lsd29_c_hora?>
              <?db_input('sd29_c_hora',4,$Isd29_c_hora,true,'text',$db_opcao,"OnKeyUp=mascara_hora(this.value,'sd29_c_hora')")?>
          </td>
        </tr>

      <tr>
        <td valign="top" nowrap title="<?=@$Tsd29_t_tratamento?>" align="right">
           <b>Executado:</b>
        </td>
        <td colspan="3"> 
          <?
             $sd29_t_tratamento=!isset($sd29_t_tratamento)?' ':$sd29_t_tratamento;
             db_textarea('sd29_t_tratamento',2,80,@$sd29_t_tratamento,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>      
    </table>
    </center>
    </fieldset>
    <center>
    <fieldset style="width:95%" align="center"><legend>Opções</legend>
      <center>
      <table border="0" width="100%">
        <tr>
          <td>
             <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
              type="submit" id="db_opcao" 
              value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> style="width:150px;">
          </td>
          <td>
            <input name="cancelar" 
             type="button" 
             value="Cancelar" 
             <?=($db_botao1==false?"disabled":"")?> 
             onclick="location.href='sau4_fichaatendabas003.php?chavepesquisaprontuario=<?=$sd24_i_codigo?>'"
             style="width:150px;">
          </td>
          <td>
             <input name="prosseguir" 
              type="button" 
              id="prosseguir"
              value="Prosseguir"
              onclick="js_prosseguir()" 
             <?=($db_botao1==true?"disabled":"")?>
             style="width:150px;">
          </td>
          <td>
             <input name="voltar" 
              type="button" 
              id="voltar" 
              value="" 
              <?=($db_botao1==true?"disabled":"")?>
              style="width:150px;">
          </td>
          <td align="center" valign="middle" style="width: 155px; height: 10px;">
            <input name="novo"   
              type="button"
              id="novafaa"
              value="" 
              <?=($db_botao1==true?"disabled":"")?>
              style="width:150px;">
            <input name="emitir" 
              id="emitir" 
              type="button" 
              value="Emitir FAA" 
              onclick="js_emitirFaa();"
              style="width:150px;">
          </td>
        </tr>
        <tr>
          <td>
             <input name="emitir" 
              type="button"
              id="emitirfaa" 
              value="" 
              <?=($db_botao1==true?"disabled":"")?>
              style="width:150px;">
          </td>
          <td>
              <?selectModelosFaa($oSauConfig->s103_i_modelofaa);?>
          </td>
          <td>
             <input name="fatoresderisco" 
              type="button" 
              value="Fatores de Risco" 
              <?=($db_botao1==true?"disabled":"")?> 
              onclick="js_fatoresderisco()"
              style="width:150px;">
          </td>
           <td>
             <input name="triagem" 
              type="button" 
              value="Triagem" 
              <?=($db_botao1==true?"disabled":"")?> 
              onclick="js_triagem()"
              style="width:150px;">
          </td>
          <td align="center">
             <? 
               if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 1101027) == 'true') { 
             ?>
               <input name="consulta" 
                id="consulta" 
                type="button" 
                value="Consulta Geral" 
                onclick="js_consulta();"
                style="width:150px;">
             <?
               } else {
                  echo "&nbsp;";
               } 
             ?>
          </td>
        </tr>
      </table>
      </center>
   </fieldset>
   </td>
  </tr>
</table>
    
</form>

<script>

/**
 * seta documento para atualizar o enter
 * set url do arquivo RPC
 */
strURL         = 'sau1_sau_individualprocedRPC.php';
booValidaCID   = false;

/**
 * Ajax
 */
function js_ajax( objParam, strCarregando, jsRetorno ){ 
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

if( $F('sd63_c_procedimento') != '' ){
  var objParam                 = new Object();
  objParam.exec                = "getProcedimento";
  objParam.rh70_sequencial     = $F('rh70_sequencial');
  objParam.sd63_c_procedimento = $F('sd63_c_procedimento');
  objParam.rh70_descr          = $F('rh70_descr');

  js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoProcedimento' );

}


//Verifica se é Ficha de Atendimento ou Consulta Médica
var obj = parent.document.formaba;
var a1  = obj.a1.value;
if( a1.toLowerCase() == 'procedimentos' ){ // Consulta médica
  document.getElementById('prosseguir').onclick = function(){ js_prosseguir('a2') };
  document.getElementById('voltar').value = 'Consultar Paciente';
  document.getElementById('voltar').onclick = function(){ js_pesquisaprontuarios() };
  document.getElementById('emitirfaa').value = 'Prontuário Médico';
  document.getElementById('emitirfaa').onclick = function(){ js_prontuarioMedico(); };
  document.getElementById('novafaa').style.display = 'none';
}else{

  document.getElementById('novafaa').onclick = function(){ js_novaficha() };
  document.getElementById('emitir').style.display = 'none';
  document.getElementById('prosseguir').onclick = function(){ js_prosseguir('a4') };
  document.getElementById('voltar').value = 'voltar';
  document.getElementById('voltar').onclick = function(){ parent.mo_camada('a2') };
  document.getElementById('emitirfaa').value = 'Emitir FAA';
  document.getElementById('emitirfaa').onclick = function(){ js_emitirFaa() };
  document.getElementById('novafaa').value= 'Nova FAA';

}

/* Função que abre pop-up da consulta geral */
function js_consulta() {

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
/* Função que fecha a consulta geral */
function js_fechar(){
  db_iframe_consulta.hide();
}

/**
 *Botão Consultar
 */
function js_pesquisaprontuarios () {  

  js_OpenJanelaIframe('',
                      'db_iframe_prontuarios',
                      'func_prontuarios002.php?funcao_js=parent.js_preenchepesquisapront|sd24_i_codigo|z01_v_nome|sd24_i_numcgs',
                      'Pesquisa',
                      true);

}
function js_preenchepesquisapront(chave1,chave2,chave3){

 db_iframe_prontuarios.hide();
 location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+chave1+'&lAlertDiditada=true';

}
/**
 *Botão Prontuário Médico
 */

function js_prontuarioMedico() {

  if (document.form1.sd24_i_codigo.value == '') {
    alert('Paciente não informado!');
  } else {

  
    iTop  = (screen.availHeight - 800) / 2;
    iLeft = (screen.availWidth - 800) / 2;
    sUrl  = 'sau4_relatorioprontuariomedico.iframe.php?';
    sUrl += 'iFaa='+document.form1.sd24_i_codigo.value;
    sUrl += '&iCgs='+document.form1.z01_i_cgsund.value;
    
    js_OpenJanelaIframe('', 'db_iframe_relatorioprontuario', sUrl, 'Prontuário Médico', true, '20', iLeft, 700, 320);

  } 

}

/**
 * Prosseguir Consulta Médica
 */
function js_prosseguir(aba){
  if (document.form1.sd24_i_codigo.value == "") {
    alert("FAA não informada!");  
  } else if ( aba == 'a2' ){ // Rotina: Consulta Médica
    <?
    if( $clprontproced->numrows > 0){
    ?>
      parent.document.formaba.a2.disabled = false;
      parent.iframe_a2.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>
                                     &chaveprofissional='+document.form1.sd03_i_codigo.value;
      parent.document.formaba.a3.disabled = false;
      if (parent.document.formaba.a4 != undefined) {
       
        parent.iframe_a3.location.href = 'sau4_sau_receitamedica001.php?s158_i_profissional=' +
                                         document.form1.sd03_i_codigo.value +
                                         '&s162_i_prontuario=<?=$chavepesquisaprontuario?>&z01_nome=' +
                                         document.form1.z01_nome.value; 
        parent.document.formaba.a4.disabled = false;
        parent.iframe_a4.location.href='sau4_sau_encaminhamentos001.php?lAba=true&s142_i_profsolicitante=' +
                                       document.form1.sd03_i_codigo.value+'&s142_i_prontuario=<?=$chavepesquisaprontuario?>&s142_i_unidade=' +
                                       document.form1.sd24_i_unidade.value+'&nome_profsolicitante='+document.form1.z01_nome.value;
      
      } else {
        
        parent.iframe_a3.location.href='sau4_sau_encaminhamentos001.php?lAba=true&s142_i_profsolicitante=' +
                                        document.form1.sd03_i_codigo.value+'&s142_i_prontuario=<?=$chavepesquisaprontuario?>&s142_i_unidade=' +
                                        document.form1.sd24_i_unidade.value+'&nome_profsolicitante='+document.form1.z01_nome.value;
      
      
      } 
      parent.mo_camada('a2');
     <?
     }else{
       ?>alert('FAA sem procedimentos.');<?
     }   
     ?>
  }else if(parent.document.formaba.a4 != undefined && aba == 'a4' ){
     <?
     if( $clprontproced->numrows > 0){
      echo "parent.document.formaba.a4.disabled = false;";
      //echo "parent.document.formaba.a3.disabled = false;";
      //echo "parent.iframe_a3.location.href='sau4_sau_encaminhamentos001.php?lAba=true&s142_i_profsolicitante='".
          // "+document.form1.sd03_i_codigo.value+'&s142_i_prontuario=$chavepesquisaprontuario&s142_i_unidade='".
          // "+document.form1.sd24_i_unidade.value;";  
      echo "parent.iframe_a4.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=$chavepesquisaprontuario&chaveprofissional='+document.form1.sd03_i_codigo.value;";  
      echo "parent.mo_camada('a4');";
     }else{
       ?>alert('FAA sem procedimentos.');<?
     }   
     ?>
  }
}
 


function js_triagem() {

  if ($F('sd24_i_codigo') != '') {

  
    iTop  = (screen.availHeight - 800) / 2;
    iLeft = (screen.availWidth - 800) / 2;
    sUrl  = 'sau4_sau_triagemavulsa001.php';
    sUrl += '?lFormTriagem=true&chavefaa='+$F('sd24_i_codigo');
    sUrl += '&lConsulta=true&lFechar=true';
    
    js_OpenJanelaIframe('', 'db_iframe_triagemavulsa', sUrl, 'Triagem', true, '20', iLeft, 800, 400);

  } else {
    alert('Selecine uma FAA primeiro.');
  }

}

function js_fatoresderisco(){
    iTop = ( screen.availHeight-800 ) / 2;
    iLeft = ( screen.availWidth-800 ) / 2;
    x  = 'sau4_consultamedica006.php';
    x += '?chavepesquisacgs='+parent.iframe_a1.document.form1.z01_i_cgsund.value;
    js_OpenJanelaIframe('','db_iframe_fatoresderisco',x,'Fator de Risco',true, '40', iLeft, 800, 320);
}


function js_novaficha(){
     parent.document.formaba.a3.disabled = true;
     parent.document.formaba.a2.disabled = true;
     parent.iframe_a1.location.href='sau4_fichaatendabas001.php';
     parent.mo_camada('a1');
}

function js_pesquisasd04_i_cbo(mostra){
  if(mostra==true){
//    js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostrarhcbo1|sd04_i_codigo|rh70_estrutural|rh70_descr|rh70_sequencial&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
  }else{
     if(document.form1.rh70_estrutural.value != ''){ 
//        js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd04_i_codigo|rh70_estrutural|rh70_descr|rh70_estrutural&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
        js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
        document.form1.rh70_estrutural.value = '';
        document.form1.rh70_descr.value = '';
     }else{
       document.form1.rh70_estrutural.value = '';
     }
  }
}
function js_mostrarhcbo(erro,chave1, chave2, chave3,chave4){
  document.form1.rh70_descr.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.sd29_i_profissional.value = chave3;
  document.form1.rh70_sequencial.value = chave4;
  if(erro==true){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2,chave3,chave4){
  document.form1.sd29_i_profissional.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;

  document.form1.sd29_i_procedimento.value = '';
  document.form1.sd63_c_procedimento.value = '';
  document.form1.sd63_c_nome.value = '';
  db_iframe_especmedico.hide();

  if(chave2=''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }  
}


function js_pesquisasd29_i_procedimento(mostra){
  var strParam = 'func_sau_proccbo.php';
  strParam += '?chave_rh70_sequencial='+document.form1.rh70_sequencial.value;
  strParam += '&intUnidade='+$F('sd24_i_unidade');
  strParam += '&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
  
  $('sd70_i_codigo').value = '';
  $('sd70_c_cid').value = '';
  $('sd70_c_nome').value = '';
  
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimento Geral',true);
  }else{
    //if(document.form1.sd29_i_procedimento.value != ''){
    //  strParam += '&chave_sd96_i_procedimento='+document.form1.sd29_i_procedimento.value 
    //  js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimento',true);
    //} else 
    if(document.form1.sd63_c_procedimento.value != ''){
      strParam += '&chave_sd63_c_procedimento='+document.form1.sd63_c_procedimento.value;
      js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimento Nome',true);
    }else{     
          document.form1.sd63_c_nome.value = ''; 
    }
  }
}

function js_mostraprocedimentos(chave,erro){
  document.form1.sd63_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_procedimento.focus(); 
    document.form1.sd29_i_procedimento.value = ''; 
    document.form1.sd29_c_procedimento.focus(); 
    document.form1.sd29_c_procedimento.value = ''; 
  }
}
function js_mostraprocedimentos1(chave1,chave2,chave3){
  //alert(chave1);
  if(chave1==''){
    alert('CBO não tem ligação com procedimento');
  }
  //document.form1.sd29_i_procedimento.value = chave1;
  //document.form1.sd63_c_procedimento.value = chave2;
  //document.form1.sd63_c_nome.value = chave3;
  var objParam                 = new Object();
  objParam.exec                = "getProcedimento";
  objParam.rh70_sequencial     = $F('rh70_sequencial');
  objParam.sd63_c_procedimento = chave2;
  objParam.rh70_descr          = $F('rh70_descr');
  objParam.sd24_i_unidade      = $F('sd24_i_unidade');

  js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoProcedimento' );
  
  db_iframe_sau_proccbo.hide();
}

function js_pesquisasd03_i_codigo(mostra){
  var strParam = 'func_medicos.php';
  strParam += '?chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value;
  if(mostra==true){
    strParam += '&funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome';
    js_OpenJanelaIframe('','db_iframe_medicos',strParam,'Pesquisa Profissional',true);
  }else{
    if(document.form1.sd03_i_codigo.value != ''){
      strParam += '&pesquisa_chave='+document.form1.sd03_i_codigo.value;
      strParam += '&funcao_js=parent.js_mostramedicos'; 
      js_OpenJanelaIframe('','db_iframe_medicos',strParam,'Pesquisa Profissional',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value = '';
    document.form1.sd29_i_profissional.value = '';
    document.form1.rh70_estrutural.value = '';
    document.form1.rh70_descr.value = '';
  }else{
    js_pesquisasd04_i_cbo(true);    
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true);
}



function js_pesquisasd29_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_usuario.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd29_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_usuario.focus(); 
    document.form1.sd29_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd29_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}


function js_pesquisasd29_i_prontuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_mostraprontuarios1|sd24_i_codigo|sd24_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_prontuario.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?pesquisa_chave='+document.form1.sd29_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa',false);
     }else{
       document.form1.sd24_i_codigo.value = ''; 
     }
  }
}
function js_mostraprontuarios(chave,erro){
  document.form1.sd24_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_prontuario.focus(); 
    document.form1.sd29_i_prontuario.value = ''; 
  }
}
function js_mostraprontuarios1(chave1,chave2){
  document.form1.sd29_i_prontuario.value = chave1;
  document.form1.sd24_i_codigo.value = chave2;
  db_iframe_prontuarios.hide();
}


function js_pesquisasd29_i_procafaixaetaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_procfaixaetaria','func_procfaixaetaria.php?funcao_js=parent.js_mostraprocfaixaetaria1|sd16_i_codigo|sd16_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_procafaixaetaria.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_procfaixaetaria','func_procfaixaetaria.php?pesquisa_chave='+document.form1.sd29_i_procafaixaetaria.value+'&funcao_js=parent.js_mostraprocfaixaetaria','Pesquisa',false);
     }else{
       document.form1.sd16_i_codigo.value = ''; 
     }
  }
}
function js_mostraprocfaixaetaria(chave,erro){
  document.form1.sd16_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_procafaixaetaria.focus(); 
    document.form1.sd29_i_procafaixaetaria.value = ''; 
  }
}
function js_mostraprocfaixaetaria1(chave1,chave2){
  document.form1.sd29_i_procafaixaetaria.value = chave1;
  document.form1.sd16_i_codigo.value = chave2;
  db_iframe_procfaixaetaria.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_prontproced','func_prontproced.php?funcao_js=parent.js_preenchepesquisa|sd29_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontproced.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
//document.form1.sd24_i_codigo.value = parent.iframe_a21.document.form1.z01_nome.value;

/**
 * Retorno Pesquisa Procedimento
 */
function js_retornoProcedimento( objAjax ){
    var objRetorno = eval("("+objAjax.responseText+")");

  if (objRetorno.status == 1) {
    if (objRetorno.itens.length > 0) {
         objRetorno.itens.each(function (objProcedimento, iIteracao) {
           //Prenche Procedimento
        $('sd29_i_procedimento').value = objProcedimento.db_sd96_i_procedimento;
        $('sd63_c_procedimento').value = objProcedimento.sd63_c_procedimento.urlDecode();
        $('sd63_c_nome').value         = objProcedimento.sd63_c_nome.urlDecode();
      });
      booValidaCID = objRetorno.itens[0].intcid > 0;
      $('sd70_c_cid').focus();
     }
  } else {
      alert(objRetorno.message.urlDecode());
    $('sd63_c_procedimento').focus();      
    $('sd63_c_procedimento').select();      
  }
} 


/**
 * Pesquisa CID
 */
function js_pesquisasd70_c_cid(mostra){
  if(mostra==true){
    var strParam = ( booValidaCID == true )?'func_sau_proccid2.php':'func_sau_cid.php';
    strParam += '?funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome';
    strParam += '&chave_sd72_i_procedimento='+$F('sd29_i_procedimento');
    strParam += '&campoFoco=sd70_c_cid';
    js_OpenJanelaIframe('','db_iframe_sau_cid',strParam,'Pesquisa CID',true);
  }else{
    if(document.form1.sd70_c_cid.value != ''){
      var objParam            = new Object();
      objParam.exec           = "getCID";
      objParam.sd70_c_cid     = $F('sd70_c_cid');
      objParam.sd29_i_procedimento = $F('sd29_i_procedimento');
      objParam.booValidaCID   = booValidaCID; 
  
      js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoCID' );
    }else{
      $('sd70_i_codigo').value = '';
      $('sd70_c_cid').value    = '';
      $('sd70_c_nome').value   = '';
    }
  }
}
function js_mostrasd70_c_cid1(chave1,chave2,chave3){
  $('sd70_i_codigo').value = chave1;
  $('sd70_c_cid').value    = chave2;
  $('sd70_c_nome').value   = chave3;
  
  db_iframe_sau_cid.hide();
    
}

/**
 * retorno CID
 */
function js_retornoCID( objAjax ){
    var objRetorno = eval("("+objAjax.responseText+")");
    var objForm    = document.form1;

  $('sd70_i_codigo').value = '';
  $('sd70_c_cid').value    = '';
  $('sd70_c_nome').value   = '';
          
    if (objRetorno.status == 1) {
       if (objRetorno.itens.length > 0) {
         objRetorno.itens.each(function (objCID, iIteracao) {
           $('sd70_i_codigo').value = objCID.sd70_i_codigo;
           $('sd70_c_cid').value    = objCID.sd70_c_cid.urlDecode();
           $('sd70_c_nome').value   = objCID.sd70_c_nome.urlDecode();
           });
       }
    } else {
      alert(objRetorno.message.urlDecode());
      $('sd70_c_cid').focus();
    }

}

/**
 * valida obrigatoriedade do cid
 */
function js_validacid( objCID ){
  if( booValidaCID == true && objCID.value == '' ){
    alert('CID obrigatório.');
    $('sd70_c_cid').focus();
  }
} 

function js_emitirFaa() {

  if ($F('sd24_i_codigo') != '') {

    var oParam             = new Object();
    oParam.exec            = 'gerarFAATXT';
    oParam.sChaveProntuarios = $F('sd24_i_codigo');
    oParam.iModelo           = $F('s103_i_modelofaa');
    js_webajax(oParam, 'js_retornoEmissaofaa', 'sau4_ambulatorial.RPC.php');

  } else {

    alert('Nenhuma FAA para gerar.');

  }

}

function js_retornoEmissaofaa (oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    if (oRetorno.iTipo == 1) {
      js_emitiefaaPDF (oRetorno);
    } else {
      js_emitirfaaTXT (oRetorno);
    }

  }

}

function js_emitiefaaPDF (oDados) {

  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";

  WindowObjectReference = window.open(oDados.sArquivo+sChave,"CNN_WindowName", strWindowFeatures);

}

function js_emitirfaaTXT (oRetorno) {

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;
  sChave = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe ('', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave, 
                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
                      );

}

function js_retornoEmissaofaa (oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  } else {
    if (oRetorno.iTipo == 1) {
      js_emitiefaaPDF (oRetorno);
    } else {
      js_emitirfaaTXT (oRetorno);
    }
  }

}

function js_emitiefaaPDF (oDados) {

  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
  sArquivo = js_getArquivoFaa($F('s103_i_modelofaa'));
  WindowObjectReference = window.open(sArquivo+sChave,"CNN_WindowName", strWindowFeatures);

}

function js_emitirfaaTXT (oRetorno) {

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;
  sChave = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe ('', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave, 
                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
                      );

}

function js_getArquivoFaa(iCodModelo) {

  oSel = $('sArquivoFaa');
  for (var iCont = 0; iCont < oSel.length; iCont++) {

    if (iCodModelo == oSel.options[iCont].value) {
      return oSel.options[iCont].text;
    }

  }

}


</script>