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

//MODULO: saude
$clprontuarios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd02_c_nome");
$clrotulo->label("sd15_c_descr");
$clrotulo->label("sd22_c_descr");
$clrotulo->label("nome");
$clrotulo->label("sd23_i_cgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd24_i_id?>">
       <?=@$Lsd24_i_id?>
    </td>
    <td> 
<?
db_input('sd24_i_id',10,$Isd24_i_id,true,'text',3,"")
?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Tsd24_i_unidade?>">
       <?
       db_ancora(@$Lsd24_i_unidade,"js_pesquisasd24_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd24_i_unidade',10,$Isd24_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd24_i_unidade(false);' onKeyPress='tab(event, 3)'")
?>
       <?
db_input('sd02_c_nome',50,$Isd02_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="ano/mes">
     <strong>Ano/Mes</Strong>
    </td>
    <td>
     <input type="text" name="ano" value="<?=substr($sd24_c_atendimento,0,4)?>" size="4" maxlength="4" onKeyPress='tab(event, 4)' <?if($db_opcao == 2){ echo "disabled";}?> >
     <input type="text" name="mes" value="<?=substr($sd24_c_atendimento,4,2)?>" size="2" maxlength="2" onKeyPress='tab(event, 5)' <?if($db_opcao == 2){ echo "disabled";}?> >
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_cgm?>">
       <?
       db_ancora(@$Lsd24_i_cgm,"js_pesquisasd24_i_cgm(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd24_i_cgm',11,$Isd24_i_cgm,true,'text',$db_opcao,"onchange='js_pesquisasd24_i_cgm(false);' onKeyPress='tab(event,7)'")
?>
<?
db_input('z01_nome',50,@$Iz01_nome,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_grupoatend?>">
       <?
       db_ancora(@$Lsd24_i_grupoatend,"js_pesquisasd24_i_grupoatend(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd24_i_grupoatend',11,$Isd24_i_grupoatend,true,'text',$db_opcao," onchange='js_pesquisasd24_i_grupoatend(false);' onKeyPress='tab(event, 9)'")
?>
       <?
db_input('sd15_c_descr',50,$Isd15_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_c_cid?>">
       <?
       db_ancora(@$Lsd24_c_cid,"js_pesquisasd24_c_cid(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd24_c_cid',11,$Isd24_c_cid,true,'text',$db_opcao," onchange='js_pesquisasd24_c_cid(false);' onKeyPress='tab(event, 11)'")
?>
       <?
db_input('sd22_c_descr',50,$Isd22_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_c_motivo?>">
       <?=@$Lsd24_c_motivo?>
    </td>
    <td> 
<?
db_input('sd24_c_motivo',67,$Isd24_c_motivo,true,'text',$db_opcao,"onKeyPress='tab(event, 12)'")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="hidden" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="submit()">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
<?
 if($tp != 3){
  echo "document.form1.sd24_c_atendimento.focus();";
 }else{
  echo "document.form1.sd24_i_unidade.focus();";
 }
?>

function tab(event, form){
 e = event;
 k = e.keyCode;
 if(k == 13){
  document.form1[form].focus()
 }
}
function mascara_hora(hora,x)
 {
   var myhora = '';
   myhora = myhora + hora;
   if (myhora.length == 2)
   {
    myhora = myhora + ':';
    document.form1[x].value = myhora;
   }
   if (myhora.length == 5)
   {
    verifica_hora(x);
   }
 }

function verifica_hora(x)
{
 hrs = (document.form1[x].value.substring(0,2));
 min = (document.form1[x].value.substring(3,5));

 situacao = "";
// verifica hora
 if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) )
  {
   alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
   document.form1[x].focus();
  }
}

function js_pesquisasd24_i_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_cgm.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.sd24_i_cgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.Z01_nome.value = chave;
  if(erro==true){
    document.form1.sd24_i_cgm.focus();
    document.form1.sd24_i_cgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.sd24_i_cgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}

function js_pesquisasd24_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd24_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = ''; 
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.sd02_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd24_i_unidade.focus(); 
    document.form1.sd24_i_unidade.value = ''; 
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd24_i_unidade.value = chave1;
  document.form1.sd02_c_nome.value = chave2;
  db_iframe_unidades.hide();
}
function js_pesquisasd24_i_grupoatend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_grupoatend','func_grupoatend.php?funcao_js=parent.js_mostragrupoatend1|sd15_i_codigo|sd15_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_grupoatend.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_grupoatend','func_grupoatend.php?pesquisa_chave='+document.form1.sd24_i_grupoatend.value+'&funcao_js=parent.js_mostragrupoatend','Pesquisa',false);
     }else{
       document.form1.sd15_c_descr.value = ''; 
     }
  }
}
function js_mostragrupoatend(chave,erro){
  document.form1.sd15_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd24_i_grupoatend.focus(); 
    document.form1.sd24_i_grupoatend.value = ''; 
  }
}
function js_mostragrupoatend1(chave1,chave2){
  document.form1.sd24_i_grupoatend.value = chave1;
  document.form1.sd15_c_descr.value = chave2;
  db_iframe_grupoatend.hide();
}
function js_pesquisasd24_c_cid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cids','func_cids.php?funcao_js=parent.js_mostracids1|sd22_c_codigo|sd22_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd24_c_cid.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cids','func_cids.php?pesquisa_chave='+document.form1.sd24_c_cid.value+'&funcao_js=parent.js_mostracids','Pesquisa',false);
     }else{
       document.form1.sd22_c_descr.value = ''; 
     }
  }
}
function js_mostracids(chave,erro){
  document.form1.sd22_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd24_c_cid.focus(); 
    document.form1.sd24_c_cid.value = ''; 
  }
}
function js_mostracids1(chave1,chave2){
  document.form1.sd24_c_cid.value = chave1;
  document.form1.sd22_c_descr.value = chave2;
  db_iframe_cids.hide();
}
function js_pesquisasd24_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd24_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.sd24_i_usuario.focus(); 
    document.form1.sd24_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd24_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisasd24_c_atendimento(mostra){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_agendamentos','func_agendamentos.php?funcao_js=parent.js_mostraagendamentos1|sd23_c_atendimento|sd23_i_cgm','Pesquisa',true);
}
function js_mostraagendamentos1(chave1,chave2){
  document.form1.sd24_c_atendimento.value = chave1;
  db_iframe_agendamentos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_preenchepesquisa|sd24_i_id','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontuarios.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>