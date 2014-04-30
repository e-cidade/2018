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

//MODULO: Laboratório
$cllab_requisicao->rotulo->label();
$cllab_requiitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la02_c_descr");
$clrotulo->label("la08_i_codigo");
$clrotulo->label("la08_c_descr");
$clrotulo->label("la09_i_codigo");
$clrotulo->label("la09_i_exame");
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("la23_i_codigo");
$clrotulo->label("la38_i_medico");
$clrotulo->label("sd03_i_crm");
$clrotulo->label("la24_i_laboratorio");

?>
<form name="form1" method="post" action="">
<center>
  <fieldset style='width: 75%;'><b><legend>Requisição:</legend></b>
  <table style='width: 100%;' border="0">
    <tr>
      <td>
        <table style='width: 95%;' border="0" id="tabela1">
          <tr>
            <td>
              <?=@$Lla22_i_codigo?>
            </td>
            <td>
              <table>
                <tr>
                  <td>
                    <?db_input('la22_i_codigo',10,$Ila22_i_codigo,true,'text',3,"")?>
                  </td>
                  <td>
                    <b>Data:</b>
                  </td>
                  <td> 
                    <?db_inputdata('la22_d_data',@$la22_d_data_dia,@$la22_d_data_mes,@$la22_d_data_ano,true,'text',3,"")?>
                  </td>
                </tr>
              </table>
            </td>
            <td>  
              <table>
               <tr>
                 <td>
                   <input type="button" id="medicamentos" name="medicamentos" Value="Medicamentos" onclick="js_lanca(1)">
                 </td>
                 <td>
                   <input type="button" id="diagnostico" name="diagnostico" value="Diagn&oacute;stico" onclick="js_lanca(2)">
                 </td>
                 <td>
                   <input type="button" id="obs" name="obs" value="Observa&ccedil;&atilde;o" onclick="js_lanca(3)">
                 </td>
               </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <?db_ancora(@$Lla22_i_cgs,"js_pesquisala22_i_cgs(true);",$db_opcao);?>
            </td>
            <td nowrap>
              <?db_input('la22_i_cgs',10,$Ila22_i_cgs,true,'text',$db_opcao," onchange='js_pesquisala22_i_cgs(false);'")?>
              <?db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'')?>
              <?db_input('z01_v_sexo',50,"",true,'hidden',3,'')?>
            </td>
          </tr>
          <tr style="display:<?=(@$z01_v_sexo == "F") ? "" : "none"?>" id="linha_dum">
            <td>
              <?=@$Lla22_d_dum?>
            </td>
            <td>
              <?db_inputdata('la22_d_dum',@$la22_d_dum_dia,@$la22_d_dum_mes,@$la22_d_dum_ano,true,'text',$db_opcao,"")?>
            </td>
          </tr>
          <tr>
            <td>
              <?db_ancora(@$Lla38_i_medico,"js_pesquisala38_i_medico(true);","");?>
            </td>
            <td>
              <?db_input('la38_i_medico',10,@$Ila38_i_medico,true,'text',""," onchange='js_pesquisala38_i_medico(false);'")?>
              <?db_input('z01_nome',50,@$Ila22_c_medico,true,'text',1,'');?>
            </td>
            <td>
              <? 
              if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8675) == 'true') { 
              ?>
                <input type="button" id="cadProf" title="Cadastro de Profissionais Fora da Rede"  
                  name="cadProf" value="Cadastro de Profissionais" onclick="js_abreCadProf();">
              <?
              }
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Responsavel: </b>
            </td>
            <td>
              <?db_input('la22_c_responsavel',64,$Ila22_c_responsavel,true,'text',$db_opcao,'')?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Contato:</b>
            </td>
            <td>
              <?db_input('la22_c_contato',64,@$Ila22_c_contato,true,'text',$db_opcao,'')?>
            </td>
          </tr>
          <tr align="center">
            <td colspan="3">
              <fieldset style='width: 95%;'><b><legend>Exames:</legend></b>
              <table style='width: 95%;' border="0">
                <tr>
                  <td align="right">
                    <?db_ancora(@$Lla09_i_exame,"js_pesquisala09_i_exame(true);",$db_opcao);?>
                  </td>
                  <td>
                    <?db_input('la09_i_exame',10,@$Ila38_i_medico,true,'hidden',1,'')?>
                    <?db_input('la08_c_descr',50,@$Ila22_c_medico,true,'text',1,' style="width:500px" ');?>
                  </td>
                  <td colspan="3">
                    <?=$Lla21_i_quantidade?>
                    <?$la21_i_quantidade = 1;
                      db_input('la21_i_quantidade',2,@$Ila21_i_quantidade,true,'text',1,'');?>
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b><?=$Lla24_i_laboratorio?></b>
                  </td>
                  <td>
                    <?
                      $aOptions=array("0"=>"Selecione::");
                      db_select("la09_i_codigo",$aOptions,$Ila09_i_codigo,$db_opcao,"onchange=\"js_LoadSetorExame(this.value);\" style=\"width:500px\" ");
                    ?>
                  </td>
                  <td>
                    <a onclick="pegaPosMouse(event);js_calendario();" id="ancora_calend" style="color: blue; text-decoration: underline; cursor: pointer;"><b>Data:</b></a>
                  </td>
                  <td>
                    <?db_inputdata('la21_d_data',@$la21_d_data_dia,@$la21_d_data_mes,@$la21_d_data_ano,true,'text',$db_opcao," disabled ")?>
                  </td>
                  <td>    
                    <input type="button" Value="Lan&ccedil;ar" name="lanc" id="lanc" onclick="js_IncluirExame();">
                  </td>
                </tr>
                <tr>
                  <td colspan="5">
                    <div id="GridExames" name="GridExames"></div>
                    <select name="exames" style="display:none;"></select>
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
</center>
<input name="confirma" type="submit" id="db_opcao" value="Confirmar" onclick="return js_envia()">
<input name="excluir" type="submit" id="db_opcao" value="Excluir" disabled >
<? if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8344) == 'true') { ?>
     <input name="autorizar" type="submit" id="autorizar" value="Autorizar" disabled >
<? } ?>
<input name="conprov" type="button" id="comprov" value="Comprovante" onclick="js_comprovante();" disabled >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa()">
<input name="nova" type="button" id="nova" value="Nova Requisi&ccedil;&atilde;o" onclick="js_nova()">
<?db_input('la21_c_dia',20,"",true,'hidden',$db_opcao,'');
db_input('la21_c_hora',20,"",true,'hidden',$db_opcao,'');
db_input('la08_i_dias',20,"",true,'hidden',$db_opcao,'');
db_input('la02_i_codigo',20,"",true,'hidden',$db_opcao,'');
db_input('la02_c_descr',20,"",true,'hidden',$db_opcao,'');
db_input('requisitos',20,"",true,'hidden',$db_opcao,'');
db_input('la22_t_medicamento',20,"",true,'hidden',$db_opcao,'');
db_input('la22_t_diagnostico',20,"",true,'hidden',$db_opcao,'');
db_input('la22_t_observacao',20,"",true,'hidden',$db_opcao,'');
db_input('sStr',20,"",true,'hidden',$db_opcao,'');
db_input('sUrgente',20,"",true,'hidden',$db_opcao,'')?>
</form>
<script>

