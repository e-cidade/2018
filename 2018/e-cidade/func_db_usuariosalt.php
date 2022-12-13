<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_db_usuarios_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$cldb_usuarios = new cl_db_usuarios;
$cldb_usuarios->rotulo->label("id_usuario");
$cldb_usuarios->rotulo->label("nome");
$cldb_usuarios->rotulo->label("usuarioativo");
$cldb_usuarios->rotulo->label("chave_usuarioativo");

?>
<style>

 #chave_nome {
   text-transform: uppercase;
 }
</style>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>

</script>

</head>
<body>
<div class="container">
 <form name="form2" method="post" action="" >
    <fieldset>
      <legend>Filtros</legend>
      <table class="form-container">
        <tr>
          <td  title="<?=$Tid_usuario?>">
            <label for="chave_id_usuario"><?=$Lid_usuario?></label>
          </td>
          <td >
            <?php
              db_input("id_usuario",10,$Iid_usuario,true,"text",4,"","chave_id_usuario");
            ?>
          </td>
        </tr>
        <tr>
          <td  title="<?=$Tnome?>">
            <label for="chave_nome"><?=$Lnome?></label>
          </td>
          <td >
            <?php
              db_input("nome",40,$Inome,true,"text",4,"","chave_nome");
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Tusuarioativo?>">
            <label for="chave_usuarioativo">Situação:</label></td>
          <td >
            <select id='chave_usuarioativo' name="chave_usuarioativo" >
              <option selected value='x' >Selecione</option>
              <option value='0' >Inativo</option>
              <option value='1' >Ativo</option>
              <option value='2' >Bloqueado</option>
              <option value='3' >Aguardando Ativação</option>
            </select>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_usuarios.hide();">
  </form>
</div>
<?php

  $aWhere = array();
  $dbwhere_usuext = " usuext = 0 ";
  if (isset($usuext) && trim(@$usuext) != ""){
       db_input("usuext",1,0,true,"hidden",3);
       $dbwhere_usuext = " usuext = $usuext ";
  }
  $aWhere[] = $dbwhere_usuext;

  if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
       if(file_exists("funcoes/db_func_db_usuarios.php")==true){
         include(modification("funcoes/db_func_db_usuarios.php"));
       }else{
       $campos = "db_usuarios.*";
       }
    }
    $campos .= " ,case
                   when usuarioativo = 0
                     then 'Inativo'::varchar
                   when usuarioativo = 1
                     then 'Ativo'::varchar
                   when usuarioativo = 2
                     then 'Bloqueado'::varchar
                   when usuarioativo = 3
                     then 'Aguardando Ativação'::varchar
                 end as dl_Situação ";

    if(isset($chave_id_usuario) && (trim($chave_id_usuario)!="") ){
      $aWhere[] = " id_usuario = {$chave_id_usuario} ";
    }else if(isset($chave_nome) && (trim($chave_nome)!="") ){
      $aWhere[] = " nome ilike '$chave_nome%' ";
    }
    if(isset($chave_usuarioativo) && trim($chave_usuarioativo)!=="x"){
      $aWhere[] = " usuarioativo = '$chave_usuarioativo' ";
    }

    $sql = $cldb_usuarios->sql_query("",$campos,"nome", implode(" and ", $aWhere));


    $repassa = array();
    if(isset($chave_nome)){
      $repassa = array(
        "chave_id_usuario"=>$chave_id_usuario,
        "chave_nome"=>$chave_nome,
        "chave_usuarioativo"=>@$chave_usuarioativo);
    }

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot($sql,30,"()","",$funcao_js,"","NoMe",$repassa);
    echo '  </fieldset>';
    echo '</div>';
  } else {

    if ($pesquisa_chave!=null && $pesquisa_chave!="") {

      $result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query(null,"*",null," usuarioativo = '1' $dbwhere_usuext and id_usuario=$pesquisa_chave"));
      if ($cldb_usuarios->numrows != 0) {

        db_fieldsmemory($result,0);
	      if(isset($campologin)){
          echo "<script>".$funcao_js."('$nome','$login',false);</script>";
  	    }else{
          echo "<script>".$funcao_js."('$nome',false);</script>";
  	    }
      } else {
  	    if(isset($campologin)){
  	      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,true);</script>";
              }else{
  	      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
  	    }
      }
    }else{
  	  if(isset($campologin)){
  	     echo "<script>".$funcao_js."('',false,false);</script>";
  	  }else{
  	     echo "<script>".$funcao_js."('',false);</script>";
  	  }
    }
  }
  ?>
</body>
</html>


<script type="text/javascript">
js_tabulacaoforms("form2","chave_nome",true,1,"chave_nome",true);
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
