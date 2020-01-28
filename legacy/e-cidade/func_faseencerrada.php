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
require_once(modification("classes/db_fase_classe.php"));

db_postmemory($_POST);
parse_str( $_SERVER["QUERY_STRING"] );

$oDaoFase = new cl_fase;
$oDaoFase->rotulo->label("mo04_codigo");
$oDaoFase->rotulo->label("mo04_desc");


$aWhere   = array();
$aWhere[] = " mo04_processada = false ";
$aWhere[] = " mo04_dtfim < '". date("Y-m-d") . "' " ;
$aWhere[] = " exists( select 1 from vagas where mo10_fase = mo04_codigo )" ;

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
          <td><label for="chave_mo04_codigo"><?=$Lmo04_codigo?></label></td>
          <td><? db_input("mo04_codigo", 10, $Imo04_codigo, true, "text", 4, "", "chave_mo04_codigo"); ?></td>
        </tr>
        <tr>
          <td><label for="chave_mo04_desc"><?=$Lmo04_desc?></label></td>
          <td><? db_input("mo04_desc", 20, $Imo04_desc, true, "text", 4, "", "chave_mo04_desc");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_fase.hide();">
  </form>
  <?php

    if( !isset($pesquisa_chave) ) {

      $sCampos  = " mo04_codigo,   ";
      $sCampos .= " mo04_desc,     ";
      $sCampos .= " mo04_anousu,   ";
      $sCampos .= " mo04_dtini,    ";
      $sCampos .= " mo04_dtfim,    ";
      $sCampos .= " mo04_datacorte ";

      if (isset($chave_mo04_codigo) && (trim($chave_mo04_codigo)!="") ) {
        $aWhere[] = " mo04_codigo = {$chave_mo04_codigo} ";
      }
      if(isset($chave_mo04_desc) && (trim($chave_mo04_desc)!="") ) {
        $aWhere[] = " mo04_desc like '{$chave_mo04_desc}%' ";
      }
      $sWhere = implode(" and ", $aWhere);
      $sSql   = $oDaoFase->sql_query("", $sCampos, "mo04_desc", $sWhere);

      $repassa = array();
      if(isset($chave_mo04_desc)){
        $repassa = array("chave_mo04_codigo"=>$chave_mo04_codigo,"chave_mo04_desc"=>$chave_mo04_desc);
      }

      echo '<div class="container">';
      echo '  <fieldset>';
      echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      echo '  </fieldset>';
      echo '</div>';
    } else {

      if ( !empty($pesquisa_chave) ) {

        $aWhere[] = " mo04_codigo = {$pesquisa_chave} ";
        $sWhere   = implode(" and ", $aWhere);
        $rsFase   = $oDaoFase->sql_record($oDaoFase->sql_query(null, "*", null, $sWhere));
        if ($oDaoFase->numrows!=0) {

          db_fieldsmemory($rsFase,0);
          echo "<script>".$funcao_js."('$mo04_desc',false, '$mo04_ciclo');</script>";
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
