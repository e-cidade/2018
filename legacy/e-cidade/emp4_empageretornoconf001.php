
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
require_once(modification("classes/db_empagepag_classe.php"));
require_once(modification("classes/db_empagetipo_classe.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification('model/MaterialCompras.model.php'));
require_once(modification("classes/ordemPagamento.model.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("model/slip.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/CgmBase.model.php"));
require_once(modification("model/CgmJuridico.model.php"));
require_once(modification("model/CgmFisico.model.php"));
require_once(modification("model/Dotacao.model.php"));


db_app::import("CgmFactory");
db_app::import("MaterialCompras");
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("caixa.*");
db_app::import("financeiro.*");
db_app::import("caixa.slip.Transferencia");
db_app::import("caixa.slip.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("empenho.*");
db_app::import("orcamento.*");
db_app::import("exceptions.*");

$clempagepag      = new cl_empagepag;
$clempagetipo     = new cl_empagetipo;
$clcfautent       = new cl_cfautent;
$clpagordem       = new cl_pagordem;
$clpagordemele    = new cl_pagordemele;
$clempempenho     = new cl_empempenho;
$clempelemento    = new cl_empelemento;
$clconlancam      = new cl_conlancam;
$clconlancamele   = new cl_conlancamele;
$clconlancampag   = new cl_conlancampag;
$clconlancamcgm   = new cl_conlancamcgm;
$clconparlancam   = new cl_conparlancam;
$clconlancamemp   = new cl_conlancamemp;
$clconlancamval   = new cl_conlancamval;
$clconlancamdot   = new cl_conlancamdot;
$clconlancamdoc   = new cl_conlancamdoc;
$clconlancamcompl = new cl_conlancamcompl;
$clconplanoreduz  = new cl_conplanoreduz;
$clsaltes         = new cl_saltes;
$clconlancamord   = new cl_conlancamord;
$clconlancamlr       = new cl_conlancamlr;
$cltranslan          = new cl_translan;
$clempagedadosret    = new cl_empagedadosret;
$clempagedadosretmov = new cl_empagedadosretmov;

parse_str($_SERVER['QUERY_STRING']);

//db_postmemory($_POST);
//db_postmemory($_GET);

if (isset($atualizar)) {

  db_inicio_transacao();
  $sqlerro=false;
  $sCodigoRetornoGerado = urldecode($retornoarq);
  $retornoarq = urldecode($retornoarq);
  $movs   = str_replace("XX", ",", $movs);
  $sWhere = "e76_codret in ($retornoarq) and e76_codmov in ($movs)";
  $sSqlDadosEmpagedadosretmov = $clempagedadosretmov->sql_query_file(null,null,"e76_codret, e76_codmov",null,$sWhere);
  $rsDadosEmpagedadosretmov   = $clempagedadosretmov->sql_record($sSqlDadosEmpagedadosretmov);
  $iTotalRegistros            = $clempagedadosretmov->numrows;
  if (!$rsDadosEmpagedadosretmov || $iTotalRegistros == 0){

    $erro_msg = "Nenhum registro encontrado para confirmação de pagamento !";
    $sqlerro = true;
  }

  $oDataSistema = new DBDate($data_baixa);

  $sCodmovProcessados = '';
  for ($iRegistros = 0; $iRegistros < $iTotalRegistros; $iRegistros++) {

    $oDados = db_utils::fieldsMemory($rsDadosEmpagedadosretmov, $iRegistros);
    $retornoarq = $oDados->e76_codret;
    $movimento  = $oDados->e76_codmov;
    $clempagedadosretmov->e76_processado = 'true';
    $clempagedadosretmov->e76_codret     = $retornoarq;
    $clempagedadosretmov->e76_codmov     = $movimento;
    $clempagedadosretmov->alterar($retornoarq,$movimento);
    if($clempagedadosretmov->erro_status==0){
      $erro_msg = $clempagedadosretmov->erro_msg;
      $sqlerro = true;
      break;
    }

    /**
     * consultamos a tabela empageslip e empord,
     * para descobrimos qual tipo de retorno é.
     * caso ha registro na empagslip devemos devemos rodar a PL fc_auttransf
     * caso ha registro na empord, usamos o metodo pagaOrdem da classe ordemPagamento
     */

    $sSqlTipoMov  = "Select e89_codigo, e82_codord ";
    $sSqlTipoMov .= "  from empagemov  ";
    $sSqlTipoMov .= "       left join empageslip on e89_codmov = e81_codmov ";
    $sSqlTipoMov .= "       left join empord     on e82_codmov = e81_codmov ";
    $sSqlTipoMov .= " where e81_codmov = {$movimento} ";
    $rsTipoMov    = db_query($sSqlTipoMov);
    $oTipoMov     = db_utils::fieldsMemory($rsTipoMov, 0);
    // Buscar ordem, conta, valor processado pelo banco
    if ($oTipoMov->e82_codord != "") {

      $result_ordemtipo = $clempagepag->sql_record($clempagepag->sql_query_pago(null,null,"e82_codord as e50_codord,e83_conta as k13_conta,e76_valorefet as vlrpag,e90_correto,e90_codgera",""," e81_codmov=$movimento and e76_codret=$retornoarq and e80_instit = " . db_getsession("DB_instit")));
      if($clempagepag->numrows==0){
        $sqlerro = true;
        $erro_msg = "Ordem de pagamento não encontrada.";
        break;
      }
      db_fieldsmemory($result_ordemtipo,0);
      $e91_valor = $vlrpag;

      // Buscar empenho
      $result_numemp = $clpagordem->sql_record($clpagordem->sql_query_emp($e50_codord,"e60_numemp,e60_codemp"));
      if($clpagordem->numrows==0){
        $sqlerro = true;
        $erro_msg = "Empenho não encontrado.";
        break;
      }
      db_fieldsmemory($result_numemp,0);
      // Buscar elemento, valor pago, valor liquidado e valor do empenho
      $result_vardados  = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,null,"e64_codele,e64_vlrpag,e64_vlrliq,e64_vlremp"));
      $numrows_vardados = $clempelemento->numrows;
      if($clempelemento->numrows==0){
        $sqlerro = true;
        $erro_msg = "Elemento não encontrado.";
        break;
      }
      $dados = '';         // Variável passada para o arquivo que dará baixa do pagamento no sistema.
      $sep   = '';         // Separador.
      $liberado  =  false;
      for($e=0; $e<$numrows_vardados; $e++){
        db_fieldsmemory($result_vardados,$e);

        $vlrdis = $e64_vlrliq - $e64_vlrpag;
        if($e91_valor <= $vlrdis ){
          $valor  = $e91_valor;
          $liberado =  true;
        }else{
          $valor  =  $vlrdis;
          $e91_valor  = $e91_valor - $vlrdis;
        }

        $dados .= $sep.$e64_codele."-".$valor;
        $sep    = '#';
      }
      $data_usuario="";

      if ($sqlerro == false) {
        $pagamento_auto = true;  // Variável setada para indicar que é pagamento automático
        // Arquivo que dará baixa do pagamento dos fornecedores no sistema

  	    $data_usuario = $oDataSistema->getDate();

        try {

          $oOrdemPagamento = new ordemPagamento($e50_codord);
          $oOrdemPagamento->setCheque(null);
          $oOrdemPagamento->setChequeAgenda(null);
          $oOrdemPagamento->setConta($k13_conta);
          $oOrdemPagamento->setValorPago($vlrpag);
          $oOrdemPagamento->setDataUsu($data_usuario);
  	      $oOrdemPagamento->setMovimentoAgenda($movimento);
          $oOrdemPagamento->pagarOrdem();
          $sqlerro       = false;
          $erro_msg      = "";
          $k11_tipautent = $oOrdemPagamento->oAutentica->k11_tipautent;
          $retorno       = true;
          $c70_codlan    = $oOrdemPagamento->iCodLanc;

       }
       catch (Exception $e) {

         $sqlerro    = true;
         $erro_msg   = str_replace("\n","\\n",$e->getMessage());
        }
      }
    } else if ($oTipoMov->e89_codigo != "") {

       $sIp     = db_getsession("DB_ip");
       $iInstit = db_getsession("DB_instit");

  	   $data    = $oDataSistema->getDate();

       $sSqlaut        = "select fc_auttransf({$oTipoMov->e89_codigo},'".$data."','".$sIp."',true,0,".$iInstit.") as verautenticacao";
       $rsAutenticacao = db_query($sSqlaut);
       if (pg_num_rows($rsAutenticacao) == 0) {

         $erro_msg = "Erro ao Autenticar SLIP {$oTipoMov->e89_codigo}!";
		     break;

	     } else {

	       $oRetornoAut = db_utils::fieldsMemory($rsAutenticacao,0);
	       if (substr($oRetornoAut->verautenticacao,0,1) != "1") {

		       $erro_msg = $oRetornoAut->verautenticacao;
		       $sqlerro = true;
	  	     break;
	       } else {

	         if (USE_PCASP) {

	           try {

	             $oDaoSlipTipoOperacao  = db_utils::getDao('sliptipooperacaovinculo');
	             $sSqlBuscaTipoOperacao = $oDaoSlipTipoOperacao->sql_query_file($oTipoMov->e89_codigo);
	             $rsBuscaTipoOperacao   = $oDaoSlipTipoOperacao->sql_record($sSqlBuscaTipoOperacao);
	             if ($oDaoSlipTipoOperacao->numrows == 0) {
	               throw new Exception("Não foi possível localizar o tipo de operação do slip {$oTipoMov->e89_codigo}.");
	             }
	             $iTipoOperacao  = db_utils::fieldsMemory($rsBuscaTipoOperacao, 0)->k153_slipoperacaotipo;
	             $oTransferencia = TransferenciaFactory::getInstance($iTipoOperacao, $oTipoMov->e89_codigo);

	             $oDaocfautent      = db_utils::getDao('cfautent');
	             $sSqlAutenticadora = $oDaocfautent->sql_query_file(null,
                                                	                 "k11_id, k11_tipautent",
                                                	                 '',
                                                	                 "k11_ipterm    = '{$sIp}'
                                                	                 and k11_instit = ".db_getsession("DB_instit"));
	             $rsAutenticador    = $oDaocfautent->sql_record($sSqlAutenticadora);

               if ($oDaocfautent->numrows == '0') {
                 throw new Exception("Cadastre o ip {$iIp} como um caixa.");
               }
               $iCodigoTerminal = db_utils::fieldsMemory($rsAutenticador, 0)->k11_id;
               $oTransferencia->setDataAutenticacao($data);
               $oTransferencia->setIDTerminal($iCodigoTerminal);
               $oTransferencia->setNumeroAutenticacao(substr($oRetornoAut->verautenticacao, 1, 7));
	             $oTransferencia->executarLancamentoContabil($data);

	           } catch (Exception $eErro) {

	             $sqlerro  = true;
	             $erro_msg = str_replace("\n", "\\n", $eErro->getMessage());
	           }

	         }
	       }
      }
    }

    if ($sCodmovProcessados == ''){
      $sCodmovProcessados = '('.$movimento;
    }else{
      $sCodmovProcessados .= ', '.$movimento;
    }

  }
  if ($sCodmovProcessados != ''){
     $sCodmovProcessados .= ')';
  }

  db_fim_transacao($sqlerro);
}

if (!isset($db_opcao)){
	 $db_opcao=1;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
    function verificaCampos(){

        var data_baixa = $F("data_baixa");
        var data_atual = new Date();

        if (js_comparadata(data_baixa, data_atual.getDateBR(), ">")) {
            alert("Data da baixa não pode ser maior que a data atual.");
            return false;
        }

        return true;
    }

function js_atualizar(){

    if (!verificaCampos()){
        return false;
    }

  if(canc.document.form1){

    obj = canc.document.form1;
    var coluna='';
    var sep='';
    for(i=0; i<obj.length; i++){
      nome = obj[i].name.substr(0,5);
      if(nome=="CHECK" && obj[i].checked==true){
        coluna += sep+obj[i].value;
        sep= "XX";
      }
    }
    if(coluna==''){
      alert("Selecione um movimento!");
      return false;
    }
    document.form1.movs.value = coluna;
    return true;
  }else{
    return false;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="100%" height="95%" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" enctype="multipart/form-data" method="post">
  <tr>
    <td height="10%" align="center" bgcolor="#CCCCCC">
        <?if(isset($retornoarq) && trim($retornoarq)!=""){?>
          <?
	  db_input("movs",10,'',true,'hidden',3);
	  db_input("retornoarq",10,'',true,'hidden',3);
	  $passaparametro = "";
	  if(isset($contapaga) && trim($contapaga)!=""){
	    db_input("contapaga",10,'',true,'text',3);
	    $passaparametro = "&contapaga=$contapaga";
	  }

	  $sArquivoRetornoDecode  = urldecode($retornoarq);
	  $sWhereNomeBancoRetorno = "e75_codret in ({$sArquivoRetornoDecode})";
	  $sSqlBuscaNomeBanco     = $clempagedadosret->sql_query_bco(null,"distinct db90_descr as nomebanco,e76_dataefet,e87_data ", null, $sWhereNomeBancoRetorno);
	  $result_nomebanco       = $clempagedadosret->sql_record($sSqlBuscaNomeBanco);



	  if($clempagedadosret->numrows>0){
	    db_fieldsmemory($result_nomebanco,0);
	  }

    ?>

	  <?php if(isset($nomebanco)) { ?>
      <strong>BANCO <?php echo $nomebanco; ?></strong>
	  <?php } ?>

    </td>
   </tr>
   <tr>
     <td height="70%">
          <iframe
	    name="canc" height="100%"
	    src="emp4_empageretornoconf001_iframe.php?&lCancelado=0&retornoarq=<?=(@$retornoarq)?><?=$passaparametro?>"
	    width="100%" marginwidth="0" marginheight="0" frameborder="0"></iframe>
    </td>
    </tr>
    <tr>
     <td height="10%" nowrap align=center>
     <table width="95%">
      <tr>
        <td align="left" width="1%" nowrap>
          <strong>Data da Remessa:</strong>
          <?php

            if (isset($e87_data) && trim($e87_data) != "") {
              $arr_data = split("-",$e87_data);
              $data_proc_dia = $arr_data[2];
              $data_proc_mes = $arr_data[1];
              $data_proc_ano = $arr_data[0];
            }

            db_inputdata('data_proc', @$data_proc_dia, @$data_proc_mes, @$data_proc_ano, true, 'text', 3);
          ?>
        </td>
        <td align="left">
          <strong>Data da Baixa:</strong>
          <?php db_inputdata('data_baixa', date('d', db_getsession("DB_datausu")), date('m', db_getsession("DB_datausu")), date('Y', db_getsession("DB_datausu")), true, 'text', 1); ?>
        </td>
        <td>
          <small><span style="color:darkblue;">** Verifique a mensagem de retorno</span></small>
        </td>
        <td align=right>
          <input name="atualizar" type="submit"  value="Baixar selecionados" onclick='return js_atualizar();'>
	  <?php } else { ?>
	    <BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
	    <b>Arquivo retorno não encontrado.</b>
	    <BR><BR><BR><BR><BR><BR><BR><BR><BR>
	  <?php }

	    $voltacorreto = "emp4_empageretorno001.php";

	    if (isset($retornomn)) {
	      $voltacorreto = "emp4_selarquivo001.php?conf=true";
 	    }
	  ?>
          <input name="voltar" type="button"  value="Voltar" onclick="js_voltar();">
        </td>
	    </tr>
	  </table>
    </td>
  </tr>
</form>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_voltar(){
  <?
  if(isset($voltacorreto)){
    echo "location.href='$voltacorreto';";
  }
  ?>
}

function js_showInconsistencias(iCodArquivo,sCodmovProcessados) {

  if (confirm('Imprimir Relatório de Baixa?')) {
    jan = window.open('cai2_inconsistenciaagenda002.php?lCancelado=0&retorno='+iCodArquivo+'&sCodmovProcessados='+sCodmovProcessados+'&ordem=t',
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  }
}
</script>
<?
if(isset($atualizar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  } else {

    echo "<script>\n";
    echo "js_showInconsistencias('$sCodigoRetornoGerado','$sCodmovProcessados')\n";
    echo "</script>\n";

  }
}
?>
