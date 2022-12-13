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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_slip_classe.php");
require_once("classes/db_slipnum_classe.php");
require_once("classes/db_sliprecurso_classe.php");
require_once("classes/db_empageslip_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once('model/agendaPagamento.model.php');

/**
 * Chamada de função criada para bloquear o acesso ao usuário no menu
 */
db_validarMenuPCASP(db_getsession("DB_itemmenu_acessado", false));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clslip        = new cl_slip;
$clslipnum     = new cl_slipnum;
$clsliprecurso = new cl_sliprecurso;
$clempageslip  = new cl_empageslip;

db_postmemory($HTTP_POST_VARS);
// parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$db_erro         = "";
$alteracao       = false;
$db_opcao        = 22;
$desabilitabotao = false;
$iSituacao       = 1;
$sqlerro         = false;

/**
*	//
*	// retirar desta pesquisa os slip autenticados ( mesmo que estornados )
*	// ou os slips que estao na agenda de pagamento
*	//
*
*/
if (isset($chavepesquisa)) {

	$sql  = " select e81_codage ";
	$sql .= "   from empageslip  ";
	$sql .= "        inner join empagemov      on e89_codmov = e81_codmov";
	$sql .= "        inner join empage         on e81_codage = e80_codage";
	$sql .= "        inner join empagemovforma on e81_codmov = e97_codmov";
	$sql .= " where empageslip.e89_codigo = {$chavepesquisa}";
	$result = $clslip->sql_record($sql);
	if ($clslip->numrows > 0){
	  db_fieldsmemory($result,0);
	}

	if ($clslip->numrows > 0) {

		$db_erro          = "Slip não pode ser alterado, pois está na agenda {$e81_codage}.\n";
		$db_erro         .= "Para altera-lo deve ser retirado da agenda.";
	  $sqlerro          = true;
	  $desabilitabotao  = true;
	}

}

if (isset($confirma) && !empty($confirma)) {

	$sqlerro = false;

	if ($sqlerro==false){
	db_inicio_transacao();

	//
	//  apagar este slip
	//  e inserir novamente é a maneira mais fácil de proceder atualização
	//
	  if (isset($numslip) && $numslip !=""){


	      $clsliprecurso->excluir(null," k29_slip = $numslip ");
	      if($clsliprecurso->erro_status == 0){
	           $db_erro = $clsliprecurso->erro_msg;
	  	   $sqlerro = true;
	      }
	      $clslipnum->excluir($numslip);
	      if($clslipnum->erro_status == 0){

	         $db_erro = $clslipnum->erro_msg;
	         $sqlerro = true;

	      }

		  $sSqlMov = $clempageslip->sql_query_file(null,$numslip);
		  $rsMovSlip = $clempageslip->sql_record($sSqlMov);
		  if ($clempageslip->numrows > 0) {

		    $oMovimentoSlip = db_utils::fieldsMemory($rsMovSlip, 0);
		    $clempageslip->excluir($oMovimentoSlip->e89_codmov);
		    if ($clempageslip->erro_status == 0){

		      $sqlerro = true;
	          $db_erro = $clempageslip->erro_msg;

		    }
		    $oDaoEmpPag = db_utils::getDao("empagepag");
		    $oDaoEmpPag->excluir($oMovimentoSlip->e89_codmov);
		    if ($oDaoEmpPag->erro_status == 0){

		      $sqlerro = true;
	          $db_erro = $oDaoEmpPag->erro_msg;

		    }
		    $oDaoNotasOrdem = db_utils::getDao("empagenotasordem");
		    $oDaoNotasOrdem->excluir(null,"e43_empagemov={$oMovimentoSlip->e89_codmov}");
		    if ($oDaoNotasOrdem->erro_status == 0){

		      $sqlerro = true;
	          $db_erro = $oDaoNotasOrdem->erro_msg;

		    }
		    $oDaoEmpageMov = db_utils::getDao("empagemov");
		    $oDaoEmpageMov->excluir($oMovimentoSlip->e89_codmov);
		    if ($oDaoEmpageMov->erro_status == 0){

		      $sqlerro = true;
	          $db_erro = $oDaoEmpageMov->erro_msg;

		    }
		  }
	  }

	  //  db_criatabela(db_query("select * from slip where k17_codigo=$numslip "));

	  if(trim($debito) == ""){
	   $db_erro = "Conta a Debitar(Receber) não Informada";
	   $sqlerro = true;
	  }

	  if(trim($credito) == ""){
	   $db_erro = "Conta a Creditar(Pagar) não Informada";
	   $sqlerro = true;
	  }


	  $sDataAnulacao = "";
	  $clslip = new  cl_slip;
	  //$clslip->k17_data     = date("Y-m-d",db_getsession("DB_datausu"));
	  $clslip->k17_debito   = $debito;
	  $clslip->k17_credito  = "$credito";
	  $clslip->k17_valor    = "$k17_valor";
	  $clslip->k17_hist     = "$k17_hist";
	  $clslip->k17_texto    = $texto;
	  $clslip->k17_instit   = db_getsession("DB_instit");
	  $clslip->k17_dtanu    = $sDataAnulacao;
	  $clslip->k17_codigo   = $numslip;
	  $clslip->alterar($numslip);
	  $numslip = $clslip->k17_codigo;
	  if($clslip->erro_status == 0){
	        $sqlerro = true;
	        $db_erro = $clslip->erro_msg;
	//        $db_erro = $clslip="slip não pode ser alterado, pois está agendado.";
	  }
	  $oInstit = db_stdClass::getDadosInstit();
	  if ($z01_numcgm == "") {
	    $z01_numcgm = $oInstit->numcgm;
	  }
	  $oAgendaPagamento = new agendaPagamento();
	  $oSlipAgenda = new stdClass();
	  $oSlipAgenda->iCodigoSlip = $clslip->k17_codigo;
	  $oSlipAgenda->nValor      = "$k17_valor";
	  /**
	   * Procuramos se a conta credito do slip é uma conta pagadora no caixa.
	   * caso for. setamos essa conta como conta pagadora na agenda.
	   */
	  $oParametroAgenda = (db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")),"e30_agendaautomatico"));
	  if ($credito != 0 && $oParametroAgenda[0]->e30_agendaautomatico == "t" ) {

	    $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
	    $sSqlConta      = $oDaoEmpAgeTipo->sql_query_file(null,"e83_codtipo", null,"e83_conta = {$credito}");
	    $rsConta        = $oDaoEmpAgeTipo->sql_record($sSqlConta);
	    if ($oDaoEmpAgeTipo->numrows > 0 ) {
	      $oSlipAgenda->iCodTipo = db_utils::fieldsMemory($rsConta,0)->e83_codtipo;
	    }

	  }
	  try {
	    $oAgendaPagamento->addMovimentoAgenda(2, $oSlipAgenda);
	  }
	  catch (Exception $eErro) {

	    $sqlerro = true;
	    $db_erro = $eErro->getMessage();
	  }


	  if($sqlerro == false){
	    if($z01_numcgm!=""){
	      $clslipnum->k17_numcgm = $z01_numcgm;
	      $clslipnum->incluir($numslip);
	      if($clslipnum->erro_status == 0){
	          $db_erro = $clslipnum->erro_msg;
	          $sqlerro = true;
	      }
	    }
	  }


	  if($sqlerro == false){
	  	// pega a variavel chaves e desmebra-a em pares chave=>valor
	  	$ch = split('#',$chaves);

	        foreach ($ch as $key => $value) {
	                // echo "Chave: $key; Valor: $value<br />\n";
		        $par = explode("_",$value);

			$clsliprecurso->k29_slip = $numslip;
	    	  	$clsliprecurso->k29_recurso = $par[0];
	    	 	$clsliprecurso->k29_valor     = $par[1];
	    	 	$clsliprecurso->incluir(null);
	    	 	if($clsliprecurso->erro_status == 0){
	      		 	$db_erro = $clsliprecurso->erro_msg;
	      		 	$sqlerro = true;
		                db_msgbox($db_erro);
	    	 	}


	        }
	  }

	  $k17_texto = $texto;

	  if($sqlerro == false){
	    $alteracao = true;
	    $db_erro = "Código Incluído : ".$numslip." Imprimir Relatório? ";
	  }

	  db_fim_transacao();

	}

} else if (isset($chavepesquisa) && $chavepesquisa!=""){

    $db_opcao=2;
    $sql = $clslip->sql_query_alteracao(null, "slip.*,z01_numcgm, z01_nome, c50_codhist,c50_descr", "", " slip.k17_codigo = $chavepesquisa and k17_instit = " . db_getsession("DB_instit"));

    $sSqlPesquisaDebCred  = "  select concarpeculiar.c58_sequencial, concarpeculiar.c58_descr, slipconcarpeculiar.k131_tipo ";
    $sSqlPesquisaDebCred .= "    from slipconcarpeculiar ";
    $sSqlPesquisaDebCred .= "         inner join concarpeculiar on concarpeculiar.c58_sequencial = slipconcarpeculiar.k131_concarpeculiar";
    $sSqlPesquisaDebCred .= "   where k131_slip = {$chavepesquisa};";

    $rsExecutaBuscaDebCred = db_query($sSqlPesquisaDebCred);
    $iRowBuscaDebCred      = pg_num_rows($rsExecutaBuscaDebCred);

    if ($iRowBuscaDebCred != 0) {

      for ($iRow = 0; $iRow < $iRowBuscaDebCred; $iRow++) {

        $oDadoSlipConCar = db_utils::fieldsMemory($rsExecutaBuscaDebCred, $iRow);

        if ($oDadoSlipConCar->k131_tipo == 1) {
          $k17_caracteristicapeculiardebito     = $oDadoSlipConCar->c58_sequencial;
          $k17_caracteristicapeculiardebitodesc = $oDadoSlipConCar->c58_descr;
        } else if ($oDadoSlipConCar->k131_tipo == 2) {

          $k17_caracteristicapeculiarcredito     = $oDadoSlipConCar->c58_sequencial;
          $k17_caracteristicapeculiarcreditodesc = $oDadoSlipConCar->c58_descr;
        }
      }
    }

    $oTransferenciaBancaria = new TransferenciaBancaria($chavepesquisa);
    $oFinalidadePagamento   = $oTransferenciaBancaria->getFinalidadePagamentoFundebCredito();
    if (!empty($oFinalidadePagamento)) {
      $e151_codigo_credito = $oFinalidadePagamento->getCodigo();
    }

    $result = $clslip->sql_record($sql);
    if($clslip->numrows > 0){
        db_fieldsmemory($result,0);
        $debito = $k17_debito;
        $credito = $k17_credito;
        $numslip = $k17_codigo;
        $codhist = $k17_hist;
        $altera = true;
     }else{
        db_msgbox("Slip não encontrado ! Verifique o número e instituição ou tente novamente !  ");
     }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
 db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, estilos.css, grid.style.css");
?>
<script>
function js_gravar(){
  if(document.form1.debito.value == " "){
      alert('Conta Débito não informada.');
	  document.form1.debito.focus();
      return false;
  }
  if(document.form1.credito.value == " "){
      alert('Conta Crédito não informada.');
	  document.form1.credito.focus();
      return false;
  }
  if(document.form1.debito.value == document.form1.credito.value ){
      alert('As contas nao podem ser iguais.');
      return false;
  }
  if(document.form1.k17_valor.value == ''){
     alert('Valor zerado.');
     return false;
  }
  if(document.form1.k17_hist.value == ''){
     alert('Campo Histórico não informado !');
     return false;
  }
  // recurso_valor | recurso_valor | recurso_valor| (...)

  var conteudo ='';
  var sep = '';
  var tab = document.getElementById("tabRecursos");
  for(var x=1; x< tab.rows.length;x++){ // começa na linha 1 porque a 0 zero é o header da tabela
        id_tr = tab.rows[x].id;
        id = id_tr.split('_');
        id  = id[1];
        receita = eval('document.form1.rec_'+id+'.value');
        valor    = eval('document.form1.rec_val_'+id+'.value');

        conteudo  += sep+ receita+'_'+valor;
        sep = '#';

  }
  document.form1.chaves.value = conteudo;

  document.form1.k17_valor.disabled=false;

  return true;
}
</script>
<style>
td {
  white-space: nowrap
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
    <?
  	  include("forms/db_frmslip.php");
    ?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?valida==1&funcao_js=parent.js_preenchepesquisa|k17_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_slip.hide();
  <?
    echo "window.document.location.href='".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    //echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}

</script>
<?

if ($db_opcao==22){
   echo "
       <script>
         js_pesquisa();
       </script>
      ";

}


/**
*	//
* 	// caso tenha setado a chavepesquisa
*	// adiciona na tabela de recurso os recursos encontrados
*	//
*/
if (isset($chavepesquisa) && $chavepesquisa!=""){
  echo "
       <script>
	  js_adiciona_linha(false,document.form1.debito.value);
	  document.form1.k17_valor.focus();
       </script>
       ";

}
?>