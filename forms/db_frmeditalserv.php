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

//MODULO: contrib
$cleditalserv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("d02_codedi");
$clrotulo->label("d03_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Td04_contri?>">
       <?
       db_ancora(@$Ld04_contri,"js_pesquisad04_contri(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d04_contri',4,$Id04_contri,true,'text',$db_opcao," onchange='js_pesquisad04_contri(false);'")
?>
       <?
db_input('d02_codedi',4,$Id02_codedi,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td04_tipos?>">
       <?
       db_ancora(@$Ld04_tipos,"js_pesquisad04_tipos(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d04_tipos',4,$Id04_tipos,true,'text',$db_opcao," onchange='js_pesquisad04_tipos(false);'")
?>
       <?
db_input('d03_descr',40,$Id03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td04_quant?>">
       <?=@$Ld04_quant?>
    </td>
    <td> 
<?
db_input('d04_quant',15,$Id04_quant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td04_vlrcal?>">
       <?=@$Ld04_vlrcal?>
    </td>
    <td> 
<?
db_input('d04_vlrcal',15,$Id04_vlrcal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisad04_contri(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_editalrua.php?funcao_js=parent.js_mostraeditalrua1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_editalrua.php?pesquisa_chave='+document.form1.d04_contri.value+'&funcao_js=parent.js_mostraeditalrua';
  }
}
function js_mostraeditalrua(chave,erro){
  document.form1.d02_codedi.value = chave; 
  if(erro==true){ 
    document.form1.d04_contri.focus(); 
    document.form1.d04_contri.value = ''; 
  }
}
function js_mostraeditalrua1(chave1,chave2){
  document.form1.d04_contri.value = chave1;
  document.form1.d02_codedi.value = chave2;
  db_iframe.hide();
}
function js_pesquisad04_tipos(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_editaltipo.php?funcao_js=parent.js_mostraeditaltipo1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_editaltipo.php?pesquisa_chave='+document.form1.d04_tipos.value+'&funcao_js=parent.js_mostraeditaltipo';
  }
}
function js_mostraeditaltipo(chave,erro){
  document.form1.d03_descr.value = chave; 
  if(erro==true){ 
    document.form1.d04_tipos.focus(); 
    document.form1.d04_tipos.value = ''; 
  }
}
function js_mostraeditaltipo1(chave1,chave2){
  document.form1.d04_tipos.value = chave1;
  document.form1.d03_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_editalserv.php?funcao_js=parent.js_preenchepesquisa|0|1';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave,chave1){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave+"&chavepesquisa1="+chave1;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>