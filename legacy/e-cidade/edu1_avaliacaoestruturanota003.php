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
  require_once(modification("libs/db_utils.php"));
  require_once(modification("classes/db_avaliacaoestruturanota_classe.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("libs/db_stdlibwebseller.php"));

  parse_str($_SERVER["QUERY_STRING"]);
  db_postmemory($_POST);
  $oDaoAvaliacaoEstruturaNota  = new cl_avaliacaoestruturanota;
  $oDaoAvaliacaoEstruturaRegra = new cl_avaliacaoestruturaregra;
  $db_botao   = false;
  $db_opcao   = 33;
  $iCodEscola = db_getsession("DB_coddepto");

  if (isset($excluir)) {

    db_inicio_transacao();
    $db_opcao = 3;
    $sWhereCodigo       = " ed318_avaliacaoestruturanota = {$ed315_sequencial}";
    $oDaoAvaliacaoEstruturaRegra->excluir(null, $sWhereCodigo);
    $oDaoAvaliacaoEstruturaNota->excluir($ed315_sequencial);
    if ($oDaoAvaliacaoEstruturaNota->erro_status == 0) {

      db_msgbox($oDaoAvaliacaoEstruturaNota->erro_msg);
      $sqlerro = true;
    } else {

      db_msgbox($oDaoAvaliacaoEstruturaNota->erro_msg);
      $ed315_sequencial     = '';
      $ed315_db_estrutura   = '';
      $db77_descr           = '';
      $ed315_ativo          = '';
      $ed315_arredondamedia = '';
      $ed316_sequencial     = '';
      $ed316_descricao      = '';
      $ed315_observacao     = '';
    }
    db_fim_transacao();
  } else if (isset($chavepesquisa)) {

     $db_opcao = 3;
     $sSqlDadosAvaliacao       = $oDaoAvaliacaoEstruturaNota->sql_query_configuracao_escola($chavepesquisa);
     $rsAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNota->sql_record($sSqlDadosAvaliacao);
     db_fieldsmemory($rsAvaliacaoEstruturaNota, 0);
     $db_botao = true;
  }
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC style="margin-top: 25px" >
    <center>
  	  <?
  	    require_once(modification("forms/db_frmavaliacaoestruturanota.php"));
  	  ?>
    </center>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<?
  if (isset($excluir)) {

    if ($oDaoAvaliacaoEstruturaNota->erro_status == "0") {
      $oDaoAvaliacaoEstruturaNota->erro(true,false);
    } else {
      $oDaoAvaliacaoEstruturaNota->erro(true,true);
    }
  }
  if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
<script>
  js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>