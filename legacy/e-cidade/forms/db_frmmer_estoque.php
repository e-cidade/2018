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
$clmer_estoque->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me10_i_codigo");
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("m40_codigo");
$clrotulo->label("ed52_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme18_i_codigo?>">
     <?=@$Lme18_i_codigo?>
    </td>
    <td> 
     <?db_input('me18_i_codigo',5,$Ime18_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme18_f_quant?>">
     <?=@$Lme18_f_quant?>
    </td>
    <td> 
      <?db_input('me18_f_quant',5,$Ime18_f_quant,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme18_f_valor?>">
       <?=@$Lme18_f_valor?>
    </td>
    <td> 
     <?db_input('me18_f_valor',5,$Ime18_f_valor,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <? $me18_i_matrequi=null; ?>
  <tr>
    <td nowrap title="<?=@$Tme18_i_calendario?>">
     <?db_ancora(@$Lme18_i_calendario,"js_pesquisame18_i_calendario(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me18_i_calendario',5,$Ime18_i_calendario,true,'text',$db_opcao," 
                 onchange='js_pesquisame18_i_calendario(false);'")
     ?>
     <?db_input('ed52_i_codigo',20,$Ied52_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme18_i_escola?>">
      <?db_ancora(@$Lme18_i_escola,"js_pesquisame18_i_escola(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me18_i_escola',5,$Ime18_i_escola,true,'text',$db_opcao,
                 " onchange='js_pesquisame18_i_escola(false);'"
               )
     ?>
     <?db_input('ed18_i_codigo',20,$Ied18_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme18_i_codmater?>">
     <?db_ancora(@$Lme18_i_codmater,"js_pesquisame18_i_codmater(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me18_i_codmater',5,$Ime18_i_codmater,true,'text',$db_opcao,
                " onchange='js_pesquisame18_i_codmater(false);'"
               )
     ?>
     <?db_input('me10_i_codigo',5,$Ime10_i_codigo,true,'text',3,'')?>
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
function js_pesquisame18_i_codmater(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_mer_item',
    	                'func_mer_item.php?funcao_js=parent.js_mostramer_item1|me10_i_codigo|me10_i_codigo',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me18_i_codmater.value != '') { 
        
        js_OpenJanelaIframe('top.corpo','db_iframe_mer_item',
                            'func_mer_item.php?pesquisa_chave='+document.form1.me18_i_codmater.value+
                            '&funcao_js=parent.js_mostramer_item','Pesquisa',false
                           );
        
     } else {
       document.form1.me10_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_item(chave,erro) {
	
  document.form1.me10_i_codigo.value = chave; 
  if (erro == true) {
	    
    document.form1.me18_i_codmater.focus(); 
    document.form1.me18_i_codmater.value = '';
     
  }
}

function js_mostramer_item1(chave1,chave2) {
	
  document.form1.me18_i_codmater.value = chave1;
  document.form1.me10_i_codigo.value   = chave2;
  db_iframe_mer_item.hide();
  
}

function js_pesquisame18_i_escola(mostra) {
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_escola',
    	                'func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_i_codigo','Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me18_i_escola.value != '') {
         
      js_OpenJanelaIframe('top.corpo','db_iframe_escola',
    	                  'func_escola.php?pesquisa_chave='+document.form1.me18_i_escola.value+
    	                  '&funcao_js=parent.js_mostraescola','Pesquisa',false
    	                 );
      
    } else {
      document.form1.ed18_i_codigo.value = ''; 
     }
  }
}

function js_mostraescola(chave,erro) {
	
  document.form1.ed18_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me18_i_escola.focus(); 
    document.form1.me18_i_escola.value = '';
     
  }
}

function js_mostraescola1(chave1,chave2) {
	
  document.form1.me18_i_escola.value = chave1;
  document.form1.ed18_i_codigo.value = chave2;
  db_iframe_escola.hide();
  
}

function js_pesquisame18_i_matrequi(mostra){
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_matrequi',
    	                'func_matrequi.php?funcao_js=parent.js_mostramatrequi1|m40_codigo|m40_codigo','Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me18_i_matrequi.value != '') {
         
      js_OpenJanelaIframe('top.corpo','db_iframe_matrequi',
    	                   'func_matrequi.php?pesquisa_chave='+document.form1.me18_i_matrequi.value+
    	                   '&funcao_js=parent.js_mostramatrequi','Pesquisa',false
    	                 );
      
    } else {
      document.form1.m40_codigo.value = ''; 
     }
  }
}

function js_mostramatrequi(chave,erro) {
	
  document.form1.m40_codigo.value = chave; 
  if (erro==true) { 
	  
    document.form1.me18_i_matrequi.focus(); 
    document.form1.me18_i_matrequi.value = '';
     
  }
}

function js_mostramatrequi1(chave1,chave2) {
	
  document.form1.me18_i_matrequi.value = chave1;
  document.form1.m40_codigo.value      = chave2;
  db_iframe_matrequi.hide();
  
}

function js_pesquisame18_i_calendario(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_calendario',
    	                'func_calendario.php?funcao_js=parent.js_mostracalendario1|ed52_i_codigo|ed52_i_codigo',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me18_i_calendario.value != '') {
         
        js_OpenJanelaIframe('top.corpo','db_iframe_calendario',
                            'func_calendario.php?pesquisa_chave='+document.form1.me18_i_calendario.value+
                            '&funcao_js=parent.js_mostracalendario','Pesquisa',false
                           );
        
    } else {
       document.form1.ed52_i_codigo.value = ''; 
     }
  }
}

function js_mostracalendario(chave,erro) {
	
  document.form1.ed52_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me18_i_calendario.focus(); 
    document.form1.me18_i_calendario.value = '';
      
  }
}

function js_mostracalendario1(chave1,chave2) {
	
  document.form1.me18_i_calendario.value = chave1;
  document.form1.ed52_i_codigo.value     = chave2;
  db_iframe_calendario.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('top.corpo','db_iframe_mer_estoque',
		              'func_mer_estoque.php?funcao_js=parent.js_preenchepesquisa|me18_i_codigo','Pesquisa',true
		             );
  
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_estoque.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>