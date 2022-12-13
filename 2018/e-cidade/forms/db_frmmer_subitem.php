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
$clmer_subitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me01_c_nome");
$clrotulo->label("m60_descr");
$clrotulo->label("m60_descr");
$db_opcao1=3;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme29_i_codigo?>">
   <?=@$Lme29_i_codigo?>
  </td>
  <td>
  <?db_input('me29_i_codigo',4,$Ime29_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme29_i_refeicao?>">
   <?db_ancora(@$Lme29_i_refeicao,"js_pesquisame29_i_refeicao(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me29_i_refeicao',4,$Ime29_i_refeicao,true,'text',$db_opcao,
              " onchange='js_pesquisame29_i_refeicao(false);'"
             )
   ?>
   <?db_input('me01_c_nome',45,$Ime01_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme29_i_alimentoorig?>">
   <?db_ancora("<b>Alimento</b>","js_pesquisame29_i_alimentoorig(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me29_i_alimentoorig',4,@$Ime29_i_alimentoorig,true,'text',$db_opcao,
              " onchange='js_pesquisame29_i_alimentoorig(false);'"
             )
   ?>
   <?db_input('me35_c_nomealimento',45,@$Ime35_c_nomealimento,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme29_i_alimentonovo?>">
   <?db_ancora(@$Lme29_i_alimentonovo,"js_pesquisame29_i_alimentonovo(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me29_i_alimentonovo',4,$Ime29_i_alimentonovo,true,'text',$db_opcao,
              " onchange='js_pesquisame29_i_alimentonovo(false);'"
             )
   ?>
   <?db_input('me35_c_nomealimento2',45,@$Ime35_c_nomealimento,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme29_f_quantidade?>">
   <?=@$Lme29_f_quantidade?>
  </td>
  <td>
   <?db_input('me29_f_quantidade',8,$Ime29_f_quantidade,true,'text',$db_opcao,"")?>
   <?db_input('unidade',41,"unidade",true,'text',3,"")?> 
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme29_c_medidacaseira?>">
   <?=@$Lme29_c_medidacaseira?>
  </td>
  <td>
   <?db_input('me29_c_medidacaseira',50,$Ime29_c_medidacaseira,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme29_d_inicio?>">
   <?=@$Lme29_d_inicio?>
  </td>
  <td>
   <? if (!isset($me29_d_inicio)) {
        $me29_d_inicio=date("d/m/Y",db_getsession("DB_datausu"));
        $me29_d_inicio_dia=date("d",db_getsession("DB_datausu"));
        $me29_d_inicio_mes=date("m",db_getsession("DB_datausu"));
        $me29_d_inicio_ano=date("Y",db_getsession("DB_datausu"));
      }
      db_inputdatamerenda('me29_d_inicio',@$me29_d_inicio_dia,@$me29_d_inicio_mes,@$me29_d_inicio_ano,true,'text',
                          $db_opcao, "onchange=\"js_validadata(2);\"","",""," parent.js_validadata(2);"
                         );
   ?>
   <b>á</b>
   <?
      db_inputdatamerenda('me29_d_fim',@$me29_d_fim_dia,@$me29_d_fim_mes,@$me29_d_fim_ano,true,'text',$db_opcao,
                          "onchange=\"js_validadata(1);\"","",""," parent.js_validadata(1);"
                         );
    ?>
   <b>Duração:</b><input name="duracao" id="duracao" valor="" type="text" size="2"  disabled><b>dia(s)</b>
    <iframe src="" name="iframe_verificadata" id="iframe_verificadata" width="0" height="0" frameborder="0"></iframe>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme29_t_obs?>">
   <?=@$Lme29_t_obs?>
  </td>
  <td>
   <?db_textarea('me29_t_obs',2,50,$Ime29_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" 
       type="button" 
       id="cancelar" 
       value="Cancelar" <?=($db_opcao==1?"disabled":"")?> 
       onClick='location.href="mer4_mer_subitens001.php?cancelar"'>
<br><br>
<?
  $chavepri= array("me29_i_codigo"=>@$me29_i_codigo);
  $cliframe_alterar_excluir->chavepri=$chavepri;
  $campos2  = " me29_i_codigo,me01_c_nome,me01_f_versao,";
  $campos2 .= " substr(mer_alimento.me35_c_nomealimento,0,30) as me35_c_nomealimento,me29_i_alimentonovo,";
  $campos2 .= " substr(alimento.me35_c_nomealimento,0,30) as me27_c_nome, "; 
  $campos2 .= " me29_f_quantidade,me29_d_inicio,me29_d_fim ";
  $cliframe_alterar_excluir->sql = $clmer_subitem->sql_query("",
                                                             $campos2,
                                                             "me29_d_fim desc",
                                                             ""
                                                            );
  $campos  = " me29_i_codigo,me01_c_nome,me01_f_versao,";
  $campos .= " substr(mer_alimento.me35_c_nomealimento,0,30) as me35_c_nomealimento, ";
  $campos .= " substr(alimento.me35_c_nomealimento,0,30) as me27_c_nome, ";
  $campos .= " me29_f_quantidade,me29_d_inicio,me29_d_fim ";     
  $where   = " me29_d_fim < '".date("Y-m-d",db_getsession("DB_datausu"))."' "; 
  $campos1 = " me29_i_codigo,me01_c_nome,me01_f_versao,me29_i_alimentonovo,";
  $campos1.= " me29_f_quantidade,me29_d_inicio,me29_d_fim ";                                                    
  $cliframe_alterar_excluir->sql_disabled = $clmer_subitem->sql_query("",
                                                                      $campos,
                                                                      "me29_d_fim desc",
                                                                      "$where"
                                                                     );
  $cliframe_alterar_excluir->legenda       = "Registros";
  $cliframe_alterar_excluir->campos        = $campos1;
  $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
  $cliframe_alterar_excluir->textocabec    = "darkblue";
  $cliframe_alterar_excluir->textocorpo    = "black";
  $cliframe_alterar_excluir->fundocabec    = "#aacccc";
  $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
  $cliframe_alterar_excluir->iframe_width  = "100%";
  $cliframe_alterar_excluir->iframe_height = "200";
  $cliframe_alterar_excluir->opcoes        = 1;
  $cliframe_alterar_excluir->iframe_alterar_excluir(1);
?> 
<style>
#duracao{
background-color:#DEB887;
color: black;
}
</style>
</form>
<script>
function js_pesquisame29_i_refeicao(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapio',
    	                 'func_mer_cardapio.php?funcao_js=parent.js_mostramer_cardapio1|me01_i_codigo|me01_c_nome',
    	                 'Pesquisa',true
		               );
    
  } else{
	  
    if (document.form1.me29_i_refeicao.value != '') {
        
      js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapio',
    	                  'func_mer_cardapio.php?pesquisa_chave='+document.form1.me29_i_refeicao.value+
    	                  '&funcao_js=parent.js_mostramer_cardapio','Pesquisa',false
		                 );
      
    } else {
      document.form1.me01_c_nome.value = '';
    }
  }
}

function js_mostramer_cardapio(chave,erro) {
	
  document.form1.me01_c_nome.value = chave;
  if (erro == true) {
	  
    document.form1.me29_i_refeicao.focus();
    document.form1.me29_i_refeicao.value = '';
    
  }
}

function js_mostramer_cardapio1(chave1,chave2) {
	
  document.form1.me29_i_refeicao.value = chave1;
  document.form1.me01_c_nome.value     = chave2;
  db_iframe_mer_cardapio.hide();
  
}

function js_pesquisame29_i_alimentoorig(mostra) {
	
  if (document.form1.me29_i_refeicao.value == '') {
	  
    alert('Selecione uma refeição!');
    document.form1.me29_i_refeicao.focus();
    document.form1.me29_i_alimentoorig.value = '';
    return false;
    
  } else {
    refeicao=document.form1.me29_i_refeicao.value;
  }
  if (mostra == true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_alimento',
    	                'func_mer_alimento.php?refeicao='+refeicao+'&funcao_js=parent.js_mostraalimento1|me35_i_codigo|me35_c_nomealimento',
    	                'Pesquisa',true
		               );
    
  } else {
	  
    if (document.form1.me29_i_alimentoorig.value != '') {
        
      js_OpenJanelaIframe('top.corpo','db_iframe_alimento',
    	                  'func_mer_alimento.php?pesquisa_chave='+document.form1.me29_i_alimentonovo.value+
    	                  '&funcao_js=parent.js_mostraalimento','Pesquisa',false
		                 );
      
    } else {
      document.form1.me35_c_nomealimento.value = '';
    }
  }
}

function js_mostraalimento(chave,erro) {
	
  document.form1.me35_c_nomealimento.value = chave;
  if (erro==true) {
	  
    document.form1.me29_i_alimentoorig.focus();
    document.form1.me29_i_alimentoorig.value = '';
    
  }
}

function js_mostraalimento1(chave1,chave2) {
	
  document.form1.me29_i_alimentoorig.value = chave1;
  document.form1.me35_c_nomealimento.value = chave2;
  db_iframe_alimento.hide();
  
}

function js_pesquisame29_i_alimentonovo(mostra) {
	
  if (document.form1.me29_i_alimentoorig.value == '') {
	  
    alert('Selecione um alimento a ser substituido!');
    document.form1.me29_i_alimentonovo.focus();
    document.form1.me29_i_alimentonovo.value = '';
    return false;
    
  } else {
    item=document.form1.me29_i_alimentonovo.value;
  }
  if (mostra == true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_alimento',
    	                'func_mer_alimento.php?funcao_js=parent.js_mostraalimento3|me35_i_codigo|me35_c_nomealimento',
    	                'Pesquisa',true
		               );
    
  } else {
	  
    if (document.form1.me29_i_alimentonovo.value != '') {
        
      js_OpenJanelaIframe('top.corpo','db_iframe_alimento',
    	                  'func_mer_alimento.php?pesquisa_chave='+document.form1.me29_i_alimentonovo.value+
		                   '&funcao_js=parent.js_mostraalimento2','Pesquisa',false
		                 );
      
    } else {
      document.form1.me35_c_nomealimento.value = '';
  }
 }
}

