<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("libs/db_sql.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_solicitafolha.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_rhempfolha_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhempfolha = new cl_rhempfolha;
$clrhsolicita = new cl_rhsolicita;

$cliframe_seleciona_sol = new cl_iframe_seleciona;

$erro_msg = "";

if (isset($solicitacoes) && trim($solicitacoes) != ""){
  db_inicio_transacao();

  $sqlerro          = false;
  $vet_solicitacoes = split("_",$solicitacoes);

  $solicitacao      = "";
  $virgula          = "";
  for ($i = 0; $i < count($vet_solicitacoes); $i++){
    $sqlerro = db_exclusao_solicitacao($vet_solicitacoes[$i]);
    
    $solicitacao .= $virgula.$vet_solicitacoes[$i];
    $virgula      = ",";

    if ($sqlerro == true){
      break;
    }
  }

  if ($sqlerro == true){
    $erro_msg = "Erro na exclusão das solicitações($solicitacao).Verifique.";
  } else {
    $erro_msg = "Exclusão feita com sucesso. Solicitações($solicitacao) excluidas.";
  }

//  $sqlerro = true;
  db_fim_transacao($sqlerro);
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center">
  <tr>
    <td>
    <?
$numrows_confirma = 0;
$mostra           = false;
if ((isset($DBtxt23)&&trim(@$DBtxt23)!="") &&
(isset($DBtxt25)&&trim(@$DBtxt25)!="") &&
(isset($ponto)  &&trim(@$ponto)  !="")) {
  if (!isset($rh40_sequencia)||$rh40_sequencia=="") {
    $rh40_sequencia = '0';
  }
  
  $ano = $DBtxt23;
  $mes = $DBtxt25;
  $sequencia = '';
  $rh40_tipo = 'n';
  
  if ($ponto == 's') {
    $siglaarq = 'r14';
  } else if ($ponto == 'c') {
    $sequencia = " and r48_semest = $rh40_sequencia ";
    $siglaarq  = 'r48';
  } else if ($ponto == 'a') {
    $siglaarq  = 'r22';
  } else if ($ponto == 'r') {
    $siglaarq  = 'r20';
  } else if ($ponto == 'd') {
    $siglaarq  = 'r35';
  } else if ($ponto == 'f') {
    $siglaarq  = 'r31';
  }
  
  $result_confirma  = $clrhempfolha->sql_record($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,null,null,"*",null,"rh40_anousu    = $ano and
                                                                                                                                          rh40_mesusu    = $mes and
                                                                                                                                          rh40_sequencia = $rh40_sequencia and 
                                                                                                                                          rh40_siglaarq  = '$siglaarq' and 
                                                                                                                                          rh40_instit    = ".db_getsession("DB_instit")));
  $numrows_confirma = $clrhempfolha->numrows;
  
  if ($numrows_confirma > 0) {
    $mostra = true;
  } else {
    $mostra = false;
  }
}

include("forms/db_frmrhempexcluirsol.php");

if (trim($erro_msg) != ""){
  db_msgbox($erro_msg);
}
    ?>
    </td>
  </tr>
  <tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>