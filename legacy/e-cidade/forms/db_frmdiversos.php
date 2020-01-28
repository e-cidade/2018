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

//MODULO: diversos
$cldiversos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("dv09_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdv05_coddiver?>">
       <?=@$Ldv05_coddiver?>
    </td>
    <td> 
<?
db_input('dv05_coddiver',10,$Idv05_coddiver,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_numcgm?>">
       <?
       db_ancora(@$Ldv05_numcgm,"js_pesquisadv05_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('dv05_numcgm',6,$Idv05_numcgm,true,'text',$db_opcao," onchange='js_pesquisadv05_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_dtinsc?>">
       <?=@$Ldv05_dtinsc?>
    </td>
    <td> 
<?
db_inputdata('dv05_dtinsc',@$dv05_dtinsc_dia,@$dv05_dtinsc_mes,@$dv05_dtinsc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_exerc?>">
       <?=@$Ldv05_exerc?>
    </td>
    <td> 
<?
db_input('dv05_exerc',4,$Idv05_exerc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_numpre?>">
       <?=@$Ldv05_numpre?>
    </td>
    <td> 
<?
db_input('dv05_numpre',8,$Idv05_numpre,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_vlrhis?>">
       <?=@$Ldv05_vlrhis?>
    </td>
    <td> 
<?
db_input('dv05_vlrhis',15,$Idv05_vlrhis,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_procdiver?>">
       <?
       db_ancora(@$Ldv05_procdiver,"js_pesquisadv05_procdiver(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('dv05_procdiver',5,$Idv05_procdiver,true,'text',$db_opcao," onchange='js_pesquisadv05_procdiver(false);'")
?>
       <?
db_input('dv09_descr',40,$Idv09_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_numtot?>">
       <?=@$Ldv05_numtot?>
    </td>
    <td> 
<?
db_input('dv05_numtot',4,$Idv05_numtot,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_privenc?>">
       <?=@$Ldv05_privenc?>
    </td>
    <td> 
<?
db_inputdata('dv05_privenc',@$dv05_privenc_dia,@$dv05_privenc_mes,@$dv05_privenc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_provenc?>">
       <?=@$Ldv05_provenc?>
    </td>
    <td> 
<?
db_inputdata('dv05_provenc',@$dv05_provenc_dia,@$dv05_provenc_mes,@$dv05_provenc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_diaprox?>">
       <?=@$Ldv05_diaprox?>
    </td>
    <td> 
<?
db_input('dv05_diaprox',4,$Idv05_diaprox,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_oper?>">
       <?=@$Ldv05_oper?>
    </td>
    <td> 
<?
db_inputdata('dv05_oper',@$dv05_oper_dia,@$dv05_oper_mes,@$dv05_oper_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_valor?>">
       <?=@$Ldv05_valor?>
    </td>
    <td> 
<?
db_input('dv05_valor',15,$Idv05_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_infla?>">
       <?=@$Ldv05_infla?>
    </td>
    <td> 
<?
db_input('dv05_infla',5,$Idv05_infla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdv05_obs?>">
       <?=@$Ldv05_obs?>
    </td>
    <td> 
<?
db_textarea('dv05_obs',0,0,$Idv05_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadv05_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.dv05_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.dv05_numcgm.focus(); 
    document.form1.dv05_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.dv05_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisadv05_procdiver(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_procdiver.php?funcao_js=parent.js_mostraprocdiver1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_procdiver.php?pesquisa_chave='+document.form1.dv05_procdiver.value+'&funcao_js=parent.js_mostraprocdiver';
  }
}
function js_mostraprocdiver(chave,erro){
  document.form1.dv09_descr.value = chave; 
  if(erro==true){ 
    document.form1.dv05_procdiver.focus(); 
    document.form1.dv05_procdiver.value = ''; 
  }
}
function js_mostraprocdiver1(chave1,chave2){
  document.form1.dv05_procdiver.value = chave1;
  document.form1.dv09_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_diversos.php?funcao_js=parent.js_preenchepesquisa|0';
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