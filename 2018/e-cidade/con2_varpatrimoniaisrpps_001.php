<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
include("dbforms/db_classesgenericas.php");

$clcriaabas = new cl_criaabas;

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($HTTP_POST_VARS);
$abas    = array();
$titulos = array();
$fontes  = array();
$sizecp  = array();

$anousu     = db_getsession("DB_anousu");
$codrel      = 54;
$sNomeFonte  = "con2_varpatrimoniaisrpps_003.php";
$sNotas      = "con2_varpatrimoniaisrpps_004.php?c83_codrel= $codrel";
$sParametros = "con2_conrelparametros.php?c83_codrel= $codrel";

if (db_getsession("DB_anousu") >= 2014) {

  $codrel      = 136;
  $sNomeFonte  = "con2_varpatrimoniaisrpps_2014_003.php?c83_codrel={$codrel}";
  $sParametros = "con4_parametrosrelatorioslegais001.php?c83_codrel={$codrel}";
  $sNotas      = "con2_conrelnotas.php?c83_codrel={$codrel}";
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?php
    $clcriaabas->identifica = array("relatorio" => "Relatório",
                                    "parametro" => "Parâmetros",
                                    "notas"     => "Fonte/Notas Explicativas");

    $clcriaabas->title = array("relatorio" =>"Relatório",
                               "parametro" =>"Parâmetros",
                               "notas"     =>"Fonte/Notas Explicativas");

    $clcriaabas->src = array("relatorio" => $sNomeFonte,
                             "parametro" => $sParametros,
                             "notas"     => $sNotas);

    $clcriaabas->sizecampo= array("relatorio" => "30",
                                  "parametro" => "30",
                                  "notas"     => "30");
    $clcriaabas->cria_abas();    
    ?>
    </center>
  </td>
  </tr>
</table>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>