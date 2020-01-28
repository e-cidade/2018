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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_libtributario.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_iptucalc_classe.php"));
require_once(modification("classes/db_iptunump_classe.php"));
require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("classes/db_massamat_classe.php"));
require_once(modification("classes/db_iptuender_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_db_docparag_classe.php"));
require_once(modification("classes/db_arrematric_classe.php"));
require_once(modification("classes/db_listadoc_classe.php"));
require_once(modification("classes/db_db_layouttxtgeracao_classe.php"));
require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/convenio.model.php"));
require_once(modification("model/recibo.model.php"));

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

$cliptucalc  = new cl_iptucalc;
$cliptuender = new cl_iptuender;
$cliptunump  = new cl_iptunump;
$clmassamat  = new cl_massamat;

$cldb_config   = new cl_db_config;
$cldb_docparag = new cl_db_docparag;
$clarrematric  = new cl_arrematric;
$cllistadoc    = new cl_listadoc;

$iTamNossoNumeroVersao2 = 17;
$temporizador = date("His", mktime(date("H"), date("i"), date("s"), 0, 0, 0));


  /**
   * $sqlnaogera = $sSqlNaoGera
   * @param $1 = $j34_setor   text
   * @param $2 = $j34_quadra  text
   */
  $sSqlNaoGera  = ' select *                                                                        ';
  $sSqlNaoGera .= '   from iptunaogeracarne                                                         ';
  $sSqlNaoGera .= '        inner join iptunaogeracarnesetqua on j66_sequencial = j67_naogeracarne   ';
  $sSqlNaoGera .= '  where j67_setor  = $1                                                          ';
  $sSqlNaoGera .= '    and j67_quadra = $2                                                          ';
  db_query("PREPARE sSqlNaoGera (text, text) AS {$sSqlNaoGera}") or die("sSqlNaoGera: ".$sSqlNaoGera);

  /**
   * $sqlnaogeracgm = $sSqlNaoGeraCgm
   * @param $1 = $z01_cgmpri integer
   */
  $sSqlNaoGeraCgm  = ' select *                                                                     ';
  $sSqlNaoGeraCgm .= '   from iptunaogeracarne                                                      ';
  $sSqlNaoGeraCgm .= '        inner join iptunaogeracarnecgm on j66_sequencial = j68_naogeracarne   ';
  $sSqlNaoGeraCgm .= '  where j68_numcgm = $1                                                       ';
  db_query("PREPARE sSqlNaoGeraCgm (integer) AS {$sSqlNaoGeraCgm}") or die("sSqlNaoGeraCgm: ".$sSqlNaoGeraCgm);

  /**
   * $sqlnaogeramatric = $sSqlNaoGeraMatric
   * @param $1 = $j23_matric  integer
   */
  $sSqlNaoGeraMatric  = 'select *                                                                         ';
  $sSqlNaoGeraMatric .= '  from iptunaogeracarne                                                          ';
  $sSqlNaoGeraMatric .= '       inner join iptunaogeracarnematric on j66_sequencial = j131_naogeracarne   ';
  $sSqlNaoGeraMatric .= ' where j131_matric = $1                                                          ';
  db_query("PREPARE sSqlNaoGeraMatric (integer) AS {$sSqlNaoGeraMatric}") or die("sSqlNaoGeraMatric: ".$sSqlNaoGeraMatric);

  /**
   * $sqvalorMax = $sSqlValorMax
   * @param $1 = $j20_numpre  integer
   */
  $sSqlValorMax  = ' select sum(k00_valor)  as viptu,          ';
  $sSqlValorMax .= '        max(k00_numpar) as parcelamaxima   ';
  $sSqlValorMax .= '   from arrecad                            ';
  $sSqlValorMax .= '  where k00_numpre = $1                    ';
  db_query("PREPARE sSqlValorMax (integer) AS {$sSqlValorMax}") or die("sSqlValorMax: ".$sSqlValorMax);

  /**
   * $sqlareaconstr = $sSqlAreaConstr
   * @param $1 = $j23_matric  integer
   */
  $sSqlAreaConstr  = ' select sum(j39_area) as j39_area   ';
  $sSqlAreaConstr .= '   from iptuconstr                  ';
  $sSqlAreaConstr .= '  where j39_dtdemo is null          ';
  $sSqlAreaConstr .= '    and j39_matric = $1             ';
  db_query("PREPARE sSqlAreaConstr (integer) AS {$sSqlAreaConstr}") or die("sSqlAreaConstr: ".$sSqlAreaConstr);

  /**
   * $resultcalc = $sSqlCalcIptuCale
   * @param $1 = $anousu      integer
   * @param $2 = $j23_matric  integer
   */
  $sSqlCalcIptuCale  = 'select sum(j22_valor) as j22_valor   ';
  $sSqlCalcIptuCale .= '  from iptucale                      ';
  $sSqlCalcIptuCale .= ' where j22_anousu = $1               ';
  $sSqlCalcIptuCale .= '   and j22_matric = $2               ';
  db_query("PREPARE sSqlCalcIptuCale (integer, integer) AS {$sSqlCalcIptuCale}") or die("sSqlCalcIptuCale: ".$sSqlCalcIptuCale);

  /**
   * $sqlfin = $sSqlFin
   * @param $1 = $anousu      integer
   * @param $2 = $j23_matric  integer
   */
  $sSqlFin  = ' select *                 ';
  $sSqlFin .= '   from iptunump          ';
  $sSqlFin .= '  where j20_anousu = $1   ';
  $sSqlFin .= '    and j20_matric = $2   ';
  db_query("PREPARE sSqlFin (integer, integer) AS {$sSqlFin}") or die("sSqlFin: ".$sSqlFin);

  /**
   * $sqlsetfisc = $sSqlSetFisc
   * @param $1 = $j23_matric  integer
   */
  $sSqlSetFisc  = ' select *                                              ';
  $sSqlSetFisc .= '   from lotesetorfiscal                                ';
  $sSqlSetFisc .= '        inner join iptubase on j01_idbql = j91_idbql   ';
  $sSqlSetFisc .= '  where j01_matric = $1                                ';
  db_query("PREPARE sSqlSetFisc (integer) AS {$sSqlSetFisc}") or die("sSqlSetFisc: ".$sSqlSetFisc);

  /**
   * $sqljuros = $sSqlJuros
   * @param $1 = $anousu integer
   */
  $sSqlJuros  = ' select distinct                                                                      ';
  $sSqlJuros .= '        tabrecjm.k02_codjm,                                                           ';
  $sSqlJuros .= '        k02_juros,                                                                    ';
  $sSqlJuros .= '        k140_faixa,                                                                   ';
  $sSqlJuros .= '        k140_multa                                                                    ';
  $sSqlJuros .= '   from cfiptu                                                                        ';
  $sSqlJuros .= '        inner join tabrec        on cfiptu.j18_rpredi           = tabrec.k02_codigo   ';
  $sSqlJuros .= '        inner join tabrecjm      on tabrecjm.k02_codjm          = tabrec.k02_codjm    ';
  $sSqlJuros .= '        inner join tabrecjmmulta on tabrecjmmulta.k140_tabrecjm = tabrec.k02_codjm    ';
  $sSqlJuros .= '  where j18_anousu = $1                                                               ';
  $sSqlJuros .= '  order by k140_multa                                                                 ';
  $sSqlJuros .= '  limit 1                                                                             ';
  db_query("PREPARE sSqlJuros (integer) AS {$sSqlJuros}") or die("sSqlJuros: ".$sSqlJuros);

  /**
   * $sqlisen = $sSqlIsen
   * @param $1 = $anousu      integer
   * @param $2 = $j23_matric  integer
   */
  $sSqlIsen  = ' select j46_codigo, j45_descr,                           ';
  $sSqlIsen .= '        j46_dtinc , j46_tipo                             ';
  $sSqlIsen .= '   from iptuisen                                         ';
  $sSqlIsen .= '        inner join isenexe  on j46_codigo = j47_codigo   ';
  $sSqlIsen .= '                           and j47_anousu = $1           ';
  $sSqlIsen .= '        inner join tipoisen on j46_tipo   = j45_tipo     ';
  $sSqlIsen .= '  where j46_matric = $2                                  ';
  db_query("PREPARE sSqlIsen (integer, integer) AS {$sSqlIsen}") or die("sSqlIsen: ".$sSqlIsen);

  /**
   * $sqlarrecad = $sSqlArrecad
   * @param $1 = $j20_numpre  integer
   */
  $sSqlArrecad  = ' select k00_tipo  , a.k00_numpre,                             ';
  $sSqlArrecad .= '        k00_numpar, k00_numtot,                               ';
  $sSqlArrecad .= '        k00_numdig, k00_dtvenc,                               ';
  $sSqlArrecad .= '        sum(k00_valor)::float8 as k00_valor                   ';
  $sSqlArrecad .= '   from arrematric m                                          ';
  $sSqlArrecad .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre   ';
  $sSqlArrecad .= '  where m.k00_numpre = $1                                     ';
  $sSqlArrecad .= '  group by a.k00_numpre, k00_numpar,                          ';
  $sSqlArrecad .= '           k00_numtot, k00_numdig,                            ';
  $sSqlArrecad .= '           k00_dtvenc, k00_tipo                               ';
  $sSqlArrecad .= '  order by k00_numpar                                         ';
  db_query("PREPARE sSqlArrecad (integer) AS {$sSqlArrecad}") or die("sSqlArrecad: ".$sSqlArrecad);

  /**
   * $sql = $sSqlArretipo
   * @param $1 = $k00_tipo  integer
   */
  $sSqlArreTipoTxBanco  = ' select k00_tipo, k00_codbco, k00_codage, k00_descr,   ';
  $sSqlArreTipoTxBanco .= '        k00_hist1, k00_hist2, k00_hist3,  k00_hist4,   ';
  $sSqlArreTipoTxBanco .= '        k00_hist5, k00_hist6, k00_hist7, k00_hist8,    ';
  $sSqlArreTipoTxBanco .= '        k03_tipo, k00_txban as tx_banc                 ';
  $sSqlArreTipoTxBanco .= '   from arretipo                                       ';
  $sSqlArreTipoTxBanco .= '  where k00_tipo = $1                                  ';
  db_query("PREPARE sSqlArreTipoTxBanco (integer) AS {$sSqlArreTipoTxBanco}") or die("sSqlArreTipoTxBanco: ".$sSqlArreTipoTxBanco);

  /**
   * $sqlcalc = $sSqlCalcQntImpTaxas
   * @param $1 = $anousu      integer
   * @param $2 = $j23_matric  integer
   */
  $sSqlCalcQntImpTaxas  = ' select sum(j21_valor) as total_j21_valor,      ';
  $sSqlCalcQntImpTaxas .= '        count(*)       as quant_imposto_taxas   ';
  $sSqlCalcQntImpTaxas .= '   from iptucalv                                ';
  $sSqlCalcQntImpTaxas .= '  where j21_anousu = $1                         ';
  $sSqlCalcQntImpTaxas .= '    and j21_matric = $2                         ';
  db_query("PREPARE sSqlCalcQntImpTaxas (integer, integer) AS {$sSqlCalcQntImpTaxas}") or die("sSqlCalcQntImpTaxas: ".$sSqlCalcQntImpTaxas);

  /**
   * $sqlcalc = $sSqlCalcQntTaxas
   * @param $1 = $anousu      integer
   * @param $2 = $j23_matric  integer
   */
  $sSqlCalcQntTaxas  = ' select sum(j21_valor) as total_j21_valor,   ';
  $sSqlCalcQntTaxas .= '        count(*)       as quant_taxas        ';
  $sSqlCalcQntTaxas .= '   from iptucalv                             ';
  $sSqlCalcQntTaxas .= '  where j21_anousu = $1                      ';
  $sSqlCalcQntTaxas .= '    and j21_matric = $2                      ';
  db_query("PREPARE sSqlCalcQntTaxas (integer, integer) AS {$sSqlCalcQntTaxas}") or die("sSqlCalcQntTaxas: ".$sSqlCalcQntTaxas);

  /**
    $sqlcalc = $sSqlCalcQntTaxasReceita
    @param $1 = $anousu      integer
    @param $2 = $j23_matric  integer
   */
  $sSqlCalcQntTaxasReceita  = ' select sum(j21_valor) as total_j21_valor,       ';
  $sSqlCalcQntTaxasReceita .= '        count(*)       as quant_taxas            ';
  $sSqlCalcQntTaxasReceita .= '   from iptucalv                                 ';
  $sSqlCalcQntTaxasReceita .= '  where j21_anousu = $1                          ';
  $sSqlCalcQntTaxasReceita .= '    and j21_matric = $2                          ';
  $sSqlCalcQntTaxasReceita .= '    and j21_receit in (select j19_receit         ';
  $sSqlCalcQntTaxasReceita .= '                         from iptutaxa           ';
  $sSqlCalcQntTaxasReceita .= '                        where j19_anousu = $1)   ';
  db_query("PREPARE sSqlCalcQntTaxasReceita (integer, integer) AS {$sSqlCalcQntTaxasReceita}") or die("sSqlCalcQntTaxasReceita: ".$sSqlCalcQntTaxasReceita);

  /**
    $sqliptu = $sSqlIptu
    @param $1 = $anousu     integer
    @param $2 = $anoant     integer
    @param $3 = $j23_matric integer
   */
  $sSqlIptu  = ' select fc_calcula(k00_numpre, k00_numpar, 0, current_date, current_date, $1)           ';
  $sSqlIptu .= '   from ( select distinct                                                               ';
  $sSqlIptu .= '                 arrecad.k00_numpre, arrecad.k00_numpar                                 ';
  $sSqlIptu .= '            from iptunump                                                               ';
  $sSqlIptu .= '                 inner join arrematric on iptunump.j20_numpre = arrematric.k00_numpre   ';
  $sSqlIptu .= '                 inner join arrecad    on iptunump.j20_numpre = arrecad.k00_numpre      ';
  $sSqlIptu .= '           where j20_anousu = $2                                                        ';
  $sSqlIptu .= '             and k00_matric = $3 ) as x                                                 ';
  db_query("PREPARE sSqlIptu (integer, integer, integer) AS {$sSqlIptu}") or die("sSqlIptu: ".$sSqlIptu);

  /**
    $sqlvalorm2 = $sSqlValorM2
    @param $1 = $j01_idbql   integer
    @param $2 = $anousu      integer
    @param $3 = $j23_matric  integer
   */
  $sSqlValorM2  = ' select j37_face, j81_valorterreno as j37_valor, j37_outros,   ';
  $sSqlValorM2 .= '        case when j36_testle = 0                               ';
  $sSqlValorM2 .= '          then j36_testad                                      ';
  $sSqlValorM2 .= '          else j36_testle                                      ';
  $sSqlValorM2 .= '        end as j36_testle,                                     ';
  $sSqlValorM2 .= '        j81_valorconstr as j37_vlcons                          ';
  $sSqlValorM2 .= '   from iptuconstr                                             ';
  $sSqlValorM2 .= '        inner join testada   on j36_face   = j39_codigo        ';
  $sSqlValorM2 .= '                            and j36_idbql  = $1                ';
  $sSqlValorM2 .= '        inner join face      on j37_face   = j36_face          ';
  $sSqlValorM2 .= '        left  join facevalor on j81_face   = j37_face          ';
  $sSqlValorM2 .= '                            and j81_anousu = $2                ';
  $sSqlValorM2 .= '        inner join iptubase  on j01_matric = j39_matric        ';
  $sSqlValorM2 .= '  where j39_matric = $3                                        ';
  $sSqlValorM2 .= '    and j39_dtdemo is null                                     ';
  $sSqlValorM2 .= '    and j01_baixa  is null                                     ';
  $sSqlValorM2 .= '  limit 1                                                      ';
  db_query("PREPARE sSqlValorM2 (integer, integer, integer) AS {$sSqlValorM2}") or die("sSqlValorM2: ".$sSqlValorM2);

  /**
   * $sqlpagas = $sSqlPagas
   * @param $1 = $j20_numpre  integer
   * @param $2 = $parcpaga    integer
   */
  $sSqlPagas  = ' select max(dtpago)    as dtpago,                                                                 ';
  $sSqlPagas .= '        sum(k00_valor) as valorpago                                                               ';
  $sSqlPagas .= '   from ( select distinct                                                                         ';
  $sSqlPagas .= '                 j20_numpre, j20_matric, arrepaga.k00_numpar,                                     ';
  $sSqlPagas .= '                 arrepaga.k00_receit   , arrepaga.k00_valor ,                                     ';
  $sSqlPagas .= '                 case when disbanco2.dtpago is null                                               ';
  $sSqlPagas .= '                   then case when disbanco1.dtpago is null                                        ';
  $sSqlPagas .= '                          then arrepaga.k00_dtpaga                                                ';
  $sSqlPagas .= '                          else disbanco1.dtpago                                                   ';
  $sSqlPagas .= '                        end                                                                       ';
  $sSqlPagas .= '                   else disbanco2.dtpago                                                          ';
  $sSqlPagas .= '                 end as dtpago                                                                    ';
  $sSqlPagas .= '            from iptunump                                                                         ';
  $sSqlPagas .= '                 inner join arrepaga           on arrepaga.k00_numpre   = j20_numpre              ';
  $sSqlPagas .= '                 left  join disbanco disbanco1 on disbanco1.k00_numpre  = arrepaga.k00_numpre     ';
  $sSqlPagas .= '                                              and disbanco1.k00_numpar  = arrepaga.k00_numpar     ';
  $sSqlPagas .= '                 left  join recibopaga         on recibopaga.k00_numpre = arrepaga.k00_numpre     ';
  $sSqlPagas .= '                                              and recibopaga.k00_numpar = recibopaga.k00_numpar   ';
  $sSqlPagas .= '                                              and recibopaga.k00_receit = arrepaga.k00_receit     ';
  $sSqlPagas .= '                 left  join disbanco disbanco2 on disbanco2.k00_numpre  = recibopaga.k00_numnov   ';
  $sSqlPagas .= '           where j20_numpre = $1                                                                  ';
  $sSqlPagas .= '             and arrepaga.k00_numpar = $2                                                         ';
  $sSqlPagas .= '           order by j20_matric                                                                    ';
  $sSqlPagas .= '        ) as x                                                                                    ';
  db_query("PREPARE sSqlPagas (integer, integer) AS {$sSqlPagas}") or die("sSqlPagas: ".$sSqlPagas);

  /**
   * $sqlfin2 = $sSqlFin02
   * @param $1 = $j20_numpre  integer
   */
  $sSqlFin02  = 'select k00_tipo, k00_dtvenc,         ';
  $sSqlFin02 .= '       k00_numpre, k00_numpar,       ';
  $sSqlFin02 .= '       sum(k00_valor) as k00_valor   ';
  $sSqlFin02 .= '  from arrecad                       ';
  $sSqlFin02 .= ' where k00_numpre = $1               ';
  $sSqlFin02 .= ' group by k00_dtvenc, k00_numpre,    ';
  $sSqlFin02 .= '          k00_numpar, k00_tipo       ';
  $sSqlFin02 .= ' order by k00_numpre, k00_numpar     ';
  db_query("PREPARE sSqlFin02 (integer) AS {$sSqlFin02}") or die("sSqlFin02: ".$sSqlFin02);

  /**
   * $sqliptuant = $sSqlIptuAnt
   * @param $1 = $j23_matric  integer
   */
  $sSqlIptuAnt  = ' select *                 ';
  $sSqlIptuAnt .= '   from iptuant           ';
  $sSqlIptuAnt .= '  where j40_matric = $1   ';
  db_query("PREPARE sSqlIptuAnt (integer) AS {$sSqlIptuAnt}") or die("sSqlIptuAnt: ".$sSqlIptuAnt);

  /**
   * $sqltipo = $sSqlTipo
   * @param $1 = $anousu  integer
   */
  $sSqlTipo  = ' select q92_tipo as tipodeb                                 ';
  $sSqlTipo .= '   from cfiptu                                              ';
  $sSqlTipo .= '        inner join cadvencdesc on q92_codigo = j18_vencim   ';
  $sSqlTipo .= '  where j18_anousu = $1                                     ';
  db_query("PREPARE sSqlTipo (integer) AS {$sSqlTipo}") or die("sSqlTipo: ".$sSqlTipo);

  /**
   * $sqlfinpripaga = $sSqlFinPriPagaComPgto
   * @param $1 = $j20_numpre                                 integer
   * @param $2 = date("Y-m-d", db_getsession("DB_datausu"))  date
   */
  $sSqlFinPriPagaComPgto  = ' select distinct a.k00_numpar                                 ';
  $sSqlFinPriPagaComPgto .= '   from arrematric m                                          ';
  $sSqlFinPriPagaComPgto .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre   ';
  $sSqlFinPriPagaComPgto .= '  where m.k00_numpre = $1                                     ';
  $sSqlFinPriPagaComPgto .= '    and a.k00_dtvenc < $2                                     ';
  db_query("PREPARE sSqlFinPriPagaComPgto (integer, date) AS {$sSqlFinPriPagaComPgto}") or die("sSqlFinPriPagaComPgto: ".$sSqlFinPriPagaComPgto);

  /**
   * $sqlfinpripaga = $sSqlFinPriPagaSemPgto
   * @param $1 = $j20_numpre  integer
   */
  $sSqlFinPriPagaSemPgto  = ' select *                                                      ';
  $sSqlFinPriPagaSemPgto .= '   from arrematric m                                           ';
  $sSqlFinPriPagaSemPgto .= '        inner join arrepaga a on m.k00_numpre = a.k00_numpre   ';
  $sSqlFinPriPagaSemPgto .= '        inner join arrecant t on m.k00_numpre = t.k00_numpre   ';
  $sSqlFinPriPagaSemPgto .= '  where m.k00_numpre = $1                                      ';
  $sSqlFinPriPagaSemPgto .= '  limit 1                                                      ';
  db_query("PREPARE sSqlFinPriPagaSemPgto (integer) AS {$sSqlFinPriPagaSemPgto}") or die("sSqlFinPriPagaSemPgto: ".$sSqlFinPriPagaSemPgto);

  /**
   * $result_iptuhist = $sSqlValorIptuHist
   * @param $1 = $anousu      integer
   * @param $2 = $j23_matric  integer
   */
  $sSqlValorIptuHist  = ' select sum(j21_valor) as j21_valor   ';
  $sSqlValorIptuHist .= '   from iptucalv                      ';
  $sSqlValorIptuHist .= '  where j21_anousu = $1               ';
  $sSqlValorIptuHist .= '    and j21_matric = $2               ';
  $sSqlValorIptuHist .= '    and j21_codhis in (1,7)           ';
  db_query("PREPARE sSqlValorIptuHist (integer, integer) AS {$sSqlValorIptuHist}") or die("sSqlValorIptuHist: ".$sSqlValorIptuHist);

  /**
   * $result_iptuhist = $sSqlValorTaxaHist
   * @param $1 = $anousu      integer
   * @param $2 = $j23_matric  integer
   */
  $sSqlValorTaxaHist  =' select sum(j21_valor) as j21_valor    ';
  $sSqlValorTaxaHist .='   from iptucalv                       ';
  $sSqlValorTaxaHist .='  where j21_anousu = $1                ';
  $sSqlValorTaxaHist .='    and j21_matric = $2                ';
  $sSqlValorTaxaHist .='    and j21_codhis in (2,8)            ';
  db_query("PREPARE sSqlValorTaxaHist (integer, integer) AS {$sSqlValorTaxaHist}") or die("sSqlValorTaxaHist: ".$sSqlValorTaxaHist);

  /**
   * $result_iptuhist = $sSqlValorIptuHistReceita
   * @param $1 = $k00_numpre  integer
   * @param $2 = $k00_numpar  integer
   */
  $sSqlValorIptuHistReceita  = ' select sum(k00_valor) as k00_valor    ';
  $sSqlValorIptuHistReceita .= '   from arrecad                        ';
  $sSqlValorIptuHistReceita .= '  where k00_numpre = $1                ';
  $sSqlValorIptuHistReceita .= '    and k00_numpar = $2                ';
  $sSqlValorIptuHistReceita .= '    and k00_receit in (1,2)            ';
  db_query("PREPARE sSqlValorIptuHistReceita (integer, integer) AS {$sSqlValorIptuHistReceita}") or die("sSqlValorIptuHistReceita: ".$sSqlValorIptuHistReceita);

  /**
   * $result_iptuhist = $sSqlValorTaxaHistReceita
   * @param $1 = $k00_numpre  integer
   * @param $2 = $k00_numpar  integer
   */
  $sSqlValorTaxaHistReceita  = ' select sum(k00_valor) as k00_valor    ';
  $sSqlValorTaxaHistReceita .= '  from arrecad                         ';
  $sSqlValorTaxaHistReceita .= ' where k00_numpre = $1                 ';
  $sSqlValorTaxaHistReceita .= '   and k00_numpar = $2                 ';
  $sSqlValorTaxaHistReceita .= '   and k00_receit in (10)              ';
  db_query("PREPARE sSqlValorTaxaHistReceita (integer, integer) AS {$sSqlValorTaxaHistReceita}") or die("sSqlValorTaxaHistReceita: ".$sSqlValorTaxaHistReceita);

  /**
   * $sql = $sSqlArretipo
   * @param $1 = $k00_tipo  integer
   */
  $sSqlArretipo  = ' select k00_tipo, k00_codbco, k00_codage, k00_descr,  ';
  $sSqlArretipo .= '        k00_hist1, k00_hist2, k00_hist3, k00_hist4,   ';
  $sSqlArretipo .= '        k00_hist5, k00_hist6, k00_hist7, k00_hist8,   ';
  $sSqlArretipo .= '        k03_tipo                                      ';
  $sSqlArretipo .= ' from arretipo                                        ';
  $sSqlArretipo .= ' where k00_tipo = $1                                  ';
  db_query("PREPARE sSqlArretipo (integer) AS {$sSqlArretipo}") or die("sSqlArretipo: $sSqlArretipo ".pg_last_error());

  /**
   * $sqlpagas = $sSqlParcPagas
   * @param $1 = $j20_numpre  integer
   */
  $sSqlParcPagas  = ' select sum(k00_valor) as valorpago                                         ';
  $sSqlParcPagas .= '   from ( select distinct                                                   ';
  $sSqlParcPagas .= '                 j20_numpre,                                                ';
  $sSqlParcPagas .= '                 j20_matric,                                                ';
  $sSqlParcPagas .= '                 arrepaga.k00_numpar,                                       ';
  $sSqlParcPagas .= '                 arrepaga.k00_receit,                                       ';
  $sSqlParcPagas .= '                 arrepaga.k00_valor                                         ';
  $sSqlParcPagas .= '            from iptunump                                                   ';
  $sSqlParcPagas .= '                 inner join arrepaga on arrepaga.k00_numpre = j20_numpre    ';
  $sSqlParcPagas .= '           where j20_numpre = $1) as x                                      ';
  db_query("PREPARE sSqlParcPagas (integer) AS {$sSqlParcPagas}") or die("sSqlParcPagas: ".$sSqlParcPagas);

  /**
   * $sqltestada = $sSqlTestada
   * @param $1 = $j23_matric  integer
   */
  $sSqlTestada  = ' select j36_testad                                              ';
  $sSqlTestada .= '   from iptubase                                                ';
  $sSqlTestada .= '        inner join testada on j36_idbql = j01_idbql             ';
  $sSqlTestada .= '        inner join testpri on j49_idbql = testada.j36_idbql     ';
  $sSqlTestada .= '  where j01_matric = $1                                         ';
  db_query("PREPARE sSqlTestada (integer) AS {$sSqlTestada}") or die("sSqlTestada: ".$sSqlTestada);

  /**
   * $sqlfinvcto = $sSqlFinVencimento
   * @param $1 = $j20_numpre   integer
   */
  $sSqlFinVencimento  = ' select min(k00_dtvenc) as vctorecibo                         ';
  $sSqlFinVencimento .= '   from arrematric m                                          ';
  $sSqlFinVencimento .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre   ';
  $sSqlFinVencimento .= '  where m.k00_numpre = $1                                     ';
  $sSqlFinVencimento .= '    and a.k00_dtvenc > current_date                           ';
  db_query("PREPARE sSqlFinVencimento (integer) AS {$sSqlFinVencimento}") or die("sSqlFinVencimento: ".$sSqlFinVencimento);

  /**
   * $sqlfinrecibo = $sSqlFinRecibo
   * @param $1 = $j20_numpre  integer
   */
  $sSqlFinRecibo  = ' select distinct                                               ';
  $sSqlFinRecibo .= '        a.k00_numpre, a.k00_numpar, k00_dtvenc                 ';
  $sSqlFinRecibo .= '   from arrematric m                                           ';
  $sSqlFinRecibo .= '        inner join arrecad a on m.k00_numpre = a.k00_numpre    ';
  $sSqlFinRecibo .= '  where m.k00_numpre = $1                                      ';
  $sSqlFinRecibo .= '    and a.k00_dtvenc < current_date                            ';
  db_query("PREPARE sSqlFinRecibo (integer) AS {$sSqlFinRecibo}") or die("sSqlFinRecibo: ".$sSqlFinRecibo);

  /**
   * $sql = $sSqlRecibo
   * @param $1 = $k03_numpre  integer
   */
  $sSqlRecibo  = ' select sum(k00_valor) as valorrecibo   ';
  $sSqlRecibo .= '   from recibopaga                      ';
  $sSqlRecibo .= '  where k00_numnov = $1                 ';
  db_query("PREPARE sSqlRecibo (integer) AS {$sSqlRecibo}") or die("sSqlRecibo: ".$sSqlRecibo);


  /**
   * $sSqlCorrecao = $sSqlValorCorrecao
   * @param $1 = $dtVencimentoUnicaCorrecao  Date
   * @param $2 = db_getsession("DB_anousu")  integer
   * @param $3 = $j20_numpre                 integer
   */
  $sSqlValorCorrecao  = "select k00_numpre         as numpre,                                                                   \n";
  $sSqlValorCorrecao .= "       sum(vlr_historico) as historico,                                                            \n";
  $sSqlValorCorrecao .= "       sum(vlr_corrigido) as corrigido,                                                            \n";
  $sSqlValorCorrecao .= "       sum(vlr_juros)     as juros,                                                                \n";
  $sSqlValorCorrecao .= "       sum(vlr_multa)     as multa,                                                                \n";
  $sSqlValorCorrecao .= "       sum(vlr_desconto)  as desconto,                                                             \n";
  $sSqlValorCorrecao .= "       sum(vlr_corrigido + vlr_juros + vlr_multa - vlr_desconto) as total                          \n";
  $sSqlValorCorrecao .= "  from ( select k00_numpre,                                                                            \n";
  $sSqlValorCorrecao .= "                k00_valor     as vlr_historico,                                                        \n";
  $sSqlValorCorrecao .= "                k00_valor     as vlr_corrigido,                                                        \n";
  $sSqlValorCorrecao .= "                round(  cast(k00_valor as float) * coalesce(fc_juros(cast(k00_receit as integer),     \n";
  $sSqlValorCorrecao .= "                                                                      k00_dtvenc,                                     \n";
  $sSqlValorCorrecao .= "                                                                      $1,                                             \n";
  $sSqlValorCorrecao .= "                                                                      $1,                                             \n";
  $sSqlValorCorrecao .= "                                                                      cast(false as boolean),                         \n";
  $sSqlValorCorrecao .= "                                                                      $2
  		                                                                                        ), 0),2)                                       \n";
  $sSqlValorCorrecao .= "                  as vlr_juros,                                                                        \n";
  $sSqlValorCorrecao .= "                round( ( k00_valor * coalesce(fc_multa(k00_receit,                                     \n";
  $sSqlValorCorrecao .= "                                                       k00_dtvenc,                                     \n";
  $sSqlValorCorrecao .= "                                                       $1,                                             \n";
  $sSqlValorCorrecao .= "                                                       $1,                                             \n";
  $sSqlValorCorrecao .= "                                                       $2 ),0) ),2)                                    \n";
  $sSqlValorCorrecao .= "                  as vlr_multa,                                                                        \n";
  $sSqlValorCorrecao .= "                round( ( ( k00_valor / 100 ) * coalesce( ( select k00_percdes                          \n";
  $sSqlValorCorrecao .= "                                                             from recibounica r                        \n";
  $sSqlValorCorrecao .= "                                                            where r.k00_numpre = arrecad.k00_numpre    \n";
  $sSqlValorCorrecao .= "                                                              and r.k00_dtvenc <= $1                   \n";
  $sSqlValorCorrecao .= "                                                            order by r.k00_dtvenc limit 1 ),0 ) ) ,2)  \n";
  $sSqlValorCorrecao .= "                  as vlr_desconto                                                                      \n";
  $sSqlValorCorrecao .= "           from arrecad                                                                                \n";
  $sSqlValorCorrecao .= "          where k00_numpre = $3                                                                        \n";
  $sSqlValorCorrecao .= " ) as arrecad                                                                                          \n";
  $sSqlValorCorrecao .= " group by numpre                                                                                       \n";
  db_query("PREPARE sSqlValorCorrecao (date, integer, integer) AS {$sSqlValorCorrecao}") or die("sSqlValorCorrecao: ".$sSqlValorCorrecao . " ".pg_last_error());

  // setando variaveis FIM

