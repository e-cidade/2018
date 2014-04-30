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

//MODULO: educação
$clalunotransfturma->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed60_i_codigo");
$clrotulo->label("ed60_matricula");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed60_d_datamatricula");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td nowrap title="<?=@$Ted69_i_codigo?>" width="15%">
   <?=@$Led69_i_codigo?>
  </td>
  <td>
   <?db_input('ed69_i_codigo',15,$Ied69_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted69_i_matricula?>">
   <?db_ancora(@$Led69_i_matricula,"js_pesquisaed69_i_matricula(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed60_matricula',   15, $Ied60_matricula,   true, 'text',   $db_opcao, " onchange='js_pesquisaed69_i_matricula(false);'")?>
   <?db_input('ed69_i_matricula', 15, $Ied69_i_matricula, true, 'hidden', 3)?>
   <?db_input('ed47_v_nome',50,@$ed47_v_nome,true,'text',3,'')?>
   <?=@$Led60_d_datamatricula?>
   <?db_input('datamatricula',10,@$datamatricula,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted69_i_turmaorigem?>">
   <?db_ancora(@$Led69_i_turmaorigem,"",3);?>
  </td>
  <td>
   <?db_input('ed69_i_turmaorigem',15,$Ied69_i_turmaorigem,true,'text',3,'')?>
   <?db_input('ed57_c_origem',20,@$Ied57_c_origem,true,'text',3,'')?>
   <?db_input('etapaorigem',20,@$Ietapaorigem,true,'hidden',3,'')?>
   <?db_input('ed11_c_origem',30,@$Ied11_c_origem,true,'text',3,'')?><br>
   <?db_input('ed10_c_origem',40,@$Ied10_c_origem,true,'text',3,'')?>
   <?db_input('ed52_c_origem',20,@$Ied52_c_origem,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted69_i_turmadestino?>">
   <?db_ancora(@$Led69_i_turmadestino,"js_pesquisaed69_i_turmadestino(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed69_i_turmadestino',15,$Ied69_i_turmadestino,true,'text',3," onchange='js_pesquisaed69_i_turmadestino(false);'")?>
   <?db_input('ed57_c_destino',20,@$Ied57_c_destino,true,'text',3,'')?>
   <?db_input('etapadestino',20,@$Ietapadestino,true,'hidden',3,'')?>
   <?db_input('ed11_c_destino',30,@$Ied11_c_destino,true,'text',3,'')?><br>
   <?db_input('ed10_c_destino',40,@$Ied10_c_destino,true,'text',3,'')?>
   <?db_input('ed52_c_destino',20,@$Ied52_c_destino,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <iframe id="iframe_trocaturma" name="iframe_trocaturma" src="" width="100%" height="800" frameborder="0"></iframe>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed69_i_matricula(mostra){
 document.getElementById("iframe_trocaturma").style.visibility = "hidden";
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_matricula',
                      'func_matriculatransf.php?funcao_js=parent.js_mostramatricula1|ed60_i_codigo'
                                                                                  +'|ed47_v_nome'
                                                                                  +'|ed60_i_turma'
                                                                                  +'|ed57_c_descr'
                                                                                  +'|dl_serie'
                                                                                  +'|ed10_c_descr'
                                                                                  +'|dl_calendario'
                                                                                  +'|ed60_d_datamatricula'
                                                                                  +'|etapaorigem'
                                                                                  +'|ed60_matricula',
                      'Pesquisa de Matrículas para Transferência de Turma',
                      true);
 }else{
  if(document.form1.ed60_matricula.value != '') {

   js_OpenJanelaIframe('top.corpo','db_iframe_matricula','func_matriculatransf.php?pesquisa_chave='+document.form1.ed60_matricula.value+'&funcao_js=parent.js_mostramatricula','Pesquisa',false);
  }else{
   document.form1.ed47_v_nome.value = '';
   document.form1.ed69_i_turmaorigem.value = '';
   document.form1.ed57_c_origem.value = '';
   document.form1.etapaorigem.value = '';
   document.form1.ed11_c_origem.value = '';
   document.form1.ed10_c_origem.value = '';
   document.form1.ed52_c_origem.value = '';
   document.form1.datamatricula.value = '';
   document.form1.ed69_i_turmadestino.value = "";
   document.form1.etapadestino.value = "";
   document.form1.ed57_c_destino.value = "";
   document.form1.ed11_c_destino.value = "";
   document.form1.ed10_c_destino.value = "";
   document.form1.ed52_c_destino.value = "";
  }
 }
}
function js_mostramatricula(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,erro, chave9){

  document.form1.ed47_v_nome.value        = chave1
  document.form1.ed69_i_turmaorigem.value = chave2;
  document.form1.ed57_c_origem.value      = chave3;
  document.form1.ed11_c_origem.value      = chave4;
  document.form1.ed10_c_origem.value      = chave5;
  document.form1.ed52_c_origem.value      = chave6;
  document.form1.ed69_i_matricula.value   = chave9
  if(chave7!="") {
    document.form1.datamatricula.value = chave7.substr(8,2)+"/"+chave7.substr(5,2)+"/"+chave7.substr(0,4);
  }else{
    document.form1.datamatricula.value = '';
  }
  document.form1.etapaorigem.value         = chave8;
  document.form1.ed69_i_turmadestino.value = "";
  document.form1.etapadestino.value        = "";
  document.form1.ed57_c_destino.value      = "";
  document.form1.ed11_c_destino.value      = "";
  document.form1.ed10_c_destino.value      = "";
  document.form1.ed52_c_destino.value      = "";
  if(erro==true){
    document.form1.ed60_matricula.focus();
    document.form1.ed69_i_matricula.value = '';
    document.form1.ed60_matricula.value   = '';
  }
}
function js_mostramatricula1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9, chave10){
 document.form1.ed69_i_matricula.value = chave1;
 document.form1.ed47_v_nome.value = chave2;
 document.form1.ed69_i_turmaorigem.value = chave3;
 document.form1.ed57_c_origem.value = chave4;
 document.form1.ed11_c_origem.value = chave5;
 document.form1.ed10_c_origem.value = chave6;
 document.form1.ed52_c_origem.value = chave7;
 document.form1.ed60_matricula.value = chave10;
 if(chave8!=""){
  document.form1.datamatricula.value = chave8.substr(8,2)+"/"+chave8.substr(5,2)+"/"+chave8.substr(0,4);
 }
 document.form1.etapaorigem.value = chave9;
 document.form1.ed69_i_turmadestino.value = "";
 document.form1.etapadestino.value = "";
 document.form1.ed57_c_destino.value = "";
 document.form1.ed11_c_destino.value = "";
 document.form1.ed10_c_destino.value = "";
 document.form1.ed52_c_destino.value = "";
 db_iframe_matricula.hide();
}
function js_pesquisaed69_i_turmadestino(mostra){
 document.getElementById("iframe_trocaturma").style.visibility = "hidden";
 if(document.form1.ed69_i_matricula.value==""){
  alert("Informe a Matrícula!");
  document.form1.ed69_i_turmadestino.value = '';
  document.form1.ed69_i_matricula.style.backgroundColor='#99A9AE';
  document.form1.ed69_i_matricula.focus();
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('top.corpo',
                       'db_iframe_turma',
                       'func_turmatransf.php?turma='+document.form1.ed69_i_turmaorigem.value+'&turmasprogressao=f'+
                       '&etapaorig='+document.form1.etapaorigem.value+'&matricula='+document.form1.ed69_i_matricula.value+
                       '&funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_c_descr|nomeetapa|ed10_c_descr|ed57_i_calendario|codetapa',
                       'Pesquisa de Turmas',true);
  }
 }
}
function js_mostraturma1(chave1,chave2,chave3,chave4,chave5,chave6) {
 document.form1.ed69_i_turmadestino.value = chave1;
 document.form1.ed57_c_destino.value = chave2;
 document.form1.ed11_c_destino.value = chave3;
 document.form1.ed10_c_destino.value = chave4;
 document.form1.ed52_c_destino.value = chave5;
 document.form1.etapadestino.value = chave6;
 db_iframe_turma.hide();
 iframe_trocaturma.location.href = 'edu1_alunotransfturma002.php?matricula='+document.form1.ed69_i_matricula.value+
                                                                '&turmaorigem='+document.form1.ed69_i_turmaorigem.value+
                                                                '&turmadestino='+document.form1.ed69_i_turmadestino.value+
                                                                '&codetapaorigem='+document.form1.etapaorigem.value+
                                                                '&iMatriculaOrigem='+document.form1.ed60_matricula.value;
 document.getElementById("iframe_trocaturma").style.visibility = "visible";
}
</script>