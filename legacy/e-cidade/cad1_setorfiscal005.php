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
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clsetorfiscal->alterar($j90_codigo);
  if($clsetorfiscal->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $clsetorfiscal->erro_msg;
  db_fim_transacao($sqlerro);
  $db_opcao = 2;
  $db_botao = true;
}else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $db_botao = true;
  $result = $clsetorfiscal->sql_record($clsetorfiscal->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
}
?>
  <html xmlns="http://www.w3.org/1999/html">
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <div class="container">
    <center>
      <?php
      require_once(modification("forms/db_frmsetorfiscal.php"));
      ?>
    </center>
  </div>
  </body>
  </html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clsetorfiscal->erro_campo!=""){
      echo "<script> document.form1.".$clsetorfiscal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsetorfiscal->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.setorfiscalvalor.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_setorfiscalvalor.location.href='cad1_setorfiscalvalor001.php?j82_setorfiscal=".@$j90_codigo."&j90_descr=".@$j90_descr."';
     ";
  if(isset($liberaaba)){
    echo "  parent.mo_camada('setorfiscalvalor');";
  }
  echo"}\n
    js_db_libera();
  </script>\n
 ";
}
if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>