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
include("classes/db_fiscaltipo_classe.php");
include("classes/db_fiscalrec_classe.php");
include("classes/db_fiscalprocrec_classe.php");
include("classes/db_fiscalandam_classe.php");
include("classes/db_fiscalultandam_classe.php");
include("classes/db_fiscalusuario_classe.php");
include("classes/db_fandam_classe.php");
include("classes/db_fandamusu_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clfiscaltipo     = new cl_fiscaltipo;
$clfiscalrec      = new cl_fiscalrec;
$clfiscalprocrec  = new cl_fiscalprocrec;
$clfandam         = new cl_fandam;
$clfandamusu      = new cl_fandamusu;
$clfiscalandam    = new cl_fiscalandam;
$clfiscalusuario  = new cl_fiscalusuario;
$clfiscalultandam = new cl_fiscalultandam;
$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
  $result = $clfiscalprocrec->sql_record($clfiscalprocrec->sql_query_fiscaltipo("",""," distinct y45_receit,y45_codtipo,y45_descr,y45_valor",""," y31_codnoti = $y31_codnoti"));
  if($clfiscalprocrec->numrows > 0){
    $numrows = $clfiscalprocrec->numrows;
    for($y=0;$y<$numrows;$y++){
      db_fieldsmemory($result,$y);
      $result1 = $clfiscalrec->sql_record($clfiscalrec->sql_query_file($y31_codnoti));
      $num = $clfiscalrec->numrows; 
      if($clfiscalrec->numrows > 0){
        for($x=0;$x<$num;$x++){
          db_fieldsmemory($result1,$x);
	  if($y42_receit == $y45_receit){
            $clfiscalrec->y42_codnoti = $y42_codnoti;
            $clfiscalrec->y42_receit = $y42_receit;
            $clfiscalrec->excluir($y42_codnoti,$y42_receit);
	  }
        }
      }
    }
  }
  $result = $clfiscalandam->sql_record($clfiscalandam->sql_query_file("","","y49_codandam",""," y49_codnoti = $y31_codnoti"));
  if($clfiscalandam->numrows == 1){
    db_fieldsmemory($result,0);
    $clfandamusu->excluir($y49_codandam);
    $clfiscalusuario->excluir($y31_codnoti);
    $clfiscalultandam->excluir($y31_codnoti,$y49_codandam);
    $clfiscalandam->excluir($y31_codnoti,$y49_codandam);
    $clfandam->excluir($y49_codandam);
  }
  $clfiscaltipo->excluir($y31_codnoti,$y31_codtipo);
  db_fim_transacao();
  echo "<script>parent.iframe_receitas.location.href='fis1_fiscalrec001.php?y42_codnoti=".$y31_codnoti."&abas=1';</script>\n";
  echo "<script>parent.iframe_fiscais.location.href='fis1_fiscalusuario001.php?y38_codnoti=".$y31_codnoti."&abas=1&y39_codandam=$y39_codandam';</script>\n";
  echo "<script>parent.iframe_artigos.location.href='fis1_fiscarquivos001.php?y26_codnoti=".$y31_codnoti."&abas=1&y39_codandam=$y39_codandam';</script>\n";
  echo "<script>parent.iframe_venc.location.href='fis1_vencimento001.php?y30_codnoti=".$y31_codnoti."&abas=1&y39_codandam=$y39_codandam';</script>\n";
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clfiscaltipo->sql_record($clfiscaltipo->sql_query($chavepesquisa,$chavepesquisa1)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
	include("forms/db_frmfiscaltipo.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","db_opcao",true,1,"db_opcao",true);
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clfiscaltipo->erro_status=="0"){
    $clfiscaltipo->erro(true,false);
  }else{
    $clfiscaltipo->erro(true,false);
    echo "<script>parent.iframe_fiscaltipo.location.href='fis1_fiscaltipo001.php?y31_codnoti=".$y31_codnoti."&abas=1';</script>\n";
    echo "<script>parent.iframe_receitas.location.href='fis1_fiscalrec001.php?y42_codnoti=".$y31_codnoti."&abas=1';</script>\n";
    echo "<script>parent.iframe_fiscais.location.href='fis1_fiscalusuario001.php?y38_codnoti=".$y31_codnoti."&abas=1&y39_codandam=$y39_codandam';</script>\n";
    echo "<script>parent.iframe_artigos.location.href='fis1_fiscarquivos001.php?y26_codnoti=".$y31_codnoti."'&abas=1&y39_codandam=$y39_codandam';</script>\n";
    echo "<script>parent.iframe_venc.location.href='fis1_vencimento001.php?y30_codnoti=".$y31_codnoti."&abas=1&y39_codandam=$y39_codandam';</script>\n";
  };
};
?>