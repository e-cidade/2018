<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

require_once("fpdf151/impcarne.php");
require_once("libs/db_barras.php");
require_once("libs/db_utils.php");
require_once("classes/db_db_bancos_classe.php");
require_once("classes/db_recibopagaboleto_classe.php");
require_once("model/regraEmissao.model.php");
require_once("model/convenio.model.php");


db_postmemory($HTTP_SERVER_VARS);
$matricularecibo = @$j01_matric;
$inscricaorecibo = @$q02_inscr;
$numcgmrecibo    = @$z01_numcgm;
db_postmemory($HTTP_POST_VARS);

if (isset($tipo) && $tipo != "" ) {
  $tipo_debito = $tipo;
}

$instit = db_getsession('DB_instit');

$cldb_bancos    = new cl_db_bancos;
$clconfigdbpref = new cl_configdbpref();

$sqluf = "select db12_uf,db12_extenso,db21_usasisagua from db_config  inner join db_uf on db12_uf=uf  where codigo = ".$instit;
$resultuf = db_query($sqluf);
db_fieldsmemory($resultuf,0);

try {
   $oRegraEmissao = new regraEmissao($tipo_debito,23,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

/*
 * Verificamos se a regra de emissão configurada para o tipo de débito se trata de cobrança registrada
 * Caso seja cobrança registrada então bloqueamos a emissão do recibo
 */
$sSqlCadTipoConvenio = "select ar11_cadtipoconvenio from cadconvenio where ar11_sequencial = {$oRegraEmissao->getConvenio()}";
$rsCadTipoConvenio   = db_query($sSqlCadTipoConvenio);
$iCadTipoConvenio    = db_utils::fieldsMemory($rsCadTipoConvenio,0)->ar11_cadtipoconvenio;
if ($iCadTipoConvenio == 7) {
  db_redireciona("db_erros.php?fechar=true&db_erro='A emissão do recibo deste tipo de débito pode ser realizado apenas na Prefeitura'");
  exit;
}

$tipoidentificacao = 0;
$naopassa          = 'f';
$sPQLLocal         = '';

$lConvenioCobrancaValido = false;

if(!isset($emite_recibo_protocolo)){
  db_query("BEGIN");

  $result = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
  db_fieldsmemory($result,0);

  //pega os numpres da ca3_gerfinanc002.php, separa e insere em db_reciboweb
  $result = db_query("select k00_codbco,k00_codage,k00_descr,k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,k00_hist6,k00_hist7,k00_hist8,k03_tipo,k00_tipoagrup from arretipo where k00_tipo = $tipo");


  if(pg_numrows($result)==0){
    echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo.";
    exit;
  }
  db_fieldsmemory($result,0);

  $k00_descr = $k00_descr;
  $historico = $k00_descr;

  $vt = $HTTP_POST_VARS;
  $desconto = 0;

  if(isset($inicial)) {
    $tipo_debito=18;
  }

  if(!isset($numpre_unica) || $numpre_unica =="") {

    $tam = sizeof($vt);
    reset($vt);
    $numpres = "";
    $numprestemp = array();
    $meses= array();
    $arretipos = array();

    for($i = 0;$i < $tam;$i++) {

      if(db_indexOf(key($vt) ,"CHECK") > 0){

        $numpres .= "N" . $vt[key($vt)];
        $matnumpres = split("N", $vt[key($vt)]);

        if (!isset($inicial)) {
          for ($contanumpres = 0; $contanumpres < sizeof($matnumpres); $contanumpres++) {

            $numprecerto = $matnumpres[$contanumpres];

            if ($matnumpres[$contanumpres] == "") {
              continue;
            }

            $resultado = split("P",$numprecerto);
            $numpar    = split("P",$resultado[1]);
            $numpar    = split("R",$numpar[0]);

            $sqlagrupa = "select distinct
                                 k00_descr as descrarretipo,
                                 extract (months from k00_dtvenc) as mesagrupa,
                                 extract (year from k00_dtvenc) as anoagrupa
                            from arrecad
                           inner join arretipo              on arrecad.k00_tipo = arretipo.k00_tipo
                            left join configdbprefarretipo  on w17_arretipo = arrecad.k00_tipo
                                                           and w17_instit   = ".db_getsession("DB_instit")."
                           where k00_numpre = " . $resultado[0] . "
                             and k00_numpar = " . $numpar[0] . "
                             and case
                                 when w17_sequencial is null then
                                 true
                                 else
                             arrecad.k00_dtvenc between w17_dtini and w17_dtfim end  ";

            $resultagrupa = db_query($sqlagrupa) or die($sqlagrupa);
            if (pg_numrows($resultagrupa) > 0) {
                db_fieldsmemory($resultagrupa,0);
                if (!in_array(str_pad($mesagrupa,2,"0") . $anoagrupa, $meses)) {
                  $meses[] = str_pad($mesagrupa,2,"0",STR_PAD_LEFT) . $anoagrupa;
                }

                if (!in_array($descrarretipo, $arretipos)) {
                  $arretipos[] = $descrarretipo;
                }
            }
          }
        }
      }
      next($vt);
    }

    if(!empty($HTTP_POST_VARS["ver_matric"])) {
      $inner = "arrematric ";
      $campoinner = "k00_matric = $ver_matric";
    } elseif (!empty($HTTP_POST_VARS["ver_inscr"])) {
      $inner = "arreinscr ";
      $campoinner = "k00_inscr = $ver_inscr";
    } elseif (!empty($HTTP_POST_VARS["ver_numcgm"])) {
      $inner = "arrenumcgm ";
      $campoinner = "k00_numcgm = $ver_numcgm";
    }

    $numpre_temp1 = "";

    if ($k00_tipoagrup == 2) {

      for ($mes=0; $mes < sizeof($meses); $mes++) {

        $sqlagrupa = "
          select distinct
                 arrecad.k00_numpre as numpreagrupa,
                 arrecad.k00_numpar as numparagrupa,
                 ( case when w17_sequencial is null then 's'
                        when arrecad.k00_dtvenc between w17_dtini and w17_dtfim then 's'
                        else 'n'
                   end ) as imprime
            from (select {$inner}.*
                    from {$inner}
                         inner join arreinstit    on arreinstit.k00_numpre = {$inner}.k00_numpre
                                                 and arreinstit.k00_instit = ".db_getsession("DB_instit")."
                   where {$inner}.{$campoinner}) as {$inner}
                 inner join arrecad  on arrecad.k00_numpre =  {$inner}.k00_numpre
                                    and arrecad.k00_tipo   <> {$tipo_debito}
                                    and extract (months from arrecad.k00_dtvenc) = " . substr($meses[$mes],0,2) . "
                                    and extract (years  from arrecad.k00_dtvenc) = " . substr($meses[$mes],2,4) . "
                 left  join configdbprefarretipo  on w17_arretipo = arrecad.k00_tipo
                                                 and w17_instit   = ".db_getsession("DB_instit")."
           where not exists (select arrenaoagrupa.k00_numpre
                               from arrenaoagrupa
                              where arrenaoagrupa.k00_numpre = {$inner}.k00_numpre)
             and case when w17_sequencial is null then true
                      else arrecad.k00_dtvenc between w17_dtini and w17_dtfim end  ";

        $resultagrupa = db_query($sqlagrupa);
       $numpres_temp = "";
        for ($agrupa=0; $agrupa<pg_numrows($resultagrupa);$agrupa++) {
          db_fieldsmemory($resultagrupa,$agrupa);
          if($imprime == 'n'){
            unset ($numprestemp[$mes]);
            $numpres_temp = "";
            break;
          }else{
            $numpres_temp .= "N" . $numpreagrupa . "P" . $numparagrupa;
          }
        }

        if($numpres_temp != ""){
          $numpre_temp1 .= $numpres_temp;
        }
      }
      foreach ($numprestemp as $value){
        $numpres .= $value;
      }
      $numpres .= $numpre_temp1;
    }

  $rs_agrupadebitos = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file($instit,"w13_agrupadebrecibos"));
  if($clconfigdbpref->numrows > 0){
    db_fieldsmemory($rs_agrupadebitos,0);
  } else {
    $w13_agrupadebrecibos = 'f';
  }

  if ($w13_agrupadebrecibos == 't'){
      $dDataVenc = $dt_agrupadebitos;

      $sqlagrupa = "
          select distinct
                 arrecad.k00_numpre as numpreagrupa,
                 arrecad.k00_numpar as numparagrupa
            from (select {$inner}.*
                    from {$inner}
                         inner join arreinstit    on arreinstit.k00_numpre = {$inner}.k00_numpre
                                                 and arreinstit.k00_instit = ".db_getsession("DB_instit")."
                   where {$inner}.{$campoinner}) as {$inner}

                 inner join arrecad  on arrecad.k00_numpre =  {$inner}.k00_numpre
                                    and arrecad.k00_dtvenc < fc_calculavenci('{$dDataVenc}')
                 left  join configdbprefarretipo  on w17_arretipo = arrecad.k00_tipo
                                                 and w17_instit   = ".db_getsession("DB_instit")."
           where
                 case
                  when w17_sequencial is null then
                    true
                  else
                    arrecad.k00_dtvenc between w17_dtini and w17_dtfim
                 end";
      $resultagrupa = db_query($sqlagrupa);

      for ($agrupa=0; $agrupa<pg_numrows($resultagrupa); $agrupa++) {
        db_fieldsmemory($resultagrupa,$agrupa);

        $numpre_testa = "N" . $numpreagrupa . "P" . $numparagrupa;

        if(strpos($numpres, $numpre_testa) === false) {
          $numpres .= "N" . $numpreagrupa . "P" . $numparagrupa;
        }

      }
    }

    $numpres = split("N",$numpres);
    $totalregistrospassados=0;

    for($iii = 0;$iii < sizeof($numpres);$iii++) {
      $valores = split("P",$numpres[$iii]);
      if ($numpres[$iii] <> "") {
        if(!isset($inicial)) {
          $totalregistrospassados+=sizeof($valores)-1;
        } else {
          $totalregistrospassados+=sizeof($valores);
        }
      }
    }

    $loteador = false;

    if (isset($numcgm) and !isset($matric)) {

      $sqlloteador = "select * from loteam where j34_loteam = $numcgm";
      $resultloteador = db_query($sqlloteador) or die($sqlloteador);
      if (pg_numrows($resultloteador) > 0) {
        $loteador = true;
      }

    }

    $whereloteador = " and k40_forma <> 3";

    if ($loteador == true) {
      $whereloteador = " and k40_forma = 3";
    }


    $aRegTodasMarc = array();

    for($ii = 1;$ii < sizeof($numpres);$ii++) {

      if ($numpres[$ii] == "") {
        continue;
      }

      $valores = split("P",$numpres[$ii]);

      if (isset($inicial)) {
        $sqlinicial = "select distinct
                              arrecad.k00_numpre,
                              arrecad.k00_numpar
                         from inicialnumpre
                              inner join arrecad on arrecad.k00_numpre = inicialnumpre.v59_numpre
                        where v59_inicial = ".$numpres[$ii];
        $resultinicial = db_query($sqlinicial);
        for ($xinicial=0; $xinicial < pg_numrows($resultinicial); $xinicial++) {
          db_fieldsmemory($resultinicial,$xinicial);

          $desconto = recibodesconto($k00_numpre, $k00_numpar, $tipo, $tipo, $whereloteador, $totalregistrospassados, $totregistros);
          $sql = "insert into db_reciboweb values(".$k00_numpre.",".$k00_numpar.",$k03_numpre,$k00_codbco,'$k00_codage','{$oRegraEmissao->getCodConvenioCobranca()}',$desconto)";
          db_query($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage());

        }

      } else {
        $numpar = split("R", $valores[1]);
        $desconto = recibodesconto($valores[0], $numpar[0], $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros);
        $sqlprocura = "select * from db_reciboweb where k99_numpre = " . $valores[0] . " and k99_numpar = " . $numpar[0] . " and k99_numpre_n = $k03_numpre";
        $resultprocura = db_query($sqlprocura) or die($sqlprocura);
        if (pg_numrows($resultprocura ) == 0) {
          $sql = "insert into db_reciboweb values(".$valores[0].",".$numpar[0].",$k03_numpre,$k00_codbco,'$k00_codage','{$oRegraEmissao->getCodConvenioCobranca()}',$desconto)";
          db_query($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage() . "\ncomando:\n$sql");
        }
      }

    }

  } else {

    $sql = "insert into db_reciboweb values(".$numpre_unica.",0,$k03_numpre,$k00_codbco,'$k00_codage','{$oRegraEmissao->getCodConvenioCobranca()}',$desconto)";
    db_query($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage());

  }

  $sqlrecibo = "select * from db_reciboweb where k99_numpre_n = $k03_numpre";
  $resultrecibo = db_query($sqlrecibo) or die($sqlrecibo);

  /* REGRAS PARA DATA DE CALCULO */
  $minvenc = "";
  if(isset($forcarvencimento) && $forcarvencimento == 'true'){
    if(date("Y-m-d",$DB_DATACALC) > db_getsession('DB_anousu').'-12-31'){
      $minvenc = db_getsession('DB_anousu').'-12-31';
    }else{
      $minvenc = date("Y-m-d",$DB_DATACALC);
    }
  }else{
    for ($conta=0; $conta < pg_numrows($resultrecibo); $conta++) {
      $sqlvenc =  " select min(k00_dtvenc) as k00_dtvenc from arrecad where k00_numpre = " . pg_result($resultrecibo,$conta,"k99_numpre") . " and ";
      $sqlvenc .= " k00_numpar = " . pg_result($resultrecibo,$conta,"k99_numpar");
      $resultvenc = db_query($sqlvenc) or die($sqlvenc);
      db_fieldsmemory($resultvenc,0);
      if ($k00_dtvenc < $minvenc or $minvenc == "") {
        $minvenc = $k00_dtvenc;
      }
    }
    $exerc = substr($minvenc,0,4);
    /* se o menor vencimento do numpre for menor que a data para pagamento(data informada na CGF) menor vencimento = data para pagamento */
    if ($minvenc < date("Y-m-d",$DB_DATACALC)) {
      $minvenc = date("Y-m-d",$DB_DATACALC);
    }

    /* se menor vencimento do numpre for maior que 31-12 do ano corrente menor vencimento = 31-12 do ano corrente */
    if ($minvenc > db_getsession('DB_anousu')."-12-31") {
      $minvenc = db_getsession('DB_anousu')."-12-31";
    }
  }

  $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

  /**
   * Caso seja um convenio de cobrança válido e a data de vencimento seja a mesma data de geração, adiciona mais um dia na data de vencimento
   */
  if ($lConvenioCobrancaValido) {

    $sSqlVencimento = "select fc_proximo_dia_util('{$minvenc}'::date) as vencimento";
    $rsVencimento   = db_query($sSqlVencimento);

    if ( !$rsVencimento ) {
      throw new DBException("Erro ao buscar dia útil para o vencimento do recibo.");
    }

    $oVencimento = db_utils::fieldsMemory($rsVencimento, 0);

    // Verifica se o ano do vencimento é maior que o ano atual
    // Caso seja, pega o ultimo dia útil do ano
    $iAnoAtual = (int) date('Y', db_getsession('DB_datausu'));
    $iAnoRecibo = (int) date('Y', strtotime($oVencimento->vencimento));

    if($iAnoRecibo > $iAnoAtual){

      $sSqlVencimento = "select fc_ultimo_dia_util('{$iAnoAtual}-12-31'::date) as vencimento";
      $rsVencimento   = db_query($sSqlVencimento);

      if ( !$rsVencimento ) {
        throw new DBException("Erro ao buscar ultimo dia útil para o vencimento do recibo.");
      }

      $oVencimento = db_utils::fieldsMemory($rsVencimento, 0);
    }

    $minvenc     = $oVencimento->vencimento;
  }

  //roda funcao fc_recibo pra gerar o recibo
  $sql = "select fc_recibo($k03_numpre,'$minvenc'::date,'$minvenc'::date,".db_getsession("DB_anousu").")";
  $Recibo = db_query($sql) or die($sql);

  $oDaoReciboPagaBoleto = new cl_recibopagaboleto();

  $oDaoReciboPagaBoleto->k138_numnov  = $k03_numpre;
  $oDaoReciboPagaBoleto->k138_data    = date("Y-m-d",db_getsession("DB_datausu"));
  $oDaoReciboPagaBoleto->k138_hora    = date("H:i:s",db_getsession("DB_datausu"));
  $oDaoReciboPagaBoleto->k138_usuario = db_getsession("DB_id_usuario");
  $oDaoReciboPagaBoleto->incluir("");

  if ( (int)$oDaoReciboPagaBoleto->erro_status == 0 ) {
  	throw new Exception("Gravar dados recibopagaboleto: \n" . $oDaoReciboPagaBoleto->erro_msg );
  }

}else{

  db_postmemory($HTTP_SERVER_VARS);

  if(isset($db_datausu)) {
    if(!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
      echo "Data para Cálculo Inválida. <br><br>";
      echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
      exit;
    }
    if(mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) < mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu"))) ){
      echo "Data não permitida para cálculo. <br><br>";
      echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
      exit;
    }
    $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
  }else{
    $DB_DATACALC = db_getsession("DB_datausu");
  }

  $k00_descr = $k00_histtxt;

}

