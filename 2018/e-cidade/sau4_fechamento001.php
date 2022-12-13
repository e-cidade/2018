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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("libs/db_stdlibwebseller.php"));

db_postmemory ( $_POST );
$oDaoSauFechamento    = db_utils::getdao('sau_fechamento');
$oDaoSauFechapront    = db_utils::getdao('sau_fechapront');
$oDaoProntproced      = db_utils::getdao('prontproced');
$oDaoSauFinanciamento = db_utils::getdao('sau_financiamento');
$db_opcao2            = 1;
$db_opcao             = 1;
$db_botao             = true;
$db_opcao1            = 1;
$oIframeAltExc        = new cl_iframe_alterar_excluir();

if (isset ( $opcao )) {

  $db_botao1 = true;
  if ($opcao == "alterar") {

    $db_opcao  = 2;
    $db_opcao2 = 3;
    $sSql      = $oDaoSauFechamento->sql_query ($sd97_i_codigo);
    $result    = $oDaoSauFechamento->sql_record($sSql);
    db_fieldsmemory ($result, 0);

  }
}

if (isset ( $incluir )) {

  $aDataIni = explode('/', $sd97_d_dataini);
  $dIni     = $aDataIni[2]."-".$aDataIni[1]."-".$aDataIni[0];
  $aDataFim = explode('/', $sd97_d_datafim);
  $dFim     = $aDataFim[2]."-".$aDataFim[1]."-".$aDataFim[0];
  $sWhere   = " sd29_d_data between '$dIni' and  '$dFim' ";
  $sWhere  .= " and sd24_c_digitada = 'S' ";
  if ($sd97_i_financiamento != "0") {

    $sWhere .= " and  sd65_c_financiamento=(select sd65_c_financiamento from sau_financiamento where ";
    $sWhere .= " sd65_i_codigo=$sd97_i_financiamento)";

  }
  $sSql     = $oDaoProntproced->sql_query_procedimentos("", "*", "", $sWhere);
  $rsResult = $oDaoSauFechamento->sql_record($sSql);

  /*PLUGIN ESF - Adicionando mais uma condição no IF do incluir - NÃO ALTERAR O IF ABAIXO*/
  if ($oDaoSauFechamento->numrows > 0) {

    db_inicio_transacao ();

    //inclusao fechamento
    $oDaoSauFechamento->sd97_c_tipo          = "Fechada";
    $oDaoSauFechamento->sd97_d_dataini       = $dIni;
    $oDaoSauFechamento->sd97_d_datafim       = $dFim;
    $oDaoSauFechamento->sd97_i_compmes       = $sd97_i_compmes;
    $oDaoSauFechamento->sd97_i_compano       = $sd97_i_compano;
    $oDaoSauFechamento->sd97_i_financiamento = $sd97_i_financiamento;
    $oDaoSauFechamento->sd97_i_login         = DB_getsession("DB_id_usuario");
    $oDaoSauFechamento->sd97_c_hora          = db_hora();
    $oDaoSauFechamento->incluir ( null );

    if ($oDaoSauFechamento->erro_status != "0") {
      /*PLUGIN ESF - Adicionando mais uma condição validando procedimetno ambulatorio */

      $sSql      = "insert into sau_fechapront ";
      $sCampos   = "nextval('sau_fechapront_sd98_codigo_seq'),sd29_i_codigo," . $oDaoSauFechamento->sd97_i_codigo;
      $sSql     .= $oDaoProntproced->sql_query_procedimentos("", $sCampos, "", $sWhere);
      $rsResult  = db_query($sSql);
      if (pg_affected_rows($rsResult) == 0 || pg_affected_rows($rsResult) == false) {

        $oDaoSauFechamento->erro_msg    = " Nenhum registro encontrado![2] ";
        $oDaoSauFechamento->erro_status = "0";
      }

      /*PLUGIN ESF - fecha condição validando procedimetno ambulatorio */


      /*PLUGIN ESF - Na inclusão sau_fechapront, incluso procedimento ESF - NÃO ALTERAR O IF ABAIXO*/

    }
    db_fim_transacao ();

  } else {

    $oDaoSauFechamento->erro_status = "0";
    $oDaoSauFechamento->erro_msg    = " Nenhum registro encontrado! ";
  }

} else if (isset ( $alterar )) {

  $aDataIni = explode('/',$sd97_d_dataini);
  $dIni     = $aDataIni[2]."-".$aDataIni[1]."-".$aDataIni[0];
  $aDataFim = explode('/',$sd97_d_datafim);
  $dFim     = $aDataFim[2]."-".$aDataFim[1]."-".$aDataFim[0];
  $sWhere   = " sd29_d_data between '$dIni' and  '$dFim' ";
  $sWhere  .= " and sd24_c_digitada = 'S' ";
  if ($sd97_i_financiamento != "0") {

    $sWhere .= " and  sd65_c_financiamento=(select sd65_c_financiamento from sau_financiamento where ";
    $sWhere .= " sd65_i_codigo=$sd97_i_financiamento)";

  }
  $sSql     = $oDaoProntproced->sql_query_procedimentos("", "*", "", $sWhere);
  $rsResult = $oDaoSauFechamento->sql_record($sSql);

  /*PLUGIN ESF - Adicionando mais uma condição no IF do alterar - NÃO ALTERAR O IF ABAIXO*/
  if ($oDaoSauFechamento->numrows > 0) {

    db_inicio_transacao();
    $oDaoSauFechamento->sd97_c_hora = db_hora();
    $oDaoSauFechamento->alterar($sd97_i_codigo);

    /*PLUGIN ESF - Adicionando mais uma condição no IF da exclusao da sau_fechapront - NÃO ALTERAR O IF ABAIXO*/
    if ($oDaoSauFechamento->erro_status != "0") {

      $oDaoSauFechapront->excluir("", "sd98_i_fechamento= $sd97_i_codigo");

      /*PLUGIN ESF - Adicionando mais uma condição no IF da nova inclusão da sau_fechapront - NÃO ALTERAR O IF ABAIXO*/
      if ($oDaoSauFechapront->erro_status != "0") {

        $sSql      = " insert into sau_fechapront ";
        $sCampos   = "nextval('sau_fechapront_sd98_codigo_seq'),sd29_i_codigo," . $oDaoSauFechamento->sd97_i_codigo;
        $sSql     .= $oDaoProntproced->sql_query_procedimentos("", $sCampos, "", $sWhere);
        $rsResult  = db_query($sSql);
        if (pg_affected_rows($rsResult) == 0 || pg_affected_rows($rsResult) == false) {

          $oDaoSauFechamento->erro_msg   = " Nenhum registro encontrado![2] ";
          $cllab_fechamento->erro_status = "0";
        }
      } else {

        $oDaoSauFechamento->erro_status = "0";
        $oDaoSauFechamento->erro_msg    = " Erro ao excluir: ".$oDaoSauFechapront->erro_msg;
      }
    }

    /** PLUGIN ESF - Na alteração sau_fechapront, exclui se houver procedimentos ESF */
    db_fim_transacao ();

  } else {

    $oDaoSauFechamento->erro_status = "0";
    $oDaoSauFechamento->erro_msg    = " Nenhum registro encontrado! ";
  }
} elseif ($db_opcao == 1 && !isset($sd97_d_dataini)) {

  //Pega o ultimo fechamento e incrementa os valores
  $sSql =  $oDaoSauFechamento->sql_query ("",
                                          "sd97_i_compmes,sd97_i_compano,sd97_d_datafim",
                                          "sd97_i_compano desc,sd97_i_compmes desc limit 1");
  $result1 = $oDaoSauFechamento->sql_record ($sSql);
  if ($oDaoSauFechamento->numrows > 0) {

    $oFechamento = db_utils::fieldsmemory($result1, 0);
    if ($oFechamento->sd97_i_compmes == 12) {

      $sd97_i_compmes = $oFechamento->sd97_i_compmes = 1;
      $sd97_i_compano = $oFechamento->sd97_i_compano+1;

    } else {

      $sd97_i_compmes = $oFechamento->sd97_i_compmes+1;
      $sd97_i_compano = $oFechamento->sd97_i_compano;

    }
    $aDataIni           = explode('-',$oFechamento->sd97_d_datafim);
    $sd97_d_dataini     = somaDataDiaMesAno($aDataIni[2], $aDataIni[1], $aDataIni[0], 1, 0, 0);
    $aData              = explode('/', $sd97_d_dataini);
    $sd97_d_dataini_dia = $aData[0];
    $sd97_d_dataini_mes = $aData[1];
    $sd97_d_dataini_ano = $aData[2];
    $sd97_d_datafim_dia = "";
    $sd97_d_datafim_mes = "";
    $sd97_d_datafim_ano = "";
    $sd97_d_datafim     = "";

  }

}



/*PLUGIN ESF - Adicionado Funções para tratar os procedimentos do ESF */

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <center>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC"><br>
          <br><br>
          <?
            include(modification("forms/db_frmsau_fechamento.php"));
          ?>
        </td>
      </tr>
    </table>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
        db_getsession("DB_instit"));
?>
  </body>
</html>
<script>
  js_tabulacaoforms("form1", "sd97_i_compmes", true, 1, "sd97_i_compmes", true);
</script>
<?
  if (isset ($incluir) || isset ($alterar)) {

    if ($oDaoSauFechamento->erro_status == "0") {

      $oDaoSauFechamento->erro (true, false);
      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if ($oDaoSauFechamento->erro_campo != "") {

        echo "<script> document.form1.".$oDaoSauFechamento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoSauFechamento->erro_campo.".focus();</script>";

      }

    } else {
      $oDaoSauFechamento->erro (true, true);
    }

  }
  if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>