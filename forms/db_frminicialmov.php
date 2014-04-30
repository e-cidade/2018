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

//MODULO: patrim
$clinicialmov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v50_data");
$clrotulo->label("v52_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat01_status?>">
       <?=@$Lat01_status?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('at01_status',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_codmov?>">
       <?=@$Lv56_codmov?>
    </td>
    <td> 
<?
db_input('v56_codmov',6,$Iv56_codmov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_codatend?>">
       <?=@$Lat02_codatend?>
    </td>
    <td> 
<?
db_input('at02_codatend',6,$Iat02_codatend,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_inicial?>">
       <?
       db_ancora(@$Lv56_inicial,"js_pesquisav56_inicial(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v56_inicial',8,$Iv56_inicial,true,'text',$db_opcao," onchange='js_pesquisav56_inicial(false);'")
?>
       <?
db_input('v50_data',8,$Iv50_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_codsit?>">
       <?
       db_ancora(@$Lv56_codsit,"js_pesquisav56_codsit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v56_codsit',6,$Iv56_codsit,true,'text',$db_opcao," onchange='js_pesquisav56_codsit(false);'")
?>
       <?
db_input('v52_descr',40,$Iv52_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_obs?>">
       <?=@$Lv56_obs?>
    </td>
    <td> 
<?
db_textarea('v56_obs',0,0,$Iv56_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_data?>">
       <?=@$Lv56_data?>
    </td>
    <td> 
<?
db_inputdata('v56_data',@$v56_data_dia,@$v56_data_mes,@$v56_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_id_login?>">
       <?
       db_ancora(@$Lv56_id_login,"js_pesquisav56_id_login(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v56_id_login',6,$Iv56_id_login,true,'text',$db_opcao," onchange='js_pesquisav56_id_login(false);'")
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav56_inicial(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicial1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v56_inicial.value+'&funcao_js=parent.js_mostrainicial';
  }
}
function js_mostrainicial(chave,erro){
  document.form1.v50_data.value = chave; 
  if(erro==true){ 
    document.form1.v56_inicial.focus(); 
    document.form1.v56_inicial.value = ''; 
  }
}
function js_mostrainicial1(chave1,chave2){
  document.form1.v56_inicial.value = chave1;
  document.form1.v50_data.value = chave2;
  db_iframe.hide();
}
function js_pesquisav56_codsit(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_situacao.php?funcao_js=parent.js_mostrasituacao1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_situacao.php?pesquisa_chave='+document.form1.v56_codsit.value+'&funcao_js=parent.js_mostrasituacao';
  }
}
function js_mostrasituacao(chave,erro){
  document.form1.v52_descr.value = chave; 
  if(erro==true){ 
    document.form1.v56_codsit.focus(); 
    document.form1.v56_codsit.value = ''; 
  }
}
function js_mostrasituacao1(chave1,chave2){
  document.form1.v56_codsit.value = chave1;
  document.form1.v52_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisav56_id_login(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.v56_id_login.value+'&funcao_js=parent.js_mostradb_usuarios';
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.v56_id_login.focus(); 
    document.form1.v56_id_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.v56_id_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_inicialmov.php?funcao_js=parent.js_preenchepesquisa|0';
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
//MODULO: juridico
$clinicialmov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v50_data");
$clrotulo->label("v52_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat01_status?>">
       <?=@$Lat01_status?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('at01_status',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_codmov?>">
       <?=@$Lv56_codmov?>
    </td>
    <td> 
<?
db_input('v56_codmov',6,$Iv56_codmov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat02_codatend?>">
       <?=@$Lat02_codatend?>
    </td>
    <td> 
<?
db_input('at02_codatend',6,$Iat02_codatend,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_inicial?>">
       <?
       db_ancora(@$Lv56_inicial,"js_pesquisav56_inicial(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v56_inicial',8,$Iv56_inicial,true,'text',$db_opcao," onchange='js_pesquisav56_inicial(false);'")
?>
       <?
db_input('v50_data',8,$Iv50_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_codsit?>">
       <?
       db_ancora(@$Lv56_codsit,"js_pesquisav56_codsit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v56_codsit',6,$Iv56_codsit,true,'text',$db_opcao," onchange='js_pesquisav56_codsit(false);'")
?>
       <?
db_input('v52_descr',40,$Iv52_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_obs?>">
       <?=@$Lv56_obs?>
    </td>
    <td> 
<?
db_textarea('v56_obs',0,0,$Iv56_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_data?>">
       <?=@$Lv56_data?>
    </td>
    <td> 
<?
db_inputdata('v56_data',@$v56_data_dia,@$v56_data_mes,@$v56_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv56_id_login?>">
       <?
       db_ancora(@$Lv56_id_login,"js_pesquisav56_id_login(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v56_id_login',6,$Iv56_id_login,true,'text',$db_opcao," onchange='js_pesquisav56_id_login(false);'")
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav56_inicial(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicial1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v56_inicial.value+'&funcao_js=parent.js_mostrainicial';
  }
}
function js_mostrainicial(chave,erro){
  document.form1.v50_data.value = chave; 
  if(erro==true){ 
    document.form1.v56_inicial.focus(); 
    document.form1.v56_inicial.value = ''; 
  }
}
function js_mostrainicial1(chave1,chave2){
  document.form1.v56_inicial.value = chave1;
  document.form1.v50_data.value = chave2;
  db_iframe.hide();
}
function js_pesquisav56_codsit(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_situacao.php?funcao_js=parent.js_mostrasituacao1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_situacao.php?pesquisa_chave='+document.form1.v56_codsit.value+'&funcao_js=parent.js_mostrasituacao';
  }
}
function js_mostrasituacao(chave,erro){
  document.form1.v52_descr.value = chave; 
  if(erro==true){ 
    document.form1.v56_codsit.focus(); 
    document.form1.v56_codsit.value = ''; 
  }
}
function js_mostrasituacao1(chave1,chave2){
  document.form1.v56_codsit.value = chave1;
  document.form1.v52_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisav56_id_login(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.v56_id_login.value+'&funcao_js=parent.js_mostradb_usuarios';
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.v56_id_login.focus(); 
    document.form1.v56_id_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.v56_id_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_inicialmov.php?funcao_js=parent.js_preenchepesquisa|0';
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