db_query("COMMIT");
//seleciona os valores gerado pela funcao fc_recibo


if(!isset($emite_recibo_protocolo)){

  $sql = "select r.k00_numcgm,
                 r.k00_receit,r.k00_hist,
                 case when taborc.k02_codigo is null then tabplan.k02_reduz else taborc.k02_codrec end as codreduz,
                 t.k02_descr,
                 t.k02_drecei,
                 r.k00_dtoper as k00_dtoper,
                 sum(r.k00_valor) as valor,
                 (select (select k02_codigo from tabrec where k02_recjur = r.k00_receit or k02_recmul = r.k00_receit limit 1) is not null ) as codtipo
            from recibopaga r
           inner join tabrec t      on t.k02_codigo       = r.k00_receit
           inner join tabrecjm      on tabrecjm.k02_codjm = t.k02_codjm
            left outer join taborc  on t.k02_codigo       = taborc.k02_codigo
                                   and taborc.k02_anousu  = ".db_getsession("DB_anousu")."
            left outer join tabplan on t.k02_codigo       = tabplan.k02_codigo
                                   and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
           where r.k00_numnov = ".$k03_numpre."
           group by r.k00_dtoper,r.k00_receit,r.k00_hist,t.k02_descr,t.k02_drecei,r.k00_numcgm,codreduz";
}else{

  $sql = "select r.k00_numcgm,
                 r.k00_receit,
                 r.k00_hist,
                 case when taborc.k02_codigo is null then tabplan.k02_reduz else taborc.k02_codrec end as codreduz,
                 t.k02_descr,
                 t.k02_drecei,
                 r.k00_dtoper as k00_dtoper,
                 sum(r.k00_valor) as valor,
                 (select (select k02_codigo from tabrec where k02_recjur = r.k00_receit or k02_recmul = r.k00_receit limit 1) is not null ) as codtipo
            from recibo r
           inner join tabrec t       on t.k02_codigo       = r.k00_receit
           inner join tabrecjm       on tabrecjm.k02_codjm = t.k02_codjm
            left outer join taborc   on t.k02_codigo       = taborc.k02_codigo
                                    and taborc.k02_anousu  = ".db_getsession("DB_anousu")."
            left outer join tabplan  on t.k02_codigo       = tabplan.k02_codigo
                                    and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
           where r.k00_numpre = ".$k03_numpre."
           group by r.k00_dtoper,r.k00_receit,r.k00_hist,t.k02_descr,t.k02_drecei,r.k00_numcgm,codreduz";

}