/*
 * função que retorna ultima sexta do mes
 * @param $iMes integer mes a ser validado
 * @param $iAno integer ano a ser validado
 */
function getUltimoDiaMes($iMes, $iAno, $diaSemana = null){

  $ultimo_dia_mes   = strtotime("{$iAno}-{$iMes}-".cal_days_in_month(CAL_GREGORIAN, $iMes,$iAno));

  if(!empty($diaSemana)){

    if(date('w', $ultimo_dia_mes) != 5){
      $ultima_sexta   = strtotime("last {$diaSemana}", $ultimo_dia_mes);
    } else {
      $ultima_sexta   = $ultimo_dia_mes;
    }
    return date("d",$ultima_sexta);
  }
}

$cldb_layouttxtgeracao = new cl_db_layouttxtgeracao;
$arqTXTISENTOS         = fopen("tmp/dadosisencoes.txt","w");
$lReciboGerado         = false;

db_postmemory($_REQUEST);

if(!isset($vlrminunica) || $vlrminunica == ""){
  $vlrminunica = 0;
}

if(!isset($vlrmaxunica) || $vlrmaxunica == ""){
  $vlrmaxunica = 999999999;
}
if(!isset($vlrmin) || $vlrmin == ""){
  $vlrmin = 0;
}

if(!isset($vlrmax) || $vlrmax == ""){
  $vlrmax = 999999999;
}
$intervalorvlrminimo = false;

$lGeraVencParcelas     = false;
if(isset($opVenc) && $opVenc == 1){
  $lGeraVencParcelas = true;
}

if (isset($unica) && $unica != "") {

  $aUnicas =  explode("U", $unica);
  $vt      = split("U",$unica);
  $unicas  = array();

  foreach ($vt as $i => $v){

    $check = split("=",$v);
    if (isset($check) && $check != "") {
      array_push($unicas, $check[0]."-".$check[1]."-".$check[2])."#";
    }
  }
  $temUnica = true;
}else{
  $temUnica = false;
}

$processarmovimentacao  = (int) $processarmovimentacao;

$debugar_passou=0;

/**
 * @todo parcela maxima do arrecad
 */
$iParcelaMaxima = 12;

