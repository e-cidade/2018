<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='com1_pccontratos005.php'</script>";
  exit;
}
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pccontratos_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_pccontrdep_classe.php");
include("classes/db_pccontrlic_classe.php");
include("classes/db_pccontrdot_classe.php");
include("classes/db_pccontrcompra_classe.php");
db_postmemory($HTTP_POST_VARS);
$clpccontrcompra = new cl_pccontrcompra;
$clpccontrdot = new cl_pccontrdot;
$clpccontrlic = new cl_pccontrlic;
$clpccontratos = new cl_pccontratos;
$clpccontrdep = new cl_pccontrdep;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $clpccontratos->incluir($p71_codcontr);
  if(isset($p74_valor)){
    $clpccontrdep->p74_codcontr = $clpccontratos->p71_codcontr;
    $clpccontrdep->incluir($clpccontratos->p71_codcontr);
//    $clpccontrdep->erro(true,false);
  }
  if(isset($p75_tipo)){
    $clpccontrlic->p75_codcontr = $clpccontratos->p71_codcontr;
    $clpccontrlic->incluir($clpccontratos->p71_codcontr);
  //  $clpccontrlic->erro(true,false);
  }
  if(isset($p72_codcom)){
    $clpccontrcompra->p72_codcontr = $clpccontratos->p71_codcontr;
    $clpccontrcompra->incluir($clpccontratos->p71_codcontr);
    //$clpccontrcompra->erro(true,false);
  }
  db_fim_transacao();
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
	include("forms/db_frmpccontratos.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clpccontratos->erro_status=="0"){
    $clpccontratos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpccontratos->erro_campo!=""){
      echo "<script> document.form1.".$clpccontratos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpccontratos->erro_campo.".focus();</script>";
    };
  }else{
    $clpccontratos->erro(true,false);
    echo "
         <script>
           parent.iframe_contratos.location.href='com1_pccontratos002.php?chavepesquisa=".$p71_codcontr."&abas=1';\n
           parent.iframe_dotacao.location.href='com1_pccontrdot001.php?p73_codcontr=".$p71_codcontr."&abas=1';\n
	   parent.document.formaba.dotacao.disabled = false;
	   parent.document.formaba()
	</script>";   
  };
};
?>