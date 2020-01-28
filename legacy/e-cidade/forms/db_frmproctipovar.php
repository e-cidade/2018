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

//MODULO: protocolo
$clproctipovar->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_codproc");
$clrotulo->label("p54_codigo");
$clrotulo->label("p54_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp55_codproc?>">
       <?
       db_ancora(@$Lp55_codproc,"js_pesquisap55_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p55_codproc',3,$Ip55_codproc,true,'text',$db_opcao," onchange='js_pesquisap55_codproc(false);'")
?>
       <?
db_input('p58_codproc',3,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp55_codvar?>">
       <?
       db_ancora(@$Lp55_codvar,"js_pesquisap55_codvar(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p55_codvar',3,$Ip55_codvar,true,'text',$db_opcao," onchange='js_pesquisap55_codvar(false);'")
?>
       <?
db_input('p54_codigo',3,$Ip54_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp55_codcam?>">
       <?
       db_ancora(@$Lp55_codcam,"js_pesquisap55_codcam(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p55_codcam',5,$Ip55_codcam,true,'text',$db_opcao," onchange='js_pesquisap55_codcam(false);'")
?>
       <?
db_input('p54_codigo',3,$Ip54_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp55_conteúdo?>">
       <?=@$Lp55_conteúdo?>
    </td>
    <td> 
<?
db_textarea('p55_conteúdo',5,23,$Ip55_conteúdo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap55_codproc(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_protprocesso.php?pesquisa_chave='+document.form1.p55_codproc.value+'&funcao_js=parent.js_mostraprotprocesso';
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.p55_codproc.focus(); 
    document.form1.p55_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.p55_codproc.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe.hide();
}
function js_pesquisap55_codvar(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_procvar.php?funcao_js=parent.js_mostraprocvar1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_procvar.php?pesquisa_chave='+document.form1.p55_codvar.value+'&funcao_js=parent.js_mostraprocvar';
  }
}
function js_mostraprocvar(chave,erro){
  document.form1.p54_codigo.value = chave; 
  if(erro==true){ 
    document.form1.p55_codvar.focus(); 
    document.form1.p55_codvar.value = ''; 
  }
}
function js_mostraprocvar1(chave1,chave2){
  document.form1.p55_codvar.value = chave1;
  document.form1.p54_codigo.value = chave2;
  db_iframe.hide();
}
function js_pesquisap55_codcam(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_procvar.php?funcao_js=parent.js_mostraprocvar1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_procvar.php?pesquisa_chave='+document.form1.p55_codcam.value+'&funcao_js=parent.js_mostraprocvar';
  }
}
function js_mostraprocvar(chave,erro){
  document.form1.p54_codigo.value = chave; 
  if(erro==true){ 
    document.form1.p55_codcam.focus(); 
    document.form1.p55_codcam.value = ''; 
  }
}
function js_mostraprocvar1(chave1,chave2){
  document.form1.p55_codcam.value = chave1;
  document.form1.p54_codigo.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_proctipovar.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
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