$DadosPagamento = db_query($sql) or die($sql);
//faz um somatorio do valor
//db_criatabela($DadosPagamento);exit;
if (pg_numrows($DadosPagamento) == 0) {
  echo "problemas ao gerar recibo! Contate suporte";
  exit;
}
$datavencimento = pg_result($DadosPagamento,0,"k00_dtoper");
$total_recibo = 0;
for($i = 0;$i < pg_numrows($DadosPagamento);$i++) {
  $total_recibo += pg_result($DadosPagamento,$i,"valor");
}

//seleciona da tabela db_config, o numero do banco e a taxa bancaria e concatena em variavel

$DadosInstit = db_query("select nomeinst,ender,numero,munic,email,telef,cgc,uf,logo,to_char(tx_banc,'99.99') as tx_banc,numbanco from db_config where codigo = $instit");
//cria codigo de barras e linha digitável
$NumBanco = pg_result($DadosInstit,0,"numbanco");
$taxabancaria = pg_result($DadosInstit,0,"tx_banc");
$src          = pg_result($DadosInstit,0,'logo');
$db_nomeinst  = pg_result($DadosInstit,0,'nomeinst');
$db_ender     = pg_result($DadosInstit,0,'ender');
$db_numero    = pg_result($DadosInstit,0,'numero');
$db_munic     = pg_result($DadosInstit,0,'munic');
$db_uf        = pg_result($DadosInstit,0,'uf');
$db_telef     = pg_result($DadosInstit,0,'telef');
$db_cgc       = pg_result($DadosInstit,0,'cgc');
$db_email     = pg_result($DadosInstit,0,'email');

