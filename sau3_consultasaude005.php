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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once('libs/db_utils.php');
db_postmemory($HTTP_POST_VARS);

$sSql         = "";
$oDaoHiperdia = db_utils::getdao('far_cadacomppachiperdia');
$clrotulo     = new rotulocampo;
$oDaoHiperdia->rotulo->label();
$clrotulo->label("z01_i_cgsund");


if(isset($z01_i_cgsund)){
  
	$sCampos  = " s152_i_codigo, s152_i_pressaosistolica, s152_i_pressaodiastolica, s152_i_cintura, s152_n_peso,";
	$sCampos .= " s152_i_altura, s152_i_glicemia, "; 
	$sCampos .= " case when s152_i_alimentacaoexameglicemia=0 then "; 
	$sCampos .= "   'Não informado' ";
	$sCampos .= " else case when s152_i_alimentacaoexameglicemia=1 then ";
	$sCampos .= "        'Em jejum' ";
	$sCampos .= "      else ";
	$sCampos .= "        'Pós prandial' ";
	$sCampos .= "      end ";
	$sCampos .= " end as s152_i_alimentacaoexameglicemia , ";
	$sCampos .= " z01_nome as dl_profissional, ";
	$sCampos .= " s152_d_dataconsulta ";
  $sWhere   = " fa50_i_cgsund = $z01_i_cgsund ";
  $sSql     = $oDaoHiperdia->sql_query2("",$sCampos,"s152_d_dataconsulta desc",$sWhere);
  
}

?>
<html>
  <head>
     <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
     <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
     <meta http-equiv="Expires" CONTENT="0">
     <?
      $sLib  = "scripts.js,prototype.js,datagrid.widget.js,strings.js,grid.style.css,";
      $sLib .= "estilos.css,/widgets/dbautocomplete.widget.js,webseller.js";
      db_app::load($sLib);
     ?>
     <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
     <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name="form1" method="post" action="">    
   <center>
   <br>
   <fieldset style="width:600px;" ><legend><b>Hiperdia</b></legend>
   <?      
     if ($sSql != "") {
     	 
     	 global $cor1;
       global $cor2;
       $cor1 = "#FFFAF0";
       $cor2 = "#FFFAF0";
       db_lovrot($sSql, $iLinhas, "()", "", "");
       
     }
   ?>
   </fieldset> 
   </center>
  </form>
  </body>
</html>