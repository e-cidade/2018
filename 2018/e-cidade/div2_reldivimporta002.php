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


require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_utils.php"));
//include(modification("libs/db_stdlib.php"));
require_once(modification("classes/db_db_modulos_classe.php"));
require_once(modification("libs/JSON.php"));

ini_set('memory_limit', -1);

$oJson       = new services_json(0, true);
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

// *** dataini => 2009-12-01
// *** datafim => 2009-12-17
// *** z01_numcgm => 81218
// *** j01_matric => 15915
// *** q02_inscr => 51971
// *** tipoper => 0-Lançamento / 1-Inscrição
// *** tiporel => 0-Sintético / 1-Analítico
// *** tipoimp => 0-Todos 1-Parcial / 2-Geral / 3-Inclusão Manual
//
// Quando sintético terá as seguintes opções :
// *** ordenar => 0-Código de Importação / 1-Usuário / 2-Tipo / 3-Data Inicial
//
// Quando análitico terá as seguintes opções :
// *** agrupar => 0-Nome / 1-Origem / 2-Origem/Exercício / 3- Código de Importação / 4-Somente no Final

$cl_db_modulos = new cl_db_modulos;

$dtDataUsu     = date('Y-m-d',db_getsession('DB_datausu'));
$iAnoHoje      = date('Y',db_getsession('DB_datausu'));
$iDbInstit     = db_getsession('DB_instit');
$sLetra        = 'arial';
$sWhere        = "";
$sAnd          = "";
$sOrder        = "";

if (isset($oParametros->tiporel)) {
  if ($oParametros->tiporel == 0) {
    $sTipoRel = "Sintético";
    $lTipoRel = 0;

    if (isset($oParametros->ordenar)) {
      if ($oParametros->ordenar == 0) {
         $sOrder = " order by v02_divimporta ";
      } else if ($oParametros->ordenar == 1) {
         $sOrder = " order by v02_usuario ";
      } else if ($oParametros->ordenar == 2) {
         $sOrder = " order by v02_tipo ";
      } else if ($oParametros->ordenar == 3) {
         $sOrder = " order by v02_data ";
      }
    }
  } else if ($oParametros->tiporel == 1) {
    $sTipoRel = "Análitico";
    $lTipoRel = 1;

    if (isset($oParametros->agrupar)) {
      if ($oParametros->agrupar == 0) {
         $sOrder = " order by usuario ";
      } else if ($oParametros->agrupar == 1) {
         $sOrder = " order by origem ";
      } else if ($oParametros->agrupar == 2) {
         $sOrder = " order by origem,v01_exerc ";
      } else if ($oParametros->agrupar == 3) {
         $sOrder = " order by v02_divimporta ";
      }
    }
  }
}

if (isset($iDbInstit)) {
  if ($iDbInstit != "") {
    $sWhere .= " {$sAnd} divida.v01_instit = {$iDbInstit} ";
    $sAnd    = " and ";
  }
}

if (isset($oParametros->z01_numcgm)) {
  if ($oParametros->z01_numcgm != "") {
    $sWhere .= " {$sAnd} arrenumcgm.k00_numcgm = {$oParametros->z01_numcgm} ";
    $sAnd    = " and ";
  }
}

if (isset($oParametros->j01_matric)) {
  if ($oParametros->j01_matric != "") {
    $sWhere .= " {$sAnd} arrematric.k00_matric = {$oParametros->j01_matric} ";
    $sAnd    = " and ";
  }
}

if (isset($oParametros->q02_inscr)) {
  if ($oParametros->q02_inscr != "") {
    $sWhere .= " {$sAnd} arreinscr.k00_inscr = {$oParametros->q02_inscr} ";
    $sAnd    = " and ";
  }
}

if (isset($oParametros->dataini) && isset($oParametros->datafim)) {
  if ($oParametros->dataini != "--" && $oParametros->datafim != "--") {
    if ($oParametros->dataini != "" && $oParametros->datafim != "") {
      if (isset($oParametros->tipoper)) {
        if ($oParametros->tipoper == 0) {
          $sTipoPer = "Lançamento";
          $sWhere .= " {$sAnd} v01_dtinclusao between '{$oParametros->dataini}' and '{$oParametros->datafim}' ";
          $sAnd    = " and ";
        } else if ($oParametros->tipoper == 1) {
          $sTipoPer = "Inscrição";
          $sWhere .= " {$sAnd} v01_dtinsc between '{$oParametros->dataini}' and '{$oParametros->datafim}' ";
          $sAnd    = " and ";
        }
      }
    }
  }
}

if (isset($oParametros->tipoimp)) {
  if ($oParametros->tipoimp == 1) {
    $sTipoImp = "Parcial";
    $sWhere  .= " {$sAnd} divimporta.v02_tipo = 1 ";
  } else if ($oParametros->tipoimp == 2) {
    $sTipoImp = "Geral";
    $sWhere  .= " {$sAnd} divimporta.v02_tipo = 2 ";
  } else if ($oParametros->tipoimp == 3) {
    $sTipoImp = "Inclusão Manual";
    $sWhere  .= " {$sAnd} divimporta.v02_tipo is null ";
  } else {
    $sTipoImp = "Todos";
  }

}

$sSql  = " select distinct *,                                                                                         ";
$sSql .= "        round( corrigido * juros_base, 2) as juros,                                                         ";
$sSql .= "        round( corrigido * multa_base, 2) as multa,                                                         ";
$sSql .= "       ( corrigido + round( corrigido * juros_base, 2) + round( corrigido * multa_base, 2)) as total,       ";
$sSql .= "       (fim - inicio)  as tempo from (                                                                      ";
$sSql .= " select v01_coddiv,                                                                                         ";
$sSql .= "        v01_numpre,                                                                                         ";
$sSql .= "        v01_numpar,                                                                                         ";
$sSql .= "        k00_receit,                                                                                         ";
$sSql .= "        v01_proced,                                                                                         ";
$sSql .= "        cadtipo.k03_tipo,                                                                                   ";
$sSql .= "        cadtipo.k03_descr as descrtipo,                                                                     ";
$sSql .= "        tabrec.k02_drecei as descrreceit,                                                                   ";
$sSql .= "        proced.v03_dcomp  as descrproced,                                                                   ";
$sSql .= "        v07_descricao     as descrtipoproced,                                                               ";
$sSql .= "        v01_numcgm,                                                                                         ";
$sSql .= "        v02_usuario,                                                                                        ";
$sSql .= "        v02_data,                                                                                           ";
$sSql .= "        v02_hora,                                                                                           ";
$sSql .= "        v02_datafim,                                                                                        ";
$sSql .= "        v02_horafim,                                                                                        ";
$sSql .= "        v02_tipo,                                                                                           ";
$sSql .= "        v02_instit,                                                                                         ";
$sSql .= "        v02_divimporta,                                                                                     ";
$sSql .= "        v03_tributaria,                                                                                     ";
$sSql .= "        v07_descricao,                                                                                      ";
$sSql .= "        v01_dtvenc,                                                                                         ";
$sSql .= "        v01_exerc,                                                                                          ";
$sSql .= "        v01_vlrhis,                                                                                         ";
$sSql .= "        db_usuarios.nome as usuario,                                                                        ";
$sSql .= "        case                                                                                                ";
$sSql .= "            when arrematric.k00_matric is not null then 'M-'||k00_matric                                    ";
$sSql .= "            when arreinscr.k00_inscr   is not null then 'I-'||k00_inscr                                     ";
$sSql .= "          else 'C-'||arrenumcgm.k00_numcgm                                                                  ";
$sSql .= "        end as origem,                                                                                      ";
$sSql .= "        case                                                                                                ";
$sSql .= "            when arrematric.k00_matric is not null then                                                     ";
$sSql .= "                ( select rvNome from fc_busca_envolvidos(true,1,'M',arrematric.k00_matric) limit 1)         ";
$sSql .= "            when arreinscr.k00_inscr is not null then                                                       ";
$sSql .= "                ( select rvNome from fc_busca_envolvidos(true,1,'I',arreinscr.k00_inscr) limit 1 )          ";
$sSql .= "          else ( select rvNome from fc_busca_envolvidos(true,1,'C',arrenumcgm.k00_numcgm) limit 1 )         ";
$sSql .= "        end as nomecontribuinte,                                                                            ";
$sSql .= "        fc_corre( arrecad.k00_receit,                                                                       ";
$sSql .= "                  arrecad.k00_dtoper,                                                                       ";
$sSql .= "                  arrecad.k00_valor,                                                                        ";
$sSql .= "                  v01_dtinclusao,                                                                           ";
$sSql .= "                  cast(extract(year from v01_dtinclusao) as integer),                                       ";
$sSql .= "                  v01_dtinclusao ) as corrigido,                                                            ";
$sSql .= "        coalesce( fc_juros( arrecad.k00_receit,                                                             ";
$sSql .= "                            arrecad.k00_dtvenc,                                                             ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            false,                                                                          ";
$sSql .= "                            cast( extract(                                                                  ";
$sSql .= "                            year from v01_dtinclusao) as integer)),0) as juros_base,                        ";
$sSql .= "        coalesce( fc_multa( arrecad.k00_receit,                                                             ";
$sSql .= "                            arrecad.k00_dtvenc,                                                             ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            arrecad.k00_dtoper,                                                             ";
$sSql .= "                            cast( extract(                                                                  ";
$sSql .= "                            year from v01_dtinclusao) as integer)),0) as multa_base,                        ";
$sSql .= "        ( to_timestamp(                                                                                     ";
$sSql .= "           (divimporta.v02_data || ' ' ||divimporta.v02_hora)::text, 'YYYY-MM-DD HH24:MI') ) as inicio,     ";
$sSql .= "        ( to_timestamp(                                                                                     ";
$sSql .= "           (divimporta.v02_datafim || ' ' ||divimporta.v02_horafim)::text, 'YYYY-MM-DD HH24:MI') ) as fim   ";
$sSql .= "   from divida                                                                                              ";
$sSql .= "        inner join cgm           on cgm.z01_numcgm             = divida.v01_numcgm                          ";
$sSql .= "        inner join proced        on proced.v03_codigo          = divida.v01_proced                          ";
$sSql .= "        inner join tipoproced    on tipoproced.v07_sequencial  = proced.v03_tributaria                      ";
$sSql .= "        inner join arrecad       on arrecad.k00_numpre         = divida.v01_numpre                          ";
$sSql .= "                                and arrecad.k00_numpar         = divida.v01_numpar                          ";
$sSql .= "        inner join tabrec        on tabrec.k02_codigo          = arrecad.k00_receit                         ";
$sSql .= "        inner join arretipo      on arretipo.k00_tipo          = arrecad.k00_tipo                           ";
$sSql .= "        inner join cadtipo       on cadtipo.k03_tipo           = arretipo.k03_tipo                          ";
$sSql .= "        left  join divimportareg on divimportareg.v04_coddiv   = divida.v01_coddiv                          ";
$sSql .= "        left  join divimporta    on divimporta.v02_divimporta  = divimportareg.v04_divimporta               ";
$sSql .= "        left  join db_usuarios   on db_usuarios.id_usuario     = divimporta.v02_usuario                     ";
$sSql .= "        left  join arrematric    on arrematric.k00_numpre      = divida.v01_numpre                          ";
$sSql .= "        left  join arreinscr     on arreinscr.k00_numpre       = divida.v01_numpre                          ";
$sSql .= "        left  join arrenumcgm    on arrenumcgm.k00_numpre      = divida.v01_numpre                          ";
$sSql .= "   where {$sWhere}                                                                                          ";

