<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_clabens_classe.php");
require_once("classes/db_bensmater_classe.php");
require_once("classes/db_bensimoveis_classe.php");
require_once("classes/db_bensbaix_classe.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_cfpatri_classe.php");
require_once("classes/db_bensplaca_classe.php");
require_once("classes/db_benslote_classe.php");
require_once("classes/db_benstransfcodigo_classe.php");
require_once("classes/db_departdiv_classe.php");
require_once("classes/db_bensdiv_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/db_cfpatriplaca_classe.php");
require_once("classes/db_histbem_classe.php");

$cldepartorg    = new cl_db_departorg;
$cldb_estrut    = new cl_db_estrut;
$clbens         = new cl_bens;
$clbensmater    = new cl_bensmater;
$clbensimoveis  = new cl_bensimoveis;
$clclabens      = new cl_clabens;
$clbensbaix     = new cl_bensbaix;
$clcfpatri      = new cl_cfpatri;
$clbensplaca    = new cl_bensplaca;
$clbenslote     = new cl_benslote;
$cldepartdiv    = new cl_departdiv;
$clbensdiv      = new cl_bensdiv;
$clcfpatri      = new cl_cfpatri;
$clcfpatriplaca = new cl_cfpatriplaca;
$clbenstransfcodigo = new cl_benstransfcodigo;
$clhistbem      = new cl_histbem;
$oDaoBensMedida = db_utils::getDao('bensmedida');
$oDaoBensMarca  = db_utils::getDao('bensmarca');
$oDaoBensModelo = db_utils::getDao('bensmodelo');
$db_opcao       = 2;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, strings.js, DBToogle.widget.js, dbmessageBoard.widget.js");
  db_app::load("estilos.css");
?>
<style type="text/css">
  .bold {
    font-weight: bold;
  }
div#fieldsetInclusaoBensGlobal table {
    border-collapse: collapse;
  }
div#fieldsetInclusaoBensGlobal  table tr td {
    padding-top:4px;
    white-space:nowrap;
  }
  div#fieldsetInclusaoBensGlobaltable tr td:first-child {
    text-align: left;
    width: 130px;
  }
  /* pega a segunda td */
 div#fieldsetInclusaoBensGlobaltable tr td + td {
    
  }
  /* pega a terceira td */
div#fieldsetInclusaoBensGlobal  table tr td + td + td {
    text-align: right;
    padding-left: 5px;
    width: 100px;
  }
div#fieldsetInclusaoBensGlobal  table tr td + td + td + td {
    text-align: left;
    width: 150px;
  }
  .ancora, legend {
    font-weight: bold;
  }
  .leiutura{
    background-color: #DEB887;
  }
  
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_carregaDadosForm(<?=$db_opcao?>);" >
<br><br>
<table valign="top" marginwidth="0" width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
        <center>
          <?
            include("forms/db_frm_bensglobalnovo.php");
          ?>
        </center>
      </td>
    </tr>
</table>
</body>
</html>

<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
  
<?
  if (isset($alterar) && $erro_msg!="") {
    
    db_msgbox($erro_msg);
    if ($sqlerro==true) {
      if($clbens->erro_campo!=""){
        echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
      };
    }
  }
 if(($db_opcao==22||$db_opcao==33) && $msg_erro==""){
    echo "<script>js_pesquisa();</script>";
 }
?>