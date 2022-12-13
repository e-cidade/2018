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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_vac_calendario_classe.php");
require("libs/db_utils.php");

$clcriaabas = new cl_criaabas;
$clvac_calendario = new cl_vac_calendario;

$oRotulo = new rotulocampo;
$oRotulo->label('vc16_i_cgs');
$oRotulo->label('z01_v_nome');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="formaba">
<br>
<table marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap width="4%">
      <?=$Lvc16_i_cgs?>
    </td>
    <td nowrap width="30%">
      <?
      db_input('iCgs', 10, $Ivc16_i_cgs, true, 'text', 3, '');
      db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
      ?>
    </td>
    <td nowrap width="6%" align="right">
      <b>Idade:</b>
    </td>
    <td nowrap width="64%">
      &nbsp;&nbsp;
      <?
      db_input('idade', 23, '', true, 'text', 3, '');
      ?>
    </td>
  </tr>
</table>

<table marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <?
   
   $sSql      = $clvac_calendario->sql_query("","*","vc05_i_idadeini","");
   $rsResult  = $clvac_calendario->sql_record($sSql);
   if ($clvac_calendario->numrows > 0) {

     $aAbaNome   = array();
     $aSrc       = array();
     $aSizecampo = array();
     $aDisabled  = array();
     for($iX=0; $iX < $clvac_calendario->numrows; $iX++){
      
       $oCalendario           = db_utils::fieldsmemory($rsResult,$iX); 
       $sApelido              = "a".($iX+1);
       $aAbaNome[$sApelido]   = $oCalendario->vc05_c_descr;
       $aSrc[$sApelido]       = "vac4_vac_grade004.php?iCalendario=$oCalendario->vc05_i_codigo&iCgs=$iCgs";
       $aSizecampo[$sApelido] = "20";
       $aDisabled[$sApelido]  = "false";

     } 
     $sApelido       = "a".($iX+1);
     $aAbaNome[$sApelido]   = "Vacinas fora da rede";
     $aSrc[$sApelido]       = "vac4_foradarede004.php?iCgs=$iCgs";
     $aSizecampo[$sApelido] = "30";
     $aDisabled[$sApelido]  = "false";
 
     $clcriaabas->identifica    = $aAbaNome;
     $clcriaabas->src           = $aSrc;
     $clcriaabas->sizecampo     = $aSizecampo;
     $clcriaabas->disabled      = $aDisabled;
     $clcriaabas->scrolling     = "yes";
     $clcriaabas->iframe_height = "530";
     $clcriaabas->iframe_width  = "100%";
      $clcriaabas->abas_top = "70";
     $clcriaabas->cria_abas();
   
   }else{
     
     echo"<br><br><center> Nenhum Calendario encontrado! </center>";

   }
   ?>
  </td>
 </tr>
</table>
</form>
</body>
</html>