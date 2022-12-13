<?php

/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
   <table style='width: 100%;' border="0">
         <tr align="center">
             <td>
                  <fieldset style='width: 75%;'><b><legend>Requisicao<legend></b>
                        <table style='width: 95%;' border="0">
                              <tr>
                                   <td>
                                        <table style='width: 100%;' border="0">
                                            <tr>
                                               <td>
                                                   <?=@$Lla22_i_codigo?>&nbsp;<?db_input('la22_i_codigo',10,$Ila22_i_codigo,true,'text',3,"")?>                                                 
                                               </td>                                               
                                            </tr>
                                        </table>
                                   </td>
                              </tr>
                              <tr>
                                   <td>
                                       <fieldset><b><legend>Paciente<legend></b>
                                            <table>
                                                <tr>
                                                    <td nowrap><?db_ancora(@$Lla22_i_cgs,"js_pesquisala22_i_cgs(true);",3);?></td>
                                                    <td nowrap>
                                                        <?db_input('la22_i_cgs',10,$Ila22_i_cgs,true,'text',3," onchange='js_pesquisala22_i_cgs(false);'")?>
                                                        <?db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'')?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><?=@$Lla22_d_dum?></td>
                                                    <td><?db_inputdata('la22_d_dum',@$la22_d_dum_dia,@$la22_d_dum_mes,@$la22_d_dum_ano,true,'text',3,"")?></td>
                                                </tr>
                                            </table> 
                                       </fieldset>
                                   </td>
                              </tr>
                              <tr>
                                   <td>
                                       <fieldset><b><legend>Médico<legend></b>
                                             <table>
                                                  <tr>
                                                      <td><?db_ancora(@$Lla38_i_medico,"js_pesquisala38_i_medico(true);",3);?></td>
                                                      <td>
                                                          <?db_input('la38_i_medico',10,@$Ila38_i_medico,true,'text',3," onchange='js_pesquisala38_i_medico(false);'")?>
                                                          <?db_input('la22_c_medico',50,@$Ila22_c_medico,true,'text',3,'');?>
                                                      </td>
                                                  </tr>
                                                  <tr>
                                                      <td><b>CRM:<b></td>
                                                      <td><?db_input('sd03_i_crm',10,@$sd03_i_crm,true,'text',3,'');?></td>
                                                  </tr>
                                             </table>
                                       </fieldset>
                                   </td>
                              </tr>
                              <tr>
                                   <td>
                                       <fieldset><b><legend>Responsavel<legend></b>
                                             <table>
                                                 <tr>
                                                      <td><b>Responsavel: </b></td>
                                                      <td><?db_input('la22_c_responsavel',56,$Ila22_c_responsavel,true,'text',3,'')?></td>
                                                 </tr>
                                                 <tr>
                                                      <td><b>Contato:</b></td>
                                                      <td><?db_input('la22_c_contato',56,@$Ila22_c_contato,true,'text',3,'')?></td>
                                                 </tr>
                                             </table>
                                       </fieldset>
                                   </td>
                              </tr>
                        </table>
                  </fieldset>
             </td>
         </tr>
         <tr align="center">
             <td>
                  <fieldset style='width: 95%;'><b><legend>Exames<legend></b>
                       <table style='width: 95%;' border="0">
                            <tr>
                                <td>
                                    <?
                                       $rResult=$cllab_laboratorio->sql_record($cllab_laboratorio->sql_query(""," la02_i_codigo as chave, la02_c_descr as descricao","",""));
                                       $aLaboratorios = array();
                                       for($x=0;$x<$cllab_laboratorio->numrows;$x++){
                                          db_fieldsmemory($rResult,$x);
                                          $aLaboratorios[$chave] = $descricao;
                                       }
                                       db_input('la02_i_codigo',56,@$aLaboratorios,true,'hidden',3,'');                                       
                                    ?>
                                </td>
                                <td>
                                    <?
                                       if($cllab_laboratorio->numrows>0){
                                          db_fieldsmemory($rResult,0);
                                          if(isset($la02_i_codigo)){
                                             $chave=$la02_i_codigo;
                                          }
                                          $rResult=$cllab_setorexame->sql_record($cllab_setorexame->sql_query(""," la09_i_codigo as chave,la08_c_descr as descricao",""," la24_i_laboratorio=$chave "));
                                          $aExames=array();
                                          for ($x = 0; $x < $cllab_setorexame->numrows; $x++) {
                                             db_fieldsmemory($rResult,$x);
                                             $aExames[$chave] = $descricao;
                                          }
                                          
                                          db_input('la09_i_codigo',56,@$aExames,true,'hidden',3,'');                                       
                                       }
                                    ?>
                                </td>
                                <td>                                    
                                    <?db_inputdata('la21_d_data',@$la21_d_data_dia,@$la21_d_data_mes,@$la21_d_data_ano,true,'hidden',$db_opcao," disabled ")?>
                                    <?db_input('la21_c_dia',20,@$Icontato,true,'hidden',$db_opcao,'')?>
                                    <?db_input('la21_c_hora',20,@$Icontato,true,'hidden',$db_opcao,'')?>
                                    <?db_input('la08_i_dias',20,@$Icontato,true,'hidden',$db_opcao,'')?>
                                    <?db_input('requisitos',20,@$Icontato,true,'hidden',$db_opcao,'')?>
                                    <?db_input('la22_t_medicamento',20,"",true,'hidden',$db_opcao,'')?>
                                    <?db_input('la08_t_observacao',20,"",true,'hidden',$db_opcao,'')?>
                                    <?db_input('sStr',20,"",true,'hidden',$db_opcao,'')?>
                                    <?db_input('sUrgente',20,"",true,'hidden',$db_opcao,'')?>                                   
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <div id="GridExames" name="GridExames"></div>
                                    <select name="exames" style="display:none"></select>
                                </td>
                            </tr>
                       </table>
                  </fieldset>
             </td>
         </tr>
   </table>
