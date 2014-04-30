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
$clmer_requiitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me15_i_codigo");
$clrotulo->label("me10_i_codigo");
$clrotulo->label("me16_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme17_i_codigo?>">
       <?=@$Lme17_i_codigo?>
    </td>
    <td> 
     <?db_input('me17_i_codigo',5,$Ime17_i_codigo,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme17_f_quant?>">
       <?=@$Lme17_f_quant?>
    </td>
    <td> 
      <?db_input('me17_f_quant',4,$Ime17_f_quant,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme17_i_unidade?>">
       <?db_ancora(@$Lme17_i_unidade,"js_pesquisame17_i_unidade(true);",$db_opcao);?>
    </td>
    <td> 
      <?db_input('me17_i_unidade',5,$Ime17_i_unidade,true,'text',$db_opcao,
                 " onchange='js_pesquisame17_i_unidade(false);'"
                )
      ?>
      <?db_input('me15_i_codigo',5,$Ime15_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme17_i_item?>">
       <?db_ancora(@$Lme17_i_item,"js_pesquisame17_i_item(true);",$db_opcao);?>
    </td>
    <td> 
    <?db_input('me17_i_item',5,$Ime17_i_item,true,'text',$db_opcao," onchange='js_pesquisame17_i_item(false);'")?>
    <?db_input('me10_i_codigo',5,$Ime10_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme17_i_merrequi?>">
     <?db_ancora(@$Lme17_i_merrequi,"js_pesquisame17_i_merrequi(true);",$db_opcao);?>
    </td>
    <td> 
    <?db_input('me17_i_merrequi',5,$Ime17_i_merrequi,true,'text',$db_opcao,
               " onchange='js_pesquisame17_i_merrequi(false);'"
              )
    ?>
    <?db_input('me16_i_codigo',5,$Ime16_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisame17_i_unidade(mostra) {
	
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_mer_unidade',
    	                'func_mer_unidade.php?funcao_js=parent.js_mostramer_unidade1|me15_i_codigo|me15_i_codigo',
    	                'Pesquisa',true
    	               );
  } else {
	  
    if (document.form1.me17_i_unidade.value != '') { 
      js_OpenJanelaIframe('top.corpo','db_iframe_mer_unidade',
    	                  'func_mer_unidade.php?pesquisa_chave='+document.form1.me17_i_unidade.value+
    	                  '&funcao_js=parent.js_mostramer_unidade','Pesquisa',false
    	                 );
    } else {
      document.form1.me15_i_codigo.value = ''; 
    }
  }
}

function js_mostramer_unidade(chave,erro) {
	
  document.form1.me15_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me17_i_unidade.focus(); 
    document.form1.me17_i_unidade.value = '';
      
  }
}

function js_mostramer_unidade1(chave1,chave2) {
	
  document.form1.me17_i_unidade.value = chave1;
  document.form1.me15_i_codigo.value  = chave2;
  db_iframe_mer_unidade.hide();
  
}

function js_pesquisame17_i_item(mostra) {
	
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_mer_item',
    	                'func_mer_item.php?funcao_js=parent.js_mostramer_item1|me10_i_codigo|me10_i_codigo',
    	                'Pesquisa',true
    	               );
  } else {
	  
    if (document.form1.me17_i_item.value != '') { 
      js_OpenJanelaIframe('top.corpo','db_iframe_mer_item',
    	                  'func_mer_item.php?pesquisa_chave='+document.form1.me17_i_item.value+
    	                  '&funcao_js=parent.js_mostramer_item','Pesquisa',false
    	                 );
    } else {
      document.form1.me10_i_codigo.value = ''; 
    }
  }
}

function js_mostramer_item(chave,erro) {
	
  document.form1.me10_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me17_i_item.focus(); 
    document.form1.me17_i_item.value = '';
     
  }
}

function js_mostramer_item1(chave1,chave2) {
	
  document.form1.me17_i_item.value   = chave1;
  document.form1.me10_i_codigo.value = chave2;
  db_iframe_mer_item.hide();
  
}

function js_pesquisame17_i_merrequi(mostra) {
	
  if (mostra==true) { 
    js_OpenJanelaIframe('top.corpo','db_iframe_mer_requi',
    	                'func_mer_requi.php?funcao_js=parent.js_mostramer_requi1|me16_i_codigo|me16_i_codigo',
    	                'Pesquisa',true
    	               );
  } else {
	  
    if (document.form1.me17_i_merrequi.value != '') { 
      js_OpenJanelaIframe('top.corpo','db_iframe_mer_requi',
    	                   'func_mer_requi.php?pesquisa_chave='+document.form1.me17_i_merrequi.value+
    	                   '&funcao_js=parent.js_mostramer_requi','Pesquisa',false
    	                  );
    } else {
      document.form1.me16_i_codigo.value = ''; 
    }
  }
}

function js_mostramer_requi(chave,erro) {
	
  document.form1.me16_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me17_i_merrequi.focus(); 
    document.form1.me17_i_merrequi.value = '';
     
  }
}

function js_mostramer_requi1(chave1,chave2) {
	
  document.form1.me17_i_merrequi.value = chave1;
  document.form1.me16_i_codigo.value   = chave2;
  db_iframe_mer_requi.hide();
  
}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo','db_iframe_mer_requiitem',
		              'func_mer_requiitem.php?funcao_js=parent.js_preenchepesquisa|me17_i_codigo','Pesquisa',true
		             );
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_requiitem.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>