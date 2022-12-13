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

//MODULO: compras
$clpccontrlic->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("l03_tipo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp75_codcontr?>">
       <?=@$Lp75_codcontr?>
    </td>
    <td> 
<?
db_input('p75_codcontr',10,$Ip75_codcontr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp75_tipo?>">
       <?
       db_ancora(@$Lp75_tipo,"js_pesquisap75_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p75_tipo',1,$Ip75_tipo,true,'text',$db_opcao," onchange='js_pesquisap75_tipo(false);'")
?>
       <?
db_input('l03_tipo',1,$Il03_tipo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp75_numero?>">
       <?=@$Lp75_numero?>
    </td>
    <td> 
<?
db_input('p75_numero',8,$Ip75_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap75_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cflicita','func_cflicita.php?funcao_js=parent.js_mostracflicita1|l03_tipo|l03_tipo','Pesquisa',true);
  }else{
     if(document.form1.p75_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cflicita','func_cflicita.php?pesquisa_chave='+document.form1.p75_tipo.value+'&funcao_js=parent.js_mostracflicita','Pesquisa',false);
     }else{
       document.form1.l03_tipo.value = ''; 
     }
  }
}
function js_mostracflicita(chave,erro){
  document.form1.l03_tipo.value = chave; 
  if(erro==true){ 
    document.form1.p75_tipo.focus(); 
    document.form1.p75_tipo.value = ''; 
  }
}
function js_mostracflicita1(chave1,chave2){
  document.form1.p75_tipo.value = chave1;
  document.form1.l03_tipo.value = chave2;
  db_iframe_cflicita.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pccontrlic','func_pccontrlic.php?funcao_js=parent.js_preenchepesquisa|p75_codcontr','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pccontrlic.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>