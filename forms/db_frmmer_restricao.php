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
$clmer_restricao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme24_i_codigo?>">
   <?=@$Lme24_i_codigo?>
  </td>
  <td>
   <?db_input('me24_i_codigo',10,$Ime24_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme24_i_aluno?>">
   <?db_ancora(@$Lme24_i_aluno,"js_pesquisame24_i_aluno(true);",($db_opcao!=1?3:1));?>
  </td>
  <td>
   <?db_input('me24_i_aluno',10,$Ime24_i_aluno,true,'text',($db_opcao!=1?3:1),
               "onchange='js_pesquisame24_i_aluno(false);'"
             )
   ?>
   <?db_input('ed47_v_nome',40,$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="novo" value="Novo" onclick="js_novo();">
</center>
</form>
<script>
function js_pesquisame24_i_aluno(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_aluno',
    	                'func_mer_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_v_nome','Pesquisa',true
    	               );
  } else {
	  
    if (document.form1.me24_i_aluno.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_aluno',
    	                  'func_mer_aluno.php?pesquisa_chave2='+document.form1.me24_i_aluno.value+
    	                  '&funcao_js=parent.js_mostraaluno','Pesquisa',false
    	                 );
    } else {
      document.form1.ed47_v_nome.value = '';
    }
  }
}

function js_mostraaluno(chave,erro){
	
  document.form1.ed47_v_nome.value = chave;
  if (erro==true) {
	  
    document.form1.me24_i_aluno.focus();
    document.form1.me24_i_aluno.value = '';
    
  }
}

function js_mostraaluno1(chave1,chave2) {
	
  document.form1.me24_i_aluno.value = chave1;
  document.form1.ed47_v_nome.value = chave2;
  db_iframe_aluno.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('','db_iframe_mer_restricao',
		              'func_mer_restricao.php?funcao_js=parent.js_preenchepesquisa|me24_i_codigo','Pesquisa',true
		             );
}

function js_preenchepesquisa(chave) {
	
  db_iframe_mer_restricao.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
 ?>
 
}
function js_novo() {
  location.href='mer1_mer_restricao001.php';
}
</script>