$iQuantidadeUnicas = 0;
if ($temUnica) {
  $iQuantidadeUnicas = count($unicas);
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
    <table width="100%" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
      <tr>
        <td width="100%" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td width="100%"  align="center">
          <? db_criatermometro('termometro','Concluido...','blue',1); ?>
        </td>
      </tr>
    </table>
    <form name='form1'>
    </form>
  </body>
</html>
<?

$sQuebraLinha = "\r\n";

$rsPref = db_query("select munic, cep, uf, db21_codcli from db_config where prefeitura is true ");
db_fieldsMemory($rsPref,0);

$iTamCodArrecadao = 11;

// Busca Receitas do IPTU
$sSqlReceitas  = "select j18_rterri as j18_receit from cfiptu where j18_anousu = $anousu ";
$sSqlReceitas .= "union ";
$sSqlReceitas .= "select j18_rpredi as j18_receit from cfiptu where j18_anousu = $anousu ";
$sSqlReceitas .= "union ";
$sSqlReceitas .= "select distinct j23_recdst as j18_receit from iptucalcconfrec where j23_anousu = $anousu and j23_tipo = 1";

$rsReceitas = db_query($sSqlReceitas);
$iLinhasRec = pg_numrows($rsReceitas);

if($iLinhasRec>0) {
  //
  $aRec = array();
  for($indx=0; $indx<$iLinhasRec; $indx++) {
    $aRec[] = pg_result($rsReceitas, $indx, "j18_receit");
  }
  $iRecMin = min($aRec);
  $sListaReceitas = implode(",", $aRec);
} else {
  $sListaReceitas = "null";
}

$whereimobil = "";
if ($imobiliaria == "com") {
  $whereimobil = " and j44_matric is not null ";
} elseif ($imobiliaria == "sem") {
  $whereimobil = " and j44_matric is null ";
}

$wherelistamatrics = "";
if ($listamatrics == "") {
  $wherelistamatrics = " ";
} else {
  $wherelistamatrics = " and iptucalc.j23_matric in ($listamatrics) ";
  $quantidade = "";
}

$whereloteam = "";
if ($loteamento == "com") {
  $whereloteam = " and loteloteam.j34_idbql is not null ";
} elseif ($loteamento == "sem") {
  $whereloteam = " and loteloteam.j34_idbql is null ";
}
$sOrder = null;

switch ($ordem)  {

  case "endereco":
    $sOrder = "j23_munic, j23_uf, j23_ender, j23_numero, j23_compl";
  break;
  case "bairroender":
    $sOrder = "j23_bairro, j23_munic, j23_uf, j23_ender, j23_numero, j23_compl";
  break;
  case "alfabetica":
    $sOrder = "z01_nome";
  break;
  case "zonaentrega":
    $sOrder = "j86_iptucadzonaentrega";
  break;
  case "refant":
    $sOrder = "j40_refant";
  break;
  case "setorquadralote":
    $sOrder = "j34_setor, j34_quadra, j34_lote";
  break;
  case "bairroalfa":
    $sOrder = " j23_bairro ";
  break;
  default :
  $sOrder = "z01_nome";
  break;
}
$sqlprinc  = "";

$sqlprinc .= "select * from ( ";
$sqlprinc .= "select distinct ";
$sqlprinc .= "       j23_matric, ";
$sqlprinc .= "       j23_vlrter, ";
$sqlprinc .= "       j23_aliq, ";
$sqlprinc .= "       j23_areafr, ";
$sqlprinc .= "       j86_iptucadzonaentrega, ";
$sqlprinc .= "       z01_nome, ";
$sqlprinc .= "       j20_numpre, ";
$sqlprinc .= "       j01_idbql,";
$sqlprinc .= "       j23_arealo,";
$sqlprinc .= "       j23_m2terr,";
$sqlprinc .= "       j40_refant,";
$sqlprinc .= "       j34_setor,";
$sqlprinc .= "       j34_quadra,";
$sqlprinc .= "       j34_lote,";
$sqlprinc .= "       substr(fc_iptuender,001,40) as j23_ender, ";
$sqlprinc .= "       substr(fc_iptuender,042,10) as j23_numero, ";
$sqlprinc .= "       substr(fc_iptuender,053,20) as j23_compl, ";
$sqlprinc .= "       substr(fc_iptuender,074,40) as j23_bairro, ";
$sqlprinc .= "       substr(fc_iptuender,115,40) as j23_munic, ";
$sqlprinc .= "       substr(fc_iptuender,156,02) as j23_uf, ";
$sqlprinc .= "       substr(fc_iptuender,159,08) as j23_cep, ";
$sqlprinc .= "       substr(fc_iptuender,168,20) as j23_cxpostal, ";
$sqlprinc .= "       substr(fc_iptuender,189,40) as j23_dest from  ( ";
$sqlprinc .= "       select j23_matric, ";
$sqlprinc .= "              j23_vlrter, ";
$sqlprinc .= "              j23_aliq, ";
$sqlprinc .= "              j23_areafr, ";
$sqlprinc .= "              j20_numpre, ";
$sqlprinc .= "              j86_iptucadzonaentrega, ";
$sqlprinc .= "              (select z01_nome from proprietario_nome where j01_matric = j23_matric limit 1) as z01_nome, ";
$sqlprinc .= "              j01_idbql, ";
$sqlprinc .= "              j23_m2terr,";
$sqlprinc .= "              j23_arealo, ";
$sqlprinc .= "              j40_refant,";
$sqlprinc .= "              j34_setor,";
$sqlprinc .= "              j34_quadra,";
$sqlprinc .= "              j34_lote,";
$sqlprinc .= "              fc_iptuender(j23_matric) ";
$sqlprinc .= "         from iptucalc  ";
$sqlprinc .= "              inner join iptubase       on iptubase.j01_matric = iptucalc.j23_matric ";
$sqlprinc .= "              inner join iptunump       on iptunump.j20_matric = iptubase.j01_matric  and iptunump.j20_anousu = $anousu ";
$sqlprinc .= "              inner join lote           on lote.j34_idbql = iptubase.j01_idbql  ";
$sqlprinc .= "              inner join cgm            on cgm.z01_numcgm = iptubase.j01_numcgm  ";
$sqlprinc .= "              left  join iptumatzonaentrega on iptumatzonaentrega.j86_matric = iptubase.j01_matric ";
$sqlprinc .= "              left  join imobil             on imobil.j44_matric = iptubase.j01_matric ";
$sqlprinc .= "              left  join loteloteam         on loteloteam.j34_idbql = lote.j34_idbql ";
$sqlprinc .= "              left  join iptuant            on iptuant.j40_matric   = iptubase.j01_matric ";
$sqlprinc .= "        where iptucalc.j23_anousu = $anousu ";
$sqlprinc .= "        {$whereimobil} {$wherelistamatrics} {$whereloteam}" . ($quantidade != ""?" limit {$quantidade}":"") . ") as x ";

$sqlprinc .= "        ) as y ";

$sqlprinc .= " order by {$sOrder}";
$sqlprinc .= !empty($quantidade) ? " limit $quantidade" : "";
$resultprinc = $cliptucalc->sql_record($sqlprinc);

if ($resultprinc == false || $cliptucalc->numrows == 0) {
  $erro = true;
  $descricao_erro = "Não existe cálculo efetuado!";
  die($sqlprinc);
}

$quantescolhida = $quantidade;

// funcao q da um select na db_config e cria as variavei
// com o valor e nome dos campos passado por parametro
db_sel_instit(null," tx_banc ");

$nTotalBomPagador = 0;
$arqnomes = "";
$iTotalLinhas20 = 0;
$nTotalParcelas20 = 0;

for ($vez = 0; $vez <= 1; $vez++) {

  if ($vez == 0) {
    $gerar = "layout";
  }
  if ($vez == 1) {
    $gerar = "dados";
  }

  $nomedoarquivo = "tmp/" . $gerar . "_iptu_" . $filtroprinc . ($quantidadeparcelas == ""?"":"_quantparc_" . str_pad($quantidadeparcelas,3,"0",STR_PAD_LEFT)) . "_" . $anousu . "_" . date("Y-m-d_His",db_getsession("DB_datausu")) . ".txt";
  $arqnomes .= $nomedoarquivo."# Download do Arquivo - ".$nomedoarquivo."|";
  $erro = false;
  $descricao_erro = false;
  set_time_limit(0);
  $clabre_arquivo = new cl_abre_arquivo($nomedoarquivo);

  if ($clabre_arquivo->arquivo != false) {

    $quantos = 0;
    $cliptubase = new cl_iptubase;

    $total_reg = pg_numrows($resultprinc);

    if ($quantescolhida == "") {
      $quantidade = $total_reg;
    } else {
      $quantidade = $quantescolhida;
      if ($quantidade > $total_reg) {
        $quantidade = $total_reg;
      }
    }

    global $contadorgeral;
    $contadorgeral = 1;

    global $contador;
    $contador = 0;

    /**
     * Instancia a regra de emissão a ser utilizada para emissão dos recibos
     */
    try {

      $oRegraEmissao = null;
      $lConvenioCobrancaValido = false;

      if ($quantidade > 0) {

        $iNumpreTipo = db_utils::fieldsMemory($resultprinc, 0)->j20_numpre;
        $rsTipo = db_query("select k00_tipo from arrecad where k00_numpre = {$iNumpreTipo} limit 1");

        if (pg_num_rows($rsTipo) == 0) {
          throw new Exception("Erro ao buscar o tipo de débito.");
        }

        $oRegraEmissao = new regraEmissao(
          db_utils::fieldsMemory($rsTipo, 0)->k00_tipo,
          10,
          db_getsession('DB_instit'),
          date("Y-m-d", db_getsession("DB_datausu")),
          db_getsession('DB_ip')
        );

        $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());
      }

    } catch (Exception $eExeption){

      db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
      exit;
    }

    $aInconsistencias = array();

    for ($i = 0; $i < $quantidade; $i ++) {

      flush();

      if (isset($randomico) and $gerar == "dados" and $quantidade != "" and 1==2) {
        $pular = rand(1,$total_reg);
        $i    += $pular;
      }
      db_fieldsmemory($resultprinc, $i);

      if ($debugar_passou == 1) {
        echo $j23_matric . "<br>";
      }

      db_atutermometro($i,$quantidade,'termometro');

      if (empty($proc)) {
        $clmassamat->sql_record($clmassamat->sql_query_file(null, $j23_matric));
        if ($clmassamat->numrows > 0) {
          continue;
        }
      }

      if ($debugar_passou == 1) {
        echo "passou 0...<br>";
      }

      if (!isset($cidadebranco)) {
        if ($j23_munic == "" and $j23_cxpostal == "") {
          continue;
        }
      }

      if ($debugar_passou == 1) {
        echo "passou 1...<br>";
      }

      $resultmat = $cliptubase->proprietario_record($cliptubase->proprietario_query($j23_matric));
      if (pg_numrows($resultmat) == 0) {
        continue;
      }

      db_fieldsmemory($resultmat, 0);

      if ($debugar_passou == 1) {
        echo "passou 2...<br>";
      }

      if ($especie == "predial" and $j01_tipoimp == "Territorial") {
        continue;
      } elseif ($especie == "territorial" and $j01_tipoimp == "Predial") {
        continue;
      }

      if ($debugar_passou == 1) {
        echo "passou 3...<br>";
      }

      $sSqlNaoGeraExec = "EXECUTE sSqlNaoGera('$j34_setor', '$j34_quadra')";
      $resultgera = db_query($sSqlNaoGeraExec) or die($sSqlNaoGeraExec);

      if (pg_numrows($resultgera) > 0) {
        continue;
      }

      if ($debugar_passou == 1) {
        echo "passou 4...<br>";
      }

      if ($lConvenioCobrancaValido) {

        if ( !DBString::isCNPJ($z01_cgccpf) && !DBString::isCPF($z01_cgccpf) ) {

          $aInconsistencias[$z01_cgmpri] = array(
            $z01_cgmpri,
            $z01_nome,
            "CPF ou CNPJ do contribuinte é inválido."
          );
          continue;
        }
      }

      $sSqlNaoGeraCgmExec = "EXECUTE sSqlNaoGeraCgm($z01_cgmpri)";
      $resultgeracgm      = db_query($sSqlNaoGeraCgmExec) or die($sSqlNaoGeraCgmExec);

      if (pg_numrows($resultgeracgm) > 0) {
        continue;
      }

      $sSqlNaoGeraMatricExec = "EXECUTE sSqlNaoGeraMatric($j23_matric)";
      $resultgeramatric = db_query($sSqlNaoGeraMatricExec) or die($sSqlNaoGeraMatricExec);

      if (pg_numrows($resultgeramatric) > 0) {
        continue;
      }

      if ($debugar_passou == 1) {
        echo "passou 5...<br>";
      }

      // verifica se endereco de entrega é valido
      if (!empty($entregavalido)) {
        if (empty($j23_ender) and empty($j23_cxpostal)) {
          continue;
        }
      }

      if ($debugar_passou == 1) {
        echo "passou 6...<br>";
      }

      if ($processarmovimentacao > 0) {

        $ano_movimentacao_ini = $anousu - $processarmovimentacao;
        $ano_movimentacao_fim = $anousu - 1;

        $sql_movimentacao = " select
          case when pagamentos > 0 then 'SIM' else
          case when iptunump_anoant = 0 and isencao_anoant = 0 and debitos = 0 then 'SIM' else
          case when pagamentos = 0 and isencao_anoant = 0 then 'NAO' else
          'NAO'
          end
          end
          end as situacao
          from
          (
           select
           j20_matric,
           j20_numpre,
           coalesce((select case when arrepaga.k00_numpre     is null then 0 else 1 end from arrepaga inner join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre where arrematric.k00_matric = iptunump.j20_matric and extract (year from k00_dtpaga) >= $ano_movimentacao_ini                    limit 1),0) as pagamentos,
           coalesce((select case when arrecad.k00_numpre      is null then 0 else 1 end from arrecad  inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre  where arrematric.k00_matric = iptunump.j20_matric and extract (year from k00_dtvenc) between $ano_movimentacao_ini and $ano_movimentacao_fim  limit 1),0) as debitos,
           coalesce((select case when iptunump_anoant.j20_numpre is null then 0 else 1 end from iptunump iptunump_anoant                                                         where iptunump_anoant.j20_matric = iptunump.j20_matric and iptunump_anoant.j20_anousu = $ano_movimentacao_fim                          limit 1),0) as iptunump_anoant,
           coalesce(( select case when j46_codigo is null then 0 else 1 end from iptuisen inner join isenexe on j47_codigo = j46_codigo                                    where j46_matric = j20_matric and j47_anousu = $ano_movimentacao_fim                                                             limit 1),0) as isencao_anoant
           from iptunump
           where j20_anousu = $anousu and j20_matric = $j23_matric
           order by j20_matric desc
          ) as x
          order by j20_matric";
        $result_movimentacao = db_query($sql_movimentacao) or die($sql_movimentacao);

        $imprime=0;
        if (pg_numrows($result_movimentacao) > 0) {
          db_fieldsmemory($result_movimentacao,0);

          if ($situacao == "SIM") {
            $imprime=1;
          }

        }

        if ($imprime == 0) {
          continue;
        }

      }

      if ($debugar_passou == 1) {
        echo "passou 7...<br>";
      }

      $sSqlValorMaxExec = "EXECUTE sSqlValorMax($j20_numpre)";
      $rsValorMax = db_query($sSqlValorMaxExec) or die($sSqlValorMaxExec);

      $intNumrowsValorMax = pg_numrows($rsValorMax);
      if($intNumrowsValorMax > 0){
        db_fieldsmemory($rsValorMax,0);
      }

      $quantunica_linha10 = 0;

      if (!empty($unicas)) {

        for ($unica=0; $unica < sizeof($unicas); $unica++) {
          $vencunica = substr($unicas[$unica],0,10);
          $operunica = substr($unicas[$unica],11,10);
          $percunica = substr($unicas[$unica],22,strlen($unicas[$unica])-22);
          $sqlfin    = "select r.k00_numpre,
                               r.k00_dtvenc,
                               r.k00_dtoper,
                               r.k00_percdes,
                               fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,$anousu)
                          from recibounica r
                         where r.k00_numpre = $j20_numpre
                           and r.k00_dtvenc = '$vencunica'
                           and r.k00_dtoper = '$operunica'
                           and k00_percdes  = $percunica";
          $resultfin = db_query($sqlfin) or die($sqlfin);

          if (pg_num_rows($resultfin) > 0) {
            $quantunica_linha10=1;
          }

        }

      }

      if ($quantidadeparcelas != "") {

        if ($parcelamaxima + $quantunica_linha10 != $quantidadeparcelas) {
          continue;
        }
//      echo "<br>numpre: $j20_numpre - parcelamaxima: $parcelamaxima - quantunica_linha10: $quantunica_linha10<br>";exit;

      }

      if ($tipo == "txtbsj" ) { // nao pode processar registro se nao tiver parcela unica - ocorreu erro na emissao de 2012 por causa disso, pois criaram novas matriculas, efetuaram o calculo e nao gerar a unica e isso gerou erro nos txts, pois a logica se perdeu, pois espera sempre que tenha cota unica para TXTBSJ - evandro - 20120131

        if ( $quantunica_linha10 == 0 ) {
          continue;
        }

      }

      $intervalorvlrminimo = false;

      if($viptu < $vlrmin or $viptu > $vlrmax) { // se valor total do iptu for menor que valor minimo ou maior que o valor maximo
        continue;
      }

      if ($debugar_passou == 1) {
        echo "passou 8...<br>";
      }

      if($viptu >= $vlrminunica and $viptu <= $vlrmaxunica) {
        $intervalorvlrminimo = true;
      }

      $gerarparcelado = true;

      if ($intervalo == "gerar") {

        if ($intervalorvlrminimo == true) {
          $gerarparcelado = true;
        } else {
          $gerarparcelado = false;
        }

      } elseif ($intervalo == "naogerar") {

        if ($intervalorvlrminimo == true) {
          $gerarparcelado = false;
          $gerarparcelado = true;
        }

      }

      $sSqlAreaConstrExec = "EXECUTE sSqlAreaConstr($j23_matric)";
      $resultsqlareaconstr = db_query($sSqlAreaConstrExec) or die($sSqlAreaConstrExec);

      if (!empty($resulttestada) and pg_numrows($resulttestada) > 0) {
        db_fieldsmemory($resultsqlareaconstr, 0);
      } else {
        $j39_area = 0;
      }

      $sSqlCalcIptuCaleExec = "EXECUTE sSqlCalcIptuCale($anousu, $j23_matric)";
      $resultcalc = db_query($sSqlCalcIptuCaleExec) or die($sSqlCalcIptuCaleExec);

      if (pg_numrows($resultcalc) > 0) {
        db_fieldsmemory($resultcalc, 0);
      } else {
        $j22_valor = 0;
      }

      $sSqlFinExec = "EXECUTE sSqlFin($anousu, $j23_matric)";
      $resultfin = db_query($sSqlFinExec) or die($sSqlFinExec);

      if (pg_numrows($resultfin) > 0) {
        db_fieldsmemory($resultfin, 0);

        $sSqlSetFiscExec = "EXECUTE sSqlSetFisc($j23_matric)";
        $resultsetfisc = db_query($sSqlSetFiscExec) or die($sSqlSetFiscExec);

        if (pg_numrows($resultsetfisc) == 0) {
          $j91_codigo = 0;
        } else {
          db_fieldsmemory($resultsetfisc,0);
        }

        $sSqlJurosExec = "EXECUTE sSqlJuros($anousu)";
        $resultjuros = db_query($sSqlJurosExec) or die($sSqlJurosExec);


        if (pg_numrows($resultjuros) == 0) {
          $k02_juros  = 0;
          $k140_faixa = 0;
        } else {
          db_fieldsmemory($resultjuros,0);
        }

        $sSqlIsenExec = "EXECUTE sSqlIsen($anousu, $j23_matric)";
        $resultisen = db_query($sSqlIsenExec) or die($sSqlIsenExec);

        if (pg_numrows($resultisen) == 0) {

          $j46_tipo = 0;
          $j46_codigo = 0;
          $j45_descr = "";
          $j46_dtinc = "";

        } else {
          db_fieldsmemory($resultisen,0);
        }

        $sSqlArrecadExec = "EXECUTE sSqlArrecad($j20_numpre)";
        $resultfinarrecad = db_query($sSqlArrecadExec) or die($sSqlArrecadExec);

        $aParcelasArrecad = db_utils::getCollectionByRecord($resultfinarrecad);

        if (pg_numrows($resultfinarrecad) == 0) {
          continue;
        }

        $k00_tipo = $aParcelasArrecad[0]->k00_tipo;

        $sSqlArretipoTxBancoExec = "EXECUTE sSqlArretipoTxBanco($k00_tipo)";
        $rsSqlArretipo = db_query($sSqlArretipoTxBancoExec) or die($sSqlArretipoTxBancoExec);

        $iNumRows      = pg_numrows($rsSqlArretipo);

        if ( $iNumRows == 0 ) {
	        echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo!";
          exit;
        }

  db_fieldsmemory($rsSqlArretipo,0);
  $k00_descr = $k00_descr;

  if(isset($tx_banc) && $tx_banc != ""){
    $taxa_bancaria = $tx_banc;
  }else{
    $taxa_bancaria = 0;
  }

        $resultmat = $cliptunump->sql_record($cliptunump->sql_query($anousu, $j23_matric));

        $propri_escritura = $proprietario;

        $passar = true;

        if (isset($parcobrig) and $parcobrig != "") {

          $sqlparcobrig = "select k00_matric
                             from arrematric m
                                  inner join arrecad a on m.k00_numpre = a.k00_numpre
                            where m.k00_numpre = {$j20_numpre}
                            group by k00_matric, m.k00_numpre
                           having array_accum(k00_numpar) @> array[{$parcobrig}]
                            limit 1";
          $resultfinparcobrig = db_query($sqlparcobrig) or die($sqlparcobrig);

          if (pg_numrows($resultfinparcobrig) == 0) {
            $passar = false;
          }

        }

        if ($filtroprinc == "compgto") {

          $sSqlFinPriPagaComPgtoExec = "EXECUTE sSqlFinPriPagaComPgto($j20_numpre, ".date("Y-m-d", db_getsession("DB_datausu")).")";
          $resultfinpripaga = db_query($sSqlFinPriPagaComPgtoExec) or die($sSqlFinPriPagaComPgtoExec);

          if (pg_numrows($resultfinpripaga) > 0) {
            $passar = false;
          }

        } elseif ($filtroprinc == "sempgto") {

          $sSqlFinPriPagaSemPgtoExec = "EXECUTE sSqlFinPriPagaSemPgto($j20_numpre)";
          $resultfinpripaga = db_query($sSqlFinPriPagaSemPgtoExec) or die($sSqlFinPriPagaSemPgtoExec);

          if (pg_numrows($resultfinpripaga) == 1) {
            $passar = false;
          }

        }

        if ($passar == false) {
          continue;
        }

        if ($debugar_passou == 1) {
          echo "passou 9...<br>";
        }

        if ($cliptunump->numrows > 0) {
          $quantos ++;

          $sSqlCalcQntImpTaxasExec = "EXECUTE sSqlCalcQntImpTaxas($anousu, $j23_matric)";
          $resultcalc = db_query($sSqlCalcQntImpTaxasExec) or die($sSqlCalcQntImpTaxasExec);

          if (pg_numrows($resultcalc) == 0) {
            $total_j21_valor = 0;
            $quant_imposto_taxas = 0;
          } else {
            db_fieldsmemory($resultcalc, 0, true);
          }

          if ( (int) $quantidade_registros_real > 0 and $gerar == "dados" ) {
            if ( $quantos > $quantidade_registros_real ) {
              db_atutermometro($quantidade,$quantidade,'termometro');
              flush();
              break;
            }
          }

          if ($gerar == "dados") {

            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, str_pad($quantos, 10));
              fputs($clabre_arquivo->arquivo, ($j01_tipoimp == "Predial"?"2":"1"));
              fputs($clabre_arquivo->arquivo, str_pad($j01_tipoimp, 11));
              fputs($clabre_arquivo->arquivo, str_pad($j23_matric, 10));
              fputs($clabre_arquivo->arquivo, str_pad($anousu, 4));
              fputs($clabre_arquivo->arquivo, str_pad(0, 10));
              fputs($clabre_arquivo->arquivo, str_pad($j86_iptucadzonaentrega, 5));
              fputs($clabre_arquivo->arquivo, str_pad($j34_zona, 5));
              fputs($clabre_arquivo->arquivo, str_pad($j91_codigo, 5));
              fputs($clabre_arquivo->arquivo, str_pad($j34_setor, 4));
              fputs($clabre_arquivo->arquivo, str_pad($j34_quadra, 4));
              fputs($clabre_arquivo->arquivo, str_pad($j34_lote, 4));
            } elseif ($tipo == "txtbsj" and $quantos == 1) {

              $sCedente = "1035"; // ###falta###

              $linha00 = "";
              $linha00 .= "BSJR00";
              $linha00 .= str_replace("/","",date('d/m/y',db_getsession('DB_datausu')));
              $linha00 .= str_replace(":","",db_hora(0,"H:i:s"));
              $linha00 .= $sCedente;
              $linha00 .= "    ";
              $linha00 .= "N";
              $linha00 .= "IPTU".substr($anousu,2,2);
              $linha00 .= str_repeat(" ",255);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha00,"",$contador,288));

            }

          } else {

            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("CONTADOR","CONTADOR",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("ESPECIE","CODIGO DO TIPO DO IMOVEL - 1 = TERRITORIAL E 2 = PREDIAL",$contador, 1));
              fputs($clabre_arquivo->arquivo, db_contador("TIPOIMOVEL","EXPRESSAO DO TIPO DO IMOVEL - TERRITORIAL OU PREDIAL",$contador, 11));
              fputs($clabre_arquivo->arquivo, db_contador("MATRICULA","MATRICULA",$contador, 10));
              fputs($clabre_arquivo->arquivo, db_contador("EXERCICIO","EXERCÍCIO DO CALCULO",$contador, 4));
              fputs($clabre_arquivo->arquivo, db_contador("NOTIFICACAO","NOTIFICACAO",$contador, 10));
              fputs($clabre_arquivo->arquivo, db_contador("ZONAENTREGA","ZONA DE ENTREGA",$contador, 5));
              fputs($clabre_arquivo->arquivo, db_contador("ZONAFISCALLOTE","ZONA FISCAL DA TABELA LOTE",$contador, 5));
              fputs($clabre_arquivo->arquivo, db_contador("SETORFISCAL","SETOR FISCAL",$contador, 5));
              fputs($clabre_arquivo->arquivo, db_contador("SETORCARTO","SETOR CARTOGRAFICO (DO SETOR/QUADRA/LOTE)",$contador,4));
              fputs($clabre_arquivo->arquivo, db_contador("QUADRACARTO","QUADRA CARTOGRAFICA",$contador,4));
              fputs($clabre_arquivo->arquivo, db_contador("LOTECARTO","LOTE CARTOGRAFICA",$contador,4));
            }

          }

          if ($j40_refant == "") {
            $j40_refant = "....";
          }

          $sqlsub = split('\.', $j40_refant);
          if ($gerar == "dados") {

            if ($tipo == "txt") {

              if (isset($sqlsub)) {
                if (sizeof($sqlsub) >= 5) {
                  fputs($clabre_arquivo->arquivo, substr(str_pad($sqlsub[4], 4),0,4));
                } else {
                  fputs($clabre_arquivo->arquivo, "    ");
                }
              } else {
                fputs($clabre_arquivo->arquivo, "    ");
              }

            }

          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("SUBLOTELOC","SUBLOTE",$contador,4));
            }
          }

          if ($tipo == "txtbsj") {
            $linha10 = "BSJR10";
            }

          if ($z01_cgmpri <> $z01_numcgm) {
            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, substr(str_pad($z01_nome, 40),0,40));
                fputs($clabre_arquivo->arquivo, substr(str_pad($z01_nome, 40),0,40));
              } elseif ($tipo == "txtbsj") {
                $linha10 .= substr(str_pad($z01_nome, 40, " ",STR_PAD_RIGHT),0,40);
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("NOME","NOME A SER IMPRESSO NO CARNE",$contador,40));
                fputs($clabre_arquivo->arquivo, db_contador("PROMITENTE","PROMITENTE COMPRADOR POR CONTRATO",$contador,40));
              }
            }
            $propri_contrato = $z01_nome;
          } else {
            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, substr(str_pad($propri_escritura, 40),0,40));
                fputs($clabre_arquivo->arquivo, str_pad(' ', 40));
              } elseif ($tipo == "txtbsj") {
                $linha10 .= substr(str_pad($propri_escritura, 40, " ",STR_PAD_RIGHT),0,40);
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("NOME","NOME A SER IMPRESSO NO CARNE",$contador,40));
                fputs($clabre_arquivo->arquivo, db_contador("PROMITENTE","PROMITENTE COMPRADOR POR CONTRATO",$contador,40));
              }
            }
            $propri_contrato = '';
          }

          if ($gerar == "dados") {

            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, substr(str_pad($propri_escritura, 40),0,40));

              fputs($clabre_arquivo->arquivo, substr(str_pad($z01_ender, 40),0,40));
              fputs($clabre_arquivo->arquivo, str_pad($z01_numero, 10));
              fputs($clabre_arquivo->arquivo, substr(str_pad($z01_compl, 20),0,20));
              fputs($clabre_arquivo->arquivo, substr(str_pad($z01_munic, 20),0,20));
              fputs($clabre_arquivo->arquivo, str_pad($z01_cep, 8));
              fputs($clabre_arquivo->arquivo, str_pad($z01_uf, 2));
              fputs($clabre_arquivo->arquivo, str_pad($z01_cgccpf, 20));

              fputs($clabre_arquivo->arquivo, substr(str_pad($codpri, 6, "0", STR_PAD_LEFT),0,6));
              fputs($clabre_arquivo->arquivo, str_pad($tipopri, 20));
              fputs($clabre_arquivo->arquivo, str_pad($nomepri, 50));
              fputs($clabre_arquivo->arquivo, str_pad($j39_numero, 10));
              fputs($clabre_arquivo->arquivo, substr(str_pad($j39_compl, 20), 0, 20));
              fputs($clabre_arquivo->arquivo, str_pad($j13_descr, 40));

              if (trim($j23_cxpostal) != "" and $j23_cxpostal > 0) {
                $j23_ender = "CAIXA POSTAL: $j23_cxpostal";
              }

              fputs($clabre_arquivo->arquivo, str_pad((trim($j23_ender )==""?$nomepri:$j23_ender), 50));
              fputs($clabre_arquivo->arquivo, str_pad((trim($j23_ender)==""?$j39_numero:$j23_numero), 10));
              fputs($clabre_arquivo->arquivo, substr(str_pad((trim($j23_ender)==""?$j39_compl:$j23_compl), 20), 0, 20));
              fputs($clabre_arquivo->arquivo, str_pad($j23_bairro, 40));
              fputs($clabre_arquivo->arquivo, str_pad($j23_munic, 40));
              fputs($clabre_arquivo->arquivo, str_pad($j23_uf, 2));
              fputs($clabre_arquivo->arquivo, str_pad($j23_cep, 10));
              fputs($clabre_arquivo->arquivo, str_pad($j23_cxpostal, 10));
              fputs($clabre_arquivo->arquivo, str_pad($j23_dest, 40));

              fputs($clabre_arquivo->arquivo, str_repeat(" ", 3));
              fputs($clabre_arquivo->arquivo, str_repeat(" ", 5));
              if ($j45_descr == "") {
                fputs($clabre_arquivo->arquivo, str_repeat(" ", 40));
                fputs($clabre_arquivo->arquivo, str_repeat(" ", 10));
              } else {
                fputs($clabre_arquivo->arquivo, str_pad($j45_descr, 40));
                fputs($clabre_arquivo->arquivo, db_formatar($j46_dtinc,'d'));
              }

            } elseif ($tipo == "txtbsj") {

              if (trim($j23_cxpostal) != "" and $j23_cxpostal > 0) {
                $linha10 .= str_pad("CAIXA POSTAL: $j23_cxpostal",40," ",STR_PAD_RIGHT);
              } else {

                if ( strlen(trim($j23_ender)) >= 40 ) {
                  $j23_ender = substr($j23_ender,0,34);
                }

                $linha10 .= substr( str_pad(substr( trim($j23_ender) . (strlen(trim($j23_numero)) > 0?", ":"") . trim($j23_numero) . (strlen(trim($j23_compl)) > 0?"/":"") . trim($j23_compl) . "-" . $j23_bairro ,0,40),40," ",STR_PAD_RIGHT) ,0,40);
              }
              $linha10 .= substr(str_pad($j23_munic, 20," ",STR_PAD_RIGHT),0,20);
              $linha10 .= str_pad(substr($j23_cep,0,5), 5);
              $linha10 .= str_pad($j23_uf, 2," ",STR_PAD_RIGHT);
              $linha10 .= str_repeat(" ", 17);
              $linha10 .= str_repeat(" ", 80);
              $linha10 .= str_pad($anousu, 4, "0", STR_PAD_LEFT) . " ";

              $linha10 .= str_pad($parcelamaxima + $quantunica_linha10,2,"0",STR_PAD_LEFT);
              $linha10 .= str_repeat("0", 15);
              $linha10 .= str_repeat("0", 5);
              $linha10 .= str_repeat("0", 2);
              $linha10 .= ($mensagemdebitosanosanteriores == ""?"N":"S");
              $linha10 .= ($quantunica_linha10 == 0?"N":"S");
              $linha10 .= str_pad(substr($j23_cep,0,8), 8, "0", STR_PAD_LEFT);
              $linha10 .= str_pad($parcelamaxima,2,"0",STR_PAD_LEFT);
              $linha10 .= str_repeat(" ", 37);
              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha10,"",$contador,288));

              // parte 1
              $linha31 = "BSJR30";

              $imp_linha31 = " ";
              $imp_linha31 .= "MATRIC: $j23_matric - S/Q/L: " . $j34_setor . "/" . $j34_quadra . "/" . $j34_lote . " - BAIRRO: " . $j13_descr;
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $imp_linha31 .= $tipopri . " " . $nomepri . ", " . $j39_numero . "/" . $j39_compl;
              if (trim($j23_cxpostal) != "" and $j23_cxpostal > 0) {
          $imp_linha31 .= " - CX POSTAL: $j23_cxpostal";
              }
              $imp_linha31 .= " - ALIQ: $j23_aliq - TIPO: $j01_tipoimp";
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $imp_linha31 .= "AREA CONSTR: $j39_area";
              $imp_linha31 .= " - EXERC: $anousu - TOTAL (IMPOSTO+TAXAS): " . trim(db_formatar($total_j21_valor, 'f', ' ', 15));
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $linha31 .= str_repeat(" ",24);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha31,"",$contador,288));

              // parte 2
              $linha31 = "BSJR30";

              $imp_linha31 = " ";
              $imp_linha31 .= "AREA TOTAL LOTE: $j34_area - AREA LOTE P/CALCULO: $j23_arealo - VLR M2 TERRENO: " . trim(db_formatar($j23_m2terr, 'f', ' ', 10));
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $imp_linha31 .= "VALOR VENAL DO TERRENO: " . trim(db_formatar($j23_vlrter, 'f', ' ', 15)) . " - VALOR VENAL EDIFICACOES: " . trim(db_formatar($j22_valor, 'f', ' ', 15));
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $imp_linha31 .= "VALOR VENAL TOTAL: " . trim(db_formatar($j23_vlrter + $j22_valor, 'f', ' ', 15));
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $linha31 .= str_repeat(" ",24);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha31,"",$contador,288));

              // parte 3
              $linha31 = "BSJR30";

              $imp_linha31 = " ";
              $imp_linha31 .= "DESPESAS EXTRAS: " . trim(db_formatar($taxa_bancaria, 'f', ' ', 15));
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $imp_linha31 .= $mensagemdebitosanosanteriores;
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $imp_linha31 .= "";
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $linha31 .= str_repeat(" ",24);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha31,"",$contador,288));

            }

          } else {

            if ($tipo == "txt") {

              fputs($clabre_arquivo->arquivo, db_contador("PROPRIETARIOESCRITURA","PROPRIETARIO DA ESCRITURA",$contador,40));

              fputs($clabre_arquivo->arquivo, db_contador("ENDNOME","ENDERECO DO CGM DO PROPRIETARIO",$contador,40));
              fputs($clabre_arquivo->arquivo, db_contador("NUMIMONOME","NUMERO DO IMOVEL DO CGM DO PROPRIETARIO",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("COMPLIMONOME","COMPLEMENTO DO CGM DO PROPRIETARIO",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("MUNICNOME","MUNICIPIO DO CGM DO PROPRIETARIO",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("CEPNOME","CEP DO CGM DO PROPRIETARIO",$contador,8));
              fputs($clabre_arquivo->arquivo, db_contador("UFNOME","UF DO CGM DO PROPRIETARIO",$contador,2));
              fputs($clabre_arquivo->arquivo, db_contador("CNPJCPFNOME","CNPJ/CPF DO CGM DO PROPRIETARIO",$contador,20));

              fputs($clabre_arquivo->arquivo, db_contador("CODLOGIMO","CODIGO DO LOGRADOURO DO IMOVEL",$contador,6));
              fputs($clabre_arquivo->arquivo, db_contador("TIPOLOGIMO","TIPO DO LOGRADOURO DO IMOVEL",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("DESCRLOGIMO","NOME DO LOGRADOURO PRINCIPAL DO IMOVEL",$contador,50));
              fputs($clabre_arquivo->arquivo, db_contador("NUMIMOIMO","NUMERO DO IMOVEL",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("COMPLIMOIMO","COMPLEMENTO DO IMOVEL",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("BAIIMO","BAIRRO DO IMOVEL",$contador,40));

              fputs($clabre_arquivo->arquivo, db_contador("LOGRADENDENT","DESCRICAO DO LOGRADOURO DO ENDERECO DE ENTREGA", $contador, 50));
              fputs($clabre_arquivo->arquivo, db_contador("NUMIMOENDENT","NUMERO DO ENDERECO DE ENTREGA", $contador, 10));
              fputs($clabre_arquivo->arquivo, db_contador("COMPLENDENT","COMPLEMENTO DO ENDERECO DE ENTREGA", $contador, 20));
              fputs($clabre_arquivo->arquivo, db_contador("BAIENDENT","BAIRRO DO ENDERECO DE ENTREGA", $contador, 40));
              fputs($clabre_arquivo->arquivo, db_contador("CIDENDENT","CIDADE DO ENDERECO DE ENTREGA", $contador, 40));
              fputs($clabre_arquivo->arquivo, db_contador("UFENDENT","UF DO ENDERECO DE ENTREGA", $contador, 2));
              fputs($clabre_arquivo->arquivo, db_contador("CEPENDENT","CEP DO ENDERECO DE ENTREGA", $contador, 10));
              fputs($clabre_arquivo->arquivo, db_contador("CXPENDENT","CAIXA POSTAL DO ENDERECO DE ENTREGA", $contador, 10));
              fputs($clabre_arquivo->arquivo, db_contador("DESTENDENT","DESTINATARIO DO ENDERECO DE ENTREGA", $contador, 40));

              fputs($clabre_arquivo->arquivo, db_contador("BRANCOS","BRANCOS",$contador,3));
              fputs($clabre_arquivo->arquivo, db_contador("BRANCOS","BRANCOS",$contador,5));
              fputs($clabre_arquivo->arquivo, db_contador("DESCRISEN","DESCRICAO DO TIPO DE ISENCAO",$contador,40));
              fputs($clabre_arquivo->arquivo, db_contador("LANCISEN","DATA DE LANCAMENTO DA ISENCAO",$contador,10));

            }

          }

          $sSqlCalcQntTaxasExec = "EXECUTE sSqlCalcQntTaxas($anousu, $j23_matric)";
          $resultcalc = db_query($sSqlCalcQntTaxasExec) or die($sSqlCalcQntTaxasExec);

          if (pg_numrows($resultcalc) == 0) {
            $total_j21_valor = 0;
            $quant_taxas = 0;
          } else {
            db_fieldsmemory($resultcalc, 0, true);
          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_formatar($total_j21_valor, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, str_pad($quant_taxas, 3));
            }
          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("TOTREGLANC","TOTAL DOS VALORES LANCADOS (IMPOSTO + TAXAS)",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("QUANTREGLANC","QUANTIDADE DE LANCAMENTOS (IMPOSTO + TAXAS)",$contador,3));
            }
          }


          $sSqlCalcQntTaxasReceitaExec = "EXECUTE sSqlCalcQntTaxasReceita($anousu, $j23_matric)";
          $resultcalc = db_query($sSqlCalcQntTaxasReceitaExec) or die($sSqlCalcQntTaxasReceitaExec);


          if (pg_numrows($resultcalc) == 0) {
            $total_j21_valor = 0;
            $quant_taxas = 0;
          } else {
            db_fieldsmemory($resultcalc, 0, true);
          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_formatar($total_j21_valor, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, str_pad($quant_taxas, 3));
            }
          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("TOTREGLANCTAXAS","TOTAL DOS VALORES LANCADOS (TAXAS)",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("QUANTREGLANCTAXAS","QUANTIDADE DE LANCAMENTOS (TAXAS)",$contador,3));
            }
          }

          $anoant = db_getsession("DB_anousu") - 1;

          $sSqlIptuExec = "EXECUTE sSqlIptu($anousu, $anoant, $j23_matric)";
          $resultiptu = db_query($sSqlIptuExec) or die($sSqlIptuExec);

          $iptucor       = 0;
          $iptujuros     = 0;
          $iptumulta     = 0;
          $iptudesconto  = 0;
          $iptutotal     = 0;

          if (pg_numrows($resultiptu) > 0) {
            for ($iptu = 0; $iptu < pg_numrows($resultiptu); $iptu++) {
              db_fieldsmemory($resultiptu, $iptu);
              $iptucor      += (float) substr($fc_calcula,14,13);
              $iptujuros    += (float) substr($fc_calcula,27,13);
              $iptumulta    += (float) substr($fc_calcula,40,13);
              $iptudesconto += (float) substr($fc_calcula,53,13);
              $iptutotal    += (float) substr($fc_calcula,14,13) + (float) substr($fc_calcula,27,13) + (float) substr($fc_calcula,40,13) - (float) substr($fc_calcula,53,13);
            }
          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_formatar($iptucor, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, db_formatar($iptujuros, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, db_formatar($iptumulta, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, db_formatar($iptudesconto, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, db_formatar($iptutotal, 'f', ' ', 15));
            }
          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("VALORCORRIGIDOIPTU$anoant","VALOR CORRIGIDO DA IPTU DESTA MATRICULA NO ANO $anoant",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("VALORJUROSIPTU$anoant","VALOR DOS JUROS DA IPTU DESTA MATRICULA NO ANO $anoant",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("VALORMULTAIPTU$anoant","VALOR DA MULTA DA IPTU DESTA MATRICULA NO ANO $anoant",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("VALORDESCONTOIPTU$anoant","VALOR DO DESCONTO DA IPTU DESTA MATRICULA NO ANO $anoant",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("VALORTOTALIPTU$anoant","VALOR TOTAL DA IPTU DESTA MATRICULA NO ANO $anoant",$contador,15));
            }
          }

          if ($gerar == "layout") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("CODIGOFACE","CODIGO DA FACE",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("VALORM2TERRENOFACE","VALOR DO M2 DO TERRENO BASEADO NA FACE",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("VALORM2CONSTRFACE","VALOR DO M2 DAS EDIFICACOES BASEADO NA FACE",$contador,20));
            }
          }


          $sSqlValorM2Exec = "EXECUTE sSqlValorM2($j01_idbql, $anousu, $j23_matric)";
          $resultvalorm2 = db_query($sSqlValorM2Exec) or die($sSqlValorM2Exec);

          if (pg_numrows($resultvalorm2) == 0) {

            $sqlvalorm2 = " select  j49_face as j37_face,
              j81_valorterreno as j37_valor, j37_outros,
                               case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle,
                               j81_valorconstr as j37_vlcons
                                 from testpri
                                 inner join face on j49_face = j37_face
                                 left  join facevalor on j81_face = j37_face and j81_anousu = $anousu
                                 inner join testada on j49_face = j36_face and j49_idbql = j36_idbql
                                 where j49_idbql = $j01_idbql";
            $resultvalorm2 = db_query($sqlvalorm2);
            if (pg_numrows($resultvalorm2) == 0) {

              if ($gerar == "dados") {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, str_pad($j37_face, 10));
                  fputs($clabre_arquivo->arquivo, db_formatar(0, 'f', ' ', 20));
                  fputs($clabre_arquivo->arquivo, db_formatar(0, 'f', ' ', 20));
                }
              }
            } else {
              db_fieldsmemory($resultvalorm2,0);
              if ($gerar == "dados") {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, str_pad($j37_face, 10));
                  fputs($clabre_arquivo->arquivo, db_formatar($j37_valor, 'f', ' ', 20));
                  fputs($clabre_arquivo->arquivo, db_formatar($j37_vlcons, 'f', ' ', 20));
                }
              }
            }

          } else {
            db_fieldsmemory($resultvalorm2,0);
            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, str_pad($j37_face, 10));
                fputs($clabre_arquivo->arquivo, db_formatar($j37_valor, 'f', ' ', 20));
                fputs($clabre_arquivo->arquivo, db_formatar($j37_vlcons, 'f', ' ', 20));
              }
            }
          }

          $sSqlCalcIptuCaleExec = "EXECUTE sSqlCalcIptuCale($anousu, $j23_matric)";
          $resultcalc = db_query($sSqlCalcIptuCaleExec) or die($sSqlCalcIptuCaleExec);


          if (pg_numrows($resultcalc) > 0) {
            db_fieldsmemory($resultcalc, 0);
          } else {
            $j22_valor = 0;
          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_formatar($j23_vlrter, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, db_formatar($j22_valor, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, db_formatar($j23_vlrter + $j22_valor, 'f', ' ', 15));
              fputs($clabre_arquivo->arquivo, str_pad($j23_aliq, 6));
            }
          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("VLRVENALTER", "VALOR VENAL TERRENO",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("VLRVENALEDI", "VALOR VENAL EDIFICACOES",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("VLRVENALTOTAL", "VALOR VENAL TOTAL (TERRENO + EDIFICACOES)",$contador,15));
              fputs($clabre_arquivo->arquivo, db_contador("ALIQ","ALIQUOTA",$contador,6));
            }
          }

          $mensagemdebitosanosanteriores = "";
          if ( $db21_codcli == 19985 ) {
            $mensagemdebitosanosanteriores = "Parabéns! Contribuinte que paga os tributos colabora para a construção da Nova Maricá.";
          }

          if ($mensagemanosanteriores == "s") {
            $sql_debitos = "select fc_tipocertidao($j23_matric,'m',current_date,'')";
            $result_debitos = db_query($sql_debitos) or die($sql_debitos);
            if (pg_numrows($result_debitos) > 0) {
              db_fieldsmemory($result_debitos,0);
              if ($fc_tipocertidao == "positiva") {
                $mensagemdebitosanosanteriores = "EXISTEM DÉBITOS EM ABERTO PARA ESTA MATRÍCULA ATÉ A DATA " . db_formatar(date("Y-m-d",db_getsession("DB_datausu")),'d');
              }
            }
          }

          // valores das unica e parcelado

          if($temUnica) {
            $lReciboGerado = false;

            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, str_pad(sizeof($unicas),3));
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("TOTUNICAS", "TOTAL DE PARCELA UNICA",$contador,3));
              }
            }

            // unicas
            $linha20 = "BSJR20";

            for ($unica=0; $unica < sizeof($unicas); $unica++) {

              $vencunica = substr($unicas[$unica],0,10);
              $operunica = substr($unicas[$unica],11,10);
              $percunica = substr($unicas[$unica],22,strlen($unicas[$unica])-22);
              $sqlfin    = "select r.k00_numpre,
                                   r.k00_dtvenc,
                                   r.k00_dtoper,
                                   r.k00_percdes,
                                   fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,$anousu)
                              from recibounica r
                             where r.k00_numpre = $j20_numpre
                               and r.k00_dtvenc = '$vencunica'
                               and r.k00_dtoper = '$operunica'
                               and k00_percdes  = $percunica";
              $resultfin = db_query($sqlfin) or die($sqlfin);

              if (pg_num_rows($resultfin) > 0) {

                for ($unicont = 0; $unicont < pg_numrows($resultfin); $unicont ++) {
                  db_fieldsmemory($resultfin, $unicont);

                  $uvlrhis =  substr($fc_calcula,1,13);
                  $uvlrcor = substr($fc_calcula,14,13);
                  $uvlrjuros = substr($fc_calcula,27,13);
                  $uvlrmulta = substr($fc_calcula,40,13);
                  $uvlrdesconto = substr($fc_calcula,53,13);
                  $utotal = $uvlrcor + $uvlrjuros + $uvlrmulta - $uvlrdesconto + $taxa_bancaria;

                  $k00_numpar = 0;

                  if ($gerar == "dados") {
                    if ($tipo == "txt") {
                      fputs($clabre_arquivo->arquivo,db_formatar($k00_dtoper,'d'));
                      fputs($clabre_arquivo->arquivo,db_formatar($k00_dtvenc,'d'));
                      fputs($clabre_arquivo->arquivo,db_formatar($k00_percdes,'f',' ',15));
                      fputs($clabre_arquivo->arquivo,db_formatar($uvlrhis,'f',' ',15));
                      fputs($clabre_arquivo->arquivo,db_formatar($uvlrcor,'f',' ',15));
                      fputs($clabre_arquivo->arquivo,db_formatar($uvlrjuros,'f',' ',15));
                      fputs($clabre_arquivo->arquivo,db_formatar($uvlrmulta,'f',' ',15));
                      fputs($clabre_arquivo->arquivo,db_formatar($uvlrdesconto,'f',' ',15));
                      fputs($clabre_arquivo->arquivo,db_formatar($uvlrcor,'f',' ',15));
                      fputs($clabre_arquivo->arquivo,db_formatar($utotal,'f',' ',15));
                    }
                  } else {
                    if ($tipo == "txt") {
                      fputs($clabre_arquivo->arquivo,db_contador("OPERUNICA".$k00_percdes,"OPERACAO/LANCAMENTO DA UNICA DE $k00_percdes% DE DESCONTO COM VENCIMENTO EM " . db_formatar($k00_dtvenc,'d'),$contador,10));
                      fputs($clabre_arquivo->arquivo,db_contador("VENCUNICA".$k00_percdes,"VENCIMENTO",$contador,10));
                      fputs($clabre_arquivo->arquivo,db_contador("PERCDESCUNICA".$k00_percdes,"PERCENTUAL DE DESCONTO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("VLRHISTUNICA".$k00_percdes,"VALOR HISTORICO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("VLRCORUNICA".$k00_percdes,"VALOR CORRIGIDO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("JURUNICA".$k00_percdes,"JUROS",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("MULUNICA".$k00_percdes,"MULTA",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("DESCUNICA".$k00_percdes,"DESCONTO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("TOTALUNICA".$k00_percdes,"TOTAL (VALOR CORRIGIDO + JUROS + MULTA)",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("TOTALLIQUNICA".$k00_percdes,"TOTAL - DESCONTO DE " . $k00_percdes,$contador,15));
                    }
                  }
                  $numpre  = db_numpre($k00_numpre).str_pad($k00_numpar,3,"0",STR_PAD_LEFT);
                  $numpref = db_numpre($k00_numpre).str_pad($k00_numpar,3,"0",STR_PAD_LEFT);

                /* PARA PADRAO COBRANCA */

                $sqltipo = " select k00_tipo from arrecad where k00_numpre = $k00_numpre limit 1 ";
                $rsTipo  = db_query($sqltipo);

                if (pg_numrows($rsTipo) == 0) {
                  echo "Erro ao processar tipos de debito! Contate suporte!";
                  exit;
                }

                db_fieldsmemory($rsTipo,0);

                  $k03_numpreunica = '';
                  if ($gerar == "dados") {

                    db_inicio_transacao();

                    try {
                      $oRecibo = new recibo(2,null, 5);
                      $oRecibo->addNumpre($k00_numpre,0);
                      $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
                      $oRecibo->setDataRecibo($k00_dtvenc);
                      $oRecibo->setDataVencimentoRecibo($k00_dtvenc);
                      $oRecibo->emiteRecibo();
                      $k03_numpreunica = $oRecibo->getNumpreRecibo();
                    } catch ( Exception $eException ) {
                      db_fim_transacao(true);
                      db_redireciona("db_erros.php?fechar=true&db_erro=Erro2 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eException->getMessage()}");
                      exit;
                    }

                    db_fim_transacao();

                  }

                  if ($gerar == 'layout') {
                    if ($tipo == "txt") {
                      fputs($clabre_arquivo->arquivo,db_contador("CODARREC".$k00_percdes, "NUMERO DE ARRECADACAO",$contador,$iTamCodArrecadao));
                    }
                  }

                }

                $vlrbar = db_formatar(str_replace('.','',str_pad(number_format($utotal,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');

                if ($barrasunica == "seis") {
                  $terceiro = "6";
                } else {
                  $terceiro = "7";
                }

               if ($gerar == "dados") {

                  try {
                    $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpreunica,0,$utotal,$vlrbar,$k00_dtvenc,$terceiro);
                  } catch (Exception $eExeption){
                    db_redireciona("db_erros.php?fechar=true&db_erro=Erro3 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eExeption->getMessage()}");
                    exit;
                  }

                  /**
                   * Quando o convenio for um convenio de cobrança registrada válido, adiciona o recibo gerado na fila para geração do arquivo de cobrança registrada
                   */
                  if ($lConvenioCobrancaValido) {
                    CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
                  }

                  if ($oRegraEmissao->getCadTipoConvenio() == 5 || $oRegraEmissao->getCadTipoConvenio() == 6) {
                    $aNossoNumero = explode("-",$oConvenio->getNossoNumero());
                    $sNossoNumero    = $aNossoNumero[0];
                    $sDigNossoNumero = $aNossoNumero[1];
                  } else {
                    $sNossoNumero    = $oConvenio->getNossoNumero();
                    $sDigNossoNumero = '';
                  }
                  $oNossoNumero = new stdClass();
                  $oNossoNumero->sNumero = $sNossoNumero;
                  $oNossoNumero->sDigito = $sDigNossoNumero;

                  $aListaNossoNumeroUnica[ ( $unica + 1 ) ] = $oNossoNumero;

                  $codigobarras    = $oConvenio->getCodigoBarra();
                  $linhadigitavel  = $oConvenio->getLinhaDigitavel();

                }

                if( $oRegraEmissao->isCobranca() ) {

                  if ( $k03_numpreunica == '' && $k00_dtvenc == '' && $utotal == '' ){
                    $fc_febraban = str_repeat('0',101);
                  }

                  if ($gerar == "dados") {
                    $fc_febraban = $linhadigitavel.",".$codigobarras;
                    $numpreunica = db_numpre($k03_numpreunica).str_pad(null,3,"0",STR_PAD_LEFT);
                    if ($tipo == "txt") {
                      fputs($clabre_arquivo->arquivo,$numpreunica);
                    } elseif ($tipo == "txtbsj") {
                      $linha20 .= str_pad($numpreunica,25," ",STR_PAD_RIGHT);
                    }
                  }

                  $maxcols = 101;

                } else {

                  if ($gerar == "dados") {
                    if ($tipo == "txt") {
                      fputs($clabre_arquivo->arquivo, str_pad($k03_numpreunica,8,"0",STR_PAD_LEFT) . "000" );
                    }
                    $fc_febraban = $oConvenio->getLinhaDigitavel().",".$oConvenio->getCodigoBarra();
                  } else {
                    $maxcols     = 96;
                  }

                }

                if ( $tipo == "txtbsj") {


                  $sSqlValorIptuHistExec = "EXECUTE SqlValorIptuHist($anousu, $j23_matric)";
                  $valor_iptu_his = pg_result($sSqlValorIptuHistExec,0,0);

                  $sSqlValorTaxaHist = "EXECUTE sSqlValorTaxaHist($anousu, $j23_matric)";
                  $valor_taxa_his = pg_result($sSqlValorTaxaHist,0,0);
                }

                if ($gerar == "dados") {
                  if ($tipo == "txt") {
                    fputs($clabre_arquivo->arquivo,$fc_febraban);
                  } elseif ($tipo == "txtbsj") {
                    $linha20 .= str_pad($oConvenio->getNossoNumero(),13," ",STR_PAD_LEFT);
                    $linha20 .= "00";
                    $linha20 .= substr($vencunica,8,2) . substr($vencunica,5,2) . substr($vencunica,2,2);
                    $linha20 .= str_replace(".","",db_formatar($utotal,'p','0',16,"e"));
                    $linha20 .= str_repeat("0",11);
                    $linha20 .= str_replace(".","",db_formatar($uvlrdesconto,'p','0',12,"e"));

                    $linha20 .= ($valor_iptu_his>0? ($j01_tipoimp == "Predial"?"01":"02"):"00");

                    $linha20 .= str_replace(".","",db_formatar($valor_iptu_his,'p','0',16,"e"));

                    $linha20 .= ($valor_taxa_his>0?"10":"00");
                    $linha20 .= str_replace(".","",db_formatar($valor_taxa_his,'p','0',16,"e"));

                    $linha20 .= "18";
                    $linha20 .= str_replace(".","",db_formatar($taxa_bancaria,'p','0',16,"e"));

                    $linha20 .= str_repeat("0",148);

                    fputs($clabre_arquivo->arquivo, db_contador_bsj($linha20,"",$contador,288));
                    $iTotalLinhas20++;
                    $nTotalParcelas20 += $utotal;

                  }
                } else {
                  if ($tipo == "txt") {
                    fputs($clabre_arquivo->arquivo,db_contador("BARRASUNICA".$k00_percdes,"CODIGO DE BARRAS",$contador,$maxcols));
                  }
                }

              } else {

                if ( $oRegraEmissao->isCobranca() ) {
                  $maxcols = 101;
                } else {
                  $maxcols = 96;
                }

                if ($gerar == "layout") {

                    $k00_percdes = substr($unicas[$unica],22);
                    $k00_dtvenc  = db_formatar(substr($unicas[$unica],0,10),'d');

                    if ($tipo == "txt") {
                      fputs($clabre_arquivo->arquivo,db_contador("OPERUNICA".$k00_percdes,"OPERACAO/LANCAMENTO DA UNICA DE $k00_percdes% DE DESCONTO COM VENCIMENTO EM " . db_formatar($k00_dtvenc,'d'),$contador,10));
                      fputs($clabre_arquivo->arquivo,db_contador("VENCUNICA".$k00_percdes,"VENCIMENTO",$contador,10));
                      fputs($clabre_arquivo->arquivo,db_contador("PERCDESCUNICA".$k00_percdes,"PERCENTUAL DE DESCONTO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("VLRHISTUNICA".$k00_percdes,"VALOR HISTORICO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("VLRCORUNICA".$k00_percdes,"VALOR CORRIGIDO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("JURUNICA".$k00_percdes,"JUROS",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("MULUNICA".$k00_percdes,"MULTA",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("DESCUNICA".$k00_percdes,"DESCONTO",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("TOTALUNICA".$k00_percdes,"TOTAL (VALOR CORRIGIDO + JUROS + MULTA)",$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("TOTALLIQUNICA".$k00_percdes,"TOTAL - DESCONTO DE " . $k00_percdes,$contador,15));
                      fputs($clabre_arquivo->arquivo,db_contador("CODARREC".$k00_percdes, "NUMERO DE ARRECADACAO",$contador,$iTamCodArrecadao));
                      fputs($clabre_arquivo->arquivo,db_contador("BARRASUNICA".$k00_percdes,"CODIGO DE BARRAS",$contador,$maxcols));
                    }
                } else {
                  if ($tipo == "txt") {
                    fputs($clabre_arquivo->arquivo, str_repeat(" ",(151+$maxcols)));
                  }
                }

              }

              if ($tipo == "txtbsj" and $gerar == "dados") {
                // pode ter somente uma linha de unica

                  $linha50 = "BSJR50";

                  $imp_linha50 = "";
                  $imp_linha50 .= str_pad("MATRIC: $j23_matric - S/Q/L: " . $j34_setor . "/" . $j34_quadra . "/" . $j34_lote . " - EXERC: $anousu",55," ",STR_PAD_RIGHT);

                  $imp_linha50 .= trim(strtoupper(str_pad("IPTU", 37))) . ": " . trim(db_formatar($valor_iptu_his, 'f', ' ', 12));
                  $imp_linha50 .= " - ".trim(strtoupper(str_pad("COLETA DE LIXO", 37))) . ": " . trim(db_formatar($valor_taxa_his, 'f', ' ', 12));

                  $linha50 .= str_pad($imp_linha50,220," ",STR_PAD_RIGHT);

                  $linha50 .= str_repeat(" ",62);

                  fputs($clabre_arquivo->arquivo, db_contador_bsj($linha50,"",$contador,288));

                  $result_mesg = db_query("select k00_msguni from arretipo where k00_tipo = {$k00_tipo}");

                  $imp_linha50 = str_pad("BSJR50".pg_result($result_mesg,0,0),288," ",STR_PAD_RIGHT);

                  fputs($clabre_arquivo->arquivo, db_contador_bsj($imp_linha50,"",$contador,288));

                  break;

              }

            } // fim das unicas

            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, "# FIM DAS UNICAS",16 );
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("FIMUNICAS","EXPRESSAO # FIM DAS UNICAS",$contador,16));
              }
            }
          }

          // inicio parceladas
          if ($resultfinarrecad != false) {

            if (pg_numrows($resultfinarrecad) > 0) {

              if ($gerar == "dados") {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, str_pad(pg_numrows($resultfinarrecad),3,"0",STR_PAD_LEFT));
                  fputs($clabre_arquivo->arquivo, "PARCELADOS");
                  fputs($clabre_arquivo->arquivo, db_formatar($k02_juros, 'f', ' ', 15));
                  fputs($clabre_arquivo->arquivo, db_formatar($k140_faixa, 'f', ' ', 15));
                }
              } else {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, db_contador("TOTPARC","QUANTIDADE TOTAL DE PARCELAS",$contador,3));
                  fputs($clabre_arquivo->arquivo, db_contador("EXP_PARCELADOS","EXPRESSAO PARCELADOS",$contador,10));
                  fputs($clabre_arquivo->arquivo, db_contador("PERCMESJURATRASO","PERCENTUAL POR MES DE JUROS POR ATRASO",$contador,15));
                  fputs($clabre_arquivo->arquivo, db_contador("PERCGERMULATRASO","PERCENTUAL GERAL DE MULTA POR ATRASO",$contador,15));
                }
              }

              if ($tipo == "txt") {
                $parcelamaxima = 12;
              }

              for ($unicont = 1; $unicont <= $parcelamaxima; $unicont ++) {

                $linha21 = "BSJR20";

                $achoua      = false;
                $fc_febraban = "";

                for ($a=0; $a < pg_numrows($resultfinarrecad);$a++) {
                  if (pg_result($resultfinarrecad,$a,"k00_numpar") == $unicont ) {
                    db_fieldsmemory($resultfinarrecad, $a);
                    $achoua=true;
                    break;
                  }
                }
                if ($achoua == false) {
                  $k00_numpre = "";
                  $k00_numpar = $unicont;
                  $k00_numtot = "";
                  $k00_numdig = "";
                  $k00_dtvenc = "";
                  $k00_valor = 0;
                } else {

                  $data_calc   = date("Y-m-d",db_getsession("DB_datausu"));
                  $sql_calcula = "select fc_calcula({$k00_numpre}, {$k00_numpar}, 0, '{$data_calc}', '{$data_calc}', $anousu)";
                  $rsCalcula = db_query($sql_calcula);
                  if(@pg_numrows($rsCalcula)>0) {
                    @db_fieldsmemory($rsCalcula, 0);
                    $k00_valor = (float) substr($fc_calcula,14,13) +
                                 (float) substr($fc_calcula,27,13) +
                                 (float) substr($fc_calcula,40,13) -
                                 (float) substr($fc_calcula,53,13);

                  }

                }

                $k00_valor += $taxa_bancaria;

                if ($gerar == "dados") {
                  ////////
                  if ($tipo == "txt") {
                    if ($gerarparcelado == true && $achoua) {
                      if ($k00_dtvenc == "") {
                        fputs($clabre_arquivo->arquivo, str_repeat(" ", 10));
                      } else {
                        fputs($clabre_arquivo->arquivo, db_formatar($k00_dtvenc, 'd'));
                      }
                      fputs($clabre_arquivo->arquivo, db_formatar($k00_valor, 'f', ' ', 15));
                      fputs($clabre_arquivo->arquivo, db_formatar($k00_valor * $k02_juros / 100, 'f', ' ', 15));
                      fputs($clabre_arquivo->arquivo, db_formatar($k00_valor * $k140_faixa / 100, 'f', ' ', 15));
                    } else {
                      fputs($clabre_arquivo->arquivo, str_repeat(" ", 10));
                      fputs($clabre_arquivo->arquivo, db_formatar(0, 'f', ' ', 15));
                      fputs($clabre_arquivo->arquivo, db_formatar(0, 'f', ' ', 15));
                      fputs($clabre_arquivo->arquivo, db_formatar(0, 'f', ' ', 15));
                    }
                  }

                } else {
                  if ($tipo == "txt") {
                    fputs($clabre_arquivo->arquivo, db_contador("VENCPARC"   . str_pad($k00_numpar,3,"0", STR_PAD_LEFT) ,"VENCIMENTO DA PARCELA $k00_numpar",$contador,10));
                    fputs($clabre_arquivo->arquivo, db_contador("VALPARC"    . str_pad($k00_numpar,3,"0", STR_PAD_LEFT) ,"VALOR DA PARCELA $k00_numpar",$contador,15));
                    fputs($clabre_arquivo->arquivo, db_contador("VALJURPARC" . str_pad($k00_numpar,3,"0", STR_PAD_LEFT) ,"JUROS POR ATRASO DE 1 MES JA CALCULADOS DA PARCELA $k00_numpar",$contador,15));
                    fputs($clabre_arquivo->arquivo, db_contador("VALMULPARC" . str_pad($k00_numpar,3,"0", STR_PAD_LEFT) ,"MULTA POR ATRASO DE 1 MES JA CALCULADOS DA PARCELA $k00_numpar",$contador,15));
                  }
                }

                $numpre  = db_numpre($k00_numpre).str_pad($k00_numpar,3,"0",STR_PAD_LEFT);
                $numpref = db_numpre($k00_numpre).str_pad($k00_numpar,3,"0",STR_PAD_LEFT);

        /* PARA PADRAO COBRANCA */

                $k03_numprepar = '';
                if ($gerar == "dados" and $achoua){

                  $sSqlArretipoExec = "EXECUTE sSqlArretipo($k00_tipo)";
                  $rsSqlArretipo = db_query($sSqlArretipoExec);

                  $iNumRows      = pg_numrows($rsSqlArretipo);

                  if ( $iNumRows == 0 ) {
                    echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo!";
                    exit;
                  }

                  db_fieldsmemory($rsSqlArretipo,0);
                  $k00_descr = $k00_descr;

                  db_inicio_transacao();

                    try {
                      $oRecibo = new recibo(2, null, 5);
                      $oRecibo->addNumpre($k00_numpre,$k00_numpar);
                      $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
                      $oRecibo->setDataRecibo($k00_dtvenc);
                      $oRecibo->setDataVencimentoRecibo($k00_dtvenc);
                      $oRecibo->emiteRecibo();
                      $k03_numprepar = $oRecibo->getNumpreRecibo();
                    } catch ( Exception $eException ) {
                      db_fim_transacao(true);
                      db_redireciona("db_erros.php?fechar=true&db_erro=Erro6 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eException->getMessage()}");
                      exit;
                    }

                  db_fim_transacao();

                }

                if ($gerar == 'layout' ){
                  if ($tipo == "txt") {
                    fputs($clabre_arquivo->arquivo, db_contador("NUMPREPARC" . str_pad($k00_numpar,3,"0", STR_PAD_LEFT),"CODIGO DE ARRECADACAO DA PARCELA $k00_numpar",$contador,$iTamCodArrecadao));
                  }
                }

                $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
                $dtvenc = str_replace("-", "", $k00_dtvenc);

                if ($barrasparc == "seis") {
                  $terceiro = "6";
                } else {
                  $terceiro = "7";
                }

                $datavencimento = $k00_dtvenc;
                if ($datavencimento == "") {
                  $datavencimento = "0000-00-00";
                }

                if ($gerar == "dados"  and $achoua) {

                  try{
                    $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numprepar,0,$k00_valor,$vlrbar,$datavencimento,$terceiro);
                  } catch (Exception $eExeption){
                    db_redireciona("db_erros.php?fechar=true&db_erro=Erro6 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eExeption->getMessage()}");
                    exit;
                  }

                  /**
                   * Quando o convenio for um convenio de cobrança registrada válido, adiciona o recibo gerado na fila para geração do arquivo de cobrança registrada
                   */
                  if ($lConvenioCobrancaValido) {
                    CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
                  }

                  if ( $oRegraEmissao->getCadTipoConvenio() == 5 || $oRegraEmissao->getCadTipoConvenio() == 6) {
                    $aNossoNumero = explode("-",$oConvenio->getNossoNumero());
                    $sNossoNumero    = $aNossoNumero[0];
                    $sDigNossoNumero = $aNossoNumero[1];
                  } else {
                    $sNossoNumero    = $oConvenio->getNossoNumero();
                    $sDigNossoNumero = '';
                  }

                  $oNossoNumero = new stdClass();
                  $oNossoNumero->sNumero = $sNossoNumero;
                  $oNossoNumero->sDigito = $sDigNossoNumero;

                  $aListaNossoNumero[$k00_numpar] = $oNossoNumero;

                  $codigobarras   = $oConvenio->getCodigoBarra();
                  $linhadigitavel = $oConvenio->getLinhaDigitavel();

                }

                if ($tipo == "txtbsj") {

                  $sSqlValorIptuHistReceitaExec = "EXECUTE sSqlValorIptuHistReceita($k00_numpre, $k00_numpar)";
                  $valor_iptu_his = pg_result($sSqlValorIptuHistReceitaExec,0,0);


                  $sSqlValorTaxaHistReceitaExec = "EXECUTE sSqlValorTaxaHistReceita($k00_numpre, $k00_numpar)";
                  $valor_taxa_his = pg_result($sSqlValorTaxaHistReceitaExec,0,0);
                }

                if( $oRegraEmissao->isCobranca() ) {

                  if ( $k03_numprepar == '' && $k00_numpar == '' && $k00_dtvenc == '' && $k00_valor == '' ){
                    $fc_febraban = str_repeat('0',101);
                  }

                  if ($gerar == "dados") {

                    if ($gerarparcelado == true && $achoua) {

                      $fc_febraban = $oConvenio->getLinhaDigitavel().",".$oConvenio->getCodigoBarra();

                      $numprepar = db_numpre($k03_numprepar).str_pad(0,3,"0",STR_PAD_LEFT);
                      if ($tipo == "txt") {
                        fputs($clabre_arquivo->arquivo, $numprepar);
                      } elseif ($tipo == "txtbsj") {
                        $linha21 .= str_pad($numprepar,25," ",STR_PAD_RIGHT);
                      }
                    } else {

                      if ($tipo == "txt") {
                        fputs($clabre_arquivo->arquivo, str_repeat(" ", $iTamCodArrecadao));
                      } elseif ($tipo == "txtbsj") {
                        $linha21 .= str_repeat("0",25);
                      }

                      $fc_febraban = '';
                    }

                    if ($tipo == "txtbsj" && $achoua) {

                      $linha21 .= str_pad($oConvenio->getNossoNumero(),13," ",STR_PAD_LEFT);
                      $linha21 .= str_pad($k00_numpar,2,"0", STR_PAD_LEFT);
                      $linha21 .= substr($k00_dtvenc,8,2) . substr($k00_dtvenc,5,2) . substr($k00_dtvenc,2,2);
                      $linha21 .= str_replace(".","",db_formatar($k00_valor,'p','0',16,"e"));
                      $linha21 .= str_repeat("0",11);
                      $linha21 .= str_repeat("0",11);

                      $linha21 .= ($valor_iptu_his>0? ($j01_tipoimp == "Predial"?"01":"02"):"00");

                      $linha21 .= str_replace(".","",db_formatar($valor_iptu_his,'p','0',16,"e"));

                      $linha21 .= ($valor_taxa_his>0?"10":"00");
                      $linha21 .= str_replace(".","",db_formatar($valor_taxa_his,'p','0',16,"e"));

                      $linha21 .= "18";
                      $linha21 .= str_replace(".","",db_formatar($taxa_bancaria,'p','0',16,"e"));

                      $linha21 .= str_repeat("0",148);

                      fputs($clabre_arquivo->arquivo, db_contador_bsj($linha21,"",$contador,288));
                      $iTotalLinhas20++;
                      $nTotalParcelas20 += $k00_valor;

                    }

                  }

                  $maxcols = 101;

                } else {

                  $numpre = db_numpre($k03_numprepar).str_pad(null,3,"0",STR_PAD_LEFT);

                  if ( $gerar == "dados" ) {

                    if ($tipo == "txt") {
                      if ($gerarparcelado == true && $achoua) {
                        fputs($clabre_arquivo->arquivo, $numpre);
                      }else{
                        fputs($clabre_arquivo->arquivo, str_pad(" ",$iTamCodArrecadao," ",STR_PAD_LEFT));
                      }
                    }

                    if ($achoua) {

                      try {
                        $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numprepar,0,$k00_valor,$vlrbar,$datavencimento,$terceiro);
                      } catch (Exception $eExeption){
                        db_redireciona("db_erros.php?fechar=true&db_erro=Erro7 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eExeption->getMessage()}");
                        exit;
                      }

                      $fc_febraban = $oConvenio->getLinhaDigitavel().",".$oConvenio->getCodigoBarra();
                    }

                  }

                  $maxcols = 96;
                }

                if ($gerar == "dados") {
                  if ($tipo == "txt") {
                    if ($gerarparcelado == true && $achoua ) {
                      fputs($clabre_arquivo->arquivo,$fc_febraban);
                      fputs($clabre_arquivo->arquivo, str_pad($k00_numpar,3,"0", STR_PAD_LEFT));
                    } else {
                      fputs($clabre_arquivo->arquivo, str_repeat(" ", $maxcols));
                      fputs($clabre_arquivo->arquivo, str_repeat(" ",3));
                    }
                  }
                } else {
                  if ($tipo == "txt") {
                    fputs($clabre_arquivo->arquivo, db_contador("BARRASPARC" . str_pad($k00_numpar,3,"0", STR_PAD_LEFT),"CODIGO DE BARRAS DA PARCELA $k00_numpar",$contador,$maxcols));
                    fputs($clabre_arquivo->arquivo, db_contador("PARC" . str_pad($k00_numpar,3,"0",STR_PAD_LEFT),"PARCELA " . str_pad($k00_numpar,2),$contador,3));
                  }
                }

                // imprime linha 50 para parcela

            if ($tipo == "txtbsj" and $gerar == "dados") {

                  $linha50 = "BSJR50";

                  $imp_linha50 = "";
                  $imp_linha50 .= str_pad("MATRIC: $j23_matric - S/Q/L: " . $j34_setor . "/" . $j34_quadra . "/" . $j34_lote . " - EXERC: $anousu",55," ",STR_PAD_RIGHT);

                  $imp_linha50 .= trim(strtoupper(str_pad("IPTU", 37))) . ": " . trim(db_formatar($valor_iptu_his, 'f', ' ', 12));
                  $imp_linha50 .= " - ".trim(strtoupper(str_pad("COLETA DE LIXO", 37))) . ": " . trim(db_formatar($valor_taxa_his, 'f', ' ', 12));

                  $linha50 .= str_pad($imp_linha50,220," ",STR_PAD_RIGHT);

                  $linha50 .= str_repeat(" ",62);

                  fputs($clabre_arquivo->arquivo, db_contador_bsj($linha50,"",$contador,288));

                  $result_mesg = db_query("select k00_msgparc from arretipo where k00_tipo = {$k00_tipo}");

                  $imp_linha50 = str_pad("BSJR50".pg_result($result_mesg,0,0),288," ",STR_PAD_RIGHT);

                  fputs($clabre_arquivo->arquivo, db_contador_bsj($imp_linha50,"",$contador,288));

              }

              } //final

            }

          }

          for ($parcpaga = 1; $parcpaga <= $iParcelaMaxima; $parcpaga++) {

            $sSqlPagasExec = "EXECUTE sSqlPagas($j20_numpre, $parcpaga)";
            $resultpagas = db_query($sSqlPagasExec) or die($sSqlPagasExec);

            if ($gerar == "dados") {
              if (pg_numrows($resultpagas) == 0) {
                $dtpago     = "          ";
                $k00_valor  = 0;
              } else {
                db_fieldsmemory($resultpagas, 0);
                if (strlen($dtpago) == 0) {
                  $dtpago   = "          ";
                  $valorpago  = 0;
                } else {
                  $dtpago = db_formatar($dtpago,'d');
                }
              }

              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo,$dtpago);
                fputs($clabre_arquivo->arquivo,db_formatar($valorpago + 0,'f', ' ', 15));
              }

            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("DTPGTOPARC" . str_pad($parcpaga, 3, "0", STR_PAD_LEFT),"DATA DO PAGAMENTO DA PARCELA $parcpaga",$contador,10));
                fputs($clabre_arquivo->arquivo, db_contador("VALORPGTOPARC" . str_pad($parcpaga, 3, "0", STR_PAD_LEFT),"VALOR DO PAGAMENTO DA PARCELA $parcpaga",$contador,15));
              }
            }

          }


          $sSqlParcPagasExec = "EXECUTE sSqlParcPagas($j20_numpre)";
          $resultpagas = db_query($sSqlParcPagasExec) or die($sSqlParcPagasExec);


          if (pg_numrows($resultpagas) == 0) {
            $valorpago = 0;
          } else {
            db_fieldsmemory($resultpagas, 0);
          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, str_pad(db_formatar($valorpago, 'f', ' ', 18), 18, ' ', STR_PAD_LEFT));
            }
          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("TOTALPAGO","TOTAL PAGO DESTE REGISTRO",$contador,18));
            }
          }

          $sSqlFin02Exec = "EXECUTE sSqlFin02($j20_numpre)";
          $resultfin2 = db_query($sSqlFin02Exec) or die($sSqlFin02Exec);

          if ($gerar == "dados") {

            $colunaTotal = " j21_quant, sum ( coalesce(j21_valor,0) - abs( coalesce(j21_valorisen,0 ) ) ) as j21_valor, ";
            $groupby     = " group by j17_codhis, j21_quant, j17_descr ";
            $whereMatric = " and j21_matric = $j23_matric ";


          } else {

            $colunaTotal = " sum( ( coalesce(j21_valor,0) - abs( coalesce(j21_valorisen,0 ) ) ) )  as j21_valor ";
            $colunaTotal = "";
            $groupby     = " group by j17_codhis, ";
            $groupby    .= "          j17_descr  ";
            $whereMatric = "";

          }


          $sqlcalc  = "  select j17_codhis,                                                                      ";
          $sqlcalc .= "         $colunaTotal ";
          $sqlcalc .= "         j17_descr                                                                        ";
          $sqlcalc .= "    from (select k02_codigo,                                                              ";
          $sqlcalc .= "                 k02_descr,                                                               ";
          $sqlcalc .= "                 j17_codhis,                                                              ";
          $sqlcalc .= "                 j17_descr,                                                               ";
          $sqlcalc .= "                 sum(j21_valor) as j21_valor,                                             ";
          $sqlcalc .= "                 sum(coalesce(j21_quant,0)) as j21_quant,                                 ";
          $sqlcalc .= "                 sum(case                                                                     ";
          $sqlcalc .= "                   when iptucalhconf.j89_codhis is not null then                          ";
          $sqlcalc .= "                     (select sum(x.j21_valor)                                             ";
          $sqlcalc .= "                       from iptucalv x                                                    ";
          $sqlcalc .= "                      where x.j21_anousu = iptucalv.j21_anousu                            ";
          $sqlcalc .= "                        and x.j21_matric = iptucalv.j21_matric                            ";
          $sqlcalc .= "                        and x.j21_receit = iptucalv.j21_receit                            ";
          $sqlcalc .= "                        and x.j21_codhis = iptucalhconf.j89_codhis)                       ";
          $sqlcalc .= "                   else 0                                                                 ";
          $sqlcalc .= "                 end) as j21_valorisen                                                     ";
          $sqlcalc .= "            from iptucalv                                                                 ";
          $sqlcalc .= "                 inner join iptucalh        on iptucalh.j17_codhis        = j21_codhis    ";
          $sqlcalc .= "                 left  join iptucalhconf    on iptucalhconf.j89_codhispai = j21_codhis    ";
          $sqlcalc .= "                 inner join tabrec          on tabrec.k02_codigo          = j21_receit    ";
          $sqlcalc .= "                 left  join iptucadtaxaexe  on iptucadtaxaexe.j08_tabrec  = j21_receit    ";
          $sqlcalc .= "                                           and iptucadtaxaexe.j08_anousu  = $anousu       ";
          $sqlcalc .= "           where j21_anousu = $anousu                                                     ";
          $sqlcalc .= "                 $whereMatric                                                             ";
          $sqlcalc .= "             and j17_codhis not in (select j89_codhis from iptucalhconf)                  ";
          $sqlcalc .= "           group by k02_codigo, ";
          $sqlcalc .= "                    k02_descr,  ";
          $sqlcalc .= "                    j17_codhis, ";
          $sqlcalc .= "                    j17_descr  ";
          //            $sqlcalc .= "                    j21_valor,  ";
          //            $sqlcalc .= "                    j21_valorisen   ";
          $sqlcalc .= "           order by iptucalh.j17_codhis ";
          $sqlcalc .= "        ) as x ";
          $sqlcalc .=     $groupby ;
          $sqlcalc .= " order by j17_codhis ";

          $w_iptucalv = "w_iptucalv_$anousu";
          $w_iptucalv2 = "w_iptucalv2_$anousu";

          if ($gerar == "layout") {
            $cria_tab_sqlcalc = "create temp table $w_iptucalv as $sqlcalc";
            //              echo $cria_tab_sqlcalc . ";<br>";
            $resultcriacalc = db_query($cria_tab_sqlcalc) or die($cria_tab_sqlcalc);
          } else {
            $cria_tab_sqlcalc = "create temp table $w_iptucalv2 as $sqlcalc";
            //              echo $cria_tab_sqlcalc . ";<br>";
            $resultcriacalc = db_query($cria_tab_sqlcalc) or die($cria_tab_sqlcalc);

            $sqlcalc = "select $w_iptucalv.j17_codhis, $w_iptucalv.j17_descr, coalesce( (select j21_valor from $w_iptucalv2 where $w_iptucalv2.j17_codhis = $w_iptucalv.j17_codhis ), 0) as j21_valor, coalesce ( (select j21_quant from $w_iptucalv2 where $w_iptucalv2.j17_codhis = $w_iptucalv.j17_codhis ),0) as j21_quant from $w_iptucalv order by $w_iptucalv.j17_codhis";
            //              echo $sqlcalc . ";<br>";
            //              exit;
          }
          $resultcalc = db_query($sqlcalc) or die("erro: " . $sqlcalc);
          if (pg_numrows($resultcalc) > 0) {

            $aArrayTaxa = array();

              for ($vlr = 0; $vlr < pg_numrows($resultcalc); $vlr ++) {
              db_fieldsmemory($resultcalc, $vlr);
              if ($gerar == "dados") {

                if ($tipo == "txt") {
                  if ($j21_valor == 0 ) {
                    fputs($arqTXTISENTOS,"{$j23_matric} - {$j17_descr}{$sQuebraLinha}");

                    fputs($clabre_arquivo->arquivo, str_pad(null, 40));
                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar(0, 'f', ' ', 10),"0",STR_PAD_LEFT));
                    fputs($clabre_arquivo->arquivo, str_pad("", 18, ' ', STR_PAD_LEFT));
                    fputs($clabre_arquivo->arquivo, str_pad("", 18, ' ', STR_PAD_LEFT));

                  } else {

                    // alterado por robson em 2008-02-12

                    fputs($clabre_arquivo->arquivo, str_pad($j17_descr, 40));
                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j21_quant, 'f', ' ', 10),"0",STR_PAD_LEFT));
                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j21_valor, 'f', ' ', 18), 18, ' ', STR_PAD_LEFT));
                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j21_valor / pg_numrows($resultfin2), 'f', ' ', 18), 18, ' ', STR_PAD_LEFT));

                  }
                }
              } else {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, db_contador("DESCRTAXA" . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"DESCRICAO DA TAXA $j17_descr",$contador,40));
                  fputs($clabre_arquivo->arquivo, db_contador("QUANTTAXA" . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"QUANTIDADE DA TAXA $j17_descr",$contador,10));
                  fputs($clabre_arquivo->arquivo, db_contador("VALTAXA"   . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"VALOR DA TAXA $j17_descr",$contador,18));
                  fputs($clabre_arquivo->arquivo, db_contador("VALTAXAPARC"   . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"VALOR DA TAXA $j17_descr PARA CADA PARCELA",$contador,18));
                }
              }

            }

            for ($taxa=$vlr; $taxa < 10; $taxa++) {
              if ($gerar == "dados") {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, str_pad(' ', 86));
                }
              } else {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, db_contador("BRANCOS","TAXA SEM USO $taxa",$contador,86));
                }
              }
            }

          } else {
            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, str_repeat(" ", 63));
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("BRANCOS","ESPACOS EM BRANCO",$contador,63));
              }
            }
          }

          if ($gerar == "dados") {
            $cria_tab_sqlcalc = "drop table $w_iptucalv2";
            $resultcriacalc = db_query($cria_tab_sqlcalc) or die($cria_tab_sqlcalc);
          }

          $sSqlTestadaExec = "EXECUTE sSqlTestada($j23_matric)";
          $resulttestada = db_query($sSqlTestadaExec) or die($sSqlTestadaExec);

          if (pg_numrows($resulttestada) > 0) {
            db_fieldsmemory($resulttestada, 0);
          } else {
            $j36_testad = 0;
          }

          $sSqlIptuAntExec = "EXECUTE sSqlIptuAnt($j23_matric)";
          $resultiptuant = db_query($sSqlIptuAntExec) or die($sSqlIptuAntExec);

          if (pg_numrows($resultiptuant) > 0) {
            db_fieldsmemory($resultiptuant, 0);
          } else {
            $j40_refant = "";
          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, str_pad($j36_testad, 20));
              fputs($clabre_arquivo->arquivo, str_pad($j34_area, 20));
              fputs($clabre_arquivo->arquivo, str_pad($j39_area, 20));
              fputs($clabre_arquivo->arquivo, str_pad($j40_refant, 20));
              fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j23_arealo, 'f', ' ', 18), 18, ' ', STR_PAD_LEFT));
              fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j23_m2terr, 'f', ' ', 18), 18, ' ', STR_PAD_LEFT));
            }
          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("TESTADALOTE","TESTADA PRINCIPAL DO LOTE",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("AREALOTE","AREA DO LOTE",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("AREATOTCONSTR", "AREA TOTAL CONSTRUIDA",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("REFERENCIAANTERIOR", "REFERENCIA ANTERIOR",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("AREADOLOTE", "AREA DO LOTE CONSIDERADA NO CALCULO",$contador,18));
              fputs($clabre_arquivo->arquivo, db_contador("VALORM2CALCULO", "VALOR DO METRO QUADRADO DO TERRENO DO CALCULO",$contador,18));
            }
          }

          if ($filtroprinc == "sempgto") {
            // recibo com parcelas agrupadas a pagar

            $sSqlTipoExec = "EXECUTE sSqlTipo($anousu)";
            $resulttipo = db_query($sSqlTipoExec) or die($sSqlTipoExec);

            if (pg_numrows($resulttipo) == 0) {
              $tipodeb = 1;
            } else {
              db_fieldsmemory($resulttipo,0);
            }

            $sSqlArretipoExec = "EXECUTE sSqlArretipo($tipodeb)";
            $result = db_query($sSqlArretipoExec);

            if(pg_numrows($result)==0){
              echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo!";
              exit;
            }
            db_fieldsmemory($result,0);

            $k00_descr = $k00_descr;

            $sSqlFinVencimentoExec = "EXECUTE sSqlFinVencimento($j20_numpre)";
            $resultfinvcto = db_query($sSqlFinVencimentoExec);

            if (pg_numrows($resultfinvcto) == 0) {
              db_msgbox("Problema ao definir vcto do recibo! Contate suporte!");
              exit;
            }
            db_fieldsmemory($resultfinvcto,0);

            $sSqlFinReciboExec = "EXECUTE sSqlFinRecibo($j20_numpre)";
            $resultfinrecibo = db_query($sSqlFinReciboExec) or die($sSqlFinReciboExec);

            $iNumRows        = pg_numrows($resultfinrecibo);

            try {
              $oRecibo = new recibo(2, null, 5);
            } catch ( Exception $eException ) {
              db_redireciona("db_erros.php?fechar=true&db_erro=Erro8 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eException->getMessage()}");
              exit;
            }

            for ($recibo=0; $recibo < $iNumRows; $recibo++) {

              db_fieldsmemory($resultfinrecibo,$recibo);

              try {
                $oRecibo->addNumpre($k00_numpre,$k00_numpar);
              } catch ( Exception $eException ) {
                db_redireciona("db_erros.php?fechar=true&db_erro=Erro9 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eException->getMessage()}");
                exit;
              }

            }

            db_inicio_transacao();

            try {
              $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
              $oRecibo->setDataRecibo($vctorecibo);
              $oRecibo->setDataVencimentoRecibo($vctorecibo);
              $oRecibo->emiteRecibo();
              $k03_numpre = $oRecibo->getNumpreRecibo();
            } catch ( Exception $eException ) {
              db_fim_transacao(true);
              db_redireciona("db_erros.php?fechar=true&db_erro=Erro10 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eException->getMessage()}");
              exit;
            }

            db_fim_transacao();


            $sSqlReciboExec = "EXECUTE sSqlRecibo($k03_numpre)";
            $recibo = db_query($sSqlReciboExec);

            db_fieldsmemory($recibo,0,true);

            $numpre  = db_numpre($k03_numpre).'000';
            $numpref = db_numpre($k03_numpre).'.000';

            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo,db_formatar($vctorecibo,'d'));
                fputs($clabre_arquivo->arquivo,db_formatar($valorrecibo,'f',' ',15));
                fputs($clabre_arquivo->arquivo,$numpref);
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("VENCIMENTORECIBO","VENCIMENTO DO RECIBO GERADO DAS PARCELAS VENCIDAS COM CORRECAO, JURO E MULTA",$contador,10));
                fputs($clabre_arquivo->arquivo, db_contador("VALORRECIBO","VALOR DO RECIBO",$contador,15));
                fputs($clabre_arquivo->arquivo, db_contador("CODIGOARRECADACAORECIBO","CODIGO DE ARRECADACAO DO RECIBO",$contador,12));
              }
            }

            $vlrbar = db_formatar(str_replace('.','',str_pad(number_format($valorrecibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
            $datavencimento = $vctorecibo;

          if ($gerar == "dados") {

            try{
              $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpre,$k00_numpar,$k00_valor,$vlrbar,$k00_dtvenc,6);
            } catch (Exception $eExeption){
              db_redireciona("db_erros.php?fechar=true&db_erro=Erro11 - Matricula: $j23_matric - Numpre:$j20_numpre - {$eExeption->getMessage()}");
              exit;
            }

            /**
             * Quando o convenio for um convenio de cobrança registrada válido, adiciona o recibo gerado na fila para geração do arquivo de cobrança registrada
             */
            if ($lConvenioCobrancaValido) {
              CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
            }

            if ( $oRegraEmissao->getCadTipoConvenio() == 5 || $oRegraEmissao->getCadTipoConvenio() == 6) {

              $aNossoNumero = explode("-",$oConvenio->getNossoNumero());
              $sNossoNumero    = $aNossoNumero[0];
              $sDigNossoNumero = $aNossoNumero[1];

            } else {

              $sNossoNumero    = $oConvenio->getNossoNumero();
              $sDigNossoNumero = '';

            }

            $oNossoNumero = new stdClass();
            $oNossoNumero->sNumero = str_replace("/","",$sNossoNumero);
            $oNossoNumero->sDigito = $sDigNossoNumero;

            $aListaNossoNumero[$k00_numpar] = $oNossoNumero;
            $codigobarras   = $oConvenio->getCodigoBarra();
            $linhadigitavel = $oConvenio->getLinhaDigitavel();

          }

            if( $oRegraEmissao->isCobranca() ){

              if ( $k00_numpre == '' && $k00_numpar == '' && $k00_dtvenc == '' && $k00_valor == '' ){
                $fc_febraban = str_repeat('0',101);
              }

              if( $gerar=="dados" ){
                $fc_febraban = $linhadigitavel.",".$codigobarras;
              }

              $maxcols = 101;

            }else{

              $fc_febraban = $oConvenio->getLinhaDigitavel().",".$oConvenio->getCodigoBarra();
              $maxcols     = strlen($fc_febraban);

            }



            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo,$fc_febraban);
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("BARRASRECIBO","CODIGO DE BARRAS DO RECIBO",$contador,$maxcols));
              }
            }

            // fim dos agrupamentos de parcelas

          }

          if ($gerar == "layout") {

            $sqlarrecadrecgeral = "
              select * from (
                  select
                  distinct
                  case when arrecad.k00_receit in ($sListaReceitas) then $iRecMin else arrecad.k00_receit end as k00_receit
                  from iptunump
                  inner join arrematric on j20_numpre = arrematric.k00_numpre
                  inner join arrecad on arrematric.k00_numpre = arrecad.k00_numpre
                  where j20_anousu = $anousu) as x
              order by k00_receit ";
            $resultfinarrecadrecgeral = db_query($sqlarrecadrecgeral) or die($sqlarrecadrecgeral);

            if (pg_numrows($resultfinarrecadrecgeral) > 0) {
              for ($unicont = 1; $unicont <= $iParcelaMaxima; $unicont ++) {
                for ($rec=0; $rec < pg_numrows($resultfinarrecadrecgeral); $rec++) {
                  db_fieldsmemory($resultfinarrecadrecgeral, $rec);
                   if ($tipo == "txt") {
                     fputs($clabre_arquivo->arquivo, db_contador("PARC"       . str_pad($unicont,3,"0", STR_PAD_LEFT) . str_pad($k00_receit,3,"0", STR_PAD_LEFT),"PARCELA $unicont - RECEITA $k00_receit",$contador,3));
                     fputs($clabre_arquivo->arquivo, db_contador("REC"        . str_pad($unicont,3,"0", STR_PAD_LEFT) . str_pad($k00_receit,3,"0", STR_PAD_LEFT),"RECEITA $k00_receit - PARCELA $unicont",$contador,3));
                     fputs($clabre_arquivo->arquivo, db_contador("VALPARCREC" . str_pad($unicont,3,"0", STR_PAD_LEFT) . str_pad($k00_receit,3,"0", STR_PAD_LEFT) ,"VALOR DA PARCELA $unicont - RECEITA $k00_receit",$contador,15));
                   }
                }
              }
            }
          } else {

            // inicio parcelas com receitas
            for ($unicont = 1; $unicont <= $iParcelaMaxima; $unicont ++) {

              for ($rec=0; $rec < pg_numrows($resultfinarrecadrecgeral); $rec++) {
                db_fieldsmemory($resultfinarrecadrecgeral, $rec);

                $achoua=false;

                $sqlarrecadrec = "
                  select
                  sum(k00_valor) as k00_valor
                  from iptunump
                  inner join arrematric on iptunump.j20_numpre = arrematric.k00_numpre
                  inner join arrecad on arrematric.k00_numpre = arrecad.k00_numpre
                  where iptunump.j20_anousu = $anousu and iptunump.j20_matric = $j23_matric and k00_numpar = $unicont and
                  case when $k00_receit in ($sListaReceitas) then k00_receit in ($sListaReceitas) else k00_receit = $k00_receit end";
                $resultfinarrecadrec = db_query($sqlarrecadrec) or die($sqlarrecadrec);

                if (pg_numrows($resultfinarrecadrec) == 0) {
                  $k00_valor  = 0;
                } else {
                  db_fieldsmemory($resultfinarrecadrec, 0);
                }

                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, substr(str_pad($unicont,3,"0", STR_PAD_LEFT),0,3));
                  fputs($clabre_arquivo->arquivo, substr(str_pad($k00_receit,3,"0", STR_PAD_LEFT),0,3));
                }

                $sqlimposto = "select $k00_receit in ($sListaReceitas) as imposto";
                $resultimposto = db_query($sqlimposto) or die($sqlimposto);
                db_fieldsmemory($resultimposto , 0);

                if ($imposto == 't') {

                  $sqlisencao = "select * from iptuisen
                    inner join isenexe on  isenexe.j47_codigo = iptuisen.j46_codigo
                    where j46_matric = $j23_matric and
                    j47_anousu = $anousu and j46_perc > 0";

                } else {

                  $sqlisencao = "select * from iptuisen
                    inner join isenexe on  isenexe.j47_codigo = iptuisen.j46_codigo
                    inner join isentaxa on isentaxa.j56_codigo = iptuisen.j46_codigo
                    where j46_matric = $j23_matric and
                    j47_anousu = $anousu and
                    case when $k00_receit in ($sListaReceitas) then j56_receit in ($sListaReceitas) else j56_receit = $k00_receit end";

                }
                $resultisencao = db_query($sqlisencao) or die($sqlisencao);

                if ($tipo == "txt") {
                  if (pg_numrows($resultisencao) == 0 or $k00_valor > 0) {
                    fputs($clabre_arquivo->arquivo, db_formatar($k00_valor, 'f', ' ', 15));
                  } else {
                    fputs($clabre_arquivo->arquivo, str_pad("", 15, ' ', STR_PAD_LEFT));
                  }
                }

              }

            }

          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo,str_pad($j37_outros,40,' ',STR_PAD_LEFT));
              fputs($clabre_arquivo->arquivo, str_pad($z01_cgmpri,10));
              fputs($clabre_arquivo->arquivo, substr(str_pad($j23_areafr,10),0,10));

              //fputs($clabre_arquivo->arquivo, str_pad($cep, 8));
              /*
               if ($j01_tipoimp == 'Predial') {

               $cep = $j14_codigo;
               }
               */

              fputs($clabre_arquivo->arquivo, str_pad(getCepLogradouroPredial($j14_codigo), 8));

              fputs($clabre_arquivo->arquivo, str_pad(strtoupper($munic), 40, ' ',STR_PAD_RIGHT));
              fputs($clabre_arquivo->arquivo, str_pad($uf, 2));
              fputs($clabre_arquivo->arquivo, str_pad($mensagemdebitosanosanteriores, 100, ' ',STR_PAD_RIGHT));
              fputs($clabre_arquivo->arquivo, substr(str_pad($z01_bairro, 40),0,40));
              fputs($clabre_arquivo->arquivo, substr(str_pad($j46_codigo, 10, "0", STR_PAD_LEFT),0,10));
              fputs($clabre_arquivo->arquivo, substr(str_pad($j46_tipo, 5, "0", STR_PAD_LEFT),0,5));
            }
          } else {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("FACEOUTROS","OUTRAS INFORMACOES DA FACE", $contador, 40));
              fputs($clabre_arquivo->arquivo, db_contador("NUMCGMNOME","CODIGO DO CGM DO NOME A SER IMPRESSO NO CARNE",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("FRACAODOLOTE","FRACAO DO LOTE UTILIZADA NO CALCULO",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("CEPDOIMOVEL","CEP DO IMOVEL",$contador,8));
              fputs($clabre_arquivo->arquivo, db_contador("MUNICDOIMOVEL","MUNICIPIO DO IMOVEL",$contador,40));
              fputs($clabre_arquivo->arquivo, db_contador("UFDOIMOVEL","UF DO IMOVEL",$contador,2));
              fputs($clabre_arquivo->arquivo, db_contador("MSGDEBANOSANT","MENSAGEM CASO A MATRICULA TENHA DEBITOS EM ANOS ANTERIORES",$contador,100));
              fputs($clabre_arquivo->arquivo, db_contador("BAIRRONOME","BAIRRO DO CGM DO PROPRIETARIO",$contador,40));
              fputs($clabre_arquivo->arquivo, db_contador("CODISEN","CODIGO DA ISENCAO",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("TIPOISEN","CODIGO DO TIPO DE ISENCAO",$contador,5));
            }
          }


          if ($gerar == "dados") {

            $colunaTotal = " j21_quant, sum ( coalesce(j21_valor,0) ) as j21_valor, sum ( abs( coalesce(j21_valorisen,0 ) ) ) as j21_valorisen, ";
            $groupby     = " group by j17_codhis, j21_quant, j17_descr ";
            $whereMatric = " and j21_matric = $j23_matric ";


          } else {

            $colunaTotal = " sum( ( coalesce(j21_valor,0) ) )  as j21_valor, sum( ( abs( coalesce(j21_valorisen,0 ) ) ) ) as j21_valorisen, ";
            $colunaTotal = "";
            $groupby     = " group by j17_codhis, ";
            $groupby    .= "          j17_descr  ";
            $whereMatric = "";

          }


          $sqlcalc  = "  select j17_codhis,                                                                      ";
          $sqlcalc .= "         $colunaTotal ";
          $sqlcalc .= "         j17_descr                                                                        ";
          $sqlcalc .= "    from (select k02_codigo,                                                              ";
          $sqlcalc .= "                 k02_descr,                                                               ";
          $sqlcalc .= "                 j17_codhis,                                                              ";
          $sqlcalc .= "                 j17_descr,                                                               ";
          $sqlcalc .= "                 sum(j21_valor) as j21_valor,                                             ";
          $sqlcalc .= "                 sum(coalesce(j21_quant,0)) as j21_quant,                                 ";
          $sqlcalc .= "                 sum(case                                                                     ";
          $sqlcalc .= "                   when iptucalhconf.j89_codhis is not null then                          ";
          $sqlcalc .= "                     (select sum(x.j21_valor)                                             ";
          $sqlcalc .= "                       from iptucalv x                                                    ";
          $sqlcalc .= "                      where x.j21_anousu = iptucalv.j21_anousu                            ";
          $sqlcalc .= "                        and x.j21_matric = iptucalv.j21_matric                            ";
          $sqlcalc .= "                        and x.j21_receit = iptucalv.j21_receit                            ";
          $sqlcalc .= "                        and x.j21_codhis = iptucalhconf.j89_codhis)                       ";
          $sqlcalc .= "                   else 0                                                                 ";
          $sqlcalc .= "                 end) as j21_valorisen                                                     ";
          $sqlcalc .= "            from iptucalv                                                                 ";
          $sqlcalc .= "                 inner join iptucalh        on iptucalh.j17_codhis        = j21_codhis    ";
          $sqlcalc .= "                 left  join iptucalhconf    on iptucalhconf.j89_codhispai = j21_codhis    ";
          $sqlcalc .= "                 inner join tabrec          on tabrec.k02_codigo          = j21_receit    ";
          $sqlcalc .= "                 left  join iptucadtaxaexe  on iptucadtaxaexe.j08_tabrec  = j21_receit    ";
          $sqlcalc .= "                                           and iptucadtaxaexe.j08_anousu  = $anousu       ";
          $sqlcalc .= "           where j21_anousu = $anousu                                                     ";
          $sqlcalc .= "                 $whereMatric                                                             ";
          $sqlcalc .= "           group by k02_codigo, ";
          $sqlcalc .= "                    k02_descr,  ";
          $sqlcalc .= "                    j17_codhis, ";
          $sqlcalc .= "                    j17_descr  ";
          $sqlcalc .= "           order by iptucalh.j17_codhis ";
          $sqlcalc .= "        ) as x ";
          $sqlcalc .=     $groupby ;
          $sqlcalc .= " order by j17_codhis ";

          $w_iptucalv = "ww_iptucalv_$anousu";
          $w_iptucalv2 = "ww_iptucalv2_$anousu";

          if ($gerar == "layout") {
            $cria_tab_sqlcalc = "create temp table $w_iptucalv as $sqlcalc";
            $resultcriacalc = db_query($cria_tab_sqlcalc) or die($cria_tab_sqlcalc);
          } else {
            $cria_tab_sqlcalc = "create temp table $w_iptucalv2 as $sqlcalc";
            $resultcriacalc = db_query($cria_tab_sqlcalc) or die($cria_tab_sqlcalc);

            $sqlcalc = "select $w_iptucalv.j17_codhis, $w_iptucalv.j17_descr, coalesce( (select j21_valor from $w_iptucalv2 where $w_iptucalv2.j17_codhis = $w_iptucalv.j17_codhis ), 0) as j21_valor, coalesce( (select j21_valorisen from $w_iptucalv2 where $w_iptucalv2.j17_codhis = $w_iptucalv.j17_codhis ), 0) as j21_valorisen, coalesce ( (select j21_quant from $w_iptucalv2 where $w_iptucalv2.j17_codhis = $w_iptucalv.j17_codhis ),0) as j21_quant from $w_iptucalv order by $w_iptucalv.j17_codhis";
          }

          $resultcalc = db_query($sqlcalc) or die("erro: " . $sqlcalc);
          if (pg_numrows($resultcalc) > 0) {

            for ($vlr = 0; $vlr < pg_numrows($resultcalc); $vlr ++) {
              db_fieldsmemory($resultcalc, $vlr);

              if ($gerar == "dados") {

                if ($tipo == "txt") {

                  if ($j21_valor == 0 ) {

                    fputs($clabre_arquivo->arquivo, str_pad(null, 40));
                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar(0, 'f', ' ', 10),"0",STR_PAD_LEFT));

                    fputs($clabre_arquivo->arquivo, str_pad("", 18, ' ', STR_PAD_LEFT));
                    fputs($clabre_arquivo->arquivo, str_pad("", 18, ' ', STR_PAD_LEFT));

                  } else {

                    fputs($clabre_arquivo->arquivo, str_pad($j17_descr, 40));
                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j21_quant, 'f', ' ', 10),"0",STR_PAD_LEFT));

                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j21_valor, 'f', ' ', 18), 18, ' ', STR_PAD_LEFT));
                    fputs($clabre_arquivo->arquivo, str_pad(db_formatar($j21_valor / pg_numrows($resultfin2), 'f', ' ', 18), 18, ' ', STR_PAD_LEFT));

                    if ( $j17_codhis == 11 || $j17_codhis == 12 ) {
                      $nTotalBomPagador += $j21_valor;
                    }

                  }

                }

              } else {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, db_contador("DESCRTAXA" . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"DESCRICAO DA TAXA $j17_descr",$contador,40));
                  fputs($clabre_arquivo->arquivo, db_contador("QUANTTAXA" . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"QUANTIDADE DA TAXA $j17_descr",$contador,10));
                  fputs($clabre_arquivo->arquivo, db_contador("VALTAXA"   . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"VALOR DA TAXA $j17_descr",$contador,18));
                  fputs($clabre_arquivo->arquivo, db_contador("VALTAXAPARC"   . str_pad($j17_codhis, 3, "0", STR_PAD_LEFT),"VALOR DA TAXA $j17_descr PARA CADA PARCELA",$contador,18));
                }
              }

            }

            for ($taxa=$vlr; $taxa < 10; $taxa++) {
              if ($gerar == "dados") {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, str_pad(' ', 86));
                }
              } else {
                if ($tipo == "txt") {
                  fputs($clabre_arquivo->arquivo, db_contador("BRANCOS","TAXA SEM USO $taxa",$contador,86));
                }
              }
            }

          } else {
            if ($gerar == "dados") {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, str_repeat(" ", 63));
              }
            } else {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("BRANCOS","ESPACOS EM BRANCO",$contador,63));
              }
            }
          }

          // Convênio SICOB
          if ( $oRegraEmissao->getCadTipoConvenio() == 5 ) {

            $iTamCarteira    = 2;
            $iTamCedente     = 8;
            $iTamConvenio    = 4;
            $iTamNossoNumero = 10;

            if ( $gerar == "dados" ) {

              $aNossoNumero    = explode("-",$oConvenio->getNossoNumero());
              $aCarteira       = explode("-",$oConvenio->getCarteira());
              $iConvenio       = ($oConvenio->getConvenioCobranca()==0?' ':$oConvenio->getConvenioCobranca());

              $sAgencia        = str_pad($oConvenio->getCodAgencia()              ,5,"0",STR_PAD_LEFT);
              $sDigAgencia     = str_pad($oConvenio->getDigitoAgencia()           ,1," ",STR_PAD_LEFT);
              $sOperacao       = str_pad($oConvenio->getOperacao()                ,3,"0",STR_PAD_LEFT);
              $sCedente        = str_pad($oConvenio->getCedente()      ,$iTamCedente,"0",STR_PAD_LEFT);
              $sDigCedente     = str_pad($oConvenio->getDigitoCedente()           ,1," ",STR_PAD_LEFT);
              $sCarteira       = str_pad($aCarteira[0]                ,$iTamCarteira," ",STR_PAD_LEFT);
              $sConvenio       = str_pad($iConvenio                   ,$iTamConvenio," ",STR_PAD_LEFT);

            }

          // Convênio BSJ,BDL
          } else if ( in_array($oRegraEmissao->getCadTipoConvenio(),array(1,2)) ) {

            $iTamCarteira = 6;

            $sSqlCedente  = "select ar13_cedente                                        ";
            $sSqlCedente .= "  from conveniocobranca                                    ";
            $sSqlCedente .= " where ar13_cadconvenio = {$oRegraEmissao->getConvenio()} ";

            $rsCendete    = db_query($sSqlCedente);

            if ( pg_num_rows($rsCendete) > 0 ) {
              $iTamCedente = strlen(pg_result($rsCendete, 0, 'ar13_cedente'));
            } else {
              $iTamCedente = 7;
            }

            if ( $oRegraEmissao->getCadTipoConvenio() == 1 ) {
              $iTamConvenio    = 7;
              $iTamNossoNumero = 10;
            } else {
              $iTamConvenio    = 4;
              $iTamNossoNumero = 13;
            }

            if ( $gerar == "dados" ) {

              $iConvenio       = ($oConvenio->getConvenioCobranca()==0?' ':$oConvenio->getConvenioCobranca());
              $aCarteira       = explode("-",$oConvenio->getCarteira());
              $aAgencia        = explode("-",$oConvenio->getCodAgencia());

              $sAgencia        = str_pad($aAgencia[0]                                 ,5,"0",STR_PAD_LEFT);
              $sDigAgencia     = str_pad($aAgencia[1]                                 ,1," ",STR_PAD_LEFT);
              $sOperacao       = str_pad($oConvenio->getOperacao()                    ,3,"0",STR_PAD_LEFT);
              $sCedente        = str_pad($oConvenio->getCedente()          ,$iTamCedente," ",STR_PAD_LEFT);
              $sDigCedente     = str_pad($oConvenio->getDigitoCedente()               ,1," ",STR_PAD_LEFT);
              $sCarteira       = str_pad($aCarteira[0]                    ,$iTamCarteira," ",STR_PAD_LEFT);
              $sConvenio       = str_pad($iConvenio                       ,$iTamConvenio," ",STR_PAD_LEFT);
            }

          // Demais Convênios ARRECADAÇÃO, CAIXA PADRÃO etc.
          } else {

            $iTamCarteira    = 6;
            $iTamCedente     = 6;
            $iTamConvenio    = 4;
            $iTamNossoNumero = 10;
              $iTamNossoNumeroVersao2  = 17;

            if ( $gerar == "dados" ) {

              $aAgencia        = explode("-",$oConvenio->getCodAgencia());

              $sAgencia        = str_pad($aAgencia[0],5     ,"0",STR_PAD_LEFT);
              $sDigAgencia     = str_pad(@$aAgencia[1],1    ," ",STR_PAD_LEFT);
              $sOperacao       = str_pad("",3               ," ",STR_PAD_LEFT);
              $sCedente        = str_pad("",$iTamCedente    ," ",STR_PAD_LEFT);
              $sDigCedente     = str_pad("",1               ," ",STR_PAD_LEFT);
              $sCarteira       = str_pad("",$iTamCarteira   ," ",STR_PAD_LEFT);
              $sConvenio       = str_pad($oConvenio->getConvenioArrecadacao(),$iTamConvenio," ",STR_PAD_LEFT);
            }

          }


          if ( $gerar == "dados" ) {

            if ($tipo == "txt") {

              /**
               * Nosso numero para unica
               */
              for ( $iUnica=1; $iUnica <= $iQuantidadeUnicas; $iUnica++ ) {
                if ( isset($aListaNossoNumeroUnica[$iUnica])) {
                  $oNossoNumero = $aListaNossoNumeroUnica[$iUnica];
                  fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sNumero,$iTamNossoNumero," ",STR_PAD_LEFT),$iTamNossoNumero);
                  fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sDigito,1               ," ",STR_PAD_LEFT),1);
                } else {
                  fputs($clabre_arquivo->arquivo, str_pad("",$iTamNossoNumero," ",STR_PAD_LEFT),$iTamNossoNumero);
                  fputs($clabre_arquivo->arquivo, str_pad("",1               ," ",STR_PAD_LEFT),1);
                }
              }

              for ( $iParcela=1; $iParcela <= $iParcelaMaxima; $iParcela++ ) {
                if ( isset($aListaNossoNumero[$iParcela])) {
                  $oNossoNumero = $aListaNossoNumero[$iParcela];
                  fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sNumero,$iTamNossoNumero," ",STR_PAD_LEFT),$iTamNossoNumero);
                  fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sDigito,1               ," ",STR_PAD_LEFT),1);
                } else {
                  fputs($clabre_arquivo->arquivo, str_pad("",$iTamNossoNumero," ",STR_PAD_LEFT),$iTamNossoNumero);
                  fputs($clabre_arquivo->arquivo, str_pad("",1               ," ",STR_PAD_LEFT),1);
                }
              }
            }

            //unset($aListaNossoNumero);

            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, str_pad(db_formatar($nTotalBomPagador,'f',' ',18), 18,' ', STR_PAD_LEFT));
              fputs($clabre_arquivo->arquivo, $sAgencia       ,5);
              fputs($clabre_arquivo->arquivo, $sDigAgencia    ,1);
              fputs($clabre_arquivo->arquivo, $sOperacao      ,3);
              fputs($clabre_arquivo->arquivo, $sCedente       ,$iTamCedente);
              fputs($clabre_arquivo->arquivo, $sDigCedente    ,1);
              fputs($clabre_arquivo->arquivo, $sCarteira      ,$iTamCarteira);
              fputs($clabre_arquivo->arquivo, $sConvenio      ,$iTamConvenio);
              fputs($clabre_arquivo->arquivo, date('d/m/Y',db_getsession('DB_datausu')),10);
              fputs($clabre_arquivo->arquivo, str_pad($oRegraEmissao->getNomeConvenio(),50," ",STR_PAD_RIGHT),50);
            }

            $nTotalBomPagador = 0;

            if ($tipo == "txt") {

                for ( $iUnica=1; $iUnica <= $iQuantidadeUnicas; $iUnica++ ) {
                  if ( isset($aListaNossoNumeroUnica[$iUnica])) {
                    $oNossoNumero = $aListaNossoNumeroUnica[$iUnica];
                    fputs($clabre_arquivo->arquivo, str_pad(str_replace("/", "", $oNossoNumero->sNumero),$iTamNossoNumeroVersao2," ",STR_PAD_LEFT),$iTamNossoNumeroVersao2);
                    fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sDigito,1               ," ",STR_PAD_LEFT),1);
                  } else {
                    fputs($clabre_arquivo->arquivo, str_pad("",$iTamNossoNumeroVersao2," ",STR_PAD_LEFT),$iTamNossoNumeroVersao2);
                    fputs($clabre_arquivo->arquivo, str_pad("",1               ," ",STR_PAD_LEFT),1);
                  }
                }
                for ( $iParcela=1; $iParcela <= $iParcelaMaxima; $iParcela++ ) {
                  if ( isset($aListaNossoNumero[$iParcela])) {
                    $oNossoNumero = $aListaNossoNumero[$iParcela];
                    fputs($clabre_arquivo->arquivo, str_pad(str_replace("/", "", $oNossoNumero->sNumero),$iTamNossoNumeroVersao2," ",STR_PAD_LEFT),$iTamNossoNumeroVersao2);
                    fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sDigito,1               ," ",STR_PAD_LEFT),1);
                  } else {
                    fputs($clabre_arquivo->arquivo, str_pad("",$iTamNossoNumeroVersao2," ",STR_PAD_LEFT),$iTamNossoNumeroVersao2);
                    fputs($clabre_arquivo->arquivo, str_pad("",1               ," ",STR_PAD_LEFT),1);
                  }
                }
            }

            unset($aListaNossoNumero);
            unset($aListaNossoNumero, $aListaNossoNumeroUnica);

          } else {

            for ( $iUnica=1; $iUnica <= $iQuantidadeUnicas; $iUnica++ ) {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("NOSSO_NUMERO_UNICA{$iUnica}"   ,"NOSSO NUMERO UNICA {$iUnica}"          ,$contador,$iTamNossoNumero));
                fputs($clabre_arquivo->arquivo, db_contador("DG_NOSSO_NUMERO_UNICA{$iUnica}","DIGITO DO NOSSO NUMERO UNICA {$iUnica}",$contador,1));
              }
            }

            for ( $iParcela=1; $iParcela <= $iParcelaMaxima; $iParcela++ ) {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("NOSSO_NUMERO_PARC{$iParcela}"   ,"NOSSO NUMERO PARCELA {$iParcela}"          ,$contador,$iTamNossoNumero));
                fputs($clabre_arquivo->arquivo, db_contador("DG_NOSSO_NUMERO_PARC{$iParcela}","DIGITO DO NOSSO NUMERO PARCELA {$iParcela}",$contador,1));
              }
            }

            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("VALTOTALBOMPAGADOR" ,"VALOR TOTAL DO BOM PAGADOR",$contador,18));
              fputs($clabre_arquivo->arquivo, db_contador("AGENCIA"            ,"AGENCIA DO CONVENIO"       ,$contador,5));
              fputs($clabre_arquivo->arquivo, db_contador("DG_AGENCIA"         ,"DIGITO DA AGENCIA"         ,$contador,1));
              fputs($clabre_arquivo->arquivo, db_contador("OPERACAO"           ,"OPERACAO DO CONVENIO"      ,$contador,3));
              fputs($clabre_arquivo->arquivo, db_contador("CEDENTE"            ,"CEDENTE DO CONVENIO"       ,$contador,$iTamCedente));
              fputs($clabre_arquivo->arquivo, db_contador("DG_CEDENTE"         ,"DIGITO DO CEDENTE"         ,$contador,1));
              fputs($clabre_arquivo->arquivo, db_contador("CARTEIRA"           ,"CARTEIRA DO CONVENIO"      ,$contador,$iTamCarteira));
              fputs($clabre_arquivo->arquivo, db_contador("CONVENIO"           ,"CONVENIO"                  ,$contador,$iTamConvenio));
              fputs($clabre_arquivo->arquivo, db_contador("DATA_PROCESSAMENTO" ,"DATA DO PROCESSAMENTO"     ,$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("DESCRICAO_CONVENIO" ,"DESCRICAO DO CONVENIO"     ,$contador,50));
            }

            for ( $iUnica=1; $iUnica <= $iQuantidadeUnicas; $iUnica++ ) {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("NOSSO_NUMERO_VERSAO2_UNICA{$iUnica}"   ,"NOSSO NUMERO VERSAO 2 UNICA {$iUnica}"          ,$contador,$iTamNossoNumeroVersao2));
                fputs($clabre_arquivo->arquivo, db_contador("DG_NOSSO_NUMERO_VERSAO2_UNICA{$iUnica}","DIGITO DO NOSSO NUMERO VERSAO 2 UNICA {$iUnica}",$contador,1));
              }
            }

            for ( $iParcela=1; $iParcela <= $iParcelaMaxima; $iParcela++ ) {
              if ($tipo == "txt") {
                fputs($clabre_arquivo->arquivo, db_contador("NOSSO_NUMERO_VERSAO2_PARC{$iParcela}"   ,"NOSSO NUMERO VERSAO 2 PARCELA {$iParcela}"          ,$contador,$iTamNossoNumeroVersao2));
                fputs($clabre_arquivo->arquivo, db_contador("DG_NOSSO_NUMERO_VERSAO2_PARC{$iParcela}","DIGITO DO NOSSO NUMERO VERSAO 2 PARCELA {$iParcela}",$contador,1));
              }
            }

          }

          if ($gerar == "dados") {

            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, str_pad($j05_codigoproprio,      10));
              fputs($clabre_arquivo->arquivo, str_pad($j06_setorloc,           10));
              fputs($clabre_arquivo->arquivo, str_pad(substr($j05_descr,0,40), 40));
              fputs($clabre_arquivo->arquivo, str_pad($j06_quadraloc,          10));
              fputs($clabre_arquivo->arquivo, str_pad($j06_lote,               10));
            }

          } else {

            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, db_contador("SEQUENCIALSETORLOCALIZACAO", "SEQUENCIAL DO SETOR DE LOCALIZACAO",     $contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("CODPROPRIOSETORLOCALIZACAO", "CODIGO PROPRIO DO SETOR DE LOCALIZACAO", $contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("DESCRSETORLOCALIZACAO",      "DESCRICAO DO SETOR DE LOCALIZACAO",      $contador,40));
              fputs($clabre_arquivo->arquivo, db_contador("QUADRALOCALIZACAO",          "QUADRA DE LOCALIZACAO",                  $contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("LOTELOCALIZACAO",            "LOTE DE LOCALIZACAO",                    $contador,10));
            }

          }

########################################################################################################################
// Melhoria na emissao para gerar parcelas e unicas opcionais de vencimentos, aplicando correção
include(modification("cad4_geracarneiptutxtParcelasOpcionais.php"));


########################################################################################################################

          if ($gerar == "dados") {
            $cria_tab_sqlcalc = "drop table $w_iptucalv2";
            $resultcriacalc = db_query($cria_tab_sqlcalc) or die($cria_tab_sqlcalc);
          }

          if ($gerar == "dados") {
            if ($tipo == "txt") {
              fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");
            }
          }

        }

      }

      if ($gerar == "layout" and $quantos >= 1) {
        if ($tipo == "txt" or $tipo == "txtbsj") {
          fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}{$sQuebraLinha}{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "OPCOES ESCOLHIDAS NA GERACAO: {$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "BASE DE DADOS UTILIZADA: " . @$GLOBALS["DB_NBASE"] . "{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "ORDEM: " . ($ordem == "endereco"?"Endereco de entrega":($ordem == "alfabetica"?"Alfabética":"Zona de entrega")) . "{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "ESPÉCIE: $especie{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "QUANTIDADE DE REGISTROS A PROCESSAR: $quantescolhida{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "IMPRIMIR APENAS REGISTROS COM ENDERECO DE ENTREGA VALIDOS: " . (!empty($entregavalido)?"SIM":"NAO"));

          $sqlnaogeracgm = "select j68_numcgm, z01_nome from iptunaogeracarnecgm inner join cgm on z01_numcgm = j68_numcgm order by j68_numcgm";
          $resultnaogeracgm = db_query($sqlnaogeracgm) or die($sqlnaogeracgm);

          if (pg_numrows($resultnaogeracgm) > 0) {
            fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}{$sQuebraLinha}{$sQuebraLinha}");
            fputs($clabre_arquivo->arquivo, "LISTA DE CGM NAO GERADOS: {$sQuebraLinha}");
            fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");
          }

          for ($naogeracgm = 0; $naogeracgm < pg_numrows($resultnaogeracgm); $naogeracgm++) {
            db_fieldsmemory($resultnaogeracgm, $naogeracgm);

            fputs($clabre_arquivo->arquivo, str_pad($j68_numcgm, 6, "0", STR_PAD_LEFT) . " - $z01_nome{$sQuebraLinha}");

          }
          fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "TOTAL DE CGM A NAO GERAR: " . pg_numrows($resultnaogeracgm) . "{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");

          if ($filtroprinc == "normal") {
            $filtroprincipal = "NORMAL";
          } elseif ($filtroprinc == "compgto") {
            $filtroprincipal = "SOMENTE SEM PARCELAS EM ATRASO";
          } else {
            $filtroprincipal = "SOMENTE OS REGISTROS SEM PAGAMENTOS";
          }


          fputs($clabre_arquivo->arquivo, "FILTRO PRINCIPAL: $filtroprincipal{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");

          if ($imobiliaria == "todos") {
            fputs($clabre_arquivo->arquivo, strtoupper("IMPRIMIR TODOS OS REGISTROS, INDEPENDENTE DO VINCULO COM IMOBILIARIA{$sQuebraLinha}"));
          } elseif ($imobiliaria == "com") {
            fputs($clabre_arquivo->arquivo, strtoupper("SOMENTE OS QUE TENHAM VINCULO COM IMOBILIARIA{$sQuebraLinha}"));
          } elseif ($imobiliaria == "sem") {
            fputs($clabre_arquivo->arquivo, strtoupper("SOMENTE OS QUE NAO TENHAM VINCULO COM IMOOBILIARIA{$sQuebraLinha}"));
          }

          if ($loteamento == "todos") {
            fputs($clabre_arquivo->arquivo, strtoupper("IMPRIMIR TODOS OS REGISTROS, INDEPENDENTE DO VINCULO COM LOTEAMENTO{$sQuebraLinha}"));
          } elseif ($loteamento == "com") {
            fputs($clabre_arquivo->arquivo, strtoupper("SOMENTE OS QUE TENHAM VINCULO COM LOTEAMENTO{$sQuebraLinha}"));
          } elseif ($loteamento == "sem") {
            fputs($clabre_arquivo->arquivo, strtoupper("SOMENTE OS QUE NAO TENHAM VINCULO COM LOTEAMENTO{$sQuebraLinha}"));
          }

          if ($barrasunica == "seis") {
            fputs($clabre_arquivo->arquivo, "TERCEIRO DIGITO CODIGO DE BARRAS UNICA: 6{$sQuebraLinha}");
          } else {
            fputs($clabre_arquivo->arquivo, "TERCEIRO DIGITO CODIGO DE BARRAS UNICA: 7{$sQuebraLinha}");
          }

          if ($barrasparc == "seis") {
            fputs($clabre_arquivo->arquivo, "TERCEIRO DIGITO CODIGO DE BARRAS PARCELADO: 6{$sQuebraLinha}");
            fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");
          } else {
            fputs($clabre_arquivo->arquivo, "TERCEIRO DIGITO CODIGO DE BARRAS PARCELADO: 7{$sQuebraLinha}");
            fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");
          }

          fputs($clabre_arquivo->arquivo, 'COLOCAR EXPRESSAO "ISENTO" QUANDO TAXAS/IMPOSTO ZERADO: ' . (isset($zerado)?"SIM":"NAO") . "{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, 'PROCESSAR MASSA FALIDA: ' . (isset($proc)?"SIM":"NAO") ."{$sQuebraLinha}");

          if (isset($parcobrig)) {
            fputs($clabre_arquivo->arquivo, 'PARCELA OBRIGATORIA EM ABERTO: ' . $parcobrig . "{$sQuebraLinha}");
          } else {
            fputs($clabre_arquivo->arquivo, "SEM DEFINICAO DE PARCELA OBRIGATORIA EM ABERTO{$sQuebraLinha}");
          }

          fputs($clabre_arquivo->arquivo, 'VALOR MINIMO : '.db_formatar($vlrmin,'f')."{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, 'VALOR MAXIMO : '.db_formatar($vlrmax,'f')."{$sQuebraLinha}");

          fputs($clabre_arquivo->arquivo, 'VALOR MINIMO PARA PARCELADO: '.db_formatar($vlrminunica,'f')."{$sQuebraLinha}");
          fputs($clabre_arquivo->arquivo, 'VALOR MAXIMO PARA PARCELADO: '.db_formatar($vlrmaxunica,'f')."{$sQuebraLinha}");

          if($intervalo == "desconsiderar") {
            fputs($clabre_arquivo->arquivo, "DESCONSIDERAR INTERVALO{$sQuebraLinha}");
          } elseif ($intervalo == "gerar") {
            fputs($clabre_arquivo->arquivo, "GERAR PARA OS QUE ESTIVEREM NO INTERVALO{$sQuebraLinha}");
          } elseif ($intervalo == "naogerar") {
            fputs($clabre_arquivo->arquivo, "NAO GERAR PARA OS QUE ESTIVEREM NO INTERVALO{$sQuebraLinha}");
          }

          fputs($clabre_arquivo->arquivo, "{$sQuebraLinha}");
        }
        break;
      }

      if (isset($randomico) and $gerar == "dados" and $quantidade != "" and 1==2) {
        if ($quantos >= $quantidade) {
          break;
        }
        $i = 0;
      }

    } // final do for principal

    if ($gerar == "dados") {

      if ($tipo == "txtbsj") {
        $linha90 = "";
        $linha90 .= "BSJR90";
        $linha90 .= str_pad($iTotalLinhas20,7,"0",STR_PAD_LEFT);
        $linha90 .= str_pad(str_replace(".","",   db_formatar($nTotalParcelas20,'p','0',15) ),15,"0",STR_PAD_LEFT);
        $linha90 .= str_pad(0,7,"0",STR_PAD_LEFT);
        $linha90 .= str_pad(0,15,"0",STR_PAD_LEFT);
        $linha90 .= str_repeat(" ",238);

        fputs($clabre_arquivo->arquivo, db_contador_bsj($linha90,"",$contador,288));
      }

    }

    fclose($clabre_arquivo->arquivo);
    $erro = true;
    $descricao_erro = "Carnes gerados com sucesso no diretorio /tmp do servidor.";

  } else {
    $erro = true;
    $descricao_erro = "Erro ao Criar arquivo: $arquivo";
  }

} // final do primeiro for