$sSql .= "  union all                                                                                                 ";

$sSql .= "  select v01_coddiv,                                                                                        ";
$sSql .= "         v01_numpre,                                                                                        ";
$sSql .= "         v01_numpar,                                                                                        ";
$sSql .= "         k00_receit,                                                                                        ";
$sSql .= "         v01_proced,                                                                                        ";
$sSql .= "         cadtipo.k03_tipo,                                                                                  ";
$sSql .= "         cadtipo.k03_descr as descrtipo,                                                                    ";
$sSql .= "         tabrec.k02_drecei as descrreceit,                                                                  ";
$sSql .= "         proced.v03_dcomp  as descrproced,                                                                  ";
$sSql .= "         v07_descricao     as descrtipoproced,                                                              ";
$sSql .= "         v01_numcgm,                                                                                        ";
$sSql .= "         v02_usuario,                                                                                       ";
$sSql .= "         v02_data,                                                                                          ";
$sSql .= "         v02_hora,                                                                                          ";
$sSql .= "         v02_datafim,                                                                                       ";
$sSql .= "         v02_horafim,                                                                                       ";
$sSql .= "         v02_tipo,                                                                                          ";
$sSql .= "         v02_instit,                                                                                        ";
$sSql .= "         v02_divimporta,                                                                                    ";
$sSql .= "         v03_tributaria,                                                                                    ";
$sSql .= "         v07_descricao,                                                                                     ";
$sSql .= "         v01_dtvenc,                                                                                        ";
$sSql .= "         v01_exerc,                                                                                         ";
$sSql .= "         v01_vlrhis,                                                                                        ";
$sSql .= "         db_usuarios.nome as usuario,                                                                       ";
$sSql .= "         case                                                                                               ";
$sSql .= "             when arrematric.k00_matric is not null then 'M-'||k00_matric                                   ";
$sSql .= "             when arreinscr.k00_inscr is not null then 'I-'||k00_inscr                                      ";
$sSql .= "           else 'C-'||arrenumcgm.k00_numcgm                                                                 ";
$sSql .= "         end as origem,                                                                                     ";
$sSql .= "         case                                                                                               ";
$sSql .= "             when arrematric.k00_matric is not null then                                                    ";
$sSql .= "                  ( select rvNome from fc_busca_envolvidos(true, 1,'M',arrematric.k00_matric) limit 1 )     ";
$sSql .= "             when arreinscr.k00_inscr is not null then                                                      ";
$sSql .= "                  ( select rvNome from fc_busca_envolvidos(true,1,'I',arreinscr.k00_inscr) limit 1 )        ";
$sSql .= "           else ( select rvNome from fc_busca_envolvidos(true,1,'C',arrenumcgm.k00_numcgm) limit 1 )        ";
$sSql .= "         end as nomecontribuinte,                                                                           ";
$sSql .= "        fc_corre( arrecant.k00_receit,                                                                      ";
$sSql .= "                  arrecant.k00_dtoper,                                                                      ";
$sSql .= "                  arrecant.k00_valor,                                                                       ";
$sSql .= "                  v01_dtinclusao,                                                                           ";
$sSql .= "                  cast(extract(year from v01_dtinclusao) as integer),                                       ";
$sSql .= "                  v01_dtinclusao ) as corrigido,                                                            ";
$sSql .= "        coalesce( fc_juros( arrecant.k00_receit,                                                            ";
$sSql .= "                            arrecant.k00_dtvenc,                                                            ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            false,                                                                          ";
$sSql .= "                            cast( extract(                                                                  ";
$sSql .= "                            year from v01_dtinclusao) as integer)),0) as juros_base,                        ";
$sSql .= "        coalesce( fc_multa( arrecant.k00_receit,                                                            ";
$sSql .= "                            arrecant.k00_dtvenc,                                                            ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            arrecant.k00_dtoper,                                                            ";
$sSql .= "                            cast( extract(                                                                  ";
$sSql .= "                            year from v01_dtinclusao) as integer)),0) as multa_base,                        ";
$sSql .= "         ( to_timestamp(                                                                                    ";
$sSql .= "            (divimporta.v02_data || ' ' ||divimporta.v02_hora)::text, 'YYYY-MM-DD HH24:MI') ) as inicio,    ";
$sSql .= "         ( to_timestamp(                                                                                    ";
$sSql .= "            (divimporta.v02_datafim || ' ' ||divimporta.v02_horafim)::text, 'YYYY-MM-DD HH24:MI') ) as fim  ";
$sSql .= "    from divida                                                                                             ";
$sSql .= "         inner join cgm           on cgm.z01_numcgm             = divida.v01_numcgm                         ";
$sSql .= "         inner join proced        on proced.v03_codigo          = divida.v01_proced                         ";
$sSql .= "         inner join tipoproced    on tipoproced.v07_sequencial  = proced.v03_tributaria                     ";
$sSql .= "         inner join arrecant      on arrecant.k00_numpre        = divida.v01_numpre                         ";
$sSql .= "                                 and arrecant.k00_numpar        = divida.v01_numpar                         ";
$sSql .= "         inner join tabrec        on tabrec.k02_codigo          = arrecant.k00_receit                       ";
$sSql .= "         inner join arretipo      on arretipo.k00_tipo          = arrecant.k00_tipo                         ";
$sSql .= "         inner join cadtipo       on cadtipo.k03_tipo           = arretipo.k03_tipo                         ";
$sSql .= "         left  join divimportareg on divimportareg.v04_coddiv   = divida.v01_coddiv                         ";
$sSql .= "         left  join divimporta    on divimporta.v02_divimporta  = divimportareg.v04_divimporta              ";
$sSql .= "         left  join db_usuarios   on db_usuarios.id_usuario     = divimporta.v02_usuario                    ";
$sSql .= "         left  join arrematric    on arrematric.k00_numpre      = divida.v01_numpre                         ";
$sSql .= "         left  join arreinscr     on arreinscr.k00_numpre       = divida.v01_numpre                         ";
$sSql .= "         left  join arrenumcgm    on arrenumcgm.k00_numpre      = divida.v01_numpre                         ";
$sSql .= "   where {$sWhere}                                                                                          ";

$sSql .= "  union all                                                                                                 ";

