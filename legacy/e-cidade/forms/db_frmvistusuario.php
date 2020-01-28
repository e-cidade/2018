<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clvistusuario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y70_data");
$clrotulo->label("y39_codandam");
$clrotulo->label("nome");
$aux = new cl_arquivo_auxiliar;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_fiscais.location.href='fis1_vistusuario002.php?chavepesquisa=$y75_codvist&chavepesquisa1=$y75_id_usuario&y39_codandam=$y39_codandam'</script>";}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_fiscais.location.href='fis1_vistusuario003.php?chavepesquisa=$y75_codvist&chavepesquisa1=$y75_id_usuario&y39_codandam=$y39_codandam'</script>";
}
?>
<form name="form1" method="post" action="" onsubmit="js_seleciona_fiscais();">
<center>


<table width="780" align=center style="margin-top:15px">
<tr><td align=center >
<p align="center"><small>*A vistoria deve ter no mínimo um fiscal cadastrado!</small></p>

<fieldset>
<legend><b>Cadastro de Fiscais</b></legend>

<table border="0" width=750 >
  <tr>
    <td nowrap title="<?=$Ty75_codvist?>">
    <?=$Ly75_codvist?>
    </td>
    <td>
<?
db_input('y75_codvist',5,$Iy75_codvist,true,'text',3,"");
db_input('y39_codandam',5,$Iy39_codandam,true,'hidden',3,"");
?>
       <?
db_input('y70_data',10,$Iy70_data,true,'hidden',3,'')
       ?>
    </td>
  </tr>

  <?
    if($db_opcao == 1 || $db_opcao == 11) {
  ?>
      <tr>
        <td colspan=2 align=center>
        <?
            $aux->cabecalho = "<strong>Fiscais</strong>";
            $aux->codigo = "id_usuario";
            $aux->descr  = "nome";
            $aux->nomeobjeto = 'y75_id_usuario';
            $aux->funcao_js = 'js_mostra';
		        $aux->funcao_js_hide = 'js_mostra1';
		        $aux->sql_exec  = "";
            $aux->func_arquivo = "func_db_usuariosdepto.php";
            $aux->parametros = "+'&fiscal=1'";
		        $aux->nomeiframe = "db_iframe_db_usuarios";
		        $aux->localjan = "";
		        $aux->onclick = "";
		        $aux->db_opcao = 2;
		        $aux->tipo = 2;
		        $aux->top = 0;
		        $aux->linhas = 10;
		        $aux->vwhidth = 400;
		        $aux->btn_lanca = "lancausuario";
		        $aux->funcao_gera_formulario();
        ?>
       </td>
      </tr>

   <?
    } else {
   ?>
   <tr>
    <td nowrap title="<?=$Ty75_id_usuario?>">
       <?
       db_ancora($Ly75_id_usuario,"js_pesquisay75_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td>
    <?
			db_input('y75_id_usuario',5,$Iy75_id_usuario,true,'text',$db_opcao," onchange='js_pesquisay75_id_usuario(false);'");
			if($db_opcao == 2){
			  db_input('y75_id_usuario',5,$Iy75_id_usuario,true,'hidden',$db_opcao," onchange='js_pesquisay75_id_usuario(false);'","y75_id_usuario_old");
			  echo "<script>document.form1.y75_id_usuario_old.value = '$y75_id_usuario'</script>";
			}
		?>
       <?
        db_input('nome',20,$Inome,true,'text',3,'');
       ?>
    </td>
  </tr>

   <? }	?>


  <tr>
    <td nowrap title="<?=$Ty75_obs?>">
       <?=$Ly75_obs?>
    </td>
    <td>
<?
db_textarea('y75_obs',3,50,$Iy75_obs,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>

  </fieldset>



  </td></tr>
  </table>



 <table border="0" align=center>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      if($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_vistusuario001.php?y75_codvist=<?=$y75_codvist?>&y79_codandam=<?=$y39_codandam?>'" >
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?php

     if(!empty($y75_codvist)){

      $chavepri= array("y75_codvist"=>$y75_codvist,"y75_id_usuario"=>$y75_id_usuario);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->campos="y75_codvist,y75_id_usuario,y75_obs,nome";
      $cliframe_alterar_excluir->sql=$clvistusuario->sql_query("",""," vistusuario.*,db_usuarios.*",""," y75_codvist = $y75_codvist");
      $cliframe_alterar_excluir->legenda="Fiscais da Vistoria";
      $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Usuário Cadastrado!</font>";
      $cliframe_alterar_excluir->textocabec ="darkblue";
      $cliframe_alterar_excluir->textocorpo ="black";
      $cliframe_alterar_excluir->fundocabec ="#aacccc";
      $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
      $cliframe_alterar_excluir->iframe_height ="170";
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     }
   ?>
   </td>
   <?
   if(!empty($y75_codvist)){

     $resultvistusuario = db_query($clvistusuario->sql_query("",""," vistusuario.*,db_usuarios.*",""," y75_codvist = $y75_codvist"));
     if(pg_numrows($resultvistusuario) != 0){

         echo "<script>
           tam = parent.document.formaba;
           for(i=0;i<tam.length;i++){
            if(tam[i].name == 'calculo'){
               parent.iframe_calculo.location.href = 'fis1_calculo001.php?y70_codvist=$y75_codvist';
               parent.document.formaba.calculo.disabled = false;
            }
           }
          </script>";
     }
   }
   ?>
 </tr>
  </table>

  </center>
</form>
<script>
function js_pesquisay75_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_mostravistorias1|y70_codvist|y70_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y75_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_data.value = chave;
  if(erro==true){
    document.form1.y75_codvist.focus();
    document.form1.y75_codvist.value = '';
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y75_codvist.value = chave1;
  document.form1.y70_data.value = chave2;
  db_iframe_vistorias.hide();
}
function js_pesquisay75_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuariosdepto.php?fiscal=1&funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuariosdepto.php?fiscal=1&pesquisa_chave='+document.form1.y75_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.y75_id_usuario.focus();
    document.form1.y75_id_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y75_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_vistusuario','func_vistusuario.php?funcao_js=parent.js_preenchepesquisa|y75_codvist|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_vistusuario.hide();
}


function js_seleciona_fiscais() {

  var ofiscais = $('y75_id_usuario');

  for(i = 0 ; i < ofiscais.options.length ; i++ ) {
    ofiscais.options[i].selected = true;
  }

}


</script>
<?
if(isset($y75_codvist)){
  echo "<script>js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?pesquisa_chave=$y75_codvist&funcao_js=parent.js_mostravistorias','Pesquisa',false);</script>";
}else{
  echo "<script>parent.document.formaba.fiscais.disabled=true</script>";
}
?>