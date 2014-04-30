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

//MODULO: configuracoes
$cldb_config->rotulo->label();
$cldb_usuarios->rotulo->label();
      if($db_opcao==1){
 	   $db_action="con1_db_usuariosperfil001.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="con1_db_usuariosperfil002.php";
      }
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap><b>Cod. Perfil:</b></td>
    <td> 
<?
db_input('id_usuario',10,0,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>   
      <td nowrap><b>
      <?
         db_ancora("Nome do Perfil:","js_cgmlogin(true)",$db_opcao);
      ?></b>
      </td>
      <td> 
      <?
       db_input('nome',20,@$Inome,true,'text',$db_opcao,"");
      ?>
       </td>
     </tr>
  <tr>   
      <td nowrap align="right"><b>Login:</b></td>
      <td> 
      <?
       db_input('login',20,@$Ilogin,true,'text',$db_opcao);
      ?>
       </td>
  </tr>
  <tr>
    <td nowrap align="right" title="<?=@$Tcodigo?>"><b>Instituicao:</b></td> 
    <td nowrap>
    <?
       $res_instit = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo,substr(nomeinst,1,40)"));
       if(isset($id_usuario) && $id_usuario != ""){
          $record_select = pg_exec("select id_instit from db_userinst where id_usuario = $id_usuario");
       }else{
          $record_select = "";
       }
       db_selectmultiple("id_instit",$res_instit,10,$db_opcao,"", "", "", $record_select );

    ?>
    </td>
  </tr>
  </table>
  </center>
<br>  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==1||$db_opcao==2||$db_opcao==22?"Onclick='return js_submit();'":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_submit(){
}		
function js_cgmlogin(mostra){
  var login = document.form1.login.value;
  if (mostra == true){
       js_OpenJanelaIframe('','db_iframe2','func_db_usuariosalt.php?usuext=2&funcao_js=parent.js_mostracgm|nome|login','Pesquisa',true);
  }
}
function js_mostracgm(nome,login){
  document.form1.nome.value  = nome;
  document.form1.login.value = login;
  db_iframe2.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuariosalt.php?usuext=2&funcao_js=parent.js_preenchepesquisa|id_usuario','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_usuarios.hide();  
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>  
}
</script>