$sSql .= "  select v01_coddiv,                                                                                        ";
$sSql .= "         v01_numpre,                                                                                        ";
$sSql .= "         v01_numpar,                                                                                        ";
$sSql .= "         k00_receit,                                                                                        ";
$sSql .= "         v01_proced,                                                                                        ";
$sSql .= "         cadtipo.k03_tipo,                                                                                  ";
$sSql .= "         cadtipo.k03_descr as descrtipo,                                                                    ";
$sSql .= "         tabrec.k02_drecei as descrreceit,                                                                  ";
$sSql .= "         proced.v03_dcomp  as descrproced,                                                                  ";
$sSql .= "         v07_descricao     as descrtipoproced,                                                              ";
$sSql .= "         v01_numcgm,                                                                                        ";
$sSql .= "         v02_usuario,                                                                                       ";
$sSql .= "         v02_data,                                                                                          ";
$sSql .= "         v02_hora,                                                                                          ";
$sSql .= "         v02_datafim,                                                                                       ";
$sSql .= "         v02_horafim,                                                                                       ";
$sSql .= "         v02_tipo,                                                                                          ";
$sSql .= "         v02_instit,                                                                                        ";
$sSql .= "         v02_divimporta,                                                                                    ";
$sSql .= "         v03_tributaria,                                                                                    ";
$sSql .= "         v07_descricao,                                                                                     ";
$sSql .= "         v01_dtvenc,                                                                                        ";
$sSql .= "         v01_exerc,                                                                                         ";
$sSql .= "         v01_vlrhis,                                                                                        ";
$sSql .= "         db_usuarios.nome as usuario,                                                                       ";
$sSql .= "         case                                                                                               ";
$sSql .= "             when arrematric.k00_matric is not null then 'M-'||k00_matric                                   ";
$sSql .= "             when arreinscr.k00_inscr is not null then 'I-'||k00_inscr                                      ";
$sSql .= "           else 'C-'||arrenumcgm.k00_numcgm                                                                 ";
$sSql .= "         end as origem,                                                                                     ";
$sSql .= "         case                                                                                               ";
$sSql .= "             when arrematric.k00_matric is not null then                                                    ";
$sSql .= "                  ( select rvNome from fc_busca_envolvidos(true, 1,'M',arrematric.k00_matric) limit 1 )     ";
$sSql .= "             when arreinscr.k00_inscr is not null then                                                      ";
$sSql .= "                  ( select rvNome from fc_busca_envolvidos(true,1,'I',arreinscr.k00_inscr) limit 1 )        ";
$sSql .= "           else ( select rvNome from fc_busca_envolvidos(true,1,'C',arrenumcgm.k00_numcgm) limit 1 )        ";
$sSql .= "         end as nomecontribuinte,                                                                           ";
$sSql .= "        fc_corre( arreold.k00_receit,                                                                       ";
$sSql .= "                  arreold.k00_dtoper,                                                                       ";
$sSql .= "                  arreold.k00_valor,                                                                        ";
$sSql .= "                  v01_dtinclusao,                                                                           ";
$sSql .= "                  cast(extract(year from v01_dtinclusao) as integer),                                       ";
$sSql .= "                  v01_dtinclusao ) as corrigido,                                                            ";
$sSql .= "        coalesce( fc_juros( arreold.k00_receit,                                                             ";
$sSql .= "                            arreold.k00_dtvenc,                                                             ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            false,                                                                          ";
$sSql .= "                            cast( extract(                                                                  ";
$sSql .= "                            year from v01_dtinclusao) as integer)),0) as juros_base,                        ";
$sSql .= "        coalesce( fc_multa( arreold.k00_receit,                                                             ";
$sSql .= "                            arreold.k00_dtvenc,                                                             ";
$sSql .= "                            v01_dtinclusao,                                                                 ";
$sSql .= "                            arreold.k00_dtoper,                                                             ";
$sSql .= "                            cast( extract(                                                                  ";
$sSql .= "                            year from v01_dtinclusao) as integer)),0) as multa_base,                        ";
$sSql .= "         ( to_timestamp(                                                                                    ";
$sSql .= "            (divimporta.v02_data || ' ' ||divimporta.v02_hora)::text, 'YYYY-MM-DD HH24:MI') ) as inicio,    ";
$sSql .= "         ( to_timestamp(                                                                                    ";
$sSql .= "            (divimporta.v02_datafim || ' ' ||divimporta.v02_horafim)::text, 'YYYY-MM-DD HH24:MI') ) as fim  ";
$sSql .= "    from divida                                                                                             ";
$sSql .= "         inner join cgm           on cgm.z01_numcgm             = divida.v01_numcgm                         ";
$sSql .= "         inner join proced        on proced.v03_codigo          = divida.v01_proced                         ";
$sSql .= "         inner join tipoproced    on tipoproced.v07_sequencial  = proced.v03_tributaria                     ";
$sSql .= "         inner join arreold       on arreold.k00_numpre         = divida.v01_numpre                         ";
$sSql .= "                                 and arreold.k00_numpar         = divida.v01_numpar                         ";
$sSql .= "         inner join tabrec        on tabrec.k02_codigo          = arreold.k00_receit                        ";
$sSql .= "         inner join arretipo      on arretipo.k00_tipo          = arreold.k00_tipo                          ";
$sSql .= "         inner join cadtipo       on cadtipo.k03_tipo           = arretipo.k03_tipo                         ";
$sSql .= "         left  join divimportareg on divimportareg.v04_coddiv   = divida.v01_coddiv                         ";
$sSql .= "         left  join divimporta    on divimporta.v02_divimporta  = divimportareg.v04_divimporta              ";
$sSql .= "         left  join db_usuarios   on db_usuarios.id_usuario     = divimporta.v02_usuario                    ";
$sSql .= "         left  join arrematric    on arrematric.k00_numpre      = divida.v01_numpre                         ";
$sSql .= "         left  join arreinscr     on arreinscr.k00_numpre       = divida.v01_numpre                         ";
$sSql .= "         left  join arrenumcgm    on arrenumcgm.k00_numpre      = divida.v01_numpre                         ";
$sSql .= "   where {$sWhere} {$sOrder}                                                                                ";
$sSql .= "   ) as x                                                                                                   ";

$rsSql        = db_query($sSql);
$iNumRownsSql = pg_num_rows($rsSql);

if ($iNumRownsSql == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$aLongoPrazo   = array();
$aCurtoPrazo   = array();
$aResumos      = array();

$aAgrupador['proced']      = 'v01_proced';
$aAgrupador['receita']     = 'k00_receit';
$aAgrupador['tipo_proced'] = 'v03_tributaria';
$aAgrupador['tipo_debito'] = 'k03_tipo';

if ($lTipoRel == 0) {
  for ( $iInd=0; $iInd < $iNumRownsSql; $iInd++ ) {

    $oDadosImpDivida = db_utils::fieldsMemory($rsSql,$iInd);

    $dtDataLimite = ($oDadosImpDivida->v01_exerc + 1)."-12-31";

    foreach ( $aAgrupador as $sDescrAgrupa => $sCampo ) {

      if (  in_array($oDadosImpDivida->k03_tipo,array(5,15,18)) || ( in_array($oDadosImpDivida->k03_tipo,array(6,13)) && $oDadosImpDivida->v01_dtvenc > $dtDataLimite ) ) {

        if ( isset($aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]) ) {
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']   += $oDadosImpDivida->v01_vlrhis;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']   += $oDadosImpDivida->corrigido;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']     += $oDadosImpDivida->multa;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']     += $oDadosImpDivida->juros;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']     += $oDadosImpDivida->total;
        } else {
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['iTipoProced'] = $oDadosImpDivida->v03_tributaria;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']    = $oDadosImpDivida->v01_vlrhis;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']    = $oDadosImpDivida->corrigido;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']      = $oDadosImpDivida->multa;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']      = $oDadosImpDivida->juros;
          $aLongoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']      = $oDadosImpDivida->total;
        }

      } else if ( in_array($oDadosImpDivida->k03_tipo,array(6,13)) && $oDadosImpDivida->v01_dtvenc <= $dtDataLimite ) {

        if ( isset($aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]) ) {
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']   += $oDadosImpDivida->v01_vlrhis;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']   += $oDadosImpDivida->corrigido;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']     += $oDadosImpDivida->multa;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']     += $oDadosImpDivida->juros;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']     += $oDadosImpDivida->total;
        } else {
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['iTipoProced'] = $oDadosImpDivida->v03_tributaria;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist']    = $oDadosImpDivida->v01_vlrhis;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr']    = $oDadosImpDivida->corrigido;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']      = $oDadosImpDivida->multa;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']      = $oDadosImpDivida->juros;
          $aCurtoPrazo[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']      = $oDadosImpDivida->total;
        }

      }

      if ( isset($aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]) ) {
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist'] += $oDadosImpDivida->v01_vlrhis;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr'] += $oDadosImpDivida->corrigido;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']   += $oDadosImpDivida->multa;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']   += $oDadosImpDivida->juros;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']   += $oDadosImpDivida->total;
      } else {
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrHist'] = $oDadosImpDivida->v01_vlrhis;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nVlrCorr'] = $oDadosImpDivida->corrigido;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nMulta']   = $oDadosImpDivida->multa;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nJuros']   = $oDadosImpDivida->juros;
        $aResumos[$sDescrAgrupa][$oDadosImpDivida->$sCampo]['nTotal']   = $oDadosImpDivida->total;
      }

    }


    $aDescrTipo[$oDadosImpDivida->k03_tipo]             = $oDadosImpDivida->descrtipo;
    $aDescrProced[$oDadosImpDivida->v01_proced]         = $oDadosImpDivida->descrproced;
    $aDescrReceit[$oDadosImpDivida->k00_receit]         = $oDadosImpDivida->descrreceit;
    $aDescrTipoProced[$oDadosImpDivida->v03_tributaria] = $oDadosImpDivida->descrtipoproced;

  }
}


