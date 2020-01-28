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


require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_custoplano_classe.php"));
require_once(modification("classes/db_custoplanoanalitica_classe.php"));
require_once(modification("classes/db_custotipoconta_classe.php"));
require_once(modification("classes/db_custoplanotipoconta_classe.php"));
require_once(modification("classes/db_custoplanoanaliticacriteriorateio_classe.php"));
require_once(modification("classes/db_custocriteriorateio_classe.php"));
require_once(modification("classes/db_parcustos_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("model/custoRegraRateio.model.php"));
require_once(modification("model/custorateio.model.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($_GET);
db_postmemory($_POST);

$clcustoplano 		   = new cl_custoplano;
$clcustotipoconta      = new cl_custotipoconta;
$clcustoplanotipoconta = new cl_custoplanotipoconta;
$clcustoplanoanalitica = new cl_custoplanoanalitica;
$clparcustos           = new cl_parcustos;
$cldb_estrut           = new cl_db_estrut;
$cldb_depart           = new cl_db_depart;

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;
$custoplanoanalitica = null;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;">
<center>
  <table style="padding-top:15px">
    <tr>
      <td width="500">
        <?
        include(modification("forms/db_frmcustoplano.php"));
        ?>
      </td>
    </tr>
  </table>
</center>
<script>
  js_tabulacaoforms("form1", "cc01_instit", true, 1, "cc01_instit", true);
</script>
<?

if (isset($incluir) && isset($cc01_estrutural)) {

  // vê se o estrutural não está sendo utilizado

  $rsConsulta = $clcustoplano->sql_record($clcustoplano->sql_query_file(null,
                                                                        "count(cc01_estrutural) as qtdreg",
                                                                        null, "cc01_estrutural = '{$cc01_estrutural}'")
  );
  $ConsultaRetorno = db_utils::fieldsMemory($rsConsulta,0);

  if ($ConsultaRetorno->qtdreg == 1) {
    echo "<script> document.form1.cc01_estrutural.style.backgroundColor='#99A9AE'; </script>";
    echo "<script> document.form1.cc01_estrutural.focus(); </script>";
    db_msgbox("Valor do estrutural já está sendo utilizado. Insira um novo valor para o estrutural!");
    exit();

  } else {

    /*
    * faz a validação do campo cc01_estrutural verificando se os níveis do estrutural
    * possuem pai. Se possuir pode inserir o estrutural, se não possuir pai é necessário
    * inserir outro nível e portanto verificar se a conta está selecionada como sintética
    */

    $rsConsulta	   = $clcustoplano->sql_record($clcustoplano->sql_query_file(null,"count( fc_estrutural_pai(cc01_estrutural) ) as regpai",null,"cc01_estrutural = fc_estrutural_pai('{$cc01_estrutural}')"));
    $ConsultaRetorno = db_utils::fieldsMemory($rsConsulta,0);

    // um novo nível só pode ser inserido se for sintático

    if ( ($ConsultaRetorno->regpai == 0) && ($analitico == "s") ) {
      db_msgbox("Não existe conta sintética de nível superior! Ao inserir um novo nível, no estrutural, a conta precisa estar selecionada como sintética!");
      echo "<script> document.form1.analitico.style.backgroundColor='#99A9AE'; </script>";
      exit();
    }

    /*
    * verifica se o nível superior da conta, que será inserida, está cadastrada como sintética,
    * sendo que não pode haver conta analítica sem a conta pai estar definida como sintética
      */

    $rsConsultaAnalitica = $clcustoplanoanalitica->sql_record($clcustoplanoanalitica->sql_query(null, "fc_estrutural_pai('{$cc01_estrutural}') as estrutpai, cc04_custoplano", null, "cc01_estrutural = fc_estrutural_pai('{$cc01_estrutural}')"));

    if ( ($ConsultaRetorno->regpai == 1) && ($clcustoplanoanalitica->numrows > 0) ) {
      $oRetornoEstrutPai = db_utils::fieldsMemory($rsConsultaAnalitica,0);
      db_msgbox("O estrutural {$oRetornoEstrutPai->estrutpai}, da conta superior, precisa estar definido como sintético! Faça a alteração antes de continuar!");
      exit();
    }
  }

  db_inicio_transacao();

  $clcustoplano->cc01_instit  = db_getsession("DB_instit");
  $clcustoplano->incluir(null);

  if ($clcustoplano->erro_status == 0) {
    $lSqlErro = true;
  }
  $sMsgErro = $clcustoplano->erro_msg;
  db_msgbox($sMsgErro);

  /*
  * caso custoplano seja selecionado analitico == "s" no db_frmcustoplano.php
  * grava a chave primária da tabela custoplano na tabela custoplanoanalitica e insere registros
  * na tabela custoplanotipoconta
  */

  if ($analitico == "s") {

    $clcustoplanoanalitica->cc04_custoplano = $clcustoplano->cc01_sequencial;
    $clcustoplanoanalitica->cc04_coddepto   = $coddepto;
    $clcustoplanoanalitica->incluir(null);

    if ($clcustoplanoanalitica->erro_status == 0) {

      $lSqlErro = true;
      $sMsgErro = $clcustoplanoanalitica->erro_msg;

    }

    $clcustoplanotipoconta->cc03_custotipoconta 	 = $cc02_sequencial;
    $clcustoplanotipoconta->cc03_custoplanoanalitica = $clcustoplanoanalitica->cc04_sequencial;
    $clcustoplanotipoconta->incluir(null);

    if ($clcustoplanotipoconta->erro_status == 0) {

      $lSqlErro = true;
      $sMsgErro = $clcustoplanotipoconta->erro_msg;

    }

    /*
     * incluimos um criterio de raterio para essa conta.
     */
    if (!$lSqlErro) {

      $oDaoCustoCriterio = new cl_custocriteriorateio;
      $oDaoCustoCriterio->cc08_automatico = "true";
      $oDaoCustoCriterio->cc08_ativo      = "true";
      $oDaoCustoCriterio->cc08_coddepto   = $coddepto;
      $oDaoCustoCriterio->cc08_descricao  = substr("Conta {$cc01_estrutural} - {$cc01_descricao}",0,50);
      $oDaoCustoCriterio->cc08_obs        = substr("Conta {$cc01_estrutural} - {$cc01_descricao}",0,50);
      $oDaoCustoCriterio->cc08_instit     = db_getsession("DB_instit");
      $oDaoCustoCriterio->cc08_matunid    = 1;
      $oDaoCustoCriterio->incluir(null);
      if ($oDaoCustoCriterio->erro_status == 0){

        $lSqlErro = true;
        $sMsgErro = $oDaoCustoCriterio->erro_msg;

      } else {

        $oCriterioCusto = new custorateio($oDaoCustoCriterio->cc08_sequencial);
        $oRegraCriterio = new custoRegraRateio($oDaoCustoCriterio->cc08_sequencial);
        $oRegraCriterio->setContaPlano($clcustoplanoanalitica->cc04_sequencial);
        $oRegraCriterio->setQuantidade(1);
        $oCriterioCusto->addRegraRateio($oRegraCriterio);
        $oCriterioCusto->save(true);
      }
    }
  } else if ($analitico == "n") {

    //se a conta não é analítica insere ela somente na tabela custoplano e continua na tela de inserção
    db_fim_transacao($lSqlErro);
    db_redireciona("cus1_custoplano001.php");

  }

  db_fim_transacao($lSqlErro);
}

if (isset($incluir)) {

  if ($lSqlErro) {
    db_msgbox($sMsgErro);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false; </script>";
    if ($clcustoplano->erro_campo != "") {
      echo "<script> document.form1.".$clcustoplano->erro_campo.".style.backgroundColor='#99A9AE'; </script>";
      echo "<script> document.form1.".$clcustoplano->erro_campo.".focus(); </script>";
    }

  } else {
    db_redireciona("cus1_custoplano002.php?liberaaba=true&chavepesquisa=".$clcustoplano->cc01_sequencial);
  }
}
?>
</body>
</html>