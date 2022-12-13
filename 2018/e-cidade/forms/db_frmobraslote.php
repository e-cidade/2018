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

//MODULO: projetos
$clobraslote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("j34_setor");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tob05_codobra?>">
       <?
       db_ancora(@$Lob05_codobra,"js_pesquisaob05_codobra(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ob05_codobra',10,$Iob05_codobra,true,'text',$db_opcao," onchange='js_pesquisaob05_codobra(false);'")
?>
       <?
db_input('ob01_nomeobra',55,$Iob01_nomeobra,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob05_idbql?>">
       <?
       db_ancora(@$Lob05_idbql,"js_pesquisaob05_idbql(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ob05_idbql',6,$Iob05_idbql,true,'text',$db_opcao," onchange='js_pesquisaob05_idbql(false);'")
?>
       <?
db_input('j34_setor',4,$Ij34_setor,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaob05_codobra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra','Pesquisa',true);
  }else{
     if(document.form1.ob05_codobra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?pesquisa_chave='+document.form1.ob05_codobra.value+'&funcao_js=parent.js_mostraobras','Pesquisa',false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}
function js_mostraobras(chave,erro){
  document.form1.ob01_nomeobra.value = chave; 
  if(erro==true){ 
    document.form1.ob05_codobra.focus(); 
    document.form1.ob05_codobra.value = ''; 
  }
}
function js_mostraobras1(chave1,chave2){
  document.form1.ob05_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}
function js_pesquisaob05_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|j34_idbql|j34_setor','Pesquisa',true);
  }else{
     if(document.form1.ob05_idbql.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?pesquisa_chave='+document.form1.ob05_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false);
     }else{
       document.form1.j34_setor.value = ''; 
     }
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.ob05_idbql.focus(); 
    document.form1.ob05_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2){
  document.form1.ob05_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe_lote.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obraslote','func_obraslote.php?funcao_js=parent.js_preenchepesquisa|ob05_codobra','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obraslote.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>