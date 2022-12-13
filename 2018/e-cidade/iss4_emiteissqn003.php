<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_issbase_classe.php"));
require_once(modification("classes/db_isscalc_classe.php"));
require_once(modification("classes/db_arrecad_classe.php"));
require_once(modification("classes/db_db_bancos_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/convenio.model.php"));
require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/recibo.model.php"));

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

db_postmemory($HTTP_SERVER_VARS);

$cldb_bancos  = new cl_db_bancos();

$auxiliar     = "";
$quanti       = 0;
$intixxx      = 0;
$histinf      = '';
$iIniPag      = 1;
$iFimPag      = 0;
$msgvencida   = '';
$nomearquivos = '';
$limit        = '';
$tipo_debito  = $k00_tipo;

if (isset($imprimir) && $imprimir == 'socotunica') {

  $lImprimeParcelas = false;
  $lImprimeUnicas   = true;
  $sWhereUnicas     = ' and recibounica.k00_numpre is not null ';
} else if (isset($imprimir) && $imprimir == 'soparcela') {

  $lImprimeParcelas = true;
  $lImprimeUnicas   = false;
  $sWhereUnicas     = '';
} else {

  $lImprimeParcelas = true;
  $lImprimeUnicas   = true;
  $sWhereUnicas     = '';
}

if (isset($quantidade) && trim($quantidade) != "") {
  $limit = "limit $quantidade";
}

$QuebraPag   = 500;
$nomeTipoMod = "arquivos";
$impmodelo   = 1;


if (isset($unica) && $unica != "") {

  $vt = split("U", $unica);
  $unicas = array ();

  foreach ( $vt as $i => $v ) {

    $check = split("=", $v);
    if (isset($check) && $check != "") {
      array_push($unicas, $check [0] . "-" . $check [1] . "-" . $check [2]) . "#";
    }
  }

}

$result = db_query("select * from arretipo where k00_tipo = $tipo_debito");
db_fieldsmemory($result, 0);
db_sel_instit();

try {

  $oRegraEmissao = new regraEmissao($tipo_debito,17,db_getsession('DB_instit'), date("Y-m-d", db_getsession("DB_datausu")), db_getsession('DB_ip'));
  $pdf1 = $oRegraEmissao->getObjPdf();
} catch ( Exception $eExeption ) {

  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit();
}

$pdf1->prefeitura = $nomeinst;

$sqlparag  = " select db02_texto   ";
$sqlparag .= "   from db_documento ";
$sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
$sqlparag .= "        inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
$sqlparag .= "        inner join db_paragrafo on db04_idparag = db02_idparag ";
$sqlparag .= "  where db03_tipodoc = 1017 ";
$sqlparag .= "    and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";

$resparag = db_query($sqlparag);
if (pg_numrows($resparag) == 0) {
  $pdf1->secretaria = 'SECRETARIA DE FINANÇAS';
} else {

  db_fieldsmemory($resparag, 0);
  $pdf1->secretaria = $db02_texto;
}
$pdf1->tipodebito = $k00_descr;
$pdf1->logo       = $logo;

/**
 * MONTANDO O SELECT
 */
$whereescrito = "";
$whereescrito_ativo = "";

if (isset($emis) && $emis == "comescr") {

  if (isset($cgmescrito) && $cgmescrito != "") {

    $whereescrito = " and q10_numcgm in ($cgmescrito) ";
    $whereescrito_ativo = " and ( q10_dtfim is null or q10_dtfim >= '".date('Y-m-d',db_getsession('DB_datausu'))."' )";
  } else {
    $whereescrito = "";
  }
  $join = "inner";
} else if (isset($emis) && $emis == "semescr") {

  $join = " left ";
  $whereescrito = " and q10_numcgm is null";
  $whereescrito_ativo = "";
} else if (isset($emis) && $emis == "geral") {

  $whereescrito = "";
  $whereescrito_ativo = "";
  $join = " left ";
}

if ($tipo_debito == 3) {

  $whereescrito .= " and not exists ( select 1                                                                          ";
  $whereescrito .= "                    from caracteristica                                                             ";
  $whereescrito .= "                         inner join issbasecaracteristica on db140_sequencial = q138_caracteristica ";
  $whereescrito .= "                   where q138_inscr = q02_inscr                                                     ";
  $whereescrito .= "                     and db140_grupocaracteristica = 2                                              ";
  $whereescrito .= "                     and db140_sequencial in (10, 11))                                              ";
}

if (isset($ord) && $ord == "escritorio") {

  $ordenaescrito = " q10_numcgm, ";
  $ordena        = " z01_nome ";
} else if (isset($ord) && $ord == "inscricao") {

  $ordenaescrito = " q02_inscr, ";
  $ordena        = " z01_nome ";
} else if (isset($ord) && $ord == "nome") {
  $ordenaescrito = " z01_nome ";
  $ordena        = "";
}

if ( $k03_tipo == 19 ) {

  $sql = "select * from (
           select y69_numpre as q01_numpre,
              q10_numcgm,
              q02_inscr,
              z01_nome,
              y77_descricao,
              substr(y70_data,1,4) as anousu
           from issbase
            inner join vistinscr      on vistinscr.y71_inscr        = issbase.q02_inscr
            inner join vistorias      on vistorias.y70_codvist      = vistinscr.y71_codvist
            inner join tipovistorias  on vistorias.y70_tipovist     = tipovistorias.y77_codtipo
            inner join vistorianumpre on vistorianumpre.y69_codvist = vistorias.y70_codvist
            inner join arreinscr      on vistorianumpre.y69_numpre  = arreinscr.k00_numpre
            left  join recibounica    on recibounica.k00_numpre     = arreinscr.k00_numpre
            $join join escrito        on escrito.q10_inscr          = issbase.q02_inscr
            inner join cgm            on cgm.z01_numcgm             = issbase.q02_numcgm
      where 1=1 $sWhereUnicas $whereescrito_ativo
    union all
          select y69_numpre as q01_numpre,
             q10_numcgm,
             q02_inscr,
             z01_nome,
             y77_descricao,
             substr(y70_data,1,4) as anousu
          from issbase
             inner join sanitarioinscr on sanitarioinscr.y18_inscr    = issbase.q02_inscr
             inner join vistsanitario  on vistsanitario.y74_codsani   = sanitarioinscr.y18_codsani
             inner join vistorias      on vistorias.y70_codvist       = vistsanitario.y74_codvist
             inner join tipovistorias  on vistorias.y70_tipovist      = tipovistorias.y77_codtipo
             inner join vistorianumpre on vistorianumpre.y69_codvist  = vistorias.y70_codvist
             inner join arreinscr      on vistorianumpre.y69_numpre   = arreinscr.k00_numpre
             left  join recibounica    on recibounica.k00_numpre      = arreinscr.k00_numpre
             $join join escrito        on escrito.q10_inscr           = issbase.q02_inscr
             inner join cgm            on cgm.z01_numcgm              = issbase.q02_numcgm
         where 1=1 $sWhereUnicas $whereescrito_ativo
         ) as xx
      where 1=1 $whereescrito
        and anousu = '" . db_getsession('DB_anousu') . "'
      order by $ordenaescrito
      $ordena
      $limit";
} elseif ($k03_tipo == 5) {

  $sql = "select * from (
           select y69_numpre as q01_numpre,
              q10_numcgm,
              q02_inscr,
              z01_nome,
              y77_descricao,
              substr(y70_data,1,4) as anousu
           from vistorias
            $join join escrito        on escrito.q10_inscr          = issbase.q02_inscr
            inner join cgm            on cgm.z01_numcgm             = issbase.q02_numcgm
            left  join recibounica    on recibounica.k00_numpre     = y69_numpre
            where extract (year from y70_data) = " . db_getsession("DB_anousu") . " and y70_parcial = 'f'
            $sWhereUnicas $whereescrito_ativo
         ) as xx
      where 1=1 $whereescrito
      order by $ordenaescrito
      $ordena
      $limit";
} else {

  $dDataSistema = date("Y-m-d",db_getsession("DB_datausu"));

  $sql = "
      select  q01_numpre,
              q10_numcgm,
              q02_inscr,
              q02_inscr as inscricao_empresa,
              q01_inscr,
              z01_numcgm,
              q01_anousu,
              z01_nome
         from ( select * from isscalc
                 where isscalc.q01_cadcal = {$k03_tipo}
                   and isscalc.q01_anousu = '" . db_getsession('DB_anousu') . "' ) as x
          inner join issbase on issbase.q02_inscr = q01_inscr
          inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm
          $join join escrito on q02_inscr = escrito.q10_inscr
          inner join arreinscr on arreinscr.k00_numpre = q01_numpre
          left  join recibounica    on recibounica.k00_numpre     = arreinscr.k00_numpre
          where not exists ( select 1
                               from isscadsimples
                                    left join isscadsimplesbaixa on q38_sequencial = q39_isscadsimples
                              where (q39_dtbaixa is null
                                     or q39_dtbaixa > '{$dDataSistema}')
                                and q38_inscr = q02_inscr )
                $whereescrito $whereescrito_ativo
                $sWhereUnicas
       order by $ordenaescrito
                $ordena
                $limit";
}

$rsNumpres = db_query($sql) or die($sql);

$H_ANOUSU  = db_getsession("DB_anousu");
$H_DATAUSU = db_getsession('DB_datausu');
$intNumrowsNumpre = pg_numrows($rsNumpres);

if ($intNumrowsNumpre == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem inscrições com calculo efetuado.');
}

$db_datausu = date('Y-m-d', db_getsession('DB_datausu'));

$pipe = '';