// Cria lista com exercícios de 3 anos anteriores aos exercícios selecionados
$aDataDebitos = explode("-",$oParametros->dataini);

for ( $iInd=1; $iInd <= 3; $iInd++ ) {
  $aExercicioPago[] = ($aDataDebitos[0] - $iInd);
}

$aExercicioPago = array_unique($aExercicioPago);

// Consulta os débitos pagos de 3 anos anteriores aos exercícios selecionados
$sSqlDebitosPago  = " select arretipo.k03_tipo,                                                ";
$sSqlDebitosPago .= "        arrecant.k00_receit,                                              ";
$sSqlDebitosPago .= "        divida.v01_proced,                                                ";
$sSqlDebitosPago .= "        divida.v01_exerc,                                                 ";
$sSqlDebitosPago .= "        v03_tributaria,                                                   ";
$sSqlDebitosPago .= "        round(sum(arrepaga.k00_valor),2) as total                         ";
$sSqlDebitosPago .= "   from divida                                                            ";
$sSqlDebitosPago .= "        inner join arrepaga   on arrepaga.k00_numpre = divida.v01_numpre  ";
$sSqlDebitosPago .= "                             and arrepaga.k00_numpar = divida.v01_numpar  ";
$sSqlDebitosPago .= "        inner join arrecant   on arrecant.k00_numpre = divida.v01_numpre  ";
$sSqlDebitosPago .= "                             and arrecant.k00_numpar = divida.v01_numpar  ";
$sSqlDebitosPago .= "        inner join arretipo   on arretipo.k00_tipo   = arrecant.k00_tipo  ";
$sSqlDebitosPago .= "        inner join proced     on proced.v03_codigo          = divida.v01_proced                         ";
$sSqlDebitosPago .= "  where extract( year from arrepaga.k00_dtpaga) in (".implode(',',$aExercicioPago).") ";
$sSqlDebitosPago .= "  group by arretipo.k03_tipo,                                              ";
$sSqlDebitosPago .= "           arrecant.k00_receit,                                            ";
$sSqlDebitosPago .= "           divida.v01_proced,                                              ";
$sSqlDebitosPago .= "           divida.v01_exerc,                                               ";
$sSqlDebitosPago .= "           v03_tributaria                                                  ";
$sSqlDebitosPago .= "  order by arretipo.k03_tipo,                                              ";
$sSqlDebitosPago .= "           divida.v01_exerc,                                               ";
$sSqlDebitosPago .= "           divida.v01_proced;                                              ";

$rsDebitosPagos   = db_query($sSqlDebitosPago);
$iNroDebitosPagos = pg_num_rows($rsDebitosPagos);

for ( $iInd=0; $iInd < $iNroDebitosPagos; $iInd++ ) {

  $oDebitosPagos = db_utils::fieldsMemory($rsDebitosPagos,$iInd);

  foreach ($aAgrupador as $sDescrAgrupa => $sCampo ) {
    if ( isset($aDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]) ) {
      $aDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal'] += $oDebitosPagos->total;
    } else {
      $aDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal']  = $oDebitosPagos->total;
    }
  }

}

