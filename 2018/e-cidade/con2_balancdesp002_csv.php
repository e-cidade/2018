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
include(modification("libs/db_liborcamento.php"));

$tipo_mesini = 1;
$tipo_mesfim = 1;

//$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco
//$tipo_agrupa = 1;
// 1 = geral
// 2 = orgao
// 3 = unidade
//$tipo_nivel = 6;
// 1 = funcao
// 2 = subfuncao
// 3 = programa
// 4 = projeto/atividade
// 5 = elemento 
// 6 = recurso 
$tipo_agrupa = 3;
$tipo_nivel  = 6;

$qorgao   = 0;
$qunidade = 0;

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("fpdf151/assinatura.php"));

$classinatura = new cl_assinatura();
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

if ($orgaos == "") {
  db_redireciona('db_erros.php?fechar=true&db_erro=Selecione orgao/unidade!');
}

$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
} else {

  $xtipo = "BALANÇO";
  if ($opcao == 3) {
    $head6 = "PERÍODO : " . db_formatar($perini, 'd') . " A " . db_formatar($perfin, 'd');
  } else {
    $head6 = "PERÍODO : " . strtoupper(db_mes(substr($perini, 5, 2))) . " A " . strtoupper(db_mes(substr($perfin, 5, 2)));
  }
}
$head1 = "DEMONSTRATIVO DA DESPESA";
$head3 = "EXERCÍCIO: " . db_getsession("DB_anousu");

$xinstit = split("-", $db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (" . str_replace('-', ', ', $db_selinstit) . ") ");
$descr_inst = '';
$xvirg      = '';
$flag_abrev = false;

for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {

  db_fieldsmemory($resultinst, $xins);
  if (strlen(trim($nomeinstabrev)) > 0) {

    $descr_inst .= $xvirg . $nomeinstabrev;
    $flag_abrev  = true;
  } else {
    $descr_inst .= $xvirg.$nomeinst;
  }
  $xvirg = ', ';
}

if ($flag_abrev == false) {

  if (strlen($descr_inst) > 42) {
    $descr_inst = substr($descr_inst, 0, 100);
  }
}

$head5     = "INSTITUIÇÕES : " . $descr_inst;
$nivela    = substr($vernivel, 0, 1);
$sele_work = ' w.o58_instit in (' . str_replace('-', ', ', $db_selinstit) . ') ';
if ($nivela >= 1) {
  $sele_work .= " and exists (select 1 from t where t.o58_orgao = w.o58_orgao)";
}
if ($nivela >= 2) {
  $sele_work .= "  and exists (select 1 from t where t.o58_unidade = w.o58_unidade) ";
}
if ($recurso != 0) {

  $resrec     = db_query("select o15_descr from orctiporec where o15_codigo = {$recurso} ");
  $head2      = "Recurso: " . $recurso . "-" . substr(pg_result($resrec, 0, 0), 0, 30);
  $sele_work .= " and o58_codigo = {$recurso} ";
}

db_query("begin");
db_query("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");

$xcampos = split("-", $orgaos);

for ($i = 0; $i < sizeof($xcampos); $i++) {

  $where    = '';
  $virgula  = '';
  $xxcampos = split("_", $xcampos[$i]);
  for ($ii = 0; $ii < sizeof($xxcampos); $ii++) {

    if ($ii > 0) {

      $where  .= $virgula . $xxcampos[$ii];
      $virgula = ', ';
    }
  }

  if ($nivela == 1) {
    $where .= ",0,0,0,0,0,0,0";
  }

  if ($nivela == 2) {
    $where .= ",0,0,0,0,0,0";
  }
  db_query("insert into t values({$where})");
}
$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;
if ($totaliza == "A") {
  $result = db_dotacaosaldo(8, 1, 4, true, $sele_work, $anousu, $dataini, $datafin);
} else {
  $result = db_dotacaosaldo(2, 2, 4, true, $sele_work, $anousu, $dataini, $datafin);
}
$fp = fopen("tmp/baldesp.csv","w");
fputs($fp,"Orgão;;Unidade;;Função;;Subfunção;;Programa;;ProjAtiv;;Elemento;;Recurso;;DotIni;Suplem;Especial;Reduzido;Empenhado;Anulado;Liquidado;Pago;Empenhado_Acumulado;Anulado_acumulado;Liquidado_acumulado;Pago_acumulado\n");

while ($ln = pg_fetch_array($result)) {

  fputs($fp,$ln["o58_orgao"].";".$ln["o40_descr"].";".
            $ln["o58_unidade"].";".$ln["o41_descr"].";".
            $ln["o58_funcao"].";".$ln["o52_descr"].";".
            $ln["o58_subfuncao"].";".$ln["o53_descr"].";".
            $ln["o58_programa"].";".$ln["o54_descr"].";".
            $ln["o58_projativ"].";".$ln["o55_descr"].";".
            $ln["o58_elemento"].";".$ln["o56_descr"].";".
            $ln["o58_codigo"].";".$ln["o15_descr"].";"
  );

  fputs($fp,db_formatar($ln["dot_ini"],'f').";".
            db_formatar($ln["suplemen_acumulado"],'f').";".
            db_formatar($ln["especial_acumulado"],'f').";".
            db_formatar($ln["reduzido_acumulado"],'f').";".
            db_formatar($ln["empenhado"],'f').";".
            db_formatar($ln["anulado"],'f').";".
            db_formatar($ln["liquidado"],'f').";".
            db_formatar($ln["pago"],'f').";".
            db_formatar($ln["empenhado_acumulado"],'f').";".
            db_formatar($ln["anulado_acumulado"],'f').";".
            db_formatar($ln["liquidado_acumulado"],'f').";".
            db_formatar($ln["pago_acumulado"],'f').";\n ");
}

echo "<html><body bgcolor='#cccccc'><center><a href='tmp/baldesp.csv'>Clique com botão direito para Salvar o arquivo <b>baldesp.csv</b></a></body></html>";
fclose($fp);
