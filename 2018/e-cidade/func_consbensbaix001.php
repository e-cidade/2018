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
require("libs/db_liborcamento.php");
include("classes/db_bensbaix_classe.php");
$clbensbaix = new cl_bensbaix;
db_postmemory($HTTP_POST_VARS);

$numrows = 0;
$relbaix = null;
$msg = null;
if(isset($dataINI) && trim($dataINI)!="" || isset($dataFIM) && trim($dataFIM)!=""){
  if(isset($dataINI) && trim($dataINI)!=""&&trim($dataFIM)!=""){
    $relbaix=" t55_baixa >='".$dataINI."' ";
    $msg = "posterior a ".db_formatar($dataINI,"d");
  }
 if(isset($dataFIM) && trim($dataFIM)!=""){
   if($relbaix!=""){
     $relbaix =" t55_baixa between '".$dataINI."' and '".$dataFIM."' ";
     $msg = "entre ".db_formatar($dataINI,"d")." e ".db_formatar($dataFIM,"d");
   }else{
     $relbaix=" t55_baixa<'".$dataFIM."' ";
     $msg = "anterior a ".db_formatar($dataFIM,"d");
   }
 }
}else{
  $msg = "TODOS OS BENS BAIXADOS";  
}
//die($clbensbaix->sql_query_file(null,"*","","$relbaix"));
$result_baixa = $clbensbaix->sql_record($clbensbaix->sql_query_file(null,"*","","$relbaix"));
$numrows=$clbensbaix->numrows;
if($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum cadastro de bem baixado $msg");
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

<center>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
      <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
	  <br><br>
	    <center>
	      <table width="100%" border='1' cellspacing="0" cellpadding="0" align ="center" >
		<tr>
		  <td colspan='9' align='center' nowrap ><b> BENS BAIXADOS </b></td>
		</tr>
		<tr>                                                 
		  <td colspan='9' align='center' nowrap ><b> <?=isset($relbaix)?"Período $msg":"$msg"?> </b></td>
		</tr>
		<tr>
      <?
      $result_bens = $clbensbaix->sql_query(null,"bens.t52_bem,
						  bens.t52_descr,
						  bens.t52_ident,
						  bens.t52_depart,
						  db_depart.descrdepto,
						  bensbaix.t55_baixa,
						  bensbaix.t55_obs,
						  clabens.t64_class,
						  clabens.t64_descr,
						  (case when t52_bem in
							(select t53_codbem from bensmater) then 'Material' else
							(case when t52_bem in
							      (select t54_codbem from bensimoveis) then 'Imóvel' else 'Indefinido'
							end)
						  end) as dl_Definicao","","$relbaix"); 
      db_lovrot($result_bens,15,"","");
      ?>        </tr>
	      </table>
	    </center>
	  </td>
	</tr>
      </table>
    </td>
  </tr>
</table>
</center>
</body>
</html>