//Autocomplete do Exame
oAutoComplete1 = new dbAutoComplete(document.form1.la08_c_descr,'lab4_agendar.RPC.php?tipo=2');
oAutoComplete1.setTxtFieldId(document.getElementById('la09_i_exame'));
oAutoComplete1.setHeightList(180);
oAutoComplete1.show();
oAutoComplete1.setCallBackFunction(function(id,label) {
	  
    document.form1.la09_i_exame.value=id;
    document.form1.la08_c_descr.value=label;
    js_loadLaboratorios(id);
      
  });

//Autocomplete do profissional
oAutoComplete2 = new dbAutoComplete(document.form1.z01_nome,'lab4_agendar.RPC.php?tipo=1');
oAutoComplete2.setTxtFieldId(document.getElementById('la38_i_medico'));
oAutoComplete2.setHeightList(180);
oAutoComplete2.show();

//Inicializar Rotina
lab_ajax                         = new ws_ajax('lab4_agendar.RPC.php');
sRPC                             = 'lab4_agendar.RPC.php';
objGridExames                    = new DBGrid(' GridExames ');
F                                = document.form1;
F.dtjs_la21_d_data.style.display ='none';
js_init();
<?if($cllab_setorexame->numrows>0){
    
	echo" js_LoadSetorExame(F.la09_i_codigo.value); ";
}?>                                         

