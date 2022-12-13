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

//MODULO: recursoshumanos
$clrhestagioavaliacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h59_sequencial");
$clrotulo->label("h64_data");
$clrotulo->label("h57_regist");
$clrotulo->label("z01_nome");
$clrotulo->label("h59_descr");
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
     <td>
       <fieldset><legend><b>Dados do Avaliado</b></legend> 
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th56_rhestagiocomissao?>">
       <?
       db_ancora(@$Lh56_rhestagiocomissao,"js_pesquisah56_rhestagiocomissao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h56_rhestagiocomissao',10,$Ih56_rhestagiocomissao,true,'text',$db_opcao," onchange='js_pesquisah56_rhestagiocomissao(false);'")
?>
       <?
db_input('h59_descr',40,$Ih59_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
  <tr>
    <td nowrap title="<?=@$Th57_regist?>">
       <?
       db_ancora(@$Lh57_regist,"js_pesquisah57_regist(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('h57_regist',10,$Ih57_regist,true,'text',3,"");
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  <tr>
    <td nowrap title="<?=@$Th56_data?>">
       <?=@$Lh56_data?>
    </td>
    <td> 
<?
db_inputdata('h56_data',@$h56_data_dia,@$h56_data_mes,@$h56_data_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th56_avaliador?>">
       <?
       db_ancora(@$Lh56_avaliador,"js_pesquisaavaliador(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h56_avaliador',10,$Ih56_avaliador,true,'text',$db_opcao,"onchange=js_pesquisaavaliador(false)");
db_input('nomeavaliador',40,$Iz01_nome,true,'text',3,'')
?>
    </td>
  </tr>
  </table>
  </td>
  </tr>
  </fieldset>
  </table>
  </center>
<input name="gravar" type="button" id="gravar" value="Salvar Avaliação" onclick='js_salvarExame()'>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisaDatas();" >
<input name="pesquisar" type="button" id="relatorio" value="Emitir Avaliação" onclick="js_emiteRelatorio();" >
<input type='hidden' id='iCodExame'>
<center>
<table style='width:70%'>
   <tr>
      <td class='table_header'>
         <b id='quesito'>Quesito:</b>
         <select id='quesitos' onChange="js_getDadosExame($F('iCodExame'),this.value)">
         <option value=''>Selecione</option>
         </select>
      </td>
   </tr>   
   <tr>
      <td colspan='2' nowrap>
      <fieldset><legend><b>&nbsp;Questões&nbsp;</b></legend>
      <div style='border:2px inset white;background-color:white'> 
      <table id='response' style='border-collapse:collapse;border:1px solid #CCCCCC;width:100%'>
      
      </table>
      </div>
      <div id='divobsquesito'>
      </div>
      </fieldset>
      </td>
    </tr>  
</table>
</center>
</form>
<script>
function js_pesquisah56_rhestagiocomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhestagiocomissao','func_rhestagiocomissao.php?funcao_js=parent.js_mostrarhestagiocomissao1|h59_sequencial|h59_descr','Pesquisa',true);
  }else{
     if(document.form1.h56_rhestagiocomissao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhestagiocomissao','func_rhestagiocomissao.php?pesquisa_chave='+document.form1.h56_rhestagiocomissao.value+'&funcao_js=parent.js_mostrarhestagiocomissao','Pesquisa',false);
     }else{
       document.form1.h59_descr.value = ''; 
     }
  }
}
function js_mostrarhestagiocomissao(chave,erro){
  document.form1.h59_descr.value = chave; 
  if(erro==true){ 
    document.form1.h56_rhestagiocomissao.focus(); 
    document.form1.h56_rhestagiocomissao.value = ''; 
  }
}
function js_mostrarhestagiocomissao1(chave1,chave2){
  document.form1.h56_rhestagiocomissao.value = chave1;
  document.form1.h59_descr.value = chave2;
  db_iframe_rhestagiocomissao.hide();
}
function js_pesquisah57_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.h57_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.h57_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false,0);
     }else{
       document.form1.rh01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.h57_regist.focus(); 
    document.form1.h57_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.h57_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}

function js_pesquisaavaliador(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostraravaliador1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.h56_avaliador.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.h56_avaliador.value+'&funcao_js=parent.js_mostraravaliador','Pesquisa',false);
     }else{
       document.form1.nomeavaliador.value = ''; 
     }
  }
}
function js_mostraravaliador(chave,erro){
  document.form1.nomeavaliador.value = chave; 
  if(erro==true){ 
    document.form1.h56_avaliador.focus(); 
    document.form1.h56_avalidor.value = ''; 
  }
}