</center>
<input name="conprov" type="button" id="comprov" value="Comprovante" onclick="js_comprovante();" disabled >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa()">
</form>
<script>

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

// GridExames
  function js_init() {

     var arrHeader = new Array ( "M",
                                 " Cod. ",  
                                 "    <?=substr(@$Lla24_i_laboratorio,8,-10)?>   ", 
                                 "     <?=substr(@$Lla09_i_exame,8,-10)?>     ",
                                 " <?=substr(@$Lla21_d_data,8,-10)?>  ",
                                 " <?=substr(@$Lla21_c_hora,8,-10)?> ",
                                 " <?=substr(@$Lla21_d_entrega,8,-10)?> ",
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
       alinha[0]='<input type="checkbox" id="check'+x+'" checked >';  
       alinha[1]=avet[0]; //codigo Setor/Exame
       alinha[2]=avet[1]; //descr  laboratorio
       alinha[3]=avet[2]; //descr  exame
       alinha[4]=avet[3]; //data coleta
       alinha[5]=avet[4]; //hora coleta
       alinha[6]=avet[5]+'dias'; //data entrega
       scheck=(avet[6]==1)?' checked ':'';
       alinha[7]='<input type="checkbox" id="urgente'+x+'" '+scheck+' disabled >';
       objGridExames.addRow(alinha);

    }
    objGridExames.renderRows();

  }
  function js_DadosExames(){

     iSetorExame  = F.la09_i_codigo.value;
     sLaboratorio = F.la02_i_codigo.options[F.la02_i_codigo.selectedIndex].text;
     sExame       = F.la09_i_codigo.options[F.la09_i_codigo.selectedIndex].text;
     dData        = F.la21_d_data.value;
     sHora        = F.la21_c_hora.value;
     dEntrega     = F.la08_i_dias.value;
     iurgencia    = '0';
     return sStr  = iSetorExame+'#'+sLaboratorio+'#'+sExame+'#'+dData+'#'+sHora+'#'+dEntrega+'#'+iurgencia;

  }
  function js_IncluirExame(){
     if(F.la09_i_codigo.value!=''){
        if(F.la21_d_data.value!=''){
           if(confirm(F.requisitos.value)){
              if(js_verificaexame()){
              
                 sStr = js_DadosExames();
                 F.exames.add(new Option(sStr,F.exames.length),null);
                 js_AtualizaGrid();
                 F.la21_d_data.value='';
                  
              }
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
  function js_RecarregaExames(laboratorio){
        if((laboratorio!='')&&(laboratorio>0)){
           
           //classe Ajax
           //lab_ajax.clear();
           //lab_ajax.add('la24_i_laboratorio',laboratorio);
           //lab_ajax.execute('LoadExames','js_retornoRecExames');
           
           //Requisição ajax normal
           var oParam      = new Object();
           oParam.exec     = 'LoadExames';
           oParam.la24_i_laboratorio  = laboratorio;
           js_ajax( oParam, 'js_retornoRecExames' );

       }
  }
  function js_retornoRecExames(oAjax){
        oRetorno=lab_ajax.monta(oAjax);
        if(oRetorno.status==1){
           Tam=F.la09_i_codigo.length;
           for(x=Tam;x>0;x--){
               F.la09_i_codigo.remove(x-1);
           }
           for(x=0;x<oRetorno.codigos.length;x++){
               F.la09_i_codigo.add( new Option(oRetorno.exames[x],oRetorno.codigos[x]),null);
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
        F.la21_d_data.value='';
        if((cod!='')&&(cod>0)){        
           
           //Requisição ajax normal
           var oParam      = new Object();
           oParam.exec     = 'DadosExame';
           oParam.la09_i_codigo  = cod;
           js_ajax( oParam, 'js_retornoDadosExame' );
           
       }
  }
  function js_retornoDadosExame(oAjax){
        oRetorno=lab_ajax.monta(oAjax);
        if(oRetorno.status==1){
           F.la08_i_dias.value=oRetorno.dias;
           F.requisitos.value=oRetorno.sRequisitos;
        }else{
           message_ajax(oRetorno.message); 
        }
  }
  function js_calendario(){
         show_calendariolaboratorio('la21_d_data','parent.js_HoraExame(); ',document.form1.la09_i_codigo.value);
  }
  function js_HoraExame(){
      //retorno da função docalendario 
  }  
  function js_envia(){
      if(F.la22_i_cgs.value==''){
         alert('Informe um CGS!');
         return false;
      }
      if(F.la22_c_medico.value==''){
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

      for(x=0;x<tam;x++){
          sStr+=sSep+F.exames.options[x].text;
          if(document.getElementById('urgente'+x).checked==true){
              sUrgente+=sSep+'1';
          }else{
              sUrgente+=sSep+'0';
          }
          sSep='##';
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
      } else {
         sNome='OBSERVACAO';
         sTexto=F.la08_t_observacao.value;
         sCampo='la08_t_observacao';
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
        }else{
            echo"alert('Erro - Nenhum exame vinculado a requisicao!');";
        }
    }

?>


function js_pesquisa(){
  js_OpenJanelaIframe('',
		              'db_iframe_lab_requisicao',
		              'func_lab_requisicao.php'+
		              '?iLaboratorioLogado=<?=$iLaboratorioLogado?>'+
		              '&funcao_js=parent.js_preenchepesquisa|la22_i_codigo',
		              'Pesquisa',
		              true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_requisicao.hide();
  location.href = 'lab2_reemissaoreq001.php?chavepesquisa='+chave;
}

function js_ajax( objParam,jsRetorno ){
  var objAjax = new Ajax.Request(
                         sRPC, 
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


</script>