$total_recibo += $taxabancaria;
if ( $total_recibo == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=O Recibo Com Valor Zerado.');
}
$valor_parm = $total_recibo;

//seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
//essa variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str
if(!empty($HTTP_POST_VARS["ver_matric"]) || $matricularecibo > 0 ) {
  $numero = @$HTTP_POST_VARS["ver_matric"] + $matricularecibo;
  $tipoidentificacao = "Matricula :";
  $sSqlMatric        = "select z01_nome,
                               z01_ender,
                               z01_numero,
                               z01_compl,
                               z01_munic,
                               z01_uf,
                               z01_cep,
                               nomepri,
                               j39_compl,
                               j39_numero,
                               j13_descr as bairro_matricula,
                               case when j13_descr is not null and j13_descr != '' then
                               j13_descr
                               else ''
                               end as j13_descr,
                               j34_setor||'.'||j34_quadra||'.'||j34_lote as sql,
                               z01_cgccpf,
                               z01_bairro,
                               z01_cgmpri as z01_numcgm,
                               j05_codigoproprio,
                               j05_descr,
                               j06_quadraloc,
                               j06_lote
                          from proprietario
                          where j01_matric = $numero
                          limit 1";

  $Identificacao = db_query($sSqlMatric);

  db_fieldsmemory($Identificacao,0);

  $sPQLLocal     = "PQL: {$j05_codigoproprio} - {$j05_descr} / {$j06_quadraloc} / {$j06_lote}";
  $ident_tipo_ii = '';
} else if(!empty($HTTP_POST_VARS["ver_inscr"]) || $inscricaorecibo > 0 ) {
  $numero = @$HTTP_POST_VARS["ver_inscr"] + $inscricaorecibo;
  $tipoidentificacao = "Inscricao :";
  $Identificacao = db_query("select z01_nome,
  z01_ender,
  z01_numero,
  z01_compl,
  z01_munic,
  z01_uf,
  z01_cep,
  z01_ender as nomepri,
  z01_compl as j39_compl,
  z01_numero as j39_numero,
  z01_bairro as j13_descr,
  z01_bairro,
  '' as sql,
  z01_cgccpf
  from empresa
  where q02_inscr = $numero");
  $sqlidentificacao = "select
  cgm.z01_numcgm,
  cgm.z01_nome,
  cgm.z01_ender,
  cgm.z01_numero,
  cgm.z01_compl,
  cgm.z01_bairro,
  cgm.z01_munic,
  cgm.z01_uf,
  cgm.z01_cep,
  empresa.z01_ender as nomepri,
  empresa.z01_compl as j39_compl,
  empresa.z01_numero as j39_numero,
  empresa.z01_bairro as j13_descr,
  '' as sql,
  cgm.z01_cgccpf
  from issbase
  inner join empresa on issbase.q02_inscr = empresa.q02_inscr
  inner join cgm on issbase.q02_numcgm = cgm.z01_numcgm
  where issbase.q02_inscr = $numero";
  $Identificacao = db_query($sqlidentificacao) or die($sqlidentificacao);

  $ident_tipo_ii = 'Alvará';
  db_fieldsmemory($Identificacao,0);
} else if(!empty($HTTP_POST_VARS["ver_numcgm"]) || $numcgmrecibo > 0 ) {
  $numero = @$HTTP_POST_VARS["ver_numcgm"] + $numcgmrecibo ;
  $tipoidentificacao = "Numcgm :";
  $Identificacao = db_query("select z01_numcgm,z01_nome,z01_ender,z01_numero,z01_compl,z01_bairro,z01_munic,z01_uf,z01_cep,''::bpchar as nomepri,''::bpchar as j39_compl,''::bpchar as j39_numero,z01_bairro as j13_descr, '' as sql, z01_cgccpf
  from cgm
  where z01_numcgm = $numero ");
  db_fieldsmemory($Identificacao,0);
  $ident_tipo_ii = '';
} else {
  if(isset($emite_recibo_protocolo)){
    $Identificacao = db_query("
    select c.z01_bairro, c.z01_nome,c.z01_ender,c.z01_numero,c.z01_compl,c.z01_munic,c.z01_uf,c.z01_cep,' ' as nomepri,' ' as j39_compl, ' ' as j39_numero, ' ' as j13_descr, '' as sql,z01_cgccpf, c.z01_numcgm
    from recibo r
    inner join cgm c on c.z01_numcgm = r.k00_numcgm
    where r.k00_numpre = ".$k03_numpre."
    limit 1");
    db_fieldsmemory($Identificacao,0);
  }
}

if(isset($tipo_debito)) {

  if(isset($inicial)) {
    $resulttipo = db_query("select k03_tipo, k00_tipoagrup from arretipo where k00_tipo = 34");
  } else {
    $resulttipo = db_query("select k03_tipo, k00_tipoagrup, k00_msgparc from arretipo where k00_tipo = $tipo_debito");
  }
  db_fieldsmemory($resulttipo,0);

  if($k03_tipo==5 && $k00_tipoagrup<>2 ) {
    $histparcela = "Divida: ";
    $sqlhist = "select distinct
    v01_exerc,
    v01_numpar
    from db_reciboweb
    left outer join divida on v01_numpre = k99_numpre and
    v01_numpar = k99_numpar
    where k99_numpre_n = $k03_numpre
    group by v01_exerc,v01_numpar
    order by v01_exerc,v01_numpar";
    $result = db_query($sqlhist);
    if(pg_numrows($result)!=false){
      $exercv = "0000";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if( $exercv != pg_result($result,$xy,0)){
          $exercv = pg_result($result,$xy,0);
          $histparcela .= pg_result($result,$xy,0).":";
        }
        $histparcela .= pg_result($result,$xy,1)."-";
      }
    }
    $sqlobs = "select distinct
    v01_obs
    from db_reciboweb
    inner join divida on v01_numpre = k99_numpre and
    v01_numpar = k99_numpar
    where k99_numpre_n = $k03_numpre";
    $result = db_query($sqlobs);
    if (pg_numrows($result) > 0) {
      $histparcela .= "OBS: ";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if (ltrim(rtrim(pg_result($result,$xy,0))) != "") {
          $histparcela .= ltrim(rtrim(pg_result($result,$xy,0)));
        }
      }
    }
  }else if($k03_tipo == 2 && $k00_tipoagrup<>2){
    $histparcela = "Exercicio: ";
    $sqlhist = "select distinct q01_anousu, k99_numpar
    from db_reciboweb
    inner join isscalc on q01_numpre = k99_numpre
    where k99_numpre_n = $k03_numpre
    group by q01_anousu,k99_numpar
    order by q01_anousu,k99_numpar";
    $result = db_query($sqlhist);
    if(pg_numrows($result)!=false){
      $exercv = "0000";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if( $exercv != pg_result($result,$xy,0)){
          $exercv = pg_result($result,$xy,0);
          $histparcela .= "  ".pg_result($result,$xy,0).": Parc:";
        }
        $histparcela .= "-".pg_result($result,$xy,1);
      }
    }
  }else if($k03_tipo == 3 && $k00_tipoagrup<>2){
    $histparcela = "Exercicio: ";
    $sqlhist = "select distinct q05_ano, q05_mes
    from db_reciboweb
    left outer join issvar on q05_numpre = k99_numpre and q05_numpar = k99_numpar
    where k99_numpre_n = $k03_numpre
    group by q05_ano,q05_mes
    order by q05_ano,q05_mes";
    $result = db_query($sqlhist);
    if(pg_numrows($result)!=false){
      $exercv = "0000";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if( $exercv != pg_result($result,$xy,0)){
          $exercv = pg_result($result,$xy,0);
          $histparcela .= "  ".pg_result($result,$xy,0).": Mês:";
        }
        $histparcela .= "-".pg_result($result,$xy,1);

        if (pg_result($result,$xy,1) != "") {
          $sqlhistor = "select distinct q05_histor
          from db_reciboweb
          inner join issvar on q05_numpre = k99_numpre and q05_numpar = k99_numpar
          where k99_numpre_n = $k03_numpre and q05_numpar = " . pg_result($result,$xy,1);
          $resulthistor = db_query($sqlhistor);
          if (pg_numrows($resulthistor) > 0) {
            db_fieldsmemory($resulthistor,0);
            if ($q05_histor <> "Arrecadacao Normal") {
              $histparcela .= " - " . $q05_histor;
            }
          }
        }

      }

    }

  }else if(($k03_tipo==6 or $k03_tipo==13) && $k00_tipoagrup<>2){

    $histparcela = '';
    $parcelamento = '';
    $sqlhist = "select v07_parcel, k99_numpar
    from db_reciboweb
    left outer join termo on v07_numpre = k99_numpre
    where k99_numpre_n = $k03_numpre
    order by v07_parcel,k99_numpar";
    $result = db_query($sqlhist);
    if(pg_numrows($result)!=false){
      //         $histparcela = "Parcelamento: ".pg_result($result,0,0)." Parc:";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if (pg_result($result,$xy,0) != $parcelamento){
          $histparcela .= ' Parcelamento' . ($k03_tipo == 13?" do foro":"") . ': '.pg_result($result,$xy,0)." - ";
        }
        $histparcela .= pg_result($result,$xy,1).", ";
        $parcelamento = pg_result($result,$xy,0);
      }
    }
  } elseif($k03_tipo==7 && $k00_tipoagrup<>2){
    $histparcela = "Diversos: ";
    $sqlhist = "select distinct dv05_exerc, k00_numpar
    from db_reciboweb
    inner join arrecad on k99_numpre = k00_numpre and k99_numpar = k00_numpar
    inner join diversos on dv05_numpre = k99_numpre
    where k99_numpre_n = $k03_numpre
    group by dv05_exerc,k00_numpar
    order by dv05_exerc,k00_numpar";
    $result = db_query($sqlhist);
    if(pg_numrows($result)!=false){
      $exercv = "0000";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if( $exercv != pg_result($result,$xy,0)){
          $exercv = pg_result($result,$xy,0);
          $histparcela .= pg_result($result,$xy,0).":";
        }
        $histparcela .= pg_result($result,$xy,1)."-";
      }
    }
    $sqlobs = "select distinct dv05_obs
    from db_reciboweb
    inner join diversos on dv05_numpre = k99_numpre
    where k99_numpre_n = $k03_numpre";
    $result = db_query($sqlobs);
    if (pg_numrows($result) > 0) {
      $histparcela .= "OBS: ";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if (ltrim(rtrim(pg_result($result,$xy,0))) != "") {
          $histparcela .= ltrim(rtrim(pg_result($result,$xy,0)));
        }
      }
    }

  } elseif($k03_tipo==18 && $k00_tipoagrup<>2) {
    $histparcela = "Inicial: ";
    $sqlhist = "select * from (
    select distinct v59_inicial,
    case when divida.v01_exerc is null then case when divida2.v01_exerc is null then 0 else divida2.v01_exerc end else divida.v01_exerc end as v01_exerc
    from db_reciboweb
    inner join arrecad  on db_reciboweb.k99_numpre = arrecad.k00_numpre and db_reciboweb.k99_numpar = arrecad.k00_numpar
    inner join inicialnumpre  on inicialnumpre.v59_numpre = arrecad.k00_numpre
    inner join inicialcert  on inicialcert.v51_inicial = inicialnumpre.v59_inicial
    left join certdiv     on certdiv.v14_certid = inicialcert.v51_certidao
    left join divida    on divida.v01_coddiv = certdiv.v14_coddiv
    left join certter     on certter.v14_certid = inicialcert.v51_certidao
    left join termo   on termo.v07_parcel = certter.v14_parcel
    left join termodiv    on termodiv.parcel = termo.v07_parcel
    left join divida divida2  on divida2.v01_coddiv = termodiv.coddiv
    where db_reciboweb.k99_numpre_n = $k03_numpre) as x
    order by v59_inicial, v01_exerc";
    $result = db_query($sqlhist);


    if(pg_numrows($result)!=false){
      $exercv = "0000";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if( $exercv != pg_result($result,$xy,0)){
          $exercv = pg_result($result,$xy,0);
          $histparcela .= pg_result($result,$xy,0).":";
        }
        $histparcela .= pg_result($result,$xy,1)."-";
      }
    }

  }else{
    $histparcela = "";
    $sqlhist = "
    select * from (
      select  distinct
      arretipo.k00_tipo,
      k00_descr,
      k99_numpar,
      case when divida.v01_exerc is not null then divida.v01_exerc
      else
        case when termo.v07_parcel is not null then termo.v07_parcel
        else
          extract (year from arrecad.k00_dtoper)
        end
      end as k00_origem
      from db_reciboweb
      inner join arrecad on k99_numpre = k00_numpre and k99_numpar = k00_numpar
      inner join arretipo on  arretipo.k00_tipo = arrecad.k00_tipo
      left join divida on divida.v01_numpre = arrecad.k00_numpre and divida.v01_numpar = arrecad.k00_numpar
      left join termo  on termo.v07_numpre = arrecad.k00_numpre
      where k99_numpre_n = $k03_numpre
    ) as x
    order by
    k00_origem,
    k00_descr,
    k99_numpar";

    $result = db_query($sqlhist) or die($sqlhist);

    $histant = pg_result($result,0,"k00_origem") . "-" . pg_result($result,0,"k00_descr");
    $histparcela .=  pg_result($result,0,"k00_descr") . "=>" . pg_result($result,0,"k00_origem") . " / PARCELAS: ";

    for($xy=0;$xy<pg_numrows($result);$xy++) {
      if (pg_result($result,$xy,"k00_origem") . "-" . pg_result($result,$xy,"k00_descr") <> $histant) {
        $histparcela .= "-" . pg_result($result,$xy,"k00_descr") . "=>" . pg_result($result,$xy,"k00_origem") . " / PARCELAS: ";
        $histant = pg_result($result,$xy,"k00_origem") . "-" . pg_result($result,$xy,"k00_descr");
      }
      $histparcela .= pg_result($result,$xy,"k99_numpar") . " ";
    }
  }
  $historico = $histparcela;
}
//select pras observacoes
$Observacoes = db_query($conn,"select mens,alinhamento from db_confmensagem where cod in('obsboleto1','obsboleto2','obsboleto3','obsboleto4')");
$db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');

