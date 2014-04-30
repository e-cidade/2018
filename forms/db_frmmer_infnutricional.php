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
$clmer_infnutricional->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");
$clrotulo->label("me09_c_descr");
$clrotulo->label("m61_descr");
$clrotulo->label("me08_i_grupoalimento");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme08_i_codigo?>">
   <?=@$Lme08_i_codigo?>
  </td>
  <td>
   <?db_input('me08_i_codigo',10,$Ime08_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme08_i_alimento?>">
   <?db_ancora(@$Lme08_i_alimento,"js_pesquisame08_i_alimento(true);",3);?>
  </td>
  <td>
   <?db_input('me08_i_alimento',10,$Ime08_i_alimento,true,'text',3,
              " onchange='js_pesquisame08_i_alimento(false);'"
             );
   ?>
   <?db_input('me35_c_nomealimento',40,@$Ime35_c_nomealimento,true,'text',3,'');?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme08_i_nutriente?>">
   <?db_ancora(@$Lme08_i_nutriente,"js_pesquisame08_i_nutriente(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me08_i_nutriente',10,$Ime08_i_nutriente,true,'text',$db_opcao,
               " onchange='js_pesquisame08_i_nutriente(false);'"
             )
   ?>
   <?db_input('me09_c_descr',40,$Ime09_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
    <td nowrap title="<?=@$Tme08_f_quant?>">
       <?=@$Lme08_f_quant?>
    </td>
    <td> 
      <?db_input('me08_f_quant',10,$Ime08_f_quant,true,'text',$db_opcao,"")?>    
    </td>
  </tr> 
</table>
</center>
<br>
<center>
<input name="<?=($db_opcao == 1?"incluir":($db_opcao == 2 || $db_opcao == 22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao == 1?"Incluir":($db_opcao == 2 || $db_opcao == 22?"Alterar":"Excluir"))?>" 
       <?=($db_botao == false?"disabled":"")?>>
<input name="Cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_reload();"  
       <?=($db_botao1 == false?"disabled":"")?>>
<br><br>
  <?
  $chavepri= array("me08_i_codigo"=>@$me08_i_codigo, "me08_f_quant"=>@$me08_f_quant, "me08_i_nutriente"=>@$me08_i_nutriente, "me09_c_descr"=>@$me09_c_descr);
  $cliframe_alterar_excluir->chavepri = $chavepri;
  if (isset($me08_i_alimento) && @$me08_i_alimento != "") {
  	$campos  = " me35_c_nomealimento,me09_c_descr,me08_i_nutriente,me08_f_quant,";
  	$campos .= " me08_i_codigo,m61_descr ";
  	
    $cliframe_alterar_excluir->sql = $clmer_infnutricional->sql_query(null,
                                                                      $campos,
                                                                       null,
                                                                       "me08_i_alimento=$me08_i_alimento"
                                                                     );
    $cliframe_alterar_excluir->legenda      = "Informação Nutricional do ìtem $me08_i_alimento - $me35_c_nomealimento";
    
  } else {
    $cliframe_alterar_excluir->legenda      = "Registros";
  }
  $camposiframe                            = " me08_i_codigo,me35_c_nomealimento,me09_c_descr, ";
  $camposiframe                           .= " me08_f_quant,m61_descr ";
  $cliframe_alterar_excluir->campos        =  $camposiframe;
  $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
  $cliframe_alterar_excluir->textocabec    = "darkblue";
  $cliframe_alterar_excluir->textocorpo    = "black";
  $cliframe_alterar_excluir->fundocabec    = "#aacccc";
  $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
  $cliframe_alterar_excluir->iframe_width  = "100%";
  $cliframe_alterar_excluir->iframe_height = "130";
  $cliframe_alterar_excluir->opcoes        = 1;
  $cliframe_alterar_excluir->iframe_alterar_excluir(1);
  ?>  
</form>
<script>
function js_pesquisame08_i_alimento(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_alimento',
    	                'func_mer_alimento.php?funcao_js=parent.js_mostraalimento1|me35_i_codigo|me35_c_nomealimento',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me08_i_alimento.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_mer_alimento',
    	                  'func_mer_alimento.php?pesquisa_chave='+document.form1.me08_i_alimento.value+
    	                  '&funcao_js=parent.js_mostraalimento','Pesquisa',false
    	                 );
    } else {
        
      document.form1.me35_c_nomealimento.value = '';
      location.href = 'mer1_mer_infnutricional001.php';
      
    }
  }
}

