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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("libs/db_libcontabilidade.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_orctiporec_classe.php");
include ("classes/db_empempenho_classe.php");
include ("classes/db_empelemento_classe.php");
include ("classes/db_pagordem_classe.php");
include ("classes/db_pagordemele_classe.php");
include ("classes/db_empnota_classe.php");
include ("classes/db_empnotaele_classe.php");
include ("classes/db_pagordemnota_classe.php");

include ("classes/db_pagordemval_classe.php");
include ("classes/db_pagordemrec_classe.php");
include ("classes/db_tabrec_classe.php");
include ("classes/db_conplanoreduz_classe.php");
include ("classes/db_conlancam_classe.php");
include ("classes/db_conlancamemp_classe.php");
include ("classes/db_conlancamdoc_classe.php");
include ("classes/db_conlancamele_classe.php");
include ("classes/db_conlancamnota_classe.php");
include ("classes/db_conlancamcgm_classe.php");
include ("classes/db_conlancamdot_classe.php");
include ("classes/db_conlancamval_classe.php");
include ("classes/db_conlancamlr_classe.php");
include ("classes/db_conlancamcompl_classe.php");
include ("classes/db_conlancamord_classe.php");
include ("classes/db_pagordemtiporec_classe.php");

include ("classes/empenho.php");

$clempnota = new cl_empnota;
$clempnotaele = new cl_empnotaele;
$clpagordem = new cl_pagordem;
$clpagordemele = new cl_pagordemele;
$clpagordemnota = new cl_pagordemnota;
$clpagordemval = new cl_pagordemval;
$clpagordemrec = new cl_pagordemrec;
$clempempenho = new cl_empempenho;
$clempelemento = new cl_empelemento;
$clorcdotacao = new cl_orcdotacao;
$clorctiporec = new cl_orctiporec;
$cltabrec = new cl_tabrec;
$clconplanoreduz = new cl_conplanoreduz;

$cltranslan = new cl_translan;
$clconlancam = new cl_conlancam;
$clconlancamemp = new cl_conlancamemp;
$clconlancamdoc = new cl_conlancamdoc;
$clconlancamele = new cl_conlancamele;
$clconlancamnota = new cl_conlancamnota;
$clconlancamcgm = new cl_conlancamcgm;
$clconlancamdot = new cl_conlancamdot;
$clconlancamval = new cl_conlancamval;
$clconlancamlr = new cl_conlancamlr;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamord = new cl_conlancamord;
$clpagordemtiporec = new cl_pagordemtiporec;

$clempenho = new empenho;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$op           = 3;
$db_opcao     = 22;
$db_botao     =   false;
$tela_estorno = true;
if(isset($numemp)){
  
  $db_opcao = 1;
  $db_botao = true;
//  echo "<br>{$numemp}";
}  
// para estornar a nota de liquidação, ela não deve estar na agenda de pagamento
// não deve estar paga, e nem deve ter sido recolhido as retenções
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<?
include ("forms/db_frmliquida.php");
?>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if ($db_opcao == 22) {
	echo "<script>document.form1.pesquisar.click();</script>";
} else {
  echo "<script>js_pesquisa({$numemp});</script>";
}
?>