$sqlvalor = "select k00_tercdigrecnormal,k00_msgrecibo from arretipo where k00_tipo = $tipo_debito";
db_fieldsmemory(db_query($sqlvalor),0);
if(!isset($k00_tercdigrecnormal) || $k00_tercdigrecnormal == ""){

  db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito: ' . $tipo_debito);
}

try {
  $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpre,0,$total_recibo,$db_vlrbar,$datavencimento,$k00_tercdigrecnormal);
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

try {

  if ($lConvenioCobrancaValido) {

    if (CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
      CobrancaRegistrada::registrarReciboWebservice($k03_numpre, $oRegraEmissao->getConvenio(), $total_recibo, true);
    } else {
      CobrancaRegistrada::adicionarRecibo($k03_numpre, $oRegraEmissao->getConvenio());
    }
  }
} catch( Exception $oErro ) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
  exit;
}

/***************************************  CRIA O MODELO DE RECIBO  ***************************************************************/

$pdf1 = $oRegraEmissao->getObjPdf();


/*********************************************************************************************************************************/
$codigobarras   = $oConvenio->getCodigoBarra();
$linhadigitavel = $oConvenio->getLinhaDigitavel();

$pdf1->tipo_convenio = $oConvenio->getTipoConvenio();

if($oRegraEmissao->isCobranca()){

  $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
  $pdf1->carteira        = $oConvenio->getCarteira();
  $pdf1->nosso_numero    = $oConvenio->getNossoNumero();
}

