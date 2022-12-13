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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_procavalalternativa_classe.php"));
include(modification("classes/db_procavalalternativaregra_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
$sPossuiTurmasEncerradas = 'N';
$clprocavalalternativa = new cl_procavalalternativa;
$clprocavalalternativaregra = new cl_procavalalternativaregra;
$db_opcao = 1;
$db_botao = true;

if (isset($_GET['possuiTurmasEncerradas'])) {
  $sPossuiTurmasEncerradas = $_GET['possuiTurmasEncerradas'];
}

if (isset($incluir)) {

  $consulta = "";
  $sep = "";
  for($vv=0;$vv<count($xcodavaliacao);$vv++){
   if(trim($xformaavaliacao[$vv])==""){
    $consulta .= $sep.$xcodavaliacao[$vv];
    $sep = ",";
   }
  }
  db_inicio_transacao();
  $clprocavalalternativa->ed281_i_procresultado = $ed281_i_procresultado;
  $clprocavalalternativa->ed281_i_alternativa = $ed281_i_alternativa;
  $clprocavalalternativa->incluir(null);

  for ($rr = 0; $rr < count($xcodavaliacao); $rr++) {

    $clprocavalalternativaregra->ed282_i_procavalalternativa = $clprocavalalternativa->ed281_i_codigo;
    $clprocavalalternativaregra->ed282_i_codavaliacao = $xcodavaliacao[$rr];
    $clprocavalalternativaregra->ed282_i_tipoaval = $xtipoaval[$rr];
    $clprocavalalternativaregra->ed282_i_formaavaliacao = $xformaavaliacao[$rr];
    $clprocavalalternativaregra->incluir(null);
  }

  db_fim_transacao();
}

if( isset($excluir) ) {

  $oDaoDiarioAvaliacaoAlternativa    = new cl_diarioavaliacaoalternativa();
  $sSqlDiarioAvaliacaoAlternativa    = $oDaoDiarioAvaliacaoAlternativa->sql_query_file(null, "*", null, "ed136_procavalalternativa = {$codexclusao}");
  $rsDadosDiarioAvaliacaoAlternativa = db_query($sSqlDiarioAvaliacaoAlternativa);

  // Verifica se já existem diários vinculados à esta avaliação alternativa
  if (pg_num_rows($rsDadosDiarioAvaliacaoAlternativa) > 0) {

    $sMessage = "Esta avaliação alternativa não pode ser excluída, pois existem alunos vinculados à ela.";
    echo "<script> alert('{$sMessage}'); </script>";

    db_redireciona("edu1_procavalalternativa001.php?procedimento={$procedimento}&ed281_i_procresultado={$ed281_i_procresultado}&ed42_c_descr={$ed42_c_descr}&forma={$forma}&possuiTurmasEncerradas={$sPossuiTurmasEncerradas}");
  }

  $clprocavalalternativaregra->excluir(""," ed282_i_procavalalternativa = $codexclusao");
  $clprocavalalternativa->excluir($codexclusao);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <br>
    <center>
    <fieldset style="width:95%"><legend><b>Avaliações Alternativas</b></legend>
	<?include(modification("forms/db_frmprocavalalternativa.php"));?>
    </center>
    </fieldset>
	</td>
  </tr>
</table>
</body>
</html>
<script>
</script>
<?php
if(isset($incluir)){

  if($clprocavalalternativa->erro_status=="0"){
    $clprocavalalternativa->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprocavalalternativa->erro_campo!=""){
      echo "<script> document.form1.".$clprocavalalternativa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocavalalternativa->erro_campo.".focus();</script>";
    }
  }else{
    $clprocavalalternativa->erro(true,false);
    db_redireciona("edu1_procavalalternativa001.php?procedimento=$procedimento&ed281_i_procresultado=$ed281_i_procresultado&ed42_c_descr=$ed42_c_descr&forma=$forma&possuiTurmasEncerradas={$sPossuiTurmasEncerradas}");
  }
}
if(isset($excluir)){

  if($clprocavalalternativa->erro_status=="0"){
    $clprocavalalternativa->erro(true,false);
  }else{

    $clprocavalalternativa->erro(true,false);
    $result_pr = $clprocavalalternativa->sql_record($clprocavalalternativa->sql_query("","ed281_i_codigo as confcod","ed281_i_alternativa"," ed281_i_procresultado = $ed281_i_procresultado"));

    for ($tt = 0; $tt < $clprocavalalternativa->numrows; $tt++) {

      db_fieldsmemory($result_pr,$tt);
      $sql_up = "update procavalalternativa set ed281_i_alternativa = ".($tt+1)." WHERE ed281_i_codigo = $confcod";
      $result_up = db_query($sql_up);
    }

    db_redireciona("edu1_procavalalternativa001.php?procedimento=$procedimento&ed281_i_procresultado=$ed281_i_procresultado&ed42_c_descr=$ed42_c_descr&forma=$forma&possuiTurmasEncerradas={$sPossuiTurmasEncerradas}");
  }
}
?>