//Seletor de tipo
function js_tipo(sexo){
   var table = document.getElementById('tabela1');
   status='none';
   if(sexo=='F'){
      status = '';
   }   
   for (var r = 0; r < table.rows.length; r++){
       var id = table.rows[r].id;
       if(id == 'linha_dum'){
          table.rows[r].style.display = status;
       }
   }                                        
}

// GridExames
  function js_init() {

     var arrHeader = new Array ( "M",
                                 " Cod. ",  
                                 "    <?=substr(@$Lla24_i_laboratorio,8,-10)?>   ", 
                                 "     <?=substr(@$Lla09_i_exame,8,-10)?>     ",
                                 "     <?=substr(@$Lla21_i_quantidade,8,-10)?>     ",
                                 " <?=substr(@$Lla21_d_data,8,-10)?>  ",
                                 " <?=substr(@$Lla21_c_hora,8,-10)?> ",
                                 " <?=substr(@$Lla21_d_entrega,8,-10)?> ",
                                 " opções ",
                                 " urgente");

    objGridExames.nameInstance = 'oGridExames';
    objGridExames.setHeader( arrHeader );
    objGridExames.setHeight(80);
    objGridExames.show($('GridExames')); 

  } 
  function js_AtualizaGrid(){

    objGridExames.clearAll(true);
    tam=F.exames.length; 
    for(x=0;x<tam;x++){

       sText=F.exames.options[x].text;
       avet=sText.split('#');
       alinha= new Array();
       alinha[0] = '<input type="checkbox" id="check'+x+'" checked >';  
       alinha[1] = avet[0]; //codigo Setor/Exame
       alinha[2] = avet[1]; //descr  laboratorio
       alinha[3] = avet[2]; //descr  exame
       alinha[4] = avet[7]; //quantidade
       alinha[5] = avet[3]; //data coleta
       alinha[6] = avet[4]; //hora coleta
       alinha[7] = avet[5]+'dias'; //data entrega
       alinha[8] = '<input type="button" name="exc'+x+'" value="Excluir" onclick="js_excluirExame('+F.exames.options[x].value+',\''+avet[2]+'\')">';
       scheck=(avet[6]==1)?' checked ':'';
       alinha[9] = '<input type="checkbox" id="urgente'+x+'" '+scheck+' >';
       objGridExames.addRow(alinha);

    }
    objGridExames.renderRows();

  }
  function js_DadosExames(){

     aVet = new Array();
     aVet[aVet.length] = F.la09_i_codigo.value;
     aVet[aVet.length] = F.la09_i_codigo.options[F.la09_i_codigo.selectedIndex].text;
     aVet[aVet.length] = F.la08_c_descr.value;
     aVet[aVet.length] = F.la21_d_data.value;
     aVet[aVet.length] = F.la21_c_hora.value;
     aVet[aVet.length] = F.la08_i_dias.value;
     aVet[aVet.length] = '0';
     aVet[aVet.length] = F.la21_i_quantidade.value;

     return sStr  = aVet.join('#'); 

  }
  function js_IncluirExame(){
     if(F.la09_i_codigo.value!=''){
        if(F.la21_d_data.value!=''){
           if(confirm(F.requisitos.value)){
              <? if ($oConfig->la49_i_exameduplo == 2) { ?> 
              if (js_verificaexame()) {
            	<? } ?>
                 sStr = js_DadosExames();
                 F.exames.add(new Option(sStr,F.exames.length),null);
                 js_AtualizaGrid();
                 F.la08_c_descr.select();
                 F.la21_i_quantidade.value = 1;
              <? if ($oConfig->la49_i_exameduplo == 2) { ?>     
              }
              <? } ?> 
           }
        }else{
           alert('Informe uma Data!');
        }
     }else{
        alert('Informe um exame!');
     }
  }
  function js_verificaexame(){
	  
    tam=F.exames.length; 
    if(tam==0){
      return true;
    } 
    for(x=0;x<tam;x++){
      sStr=F.exames.options[x].text;
      aVet=sStr.split('#');
      if(aVet[0]==F.la09_i_codigo.value){
        alert('Exame ja lançado!');
        return false;
      }
    }
    return true;
    
  }
  function js_excluirExame(id_linha,exame){
    if(confirm('Deseja apagar o exame:'+exame+' ?')){ 
       F.exames.remove(id_linha);
       js_AtualizaGrid();
    }
  }
