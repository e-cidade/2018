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

//MODULO: Contabilidade
$clconrelinfo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");
?>
<form name="form1" method="post" action="">
	<fieldset style="width:500px">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc83_codigo?>">
       <?=@$Lc83_codigo?>
    </td>
    <td> 
		<?
		db_input('c83_codigo',10,$Ic83_codigo,true,'text',3,"")
		?>
    </td>
  </tr>
<!--	
	
  <tr>
    <td nowrap title="<?=@$Tc83_codrel?>">
       <?=@$Lc83_codrel?>
    </td>
    <td> 
		<?
		db_input('c83_codrel',5,$Ic83_codrel,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
	-->
	<tr>
    <td nowrap title="<?=@$Tc83_codrel?>">
       <?
       db_ancora(@$Lc83_codrel,"js_pesquisao69_codparamrel(true);",$db_opcao);
       ?>
    </td>
    <td nowrap> 
			<?
			  db_input('c83_codrel',10,null,true,'text',$db_opcao," onchange='js_pesquisao69_codparamrel(false);'");
			  db_input('o42_descrrel',36,$Io42_descrrel,true,'text',3,'')
			?>
    </td>
  </tr>
	
	
	
	
  <tr>
    <td nowrap title="<?=@$Tc83_variavel?>">
       <?=@$Lc83_variavel?>
    </td>
    <td> 
		<?
		db_input('c83_variavel',50,$Ic83_variavel,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc83_anousu?>">
       <?=@$Lc83_anousu?>
    </td>
    <td> 
		<?
		$c83_anousu = db_getsession('DB_anousu');
		db_input('c83_anousu',4,$Ic83_anousu,true,'text',3,"")
		?>
    </td>
  </tr>
  </table>
  </center>
	</fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao69_codparamrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel|o69_codseq','Pesquisa');
  }else{
     if(document.form1.c83_codrel.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_orcparamrel','func_orcparamrel.php?pesquisa_chave='+document.form1.c83_codrel.value+'&funcao_js=parent.js_mostraorcparamrel','Pesquisa',false);
     }else{
       document.form1.o42_descrrel.value = ''; 
     }
  }
}
function js_mostraorcparamrel(chave,erro){
  document.form1.o42_descrrel.value = chave; 
  if(erro==true){ 
    document.form1.c83_codrel.focus(); 
    document.form1.c83_codrel.value = ''; 
  }
}
function js_mostraorcparamrel1(chave1,chave2,chave3){
  document.form1.c83_codrel.value = chave1;
  document.form1.o42_descrrel.value    = chave2;
  db_iframe_orcparamrel.hide();
}	
	
	
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conrelinfo','func_conrelinfo.php?funcao_js=parent.js_preenchepesquisa|c83_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conrelinfo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>