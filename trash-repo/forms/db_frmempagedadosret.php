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

//MODULO: empenho
$clempagedadosret->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e87_descgera");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te75_codret?>">
       <?=@$Le75_codret?>
    </td>
    <td> 
<?
db_input('e75_codret',10,$Ie75_codret,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te75_codgera?>">
       <?
       db_ancora(@$Le75_codgera,"js_pesquisae75_codgera(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e75_codgera',6,$Ie75_codgera,true,'text',$db_opcao," onchange='js_pesquisae75_codgera(false);'")
?>
       <?
db_input('e87_descgera',40,$Ie87_descgera,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te75_arquivoret?>">
       <?=@$Le75_arquivoret?>
    </td>
    <td> 
<?
db_input('e75_arquivoret',20,$Ie75_arquivoret,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te75_febraban?>">
       <?=@$Le75_febraban?>
    </td>
    <td> 
<?
db_input('e75_febraban',29,$Ie75_febraban,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te75_seqarq?>">
       <?=@$Le75_seqarq?>
    </td>
    <td> 
<?
db_input('e75_seqarq',6,$Ie75_seqarq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisae75_codgera(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?funcao_js=parent.js_mostraempagegera1|e87_codgera|e87_descgera','Pesquisa',true);
  }else{
     if(document.form1.e75_codgera.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?pesquisa_chave='+document.form1.e75_codgera.value+'&funcao_js=parent.js_mostraempagegera','Pesquisa',false);
     }else{
       document.form1.e87_descgera.value = ''; 
     }
  }
}
function js_mostraempagegera(chave,erro){
  document.form1.e87_descgera.value = chave; 
  if(erro==true){ 
    document.form1.e75_codgera.focus(); 
    document.form1.e75_codgera.value = ''; 
  }
}
function js_mostraempagegera1(chave1,chave2){
  document.form1.e75_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empagedadosret','func_empagedadosret.php?funcao_js=parent.js_preenchepesquisa|e75_codret','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empagedadosret.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>