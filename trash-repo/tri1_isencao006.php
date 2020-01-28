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
include("classes/db_isencao_classe.php");
include("classes/db_isencaocgm_classe.php");
include("classes/db_isencaomatric_classe.php");
include("classes/db_isencaoinscr_classe.php");
include("classes/db_isencaolanc_classe.php");
include("classes/db_isencaoproc_classe.php");
$clisencao = new cl_isencao;
$clisencaoproc = new cl_isencaoproc;
$clisencaocgm = new cl_isencaocgm;
$clisencaomatric = new cl_isencaomatric;
$clisencaoinscr = new cl_isencaoinscr;

$clisencaolanc = new cl_isencaolanc;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  // se tem processo exclui
	$rsProcuraProcesso = $clisencaoproc->sql_record($clisencaoproc->sql_query_file(null,"v17_protprocesso",null," v17_isencao = ".$v10_sequencial));
	if($clisencaoproc->numrows > 0){
		$clisencaoproc->excluir(null," v17_isencao = ".$v10_sequencial);
		if($clisencaoproc->erro_status==0){
			$erro_msg = $clisencaoproc->erro_msg; 
			$sqlerro=true;
		} 
	}
	// se origem for cgm exclui
	$rsProcuracgm = $clisencaocgm->sql_record($clisencaocgm->sql_query_file(null,"v12_numcgm",null," v12_isencao = ".$v10_sequencial));
	if($clisencaocgm->numrows > 0){
		$clisencaocgm->excluir(null," v12_isencao = ".$v10_sequencial);
		if($clisencaocgm->erro_status==0){
			$erro_msg = $clisencaocgm->erro_msg; 
			$sqlerro=true;
		} 
	}
	// se origem for por matricula exclui
	$rsProcuramatric = $clisencaomatric->sql_record($clisencaomatric->sql_query_file(null,"v15_matric",null," v15_isencao = ".$v10_sequencial));
	if($clisencaomatric->numrows > 0){
		$clisencaomatric->excluir(null," v15_isencao = ".$v10_sequencial);
		if($clisencaoproc->erro_status==0){
			$erro_msg = $clisencaoproc->erro_msg; 
			$sqlerro=true;
		} 
	}
	// se origem for inscricao exclui
	$rsProcurainscr = $clisencaoinscr->sql_record($clisencaoinscr->sql_query_file(null,"v16_inscr",null," v16_isencao = ".$v10_sequencial));
	if($clisencaoinscr->numrows > 0){
		$clisencaoinscr->excluir(null," v16_isencao = $v10_sequencial ");
		if($clisencaoinscr->erro_status==0){
			$erro_msg = $clisencaoinscr->erro_msg; 
			$sqlerro=true;
		} 
	}
  // se achar lancamentos exclui
	$rsProcuraLanc = $clisencaolanc->sql_record($clisencaolanc->sql_query_file(null,"v18_isencao",null," v18_isencao = ".$v10_sequencial));
	if($clisencaolanc->numrows > 0){
    $clisencaolanc->excluir(null,"v18_isencao = $v10_sequencial");
    if($clisencaolanc->erro_status==0){
      $erro_msg = $clisencaolanc->erro_msg; 
      $sqlerro=true;
    } 
	}

  $clisencao->excluir($v10_sequencial);
  if($clisencao->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clisencao->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clisencao->sql_record($clisencao->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
	 $rsProcuraProcesso = $clisencaoproc->sql_record($clisencaoproc->sql_query_file(null,"v17_protprocesso",null," v17_isencao = $chavepesquisa "));
	 if($clisencaoproc->numrows > 0){
     db_fieldsmemory($rsProcuraProcesso,0);
	 }
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
	include("forms/db_frmisencao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clisencao->erro_campo!=""){
      echo "<script> document.form1.".$clisencao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clisencao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='tri1_isencao003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa) && $db_opcao != 3){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.isencaolanc.disabled=false;
         top.corpo.iframe_isencaolanc.location.href='tri1_isencaolanc001.php?db_opcaoal=33&v18_sequencial=".@$v10_sequencial."&valorigem=$valorigem';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('isencaolanc');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>