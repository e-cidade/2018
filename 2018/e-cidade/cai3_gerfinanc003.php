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

/**
 * @version $Revision: 1.234 $
 */
$lNovaEmissao   = false;
if(isset($_GET['sessao'])){
  $lNovaEmissao   = true;
  require_once(modification("fpdf151/scpdf.php"));
  require_once(modification("fpdf151/impcarne.php"));
  require_once(modification("libs/db_sql.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("ext/php/adodb-time.inc.php"));
  require_once(modification("model/regraEmissao.model.php"));
  require_once(modification("model/convenio.model.php"));
  require_once(modification("model/recibo.model.php"));
  require_once(modification("classes/db_db_bancos_classe.php"));
  require_once(modification("libs/db_barras.php"));
  require_once(modification("classes/db_db_bancos_classe.php"));
  require_once(modification("dbforms/db_funcoes.php"));

  $aDados      = $_SESSION[$_GET['sessao']];

  if(isset($_SESSION["DB_datausu"])){
    $DB_DATACALC = adodb_mktime(0,0,0,date("m",db_getsession("DB_datausu")),date("d",db_getsession("DB_datausu")),date("Y",db_getsession("DB_datausu")));
  }
} else {
  $aDados      = $_POST;

  require_once(modification("fpdf151/impcarne.php"));
  require_once(modification("libs/db_barras.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("classes/db_db_bancos_classe.php"));
  require_once(modification("model/regraEmissao.model.php"));
  require_once(modification("model/convenio.model.php"));
  require_once(modification("model/recibo.model.php"));

}

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_GET_VARS);
db_postmemory($aDados);
$oPost = db_utils::postMemory($aDados);
$oGet  = db_utils::postMemory($_GET);
$cldb_bancos = new cl_db_bancos;


if (isset($oPost->H_DATAUSU)) {
  $sDataVenc = date("Y-m-d",$oPost->H_DATAUSU);
}

// recibo
$matricularecibo  = @$j01_matric;
$inscricaorecibo  = @$q02_inscr;
$numcgmrecibo     = @$z01_numcgm;
$exerc            = '';
$aProcessoForo    = array();
$aCodProcessoForo = array();
$iModeloRecibo    =  $aDados["iModeloRecibo"];
if (isset($processarDescontoRecibo)) {
  (bool)$processarDescontoRecibo = $processarDescontoRecibo;
} else {
  (bool)$processarDescontoRecibo = true;
}

if (isset($oPost->iParcIni, $oPost->iParcFim)) {
  $iParcelaIni = $oPost->iParcIni;
  $iParcelaFim = $oPost->iParcFim;
} else {
  $iParcelaIni = null;
  $iParcelaFim = null;
}

try {
  $oRegraEmissao = new regraEmissao($tipo_debito,$iModeloRecibo,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'),true,false,$iParcelaIni,$iParcelaFim);
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro=[1] - {$eExeption->getMessage()}");
  exit;
}

try {
  $oRecibo = new recibo(2, null, 1);
} catch ( Exception $eException ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=[2] - {$eException->getMessage()}");
  exit;
}

$tipoidentificacao = 0;

$naopassa = 'f';
$sqluf    = "select db21_codcli,
                    db12_uf,
                    db12_extenso 
               from db_config  
              inner join db_uf on db_uf.db12_uf = db_config.uf  
              where codigo = ".db_getsession("DB_instit");
$resultuf = db_query($sqluf);
db_fieldsmemory($resultuf,0);

if (!isset($emite_recibo_protocolo) and !isset($reemite_recibo)) {

  db_inicio_transacao();

  if (isset($k03_numnov) && $k03_numnov != null){

    /**
     *  Na tarefa 29472 foi alterado a forma de emissão de recibo passando a utilizar o model recibo.model.php
     *  porém foi detectado que essa variável (k03_numnov) substitui o valor do numpre gerado pelo model.
     *  Como não foi encontrado o fonte que faz o envio dessa variável colocamos o redirecionamento de erro para ser
     *  identificado  algum caso, encontrando assim a rotina que faz o envio dessa variável.
     */
    //db_fim_transacao(true);
    //db_redireciona("db_erros.php?fechar=true&db_erro=Erro ao emitir recibo. Contate o suporte!");
    //exit;
    $oRecibo->setNumnov($k03_numnov);
    //$k03_numpre = $k03_numnov;

  }

  //pega os numpres da ca3_gerfinanc002.php, separa e insere em db_reciboweb
  $result = db_query("select k00_codbco,k00_codage,k00_descr,k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,k00_hist6,k00_hist7,k00_hist8,k03_tipo,k00_tipoagrup from arretipo where k00_tipo = $tipo");

  if(pg_numrows($result)==0){
    db_fim_transacao(true);
    echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo.";
    exit;
  }
  db_fieldsmemory($result,0);

  $k00_descr = $k00_descr;
  $historico = $k00_descr;

  $vt        = $aDados;
  $desconto  = 0;



  if(!isset($numpre_unica) || $numpre_unica =="") {

    $tam = sizeof($vt);
    reset($vt);
    $numpres = "";
    $meses= array();
    $arretipos = array();
    $aParcelasSemInflatores = array();

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

            $sSqlInflatores  = " select distinct   ";
            $sSqlInflatores .= "        k00_numpar, ";
            $sSqlInflatores .= "        (substr(fc_calcula,15,13)::float8+  ";
            $sSqlInflatores .= "         substr(fc_calcula,28,13)::float8+  ";
            $sSqlInflatores .= "         substr(fc_calcula,41,13)::float8-  ";
            $sSqlInflatores .= "         substr(fc_calcula,54,13)::float8) as total,  ";
            $sSqlInflatores .= "         substr(fc_calcula,77,17)::float8 as qinfla,  ";
            $sSqlInflatores .= "         substr(fc_calcula,94,4)::varchar(5) as ninfla  ";
            $sSqlInflatores .= "   from (select arrecad.k00_numpre,  ";
            $sSqlInflatores .= "                k00_numpar,  ";
            $sSqlInflatores .= "                k00_receit,  ";
            $sSqlInflatores .= "                fc_calcula(arrecad.k00_numpre,  ";
            $sSqlInflatores .= "                           k00_numpar,  ";
            $sSqlInflatores .= "                           k00_receit,  ";
            $sSqlInflatores .= "                           '".db_vencimento($DB_DATACALC)."',   ";
            $sSqlInflatores .= "                           '".db_vencimento($DB_DATACALC)."',   ";
            $sSqlInflatores .= "                           ".db_getsession('DB_anousu').")  ";
            $sSqlInflatores .= "           from arrecad  ";
            $sSqlInflatores .= "                inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre   ";
            $sSqlInflatores .= "                                     and arreinstit.k00_instit = ".db_getsession('DB_instit') ;
            $sSqlInflatores .= "          where arrecad.k00_numpre = {$resultado[0]} ";
            $sSqlInflatores .= "            and arrecad.k00_numpar = {$numpar[0]} ) as x ";

            $rsDadosPagamento = db_query( $sSqlInflatores ) or die ($sSqlInflatores) ;

            db_fieldsmemory($rsDadosPagamento, 0);

            if ( $total < 0 ) {
              array_push($aParcelasSemInflatores,$k00_numpar);
            }

            $sqlagrupa = "select distinct k00_descr as descrarretipo,
                                 extract (months from k00_dtvenc) as mesagrupa, 
                                 extract (year from k00_dtvenc) as anoagrupa 
                            from arrecad 
                                 inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo 
                           where k00_numpre = " . $resultado[0] . " 
                             and k00_numpar = " . $numpar[0];
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

    if (count($aParcelasSemInflatores) > 0 ) {
      $sS = ( count($aParcelasSemInflatores)>1?'s':'' );
      db_fim_transacao(true);
      db_redireciona("db_erros.php?fechar=true&db_erro=[3] - Valor negativo na{$sS} parcela{$sS} ".implode(",",$aParcelasSemInflatores)." verifique.");
      exit;
    }


    if(!empty($aDados["ver_matric"])) {
      $inner = "arrematric ";
      $campoinner = "k00_matric = $ver_matric";
    } elseif (!empty($aDados["ver_inscr"])) {
      $inner = "arreinscr ";
      $campoinner = "k00_inscr = $ver_inscr";
    } elseif (!empty($aDados["ver_numcgm"])) {
      $inner = "arrenumcgm ";
      $campoinner = "k00_numcgm = $ver_numcgm";
    }

    if ($k00_tipoagrup == 2) {

      for ($mes=0; $mes < sizeof($meses); $mes++) {

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
                                    and arrecad.k00_tipo   <> {$tipo_debito}
                                    and extract (months from arrecad.k00_dtvenc) = " . substr($meses[$mes],0,2) . "
                                    and extract (years  from arrecad.k00_dtvenc) = " . substr($meses[$mes],2,4) . "

           where not exists (select arrenaoagrupa.k00_numpre
                               from arrenaoagrupa
                              where arrenaoagrupa.k00_numpre = {$inner}.k00_numpre) ";

        $resultagrupa = db_query($sqlagrupa);

        for ($agrupa=0; $agrupa<pg_numrows($resultagrupa);$agrupa++) {
          db_fieldsmemory($resultagrupa,$agrupa);
          $numpres .= "N" . $numpreagrupa . "P" . $numparagrupa;
        }

      }

    }

    if (isset($oPost->marcarvencidas) && isset($oPost->marcartodas)) {

      if ($oPost->marcarvencidas == 'true' && $oPost->marcartodas == 'false') {

        $aNumpres   = split("N",$numpres);
        $numpres   = "";
        $sNumPreAnt = "";
        $sAuxiliar  = "";
        for ($iInd = 0; $iInd < count($aNumpres); $iInd++) {

          if ($aNumpres[$iInd] == "") {
            continue;
          }

          $iNumpre = split("P",$aNumpres[$iInd]);
          $iNumpar = split("P", strstr($aNumpres[$iInd],"P"));
          $iNumpar = split("R",$iNumpar[1]);
          $iReceit = $iNumpar[1];
          $iNumpar = $iNumpar[0];
          $iNumpre = $iNumpre[0];

          $sSqlArrecad  = "  select *                               ";
          $sSqlArrecad .= "    from arrecad                         ";
          $sSqlArrecad .= "   where k00_numpre   = {$iNumpre}       ";
          $sSqlArrecad .= "     and k00_numpar   = {$iNumpar}       ";
          $sSqlArrecad .= "     and k00_dtvenc   > '{$sDataVenc}'   ";
          $rsSqlArrecad = db_query($sSqlArrecad);
          $iNumRows     = pg_num_rows($rsSqlArrecad);
          if ($iNumRows == 0) {

            if ($aDados["tipo_debito"] == 3 || $aDados["tipo_debito"] == 5) {

              if (empty($sNumPreAnt) || $sNumPreAnt != $iNumpre) {

                $sNumPreAnt = $iNumpre;
                $sAuxiliar  = "N";
              }

              $numpres .= "{$sAuxiliar}N".$iNumpre."P".$iNumpar."R".$iReceit;
              $sAuxiliar = "";
            } else {
              $numpres .= 'N'.$iNumpre."P".$iNumpar."R".$iReceit;
            }
          }

        }
      }

    }

    $numpres   = split("N",$numpres);

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

      $sqlloteador  = "  select *                                                                   ";
      $sqlloteador .= "    from loteam                                                              ";
      $sqlloteador .= "         left join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam   ";
      $sqlloteador .= "   where j120_cgm = {$numcgm}                                                ";

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
    $iNumpreAnt    = 0;
    $iNumparAnt    = 0;
    $aDebitosRecibo = array();

    for($ii = 1;$ii < sizeof($numpres);$ii++) {



      if ($numpres[$ii] == "") {
        continue;
      }

      $valores = split("P",$numpres[$ii]);


      if (isset($inicial)) {

        $sSqlinicial  = " select distinct arrecad.k00_numpre,                                        ";
        $sSqlinicial .= "        arrecad.k00_numpar                                                  ";
        $sSqlinicial .= "   from inicialnumpre                                                       ";
        $sSqlinicial .= "        inner join arrecad on arrecad.k00_numpre = inicialnumpre.v59_numpre ";
        $sSqlinicial .= "  where v59_inicial = ".$numpres[$ii];

        $resultinicial = db_query($sSqlinicial);

        for ($xinicial=0;$xinicial<pg_numrows($resultinicial);$xinicial++){

          db_fieldsmemory($resultinicial,$xinicial);

          if ($processarDescontoRecibo == 'true') {
            $desconto = recibodesconto($k00_numpre, $k00_numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros, $ver_matric);
          }else{
            $desconto = 0;
          }

          if ( in_array(array($k00_numpre,$k00_numpar),$aDebitosRecibo) ) {
            continue;
          }

          $aDebitosRecibo[] = array($k00_numpre,$k00_numpar);

          try {
            $oRecibo->addNumpre($k00_numpre,$k00_numpar);
            $oRecibo->setDescontoReciboWeb($k00_numpre,$k00_numpar,$desconto);
          } catch ( Exception $eException ) {
            db_fim_transacao(true);
            db_redireciona("db_erros.php?fechar=true&db_erro=[4] - {$eException->getMessage()}");
            exit;
          }

        }

      } else {

        $numpar = split("R", $valores[1]);

        if ( in_array(array($valores[0],$numpar[0]),$aDebitosRecibo) ) {
          continue;
        }

        $aDebitosRecibo[] = array($valores[0],$numpar[0]);

        if ($processarDescontoRecibo == 'true') {
          $desconto = recibodesconto($valores[0], $numpar[0], $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros, $ver_matric);
        }else{
          $desconto = 0;
        }

        try {
          $oRecibo->addNumpre($valores[0],$numpar[0]);
          $oRecibo->setDescontoReciboWeb($valores[0],$numpar[0],$desconto);
        } catch ( Exception $eException ) {
          db_fim_transacao(true);
          db_redireciona("db_erros.php?fechar=true&db_erro=[5] - {$eException->getMessage()}");
          exit;
        }

      }

    }

  } else {

    try {
      $oRecibo->addNumpre($numpre_unica,0);
    } catch ( Exception $eException ) {
      db_fim_transacao(true);
      db_redireciona("db_erros.php?fechar=true&db_erro=[6] - {$eException->getMessage()}");
      exit;
    }

  }

  $aDebitosRecibo = $oRecibo->getDebitosRecibo();


  /* REGRAS PARA DATA DE CALCULO */

  $minvenc = "";

  if(isset($oGet->forcarvencimento) && $oGet->forcarvencimento == 'true'){

    $minvenc = date("Y-m-d",$DB_DATACALC);
    $exerc   = substr($minvenc,0,4);

  } else {

    foreach ( $aDebitosRecibo as $oDebito ) {

      $sSqlVenc  = " select min(k00_dtvenc) as k00_dtvenc      ";
      $sSqlVenc .= "    from arrecad                           ";
      $sSqlVenc .= " where k00_numpre = {$oDebito->k00_numpre} ";
      $sSqlVenc .= "   and k00_numpar = {$oDebito->k00_numpar} ";

      $rsVencimento = db_query($sSqlVenc);
      $dtDataVenc   = db_utils::fieldsMemory($rsVencimento,0)->k00_dtvenc;

      if ( $dtDataVenc < $minvenc or $minvenc == "" ) {
        $minvenc = $dtDataVenc;
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

  try {

    $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
    $oRecibo->setDataRecibo($minvenc);
    $oRecibo->setDataVencimentoRecibo($minvenc);
    $oRecibo->setExercicioRecibo(substr($minvenc,0,4));
    $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());
    $oRecibo->emiteRecibo($lConvenioCobrancaValido);
    if ($lConvenioCobrancaValido && !CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
      CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
    }
    $k03_numpre = $oRecibo->getNumpreRecibo();

  } catch ( Exception $eException ) {

    db_fim_transacao(true);
    db_redireciona("db_erros.php?fechar=true&db_erro=[7] - {$eException->getMessage()}");
    exit;

  }

} else {

  //Apenas reemisão do recibo
  //pega os numpres da ca3_gerfinanc002.php, separa e insere em db_reciboweb
  $sSqlArreTipo = "select k00_codbco,
                          k00_codage,
                          k00_descr,
                          k00_hist1,
                          k00_hist2,
                          k00_hist3,
                          k00_hist4,
                          k00_hist5,
                          k00_hist6,
                          k00_hist7,
                          k00_hist8,
                          k03_tipo,
                          k00_tipoagrup 
                     from arretipo 
                    where k00_tipo = {$tipo}";
  $result = db_query($sSqlArreTipo);

  $loteador = false;

  if (isset($numcgm) and !isset($matric)) {

    $sqlloteador  = "  select *                                                                   ";
    $sqlloteador .= "    from loteam                                                              ";
    $sqlloteador .= "         left join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam   ";
    $sqlloteador .= "   where j120_cgm = {$numcgm}                                                ";

    $resultloteador = db_query($sqlloteador) or die($sqlloteador);
    if (pg_numrows($resultloteador) > 0) {
      $loteador = true;
    }

  }

  $whereloteador = " and k40_forma <> 3";

  if ($loteador == true) {
    $whereloteador = " and k40_forma = 3";
  }

  if(pg_numrows($result)==0){
    db_fim_transacao(true);
    echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo.";
    exit;
  }
  db_fieldsmemory($result,0);

  $k00_descr = $k00_descr;

  $historico = $k00_descr;


  //Gerandp data minima de vencimento
  $sqlrecibo = "select * from db_reciboweb where k99_numpre_n = $k03_numpre";
  $resultrecibo = db_query($sqlrecibo) or die($sqlrecibo);
  $minvenc = "";
  if (isset($oGet->forcarvencimento) && $oGet->forcarvencimento == 'true') {
    if (date("Y-m-d",$DB_DATACALC) > db_getsession('DB_anousu').'-12-31') {
      $minvenc = db_getsession('DB_anousu').'-12-31';
    } else {
      $minvenc = date("Y-m-d",$DB_DATACALC);
    }
    if(isset($oGet->db_datausu) && $oGet->db_datausu > $minvenc){
      $minvenc = $oGet->db_datausu;
    }
  } else {

    for ($conta=0; $conta < pg_numrows($resultrecibo); $conta++) {

      $sqlvenc  = " select min(k00_dtvenc) as k00_dtvenc ";
      $sqlvenc .= "   from arrecad                       ";
      $sqlvenc .= "  where k00_numpre = " . pg_result($resultrecibo,$conta,"k99_numpre");
      $sqlvenc .= "    and k00_numpar = " . pg_result($resultrecibo,$conta,"k99_numpar");
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
    /*  regra a verificar
     if ($minvenc > date("Y",$DB_DATACALC)."-12-31"){
     $minvenc = date("Y",$DB_DATACALC)."-12-31";
     }*/

    /* se menor vencimento do numpre for maior que 31-12 do ano corrente menor vencimento = 31-12 do ano corrente */
    if ($minvenc > db_getsession('DB_anousu')."-12-31") {
      $minvenc = db_getsession('DB_anousu')."-12-31";
    }

  }
  db_postmemory($HTTP_SERVER_VARS);
  db_postmemory($HTTP_GET_VARS);

  if (isset($db_datausu)) {
    if (!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))) {
      echo "Data para Cálculo Inválida. <br><br>";
      echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
      exit;
    }

    if (mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) < mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu"))) ) {
      echo "Data não permitida para cálculo. <br><br>";
      echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
      exit;
    }

    $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));

  } else {
    $DB_DATACALC = db_getsession("DB_datausu");
  }

  if (isset($k00_histtxt)) {
    $k00_descr = $k00_histtxt;
  }

}



//seleciona os valores gerado pela funcao fc_recibo

if(!isset($emite_recibo_protocolo)){

  if($k03_tipo == 20) {

    $sCampoNull = ", null as k00_histtxt ";
    $sWhere1    = " r.k00_valor >= 0 ";
    $sWhere2    = " r.k00_valor <  0 ";
    $sCampoJoin = ", a.k00_histtxt as k00_histtxt ";
    $sJoinHist  = " left join arrehist a on a.k00_numpre = r.k00_numpre and a.k00_numpar = r.k00_numpar and a.k00_hist = 918";
    $sGroupBy   = ", k00_histtxt";

  } else {

    $sCampoNull = "";
    $sWhere1    = " r.k00_hist  <> 918";
    $sWhere2    = " r.k00_hist  =  918";
    $sCampoJoin = "";
    $sJoinHist  = "";
    $sGroupBy   = "";

  }

  $sql  = "select * from (  select r.k00_numcgm,
                                   r.k00_receit,
                                   null as k00_hist,  
                                   case when taborc.k02_codigo is null 
                                        then tabplan.k02_reduz 
                                        else taborc.k02_codrec 
                                   end as codreduz,
                                   t.k02_descr,
                                   t.k02_drecei,
                                   r.k00_dtpaga as k00_dtpaga,
                                   sum(r.k00_valor) as valor,
                                   (select (select k02_codigo 
                                             from tabrec 
                                            where k02_recjur = r.k00_receit 
                                               or k02_recmul = r.k00_receit limit 1) 
                                          is not null ) as codtipo
                                          {$sCampoNull}
                             from recibopaga r
                                  inner join tabrec t on t.k02_codigo = r.k00_receit 
                                  inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
                                   left outer join taborc  on t.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".db_getsession("DB_anousu")."
                                   left outer join tabplan  on t.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
                            where r.k00_numnov = ".$k03_numpre."
                              and {$sWhere1}
                            group by r.k00_dtpaga,
                                     r.k00_receit,
                                     t.k02_descr,
                                     t.k02_drecei,
                                     r.k00_numcgm,
                                     codreduz
                                     
                          union
                           
                            select r.k00_numcgm,
                                   r.k00_receit,
                                   r.k00_hist,  
                                   case when taborc.k02_codigo is null 
                                        then tabplan.k02_reduz 
                                        else taborc.k02_codrec 
                                    end as codreduz,
                                   t.k02_descr,
                                   t.k02_drecei,
                                   r.k00_dtpaga as k00_dtpaga,
                                   sum(r.k00_valor) as valor,
                                   (select (select k02_codigo 
                                             from tabrec 
                                            where k02_recjur = r.k00_receit 
                                               or k02_recmul = r.k00_receit limit 1) 
                                          is not null ) as codtipo
                                          {$sCampoJoin}
                              from recibopaga r
                                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
                                    left outer join taborc   on t.k02_codigo = taborc.k02_codigo  and taborc.k02_anousu = ".db_getsession("DB_anousu")."
                                    left outer join tabplan  on t.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
                                   {$sJoinHist}
                             where r.k00_numnov = ".$k03_numpre."
                               and {$sWhere2}
                             group by r.k00_dtpaga,
                                      r.k00_receit,
                                      r.k00_hist,
                                      t.k02_descr,
                                      t.k02_drecei,
                                      r.k00_numcgm,
                                      codreduz
                                      {$sGroupBy}) as x order by k00_receit, valor desc";
} else {
  $sql = "select r.k00_numcgm,
                 r.k00_receit,
                 r.k00_hist,
                 case when taborc.k02_codigo is null 
                       then tabplan.k02_reduz 
                       else taborc.k02_codrec 
                 end as codreduz,
                 t.k02_descr,
                 t.k02_drecei,
                 r.k00_dtpaga as k00_dtpaga,
                 sum(r.k00_valor) as valor,
                 (select (select k02_codigo 
                            from tabrec 
                           where k02_recjur = r.k00_receit 
                              or k02_recmul = r.k00_receit limit 1) is not null ) as codtipo
                    from recibo r
                         inner join tabrec t on t.k02_codigo = r.k00_receit 
                         inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
                   where r.k00_numpre = ".$k03_numpre."
            left outer join taborc   on t.k02_codigo = taborc.k02_codigo 
                                    and taborc.k02_anousu = ".db_getsession("DB_anousu")."
            left outer join tabplan  on t.k02_codigo = tabplan.k02_codigo 
                                    and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
           group by r.k00_dtpaga,
                    r.k00_receit,
                    r.k00_hist,
                    t.k02_descr,
                    t.k02_drecei,
                    r.k00_numcgm,
                    codreduz";
}

$DadosPagamento = db_query($sql) or die($sql);
//faz um somatorio do valor

if (pg_numrows($DadosPagamento) == 0) {
  echo "problemas ao gerar recibo! Contate suporte";
  exit;
}

$datavencimento = pg_result($DadosPagamento,0,"k00_dtpaga");
$total_recibo = 0;
$sHistoricoDesconto = '';
$iMostra = 0;
for($i = 0;$i < pg_numrows($DadosPagamento);$i++) {
  $total_recibo += pg_result($DadosPagamento,$i,"valor");

  if(($k03_tipo == 20) and
    (pg_result($DadosPagamento,$i,"k00_histtxt") <> '') and
    (pg_result($DadosPagamento,$i,"valor") < 0) and
    (pg_result($DadosPagamento,$i,"k00_receit") == '401002') and
    ($iMostra == 0)) {
    $sHistoricoDesconto .= pg_result($DadosPagamento,$i,"k00_histtxt");
    $sHistoricoDesconto .= '=>RECEITA:'.pg_result($DadosPagamento,$i,"k00_receit");
    $iMostra = 1;
  }

}


$sqldtop =" select min(arrecad.k00_dtoper) as mindatop ,
                   case 
                     when arrecad.k00_tipo = 3 
                      then coalesce( sum(issvar.q05_vlrinf),0)
                      else coalesce( sum(arrecad.k00_valor) ,0)
                   end as valor_origem
              from recibopaga
             inner join arrecad on arrecad.k00_numpre    = recibopaga.k00_numpre
                               and recibopaga.k00_numpar = arrecad.k00_numpar
                               and recibopaga.k00_receit = arrecad.k00_receit
             left join issvar   on arrecad.k00_numpre    = q05_numpre
                               and arrecad.k00_numpar    = q05_numpar
             where k00_numnov= {$k03_numpre}
             group by arrecad.k00_tipo limit 1";
$resultdtop   = db_query($sqldtop);
$mindatop     = pg_result($resultdtop,0,"mindatop");
$valor_origem = pg_result($resultdtop,0,"valor_origem");

//seleciona da tabela db_config, o numero do banco e a taxa bancaria e concatena em variavel
$sSqlDadosInstit = "select db12_uf,
                           db12_extenso,
                           nomeinst,
                           ender,
                           numero,
                           munic,
                           email,
                           telef,
                           cgc,
                           uf,
                           logo,
                           to_char(tx_banc,'99.99') as tx_banc,
                           numbanco
                      from db_config 
                     inner join db_uf on db_uf.db12_uf = db_config.uf
                     where codigo = ".db_getsession("DB_instit");
$DadosInstit = db_query($sSqlDadosInstit);
//cria codigo de barras e linha digitável

$sSqlTxBancaria = "select to_char(k00_txban,'99.99') as tx_banc 
                     from arretipo 
                    where k00_instit = ".db_getsession("DB_instit")." 
                      and k00_tipo = $tipo";
$sqlArretipo_tx_banc = db_query($sSqlTxBancaria);
$taxabancaria = pg_result($sqlArretipo_tx_banc,0,"tx_banc");
$src = pg_result($DadosInstit,0,'logo');
$db_nomeinst = pg_result($DadosInstit,0,'nomeinst');
$db_ender    = pg_result($DadosInstit,0,'ender');
$db_numero   = pg_result($DadosInstit,0,'numero');
$db_munic    = pg_result($DadosInstit,0,'munic');
$db_uf       = pg_result($DadosInstit,0,'uf');
$db_telef    = pg_result($DadosInstit,0,'telef');
$db_cgc      = pg_result($DadosInstit,0,'cgc');
$db_email    = pg_result($DadosInstit,0,'email');
$db_logo     = pg_result($DadosInstit,0,'logo');

$total_recibo += $taxabancaria;
if ( $total_recibo == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=[8] - Recibo Com Valor Zerado.');
}
$valor_parm = $total_recibo;
$pql_localizacao = '';


$sEmitirPor = '';
$iNumero = 0;


//seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
//essa variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str
if (!empty($aDados["ver_matric"]) || $matricularecibo > 0 ) {

  $sEmitirPor = 'matricula';

  $numero = @$aDados["ver_matric"] + $matricularecibo;
  $tipoidentificacao = "Matricula :";

  $sSqlIdentificacao = "select proprietario.z01_nome,
                               proprietario.z01_ender,
                               proprietario.z01_numero,
                               proprietario.z01_compl,
                               proprietario.z01_munic,
                               proprietario.z01_uf,
                               proprietario.z01_cep,
                               proprietario.nomepri,
                               proprietario.j39_compl,
                               proprietario.j39_numero,
                               proprietario.j13_descr as bairro_matricula,
                               case 
                                 when proprietario.j13_descr is not null and proprietario.j13_descr != '' 
                                   then proprietario.j13_descr 
                                   else ''
                               end as j13_descr,
                               proprietario.j34_setor||'.'||proprietario.j34_quadra||'.'||proprietario.j34_lote as sql,
                               proprietario.z01_cgccpf, 
                               proprietario.z01_bairro,
                               proprietario.z01_cgmpri as z01_numcgm,
                               proprietario.j40_refant,
                               proprietario.pql_localizacao
                          from proprietario
                         where j01_matric = $numero limit 1";
  $Identificacao = db_query($sSqlIdentificacao) or die($sSqlIdentificacao);
  $iNumero = $numero;
  if (pg_numrows($Identificacao)==0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=[9] - Problemas no Cadastro da Matricula ' . $numero);
  }

  db_fieldsmemory($Identificacao,0);

  $sPQLLocal = "PQL: {$pql_localizacao}";

  $ident_tipo_ii = 'Imóvel';

  $numero = $numero." SQL: ".$sql;

} else if(!empty($aDados["ver_inscr"]) || $inscricaorecibo > 0 ) {

  $iNumero = $numero;
  $numero = @$aDados["ver_inscr"] + $inscricaorecibo;
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

  $sqlidentificacao = "select cgm.z01_numcgm,
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

  if(pg_numrows($Identificacao)==0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=[10] - Problemas no Cadastro da Inscrição ' . $numero);
  }

  $ident_tipo_ii = 'Alvará';
  db_fieldsmemory($Identificacao,0);

} else if(!empty($aDados["ver_numcgm"]) || $numcgmrecibo > 0 ) {

  $sEmitirPor = 'cgm';
  $numero = @$aDados["ver_numcgm"] + $numcgmrecibo ;
  $tipoidentificacao = "Numcgm :";

  $iNumero = $numero;

  $sSqlIdentificacao = "select z01_numcgm,
                               z01_nome,
                               z01_ender,
                               z01_numero,
                               z01_compl,
                               z01_bairro,
                               z01_munic,
                               z01_uf,
                               z01_cep,
                               z01_ender as nomepri, 
                               z01_compl as j39_compl,
                               z01_numero as j39_numero,
                               z01_bairro as j13_descr,
                               '' as sql,
                               z01_cgccpf
                          from cgm
                         where z01_numcgm = $numero ";
  $Identificacao = db_query($sSqlIdentificacao);

  if(pg_numrows($Identificacao)==0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=[11] - Problema no Cadastro do CGM ' . $numero);
  }

  db_fieldsmemory($Identificacao,0);
  $ident_tipo_ii = '';

} else {

  if (isset($emite_recibo_protocolo)) {

    $sSqlIdentificacao = " select c.z01_bairro, 
                                  c.z01_nome,
                                  c.z01_ender,
                                  c.z01_numero,
                                  c.z01_compl,
                                  c.z01_munic,
                                  c.z01_uf,
                                  c.z01_cep,
                                  ' ' as nomepri,
                                  ' ' as j39_compl, 
                                  ' ' as j39_numero, 
                                  ' ' as j13_descr, 
                                  '' as sql,
                                  z01_cgccpf, 
                                  c.z01_numcgm
                             from recibo r
                            inner join cgm c on c.z01_numcgm = r.k00_numcgm
                            where r.k00_numpre = ".$k03_numpre." limit 1";
    $Identificacao = db_query($sSqlIdentificacao);
    if(pg_numrows($Identificacao)==0) {
      db_redireciona('db_erros.php?fechar=true&db_erro=[12] - Problema no Cadastro do Recibo do Protocolo Numpre ' . $k03_numpre);
    }
    db_fieldsmemory($Identificacao,0);

  }

}

$aEmitirPor = array();

$aEmitirPor[$sEmitirPor] = $iNumero;

// Controle de Limitação do Tamanho do Historico em 210 Caracteres
$lHistLimitado = true;

if (isset($tipo_debito)) {

  $resulttipo = db_query("select k03_tipo, k00_tipoagrup from arretipo where k00_tipo = $tipo_debito");
  db_fieldsmemory($resulttipo,0);

  // Se existir algum tipo de Debito com o Tipo de Agrupamento = 2 (agrupa)
  // entao nao limita o tamanho do Historico em 210 Caracteres
  if($k00_tipoagrup==2) {
    $lHistLimitado = false;
  }

  $sHistoricoIniciaisParcelamento = "";
  if ($k03_tipo==5 && $k00_tipoagrup<>2 ) {

    $histparcela = "Divida: ";
    $sqlhist = "select distinct
                       v01_exerc,
                       v01_numpar
                  from db_reciboweb
                       left outer join divida on v01_numpre = k99_numpre 
                                             and v01_numpar = k99_numpar
                 where k99_numpre_n = $k03_numpre 
                 group by v01_exerc,v01_numpar
                 order by v01_exerc,v01_numpar";
    $result = db_query($sqlhist);
    if (pg_numrows($result)!=false) {
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
                inner join divida on v01_numpre = k99_numpre 
                                 and v01_numpar = k99_numpar
                where k99_numpre_n = $k03_numpre";
    $result = db_query($sqlobs);
    if (pg_numrows($result) > 0) {
      $histparcela .= "\nOBS: ";
      for($xy=0;$xy<pg_numrows($result);$xy++){
        if (ltrim(rtrim(pg_result($result,$xy,0))) != "") {
          $histparcela .= ltrim(rtrim(pg_result($result,$xy,0)));
        }
      }
    }

  } else if($k03_tipo == 2 && $k00_tipoagrup<>2) {

    $histparcela = "Exercicio: ";
    $sqlhist = "select distinct 
                       q01_anousu, 
                       k99_numpar
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

  } else if($k03_tipo == 3 && $k00_tipoagrup<>2) {

    $histparcela = "Exercicio: ";
    $sqlhist = "select distinct 
                       q05_ano, 
                       q05_mes
                  from db_reciboweb
                       left outer join issvar on q05_numpre = k99_numpre 
                                             and q05_numpar = k99_numpar
                 where k99_numpre_n = $k03_numpre 
                 group by q05_ano,q05_mes
                 order by q05_ano,q05_mes";
    $result = db_query($sqlhist);
    if (pg_numrows($result)!=false) {
      $exercv = "0000";

      for ($xy=0;$xy<pg_numrows($result);$xy++) {

        if ( $exercv != pg_result($result,$xy,0)) {
          $exercv = pg_result($result,$xy,0);
          $histparcela .= "  ".pg_result($result,$xy,0).": Mês:";
        }
        $histparcela .= "-".pg_result($result,$xy,1);

        if (pg_result($result,$xy,1) != "") {
          $sqlhistor = "select distinct 
                               q05_histor
                          from db_reciboweb
                               inner join issvar on q05_numpre = k99_numpre 
                                                and q05_numpar = k99_numpar
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

  } else if ($k03_tipo==6 && $k00_tipoagrup<>2) {

    $histparcela = '';
    $parcelamento = '';
    $sqlhist = "select v07_parcel, 
                       k99_numpar
                  from db_reciboweb
                       left outer join termo on v07_numpre = k99_numpre
                 where k99_numpre_n = $k03_numpre 
                 order by v07_parcel,k99_numpar";
    $result = db_query($sqlhist);
    if(pg_numrows($result)!=false){

      for ($xy=0;$xy<pg_numrows($result);$xy++) {
        if (pg_result($result,$xy,0) != $parcelamento){
          $histparcela .= "\nParcelamento" . ($k03_tipo == 13?" do foro":"") . ': '.pg_result($result,$xy,0)." - ";
        }
        $histparcela .= pg_result($result,$xy,1).", ";
        $parcelamento = pg_result($result,$xy,0);
      }
    }

  } else if ($k03_tipo==13 && $k00_tipoagrup<>2) {

    $histparcela  = "";
    $parcelamento = "";
    $iMinParc     = "";
    $iMaxParc     = "";

    $sSqlHist = "select v07_parcel   as parcel,
                        v07_numpre   as numpre,
                        v07_totpar   as totpar,
                        k99_numpre_n as numnov,
                        k99_numpar   as numpar
                   from db_reciboweb
                        left join termo on termo.v07_numpre = db_reciboweb.k99_numpre
                  where k99_numpre_n = $k03_numpre 
                  order by v07_parcel,k99_numpar";
    $result = db_query($sSqlHist);
    if (pg_numrows($result)!=false) {

      for ($xy=0;$xy<pg_numrows($result);$xy++) {
        $oHistParcelaTermo = db_utils::fieldsMemory($result, $xy);

        if ($iMinParc > $oHistParcelaTermo->numpar || $iMinParc == "") {
          $iMinParc =  $oHistParcelaTermo->numpar;
        }

        if ($oHistParcelaTermo->parcel != $parcelamento) {
          $histparcela .= " Parcelamento do foro : {$oHistParcelaTermo->parcel}\n Parcelas:";
        }
        $histparcela .= "{$oHistParcelaTermo->numpar}/$oHistParcelaTermo->totpar, ";
        $parcelamento = $oHistParcelaTermo->parcel;
      }

    }

    $sSqlIniciaisParcelamento = "select termoini.inicial as inicial,
                                        processoforo.v70_sequencial, 
                                        processoforo.v70_codforo,    
                                        array_accum(distinct v01_exerc) as exerc    
                                   from fc_origemparcelamento({$oHistParcelaTermo->numpre}) as origemparcelamento 
                                  inner join termo               on termo.v07_parcel                = riparcel         
                                  inner join termoini            on termoini.parcel                 = riparcel         
                                  inner join inicialcert         on inicial                         = v51_inicial
                                  inner join certdiv             on v14_certid                      = v51_certidao     
                                  inner join divida              on divida.v01_coddiv               = v14_coddiv       
                                                                and divida.v01_instit               = " . db_getsession("DB_instit")."
                                  inner join proced              on proced.v03_codigo               = divida.v01_proced
                                   left join processoforoinicial on processoforoinicial.v71_inicial = termoini.inicial
                                   left join processoforo        on processoforo. v70_sequencial    = processoforoinicial.v71_processoforo                              
                                  group by termoini.inicial, processoforo.v70_codforo, processoforo.v70_sequencial";
    $rsIniciaisParcelamento = db_query($sSqlIniciaisParcelamento);
    if (pg_numrows($rsIniciaisParcelamento) > 0) {
      $sHistoricoIniciaisParcelamento = "";
      for ($xy=0; $xy < pg_num_rows($rsIniciaisParcelamento); $xy++) {
        $oDadosInicial = db_utils::fieldsmemory($rsIniciaisParcelamento, $xy);
        $aProcessoForo[]    = $oDadosInicial->v70_sequencial;
        $aCodProcessoForo[] = $oDadosInicial->v70_codforo;
        $sHistoricoIniciaisParcelamento  .= "Inicial: $oDadosInicial->inicial - Processo do Foro: {$oDadosInicial->v70_codforo} \nExercício(s): ".str_replace("{", "", str_replace("}", "", $oDadosInicial->exerc))."\n";
      }

    }

  } else if ($k03_tipo==7 && $k00_tipoagrup<>2) {

    $histparcela = "\nDiversos: ";
    $sqlhist = "select distinct 
                       dv05_exerc, 
                       k00_numpar
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

    $sqlobs = "select distinct 
                      dv05_obs
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

  } else if ($k03_tipo==18 && $k00_tipoagrup<>2) {

    $histparcela = "\n";

    $sqlhist = "select * 
                  from ( select distinct 
                                v59_inicial as inicial,
                                v70_sequencial,
                                v70_codforo,
                                case 
                                  when divida.v01_exerc is null 
                                    then 
                                      case 
                                        when divida2.v01_exerc is null 
                                          then 0 
                                          else divida2.v01_exerc 
                                      end 
                                    else divida.v01_exerc 
                                end as exerc
                           from db_reciboweb
                                inner join arrecad             on db_reciboweb.k99_numpre         = arrecad.k00_numpre 
                                                              and db_reciboweb.k99_numpar         = arrecad.k00_numpar
                                inner join inicialnumpre       on inicialnumpre.v59_numpre        = arrecad.k00_numpre
                                inner join inicialcert         on inicialcert.v51_inicial         = inicialnumpre.v59_inicial
                                 left join processoforoinicial on processoforoinicial.v71_inicial = inicialnumpre.v59_inicial 
                                 left join processoforo        on processoforo. v70_sequencial    = processoforoinicial.v71_processoforo                              
                                 left join certdiv             on certdiv.v14_certid              = inicialcert.v51_certidao
                                 left join divida              on divida.v01_coddiv               = certdiv.v14_coddiv
                                 left join certter             on certter.v14_certid              = inicialcert.v51_certidao
                                 left join termo               on termo.v07_parcel                = certter.v14_parcel
                                 left join termodiv            on termodiv.parcel                 = termo.v07_parcel
                                 left join divida divida2      on divida2.v01_coddiv              = termodiv.coddiv
                                where db_reciboweb.k99_numpre_n = {$k03_numpre}) as x
                 order by inicial, exerc";
    $result = db_query($sqlhist);
    if (pg_numrows($result)!=false) {
      $exercv = "0000";
      $sSeparador = "";
      for ($xy=0;$xy<pg_numrows($result);$xy++) {
        $oHistParcela = db_utils::fieldsMemory($result, $xy);

        $aProcessoForo[]    = $oHistParcela->v70_sequencial;
        $aCodProcessoForo[] = $oHistParcela->v70_codforo;
        if ( $exercv != $oHistParcela->inicial) {
          $exercv = $oHistParcela->inicial;
          $histparcela .= ($xy != 0 ? "\n"  : "");
          $histparcela .= "Inicial: {$oHistParcela->inicial}";
          $histparcela .= (!empty($oHistParcela->v70_codforo) ? " - Processo do Foro: {$oHistParcela->v70_codforo} " : "");
          $histparcela .= " - Exercício(s): ";
          $sSeparador = "";
        }
        $histparcela .= $sSeparador.$oHistParcela->exerc;

        $sSeparador = ", ";
      }
    }

  } else if ($k03_tipo==11 && $k00_tipoagrup<>2) {

    $sSqlDadosHist = "select y50_codauto,
                             y50_obs,
                             y50_data,
                             extract(year from arrecad.k00_dtoper) as exerc,
                             k00_descr,
                             k99_numpar                             
                        from db_reciboweb 
                             inner join autonumpre on autonumpre.y17_numpre = db_reciboweb.k99_numpre 
                             inner join auto       on auto.y50_codauto      = autonumpre.y17_codauto
                             inner join arrecad    on k99_numpre            = k00_numpre 
                                                  and k99_numpar            = k00_numpar
                             inner join arretipo   on arretipo.k00_tipo     = arrecad.k00_tipo                              
                       where db_reciboweb.k99_numpre_n = {$k03_numpre}";
    $rsDadosHist = db_query($sSqlDadosHist);
    if (@pg_numrows($rsDadosHist) > 0) {
      $oDadosAuto   = db_utils::fieldsmemory($rsDadosHist,0);
      $sObsAuto     = "";

      $aObs = split("\n",$oDadosAuto->y50_obs);
      if (count($aObs) > 3) {
        $sObsAuto = $aObs[0]."\n".$aObs[1]."\n".$aObs[2];
      } else {
        $sObsAuto = $oDadosAuto->y50_obs;
      }

      $histparcela  = $oDadosAuto->k00_descr."=>".$oDadosAuto->exerc."/P:".$oDadosAuto->k99_numpar."\n";
      $histparcela .= "Cod. Auto: ".$oDadosAuto->y50_codauto." - Obs.: ".$sObsAuto;
    }

  } else {

    $histparcela = "";
    $sqlhist =  "select * from ( 
                      select distinct 
                            arretipo.k00_tipo,
                            k00_descr,
                            k99_numpar,
                            case
                                when divida.v01_exerc is not null
                                    then divida.v01_exerc
                                when termo.v07_parcel is not null 
                                    then termo.v07_parcel
                                when aguacalc.x22_exerc is not null
                                    then aguacalc.x22_exerc
                                else extract (year from arrecad.k00_dtoper)
                            end as k00_origem
                        from db_reciboweb
                            inner join arrecad on
                                k99_numpre = k00_numpre and k99_numpar = k00_numpar
                            inner join arretipo on
                                arretipo.k00_tipo = arrecad.k00_tipo
                            left join divida on
                                divida.v01_numpre = arrecad.k00_numpre and divida.v01_numpar = arrecad.k00_numpar
                            left join termo on
                                termo.v07_numpre  = arrecad.k00_numpre
                            left join aguacalc on
                                x22_numpre = arrecad.k00_numpre 
                        where
                            k99_numpre_n = $k03_numpre
                      ) as x
                order by k00_origem, k00_descr, k99_numpar";
    $result = db_query($sqlhist) or die($sqlhist);

    $histant = pg_result($result,0,"k00_origem") . "-" . pg_result($result,0,"k00_descr");
    $histparcela .=  pg_result($result,0,"k00_descr") . "=>" . pg_result($result,0,"k00_origem") . " / P: ";

    for ($xy=0;$xy<pg_numrows($result);$xy++) {

      if (pg_result($result,$xy,"k00_origem") . "-" . pg_result($result,$xy,"k00_descr") <> $histant) {
        $histparcela .= "-" . pg_result($result,$xy,"k00_descr") . "=>" . pg_result($result,$xy,"k00_origem") . " / P: ";
        $histant = pg_result($result,$xy,"k00_origem") . "-" . pg_result($result,$xy,"k00_descr");
      }
      $histparcela .= pg_result($result,$xy,"k99_numpar") . " ";

    }

  }

  if ($lHistLimitado == true) {
    $historico = substr($histparcela,0,210);
  } else {
    $historico = $histparcela.$sHistoricoDesconto;
  }
}

//select pras observacoes
$sSqlConfMensagem = "select mens,
                            alinhamento 
                       from db_confmensagem 
                      where cod in('obsboleto1','obsboleto2','obsboleto3','obsboleto4')";
$Observacoes = db_query($conn,$sSqlConfMensagem);
$db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');


$sqlvalor = "select k00_tercdigrecnormal,
                    k00_msgrecibo 
               from arretipo 
              where k00_tipo = $tipo_debito";
db_fieldsmemory(db_query($sqlvalor),0);
if(!isset($k00_tercdigrecnormal) || $k00_tercdigrecnormal == ""){
  db_redireciona('db_erros.php?fechar=true&db_erro=[13] - Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito: ' . $tipo_debito);
}

/***************************************  CRIA O MODELO DE RECIBO  ***************************************************************/

//$pdf1 = $oRegraEmissao->getObjPdf();
//$pdf1->sMensagemContribuinte = "";
//$pdf1->sMensagemCaixa        = "";

/*********************************************************************************************************************************/
if (isset($reemite_recibo)) {
  $k03_numpre = $k03_numnov;
}

try {
  $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpre,0,$total_recibo,$db_vlrbar,$datavencimento,$k00_tercdigrecnormal);
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro=[14] - {$eExeption->getMessage()}");
  exit;
}

try {

  if (isset($reemite_recibo)) {
    $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());
  }

  if ($lConvenioCobrancaValido && CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {

    CobrancaRegistrada::registrarReciboWebservice($k03_numpre, $oRegraEmissao->getConvenio(), $total_recibo, false, $aEmitirPor);
  }
} catch(Exception $oErro) {

  db_redireciona("db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
  exit;
}

/***************************************  CRIA O MODELO DE RECIBO  ***************************************************************/

$pdf1 = $oRegraEmissao->getObjPdf();
$pdf1->sMensagemContribuinte = "";
$pdf1->sMensagemCaixa        = "";

/*********************************************************************************************************************************/

$codigobarras   = $oConvenio->getCodigoBarra();
$linhadigitavel = $oConvenio->getLinhaDigitavel();
$datavencimento = db_formatar($datavencimento,"d");

if($oRegraEmissao->isCobranca()){

  $pdf1->agencia_cedente  = $oConvenio->getAgenciaCedente();
  $pdf1->carteira         = $oConvenio->getCarteira();
  $pdf1->nosso_numero     = $oConvenio->getNossoNumero();

}

$pdf1->tipo_convenio = $oConvenio->getTipoConvenio();
$pdf1->codigoConvenio= $oRegraEmissao->getConvenio();
$numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
$numpre = $numpre . db_CalculaDV($numpre,11);

$pdf1->uf_config     = $db12_uf;
$pdf1->modelo       = 2;
//$pdf1->logo         = 'logo_boleto.png';
$pdf1->logo         = $db_logo;

if ($oRegraEmissao->getCadTipoConvenio() == 6 ) {
  $pdf1->sCedenteBoleto    = $oRegraEmissao->getNomeConvenio();
  $pdf1->sTituloInstrucoes = 'TEXTO DE RESPONSABILIDADE DO CEDENTE';
}

$pdf1->prefeitura       = $db_nomeinst;

/**
 * Quando for convênio BDL de qualquer banco, sistema deve listar no boleto de Recibo (92) e Carnê (100)
 * o nome do cedente que constar como nome no cadastro de convênio.
 * Não deve usar o nome da instituição.
 */
if ($oRegraEmissao->getCadTipoConvenio() == 1) {
    $pdf1->prefeitura = $oRegraEmissao->getNomeConvenio();
}

$pdf1->enderpref        = $db_ender;
$pdf1->numeropref       = $db_numero;
$pdf1->municpref        = $db_munic;
$pdf1->telefpref        = $db_telef;
$pdf1->cgcpref          = $db_cgc;
$pdf1->emailpref        = @$db_email;
$pdf1->nome             = trim(pg_result($Identificacao,0,"z01_numcgm")) . "-" . trim(pg_result($Identificacao,0,"z01_nome"));
$pdf1->ender            = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl")) . (strlen(trim(pg_result($Identificacao,0,"z01_bairro"))) > 0?"/":"") . trim(pg_result($Identificacao,0,"z01_bairro"));
$pdf1->munic            = trim(pg_result($Identificacao,0,"z01_munic"));
$pdf1->cep              = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->cgccpf           = trim(@pg_result($Identificacao,0,"z01_cgccpf"));
$pdf1->tipoinscr        = $tipoidentificacao;
$pdf1->nrinscr          = $numero;
$pdf1->ufcgm            = trim(@pg_result($Identificacao,0,"z01_uf"));
$pdf1->ip               = db_getsession("DB_ip");
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

if ( $tipoidentificacao == "Matricula :") {

  $pdf1->refant           = $j40_refant;
  $pdf1->pql_localizacao  = $pql_localizacao;
} else {

  $pdf1->refant          = "";
  $pdf1->pql_localizacao = "";

}

if (trim($j13_descr) != trim($z01_bairro)) {
  $pdf1->bairropri = $j13_descr; //$z01_bairro;
} else {
  $pdf1->bairropri = "";
}

$pdf1->prebairropri     = $z01_bairro; // $j13_descr;
$pdf1->bairrocontri     =  $z01_bairro; // $j13_descr;
$pdf1->dtvenc           = db_formatar($minvenc, "d");
$pdf1->datacalc         = db_formatar($minvenc, "d");
$pdf1->predatacalc      = db_formatar($minvenc, "d");
$pdf1->taxabanc         = db_formatar($taxabancaria,'f');
$pdf1->recorddadospagto = $DadosPagamento;
$pdf1->linhasdadospagto = pg_numrows($DadosPagamento);
$pdf1->receita          = 'k00_receit';
$pdf1->valor            = 'valor';
$pdf1->receitared       = 'codreduz';
$pdf1->dreceita         = 'k02_descr';
$pdf1->ddreceita        = 'k02_drecei';
$pdf1->historico        = $k00_descr;

$sSqlReEmiteRecibo = "select k00_numnov from recibopagahist where k00_numnov = $k03_numpre";
$rsReEmiteRecibo   = db_query($sSqlReEmiteRecibo);

if (pg_num_rows($rsReEmiteRecibo) > 0){

  $sqlObs = "select k00_historico
               from recibopagahist
              where k00_numnov = $k03_numpre";

  $rsObs  = db_query($sqlObs);

  if (pg_num_rows($rsObs) > 0){

    $historico = pg_result($rsObs, 0, 0);
  }

} else {

  if (isset($_SESSION["DB_obsrecibo"])) {
    $historico = db_getsession("DB_obsrecibo");
  } else {
    $historico = $tipoidentificacao." ".$numero." ".($pql_localizacao!=""?"PQL: $pql_localizacao":"")." \n".$historico."\n".$k00_msgrecibo;
  }
  $sqlObs = "insert into recibopagahist values ($k03_numpre,'".addslashes($historico)."')";
  db_query($sqlObs);
}

if(    (!empty($_GET['numcgm']) || !empty($_GET['inscr']))
  && !empty($_GET['k03_numpre'])
) {

  $iCgm       = !empty($_GET['numcgm']) ? $_GET['numcgm'] : null;
  $iInscricao = !empty($_GET['inscr']) ? $_GET['inscr'] : null;
  $historico .= LancamentoTaxaDiversosRepository::getObservacoesTaxas($iCgm, $_GET['k03_numpre'], $iInscricao);
}

$pdf1->historico      = $historico;
$pdf1->histparcel     = @$histparcela;

$pdf1->dtvenc         = $datavencimento;
$pdf1->numpre         = $numpre;
$pdf1->valororigem    = db_formatar(@$valor_origem,'f');
$pdf1->valtotal       = db_formatar(@$valor_parm,'f');
$pdf1->linhadigitavel = $linhadigitavel;
$pdf1->codigobarras   = $codigobarras;
$pdf1->texto          = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();

$pdf1->descr3_1       = trim(pg_result($Identificacao,0,"z01_numcgm")) . "-" . trim(pg_result($Identificacao,0,"z01_nome")); // contribuinte
$pdf1->descr3_2       = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl")) . (strlen(trim(pg_result($Identificacao,0,"z01_bairro"))) > 0?"/":"") . trim(pg_result($Identificacao,0,"z01_bairro"));// endereco
$pdf1->predescr3_1    = trim(pg_result($Identificacao,0,"z01_nome")); // contribuinte
$pdf1->predescr3_2    = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl"));// endereco
$pdf1->bairropri      = $j13_descr;    // municipio
$pdf1->munic          = trim(pg_result($Identificacao,0,"z01_munic"));    // bairro
$pdf1->premunic       = trim(pg_result($Identificacao,0,"z01_munic"));    // bairro

$pdf1->cep            = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->precep         = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->cgccpf         = trim(@pg_result($Identificacao,0,"z01_cgccpf"));
$pdf1->precgccpf      = trim(@pg_result($Identificacao,0,"z01_cgccpf"));
$pdf1->cgccpfcomprador = trim(@pg_result($Identificacao,0,"z01_cgccpf"));

$pdf1->titulo5        = "";                 // titulo parcela
$pdf1->descr5         = "";                 // descr parcela
$pdf1->titulo8        = $tipoidentificacao;  // tipo de identificacao;
$pdf1->pretitulo8     = $tipoidentificacao;  // tipo de identificacao;
$pdf1->descr8         = $numero;            //descr matricula ou inscricao
$pdf1->predescr8      = $numero;            //descr matricula ou inscricao


$sqlReceitas = " select k00_receit as codreceita,
                        k02_descr as descrreceita,
                        sum(k00_valor) as valreceita,
                        null as codhist,
                        case 
                          when taborc.k02_codigo is not null
                            then taborc.k02_codrec
                            else tabplan.k02_reduz
                        end as reduzreceita,
                        (select (select k02_codigo from tabrec where k02_recjur = k00_receit or k02_recmul = k00_receit limit 1) is not null ) as codtipo
                   from recibopaga
                        inner join tabrec  on tabrec.k02_codigo  = recibopaga.k00_receit
                         left join taborc  on tabrec.k02_codigo  = taborc.k02_codigo
                                          and taborc.k02_anousu  = ".db_getsession("DB_anousu")."
                         left join tabplan on tabrec.k02_codigo  = tabplan.k02_codigo
                                          and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
                  where k00_numnov = ".$k03_numpre."
                    and k00_hist <> 918
                  group by k00_receit,
                           k02_descr,
                           taborc.k02_codrec,
                           tabplan.k02_reduz,
                           taborc.k02_codigo
                           
                  union

                 select k00_receit as codreceita,
                        k02_descr as descrreceita,
                        sum(k00_valor) as valreceita,
                        k00_hist as codhist,
                        case 
                          when taborc.k02_codigo is not null
                            then taborc.k02_codrec
                            else tabplan.k02_reduz
                        end as reduzreceita,
                       (select (select k02_codigo from tabrec where k02_recjur = k00_receit or k02_recmul = k00_receit limit 1) is not null ) as codtipo
                  from recibopaga
                       inner join tabrec  on tabrec.k02_codigo  = recibopaga.k00_receit
                        left join taborc  on tabrec.k02_codigo  = taborc.k02_codigo
                                         and taborc.k02_anousu  = ".db_getsession("DB_anousu")."
                        left join tabplan on tabrec.k02_codigo  = tabplan.k02_codigo
                                         and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
                 where k00_numnov = ".$k03_numpre."
                   and k00_hist   = 918
                 group by k00_receit,
                          k02_descr,
                          k00_hist,
                          taborc.k02_codrec,
                          tabplan.k02_reduz,
                          taborc.k02_codigo";
$rsReceitas = db_query($sqlReceitas) or die($sqlReceitas);
$intnumrows = pg_num_rows($rsReceitas);
for ($x=0;$x<$intnumrows;$x++) {
  db_fieldsmemory($rsReceitas,$x);
  $pdf1->arraycodreceitas[$x]   = $codreceita;
  $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
  $pdf1->arraydescrreceitas[$x] = $descrreceita;
  $pdf1->arrayvalreceitas[$x]   = $valreceita;
  $pdf1->arraycodhist[$x]       = $codhist;
  $pdf1->arraycodtipo[$x]       = $codtipo;
}

$pdf1->descr4_1            = $historico;
$pdf1->historicoparcela    = $historico;
$pdf1->prehistoricoparcela = $historico;
$pdf1->descr4_2            = ""; // historico - linha 1
$pdf1->predescr4_2         = ""; // historico - linha 1
$pdf1->descr16_1           = "";
$pdf1->descr16_2           = "";
$pdf1->descr16_3           = ""; //
$pdf1->predescr16_1        = "";
$pdf1->predescr16_2        = "";
$pdf1->predescr16_3        = ""; //
$pdf1->descr12_2           = ""; //
$pdf1->linha_digitavel     = $linhadigitavel;
$pdf1->codigo_barras       = $codigobarras;
$pdf1->descr6              = $datavencimento;  // Data de Vencimento

$pdf1->descr7              = db_formatar(@$valor_parm,'f');  // qtd de URM ou valor
$pdf1->descr9              = str_pad($k03_numpre."000",11,0,STR_PAD_LEFT); // cod. de arrecadação
$pdf1->predescr6           = $datavencimento;  // Data de Vencimento
$pdf1->predescr7           = db_formatar(@$valor_parm,'f');  // qtd de URM ou valor
$pdf1->predescr9           = str_pad($k03_numpre."000",11,0,STR_PAD_LEFT); // cod. de arrecadação

/***************************************************************************************************************************************/

$sSqlMsgCarne = "select k03_msgbanco from numpref where k03_anousu = ".db_getsession('DB_anousu');
$rsMsgcarne = db_query($sSqlMsgCarne);
$iNumrows   = pg_numrows($rsMsgcarne);
if($iNumrows > 0){
  db_fieldsmemory($rsMsgcarne,0);
}else{
  $k03_msgbanco = '';
}

$pdf1->descr16_1           = substr($k03_msgbanco, 0, 50);
$pdf1->descr16_2           = substr($k03_msgbanco, 50, 50);
$pdf1->descr16_3           = substr($k03_msgbanco, 100, 50);
$pdf1->predescr16_1        = substr($k03_msgbanco, 0, 50);
$pdf1->predescr16_2        = substr($k03_msgbanco, 50, 50);
$pdf1->predescr16_3        = substr($k03_msgbanco, 100, 50);

$pdf1->descr11_1           = trim(pg_result($Identificacao,0,"z01_numcgm")) . "-" . trim(pg_result($Identificacao,0,"z01_nome"));
if(trim(pg_result($Identificacao,0,"z01_ender")) != ""){
  $pdf1->descr11_2           = trim(pg_result($Identificacao,0,"z01_ender")).", ".trim(pg_result($Identificacao,0,"z01_numero")).'  '.trim(pg_result($Identificacao,0,"z01_compl"));
} else {
  $pdf1->descr11_2           = "";
}
$pdf1->descr11_3           = trim(pg_result($Identificacao,0,"z01_munic"));
$pdf1->cep                 = trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->uf                  = trim(pg_result($Identificacao,0,"z01_uf"));
$pdf1->tipoinscr           = $tipoidentificacao;
$pdf1->nrinscr             = $numero;

$sqlmensagemdesconto = "select distinct 
                               k99_desconto, 
                               k40_descr
                          from db_reciboweb 
                         inner join cadtipoparc on cadtipoparc.k40_codigo = k99_desconto
                         where k99_numpre_n = $k03_numpre";
$resultmensagemdesconto = db_query($sqlmensagemdesconto) or die($sqlmensagemdesconto);

$k00_mensagemdesconto  = "\n";
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

$descr12_1 = "\n".$historico;

if (trim($k00_mensagemdesconto) != "") {
  $descr12_1 .= "\n".$k00_mensagemdesconto;
}

$pdf1->descr12_1   = $descr12_1;
$pdf1->pqllocal    = @$sPQLLocal;

$pdf1->descr14     = $datavencimento; // vencimento
$pdf1->descr10     = "1 / 1";
$pdf1->tipo_exerc  = $tipo_debito." / ".$exerc;
$pdf1->k03_tipo    = $k03_tipo;
$pdf1->tipo_debito = $tipo_debito;
$pdf1->especie     = "R$";

if (db_strtotime($mindatop) < db_strtotime(date("Y-m-d"))) {
  $pdf1->data_processamento = date("d/m/Y");
} else {
  $pdf1->data_processamento = db_formatar($mindatop,'d');  // data do documento = menor data de operação do numpre
}
$pdf1->dtparapag  = $datavencimento; //date('d/m/Y',db_getsession('DB_datausu'));
$pdf1->loteamento = $loteador;

// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco

if ($oRegraEmissao->isCobranca()) {

  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco     = db_utils::fieldsMemory($rsConsultaBanco,0);
  $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $pdf1->banco      = $oBanco->db90_abrev;

  try{
    $pdf1->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro=[15] - ".$eExeption->getMessage());
  }


  /*
   * Caso o tipo de convênio seja cobrança registrada buscamos os dados das taxas com o grupo de taxa
   * vinculado ao cadastro de convênio utilizado
   */
  if ( $k03_tipo == 18 || $k03_tipo == 12 || $k03_tipo == 13 ) {

    $oDaoParJuridico = db_utils::getDao("parjuridico");
    $rsValidaUtilizacaoPartilha = $oDaoParJuridico->sql_record($oDaoParJuridico->sql_query_file(db_getsession("DB_anousu"),db_getsession("DB_instit"), "v19_partilha"));
    $lUtilizaPartilha = db_utils::fieldsMemory($rsValidaUtilizacaoPartilha, 0)->v19_partilha;

    $aProcessoForo                  = array_unique($aProcessoForo);
    $aCodProcessoForo               = array_unique($aCodProcessoForo);
    $nTotalTaxas                    = 0;
    $aTaxas                         = array();
    $aDadosPartilha                 = new stdClass();
    $aDadosPartilha->ar37_descricao = null;

    if ($lUtilizaPartilha == "t") {
      /*
       *
       * Buscamos os dados das custas envolvidas na partilha do Recibo
       *
       * Caso não sejam encontrados registros, significa que não foram geradas custas para este recibo pois foi efetuado o
       * pagamento do recibo com as custas emitidas ou um lançamento manual ou uma isenção para o processo do foro
       *
       */
      /* FIXME falidar se pode ser removido todo esse bloco de codigo */
      $sSqlPartilhaReciboPaga = "select ar37_descricao,
                                        ar36_sequencial, 
                                        ar36_descricao, 
                                        v76_tipolancamento,
                                        sum(v77_valor) as v77_valor 
                                   from processoforopartilhacusta
                                        inner join processoforopartilha on processoforopartilha.v76_sequencial = processoforopartilhacusta.v77_processoforopartilha 
                                        inner join processoforo         on processoforo.v70_sequencial         = processoforopartilha.v76_processoforo
                                        inner join taxa                 on taxa.ar36_sequencial                = processoforopartilhacusta.v77_taxa
                                        inner join grupotaxa            on grupotaxa.ar37_sequencial           = taxa.ar36_grupotaxa
                                  where processoforopartilhacusta.v77_numnov = {$k03_numpre}
                                    and not exists ( select 1 
                                                       from processoforopartilha as p
                                                      where p.v76_processoforo = processoforo.v70_sequencial
                                                        and (p.v76_tipolancamento <> 1 or p.v76_dtpagamento is not null ) )                                                      
                                  group by ar37_descricao,
                                           ar36_sequencial, 
                                           ar36_descricao,
                                           v76_tipolancamento ";
      $rsDadosPartilhaReciboPaga = db_query($sSqlPartilhaReciboPaga);
      if ( pg_num_rows($rsDadosPartilhaReciboPaga) > 0 ) {

        for ($iInd=0; $iInd < pg_num_rows($rsDadosPartilhaReciboPaga); $iInd++) {
          $aDadosPartilha = db_utils::fieldsMemory($rsDadosPartilhaReciboPaga, $iInd);

          $aTaxas[$iInd]["sequencial"] = $aDadosPartilha->ar36_sequencial;
          $aTaxas[$iInd]["descricao"]  = $aDadosPartilha->ar36_descricao;
          $aTaxas[$iInd]["valor"]      = $aDadosPartilha->v77_valor;
          $nTotalTaxas += $aDadosPartilha->v77_valor;

        }

        /*
         * Se foram encontradas custas para o recibo gerado
         *
         * Chamamos novamente a classe convenio só que somando o valor total das custas ao valor do débito para geração
         * do código de barras e da linha digtável com o valor total a ser pago
         */
        try {
          $nValor    = number_format(round($total_recibo+$nTotalTaxas,2), 2, '', '');
          $db_vlrbar = str_pad($nValor, 10, 0,STR_PAD_LEFT);
          unset($oConvenio);
          $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpre,0,$total_recibo+$nTotalTaxas,$db_vlrbar,$datavencimento,$k00_tercdigrecnormal);

        } catch (Exception $eExeption){
          db_redireciona("db_erros.php?fechar=true&db_erro=[16] - {$eExeption->getMessage()}");
          exit;
        }

        $pdf1->codigobarras   = $oConvenio->getCodigoBarra();
        $pdf1->codigo_barras  = $oConvenio->getCodigoBarra();
        $pdf1->linha_digitavel= $oConvenio->getLinhaDigitavel();

        $pdf1->partilhaTipoLancamento = "";
          $pdf1->partilhaDtPaga         = "";
          $pdf1->partilhaObs            = "";

      } else {
          $pdf1->partilhaTipoLancamento = "";
          $pdf1->partilhaDtPaga = "";
          $pdf1->partilhaObs = "";

          /*
           *
           * Buscamos os dados das custas envolvidas na partilha do processo do foro pois não foram geradas as custas para o recibo
           * Retornando apenas quando as custas foram pagas (processoforopartilha.v76_dtpagamento is not null) ou o tipo de lancamento
           * seja manual ou isento (processoforopartilha.v76_tipolancamento)
           *
           */
          if ( count($aProcessoForo) > 0 ) {

              $sSqlPartilha = "select ar37_descricao,
                                  ar36_sequencial, 
                                  ar36_descricao, 
                                  v76_tipolancamento,
                                  v76_dtpagamento,
                                  v76_obs,
                                  sum(v77_valor) as v77_valor 
                             from processoforopartilhacusta
                                  inner join processoforopartilha on processoforopartilha.v76_sequencial = processoforopartilhacusta.v77_processoforopartilha 
                                  inner join processoforo         on processoforo.v70_sequencial         = processoforopartilha.v76_processoforo
                                  inner join taxa                 on taxa.ar36_sequencial                = processoforopartilhacusta.v77_taxa
                                  inner join grupotaxa            on grupotaxa.ar37_sequencial           = taxa.ar36_grupotaxa
                            where processoforopartilha.v76_processoforo in (".implode(",",$aProcessoForo).")
                              and ( processoforopartilha.v76_tipolancamento <> 1 or processoforopartilha.v76_dtpagamento is not null)
                            group by ar37_descricao,
                                     ar36_sequencial, 
                                     ar36_descricao,
                                     v76_tipolancamento,
                                     v76_dtpagamento,
                                     v76_obs";

              $rsDadosPartilha = db_query($sSqlPartilha);

              if ($rsDadosPartilha) {

                  $iLinhasPartilha = pg_num_rows($rsDadosPartilha);
                  for ($iInd = 0; $iInd < $iLinhasPartilha; $iInd++) {
                      $aDadosPartilha = db_utils::fieldsMemory($rsDadosPartilha, $iInd);

                      $aTaxas[$iInd]["sequencial"] = $aDadosPartilha->ar36_sequencial;
                      $aTaxas[$iInd]["descricao"] = $aDadosPartilha->ar36_descricao;
                      $aTaxas[$iInd]["valor"] = $aDadosPartilha->v77_valor;
                      $nTotalTaxas += $aDadosPartilha->v77_valor;
                  }

                  /*
                   * Situação das Custas de Acordo com o tipo de lançamento
                   *
                   * Caso o tipo de lancamento seja 1 (Automático) e a data de pagamento não seja nula significa que as custas foram pagas
                   * Caso o tipo de lancamento seja 2 (Manual) significa que as custas foram pagas no Foro e lançadas manualmente com a data de pagamento
                   * Caso o tipo de lancamento seja 3 (Isento) significa que o processo é isento de Custas
                   *
                   */
                  if ($iLinhasPartilha > 0) {

                      $sTipoLancamento = '';
                      if (($aDadosPartilha->v76_tipolancamento == 1 && !empty($aDadosPartilha->v76_dtpagamento)) || $aDadosPartilha->v76_tipolancamento == 2) {
                          $sTipoLancamento = "Custas Pagas";
                      } else if ($aDadosPartilha->v76_tipolancamento == 3) {
                          $sTipoLancamento = "Isento de Custas";
                      }

                      $pdf1->partilhaTipoLancamento = $sTipoLancamento;
                      $pdf1->partilhaDtPaga = db_formatar($aDadosPartilha->v76_dtpagamento, "d");
                      $pdf1->partilhaObs = $aDadosPartilha->v76_obs;
                  }
              }
          }
      }
    } else {

      $pdf1->partilhaTipoLancamento = "";
      $pdf1->partilhaDtPaga         = "";
      $pdf1->partilhaObs            = "";
    }

    $sSqlExercValor  = "select exerc,                                                                              ";
    $sSqlExercValor .= "       sum(vlrhist)    as historico,                                                       ";
    $sSqlExercValor .= "       sum(principal)  as corrigido,                                                       ";
    $sSqlExercValor .= "       sum(juro)       as juro,                                                            ";
    $sSqlExercValor .= "       sum(multa)      as multa,                                                           ";
    $sSqlExercValor .= "       sum(desconto)   as desconto,                                                        ";
    $sSqlExercValor .= "       sum(valor)      as total                                                            ";
    $sSqlExercValor .= "  from ( select case                                                                       ";
    $sSqlExercValor .= "                  when divida.v01_exerc is null                                            ";
    $sSqlExercValor .= "                   then termo.exerc                                                        ";
    $sSqlExercValor .= "                   else divida.v01_exerc                                                   ";
    $sSqlExercValor .= "                end as exerc,                                                              ";
    $sSqlExercValor .= "                case                                                                       ";
    $sSqlExercValor .= "                  when termo.perc is not null                                              ";
    $sSqlExercValor .= "                    then recibopaga.k00_valor_historico * perc                             ";
    $sSqlExercValor .= "                    else recibopaga.k00_valor_historico                                    ";
    $sSqlExercValor .= "                end as vlrhist,                                                            ";
    $sSqlExercValor .= "                case                                                                       ";
    $sSqlExercValor .= "                  when k02_tabrectipo = 1                                                  ";
    $sSqlExercValor .= "                    then                                                                   ";
    $sSqlExercValor .= "                      case                                                                 ";
    $sSqlExercValor .= "                        when termo.perc is not null                                        ";
    $sSqlExercValor .= "                          then recibopaga.k00_valor*perc                                   ";
    $sSqlExercValor .= "                          else
              case
                when (select ac.k00_numpre from arrecad ac where ac.k00_numpre = recibopaga.k00_numpre and ac.k00_numpar = recibopaga.k00_numpar and ac.k00_receit = recibopaga.k00_receit) is null then
                  0
                else
                  recibopaga.k00_valor
              end ";
    $sSqlExercValor .= "                      end                                                                  ";
    $sSqlExercValor .= "                    else 0                                                                 ";
    $sSqlExercValor .= "                end as principal,                                                          ";
    $sSqlExercValor .= "                case                                                                       ";
    $sSqlExercValor .= "                  when k02_tabrectipo = 2                                                  ";
    $sSqlExercValor .= "                    then                                                                   ";
    $sSqlExercValor .= "                      case                                                                 ";
    $sSqlExercValor .= "                        when termo.perc is not null                                        ";
    $sSqlExercValor .= "                          then recibopaga.k00_valor*perc                                   ";
    $sSqlExercValor .= "                          else recibopaga.k00_valor                                        ";
    $sSqlExercValor .= "                      end                                                                  ";
    $sSqlExercValor .= "                    else 0                                                                 ";
    $sSqlExercValor .= "                end as juro,                                                               ";
    $sSqlExercValor .= "                case                                                                       ";
    $sSqlExercValor .= "                  when k02_tabrectipo = 3                                                  ";
    $sSqlExercValor .= "                    then                                                                   ";
    $sSqlExercValor .= "                      case                                                                 ";
    $sSqlExercValor .= "                        when termo.perc is not null                                        ";
    $sSqlExercValor .= "                          then recibopaga.k00_valor*perc                                   ";
    $sSqlExercValor .= "                          else recibopaga.k00_valor                                        ";
    $sSqlExercValor .= "                      end                                                                  ";
    $sSqlExercValor .= "                    else 0                                                                 ";
    $sSqlExercValor .= "                end as multa,                                                              ";
    $sSqlExercValor .= "                case                                                                       ";
    $sSqlExercValor .= "                  when k02_tabrectipo = 4                                                  ";
    $sSqlExercValor .= "                    then                                                                   ";
    $sSqlExercValor .= "                      case                                                                 ";
    $sSqlExercValor .= "                        when termo.perc is not null                                        ";
    $sSqlExercValor .= "                          then recibopaga.k00_valor*perc                                   ";
    $sSqlExercValor .= "                          else recibopaga.k00_valor                                        ";
    $sSqlExercValor .= "                      end                                                                  ";
    $sSqlExercValor .= "                    else 0                                                                 ";
    $sSqlExercValor .= "                end as desconto,                                                           ";
    $sSqlExercValor .= "                recibopaga.k00_valor as valor                                              ";
    $sSqlExercValor .= " from (select sum(recibopaga.k00_valor) as k00_valor,                                      ";
    $sSqlExercValor .= "              (select sum(arrecad.k00_valor)                                               ";
    $sSqlExercValor .= "                 from arrecad                                                              ";
    $sSqlExercValor .= "                where arrecad.k00_numpre = recibopaga.k00_numpre                           ";
    $sSqlExercValor .= "                  and arrecad.k00_numpar = recibopaga.k00_numpar                           ";
    $sSqlExercValor .= "                  and arrecad.k00_receit = recibopaga.k00_receit ) as k00_valor_historico, ";
    $sSqlExercValor .= "       recibopaga.k00_receit,                                                              ";
    $sSqlExercValor .= "       recibopaga.k00_numpar,                                                              ";
    $sSqlExercValor .= "       recibopaga.k00_numpre,                                                              ";
    $sSqlExercValor .= "       recibopaga.k00_numnov                                                               ";
    $sSqlExercValor .= "  from recibopaga                                                                          ";
    $sSqlExercValor .= " where k00_numnov = {$k03_numpre}                                                          ";
    $sSqlExercValor .= " group by recibopaga.k00_receit,                                                           ";
    $sSqlExercValor .= "          recibopaga.k00_numpar,                                                           ";
    $sSqlExercValor .= "          recibopaga.k00_numpre,                                                           ";
    $sSqlExercValor .= "          recibopaga.k00_numnov ) as recibopaga                                            ";
    $sSqlExercValor .= "          inner join tabrec  on tabrec.k02_codigo  = recibopaga.k00_receit                 ";
    $sSqlExercValor .= "           left join divida  on divida.v01_numpre  = recibopaga.k00_numpre                 ";
    $sSqlExercValor .= "                            and divida.v01_numpar  = recibopaga.k00_numpar                 ";
    $sSqlExercValor .= "           left join (                                                                     ";
    $sSqlExercValor .= "                       select                                                                                                                      ";
    $sSqlExercValor .= "                            (select v07_numpre                                                                                                     ";
    $sSqlExercValor .= "                             from termo                                                                                                            ";
    $sSqlExercValor .= "                             inner join recibopaga on recibopaga.k00_numpre = termo.v07_numpre                                                     ";
    $sSqlExercValor .= "                             where recibopaga.k00_numnov = {$k03_numpre} limit 1), v01_exerc as exerc,                                             ";
    $sSqlExercValor .= "                                                                              (((sum(k00_valor)*100)/                                              ";
    $sSqlExercValor .= "                                                                                  (select sum(k00_valor)                                           ";
    $sSqlExercValor .= "                                                                                   from fc_parc_origem_completo(                                   ";
    $sSqlExercValor .= "                                                                                                                  (select k00_numpre               ";
    $sSqlExercValor .= "                                                                                                                   from recibopaga                 ";
    $sSqlExercValor .= "                                                                                                                   where k00_numnov = {$k03_numpre}";
    $sSqlExercValor .= "                                                                                                                     and exists                    ";
    $sSqlExercValor .= "                                                                                                                       (select 1                   ";
    $sSqlExercValor .= "                                                                                                                        from termo                 ";
    $sSqlExercValor .= "                                                                                                                        where termo.v07_numpre = recibopaga.k00_numpre limit 1) limit 1))";
    $sSqlExercValor .= "                                                                                   inner join termo on termo.v07_parcel = riparcel                       ";
    $sSqlExercValor .= "                                                                                   inner join termodiv on termodiv.parcel = riparcel                     ";
    $sSqlExercValor .= "                                                                                   inner join divida on divida.v01_coddiv = termodiv.coddiv              ";
    $sSqlExercValor .= "                                                                                   and divida.v01_instit = ".db_getsession("DB_instit")."                ";
    $sSqlExercValor .= "                                                                                   inner join proced on proced.v03_codigo = v01_proced                   ";
    $sSqlExercValor .= "                                                                                   inner join arreold on arreold.k00_numpre = divida.v01_numpre          ";
    $sSqlExercValor .= "                                                                                   and arreold.k00_numpar = divida.v01_numpar                            ";
    $sSqlExercValor .= "                                                                                   and arreold.k00_receit = proced.v03_receit))/100) as perc             ";
    $sSqlExercValor .= "                          from fc_parc_origem_completo(                                                                                                  ";
    $sSqlExercValor .= "                                                         (select k00_numpre                                                                              ";
    $sSqlExercValor .= "                                                            from recibopaga                                                                              ";
    $sSqlExercValor .= "                                                            where k00_numnov = {$k03_numpre}                                                             ";
    $sSqlExercValor .= "                                                              and exists                                                                                 ";
    $sSqlExercValor .= "                                                                (select 1                                                                                ";
    $sSqlExercValor .= "                                                                 from termo                                                                              ";
    $sSqlExercValor .= "                                                                 where termo.v07_numpre = recibopaga.k00_numpre limit 1) limit 1)) as origemparcelamento ";
    $sSqlExercValor .= "                          inner join termo on termo.v07_parcel = riparcel                                                                                ";
    $sSqlExercValor .= "                          inner join termodiv on termodiv.parcel = riparcel                                                                              ";
    $sSqlExercValor .= "                          inner join divida on divida.v01_coddiv = termodiv.coddiv                                                                       ";
    $sSqlExercValor .= "                          and divida.v01_instit = ".db_getsession("DB_instit")."                                                                         ";
    $sSqlExercValor .= "                          inner join proced on proced.v03_codigo = v01_proced                                                                            ";
    $sSqlExercValor .= "                          inner join arreold on arreold.k00_numpre = divida.v01_numpre                                                                   ";
    $sSqlExercValor .= "                          and arreold.k00_numpar = divida.v01_numpar                                                                                     ";
    $sSqlExercValor .= "                          and arreold.k00_receit = proced.v03_receit                                                                                     ";
    $sSqlExercValor .= "                          group by v07_numpre,                                                                                                           ";
    $sSqlExercValor .= "                                   v01_exerc,                                                                                                            ";
    $sSqlExercValor .= "                                   v07_vlrhis                                                                                                            ";
    $sSqlExercValor .= "                          union select                                                                                                                   ";
    $sSqlExercValor .= "                            (select v07_numpre                                                                                                           ";
    $sSqlExercValor .= "                             from termo                                                                                                                  ";
    $sSqlExercValor .= "                             inner join recibopaga on recibopaga.k00_numpre = termo.v07_numpre                                                           ";
    $sSqlExercValor .= "                             where recibopaga.k00_numnov = {$k03_numpre} limit 1), v01_exerc as exerc,                                                   ";
    $sSqlExercValor .= "                                                                              (((sum(k00_valor)*100)/                                                    ";
    $sSqlExercValor .= "                                                                                  (select sum(k00_valor)                                                 ";
    $sSqlExercValor .= "                                                                                   from fc_parc_origem_completo(                                         ";
    $sSqlExercValor .= "                                                                                                                  (select k00_numpre                     ";
    $sSqlExercValor .= "                                                                                                                   from recibopaga                       ";
    $sSqlExercValor .= "                                                                                                                   where k00_numnov = {$k03_numpre}      ";
    $sSqlExercValor .= "                                                                                                                     and exists                          ";
    $sSqlExercValor .= "                                                                                                                       (select 1                         ";
    $sSqlExercValor .= "                                                                                                                        from termo                       ";
    $sSqlExercValor .= "                                                                                                                        where termo.v07_numpre = recibopaga.k00_numpre limit 1) limit 1))";
    $sSqlExercValor .= "                                                                                   inner join termo on termo.v07_parcel = riparcel                       ";
    $sSqlExercValor .= "                                                                                   inner join termoini on termoini.parcel = riparcel                     ";
    $sSqlExercValor .= "                                                                                   inner join inicialcert on inicial = v51_inicial                       ";
    $sSqlExercValor .= "                                                                                   inner join certdiv on v14_certid = v51_certidao                       ";
    $sSqlExercValor .= "                                                                                   inner join divida on divida.v01_coddiv = v14_coddiv                   ";
    $sSqlExercValor .= "                                                                                   and divida.v01_instit = ".db_getsession("DB_instit")."                ";
    $sSqlExercValor .= "                                                                                   inner join proced on proced.v03_codigo = v01_proced                   ";
    $sSqlExercValor .= "                                                                                   inner join arreold on arreold.k00_numpre = divida.v01_numpre          ";
    $sSqlExercValor .= "                                                                                   and arreold.k00_numpar = divida.v01_numpar                            ";
    $sSqlExercValor .= "                                                                                   and arreold.k00_receit = proced.v03_receit))/100) as perc             ";
    $sSqlExercValor .= "                          from fc_parc_origem_completo(                                                                                                  ";
    $sSqlExercValor .= "                                                         (select k00_numpre                                                                              ";
    $sSqlExercValor .= "                                                          from recibopaga                                                                                ";
    $sSqlExercValor .= "                                                          where k00_numnov = {$k03_numpre}                                                               ";
    $sSqlExercValor .= "                                                            and exists                                                                                   ";
    $sSqlExercValor .= "                                                              (select 1                                                                                  ";
    $sSqlExercValor .= "                                                               from termo                                                                                ";
    $sSqlExercValor .= "                                                               where termo.v07_numpre = recibopaga.k00_numpre limit 1) limit 1)) as origemparcelamento   ";
    $sSqlExercValor .= "                          inner join termo on termo.v07_parcel = riparcel                                                                                ";
    $sSqlExercValor .= "                          inner join termoini on termoini.parcel = riparcel                                                                              ";
    $sSqlExercValor .= "                          inner join inicialcert on inicial = v51_inicial                                                                                ";
    $sSqlExercValor .= "                          inner join certdiv on v14_certid = v51_certidao                                                                                ";
    $sSqlExercValor .= "                          inner join divida on divida.v01_coddiv = v14_coddiv                                                                            ";
    $sSqlExercValor .= "                          and divida.v01_instit = ".db_getsession("DB_instit")."                                                                         ";
    $sSqlExercValor .= "                          inner join proced on proced.v03_codigo = v01_proced                                                                            ";
    $sSqlExercValor .= "                          inner join arreold on arreold.k00_numpre = divida.v01_numpre                                                                   ";
    $sSqlExercValor .= "                          and arreold.k00_numpar = divida.v01_numpar                                                                                     ";
    $sSqlExercValor .= "                          and arreold.k00_receit = proced.v03_receit                                                                                     ";
    $sSqlExercValor .= "                          group by v07_numpre,                                                                                                           ";
    $sSqlExercValor .= "                                   v01_exerc,                                                                                                            ";
    $sSqlExercValor .= "                                   v07_vlrhis) as termo on termo.v07_numpre = recibopaga.k00_numpre                                                      ";
    $sSqlExercValor .= " WHere recibopaga.k00_numnov = {$k03_numpre } ) as x                                                                                                     ";
    $sSqlExercValor .= " group by exerc                                                                                                                                          ";
    $sSqlExercValor .= " order by exerc                                                                                                                                          ";
    $rsExercValor = db_query($sSqlExercValor);

    $pdf1->aExercValor      = db_utils::getCollectionByRecord($rsExercValor);
    //echo "<pre>";
    //var_dump($pdf1->aExercValor); exit;
    $pdf1->sCodforo         = implode(",",$aCodProcessoForo);
    $pdf1->aTaxas           = $aTaxas;
    $pdf1->nTotalValorTaxas = $nTotalTaxas;
    $pdf1->sGrupoTaxa       = $aDadosPartilha->ar37_descricao;
    $pdf1->nTaxaBancaria    = $taxabancaria;
    $pdf1->valor_cobrado    = $pdf1->valtotal+$nTotalTaxas;

    $sSqlMsgContribuinte  = "select k00_msgparc,
                                    k00_msgparc2,
                                    k00_msgrecibo 
                               from arretipo 
                              where k00_tipo = {$tipo_debito}";
    $rsMsgContribuinte    = db_query($sSqlMsgContribuinte);
    $oDadosMsg = db_utils::fieldsMemory($rsMsgContribuinte,0);

    $pdf1->msgcontribuinte = $oDadosMsg->k00_msgparc;
    $pdf1->msgbanco        = $oDadosMsg->k00_msgparc2;
    $pdf1->msgrecibo       = $oDadosMsg->k00_msgrecibo;

    if ($k03_tipo == 13) {
      $pdf1->descr10                        = "1 / $oHistParcelaTermo->totpar";
      $pdf1->sHistoricoIniciaisParcelamento = $sHistoricoIniciaisParcelamento;
    }
  }

}
db_fim_transacao();
$pdf1->numnov_recibo = $k03_numpre;

/***************************************************************************************************************/
if ( ( $db21_codcli == 4 || $db21_codcli == 7107 ) && $pdf1->impmodelo == 2 )  {
  $pdf1->lUtilizaModeloDefault = false;
}

$pdf1->imprime();
$sNomeArquivo = "Recibo" . date('dmYGis') . ".pdf";
$pdf1->objpdf->output('tmp/'.$sNomeArquivo);


unset($_SESSION["DB_obsrecibo"]);
//echo "<script>location.href='$arq'</script>";

function recibodesconto($numpre, $numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros, $ver_matric) {

  $sql_cgc = "select cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit");
  $rs_cgc = db_query($sql_cgc);
  $oConfig = new stdClass();
  $oConfig->cgc = pg_result($rs_cgc);

  /* testa se está em dia com IPTU */
  $iTemDesconto = 1;

  if ( isset($ver_matric) and $oConfig->db21_codcli == 19985 and false ) { // marica/rj
    $sIptuAberto = "select count(distinct k00_numpar) from caixa.arrecad inner join caixa.arretipo on arrecad.k00_tipo = arretipo.k00_tipo inner join caixa.arrematric on arrecad.k00_numpre = arrematric.k00_numpre where k03_tipo = 1 and k00_matric = $ver_matric";
    $rsIptuAberto = db_query($sIptuAberto) or die($sIptuAberto);
    if ( pg_numrows($rsIptuAberto) > 0 ) {
      $iQuantAberto = pg_result($rsIptuAberto,0,0);
      if ( $iQuantAberto > 2 ) {
        $iTemDesconto = 0;
      }
    }
  }

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


  $dDataUsu = date("Y-m-d",db_getsession("DB_datausu"));

  $sqltipoparc = "select k40_codigo,
                         k40_todasmarc, 
                         cadtipoparc
                    from tipoparc 
                         inner join cadtipoparc    on cadtipoparc     = k40_codigo
                         inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
                   where maxparc = 1 
                     and '{$dDataUsu}' >= k40_dtini 
                     and '{$dDataUsu}' <= k40_dtfim 
                     and k41_arretipo   = $tipo $whereloteador 
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
                       and '{$dDataUsu}' >= k40_dtini 
                       and '{$dDataUsu}' <= k40_dtfim 
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

?>
