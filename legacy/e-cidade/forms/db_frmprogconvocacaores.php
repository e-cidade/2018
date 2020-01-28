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
$oDaoProgConvocacaoRes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed112_d_datainicio");
$clrotulo->label("ed112_i_progclasse");
$clrotulo->label("ed112_c_situacao");

if ($ed110_i_ptconvocacao == 0 || $ed110_i_ptgeral == 0) {
	
  db_msgbox("Pontuação da Convocação ou Pontuação Geral está com valor zero (Configurações)!");
  $db_opcao  = 3;
  $db_opcao1 = 3;
  $db_botao  = false;
  
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td nowrap title="<?=@$Ted127_i_codigo?>">
   <?=@$Led127_i_codigo?>
  </td>
  <td>
   <?db_input('ed127_i_codigo',10,$Ied127_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted127_i_progmatricula?>">
   <?db_ancora(@$Led127_i_progmatricula,"js_pesquisaed127_i_progmatricula(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed127_i_progmatricula',10,$Ied127_i_progmatricula,true,'hidden',3,"")?>
   <?db_input('ed112_i_rhpessoal',10,@$Ied112_i_rhpessoal,true,'text',3,"")?>
   <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_d_datainicio?>">
   <?=@$Led112_d_datainicio?>
  </td>
  <td>
   <?db_inputdata('ed112_d_datainicio',@$ed112_d_datainicio_dia,
                  @$ed112_d_datainicio_mes,@$ed112_d_datainicio_ano,true,'text',3,"")?>
   <?=@$Led112_i_progclasse?>
   <?db_input('ed107_c_descr',10,@$Ied107_c_descr,true,'text',3,'')?>
   
   <?
    if ($db_opcao != 1) {
      if (@$ed112_c_situacao == "A") {
        $ed112_c_situacao = "ABERTA";
      } elseif (@$ed112_c_situacao == "I") {
        $ed112_c_situacao = "INTERROMPIDA";
      } else {
        $ed112_c_situacao = "ENCERRADA";
      }
      ?>
      <?=@$Led112_c_situacao?>
      <input name="ed112_c_situacao" type="text" value="<?=@$ed112_c_situacao?>" style="background:#DEB887;" readonly>
      
   <?}?>
   
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted127_d_data?>">
   <?=@$Led127_d_data?>
  </td>
  <td>
   <?db_inputdata('ed127_d_data',@$ed127_d_data_dia,@$ed127_d_data_mes,@$ed127_d_data_ano,
                  true,'text',$db_opcao," onchange=\"js_data();\"","","","parent.js_data();","js_data();"
                 )
   ?>
   <?=@$Led127_i_ano?>
   <?db_input('ed127_i_ano',4,$Ied127_i_ano,true,'text',$db_opcao," onChange='js_valida(this.value);'")?>
   <?=@$Led127_i_nconvoca?>
   <?
   $onchange = $db_opcao==1?"":"onchange='js_convocacao(this.value)'";
   db_input('ed127_i_nconvoca',10,$Ied127_i_nconvoca,true,'text',$db_opcao," $onchange")
   ?>
  </td>
 </tr>
 <?if (isset($chavepesquisa)) {?>
 
     <tr>
      <td nowrap title="<?=@$Ted127_i_nparticipa?>">
       <?=@$Led127_i_nparticipa?>
      </td>
      <td>
       <?db_input('ed127_i_nparticipa',10,$Ied127_i_nparticipa,true,'text',3,"")?>
       <?=@$Led127_i_nfaltajust?>
       <?db_input('ed127_i_nfaltajust',10,$Ied127_i_nfaltajust,true,'text',3,"")?>
       <?=@$Led127_i_nfaltanjust?>
       <?db_input('ed127_i_nfaltanjust',10,$Ied127_i_nfaltanjust,true,'text',3,"")?>
      </td>
     </tr>
     
 <?}?>
 <tr>
  <td colspan="2" align="center">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
          type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
           <?=($db_botao==false?"disabled":"")?> >
   <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </td>
 </tr>
 <?if (isset($chavepesquisa) && $db_opcao != 3) {?>
 
     <tr>
      <td colspan="2">
       <table border="0" width="100%">
        <tr>
         <td width="50%">
          <iframe src="edu1_progconvfaltas001.php?ed128_i_progconvres=<?=$chavepesquisa?>" width="350" height="350" 
                  frameborder="0" scrolling="no"></iframe>
         </td>
         <td width="50%">
          <iframe src="edu1_progconvfaltas002.php?ed128_i_progconvres=<?=$chavepesquisa?>" width="350" 
                  height="350" frameborder="0" scrolling="no"></iframe>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     
 <?}?>
  </table>
 </center>
</form>
<script>
function js_pesquisaed127_i_progmatricula(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula',
    	                'func_progmatricula.php?funcao_js=parent.js_mostraprogmatricula1|ed112_i_codigo|'+
    	                'ed112_i_rhpessoal|z01_nome|ed112_d_datainicio|ed107_c_descr','Pesquisa de Matrículas',true);
    
  }
  
}

function js_mostraprogmatricula1(chave1,chave2,chave3,chave4,chave5) {
	
  document.form1.ed127_i_progmatricula.value  = chave1;
  document.form1.ed112_i_rhpessoal.value      = chave2;
  document.form1.z01_nome.value               = chave3;
  document.form1.ed112_d_datainicio_ano.value = chave4.substr(0,4);
  document.form1.ed112_d_datainicio_mes.value = chave4.substr(5,2);
  document.form1.ed112_d_datainicio_dia.value = chave4.substr(8,2);
  document.form1.ed107_c_descr.value          = chave5;
  db_iframe_progmatricula.hide();
  
}

function js_valida(ano) {
	
  if (document.form1.ed127_i_progmatricula.value == "") {
	  
    alert("Informe a Matrícula!");
    document.form1.ed127_i_ano.value = "";
    js_pesquisaed127_i_progmatricula(true);
    
  } else {
	  
    if (ano.length < 4) {
        
      alert("Ano deve ser digitado com 4 dígitos!");
      document.form1.ed127_i_ano.value = "";
      
    } else {
        
      if (document.form1.ed127_i_ano.value < document.form1.ed112_d_datainicio_ano.value) {
          
        alert("Ano Referente deve ser maior ou igual ao ano da Data de Início!");
        document.form1.ed127_i_ano.value = "";
        
      } else if (document.form1.ed127_i_ano.value != document.form1.ed127_d_data_ano.value) {
          
        alert("Ano Referente deve igual ao ano da Data!");
        document.form1.ed127_i_ano.value = "";
        
      }
      
    }
    
  }
  
}

function js_data() {
	
  if (document.form1.ed127_i_progmatricula.value == "") {
	  
    alert("Informe a Matrícula!");
    document.form1.ed127_d_data_dia.value = "";
    document.form1.ed127_d_data_mes.value = "";
    document.form1.ed127_d_data_ano.value = "";
    js_pesquisaed127_i_progmatricula(true);
    
  } else {
	  
    dataini = document.form1.ed112_d_datainicio_ano.value+document.form1.ed112_d_datainicio_mes.value+
              document.form1.ed112_d_datainicio_dia.value;
    data    = document.form1.ed127_d_data_ano.value+document.form1.ed127_d_data_mes.value+
              document.form1.ed127_d_data_dia.value;
    
    if (dataini > data && document.form1.ed127_d_data_dia.value != "" && document.form1.ed127_d_data_mes.value != "" 
        && document.form1.ed127_d_data_ano.value != "") {
        
      alert("Data deve ser maior que a Data de Início na Classe!");
      document.form1.ed127_d_data_dia.value = "";
      document.form1.ed127_d_data_mes.value = "";
      document.form1.ed127_d_data_ano.value = "";
      document.form1.ed127_d_data_dia.focus();
      
    }
    
  }
  
}

function js_convocacao(valor) {
	
  F            = document.form1;
  valor        = parseFloat(valor);
  participacao = parseFloat(F.ed127_i_nparticipa.value);
  faltajust    = parseFloat(F.ed127_i_nfaltajust.value);
  faltanjust   = parseFloat(F.ed127_i_nfaltanjust.value);
  
  if ((faltajust+faltanjust) > valor) {
	  
    alert("N° de Convocações é menor que as faltas registradas!");
    F.ed127_i_nparticipa.value = "";
    F.ed127_i_nconvoca.value   = "";
    
  } else {
    F.ed127_i_nparticipa.value = valor-(faltajust+faltanjust);
  }
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('top.corpo','db_iframe_progconvocacaores',
		              'func_progconvocacaores.php?funcao_js=parent.js_preenchepesquisa|ed127_i_codigo','Pesquisa',true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_progconvocacaores.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
 ?>

}
</script>