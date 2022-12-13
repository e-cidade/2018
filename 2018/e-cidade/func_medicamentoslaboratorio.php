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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_medicamentoslaboratorio_classe.php"));
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmedicamentoslaboratorio = new cl_medicamentoslaboratorio;
$clmedicamentoslaboratorio->rotulo->label("la43_abreviatura");
$clmedicamentoslaboratorio->rotulo->label("la43_nome");
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
          <td><label for='la43_abreviatura'><?=$Lla43_abreviatura?></label></td>
          <td><? db_input("la43_abreviatura",10,$Ila43_abreviatura,true,"text",4,"","chave_la43_abreviatura"); ?></td>
        </tr>
        <tr>
          <td><label for='la43_nome'><?=$Lla43_nome?></label></td>
          <td><? db_input("la43_nome",50,$Ila43_nome,true,"text",4,"","chave_la43_nome");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_medicamentoslaboratorio.hide();">
  </form>
    <?php
      if ( isset($campos) == false) {

        if(file_exists("funcoes/db_func_medicamentoslaboratorio.php")==true){
          include(modification("funcoes/db_func_medicamentoslaboratorio.php"));
        } else {
          $campos = "medicamentoslaboratorio.*";
        }
      }
      if (!isset($pesquisa_chave)) {

        $aWhere = array();
        if ( isset($chave_la43_abreviatura) && (trim($chave_la43_abreviatura)!="") ) {
	        $aWhere[] = " la43_abreviatura = '{$chave_la43_abreviatura}' ";
        }
        if(isset($chave_la43_nome) && (trim($chave_la43_nome)!="") ) {
	        $aWhere[] = " la43_nome like '$chave_la43_nome%' ";
        }
        $sWhere  = implode(' and ', $aWhere);
        $sSql    = $clmedicamentoslaboratorio->sql_query("",$campos, "la43_nome", $sWhere);
        $repassa = array();
        if (isset($chave_la43_nome)) {
          $repassa = array("chave_la43_abreviatura"=>$chave_la43_abreviatura, "chave_la43_nome"=>$chave_la43_nome);
        }

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave != "") {

          /**
           * Função de pesquisa criada e alterada para atender exclusivamente a view LancarMedicamentoExame.classe.js
           */

          $sWhere = " la43_abreviatura ilike '$pesquisa_chave' ";
          $result = $clmedicamentoslaboratorio->sql_record($clmedicamentoslaboratorio->sql_query(null, $campos, null, $sWhere));
          if ($clmedicamentoslaboratorio->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."(false, '$la43_nome', '$la43_abreviatura', $la43_sequencial );</script>";
          } else {
	         echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado', '', '');</script>";
          }
        } else {
	       echo "<script>".$funcao_js."(false, '');</script>";
        }
      }
      ?>
</body>
</html>
<script>
js_tabulacaoforms("form2", "chave_la43_nome", true, 1, "chave_la43_nome", true);
</script>
