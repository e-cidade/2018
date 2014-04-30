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
$clprocrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p51_descr");
$clrotulo->label("k02_drecei");
?>
<form name="form1" method="post" action="">
<center>
<br>
<table border="0" width="100%" align="center">
  <tr>
    <td nowrap title="<?=@$Tp52_codigo?>">
       <?
       db_ancora(@$Lp52_codigo,"js_pesquisap52_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p52_codigo',3,$Ip52_codigo,true,'text',$db_opcao," onchange='js_pesquisap52_codigo(false);'")
?>
       <?
db_input('p51_descr',60,$Ip51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp52_codrec?>">
       <?
       db_ancora(@$Lp52_codrec,"js_pesquisap52_codrec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p52_codrec',3,$Ip52_codrec,true,'text',$db_opcao," onchange='js_pesquisap52_codrec(false);'")
?>
       <?
db_input('k02_drecei',40,$Ik02_drecei,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp52_valor?>">
       <?=@$Lp52_valor?>
    </td>
    <td> 
<?
db_input('p52_valor',15,$Ip52_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap52_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_tipoproc.php?grupo=1&funcao_js=parent.js_mostratipoproc1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_tipoproc.php?grupo=1&pesquisa_chave='+document.form1.p52_codigo.value+'&funcao_js=parent.js_mostratipoproc';
  }
}
function js_mostratipoproc(chave,erro){
  document.form1.p51_descr.value = chave; 
  if(erro==true){ 
    document.form1.p52_codigo.focus(); 
    document.form1.p52_codigo.value = ''; 
  }
}
function js_mostratipoproc1(chave1,chave2){
  document.form1.p52_codigo.value = chave1;
  document.form1.p51_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisap52_codrec(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_tabrec.php?funcao_js=parent.js_mostratabrec1|1|k02_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_tabrec.php?pesquisa_chave='+document.form1.p52_codrec.value+'&funcao_js=parent.js_mostratabrec';
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_drecei.value = chave; 
  if(erro==true){ 
    document.form1.p52_codrec.focus(); 
    document.form1.p52_codrec.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.p52_codrec.value = chave1;
  document.form1.k02_drecei.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){

  db_iframe.jan.location.href = 'func_procrec.php?funcao_js=parent.js_preenchepesquisa|p52_codigo|p52_codrec';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave1, chave2){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa1="+chave1+"&chavepesquisa2="+chave2;
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
if($db_opcao != 1){
?>
<script>
//onLoad=js_pesquisa();
</script>
<?
}
?>