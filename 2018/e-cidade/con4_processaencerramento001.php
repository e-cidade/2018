<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/db_conlancam_classe.php");
require_once("classes/db_conlancamval_classe.php");
require_once("classes/db_conlancamdoc_classe.php");
require_once("classes/db_conlancamlr_classe.php");
require_once("classes/db_conencerramento_classe.php");

$cltranslan        = new cl_translan;
$clconlancam       = new cl_conlancam;
$clconlancamdoc    = new cl_conlancamdoc;
$clconlancamval    = new cl_conlancamval;
$clconlancamlr     = new cl_conlancamlr;
$clconencerramento = new cl_conencerramento;

$lPossuiTipo1ou5  = false;

$liberainscr      = "";
$liberanaoinscr   = "";
$liberancancinscr = " disabled ";

$liberaenc        = " disabled ";
$cancelaenc       = " disabled ";

$liberarec        = " disabled ";
$cancelarec       = " disabled ";

$liberatrans      = " disabled ";
$cancelatrans     = " disabled ";

//Definindo a data do lançamento.caso já se tenha iniciado um procedimento, a data devera ser igual para todos.
$rsEnc = $clconencerramento->sql_record($clconencerramento->sql_query(null,"*","c42_encerramentotipo ",
		" c42_anousu=".db_getsession("DB_anousu")." and c42_instit=".db_getsession("DB_instit")));

if ($clconencerramento->numrows > 0){

	$oEnc        = db_utils::fieldsMemory($rsEnc,0);
	$data        = explode("-",$oEnc->c42_data);
	$datalancdia = $data[2];
	$datalancmes = $data[1];
	$datalancano = $data[0];
	$db_opcao    = 3;
	
	for ($i = 0;$i < $clconencerramento->numrows;$i++){

		$oEnc     = db_utils::fieldsMemory($rsEnc, $i);
		$aTipos[] = $oEnc->c42_encerramentotipo;
		
		if ($oEnc->c42_encerramentotipo == 1 || $oEnc->c42_encerramentotipo == 5) {
			
			$lPossuiTipo1ou5 = true;
			$liberaenc       = "";
			$liberarec       = "";
			$liberatrans     = "";
			
		}

	}
	
	if (in_array(1, $aTipos)) {
	
		$liberainscr      = " disabled ";
		$liberanaoinscr   = " disabled ";
		$liberancancinscr = "";
		
	}

	if ($lPossuiTipo1ou5 and in_array(2, $aTipos)) {

		$liberaenc  = " disabled ";
		$cancelaenc = "";

	}

	if ($lPossuiTipo1ou5 and in_array(3, $aTipos)) {

		$liberarec  = " disabled ";
		$cancelarec = "";

	}

	if ($lPossuiTipo1ou5 and in_array(4, $aTipos)) {

		$liberatrans  = " disabled ";
		$cancelatrans = "";

	}
	
} else{

	$datalancdia  = 31;
	$datalancmes  = 12;
	$datalancano  = db_getsession("DB_anousu");
	$db_opcao     = 1;

}
db_postmemory($HTTP_POST_VARS);
//$db_opcao = 22;
$db_botao = false;


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>

.fieldsetinterno {
  border:0px;
  border-top:2px groove white;
}

