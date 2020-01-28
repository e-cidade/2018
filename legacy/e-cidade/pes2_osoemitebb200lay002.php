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

$clRhGeracaoFolha = new cl_rhgeracaofolha();
$clRhArqBanco     = new cl_rharqbanco;
$clrhgeracaofolhaarquivo    = new cl_rhgeracaofolhaarquivo;
$clrhgeracaofolhaarquivoreg = new cl_rhgeracaofolhaarquivoreg;

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("rh104_vlrliquido");
$clrotulo->label("rh44_codban");
$clrotulo->label("rh44_agencia");
$clrotulo->label("rh44_conta");

try {

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

  $sNomeArquivo = $datag_mes.$datag_ano."_".date("H").date("i");

  $dDataGeracao  = $datag_ano."-".$datag_mes."-".$datag_dia;
  $dDataDeposito = $datad_ano."-".$datad_mes."-".$datad_dia;

  $sHoraGeracao    = date("H").date("i").date("s");
  $sNomeArquivoTXT = "/tmp/BB120".$sNomeArquivo.".txt";
  $sNomeArquivoPDF = "/tmp/BB120".$sNomeArquivo.".pdf";

  $sWhere          = '';

  if(!empty($oDadosArquivoBancario->rh34_where)){

    $sWhere   = ' and ' ;
    $sWhere  .= str_ireplace("r38_banco","rh44_codban", $oDadosArquivoBancario->rh34_where);
  }

  $sWhere .= " and trim(rh44_conta) <> '0' ";

  if ( trim($oGet->vinculo) != "") {

    if($oGet->vinculo == 'A'){

      $sWhere .= " and rh30_vinculo = '$oGet->vinculo' ";
      $head8 = 'BANCO : '.$oDadosArquivoBancario->rh34_codban.' - '.$oDadosArquivoBancario->db90_descr.' - ATIVOS';

    } else {

      $sWhere .= " and rh30_vinculo <> 'A' ";
      $head8 = 'BANCO : '.$oDadosArquivoBancario->rh34_codban.' - '.$oDadosArquivoBancario->db90_descr.' - INATIVOS';

      $rh34_conta = '8162';
      $rh34_dvconta = '0';
    }
  }

  db_inicio_transacao();

  $sOrdem = "rh44_codban,z01_nome";

  $sCampos = "rh01_regist,
              rh44_dvagencia,
              z01_numcgm,
              z01_nome,
              z01_cgccpf,
              rh104_vlrliquido,
              case
                when to_number( case
                                  when trim(rh44_conta) = '' or rh44_conta is null
                                    then '0'
                                  else rh44_conta
                                end,'999999999999999') = 0
                  then '0'
                else rh44_codban
              end as rh44_codban,
              to_number(rh44_agencia,'9999') as rh44_agencia,
              rh44_dvagencia,
              rh44_conta,
              rh44_dvconta,
              rh104_sequencial";
  $sSqlGeracaoArquivo = $clRhGeracaoFolha->sqlGeracaoFolhaArquivoBancario($oGet->rh102_sequencial, $sCampos, $sWhere, $sOrdem);
  $rsGeracaoArquivo   = $clRhGeracaoFolha->sql_record($sSqlGeracaoArquivo);
  if ($clRhGeracaoFolha->numrows == 0) {
    throw new Exception("Nenhum registro encontrado");
  }
  $oDadosGeracao = db_utils::getColectionByRecord($rsGeracaoArquivo);
  $sequencialbb120 = 1;

  $oLayoutTXT       = new db_layouttxt(217,$sNomeArquivoTXT);

  $oHeader = new stdClass();
  $oHeader->nomeinst           = $nomeinst;
  $oHeader->rh34_codban        = $oDadosArquivoBancario->rh34_codban   ;
  $oHeader->rh34_convenio      = $oDadosArquivoBancario->rh34_convenio ;
  $oHeader->rh34_agencia       = $oDadosArquivoBancario->rh34_agencia  ;
  $oHeader->rh34_dvagencia     = $oDadosArquivoBancario->rh34_dvagencia;
  $oHeader->rh34_conta         = $oDadosArquivoBancario->rh34_conta    ;
  $oHeader->rh34_dvconta       = $oDadosArquivoBancario->rh34_dvconta  ;
  $oHeader->sequencialbb120    = 1;
  $oLayoutTXT->setByLineOfDBUtils($oHeader,1);
  if(!is_writable("/tmp/")){
    $sqlerro= true;
    $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
  }

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

  ///// INICIA IMPRESSÃO DO RELATÓRIO
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $alt = 4;

  $totalvalor = 0;
  $totalquant = 0;
  $entrar     = true;

  foreach($oDadosGeracao as $oDados) {

    $sequencialbb120++;
    $oRegistro = new stdClass();
    $oRegistro->confcontaagencia = 1;
    $oRegistro->rh01_regist      = $oDados->rh01_regist;
    $oRegistro->rh44_dvagencia   = $oDados->rh44_dvagencia;
    $oRegistro->rh44_agencia     = db_formatar(str_replace('.','',str_replace('-','',$oDados->rh44_agencia)),'s','0', 3,'e',0);
    $oRegistro->rh44_conta       = db_formatar(str_replace('.','',str_replace('-','',$oDados->rh44_conta)),'s','0',12,'e',0);
    $oRegistro->rh44_dvconta     = $oDados->rh44_dvconta  ;
    $oRegistro->z01_nome         = $oDados->z01_nome;
    $oRegistro->anomesgera       = $oGet->datadeposito;
    $oRegistro->rh104_vlrliquido = $oDados->rh104_vlrliquido;
    $oRegistro->rh44_codban      = $oDados->rh44_codban     ;
    $oRegistro->sequencialbb120  = $sequencialbb120;
    $oLayoutTXT->setByLineOfDBUtils($oRegistro,3);

    $clrhgeracaofolhaarquivoreg->rh106_rhgeracaofolhaarquivo = $clrhgeracaofolhaarquivo->rh105_sequencial;
    $clrhgeracaofolhaarquivoreg->rh106_rhgeracaofolhareg     = $oDados->rh104_sequencial;
    $clrhgeracaofolhaarquivoreg->incluir(null);
    if ($clrhgeracaofolhaarquivoreg->erro_status == "0") {
      throw new Exception($clrhgeracaofolhaarquivoreg->erro_msg);
    }

    if($entrar == true || $pdf->gety() > $pdf->h - 30){
      $pdf->addpage();

      $head3 = "ARQUIVO PAGAMENTO FOLHA";
      $head5 = "SEQUENCIAL DO ARQUIVO  :  ".$oDadosArquivoBancario->rh34_sequencial;
      $head6 = "GERAÇÃO  :  ".db_formatar($dDataGeracao,"d").' AS '.$sHoraGeracao.' HS';
      $head7 = "PAGAMENTO:  ".db_formatar($dDataDeposito,"d");

      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
      $pdf->cell(20,$alt,$RLrh104_vlrliquido,1,0,"C",1);
      $pdf->cell(15,$alt,$RLrh44_codban,1,0,"C",1);
      $pdf->cell(15,$alt,$RLrh44_agencia,1,0,"C",1);
      $pdf->cell(20,$alt,$RLrh44_conta,1,1,"C",1);

      $entrar = false;
      $mrecurso = true;
      $pdf->ln(1);

    }

    $pdf->setfont('arial','',7);
    $pdf->cell(15,$alt,$oDados->rh01_regist,1,0,"C",0);
    $pdf->cell(15,$alt,$oDados->z01_numcgm,1,0,"C",0);
    $pdf->cell(70,$alt,$oDados->z01_nome,1,0,"L",0);
    $pdf->cell(20,$alt,$oDados->z01_cgccpf,1,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($oDados->rh104_vlrliquido,'f'),1,0,"R",0);
    $pdf->cell(15,$alt,$oDados->rh44_codban,1,0,"C",0);
    $pdf->cell(15,$alt,$oDados->rh44_agencia." - ".$oDados->rh44_dvagencia,1,0,"R",0);
    $pdf->cell(20,$alt,$oDados->rh44_conta." - ".$oDados->rh44_dvconta,1,1,"R",0);

    $totalquant ++;
    $totalvalor += $oDados->rh104_vlrliquido;

  }

  $pdf->setfont('arial','',7);

  $pdf->ln(2);

  $pdf->setfont('arial','b',8);
  $pdf->cell(100,$alt,'Totalização geral',"LTB",0,"R",1);
  $pdf->cell(20,$alt,$totalquant,"TB",0,"R",1);
  $pdf->cell(20,$alt,db_formatar($totalvalor,"f"),"TB",0,"C",1);
  $pdf->cell(50,$alt,"","RTB",1,"C",1);

  $sequencialbb120++;
  $oTrailler = new stdClass();
  $oTrailler->sequencialbb120 = $sequencialbb120;
  $oLayoutTXT->setByLineOfDBUtils($oTrailler,5);
  $pdf->Output($sNomeArquivoPDF,false,true);

  db_fim_transacao(false);
  echo " <script> parent.js_detectaarquivo('$sNomeArquivoTXT','$sNomeArquivoPDF'); </script>";


} catch (Exception $oErro) {

  db_fim_transacao(true);
  echo " <script> parent.js_erro('".$oErro->getMessage()."'); </script> ";

}
?>