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
  require_once("libs/db_usuariosonline.php");
  require_once("classes/db_aguacorresp_classe.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_app.utils.php");
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);
  
  $claguacorresp = new cl_aguacorresp;
  
  $db_botao = false;
  $db_opcao = 33;
  
  if (isset($excluir)) {
    
  	db_inicio_transacao();
    
  	$db_opcao = 3;
    $claguacorresp->excluir($x02_codcorresp);
    
    db_fim_transacao();
  } else if (isset($chavepesquisa)) {
     
  	 $db_opcao = 3;
  	 $db_botao = true;
  	 
     $result = $claguacorresp->sql_record($claguacorresp->sql_query($chavepesquisa)); 
     db_fieldsmemory($result, 0);
  }
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load('scripts.js,estilos.css, prototype.js, strings.js');
    ?>
  </head>
  <body bgcolor="#CCCCCC">
    <center>
	    <?php
	      include("forms/db_frmaguacorresp.php");
	    ?>
    </center>
    <?php
      db_menu(db_getsession("DB_id_usuario"),
      		    db_getsession("DB_modulo"),
      		    db_getsession("DB_anousu"),
      		    db_getsession("DB_instit"));
    ?>
  </body>
</html>
<?php

  if (isset($excluir)) {
    
  	if ($claguacorresp->erro_status == "0") {
      
  		$claguacorresp->erro(true, false);
    } else {
    	
      $claguacorresp->erro(true, true);
    }
  }

  if ($db_opcao == 33) {
    
  	echo "<script>document.form1.pesquisar.click();</script>";
  }
?>