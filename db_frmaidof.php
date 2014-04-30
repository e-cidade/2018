<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: fiscal
$claidof->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q09_descr");
$clrotulo->label("q02_numcgm");
$clrotulo->label("nome");
$clrotulo->label("y20_id_usuario");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty08_codigo?>">
       <?=@$Ly08_codigo?>
    </td>
    <td> 
<?
db_input('y08_codigo',6,$Iy08_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_nota?>">
       <?
       db_ancora(@$Ly08_nota,"js_pesquisay08_nota(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y08_nota',5,$Iy08_nota,true,'text',$db_opcao," onchange='js_pesquisay08_nota(false);'")
?>
       <?
db_input('q09_descr',40,$Iq09_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_inscr?>">
       <?
       db_ancora(@$Ly08_inscr,"js_pesquisay08_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y08_inscr',6,$Iy08_inscr,true,'text',$db_opcao," onchange='js_pesquisay08_inscr(false);'")
?>
       <?
db_input('q02_numcgm',6,$Iq02_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_dtlanc?>">
       <?=@$Ly08_dtlanc?>
    </td>
    <td> 
<?
db_inputdata('y08_dtlanc',@$y08_dtlanc_dia,@$y08_dtlanc_mes,@$y08_dtlanc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_notain?>">
       <?=@$Ly08_notain?>
    </td>
    <td> 
<?
db_input('y08_notain',5,$Iy08_notain,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_notafi?>">
       <?=@$Ly08_notafi?>
    </td>
    <td> 
<?
db_input('y08_notafi',5,$Iy08_notafi,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_login?>">
       <?
       db_ancora(@$Ly08_login,"js_pesquisay08_login(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y08_login',8,$Iy08_login,true,'text',$db_opcao," onchange='js_pesquisay08_login(false);'")
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_numcgm?>">
       <?
       db_ancora(@$Ly08_numcgm,"js_pesquisay08_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y08_numcgm',6,$Iy08_numcgm,true,'text',$db_opcao," onchange='js_pesquisay08_numcgm(false);'")
?>
       <?
db_input('y20_id_usuario',8,$Iy20_id_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_obs?>">
       <?=@$Ly08_obs?>
    </td>
    <td> 
<?
db_textarea('y08_obs',0,0,$Iy08_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay08_nota(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_notasiss.php?funcao_js=parent.js_mostranotasiss1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_notasiss.php?pesquisa_chave='+document.form1.y08_nota.value+'&funcao_js=parent.js_mostranotasiss';
  }
}
function js_mostranotasiss(chave,erro){
  document.form1.q09_descr.value = chave; 
  if(erro==true){ 
    document.form1.y08_nota.focus(); 
    document.form1.y08_nota.value = ''; 
  }
}
function js_mostranotasiss1(chave1,chave2){
  document.form1.y08_nota.value = chave1;
  document.form1.q09_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisay08_inscr(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_issbase.php?funcao_js=parent.js_mostraissbase1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_issbase.php?pesquisa_chave='+document.form1.y08_inscr.value+'&funcao_js=parent.js_mostraissbase';
  }
}
function js_mostraissbase(chave,erro){
  document.form1.q02_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.y08_inscr.focus(); 
    document.form1.y08_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.y08_inscr.value = chave1;
  document.form1.q02_numcgm.value = chave2;
  db_iframe.hide();
}
function js_pesquisay08_login(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.y08_login.value+'&funcao_js=parent.js_mostradb_usuarios';
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y08_login.focus(); 
    document.form1.y08_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y08_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisay08_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_graficas.php?funcao_js=parent.js_mostragraficas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_graficas.php?pesquisa_chave='+document.form1.y08_numcgm.value+'&funcao_js=parent.js_mostragraficas';
  }
}
function js_mostragraficas(chave,erro){
  document.form1.y20_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y08_numcgm.focus(); 
    document.form1.y08_numcgm.value = ''; 
  }
}
function js_mostragraficas1(chave1,chave2){
  document.form1.y08_numcgm.value = chave1;
  document.form1.y20_id_usuario.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_aidof.php?funcao_js=parent.js_preenchepesquisa|0';
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