<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_setorfiscal_classe.php"));
require_once(modification("classes/db_setorfiscalvalor_classe.php"));
$clsetorfiscal = new cl_setorfiscal;
/*
$clsetorfiscalvalor = new cl_setorfiscalvalor;
*/
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clsetorfiscal->j90_valor = '0';
  $clsetorfiscal->incluir($j90_codigo);
  if($clsetorfiscal->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $clsetorfiscal->erro_msg;
  db_fim_transacao($sqlerro);
  $j90_codigo= $clsetorfiscal->j90_codigo;
  $db_opcao = 1;
  $db_botao = true;
}
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
  <div class="container">
    <?php
    require_once(modification("forms/db_frmsetorfiscal.php"));
    ?>
  </div>
  </body>
  </html>
<?php
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clsetorfiscal->erro_campo!=""){
      echo "<script> document.form1.".$clsetorfiscal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsetorfiscal->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
    db_redireciona("cad1_setorfiscal005.php?liberaaba=true&chavepesquisa=$j90_codigo");
  }
}
?>