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
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_jsplibwebseller.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cltrocaserie->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed60_d_datamatricula");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="85%">
 <tr>
   <td colspan="2" width="100%"></td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted101_i_codigo?>">
   <?=@$Led101_i_codigo?>
  </td>
  <td>
   <?db_input('ed101_i_codigo',15,$Ied101_i_codigo,true,'text',3,"")?>
   <?db_input('iMatricula',10,'',true,'hidden',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted101_i_aluno?>">
   <?db_ancora(@$Led101_i_aluno,"js_pesquisaed101_i_aluno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed101_i_aluno',15,$Ied101_i_aluno,true,'text',$db_opcao," onchange='js_pesquisaed101_i_aluno(false);'")?>
   <?db_input('ed47_v_nome',50,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted101_i_turmaorig?>">
   <?db_ancora(@$Led101_i_turmaorig,"",3);?>
  </td>
  <td>
   <?db_input('ed101_i_turmaorig',15,@$Ied101_i_turmaorig,true,'text',3,"")?>
   <?db_input('ed57_c_descrorig',30,@$Ied57_c_descrorig,true,'text',3,'')?>
   <?db_input('ed11_c_origem',30,@$Ied11_c_origem,true,'text',3,'')?>
   <?db_input('ed11_i_codorigem',30,@$Ied11_i_codorigem,true,'hidden',3,'')?>
  </td>
 </tr>
 <tr>
  <td>
   <?=@$Led60_d_datamatricula?>
  </td>
  <td>
   <?db_input('ed60_d_datamatricula',10,@$Ied60_d_datamatricula,true,'text',3,'')?>
   <?db_input('ed52_d_inicio',10,@$Ied52_d_inicio,true,'hidden',3,'')?>
   <?db_input('ed52_d_fim',10,@$Ied52_d_fim,true,'hidden',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted101_i_turmadest?>">
   <?db_ancora(@$Led101_i_turmadest,"js_pesquisaed101_i_turmadest(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed101_i_turmadest',15,@$Ied101_i_turmadest,true,'text',3,'')?>
   <?db_input('ed57_c_descrdest',30,@$Ied57_c_descrdest,true,'text',3,'')?>
   <?db_input('ed11_c_destino',30,@$Ied11_c_destino,true,'text',3,'')?>
   <?db_input('ed11_i_coddestino',30,@$Ied11_i_coddestino,true,'hidden',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted101_t_obs?>">
   <?=@$Led101_t_obs?>
  </td>
  <td>
   <?db_textarea('ed101_t_obs',4,70,@$Ied101_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted101_d_data?>">
   <?=@$Led101_d_data?>
  </td>
  <td>
   <?db_inputdata('ed101_d_data',@$ed101_d_data_dia,@$ed101_d_data_mes,@$ed101_d_data_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <iframe id="iframeImportacao" name="iframeImportacao" src="" width="100%" height="450"
     frameborder="0" style="display: none;"></iframe>
  </td>
 </tr>
</table>

</center>
<br>
</form>
<script>
function js_submit() {
 if (document.form1.ed101_d_data.value=="") {
  alert("Informe a Data da classificação para prosseguir!");
  document.form1.ed101_d_data.focus();
  document.form1.ed101_d_data.style.backgroundColor='#99A9AE';
  return false;
 } else {
  datamat = document.form1.ed60_d_datamatricula.value;
  if (document.form1.ed52_d_inicio.value!="") {
   dataclass = document.form1.ed101_d_data_ano.value+"-"+document.form1.ed101_d_data_mes.value+"-"+document.form1.ed101_d_data_dia.value;
   dataini = document.form1.ed52_d_inicio.value;
   datafim = document.form1.ed52_d_fim.value;
   check = js_validata(dataclass,dataini,datafim);
   if (check==false) {
    data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
    data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
    alert("Data da classificação fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
    document.form1.ed101_d_data.focus();
    document.form1.ed101_d_data.style.backgroundColor='#99A9AE';
    return false;
   }
  }
  if (datamat!="") {
   datamat  = datamat.substr(6,4)+''+datamat.substr(3,2)+''+datamat.substr(0,2);
   dataclass  = dataclass.substr(0,4)+''+dataclass.substr(5,2)+''+dataclass.substr(8,2);
   if (parseInt(datamat)>parseInt(dataclass)) {
    alert("Data da classificação menor que a data da matrícula do aluno!");
    document.form1.ed101_d_data.focus();
    document.form1.ed101_d_data.style.backgroundColor='#99A9AE';
    return false;
   }
  }
 }
 return true;
}
function js_pesquisaed101_i_aluno(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_aluno', 'func_alunoavanco.php?'+
                        'funcao_js=parent.js_mostraaluno1|ed60_i_aluno|ed47_v_nome|'+
                        'ed60_i_turma|ed57_c_descr|ed11_c_descr|ed11_i_codigo|'+
                        'ed60_d_datamatricula|ed52_d_inicio|ed52_d_fim|db_ed60_i_codigo',
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.ed101_i_aluno.value != '') {

      js_OpenJanelaIframe('top.corpo', 'db_iframe_aluno', 'func_alunoavanco.php?pesquisa_chave='+
                          document.form1.ed101_i_aluno.value+'&funcao_js=parent.js_mostraaluno',
                          'Pesquisa', false
                         );

    } else {

      document.form1.ed101_i_aluno.value        = '';
      document.form1.ed101_i_turmaorig.value    = '';
      document.form1.ed47_v_nome.value          = '';
      document.form1.ed57_c_descrorig.value     = '';
      document.form1.ed11_c_origem.value        = '';
      document.form1.ed11_i_codorigem.value     = '';
      document.form1.ed60_d_datamatricula.value = '';
      js_limparTurmaDest();

    }

  }

}

function js_mostraaluno(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, iMatricula, erro) {

  document.form1.ed47_v_nome.value          = chave1
  document.form1.ed101_i_turmaorig.value    = chave2;
  document.form1.ed57_c_descrorig.value     = chave3;
  document.form1.ed11_c_origem.value        = chave4;
  document.form1.ed11_i_codorigem.value     = chave5;
  document.form1.ed60_d_datamatricula.value = chave6.substr(8,2)+"/"+chave6.substr(5,2)+"/"+chave6.substr(0,4);
  document.form1.ed52_d_inicio.value        = chave7;
  document.form1.ed52_d_fim.value           = chave8;
  document.form1.iMatricula.value           = iMatricula;
  document.form1.ed101_i_turmadest.value    = '';
  document.form1.ed57_c_descrdest.value     = '';
  document.form1.ed11_c_destino.value       = '';
  document.form1.ed11_i_coddestino.value    = '';
  if (erro == true) {

    document.form1.ed101_i_aluno.focus();
    document.form1.ed101_i_aluno.value = '';

  }
  js_limparTurmaDest();

}
function js_mostraaluno1(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, iMatricula) {

  document.form1.ed101_i_aluno.value        = chave1;
  document.form1.ed47_v_nome.value          = chave2;
  document.form1.ed101_i_turmaorig.value    = chave3;
  document.form1.ed57_c_descrorig.value     = chave4;
  document.form1.ed11_c_origem.value        = chave5;
  document.form1.ed11_i_codorigem.value     = chave6;
  document.form1.ed60_d_datamatricula.value = chave7.substr(8,2)+"/"+chave7.substr(5,2)+"/"+chave7.substr(0,4);;
  document.form1.ed52_d_inicio.value        = chave8;
  document.form1.ed52_d_fim.value           = chave9;
  document.form1.iMatricula.value           = iMatricula;
  document.form1.ed101_i_turmadest.value    = '';
  document.form1.ed57_c_descrdest.value     = '';
  document.form1.ed11_c_destino.value       = '';
  document.form1.ed11_i_coddestino.value    = '';
  db_iframe_aluno.hide();
  js_limparTurmaDest();

}

function js_limparTurmaDest() {

  document.form1.ed101_i_turmadest.value = '';
  document.form1.ed57_c_descrdest.value  = '';
  document.form1.ed11_c_destino.value    = '';
  document.form1.ed11_i_coddestino.value = '';

}

function js_pesquisaed101_i_turmadest(mostra) {

  if (document.form1.ed101_i_aluno.value == '') {

    alert('Informe o Aluno!');
    document.form1.ed101_i_turmadest.value = '';
    document.form1.ed101_i_aluno.style.backgroundColor='#99A9AE';
    document.form1.ed101_i_aluno.focus();

  } else {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_turma', 'func_turmaavanco.php?aluno='+
                        document.form1.ed47_v_nome.value+'&codaluno='+
                        document.form1.ed101_i_aluno.value+'&turma='+
                        document.form1.ed101_i_turmaorig.value+
                        '&funcao_js=parent.js_mostraturma1|ed57_i_codigo|'+
                        'ed57_c_descr|ed11_i_codigo|ed11_c_descr',
                        'Pesquisa de Turma de Destino', true
                       );

  }

}

function js_mostraturma1(chave1,chave2,chave3,chave4) {

  document.form1.ed101_i_turmadest.value = chave1;
  document.form1.ed57_c_descrdest.value  = chave2;
  document.form1.ed11_i_coddestino.value = chave3;
  document.form1.ed11_c_destino.value    = chave4;
  db_iframe_turma.hide();
  
  var sGet = '';
  sGet    += 'matricula='+document.form1.iMatricula.value;
  sGet    += '&turmaorigem='+document.form1.ed101_i_turmaorig.value+'&turmadestino=';
  sGet    += document.form1.ed101_i_turmadest.value+'&codetapaorigem=';
  sGet    += document.form1.ed11_i_codorigem.value;
  sGet    += '&sTipo=C'; // Classificação
  document.getElementById('iframeImportacao').src           = 'edu4_trocaserieimportacao001.php?'+sGet;
  document.getElementById('iframeImportacao').style.display = ''; // Habilito a visualização

}
</script>