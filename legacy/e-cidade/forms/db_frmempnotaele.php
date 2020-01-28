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
$clempnotaele->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e69_codnota");
$clrotulo->label("o56_elemento");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te70_codnota?>">
       <?
       db_ancora(@$Le70_codnota,"js_pesquisae70_codnota(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e70_codnota',6,$Ie70_codnota,true,'text',$db_opcao," onchange='js_pesquisae70_codnota(false);'")
?>
       <?
db_input('e69_codnota',6,$Ie69_codnota,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te70_codele?>">
       <?
       db_ancora(@$Le70_codele,"js_pesquisae70_codele(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e70_codele',6,$Ie70_codele,true,'text',$db_opcao," onchange='js_pesquisae70_codele(false);'")
?>
       <?
db_input('o56_elemento',13,$Io56_elemento,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te70_valor?>">
       <?=@$Le70_valor?>
    </td>
    <td> 
<?
db_input('e70_valor',8,$Ie70_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te70_vlranu?>">
       <?=@$Le70_vlranu?>
    </td>
    <td> 
<?
db_input('e70_vlranu',8,$Ie70_vlranu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te70_vlrliq?>">
       <?=@$Le70_vlrliq?>
    </td>
    <td> 
<?
db_input('e70_vlrliq',8,$Ie70_vlrliq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisae70_codnota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empnotaele','db_iframe_empnota','func_empnota.php?funcao_js=parent.js_mostraempnota1|e69_codnota|e69_codnota','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.e70_codnota.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_empnotaele','db_iframe_empnota','func_empnota.php?pesquisa_chave='+document.form1.e70_codnota.value+'&funcao_js=parent.js_mostraempnota','Pesquisa',false);
     }else{
       document.form1.e69_codnota.value = ''; 
     }
  }
}
function js_mostraempnota(chave,erro){
  document.form1.e69_codnota.value = chave; 
  if(erro==true){ 
    document.form1.e70_codnota.focus(); 
    document.form1.e70_codnota.value = ''; 
  }
}
function js_mostraempnota1(chave1,chave2){
  document.form1.e70_codnota.value = chave1;
  document.form1.e69_codnota.value = chave2;
  db_iframe_empnota.hide();
}
function js_pesquisae70_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empnotaele','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.e70_codele.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_empnotaele','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.e70_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_elemento.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.e70_codele.focus(); 
    document.form1.e70_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.e70_codele.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
</script>