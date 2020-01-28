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
$clpredio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j107_nome");
?>
<form name="form1" method="post" action="">
<center>
<table style="margin-top: 20px;">
<tr>
<td>
<fieldset>
	<legend><b>Cadastro de Prédio</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj111_sequencial?>">
       <?=@$Lj111_sequencial?>
    </td>
    <td> 
		<?
		db_input('j111_sequencial',10,$Ij111_sequencial,true,'text',3,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj111_nome?>">
       <?=@$Lj111_nome?>
    </td>
    <td> 
		<?
			db_input('j111_nome',54,$Ij111_nome,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj111_condominio?>">
       <?
       db_ancora(@$Lj111_condominio,"js_pesquisaj111_condominio(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			db_input('j111_condominio',10,$Ij111_condominio,true,'text',$db_opcao," onchange='js_pesquisaj111_condominio(false);'")
			?>
			<?
			db_input('j107_nome',40,$Ij107_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  </table>
  </fieldset>
</td>
</tr>
</table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj111_condominio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_condominio','func_condominio.php?funcao_js=parent.js_mostracondominio1|j107_sequencial|j107_nome&tipo=1','Pesquisa',true);
  }else{
     if(document.form1.j111_condominio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_condominio','func_condominio.php?pesquisa_chave='+document.form1.j111_condominio.value+'&funcao_js=parent.js_mostracondominio&tipo=1','Pesquisa',false);
     }else{
       document.form1.j107_nome.value = ''; 
     }
  }
}
function js_mostracondominio(chave,erro){
  document.form1.j107_nome.value = chave; 
  if(erro==true){ 
    document.form1.j111_condominio.focus(); 
    document.form1.j111_condominio.value = ''; 
  }
}
function js_mostracondominio1(chave1,chave2){
  document.form1.j111_condominio.value = chave1;
  document.form1.j107_nome.value = chave2;
  db_iframe_condominio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_predio','func_predio.php?funcao_js=parent.js_preenchepesquisa|j111_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_predio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>