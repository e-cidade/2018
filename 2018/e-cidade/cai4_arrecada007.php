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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcaixa.php");
require_once("libs/db_utils.php");

require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("model/caixa/AutenticacaoArrecadacao.model.php");
require_once("libs/db_app.utils.php");

require_once 'model/contabilidade/planoconta/ContaPlano.model.php';
require_once 'model/contabilidade/planoconta/ContaOrcamento.model.php';
require_once 'model/contabilidade/planoconta/ClassificacaoConta.model.php';
require_once 'model/contabilidade/planoconta/SistemaConta.model.php';
require_once 'model/contabilidade/planoconta/SubSistemaConta.model.php';
require_once 'model/CgmFactory.model.php';

require_once("model/caixa/ArrecadacaoReceitaOrcamentaria.model.php");
require_once("model/caixa/AutenticacaoArrecadacao.model.php");
require_once("model/caixa/AutenticacaoBaixaBanco.model.php");
require_once("model/caixa/AutenticacaoPlanilha.model.php");
require_once("model/caixa/PlanilhaArrecadacao.model.php");
require_once("model/caixa/ReceitaPlanilha.model.php");

db_app::import("exceptions.*");
db_app::import("configuracao.Instituicao");
db_app::import("configuracao.DBEstrutura");
db_app::import("CgmFactory");
db_app::import("contaTesouraria");
db_app::import("orcamento.*");
db_app::import("contabilidade.*");
db_app::import("orcamento.*");
db_app::import("configuracao.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("exceptions.*");
$clautenticar = new cl_autenticar;

require_once("classes/db_cfautent_classe.php");
$clcfautent = new cl_cfautent;

$ip = db_getsession("DB_ip");
$porta = 5001;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

  //rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada
  $result99 = $clcfautent->sql_record($clcfautent->sql_query_file(null,"k11_tipautent as tipautent",'',"k11_ipterm = '".db_getsession("DB_ip")."' and k11_instit = ".db_getsession("DB_instit")));
	if($clcfautent->numrows > 0){
	  db_fieldsmemory($result99,0);
	}else{
	  db_msgbox("Cadastre o ip ".db_getsession('DB_ip')." como um terminal de caixa.");
	  die();
	}

   //---------------------------------------

  if(!isset($HTTP_POST_VARS["reautentica"])){

    db_inicio_transacao();
    if (isset($HTTP_POST_VARS["codcla"])){

      try {

        $oAutenticacaoBaixaBanco = new AutenticacaoBaixaBanco($HTTP_POST_VARS["codcla"]);
        $fc_autentica            = $oAutenticacaoBaixaBanco->autenticar();

        db_fim_transacao(false);

      } catch (BusinessException $oBusinessException) {

        db_fim_transacao(true);
        ?>
    	  <script>
        parent.js_removeObj('msgBox');
    	  parent.alert('Erro ao gerar autenticacao. Verifique :<?=str_replace("\n", '\\n', $oBusinessException->getMessage())?>');
    	  document.location.href = 'cai4_arrecada005.php';
    	  </script>
    	  <?
  	    exit;
      } catch (Exception $oBusinessException) {

        db_fim_transacao(true);
        ?>
    	  <script>
        parent.js_removeObj('msgBox');
    	  parent.alert('Erro ao gerar autenticacao. Verifique :<?=str_replace("\n", '\\n', $oBusinessException->getMessage())?>');
    	  document.location.href = 'cai4_arrecada005.php';
    	  </script>
    	  <?
  	    exit;

      }

    }
  }else{
    $HTTP_POST_VARS["codcla"] = $HTTP_POST_VARS["codautent"];
  }
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor=#CCCCCC bgcolor="#AAB7D5">
<table width="100%">
  <tr>
    <td align="center"><font id="numeros" size="2">Processando Autentica&ccedil;&atilde;o da Classificação&nbsp;&nbsp;<?=@$HTTP_POST_VARS['codcla']?></font></td>
  </tr>
</table>
<form name="form1" method="POST">
  <input name="codautent" type="hidden" >
  <input name="tipo" type="hidden" >
  <input name="reduz" type="hidden" value="<?=@$HTTP_POST_VARS["codcla"]?>">
</form>
</body>
</html>
<?

   if(isset($HTTP_POST_VARS["reautentica"])){
     $fc_autentica = $HTTP_POST_VARS["reautentica"];
   }

   if($tipautent == 1) {

     try {
	  	  require_once 'model/impressaoAutenticacao.php';
  		  $oImpressao = new impressaoAutenticacao($fc_autentica);
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
     echo "if (parent.document.getElementById('msgBox')) {parent.js_removeObj('msgBox');}";
     echo "if(parent.confirm('Autenticar Classificação " . $HTTP_POST_VARS["codcla"] . " Novamente?')==false){";
     echo "  document.location.href = 'cai4_arrecada005.php';";
     echo "}else{";
     echo "  var obj = document.createElement('input');";
     echo "  obj.setAttribute('name','reautentica');";
     echo "  obj.setAttribute('type','hidden');";
     echo "  obj.setAttribute('value','" . $fc_autentica . "');";
     echo "  document.form1.appendChild(obj);";
     echo "  document.form1.codautent.value = '" . $HTTP_POST_VARS["codcla"] . "';";
     echo "  document.form1.submit();";
     echo "}";
     echo "</script>";
   }

   echo "<script>";
   echo "parent.js_removeObj('msgBox');";
   echo "</script>";

exit;

?>