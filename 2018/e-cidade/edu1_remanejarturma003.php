<?
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_turma_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_regenciaperiodo_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_parecerturma_classe.php");
include("classes/db_alunotransfturma_classe.php");
include("classes/db_regenteconselho_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_escolaestrutura_classe.php");
include("classes/db_trocaserie_classe.php");
include("classes/db_turmaturnoadicional_classe.php");
include("classes/db_turmaserieregimemat_classe.php");
include("classes/db_turmalog_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$iAnoEtapaCenso        = null;
$clturma               = new cl_turma;
$clescola              = new cl_escola;
$clescolaestrutura     = new cl_escolaestrutura;
$clmatricula           = new cl_matricula;
$clregencia            = new cl_regencia;
$clalunotransfturma    = new cl_alunotransfturma;
$clregenteconselho     = new cl_regenteconselho;
$cltrocaserie          = new cl_trocaserie;
$clturmaturnoadicional = new cl_turmaturnoadicional;
$clparecerturma        = new cl_parecerturma;
$clregenciahorario     = new cl_regenciahorario;
$clregenciaperiodo     = new cl_regenciaperiodo;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$clturmalog            = new cl_turmalog;
$clturmaturnoreferente = new cl_turmaturnoreferente;
$clturmacensoetapa     = new cl_turmacensoetapa;
$db_botao              = false;
$db_botao2             = true;
$db_opcao              = 33;
$db_opcao1             = 3;
$codigoescola          = db_getsession("DB_coddepto");
$result_estr           = $clescolaestrutura->sql_record($clescolaestrutura->sql_query("",
                                                                                      "ed255_i_ativcomplementar,ed255_i_aee",
                                                                                      "",
                                                                                      " ed255_i_escola = $codigoescola"
                                                                                     )
                                                       );

if ($clescolaestrutura->numrows > 0) {

  db_fieldsmemory($result_estr,0);
  if ($ed255_i_aee == 2) {

  	$sTexto  = "<font color='red'><b>* Escola oferede EXCLUSIVAMENTE Atendimento Educacional Especial - AEE (Cadastros ->";
  	$sTexto .= "Dados da Escola -> Aba Infraestrutura)</b></font>";
    echo "$sTexto";

  }

  if ($ed255_i_ativcomplementar == 2) {

  	$sString  = "<br><font color='red'><b>* Escola oferece EXCLUSIVAMENTE Atividade Complementar (Cadastros -> ";
  	$sString .= "Dados da Escola -> Aba Infra Estrutura)</b></font>";
    echo "$sString";

  }

  if ($ed255_i_aee == 2 || $ed255_i_ativcomplementar == 2) {

    $db_botao  = false;
    $db_botao2 = false;

  }
}

if(isset($excluir)){

  $db_opcao  = 3;
  $db_opcao1 = 3;
  $result1   = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo",""," ed60_i_turma = $ed57_i_codigo"));

  if ($clmatricula->numrows > 0) {

    db_msgbox("Turma $ed57_c_descr não pode ser excluída, pois possui matrículas vinculadas!");
    $db_opcao = 33;

  } else {

    db_inicio_transacao();
    $clregenciahorario->excluir("",
                                " ed58_i_regencia in (select ed59_i_codigo from
                                                                         regencia where ed59_i_turma = $ed57_i_codigo)"
                               );
    $clregenciaperiodo->excluir("",
                                " ed78_i_regencia  in (select ed59_i_codigo from
                                                                          regencia where ed59_i_turma = $ed57_i_codigo)"
                               );
    $clregencia->excluir(""," ed59_i_turma = $ed57_i_codigo");
    db_query("UPDATE alunopossib SET ed79_i_turmaant = null WHERE ed79_i_turmaant = $ed57_i_codigo");
    db_query("UPDATE matricula SET ed60_i_turmaant = null WHERE ed60_i_turmaant = $ed57_i_codigo");
    $clalunotransfturma->excluir("","ed69_i_turmaorigem = $ed57_i_codigo or ed69_i_turmadestino = $ed57_i_codigo");
    $clparecerturma->excluir(""," ed105_i_turma = $ed57_i_codigo");
    $clregenteconselho->excluir("","ed235_i_turma = $ed57_i_codigo");
    $cltrocaserie->excluir("","ed101_i_turmaorig = $ed57_i_codigo or ed101_i_turmadest = $ed57_i_codigo");
    $clturmaturnoadicional->excluir("","ed246_i_turma = $ed57_i_codigo");
    $clturmaserieregimemat->excluir("","ed220_i_turma = $ed57_i_codigo");
    $clturmalog->excluir("","ed287_i_turma = $ed57_i_codigo");
    $clturmaturnoreferente->excluir("", "ed336_turma = $ed57_i_codigo");
    $clturmacensoetapa->excluir("", "ed132_turma = $ed57_i_codigo");
    $clturma->excluir($ed57_i_codigo);
    db_fim_transacao();

  }
} else if(isset($chavepesquisa)) {

  $db_opcao  = 3;
  $db_opcao1 = 3;

  $sWhereTurmaCensoEtapa  = " ed132_turma = {$chavepesquisa}";
  $sSqlTurmaCensoEtapa    = $clturmacensoetapa->sql_query_file(null, "ed132_ano", null, $sWhereTurmaCensoEtapa);
  $rsTurmaCensoEtapa      = db_query( $sSqlTurmaCensoEtapa );

  if ( !$rsTurmaCensoEtapa ) {
    throw new DBException("Não foi possivel buscar o vinculo da turma com o censo.");
  }

  if ( pg_num_rows( $rsTurmaCensoEtapa ) == 0 ) {
    throw new DBException("Não há vinculos do censo com a turma.");
  }

  $iAnoEtapaCenso = db_utils::fieldsMemory( $rsTurmaCensoEtapa, 0 )->ed132_ano;

  $result    = $clturma->sql_record($clturma->sql_query_turma_etapa_censo( $chavepesquisa, $iAnoEtapaCenso ));
  db_fieldsmemory($result,0);
  $db_botao  = true;

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Exclusão de Turma</b></legend>
    <?include("forms/db_frmturma.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if (isset($excluir)) {

  if ($clturma->erro_status == "0") {
    $clturma->erro(true,false);
  } else {

    $clturma->erro(true,false);
    ?>
    <script>parent.document.form2.teste.click();</script>
    <?

  }
}
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>