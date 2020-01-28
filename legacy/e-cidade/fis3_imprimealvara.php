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

require_once("libs/db_sql.php");
require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("classes/db_ativprinc_classe.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_sanitario_classe.php");
require_once("classes/db_saniatividade_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libsys.php");
require_once("dbagata/classes/core/AgataAPI.class");
require_once("model/documentoTemplate.model.php");
require_once("libs/db_conn.php");

db_postmemory($HTTP_SERVER_VARS);

$clativprinc     = new cl_ativprinc;
$clsanitario     = new cl_sanitario;
$clsaniatividade = new cl_saniatividade;
$clrotulo        = new rotulocampo;

$clsanitario->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("y80_texto");
$clrotulo->label("y83_ativ");
$clrotulo->label("q03_descr");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_bairro");
$clrotulo->label("y80_codsani");
$clrotulo->label("y80_numbloco");

$rsResult = db_query("select * from parfiscal where y32_instit = " . db_getsession("DB_instit"));

if (pg_numrows($rsResult)>0) {
  db_fieldsmemory($rsResult,0);
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Configure os parâmetros do módulo fiscal !!!');
  exit;
}

$datahj = date("Y-m-d",db_getsession("DB_datausu"));

$rsAtividadesSanitario = $clsanitario->sql_record( $clsanitario->sql_querysani( "",
                                                                                "*",
                                                                                "y80_codsani",
                                                                                " y80_codsani = {$y80_codsani} and y83_ativprinc = 't' "
                                                                                . " and y80_dtbaixa is null "
                                                                                . " and (y81_data is null or y81_data <= '{$datahj}')" ) );

if (!$rsAtividadesSanitario || !$clsanitario->numrows) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontradas atividades cadastrados.');
  exit;
}

db_fieldsmemory($rsAtividadesSanitario, 0);

/**
 * Imprime o alvará (Agata)
 */
if ($y32_modalvara == '3') {

  $sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");
  $sNomeRelatorio   = "tmp/alvara_sanitario_{$sDescrDoc}.pdf";
  $sCaminhoSalvoSxw = "tmp/alvara_sanitario_{$sDescrDoc}.sxw";

  $sAgt = "fiscal/alvara_sanitario.agt";

  $aParam = array(
    'iCodSani'   => $y80_codsani,
    'sDataAtual' => $datahj
  );

  /**
   * Buscamos se deve imprimir alvara provisorio ou permanente
   * validando pela atividade principal
   */
  $iTemplate = $y32_templatealvarasanitarioprovisorio;

  if ($y83_perman == 't') {
    $iTemplate = $y32_templatealvarasanitariopermanente;
  }

  db_stdClass::oo2pdf(47, $iTemplate, $sAgt, $aParam, $sCaminhoSalvoSxw, $sNomeRelatorio);
  return;
}


if ($y32_modalvara == 2){
    $modelo = '20';
}elseif ($y32_modalvara == 1){
    $modelo = '21';
}else{
    db_redireciona('db_erros.php?fechar=true&db_erro=Configure os parâmetros do módulo fiscal !!!');
    exit;
}

$pdf  = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf, $modelo);
$pdf1->objpdf->AddPage();
$pdf1->objpdf->SetTextColor(0,0,0);

$pdf1->impdatas   = $y32_impdatas;
$pdf1->impcodativ = $y32_impcodativ;
$pdf1->impobsativ = $y32_impobs;
$pdf1->impobslanc = $y32_impobslanc;

$sql    = "select munic from db_config where codigo = ".db_getsession("DB_instit");
$resultMunicipio =  db_query($sql);
db_fieldsmemory($resultMunicipio, 0);

$sqlparag = "select *
    from db_documento
    inner join db_docparag on db03_docum = db04_docum
    inner join db_tipodoc on db08_codigo  = db03_tipodoc
    inner join db_paragrafo on db04_idparag = db02_idparag
    where db03_tipodoc = 1012 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
$resparag = db_query($sqlparag);
if ( pg_numrows($resparag) == 0 ) {

     db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento do alvara sanitario!');
     exit;
}
$numrows = pg_numrows($resparag);
for($i=0;$i<$numrows;$i++){
  db_fieldsmemory($resparag,$i);
  if ($db04_ordem == '1'){
        $pdf1->texto = $db02_texto;
  }
  if ($db04_ordem == '2'){
      $pdf1->obs = $db02_texto;
  }
  if ($db04_ordem == '3'){
      $pdf1->assalvara = $db02_texto;
  }
}

$pdf1->tipoalvara = "ALVARÁ SANITÁRIO";
$pref             = split(" ",$munic);
$munic            = "";
$espaco           = "";
for ($x=0; $x < count($pref); $x++) {
  if ( strlen($pref[$x]) >2 ) {
    $munic .= $espaco.ucfirst(strtolower($pref[$x]));
  } else {
    $munic .= $espaco.strtolower($pref[$x]);
  }
  $espaco = " ";
}

$pdf1->prefeitura = "Prefeitura Municipal de ".$munic;
$pdf1->nrinscr    = $y80_codsani;
$pdf1->compl      = $y80_compl;
$pdf1->nome       = $z01_nome;
$pdf1->cnpjcpf    = $z01_cgccpf;
$pdf1->ender      = $j14_nome;
$pdf1->numero     = $y80_numero;
$pdf1->numbloco   = $y80_numbloco;
$pdf1->bairropri  = $j13_descr;
$pdf1->ativ       = $y83_ativ;
$pdf1->dtiniativ  = $y83_dtini;
$pdf1->descrativ  = $q03_descr;
$pdf1->dtfimativ  = $y83_dtfim;
$pdf1->municpref  = $munic;
$pdf1->processo   = $y82_codproc;
$pdf1->lancobs    = $y80_texto;
$pdf1->datainc    = $y80_data;
$pdf1->permanente = $y83_perman;

$descr              = $pdf1->descrativ;
if (isset($y32_impobslanc)&&$y32_impobslanc=='t'&&$y80_area>0){
$pdf1->area      = $y80_area;
}

$result = $clsanitario->sql_record($clsanitario->sql_querysani("","*","y80_codsani"," y80_codsani = $y80_codsani and y83_ativprinc = 'f' and y80_dtbaixa is null and y83_databx is null and (y81_data is null or y81_data <= '$datahj')"));

$numrows=$clsanitario->numrows;
if($numrows != 0){

   for($i=0; $i<$numrows; $i++){

     db_fieldsmemory($result,$i);
     if($y83_ativprinc == 'f'){

         $arr[$i]["codativ"] = $y83_ativ;
         $arr[$i]["descr"]   = $q03_descr;
         $arr[$i]["datain"]  = $y83_dtini;
         $arr[$i]["datafi"]  = $y83_dtfim;
         $arr[$i]["perman"]  = $y83_perman;
         $q03_atmemo=str_replace("\n","",$q03_atmemo);
         $q03_atmemo=str_replace("\r","",$q03_atmemo);
         $arr02[$y83_ativ] = $q03_atmemo;
     }
   }
  $pdf1->q03_atmemo = $arr02;
  $pdf1->outrasativs =$arr;
  if(isset($q02_memo)){

    $q02_memo=str_replace("\n","",$q02_memo);
    $q02_memo=str_replace("\r","",$q02_memo);
    $pdf1->q02_memo =$q02_memo;
  }
}
$arr= array();
$arr02= array();
$descr="";

$pdf1->imprime();
$pdf1->objpdf->Output();