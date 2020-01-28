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

//MODULO: educação
$oDaoAtestVaga->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed31_i_codigo");
$clrotulo->label("ed52_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed15_i_codigo");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed52_d_inicio");
$clrotulo->label("ed52_d_fim");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted102_i_codigo?>">
   <?=@$Led102_i_codigo?>
  </td>
  <td>
   <?db_input('ed102_i_codigo', 15, $Ied102_i_codigo, true, 'text', 3, "")?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <fieldset style="width:93%"><legend><b>Situação Atual</b></legend>
   <table>
    <tr>
     <td nowrap title="<?=@$Ted102_i_aluno?>">
      <?db_ancora(@$Led102_i_aluno, "", 3);?>
     </td>
     <td>
      <?db_input('ed102_i_aluno', 15, $Ied102_i_aluno, true, 'text', 3, "")?>
      <?db_input('ed47_v_nome', 40, @$Ied47_v_nome, true, 'text', 3, "")?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Escola Atual:</b>
     </td>
     <td>
      <?db_input('codigoescola', 15, @$codigoescola, true, 'text', 3, '')?>
      <?db_input('nomeescola', 40, @$nomeescola, true, 'text', 3, '')?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Curso:</b>
     </td>
     <td>
      <?db_input('codigocurso', 15, @$codigocurso, true, 'text', 3, '')?>
      <?db_input('nomecurso', 40, @$nomecurso, true, 'text', 3, '')?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Situação:</b>
     </td>
     <td>
      <?db_input('situacao', 20, @$situacao, true, 'text', 3, '')?>
      <b>Data Matrícula:</b>
      <?db_input('datamatricula', 10, @$situacao, true, 'text', 3, '')?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Etapa:</b>
     </td>
     <td>
      <?db_input('codigoserie', 15, @$codigoserie, true, 'text', 3, '')?>
      <?db_input('nomeserie', 40, @$nomeserie, true, 'text', 3, '')?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Ano:</b>
     </td>
     <td>
      <?db_input('anocal', 15, @$anocal, true, 'text', 3, '')?>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted102_i_escola?>">
   <?db_ancora(@$Led102_i_escola, "", 3);?>
  </td>
  <td>
   <?db_input('ed102_i_escola', 15, $Ied102_i_escola, true, 'text', 3, "")?>
   <?db_input('ed18_c_nome', 50, @$Ied18_c_nome, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted102_i_base?>">
   <?db_ancora(@$Led102_i_base, "", 3);?>
  </td>
  <td>
   <?db_input('ed102_i_base', 15, $Ied102_i_base, true, 'text', 3, "")?>
   <?db_input('ed31_c_descr', 50, @$Ied31_c_descr, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="Curso">
   <b>Curso:</b>
  </td>
  <?
  if($db_opcao==3 || $db_opcao==2){
   ?>
   <td>
    <?db_input('ed29_i_codigo', 15, @$Ied29_i_codigo, true, 'text', 3, '')?>
    <?db_input('ed29_c_descr', 50, @$Ied29_c_descr, true, 'text', 3, '')?>
   </td>
   <?
  }else{
   ?>
   <td>
    <?db_input('codcursodest', 15, @$Icodcursodest, true, 'text', 3, '')?>
    <?db_input('nomecursodest', 50, @$Inomecursodest, true, 'text', 3, '')?>
   </td>
   <?
  }
  ?>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted34_i_serie?>">
   <?db_ancora("<b>Serie</b>", "", 3);?>
  </td>
  <td>
   <?db_input('ed102_i_serie', 15, @$Ied102_i_serie, true, 'text', 3, '')?>
   <?db_input('ed11_c_descr', 50, @$Ied11_c_descr, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted102_i_turno?>">
   <?db_ancora(@$Led102_i_turno, "", 3);?>
  </td>
  <td>
   <?db_input('ed102_i_turno', 15, $Ied102_i_turno, true, 'text', 3, '')?>
   <?db_input('ed15_c_nome', 50, @$Ied15_c_nome, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted102_i_calendario?>">
   <?db_ancora(@$Led102_i_calendario, "js_pesquisaed102_i_calendario(true);", $db_opcao);?>
  </td>
  <td>
   <?db_input('ed102_i_calendario', 15, $Ied102_i_calendario, true, 'text', 3, "")?>
   <?db_input('ed52_c_descr', 50, @$Ied52_c_descr, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td>
   <?=@$Led52_d_inicio?>
  </td>
  <td>
   <?db_input('ed52_d_inicio', 10, @$Ied52_d_inicio, true, 'text', 3, '')?>
   <?=@$Led52_d_fim?>
   <?db_input('ed52_d_fim', 10, @$Ied52_d_fim, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted102_d_data?>">
   <?=@$Led102_d_data?>
  </td>
  <td>
   <?db_inputdata('ed102_d_data', @$ed102_d_data_dia, @$ed102_d_data_mes, @$ed102_d_data_ano, true, 'text', $db_opcao)?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted102_t_obs?>">
   <?=@$Led102_t_obs?>
  </td>
  <td>
   <?db_textarea('ed102_t_obs', 4, 54, $Ied102_t_obs, true, 'text', $db_opcao, "")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==2?"onclick='return js_submit();'":"")?>  
       <?=isset($alterar)?"style='visibility:hidden;'":""?>>
</form>
<script>
function js_pesquisaed102_i_calendario(mostra) {
	
  if (document.form1.ed102_i_aluno.value == "") {
	  
    alert("Informe o aluno!");
    js_OpenJanelaIframe('', 'db_iframe_aluno', 'func_alunoatest.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|'+
    	                'ed47_v_nome|dl_codigoescola|dl_escola|dl_codigocurso|dl_curso|dl_codigoserie|'+
    	                'dl_serie|ed56_c_situacao', 'Pesquisa de Alunos', true
    	               );
    
  } else {
	  
    if (mostra == true) {
        
      js_OpenJanelaIframe('', 'db_iframe_calendario', 
    	                  'func_calendarioatest.php?anocal='+document.form1.ed102_i_aluno.value+
    	                  '&funcao_js=parent.js_mostracalendario1|ed52_i_codigo|ed52_c_descr|ed52_i_ano|'+
    	                  'ed52_d_inicio|ed52_d_fim', 'Pesquisa de Calendários', true
    	                 );
      
    }
    
  }
  
}

function js_mostracalendario1(chave1, chave2, chave3, chave4, chave5) {
	
  document.form1.ed102_i_calendario.value = chave1;
  document.form1.ed52_c_descr.value       = chave2;
  document.form1.ed52_d_inicio.value      = chave4.substr(8, 2)+"/"+chave4.substr(5, 2)+"/"+chave4.substr(0, 4);
  document.form1.ed52_d_fim.value         = chave5.substr(8, 2)+"/"+chave5.substr(5, 2)+"/"+chave5.substr(0, 4);
  db_iframe_calendario.hide();
  
}

function js_submit() {
	
  if (document.form1.ed102_d_data.value == "") {
	  
    alert("Informe a Data do Atestado para prosseguir!");
    document.form1.ed102_d_data.focus();
    document.form1.ed102_d_data.style.backgroundColor = '#99A9AE';
    return false;
    
  } else {
	  
    datamat   = document.form1.datamatricula.value;
    dataatest = document.form1.ed102_d_data_ano.value+"-"+document.form1.ed102_d_data_mes.value+
                "-"+document.form1.ed102_d_data_dia.value;
    
    if (document.form1.ed52_d_inicio.value != "") {
        
      dataini = document.form1.ed52_d_inicio.value.substr(6, 4)+"-"+document.form1.ed52_d_inicio.value.substr(3, 2)+
                "-"+document.form1.ed52_d_inicio.value.substr(0, 2);
      datafim = document.form1.ed52_d_fim.value.substr(6, 4)+"-"+document.form1.ed52_d_fim.value.substr(3, 2)+
                "-"+document.form1.ed52_d_fim.value.substr(0, 2);
      check   = js_validata(dataatest, dataini, datafim);
      
      if (check == false) {
          
        data_ini = dataini.substr(8, 2)+"/"+dataini.substr(5, 2)+"/"+dataini.substr(0, 4);
        data_fim = datafim.substr(8, 2)+"/"+datafim.substr(5, 2)+"/"+datafim.substr(0, 4);
        alert("Data do Atestado fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
        document.form1.ed102_d_data.focus();
        document.form1.ed102_d_data.style.backgroundColor='#99A9AE';
        return false;
        
      }
      
    }
    
    if (datamat != "") {
        
      datamat   = datamat.substr(6, 4)+''+datamat.substr(3, 2)+''+datamat.substr(0, 2);
      dataatest = dataatest.substr(0, 4)+''+dataatest.substr(5, 2)+''+dataatest.substr(8, 2);
      
      if (parseInt(datamat) > parseInt(dataatest)) {
          
        alert("Data do Atestado menor que a data da matrícula do aluno!");
        document.form1.ed102_d_data.focus();
        document.form1.ed102_d_data.style.backgroundColor='#99A9AE';
        return false;
        
      }
      
    }
    
  }
  
  document.form1.db_opcao.style.visibility = "hidden";
  return true;
  
}

</script>