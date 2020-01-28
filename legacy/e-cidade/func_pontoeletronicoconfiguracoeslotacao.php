<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clpontoeletronicoconfiguracoeslotacao = new cl_pontoeletronicoconfiguracoeslotacao;
$clpontoeletronicoconfiguracoeslotacao->rotulo->label("rh195_sequencial");
$clpontoeletronicoconfiguracoeslotacao->rotulo->label("rh195_sequencial");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
  <script language='JavaScript' type='text/javascript' src='scripts/prototype.js'></script>
</head>
<body>
  <div class="container">
    <form name="form2" method="post" action="" class="container">
      <fieldset>
        <legend>Dados para Pesquisa</legend>
        <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
          <tr>
            <td><label><?=$Lrh195_sequencial?></label></td>
            <td><?php db_input("rh195_sequencial",19,$Irh195_sequencial,true,"text",4,"","chave_rh195_sequencial"); ?></td>
          </tr>

          <tr>
            <td>
              <label for="r70_codigo">Código da Lotação:</label>
            </td>
            <td>
              <input id="r70_codigo" name="r70_codigo" type="text" value=""/>
            </td>
          </tr>

          <tr>
            <td>
              <label for="r70_descr">Descrição da Lotação:</label>
            </td>
            <td>
              <input id="r70_descr" name="r70_descr" type="text" value="" />
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pontoeletronicoconfiguracoeslotacao.hide();">
    </form>
  </div>
      <?php

      $aWhere  = array();
      $sCampos = "rh195_sequencial, r70_codigo, r70_estrut, r70_descr";

      if(!isset($pesquisa_chave)) {

        if(!empty($chave_rh195_sequencial)) {
          $aWhere[] = "rh195_sequencial = {$chave_rh195_sequencial}";
        }

        if(!empty($r70_codigo)) {
          $aWhere[] = "r70_codigo = {$r70_codigo}";
        }

        if(!empty($r70_descr)) {
          $aWhere[] = "r70_descr ilike '{$r70_descr}%'";
        }

        $sql = $clpontoeletronicoconfiguracoeslotacao->sql_query("", $sCampos, "rh195_sequencial", implode(' AND ', $aWhere));

        $repassa = array();
        if(isset($chave_rh195_sequencial)) {
          $repassa = array("chave_rh195_sequencial" => $chave_rh195_sequencial);
        }

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if($pesquisa_chave != null && $pesquisa_chave != "") {

          $result = $clpontoeletronicoconfiguracoeslotacao->sql_record($clpontoeletronicoconfiguracoeslotacao->sql_query($pesquisa_chave));

          if($clpontoeletronicoconfiguracoeslotacao->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."({$rh195_sequencial},false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
	        echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_rh195_sequencial",true,1,"chave_rh195_sequencial",true);

$('chave_rh195_sequencial').addClassName('field-size2');
$('r70_codigo').addClassName('field-size2');
$('r70_descr').addClassName('field-size7');
</script>