function js_mostraalimento(chave,erro) {
	
  document.form1.me35_c_nomealimento.value = chave;
  if (erro == true) {
	  
    document.form1.me08_i_alimento.focus();
    document.form1.me08_i_alimento.value = '';
    location.href = 'mer1_mer_infnutricional001.php';
    
  } else {
    location.href='mer1_mer_infnutricional001.php?me08_i_alimento='+document.form1.me08_i_alimento.value+
                                                                                      '&me35_c_nomealimento='+chave;
  }
}

function js_mostraalimento1(chave1,chave2) {
	
  document.form1.me08_i_alimento.value     = chave1;
  document.form1.me35_c_nomealimento.value = chave2;
  db_iframe_mer_alimento.hide();
  location.href                        ='mer1_mer_infnutricional001.php?me08_i_alimento='+chave1+'&me35_c_nomealimento='+chave2;
  
}

function js_pesquisame08_i_nutriente(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_nutriente',
    	                 'func_mer_nutriente.php?funcao_js=parent.js_mostramer_nutriente1|me09_i_codigo|me09_c_descr',
    	                 'Pesquisa',true
    	               );
    
  } else{
	  
    if (document.form1.me08_i_nutriente.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_mer_nutriente',
    	                  'func_mer_nutriente.php?pesquisa_chave='+document.form1.me08_i_nutriente.value+
    	                  '&funcao_js=parent.js_mostramer_nutriente','Pesquisa',false
    	                 );
      
    } else {
      document.form1.me09_c_descr.value = '';
    }
  }
}

function js_mostramer_nutriente(chave,erro) {
	
  document.form1.me09_c_descr.value = chave;
  if(erro == true) {
	  
    document.form1.me08_i_nutriente.focus();
    document.form1.me08_i_nutriente.value = '';
    
  }
}

function js_mostramer_nutriente1(chave1,chave2) {
	
  document.form1.me08_i_nutriente.value = chave1;
  document.form1.me09_c_descr.value     = chave2;
  db_iframe_mer_nutriente.hide();
  
}

function js_pesquisame08_i_itemund(mostra) {
	
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_matunid',
    	                'func_matunid.php?funcao_js=parent.js_mostramatunid3|m61_codmatunid|m61_descr','Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me08_i_unidade.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_matunid',
    	                  'func_matunid.php?pesquisa_chave='+document.form1.me08_i_itemund.value+
    	                  '&funcao_js=parent.js_mostramatunid2','Pesquisa',false
    	                 );
      
    } else {
      document.form1.m61_descr1.value = '';
    }
  }
}

function js_mostramatunid2(chave,erro) {
	
  document.form1.m61_descr1.value = chave;
  if (erro == true) {
	  
    document.form1.me08_i_itemund.focus();
    document.form1.me08_i_itemund.value = '';
    
  }
}

function js_mostramatunid3(chave1,chave2) {
	
  document.form1.me08_i_itemund.value = chave1;
  document.form1.m61_descr1.value     = chave2;
  db_iframe_matunid.hide();
   
}

function js_reload() {
  location.href = 'mer1_mer_infnutricional001.php?me08_i_alimento='+document.form1.me08_i_alimento.value+
                                                '&me35_c_nomealimento='+document.form1.me35_c_nomealimento.value;
}

function js_pesquisame08_i_grupoalimento(mostra) {
  if (mostra == true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_grupoalimento',
    	                'func_mer_grupoalimento.php?funcao_js=parent.js_mostramer_grupoalimento1|me30_i_codigo|me30_c_desccricao',
    	                'Pesquisa',true
    	               );
    
  } else {
	if (document.form1.me08_i_grupoalimento.value != '') {
		
	  js_OpenJanelaIframe('','db_iframe_mer_grupoalimento',
			              'func_mer_grupoalimento.php?pesquisa_chave='+document.form1.me08_i_grupoalimento.value+
			              '&funcao_js=parent.js_mostramer_grupoalimento','Pesquisa',false
			             );
      
	} else {
	  document.form1.me30_c_descricao.value = '';
	}
  }
}

function js_mostramer_grupoalimento(chave,erro) {
	
  document.form1.me30_c_descricao.value = chave;
  if (erro == true) {
	  
	document.form1.me08_i_grupoalimento.focus();
	document.form1.me08_i_grupoalimento.value = '';
	
  }
}

function js_mostramer_grupoalimento1(chave1,chave2) {
	
  document.form1.me08_i_grupoalimento.value = chave1;
  document.form1.me30_c_descricao.value     = chave2;
  db_iframe_mer_grupoalimento.hide();
  
}
</script>