$dtbase = $datavencimento;
$datavencimento = db_formatar($datavencimento,"d");

$numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
$numpre = $numpre . db_CalculaDV($numpre,11);

//concatena todos os parametros
$pdf1->uf_config     = $db12_uf;
$pdf1->modelo       = 2;

if (!empty($src)) {
  $pdf1->logo       = $src;
} else {
  $pdf1->logo       = 'logo_boleto.jpg';
}

$pdf1->prefeitura   = $db_nomeinst;
$pdf1->enderpref    = $db_ender;
$pdf1->numeropref   = $db_numero;
$pdf1->municpref    = $db_munic;
$pdf1->telefpref    = $db_telef;
$pdf1->cgcpref      = $db_cgc;
$pdf1->emailpref    = @$db_email;
$pdf1->nome         = trim(pg_result($Identificacao,0,"z01_numcgm")) . "-" . trim(pg_result($Identificacao,0,"z01_nome"));
$pdf1->ender        = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl")) . (strlen(trim(pg_result($Identificacao,0,"z01_bairro"))) > 0?"/":"") . trim(pg_result($Identificacao,0,"z01_bairro"));
$pdf1->munic        = trim(pg_result($Identificacao,0,"z01_munic"));
$pdf1->cep          = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->cgccpf       = trim(@pg_result($Identificacao,0,"z01_cgccpf"));
$pdf1->tipoinscr    = $tipoidentificacao;
$pdf1->nrinscr      = $numero;
$pdf1->ip           = db_getsession("DB_ip");

$pdf1->identifica_dados = $ident_tipo_ii;
$pdf1->tipolograd       = 'Logradouro:';
$pdf1->pretipolograd    = 'Logradouro:';
$pdf1->nomepri          = $nomepri;
$pdf1->nomepriimo       = $nomepri;
$pdf1->prenomepri       = $nomepri;
$pdf1->tipocompl        = 'Número:';
$pdf1->pretipocompl     = 'Número:';
$pdf1->nrpri            = $j39_numero;
$pdf1->prenrpri         = $j39_numero;
$pdf1->complpri         = $j39_compl;
$pdf1->precomplpri      = $j39_compl;
$pdf1->tipobairro       = 'Bairro:';
$pdf1->pretipobairro    = 'Bairro:';
if(trim($j13_descr) != trim($z01_bairro)){
  $pdf1->bairropri = $j13_descr; //$z01_bairro;
}else{
  $pdf1->bairropri = "";
}
$pdf1->prebairropri  = $z01_bairro; // $j13_descr;
$pdf1->bairrocontri =  $j13_descr; // $j13_descr;
$pdf1->dtvenc = db_formatar($minvenc,'d');

$pdf1->datacalc= db_formatar($minvenc, "d");
$pdf1->predatacalc= db_formatar($minvenc, "d");
$pdf1->taxabanc= db_formatar($taxabancaria,'f');
$pdf1->recorddadospagto= $DadosPagamento;
$pdf1->linhasdadospagto= pg_numrows($DadosPagamento);
$pdf1->receita= 'k00_receit';
$pdf1->valor= 'valor';
$pdf1->receitared= 'codreduz';
$pdf1->dreceita= 'k02_descr';
$pdf1->ddreceita= 'k02_drecei';
$pdf1->historico= $k00_descr;

// grava historico na recibopagahist
if (isset($reemite_recibo)) {

   $sqlObs = "select k00_historico
                from recibopagahist
               where k00_numnov = $k03_numpre";
   $rsObs  = db_query($sqlObs);
   if (pg_num_rows($rsObs) > 0){

      $historico = pg_result($rsObs,0,0);

   }
}else{

  if (isset($_SESSION["DB_obsrecibo"])){
    $historico = db_getsession("DB_obsrecibo");
  }else{
    $historico = $tipoidentificacao." - ".$numero."\n".$historico;
  }
}

if($db21_usasisagua == 't'){
  $sSqlTmpArrematric     = "";//"w_tmp_arrematric";
  $sSqlTmpArrematric .= "( select distinct ";
  $sSqlTmpArrematric .= "       arrematric.k00_numpre, ";
  $sSqlTmpArrematric .= "       arrematric.k00_matric, ";
  $sSqlTmpArrematric .= "       arrematric.k00_perc    ";
  $sSqlTmpArrematric .= "  from arrematric ";
  $sSqlTmpArrematric .= "       inner join arreinstit  on arreinstit.k00_numpre = arrematric.k00_numpre ";
  $sSqlTmpArrematric .= "                             and arreinstit.k00_instit = {$instit}";
  $sSqlTmpArrematric .= " where arrematric.k00_matric = {$ver_matric} )";

  $sArreMatric = "{$sSqlTmpArrematric} as arrematric";

  $historico1 = MensagemCarne($H_ANOUSU, $tipo_debito, $dtbase, $ver_matric,$sArreMatric);

  $historico .= "\n".$historico1;
}

$pdf1->historico  = $historico;
$pdf1->histparcel= @$histparcela;

$pdf1->historico= $historico."\n \n".$k00_msgrecibo;
$pdf1->histparcel= @$histparcela;

$pdf1->dtvenc= $datavencimento;
$pdf1->numpre= $numpre;
$pdf1->valtotal= db_formatar(@$valor_parm,'f');

if(isset($k00_msgparc)){
  $pdf1->k00_msgparc= $k00_msgparc;
}

$pdf1->linhadigitavel= $linhadigitavel;
$pdf1->codigobarras= $codigobarras;
$pdf1->texto= db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();

