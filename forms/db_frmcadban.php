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

//MODULO: caixa
$clcadban->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk15_codigo?>">
       <?=@$Lk15_codigo?>
    </td>
    <td> 
<?
db_input('k15_codigo',10,$Ik15_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_numcgm?>">
       <?
       db_ancora(@$Lk15_numcgm,"js_pesquisak15_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k15_numcgm',10,$Ik15_numcgm,true,'text',$db_opcao," onchange='js_pesquisak15_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_codbco?>">
       <?=@$Lk15_codbco?>
    </td>
    <td> 
<?
db_input('k15_codbco',10,$Ik15_codbco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_codage?>">
       <?=@$Lk15_codage?>
    </td>
    <td> 
<?
db_input('k15_codage',10,$Ik15_codage,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_contat?>">
       <?=@$Lk15_contat?>
    </td>
    <td> 
<?
db_input('k15_contat',40,$Ik15_contat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_gerent?>">
       <?=@$Lk15_gerent?>
    </td>
    <td> 
<?
db_input('k15_gerent',40,$Ik15_gerent,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_agenci?>">
       <?=@$Lk15_agenci?>
    </td>
    <td> 
<?
db_input('k15_agenci',40,$Ik15_agenci,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_conta?>">
       <?=@$Lk15_conta?>
    </td>
    <td> 
<?
db_input('k15_conta',10,$Ik15_conta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_rectxb?>">
       <?=@$Lk15_rectxb?>
    </td>
    <td> 
<?
db_input('k15_rectxb',10,$Ik15_rectxb,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_txban?>">
       <?=@$Lk15_txban?>
    </td>
    <td> 
<?
db_input('k15_txban',20,$Ik15_txban,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_local?>">
       <?=@$Lk15_local?>
    </td>
    <td> 
<?
db_input('k15_local',40,$Ik15_local,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_carte?>">
       <?=@$Lk15_carte?>
    </td>
    <td> 
<?
db_input('k15_carte',10,$Ik15_carte,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_espec?>">
       <?=@$Lk15_espec?>
    </td>
    <td> 
<?
db_input('k15_espec',20,$Ik15_espec,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_aceite?>">
       <?=@$Lk15_aceite?>
    </td>
    <td> 
<?
db_input('k15_aceite',10,$Ik15_aceite,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_ageced?>">
       <?=@$Lk15_ageced?>
    </td>
    <td> 
<?
db_input('k15_ageced',40,$Ik15_ageced,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
	
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_testatx();" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_testatx(){
	if (document.form1.k15_rectxb.value!=""){
    if (document.form1.k15_txban.value==""){
			alert("Foi selecionada uma Receita da taxa bancaria, é nescessário preencher o Valor da taxa bancaria!!");
			document.form1.k15_txban.focus();
			return false;
		}
	}
	return true;
}
function js_pesquisak15_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.k15_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.k15_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.k15_numcgm.focus(); 
    document.form1.k15_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.k15_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?funcao_js=parent.js_preenchepesquisa|k15_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadban.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>