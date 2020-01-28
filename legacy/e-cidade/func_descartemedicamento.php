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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_descartemedicamento_classe.php");
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldescartemedicamento = new cl_descartemedicamento;
$cldescartemedicamento->rotulo->label("sd107_sequencial");
$cldescartemedicamento->rotulo->label("sd107_medicamento");

$oRotulo = new rotulocampo();
$oRotulo->label("m60_descr");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
  <script language='JavaScript' type='text/javascript' src='scripts/prototype.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label for="chave_sd107_sequencial" class="bold">Código Descarte:</label></td>
          <td><? db_input("sd107_sequencial",10,$Isd107_sequencial,true,"text",4,"","chave_sd107_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label for="chave_m60_descr" class="bold"><?=$Lsd107_medicamento?></label></td>
          <td><? db_input("m60_descr",30, $Im60_descr,true,"text",4,"","chave_m60_descr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_descartemedicamento.hide();">
  </form>
      <?php
      $aWhere   = array();
      $aWhere[] = "sd107_db_depart = " . db_getsession('DB_coddepto') ;
      if (!isset($pesquisa_chave)) {

        $sCampos  = " sd107_sequencial as DB_sd107_sequencial, sd107_medicamento as DB_descartemedicamento, ";
        $sCampos .= " m60_descr as DL_Medicamento, sd107_quantidade, m61_descr, descartemedicamento.sd107_motivo, ";
        $sCampos .= " sd107_data, sd107_hora, sd107_usuario DB_sd107_usuario, sd107_db_depart , ";
        $sCampos .= " sd107_quantidadetotal as db_sd107_quantidadetotal, sd107_unidadesaida as db_sd107_unidadesaida";

        if(isset($chave_sd107_sequencial) && (trim($chave_sd107_sequencial)!="") ){
	        $aWhere[] = "sd107_sequencial = {$chave_sd107_sequencial} ";
        }
        if(isset($chave_m60_descr) && (trim($chave_m60_descr)!="") ){
	       $aWhere[] = " m60_descr like '{$chave_m60_descr}%' ";
        }

        $sOrder = "sd107_data desc, sd107_hora desc";
        $sWhere = implode(" and ", $aWhere);
        $sql    = $cldescartemedicamento->sql_query_dados_descarte("", $sCampos, $sOrder, $sWhere);

        $repassa = array();
        if(isset($chave_m60_descr)){
          $repassa = array("chave_sd107_sequencial"=>$chave_sd107_sequencial,"chave_m60_descr"=>$chave_m60_descr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';

      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $result = $cldescartemedicamento->sql_record($cldescartemedicamento->sql_query($pesquisa_chave));
          if ($cldescartemedicamento->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd107_medicamento',false);</script>";
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
js_tabulacaoforms("form2", "chave_m60_descr", true, 1, "chave_m60_descr", true);
</script>