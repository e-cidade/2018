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
$clmer_item->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me15_i_codigo");
$clrotulo->label("me20_i_unidade");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme10_i_codigo?>">
       <?=@$Lme10_i_codigo?>
    </td>
    <td> 
    <?db_input('me10_i_codigo',10,$Ime10_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme10_c_descr?>">
       <?=@$Lme10_c_descr?>
    </td>
    <td> 
    <?db_input('me10_c_descr',48,$Ime10_c_descr,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme10_i_unidade?>">
     <?db_ancora(@$Lme10_i_unidade,"js_pesquisame10_i_unidade(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me10_i_unidade',10,$Ime10_i_unidade,true,'text',$db_opcao,
                 " onchange='js_pesquisame10_i_unidade(false);'"
               )
     ?>
     <?db_input('me15_c_descr',35,@$Ime15_c_descr,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme20_i_unidade?>">
      <?db_ancora(@$Lme20_i_unidade,"js_pesquisame20_i_unidade(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me20_i_unidade',10,@$Ime20_i_unidade,true,'text',$db_opcao,
                " onchange='js_pesquisame20_i_unidade(false);'"
               )
     ?>
     <?db_input('me15_c_descrunid',35,@$Ime15_c_descrunid,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme10_c_controlavalidade?>">
       <?=@$Lme10_c_controlavalidade?>
    </td>
    <td> 
     <?
       if (!isset($me10_c_controlavalidade)) {
         $me10_c_controlavalidade = 3;
       }
       db_select('me10_c_controlavalidade',getValoresPadroesCampo("me10_c_controlavalidade"),true,$db_opcao,"");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme10_c_ativo?>">
       <?=@$Lme10_c_ativo?>
    </td>
    <td> 
   <?     
     $xx = array("t"=>"SIM","f"=>"NAO");
     db_select('me10_c_ativo',$xx,true,$db_opcao,"");
   ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisame10_i_unidade(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_unidade',
    	                'func_mer_unidade.php?funcao_js=parent.js_mostramer_unidade1|me15_i_codigo|me15_c_descr',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me10_i_unidade.value != '') {
         
      js_OpenJanelaIframe('','db_iframe_mer_unidade',
    	                  'func_mer_unidade.php?pesquisa_chave='+document.form1.me10_i_unidade.value+
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
	   
    document.form1.me10_i_unidade.focus(); 
    document.form1.me10_i_unidade.value = '';
     
  }
}

function js_mostramer_unidade1(chave1,chave2) {
	
  document.form1.me10_i_unidade.value = chave1;
  document.form1.me15_c_descr.value = chave2;
  db_iframe_mer_unidade.hide();
  
}

function js_pesquisame20_i_unidade(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_mer_unidade',
    	                'func_mer_unidade.php?funcao_js=parent.js_mostramer_unidade2|me15_i_codigo|me15_c_descr',
    	                'Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me20_i_unidade.value != '') {
         
      js_OpenJanelaIframe('','db_iframe_mer_unidade',
    	                  'func_mer_unidade.php?pesquisa_chave='+document.form1.me20_i_unidade.value+
    	                  '&funcao_js=parent.js_mostramer_unidade3','Pesquisa',false
    	                 );
      
    } else {
      document.form1.me20_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_unidade3(chave,erro) {
	
  document.form1.me15_i_codigo.value = chave; 
  if (erro==true) {
	   
    document.form1.me20_i_unidade.focus(); 
    document.form1.me20_i_unidade.value = '';
     
  }
}

function js_mostramer_unidade2(chave1,chave2) {
	
  document.form1.me20_i_unidade.value = chave1;
  document.form1.me15_c_descrunid.value = chave2;
  db_iframe_mer_unidade.hide();
  
}

function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_mer_item','func_mer_item.php?funcao_js=parent.js_preenchepesquisa|me10_i_codigo',
		              'Pesquisa',true
		             );
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_item.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>