$pdf1->descr3_1    = trim(pg_result($Identificacao,0,"z01_numcgm")) . "-" . trim(pg_result($Identificacao,0,"z01_nome")); // contribuinte
$pdf1->descr3_2    = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl")) . (strlen(trim(pg_result($Identificacao,0,"z01_bairro"))) > 0?"/":"") . trim(pg_result($Identificacao,0,"z01_bairro"));// endereco
$pdf1->predescr3_1 = trim(pg_result($Identificacao,0,"z01_nome")); // contribuinte
$pdf1->predescr3_2 = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl"));// endereco
$pdf1->bairropri   = $j13_descr;    // municipio
$pdf1->munic       = trim(pg_result($Identificacao,0,"z01_munic"));
$pdf1->premunic    = trim(pg_result($Identificacao,0,"z01_munic"));

$pdf1->cep         = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->precep      = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->cgccpf      = trim(@pg_result($Identificacao,0,"z01_cgccpf"));
$pdf1->precgccpf   = trim(@pg_result($Identificacao,0,"z01_cgccpf"));

$pdf1->titulo5     = "";                 // titulo parcela
$pdf1->descr5      = "";                 // descr parcela
$pdf1->titulo8     = $tipoidentificacao; // tipo de identificacao;
$pdf1->pretitulo8  = $tipoidentificacao; // tipo de identificacao;
$pdf1->descr8      = $numero;            //descr matricula ou inscricao
$pdf1->predescr8   = $numero;            //descr matricula ou inscricao

// adicionado campo desconto para agrupar os descontos sepadaros da multa
$sqlReceitas = "select k00_receit      as codreceita,
                       k02_descr       as descrreceita,
                       sum(k00_valor)  as valreceita,
                       case
                         when taborc.k02_codigo is not null
                         then taborc.k02_codrec
                         else tabplan.k02_reduz
                       end as reduzreceita,
                       case
                         when k00_valor < 0
                         then 1
                         else 0
                       end as desconto,
                       (select (select k02_codigo
                                  from tabrec
                                 where k02_recjur = k00_receit
                                    or k02_recmul = k00_receit limit 1) is not null ) as codtipo
                  from recibopaga
                       inner join tabrec  on tabrec.k02_codigo   = recibopaga.k00_receit
                       left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo
                                         and taborc.k02_anousu   = ".db_getsession('DB_anousu')."
                       left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo
                                         and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."
                 where k00_numnov = $k03_numpre
                 group by k00_receit,
                          k02_descr,
                          taborc.k02_codrec,
                          tabplan.k02_reduz,
                          taborc.k02_codigo,
                          desconto";
$rsReceitas = db_query($sqlReceitas) or die($sqlReceitas);
$intnumrows = pg_num_rows($rsReceitas);
for($x=0;$x<$intnumrows;$x++){

  db_fieldsmemory($rsReceitas,$x);
  $pdf1->arraycodreceitas[$x]   = $codreceita;
  $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
  $pdf1->arraydescrreceitas[$x] = $descrreceita;
  $pdf1->arrayvalreceitas[$x]   = $valreceita;
  $pdf1->arraycodtipo[$x]       = $codtipo;
}

$pdf1->descr4_1  = $historico;
$pdf1->historicoparcela = $historico;
$pdf1->prehistoricoparcela = $historico;
$pdf1->descr4_2  = ""; // historico - linha 1
$pdf1->predescr4_2  = ""; // historico - linha 1
$pdf1->descr16_1 = "";
$pdf1->descr16_2 = "";
$pdf1->descr16_3 = ""; //
$pdf1->predescr16_1 = "";
$pdf1->predescr16_2 = "";
$pdf1->predescr16_3 = ""; //
$pdf1->descr12_2 = ""; //
$pdf1->linha_digitavel = $linhadigitavel;
$pdf1->codigo_barras   = $codigobarras;
$pdf1->descr6 = $datavencimento;  // Data de Vencimento
$pdf1->descr7 = db_formatar(@$valor_parm,'f');  // qtd de URM ou valor
//$pdf1->descr9 = $k03_numpre."001"; // cod. de arrecadação
$pdf1->descr9 = str_pad($k03_numpre."000",11,0,STR_PAD_LEFT); // cod. de arrecadação


$pdf1->predescr6 = $datavencimento;  // Data de Vencimento
$pdf1->predescr7 = db_formatar(@$valor_parm,'f');  // qtd de URM ou valor
$pdf1->predescr9 = str_pad($k03_numpre."000",11,0,STR_PAD_LEFT); // cod. de arrecadação
/***************************************************************************************************************************************/
$rsMsgcarne = db_query("select k03_msgbanco from numpref where k03_anousu = ".db_getsession('DB_anousu'));

$iNumrows   = pg_numrows($rsMsgcarne);
if($iNumrows > 0){
  db_fieldsmemory($rsMsgcarne,0);
}else{
  $k03_msgbanco = '';
}
$pdf1->descr16_1    = substr($k03_msgbanco, 0, 50);
$pdf1->descr16_2    = substr($k03_msgbanco, 50, 50);
$pdf1->descr16_3    = substr($k03_msgbanco, 100, 50);
$pdf1->predescr16_1 = substr($k03_msgbanco, 0, 50);
$pdf1->predescr16_2 = substr($k03_msgbanco, 50, 50);
$pdf1->predescr16_3 = substr($k03_msgbanco, 100, 50);

$pdf1->descr11_1    = trim(pg_result($Identificacao,0,"z01_numcgm")) . "-" . trim(pg_result($Identificacao,0,"z01_nome"));
$pdf1->descr11_2    = trim(pg_result($Identificacao,0,"z01_ender"));
$pdf1->descr11_2   .= trim((pg_result($Identificacao, 0, "z01_numero") == "" ? "" : ', '.pg_result($Identificacao, 0, "z01_numero").' '.pg_result($Identificacao, 0, "z01_compl")));
$pdf1->descr11_3    = trim(pg_result($Identificacao,0,"z01_munic"));
$pdf1->bairrocontri = trim(pg_result($Identificacao,0,"z01_bairro"));

$pdf1->cep          = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->uf           = trim(pg_result($Identificacao,0,"z01_uf"));

$pdf1->tipoinscr    = $tipoidentificacao;
$pdf1->nrinscr      = $numero;
if(!empty($HTTP_POST_VARS["ver_matric"]) || $matricularecibo > 0 ) {
  $pdf1->nrinscr .= " - BQL: $sql";
}

$sqlmensagemdesconto = "select distinct
                               k99_desconto,
                               k40_descr
                          from db_reciboweb
                         inner join cadtipoparc on cadtipoparc.k40_codigo = k99_desconto
                         where k99_numpre_n = $k03_numpre";
$resultmensagemdesconto = db_query($sqlmensagemdesconto) or die($sqlmensagemdesconto);
$k00_mensagemdesconto = "\n";
$k00_mensagemdesconto .= "DESCONTO CONCEDIDO REFERENTE ";
$temdesconto = false;
for ($mensdesc=0; $mensdesc < pg_numrows($resultmensagemdesconto); $mensdesc++) {
  db_fieldsmemory($resultmensagemdesconto, $mensdesc);
  $descrlei = split("#",$k40_descr);
  $k00_mensagemdesconto .= $descrlei[0] . ($mensdesc == pg_numrows($resultmensagemdesconto)?"":"-");
  $temdesconto = true;
}

