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
$clmer_desperdicio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me01_i_codigo");
$clrotulo->label("me01_c_nome");
$clrotulo->label("me12_d_data");
$clrotulo->label("me03_c_tipo");
if(!isset($me22_i_usuario)){
 $me22_i_usuario = db_getsession("DB_id_usuario");
 $nome = db_getsession("DB_login");
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme22_i_codigo?>">
   <?=@$Lme22_i_codigo?>
  </td>
  <td>
   <?db_input('me22_i_codigo',10,$Ime22_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme22_i_usuario?>">
   <?=@$Lme22_i_usuario?>
  </td>
  <td>
   <?db_input('me22_i_usuario',10,$Ime22_i_usuario,true,'text',3,"");?>
   <?db_input('nome',30,@$nome,true,'text',3,"");?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme22_i_cardapiodiaescola?>">
   <?db_ancora(@$Lme22_i_cardapiodiaescola,"js_pesquisame22_i_cardapiodiaescola(true);",($db_opcao!=1?3:1));?>
  </td>
  <td>
   <?db_input('me22_i_cardapiodiaescola',10,$Ime22_i_cardapiodiaescola,true,'text',3,"")?>
   <?db_input('me01_c_nome',30,$Ime01_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme12_d_data?>">
   <?=@$Lme12_d_data?>
  </td>
  <td>
   <?db_inputdata('me12_d_data',@$me12_d_data_dia,@$me12_d_data_mes,@$me12_d_data_ano,true,'text',3,"")?>
   <?=@$Lme03_c_tipo?>
   <?db_input('me03_c_tipo',30,@$Ime03_c_tipo,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="novo" value="Novo" onclick="js_novo();" >
</center>
</form>
<script>
function js_pesquisame22_i_cardapiodiaescola(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_cardapiodia',
    	                'func_mer_cardapiodia.php?funcao_js=parent.js_mostramer_cardapiodia1|me37_i_codigo|'+
    	                'me01_c_nome|me12_d_data|me03_c_tipo','Pesquisa',true
    	               );
    
  }
}

function js_mostramer_cardapiodia1(chave1,chave2,chave3,chave4) {
	
  document.form1.me22_i_cardapiodiaescola.value = chave1;
  document.form1.me01_c_nome.value        = chave2;
  arr_data                                = chave3.split("-");
  document.form1.me12_d_data.value        = arr_data[2]+"/"+arr_data[1]+"/"+arr_data[0];
  document.form1.me12_d_data_dia.value    = arr_data[2];
  document.form1.me12_d_data_mes.value    = arr_data[1];
  document.form1.me12_d_data_ano.value    = arr_data[0];
  document.form1.me03_c_tipo.value        = chave4;
  db_iframe_mer_cardapiodia.hide();
  
}

function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_mer_desperdicio',
		              'func_mer_desperdicio.php?funcao_js=parent.js_preenchepesquisa|me22_i_codigo','Pesquisa',true
		             );
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_desperdicio.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
 
}

function js_novo() {
  location.href="mer1_mer_desperdicio001.php";
}
</script>