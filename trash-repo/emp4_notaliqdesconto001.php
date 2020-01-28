<?php
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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("classes/db_orcdotacao_classe.php");
  require_once("classes/db_orctiporec_classe.php");
  require_once("classes/db_empempenho_classe.php");
  require_once("classes/db_empelemento_classe.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_utils.php");
  
  $clempempenho  = new cl_empempenho;
  $clempelemento = new cl_empelemento;
  $clorcdotacao  = new cl_orcdotacao;
  $clorctiporec  = new cl_orctiporec;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?
  db_app::load("scripts.js, strings.js, prototype.js, notaliquidacao.js, datagrid.widget.js, widgets/windowAux.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/dbtextField.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>


</head>
<body bgcolor=#CCCCCC  onLoad="a=1" >
  <table border="0" cellspacing="0" cellpadding="0">
    <tr height='25'> 
      <td>&nbsp;</td>
      </td>
   </tr>
  </table>
  <?
   include("forms/db_frmnotaliqdesconto.php");
  ?> 
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>