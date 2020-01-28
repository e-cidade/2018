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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cemiterio_classe.php");
require_once("classes/db_cemiteriocgm_classe.php");
require_once("classes/db_cemiteriorural_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcemiterio      = new cl_cemiterio;
$clcemiteriocgm   = new cl_cemiteriocgm;
$clcemiteriorural = new cl_cemiteriorural;

$db_botao = false;
$db_opcao = 33;

if(isset($excluir)){

  /**
   * Tipo de Cemiterio
   *   1 - Urbano
   *   2 - Rural
   */
  $lTipoCemiterioUrbano = true;

  if(isset($cm15_i_cemiterio)){
    $iCodigoCemiterio  = $cm15_i_cemiterio;
  }

  if($tp == 2){

    $lTipoCemiterioUrbano = false;
    $iCodigoCemiterio     = $cm16_i_cemiterio;
  }

  /**
   * Validar se existe registro na lotecemit ou na sepultamentos
   */
  $sSql  = "select count(*) as lvalidaexclusaocemiterio                                            ";
  $sSql .= "  from cemiterio                                                                       ";
  $sSql .= " where cm14_i_codigo = {$iCodigoCemiterio}                                             ";
  $sSql .= "   and not exists( select 1 from sepultamentos where cm01_i_cemiterio = cm14_i_codigo )";
  $sSql .= "   and not exists( select 1 from quadracemit   where cm22_i_cemiterio = cm14_i_codigo )";

  $rsCemiterio = db_query($sSql);
  $lValidaExclusaoCemiterio = db_utils::fieldsMemory($rsCemiterio,0)->lvalidaexclusaocemiterio;

  if( $lValidaExclusaoCemiterio == 0 ){

     db_msgbox("Cemitério não pode ser excluído, pois possui sepultamentos vinculados.");
     db_redireciona('cem1_cemiterio003.php');
     return false;
  }

  db_inicio_transacao();
  $db_opcao = 3;
  if($lTipoCemiterioUrbano){

   $clcemiteriocgm->excluir($cm15_i_cemiterio);
   $clcemiterio->excluir($cm15_i_cemiterio);
  }else{

   $clcemiteriorural->excluir($cm16_i_cemiterio);
   $clcemiterio->excluir($cm16_i_cemiterio);
  }

  db_fim_transacao();
}else if(isset($chavepesquisa)){

   $db_opcao = 3;
   if($tp == 2){

    $result = $clcemiteriorural->sql_record($clcemiteriorural->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
   }else{

    $result = $clcemiteriocgm->sql_record($clcemiteriocgm->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
   }
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">

      <form>
        <table>
          <tr>
            <td>
             <tr>
                <td><strong>Tipo de Cemitério:</strong></td>
                <td><?php
                      $x = array('0'=>'Selecione','1'=>'Urbano','2'=>'Rural');
                      db_select('tp',$x,true,1,"onchange='submit()'");
                    ?>
                </td>
             </tr>
        </table>
      </form>

      <?php

       if(@$tp == 1){
        include("forms/db_frmcemiteriocgm.php");
       }else if(@$tp == 2){
        include("forms/db_frmcemiteriorural.php");
       }
      ?>
  </div>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clcemiterio->erro_status=="0"){
    $clcemiterio->erro(true,false);
  }else{
    $clcemiterio->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>