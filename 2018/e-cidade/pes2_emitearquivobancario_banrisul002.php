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
require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_layouttxt.php");
require_once("classes/db_rhgeracaofolha_classe.php");
require_once("classes/db_rharqbanco_classe.php");
require_once("classes/db_rhgeracaofolhaarquivo_classe.php");
require_once("classes/db_rhgeracaofolhaarquivoreg_classe.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$oGet = db_utils::postMemory($HTTP_GET_VARS);

$clRhGeracaoFolha           = new cl_rhgeracaofolha();
$clRhArqBanco               = new cl_rharqbanco;
$clrhgeracaofolhaarquivo    = new cl_rhgeracaofolhaarquivo;
$clrhgeracaofolhaarquivoreg = new cl_rhgeracaofolhaarquivoreg;

//$cllayouts_bb  = new cl_layouts_bb;

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("rh104_vlrliquido");
$clrotulo->label("rh44_codban");
$clrotulo->label("rh44_agencia");
$clrotulo->label("rh44_conta");
$clrotulo->label("r70_descr");

try {

  $tiparq                               = 0;
  $lSomenteServidoresOpcaoContaCorrente = true;

  db_sel_instit(db_getsession("DB_instit"));

  $rsArquivoBancario =  $clRhArqBanco->sql_record($clRhArqBanco->sql_query($oGet->rh34_codarq));
  if ($clRhArqBanco->numrows == 0) {
  	throw new Exception("Não foram encontrados registros para o arquivo escolhido");
  }
  $oDadosArquivoBancario = db_utils::fieldsMemory($rsArquivoBancario,0);

  if(isset($oGet->datageracao) && $oGet->datageracao!=""){
    $datag = split('-',$oGet->datageracao);
    $datag_dia=$datag[2];
    $datag_mes=$datag[1];
    $datag_ano=$datag[0];
  }

  if(isset($oGet->datadeposito) && $oGet->datadeposito!=""){
    $datad = split('-',$oGet->datadeposito);
    $datad_dia = $datad[2];
    $datad_mes = $datad[1];
    $datad_ano = $datad[0];
  }

  $sNomeArquivoTXT = "tmp/pgtofunc_{$oDadosArquivoBancario->db90_codban}_{$datag_mes}{$datag_ano}_".date("H").date("i").".txt";
  $sNomeArquivoPDF = "tmp/pgtofunc_{$oDadosArquivoBancario->db90_codban}_{$datag_mes}{$datag_ano}_".date("H").date("i").".pdf";
  $oLayoutTXT       = new db_layouttxt(218,$sNomeArquivoTXT);

  $dDataGeracao  = $datag_ano."-".$datag_mes."-".$datag_dia;
  $sDataGeracao  = $datag_dia.$datag_mes.$datag_ano;
  $dDataDeposito = $datad_ano."-".$datad_mes."-".$datad_dia;
  $sDataDeposito = $datad_dia.$datad_mes.$datad_ano;
  $sHoraGeracao  = date("His");

  $sWhere        = '';

  if(!empty($oDadosArquivoBancario->rh34_where)){

    $sWhere   = ' and ' ;
    $sWhere  .= str_ireplace("r38_banco","rh44_codban", $oDadosArquivoBancario->rh34_where);
  }

  if ( trim($oGet->vinculo) != "") {

    if($oGet->vinculo == 'A'){
      $sWhere .= " and rh30_vinculo = '$oGet->vinculo' ";
    } else if ($oGet->vinculo == 'I') {
      $sWhere .= " and rh30_vinculo <> 'A' ";
    }
  }

  /*
      Como no banrisul os numeros de contas nao tem letras, somente numeros
      foi convertido o campo rh44_conta para fazer a verificacao - Jeferson Santos
  */

  if ( trim($oGet->tipoconta) == "O") {
  	$sWhere                              .= " and (trim(rh44_conta)::bigint = 0 or rh44_conta is null or rh44_conta = '' ) ";
    $lSomenteServidoresOpcaoContaCorrente = false;
  } else {
  	$sWhere .= " and trim(rh44_conta)::bigint <> 0 ";
  }

  db_inicio_transacao();

  $sOrdem = "rh44_codban, rh44_agencia, rh44_conta";

  $sCampos = "rh01_regist,
              rh44_dvagencia,
              z01_numcgm,
              z01_nome,
              z01_cgccpf,
              rh104_vlrliquido,
              rh44_codban,
              to_number(rh44_agencia,'9999') as rh44_agencia,
              rh44_dvagencia,
              rh44_conta,
              rh44_dvconta,
              r70_descr,
              rh104_sequencial";
  $sSqlGeracaoArquivo = $clRhGeracaoFolha->sqlGeracaoFolhaArquivoBancario($oGet->rh102_sequencial, $sCampos, $sWhere, $sOrdem, $lSomenteServidoresOpcaoContaCorrente);
  $rsGeracaoArquivo   = $clRhGeracaoFolha->sql_record($sSqlGeracaoArquivo);

  if ($clRhGeracaoFolha->numrows == 0) {
    throw new Exception("Nenhum registro encontrado");
  }
  $oDadosGeracao = db_utils::getColectionByRecord($rsGeracaoArquivo);
  $sequencialbb120 = 1;

  $clrhgeracaofolhaarquivo->rh105_dtgeracao       = $dDataGeracao;
  $clrhgeracaofolhaarquivo->rh105_dtdeposito      = $dDataDeposito;
  $clrhgeracaofolhaarquivo->rh105_codarq          = $oGet->rh34_codarq;
  $clrhgeracaofolhaarquivo->rh105_codbcofebraban  = $oGet->codban;
  $clrhgeracaofolhaarquivo->rh105_tipoarq         = "0";
  $clrhgeracaofolhaarquivo->rh105_folha           = "0";
  $clrhgeracaofolhaarquivo->rh105_arquivotxt      = "0";
  $clrhgeracaofolhaarquivo->rh105_instit          = db_getsession('DB_instit');
  $clrhgeracaofolhaarquivo->incluir(null);
  if ($clrhgeracaofolhaarquivo->erro_status == "0") {
    throw new Exception($clrhgeracaofolhaarquivo->erro_msg);
  }

  $oHeaderArquivo = new stdClass();
  $oHeaderArquivo->tipo_empresa           = "2";
  $oHeaderArquivo->cpf_cnpj               = $cgc;
  $oHeaderArquivo->convenio               = $oDadosArquivoBancario->rh34_convenio;
  $oHeaderArquivo->agencia                = db_formatar(trim(str_replace('.','',str_replace('-','',$oDadosArquivoBancario->rh34_agencia))),"s","0",5,"e",0);
  $oHeaderArquivo->conta_corrente         = $oDadosArquivoBancario->rh34_conta.$oDadosArquivoBancario->rh34_dvconta;
  $oHeaderArquivo->nome_empresa           = $nomeinst;
  $oHeaderArquivo->nome_banco             = $oDadosArquivoBancario->db90_descr;
  $oHeaderArquivo->codigo_remessa_retorno = 1;
  $oHeaderArquivo->data_geracao           = $sDataGeracao;
  $oHeaderArquivo->hora_geracao           = $sHoraGeracao;
  $oHeaderArquivo->sequencial             = db_formatar($oDadosArquivoBancario->rh34_sequencial,"s","0",6,"e",0);
  $oHeaderArquivo->versao_leiaute         = "030";
  $oHeaderArquivo->densidade_arquivo      = "00000";
  $oHeaderArquivo->uso_empresa            = db_formatar($oDadosArquivoBancario->rh34_sequencial,"s","0",20,"e",0);
  $oLayoutTXT->setByLineOfDBUtils($oHeaderArquivo,1);

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $alt = 4;
  $lMostraCabecalho = true;

  $totalvalor          = 0;
  $totalquant          = 0;
  $iTotalLinhasArquivo = 1;
  $iSequencialHeader   = 0;
  $iSequencialLote     = 0;
  $iSequencialDetalhe  = 1;
  $nValorTotalHeader   = 0;
  $sFinalidade         = "";
  $sHashBanco          = "";

  $head5 = "SEQUENCIAL DO ARQUIVO  :  ".$oDadosArquivoBancario->rh34_sequencial;
  $head6 = "GERAÇÃO  :  ".db_formatar($datageracao,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';
  $head7 = "PAGAMENTO:  ".db_formatar($datadeposito,"d");
  $head8 = 'BANCO : '.$oDadosArquivoBancario->rh34_codban.' - '.$oDadosArquivoBancario->db90_descr;

  foreach ($oDadosGeracao as $oDados) {

  	if ($lMostraCabecalho == true || $pdf->gety() > $pdf->h - 30) {
  		$pdf->addpage("L");

  		$head5 = "SEQUENCIAL DO ARQUIVO  :  ".$oDadosArquivoBancario->rh34_sequencial;
  		$head6 = "GERAÇÃO  :  ".db_formatar($datageracao,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';
  		$head7 = "PAGAMENTO:  ".db_formatar($datadeposito,"d");
  		$head8 = 'BANCO : '.$oDadosArquivoBancario->rh34_codban.' - '.$oDadosArquivoBancario->db90_descr;


  		$pdf->setfont('arial','b',8);
  		$pdf->cell(20,$alt,$RLrh01_regist     ,1,0,"C",1);
  		$pdf->cell(20,$alt,$RLz01_numcgm      ,1,0,"C",1);
  		$pdf->cell(20,$alt,$RLz01_cgccpf      ,1,0,"C",1);
  		$pdf->cell(65,$alt,$RLz01_nome        ,1,0,"C",1);
  		$pdf->cell(65,$alt,$RLr70_descr       ,1,0,"C",1);
  		$pdf->cell(20,$alt,$RLrh104_vlrliquido,1,0,"C",1);
  		$pdf->cell(15,$alt,"Cod.Pgto."        ,1,0,"C",1);
  		$pdf->cell(15,$alt,"Banco"            ,1,0,"C",1);
  		$pdf->cell(15,$alt,$RLrh44_agencia    ,1,0,"C",1);
  		$pdf->cell(25,$alt,$RLrh44_conta      ,1,1,"C",1);

  		$lMostraCabecalho = false;

  	}

  	if ($oDadosArquivoBancario->rh34_codban == $oDados->rh44_codban) {
  		$sFinalidade = "DEP";

  		if ($oDados->rh104_vlrliquido < 5000) {
  			$iCodigoCompensacao = "010";
  		}
  	} else {
      if ($oDados->rh104_vlrliquido >= 5000) {
          $iCodigoCompensacao = "018";
      }

      if($oDados->rh104_vlrliquido < 5000) {
  			$sFinalidade = "DOC";
  		} else {
  			$sFinalidade = "TED";
  		}
  	}

  	$pdf->setfont('arial','',7);
  	$pdf->cell(20,$alt,$oDados->rh01_regist                      ,1,0,"C",0);
  	$pdf->cell(20,$alt,$oDados->z01_numcgm                       ,1,0,"C",0);
  	$pdf->cell(20,$alt,$oDados->z01_cgccpf                       ,1,0,"C",0);
  	$pdf->cell(65,$alt,$oDados->z01_nome                         ,1,0,"L",0);
  	$pdf->cell(65,$alt,$oDados->r70_descr                        ,1,0,"L",0);
  	$pdf->cell(20,$alt,db_formatar($oDados->rh104_vlrliquido,'f'),1,0,"R",0);
  	$pdf->cell(15,$alt,$sFinalidade                              ,1,0,"C",0);
  	$pdf->cell(15,$alt,$oDados->rh44_codban                      ,1,0,"C",0);
  	$pdf->cell(15,$alt,$oDados->rh44_agencia                     ,1,0,"R",0);
  	$pdf->cell(25,$alt,$oDados->rh44_conta                       ,1,1,"R",0);

  	$totalquant ++;
  	$totalvalor += $oDados->rh104_vlrliquido;

  	/*
  	 * Caso o banco do servidor não seja o Banrisul
  	 * Será finalizado o lote e criado novo
  	 */
  	if ($sHashBanco != $oDados->rh44_codban) {

      $sHashBanco = $oDados->rh44_codban;
  		$iSequencialHeader++;
      
      if ($iSequencialHeader > 1) {
        
        $iSequencialLote ++;
        $iTotalLinhasArquivo ++;
        $oTraillerLote = new stdClass();
        $oTraillerLote->lote_servico      = db_formatar($iSequencialLote,"s","0",4,"e",0);
        $oTraillerLote->total_registros   = db_formatar($iTotalLinhasArquivo,'s','0',6,'e',0);
        $oTraillerLote->somatorio_valores = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($nValorTotalHeader,"f")))),'s','0',18,'e',0);

        $oLayoutTXT->setByLineOfDBUtils($oTraillerLote,4);

        $nValorTotalHeader = 0;

      }

      $oHeaderLote->lote_servico                  = db_formatar($iSequencialHeader,'s','0',4,'e',0);
      $oHeaderLote->tipo_servico                  = "30";

      // Verifica o tipo de conta
      if ( trim($oGet->tipoconta) == "O") {
  		   $oHeaderLote->forma_lancamento              = "10";
      } else {
  		   $oHeaderLote->forma_lancamento              = "01";
      }

  		$oHeaderLote->versao_header                 = "020";
  		$oHeaderLote->tipo_inscricao                = "2";
  		$oHeaderLote->cpf_cnpj                      = $cgc;
  		$oHeaderLote->convenio_banco                = $oDadosArquivoBancario->rh34_convenio;
  		$oHeaderLote->agencia                       = db_formatar(trim(str_replace('.','',str_replace('-','',$oDadosArquivoBancario->rh34_agencia))),"s","0",5,"e",0);
  		$oHeaderLote->conta_corrente                = $oDadosArquivoBancario->rh34_conta.$oDadosArquivoBancario->rh34_dvconta;
  		$oHeaderLote->nome_empresa                  = $nomeinst;
  		$oHeaderLote->endereco                      = $ender;
  		$oHeaderLote->numero_endereco               = $numero;
  		$oHeaderLote->cidade_endereco               = $munic;
  		$oHeaderLote->cep_endereco                  = $cep;
  		$oHeaderLote->uf_endereco                   = $uf;
  		$oLayoutTXT->setByLineOfDBUtils($oHeaderLote,2);

  		$iSequencialDetalhe = 0;
  		$iTotalLinhasArquivo++;
  	}

  	$iTotalLinhasArquivo ++;
  	$iSequencialDetalhe ++;
  	$nValorTotalHeader += $oDados->rh104_vlrliquido;

  	$oRegistrosSegmentoA = new stdClass();
  	$oRegistrosSegmentoA->lote_servico               = db_formatar($iSequencialHeader,"s","0",4,"e",0);
  	$oRegistrosSegmentoA->sequencial_registro_lote   = db_formatar($iSequencialDetalhe,"s","0",5,"e",0);
  	$oRegistrosSegmentoA->codigo_camara_compensacao  = $iCodigoCompensacao;
  	$oRegistrosSegmentoA->codigo_banco_favorecido    = $oDados->rh44_codban;
  	$oRegistrosSegmentoA->agencia_favorecido         = db_formatar(trim(str_replace('.','',str_replace('-','',$oDados->rh44_agencia))),"s","0",5,"e",0);
  	$oRegistrosSegmentoA->conta_corrente             = db_formatar(trim(str_replace('.','',str_replace('-','',$oDados->rh44_conta.$oDados->rh44_dvconta))),"s","0",13,"e",0);
  	$oRegistrosSegmentoA->nome_favorecido            = $oDados->z01_nome;
  	$oRegistrosSegmentoA->documento_favorecido       = db_formatar($oDados->rh01_regist,'s','0',15,'d',0);
  	$oRegistrosSegmentoA->finalidade                 = "00005";
  	$oRegistrosSegmentoA->data_credito               = $sDataDeposito;
  	$oRegistrosSegmentoA->zeros3                     = str_repeat("0",15);
  	$oRegistrosSegmentoA->valor_credito              = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($oDados->rh104_vlrliquido,"f")))),'s','0',15,'e',0);
  	$oRegistrosSegmentoA->tipo_inscricao             = (strlen(trim($oDados->z01_cgccpf)) == 14)?"2":"1";
  	$oRegistrosSegmentoA->cpf_cnpj                   = db_formatar(str_replace('.','',str_replace('-','',$oDados->z01_cgccpf)),'s','0',14,'e',0);
  	$oLayoutTXT->setByLineOfDBUtils($oRegistrosSegmentoA, 3, "A");


    $clrhgeracaofolhaarquivoreg->rh106_rhgeracaofolhaarquivo = $clrhgeracaofolhaarquivo->rh105_sequencial;
    $clrhgeracaofolhaarquivoreg->rh106_rhgeracaofolhareg     = $oDados->rh104_sequencial;
    $clrhgeracaofolhaarquivoreg->incluir(null);
    if ($clrhgeracaofolhaarquivoreg->erro_status == "0") {
      throw new Exception($clrhgeracaofolhaarquivoreg->erro_msg);
    }

  }
  
  $iSequencialLote++;
  $oTraillerLote = new stdClass();
  $oTraillerLote->lote_servico      = db_formatar($iSequencialLote,"s","0",4,"e",0);
  $oTraillerLote->total_registros   = db_formatar($iTotalLinhasArquivo,'s','0',6,'e',0);
  $oTraillerLote->somatorio_valores = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($nValorTotalHeader,"f")))),'s','0',18,'e',0);
  $oLayoutTXT->setByLineOfDBUtils($oTraillerLote,4);

  $iTotalLinhasArquivo = $iTotalLinhasArquivo + 2;
  $oTraillerArquivo = new stdClass();
  $oTraillerArquivo->quantidade_lotes     = db_formatar($iSequencialHeader,'s','0',6,'e',0);
  $oTraillerArquivo->quantidade_registros = db_formatar($iTotalLinhasArquivo,'s','0',6,'e',0);
  $oLayoutTXT->setByLineOfDBUtils($oTraillerArquivo,5);


  $pdf->setfont('arial','b',8);

  $pdf->cell(190,$alt,'Total de funcionários',1,0,"C",1);
  $pdf->cell(20,$alt,$totalquant,1,0,"R",1);
  $pdf->cell(70,$alt,'',1,1,"C",1);

  $pdf->cell(190,$alt,'Total Geral',1,0,"C",1);
  $pdf->cell(20,$alt,db_formatar($totalvalor,'f'),1,0,"R",1);
  $pdf->cell(70,$alt,'',1,1,"C",1);

  $pdf->Output($sNomeArquivoPDF,false,true);

  db_fim_transacao(false);

  echo " <script> parent.js_detectaarquivo('$sNomeArquivoTXT','$sNomeArquivoPDF'); </script>";

} catch (Exception $oErro) {

  db_fim_transacao(true);
  echo " <script> parent.js_erro('".$oErro->getMessage()."'); </script> ";

}
?>