function js_mostraalimento2(chave,erro) {
	
  document.form1.me35_c_nomealimento2.value = chave;
  if (erro == true) {
	  
    document.form1.me29_i_alimentonovo.focus();
    document.form1.me29_i_alimentonovo.value = '';
    
  } else {
    js_carrega();
  }
}

function js_mostraalimento3(chave1,chave2) {
	
  document.form1.me29_i_alimentonovo.value  = chave1;
  document.form1.me35_c_nomealimento2.value = chave2;
  js_carrega();
  db_iframe_alimento.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('top.corpo','db_iframe_mer_subitem',
		              'func_mer_subitem.php?funcao_js=parent.js_preenchepesquisa|me29_i_codigo','Pesquisa',true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_subitem.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_validadata(op) {

  if (op == 1) {
	  
    if ((document.form1.me29_d_fim.value != false) && (document.form1.me29_d_inicio.value != false)) {

      var vet       = document.form1.me29_d_inicio.value.split("/");
      var ano       = vet[2];
      var mes       = vet[1]-1;
      var dia       = vet[0];
      var inicio    = new Date(ano, mes, dia);
      vet           = document.form1.me29_d_fim.value.split("/");
      ano           = vet[2];
      mes           = vet[1]-1;
      dia           = vet[0];
      var fim       = new Date(ano, mes, dia);
   //calculando diferença
      var dif       = Date.UTC(fim.getYear(),fim.getMonth(),fim.getDate(),0,0,0) - Date.UTC(inicio.getYear(),inicio.getMonth(),inicio.getDate(),0,0,0);
      var diferenca = Math.abs((dif / 1000 / 60 / 60 / 24));
   //retornando data
      if (diferenca > 0) {
    	 document.form1.duracao.value=diferenca;
      } else {
          
    	document.form1.me29_d_fim.value ='';
    	document.form1.duracao.value    ='';
    	document.form1.me29_d_fim.focus();
        
      }
    }
  } else {
	  
    if ((document.form1.me29_d_inicio.value!='') && (document.form1.duracao.value!='')) {
        
   //montando data
      vet        = document.form1.me29_d_inicio.value.split("/");
      ano        = vet[2];
      mes        = vet[1]-1;
      dia        = vet[0];
      var mydate = new Date(ano, mes, dia);
   //somando dia
      duracao    = parseInt(f.duracao.value)-1;
      if (duracao < 0) {
          
    	document.form1.duracao.value    ='';
    	document.form1.me29_d_fim.value ='';
    	document.form1.duracao.focus();
        return false;
        
      }
      mydate.setDate(mydate.getDate()+duracao);
   //retornando data
      dia = mydate.getDate()
      mes = (1+mydate.getMonth())
      if (mes < 10) {
        mes='0'+mes
      }
      if (dia < 10) { 
        dia = '0'+dia
      }
      data = dia+'/'+mes+'/'+(1900+mydate.getYear());
      document.form1.me29_d_fim.value = data;
    }
  }
  if (document.form1.me29_d_fim_ano.value != "" && op == 1) {
	  
    d1 = document.form1.me29_d_fim_dia.value;
    m1 = document.form1.me29_d_fim_mes.value;
    a1 = document.form1.me29_d_fim_ano.value;
    if (d1=="" || m1=="" || a1=="") {
      alert("Preencha todos os campos da data!");
    } else {
        
      dev = parseInt(a1+m1+d1);
      ret = parseInt(document.form1.me29_d_inicio_ano.value+
    	             document.form1.me29_d_inicio_mes.value+
    	             document.form1.me29_d_inicio_dia.value
    	            );
      if (dev < ret) {
          
        alert("Data Final deve ser maior ou igual a Data Inicial!");
        document.form1.me29_d_fim.value = "";
        document.form1.me29_d_fim_dia.value = "";
        document.form1.me29_d_fim_mes.value = "";
        document.form1.me29_d_fim_ano.value = "";
        document.form1.duracao.value="";
        
      } else {
        iframe_verificadata.location = "mer_subitem004.php?campo=me29_d_fim&ano="+a1+"&mes="+m1+"&dia="+d1;
      }
    }
  }
  if (document.form1.me29_d_inicio_ano.value != "" && op == 2) {
	  
    d1 = document.form1.me29_d_inicio_dia.value;
    m1 = document.form1.me29_d_inicio_mes.value;
    a1 = document.form1.me29_d_inicio_ano.value;
    if (d1=="" || m1=="" || a1=="") {
      alert("Preencha todos os campos da data!");
    } else {
      dev = parseInt(a1+m1+d1);
      ret = parseInt(document.form1.me29_d_inicio_ano.value+
    	             document.form1.me29_d_inicio_mes.value+
    	             document.form1.me29_d_inicio_dia.value
    	            );
      if (dev < ret) {
          
        alert("Data Inicial deve ser maior ou igual a Data Final!");
        document.form1.me29_d_inicio.value     = "";
        document.form1.me29_d_inicio_dia.value = "";
        document.form1.me29_d_inicio_mes.value = "";
        document.form1.me29_d_inicio_ano.value = "";
        document.form1.duracao.value="";
        
      } else {
        iframe_verificadata.location = "mer_subitem004.php?campo=me29_d_inicio&ano="+a1+"&mes="+m1+"&dia="+d1;
      }
    }
  }
}

function somadata(dias) {
	
  var dia = "<?=date('d')?>";
  var mes = "<?=date('m')?>";
  var ano = "<?=date('Y')?>";
  var i = dias;
  for (i = 0; i < dias; i++) {
	  
    if (mes == "01" || mes == "03" || mes == "05" || mes == "07" || mes == "08" || mes == "10" || mes == "12") {
        
      if (mes == "12" && dia == "31") {
          
        mes = "01";
        ano++;
        dia = "00";
        
      }
      if (dia == "31" && mes != "12") {
          
        mes++;
        dia = "00";
        
      }
    }
    if (mes == "04" || mes == "06" || mes == "09" || mes == "11") {
        
      if (dia == "30") {
          
        dia =  "00";
        mes++;
        
      }
    }
    if (mes == "02") {
        
      if (ano % 4 == 0) {
          
        if (dia == "29") {
            
          dia = "00";
          mes++;
          
        }
      } else {
          
        if (dia == "28") {
             
          dia = "00";
          mes++;
          
        }
      }
    }
    dia++;
  }
  if (dia == 1) { 
	dia="01"; 
  }
  if (dia == 2) {
	dia="02"; 
  }
  if (dia == 3) {
	 dia="03"; 
  }
  if (dia == 4) { 
	dia="04"; 
  }
  if (dia == 5) { 
	dia="05"; 
  }
  if (dia == 6) { 
	dia="06"; 
  }
  if (dia == 7) { 
	dia="07"; 
  }
  if (dia == 8) { 
	dia="08"; 
  }
  if (dia == 9) { 
	dia="09"; 
  }
  if (mes == 1) { 
	mes="01"; 
  }
  if (mes == 2) { 
	mes="02"; 
  }  
  if (mes == 3) { 
	mes="03"; 
  }
  if (mes == 4) { 
	mes="04"; 
  }
  if (mes == 5) { 
	mes="05"; 
  }
  if (mes == 6) { 
	mes="06"; 
  } 
  if (mes == 7) { 
    mes="07"; 
  }
  if (mes == 8) { 
	mes="08"; 
  }
  if (mes == 9) { 
	mes="09"; 
  }
  document.form1.me29_d_fim.disabled            = false;
  document.form1.me29_d_fim.style.background    = "#FFFFFF";
  document.form1.me29_d_inicio.disabled         = false;
  document.form1.me29_d_inicio.style.background = "#FFFFFF";
  iframe_verificadata.location                  = "mer_subitem004.php?ano="+ano+"&mes="+mes+"&dia="+dia;
  
}
</script>