//fim funções do Grid  

//outras
  function js_loadLaboratorios(exame){
        if((exame!='')&&(exame>0)){
           
           //Requisição ajax normal
           var oParam      = new Object();
           oParam.exec     = 'LoadLaboratorio';
           oParam.exame    = exame;
           js_ajax( oParam, 'js_loadLaboratoriosReturn' );

        }else{
           alert('Erro - Laboratorio sem codigo(AJAX)');
        }
  }
  function js_loadLaboratoriosReturn(oAjax){
        oRetorno=lab_ajax.monta(oAjax);
        if(oRetorno.status==1){
           Tam=F.la09_i_codigo.length;
           for(x=Tam;x>0;x--){
               F.la09_i_codigo.remove(x-1);
           }
           for(x=0;x<oRetorno.codigos.length;x++){
               F.la09_i_codigo.add( new Option(oRetorno.laboratorios[x],oRetorno.codigos[x]),null);
           }
           js_LoadSetorExame(F.la09_i_codigo.value);
        }else{
           Tam=F.la09_i_codigo.length;
           for(x=Tam;x>0;x--){
               F.la09_i_codigo.remove(x-1);
           }
           message_ajax(oRetorno.message); 
        }   
  }
  function js_LoadSetorExame(cod){
        if((cod!='')&&(cod>0)){
           
           //Requisição ajax normal
           var oParam      = new Object();
           oParam.exec     = 'DadosExame';
           oParam.la09_i_codigo  = cod;
           js_ajax( oParam, 'js_retornoDadosExame' );
           
       }else{
           alert('Erro - setorexame sem codigo!(Ajax)');
       }
  }
  function js_retornoDadosExame(oAjax){
        oRetorno=lab_ajax.monta(oAjax);
        if(oRetorno.status==1){
           F.la08_i_dias.value=oRetorno.dias;
           F.la02_i_codigo.value=oRetorno.iLaboratorio;
           F.la02_c_descr.value=oRetorno.sLaboratorio;
           F.requisitos.value=oRetorno.sRequisitos;
        }else{
           message_ajax(oRetorno.message); 
        }
  }
  function js_calendario(){
	  if ($F(la21_i_quantidade) != '' && $F(la21_i_quantidade) != '0') { 
      show_calendariolaboratorio('la21_d_data','parent.js_HoraExame(); ',$F(la09_i_codigo),$F(la21_i_quantidade));
	  } else {
      alert('Qauntidade de exame invalida!');
		}
  }
  function js_HoraExame(){
      //retorno da função do calendario 
  }  
    function js_envia(){
            if(F.la22_i_cgs.value==''){
              alert('Informe um CGS!');
              return false;
            }
            if(F.la38_i_medico.value==''){
              alert('informe um medico!');
              return false;
            }
            sStr='';
            sUrgente='';
            sSep='';
            tam=F.exames.length; 
            if(tam==0){
              alert('Lance um exame para o paciente!');
              return false;
            }
            aSetores = new Array();
            aDatas   = new Array();
            for(x=0;x<tam;x++){
              sStr+=sSep+F.exames.options[x].text;
              mTmp  = F.exames.options[x].text.split('#');
              aSetores[x] = mTmp[0];
              aDatas[x] = mTmp[3];
              if(document.getElementById('urgente'+x).checked==true){
                sUrgente+=sSep+'1';
              }else{
                sUrgente+=sSep+'0';
              }
              sSep='##';
            }
            // Envio o setorexame e a data do exame no formato brasileiro para a função
            if (!js_verificarSaldo(aSetores, aDatas)) {
              return false;
            } 
            F.sStr.value=sStr;
            F.sUrgente.value=sUrgente;
            return true;
  }
  
  function js_lanca(iQual){
      if(iQual==1){
         sNome='MEDICAMENTO';
         sTexto=F.la22_t_medicamento.value;
         sCampo='la22_t_medicamento';
      }else if(iQual==2){
         sNome='DIAGNÓSTICO';
         sTexto=F.la22_t_diagnostico.value;
         sCampo='la22_t_diagnostico';
      }else{
         sNome='OBSERVAÇÃO';
         sTexto=F.la22_t_observacao.value;
         sCampo='la22_t_observacao';
      }
      iTop = ( screen.availHeight-600 ) / 2;
      iLeft = ( screen.availWidth-600 ) / 2;   
      js_OpenJanelaIframe("","db_iframe_lab_box","lab4_box001.php?sNome="+sNome+"&sTexto="+sTexto+"&sCampo="+sCampo+"","Pesquisa",true,iTop, iLeft, 600, 200);

  }
  function js_nova(){
      location.href='lab4_agendar001.php';  
  }
  function js_comprovante(){
      tam=F.exames.length;
      if(tam>0){ 
        sListaExames='';
        sep='';
        erro=true;
        for(x=0;x<tam;x++){
           sStr=F.exames.options[x].text;
           aVet=sStr.split('#');
           if(document.getElementById('check'+x).checked==true){
               sListaExames += sep+aVet[0];
               sep=',';
               erro=false;
           }
        }
        if(erro==false){
          if(F.la22_i_codigo.value!=''){
            jan = window.open('lab2_comprovante001.php?sListaExames='+sListaExames+'&la22_i_codigo='+F.la22_i_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            jan.moveTo(0,0);
          }else{
            alert('Erro - requisicao sem codigo!');  
          }
        }else{
          alert('selecione um exame para emitir o comprovante!');
        }
      }else{
        alert('Lance um exame para o paciente!');
      }
  }




//Lookup's

function js_pesquisala22_i_cgs(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome|z01_v_sexo','Pesquisa',true);
  }else{
     if(document.form1.la22_i_cgs.value != ''){ 
       js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.la22_i_cgs.value+'&funcao_js=parent.js_mostracgs_und','Pesquisa',false);
     }else{
       document.form1.z01_v_nome.value = ''; 
     }
  }
}
function js_mostracgs_und(chave,erro,sexo){
  document.form1.z01_v_nome.value = chave; 
  if(erro==true){ 
    document.form1.la22_i_cgs.focus(); 
    document.form1.la22_i_cgs.value = ''; 
  }else{
	  js_tipo(sexo);
  }
}
function js_mostracgs_und1(chave1,chave2,sexo){
  document.form1.la22_i_cgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  js_tipo(sexo);
  db_iframe_cgs_und.hide();
}

