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

//MODULO: Cadastro
$clcondominio->rotulo->label();
$clrotulo 				= new rotulocampo;
$clrotulo->label('j106_numcgm'); 
$clrotulo->label('z01_nome'); 

?>
<form name="form1" method="post" action="">
<center>
<table style="margin-top: 20px;">
<tr>
<td>
<fieldset>
	<legend><b>Cadastro de Condomínio</b></legend>

		<table border="0">
		  <tr>
		    <td nowrap title="<?=@$Tj107_sequencial?>">
		       <?=@$Lj107_sequencial?>
		    </td>
		    <td> 
				<?
				db_input('j107_sequencial',10,$Ij107_sequencial,true,'text',3,"")
				?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tj107_nome?>">
		       <?=@$Lj107_nome?>
		    </td>
		    <td> 
				<?
				db_input('j107_nome',40,$Ij107_nome,true,'text',$db_opcao,"")
				?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tj107_tipo?>">
		       <?=@$Lj107_tipo?>
		    </td>
		    <td> 
				<?
				
				$aTipo = array("0"=>"Nenhum","1"=>"VERTICAL","2"=>"HORIZONTAL");
				db_select('j107_tipo',$aTipo,1,$db_opcao);
				//db_input('j107_tipo',10,$Ij107_tipo,true,'text',$db_opcao,"")
				?>
		    </td>
		  </tr>
		  <tr>
			    <td>
			    	<? 
			    		db_ancora("<b>CGM</b>",'js_pesquisa_j106_numcgm(true)',$db_opcao);
			    	?>
			       
			    </td>
			    <td> 
						<?
						db_input('j106_numcgm',10,$Ij106_numcgm,true,'text',$db_opcao,'onchange=js_pesquisa_j106_numcgm(false)');
						db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
						?>
			    </td>
			  </tr>
		  </table>
</fieldset>
</td>
</tr>
</table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" 
     value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> 
     onclick="return FormSubmit();"
     >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_condominio','func_condominio.php?funcao_js=parent.js_preenchepesquisa|j107_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_condominio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function FormSubmit(){
	if(document.getElementById('j107_tipo').value == 0){
		alert ("Usuário:\n\n Campo Tipo não Selecionado\n\nAdministrador:");
		return false;
	}
	return true;
}
function js_pesquisa_j106_numcgm(mostra){
	
	  if(mostra==true){
	   	
	    	js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,18,0);
	    
	  }else{
	  if(document.getElementById('j106_numcgm').value == '' ||document.getElementById('j106_numcgm').value == null){
	  		
	  		document.getElementById('z01_nome').value = '';
	  	}else{
	    js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.j106_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,0);
	    }
	  }
}
function js_mostracgm(erro,chave){
	document.form1.z01_nome.value = chave;
  
  if(erro==true){ 
    document.form1.j106_numcgm.focus(); 
    document.form1.j106_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave){
  document.form1.z01_nome.value = chave; 
  document.form1.j106_numcgm.value = chave1;
  db_iframe_cgm.hide(); 
}

</script>