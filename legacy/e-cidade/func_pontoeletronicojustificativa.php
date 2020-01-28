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

$clpontoeletronicojustificativa = new cl_pontoeletronicojustificativa;
$clpontoeletronicojustificativa->rotulo->label("rh194_sequencial");
$clpontoeletronicojustificativa->rotulo->label("rh194_descricao");
$clpontoeletronicojustificativa->rotulo->label("rh194_sigla");
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
          <td><label><?=$Lrh194_sequencial?></label></td>
          <td><?php db_input("rh194_sequencial", 10, $Irh194_sequencial, true, "text", 4, "", "chave_rh194_sequencial"); ?></td>
        </tr>

        <tr>
          <td><label><?=$Lrh194_descricao?></label></td>
          <td><?php db_input("rh194_descricao", 50, $Irh194_descricao, true, "text", 4, "", "chave_rh194_descricao");?></td>
        </tr>

        <tr>
          <td><label><?=$Lrh194_sigla?></label></td>
          <td><?php db_input("rh194_sigla", 10, $Irh194_sigla, true, "text", 4, "", "chave_rh194_sigla");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pontoeletronicojustificativa.hide();">
  </form>
      <?php

      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
      $aWhere       = array("rh194_instituicao = {$oInstituicao->getCodigo()}");
      $sCampos      = "rh194_sequencial, rh194_descricao, rh194_sigla";

      if(!isset($pesquisa_chave)) {

        if(!empty($chave_rh194_sequencial)) {
          $aWhere[] = "rh194_sequencial = {$chave_rh194_sequencial}";
        }

        if(!empty($chave_rh194_descricao)) {
          $sWhere[] = "rh194_descricao ilike '{$chave_rh194_descricao}%'";
        }

        if(!empty($chave_rh194_sigla)) {
          $sWhere[] = "rh194_sigla = '{$chave_rh194_sigla}'";
        }

        $sSql    = $clpontoeletronicojustificativa->sql_query(null, $sCampos, "rh194_sequencial", implode(' AND ', $aWhere));
        $repassa = array();

        if(isset($chave_rh194_sequencial)) {
          $repassa = array("chave_rh194_sequencial" => $chave_rh194_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if($pesquisa_chave != null && $pesquisa_chave != "") {

          $result = $clpontoeletronicojustificativa->sql_record($clpontoeletronicojustificativa->sql_query($pesquisa_chave));

          if($clpontoeletronicojustificativa->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."({$rh194_sequencial},false, '{$rh194_descricao}', '{$rh194_sigla}');</script>";
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
js_tabulacaoforms("form2","chave_rh194_sequencial",true,1,"chave_rh194_sequencial",true);
</script>
