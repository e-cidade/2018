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

//MODULO: issqn
$clissnotaavulsa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("q02_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("q51_sequencial");
$clrotulo->label("q51_inscr");
$clrotulo->label("q51_obs");
?>
<form name="form1" method="post" action="">
<center>
	<table border="0">
		<tr>
			<td>
				<fieldset>
					<legend>
							<b>Cadastro de Notas Avulsas</b>
					</legend>
					<table>
						<tr>
							<td nowrap title="<?=@$Tq51_dtemiss?>">
								<?=@$Lq51_dtemiss?>
							</td>
							<td> 
								<?
									db_inputdata('q51_dtemiss',@$q51_dtemiss_dia,@$q51_dtemiss_mes,@$q51_dtemiss_ano,true,'text',3);
									db_input('q51_sequencial',10,$Iq51_sequencial,true,'hidden',3,'');
								?>
							</td>
						</tr>
						<tr>
							<td nowrap title="<?=@$Tq51_inscr?>">
								<?
									db_ancora(@$Lq51_inscr,"js_pesquisaq51_inscr(true);",$db_opcao);
								?>
							</td>
							<td> 
								<?
									db_input('q51_inscr',10,$Iq51_inscr,true,'text',$db_opcao," onchange='js_pesquisaq51_inscr(false);'")
								?>
								<?
									db_input('z01_nome',35,$Iz01_nome,true,'text',3,'')
								?>
							</td>
						</tr>
					</table>
					<fieldset>
						<legend>
							<b>Observações</b>
						</legend>
					<table>
						<tr>
							<td nowrap title="<?=@$Tq51_obs?>">  
								<?
									db_textarea('q51_obs',5,70,$Iq51_obs,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,200);'"); 
								?>
							</td> 
						</tr>
					</table>
					</fieldset>
				</fieldset>
			</td>
		</tr>
	</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input type="button" id="novaNota"  name="novaNota" value="Nova Nota" style="display:none;" onClick="js_novaNota();">
<?
if (isset($altera)){
  echo '<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >';
}
?>
</form>
<script>

function js_novaNota(){
	
 var lNovo = confirm("Deseja incluir uma nova nota avulsa?");
 
 if (lNovo) {
	parent.document.location.href = "iss1_issnotaavulsa001.php";
 }else{
 	return false;
 }	

}

function js_pesquisaq51_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.q51_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.q51_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.q51_id_usuario.focus(); 
    document.form1.q51_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.q51_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisaq51_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.q51_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q51_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraissbase(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q51_inscr.focus(); 
    document.form1.q51_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.q51_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issnotaavulsa','func_issnotaavulsaemitidos.php?funcao_js=parent.js_preenchepesquisa|q51_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issnotaavulsa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_controlatextarea(objt,max){
  obj = eval('document.form1.'+objt);
  atu = max-obj.value.length;
  if(obj.value.length > max){
    alert('A observação pode ter no máximo '+max+' caracteres !');
    obj.value = obj.value.substr(0,max);
    obj.select();
    obj.focus();
  }
}

</script>