function js_pesquisala09_i_exame(mostra){
  if (mostra == true) {
    js_OpenJanelaIframe('',
    	                  'db_iframe_lab_exame',
    	                  'func_lab_exame.php?funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr&iVinculo=0'+
    	                  '&iAtivo=1',
    	                  'Pesquisa',
    	                  true);
  }else{
     if(document.form1.la22_i_cgs.value != ''){ 
        js_OpenJanelaIframe('',
                            'db_iframe_lab_exame',
                            'func_lab_exame.php?pesquisa_chave='+document.form1.la09_i_exame.value+
                            '&funcao_js=parent.js_mostralab_exame&iVinculo=0&iAtivo=1',
                            'Pesquisa',
                            false);
     }else{
       document.form1.la08_c_descr.value = ''; 
     }
  }
}
function js_mostralab_exame(chave,erro){
  document.form1.la08_c_descr.value = chave; 
  if (erro == true) { 
    document.form1.la09_i_exame.focus(); 
    document.form1.la09_i_exame.value = ''; 
  }else{
	  js_loadLaboratorios(document.form1.la09_i_exame.value);
  }
}
function js_mostralab_exame1(chave1,chave2){
  document.form1.la09_i_exame.value = chave1;
  document.form1.la08_c_descr.value  = chave2;
  js_loadLaboratorios(chave1);
  db_iframe_lab_exame.hide();
}

function js_pesquisala38_i_medico(mostra){
    if(mostra==true){
       js_OpenJanelaIframe('',
    	                     'db_iframe_medicos',
    	                     'func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome'+
    	                     '&lTodosTiposProf=true',
    	                     'Pesquisa',
    	                     true);
    }else{
       if(document.form1.la38_i_medico.value != ''){ 
          js_OpenJanelaIframe('',
                              'db_iframe_medicos',
                              'func_medicos.php?pesquisa_chave='+document.form1.la38_i_medico.value+
                              '&funcao_js=parent.js_mostramedicos&lTodosTiposProf=true',
                              'Pesquisa',
                              false);
       }else{
          document.form1.z01_nome.value = ''; 
       }
    }
}
function js_mostramedicos(chave,erro,chave2){
    document.form1.z01_nome.value = chave; 
    if(erro==true){ 
       document.form1.la38_i_medico.focus(); 
       document.form1.la38_i_medico.value = ''; 
    }
}
function js_mostramedicos1(chave1,chave2){
    document.form1.la38_i_medico.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_medicos.hide();
}

