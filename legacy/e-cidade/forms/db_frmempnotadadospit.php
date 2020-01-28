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

//MODULO: Empenho
$clempnotadadospit->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e12_descricao");
$clrotulo->label("e10_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te11_sequencial?>">
       <?=@$Le11_sequencial?>
    </td>
    <td> 
<?
db_input('e11_sequencial',10,$Ie11_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te11_tipodocumentosfiscal?>">
       <?
       db_ancora(@$Le11_tipodocumentosfiscal,"js_pesquisae11_tipodocumentosfiscal(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e11_tipodocumentosfiscal',10,$Ie11_tipodocumentosfiscal,true,'text',$db_opcao," onchange='js_pesquisae11_tipodocumentosfiscal(false);'")
?>
       <?
db_input('e12_descricao',50,$Ie12_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te11_cfop?>">
       <?
       db_ancora(@$Le11_cfop,"js_pesquisae11_cfop(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e11_cfop',10,$Ie11_cfop,true,'text',$db_opcao," onchange='js_pesquisae11_cfop(false);'")
?>
       <?
db_input('e10_descricao',50,$Ie10_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te11_seriefiscal?>">
       <?=@$Le11_seriefiscal?>
    </td>
    <td> 
<?
db_input('e11_seriefiscal',50,$Ie11_seriefiscal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te11_inscricaosubstitutofiscal?>">
       <?=@$Le11_inscricaosubstitutofiscal?>
    </td>
    <td> 
<?
db_input('e11_inscricaosubstitutofiscal',10,$Ie11_inscricaosubstitutofiscal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te11_valoricms?>">
       <?=@$Le11_valoricms?>
    </td>
    <td> 
<?
db_input('e11_valoricms',10,$Ie11_valoricms,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te11_basecalculosubstitutotrib?>">
       <?=@$Le11_basecalculosubstitutotrib?>
    </td>
    <td> 
<?
db_input('e11_basecalculosubstitutotrib',10,$Ie11_basecalculosubstitutotrib,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te11_valoicmssubstitutotrib?>">
       <?=@$Le11_valoicmssubstitutotrib?>
    </td>
    <td> 
<?
db_input('e11_valoicmssubstitutotrib',10,$Ie11_valoicmssubstitutotrib,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisae11_tipodocumentosfiscal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipodocumentosfiscal','func_tipodocumentosfiscal.php?funcao_js=parent.js_mostratipodocumentosfiscal1|e12_sequencial|e12_descricao','Pesquisa',true);
  }else{
     if(document.form1.e11_tipodocumentosfiscal.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipodocumentosfiscal','func_tipodocumentosfiscal.php?pesquisa_chave='+document.form1.e11_tipodocumentosfiscal.value+'&funcao_js=parent.js_mostratipodocumentosfiscal','Pesquisa',false);
     }else{
       document.form1.e12_descricao.value = ''; 
     }
  }
}
function js_mostratipodocumentosfiscal(chave,erro){
  document.form1.e12_descricao.value = chave; 
  if(erro==true){ 
    document.form1.e11_tipodocumentosfiscal.focus(); 
    document.form1.e11_tipodocumentosfiscal.value = ''; 
  }
}
function js_mostratipodocumentosfiscal1(chave1,chave2){
  document.form1.e11_tipodocumentosfiscal.value = chave1;
  document.form1.e12_descricao.value = chave2;
  db_iframe_tipodocumentosfiscal.hide();
}
function js_pesquisae11_cfop(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cfop','func_cfop.php?funcao_js=parent.js_mostracfop1|e10_sequencial|e10_descricao','Pesquisa',true);
  }else{
     if(document.form1.e11_cfop.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cfop','func_cfop.php?pesquisa_chave='+document.form1.e11_cfop.value+'&funcao_js=parent.js_mostracfop','Pesquisa',false);
     }else{
       document.form1.e10_descricao.value = ''; 
     }
  }
}
function js_mostracfop(chave,erro){
  document.form1.e10_descricao.value = chave; 
  if(erro==true){ 
    document.form1.e11_cfop.focus(); 
    document.form1.e11_cfop.value = ''; 
  }
}
function js_mostracfop1(chave1,chave2){
  document.form1.e11_cfop.value = chave1;
  document.form1.e10_descricao.value = chave2;
  db_iframe_cfop.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empnotadadospit','func_empnotadadospit.php?funcao_js=parent.js_preenchepesquisa|e11_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empnotadadospit.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>