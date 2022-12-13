<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
$clcriaabas = new cl_criaabas;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="abas">
 <?php
   $clcriaabas->identifica = array("fiscalproc"=>"Procedências","fiscalprocrec"=>"Receitas");
   $clcriaabas->title      = array("fiscalproc"=>"Procêdencias","fiscalprocrec"=>"Receitas");
   $clcriaabas->src        = array("fiscalproc"=>"fis1_fiscalproc001.php?abas=1","fiscalprocrec"=>"fis1_fiscalprocrec001.php?primeira=1");
   $clcriaabas->cria_abas();

   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 ?>
</body>
</html>
<?php
if(isset($db_opcao) && $db_opcao==2){

  echo "
         <script>
	   function js_src(){
            iframe_fiscalproc.location.href='fis1_fiscalproc002.php?abas=1&db_opcao=22';\n
	   }
	   js_src();
         </script>
       ";
  exit;
}else if(isset($db_opcao) && $db_opcao==3){

  echo "
           <script>
  	   function js_src(){
              iframe_fiscalproc.location.href='fis1_fiscalproc003.php?abas=1';\n
  	    document.formaba.fiscalprocrec.disabled=true;
  	   }
  	   js_src();
           </script>
         ";
  exit;
}
  echo "
	 <script>
	    document.formaba.fiscalprocrec.disabled=true;
         </script>
       ";
       exit;
?>