<?
    if(isset($alinhasgrid)){
        if(count($alinhasgrid)>0){
            for($x=0;$x<count($alinhasgrid);$x++){
               echo" F.exames.add(new Option('".$alinhasgrid[$x]."',F.exames.length),null);";   
            }
            echo"js_AtualizaGrid(); ";
            echo"F.la21_d_data.value='';";
            echo"F.comprov.disabled=false;";
            echo"F.excluir.disabled=false;";
            echo"F.confirma.value='Alterar';";
            if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8344) == 'true') { 
              echo"F.autorizar.disabled=false;";
            }
        } else {
            echo"alert('Erro - Nenhum exame vinculado a requisicao!');";
        }
    }

?>


function js_pesquisa(){
  js_OpenJanelaIframe('',
		                  'db_iframe_lab_requisicao',
		                  'func_lab_requisicao.php?iDepResitante=<?=$departamento?>&'+
		                  'autoriza=1&funcao_js=parent.js_preenchepesquisa|la22_i_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_lab_requisicao.hide();
  location.href = 'lab4_agendar001.php?chavepesquisa='+chave;
}

function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

	  var mRetornoAjax;

	  if (sUrl == undefined) {
	    sUrl = 'lab4_agendar.RPC.php';
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
	                                    
	                                               var evlJS           = jsRetorno+'(oAjax);';
	                                               return mRetornoAjax = eval(evlJS);
	                                               
	                                           }
	                              }
	                             );

	  return mRetornoAjax;

	}

function js_preencheMedicoRecemCadastrado(iCod, sNome) {

  $('la38_i_medico').value = iCod;
  js_pesquisala38_i_medico(false);

}

function js_abreCadProf() {

  iTop  = (screen.availHeight - 650) / 2;
  iLeft = (screen.availWidth - 800) / 2;

  if ($F('la38_i_medico') == '') {

    sGet = 's154_c_nome='+$F('z01_nome')+'&sd03_i_tipo=2&lBotao=true';

    js_OpenJanelaIframe('', "db_iframe_cadprof", "sau1_sau_medicosforarede001.php?"+sGet, 
                        'Cadastro de Profissionais Fora da Rede', true,iTop, iLeft, 800, 300
                       );

  } else {
	   
    var oParam              = new Object();
    oParam.exec             = 'verificaForaRede';
    oParam.iMedico          = $F('la38_i_medico');

    if (js_ajax(oParam, 'js_retornoVerificaForaRede', 'sau4_ambulatorial.RPC.php', false)) {

      sGet = 'chavepesquisa='+$F('fa04_i_profissional')+'&lBotao=true';
	      
      js_OpenJanelaIframe('', "db_iframe_cadprof", "sau1_sau_medicosforarede002.php?"+sGet, 
                          'Cadastro de Profissionais Fora da Rede', true,iTop, iLeft, 800, 300
                         );

    } else {
      alert('Profissional selecionado não é um profissional de fora da rede.');
    }
	 
  }

}

function js_verificarSaldo(aSetores, aDatas) {

  var oParam         = new Object();
  
  oParam.exec        = 'verificarSaldoExame';
  oParam.iSetorExame = aSetores;
  oParam.dData       = aDatas;
  
  return js_ajax(oParam, 'js_retornoVerificarSaldo', 'lab4_laboratorio.RPC.php');

}

function js_retornoVerificarSaldo(oRetorno) {
	  
  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    if (oRetorno.lSaldoSuficiente == true) {
      return true;
    } else { // Saldo insuficiente
        
      if (oRetorno.lLiberarSemSaldo == true) {
        var lLiberar = confirm ("Saldo excedido! \nDeseja liberar a realização de exames\n mesmo excedendo o saldo?");
        if(lLiberar) {
          return true;
        } else {
          return false;
        }
      }
      alert(oRetorno.sMessage.urlDecode());
      return false;

    }

  } else {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  }

}
	
</script>