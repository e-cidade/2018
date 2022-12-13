<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: biblioteca
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_exemplar_classe.php"));
include(modification("classes/db_biblioteca_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clexemplar   = new cl_exemplar;
$clbiblioteca = new cl_biblioteca;
$clrotulo     = new rotulocampo;
$depto        = db_getsession("DB_coddepto");
$result       = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo",""," bi17_coddepto = $depto"));
$clexemplar->rotulo->label("bi23_codigo");
$clrotulo->label("bi06_titulo");
$clrotulo->label("bi06_subtitulo");
$clrotulo->label("bi06_titulooriginal");
if($clbiblioteca->numrows!=0){
 db_fieldsmemory($result,0);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<form name="form2" method="post" action="" class="container">
  <fieldset >
    <legend>Filtros</legend>
    <table class="form-container" >
      <tr>
        <td title="<?=$Tbi23_codigo?>">
          <label for="chave_bi23_codigo"><?=$Lbi23_codigo?></label>
        </td>
        <td >
         <?db_input("bi23_codigo",10,$Ibi23_codigo,true,"text",4,"","chave_bi23_codigo");?>
        </td>
      </tr>

      <tr>
        <td width="4%" align="right" nowrap title="<?=$Tbi06_titulo?>">
          <label for="chave_bi06_titulo"><?=$Lbi06_titulo?></label>
        </td>
        <td width="96%" align="left" nowrap>
         <?db_input("bi06_titulo",50,$Ibi06_titulo,true,"text",4,"","chave_bi06_titulo");?>
        </td>
      </tr>

      <tr>
        <td>
          <label for="chave_bi06_subtitulo"><?=$Lbi06_subtitulo?></label>
        </td>
        <td >
          <?db_input("bi06_subtitulo",50,$Ibi06_subtitulo,true,"text",4,"","chave_bi06_subtitulo");?>
        </td>
      </tr>

      <tr>
        <td>
          <label for="chave_bi06_titulooriginal"><?=$Lbi06_titulooriginal?></label>
        </td>
        <td >
          <?db_input("bi06_titulooriginal",50,$Ibi06_titulooriginal,true,"text",4,"","chave_bi06_titulooriginal");?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_exemplar.hide();">
</form>
<?php
if(isset($campos)==false) {

  if(file_exists("funcoes/db_func_exemplar.php")==true){
    include(modification("funcoes/db_func_exemplar.php"));
  }else{
    $campos = "exemplar.*";
  }
}
$sql = "SELECT $campos
       FROM exemplar
        inner join acervo        on acervo.bi06_seq               = exemplar.bi23_acervo
        inner join aquisicao     on aquisicao.bi04_codigo         = exemplar.bi23_aquisicao
        left  join colecaoacervo on colecaoacervo.bi29_sequencial = acervo.bi06_colecaoacervo
        inner join biblioteca    on biblioteca.bi17_codigo        = acervo.bi06_biblioteca
       AND bi23_situacao   = 'S'
       AND bi06_biblioteca = $bi17_codigo
       AND bi17_coddepto   = $depto
      ";
if(isset($chave_bi23_codigo) && (trim($chave_bi23_codigo)!="") ) {
  $sql .= " AND bi23_codigo = $chave_bi23_codigo ORDER BY bi06_titulo";
}else if(isset($chave_bi06_titulo) && (trim($chave_bi06_titulo)!="") ){
  $sql .= " AND bi06_titulo like '$chave_bi06_titulo%' ORDER BY bi06_titulo";
}

if ( !empty($chave_bi06_subtitulo) ) {
  $sql .= " AND bi06_subtitulo ilike '{$chave_bi06_subtitulo}'";
}

if ( !empty($chave_bi06_titulooriginal) ) {
  $sql .= " AND bi06_titulooriginal ilike '{$chave_bi06_titulooriginal}'";
}

$repassa = array();
if(isset($chave_bi23_codigo)){
  $repassa = array("chave_bi23_codigo"=>$chave_bi23_codigo,"chave_bi06_titulo"=>$chave_bi06_titulo);
}

if (!isset($pesquisa_chave)) {

  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
  echo '  </fieldset>';
  echo '</div>';
} else {

  if($pesquisa_chave!=null && $pesquisa_chave!=""){

    $sql   .= " AND bi23_codbarras = $pesquisa_chave ORDER BY bi06_titulo";
    $result = db_query($sql);
    $linhas = pg_num_rows($result);
    if ($linhas!=0) {

      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi06_titulo',$bi23_codigo,false);</script>";
    } else {
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
    }
  } else {
    echo "<script>".$funcao_js."('','',false);</script>";
  }
}
   ?>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_bi23_codigo",true,1,"chave_bi23_codigo",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
