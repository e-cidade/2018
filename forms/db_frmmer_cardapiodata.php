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
$clmer_cardapiodata->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me01_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme13_i_codigo?>">
       <?=@$Lme13_i_codigo?>
    </td>
    <td> 
     <?db_input('me13_i_codigo',5,$Ime13_i_codigo,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme13_d_data?>">
       <?=@$Lme13_d_data?>
    </td>
    <td> 
     <?db_inputdata('me13_d_data',@$me13_d_data_dia,@$me13_d_data_mes,@$me13_d_data_ano,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme13_i_cardapio?>">
       <?db_ancora(@$Lme13_i_cardapio,"js_pesquisame13_i_cardapio(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me13_i_cardapio',5,$Ime13_i_cardapio,true,'text',$db_opcao,
                " onchange='js_pesquisame13_i_cardapio(false);'"
               )
     ?>
     <?db_input('me01_i_codigo',5,$Ime01_i_codigo,true,'text',3,'')?>
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
function js_pesquisame13_i_cardapio(mostra) {
	
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapio',
    	                 'func_mer_cardapio.php?funcao_js=parent.js_mostramer_cardapio1|me01_i_codigo|me01_i_codigo',
    	                 'Pesquisa',true
    	               );
  } else {
	  
    if (document.form1.me13_i_cardapio.value != '') { 
      js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapio',
    	                  'func_mer_cardapio.php?pesquisa_chave='+document.form1.me13_i_cardapio.value+
    	                  '&funcao_js=parent.js_mostramer_cardapio','Pesquisa',false
    	                 );
    } else {
      document.form1.me01_i_codigo.value = ''; 
    }
  }
}

function js_mostramer_cardapio(chave,erro) {
	
  document.form1.me01_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me13_i_cardapio.focus(); 
    document.form1.me13_i_cardapio.value = '';
     
  }  
}

function js_mostramer_cardapio1(chave1,chave2) {
	
  document.form1.me13_i_cardapio.value = chave1;
  document.form1.me01_i_codigo.value   = chave2;
  db_iframe_mer_cardapio.hide();
  
}

function js_pesquisa() { 
  js_OpenJanelaIframe('top.corpo','db_iframe_mer_cardapiodata',
		              'func_mer_cardapiodata.php?funcao_js=parent.js_preenchepesquisa|me13_i_codigo','Pesquisa',true
		             );
}

function js_preenchepesquisa(chave) {
  db_iframe_mer_cardapiodata.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>