/**
 * Caso existam inconsistencias, deve ser gerado o relatório
 */
if ( !empty($aInconsistencias) ) {

  $sArquivoRelatorio = gerarRelatorioInconsistencia($aInconsistencias);
  $arqnomes         .= $sArquivoRelatorio."# Download do Arquivo - ".$sArquivoRelatorio."|";
}

fclose($arqTXTISENTOS);
echo "<script>";
echo "  listagem = '$arqnomes';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";

function db_contador($apelido, $expressao, $contador, $valor) {

  global $contador, $contadorgeral;
  $sQuebraLinha = "\r\n";
  $contadorant  = $contador + 1;
  $contador+=$valor;
  return str_pad($contadorgeral++,5) . " | " . str_pad($apelido,30) . " | " . str_pad($expressao,80) . " | " . str_pad($valor,4,"0",STR_PAD_LEFT) . " | " . str_pad($contadorant,4,"0",STR_PAD_LEFT) . " | " . str_pad($contador,4,"0",STR_PAD_LEFT) . "{$sQuebraLinha}";
}

function db_contador_bsj($apelido, $expressao, $contador, $valor) {

  global $contador, $contadorgeral;
  $sQuebraLinha = "\r\n";
  $contadorant  = $contador + 1;
  $contador+=$valor;
  return str_pad($apelido,30) . "{$sQuebraLinha}";
}

