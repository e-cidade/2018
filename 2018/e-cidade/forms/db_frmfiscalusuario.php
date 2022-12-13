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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clfiscalusuario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y30_data");
$clrotulo->label("y39_codandam");
$clrotulo->label("nome");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_fiscais.location.href='fis1_fiscalusuario002.php?chavepesquisa=$y38_codnoti&chavepesquisa1=$y38_id_usuario&y39_codandam=$y39_codandam'</script>";}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_fiscais.location.href='fis1_fiscalusuario003.php?chavepesquisa=$y38_codnoti&chavepesquisa1=$y38_id_usuario&y39_codandam=$y39_codandam'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty38_codnoti?>">
       <?
       db_ancora(@$Ly38_codnoti,"js_pesquisay38_codnoti(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y38_codnoti',20,$Iy38_codnoti,true,'text',3," onchange='js_pesquisay38_codnoti(false);'");
db_input('y39_codandam',20,$Iy39_codandam,true,'hidden',3,"")
?>
       <?
db_input('y30_data',10,$Iy30_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty38_id_usuario?>">
       <?
       db_ancora(@$Ly38_id_usuario,"js_pesquisay38_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y38_id_usuario',5,$Iy38_id_usuario,true,'text',$db_opcao," onchange='js_pesquisay38_id_usuario(false);'");
db_input('y38_id_usuario',5,$Iy38_id_usuario,true,'hidden',$db_opcao," ","y38_id_usuario_old");
if($db_opcao == 2){
  echo "<script>document.form1.y38_id_usuario_old.value='$y38_id_usuario'</script>";
}
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty38_obs?>">
       <?=@$Ly38_obs?>
    </td>
    <td> 
<?
db_textarea('y38_obs',3,50,$Iy38_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_fiscalusuario001.php?y38_codnoti=<?=$y38_codnoti?>'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("y38_codnoti"=>@$y38_codnoti,"y38_id_usuario"=>@$y38_id_usuario);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y38_codnoti,y38_id_usuario,y38_obs";
    $cliframe_alterar_excluir->sql=$clfiscalusuario->sql_query("","","*",""," y38_codnoti = $y38_codnoti");
    $cliframe_alterar_excluir->legenda="Fiscais da Vistoria";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Usuário Cadastrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
   <?
   $clfiscalusuario->sql_record($clfiscalusuario->sql_query("","","*",""," y38_codnoti = $y38_codnoti"));
   if($clfiscalusuario->numrows == 0){  
   ?>
   <p align="center"><small>*A Notificação deve ter no mínimo um fiscal cadastrado!</small></p>
   <?
   }
   ?>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_setatabulacao(){
  js_tabulacaoforms("form1","y38_id_usuario",true,1,"y38_id_usuario",true);
}
function js_pesquisay38_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuariosdepto.php?fiscal=1&funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuariosdepto.php?fiscal=1&pesquisa_chave='+document.form1.y38_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y38_id_usuario.focus(); 
    document.form1.y38_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y38_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_fiscalusuario','func_fiscalusuario.php?funcao_js=parent.js_preenchepesquisa|y38_codnoti|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_fiscalusuario.hide();
}
</script>