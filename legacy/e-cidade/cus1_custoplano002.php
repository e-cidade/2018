<?
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
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_custoplano_classe.php"));
require_once(modification("classes/db_custoplanoanalitica_classe.php"));
require_once(modification("classes/db_custoplanoanaliticabens_classe.php"));
require_once(modification("classes/db_custotipoconta_classe.php"));
require_once(modification("classes/db_custoplanotipoconta_classe.php"));
require_once(modification("classes/db_parcustos_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($_GET);
db_postmemory($_POST);

$clparcustos               = new cl_parcustos;
$clcustoplano 		       = new cl_custoplano;
$clcustotipoconta          = new cl_custotipoconta;
$clcustoplanotipoconta     = new cl_custoplanotipoconta;
$clcustoplanoanalitica     = new cl_custoplanoanalitica;
$clcustoplanoanaliticabens = new cl_custoplanoanaliticabens;
$cldb_estrut               = new cl_db_estrut;
$cldb_depart               = new cl_db_depart;

$db_opcao = 22;
$db_botao = false;
$lSqlErro = false;

if ($_POST) {

  // retorna o estrutural da conta antes da alteração
  $rsCustoPlano = $clcustoplano->sql_record($clcustoplano->sql_query_file($cc01_sequencial,"cc01_estrutural"));
  $oRetornoEstrutural = db_utils::fieldsMemory($rsCustoPlano,0);

  // --> se o usuário está alterando uma conta estrutural com nível pai pendente acusa erro

  /*
  * se retornar valores maior do que 1 indica que o nível do estrutural não é do estrutural pai então a alteração
  * é permitida. Caso retonar 1 no count o estrutural é do pai e é verificado se existem pendências abaixo deste
  * nível
  */
  $rsCustoPlano = $clcustoplano->sql_record($clcustoplano->sql_query_file(null,"fc_estrutural_nivel(cc01_estrutural) as estrut_nivel"));
  $oRetornoNivel = db_utils::fieldsMemory($rsCustoPlano,0);

  /*
  * se o nível do estrutural for o primeiro significa que é do estrutural pai, então é preciso verificar se o estrutural
  * pai possui pendências antes de executar a alteração
  */
  if ($oRetornoNivel->estrut_nivel == 1) {

    // obtém se o pai do estrutural não tem pendências
    $rsPendenciasPai = $clcustoplano->sql_record($clcustoplano->sql_query_file(null,"count(cc01_estrutural) as nreg",null,"fc_estrutural_pai(cc01_estrutural) = '{$oRetornoEstrutural->cc01_estrutural}'"));
    $oRetornoPendencias = db_utils::fieldsMemory($rsPendenciasPai,0);

    // cancela a operação se o usuário alterar o estrutural de uma conta com pêndencias
    if ($oRetornoPendencias->nreg > 0 && $oRetornoEstrutural->cc01_estrutural != $cc01_estrutural ) {
      db_msgbox("Operação cancelada! Não é permitido alterar o estrutural uma conta sintética com pendências!");
      db_redireciona("cus1_custoplano002.php");
      exit();
    }

  }

  /*
   * verifica se o nível superior da conta, que será inserida, está cadastrada como sintética,
   * sendo que não pode haver conta analítica sem a conta pai estar definida como sintética
   */
  $rsConsultaAnalitica = $clcustoplanoanalitica->sql_record($clcustoplanoanalitica->sql_query(null, "fc_estrutural_pai('{$cc01_estrutural}') as estrutpai, cc04_custoplano", null, "cc01_estrutural = fc_estrutural_pai('{$cc01_estrutural}')"));

  $rsConsulta     = $clcustoplano->sql_record($clcustoplano->sql_query_file(null,"count( fc_estrutural_pai(cc01_estrutural) ) as regpai",null,"cc01_estrutural = fc_estrutural_pai('{$cc01_estrutural}')"));
  $oRetornoRegPai = db_utils::fieldsMemory($rsConsulta,0);

  if ( ($oRetornoRegPai->regpai == 1) && ($clcustoplanoanalitica->numrows > 0) ) {
    $oRetornoEstrutPai = db_utils::fieldsMemory($rsConsultaAnalitica,0);
    db_msgbox("O estrutural {$oRetornoEstrutPai->estrutpai}, da conta superior, precisa estar definido como sintético! Faça a alteração antes de continuar!");
    exit();
  }

  /*
   * verifica se o tipo de conta não é analitica. Caso não seja analitica o usuário não pode conter
   * bens vinculado ao tipo de conta. Primeiro precisa excluir todos bens para depois fazer a alteração
   */

  if ($analitico == "n") {
    $rsCustoanaliticabens = $clcustoplanoanaliticabens->sql_record($clcustoplanoanaliticabens->sql_query(null,"cc04_sequencial, cc04_custoplano, cc05_sequencial","","cc04_custoplano = {$cc01_sequencial}"));

    if($clcustoplanoanaliticabens->numrows > 0) {

      db_msgbox("Para alterar a conta, de analítica para sintética, primeiro é necessário excluir os bens vinculados a conta!");
      db_redireciona("cus1_custoplano002.php?liberaaba=true&chavepesquisa=".$cc01_sequencial);

    }

    //se o usuário já excluiu os bens é deletado os registros da tabela custoplanotipoconta e custoplanoanalitica
    else {

      $rsCustoanalitica = $clcustoplanoanalitica->sql_record($clcustoplanoanalitica->sql_query_file(null,"cc04_sequencial","","cc04_custoplano = {$cc01_sequencial}"));

      if(isset($clcustoplanoanalitica->numrows) && ($clcustoplanoanalitica->numrows > 0) ) {
        $oRetorno = db_utils::fieldsMemory($rsCustoanalitica,0);

        db_inicio_transacao();

        $clcustoplanotipoconta->excluir("","cc03_custoplanoanalitica = $oRetorno->cc04_sequencial");
        if($clcustoplanotipoconta->erro_status == 0) {
          $lSqlErro = true;
          $sMsgErro = $clcustoplanotipoconta->erro_msg;
        }

        $clcustoplanoanalitica->excluir("","cc04_custoplano = $cc01_sequencial");
        if($clcustoplanoanalitica->erro_status == 0) {
          $lSqlErro = true;
          $sMsgErro = $clcustoplanoanalitica->erro_msg;
        }

        db_fim_transacao($lSqlErro);
      }
    }
  }

  /*
  * verifica se o usuário alterou a conta de analítica para sintética e insere os registros no banco de dados
  * tabela custoplanoanalitica, custoplanotipoconta
  */
  $rsCustoanaliticabens = $clcustoplanoanalitica->sql_record($clcustoplanoanalitica->sql_query_file(null,"cc04_custoplano","","cc04_custoplano = $cc01_sequencial"));
  if( ($clcustoplanoanalitica->numrows == 0) && ($analitico == "s") ) {

    db_inicio_transacao();

    /*
    * caso custoplano seja selecionado analitico == "s" no db_frmcustoplano.php
    * é gravada a chave primária da tabela custoplano na tabela custoplanoanalitica
    */
    $clcustoplanoanalitica->cc04_custoplano = $cc01_sequencial;
    $clcustoplanoanalitica->cc04_coddepto   = $coddepto;
    $clcustoplanoanalitica->incluir(null);

    if($clcustoplanoanalitica->erro_status == 0) {
      $lSqlErro = true;
      $sMsgErro = $clcustoplanoanalitica->erro_msg;
    }

    $clcustoplanotipoconta->cc03_custotipoconta 	  = $cc02_sequencial;
    $clcustoplanotipoconta->cc03_custoplanoanalitica = $clcustoplanoanalitica->cc04_sequencial;
    $clcustoplanotipoconta->incluir(null);

    if($clcustoplanotipoconta->erro_status == 0) {
      $lSqlErro = true;
      $sMsgErro = $clcustoplanotipoconta->erro_msg;
    }

    db_fim_transacao($lSqlErro);
  }

}

if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;

  //atualiza tabela custoplano
  $clcustoplano->cc01_estrutural = $cc01_estrutural;
  $clcustoplano->cc01_descricao  = $cc01_descricao;
  $clcustoplano->cc01_obs        = $cc01_obs;
  $clcustoplano->alterar($cc01_sequencial);

  if($clcustoplano->erro_status == 0) {
    $lSqlErro = true;
    $sMsgErro = $clcustoplano->erro_msg;
  }

  /*
  * faz a consulta das chaves primárias das tabelas custoplanotipoconta e custoplanoanalitica
  * para realizar a alteração do dados através do sequencial
  */
  $rsCustoanalitica  = $clcustoplanoanalitica->sql_record($clcustoplanoanalitica->sql_query_left(null,"cc04_sequencial,cc03_sequencial","","cc04_custoplano = $cc01_sequencial"));
  if($clcustoplanoanalitica->numrows == 1){

    $oRetornoAnalitica = db_utils::fieldsMemory($rsCustoanalitica,0);

    if ( trim($oRetornoAnalitica->cc03_sequencial) != "") {
      //atualiza tabela custoplanotipoconta
      $clcustoplanotipoconta->cc03_sequencial     = $oRetornoAnalitica->cc03_sequencial;
      $clcustoplanotipoconta->cc03_custotipoconta = $cc02_sequencial;
      $clcustoplanotipoconta->alterar($oRetornoAnalitica->cc03_sequencial);

      if($clcustoplanotipoconta->erro_status == 0) {
        $lSqlErro = true;
        $sMsgErro = $clcustoplanotipoconta->erro_msg;
      }
    }

    if ( trim($oRetornoAnalitica->cc04_sequencial) != "") {
      //atualiza tabela custoplanoanalitica
      $clcustoplanoanalitica->cc04_sequencial = $oRetornoAnalitica->cc04_sequencial;
      $clcustoplanoanalitica->cc04_coddepto   = $coddepto;
      $clcustoplanoanalitica->alterar($oRetornoAnalitica->cc04_sequencial);

      if($clcustoplanoanalitica->erro_status == 0) {
        $lSqlErro = true;
        $sMsgErro = $clcustoplanoanalitica->erro_msg;
      }
    }

    try {
      $oDaoCustoPlanoCriterio = new cl_custoplanoanaliticacriteriorateio();
      $sSqlBuscaCriterio      = $oDaoCustoPlanoCriterio->sql_query(null, 'cc08_sequencial', null, " cc01_sequencial = {$clcustoplano->cc01_sequencial}");
      $rsBuscaCriterio        = $oDaoCustoPlanoCriterio->sql_record($sSqlBuscaCriterio);
      if (!$rsBuscaCriterio || $oDaoCustoPlanoCriterio->numrows == "") {
        throw new Exception("Ocorreu um erro ao buscar o critério de rateio vinculado ao Plano de Custo.");
      }

      $iCodigoCriterio                     = db_utils::fieldsMemory($rsBuscaCriterio, 0)->cc08_sequencial;
      $sDescricao                          = "Conta {$cc01_estrutural} - {$cc01_descricao}";
      $oDaoCriterioRateio                  = new cl_custocriteriorateio();
      $oDaoCriterioRateio->cc08_descricao  = $sDescricao;
      $oDaoCriterioRateio->cc08_obs        = $sDescricao;
      $oDaoCriterioRateio->cc08_sequencial = $iCodigoCriterio;
      $oDaoCriterioRateio->alterar($iCodigoCriterio);
      if ($oDaoCriterioRateio->erro_status == "0") {
        throw new Exception("Não foi possível alterar a descrição do critério de rateio.");
      }

    } catch (Exception $e) {

      $lSqlErro = true;
      $sMsgErro = $e->getMessage();
    }



  }
  db_fim_transacao($lSqlErro);

} else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $db_botao = true;

  $result = $clcustoplano->sql_record($clcustoplano->sql_query_analitica($chavepesquisa));
  db_fieldsmemory($result,0);

  //usado no formulário para carregar o departamento
  $coddepto = $cc04_coddepto;
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript"
          src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
      marginheight="0" onLoad="a=1; js_esconder_campos();">
<center>
  <table style="padding-top: 10px">
    <tr>
      <td width="500">
        <?
        include(modification("forms/db_frmcustoplano.php"));
        ?>
      </td>
    </tr>
  </table>
</center>
</body>
</html>
<?
if (isset($alterar)) {

  if ($clcustoplano->erro_status=="0") {
    $clcustoplano->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clcustoplano->erro_campo != "") {
      echo "<script> document.form1.".$clcustoplano->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcustoplano->erro_campo.".focus();</script>";
    }

  } else {
    $clcustoplano->erro(true,true);
  }
}

if (isset($chavepesquisa) && isset($cc04_sequencial) && trim($cc04_sequencial) != "") {

  echo " <script>
      	  function js_db_libera(liberar){

			 parent.document.formaba.custoanaliticabens.disabled=liberar;
         	 (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_custoanaliticabens.location.href='cus1_custoplanoanaliticabens001.php?cc05_custoplanoanalitica=".$cc04_sequencial."';";

  if (isset($liberaaba)) {
    echo "  parent.mo_camada('custoanaliticabens');";
  }
  echo " }\n
    
	 js_db_libera();
	 
  </script>\n
 ";
}

if($db_opcao == 22){
  echo "<script>document.form1.pesquisar.click();</script>";
}

?>
<script>
  js_aba();
  js_tabulacaoforms("form1","cc01_instit",true,1,"cc01_instit",true);
</script>