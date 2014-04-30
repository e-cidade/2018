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
include("classes/db_fiscalusuario_classe.php");
include("classes/db_fandamusu_classe.php");
include("classes/db_fandam_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(isset($primeira))
  exit;
$clfiscalusuario = new cl_fiscalusuario;
$clfandamusu   = new cl_fandamusu;
$clfandam   = new cl_fandam;
$db_opcao = 1;
$db_botao = true;
global $y38_codnoti;
global $y40_codandam;
$y40_codandam = $y39_codandam;
$result = $clfandamusu->sql_record($clfandamusu->sql_query("","","*",""," y40_codandam = $y39_codandam"));
if($clfandamusu->numrows == 0){
  $result = $clfiscalusuario->sql_record($clfiscalusuario->sql_query("","","*",""," y38_codnoti = $y38_codnoti and y30_instit = ".db_getsession('DB_instit') ));
  if($clfiscalusuario->numrows > 0){
    db_inicio_transacao();
    $sqlerro=false;
    $numrows = $clfiscalusuario->numrows;
    for($i=0;$i<$numrows;$i++){ 
      db_fieldsmemory($result,$i);
      $clfandamusu->y40_obs="0";
      $clfandamusu->y40_id_usuario=$y38_id_usuario;
      $clfandamusu->y40_codandam=$y40_codandam;
      $clfandamusu->incluir($y40_codandam,$y38_id_usuario);
      $erro=$clfandamusu->erro_msg;
      if($clfandamusu->erro_status==0){
        $sqlerro = true;
      }
    }
    db_fim_transacao();
  }
}
$result = $clfandam->sql_record($clfandam->sql_query($y39_codandam));
if($clfandam->numrows > 0 && !isset($y40_id_usuario)){
  db_fieldsmemory($result,0);
  global $y40_id_usuario;
  $y40_id_usuario = @$y39_id_usuario;
}
$pesqandam = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmfandamusu.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>