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

//MODULO: pessoal
$clmovrel->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r56_descr");
$clrotulo->label("r56_dirarq");
$clrotulo->label("r55_descr");
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr54_codrel?>">
       <?
       db_ancora(@$Lr54_codrel,"js_pesquisar54_codrel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('r54_codrel',4,$Ir54_codrel,true,'text',$db_opcao,"onchange='js_pesquisar54_codrel(false);'");
db_input('r56_descr',40,$Ir56_descr,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
	<td align='left' nowrap title="<?=@$Tr56_dirarq?>">
       <?
       db_ancora(@$Lr56_dirarq,"",3);
       ?>
	</td>
	<td nowrap align='left'>
<?
db_input('diretorio_arquivo'  ,47,$Ir56_dirarq,true,'text',3,"");
?>
	</td>
  </tr>
  <tr>
	<td align='left' nowrap>
	</td>
	<td nowrap align='left'>
<?
db_input('r56_dirarq',46,$Ir56_dirarq,true,'file',$db_opcao,"");
?>
	</td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr54_codeve?>">
       <?
       db_ancora(@$Lr54_codeve,"js_pesquisar54_codeve(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('r54_codeve',4,$Ir54_codeve,true,'text',$db_opcao,"onchange='js_pesquisar54_codeve(false);'");
db_input('r55_descr',40,$Ir55_descr,true,'text',3,"");
?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verifica_campos();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_verifica_campos(){
  if(document.form1.r54_codrel.value == ""){
    alert("Informe o código do convênio.");
    document.form1.r54_codrel.focus();
    return false;
  }else if(document.form1.r56_dirarq.value == ""){
    alert("Informe o caminho do arquivo.");
    return false;
  }else if(document.form1.r54_codeve.value == ""){
    if(confirm("Relacionamento não informado. \nDeseja continuar?")){
      return true;
    }else{
      return false;
    }
  }
  return true;
}
function js_pesquisar54_codrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_convenio','func_convenioalt.php?funcao_js=parent.js_mostraconvenio1|r56_codrel|r56_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,20);
  }else{
     if(document.form1.r54_codrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_convenio','func_convenioalt.php?pesquisa_chave='+document.form1.r54_codrel.value+'&funcao_js=parent.js_mostraconvenio&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false,'0');
     }else{
       document.form1.r56_descr.value = '';
       document.form1.diretorio_arquivo.value = ''; 
     }
  }
}
function js_mostraconvenio(chave1,chave2,erro){
  document.form1.r56_descr.value  = chave1;
  if(erro==true){
    document.form1.diretorio_arquivo.value = "";
    document.form1.r54_codrel.value = '';
    document.form1.r54_codrel.focus(); 
  }else{
    document.form1.diretorio_arquivo.value = chave2;
  }
}
function js_mostraconvenio1(chave1,chave2,chave3){
  document.form1.r54_codrel.value = chave1;
  document.form1.r56_descr.value  = chave2;
  document.form1.diretorio_arquivo.value = chave3;
  db_iframe_convenio.hide();
}
function js_pesquisar54_codeve(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_relac','func_relac.php?funcao_js=parent.js_mostrarelac1|r55_codeve|r55_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,'20');
  }else{
     if(document.form1.r54_codeve.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_relac','func_relac.php?pesquisa_chave='+document.form1.r54_codeve.value+'&funcao_js=parent.js_mostrarelac&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false,'0');
     }else{
       document.form1.r55_descr.value = '';
     }
  }
}
function js_mostrarelac(chave,erro){
  document.form1.r55_descr.value  = chave;
  if(erro==true){ 
    document.form1.r54_codeve.value = '';
    document.form1.r54_codeve.focus(); 
  }
}
function js_mostrarelac1(chave1,chave2){
  document.form1.r54_codeve.value = chave1;
  document.form1.r55_descr.value  = chave2;
  db_iframe_relac.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_movrel','func_movrel.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_movrel.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>