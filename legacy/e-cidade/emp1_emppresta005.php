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

  require_once "libs/db_stdlib.php";
  require_once "libs/db_conecta.php";
  require_once "libs/db_sessoes.php";
  require_once "libs/db_usuariosonline.php";
  require_once "libs/db_utils.php";
  require_once "dbforms/db_funcoes.php";

  $clemppresta = new cl_emppresta;
  $clempagemov = new cl_empagemov();

  db_postmemory($HTTP_POST_VARS);

  $db_opcao = 22;
  $db_botao = false;

  if (isset($alterar)) {

    $sqlerro = false;
    db_inicio_transacao();
		$clemppresta->e45_codmov = $e45_codmov;
    $clemppresta->alterar($e45_sequencial);

    if ($clemppresta->erro_status==0) {
      $sqlerro=true;
    }

    $erro_msg = $clemppresta->erro_msg;
    db_fim_transacao($sqlerro);

    $db_opcao = 2;
    $db_botao = true;
  } else if (isset($chavepesquisa)) {

    $db_opcao = 2;
    $db_botao = true;

    $sSql = $clemppresta->sql_query_emp( null,
                                         "e60_codemp, emppresta.*, e44_descr, e60_vlrpag",
                                         null,
                                         "e45_numemp = {$chavepesquisa} and e45_codmov = {$chavemovimento}" );
    $result = $clemppresta->sql_record( $sSql );

    // Verifica se o movimento atual possui registro na tabela emppresta
    if ($clemppresta->numrows == 0) {

      // Caso não exista busca o primeiro registro da tabela emppresta para o empenho selecionado
      $sSql = $clemppresta->sql_query_emp( null,
                                           "e60_codemp, emppresta.*, e44_descr, e60_vlrpag",
                                           null,
                                           "e45_numemp = {$chavepesquisa} limit 1" );
      $result = $clemppresta->sql_record( $sSql );

      // Caso exista algum registro
      if ($clemppresta->numrows > 0) {

        $oResultadoEmpPresta = db_utils::fieldsMemory($result, 0);

        // Verifica se o registro existente na tabela possui um movimento vinculado
        if (empty($oResultadoEmpPresta->e45_codmov)) {

          // Caso não possua utiliza o registro existente setando o movimento
          $clemppresta->e45_codmov = $chavemovimento;
          $clemppresta->e45_sequencial = $oResultadoEmpPresta->e45_sequencial;

          $clemppresta->alterar($chavemovimento);
        }

        // Insere os registros de todos os movimentos que ainda não estão na tabela emppresta
        $sSql = $clempagemov->sql_query_file( null,
                                              'e81_codmov',
                                              null,
                                              "e81_numemp = {$chavepesquisa} and e81_codmov not in (select e45_codmov from emppresta where e45_numemp = {$chavepesquisa})");
        $rsEmpAgeMov = $clempagemov->sql_record( $sSql );

        if ($clempagemov->numrows > 0) {

          for ($iCount = 0; $iCount < $clempagemov->numrows; $iCount++ ) {
            $oCodigoMovimento = db_utils::fieldsMemory($rsEmpAgeMov, $iCount);

            // Salva um novo registro na tabela emppresta com os mesmos dados porém com o movimento selecionado
            $clemppresta->e45_numemp                 = $oResultadoEmpPresta->e45_numemp;
            $clemppresta->e45_data                   = $oResultadoEmpPresta->e45_data;
            $clemppresta->e45_obs                    = $oResultadoEmpPresta->e45_obs;
            $clemppresta->e45_tipo                   = $oResultadoEmpPresta->e45_tipo;
            $clemppresta->e45_acerta                 = $oResultadoEmpPresta->e45_acerta;
            $clemppresta->e45_conferido              = $oResultadoEmpPresta->e45_conferido;
            $clemppresta->e45_processoadministrativo = $oResultadoEmpPresta->e45_processoadministrativo;
            $clemppresta->e45_datalimiteaplicacao    = $oResultadoEmpPresta->e45_datalimiteaplicacao;
            $clemppresta->e45_codmov                 = $oCodigoMovimento->e81_codmov;
            $clemppresta->e45_sequencial             = null;

            $clemppresta->incluir(null);
          }

        }

        $sSql = $clemppresta->sql_query_emp( null,
                                             "e60_codemp, emppresta.*, e44_descr, e60_vlrpag",
                                             null,
                                             "e45_numemp = {$chavepesquisa} and e45_codmov = {$chavemovimento}" );
        $result = $clemppresta->sql_record( $sSql );
      }
    }

    db_fieldsmemory($result, 0);

    if (empty($e60_vlrpag)) {

      db_msgbox("Empenho não está pago.");

      $db_opcao = 3;
      $db_botao = false;

      unset($chavepesquisa);
      unset($chavemovimento);
    }
  }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top: 30px;">
  <center>
    <?
      include("forms/db_frmemppresta.php");

      if(empty($e60_vlrpag)){

        $db_opcao = 3;
        $db_botao = false;
        unset($chavepesquisa);
        echo "<script>js_pesquisa();</script>";
      }
    ?>
  </center>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clemppresta->erro_campo!=""){
      echo "<script> document.form1.".$clemppresta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clemppresta->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.empprestaitem.disabled=false;
         top.corpo.iframe_empprestaitem.location.href='emp1_empprestaitem001.php?e60_codemp=$e60_codemp&e46_numemp=".@$e45_numemp."&e45_sequencial={$e45_sequencial}'";

 if (db_permissaomenu(db_getsession("DB_anousu"),db_getsession("DB_modulo"),4069) == "false"){
      echo "
	 parent.document.formaba.encerra.disabled=true;";
 } else {
      echo "
	 parent.document.formaba.encerra.disabled=false;";
 }

 echo "top.corpo.iframe_encerra.location.href='emp1_empprestaencerra.php?e60_codemp=$e60_codemp&e60_numemp=".@$e45_numemp."&e45_sequencial={$e45_sequencial}'";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('empprestaitem');";
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