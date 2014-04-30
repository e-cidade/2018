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
$clpccontrdep->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p71_datalanc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp74_codcontr?>">
       <?
       db_ancora(@$Lp74_codcontr,"js_pesquisap74_codcontr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p74_codcontr',10,$Ip74_codcontr,true,'text',$db_opcao," onchange='js_pesquisap74_codcontr(false);'")
?>
       <?
db_input('p71_datalanc',10,$Ip71_datalanc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp74_valor?>">
       <?=@$Lp74_valor?>
    </td>
    <td> 
<?
db_input('p74_valor',20,$Ip74_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp74_perc?>">
       <?=@$Lp74_perc?>
    </td>
    <td> 
<?
db_input('p74_perc',20,$Ip74_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap74_codcontr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pccontratos','func_pccontratos.php?funcao_js=parent.js_mostrapccontratos1|p71_codcontr|p71_datalanc','Pesquisa',true);
  }else{
     if(document.form1.p74_codcontr.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pccontratos','func_pccontratos.php?pesquisa_chave='+document.form1.p74_codcontr.value+'&funcao_js=parent.js_mostrapccontratos','Pesquisa',false);
     }else{
       document.form1.p71_datalanc.value = ''; 
     }
  }
}
function js_mostrapccontratos(chave,erro){
  document.form1.p71_datalanc.value = chave; 
  if(erro==true){ 
    document.form1.p74_codcontr.focus(); 
    document.form1.p74_codcontr.value = ''; 
  }
}
function js_mostrapccontratos1(chave1,chave2){
  document.form1.p74_codcontr.value = chave1;
  document.form1.p71_datalanc.value = chave2;
  db_iframe_pccontratos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pccontrdep','func_pccontrdep.php?funcao_js=parent.js_preenchepesquisa|p74_codcontr','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pccontrdep.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>