for($inti = 0; $inti < $intNumrowsNumpre; $inti ++) {

  db_fieldsmemory($rsNumpres, $inti);

  $vt = Array ();
  $sWhereNumPar = null;
  if (isset($numparini)) {
    $sWhereNumPar = " and arrecad.k00_numpar between {$numparini} and {$numparfim}";
  }
  $sqlnumpar = " select * from arrecad where $inti = $inti and k00_numpre = $q01_numpre {$sWhereNumPar} order by k00_numpar";
  $rsNumpar = db_query($sqlnumpar);
  $rsNumrowspar = pg_numrows($rsNumpar);
  if ($rsNumrowspar == 0) {
    continue;
  }

  for($ind = 0; $ind < $rsNumrowspar; $ind ++) {

    db_fieldsmemory($rsNumpar, $ind);
    $var = "CHECK" . $ind;
    $vt [$var] = $k00_numpre . "P" . $k00_numpar;
  }

  $tam = sizeof($vt);
  reset($vt);
  $numpres = "";
  $n = "";

  for($i = 0; $i < $tam; $i ++) {

    if (db_indexOf(key($vt), "CHECK") > 0) {

      $numpres .= $n . $vt [key($vt)];
      $n = "N";
    }
    next($vt);
  }

  $sounica = $numpres;
  $numpres = split("N", $numpres);
  $unica = 2;

  if (sizeof($numpres) < 1) {

    $numpres = array (

                                  "0" => "0",
                                  "1" => $numpre_unica . 'P000'
    );
    $unica = 1;
  } else {

    if (isset($HTTP_POST_VARS ["numpre_unica"])) {

      if ($numpre_unica != '') {
        $unica = 1;
      }
    }
  }
  sizeof($numpres);
  if (isset($geracarne) && $geracarne == 'banco') {
    $pagabanco = 't';
  } else {
    $pagabanco = 't';
  }

  $impunica               = 0;
  $ultimoNumpreProcessado = 0;
  $numpresUnique          = array_unique($numpres);

  for ($volta = 0; $volta < sizeof($numpres); $volta ++) {

    if ($intixxx >= $QuebraPag) {

      $iFimPag += $intixxx;

      $arquivo       = "tmp/" . $nomeTipoMod . "_" . str_replace(" ", "", $k00_descr) . "_de_" . $iIniPag . "_ate_" . $iFimPag . "_" . date('His') . ".pdf";
      $nomearquivos .= "tmp/" . $nomeTipoMod . "_" . str_replace(" ", "", $k00_descr) . "_de_" . $iIniPag . "_ate_" . $iFimPag . "_" . date('His') . ".pdf#Dowload dos " . $nomeTipoMod . " de " . $iIniPag . " ate " . $iFimPag . "|";

      $pdf1->objpdf->Output($arquivo, false, true);

      unset($pdf1->objpdf);
      unset($pdf1);
      unset($oRegraEmissao);

      try {

        $oRegraEmissao = new regraEmissao($tipo_debito,17,db_getsession('DB_instit'), date("Y-m-d", db_getsession("DB_datausu")), db_getsession('DB_ip'));
        $pdf1 = $oRegraEmissao->getObjPdf();
      } catch ( Exception $eExeption ) {

        db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
        exit();
      }

      $pdf1->prefeitura = $nomeinst;
      $sqlparag = "select db02_texto
  	                      from db_documento
	                             inner join db_docparag on db03_docum = db04_docum
                               inner join db_tipodoc on db08_codigo = db03_tipodoc
                               inner join db_paragrafo on db04_idparag = db02_idparag
                         where db03_tipodoc = 1017 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";
      $resparag = db_query($sqlparag);
      if (pg_numrows($resparag) == 0) {
        $pdf1->secretaria = 'SECRETARIA DE FINANÇAS';
      } else {
        db_fieldsmemory($resparag, 0);
        $pdf1->secretaria = $db02_texto;
      }
      $pdf1->tipo_debito = $k00_descr;      
      $pdf1->logo       = $logo;
      $iIniPag         += $intixxx;
      $intixxx          = 0;
      $quanti           = 0;
    }

    if (! array_key_exists($volta, $numpresUnique)) {
      continue;
    }

    $k00_numpre = substr($numpres [$volta], 0, strpos($numpres [$volta], 'P'));
    $resulttipo = db_query("select k00_descr,k00_codbco,k00_codage,k00_txban,k00_rectx,
                                       k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,
                                       k00_hist6,k00_hist7,k00_hist8
                                  from arretipo
                    			 where k00_tipo = $tipo_debito ");
    db_fieldsmemory($resulttipo, 0);

    $sqlorigem = "select arrecad.k00_numpre,
         			          arrenumcgm.k00_numcgm as z01_numcgm,
			                  case when arrematric.k00_matric is not null
			                    then arrematric.k00_matric
			                        when arreinscr.k00_inscr is not null
			                          then arreinscr.k00_inscr
			                       else
			                          arrenumcgm.k00_numcgm
			                  end as origem,
			                  case when arrematric.k00_matric is not null
			                    then 'Matrícula'
			                       when arreinscr.k00_inscr is not null
			                    then 'Inscrição'
			                  else
			                    'CGM'
			                  end as descr
		                 from arrecad
		                      inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre
		                      left  join arrematric on arrematric.k00_numpre = arrecad.k00_numpre
		                      left  join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre
		                where arrecad.k00_numpre = $k00_numpre {$sWhereNumPar}";

    $rsOrigem = db_query($sqlorigem) or die($sqlorigem);

    if (pg_numrows($rsOrigem) > 0) {
      db_fieldsmemory($rsOrigem, 0);
    } else {

      db_msgbox("Nao encontrou registros do numpre: $k00_numpre!");
      exit();
    }

    $Identificacao = db_query("select * from empresa where q02_inscr = $origem");
    db_fieldsmemory($Identificacao, 0);
    $numero        = $q02_inscr;
    $z01_numcgm    = $q02_numcgm;

    $sqlIssBase    = db_query("select q02_inscmu from issbase where q02_inscr = $origem");
    db_fieldsmemory($sqlIssBase, 0);

    /**
     * UNICA
     */
    if ( $lImprimeUnicas ) {

	    $sqlUnicas = " select * from recibounica where k00_numpre = $k00_numpre ";
	    $rsUnicas  = db_query($sqlUnicas);

	    $intNumRowsUnica = pg_num_rows($rsUnicas);

	    if (isset($intNumRowsUnica) && $intNumRowsUnica > 0 && $k00_numpre != $ultimoNumpreProcessado) {

	      $unica                  = 1;
	      $ultimoNumpreProcessado = $k00_numpre;
	    } else {
	      $unica = 0;
	    }

	    if ($unica == 1) {

	      $sql = " select *,																																															";
	      $sql .= "        substr(fc_calcula,2,13)::float8 as uvlrhis,			                                              ";
	      $sql .= "        substr(fc_calcula,15,13)::float8 as uvlrcor,			                                              ";
	      $sql .= "        substr(fc_calcula,28,13)::float8 as uvlrjuros,		                                              ";
	      $sql .= "        substr(fc_calcula,41,13)::float8 as uvlrmulta,		                                              ";
	      $sql .= "        substr(fc_calcula,54,13)::float8 as uvlrdesconto,                                                  ";
	      $sql .= "        (substr(fc_calcula,15,13)::float8 +								                                  ";
	      $sql .= "								substr(fc_calcula,28,13)::float8 +                                            ";
	      $sql .= "								substr(fc_calcula,41,13)::float8 -                                            ";
	      $sql .= "								substr(fc_calcula,54,13)::float8) as utotal,                                  ";
	      $sql .= "        substr(fc_calcula,77,17)::float8 as qinfla,			                                              ";
	      $sql .= "        substr(fc_calcula,94,4)::varchar(5) as ninfla		                                              ";
	      $sql .= "   from ( select r.k00_numpre,														                      ";
	      $sql .= "                 r.k00_dtvenc as dtvencunic,							                                      ";
	      $sql .= "                 r.k00_dtoper as dtoperunic,							                                      ";
	      $sql .= "                 r.k00_percdes,													                          ";
	      $sql .= "                 fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc," . db_getsession("DB_anousu") . ") ";
	      $sql .= "            from recibounica r																		 	  ";
	      $sql .= "          where r.k00_numpre = " . $k00_numpre . "               										  ";
	      $sql .= "            and r.k00_dtvenc >= '" . date('Y-m-d', db_getsession("DB_datausu")) . "'::date ) as unica	  ";
	      $sql .= " order by dtvencunic     																																							";

	      $linha     = 220;
	      $resultfin = db_query($sql) or die($sql);

	      if ($resultfin != false) {

	        for($unicont = 0; $unicont < pg_numrows($resultfin); $unicont ++) {

	          db_fieldsmemory($resultfin, $unicont);

	          if ($tipo_debito != "3") {

	          	db_inicio_transacao();

	            try {

                $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

	            	$oRecibo = new recibo(2, null, 6);
								$oRecibo->addNumpre($k00_numpre,0);
								$oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
                $oRecibo->setDataRecibo($dtvencunic);
                $oRecibo->setDataVencimentoRecibo($dtvencunic);
								$oRecibo->emiteRecibo($lConvenioCobrancaValido);

                if ($lConvenioCobrancaValido) {
                  CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
                }

							} catch ( Exception $eException ) {

							  db_fim_transacao(true);
								db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
							  exit;
							}

							db_fim_transacao();
	          }

	          if ($tipo_debito == "3") {

	            $DadosPgtoUnica = debitos_numpre_carne($k00_numpre, 0, $H_DATAUSU, $H_ANOUSU);
	            $k00_numnov     = $k00_numpre;
	          } else {

	            $sWhere         = "arreinscr.k00_inscr = $q02_inscr and arrecad.k00_tipo = $k00_tipo ";
	            $DadosPgtoUnica = debitos_numpre_carne_recibopaga($k00_numpre, 0, $H_DATAUSU, $H_ANOUSU, db_getsession('DB_instit'), $sWhere);
	          }

	          $oDadosPgtoUnica = db_utils::fieldsMemory($DadosPgtoUnica, 0);

	          $vlrhis      = db_formatar($uvlrhis, 'f');
	          $vlrdesconto = db_formatar($uvlrdesconto, 'f');
	          $utotal     += @$taxabancaria;
	          $vlrtotal    = db_formatar($utotal, 'f');
	          $vlrbar      = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

	          $sqlvalor    = "select k00_impval, k00_tercdigcarneunica from arretipo where k00_tipo = $tipo_debito";
	          db_fieldsmemory(db_query($sqlvalor), 0);

	          if (! isset($k00_tercdigcarneunica) || $k00_tercdigcarneunica == "") {
	            db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
	          }

	          $iTercDig = $k00_tercdigcarneunica;

	          if ($k00_impval == 't') {

	            $k00_valor = $utotal;
	            $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
	            $ninfla    = '';

	            if ($utotal == 0) {

	              $iTercDig = 7;
	              $vlrbar = "00000000000";
	            }

	          } else {

	            $k00_valor = $qinfla;
	            $iTercDig  = 7;
	            $vlrbar    = "00000000000";
	          }

	          $datavencimento = $dtvencunic;
	          $tmpdt          = substr($db_datausu, 0, 4) . substr($db_datausu, 5, 2) . substr($db_datausu, 8, 2);

	          if (strtotime($tmpdt) > strtotime($datavencimento) && $k00_valor > 0) {
	            $datavencimento = $tmpdt;
	          }

	          if (isset($emiscarneiframe) && $emiscarneiframe == 'n') {

	            if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu')) {
	              continue;
	            }
	          }

	          if ($oRegraEmissao->isCobranca()) {

	            if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0) {

	              $k00_valor = 0;
	              $especie   = $ninfla;
	              $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
	            } else {

	              $especie = 'R$';
	              $histinf = "";
	            }

	            if ($datavencimento < date('Ymd', db_getsession('DB_datausu')) && $k00_valor > 0) {

	              $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original " . $k00_dtvenc;
	              $k00_dtvenc = date('d/m/Y', $H_DATAUSU);
	            } else {
	              $msgvencida = "";
	            }

	            if (isset($emiteVal) && $emiteVal == 2) {
	              $k00_valor = 0;
	            }

	            if (isset($emiteVal) && $emiteVal == 2) {
	              $vlrbar = "00000000000";
	            }
	          }

	          try {

	            $oConvenio       = new convenio($oRegraEmissao->getConvenio(), $oDadosPgtoUnica->k00_numnov, 0, $k00_valor, $vlrbar, $dtvencunic, $iTercDig);
	            $codigo_barras   = $oConvenio->getCodigoBarra();
	            $linha_digitavel = $oConvenio->getLinhaDigitavel();
	          } catch ( Exception $eExeption ) {

	            db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
	            exit();
	          }

				    if ( $oRegraEmissao->isCobranca() ) {

				      if(strlen(trim($oConvenio->getConvenioCobranca())) == 7) {
				        $pdf1->nosso_numero = trim($oConvenio->getConvenioCobranca()) . str_pad($oDadosPgtoUnica->k00_numnov."00",10,0,STR_PAD_LEFT);
				      } else {
				        $pdf1->nosso_numero = $oConvenio->getNossoNumero();
				      }

				    }

				    $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
				    $pdf1->carteira        = $oConvenio->getCarteira();

	          global $pdf;

	          $pdf1->titulo1       = $descr;
	          $pdf1->descr1        = $numero;
	          $pdf1->tipo_convenio = $oConvenio->getTipoConvenio();

	          $pdf1->descr2 = db_numpre($oDadosPgtoUnica->k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($oDadosPgtoUnica->k00_numpar, "s", "0", 3, "e") : "000");

	          if (isset($obs)) {

	            $pdf1->titulo13 = 'Observação';
	            $pdf1->descr13  = $obs;
	          }

	          /**
             * ISSQN FIXO
             */
	          if ($k03_tipo == 2) {

	            $pdf1->titulo4  = 'Atividade';
	            $pdf1->descr4_1 = '- ' . $q07_ativ . '-' . $q03_descr;
	            $pdf1->titulo13 = 'Atividade';
	            $pdf1->descr13  = $q07_ativ;

	          /**
             * PARCELAMANTO DE DIVIDA E DE INICIAL
             */
	          } else if (($k03_tipo == 6) || ($k03_tipo == 13)) {

	            $pdf1->titulo4  = 'Parcelamento';
	            $pdf1->descr4_1 = '- ' . $v07_parcel . $exercicio;
	            $pdf1->titulo13 = 'Parcelamento';
	            $pdf1->descr13  = $v07_parcel;
	          }

	          $pdf1->descr5     = 'UNICA';
	          $pdf1->descr6     = db_formatar($dtvencunic,'d');
	          $pdf1->predescr6  = db_formatar($dtvencunic,'d');
	          $pdf1->titulo8    = $descr;
	          $pdf1->pretitulo8 = $descr;
	          $pdf1->descr8     = $numero;
	          $pdf1->predescr8  = $numero;
	          $pdf1->descr9     = db_numpre($oDadosPgtoUnica->k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($oDadosPgtoUnica->k00_numpar, "s", "0", 3, "e") : "000");
	          $pdf1->predescr9  = db_numpre($oDadosPgtoUnica->k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($oDadosPgtoUnica->k00_numpar, "s", "0", 3, "e") : "000");
	          $pdf1->descr10    = 'UNICA';
	          $pdf1->tipocompl  = 'Número:';
	          $pdf1->dtparapag  = db_formatar($dtvencunic,'d');

	          if (! empty($HTTP_POST_VARS ["ver_matric"])) {

	            $pdf1->descr11_1   = $z01_cgmpri . " - " . $proprietario;
	            $pdf1->descr11_2   = $xender;
	            $pdf1->descr3_1    = $z01_cgmpri . " - " . $proprietario;
	            $pdf1->predescr3_1 = $z01_cgmpri . " - " . $proprietario;
	            $pdf1->descr3_2    = $xender;
	            $pdf1->predescr3_2 = $xender;
	            $pdf1->descr17     = $bql; //variavel q guarda o setor/quadra/lote


	          } else {

	            $sqlInfoEmpresa = "select z01_compl, z01_cgccpf,z01_numero,z01_ender,z01_bairro,z01_cep from empresa where q02_inscr = $q02_inscr";
	            $rsInfoEmpresa  = db_query($sqlInfoEmpresa);
	            db_fieldsmemory($rsInfoEmpresa, 0);

	            $pdf1->tipoinscr     = 'Inscrição: ';
	            $pdf1->nrinscr       = $q02_inscr;
	            $pdf1->munic         = $z01_munic;
	            $pdf1->premunic      = $z01_munic;
	            $pdf1->tipolograd    = 'Rua ';
	            $pdf1->pretipolograd = 'Rua ';
	            $pdf1->nomepriimo    = $z01_ender . " " . $z01_compl;
	            $pdf1->nrpri         = $z01_numero;
	            $pdf1->complpri      = $z01_compl;
	            $pdf1->bairropri     = $z01_bairro;
	            $pdf1->bairrocontri  = $z01_bairro;
	            $pdf1->prenomepri    = $z01_ender;
	            $pdf1->codDebito     = $tipo_debito;
              if ($tipo_debito == 19) {
                $pdf1->tipo_debito = $y77_descricao;
              }

	            /**
               * IMPRIME MODELO 2
               */
	            if ($impmodelo == 2) {

	              $sSqlDetalhe = "  select * from (																																							";
	              $sSqlDetalhe .= "  select r.k00_numcgm,																																					";
	              $sSqlDetalhe .= "         r.k00_receit,																																					";
	              $sSqlDetalhe .= "         null as k00_hist,																																			";
	              $sSqlDetalhe .= "         case when taborc.k02_codigo is null																										";
	              $sSqlDetalhe .= "              then tabplan.k02_reduz																														";
	              $sSqlDetalhe .= "              else taborc.k02_codrec																														";
	              $sSqlDetalhe .= "         end as codreduz,																																			";
	              $sSqlDetalhe .= "         t.k02_descr,																																					";
	              $sSqlDetalhe .= "         t.k02_drecei,																																					";
	              $sSqlDetalhe .= "         r.k00_dtoper as k00_dtoper,																														";
	              $sSqlDetalhe .= "         sum(r.k00_valor) as valor,																														";
	              $sSqlDetalhe .= "        (select (select k02_codigo																															";
	              $sSqlDetalhe .= "                   from tabrec																																	";
	              $sSqlDetalhe .= "                  where k02_recjur = r.k00_receit																							";
	              $sSqlDetalhe .= "                     or k02_recmul = r.k00_receit limit 1)																			";
	              $sSqlDetalhe .= "                is not null ) as codtipo																												";
	              $sSqlDetalhe .= "   from recibopaga r																				 																		";
	              $sSqlDetalhe .= "               inner join tabrec t 		on t.k02_codigo 		  = r.k00_receit										";
	              $sSqlDetalhe .= "               inner join tabrecjm 		on tabrecjm.k02_codjm = t.k02_codjm											";
	              $sSqlDetalhe .= "               left outer join taborc  on t.k02_codigo 			= taborc.k02_codigo 							";
	              $sSqlDetalhe .= "																			 and taborc.k02_anousu  = " . db_getsession("DB_anousu") . "	";
	              $sSqlDetalhe .= "               left outer join tabplan on t.k02_codigo 			= tabplan.k02_codigo 							";
	              $sSqlDetalhe .= "																			 and tabplan.k02_anousu = " . db_getsession("DB_anousu") . "	";
	              $sSqlDetalhe .= "  where r.k00_numnov = " . $oDadosPgtoUnica->k00_numnov . "													       		";
	              $sSqlDetalhe .= "    and r.k00_hist  <> 918																																			";
	              $sSqlDetalhe .= "  group by r.k00_dtoper,																																				";
	              $sSqlDetalhe .= "           r.k00_receit,																																				";
	              $sSqlDetalhe .= "           t.k02_descr,																																				";
	              $sSqlDetalhe .= "           t.k02_drecei,																																				";
	              $sSqlDetalhe .= "           r.k00_numcgm,																																				";
	              $sSqlDetalhe .= "           codreduz																																						";
	              $sSqlDetalhe .= " union																																													";
	              $sSqlDetalhe .= " select r.k00_numcgm,																																					";
	              $sSqlDetalhe .= "        r.k00_receit,																																					";
	              $sSqlDetalhe .= "        r.k00_hist,																																						";
	              $sSqlDetalhe .= "        case when taborc.k02_codigo is null																										";
	              $sSqlDetalhe .= "             then tabplan.k02_reduz																														";
	              $sSqlDetalhe .= "             else taborc.k02_codrec																														";
	              $sSqlDetalhe .= "        end as codreduz,																																				";
	              $sSqlDetalhe .= "        t.k02_descr,																																						";
	              $sSqlDetalhe .= "        t.k02_drecei,																																					";
	              $sSqlDetalhe .= "        r.k00_dtoper as k00_dtoper,																														";
	              $sSqlDetalhe .= "        sum(r.k00_valor) as valor,																															";
	              $sSqlDetalhe .= "       (select (select k02_codigo																															";
	              $sSqlDetalhe .= "                  from tabrec																																	";
	              $sSqlDetalhe .= "                 where k02_recjur = r.k00_receit																								";
	              $sSqlDetalhe .= "                    or k02_recmul = r.k00_receit limit 1)																			";
	              $sSqlDetalhe .= "               is not null ) as codtipo																												";
	              $sSqlDetalhe .= "   from recibopaga r																																						";
	              $sSqlDetalhe .= "               inner join tabrec t 		on t.k02_codigo 			= r.k00_receit										";
	              $sSqlDetalhe .= "               inner join tabrecjm 		on tabrecjm.k02_codjm = t.k02_codjm											";
	              $sSqlDetalhe .= "               left outer join taborc  on t.k02_codigo 			= taborc.k02_codigo 							";
	              $sSqlDetalhe .= "																		 	 and taborc.k02_anousu 	= " . db_getsession("DB_anousu") . "	";
	              $sSqlDetalhe .= "               left outer join tabplan on t.k02_codigo 			= tabplan.k02_codigo 							";
	              $sSqlDetalhe .= " 																		 and tabplan.k02_anousu = " . db_getsession("DB_anousu") . "	";
	              $sSqlDetalhe .= "  where r.k00_numnov = " . $oDadosPgtoUnica->k00_numnov . "																		";
	              $sSqlDetalhe .= "    and r.k00_hist   = 918																																			";
	              $sSqlDetalhe .= "  group by r.k00_dtoper,																																				";
	              $sSqlDetalhe .= "           r.k00_receit,																																				";
	              $sSqlDetalhe .= "           r.k00_hist,																																					";
	              $sSqlDetalhe .= "           t.k02_descr,																																				";
	              $sSqlDetalhe .= "						k02_drecei, 																																				";
	              $sSqlDetalhe .= "           r.k00_numcgm,																																				";
	              $sSqlDetalhe .= "           codreduz) as x order by k00_receit, valor desc																			";

	              $rsDetalhePgto = db_query($sSqlDetalhe) or die($sSqlDetalhe);

	              $pdf1->nome             = $z01_numcgm . "-" . $z01_nome;
	              $pdf1->ender            = $z01_ender . ", " . $z01_numero . " " . (! $z01_compl ? " " : ", " . $z01_compl);
	              $pdf1->tipobairro       = "Bairro:";
	              $pdf1->linhasdadospagto = pg_num_rows($rsDetalhePgto);
	              $pdf1->recorddadospagto = $rsDetalhePgto;
	              $pdf1->receita          = 'k00_receit';
	              $pdf1->valor            = 'valor';
	              $pdf1->receitared       = 'codreduz';
	              $pdf1->dreceita         = 'k02_descr';
	              $pdf1->ddreceita        = 'k02_drecei';
	              $pdf1->historico        = $k00_descr;
	              $pdf1->numpre           = db_numpre($oDadosPgtoUnica->k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($oDadosPgtoUnica->k00_numpar, "s", "0", 3, "e") : "000");
	              $pdf1->codigobarras     = $codigo_barras;
	              $pdf1->linhadigitavel   = $linha_digitavel;
	              $pdf1->dtvenc           = db_formatar($dtvencunic,'d');
	              $pdf1->cep              = $z01_cep;
	              $pdf1->cgccpf           = $z01_cgccpf;
	              $pdf1->nomepri          = $z01_ender;
	              $pdf1->datacalc         = db_formatar($dtvencunic,'d');

	              if ($tipo_debito == 3) {
	                if ($emiteVal == 1) {
	                  $pdf1->lEmiteVal = true;
	                } else {
	                  $pdf1->lEmiteVal = false;
	                }
	              }
	              //$k00_valor = $valor;
               

	              $resulttipo = db_query("select k03_tipo, k00_tipoagrup, k00_tercdigrecnormal,k00_msgrecibo  from arretipo where k00_tipo = $tipo_debito");
	              db_fieldsmemory($resulttipo, 0);

	              /**
                 * (Monta Histórico) VERIFICA TIPO ISSQN FIXO
                 */
	              if ($k03_tipo == 2 && $k00_tipoagrup != 2) {
	                $histparcela = "Exercicio: ";
	                $sqlhist  = " select distinct 		                                  ";
	                $sqlhist .= "  		   q01_anousu, 	                                  ";
	                $sqlhist .= "				 'ÚNICA' as k99_numpar	                        ";
	                $sqlhist .= "	  from db_reciboweb	                                  ";
	                $sqlhist .= "			inner join isscalc on q01_numpre = k99_numpre     ";
	                $sqlhist .= "	where k99_numpre_n = " . $oDadosPgtoUnica->k00_numnov . " ";
	                $sqlhist .= "	group by q01_anousu,                                  ";
	                $sqlhist .= "					 k99_numpar                                   ";
	                $sqlhist .= "	order by q01_anousu,                                  ";
	                $sqlhist .= "					 k99_numpar                                   ";
	                $result = db_query($sqlhist);

	                if (pg_numrows($result) != false) {

	                  $exercv = "0000";
	                  for($xy = 0; $xy < pg_numrows($result); $xy ++) {

	                    if ($exercv != pg_result($result, $xy, 0)) {

	                      $exercv       = pg_result($result, $xy, 0);
	                      $histparcela .= "  " . pg_result($result, $xy, 0) . ": Parc:";
	                    }

	                    $histparcela .= "-" . pg_result($result, $xy, 1);
	                  }
	                }

                /**
                 * (Monta Histórico) VERIFICA TIPO ISSQN VARIÁVEL
                 */
	              } else if ($k03_tipo == 3 && $k00_tipoagrup != 2) {

	                $histparcela  = $k00_descr . " => " . $q01_anousu . "\n";
	                $histparcela .= "Parcela:  " . $oDadosPgtoUnica->k00_numpar . "\n";

                /**
                 * (Monta Histórico) SE NÃO FOR VARIÁVEL OU FIXO
                 */
	              } else {
	                $histparcela = "";

	                $sqlhist  = " select *												 	                                            ";
	                $sqlhist .= "		from ( select distinct 				 	                                            ";
	                $sqlhist .= "		 						 arretipo.k00_tipo,	                                            ";
	                $sqlhist .= "		 						 k00_descr,					                                            ";
	                $sqlhist .= "		 						 'ÚNICA' as k99_numpar,				                                  ";
	                $sqlhist .= "		 						 case when divida.v01_exerc is not null then divida.v01_exerc	  ";
	                $sqlhist .= "		 						 else																													  ";
	                $sqlhist .= "		 						 	 case when termo.v07_parcel is not null then termo.v07_parcel ";
	                $sqlhist .= "		 							 else																													";
	                $sqlhist .= "		 								 extract (year from arrecad.k00_dtoper)											";
	                $sqlhist .= "		 							 end 																													";
	                $sqlhist .= "		 						 end as k00_origem																							";
	                $sqlhist .= "		 			  from db_reciboweb																										";
	                $sqlhist .= "		 					inner join arrecad  on k99_numpre 			 = k00_numpre 				    ";
	                $sqlhist .= "		 														 and k99_numpar 			 = k00_numpar					    ";
	                $sqlhist .= "		 					inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo		    ";
	                $sqlhist .= "		 					left  join divida   on divida.v01_numpre = arrecad.k00_numpre     ";
	                $sqlhist .= "		 														 and divida.v01_numpar = arrecad.k00_numpar	    ";
	                $sqlhist .= "		 					left  join termo    on termo.v07_numpre  = arrecad.k00_numpre	    ";
	                $sqlhist .= "						 where k99_numpre_n = " . $oDadosPgtoUnica->k00_numnov . "              ";
	                $sqlhist .= "		 	  ) as x	                                                                ";
	                $sqlhist .= "		order by 		                                                                ";
	                $sqlhist .= "		k00_origem,	                                                                ";
	                $sqlhist .= "		k00_descr,	                                                                ";
	                $sqlhist .= "		k99_numpar	                                                                ";

	                $result       = db_query($sqlhist) or die($sqlhist);
	                $histant      = pg_result($result, 0, "k00_origem") . "-" . pg_result($result, 0, "k00_descr");
	                $histparcela .= pg_result($result, 0, "k00_descr") . "=>" . pg_result($result, 0, "k00_origem") . " / PARCELAS: ";

	                for($xy = 0; $xy < pg_numrows($result); $xy ++) {

	                  if (pg_result($result, $xy, "k00_origem") . "-" . pg_result($result, $xy, "k00_descr") != $histant) {

	                    $histparcela .= "-" . pg_result($result, $xy, "k00_descr") . "=>" . pg_result($result, $xy, "k00_origem") . " / PARCELAS: ";
	                    $histant      = pg_result($result, $xy, "k00_origem") . "-" . pg_result($result, $xy, "k00_descr");
	                  }

	                  $histparcela .= pg_result($result, $xy, "k99_numpar") . " ";
	                }
	              }

	              $historico       = "Incrição: " . $q02_inscr . "\n" . $histparcela . "\n \n" . $k00_msgrecibo;
	              $pdf1->historico = $historico;
	            }
              /**
               * FIM MODELO 2
               */
	            $pdf1->descr11_1   = $z01_numcgm . " - " . $z01_nome;
	            $pdf1->descr11_2   = strtoupper($z01_ender) . ($z01_numero == "" ? "" : ', ' . $z01_numero . ($z01_compl != "" ? " / " . $z01_compl : ""));
	            $pdf1->descr3_1    = $z01_numcgm . " - " . $z01_nome;
	            $pdf1->predescr3_1 = $z01_numcgm . " - " . $z01_nome;
	            $pdf1->descr3_2    = strtoupper($z01_ender) . ($z01_numero == "" ? "" : ', ' . $z01_numero . '  ' . $z01_compl);
	            $pdf1->predescr3_2 = strtoupper($z01_ender) . ($z01_numero == "" ? "" : ', ' . $z01_numero . '  ' . $z01_compl);
              $pdf1->cgccpf      = $z01_cgccpf;
	          }
            if ($oRegraEmissao->isCobranca()) {

              $pdf1->descr12_1 .= $pdf1->tipodebito . "\n" . $pdf1->titulo1 . " - " . $pdf1->descr1 . " / " . $pdf1->titulo4 . " " . $pdf1->descr4_1 . " Parcela Única \n";

              (isset($bql) && $bql != "" ? " - " . $bql . "\n" : "\n") . (isset($obsdiver) && $obsdiver != "" ? $obsdiver : "") . "\n";

              $pdf1->predescr12_1 .= $pdf1->pretipodebito . "\n" . $pdf1->titulo1 . " - " . $pdf1->descr1 . " / " . $pdf1->titulo4 . " " . $pdf1->descr4_1 . " Parcela Única \n";

              (isset($bql) && $bql != "" ? " - " . $bql . "\n" : "\n") . (isset($obsdiver) && $obsdiver != "" ? $obsdiver : "") . "\n";
            }

            /**
             * PEGA A MENSAGEM DE PAGAMENTO E AS INSTRUÇÕES DA TABELA NUMPREF
             */
	          $rsmsgcarne = db_query("select k03_msgcarne, k03_msgbanco from numpref where k03_anousu = " . db_getsession("DB_anousu"));

	          if (pg_numrows($rsmsgcarne) > 0) {
	            db_fieldsmemory($rsmsgcarne, 0);
	          }

	          if (isset($k00_msguni2) && $k00_msguni2 != "") {
	            $pdf1->descr12_1 = $k00_msguni2; //msg unica, via contribuinte
	          } else {
	            $pdf1->descr12_1 = $k03_msgbanco . " Não aceitar apos vencimento "; //msg unica, via contribuinte
	          }

	          $pdf1->descr14 = db_formatar($dtvencunic,'d');

	          if ($iTercDig == '7') {

              /**
               * ISSQN VARIAVEL
               */
	            if ($k03_tipo == 3) {

	              $sqlaliq     = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
	              $rsIssvarano = db_query($sqlaliq);
	              $intNumrows  = pg_numrows($rsIssvarano);

	              if ($intNumrows == 0) {

	                db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
	                exit();
	              }

	              db_fieldsmemory($rsIssvarano, 0);
	              $pdf1->descr4_1 = $k00_numpar . 'a PARCELA   -   Alíquota ' . $q05_aliq . '%     EXERCÍCIO : ' . $q05_ano;
	            }

	            $pdf1->titulo7  = 'Valor ';
	            $pdf1->titulo15 = 'Valor ';
	            $pdf1->titulo13 = 'Valor da Receita Tributável';

	          } else {

	            if ($tipo_debito == 3 && $emiteVal == 2) {

	              $pdf1->descr15  = "";
	              $pdf1->valtotal = "";
	              $pdf1->descr7   = "";
	            } else {

	              $pdf1->descr15  = "R$ " . db_formatar($k00_valor, 'f');
	              $pdf1->valtotal = db_formatar($k00_valor, 'f');
	              $pdf1->descr7   = "R$ " . db_formatar($k00_valor, 'f');
	            }

	            $pdf1->predescr7 = db_formatar($k00_valor, 'f');
	          }

	          $pdf1->descr12_2       = '- PARCELA ÚNICA COM ' . $k00_percdes . '% DE DESCONTO';
	          $pdf1->linha_digitavel = $linha_digitavel;
	          $pdf1->codigo_barras   = $codigo_barras;

            /**
             * BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO
             *
             * verifica se é ficha e busca o codigo do banco
             */
	          if ($oRegraEmissao->isCobranca()) {

	            $rsConsultaBanco = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
	            $oBanco          = db_utils::fieldsMemory($rsConsultaBanco, 0);
	            $pdf1->numbanco  = $oBanco->db90_codban . "-" . $oBanco->db90_digban;
	            $pdf1->banco     = $oBanco->db90_abrev;

	            try {
	              $pdf1->imagemlogo = $oConvenio->getImagemBanco();
	            } catch ( Exception $eExeption ) {
	              db_redireciona("db_erros.php?fechar=true&db_erro=" . $eExeption->getMessage());
	            }
	          }

	          $pdf1->imprime();
	          $intixxx ++;
	        }
	      }

	      $unica           = 2;
	      $pdf1->descr12_1 = '';

	      if ($sounica == '') {

	        $pdf1->objpdf->Output();
	        exit();
	      }
	    }
    }
    /**
     * FIM PARCELA UNICA
     */

    if  ( $lImprimeParcelas ) {

      $valores    = split("P", $numpres [$volta]);
      $k00_numpre = $valores [0];
      $k00_numpar = $valores [1];
      $k03_anousu = $H_ANOUSU;

      if ($k00_codbco == "" || $k00_codage == "") {

        $errobco = "Código do banco e ou agência zerado ou nulo!";
        db_redireciona("db_erros.php?fechar=true&db_erro=Verifique cadastro do tipo de débito - $tipo_debito <br> $errobco");
        exit();
      }

      $ssqlnumpres  = " select distinct                                                          ";
      $ssqlnumpres .= "		   	 arrecad.k00_dtvenc as vencimento_recibo,                          ";
      $ssqlnumpres .= "        arrecad.k00_numpre as numpre_recibo,                              ";
      $ssqlnumpres .= "        arrecad.k00_numpar as numpar_recibo                               ";
      $ssqlnumpres .= "	  from arrecad                                                           ";
      $ssqlnumpres .= "        inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre ";
      $ssqlnumpres .= "  			                     and arreinscr.k00_inscr  = $q02_inscr         ";
      $ssqlnumpres .= " 					                 and arrecad.k00_tipo     = $k00_tipo          ";
      $ssqlnumpres .= " 					                 and arrecad.k00_numpar   = $k00_numpar        ";
      $ssqlnumpres .= "  order by arrecad.k00_numpar                                             ";
      $rsnumpres    = db_query($ssqlnumpres);

      if ($tipo_debito != "3") {

        $onumpres = db_utils::fieldsmemory($rsnumpres, 0);
        db_inicio_transacao();

        try {

          $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

        	$oRecibo = new recibo(2, null, 6);
          $oRecibo->addNumpre($onumpres->numpre_recibo, $onumpres->numpar_recibo);
          $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
          $oRecibo->setDataVencimentoRecibo($onumpres->vencimento_recibo);
          $oRecibo->emiteRecibo($lConvenioCobrancaValido);

          if ($lConvenioCobrancaValido) {
            CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
          }

        } catch ( Exception $eException ) {

          db_fim_transacao(true);
        	db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
          exit;
        }

        db_fim_transacao();
      }

      if ($tipo_debito == "3") {

        $DadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $H_DATAUSU, $H_ANOUSU);
        $k00_numnov     = $k00_numpre;
      } else {

        $sWhere         = "arreinscr.k00_inscr = $q02_inscr and arrecad.k00_tipo = $k00_tipo ";
        $DadosPagamento = debitos_numpre_carne_recibopaga($onumpres->numpre_recibo, $k00_numpar, $H_DATAUSU, $H_ANOUSU, db_getsession('DB_instit'), $sWhere);
      }

      db_fieldsmemory($DadosPagamento, 0);

      $sql1 = "select k00_dtvenc as datavencimento,
                        k00_dtvenc,
                        k00_numtot,
                        k00_dtoper
                   from arrecad
                  where k00_numpre = $k00_numpre
                    and k00_numpar = $k00_numpar limit 1";

      db_fieldsmemory(db_query($sql1), 0);

      $k00_dtvenc               = db_formatar($k00_dtvenc, 'd');
      $pdf1->data_processamento = db_formatar($k00_dtoper, 'd');

      // alterei para buscar o terceiro digito pelo tipo de debito da tabela arretipo
      $sqlvalor = "select k00_impval,k00_tercdigcarnenormal from arretipo where k00_tipo = $tipo_debito";
      db_fieldsmemory(db_query($sqlvalor), 0);

      if (! isset($k00_tercdigcarnenormal) || $k00_tercdigcarnenormal == "") {
        db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
      }

      $iTercDig = $k00_tercdigcarnenormal;
      $ss       = $ninfla;

      if ($tipo_debito == 3 && $emiteVal == 2) {
        $total = 0;
      }

      if (($total == 0) || (substr($k00_dtvenc, 6, 4) > date("Y", $H_DATAUSU))) {

        $iTercDig = 7;
        $vlrbar   = "00000000000";

        if ($total != 0) {

          $k00_valor = $qinfla;
          $ninfla    = $ss;
        }
      } else {

        $iTercDig = $k00_tercdigcarnenormal;
        $ss       = $ninfla;
      }

      if ($k00_impval == 't') {

        if ($k03_tipo == 3) {

          $rsAnoissvar  = db_query("select q05_ano from issvar where q05_numpre = $k00_numpre");
          $intAnoissvar = pg_numrows($rsAnoissvar);

          db_fieldsmemory($rsAnoissvar, 0);

          if ($intAnoissvar > 0 && $q05_ano <= date("Y", $H_DATAUSU)) {

            $k00_valor = $total;
            $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
            $ninfla    = '';
          }
        } else {

          $k00_valor = $total;
          $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          $ninfla    = '';
        }

      } else {

        $k00_valor = $qinfla;
        $iTercDig  = 7;
        $vlrbar    = "00000000000";

      }

      $dtvenc         = substr($k00_dtvenc, 6, 4) . substr($k00_dtvenc, 3, 2) . substr($k00_dtvenc, 0, 2);
      $datavencimento = $dtvenc;

      $tmpdt = substr($db_datausu, 0, 4) . substr($db_datausu, 5, 2) . substr($db_datausu, 8, 2);

      if ($tmpdt > $datavencimento && $k00_valor > 0) {
        $datavencimento = $tmpdt;
      }

      if (isset($emiscarneiframe) && $emiscarneiframe == 'n') {

        if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu')) {
          continue;
        }
      }

      if ($oRegraEmissao->isCobranca()) {

        if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0) {

          $k00_valor = 0;
          $especie   = $ninfla;
          $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
        } else {
          $especie = 'R$';
          $histinf = "";
        }

        if ($datavencimento < date('Ymd', db_getsession('DB_datausu')) && $k00_valor > 0) {

          $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original " . $k00_dtvenc;
          $k00_dtvenc = date('d/m/Y', $H_DATAUSU);
        } else {
          $msgvencida = "";
        }

        if (isset($emiteVal) && $emiteVal == 2) {
          $k00_valor = 0;
        }

        if (isset($qinfla) && $qinfla != '' && $k00_valor == 0) {
          $k00_valor = $qinfla;
        }
      }

      try {

        $iParcela  = $tipo_debito=="3"?$k00_numpar:0;
        $oConvenio = new convenio($oRegraEmissao->getConvenio(), $k00_numnov, $iParcela, $k00_valor, $vlrbar, $datavencimento, $iTercDig);
      } catch ( Exception $eExeption ) {

        db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
        exit();
      }

      $codigo_barras   = $oConvenio->getCodigoBarra();
      $linha_digitavel = $oConvenio->getLinhaDigitavel();

      if ( $oRegraEmissao->isCobranca() ) {

  		  if(strlen(trim($oConvenio->getConvenioCobranca())) == 7) {
  		    $pdf1->nosso_numero = trim($oConvenio->getConvenioCobranca()) . str_pad($k00_numnov."00",10,0,STR_PAD_LEFT);
  		  } else {
  		    $pdf1->nosso_numero = $oConvenio->getNossoNumero();
  		  }
      }

      $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
      $pdf1->carteira        = $oConvenio->getCarteira();

      $numpre = db_sqlformatar($k00_numpre, 8, '0') . '000999';
      $numpre = $numpre . db_CalculaDV($numpre, 11);

      global $pdf;

      $pdf1->descr12_2  = '';
      $pdf1->titulo1    = $descr;
      $pdf1->descr1     = $numero;
      $pdf1->descr2     = db_numpre($k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($k00_numpar, "s", "0", 3, "e") : "000");
      $pdf1->tipo_exerc = "$k00_tipo / " . substr($k00_dtoper, 0, 4);
      if ($k03_tipo == 19) {
        $pdf1->tipodebito = $y77_descricao;
      }
      
      /**
       * PEGA AS RECEITAS COM OS VALORES
       */
      $sqlReceitas = "select k00_receit as codreceita,
                                     k02_descr  as descrreceita,
                                     case when taborc.k02_codigo is not null then k02_codrec
                                          when tabplan.k02_codigo is not null then k02_reduz
                                     end  as reduzreceita,
                                     k00_valor as valreceita
                                from arrecad
                                     inner join tabrec on tabrec.k02_codigo = arrecad.k00_receit
                                     left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo
                                                       and taborc.k02_anousu   = " . db_getsession('DB_anousu') . "
                                     left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo
                                                       and tabplan.k02_anousu  = " . db_getsession('DB_anousu') . "
                              where k00_numpre in ( select distinct
                               														 arrecad.k00_numpre
  																										from arreinscr
  																										     inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre
  																										     inner join isscalc on isscalc.q01_numpre = arreinscr.k00_numpre
  																												                   and isscalc.q01_anousu = " . db_getsession('DB_anousu') . "
  																									 where arreinscr.k00_inscr = $q02_inscr
  																									   and arrecad.k00_tipo    = $k00_tipo	)
                                and k00_numpar = $k00_numpar ";

      $rsReceitas         = db_query($sqlReceitas);
      $sDescricaoReceitas = "";
      $traco              = "";

      $pdf1->descr12_2    = "";
      $pdf1->descr4_2     = "";

      $intnumrows         = pg_num_rows($rsReceitas);

      for($x = 0; $x < $intnumrows; $x ++) {

        db_fieldsmemory($rsReceitas, $x);

        $pdf1->arraycodreceitas   [$x] = $codreceita;
        $pdf1->arrayreduzreceitas [$x] = $reduzreceita;
        $pdf1->arraydescrreceitas [$x] = $descrreceita;
        $pdf1->arrayvalreceitas   [$x] = $valreceita;
        $sDescricaoReceitas           .= $traco . $descrreceita . "(" . trim(db_formatar($valreceita, 'f')) . ")";
        $traco                         = " - ";
      }

      if (isset($vlrjuros) && $vlrjuros != "" && $vlrjuros != 0) {

        $pdf1->arraycodreceitas   [$x] = "";
        $pdf1->arrayreduzreceitas [$x] = "";
        $pdf1->arraydescrreceitas [$x] = "Juros : ";
        $pdf1->arrayvalreceitas   [$x] = $vlrjuros;
      }

      if (isset($vlrmulta) && $vlrmulta != "" && $vlrmulta != 0) {

        $x ++;
        $pdf1->arraycodreceitas   [$x] = "";
        $pdf1->arrayreduzreceitas [$x] = "";
        $pdf1->arraydescrreceitas [$x] = "Multa : ";
        $pdf1->arrayvalreceitas   [$x] = $vlrmulta;
      }

      $sqlInfoEmpresa = "select z01_cgccpf,z01_bairro,z01_munic,z01_ender from empresa where q02_inscr = $q02_inscr";
      $rsInfoEmpresa  = db_query($sqlInfoEmpresa);
      db_fieldsmemory($rsInfoEmpresa, 0);

      $pdf1->tipocompl = 'Número:';

      $pdf1->tipoinscr     = 'Inscrição: ';
      $pdf1->nrinscr       = $q02_inscr;
      $pdf1->munic         = $z01_munic;
      $pdf1->premunic      = $z01_munic;
      $pdf1->tipolograd    = 'Rua ';
      $pdf1->pretipolograd = 'Rua ';
      $pdf1->nomepriimo    = $z01_ender;
      $pdf1->nrpri         = $z01_numero;
      $pdf1->complpri      = $z01_compl;
      $pdf1->bairropri     = $z01_bairro;
      $pdf1->bairrocontri  = $z01_bairro;
      $pdf1->prenomepri    = $z01_ender;
      $pdf1->cgccpf        = $z01_cgccpf;

      $pdf1->descr11_1     = $z01_numcgm . " - " . $z01_nome;
      $pdf1->descr11_2     = strtoupper($z01_ender) . ($z01_numero == "" ? "" : ', ' . $z01_numero . ($z01_compl != "" ? " / " . $z01_compl : ""));
      $pdf1->descr11_3     = $z01_bairro;
      $pdf1->descr3_1      = $z01_numcgm . " - " . $z01_nome;
      $pdf1->descr3_2      = strtoupper($z01_ender) . ($z01_numero == "" ? "" : ', ' . $z01_numero . '  ' . $z01_compl);
      $pdf1->descr3_3      = $z01_bairro;
      $pdf1->cep           = $z01_cep;
      $pdf1->precep        = $z01_cep;
      $pdf1->uf            = $z01_uf;
      $pdf1->munic         = $z01_munic;
      $pdf1->premunic      = $z01_munic;
      $pdf1->tipolograd    = 'Rua ';
      $pdf1->pretipolograd = 'Rua ';
      $pdf1->nomepri       = $z01_ender;
      $pdf1->prenomepri    = $z01_ender;
      $pdf1->codDebito     = $tipo_debito;
      if ($tipo_debito == 19) {
        $pdf1->tipodebito = $y77_descricao;
      }

      /**
       * IMPRIME MODELO 2
       */
      if ($impmodelo == 2) {

        $sSqlDetalhe  = "  select * from (																																							";
        $sSqlDetalhe .= "  select r.k00_numcgm,																																					";
        $sSqlDetalhe .= "         r.k00_receit,																																					";
        $sSqlDetalhe .= "         null as k00_hist,																																			";
        $sSqlDetalhe .= "         case when taborc.k02_codigo is null																										";
        $sSqlDetalhe .= "              then tabplan.k02_reduz																														";
        $sSqlDetalhe .= "              else taborc.k02_codrec																														";
        $sSqlDetalhe .= "         end as codreduz,																																			";
        $sSqlDetalhe .= "         t.k02_descr,																																					";
        $sSqlDetalhe .= "         t.k02_drecei,																																					";
        $sSqlDetalhe .= "         r.k00_dtoper as k00_dtoper,																														";
        $sSqlDetalhe .= "         sum(r.k00_valor) as valor,																														";
        $sSqlDetalhe .= "        (select (select k02_codigo																															";
        $sSqlDetalhe .= "                   from tabrec																																	";
        $sSqlDetalhe .= "                  where k02_recjur = r.k00_receit																							";
        $sSqlDetalhe .= "                     or k02_recmul = r.k00_receit limit 1)																			";
        $sSqlDetalhe .= "                is not null ) as codtipo																												";
        $sSqlDetalhe .= "   from recibopaga r																				 																		";
        $sSqlDetalhe .= "               inner join tabrec t 		on t.k02_codigo 		  = r.k00_receit										";
        $sSqlDetalhe .= "               inner join tabrecjm 		on tabrecjm.k02_codjm = t.k02_codjm											";
        $sSqlDetalhe .= "               left outer join taborc  on t.k02_codigo 			= taborc.k02_codigo 							";
        $sSqlDetalhe .= "																			 and taborc.k02_anousu  = " . db_getsession("DB_anousu") . "	";
        $sSqlDetalhe .= "               left outer join tabplan on t.k02_codigo 			= tabplan.k02_codigo 							";
        $sSqlDetalhe .= "																			 and tabplan.k02_anousu = " . db_getsession("DB_anousu") . "	";
        $sSqlDetalhe .= "  where r.k00_numnov = " . $k00_numnov . "																													";
        $sSqlDetalhe .= "    and r.k00_hist  <> 918																																			";
        $sSqlDetalhe .= "  group by r.k00_dtoper,																																				";
        $sSqlDetalhe .= "           r.k00_receit,																																				";
        $sSqlDetalhe .= "           t.k02_descr,																																				";
        $sSqlDetalhe .= "           t.k02_drecei,																																				";
        $sSqlDetalhe .= "           r.k00_numcgm,																																				";
        $sSqlDetalhe .= "           codreduz																																						";
        $sSqlDetalhe .= " union																																													";
        $sSqlDetalhe .= " select r.k00_numcgm,																																					";
        $sSqlDetalhe .= "        r.k00_receit,																																					";
        $sSqlDetalhe .= "        r.k00_hist,																																						";
        $sSqlDetalhe .= "        case when taborc.k02_codigo is null																										";
        $sSqlDetalhe .= "             then tabplan.k02_reduz																														";
        $sSqlDetalhe .= "             else taborc.k02_codrec																														";
        $sSqlDetalhe .= "        end as codreduz,																																				";
        $sSqlDetalhe .= "        t.k02_descr,																																						";
        $sSqlDetalhe .= "        t.k02_drecei,																																					";
        $sSqlDetalhe .= "        r.k00_dtoper as k00_dtoper,																														";
        $sSqlDetalhe .= "        sum(r.k00_valor) as valor,																															";
        $sSqlDetalhe .= "       (select (select k02_codigo																															";
        $sSqlDetalhe .= "                  from tabrec																																	";
        $sSqlDetalhe .= "                 where k02_recjur = r.k00_receit																								";
        $sSqlDetalhe .= "                    or k02_recmul = r.k00_receit limit 1)																			";
        $sSqlDetalhe .= "               is not null ) as codtipo																												";
        $sSqlDetalhe .= "   from recibopaga r																																						";
        $sSqlDetalhe .= "               inner join tabrec t 		on t.k02_codigo 			= r.k00_receit										";
        $sSqlDetalhe .= "               inner join tabrecjm 		on tabrecjm.k02_codjm = t.k02_codjm											";
        $sSqlDetalhe .= "               left outer join taborc  on t.k02_codigo 			= taborc.k02_codigo 							";
        $sSqlDetalhe .= "																		 	 and taborc.k02_anousu 	= " . db_getsession("DB_anousu") . "	";
        $sSqlDetalhe .= "               left outer join tabplan on t.k02_codigo 			= tabplan.k02_codigo 							";
        $sSqlDetalhe .= " 																		 and tabplan.k02_anousu = " . db_getsession("DB_anousu") . "	";
        $sSqlDetalhe .= "  where r.k00_numnov = " . $k00_numnov . "																													";
        $sSqlDetalhe .= "    and r.k00_hist   = 918																																			";
        $sSqlDetalhe .= "  group by r.k00_dtoper,																																				";
        $sSqlDetalhe .= "           r.k00_receit,																																				";
        $sSqlDetalhe .= "           r.k00_hist,																																					";
        $sSqlDetalhe .= "           t.k02_descr,																																				";
        $sSqlDetalhe .= "						k02_drecei, 																																				";
        $sSqlDetalhe .= "           r.k00_numcgm,																																				";
        $sSqlDetalhe .= "           codreduz) as x order by k00_receit, valor desc																			";

        $rsDetalhePgto = db_query($sSqlDetalhe) or die($sSqlDetalhe);

        $pdf1->nome             = $z01_numcgm . "-" . $z01_nome;
        $pdf1->ender            = $z01_ender . ", " . $z01_numero . " " . (! $z01_compl ? " " : ", " . $z01_compl);
        $pdf1->tipobairro       = "Bairro:";
        $pdf1->linhasdadospagto = pg_num_rows($rsDetalhePgto);
        $pdf1->recorddadospagto = $rsDetalhePgto;
        $pdf1->receita          = 'k00_receit';
        $pdf1->valor            = 'valor';
        $pdf1->receitared       = 'codreduz';
        $pdf1->dreceita         = 'k02_descr';
        $pdf1->ddreceita        = 'k02_drecei';
        $pdf1->historico        = $k00_descr;
        $pdf1->numpre           = db_numpre($k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($k00_numpar, "s", "0", 3, "e") : "000");
        $pdf1->codigobarras     = $codigo_barras;
        $pdf1->linhadigitavel   = $linha_digitavel;
        $pdf1->dtvenc           = $k00_dtvenc;

        if ($tipo_debito == 3) {

          if (isset($emiteVal) && $emiteVal == 1) {
            $pdf1->lEmiteVal = true;
          } else {
            $pdf1->lEmiteVal = false;
          }
        }

        $resulttipo = db_query("select k03_tipo, k00_tipoagrup, k00_tercdigrecnormal,k00_msgrecibo  from arretipo where k00_tipo = $tipo_debito");
        db_fieldsmemory($resulttipo, 0);

        /**
         * (Monta Histórico) VERIFICA TIPO ISSQN FIXO
         */
        if ($k03_tipo == 2 && $k00_tipoagrup != 2) {

          $histparcela = "Exercicio: ";
          $sqlhist     = " select distinct 		                              ";
          $sqlhist    .= "  		   q01_anousu, 	                              ";
          $sqlhist    .= "				 k99_numpar		                              ";
          $sqlhist    .= "	  from db_reciboweb	                              ";
          $sqlhist    .= "			inner join isscalc on q01_numpre = k99_numpre ";
          $sqlhist    .= "	where k99_numpre_n = $k00_numnov                  ";
          $sqlhist    .= "	group by q01_anousu,                              ";
          $sqlhist    .= "					 k99_numpar                               ";
          $sqlhist    .= "	order by q01_anousu,                              ";
          $sqlhist    .= "					 k99_numpar                               ";
          $result      = db_query($sqlhist);

          if (pg_numrows($result) != false) {

            $exercv = "0000";
            for($xy = 0; $xy < pg_numrows($result); $xy ++) {

              if ($exercv != pg_result($result, $xy, 0)) {

                $exercv       = pg_result($result, $xy, 0);
                $histparcela .= "  " . pg_result($result, $xy, 0) . ": Parc:";
              }

              $histparcela .= "-" . pg_result($result, $xy, 1);
            }
          }

        /**
         * (Monta Histórico) VERIFICA TIPO ISSQN VARIÁVEL
         */
        } else if ($k03_tipo == 3 && $k00_tipoagrup != 2) {

          $histparcela  = $k00_descr . " => " . $q01_anousu . "\n";
          $histparcela .= "Parcela:  " . $k00_numpar . "\n";

        /**
         * (Monta Histórico) SE NÃO FOR VARIÁVEL OU FIXO
         */
        } else {

          $histparcela = "";

          $sqlhist     = " select *												 	                                              ";
          $sqlhist    .= "		from ( select distinct 				 	                                            ";
          $sqlhist    .= "		 						 arretipo.k00_tipo,	                                            ";
          $sqlhist    .= "		 						 k00_descr,					                                            ";
          $sqlhist    .= "		 						 k99_numpar,				                                            ";
          $sqlhist    .= "		 						 case when divida.v01_exerc is not null then divida.v01_exerc	  ";
          $sqlhist    .= "		 						 else																													  ";
          $sqlhist    .= "		 						 	 case when termo.v07_parcel is not null then termo.v07_parcel ";
          $sqlhist    .= "		 							 else																													";
          $sqlhist    .= "		 								 extract (year from arrecad.k00_dtoper)											";
          $sqlhist    .= "		 							 end 																													";
          $sqlhist    .= "		 						 end as k00_origem																							";
          $sqlhist    .= "		 			  from db_reciboweb																										";
          $sqlhist    .= "		 					inner join arrecad  on k99_numpre 			 = k00_numpre 				    ";
          $sqlhist    .= "		 														 and k99_numpar 			 = k00_numpar					    ";
          $sqlhist    .= "		 					inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo		    ";
          $sqlhist    .= "		 					left  join divida   on divida.v01_numpre = arrecad.k00_numpre     ";
          $sqlhist    .= "		 														 and divida.v01_numpar = arrecad.k00_numpar	    ";
          $sqlhist    .= "		 					left  join termo    on termo.v07_numpre  = arrecad.k00_numpre	    ";
          $sqlhist    .= "						 where k99_numpre_n = $k00_numnov                                   ";
          $sqlhist    .= "		 	  ) as x	                                                                ";
          $sqlhist    .= "		order by 		                                                                ";
          $sqlhist    .= "		k00_origem,	                                                                ";
          $sqlhist    .= "		k00_descr,	                                                                ";
          $sqlhist    .= "		k99_numpar	                                                                ";

          $result       = db_query($sqlhist) or die($sqlhist);
          $histant      = pg_result($result, 0, "k00_origem") . "-" . pg_result($result, 0, "k00_descr");
          $histparcela .= pg_result($result, 0, "k00_descr") . "=>" . pg_result($result, 0, "k00_origem") . " / PARCELAS: ";

          for($xy = 0; $xy < pg_numrows($result); $xy ++) {

            if (pg_result($result, $xy, "k00_origem") . "-" . pg_result($result, $xy, "k00_descr") != $histant) {

              $histparcela .= "-" . pg_result($result, $xy, "k00_descr") . "=>" . pg_result($result, $xy, "k00_origem") . " / PARCELAS: ";
              $histant      = pg_result($result, $xy, "k00_origem") . "-" . pg_result($result, $xy, "k00_descr");
            }

            $histparcela .= pg_result($result, $xy, "k99_numpar") . " ";
          }
        }

        $historico       = "Incrição: " . $q02_inscr . "\n" . $histparcela . "\n \n" . $k00_msgrecibo;
        $pdf1->historico = $historico;
      }
      /**
       * FIM MODELO 2
       */

      if ($k00_hist1 == '' || $k00_hist2 == '') {

        $pdf1->descr4_1            = $k00_numpar . 'a PARCELA';
        $pdf1->historicoparcela    = $k00_numpar . 'a PARCELA';
        $pdf1->prehistoricoparcela = $k00_numpar . 'a PARCELA';

        if ($k03_tipo == 16) {

          $sqldiversos = " select distinct dv05_obs
                                     from termo
                                          inner join termodiver on dv10_parcel = v07_parcel
                                          inner join diversos on dv05_coddiver = dv10_coddiver and dv05_instit = " . db_getsession('DB_instit') . "
                                    where v07_numpre = $k00_numpre";

          $resultdiversos = db_query($sqldiversos);

          if (pg_numrows($resultdiversos) > 0) {

            db_fieldsmemory($resultdiversos, 0, true);
            $pdf1->descr4_2    = substr($dv05_obs, 0, 100);
            $pdf1->predescr4_2 = substr($dv05_obs, 0, 100);
            $obsdiver = substr($dv05_obs, 0, 100);
          }
        } else if ($k03_tipo == 7) {

          $sqldiversos    = "select distinct dv05_obs from diversos where dv05_numpre = $k00_numpre and dv05_instit = " . db_getsession('DB_instit') . "";
          $resultdiversos = db_query($sqldiversos);

          if (pg_numrows($resultdiversos) > 0) {

            db_fieldsmemory($resultdiversos, 0, true);
            $pdf1->descr4_2    = substr($dv05_obs, 0, 100);
            $pdf1->predescr4_2 = substr($dv05_obs, 0, 100);
            $obsdiver          = substr($dv05_obs, 0, 100);
          }
        }
      } else {

        if (isset($k00_hist1) && $k00_hist1 != "" && $k00_hist1 != ".") {
          $pdf1->descr4_1 = $k00_hist1;
        }

        if (isset($k00_hist2) && $k00_hist2 != "" && $k00_hist2 != ".") {

          $pdf1->descr4_2    = $k00_hist2;
          $pdf1->predescr4_2 = $k00_hist2;
        }
      }

      if (isset($obs)) {

        $pdf1->titulo13 = 'Observação';
        $pdf1->descr13  = $obs;
      }
      if ($k03_tipo == 2) {

        $pdf1->titulo4  = 'Atividade';
        $pdf1->descr4_1 = '- ' . $q07_ativ . '-' . $q03_descr;
        $pdf1->titulo13 = 'Atividade';
        $pdf1->descr13  = $q07_ativ;
      } else if (($k03_tipo == 6) || ($k03_tipo == 13)) {

        $pdf1->titulo4  = 'Parcelamento';
        $pdf1->descr4_1 = '- ' . $v07_parcel . $exercicio;
        $pdf1->titulo13 = 'Parcelamento';
        $pdf1->descr13  = $v07_parcel;
      }

      $pdf1->descr5 = $k00_numpar . ' / ' . $k00_numtot;

      $tmpdta       = split("/", $k00_dtvenc);
      $tmpdtvenc    = $tmpdta [2] . "-" . $tmpdta [1] . "-" . $tmpdta [0];

      if ($db_datausu > $tmpdtvenc && $k00_valor > 0) {

        $pdf1->dtparapag    = db_formatar($db_datausu, 'd');
        $pdf1->datacalc     = db_formatar($db_datausu, 'd');
        $pdf1->predatacalc  = db_formatar($db_datausu, 'd');
        $pdf1->confirmdtpag = 't';
      } else {

        $pdf1->dtparapag    = $k00_dtvenc;
        $pdf1->datacalc     = $k00_dtvenc;
        $pdf1->predatacalc  = $k00_dtvenc;
        $pdf1->confirmdtpag = 't';
      }

      $pdf1->descr6     = $k00_dtvenc;
      $pdf1->predescr6  = $k00_dtvenc;
      $pdf1->titulo8    = $descr;
      $pdf1->pretitulo8 = $descr;
      $pdf1->descr8     = $numero;
      $pdf1->predescr8  = $numero;
      $pdf1->descr9     = db_numpre($k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($k00_numpar, "s", "0", 3, "e") : "000");
      $pdf1->predescr9  = db_numpre($k00_numnov, 0) . ($tipo_debito == "3" ? db_formatar($k00_numpar, "s", "0", 3, "e") : "000");
      $pdf1->descr10    = $k00_numpar . ' / ' . $k00_numtot;
      $pdf1->descr14    = $k00_dtvenc;

      if ($total == 0) {

        /**
         * ISSQN VARIAVEL
         */
        if ($k03_tipo == 3) {

          $pdf1->tipodebito = $k00_descr . " " . $q01_anousu;
          $sqlaliq          = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
          $rsIssvarano      = db_query($sqlaliq);
          $intNumrows       = pg_numrows($rsIssvarano);

          if ($intNumrows == 0) {

            db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
            exit();
          }

          db_fieldsmemory($rsIssvarano, 0);
          $pdf1->descr4_1 = $k00_numpar . 'a PARCELA   -   Alíquota ' . $q05_aliq . '%     EXERCÍCIO : ' . $q05_ano;
        }

        $pdf1->titulo7   = 'Valor ';
        $pdf1->titulo15  = 'Valor';
        $pdf1->titulo13  = 'Valor da Receita Tributável';
        $pdf1->descr15   = '';
        $pdf1->valtotal  = '';
        $pdf1->descr7    = '';
        $pdf1->predescr7 = '';

      } else {

        if ($tipo_debito == 3 && $emiteVal == 2) {

          $pdf1->descr15  = "";
          $pdf1->valtotal = "";
          $pdf1->descr7   = "";
        } else {

          $pdf1->descr15  = ($ninfla == '' ? 'R$  ' . db_formatar($k00_valor, 'f') : $ninfla . '  ' . $k00_valor);
          $pdf1->valtotal = db_formatar($k00_valor, 'f');
          $pdf1->descr7   = ($ninfla == '' ? 'R$  ' . db_formatar($k00_valor, 'f') : $ninfla . '  ' . $k00_valor);
        }

        $pdf1->predescr7 = ($ninfla == '' ? 'R$  ' . db_formatar($k00_valor, 'f') : $ninfla . '  ' . $k00_valor);
      }

      if ($oRegraEmissao->isCobranca()) {

        $pdf1->predescr12_1 .= $pdf1->pretipodebito . "\n" . $pdf1->titulo1 . " - " . $pdf1->descr1 . " / " . $pdf1->titulo4 . " " . $pdf1->descr4_1 . " Parcela - " . $k00_numpar . "/" . $k00_numtot . "\n" . (isset($bql) && $bql != "" ? " - " . $bql . "\n" : "\n") . (isset($obsdiver) && $obsdiver != "" ? $obsdiver : "") . "\n";

        if ( isset($pdf1->tipodebito) && trim($pdf1->tipodebito) != '') {
          $pdf1->descr12_1  .= $pdf1->tipodebito . "\n";
        }

        $pdf1->descr12_1    .= $pdf1->titulo1 . " - " . $pdf1->descr1 . " / " . $pdf1->titulo4 . " " . $pdf1->descr4_1 . " Parcela - " . $k00_numpar . "/" . $k00_numtot . "\n";
        $pdf1->descr12_1    .= (isset($bql) && $bql != "" ? " - " . $bql."\n" : "");
        $pdf1->descr12_1    .= (isset($obsdiver) && $obsdiver != "" ? $obsdiver."\n" : "");
      }

      $rsmsgcarne = db_query("select k03_msgcarne,
                                             k03_msgbanco
                                        from numpref
                                       where k03_anousu = " . db_getsession("DB_anousu"));

      if (pg_numrows($rsmsgcarne) > 0) {
        db_fieldsmemory($rsmsgcarne, 0);
      }

      if ($pagabanco == 't') {

        if (isset($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {

          if (isset($k00_msgparcvenc2) && $k00_msgparcvenc2 != "") {
            $pdf1->descr12_1 .= $k00_msgparcvenc2 . " " . $histinf . " " . $msgvencida;
          }
        } else {

          if (isset($k00_msgparc2) && $k00_msgparc2 != "") {
            $pdf1->descr12_1 .= $k00_msgparc2 . " " . $histinf . " " . $msgvencida;
          } elseif (isset($k03_msgbanco) && $k03_msgbanco != "") {
            $pdf1->descr12_1 .= $k03_msgbanco . " Não aceitar após vencimento";
          }
        }
      } else {

        if (isset($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
          $pdf1->descr12_1 .= $k00_msgparcvenc2 . " " . $histinf . " " . $msgvencida;
        } elseif (isset($k00_msgparc2) && $k00_msgparc2 != "") {
          $pdf1->descr12_1 .= $k00_msgparc2 . " " . $histinf . " " . $msgvencida;
        } elseif (isset($k03_msgbanco) && $k03_msgbanco != "") {
          $pdf1->descr12_1 .= $k03_msgbanco . " Após o vencimento cobrar juros de 1%a.m e multa de 2%";
        } else {
          $pdf1->descr12_1 .= '- O PAGAMENTO DEVERÁ SER EFETUADO SOMENTE NA PREFEITURA.' . " " . $histinf . " " . $msgvencida;
        }
      }

      $sqlparag = "select db02_texto
                       from db_documento
                         inner join db_docparag on db03_docum = db04_docum
                         inner join db_paragrafo on db04_idparag = db02_idparag
                       where db03_docum = 27
                         and db02_descr ilike '%MENSAGEM CARNE%'
                       and db03_instit = " . db_getsession("DB_instit");
      $resparag = db_query($sqlparag);

      if (isset($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu"))) && $k00_valor > 0) {

        if (isset($k00_msgparcvenc) && $k00_msgparcvenc != "") {

          if (strlen($k00_msgparcvenc) > 50) {
            $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strrpos(substr($k00_msgparcvenc, 0, 50), ' '));
          } else {
            $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strlen($k00_msgparcvenc));
          }

          if (strlen($k00_msgparcvencvenc) > 100) {
            $part2 = substr(substr($k00_msgparcvenc, strlen($part1), 50), 0, strrpos(substr($k00_msgparcvenc, strlen($part1), strlen($k00_msgparcvenc)), ' '));
          } else {
            $part2 = substr(substr($k00_msgparcvenc, strlen($part1) + 1, 50), 0, strlen($k00_msgparcvenc));
          }

          if (strlen($k00_msgparcvenc) > 105) {
            $part3 = substr(substr($k00_msgparcvenc, strlen($part2), 50), 0, strlen($k00_msgparcvenc));
          }

          $pdf1->descr16_1    = $part1;
          $pdf1->descr16_2    = $part2;
          $pdf1->descr16_3    = $part3;
          $pdf1->predescr16_1 = $part1;
          $pdf1->predescr16_2 = $part2;
          $pdf1->predescr16_3 = $part3;
        }
      } elseif (isset($k00_msgparc) && $k00_msgparc != "") {

        $pdf1->descr16_1    = substr($k00_msgparc, 0, 50);
        $pdf1->descr16_2    = substr($k00_msgparc, 50, 50);
        $pdf1->descr16_3    = substr($k00_msgparc, 100, 50);
        $pdf1->predescr16_1 = substr($k00_msgparc, 0, 50);
        $pdf1->predescr16_2 = substr($k00_msgparc, 50, 50);
        $pdf1->predescr16_3 = substr($k00_msgparc, 100, 50);
      } else {

        if (isset($k03_msgcarne) && $k03_msgcarne != "") {

          $pdf1->descr16_1    = substr($k03_msgcarne, 0, 50);
          $pdf1->descr16_2    = substr($k03_msgcarne, 50, 50);
          $pdf1->descr16_3    = substr($k03_msgcarne, 100, 50);
          $pdf1->predescr16_1 = substr($k03_msgcarne, 0, 50);
          $pdf1->predescr16_2 = substr($k03_msgcarne, 50, 50);
          $pdf1->predescr16_3 = substr($k03_msgcarne, 100, 50);
        } else {

          if (pg_numrows($resparag) == 0) {
            $db02_texto = "";
          } else {
            db_fieldsmemory($resparag, 0);
          }

          $pdf1->descr16_1    = substr($db02_texto, 0, 55);
          $pdf1->descr16_2    = substr($db02_texto, 55, 55);
          $pdf1->descr16_3    = substr($db02_texto, 110, 55);
          $pdf1->predescr16_1 = substr($db02_texto, 0, 55);
          $pdf1->predescr16_2 = substr($db02_texto, 55, 55);
          $pdf1->predescr16_3 = substr($db02_texto, 110, 55);
        }
      }

      $pdf1->texto           = db_getsession('DB_login') . ' - ' . date("d-m-Y - H-i") . '   ' . db_base_ativa();
      $pdf1->linha_digitavel = $linha_digitavel;
      $pdf1->codigo_barras   = $codigo_barras;
      $pdf1->enderpref       = $ender;
      $pdf1->municpref       = $munic;
      $pdf1->telefpref       = $telef;
      $pdf1->emailpref       = $email;
      @$pdf1->especie        = @$especie;

      if ($k03_tipo == 3 && $emiteVal == 1) {

        $pdf1->descr12_2 .= $traco . $descrreceita . "(" . trim(db_formatar($k00_valor, 'f')) . ")";
        $pdf1->descr4_2  .= $traco . $descrreceita . "(" . trim(db_formatar($k00_valor, 'f')) . ")";
      } else {
        $pdf1->descr12_2 .= $sDescricaoReceitas;
        $pdf1->descr4_2  .= $sDescricaoReceitas;
      }

      /**
       * BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO
       *
       * verifica se é ficha e busca o codigo do banco
       */
      if ($oRegraEmissao->isCobranca()) {

        $rsConsultaBanco = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
        $oBanco          = db_utils::fieldsMemory($rsConsultaBanco, 0);
        $pdf1->numbanco  = $oBanco->db90_codban . "-" . $oBanco->db90_digban;
        $pdf1->banco     = $oBanco->db90_abrev;

        try {
          $pdf1->imagemlogo = $oConvenio->getImagemBanco();
        } catch ( Exception $eExeption ) {
          db_redireciona("db_erros.php?fechar=true&db_erro=" . $eExeption->getMessage());
        }
      }

      if ($imprimeparcelas == "s") {
        $pdf1->imprime();
      }

      $intixxx ++;
      $pdf1->descr12_1 = '';
    }
  }
}

$iFimPag      += $intixxx;
$arquivo       = "tmp/" . $nomeTipoMod . "_" . str_replace(" ", "", $k00_descr) . "_de_" . $iIniPag . "_ate_" . $iFimPag . "_" . date('His') . ".pdf";
$nomearquivos .= "tmp/" . $nomeTipoMod . "_" . str_replace(" ", "", $k00_descr) . "_de_" . $iIniPag . "_ate_" . $iFimPag . "_" . date('His') . ".pdf#Dowload dos " . $nomeTipoMod . " de " . $iIniPag . " ate " . $iFimPag . "|";

$pdf1->objpdf->Output($arquivo, false, true);

echo "<script>";
echo "  listagem = '$nomearquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";

echo "<script>";
echo " parent.db_iframe_carne.hide(); ";
echo "</script>";
