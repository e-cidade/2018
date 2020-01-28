<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_slip_classe.php");
require_once("classes/db_slipnum_classe.php");
require_once("classes/db_sliprecurso_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once('model/agendaPagamento.model.php');

/**
 * Chamada de fun��o criada para bloquear o acesso ao usu�rio no menu
 */
db_validarMenuPCASP(db_getsession("DB_itemmenu_acessado", false));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clslip = new cl_slip;
$clslipnum = new cl_slipnum;
$clsliprecurso = new cl_sliprecurso;
$clempparamentro = new cl_empparametro;

 db_postmemory($HTTP_POST_VARS);
// parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$db_erro = "";
$inclusao = false;
$db_opcao = 1;
$desabilitabotao=false;

if (isset($confirma) && !empty($confirma)) {

  $sqlerro = false;
  db_inicio_transacao();
  if (trim($debito) == "") {
    $db_erro = "Conta a Debitar(Receber) n�o Informada";
    $sqlerro = true;
  }

  if (trim($credito) == "" and $sqlerro == false) {
    $db_erro = "Conta a Creditar(Pagar) n�o Informada";
    $sqlerro = true;
  }

  if($sqlerro == false) {
    $clslip->k17_data     = date("Y-m-d",db_getsession("DB_datausu"));
    $clslip->k17_debito   = $debito;
    $clslip->k17_credito  = "$credito";
    $clslip->k17_valor    = "$k17_valor";
    $clslip->k17_hist     = $k17_hist;
    $clslip->k17_texto    = $texto;
    $clslip->k17_instit   = db_getsession("DB_instit");
    $clslip->k17_dtanu    = "";
    $clslip->k17_situacao = 1;
    $clslip->incluir(null);
    $numsliprel           = $clslip->k17_codigo;
    if ($clslip->erro_status == 0) {
      $sqlerro = true;
      $db_erro = $clslip->erro_msg;
    }
  }

  if($sqlerro == false) {
    /**
     * Agendamos o slip caso o parametro emparametro.e30_agendaautomatico = true.
     */
    $oInstit = db_stdClass::getDadosInstit();
    if ($z01_numcgm == "") {
      $z01_numcgm = $oInstit->numcgm;
    }
    $oAgendaPagamento = new agendaPagamento();
    $oSlipAgenda = new stdClass();
    $oSlipAgenda->iCodigoSlip = $clslip->k17_codigo;
    $oSlipAgenda->nValor      = "$k17_valor";
    /**
     * Procuramos se a conta credito do slip � uma conta pagadora no caixa.
     * caso for. setamos essa conta como conta pagadora na agenda.
     */
    $oParametroAgenda = (db_stdClass::getParametro("empparametro",array(db_getsession('DB_anousu')),"e30_agendaautomatico"));
    if ($oParametroAgenda[0]->e30_agendaautomatico == "t" ) {
      if ($credito != 0 ) {
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
      catch(Exception $eErro) {
        $sqlerro = true;
        $db_erro = $eErro->getMessage();
      }
    }
  }

  if ($sqlerro == false) {
    if ($z01_numcgm!="") {
      $clslipnum->k17_numcgm = $z01_numcgm;
      $clslipnum->incluir($numsliprel);
      if ($clslipnum->erro_status == 0) {
        $db_erro = $clslipnum->erro_msg;
        $sqlerro = true;
      }
    }
  }


  if ($sqlerro == false) {
    // pega a variavel chaves e desmebra-a em pares chave=>valor
    $ch = split('#',$chaves);

    foreach($ch as $key => $value) {
      // echo "Chave: $key; Valor: $value<br />\n";
      $par = explode("_",$value);

      $clsliprecurso->k29_slip = $numsliprel;
      $clsliprecurso->k29_recurso = $par[0];
      $clsliprecurso->k29_valor     = $par[1];
      $clsliprecurso->incluir(null);
      if ($clsliprecurso->erro_status == 0) {
        $db_erro = $clsliprecurso->erro_msg;
        $sqlerro = true;
      }
    }
  }

  $k17_texto = $texto;

  if ($sqlerro == false) {
    $inclusao = true;
    $db_erro = "C�digo Inclu�do : ".$numsliprel." Imprimir Relat�rio? ";
  }


  db_fim_transacao($sqlerro);

} else {

  $k17_texto = "Documento Transfer�ncia/Pagamento Extra-Or�ament�rio";

}
if (isset($chavepesquisa) && $chavepesquisa!="") {

  $sql = $clslip->sql_query_alteracao(null, "slip.*,z01_numcgm, z01_nome, c50_codhist,c50_descr", "", " slip.k17_codigo = $chavepesquisa and k17_instit = " . db_getsession("DB_instit"));
  $result = $clslip->sql_record($sql);
  if ($clslip->numrows > 0) {
    db_fieldsmemory($result,0);
    $debito = $k17_debito;
    $credito = $k17_credito;
    $numslip = $k17_codigo;
    $codhist = $k17_hist;
    $altera = true;
    $numslip = '';
  } else {
    db_msgbox("Slip n�o encontrado ! Verifique o n�mero e institui��o ou tente novamente !  ");
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
      alert('Conta D�bito n�o informada.');
	  document.form1.debito.focus();
      return false;
  }
  if(document.form1.credito.value == " "){
      alert('Conta Cr�dito n�o informada.');
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
     alert('Campo Hist�rico n�o informado !');
     return false;
  }
  // recurso_valor | recurso_valor | recurso_valor| (...)

  var conteudo ='';
  var sep = '';
  var tab = document.getElementById("tabRecursos");
  for(var x=1; x< tab.rows.length;x++){ // come�a na linha 1 porque a 0 zero � o header da tabela
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

#k17_caracteristicapeculiardebitodesc, #k17_caracteristicapeculiarcreditodesc {
  width: 400px;
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
<?
//if($inclusao==true){
//  echo "
//        <script>
//          if(confirm('".$db_erro."') == true){
//            window.open('cai1_slip003.php?".base64_encode(db_getsession()."&numslip=".$numsliprel)."','','location=0');
//          }
//		  location.href='cai1_slip001.php';
//        </script>
//       ";
//  db_redireciona("cai1_slip002.php?retorno=$numsliprel");
//}else{
//  if($db_erro!=""){
//    echo "<script>alert('".$db_erro."')</script>";
//  }
//}
?>
<script>

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_preenchepesquisa|k17_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_slip.hide();
  <?
    echo "window.document.location.href='".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	?>
}
</script>
<?
if (isset($chavepesquisa) && $chavepesquisa!=""){
  echo "
       <script>
	  js_adiciona_linha(false,document.form1.debito.value);
	  document.form1.k17_valor.focus();
       </script>
       ";

}
?>