function js_mostraravaliador1(chave1,chave2){
  document.form1.h56_avaliador.value = chave1;
  document.form1.nomeavaliador.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisa(){
   js_pesquisaDatas();
}

function js_preenchepesquisa(chave){
  db_iframe_rhestagioagenda.hide();
  $('iCodExame').value = chave;
  js_getQuesitosExame(chave);
}
function js_pesquisaDatas(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhestagioagenda','func_rhestagioagendadata.php?funcao_js=parent.js_preenchepesquisa|h64_sequencial','Agendamento de Avaliações',true);
}
/*
*** Funçoes ajax
*/
function js_getDadosExame(iCodExame,iCodQuesito){
  
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"getDadosExame","iCodExame":"'+iCodExame+'","iCodQuesito":"'+iCodQuesito+'"}';
   $('response').innerHTML    = '';
   //$('pesquisar').disabled = true;
   url     = 'rec4_rpcexame.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_saida
                              }
                             );

}

function js_saida(oAjax){

    obj                              = eval("("+oAjax.responseText+")");
    $('z01_nome').value              = js_urldecode(obj.z01_nome);
    $('h57_regist').value            = obj.h57_regist;
    $('h56_data').value              = obj.h64_data;
    iTotRespostaDada                 = 0;
    if ($F('h56_rhestagiocomissao') == ''){
      
       $('nomeavaliador').value         = js_urldecode(obj.nomeavaliador);
       $('h56_avaliador').value         = obj.h56_avaliador;
       $('h56_rhestagiocomissao').value = obj.h56_comissao;
       $('h59_descr').value             = js_urldecode(obj.h59_descr);
    }
    saida = '';
    $('response').innerHTML   = '';
    //alert(obj.numnotas);
    if (obj.numquesitos > 0){
      for (i = 0; i < obj.quesitos.length;i++){
       if (obj.quesitos[i].questoes){  
          for (j = 0; j < obj.quesitos[i].questoes.length; j++){
             
             idQuestao = obj.quesitos[i].questoes[j].h53_sequencial;
             saida    += "<tr><td  colspan=2 style='background-color:#EEEEEE'>";
             saida    += "<input class='chkquestoes' type='checkbox' id='questao"+idQuestao+"' style='display:none' value='"+idQuestao+"' checked>";
             saida    += (j+1)+") - <b>"+js_urldecode(obj.quesitos[i].questoes[j].h53_descr)+"</tr>";
             if (obj.quesitos[i].questoes[j].numrespostas > 0){  
                for (x = 0; x < obj.quesitos[i].questoes[j].respostas.length; x++){
                    var resposta = '';
                   if (obj.quesitos[i].questoes[j].respostadada == obj.quesitos[i].questoes[j].respostas[x].h54_sequencial){
                     resposta = " checked ";
                     iTotRespostaDada++;
                   }
                   idResposta = obj.quesitos[i].questoes[j].respostas[x].h54_sequencial;
                   saida     += "<tr id='tr"+idResposta+"'><td style='text-indent:10px;text-align:left;' class='linhagrid'>"+(x+1)+") - "+js_urldecode(obj.quesitos[i].questoes[j].respostas[x].h54_descr)+"</td>";
                   saida     += "<td class='linhagrid'>";
                   saida     += "<input onclick=\"js_salvarRespostas("+idQuestao+",this.value,1)\" "+resposta+" class='input_radio' type='radio' name='rdoquestao"+idQuestao+"' value ='"+idResposta+"' id='iresposta"+idResposta+"'>";
                   saida     += " </td></tr>";
                }
             }
             if (obj.h50_confobs == 2 || obj.h50_confobs == 3){
                saida += "<tr><td>Observações:<br>";
                saida += "<textarea id='obsresposta"+idQuestao+"'cols='100' rows='1'";
                saida += "onblur=\"js_salvarRespostas("+idQuestao+",this.value,2)\">";
                saida += js_urldecode(obj.quesitos[i].questoes[j].obsquestao);
                saida += "</textarea> </td></tr>";
                saida += "<tr><td>Recomendações:<br>";
                saida += "<textarea id='obsrecom"+idQuestao+"'cols='100' rows='1'";
                saida += "onblur=\"js_salvarRespostas("+idQuestao+",this.value,2)\">";
                saida += js_urldecode(obj.quesitos[i].questoes[j].obsrec);
                saida += "</textarea> </td></tr>";
             }
          }
        }else{
           alert('Sem questoes cadastradas para o quesito.');
        }
        if (obj.h50_confobs == 1 || obj.h50_confobs == 3){
           obsquesito  = "<b>Observações:</b>&nbsp;<br>"
           obsquesito += "<textarea id='obsquesito' cols='100' rows='1'";
           obsquesito += "onblur=\"js_salvarRespostas("+obj.quesitos[i].h51_sequencial+",this.value,3)\">";
           obsquesito += js_urldecode(obj.quesitos[i].obs);
           obsquesito += "</textarea><br>";
           obsquesito += "<b>Recomendações:</b>&nbsp;<br>"
           obsquesito += "<textarea id='obsrecomendacao' cols='100' rows='1'";
           obsquesito += "onblur=\"js_salvarRespostas("+obj.quesitos[i].h51_sequencial+",this.value,3)\">";
           obsquesito += js_urldecode(obj.quesitos[i].rec);
           obsquesito += "</textarea>";
           $('divobsquesito').innerHTML = obsquesito;
        }
        if (obj.quesitos[i].questoes){
           if (iTotRespostaDada == obj.quesitos[i].questoes.length){
            $('quesitoOpt'+obj.quesitos[i].h51_sequencial).style.background='#FFFFCC';
           }
         }
      }
    }
    $('response').innerHTML = saida;
    js_removeObj("msgBox");
}
function js_urldecode(str){


  str = str.replace(/\+/g," ");
  str = unescape(str);
  return str;


}
function js_getQuesitosExame(iCodExame){

   strJson = '{"method":"getQuesitosExame","iCodExame":"'+iCodExame+'"}';
   $('quesitos').options.length  = '1';
   //$('pesquisar').disabled = true;
   url     = 'rec4_rpcexame.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_quesitos
                              }
                            );

}
function js_quesitos(oAjax){
    $('quesitos').options.length = 1;
    obj  = eval("("+oAjax.responseText+")");
    iCodExame = $('iCodExame').value;
    for (i = 0; i < obj.quesitos.length;i++){
    
       option    = new Option(js_urldecode(obj.quesitos[i].h51_descr), obj.quesitos[i].h51_sequencial);
       option.id = 'quesitoOpt'+obj.quesitos[i].h51_sequencial;
       if (obj.quesitos[i].totalresp == 1){
          option.style.background='#FFFFCC';
       }
       $('quesitos').add(option,null);   
    }
    $('quesitos').value              = $('quesitos').options[1].value;
    $('z01_nome').value              = '';
    $('h57_regist').value            = '';
    $('h56_data').value              = '';
    $('h56_avaliador').value         = '';
    $('nomeavaliador').value         = '';
    $('h56_rhestagiocomissao').value = '';
    js_getDadosExame(iCodExame, $('quesitos').options[1].value);
}
function js_salvarRespostas(iQuestao,sResposta,iTipo){
 
  var sObsPergunta     = '';
  var sObsRecomendacao = '';
  if (iTipo == 2){
    
     sObsPergunta     = $('obsresposta'+iQuestao).value;
     sObsPergunta     = escape(sObsPergunta); 
     sObsRecomendacao = $('obsrecom'+iQuestao).value;
     sObsRecomendacao = escape(sObsRecomendacao); 
  }else if (iTipo == 3){
    
     sObsPergunta     = $('obsquesito').value;
     sObsPergunta     = escape(sObsPergunta); 
     sObsRecomendacao = $('obsrecomendacao').value;
     sObsRecomendacao = escape(sObsRecomendacao); 
  }
  strJson  = '{"method":"salvarResposta","iCodExame":"'+$F('iCodExame')+'","iCodQuestao":"'+iQuestao+'","';
  strJson += 'iResposta":"'+sResposta+'","iTipo":"'+iTipo+'","sObsPergunta":"'+sObsPergunta+'",';
  strJson += '"sObsRecomendacao":"'+sObsRecomendacao+'"}';

//   $('gravar').disabled = true;
   url     = 'rec4_rpcexame.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_retorno
                              }
                            );

}
function js_retorno(oAjax){

    obj  = eval("("+oAjax.responseText+")");
    $('gravar').disabled    = false;
    $('pesquisar').disabled = false;
    alert(js_urldecode(obj.mensagem));
    if (obj.retorno == 1 && obj.pesquisar == 1){
  
       js_pesquisaDatas();
    }
}
function js_salvarExame(){
   
   strJson =  '{"method":"salvarExame","iCodExame":"'+$F('iCodExame')+'",';
   strJson += '"h56_estagiocomissao":"'+$('h56_rhestagiocomissao').value+'",';
   strJson += '"h56_avaliador":"'+$('h56_avaliador').value+'"}';
   $('pesquisar').disabled = true;
   $('gravar').disabled    = true;
   url     = 'rec4_rpcexame.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_retorno
                              }
                            );
   

}
function js_emiteRelatorio(){
   var iCodExame = $F('iCodExame');
   window.open('rec2_estagioavaliacao002.php?iCodExame='+iCodExame+'&mostraResultado=n','','location=0');
}
js_pesquisaDatas();
</script>