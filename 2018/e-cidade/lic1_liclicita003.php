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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clliclicita         = new cl_liclicita;
$clliclicitem        = new cl_liclicitem;
$cldb_usuarios       = new cl_db_usuarios;
$clpcorcamitemlic    = new cl_pcorcamitemlic;
$clliclicitemlote    = new cl_liclicitemlote;
$clliclicitemanu     = new cl_liclicitemanu;
$clliclicitaweb      = new cl_liclicitaweb;
$clliclicitasituacao = new cl_liclicitasituacao;
$clcflicita          = new cl_cflicita;
$clliclicitaproc     = new cl_liclicitaproc;

$erro_msg = '';
$db_botao = false;
$db_opcao = 33;

if(isset($excluir)){
  $sqlerro=false;
  $db_opcao = 3;


  db_inicio_transacao();

  $clliclicitaweb->sql_record($clliclicitaweb->sql_query_file(null,"*",null,"l29_liclicita=$l20_codigo"));
	if ($clliclicitaweb->numrows > 0){

      $sqlerro  = true;
			$erro_msg = "Licitação já publicada ou baixada.\\n Não pode ser Excluida";

	}
	if ($sqlerro == false){

    $clliclicitasituacao->excluir(null,"l11_liclicita = $l20_codigo");
    if ($clliclicitasituacao->erro_status == 0){

      $sqlerro  = true;
			$erro_msg = $clliclicitasituacao->erro_msg;

		}

	}

	$result_item = $clliclicitem->sql_record($clliclicitem->sql_query_file(null,"l21_codigo",null,"l21_codliclicita=$l20_codigo"));
  $numrows_item = $clliclicitem->numrows;
  for($w=0;$w<$numrows_item;$w++){
    db_fieldsmemory($result_item,$w);
    if ($sqlerro==false){
      $clpcorcamitemlic->excluir(null,"pc26_liclicitem=$l21_codigo");
      if ($clpcorcamitemlic->erro_status==0){
	        $sqlerro=true;
          $erro_msg=$clpcorcamitemlic->erro_msg;
	        break;
      }
    }

    if ($sqlerro==false){
         $clliclicitemlote->excluir(null,"l04_liclicitem = $l21_codigo");
         if ($clliclicitemlote->erro_status==0){
              $sqlerro = true;
              $erro_msg = $clliclicitemlote->erro_msg;
              break;
         }
    }

    if ($sqlerro==false){
         $clliclicitemanu->excluir(null,"l07_liclicitem = $l21_codigo");
         if ($clliclicitemanu->erro_status==0){
              $sqlerro = true;
              $erro_msg = $clliclicitemanu->erro_msg;
              break;
         }
     }
  }

  if ($sqlerro==false){
    $clliclicitem->excluir(null,"l21_codliclicita=$l20_codigo");
    if ($clliclicitem->erro_status==0){
      $sqlerro=true;
      $erro_msg = $clliclicitem->erro_msg;
    }
  }

  if ($sqlerro==false){
    $clliclicitaproc->excluir(""," l34_liclicita = $l20_codigo");
    $erro_msg = $clliclicitaproc->erro_msg;
    if ($clliclicitaproc->erro_status==0){
      $sqlerro=true;
    }
  }

  /**
   * Remove os atributos dinâmicos vinculados a licitação
   */
  if (!$sqlerro) {

    $oDaoLicitacaoAtributos = new cl_liclicitacadattdinamicovalorgrupo();
    $sSqlAtributos = $oDaoLicitacaoAtributos->sql_query_file(null, "l16_cadattdinamicovalorgrupo", null, "l16_liclicita = {$l20_codigo}");
    $rsAtributos   = $oDaoLicitacaoAtributos->sql_record($sSqlAtributos);

    if ($rsAtributos && $oDaoLicitacaoAtributos->numrows > 0) {

      try {

        $oDaoLicitacaoAtributos->excluir(null, "l16_liclicita = {$l20_codigo}");

        if ($oDaoLicitacaoAtributos->erro_status == 0) {
          throw new Exception("Erro ao remover grupo de atributos da licitação.");
        }

        for ($iRow = 0; $iRow < pg_num_rows($rsAtributos); $iRow++) {

          $oGrupoAtributos = new DBAttDinamicoGrupo(db_utils::fieldsMemory($rsAtributos, $iRow)->l16_cadattdinamicovalorgrupo);
          $oGrupoAtributos->excluir();
        }
      } catch (Exception $e) {

        $sqlerro  = true;
        $erro_msg = $e->getMessage();
      }
    }
  }

  if ($sqlerro==false){
    $clliclicita->excluir($l20_codigo);
    $erro_msg = $clliclicita->erro_msg;
    if ($clliclicita->erro_status==0){
      $sqlerro=true;
    }
  }

  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clliclicita->sql_record($clliclicita->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   if ($l08_altera == "t"){
      	$db_botao = true;
   }
}
$db_opcao_editavel = $db_opcao;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
  	<?php
      include modification("forms/db_frmliclicita.php");
      db_menu();
    ?>
  </body>
</html>
<?php

  if (isset($excluir)) {
    if($clliclicita->erro_status==0){

  	  db_msgbox($erro_msg);
      $clliclicita->erro(true,false);
    }else{
      $clliclicita->erro(true,true);
    };
  };
  if($db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>