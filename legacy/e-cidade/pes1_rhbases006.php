<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_bases_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($_GET);
db_postmemory($_POST);
$clbases = new cl_bases;
$db_opcao = 22;
$db_botao = false;

if(isset($alterar)){

  db_inicio_transacao();
  $db_opcao = 2;
  $sqlerro  = false;
  $db_botao = true;
  $anousu = db_anofolha();
  $mesusu = db_mesfolha();

  $clbases->r08_anousu = $anousu;
  $clbases->r08_mesusu = $mesusu;

  $clbases->alterar($anousu,$mesusu,$r08_codigo,db_getsession("DB_instit"));
  $erro_msg = $clbases->erro_msg;

  if ($clbases->erro_status == 0 ) {
    $sqlerro=true;
  }

  db_fim_transacao($sqlerro);

} else if(isset($chavepesquisa)) {

   $anousu = DBPessoal::getAnoFolha();
   $mesusu = DBPessoal::getMesFolha();
   $db_opcao = 2;

   $result = $clbases->sql_record(
    $clbases->sql_query($anousu,$mesusu,$chavepesquisa,db_getsession("DB_instit"))
   );

   if (!$result) {
     $erro_msg = "Erro ao buscar os dados da Base.";
     $sqlerro = true;
   } else {
     db_fieldsmemory($result,0);
     $db_botao = true;
   }
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
  <?php

  require_once modification("forms/db_frmrhbases.php");

  if(isset($alterar)){

    db_msgbox($erro_msg);

    if($clbases->erro_status=="0"){

      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

      if($clbases->erro_campo!=""){
        echo "<script> document.form1.".$clbases->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clbases->erro_campo.".focus();</script>";
      };
    };
  };
  if(isset($chavepesquisa)){

    echo "
    <script>
      function js_db_libera(){
        parent.document.formaba.rhrubricas.disabled=false;
        (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhrubricas.location.href='pes1_rhrubricas007.php?r09_base=".$r08_codigo."';
        ";
        if(isset($liberaaba)){
          echo "  parent.mo_camada('rhrubricas');";
        }
    echo "
      }
      js_db_libera();
    </script>
    ";
  }
  if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
  }
  ?>
  </body>
</html>
