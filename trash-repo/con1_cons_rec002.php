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
include("classes/db_conlancamval_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamcompl_classe.php");
include("classes/db_conlancamdig_classe.php");
include("classes/db_conplano_classe.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_orcfontes_classe.php");
include("libs/db_sql.php");
include("libs/db_liborcamento.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig   = new cl_conlancamdig;
$clconlancam      = new cl_conlancam;
$clorcfontes      = new cl_orcfontes;

$clorcfontes->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("c69_valor");
$clrotulo->label("DBtxt22");
$clrotulo->label("DBtxt21");

$db_opcao = 22;
$db_botao = true;
$anousu = db_getsession("DB_anousu");
//-- tipos de pesquisa

$tem_dados=false;

        $dataini="";
        $datafim=""; 
        @$dataini = "$perini";
        @$datafim = "$perfin";
	if (strlen($dataini) < 9 ) {
           $dataini= db_getsession("DB_anousu")."-01-01";
           $datafim= db_getsession("DB_anousu")."-12-31";
	} 
	$sql = db_receitasaldo(11,1,2,true,'',$anousu,$dataini,$datafim,true);
        $sql1 = "select o70_codigo,
                        o15_descr,
	   	        sum(saldo_inicial)              as DL_saldo_inicial, 
	 	        sum(saldo_anterior)             as DL_saldo_anterior,
		        sum(saldo_arrecadado)           as DL_saldo_arrecadado,
		        sum(saldo_a_arrecadar)          as DL_saldo_a_arrecadar,
		        sum(saldo_arrecadado_acumulado) as DL_saldo_arrecadado_acumulado
                 from ( $sql ) as x
	         where o70_codigo > 0 ";
        if ($o70_codrec !=""){
	   $sql1 .= " and o70_codigo = $o70_codrec ";
	}     		          
        $sql1 .="group by o70_codigo,
	                  o15_descr";

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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <form name="form1" method="post" action="">
      <? 
	if (isset($sql1)) {
	   $js_funcao="";
	   db_lovrot($sql1,18,"()","","$js_funcao");
           //  pg_exec("commit"); 
	}  
        echo "</form>";
	?>

   </center>
   </td>
   </tr>
</table>
</body>
</html>