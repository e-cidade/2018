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

//MODULO: agua
$claguahidrotroca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x04_codmarca");
$clrotulo->label("x04_coddiametro");
$clrotulo->label("j31_descr");
$clrotulo->label("x03_nomemarca");
$clrotulo->label("x15_diametro");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align='right' nowrap title="<?=@$Tx28_codhidrometro?>">
       <?
       db_ancora(@$Lx28_codhidrometro,"js_pesquisax28_codhidrometro(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x28_codhidrometro',6,$Ix28_codhidrometro,true,'text',$db_opcao," onchange='js_pesquisax28_codhidrometro(false);'")
?>       
    </td>
  </tr>
  <tr>
    <td align='right' nowrap title="<?=@$Tx04_codmarca?>">
       <?=@$Lx04_codmarca?>
    </td>
    <td>
           <?
db_input('x04_codmarca',6,$Ix04_codmarca,true,'text',3,'');
db_input('x03_nomemarca',40,$Ix03_nomemarca,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td align='right' nowrap title="<?=@$Tx04_coddiametro?>">
       <?=@$Lx04_coddiametro?>
    </td>
    <td>
           <?                  
db_input('x04_coddiametro',6,$Ix04_coddiametro,true,'text',3,'');
db_input('x15_diametro',40,$Ix15_diametro,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td align='right' nowrap title="<?=@$Tx28_codigo?>">
       <?
       db_ancora(@$Lx28_codigo,"js_pesquisax28_codigo(true);",$db_opcao);
       ?>       
    </td>
    <td> 
<?
db_input('x28_codigo',6,$Ix28_codigo,true,'text',$db_opcao,"onchange=s'js_pesquisax28_codigo(false);'");
db_input('j31_descr',40,$Ij31_descr,true,'text',3,'');
?>
    </td>
  </tr>
  <tr>
    <td nowrap align='right' title="<?=@$Tx28_dttroca?>">
       <?=@$Lx28_dttroca?>
    </td>
    <td> 
<?
db_inputdata('x28_dttroca',@$x28_dttroca_dia,@$x28_dttroca_mes,@$x28_dttroca_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap align='right' title="<?=@$Tx28_obs?>">
       <?=@$Lx28_obs?>
    </td>
    <td> 
<?
db_textarea('x28_obs',5, 60,$Ix28_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisax28_codhidrometro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aguahidromatric','func_aguahidromatricalt.php?funcao_js=parent.js_mostraaguahidromatric1|x04_codhidrometro|x04_codmarca|x03_nomemarca|x04_coddiametro|x15_diametro','Pesquisa',true);
  }else{
     if(document.form1.x28_codhidrometro.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aguahidromatric','func_aguahidromatricalt.php?pesquisa_chave='+document.form1.x28_codhidrometro.value+'&funcao_js=parent.js_mostraaguahidromatric','Pesquisa',false);
     }else{      
		document.form1.x04_codmarca.value = "";
		document.form1.x03_nomemarca.value = "";
		document.form1.x04_coddiametro.value = "";
        document.form1.x15_diametro.value = "";  
     }
  }
}
function js_mostraaguahidromatric(chave,chave1,chave2,chave3,erro){ 
  if(erro==true){ 
    document.form1.x28_codhidrometro.focus(); 
    document.form1.x28_codhidrometro.value = '';
	document.form1.x04_codmarca.value = "";
	document.form1.x03_nomemarca.value = "";
	document.form1.x04_coddiametro.value = "";
    document.form1.x15_diametro.value = "";	 
  }else{
	document.form1.x04_codmarca.value = chave;
	document.form1.x03_nomemarca.value =chave1;
	document.form1.x04_coddiametro.value =chave2 ;
    document.form1.x15_diametro.value = chave3;
  }
}
function js_mostraaguahidromatric1(chave,chave1,chave2,chave3,chave4){
  	document.form1.x28_codhidrometro.value = chave;
	document.form1.x04_codmarca.value = chave1;
	document.form1.x03_nomemarca.value =chave2;
	document.form1.x04_coddiametro.value =chave3 ;
    document.form1.x15_diametro.value = chave4;  
  db_iframe_aguahidromatric.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_aguahidrotroca','func_aguahidrotroca.php?funcao_js=parent.js_preenchepesquisa|x28_codhidrometro','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_aguahidrotroca.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisax28_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_codigo','func_caracter.php?grupo=84&funcao_js=parent.js_mostracodigo1|j31_codigo|j31_descr','Pesquisa',true);
  }else{
     if(document.form1.x28_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_codigo','func_caracter.php?grupo=84&pesquisa_chave='+document.form1.x28_codigo.value+'&funcao_js=parent.js_mostracodigo','Pesquisa',false);
     }else{
       document.form1.x04_codmarca.value = ''; 
     }
  }
}
function js_mostracodigo(chave,chave1,erro){
  document.form1.j31_descr.value = chave1; 
  if(erro==true){ 
    document.form1.x28_codigo.focus(); 
    document.form1.x28_codigo.value = ''; 
  }
}
function js_mostracodigo1(chave1,chave2){
  document.form1.x28_codigo.value = chave1;
  document.form1.j31_descr.value = chave2;
  db_iframe_codigo.hide();
}
</script>