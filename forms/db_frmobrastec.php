<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: projetos
$clobrastec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form class="container" name="form1" method="post" action="">
	<fieldset>
	  <legend>Cadastros - Técnicos</legend>
		<table class="form-container">
			<tr>
				<td nowrap title="<?=@$Tob15_numcgm?>">
					<?
						db_ancora(@$Lob15_numcgm,"js_pesquisaob15_numcgm(true);",$db_opcao);
					?>
				</td>
				<td> 
					<?
						db_input('ob15_sequencial',10,"",true,'hidden',1,"");
						db_input('ob15_numcgm',10,$Iob15_numcgm,true,'text',$db_opcao," onchange='js_pesquisaob15_numcgm(false);'");
					?>
					<?
						db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tob15_crea?>">
					<?=@$Lob15_crea?>
				</td>
				<td> 
					<?
						db_input('ob15_crea',20,$Iob15_crea,true,'text',$db_opcao,"")
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tob15_tipo?>">
					<?=@$Lob15_tipo?>
				</td>
				<td> 
					<?
						$aTipo = array("1"=>"Obra","2"=>"Prefeitura");
						db_select('ob15_tipo',$aTipo,true,$db_opcao,"");
					?>
				</td>
			</tr>
		</table>
	</fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaob15_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ob15_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.ob15_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ob15_numcgm.focus(); 
    document.form1.ob15_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.ob15_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrastec','func_obrastec.php?funcao_js=parent.js_preenchepesquisa|ob15_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrastec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("ob15_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("ob15_crea").addClassName("field-size9");
$("ob15_numcgm").addClassName("field-size2");

</script>