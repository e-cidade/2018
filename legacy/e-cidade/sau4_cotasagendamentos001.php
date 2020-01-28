<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

// Basico
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
include_once(modification("libs/db_usuariosonline.php"));
include_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

db_postmemory($HTTP_POST_VARS);
$oDaoSauCotasAgendamento = db_utils::getdao('sau_cotasagendamento');
$oDaoUnidades            = db_utils::getdao('unidades');
$db_opcao                = 1;

if(!isset($s163_i_anocomp)){
  $s163_i_anocomp = date("Y",db_getsession("DB_datausu"));; 
}
if(!isset($s163_i_mescomp)){
  $s163_i_mescomp = date("m",db_getsession("DB_datausu"));; 
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
    <style type="text/css">
      .classMarcado {
        background-color: #FFFF00;
      }
    </style>
  </head>
  <body>
    <?php
    require modification("forms/db_frmsau_cotasagendamento.php");
    db_menu();
    ?>
  </body>
</html>
<script>
js_tabulacaoforms("form1", "s163_i_upsprestadora", true, 1, "s163_i_upsprestadora", true);
</script>