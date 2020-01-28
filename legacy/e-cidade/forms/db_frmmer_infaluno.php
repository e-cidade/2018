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

//MODULO: merenda
$clmer_infaluno->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$escola = db_getsession("DB_coddepto");
?>
<!--<form name="form1" method="post" action="" onsubmit="return js_verifica()" > -->
<form name="form1" method="post" action="" > 
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme14_i_codigo?>">
   <?=@$Lme14_i_codigo?>
  </td>
  <td colspan='4'>
   <?db_input('me14_i_codigo',10,$Ime14_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme14_i_aluno?>">
   <?db_ancora("<b>Aluno:</b>","js_pesquisame14_i_aluno(true);",$db_opcao2);?>
  </td>
  <td colspan='4'>
   <?db_input('me14_i_aluno',10,$Ime14_i_aluno,true,'text',3,"")?>
   <?db_input('ed47_v_nome',30,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme14_f_peso?>">
   <?=@$Lme14_f_peso?>
  </td>
  <td colspan='4'>
   <?db_input('me14_f_peso',10,$Ime14_f_peso,true,'text',$db_opcao,"onchange ='js_verifica();'")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme14_f_altura?>">
   <?=@$Lme14_f_altura?>
  </td>
  <td>
   <?db_input('me14_f_altura',10,$Ime14_f_altura,true,'text',$db_opcao,"onchange='js_imc();'")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="idade">
   <b>Idade: </b>
  </td>
  <td>
   <?db_input('idade',10,@$Iidade,true,'text',3,"")?>
  </td>
 </tr>
  <tr>
  <td nowrap title="idade">
   <b>IMC: </b>
  </td>
  <td>
   <?
   if(isset($me14_f_peso) && $me14_f_peso!=""){
   	 $imc = number_format($me14_f_peso/($me14_f_altura*2),2,".",".");
   }
   db_input('imc',10,@$Iimc,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td colspan='4'> 
  <fieldset>   
  <table>
   <tr>
    <td>     
    <b>Competência:</b>
    <?
    if(isset($me14_i_mes) && $me14_i_mes!=""){
     $tipocompetencia = "1";	
    }elseif(isset($me14_i_periodocalendario) && $me14_i_periodocalendario!=""){
     $tipocompetencia = "2";
    }else{
     $tipocompetencia = "";
    }
    $xtipocompetencia = array(""=>"","1"=>"Mês","2"=>"Período de Avaliação");
    db_select("tipocompetencia",$xtipocompetencia,true,$db_opcao," onchange=\"js_Periodo(this.value);\" ");?>          
    </td>
    <td colspan="4" align="center" id="mes" style="display: none;" >
    <b>Meses:</b>
     <?$xme14_i_mes = array( ""=>"",
                             "1"=>"Janeiro",
                            "2"=>"Fevereiro",
                            "3"=>"Março",
                            "4"=>"Abril",
                            "5"=>"Maio",
                            "6"=>"Junho",
                            "7"=>"Julho",
                            "8"=>"Agosto",
                            "9"=>"Setembro",
                           "10"=>"Outubro",
                           "11"=>"Novembro",
                           "12"=>"Dezembro"                           
                          );
       db_select("me14_i_mes",$xme14_i_mes,true,$db_opcao,"");?>      
    </td>    
    <td colspan="4" align="center" id="ano" style="display: none;" >
    <b>Ano:</b>
     <?$xme14_i_ano = array(""=>"",date('Y')=>date('Y'),
                           date('Y')-1=>date('Y')-1                                                     
                          );
       db_select("me14_i_ano",$xme14_i_ano,true,$db_opcao,"");?>      
      </td>    
      <td align="center" id="calendario2" style="display: none;" >
        <b>Calendário:</b>
          <?
           $campos     = "ed52_i_codigo as cod_cal,ed52_c_descr as descr_cal,ed52_i_ano as ano_cal";
           $result_cal = $clcalendario->sql_record($clcalendario->sql_query_calturma("",
                                                                                     $campos,
                                                                                     "ed52_i_ano DESC",
                                                                                     "ed38_i_escola = $escola 
                                                                                      AND ed52_c_passivo = 'N'"
                                                                                    ));
          ?>
        <select name="calendario" id="calendario" onChange="js_calendario(this.value);" 
                style="height:18px;font-size:10px;">
         <option value=""></option>
          <?for ($t = 0; $t < $clcalendario->numrows; $t++) {
        
               db_fieldsmemory($result_cal,$t);
           ?>
               <option value="<?=$cod_cal?>" <?=@$ed52_i_codigo==$cod_cal?"selected":""?> ><?=$descr_cal?></option>
      
           <?}?>
       </select>
      </td>        
      <td style="display: none;" id="periodo">
         <b>Períodos de Avaliação:</b>
       <select name="periodoavaliacao" id="periodoavaliacao" style="width:130px;height:18px;font-size:10px;" <?=isset($me14_i_periodocalendario)&&$me14_i_periodocalendario!=""?"":"disabled"?>>
       <?if(isset($me14_i_periodocalendario) && $me14_i_periodocalendario!=""){
         $result_per = $clperiodocalendario->sql_record($clperiodocalendario->sql_query("",
                                                                                 "ed53_i_codigo as cod_per,ed09_c_descr as descr_per",
                                                                                 "ed09_c_descr",
                                                                                 "ed53_i_calendario=$ed52_i_codigo"
                                                                                 ));?>
          <option value="" ></option>
          <?for ($t = 0; $t < $clperiodocalendario->numrows; $t++) {
        
               db_fieldsmemory($result_per,$t);
           ?>
               <option value="<?=$cod_per?>" <?=@$ed53_i_codigo==$cod_per?"selected":""?> ><?=$descr_per?></option>
      
           <?}?>
                                                                                 
       	
       	?>
       <?}?>
       </select>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="Cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_reload();"  
       <?=($db_botao1==false?"disabled":"")?>>
</center>
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array( "me14_i_codigo"=>@$me14_i_codigo,
                     "ed47_v_nome"=>@$ed47_v_nome,
                     "me14_f_peso"=>@$me14_f_peso,
                     "me14_f_altura"=>@$me14_f_altura,
                     "me14_i_mes"=>@$me14_i_mes,
                     "me14_i_ano"=>@$me14_i_ano,
                     "me14_i_periodocalendario"=>@$me14_i_periodocalendario
                     
                   );
   $cliframe_alterar_excluir->chavepri      = $chavepri;   
   if (isset($me14_i_aluno) && @$me14_i_aluno != "") {
   	 $campos = "me14_i_codigo,ed47_v_nome,me14_f_altura,me14_f_peso,me14_i_mes,me14_i_ano,me14_i_periodocalendario";   	
     $cliframe_alterar_excluir->sql = $clmer_infaluno->sql_query(null,
                                                                 $campos,
                                                                 null,
                                                                 "me14_i_aluno=$me14_i_aluno"
                                                                );
    
 }
   $campos                                  = " me14_i_codigo,ed47_v_nome,me14_f_altura,me14_f_peso,me14_i_mes,";
   $campos                                 .= " me14_i_ano,me14_i_periodocalendario";
   $cliframe_alterar_excluir->campos        = $campos;
   $cliframe_alterar_excluir->legenda       = "Registros";
   $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec    = "#DEB887";
   $cliframe_alterar_excluir->textocorpo    = "#444444";
   $cliframe_alterar_excluir->fundocabec    = "#444444";
   $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
   $cliframe_alterar_excluir->iframe_height = "160";
   $cliframe_alterar_excluir->iframe_width  = "100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario    = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
<script>

function js_calendario(calendario) {
    
	  $('periodoavaliacao').innerHTML = "";
	  $('periodoavaliacao').disabled  = true;	  
	  if (calendario=="") {
	     return false;
	  }
	  var sAction = 'PesquisaPeriodo';
	  var url     = 'mer4_mer_infalunoRPC.php';
	  var oAjax = new Ajax.Request(url,
	                                  { 
	                                    method    : 'post',
	                                    parameters: 'calendario='+calendario+'&sAction='+sAction,
	                                    onComplete: js_retornoPesquisaPeriodo
	                                  }
	                                 );
	    
	}

function js_retornoPesquisaPeriodo(oAjax) {
    
	  var oRetorno = eval("("+oAjax.responseText+")");
	  sHtml = '';
	  if(oRetorno.length==0) {
	    sHtml += '<option value="">Nenhum Período de Avaliação foi vinculado ao calendário selecionado!</option>';
	  } else {
	      
	    sHtml += '<option value=""></option>';
	    for (var i = 0;i < oRetorno.length; i++) {
	        
	      with (oRetorno[i]) {
	        sHtml += '  <option value="'+ed53_i_codigo.urlDecode()+'">'+ed09_c_descr.urlDecode()+'</option>';
	      }      
	    }    
	  }
	  $('periodo').style.display          = ''; 
	  $('periodoavaliacao').style.display = ''; 
	  $('periodoavaliacao').innerHTML     = sHtml;
	  $('periodoavaliacao').disabled      = false;
}

function js_Periodo(valor) {

  if (valor == '1') {
	document.getElementById('mes').style.display              = '';
	document.getElementById('ano').style.display              = '';  
	document.getElementById('periodo').style.display          = 'none';
    document.getElementById('calendario2').style.display = 'none';
    document.form1.periodoavaliacao.value = '';
    document.form1.calendario.value = '';
  } else if (valor == '2') {
	document.getElementById('mes').style.display = 'none';
	document.getElementById('ano').style.display = 'none';
    document.getElementById('periodo').style.display          = '';
    document.getElementById('calendario2').style.display = '';
    document.form1.me14_i_mes.value = '';
    document.form1.me14_i_ano.value = '';
	
  }else{
	  
    document.getElementById('mes').style.display              = 'none';
    document.getElementById('ano').style.display              = 'none';  
    document.getElementById('periodo').style.display          = 'none';
    document.getElementById('calendario2').style.display = 'none';
    document.form1.periodoavaliacao.value = '';
    document.form1.calendario.value = '';
    document.form1.me14_i_mes.value = '';
    document.form1.me14_i_ano.value = '';

  }
  if (valor == '2') {
	  document.getElementById('calendario2').style.display = ''; 
	  } else {
	    document.getElementById('calendario2').style.display = 'none';
	  }
}

function js_verifica() {
	
  peso   = document.form1.me14_f_peso.value;
  if ((peso<0) || (peso>200)) {
	        
    alert('Peso invalido!');
    document.form1.me14_f_peso.value='';
    document.form1.me14_f_peso.focus();
    return false;
    
  }
   return true;
}

function js_pesquisame14_i_aluno(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_aluno',
    	                'func_mer_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome|idade',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me14_i_aluno.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_aluno',
    	                  'func_mer_aluno.php?pesquisa_chave2='+document.form1.me14_i_aluno.value+
    	                  '&funcao_js=parent.js_mostraaluno','Pesquisa',false
    	                 );
      
    } else {
        
        document.form1.ed47_v_nome.value = '';
        location.href='mer1_mer_infaluno001.php';
        
      }
  }
}

