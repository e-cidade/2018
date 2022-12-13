<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
include(modification("funcoes/db_func_recibounicageracao.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrecibounicageracao = new cl_recibounicageracao;
$clrecibounicageracao->rotulo->label("ar40_sequencial");

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
          <td><label><?php echo $Lar40_sequencial; ?></label></td>
          <td><?php db_input("ar40_sequencial", 10, $Iar40_sequencial, true, "text", 4, "", "chave_ar40_sequencial"); ?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" />
    <input name="limpar"    type="reset"  id="limpar"     value="Limpar" />
    <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_recibounicageracao.hide();" />
  </form>
  <?php

    $sWhere = "";

    if(!empty($sTipoGeracao)){
      $sWhere = "ar40_tipogeracao = '{$sTipoGeracao}'";
    }

    if(!empty($lValidaVencimento)){

      if($sWhere != ""){
        $sWhere .= " and ";
      }
      $sWhere .= "ar40_dtvencimento >= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
    }

    if(!empty($lValidaUsuario) && db_getsession("DB_administrador") == 0){

      if($sWhere != ""){
        $sWhere .= " and ";
      }
      $sWhere .= "ar40_db_usuarios = ".db_getsession("DB_id_usuario");
    }

    if(!empty($chave_ar40_sequencial)){
      $sWhere = " ar40_sequencial like '$chave_ar40_sequencial%' and {$sWhere}";
    }

    if(!isset($pesquisa_chave)){

      $sSql = $clrecibounicageracao->sql_query(null, $campos, "ar40_sequencial", $sWhere);

      $repassa = array();
      if(isset($chave_ar40_sequencial)){
        $repassa = array("chave_ar40_sequencial"=>$chave_ar40_sequencial, "chave_ar40_sequencial"=>$chave_ar40_sequencial);
      }
      ?>
      <div class="container">
        <fieldset>
          <legend>Resultado da Pesquisa</legend>
            <?php db_lovrot($sSql, 15, "()", "", $funcao_js ,"", "NoMe", $repassa); ?>
        </fieldset>
      </div>
    <?php
    }else{
      if($pesquisa_chave != null && $pesquisa_chave != ""){

        $sWhere = " ar40_sequencial = {$pesquisa_chave} " . ( !empty($sWhere) ? " and {$sWhere} " : '' );
        $sSql   = $clrecibounicageracao->sql_query(null, $campos, null, $sWhere);
        $result = $clrecibounicageracao->sql_record($sSql);

        if($clrecibounicageracao->numrows != 0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ar40_observacao',false);</script>";
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