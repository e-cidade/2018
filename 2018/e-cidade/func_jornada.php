<?php
/**
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_jornada_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$cljornada = new cl_jornada;
$cljornada->rotulo->label("rh188_sequencial");
$cljornada->rotulo->label("rh188_descricao");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
<form name="form2" method="post" action="" class="container">
  <fieldset>
    <legend>Dados para Pesquisa</legend>
    <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
      <tr>
        <td><label>Código</label></td>
        <td><? db_input("rh188_sequencial",10, 1, true, "text",4,"","chave_sequencial"); ?></td>
      </tr>
      <tr>
        <td><label>Descrição</label></td>
        <td><? db_input("rh188_descricao", 40, true,"text",4,"","chave_descricao");?></td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_jornada.hide();">
</form>
<?
if(!isset($pesquisa_chave)){

  $sWhereFixos = " true ";
  if (isset ($lMostraFixos) and $lMostraFixos == 'false') {
    $sWhereFixos = " rh188_fixo is false ";
  }

  if(isset($campos)==false){
    if(file_exists("funcoes/db_func_jornada.php")==true){
      require_once("funcoes/db_func_jornada.php");
    }else{
      $campos = "jornada.*";
    }
  }
  if(isset($chave_sequencial) && (trim($chave_sequencial)!="") ){
    $sql = $cljornada->sql_query(null,$campos,"rh188_sequencial", "rh188_sequencial = $chave_sequencial and $sWhereFixos");
  }else if(isset($chave_descricao) && (trim($chave_descricao)!="") ){
    $sql = $cljornada->sql_query("",$campos,"rh188_sequencial"," rh188_descricao like '$chave_descricao%' and $sWhereFixos");
  }else{
    $sql = $cljornada->sql_query("",$campos,"rh188_sequencial","$sWhereFixos");
  }
  $repassa = array();
  if(isset($chave_sequencial)){
    $repassa = array("chave_sequencial"=>$chave_sequencial,"chave_descricao=>$chave_desricao");
  }
  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
  echo '  </fieldset>';
  echo '</div>';
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    $result = $cljornada->sql_record($cljornada->sql_query($pesquisa_chave));
    if($cljornada->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$rh188_sequencial',false);</script>";
    }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
    }
  }else{
    echo "<script>".$funcao_js."('',false);</script>";
  }
}
?>
</body>
</html>