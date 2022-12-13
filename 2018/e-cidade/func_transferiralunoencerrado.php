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

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


$oRotulo = new rotulocampo();
$oRotulo->label('ed47_i_codigo');
$oRotulo->label('ed47_v_nome');
$oRotulo->label('bi06_titulo');

$iEscola = db_getsession('DB_coddepto');



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
          <td><label for="chave_ed47_i_codigo"><?=$Led47_i_codigo?></label></td>
          <td><?php db_input("ed47_i_codigo", 10, $Ied47_i_codigo, true, "text", 4, "", "chave_ed47_i_codigo"); ?></td>
        </tr>
        <tr>
          <td><label for="chave_ed47_v_nome"><?=$Led47_v_nome?></label></td>
          <td><?php db_input("ed47_v_nome", 30, $Ied47_v_nome, true, "text", 4, "", "chave_ed47_v_nome");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_transferiralunoencerrado.hide();">
  </form>
  <?php

    $sCampos  = "ed47_i_codigo, trim(ed47_v_nome) as ed47_v_nome, to_char(ed137_data, 'DD/MM/YYYY HH24:MI:SS')::varchar as ed137_data, ";
    $sCampos .= " ed60_i_codigo as db_ed60_i_codigo, ed137_sequencial as db_transferencia ";
    $aWhere   = array();

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from ( select max(ed60_i_codigo) , ed60_i_aluno ";
    $sSql .= "            from matricula  ";
    $sSql .= "           group by ed60_i_aluno) as x ";
    $sSql .= "   join matricula                  on matricula.ed60_i_codigo     = x.max ";
    $sSql .= "   join transferencialotematricula on transferencialotematricula.ed138_matricula = matricula.ed60_i_codigo ";
    $sSql .= "   join transferencialote          on transferencialote.ed137_sequencial = transferencialotematricula.ed138_transferencialote ";
    $sSql .= "   join aluno                      on aluno.ed47_i_codigo     = matricula.ed60_i_aluno ";
    $sSql .= "   join turma                      on turma.ed57_i_codigo     = matricula.ed60_i_turma ";
    $sSql .= "  where ed57_i_escola = {$iEscola} ";

    $sOrdem = " order by ed47_v_nome";
    if ( !isset($pesquisa_chave) ) {

      if (isset($chave_ed47_i_codigo) && (trim($chave_ed47_i_codigo)!="") ){
        $aWhere[] = " ed47_i_codigo = {$chave_ed47_i_codigo} ";
      } else if (isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome)!="") ) {
        $aWhere[] = " ed47_v_nome ilike '{$chave_ed47_v_nome}%' ";
      }

      $sWhere = implode(" and ", $aWhere);
      if ( !empty($sWhere) ) {
        $sSql .= " and {$sWhere} ";
      }
      $sSql .= $sOrdem;

      $repassa = array();
      if ( isset($chave_ed47_v_nome) ) {
        $repassa = array("chave_ed47_i_codigo" => $chave_ed47_i_codigo, "chave_ed47_v_nome" => $chave_ed47_v_nome);
      }
      echo '<div class="container">';
      echo '  <fieldset>';
      echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      echo '  </fieldset>';
      echo '</div>';
    } else if (!empty($pesquisa_chave) ) {

      $sSql .= " and ed47_i_codigo = {$pesquisa_chave} ";
      $sSql .= $sOrdem;

      $rs = db_query($sSql);
      if ($rs && pg_num_rows($rs) > 0) {

        db_fieldsmemory( $rs, 0 );
        echo "<script>".$funcao_js."('{$ed47_v_nome}', false, '{$ed47_i_codigo}', '{$db_ed60_i_codigo}', '{$db_transferencia}');</script>";
      } else {
        echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
      }
    } else {
      echo "<script>".$funcao_js."('',false);</script>";
    }
  ?>
</body>
</html>

<script>
  js_tabulacaoforms("form2","chave_ed47_v_nome",true,1,"chave_ed47_v_nome",true);
</script>