foreach ( $aLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {

  foreach ( $aDadosLongoPrazo as $sCampoAgrupa =>$aValoresLongoPrazo) {

    if ( isset($aDebitosPagos[$sTipoAgrupa][$sCampoAgrupa])) {

      $nTotalPago   = $aDebitosPagos[$sTipoAgrupa][$sCampoAgrupa]['nTotal'];
      $nTotalPago   = round(( ($nTotalPago/3) * 2 ),2);
      $nTotalProced = $aValoresLongoPrazo['nTotal'];

      // Percentual que será subtraído do logon prazo e incluído no longo prazo
      $nPercentual  = round(( ($nTotalPago*100) / $nTotalProced ),2);

      $nValorHist  = ( ($aValoresLongoPrazo['nVlrHist']/100) * $nPercentual );
      $nValorCorr  = ( ($aValoresLongoPrazo['nVlrCorr']/100) * $nPercentual );
      $nValorMulta = ( ($aValoresLongoPrazo['nMulta']/100) * $nPercentual );
      $nValorJuros = ( ($aValoresLongoPrazo['nJuros']/100) * $nPercentual );
      $nValorTotal = ( ($aValoresLongoPrazo['nTotal']/100) * $nPercentual );

      if ( $nValorTotal < $aValoresLongoPrazo['nTotal'] ) {

        $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist'] -= $nValorHist;
        $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr'] -= $nValorCorr;
        $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']   -= $nValorMulta;
        $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']   -= $nValorJuros;
        $aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']   -= $nValorTotal;

        if ( isset($aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    += $nValorHist;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    += $nValorCorr;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      += $nValorMulta;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      += $nValorJuros;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      += $nValorTotal;
        } else {
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['iTipoProced'] = $aValoresLongoPrazo['iTipoProced'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    = $nValorHist;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    = $nValorCorr;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      = $nValorMulta;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      = $nValorJuros;
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      = $nValorTotal;
        }

      } else {

        if ( isset($aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    += $aValoresLongoPrazo['nVlrHist'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    += $aValoresLongoPrazo['nVlrCorr'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      += $aValoresLongoPrazo['nMulta'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      += $aValoresLongoPrazo['nJuros'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      += $aValoresLongoPrazo['nTotal'];
        } else {
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['iTipoProced'] = $aValoresLongoPrazo['iTipoProced'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']    = $aValoresLongoPrazo['nVlrHist'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']    = $aValoresLongoPrazo['nVlrCorr'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']      = $aValoresLongoPrazo['nMulta'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']      = $aValoresLongoPrazo['nJuros'];
          $aCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']      = $aValoresLongoPrazo['nTotal'];
        }

        unset($aLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]);
      }
    }
  }
}

// Remove Tipo de Débito sem valor
foreach ( $aLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {
  if ( count($aDadosLongoPrazo) == 0 ) {
    unset($aLongoPrazo[$sTipoAgrupa]);
  }
}


$head2 = "RELATÓRIO DE INSCRIÇÃO EM DIVIDA";
$head4 = "PERÍODO: ".db_formatar($oParametros->dataini,'d')." à ".db_formatar($oParametros->datafim,'d');
$head5 = "TIPO DE RELATÓRIO: ".$sTipoRel;
$head6 = "TIPO DE INSCRIÇÃO: ".$sTipoImp;

$aDadosSintetico = array();
$aDadosAnalitico = array();
$aQtdTotProced   = array();
$aDados          = array();

// for($iInd = 0; $iInd < $iNumRownsSql; $iInd++){

//   if($iAux == 0){

//     $pdf = new PDF();
//     $pdf->Open();
//     $pdf->AliasNbPages();

//     $pdf->SetTextColor(0, 0, 0);
//     $pdf->SetFillColor(235);
//   }

//   if($iAux == 50 or $iInd + 1 == $iNumRownsSql){

//     $sPdfPathFile = 'tmp/relImpDivAtiv'.uniqid().'.pdf';
//     $pdf->Output($sPdfPathFile, false, true);
//     $oRetorno->aPdf[] = $sPdfPathFile;
//     $iAux = 0;
//   }else{
//     $iAux++;
//   }
// }

// die($oJson->encode($oRetorno));

// $pdf = new PDF();
// $pdf->Open();
// $pdf->AliasNbPages();

// $pdf->SetTextColor(0,0,0);
// $pdf->SetFillColor(235);

$nVlrTotalHist      = 0;
$nVlrTotalCort      = 0;
$nVlrTotalJur       = 0;
$nVlrTotalMul       = 0;
$nVlrTotal          = 0;
$nVlrGeralTotalHist = 0;
$nVlrGeralTotalCort = 0;
$nVlrGeralTotalJur  = 0;
$nVlrGeralTotalMul  = 0;
$nVlrGeralTotal     = 0;
$nTotalVlrProced    = 0;
$lImprime           = true;

if ($lTipoRel == 0) {

  for ( $iInd = 0; $iInd  < $iNumRownsSql; $iInd++ ) {

      $oDadosImpDivida = db_utils::fieldsMemory($rsSql,$iInd);

      $oDadosImp = new stdClass();
      $oDadosImp->CodImp       = $oDadosImpDivida->v02_divimporta;
      $oDadosImp->dtIni        = $oDadosImpDivida->v02_data;
      $oDadosImp->hrIni        = $oDadosImpDivida->v02_hora;
      $oDadosImp->dtFim        = $oDadosImpDivida->v02_datafim;
      $oDadosImp->hrFim        = $oDadosImpDivida->v02_horafim;
      $oDadosImp->iTempo       = $oDadosImpDivida->tempo;
      $oDadosImp->sUsuario     = $oDadosImpDivida->usuario;
      $oDadosImp->iTipo        = $oDadosImpDivida->v02_tipo;

     if ( !isset($aDadosSintetico[$oDadosImpDivida->v02_divimporta]) ) {
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['oDadosImp'] = $oDadosImp;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrHist']  = $oDadosImpDivida->v01_vlrhis;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrCorr']  = $oDadosImpDivida->corrigido;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrJur']   = $oDadosImpDivida->juros;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrMul']   = $oDadosImpDivida->multa;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nTotal']    = $oDadosImpDivida->corrigido
                                                                       + $oDadosImpDivida->juros
                                                                       + $oDadosImpDivida->multa;
     } else {
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrHist'] += $oDadosImpDivida->v01_vlrhis;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrCorr'] += $oDadosImpDivida->corrigido;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrJur']  += $oDadosImpDivida->juros;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nVlrMul']  += $oDadosImpDivida->multa;
       $aDadosSintetico[$oDadosImpDivida->v02_divimporta]['nTotal']   += $oDadosImpDivida->corrigido
                                                                       + $oDadosImpDivida->juros
                                                                       + $oDadosImpDivida->multa;
     }

  }

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();

  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(235);

  foreach ( $aDadosSintetico as $iCodImp => $aDadosImp ) {

    $sNome = substr($aDadosImp['oDadosImp']->sUsuario,0,35);

    if ($pdf->gety() > $pdf->h - 30  || $lImprime  ){

      $lImprime = false;
      $pdf->addpage();

      $pdf->SetFont($sLetra,'B',8);
      $pdf->ln(0);
      $pdf->Cell(190,5,"Importação da Divida Ativa",0,1,"C",0);

      $pdf->ln(1);
      $pdf->SetFont($sLetra,'B',6);
      $pdf->Cell(15,4,"Cod."                                              ,1,0,"C",1);
      $pdf->Cell(20,4,"Data inicial"                                      ,1,0,"C",1);
      $pdf->Cell(20,4,"Hora inicial"                                      ,1,0,"C",1);
      $pdf->Cell(20,4,"Data final"                                        ,1,0,"C",1);
      $pdf->Cell(20,4,"Hora Final"                                        ,1,0,"C",1);
      $pdf->Cell(15,4,"Tempo"                                             ,1,0,"C",1);
      $pdf->Cell(67,4,"Usuário"                                           ,1,0,"C",1);
      $pdf->Cell(15,4,"Tipo"                                              ,1,1,"C",1);

      $pdf->Cell(110,4,""                                                 ,0,0,0,0);
      $pdf->Cell(17,4,"Vlr Hist."                                         ,1,0,"C",1);
      $pdf->Cell(17,4,"Vlr Cort."                                         ,1,0,"C",1);
      $pdf->Cell(17,4,"Vlr Jur."                                          ,1,0,"C",1);
      $pdf->Cell(16,4,"Vlr Mul."                                          ,1,0,"C",1);
      $pdf->Cell(15,4,"Total"                                             ,1,1,"C",1);

    }

    $pdf->SetFont($sLetra,'',5);
    $pdf->Cell(15,3,$aDadosImp['oDadosImp']->CodImp                       ,0,0,"C",0);
    $pdf->Cell(20,3,db_formatar($aDadosImp['oDadosImp']->dtIni,"d")       ,0,0,"C",0);
    $pdf->Cell(20,3,$aDadosImp['oDadosImp']->hrIni                        ,0,0,"C",0);
    $pdf->Cell(20,3,db_formatar($aDadosImp['oDadosImp']->dtFim,"d")       ,0,0,"C",0);
    $pdf->Cell(20,3,$aDadosImp['oDadosImp']->hrFim                        ,0,0,"C",0);
    $pdf->Cell(15,3,$aDadosImp['oDadosImp']->iTempo                       ,0,0,"C",0);
    $pdf->Cell(67,3,$sNome                                                ,0,0,"R",0);

    if($aDadosImp['oDadosImp']->iTipo == 1){
      $pdf->Cell(15,3,"Parcial"                                           ,0,1,"C",0);
    } else if ($aDadosImp['oDadosImp']->iTipo == 2){
      $pdf->Cell(15,3,"Geral"                                             ,0,1,"C",0);
    } else {
      $pdf->Cell(15,3,"Inclusão Manual"                                   ,0,1,"C",0);
    }

    $pdf->SetFont($sLetra,'',5);
    $pdf->Cell(110,3,"",0,0,0,0);
    $pdf->Cell(17,3,db_formatar($aDadosImp['nVlrHist'],"f"),0,0,"C",0);
    $pdf->Cell(17,3,db_formatar($aDadosImp['nVlrCorr'],"f"),0,0,"C",0);
    $pdf->Cell(17,3,db_formatar($aDadosImp['nVlrJur'] ,"f"),0,0,"C",0);
    $pdf->Cell(16,3,db_formatar($aDadosImp['nVlrMul'] ,"f"),0,0,"C",0);
    $pdf->Cell(15,3,db_formatar($aDadosImp['nTotal']  ,"f"),0,1,"C",0);


    $nVlrGeralTotalHist += $aDadosImp['nVlrHist'];
    $nVlrGeralTotalCort += $aDadosImp['nVlrCorr'];
    $nVlrGeralTotalJur  += $aDadosImp['nVlrJur'];
    $nVlrGeralTotalMul  += $aDadosImp['nVlrMul'];
    $nVlrGeralTotal     += $aDadosImp['nTotal'];
  }

  $pdf->Cell(192,2,""                                                     ,0,1,0,0);
  $pdf->Cell(192,0,""                                                     ,"T",1,0,0);
  $pdf->ln(0);
  $pdf->SetFont($sLetra,"B",5);
  $pdf->cell(110,3,'Total Geral --->  '                                   ,0,0,"R",0);
  $pdf->SetFont($sLetra,"",5);
  $pdf->Cell(17,3,db_formatar($nVlrGeralTotalHist,"f")                    ,0,0,"C",0);
  $pdf->SetFont($sLetra,"B",5);
  $pdf->Cell(17,3,db_formatar($nVlrGeralTotalCort,"f")                    ,0,0,"C",0);
  $pdf->Cell(17,3,db_formatar($nVlrGeralTotalJur,"f")                     ,0,0,"C",0);
  $pdf->Cell(16,3,db_formatar($nVlrGeralTotalMul,"f")                     ,0,0,"C",0);
  $pdf->Cell(15,3,db_formatar($nVlrGeralTotal,"f")                        ,0,1,"C",0);

  $sPdfPathFile = "tmp/relImpDivAtiv".uniqid().".pdf";
  $pdf->Output($sPdfPathFile, false, true);
  $oRetorno->sPdfPathRelatorio = $sPdfPathFile;

  $iAlt = 3;

  $pdf = new PDF();
  $pdf->Open();
  $pdf->addpage();
  $pdf->AliasNbPages();

  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(235);

  $pdf->SetFont($sLetra,'B',5);
  $pdf->Cell(135,3,"RESUMO POR CURTO E LONGO PRAZO"  ,1,1,"C",0);

  foreach ( $aAgrupador as $sTipoAgrupa => $sCampo ) {

    $nTotalHistCurto  = 0;
    $nTotalCorrCurto  = 0;
    $nTotalMultaCurto = 0;
    $nTotalJurosCurto = 0;
    $nTotalCurto      = 0;

    if ( $sTipoAgrupa == "proced" ) {
      $sTituloAgrupa = "Procedência";
    } else if ( $sTipoAgrupa == "receita" ) {
      $sTituloAgrupa = "Receita";
    } else if ( $sTipoAgrupa == "tipo_proced" ) {
      $sTituloAgrupa = "Tipo de Procedência";
    } else {
      $sTituloAgrupa = "Tipo de Débito";
    }

    $aTotalGeral = array();

    if ( isset($aCurtoPrazo[$sTipoAgrupa]) ) {

      if ( $pdf->gety() > $pdf->h - 30  ){
        $pdf->addpage();
      }

      $pdf->SetFont('Arial','B',5);
      $pdf->Cell(135,$iAlt,"Resumo por {$sTituloAgrupa} CURTO PRAZO",1,1,'L',1);
      $pdf->Cell(10,$iAlt,'Código'        ,1,0,'C',1);
      $pdf->Cell(50,$iAlt,'Descrição'     ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Histórico' ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Corrigido' ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Multa'     ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Juros'     ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Total'         ,1,1,'C',1);

      foreach ( $aCurtoPrazo[$sTipoAgrupa] as $sValorTipo => $aValoresCurtoPrazo ) {

        $pdf->SetFont('Arial','',5);

        if ( $sTipoAgrupa == "proced" ) {
          $sDescricao = $aDescrProced[$sValorTipo];
        } else if ( $sTipoAgrupa == "receita" ) {
          $sDescricao = $aDescrReceit[$sValorTipo];
        } else if ( $sTipoAgrupa == "tipo_proced" ) {
          $sDescricao = $aDescrTipoProced[$sValorTipo];
        } else {
          $sDescricao = $aDescrTipo[$sValorTipo];
        }

        $pdf->Cell(10,$iAlt,$sValorTipo                                             ,1,0,'C',0);
        $pdf->Cell(50,$iAlt,$sDescricao                                             ,1,0,'L',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresCurtoPrazo['nVlrHist']        ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresCurtoPrazo['nVlrCorr']        ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresCurtoPrazo['nMulta']          ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresCurtoPrazo['nJuros']          ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresCurtoPrazo['nTotal']          ,'f'),1,1,'R',0);

        $nTotalHistCurto  += $aValoresCurtoPrazo['nVlrHist'];
        $nTotalCorrCurto  += $aValoresCurtoPrazo['nVlrCorr'];
        $nTotalMultaCurto += $aValoresCurtoPrazo['nMulta'];
        $nTotalJurosCurto += $aValoresCurtoPrazo['nJuros'];
        $nTotalCurto      += $aValoresCurtoPrazo['nTotal'];


        if ( isset($aTotalGeral[$sValorTipo]) ) {
          $aTotalGeral[$sValorTipo]['nVlrHist'] += $aValoresCurtoPrazo['nVlrHist'];
          $aTotalGeral[$sValorTipo]['nVlrCorr'] += $aValoresCurtoPrazo['nVlrCorr'];
          $aTotalGeral[$sValorTipo]['nMulta']   += $aValoresCurtoPrazo['nMulta'];
          $aTotalGeral[$sValorTipo]['nJuros']   += $aValoresCurtoPrazo['nJuros'];
          $aTotalGeral[$sValorTipo]['nTotal']   += $aValoresCurtoPrazo['nTotal'];
        } else {
          $aTotalGeral[$sValorTipo]['nVlrHist'] = $aValoresCurtoPrazo['nVlrHist'];
          $aTotalGeral[$sValorTipo]['nVlrCorr'] = $aValoresCurtoPrazo['nVlrCorr'];
          $aTotalGeral[$sValorTipo]['nMulta']   = $aValoresCurtoPrazo['nMulta'];
          $aTotalGeral[$sValorTipo]['nJuros']   = $aValoresCurtoPrazo['nJuros'];
          $aTotalGeral[$sValorTipo]['nTotal']   = $aValoresCurtoPrazo['nTotal'];
        }

      }

      $pdf->SetFont('Arial','B',5);
      $pdf->Cell(10,$iAlt,'Total:'                          ,1,0,'R',0);
      $pdf->Cell(50,$iAlt,''                                ,1,0,'L',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalHistCurto ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalCorrCurto,'f'), 1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalMultaCurto,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalJurosCurto,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalCurto     ,'f'),1,1,'R',0);

      $pdf->Ln(6);

    }

    $nTotalHistLongo  = 0;
    $nTotalCorrLongo  = 0;
    $nTotalMultaLongo = 0;
    $nTotalJurosLongo = 0;
    $nTotalLongo      = 0;

    if ( isset($aLongoPrazo[$sTipoAgrupa]) ) {

      $pdf->SetFont('Arial','B',5);
      $pdf->Cell(135,$iAlt,"Resumo por {$sTituloAgrupa} LONGO PRAZO",1,1,'L',1);
      $pdf->Cell(10,$iAlt,'Código'        ,1,0,'C',1);
      $pdf->Cell(50,$iAlt,'Descrição'     ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Histórico' ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Corrigido' ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Multa'     ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Vlr Juros'     ,1,0,'C',1);
      $pdf->Cell(15,$iAlt,'Total'         ,1,1,'C',1);

      foreach ( $aLongoPrazo[$sTipoAgrupa] as $sValorTipo => $aValoresLongoPrazo ) {

        $pdf->SetFont('Arial','',5);

        if ( $sTipoAgrupa == "proced" ) {
          $sDescricao = $aDescrProced[$sValorTipo];
        } else if ( $sTipoAgrupa == "receita" ) {
          $sDescricao = $aDescrReceit[$sValorTipo];
        } else if ( $sTipoAgrupa == "tipo_proced" ) {
          $sDescricao = $aDescrTipoProced[$sValorTipo];
        } else {
          $sDescricao = $aDescrTipo[$sValorTipo];
        }

        $pdf->Cell(10,$iAlt,$sValorTipo                                             ,1,0,'C',0);
        $pdf->Cell(50,$iAlt,$sDescricao                                             ,1,0,'L',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresLongoPrazo['nVlrHist']        ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresLongoPrazo['nVlrCorr']        ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresLongoPrazo['nMulta']          ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresLongoPrazo['nJuros']          ,'f'),1,0,'R',0);
        $pdf->Cell(15,$iAlt,db_formatar($aValoresLongoPrazo['nTotal']          ,'f'),1,1,'R',0);

        $nTotalHistLongo  += $aValoresLongoPrazo['nVlrHist'];
        $nTotalCorrLongo  += $aValoresLongoPrazo['nVlrCorr'];
        $nTotalMultaLongo += $aValoresLongoPrazo['nMulta'];
        $nTotalJurosLongo += $aValoresLongoPrazo['nJuros'];
        $nTotalLongo      += $aValoresLongoPrazo['nTotal'];

        if ( isset($aTotalGeral[$sValorTipo]) ) {
          $aTotalGeral[$sValorTipo]['nVlrHist'] += $aValoresLongoPrazo['nVlrHist'];
          $aTotalGeral[$sValorTipo]['nVlrCorr'] += $aValoresLongoPrazo['nVlrCorr'];
          $aTotalGeral[$sValorTipo]['nMulta']   += $aValoresLongoPrazo['nMulta'];
          $aTotalGeral[$sValorTipo]['nJuros']   += $aValoresLongoPrazo['nJuros'];
          $aTotalGeral[$sValorTipo]['nTotal']   += $aValoresLongoPrazo['nTotal'];
        } else {
          $aTotalGeral[$sValorTipo]['nVlrHist'] = $aValoresLongoPrazo['nVlrHist'];
          $aTotalGeral[$sValorTipo]['nVlrCorr'] = $aValoresLongoPrazo['nVlrCorr'];
          $aTotalGeral[$sValorTipo]['nMulta']   = $aValoresLongoPrazo['nMulta'];
          $aTotalGeral[$sValorTipo]['nJuros']   = $aValoresLongoPrazo['nJuros'];
          $aTotalGeral[$sValorTipo]['nTotal']   = $aValoresLongoPrazo['nTotal'];
        }

      }

      $pdf->SetFont('Arial','B',5);
      $pdf->Cell(10,$iAlt,'Total:'                          ,1,0,'R',0);
      $pdf->Cell(50,$iAlt,''                                ,1,0,'L',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalHistLongo ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalCorrLongo,'f'), 1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalMultaLongo,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalJurosLongo,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($nTotalLongo     ,'f'),1,1,'R',0);

      $pdf->Ln(6);

    }

    $nTotalHistGeral  = 0;
    $nTotalCorrGeral  = 0;
    $nTotalMultaGeral = 0;
    $nTotalJurosGeral = 0;
    $nTotalGeral      = 0;

    $pdf->SetFont('Arial','B',5);
    $pdf->Cell(135,$iAlt,"Resumo por {$sTituloAgrupa} CURTO E LONGO PRAZO",1,1,'L',1);
    $pdf->Cell(10,$iAlt,'Código'        ,1,0,'C',1);
    $pdf->Cell(50,$iAlt,'Descrição'     ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Histórico' ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Corrigido' ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Multa'     ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Juros'     ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Total'         ,1,1,'C',1);

    foreach ( $aTotalGeral as $sValorTipo => $aValoresTotalGeral ) {

      $pdf->SetFont('Arial','',5);

      if ( $sTipoAgrupa == "proced" ) {
        $sDescricao = $aDescrProced[$sValorTipo];
      } else if ( $sTipoAgrupa == "receita" ) {
        $sDescricao = $aDescrReceit[$sValorTipo];
      } else if ( $sTipoAgrupa == "tipo_proced" ) {
        $sDescricao = $aDescrTipoProced[$sValorTipo];
      } else {
        $sDescricao = $aDescrTipo[$sValorTipo];
      }

      $pdf->Cell(10,$iAlt,$sValorTipo                                             ,1,0,'C',0);
      $pdf->Cell(50,$iAlt,$sDescricao                                             ,1,0,'L',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresTotalGeral['nVlrHist']        ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresTotalGeral['nVlrCorr']        ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresTotalGeral['nMulta']          ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresTotalGeral['nJuros']          ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresTotalGeral['nTotal']          ,'f'),1,1,'R',0);

      $nTotalHistGeral  += $aValoresTotalGeral['nVlrHist'];
      $nTotalCorrGeral  += $aValoresTotalGeral['nVlrCorr'];
      $nTotalMultaGeral += $aValoresTotalGeral['nMulta'];
      $nTotalJurosGeral += $aValoresTotalGeral['nJuros'];
      $nTotalGeral      += $aValoresTotalGeral['nTotal'];

    }

    $pdf->SetFont('Arial','B',5);
    $pdf->Cell(10,$iAlt,'Total:'                          ,1,0,'R',0);
    $pdf->Cell(50,$iAlt,''                                ,1,0,'L',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalHistGeral ,'f'),1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalCorrGeral,'f'), 1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalMultaGeral,'f'),1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalJurosGeral,'f'),1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalGeral     ,'f'),1,1,'R',0);

    $pdf->Ln(6);

  }

  foreach ( $aAgrupador as $sTipoAgrupa => $sCampo ) {

    $nTotalHistResumo  = 0;
    $nTotalCorrResumo  = 0;
    $nTotalMultaResumo = 0;
    $nTotalJurosResumo = 0;
    $nTotalResumo      = 0;

    if ( $sTipoAgrupa == "proced" ) {
      $sTituloAgrupa = "Procedência";
    } else if ( $sTipoAgrupa == "receita" ) {
      $sTituloAgrupa = "Receita";
    } else if ( $sTipoAgrupa == "tipo_proced" ) {
      $sTituloAgrupa = "Tipo de Procedência";
    } else {
      $sTituloAgrupa = "Tipo de Débito";
    }

    $pdf->SetFont('Arial','B',5);
    $pdf->Cell(135,$iAlt,"Resumo por {$sTituloAgrupa}",1,1,'L',1);
    $pdf->Cell(10,$iAlt,'Código'                      ,1,0,'C',1);
    $pdf->Cell(50,$iAlt,'Descrição'                   ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Histórico'               ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Corrigido'               ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Multa'                   ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Vlr Juros'                   ,1,0,'C',1);
    $pdf->Cell(15,$iAlt,'Total'                       ,1,1,'C',1);

    foreach ( $aResumos[$sTipoAgrupa] as $iCodResumo => $aValoresResumo ) {

      if ( $sTipoAgrupa == "proced" ) {
        $sDescricao = $aDescrProced[$iCodResumo];
      } else if ( $sTipoAgrupa == "receita" ) {
        $sDescricao = $aDescrReceit[$iCodResumo];
      } else if ( $sTipoAgrupa == "tipo_proced" ) {
        $sDescricao = $aDescrTipoProced[$iCodResumo];
      } else {
        $sDescricao = $aDescrTipo[$iCodResumo];
      }

      $pdf->SetFont('Arial','',5);
      $pdf->Cell(10,$iAlt,$iCodResumo                                 ,1,0,'C',0);
      $pdf->Cell(50,$iAlt,$sDescricao                                 ,1,0,'L',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresResumo['nVlrHist'],'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresResumo['nVlrCorr'],'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresResumo['nMulta']  ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresResumo['nJuros']  ,'f'),1,0,'R',0);
      $pdf->Cell(15,$iAlt,db_formatar($aValoresResumo['nTotal']  ,'f'),1,1,'R',0);

      $nTotalHistResumo  += $aValoresResumo['nVlrHist'];
      $nTotalCorrResumo  += $aValoresResumo['nVlrCorr'];
      $nTotalMultaResumo += $aValoresResumo['nMulta'];
      $nTotalJurosResumo += $aValoresResumo['nJuros'];
      $nTotalResumo      += $aValoresResumo['nTotal'];

    }

    $pdf->SetFont('Arial','B',5);
    $pdf->Cell(10,$iAlt,'Total:'                           ,1,0,'R',0);
    $pdf->Cell(50,$iAlt,''                                 ,1,0,'L',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalHistResumo ,'f'),1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalCorrResumo ,'f'),1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalMultaResumo,'f'),1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalJurosResumo,'f'),1,0,'R',0);
    $pdf->Cell(15,$iAlt,db_formatar($nTotalResumo     ,'f'),1,1,'R',0);

    $pdf->Ln(6);

  }

  $sPdfPathFile = "tmp/relImpDivAtiv".uniqid().".pdf";
  $pdf->Output($sPdfPathFile, false, true);
  $oRetorno->sPdfPathResumo = $sPdfPathFile;

} else {

  for ( $iInd = 0; $iInd  < $iNumRownsSql; $iInd++ ) {

    $oDadosImpDivida = db_utils::fieldsMemory($rsSql,$iInd);

    $oDadosImp = new stdClass();
    $oDadosImp->CodImp       = $oDadosImpDivida->v02_divimporta;
    $oDadosImp->dtIni        = $oDadosImpDivida->v02_data;
    $oDadosImp->hrIni        = $oDadosImpDivida->v02_hora;
    $oDadosImp->dtFim        = $oDadosImpDivida->v02_datafim;
    $oDadosImp->hrFim        = $oDadosImpDivida->v02_horafim;
    $oDadosImp->iTempo       = $oDadosImpDivida->tempo;
    $oDadosImp->sUsuario     = $oDadosImpDivida->usuario;
    $oDadosImp->iTipo        = $oDadosImpDivida->v02_tipo;

    $oDadosDiv = new stdClass();
    $oDadosDiv->CArrec       = $oDadosImpDivida->v01_numpre;
    $oDadosDiv->Parc         = $oDadosImpDivida->v01_numpar;
    $oDadosDiv->CDivida      = $oDadosImpDivida->v01_coddiv;
    $oDadosDiv->Venc         = $oDadosImpDivida->v01_dtvenc;
    $oDadosDiv->Trib         = $oDadosImpDivida->v03_tributaria;
    $oDadosDiv->sDescr       = $oDadosImpDivida->v07_descricao;
    $oDadosDiv->sNomeContrib = $oDadosImpDivida->nomecontribuinte;
    $oDadosDiv->iExec        = $oDadosImpDivida->v01_exerc;
    $oDadosDiv->sOrigem      = $oDadosImpDivida->origem;
    $oDadosDiv->vlrHist      = $oDadosImpDivida->v01_vlrhis;
    $oDadosDiv->vlrCort      = $oDadosImpDivida->corrigido;
    $oDadosDiv->vlrJur       = $oDadosImpDivida->juros;
    $oDadosDiv->vlrMul       = $oDadosImpDivida->multa;
    $oDadosDiv->vlrTotal     = $oDadosImpDivida->corrigido
                             + $oDadosImpDivida->juros
                             + $oDadosImpDivida->multa;

    if ( isset($aDadosAnalitico[$oDadosImpDivida->v02_divimporta]) ) {
      $aDadosAnalitico[$oDadosImpDivida->v02_divimporta]['aListaDiv'][] = $oDadosDiv;
    } else {
      $aDadosAnalitico[$oDadosImpDivida->v02_divimporta]['oDadosImp']   = $oDadosImp;
      $aDadosAnalitico[$oDadosImpDivida->v02_divimporta]['aListaDiv'][] = $oDadosDiv;
    }

  }

  $aVlrGeralTotalHist = array();
  $aVlrGeralTotalCort = array();
  $aVlrGeralTotalJur  = array();
  $aVlrGeralTotalMul  = array();
  $aVlrGeralTotal     = array();

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();

  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(235);

  foreach ($aDadosAnalitico as $iCodImp => $aDadosImp ) {

    $sNome = substr($aDadosImp['oDadosImp']->sUsuario,0,35);

    if ($pdf->gety() > $pdf->h - 30 || $lImprime){

      $lImprime = false;
      $pdf->addpage();

      $pdf->SetFont($sLetra,'B',8);
      $pdf->ln(0);
      $pdf->Cell(190,5,"Importação da Divida Ativa",0,1,"C",0);

      $pdf->ln(1);
      $pdf->SetFont($sLetra,'B',6);
      $pdf->Cell(15,4,"Cod."                                              ,1,0,"C",1);
      $pdf->Cell(20,4,"Data inicial"                                      ,1,0,"C",1);
      $pdf->Cell(20,4,"Hora inicial"                                      ,1,0,"C",1);
      $pdf->Cell(20,4,"Data final"                                        ,1,0,"C",1);
      $pdf->Cell(20,4,"Hora Final"                                        ,1,0,"C",1);
      $pdf->Cell(15,4,"Tempo"                                             ,1,0,"C",1);
      $pdf->Cell(67,4,"Usuário"                                           ,1,0,"C",1);
      $pdf->Cell(15,4,"Tipo"                                              ,1,1,"C",1);

      $pdf->Cell(14,4,"C Arrec."                                          ,1,0,"C",1);
      $pdf->Cell(10,4,"Parc."                                             ,1,0,"C",1);
      $pdf->Cell(14,4,"C Divida."                                         ,1,0,"C",1);
      $pdf->Cell(18,4,"Vencimento"                                        ,1,0,"C",1);
      $pdf->Cell(18,4,"Procedência"                                       ,1,0,"C",1);
      $pdf->Cell(29,4,"Nome Contribuinte"                                 ,1,0,"C",1);
      $pdf->Cell(10,4,"Exerc"                                             ,1,0,"C",1);
      $pdf->Cell(13,4,"Origem"                                            ,1,0,"C",1);
      $pdf->Cell(13,4,"Vlr Hist."                                         ,1,0,"C",1);
      $pdf->Cell(13,4,"Vlr Cort."                                         ,1,0,"C",1);
      $pdf->Cell(13,4,"Vlr Jur."                                          ,1,0,"C",1);
      $pdf->Cell(13,4,"Vlr Mul."                                          ,1,0,"C",1);
      $pdf->Cell(14,4,"Total"                                             ,1,1,"C",1);

    }

    $pdf->SetFont($sLetra,'',5);
    $pdf->Cell(15,3,$aDadosImp['oDadosImp']->CodImp                         ,0,0,"C",0);
    $pdf->Cell(20,3,db_formatar($aDadosImp['oDadosImp']->dtIni,"d")         ,0,0,"C",0);
    $pdf->Cell(20,3,$aDadosImp['oDadosImp']->hrIni                          ,0,0,"C",0);
    $pdf->Cell(20,3,db_formatar($aDadosImp['oDadosImp']->dtFim,"d")         ,0,0,"C",0);
    $pdf->Cell(20,3,$aDadosImp['oDadosImp']->hrFim                          ,0,0,"C",0);
    $pdf->Cell(15,3,$aDadosImp['oDadosImp']->iTempo                         ,0,0,"C",0);
    $pdf->Cell(67,3,$sNome                                                  ,0,0,"R",0);

    if($aDadosImp['oDadosImp']->iTipo == 1){
      $pdf->Cell(15,3,"Parcial"                                           ,0,1,"C",0);
    } else if ($aDadosImp['oDadosImp']->iTipo == 2){
      $pdf->Cell(15,3,"Geral"                                             ,0,1,"C",0);
    } else {
      $pdf->Cell(15,3,"Inclusão Manual"                                   ,0,1,"C",0);
    }

    $nVlrTotalHist = 0;
    $nVlrTotalCort = 0;
    $nVlrTotalJur  = 0;
    $nVlrTotalMul  = 0;
    $nVlrTotal     = 0;

    $aVlrTotalHist = array();
    $aVlrTotalCort = array();
    $aVlrTotalJur  = array();
    $aVlrTotalMul  = array();
    $aVlrTotal     = array();

    foreach ( $aDadosImp['aListaDiv'] as $iInd => $oDadosDiv ) {

      if ($pdf->gety() > $pdf->h - 30  || $lImprime  ){

        $lImprime = false;
        $pdf->addpage();

        $pdf->SetFont($sLetra,'B',8);
        $pdf->ln(0);
        $pdf->Cell(190,5,"Importação da Divida Ativa",0,1,"C",0);

        $pdf->ln(1);
        $pdf->SetFont($sLetra,'B',6);
        $pdf->Cell(15,4,"Cod."                                              ,1,0,"C",1);
        $pdf->Cell(20,4,"Data inicial"                                      ,1,0,"C",1);
        $pdf->Cell(20,4,"Hora inicial"                                      ,1,0,"C",1);
        $pdf->Cell(20,4,"Data final"                                        ,1,0,"C",1);
        $pdf->Cell(20,4,"Hora Final"                                        ,1,0,"C",1);
        $pdf->Cell(15,4,"Tempo"                                             ,1,0,"C",1);
        $pdf->Cell(67,4,"Usuário"                                           ,1,0,"C",1);
        $pdf->Cell(15,4,"Tipo"                                              ,1,1,"C",1);

        $pdf->Cell(14,4,"C Arrec."                                          ,1,0,"C",1);
        $pdf->Cell(10,4,"Parc."                                             ,1,0,"C",1);
        $pdf->Cell(14,4,"C Divida."                                         ,1,0,"C",1);
        $pdf->Cell(18,4,"Vencimento"                                        ,1,0,"C",1);
        $pdf->Cell(18,4,"Procedência"                                       ,1,0,"C",1);
        $pdf->Cell(29,4,"Nome Contribuinte"                                 ,1,0,"C",1);
        $pdf->Cell(10,4,"Exerc"                                             ,1,0,"C",1);
        $pdf->Cell(13,4,"Origem"                                            ,1,0,"C",1);
        $pdf->Cell(13,4,"Vlr Hist."                                         ,1,0,"C",1);
        $pdf->Cell(13,4,"Vlr Cort."                                         ,1,0,"C",1);
        $pdf->Cell(13,4,"Vlr Jur."                                          ,1,0,"C",1);
        $pdf->Cell(13,4,"Vlr Mul."                                          ,1,0,"C",1);
        $pdf->Cell(14,4,"Total"                                             ,1,1,"C",1);
      }

      $pdf->SetFont($sLetra,'',5);
      $sNomeContrib = substr($oDadosDiv->sNomeContrib,0,25);

      $pdf->Cell(14,3,$oDadosDiv->CArrec                                     ,0,0,"C",0);
      $pdf->Cell(10,3,$oDadosDiv->Parc                                       ,0,0,"C",0);
      $pdf->Cell(14,3,$oDadosDiv->CDivida                                    ,0,0,"C",0);
      $pdf->Cell(18,3,$oDadosDiv->Venc                                       ,0,0,"C",0);
      $pdf->Cell(18,3,$oDadosDiv->Trib." - ".$oDadosDiv->sDescr              ,0,0,"C",0);
      $pdf->Cell(29,3,$sNomeContrib                                          ,0,0,"L",0);
      $pdf->Cell(10,3,$oDadosDiv->iExec                                      ,0,0,"C",0);
      $pdf->Cell(13,3,$oDadosDiv->sOrigem                                    ,0,0,"L",0);

      $pdf->Cell(13,3,db_formatar($oDadosDiv->vlrHist,"f")                   ,0,0,"C",0);
      $pdf->Cell(13,3,db_formatar($oDadosDiv->vlrCort,"f")                   ,0,0,"C",0);
      $pdf->Cell(13,3,db_formatar($oDadosDiv->vlrJur,"f")                    ,0,0,"C",0);
      $pdf->Cell(13,3,db_formatar($oDadosDiv->vlrMul,"f")                    ,0,0,"C",0);
      $pdf->Cell(14,3,db_formatar($oDadosDiv->vlrTotal,"f")                  ,0,1,"C",0);

      $sIndex = $oDadosDiv->CArrec . '#' . $oDadosDiv->Parc;

      $aVlrTotalHist[$sIndex] = $oDadosDiv->vlrHist;
      $aVlrTotalCort[$sIndex] = $oDadosDiv->vlrCort;
      $aVlrTotalJur[$sIndex]  = $oDadosDiv->vlrJur;
      $aVlrTotalMul[$sIndex]  = $oDadosDiv->vlrMul;
      $aVlrTotal[$sIndex]     = $oDadosDiv->vlrTotal;

    }

    $aVlrGeralTotalHist[] = $aVlrTotalHist;
    $aVlrGeralTotalCort[] = $aVlrTotalCort;
    $aVlrGeralTotalJur[]  = $aVlrTotalJur;
    $aVlrGeralTotalMul[]  = $aVlrTotalMul;
    $aVlrGeralTotal[]     = $aVlrTotal;

    if (isset($oParametros->agrupar) && $oParametros->agrupar != 4) {

      $nVlrTotalHist = array_sum($aVlrTotalHist);
      $nVlrTotalCort = array_sum($aVlrTotalCort);
      $nVlrTotalJur  = array_sum($aVlrTotalJur);
      $nVlrTotalMul  = array_sum($aVlrTotalMul);
      $nVlrTotal     = array_sum($aVlrTotal);

      $aVlrGeralTotalHist[] = $nVlrTotalHist;
      $aVlrGeralTotalCort[] = $nVlrTotalCort;
      $aVlrGeralTotalJur[]  = $nVlrTotalJur;
      $aVlrGeralTotalMul[]  = $nVlrTotalMul;
      $aVlrGeralTotal[]     = $nVlrTotal;

      $pdf->Cell(192,2,""                                                    ,0,1,0,0);
      $pdf->Cell(192,0,""                                                    ,"T",1,0,0);
      $pdf->ln(0);
      $pdf->SetFont($sLetra,"B",5);
      $pdf->cell(126,5,'Total --->  '                                        ,0,0,"R",0);
      $pdf->SetFont($sLetra,"",5);
      $pdf->Cell(13,5,db_formatar($nVlrTotalHist,"f")                        ,0,0,"C",0);
      $pdf->SetFont($sLetra,"B",5);
      $pdf->Cell(13,5,db_formatar($nVlrTotalCort,"f")                        ,0,0,"C",0);
      $pdf->Cell(13,5,db_formatar($nVlrTotalJur,"f")                         ,0,0,"C",0);
      $pdf->Cell(13,5,db_formatar($nVlrTotalMul,"f")                         ,0,0,"C",0);
      $pdf->Cell(14,5,db_formatar($nVlrTotal,"f")                            ,0,1,"C",0);
    }

  }

  $nVlrGeralTotalHist = array_sum($aVlrGeralTotalHist);
  $nVlrGeralTotalCort = array_sum($aVlrGeralTotalCort);
  $nVlrGeralTotalJur  = array_sum($aVlrGeralTotalJur);
  $nVlrGeralTotalMul  = array_sum($aVlrGeralTotalMul);
  $nVlrGeralTotal     = array_sum($aVlrGeralTotal);

  $pdf->Cell(192,2,""                                                      ,0,1,0,0);
  $pdf->Cell(192,0,""                                                      ,"T",1,0,0);
  $pdf->ln(0);
  $pdf->SetFont($sLetra,"B",5);
  $pdf->cell(126,5,"Total Geral --->  "                                    ,0,0,"R",0);
  $pdf->SetFont($sLetra,"",5);
  $pdf->Cell(13,5,db_formatar($nVlrGeralTotalHist,"f")                     ,0,0,"C",0);
  $pdf->SetFont($sLetra,"B",5);
  $pdf->Cell(13,5,db_formatar($nVlrGeralTotalCort,"f")                     ,0,0,"C",0);
  $pdf->Cell(13,5,db_formatar($nVlrGeralTotalJur,"f")                      ,0,0,"C",0);
  $pdf->Cell(13,5,db_formatar($nVlrGeralTotalMul,"f")                      ,0,0,"C",0);
  $pdf->Cell(14,5,db_formatar($nVlrGeralTotal,"f")                         ,0,1,"C",0);

  $sPdfPathFile = "tmp/relImpDivAtiv".uniqid().".pdf";
  $pdf->Output($sPdfPathFile, false, true);
  $oRetorno->sPdfPathRelatorio = $sPdfPathFile;
}

echo $oJson->encode($oRetorno);

?>