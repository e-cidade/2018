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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_empage_classe.php");
require_once("classes/db_empagedadosret_classe.php");
require_once("classes/db_empagedadosretmov_classe.php");
require_once("classes/db_errobanco_classe.php");
require_once("classes/db_empagetipo_classe.php");
$clempage = new cl_empage;
$clempagedadosret = new cl_empagedadosret;
$clempagedadosretmov = new cl_empagedadosretmov;
$clerrobanco= new cl_errobanco;
$clrotulo = new rotulocampo;
$clempage->rotulo->label();
$clempagedadosret->rotulo->label();
$clempagedadosretmov->rotulo->label();
$clempagetipo = new cl_empagetipo();
$clerrobanco->rotulo->label();
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e82_codord");
$clrotulo->label("e60_codemp");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e75_codret = ".$retorno . " and e75_ativo is true and e90_cancelado is false ";
if(isset($contapaga)){
  $dbwhere .= " and e83_codtipo=$contapaga ";
}
if(isset($ordem)){
  if($ordem == "t"){
    // processados
    $dbwhere .= " and e92_processa = 't' and e92_sequencia <> 35 ";
  }else if($ordem == "f"){
    // n processados
    $dbwhere .= " and e92_processa = 'f' ";

  }else if($ordem == "a"){
    // agendados
    $dbwhere .= " and e92_sequencia=35 ";
  }
}

$sSqlRelatorio   = $clempage->sql_query_rel_arqretorno(null,
                                              "distinct e53_valor,
                                               e53_vlranu,
                                               e53_vlrpag,
                                               e87_codgera,
                                               e87_descgera,
                                               e87_data,
                                               e87_hora,
                                               e83_descr,
                                               e83_conta,
                                               pc63_conta,
                                               e89_codigo,
                                               pc63_dataconf,
                                               pc63_conta_dig,
                                               pc63_agencia,
                                               pc63_agencia_dig,
                                               e75_arquivoret,
                                               e76_lote,
                                               e76_movlote,
                                               e76_dataefet,
                                               e76_valorefet,
                                               e76_codret,
                                               e81_codmov,
                                               e60_codemp,
                                               e82_codord,
                                               e86_codmov,
                                               case when a.z01_numcgm is not null then a.z01_numcgm
                                                    when cgmslip.z01_numcgm is not null then cgmslip.z01_numcgm
                                                else cgm.z01_numcgm
                                                end as z01_numcgm,
                                               case when a.z01_nome <> '' then a.z01_nome
                                                    when cgmslip.z01_nome <> '' then cgmslip.z01_nome
                                                    else cgm.z01_nome
                                                end as z01_nome,
                                                e81_valor,
                                                fc_valorretencaomov(e81_codmov, true,e87_dataproc) as vlrretencao,
                                                e83_codtipo,
                                                e83_descr",
                                                "e83_codtipo,
                                                z01_nome,
                                                e76_lote,
                                                e76_movlote,
                                                e82_codord",
                                               $dbwhere);

$result_retorno  = $clempage->sql_record($sSqlRelatorio);
$numrows_retorno = $clempage->numrows;
if ($numrows_retorno == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Retorno $retorno não encontrado.");
}
db_fieldsmemory($result_retorno,0);

$head3 = "RELATÓRIO RETORNO DE ARQUIVO" ;
$head5 = "Arquivo:";
$head6 = db_formatar($e87_codgera,'s','0',5,'e',0);
$head6.= ' - '.$e87_descgera;

$head9 = "** - Contas conferidas";

