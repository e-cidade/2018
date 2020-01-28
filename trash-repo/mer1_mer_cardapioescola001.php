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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_mer_cardapio_classe.php");
require_once("classes/db_mer_cardapiodata_classe.php");
require_once("classes/db_mer_cardapiodia_classe.php");
require_once("classes/db_mer_tipocardapio_classe.php");
require_once("classes/db_mer_cardapioescola_classe.php");
require_once("classes/db_escola_classe.php");

$oPost                = db_utils::postMemory($_POST);
$oGet                 = db_utils::postMemory($_GET);
$clmer_tipocardapio   = new cl_mer_tipocardapio;
$clmer_cardapio   = new cl_mer_cardapio;
$clmer_cardapiodata   = new cl_mer_cardapiodata;
$clmer_cardapiodia   = new cl_mer_cardapiodia;
$clmer_cardapioescola = new cl_mer_cardapioescola;
$clescola             = new cl_escola;
$me32_i_tipocardapio  = $oGet->me32_i_tipocardapio;
$db_botao   = true;
$dataatual = date("Y-m-d",db_getsession("DB_datausu"));
$horaatual = date("H:i");
$result_verif1 = $clmer_cardapiodata->sql_record($clmer_cardapiodata->sql_query("",
                                                                                "*",
                                                                                "",
                                                                                "me01_i_tipocardapio = $me32_i_tipocardapio"
                                                                               ));
                                                                               
$sWhere         = " me01_i_tipocardapio = $me32_i_tipocardapio AND (me12_d_data < '$dataatual' OR (me12_d_data = '$dataatual' "; 
$sWhere        .= " AND me03_c_fim < '$horaatual')) AND not exists ";
$sWhere        .= "                                       (select * from mer_cardapiodata inner join mer_cardapiodiaescola on me37_i_codigo = me13_i_cardapiodiaescola";
$sWhere        .= "                                                where me12_i_codigo = me37_i_cardapiodia)";
$result_verif2  = $clmer_cardapiodia->sql_record($clmer_cardapiodia->sql_query("",
                                                                               "*",
                                                                               "",
                                                                               $sWhere
                                                                              ));
if ($clmer_cardapiodata->numrows>0 || $clmer_cardapiodia->numrows>0) {
  $db_botao   = false;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
.marcaEnvia, .marcaRetira 
 { 
   border-colappse  : collapse;
   border-right     : 1px inset black;
   border-bottom    : 1px inset black;
   cursor           : normal;
   font-family      : Arial, Helvetica, sans-serif;
   font-size        : 12px;
   background-color : #CCCDDD
 }
 
.marcaSel
{
   border-colappse  : collapse;
   border-right     : 1px inset black;
   border-bottom    : 1px inset black;
   cursor           : normal;
   font-family      : Arial, Helvetica, sans-serif;
   font-size        : 12px;
   background-color : #d1f07c
}

td.linhagrid 
{
  -moz-user-select: none;
  text-align: left;
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_pesquisaEscola(<?=$me32_i_tipocardapio?>)" 
      bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
<br><br>
  <table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
     <td>
      <?include("forms/db_frmmer_cardapioescola.php");?>     
     </td>
   </tr>   
  </table>
</form>
</center>
</body>
</html>