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

//MODULO: fiscal
$clfandam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y41_descr");
$clrotulo->label("y70_codvist");
$clrotulo->label("y30_codnoti");
$clrotulo->label("y50_codauto");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty39_codandam?>">
       <?=@$Ly39_codandam?>
    </td>
    <td> 
<?
db_input('y39_codandam',20,$Iy39_codandam,true,'text',3,"");
db_input('y70_codvist',20,$Iy70_codvist,true,'hidden',3,"");
db_input('y30_codnoti',20,$Iy30_codnoti,true,'hidden',3,"");
db_input('y50_codauto',20,$Iy50_codauto,true,'hidden',3,"");
?>
       <?=@$Ly39_data?>
<?
if($db_opcao == 1){
  $y39_data_dia = date("d",db_getsession("DB_datausu"));
  $y39_data_mes = date("m",db_getsession("DB_datausu"));
  $y39_data_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('y39_data',@$y39_data_dia,@$y39_data_mes,@$y39_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty39_codtipo?>">
       <?
       db_ancora(@$Ly39_codtipo,"js_pesquisay39_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y39_codtipo',10,$Iy39_codtipo,true,'text',$db_opcao," onchange='js_pesquisay39_codtipo(false);'")
?>
       <?
db_input('y41_descr',50,$Iy41_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty39_obs?>">
       <?=@$Ly39_obs?>
    </td>
    <td> 
<?
db_textarea('y39_obs',3,50,$Iy39_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty39_id_usuario?>">
    </td>
    <td> 
<?
db_input('y39_id_usuario',5,$Iy39_id_usuario,true,'hidden',$db_opcao,"");
echo "<script>document.form1.y39_id_usuario.value = '".db_getsession("DB_id_usuario")."'</script>";
?>
       <?
db_input('nome',20,$Inome,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty39_hora?>">
       <?=@$Ly39_hora?>
    </td>
    <td> 
<?
db_input('y39_hora',5,$Iy39_hora,true,'text',$db_opcao,"");
if($db_opcao == 1){
  echo "<script>document.form1.y39_hora.value = '".db_hora()."'</script>";
}
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
if($db_opcao != 3 && $db_opcao != 33){
?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?
}
?>
</form>
<script>
function js_pesquisay39_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipoandam','func_tipoandam.php?funcao_js=parent.js_mostratipoandam1|y41_codtipo|y41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipoandam','func_tipoandam.php?pesquisa_chave='+document.form1.y39_codtipo.value+'&funcao_js=parent.js_mostratipoandam','Pesquisa',false);
  }
}
function js_mostratipoandam(chave,erro){
  document.form1.y41_descr.value = chave; 
  if(erro==true){ 
    document.form1.y39_codtipo.focus(); 
    document.form1.y39_codtipo.value = ''; 
  }
}
function js_mostratipoandam1(chave1,chave2){
  document.form1.y39_codtipo.value = chave1;
  document.form1.y41_descr.value = chave2;
  db_iframe_tipoandam.hide();
}
function js_pesquisay39_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.y39_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y39_id_usuario.focus(); 
    document.form1.y39_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y39_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_fandam','func_<?=(!isset($pesqandam) && !isset($auto)?"vistoriaandam.php":(!isset($auto)?"fiscalandam.php":"autoandam.php"))?>?funcao_js=parent.js_preenchepesquisa|y39_codandam','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_fandam.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22 && !isset($pesqandam) && !isset($auto)){
      echo " location.href = 'fis3_fandam002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3 && !isset($pesqandam)){
      echo " location.href = 'fis3_fandam003.php?abas=1&chavepesquisa='+chave;";
    }
    if($db_opcao == 2 || $db_opcao == 22 && isset($pesqandam) && !isset($auto)){
      echo " location.href = 'fis3_fandamnoti002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3 && isset($pesqandam) && !isset($auto)){
      echo " location.href = 'fis3_fandamnoti003.php?abas=1&chavepesquisa='+chave;";
    }
    if($db_opcao == 2 || $db_opcao == 22 && isset($auto)){
      echo " location.href = 'fis3_fandamauto002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3 && isset($auto)){
      echo " location.href = 'fis3_fandamauto003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}
</script>