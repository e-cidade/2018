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
include("classes/db_aguacorte_classe.php");
include("classes/db_aguacortemat_classe.php");
include("classes/db_aguacortematnumpre_classe.php");
include("classes/db_aguacortematmov_classe.php");
include("classes/db_aguacortetipodebito_classe.php");

$claguacorte           = new cl_aguacorte;
$claguacortemat        = new cl_aguacortemat;
$claguacortematmov     = new cl_aguacortematmov;
$claguacortematnumpre  = new cl_aguacortematnumpre;
$claguacortetipodebito = new cl_aguacortetipodebito;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 33;
$db_botao = false;

if (isset($excluir)) {
  $sqlerro  = false;
  $erro_msg = "";
  db_inicio_transacao();
  
  // 1o Excluir aguacortematnumpre
  $claguacortematnumpre->excluir(null, "exists (select 1 from aguacortemat where x41_codcortemat = x44_codcortemat and x41_codcorte = $x40_codcorte)");
  if ($claguacortematnumpre->erro_status=="0") {
    $sqlerro  = true;
    $erro_msg = $claguacortematnumpre->erro_msg;
  }
  
  // 2o Excluir aguacortematmov
  if ($sqlerro==false) {
    $claguacortematmov->excluir(null, "exists (select 1 from aguacortemat where x41_codcortemat = x42_codcortemat and x41_codcorte = $x40_codcorte)");
    if ($claguacortematmov->erro_status=="0") {
      $sqlerro  = true;
      $erro_msg = $claguacortematmov->erro_msg;
    }
  }
  
  // 3o Excluir aguacortemat
  if ($sqlerro==false) {
    $claguacortemat->excluir(null, "x41_codcorte = $x40_codcorte");
    if ($claguacortemat->erro_status=="0") {
      $sqlerro  = true;
      $erro_msg = $claguacortemat->erro_msg;
    }
  }
  
  // 4o Excluir aguacortetipodebito
  if ($sqlerro==false) {
    $claguacortetipodebito->excluir(null, "x45_codcorte = $x40_codcorte");
    if ($claguacortetipodebito->erro_status=="0") {
      $sqlerro  = true;
      $erro_msg = $claguacortetipodebito->erro_msg;
    }
  }
  
  // 5o Excluir aguacorte
  if ($sqlerro==false) {
    $claguacorte->excluir($x40_codcorte);
    if ($claguacorte->erro_status==0) {
      $sqlerro = true;
    }
    $erro_msg = $claguacorte->erro_msg;
  }
  db_fim_transacao($sqlerro);

  $db_opcao = 3;
  $db_botao = true;
  
} else if (isset($chavepesquisa)) {
  $db_opcao = 3;
  $db_botao = true;
  $result = $claguacorte->sql_record($claguacorte->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
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
	include("forms/db_frmaguacorte.php");
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
    if($claguacorte->erro_campo!=""){
      echo "<script> document.form1.".$claguacorte->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$claguacorte->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='agu1_aguacorte003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.aguacortemat.disabled=false;
         top.corpo.iframe_aguacortemat.location.href='agu1_aguacorte007.php?db_opcaoal=33&x41_codcorte=".@$x40_codcorte."&x40_dtinc=".@$x40_dtinc."';
         parent.document.formaba.aguacortetipodebito.disabled=false;
         top.corpo.iframe_aguacortetipodebito.location.href='agu1_aguacortetipodebito001.php?db_opcaoal=33&x45_codcorte=".@$x40_codcorte."&x40_dtinc=".@$x40_dtinc."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('aguacortemat');";
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