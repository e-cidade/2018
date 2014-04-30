<?php
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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empage_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagemovconta_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagepag_classe.php");
include("classes/db_empageslip_classe.php");
include("classes/db_empagemovforma_classe.php");
include("classes/db_empagegera_classe.php");
include("classes/db_empageconf_classe.php");
include("classes/db_empageconfgera_classe.php");
include("classes/db_conplanoconta_classe.php");
include("classes/db_empagemod_classe.php");
include("classes/db_pagordem_classe.php");
include("classes/db_pagordemconta_classe.php");
include("classes/db_pcfornecon_classe.php");
include("classes/db_empageforma_classe.php");
include("model/agendaPagamento.model.php");
include("classes/db_empageordem_classe.php");
include("classes/db_empagenotasordem_classe.php");
include("classes/db_empparametro_classe.php");

$empagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clpagordemconta   = new cl_pagordemconta;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagemovconta  = new cl_empagemovconta;
$clempagepag  = new cl_empagepag;
$clpcfornecon  = new cl_pcfornecon;
$clempageforma = new cl_empageforma;
$clempagemovforma = new cl_empagemovforma;
$clempage = new cl_empage;
$clempagetipo = new cl_empagetipo;
$clempageslip = new cl_empageslip;
$clempagemovforma = new cl_empagemovforma;
$clempagegera = new cl_empagegera;
$clempageconf = new cl_empageconf;
$clempageconfgera = new cl_empageconfgera;
$clempagemod      = new cl_empagemod;
$oDaoNotasOrdem   = new cl_empagenotasordem;
$oDaoOrdemAgenda  = new cl_empageordem;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
.configurada {
    background-color: #d1f07c;
}
.ComMov {
    background-color: rgb(222, 184, 135);
}
.configuradamarcado {
    background-color: #EFEFEF;
}
.ComMovmarcado {
    background-color: #EFEFEF;
}
.normalmarcado{
    background-color:#EFEFEF
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="450" align="left" valign="top" bgcolor="#CCCCCC"> 
    <?
      include("forms/db_frmmanutencaoagendaslip.php");
    ?>
    </td>
  </tr>
</table>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<?
if(isset($atualizar) && $sqlerro==true){
  db_msgbox($erro_msg);
}
?>