select {
 width: 100%;
}  
fieldset.fieldsetinterno table tr TD {
  white-space: nowrap;
}
legend {
  font-weight: bold;
}
</style>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br /><br />
  <center>
  <form name="form1" action=''>
    <fieldset style="width: 650px;">
      <legend style="font-weight: bold; font-size: 13px;">&nbsp;Encerramento do Exercício&nbsp;</legend>
      <!-- Data de Lançamentos -->
      <table width="650" border="0" align="left">
        <tr>
          <td width="287"><strong>Data para Lançamentos:</strong></td>
          <td><?=db_inputdata("datalanc",$datalancdia,$datalancmes,$datalancano,true,'text',$db_opcao);?></td>
        </tr>      
      </table>
      <br />
      <!-- Inscrição de RP não processados -->
      <fieldset class="fieldsetinterno">
        <legend>&nbsp;Inscrição de RP não Processados&nbsp;</legend>
        <table width="650">
          <tr>
            <td width="280">Efetuar inscrição de RP não processados</td>
            <td><input type='button' onclick='js_abreInscricaoRp()' value='Processar' name='processa_rp'>
            <input type='button' onclick='js_abreNaoInscricaoRp()'<?=$liberanaoinscr?> value='Não Processar' name='naoprocessa_rp'>
            <input type='button' onclick='js_cancelaRP()' <?=$liberancancinscr?> value='Cancelar Processamento' name='cancela_rp'></td>
          </tr>
        </table>
      </fieldset> 
      <!-- Encerramento das Contas de Resultado -->
      <fieldset class="fieldsetinterno">
        <legend>&nbsp;Encerramento das Contas de Resultado&nbsp;</legend>
        <table width="650">
          <tr>
            <td width="280">Encerramento de saldo receita/despesa</td>
            <td>
              <input <?=$liberaenc?>  onclick="js_abreSaldorec()" id='processarec' type="button" value='Processar' name='processa_receita'>
              <input <?=$cancelaenc?> onclick="js_cancelaSaldorec()" id='cancelarec' type='button' value='Cancelar Processamento' name='estorna_receita'>
            </td>
          </tr>
        </table>
      </fieldset>
      <!-- Encerramento das contas do Sistema Orçamentário -->
      <fieldset class="fieldsetinterno">
        <legend>&nbsp;Encerramento das contas do Sistema Orçamentário&nbsp;</legend>
        <table width="650">
          <tr>
            <td width="280">Encerramento das contas do compensado</td>
            <td>
              <input <?=$liberarec;?> type="button"  id='processacompensado' value='Processar' onclick='js_abreSisOrc()' name='processa_compensado'>
              <input <?=$cancelarec;?> type="button" id='cancelacompensado'  onclick='js_cancelaCom()' value='Cancelar Processamento' name='cancela_rp'>
            </td>
          </tr>
        </table>
      </fieldset>
      <!-- Transferência de Saldo -->
      <fieldset class="fieldsetinterno">
        <legend>&nbsp;Transferência de Saldo&nbsp;</legend>
        <table width="650">
          <tr>
            <td width="280">Transferencia de saldo das contas de resultado</td>
            <td>
              <input <?=$liberatrans;?> type="button"  id='processatrans' value='Processar' onclick="js_abreTransf()" name='processa_despesa' >
              <input <?=$cancelatrans;?> type="button" id='cancelatrans' onclick='js_cancelaTrans()' value='Cancelar Processamento' name='cancela_rp'>
            </td>
          </tr>
        </table>
      </fieldset>
    </fieldset>
  </form>
  </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_abreInscricaoRp(){

  datalanc = $F('datalanc');
  if (datalanc != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_rp','con4_inscreverp.php?dtlanc='+datalanc,'Inscrever RPs',true);
  }else{
      alert('Digite a data de Lançamento.');
      $('datalanc').focus();
  }

}
function js_abreNaoInscricaoRp(){

  datalanc = $F('datalanc');
  if (datalanc != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_rp','con4_naoinscreverp.php?datalanc='+datalanc,'não Inscrever RPs',true);
  }else{
      alert('Digite a data de Lançamento.');
      $('datalanc').focus();
  }

}
function js_cancelaRP(){
  
   if (confirm("Todos os RP's Incluídos serao cancelados.\nConfirma Procedimento?")){
      js_OpenJanelaIframe('top.corpo','db_iframe_canccomp','con4_cancelarrp001.php?tipo=1','Cancelar RPs',true);
   }
}

function js_abreTransf(){

  datalanc = $F('datalanc');
  if (datalanc != ''){
     if (confirm('Essa rotina ira realizar a transferência de Saldo.\nConfirma Procedimento?')){
        js_OpenJanelaIframe('top.corpo','db_iframe_canccomp','con4_processacompensado.php?tipo=4&datalanc='+datalanc,'Transferências das contas de Resultado',true);
     }
  }else{
      alert('Digite a data de Lançamento.');
      $('datalanc').focus();
  }
}
function js_cancelaTrans(){
  
   if (confirm("As transferências das contas do resultado serão canceladas.\nConfirma Procedimento?")){
      js_OpenJanelaIframe('top.corpo','db_iframe_canccomp','con4_cancelarrp001.php?tipo=4','Cancelar Transferências',true);
   }
}
function js_abreSaldorec(){

  datalanc = $F('datalanc');
  if (datalanc != ''){
     if (confirm('Essa rotina ira realizar o encerramento do Saldo receita/Despesa.\nConfirma Procedimento?')){
        js_OpenJanelaIframe('top.corpo','db_iframe_canccomp','con4_processasaldorec.php?datalanc='+datalanc,'Encerramento do Saldo receita/Despesa',true);
     }
  }else{
      alert('Digite a data de Lançamento.');
      $('datalanc').focus();
  }
}
function js_cancelaSaldorec(){
  
   if (confirm("Os lançamentos para o encerramento do saldo Receita/Despesa.\nConfirma Procedimento?")){
      js_OpenJanelaIframe('top.corpo','db_iframe_canccomp','con4_cancelarrp001.php?tipo=2','Cancelar Lançamentos Compensado',true);
   }
}
function js_abreSisOrc(){

  datalanc = $F('datalanc');
  if (datalanc != ''){
     if (confirm('Essa Rotina ira realizar o encerramento do sistema Orçamentario.\nConfirma Procedimento?')){
        js_OpenJanelaIframe('top.corpo','db_iframe_canccomp','con4_processacompensado.php?tipo=3&datalanc='+datalanc,'Encerramento das contas do compensado',true);
     }
  }else{
      alert('Digite a data de Lançamento.');
      $('datalanc').focus();
  }
}
function js_cancelaCom(){
  
   if (confirm("Os lançamentos para o encerramento das contas do compensado serão cancelados.\nConfirma Procedimento?")){
      js_OpenJanelaIframe('top.corpo','db_iframe_canccomp','con4_cancelarrp001.php?tipo=3','Cancelar Lançamentos Compensado',true);
   }
}

</script>