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

//MODULO: issqn
$clviabilidade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("q03_descr");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_numero");
$clrotulo->label("z01_compl");
$clrotulo->label("z01_bairro");
$clrotulo->label("z01_telef");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq29_codigo?>">
       <?=@$Lq29_codigo?>
    </td>
    <td> 
<?
db_input('q29_codigo',10,$Iq29_codigo,true,'text',3,"")
?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_data?>">
       <?=@$Lq29_data?>
    </td>
    <td> 
<?
db_inputdata('q29_data',@$q29_data_dia,@$q29_data_mes,@$q29_data_ano,true,'text',$db_opcao,"")
?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_numcgm?>">
       <?
       db_ancora(@$Lq29_numcgm,"js_pesquisaq29_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q29_numcgm',6,$Iq29_numcgm,true,'text',$db_opcao," onchange='js_pesquisaq29_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_cgccpf?>">
       <?=@$Lz01_cgccpf?>
    </td>
    <td>
<?
db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',3,'')
?>
    </td>

  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_ender?>">
       <?=@$Lz01_ender?>
    </td>
    <td>
<?
db_input('z01_ender',40,$Iz01_ender,true,'text',3,'')
?>
    </td>


  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_numero?>">
       <?=@$Lz01_numero?>
    </td>
    <td>
<?
db_input('z01_numero',8,$Iz01_numero,true,'text',3,'')
?>
    </td>


  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_compl?>">
       <?=@$Lz01_compl?>
    </td>
    <td>
<?
db_input('z01_compl',40,$Iz01_compl,true,'text',3,'')
?>
    </td>


  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_bairro?>">
       <?=@$Lz01_bairro?>
    </td>
    <td>
<?
db_input('z01_bairro',40,$Iz01_bairro,true,'text',3,'')
?>
    </td>


  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_telef?>">
       <?=@$Lz01_telef?>
    </td>
    <td>
<?
db_input('z01_telef',12,$Iz01_telef,true,'text',3,'')
?>
    </td>

  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_lograd?>">
       <?
       db_ancora(@$Lq29_lograd,"js_pesquisaq29_lograd(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q29_lograd',6,$Iq29_lograd,true,'text',$db_opcao," onchange='js_pesquisaq29_lograd(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_numero?>">
       <?=@$Lq29_numero?>
    </td>
    <td> 
<?
db_input('q29_numero',5,$Iq29_numero,true,'text',$db_opcao,"")
?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_complem?>">
       <?=@$Lq29_complem?>
    </td>
    <td> 
<?
db_input('q29_complem',20,$Iq29_complem,true,'text',$db_opcao,"")
?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_bairro?>">
       <?
       db_ancora(@$Lq29_bairro,"js_pesquisaq29_bairro(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q29_bairro',6,$Iq29_bairro,true,'text',$db_opcao," onchange='js_pesquisaq29_bairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_ativ?>">
       <?
       db_ancora(@$Lq29_ativ,"js_pesquisaq29_ativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q29_ativ',6,$Iq29_ativ,true,'text',$db_opcao," onchange='js_pesquisaq29_ativ(false);'")
?>
       <?
db_input('q03_descr',40,$Iq03_descr,true,'text',3,'')
       ?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_escrito?>">
    <?
       db_ancora(@$Lq29_escrito,"js_pesquisaq29_escrito(true);",$db_opcao);
    ?>
    </td>
    <td> 
<?
db_input('q29_escrito',6,$Iq29_escrito,true,'text',$db_opcao,"")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'','z01_nomeescrito')
       ?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq29_tipo?>">
       <?=@$Lq29_tipo?>
    </td>
   <td>
<?
$x = array('I'=>'Inclusão','A'=>'Alteração');
db_select('q29_tipo',$x,true,2);
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq29_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?campos=cgm.z01_numcgm,z01_nome,z01_ender,z01_cgccpf as db_z01_cgccpf,z01_numero as db_z01_numero,z01_compl as db_z01_compl,z01_bairro as db_z01_bairro,z01_telef as db_z01_telef&funcao_js=parent.js_mostracgm1|0|1|2|3|4|5|6|7';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?campos=cgm.z01_numcgm,z01_nome,z01_ender,z01_cgccpf,z01_numero,z01_compl,z01_bairro,z01_telef&pesquisa_chave='+document.form1.q29_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q29_numcgm.focus(); 
    document.form1.q29_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8){
  document.form1.q29_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.z01_ender.value = chave3;
  document.form1.z01_cgccpf.value = chave4;
  document.form1.z01_numero.value = chave5;
  document.form1.z01_compl.value = chave6;
  document.form1.z01_bairro.value = chave7;
  document.form1.z01_telef.value = chave8;
  db_iframe.hide();
}
function js_pesquisaq29_lograd(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.q29_lograd.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.q29_lograd.focus(); 
    document.form1.q29_lograd.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.q29_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisaq29_bairro(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_bairro.php?funcao_js=parent.js_mostrabairro1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_bairro.php?pesquisa_chave='+document.form1.q29_bairro.value+'&funcao_js=parent.js_mostrabairro';
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.q29_bairro.focus(); 
    document.form1.q29_bairro.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.q29_bairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisaq29_escrito(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_cadescrito.php?campos=q86_numcgm,z01_nome&funcao_js=parent.js_mostraescrito1|0|1|';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_cadescrito.php?campos=q86_numcgm,z01_nome&pesquisa_chave='+document.form1.q29_escrito.value+'&funcao_js=parent.js_mostraescrito';
  }
}
function js_pesquisaq29_ativ(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ativid.php?funcao_js=parent.js_mostraativid1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ativid.php?pesquisa_chave='+document.form1.q29_ativ.value+'&funcao_js=parent.js_mostraativid';
  }
}
function js_mostraescrito(chave,erro){
  if(erro==true){ 
    document.form1.q29_escrito.focus(); 
    document.form1.q29_escrito.value = ''; 
  }
}
function js_mostraescrito1(chave1,chave2){
  document.form1.q29_escrito.value = chave1;
  document.form1.z01_nomeescrito.value = chave2;
  db_iframe.hide();
}
function js_mostraativid(chave,erro){
  document.form1.q03_descr.value = chave; 
  if(erro==true){ 
    document.form1.q29_ativ.focus(); 
    document.form1.q29_ativ.value = ''; 
  }
}
function js_mostraativid1(chave1,chave2){
  document.form1.q29_ativ.value = chave1;
  document.form1.q03_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_viabilidade.php?funcao_js=parent.js_preenchepesquisa|0';
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