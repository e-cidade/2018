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

//MODULO: juridico
$clinicial->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v57_oab");
$clrotulo->label("nome");
$clrotulo->label("v54_descr");
$clrotulo->label("v53_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv50_inicial?>">
       <?
       db_ancora(@$Lv50_inicial,"js_pesquisav50_inicial(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v50_inicial',6,$Iv50_inicial,true,'text',$db_opcao," onchange='js_pesquisav50_inicial(false);'")
?>
       <?
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv50_advog?>">
       <?
       db_ancora(@$Lv50_advog,"js_pesquisav50_advog(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v50_advog',6,$Iv50_advog,true,'text',$db_opcao," onchange='js_pesquisav50_advog(false);'")
?>
       <?
db_input('v57_oab',20,$Iv57_oab,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv50_data?>">
       <?=@$Lv50_data?>
    </td>
    <td> 
<?
db_inputdata('v50_data',@$v50_data_dia,@$v50_data_mes,@$v50_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv50_id_login?>">
       <?
       db_ancora(@$Lv50_id_login,"js_pesquisav50_id_login(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v50_id_login',6,$Iv50_id_login,true,'text',$db_opcao," onchange='js_pesquisav50_id_login(false);'")
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv50_codlocal?>">
       <?
       db_ancora(@$Lv50_codlocal,"js_pesquisav50_codlocal(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v50_codlocal',8,$Iv50_codlocal,true,'text',$db_opcao," onchange='js_pesquisav50_codlocal(false);'")
?>
       <?
db_input('v54_descr',30,$Iv54_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv50_codvara?>">
       <?
       db_ancora(@$Lv50_codvara,"js_pesquisav50_codvara(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v50_codvara',6,$Iv50_codvara,true,'text',$db_opcao," onchange='js_pesquisav50_codvara(false);'")
?>
       <?
db_input('v53_descr',40,$Iv53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv50_codmov?>">
       <?=@$Lv50_codmov?>
    </td>
    <td> 
<?
db_input('v50_codmov',6,$Iv50_codmov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav50_advog(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_advog.php?funcao_js=parent.js_mostraadvog1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_advog.php?pesquisa_chave='+document.form1.v50_advog.value+'&funcao_js=parent.js_mostraadvog';
  }
}
function js_mostraadvog(chave,erro){
  document.form1.v57_oab.value = chave; 
  if(erro==true){ 
    document.form1.v50_advog.focus(); 
    document.form1.v50_advog.value = ''; 
  }
}
function js_mostraadvog1(chave1,chave2){
  document.form1.v50_advog.value = chave1;
  document.form1.v57_oab.value = chave2;
  db_iframe.hide();
}
function js_pesquisav50_id_login(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.v50_id_login.value+'&funcao_js=parent.js_mostradb_usuarios';
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.v50_id_login.focus(); 
    document.form1.v50_id_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.v50_id_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisav50_codlocal(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_localiza.php?funcao_js=parent.js_mostralocaliza1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_localiza.php?pesquisa_chave='+document.form1.v50_codlocal.value+'&funcao_js=parent.js_mostralocaliza';
  }
}
function js_mostralocaliza(chave,erro){
  document.form1.v54_descr.value = chave; 
  if(erro==true){ 
    document.form1.v50_codlocal.focus(); 
    document.form1.v50_codlocal.value = ''; 
  }
}
function js_mostralocaliza1(chave1,chave2){
  document.form1.v50_codlocal.value = chave1;
  document.form1.v54_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisav50_codvara(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_vara.php?funcao_js=parent.js_mostravara1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_vara.php?pesquisa_chave='+document.form1.v50_codvara.value+'&funcao_js=parent.js_mostravara';
  }
}
function js_mostravara(chave,erro){
  document.form1.v53_descr.value = chave; 
  if(erro==true){ 
    document.form1.v50_codvara.focus(); 
    document.form1.v50_codvara.value = ''; 
  }
}
function js_mostravara1(chave1,chave2){
  document.form1.v50_codvara.value = chave1;
  document.form1.v53_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_preenchepesquisa|0';
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