if(isset($contapaga)){
  $head7 = "Conta pagadora:";
  $head8 = db_formatar($e83_codtipo,'s','0',5,'e',0);
  $head8.= ' - '.$e83_descr;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$p = 1;
$alt = 4;
$pagadora = "";

$arr_valconta = Array();
$arr_valretencao = Array();
$arr_valmovis = Array();

$arr_valtconta = 0;
$arr_valtretencao = 0;
$arr_valtmovis = 0;
for($i=0;$i<$numrows_retorno;$i++) {

  db_fieldsmemory($result_retorno,$i);
//  $e81_valor -= $vlrretencao;

  if(!isset($arr_valmovis[$e83_codtipo])){
    $arr_valmovis[$e83_codtipo] = 0;
  }
  if(!isset($arr_valconta[$e83_codtipo])){
    $arr_valconta[$e83_codtipo] = 0;
  }
  if(!isset($arr_valretencao[$e83_codtipo])){
    $arr_valretencao[$e83_codtipo] = 0;
  }

  $arr_valmovis[$e83_codtipo] += $e81_valor;
  $arr_valconta[$e83_codtipo] += $e76_valorefet;
  $arr_valretencao[$e83_codtipo] += $vlrretencao;

  $arr_valtmovis += $e81_valor;
  $arr_valtconta += $e76_valorefet;
  $arr_valtretencao += $vlrretencao;

}
for($i=0;$i<$numrows_retorno;$i++){

  db_fieldsmemory($result_retorno,$i);
  $sSqlDadosOcorrencia   = "select e92_coderro, ";
  $sSqlDadosOcorrencia  .= "       e92_descrerro ";
  $sSqlDadosOcorrencia  .= "  from empagedadosretmovocorrencia  " ;
  $sSqlDadosOcorrencia  .= "       left  join errobanco on e92_sequencia = empagedadosretmovocorrencia.e02_errobanco ";
  $sSqlDadosOcorrencia  .= " where empagedadosretmovocorrencia.e02_empagedadosret    = {$e76_codret}";
  $sSqlDadosOcorrencia  .= "   and empagedadosretmovocorrencia.e02_empagedadosretmov = {$e81_codmov}";
  $rsDadoOcorrencia      = db_query($sSqlDadosOcorrencia);
  $aOcorrencias          = db_utils::getCollectionByRecord($rsDadoOcorrencia);
  $sTextoOcorrencia      = '';
  $sVirgula              = '';
  foreach ($aOcorrencias as $oOcorrencia) {

    /**
     * verifica a conta pagadora da linha
     */
    $sSqlDadosConta = $clempagetipo->sql_query_conplanoconta($e83_codtipo);
    $rsDadosConta   = $clempagetipo->sql_record($sSqlDadosConta);
    if ($clempagetipo->numrows > 0) {


      $oDadosConta = db_utils::fieldsMemory($rsDadosConta, 0);
      if ($oDadosConta->c63_banco == '104' && $oOcorrencia->e92_coderro == "BD") {
        $oOcorrencia->e92_descrerro = "CREDITO EFETUADO COM SUCESSO";
      }
      $sTextoOcorrencia .= "{$sVirgula}{$oOcorrencia->e92_descrerro}";
      $sVirgula = ", ";
    }
  }
  if ($e89_codigo != "") {

    $e60_codemp = "slip";
    $e82_codord = $e89_codigo;

  }
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);

    $pdf->cell(20,$alt,'Cod. Emp.',1,0,"C",1);
    $pdf->cell(15,$alt,"OP/Slip",1,0,"C",1);
    $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
    $pdf->cell(105,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(15, $alt,"Data mov",1,0,"C",1);
    $pdf->cell(15, $alt,"Data ret",1,0,"C",1);
    $pdf->cell(30, $alt,"Valor bruto",1,0,"C",1);
    $pdf->cell(30, $alt,"Retenção",1,0,"C",1);
    $pdf->cell(30, $alt,"Valor líquido",1,1,"C",1);
    $pdf->cell(280, $alt,"Retorno",1,1,"C",1);
    $troca = 0;
  }
  if($pagadora!=$e83_codtipo){
    if($pagadora != ""){
      $pdf->setfont('arial','b',6);
      $pdf->cell(280,$alt,'TOTAL DE REGISTROS NESTA CONTA :  '.$total,"T",0,"L",0);
      $pdf->ln(3.5);
    }
    $pagadora = $e83_codtipo;
    $total = 0;
    $pdf->setfont('arial','b',8);
    $pdf->cell(190,$alt,$e83_codtipo .' - '. $e83_descr." - CONTA: $e83_conta",1,0,"L",1);
    $pdf->cell(30,$alt,db_formatar($arr_valmovis[$e83_codtipo],"f"),"TB",0,"R",1);
    $pdf->cell(30,$alt,db_formatar($arr_valretencao[$e83_codtipo],"f"),"TB",0,"R",1);
    $pdf->cell(30,$alt,db_formatar($arr_valconta[$e83_codtipo],"f"),"TBR",1,"R",1);
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(20,$alt,$e60_codemp,"T",0,"C",0);
  $pdf->cell(15,$alt,$e82_codord,"T",0,"C",0);
  $pdf->cell(20,$alt,$z01_numcgm,"T",0,"C",0);

  $asteriscos = "";
  if($pc63_dataconf!=""){
    $asteriscos = "** ";
  }
  $pdf->cell(105,$alt,$asteriscos.$z01_nome,"T",0,"L",0);
  $pdf->cell(15,$alt,db_formatar($e87_data,"d"),"T",0,"C",0);
  $pdf->cell(15,$alt,db_formatar($e76_dataefet,"d"),"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($e81_valor,"f"),"T",0,"R",0);
  $pdf->cell(30,$alt,db_formatar($vlrretencao,"f"),"T",0,"R",0);
  $pdf->cell(30,$alt,db_formatar($e76_valorefet,"f"),"T",1,"R",0);
  $pdf->cell(280,$alt,@$sTextoOcorrencia,"T",1,"L",0);
  $total++;
}

$pdf->setfont('arial','b',6);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS NESTA CONTA :  '.$total,"T",1,"L",0);
$pdf->setfont('arial','b',8);

$pdf->cell(205,$alt,"Total geral ",1,0,"R",1);
$pdf->cell(25,$alt,db_formatar($arr_valtmovis,"f"),"TB",0,"R",1);
$pdf->cell(25,$alt,db_formatar($arr_valtretencao,"f"),"TB",0,"R",1);
$pdf->cell(25,$alt,db_formatar($arr_valtconta,"f"),"TBR",1,"R",1);
$pdf->Output();
?>