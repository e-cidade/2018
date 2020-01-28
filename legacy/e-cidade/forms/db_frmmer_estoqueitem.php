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
$clmer_estoqueitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me18_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme19_i_codigo?>">
       <?=@$Lme19_i_codigo?>
    </td>
    <td> 
     <?db_input('me19_i_codigo',5,$Ime19_i_codigo,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme19_f_quant?>">
       <?=@$Lme19_f_quant?>
    </td>
    <td> 
     <?db_input('me19_f_quant',5,$Ime19_f_quant,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme19_f_valor?>">
       <?=@$Lme19_f_valor?>
    </td>
    <td> 
     <?db_input('me19_f_valor',5,$Ime19_f_valor,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme19_f_quantatend?>">
       <?=@$Lme19_f_quantatend?>
    </td>
    <td> 
     <?db_input('me19_f_quantatend',5,$Ime19_f_quantatend,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme19_d_data?>">
       <?=@$Lme19_d_data?>
    </td>
    <td> 
    <?db_inputdata('me19_d_data',@$me19_d_data_dia,@$me19_d_data_mes,@$me19_d_data_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme19_i_merestoque?>">
       <?db_ancora(@$Lme19_i_merestoque,"js_pesquisame19_i_merestoque(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me19_i_merestoque',5,$Ime19_i_merestoque,true,'text',$db_opcao,
                 " onchange='js_pesquisame19_i_merestoque(false);'"
               )
     ?>
     <?db_input('me18_i_codigo',5,$Ime18_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisame19_i_merestoque(mostra){
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_mer_estoque',
    	                'func_mer_estoque.php?funcao_js=parent.js_mostramer_estoque1|me18_i_codigo|me18_i_codigo',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me19_i_merestoque.value != '') {
         
      js_OpenJanelaIframe('top.corpo','db_iframe_mer_estoque',
    	                  'func_mer_estoque.php?pesquisa_chave='+document.form1.me19_i_merestoque.value+
    	                  '&funcao_js=parent.js_mostramer_estoque','Pesquisa',false
    	                 );
      
    } else {
       document.form1.me18_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_estoque(chave,erro) {
	
  document.form1.me18_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me19_i_merestoque.focus(); 
    document.form1.me19_i_merestoque.value = ''; 
  }
}

function js_mostramer_estoque1(chave1,chave2) {
	
  document.form1.me19_i_merestoque.value = chave1;
  document.form1.me18_i_codigo.value     = chave2;
  db_iframe_mer_estoque.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('top.corpo','db_iframe_mer_estoqueitem',
		              'func_mer_estoqueitem.php?funcao_js=parent.js_preenchepesquisa|me19_i_codigo','Pesquisa',true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_estoqueitem.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>