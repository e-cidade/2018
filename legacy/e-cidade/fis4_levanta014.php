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
include("classes/db_levanta_classe.php");
include("classes/db_levinscr_classe.php");
include("classes/db_levcgm_classe.php");
include("classes/db_procfiscallevanta_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllevanta = new cl_levanta;
$cllevinscr= new cl_levinscr;
$cllevcgm  = new cl_levcgm;
$clprocfiscallevanta = new cl_procfiscallevanta;
$db_botao = true;
$db_opcao = 1;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $db_opcao = 1;
  $cllevanta->y60_espontaneo=$y60_espontaneo;
  $cllevanta->incluir($y60_codlev);
  $y60_codlev=$cllevanta->y60_codlev;
  if($cllevanta->erro_status==0){
    $sqlerro=true;
  }
	if($sqlerro==false){
		if($procfiscal!=""){
			$clprocfiscallevanta->y112_procfiscal = $procfiscal;
			$clprocfiscallevanta->y112_levanta    = $y60_codlev;
			$clprocfiscallevanta->incluir(null);
			if($clprocfiscallevanta->erro_status==0){
				$erro=$clprocfiscallevanta->erro_msg;
			  $sqlerro = true;
			}
		}
	}
  if(!$sqlerro){
    if($tipo=='z01_numcgm'){
      $cllevcgm->y93_numcgm=$valor;
      $cllevcgm->y93_codlev=$y60_codlev;
      $cllevcgm->incluir($y60_codlev,$valor);
      if($cllevcgm->erro_status==0){
         $sqlerro=true;
      }
    }else if($tipo=='q02_inscr'){
      $cllevinscr->y62_inscr=$valor;
      $cllevinscr->y62_codlev=$y60_codlev;
      $cllevinscr->incluir($y60_codlev,$valor);
      if($cllevinscr->erro_status==0){
         $sqlerro=true;
      }
    }
  }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlevanta.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($cllevanta->erro_status=="0"){
    $cllevanta->erro(true,false);
  }else{
    $cllevanta->erro(true,false);
    db_redireciona("fis4_levanta015.php?chavepesquisa=$y60_codlev");
  }
}
?>