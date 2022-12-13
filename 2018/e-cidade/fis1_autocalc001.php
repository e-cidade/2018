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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoAuto       = db_utils::getDao('auto');
$oDaoArrecad    = db_utils::getDao('arrecad');
$oDaoArrecant   = db_utils::getDao('arrecant');
$oDaoAutonumpre = db_utils::getDao('autonumpre');

if( isset($calcular) ){

  /**
   * Chama pl do calculo
   */
  $rsCalculo = $oDaoAuto->sql_calculo( $y50_codauto );
  $sInfo     = db_utils::fieldsmemory($rsCalculo, 0)->fc_autodeinfracao;
  echo "<script>parent.iframe_calculo.location.href='fis1_autocalc001.php?y50_codauto=".$y50_codauto."&info1=$sInfo';</script>";
  exit;

}elseif( !isset($info1) ){

    $info   = "Auto não calculado.";
    $result = $oDaoAutonumpre->sql_record($oDaoAutonumpre->sql_query(null,"*",null,"y50_codauto = {$y50_codauto}"));
    if($oDaoAutonumpre->numrows > 0){

      db_fieldsmemory($result,0);
      $result  = $oDaoArrecad->sql_record($oDaoArrecad->sql_query("","arrecad.*",""," arrecad.k00_numpre = $y17_numpre and arreinstit.k00_instit = ".db_getsession('DB_instit') ));
      $result1 = $oDaoArrecant->sql_record($oDaoArrecant->sql_query("","*",""," arrecant.k00_numpre = $y17_numpre"));

      if($oDaoArrecad->numrows > 0){
        $info = "Auto já calculado. Numpre = $y17_numpre";
      }elseif($oDaoArrecant->numrows > 0){
        $info = "Auto já Pago. Numpre = $y17_numpre";
      }
    }

}else{

  $info       = $info1;
  $desabilita = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container">

    <form method="post" name="form1" action="">
      <?php db_input('y50_codauto',10,'',true,'hidden',3); ?>

      <fieldset style="width: 650px; !important">

        <legend>Cálculo do Auto de Infração</legend>
        <table>
          <tr>
            <td><strong><?=$info?></strong></td>
          </tr>
        </table>
    	</fieldset>

      <input name="calcular" type="submit" value="Calcular" />
    </form>
    <script type="text/javascript">
    function js_calculo(){
      document.form1.y50_codauto.value=parent.iframe_auto.document.form1.y50_codauto.value;
    }
    </script>
  </div>
</body>
</html>
<?
if( isset($desabilita) ){
  echo "<script>document.form1.calcular.disabled = true</script>";
}
?>