function js_mostraaluno(chave,erro) {
	
  document.form1.ed47_v_nome.value = chave;
  if (erro == true) {
	  
    document.form1.me14_i_aluno.focus();
    document.form1.me14_i_aluno.value = '';
    location.href='mer1_mer_infaluno001.php';
    
  } else {
    location.href='mer1_mer_infaluno001.php?me14_i_aluno='+document.form1.me14_i_aluno.value+
                                                                                      '&ed47_v_nome='+chave;
  }

}

function js_mostraaluno1(chave1,chave2,chave3) {
	
  document.form1.me14_i_aluno.value = chave1;
  document.form1.ed47_v_nome.value  = chave2;
  document.form1.idade.value        = chave3;
  db_iframe_aluno.hide();
  location.href                     ='mer1_mer_infaluno001.php?me14_i_aluno='+chave1+'&ed47_v_nome='+chave2+
                                     '&idade='+chave3;
  
}

function js_pesquisa() {
	
 js_OpenJanelaIframe('','db_iframe_mer_infaluno',
		             'func_mer_infaluno.php?funcao_js=parent.js_preenchepesquisa|me14_i_codigo','Pesquisa',true
		            );
 
}

function js_reload() {
	  location.href='mer1_mer_infaluno001.php?me14_i_aluno='+document.form1.me14_i_aluno.value+
	                                                '&ed47_v_nome='+document.form1.ed47_v_nome.value+
	                                                '&idade='+document.form1.idade.value;
}
function js_imc() {

  if ((document.form1.me14_f_altura.value>3) || (document.form1.me14_f_altura.value<0)) {
	      
	alert('Altura invalida!');
	document.form1.me14_f_altura.value='';
	document.form1.me14_f_altura.focus();
	return false;
	    
  }
  variavel = parseFloat(document.form1.me14_f_altura.value*document.form1.me14_f_altura.value);
  imc      = Math.round(document.form1.me14_f_peso.value/variavel,2);  
  document.form1.imc.value = imc;
}

</script>