/**
 * Funão que gera o relatório de inconsistências da Emissão geral de IPTU
 * @return string  path do relatório gerado
 */
function gerarRelatorioInconsistencia($aInconsistencias)
{
  $oRelatorio = new \PDFTable();

  $oRelatorio->setPercentWidth(true);
  $oRelatorio->setLineHeigth(5);

  $aCabecalho = array(
    "CGM",
    "Nome / Razão Social",
    "Inconsistência"
  );

  $aLargura     = array( 10, 50, 40 );
  $aAlinhamento = array(
    \PDFDocument::ALIGN_CENTER,
    \PDFDocument::ALIGN_LEFT,
    \PDFDocument::ALIGN_LEFT
  );

  $oRelatorio->setHeaders     ( $aCabecalho );
  $oRelatorio->setColumnsWidth( $aLargura );
  $oRelatorio->setColumnsAlign( $aAlinhamento );

  $aRazaoSocial = array();

  foreach ($aInconsistencias as $iIndice => $aInconsistencia) {
    $aRazaoSocial[$iIndice] = $aInconsistencia[1];
  }

  array_multisort($aRazaoSocial, SORT_ASC, SORT_NATURAL, $aInconsistencias);

  foreach ($aInconsistencias as $iIndice => $aInconsistencia) {
    $oRelatorio->addLineInformation($aInconsistencia);
  }

  $oPdfDocument = new \PDFDocument();
  $oPdfDocument->addHeaderDescription("Relatório de Inconsistências da Exportação de Cobrança Registrada.");

  $oPdfDocument->SetFillColor(235);
  $oPdfDocument->setFontSize(8);
  $oPdfDocument->open();

  $oRelatorio->printOut($oPdfDocument, false);

  /**
   * Criamos o arquivo com o relatório
   */
  $sNomeArquivo      = "cobranca_registrada_relatorio_inconsistencia_" . time();
  $sArquivoRelatorio = $oPdfDocument->savePDF($sNomeArquivo);

  return $sArquivoRelatorio;
}

/*
 * função para buscar o cep na ruascep, pelo codigo do logradouro
 */
function getCepLogradouroPredial( $iLogradouro ){

	$oDaoRuasCep = db_utils::getDao("ruascep");
	$sSql        = $oDaoRuasCep->sql_query_file($iLogradouro, null, "j29_cep", null, "");
	$rsCep       = $oDaoRuasCep->sql_record($sSql);
	if ( $oDaoRuasCep->numrows > 0 ) {
		return db_utils::fieldsMemory($rsCep, 0)->j29_cep;
	}
	return null;
}

?>
