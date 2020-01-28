<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_lancamentotaxadiversos_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clrotulo->label("y120_sequencial");
$clrotulo->label("y120_cgm");
$clrotulo->label("y119_natureza");
$clrotulo->label("q02_inscr");
$clrotulo->label("inscricao_nome");
$clrotulo->label("z01_nome");

$cllancamentotaxadiversos = new cl_lancamentotaxadiversos;
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <?php
  db_app::load(array(
    "strings.js",
    "scripts.js",
    "prototype.js",
    "widgets/DBLookUp.widget.js"
  ));
  ?>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
<form name="form2" method="post" action="" class="container">
  <fieldset>
    <legend>Dados para Pesquisa</legend>
    <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
      <tr>
        <td><label><?=$Ly120_sequencial?></label></td>
        <td><? db_input("y120_sequencial",10,$Iy120_sequencial,true,"text",4,'class="field-size2"',"chave_y120_sequencial"); ?></td>
      </tr>
      <tr>
        <td>
          <label id="Ly120_cgm" for="chave_y120_cgm">
            <a href="#"><?=$Ly120_cgm?></a>
          </label>
        </td>
        <td>
          <? db_input("y120_cgm",10,$Iy120_cgm,true,"text",4,'class="field-size2" data="z01_numcgm"', 'chave_y120_cgm'); ?>
          <? db_input("cgm_nome",40,$Iz01_nome,true,"text",3,'class="field-size7" data="z01_nome"'); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label id="Lq02_inscr" for="chave_q02_inscr">
            <a href="#"><?=$Lq02_inscr?></a>
          </label>
        </td>
        <td>
          <?php
          db_input('q02_inscr', 10, $Iq02_inscr, true, 'text', 8, "class='field-size2' data='q02_inscr'", 'chave_q02_inscr');
          db_input("inscricao_nome",40,$Iz01_nome,true,"text",3,'class="field-size7" data="z01_nome"');
          ?>
        </td>
      </tr>
      <tr>
        <td><label><?=$Ly119_natureza?></label></td>
        <td><? db_input("y119_natureza",50,$Iy119_natureza,true,"text",4,'class="field-size9"',"chave_y119_natureza"); ?></td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" onclick="location.href=location.href" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lancamentotaxadiversos.hide();">
</form>
<?
if(!isset($pesquisa_chave)){
  if(isset($campos)==false){
    if(file_exists("funcoes/db_func_lancamentotaxadiversos.php")==true){
      include(modification("funcoes/db_func_lancamentotaxadiversos.php"));
    }else{
      $campos = "lancamentotaxadiversos.*";
    }
  }

  if(isset($chave_y120_sequencial) && (trim($chave_y120_sequencial)!="") ){
    $sql = $cllancamentotaxadiversos->sql_query_join_diversos($chave_y120_sequencial,$campos,"y120_sequencial");
  } else {

    $sWhereLancamentoDiversos = '';

    if(  isset($chave_y120_cgm)      && (trim($chave_y120_cgm) != "")
      || isset($chave_y119_natureza) && (trim($chave_y119_natureza) != "")
      || isset($chave_q02_inscr)     && (trim($chave_q02_inscr) != "")
    ) {

      $aWhereLancamentoDiversos = array();

      if(isset($chave_y120_cgm) && (trim($chave_y120_cgm)!="") ) {
        $aWhereLancamentoDiversos[] = "y120_cgm = $chave_y120_cgm ";
      }

      if(isset($chave_y119_natureza) && (trim($chave_y119_natureza)!="") ){
        $aWhereLancamentoDiversos[] = " y119_natureza ilike '$chave_y119_natureza%' ";
      }

      if(isset($chave_q02_inscr) && (trim($chave_q02_inscr)!="") ){
        $aWhereLancamentoDiversos[] = " y120_issbase = {$chave_q02_inscr} ";
      }

      $sWhereLancamentoDiversos = implode('and', $aWhereLancamentoDiversos);
    }

    $sql = $cllancamentotaxadiversos->sql_query_join_diversos("",$campos,"y120_sequencial",$sWhereLancamentoDiversos);
  }

  $repassa = array();
  if(isset($chave_y120_sequencial)){
    $repassa = array("chave_y120_sequencial"=>$chave_y120_sequencial,"chave_y120_sequencial"=>$chave_y120_sequencial);
  }
  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
  echo '  </fieldset>';
  echo '</div>';
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    $result = $cllancamentotaxadiversos->sql_record($cllancamentotaxadiversos->sql_query_join_diversos($pesquisa_chave));
    if($cllancamentotaxadiversos->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$y120_sequencial',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
    var oAncoraCGM                = new DBLookUp(
      $('Ly120_cgm'),
      $('chave_y120_cgm'),
      $('cgm_nome'),
      {
        sArquivo      : 'func_cgm.php',
        sObjetoLookUp : 'func_nome'
      }
    );

    var oAncoraInscricaoMunicipal = new DBLookUp(
      $('Lq02_inscr'),
      $('chave_q02_inscr'),
      $('inscricao_nome'),
      {
        sArquivo      : 'func_issbase.php',
        sObjetoLookUp : 'db_iframe_issbase'
      }
    );
  </script>
  <?
}
?>
<script>
  js_tabulacaoforms("form2","chave_y120_sequencial",true,1,"chave_y120_sequencial",true);
</script>