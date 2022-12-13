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

//MODULO: atendimento
$clatendusucli->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
if($db_opcao==1){
  $db_action="ate1_atendusucli004.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="ate1_atendusucli005.php";
}else if($db_opcao==3||$db_opcao==33){
  $db_action="ate1_atendusucli006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat80_codatendcli?>">
      <?=@$Lat80_codatendcli?>
    </td>
    <td> 
      <?
      db_input('at80_codatendcli',6,$Iat80_codatendcli,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat80_id_usuario?>">
      <?
      db_ancora(@$Lat80_id_usuario,"js_pesquisaat80_id_usuario(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('at80_id_usuario',6,$Iat80_id_usuario,true,'text',$db_opcao," onchange='js_pesquisaat80_id_usuario(false);'")
      ?>
      <?
      db_input('nome',40,$Inome,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat80_obs?>">
      <?=@$Lat80_obs?>
    </td>
    <td> 
      <?
      db_textarea('at80_obs',3,49,$Iat80_obs,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat80_data?>">
      <?=@$Lat80_data?>
    </td>
    <td> 
      <?
      if(!isset($at80_data_dia)){
        $at80_data_dia = date("d",db_getsession("DB_datausu"));
        $at80_data_mes = date("m",db_getsession("DB_datausu"));
        $at80_data_ano = date("Y",db_getsession("DB_datausu"));
      }
      db_inputdata('at80_data',@$at80_data_dia,@$at80_data_mes,@$at80_data_ano,true,'text',3,"")
      ?>
      <?
      if(!isset($at80_hora)){
        $at80_hora = db_hora();
      }
      db_input('at80_hora',5,$Iat80_hora,true,'hidden',3,"")
      ?>
      <?
      if(!isset($at80_baixado)){
        $at80_baixado = "f";
      }
      db_input('at80_baixado',5,$Iat80_baixado,true,'hidden',3,"")
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat80_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atendusucli','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0');
  }else{
    if(document.form1.at80_id_usuario.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_atendusucli','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.at80_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
    }else{
      document.form1.nome.value = ''; 
    }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.at80_id_usuario.focus(); 
    document.form1.at80_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.at80_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_atendusucli','db_iframe_atendusucli','func_atendusucli.php?nrecebe=true&funcao_js=parent.js_preenchepesquisa|at80_codatendcli','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_atendusucli.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>