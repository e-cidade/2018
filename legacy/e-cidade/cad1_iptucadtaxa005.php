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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_iptucadtaxa_classe.php"));
require_once(modification("classes/db_iptucadtaxaexe_classe.php"));

$cliptucadtaxa = new cl_iptucadtaxa;
db_postmemory($_POST);
$db_opcao = 22;
$db_botao = false;

if (isset($alterar)) {
  $sqlerro = false;
  
  db_inicio_transacao();
  $cliptucadtaxa->alterar($j07_iptucadtaxa);

  if ($cliptucadtaxa->erro_status == 0) {
    $sqlerro=true;
  } 

  $erro_msg = $cliptucadtaxa->erro_msg; 
  db_fim_transacao($sqlerro);
  $db_opcao = 2;
  $db_botao = true;  

} else if (isset($chavepesquisa)) {

   $db_opcao = 2;
   $db_botao = true;
   $result   = $cliptucadtaxa->sql_record($cliptucadtaxa->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
<body class="body-default" onLoad="a=1">
	<?php
	 include(modification("forms/db_frmiptucadtaxa.php"));
	?>
</body>
</html>
<?php
if (isset($alterar)) {
  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    
    if($cliptucadtaxa->erro_campo != "") {
      echo "<script> document.form1.".$cliptucadtaxa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptucadtaxa->erro_campo.".focus();</script>";
    }

  } else {
   db_msgbox($erro_msg);
  }
}

if (isset($chavepesquisa)) {
 echo "
  <script>
    function js_db_libera() {
      parent.document.formaba.iptucadtaxaexe.disabled=false;
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_iptucadtaxaexe.location.href='cad1_iptucadtaxaexe001.php?j08_iptucadtaxa=".@$j07_iptucadtaxa."&j07_descr=".@$j07_descr."';";
      
      if (isset($liberaaba)) {
        echo "  parent.mo_camada('iptucadtaxaexe');";
      }

    echo"}\n
    js_db_libera();
  </script>\n";
}

if ($db_opcao == 22 || $db_opcao == 33) {
   echo "<script>document.form1.pesquisar.click();</script>";
}
?>