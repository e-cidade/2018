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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libcaixa.php"));
require_once(modification("libs/db_utils.php"));

require_once(modification("classes/db_cfautent_classe.php"));
require_once(modification("model/caixa/AutenticacaoArrecadacao.model.php"));
require_once(modification("libs/db_app.utils.php"));

require_once modification("model/contabilidade/planoconta/ContaPlano.model.php");
require_once modification("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once modification("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once modification("model/contabilidade/planoconta/SistemaConta.model.php");
require_once modification("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once modification("model/CgmFactory.model.php");
db_app::import("exceptions.*");
db_app::import("configuracao.Instituicao");
db_app::import("configuracao.DBEstrutura");
db_app::import("CgmFactory");
db_app::import("contaTesouraria");

//db_app::import("caixa.*");
//requires em substituição ao import caixa
require_once(modification("model/caixa/ArrecadacaoReceitaOrcamentaria.model.php"));
require_once(modification("model/caixa/AutenticacaoArrecadacao.model.php"));
require_once(modification("model/caixa/AutenticacaoBaixaBanco.model.php"));
require_once(modification("model/caixa/AutenticacaoPlanilha.model.php"));
require_once(modification("model/caixa/PlanilhaArrecadacao.model.php"));
require_once(modification("model/caixa/ReceitaPlanilha.model.php"));

db_app::import("orcamento.*");
db_app::import("contabilidade.*");
db_app::import("orcamento.*");
db_app::import("configuracao.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("exceptions.*");


$clautenticar = new cl_autenticar();
$clcfautent = new cl_cfautent();

parse_str($HTTP_SERVER_VARS ["QUERY_STRING"]);
$oGet  = db_utils::postMemory($HTTP_GET_VARS);
$oPost = db_utils::postMemory($HTTP_POST_VARS);

$iInstit           = db_getsession("DB_instit");
$AnoUsu            = db_getsession("DB_anousu");
$iIp               = db_getsession("DB_ip");
$dDataAutenticacao = date("Y-m-d", db_getsession("DB_datausu"));
$porta             = 5001;
$lErro             = false;

$sRetornoAutenticacao = '';

//{==============================
//rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada
$sSqlValidaAutenticadora = $clcfautent->sql_query_file(null, "k11_id, k11_tipautent as tipautent, k11_zeratrocoarrec, k11_aut1, k11_aut2", '', "k11_ipterm = '{$iIp}' and k11_instit = {$iInstit}");
$rsValidaAutenticadora   = $clcfautent->sql_record($sSqlValidaAutenticadora);
if ($clcfautent->numrows > 0) {
  $oDadosAutenticadora = db_utils::fieldsMemory($rsValidaAutenticadora, 0);
} else {
  $sMsg  = "Atenção!\\n\\n";
  $sMsg .= "Este endereço Ip não está cadastrado como Autenticadora!\\n\\n";
  $sMsg .= "Cadastre a autenticadora acessando o menu: \\n";
  $sMsg .= "Cadastros>>Autenticadoras>>Inclusão de Autenticadoras\\n\\n";
  $sMsg .= "Endereço Ip: {$iIp}\\n";
  db_msgbox($sMsg);
  exit;
}

if (!isset($oPost->reautentica) && isset($oPost->codautent)) {

  $iNumpre           = substr($oPost->codautent, 0, 8);
  $iNumpar           = substr($oPost->codautent, 8, 3);
  $iReduz            = $oGet->reduz;

  /* @note: Busca pelo codigo de barras inteirro para tratar os casos dos novos nosso numero gerados */
  $isReciboNovo = false;

  if (strlen(trim($oPost->codautent)) == 44) {

    $sSqlReciboCodBar = "select * from recibocodbar where k00_codbar = '{$oPost->codautent}'";
    $rsReciboCodBar = db_query($sSqlReciboCodBar);

    if (empty($rsReciboCodBar)) {
      echo "<script>
              parent.alert('Erro ao consultar o código de barras. Verifique. (Banco : $banco)');
              location.href = 'cai4_arrecada002.php?invalido=true';
            </script>";
    }

    if (pg_num_rows($rsReciboCodBar) > 0) {

      $oReciboCodBar  = db_utils::fieldsMemory($rsReciboCodBar,0);
      $sSql        = "select * from arrebanco where k00_numpre = '{$oReciboCodBar->k00_numpre}'";
      $rsArrebanco = db_query($sSql);
      $oArrebanco  = db_utils::fieldsMemory($rsArrebanco,0);

      $iNumpre = $oArrebanco->k00_numpre;
      $iNumpar = $oArrebanco->k00_numpar;
      $isReciboNovo = true;

    }

  }

  if (USE_PCASP) {

      try {

        db_inicio_transacao();
        require_once modification("libs/db_liborcamento.php");
        $oAutenticacaoArrecadacao = new AutenticacaoArrecadacao( $iNumpre, $iNumpar, $iReduz);

        db_putsession("DB_desativar_account", true);

        if ($oPost->tipo == 'Arrecadacao') {

          $oAutenticacaoArrecadacao->autenticar();

          /**
           * Removemos o recibo da fila da Cobrança Registrada
           */
          $sSqlDeleteFila = "delete from  reciboregistra where k146_numpre = {$iNumpre}";
          $rsDeleteFila   = db_query($sSqlDeleteFila);

          if (!$rsDeleteFila) {
            throw new DBException("Erro ao remover o recibo da fila da cobrança registrada.");
          }
        }else{
          $oAutenticacaoArrecadacao->estornar();
        }

        db_destroysession("DB_desativar_account");
        db_fim_transacao(false);

      } catch (BusinessException $oBusinessException) {

        $sMensagemErro  = "Possíveis causas: ";

        if (pg_last_error() != "") {

          $sMsgErroBanco  = substr($oBusinessException->getMessage(),
                                   0,
                                   strpos($oBusinessException->getMessage(),"CONTEXT") );
        }

        $sMsgErroBanco = $oBusinessException->getMessage();
        db_msgbox($sMensagemErro.  str_replace("\n" ,"\\n", $sMsgErroBanco));

        db_fim_transacao(true);
        $lErro = true;

      } catch (ParameterException $oParameterException) {

        db_msgbox($oParameterException->getMessage());
        db_fim_transacao(true);
        $lErro = true;

      } catch (DBException $oDBException) {

        db_msgbox($oDBException->getMessage());
        db_fim_transacao(true);
        $lErro = true;

      } catch (Exception $oException) {

        $sMensagemErro = "\\n\\nPossíveis causas:\\n";
        $sMensagemErro .= "- Receita possui uma conta sem vínculo com um grupo.\\n";
        $sMensagemErro .= "- Receita não está cadastrada como lançada na previsão do orçamento.\\n";
        db_msgbox($oException->getMessage().$sMensagemErro);
        db_fim_transacao(true);
        $lErro = true;

      }

    } else {

      db_inicio_transacao();

      if ($oPost->tipo == 'Arrecadacao') {
        $sSqlAutenticacao = "select *
                               from fc_autentica({$iNumpre},
                                                 {$iNumpar},
                                                 '{$dDataAutenticacao}',
                                                 '{$dDataAutenticacao}',
                                                 {$AnoUsu},
                                                 {$iReduz},
                                                 '{$iIp}',
                                                 {$iInstit}) as autenticacao";
      } else {
        $sSqlAutenticacao = "select *
                               from fc_autenesto({$iNumpre},
                                                 {$iNumpar},
                                                 '{$dDataAutenticacao}',
                                                 '{$dDataAutenticacao}',
                                                 {$AnoUsu},
                                                 {$iReduz},
                                                 '{$iIp}',
                                                 {$iInstit},
                                                 0) as autenticacao";
      }
      $rsAutenticacao = db_query($sSqlAutenticacao);
      if (!$rsAutenticacao) {
        db_msgbox("Erro executando a autenticação\\n\\nComando:{$sSqlAutenticacao}");
        $lErro = true;
        db_fim_transacao(true);
      }

      $oDadosAutenticacao   = db_utils::fieldsMemory($rsAutenticacao,0);
      if ($oDadosAutenticacao->erro == 't') {

        $lErro = true;
        $sRetornoAutenticacao = "";
        db_fim_transacao(true);

        if ($system != 'linux') {
          echo "<script>";
          echo "parent.alert('Erro ao gerar autenticacao. \\n{$oDadosAutenticacao->mensagem}');";
          echo "location.href = 'cai4_arrecada002.php'";
          echo "</script>";
        } else {
          echo "<script>";
          echo "parent.alert('Erro ao gerar autenticacao. \\n{$oDadosAutenticacao->mensagem}');";
          echo "</script>";
        }

      } else {
      	$sRetornoAutenticacao = $oDadosAutenticacao->autenticacao;
      }

      if ($lErro == false) {
        db_fim_transacao(false);
      }

    }

}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
   var lista = parent.recibos.document.getElementById("tab");
   if(lista.rows.length == 1){
     document.form1.action = 'cai4_arrecada002.php';
     document.form1.submit();
  }
</script>
<script language="JavaScript" type="text/javascript"
  src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC bgcolor="#AAB7D5">
  <table width="100%">
    <tr>
      <?
        if ($system == 'linux') {
          echo "<td align=\"center\">";
          echo "  <font id=\"numeros\" size=\"2\">";
          echo "   Processando em linux Autentica&ccedil;&atilde;o do<br>";
          echo "   Código&nbsp;&nbsp;".@$oPost->codautent."&nbsp;&nbsp;<br>";
          echo "   Valor&nbsp;&nbsp;R$&nbsp;".@$oPost->valor;
          echo "  </font>";
          echo "</td>";
        } else {
          echo "<td align=\"center\">";
          echo "  <font id=\"numeros\" size=\"4\">";
          echo "   Processando Autentica&ccedil;&atilde;o do Código&nbsp;&nbsp;".@$oPost->codautent."&nbsp;&nbsp;Valor&nbsp;&nbsp;R$&nbsp;".@$oPost->valor;
          echo "  </font>";
          echo "</td>";
        }
      ?>
    </tr>
  </table>
  <form name="form1" method="POST">
    <input name="codautent" type="hidden">
    <input name="tipo" type="hidden">
    <input name="valor" type="hidden">
    <input name="reduz" type="hidden" value="<?=$oGet->reduz?>">
  </form>
</body>
</html>
<?
if (!isset($oPost->reautentica) && $clautenticar->erro == false) {

  if (isset($oPost->codautent)) {

    $aut1 = split(",", $oDadosAutenticadora->k11_aut1);
    $aut2 = split(",", $oDadosAutenticadora->k11_aut2);

    $str_aut1 = "";
    if (trim($oDadosAutenticadora->k11_aut1) != "") {
      for($i = 0; $i < sizeof($aut1); $i ++) {
        $str_aut1 .= chr($aut1 [$i]);
      }
    }

    $str_aut2 = "";
    if (trim($oDadosAutenticadora->k11_aut2) != "") {
      for($i = 0; $i < sizeof($aut2); $i ++) {
        $str_aut2 .= chr($aut2 [$i]);
      }
    }

    if (USE_PCASP) {
      $sRetornoAutenticacao = $oAutenticacaoArrecadacao->getCodigoAutenticacao();
    }

    $str_aut1 .= $sRetornoAutenticacao;
    $str_aut2 .= $sRetornoAutenticacao;

  } else {
    echo "<script>";
    echo " id = new String(lista.rows[1].id);";
    echo " document.form1.codautent.value = id.substr(3);";
    echo " document.form1.tipo.value = String(lista.rows[1].cells[0].innerHTML);";
    echo " document.form1.valor.value = String(lista.rows[1].cells[7].innerHTML);";
    echo " document.form1.submit();";
    echo "</script>";
  }
}

if (isset($oPost->reautentica)) {
  $sRetornoAutenticacao = $oPost->reautentica;
}

if ( ! USE_PCASP && empty($sRetornoAutenticacao) && $lErro == false) {

  db_msgbox('$sRetornoAutenticacao vazio');
  $lErro = true;
}

if ($oDadosAutenticadora->tipautent == 1 && $lErro == false) {
  // abre o socket da impressora

  try {
    require_once modification("model/impressaoAutenticacao.php");
    $oImpressao = new impressaoAutenticacao($sRetornoAutenticacao);
    $oModelo = $oImpressao->getModelo();
    $oModelo->imprimir();
  } catch (Exception $EImpressao) {
    echo "<script>";
    echo "  parent.alert('{$EImpressao->getMessage()}'); ";
    echo "</script>";
    $lErro = true;
  }

}

if ($lErro == false) {

  echo "<script>";
  echo "var executaresto = false;";
  echo "if(parent.confirm('Autenticar {$oPost->codautent}  R$ {$oPost->valor} Novamente?')==false){";
  echo "  lista.deleteRow(1);";
  echo "  executaresto = true;";
  echo "}else{";
  echo "  var obj = document.createElement('input');";
  echo "  obj.setAttribute('name','reautentica');";
  echo "  obj.setAttribute('type','text');";
  echo "  obj.setAttribute('value','" . $sRetornoAutenticacao . "');";
  echo "  document.form1.appendChild(obj);";
  echo "  document.form1.codautent.value = '{$oPost->codautent}';";
  echo "  document.form1.submit();";
  echo "}";
  echo "if(executaresto == true){";
  echo "  if(lista.rows.length == 1){";
  echo "     parent.db_autent_iframe.hide();";
  echo "     parent.numeros.document.form1.codrec.focus();";
  if ($oDadosAutenticadora->k11_zeratrocoarrec == 1) {

    echo "     parent.document.form1.apagar.value = '';";
    echo "     parent.document.form1.recebido.value = '';";
    echo "     parent.document.form1.troco.value = '';";
  }
  echo "  }else{";
  echo "     id = new String(lista.rows[1].id);";
  echo "     document.form1.codautent.value = id.substr(3);";
  echo "     document.form1.tipo.value  = String(lista.rows[1].cells[0].innerHTML);";
  echo "     document.form1.valor.value = String(lista.rows[1].cells[7].innerHTML);";
  echo "     document.form1.submit();";
  echo "  }";
  echo "}";
  echo "</script>";
}

echo "<script>";
echo "parent.js_removeObj('msgBox');";
echo "</script>";


exit();
?>
