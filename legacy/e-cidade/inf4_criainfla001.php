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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_inflan_classe.php");
include("classes/db_infla_classe.php");

$rotulocampo = new rotulocampo;
$rotulocampo->label("i01_codigo");
$rotulocampo->label("DBtxt6");
$clinfla = new cl_infla;
$clinflan= new cl_inflan;

db_postmemory($HTTP_POST_VARS);

$confirme_reprocesso = false;
$processa = false;
$exclusao = false;
if (isset($processar)) {
  db_inicio_transacao();
  if ($processar == "Processar") {
    $result = $clinfla->sql_record($clinfla->sql_query_file("","","i02_codigo",""," i02_codigo = '$i01_codigo' and substr(i02_data,1,4) = '$DBtxt6' limit 1"));
    $rows1  = $clinfla->numrows;
    if ($rows1 != 0 ) {
      $confirme_reprocesso = true;
    } else {
      $processa = true;
    }
  } else {
    $exclusao = true;
    $processa = true;
  }
}
if ($exclusao == true) {
  $result1 = $clinfla->sql_record($clinfla->sql_query_file("",""," i02_codigo, i02_data ",""," i02_codigo = '$i01_codigo' and substr(i02_data,1,4) = '$DBtxt6' "));
  $rows2  = $clinfla->numrows;
  for ($ii = 0; $ii < $rows2 ; $ii++) {
    db_fieldsmemory($result1,$ii);
    $clinfla->i02_codigo = $i02_codigo;
    $clinfla->i02_data = $i02_data;
    $clinfla->excluir($i02_codigo,$i02_data);
  }

}
if ($processa == true ) {
  $sqlerro  = false;
  $erro_msg = "";

  $result2 = $clinflan->sql_record($clinflan->sql_query_file("","i01_dm",""," i01_codigo = '$i01_codigo'"));
  if ($clinflan->erro_status=="0") {
    $erro_msg = $clinflan->erro_msg;
    $sqlerro  = true;
  }

  if($clinflan->numrows > 0) {
    db_fieldsmemory($result2,0);
  } else {
    $erro_msg = "Inflator $i01_codigo não encontrado no cadastro de inflatores";
    $sqlerro  = true;
  }

  if($sqlerro == false) {
    if ($i01_dm == 0 ) {
      for ($mes = 1; $mes < 13; $mes++ ) {
        //	     db_msgbox("vai incluir mensal");
        $clinfla->i02_codigo = $i01_codigo;
        $clinfla->i02_data   = $DBtxt6."-".db_formatar($mes,'s',"0",2)."-01";
        $clinfla->i02_valor = '0';
        $clinfla->incluir($i01_codigo,$DBtxt6."-".db_formatar($mes,'s',"0",2)."-01");
        if ($clinfla->erro_status=="0") {
          $erro_msg = $clinfla->erro_msg;
          $sqlerro  = true;
          break;
        }
      }

    } else if ($i01_dm == 1) {
      $ndias = strftime('%j',mktime(0,0,0,12,31,$DBtxt6));
      for ($dia = 0; $dia < $ndias; $dia++) {
        //     db_msgbox("vai incluir diario");
        $clinfla->i02_codigo = $i01_codigo;
        $clinfla->i02_data = date("Y-m-d",mktime(0,0,0,1,1+$dia,$DBtxt6));
        $clinfla->i02_valor = '0';
        $clinfla->incluir($i01_codigo,date("Y-m-d",mktime(0,0,0,1,1+$dia,$DBtxt6)));
        if ($clinfla->erro_status=="0") {
          $erro_msg = $clinfla->erro_msg;
          $sqlerro  = true;
          break;
        }

      }
    } else if ($i01_dm == 2) {
      // db_msgbox("vai incluir anual $DBtxt6");
      $dataini             = "$DBtxt6-01-01";
      $clinfla->i02_codigo = $i01_codigo;
      $clinfla->i02_data   = $dataini;
      $clinfla->i02_valor  = '0';
      $clinfla->incluir($i01_codigo,$dataini);
      if ($clinfla->erro_status=="0") {
        $erro_msg = $clinfla->erro_msg;
        $sqlerro  = true;
      }
    }
    db_fim_transacao($sqlerro);
    if($sqlerro == false) {
      echo "<script>alert('Inclusão Efetuada Com Sucesso.')</script>";
    } else {
      echo "<script>alert('Erro na Inclusão do Inflator ($erro_msg)')</script>";
    }
  }
}


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica() {
  var exercicio = document.form1.DBtxt6.value;
  if(exercicio.valueOf() == 0){
     alert('O exercício não pode ser zero (0). Verifique !');
     return false
  }
}
</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="" onsubmit="return js_verifica();">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25" nowrap>&nbsp; &nbsp;</td>
            </tr>
            <tr>
              <td height="25" nowrap title="<?=$Ti01_codigo?>"><?=$Li01_codigo?></td>
              <td height="25" nowrap>&nbsp; &nbsp;
                <?
		$result = $clinflan->sql_record($clinflan->sql_query("","i01_codigo#i01_descr","i01_codigo"));
		db_selectrecord("i01_codigo",$result,true,2,"","","");
		?>
              </td>
            </tr>
            <tr>
              <td height="25" nowrap title="<?=$TDBtxt6?>"><?=$LDBtxt6?></td>
              <td height="25" nowrap>&nbsp; &nbsp;
              <?
                db_input('DBtxt6',4,$IDBtxt6,true,'text',2);
              ?>

	      </td>

            <tr>
              <td
	          height="25" nowrap><input name="processar" type="submit" id="processar"  value="Processar">
	      </td>
            </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<?
if($confirme_reprocesso == true){
      echo "<script>
            resultado = confirm('O inflator $i01_codigo já processado para o exercício $DBtxt6.\\n Deseja reprocessar?');
            if(resultado == true){
	      document.form1.processar.value='Reprocessar';
              document.form1.processar.click();
            }
            </script>";
}
?>