if ($temdesconto == false) {
  $k00_mensagemdesconto = "";
}

$pdf1->sMensagemCaixa        = $k00_msgrecibo;
$pdf1->sMensagemContribuinte = $k00_msgrecibo;

$pdf1->descr12_1  = "\n".$historico."\n".$k00_mensagemdesconto."\n".$pdf1->sMensagemContribuinte;
$pdf1->pqllocal   = $sPQLLocal;
$pdf1->descr14    = $datavencimento; // vencimento
$pdf1->descr10    = "1 / 1";
$pdf1->data_processamento= date('d/m/Y',db_getsession('DB_datausu'));
$pdf1->tipo_exerc = $tipo_debito." / ".$exerc;
$pdf1->especie    = "R$";
$pdf1->dtparapag  = $datavencimento; //date('d/m/Y',db_getsession('DB_datausu'));
$pdf1->loteamento = $loteador;

// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco
if($oRegraEmissao->isCobranca()){

  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco           = db_utils::fieldsMemory($rsConsultaBanco,0);
  $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $pdf1->banco      = $oBanco->db90_abrev;

  try{
    $pdf1->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
  }

}
//#############################################################

/**
 * Verifica se é um objeto db_impcarne
 */
if ( !is_a($pdf1, "db_impcarne") ){

  $sMensagemErro = "Erro ao gerar recibo. Contate suporte! Não possui modelo do documento para emissão do boleto selecionado.";
  db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMensagemErro);
}

$pdf1->imprime();
$pdf1->objpdf->output();

function recibodesconto($numpre, $numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros) {

  // desconto
  global $k00_dtvenc, $k40_codigo, $k40_todasmarc, $cadtipoparc;

  $cadtipoparc = 0;

  $sqlvenc = "select k00_dtvenc
                from arrecad
               where k00_numpre = $numpre
                 and k00_numpar = $numpar";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);
  if (pg_numrows($resultvenc) == 0) {
    return 0;
  }
  db_fieldsmemory($resultvenc, 0);

  $sqltipoparc = "select k40_codigo,
                         k40_todasmarc,
                         cadtipoparc
                    from tipoparc
                   inner join cadtipoparc    on cadtipoparc = k40_codigo
                   inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
                   where maxparc = 1
                     and '" . date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini
                     and '" . date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim
                     and k41_arretipo = $tipo $whereloteador
                     and '$k00_dtvenc' >= k41_vencini
                     and '$k00_dtvenc' <= k41_vencfim ";
  $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
  if (pg_numrows($resulttipoparc) > 0) {
    db_fieldsmemory($resulttipoparc,0);
  } else {
    $sqltipoparc = "select k40_codigo,
                           k40_todasmarc,
                           cadtipoparc
                      from tipoparc
                     inner join cadtipoparc on cadtipoparc = k40_codigo
                     inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
                     where maxparc = 1
                       and k41_arretipo = $tipo
                       and '" . date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini
                       and '" . date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim
                       $whereloteador
                       and '$k00_dtvenc' >= k41_vencini
                       and '$k00_dtvenc' <= k41_vencfim ";
    $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
    if (pg_numrows($resulttipoparc) == 1) {
      db_fieldsmemory($resulttipoparc,0);
    } else {
      $k40_todasmarc = false;
    }
  }

  $sqltipoparcdeb = "select * from cadtipoparcdeb limit 1";
  $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
  $passar = false;
  if (pg_numrows($resulttipoparcdeb) == 0) {
    $passar = true;
  } else {
    $sqltipoparcdeb = "select k40_codigo, k40_todasmarc
                       from cadtipoparcdeb
                       inner join cadtipoparc on k40_codigo = k41_cadtipoparc
                       where k41_cadtipoparc = $cadtipoparc and
                       k41_arretipo = $tipo_debito $whereloteador and
                       '$k00_dtvenc' >= k41_vencini and
                       '$k00_dtvenc' <= k41_vencfim ";
    $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
    if (pg_numrows($resulttipoparcdeb) > 0) {
      $passar = true;
    }
  }

  if (pg_numrows($resulttipoparc) == 0 or ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) or $passar == false) {
    $desconto = 0;
  } else {
    $desconto = $k40_codigo;
  }

  return $desconto;

}

function MensagemCarne($exerc, $arretipo, $dtbase, $matric, $arrematric="w_arrematric as arrematric") {
  $mensagem = "";

  // Verifica Debitos Vencidos
  $sql  = "  select arrecad.k00_tipo, ";
  $sql .= "         arretipo.k03_tipo, ";
  $sql .= "         count(distinct arrecad.k00_numpar) as qtdatraso ";
  $sql .= "    from arrecad ";
  $sql .= "         inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
  $sql .= "   where arrecad.k00_numpre in (select k00_numpre from {$arrematric}) ";
  $sql .= "     and arrecad.k00_dtvenc < '{$dtbase}' ";
  $sql .= "group by arrecad.k00_tipo, ";
  $sql .= "         arretipo.k03_tipo ";
  $sql .= "order by 3 desc "; // ordena pela qtd parcelas em atraso em ordem descendente

  //die($sql);
  $resDebitos = db_query($sql);
  $rowsDebitos = pg_numrows($resDebitos);
  for($iDeb=0; $iDeb<$rowsDebitos; $iDeb++) {
    $oDebito = db_utils::fieldsmemory($resDebitos, $iDeb);

    if($oDebito->qtdatraso >= 2) {
      // se tem duas ou mais parcelas em aberto...
      $mensagem = "AVISO DE SUSPENSÃO DO FORNECIMENTO DE ÁGUA: Fica o usuário avisado que a não regularização dos débitos do imóvel no prazo de 30 (trinta) dias, a contar do vencimento da segunda parcela em atraso, acarretará na suspensão do fornecimento de água (art. 40, V, §2º da Lei n.º 11.445/07).";
      break;
    } else {

      // Verifica o CADTIPO
      switch ($oDebito->k03_tipo) {
        // Divida Ativa
        case 5:
        case 18:
          if(empty($mensagem)) {
            $mensagem = "Imovel possui Divida Ativa";
          } else {
            $mensagem .= " / Divida Ativa";
          }
          break;

        // Parcelamento
        case 6:
        case 13:
          if(empty($mensagem)) {
            $mensagem = "Imovel possui Parcelamento em Atraso";
          } else {
            $mensagem .= " / Parcelamento em Atraso";
          }
          break;

        // Saneamento Básico (Agua Exercicio)
        case 20:
          if(empty($mensagem)) {
            $mensagem = "Imovel possui Debito no Exercicio";
          } else {
            $mensagem .= " / Debito no Exercicio ";
          }
          break;

        // Outros Débitos
        default:
          if(empty($mensagem)) {
            $mensagem = "Imovel possui Outros Debitos em Atraso";
          } else {
            $mensagem .= " / Outros Debitos em Atraso ";
          }
          break;
      }

    }

  }

  if(empty($mensagem)) {
    // se nao tem nada em aberto
    $mensagem = "Obrigado pela pontualidade!!!";
  }

  db_query("set enable_bitmapscan to off");

  return $mensagem;

}

?>
