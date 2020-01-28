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
include("dbforms/db_funcoes.php");
include("classes/db_iptucalcpadrao_classe.php");
include("classes/db_iptucalcpadraolog_classe.php");
include("classes/db_iptucalcpadraoconstr_classe.php");
include("classes/db_iptutaxamatric_classe.php");
$cliptucalcpadrao    = new cl_iptucalcpadrao;
$cliptucalcpadraolog = new cl_iptucalcpadraolog;
$j10_matric = @$j01_matric;

db_postmemory($HTTP_POST_VARS);
//echo "j01_matric= $j10_matric  forma=$forma  exec=$exec perc = $perc "; 
/*
 * verifica se ja tem dados incluidos para esta matricula e ano
 * se tiver passa para o mode de alteração... fonte 5 
 * 
 * se forma = 2, passa para o fonte 5 direto, pois la vai incluir os dados importados
 */
$sqlalt = "select j10_sequencial from iptucalcpadrao where j10_matric = $j10_matric and j10_anousu=".db_getsession("DB_anousu");
$resultalt = pg_query($sqlalt);
$linhasalt = pg_num_rows($resultalt);
if($linhasalt>0 or $forma==2){
  db_fieldsmemory($resultalt,0);
  db_redireciona("cad1_iptucalcpadrao005.php?liberaaba=true&chavepesquisa=$j10_sequencial&j10_matric=$j10_matric&forma=$forma&exec=$exec&perc=$perc&alt=true");
}
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cliptucalcpadrao->j10_perccorre = '0';
  $cliptucalcpadrao->incluir(null);
  if($cliptucalcpadrao->erro_status==0){
    $sqlerro=true;
    $erro_msg = $cliptucalcpadrao->erro_msg; 
  } 
  if($sqlerro==false){
    $cliptucalcpadraolog->j19_iptucalcpadrao = $cliptucalcpadrao->j10_sequencial ;
    $cliptucalcpadraolog->j19_usuario        = db_getsession("DB_id_usuario") ;
    $cliptucalcpadraolog->j19_data           = date("Y-m-d",db_getsession("DB_datausu"));
    $cliptucalcpadraolog->j19_hora           = db_hora(); 
    $cliptucalcpadraolog->incluir(null);
    if($cliptucalcpadraolog->erro_status==0){
      $sqlerro=true;
      $erro_msg = $cliptucalcpadraolog->erro_msg; 
     }
  }
   
   
  db_fim_transacao($sqlerro);
   $j10_sequencial= $cliptucalcpadrao->j10_sequencial;
   $db_opcao = 1;
   $db_botao = true;
}
$j10_anousu = db_getsession('DB_anousu');

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
	include("forms/db_frmiptucalcpadrao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cliptucalcpadrao->erro_campo!=""){
      echo "<script> document.form1.".$cliptucalcpadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptucalcpadrao->erro_campo.".focus();</script>";
    };
  }else{
    // db_msgbox($erro_msg);
   db_redireciona("cad1_iptucalcpadrao005.php?liberaaba=true&chavepesquisa=$j10_sequencial&j10_matric=$j10_matric&forma=$forma&exec=$exec");
  }
}
?>