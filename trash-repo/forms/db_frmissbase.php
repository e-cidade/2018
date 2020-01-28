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
$clissbase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq02_inscr?>">
       <?=@$Lq02_inscr?>
    </td>
    <td> 
<?
db_input('q02_inscr',4,$Iq02_inscr,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_numcgm?>">
       <?
       db_ancora(@$Lq02_numcgm,"js_pesquisaq02_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q02_numcgm',4,$Iq02_numcgm,true,'text',$db_opcao," onchange='js_pesquisaq02_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_memo?>">
       <?=@$Lq02_memo?>
    </td>
    <td> 
<?
db_textarea('q02_memo',0,0,$Iq02_memo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_tiplic?>">
       <?=@$Lq02_tiplic?>
    </td>
    <td> 
<?
db_input('q02_tiplic',2,$Iq02_tiplic,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_fanta?>">
       <?=@$Lq02_fanta?>
    </td>
    <td> 
<?
db_input('q02_fanta',40,$Iq02_fanta,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_regjuc?>">
       <?=@$Lq02_regjuc?>
    </td>
    <td> 
<?
db_input('q02_regjuc',14,$Iq02_regjuc,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_inscmu?>">
       <?=@$Lq02_inscmu?>
    </td>
    <td> 
<?
db_input('q02_inscmu',14,$Iq02_inscmu,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_obs?>">
       <?=@$Lq02_obs?>
    </td>
    <td> 
<?
db_input('q02_obs',70,$Iq02_obs,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_dtcada?>">
       <?=@$Lq02_dtcada?>
    </td>
    <td> 
<?
db_inputdata('q02_dtcada',@$q02_dtcada_dia,@$q02_dtcada_mes,@$q02_dtcada_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_dtinic?>">
       <?=@$Lq02_dtinic?>
    </td>
    <td> 
<?
db_inputdata('q02_dtinic',@$q02_dtinic_dia,@$q02_dtinic_mes,@$q02_dtinic_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_dtbaix?>">
       <?=@$Lq02_dtbaix?>
    </td>
    <td> 
<?
db_inputdata('q02_dtbaix',@$q02_dtbaix_dia,@$q02_dtbaix_mes,@$q02_dtbaix_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tq02_capit?>">
       <?=@$Lq02_capit?>
    </td>
    <td> 
<?
db_input('q02_capit',15,$Iq02_capit,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq02_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.q02_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q02_numcgm.focus(); 
    document.form1.q02_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.q02_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_issbase.php?funcao_js=parent.js_preenchepesquisa|0';
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