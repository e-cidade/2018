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
$clmer_nutricionista->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("me02_c_nutriativo");
?>
<center>
<form name="form1" method="post" action="">
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme02_i_codigo?>">
   <?=@$Lme02_i_codigo?>
  </td>
  <td>
   <?db_input('me02_i_codigo',10,$Ime02_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme02_i_cgm?>">
   <?
   if($db_opcao==1){
    db_ancora(@$Lme02_i_cgm,"js_pesquisame02_i_cgm(true);",$db_opcao1);
   }else{
    db_ancora(@$Lme02_i_cgm,"js_pesquisame02_i_cgm(true);",3);
   }
   ?>
  </td>
  <td>
   <?db_input('me02_i_cgm',10,$Ime02_i_cgm,true,'text',$db_opcao1," onchange='js_pesquisame02_i_cgm(false);'")?>
   <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme02_c_crn?>">
   <?=@$Lme02_c_crn?>
  </td>
  <td>
   <?db_input('me02_c_crn',10,$Ime02_c_crn,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
    <td nowrap title="<?=@$Tme02_c_nutriativo?>">
       <?=@$Lme02_c_nutriativo?>       
    </td>
    <td> 
      <?
       if (!isset($me02_c_nutriativo)||$me02_c_nutriativo=="") {
         $me02_c_nutriativo = 1;
       }
       $ativo=array("1"=>"Sim","0"=>"Não");
       db_select("me02_c_nutriativo",$ativo,true,$db_opcao);?>
    </td>
  </tr> 
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> >
              <input name="novo" type="button" id="novo" value="Novo" 
       onclick="parent.location.href='mer1_mer_nutricionista000.php';" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
<script>
function js_pesquisame02_i_cgm(mostra) {
	
  if(mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_cgm',
    	                'func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true
    	               );
  } else {
	  
    if (document.form1.me02_i_cgm.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_cgm',
    	                  'func_cgm.php?pesquisa_chave='+document.form1.me02_i_cgm.value+
    	                  '&funcao_js=parent.js_mostracgm','Pesquisa',false
    	                 );
    } else {
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostracgm(erro,chave) {
	
  document.form1.z01_nome.value = chave;
  if (erro==true) {
	  
    document.form1.me02_i_cgm.focus();
    document.form1.me02_i_cgm.value = '';
    
  }
}

function js_mostracgm1(chave1,chave2) {
	
  document.form1.me02_i_cgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
  
}

function js_pesquisa() {
	
  js_OpenJanelaIframe('','db_iframe_mer_nutricionista',
		              'func_mer_nutricionista.php?funcao_js=parent.js_preenchepesquisa|me02_i_codigo','Pesquisa',true
		             );
}

function js_preenchepesquisa(chave) {
	
 db_iframe_mer_nutricionista.hide();
 <?
 if ($db_opcao!=1) {
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>