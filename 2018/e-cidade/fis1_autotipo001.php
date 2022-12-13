<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_autotipo_classe.php");
require_once("classes/db_autoandam_classe.php");
require_once("classes/db_autoultandam_classe.php");
require_once("classes/db_fandam_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_fiscalprocrec_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clautotipo      = new cl_autotipo;
$clautoandam     = new cl_autoandam;
$clfandam        = new cl_fandam;
$clautoultandam  = new cl_autoultandam;
$clfiscalprocrec = new cl_fiscalprocrec;
$db_opcao = 1;
$db_botao = true;
global $y59_codauto;
global $y39_codandam;
$y59_codauto = @$y50_codauto;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  try {

    if (!empty($y59_codtipo) && !empty($y59_codauto)) {

      $sWhere        = " y59_codtipo = $y59_codtipo and y59_codauto = $y59_codauto";
      $sSql          = $clautotipo->sql_query_file(null, "*", null, $sWhere);
      $rsAutoLevanta = $clautotipo->sql_record($sSql);

      if ($clautotipo->numrows >= 1) {
        throw new Exception("Procedência já cadastrada neste Auto de Infração!");
      }
    }

    db_inicio_transacao();
    if ($y59_fator==""){
      $clautotipo->y59_fator='0';
    }
    if (strpos(trim($y59_valor),',')!=""){
	   $y59_valor=str_replace('.','',$y59_valor);
	   $y59_valor=str_replace(',','.',$y59_valor);
    }
    $clautotipo->y59_valor   = $y59_valor;
    $clautotipo->y59_codauto = $y59_codauto;
    $clautotipo->y59_codtipo = $y59_codtipo;
    $clautotipo->incluir(null);
    if($clautotipo->erro_status != 0 && $clautotipo->numrows <= 1){

      $clfandam->y39_codtipo = $andamento;
      $clfandam->y39_obs="0";
      $clfandam->y39_id_usuario= db_getsession("DB_id_usuario");
      $clfandam->y39_data=date("Y-m-d",db_getsession("DB_datausu"));
      $clfandam->y39_hora=db_hora();
      $clfandam->incluir(null);

      $clautoultandam->y16_codauto = $y59_codauto;
      $clautoultandam->y16_codandam = $clfandam->y39_codandam;
      $clautoultandam->incluir($y59_codauto,$clfandam->y39_codandam);
      $clautoandam->y58_codauto = $y59_codauto;
      $clautoandam->y58_codandam = $clfandam->y39_codandam;
      $clautoandam->incluir($y59_codauto,$clfandam->y39_codandam);
    }
    db_fim_transacao();

  } catch (Exception $oErro) {

    db_fim_transacao(true);
    $clautotipo->erro_status = 0;
    $clautotipo->erro_msg    = $oErro->getMessage();
  }
}elseif(isset($andamento) && $andamento != ""){

  db_inicio_transacao();
  $result = $clautoultandam->sql_record($clautoultandam->sql_query("",""," max(y16_codandam) as y16_codandam ",""," y16_codauto = $y59_codauto and y50_instit = ".db_getsession('DB_instit') ));
  if($clautoultandam->numrows > 0){

    db_fieldsmemory($result,0);
    $clfandam->y39_codtipo    = $andamento;
    $clfandam->y39_obs        = "0";
    $clfandam->y39_id_usuario = db_getsession("DB_id_usuario");
    $clfandam->y39_data       = date("Y-m-d",db_getsession("DB_datausu"));
    $clfandam->y39_hora       = db_hora();
    $clfandam->alterar($y16_codandam);
  }else{

    $clfandam->y39_codtipo    = $andamento;
    $clfandam->y39_obs        = "0";
    $clfandam->y39_id_usuario = db_getsession("DB_id_usuario");
    $clfandam->y39_data       = date("Y-m-d",db_getsession("DB_datausu"));
    $clfandam->y39_hora       = db_hora();
    $clfandam->incluir();

    $clautoultandam->y16_codauto  = $y59_codauto;
    $clautoultandam->y16_codandam = $clfandam->y39_codandam;
    $clautoultandam->incluir($y59_codauto,$clfandam->y39_codandam);

    $clautoandam->y58_codauto  = $y59_codauto;
    $clautoandam->y58_codandam = $clfandam->y39_codandam;
    $clautoandam->incluir($y59_codauto,$clfandam->y39_codandam);
  }
db_fim_transacao();
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
<body>
   <div class="container">
  	 <?php
  	   include("forms/db_frmautotipo.php");
  	 ?>
    </div>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clautotipo->erro_status=="0"){
    $clautotipo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautotipo->erro_campo!=""){
      echo "<script> document.form1.".$clautotipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautotipo->erro_campo.".focus();</script>";
    };
  }else{
    $clautotipo->erro(true,false);
    echo "<script>parent.iframe_autotipo.location.href='fis1_autotipo001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_receitas.location.href='fis1_autorec001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=".$y59_codauto."&y39_codandam=".$clfandam->y39_codandam."&abas=1';</script>\n";
  };
};
?>