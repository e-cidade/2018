<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once('libs/db_utils.php');
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");
require_once("classes/db_histcalc_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrebanco_classe.php");
require_once("classes/db_arrecant_classe.php");
require_once("classes/db_tabrec_classe.php");
require_once("classes/db_cgm_classe.php");

$oGet        = db_utils::postMemory($_GET);
$iInstit     = db_getsession("DB_instit");
//arreprescr k30_anulado is false

$clhistcalc  = new cl_histcalc;
$clarrecad   = new cl_arrecad;
$clarrebanco = new cl_arrebanco;
$clarrecant  = new cl_arrecant;
$cltabrec    = new cl_tabrec;
$clcgm       = new cl_cgm;

$clhistcalc->rotulo->label();
$clarrecad->rotulo->label();
$clarrebanco->rotulo->label();
$clarrecant->rotulo->label();
$cltabrec->rotulo->label();
$clcgm->rotulo->label();
$sSqlNumpreNumbanco  = "    select numpre,                                                                                            ";
$sSqlNumpreNumbanco .= "           numpar,                                                                                            ";
$sSqlNumpreNumbanco .= "           codreceita,                                                                                        ";
$sSqlNumpreNumbanco .= "           descrreceita,                                                                                      ";
$sSqlNumpreNumbanco .= "           numcgm,                                                                                            ";
$sSqlNumpreNumbanco .= "           nome,                                                                                              ";
$sSqlNumpreNumbanco .= "           dtoper,                                                                                            ";
$sSqlNumpreNumbanco .= "           codhist,                                                                                           ";
$sSqlNumpreNumbanco .= "           descrhist,                                                                                         ";
$sSqlNumpreNumbanco .= "           valor,                                                                                             ";
$sSqlNumpreNumbanco .= "           dtvenc,                                                                                            ";
$sSqlNumpreNumbanco .= "           totparc,                                                                                           ";
$sSqlNumpreNumbanco .= "           digitp,                                                                                            ";
$sSqlNumpreNumbanco .= "           codtipo,                                                                                           ";
$sSqlNumpreNumbanco .= "           descrtipo,                                                                                         ";
$sSqlNumpreNumbanco .= "           tipojm,                                                                                            ";
$sSqlNumpreNumbanco .= "           case                                                                                               ";
$sSqlNumpreNumbanco .= "             when arrejustreg.k28_sequencia is not null                                                       "; 
$sSqlNumpreNumbanco .= "               then 'Justificado, '||x.situacao                                                               ";
$sSqlNumpreNumbanco .= "             else x.situacao                                                                                  ";
$sSqlNumpreNumbanco .= "           end as movimentacao,                                                                               ";
$sSqlNumpreNumbanco .= "           codbanco,                                                                                          ";
$sSqlNumpreNumbanco .= "           codagencia,                                                                                        ";
$sSqlNumpreNumbanco .= "           numbanco,                                                                                          ";
$sSqlNumpreNumbanco .= "           numbancoant                                                                                        ";
$sSqlNumpreNumbanco .= "      from (  select arr.k00_numpre    as numpre,                                                             ";
$sSqlNumpreNumbanco .= "                     arr.k00_numpar    as numpar,                                                             ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_codigo     as codreceita,                                                     ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_descr      as descrreceita,                                                   ";
$sSqlNumpreNumbanco .= "                     cgm.z01_numcgm as numcgm,                                                                ";
$sSqlNumpreNumbanco .= "                     cgm.z01_nome as nome,                                                                    ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtoper    as dtoper,                                                             ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_codigo   as codhist,                                                        ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_descr    as descrhist,                                                      ";
$sSqlNumpreNumbanco .= "                     arr.k00_valor     as valor,                                                              ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtvenc    as dtvenc,                                                             ";
$sSqlNumpreNumbanco .= "                     arr.k00_numtot    as totparc,                                                            ";
$sSqlNumpreNumbanco .= "                     arr.k00_numdig    as digitp,                                                             ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_tipo     as codtipo,                                                        ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_descr    as descrtipo,                                                      ";
$sSqlNumpreNumbanco .= "                     ''                    as tipojm,                                                         ";
$sSqlNumpreNumbanco .= "                     'Aberto'              as situacao,                                                       ";   
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codbco as codbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codage as codagencia,                                                      ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_numbco as numbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_nbant  as numbancoant                                                      ";
$sSqlNumpreNumbanco .= "                from arrecad as arr                                                                           ";
$sSqlNumpreNumbanco .= "                     inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                          ";
$sSqlNumpreNumbanco .= "                     inner join arrenumcgm on arrenumcgm.k00_numpre = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                     inner join cgm        on cgm.z01_numcgm        = arrenumcgm.k00_numcgm                   ";
$sSqlNumpreNumbanco .= "                     inner join arretipo   on arretipo.k00_tipo     = arr.k00_tipo                            ";
$sSqlNumpreNumbanco .= "                     inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                            ";
$sSqlNumpreNumbanco .= "                     inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arreinstit.k00_instit = {$iInstit}                              ";
$sSqlNumpreNumbanco .= "                     left  join arrebanco  on arrebanco.k00_numpre  = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arrebanco.k00_numpar  = arr.k00_numpar                          ";
$sSqlNumpreNumbanco .= "               where arr.k00_numpre    = {$oGet->numpre}                                                      ";
$sSqlNumpreNumbanco .= "                 and arr.k00_numpar    = {$oGet->numpar}                                                      ";
if ( $oGet->receita != "undefined" )  {
  $sSqlNumpreNumbanco .= "                 and tabrec.k02_codigo = {$oGet->receita}                                                     ";
}
$sSqlNumpreNumbanco .= "               union all                                                                                      ";
$sSqlNumpreNumbanco .= "              select arr.k00_numpre   as numpre,                                                              ";
$sSqlNumpreNumbanco .= "                     arr.k00_numpar   as numpar,                                                              ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_codigo     as codreceita,                                                     ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_descr      as descrreceita,                                                   ";
$sSqlNumpreNumbanco .= "                     cgm.z01_numcgm as numcgm,                                                                ";
$sSqlNumpreNumbanco .= "                     cgm.z01_nome as nome,                                                                    ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtoper   as dtoper,                                                              ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_codigo   as codhist,                                                        ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_descr    as descrhist,                                                      ";
$sSqlNumpreNumbanco .= "                     arr.k00_valor    as valor,                                                               ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtvenc   as dtvenc,                                                              ";
$sSqlNumpreNumbanco .= "                     arr.k00_numtot   as totparc,                                                             ";
$sSqlNumpreNumbanco .= "                     arr.k00_numdig   as digitp,                                                              ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_tipo     as codtipo,                                                        ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_descr    as descrtipo,                                                      ";
$sSqlNumpreNumbanco .= "                     ''                    as tipojm,                                                         ";
$sSqlNumpreNumbanco .= "                     'Pago'                as situacao,                                                       ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codbco as codbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codage as codagencia,                                                      ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_numbco as numbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_nbant  as numbancoant                                                      ";
$sSqlNumpreNumbanco .= "                from arrepaga as arr                                                                          ";
$sSqlNumpreNumbanco .= "                     inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                          ";
$sSqlNumpreNumbanco .= "                     inner join arrenumcgm on arrenumcgm.k00_numpre = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                     inner join cgm        on cgm.z01_numcgm        = arrenumcgm.k00_numcgm                   ";
$sSqlNumpreNumbanco .= "                     inner join arrecant   on arrecant.k00_numpre   = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arrecant.k00_numpar   = arr.k00_numpar                          ";
$sSqlNumpreNumbanco .= "                     inner join arretipo   on arretipo.k00_tipo     = arrecant.k00_tipo                       ";
$sSqlNumpreNumbanco .= "                     inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                            ";
$sSqlNumpreNumbanco .= "                     inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arreinstit.k00_instit = {$iInstit}                              ";
$sSqlNumpreNumbanco .= "                     left  join arrebanco  on arrebanco.k00_numpre  = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arrebanco.k00_numpar  = arr.k00_numpar                          ";
$sSqlNumpreNumbanco .= "               where arr.k00_numpre    = {$oGet->numpre}                                                      ";
$sSqlNumpreNumbanco .= "                 and arr.k00_numpar    = {$oGet->numpar}                                                      ";
if ( $oGet->receita != "undefined" )  {
  $sSqlNumpreNumbanco .= "                 and tabrec.k02_codigo = {$oGet->receita}                                                     ";
}
$sSqlNumpreNumbanco .= "               union all                                                                                      ";
$sSqlNumpreNumbanco .= "              select arr.k00_numpre   as numpre,                                                              ";
$sSqlNumpreNumbanco .= "                     arr.k00_numpar   as numpar,                                                              ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_codigo     as codreceita,                                                     ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_descr      as descrreceita,                                                   ";
$sSqlNumpreNumbanco .= "                     cgm.z01_numcgm as numcgm,                                                                ";
$sSqlNumpreNumbanco .= "                     cgm.z01_nome as nome,                                                                    ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtoper   as dtoper,                                                              ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_codigo   as codhist,                                                        ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_descr    as descrhist,                                                      ";
$sSqlNumpreNumbanco .= "                     arr.k00_valor    as valor,                                                               ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtvenc   as dtvenc,                                                              ";
$sSqlNumpreNumbanco .= "                     arr.k00_numtot   as totparc,                                                             ";
$sSqlNumpreNumbanco .= "                     arr.k00_numdig   as digitp,                                                              ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_tipo     as codtipo,                                                        ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_descr    as descrtipo,                                                      ";
$sSqlNumpreNumbanco .= "                     ''                    as tipojm,                                                         ";
$sSqlNumpreNumbanco .= "                     'Cancelado'           as situacao,                                                       ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codbco as codbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codage as codagencia,                                                      ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_numbco as numbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_nbant  as numbancoant                                                      ";
$sSqlNumpreNumbanco .= "                from arrecant as arr                                                                          ";
$sSqlNumpreNumbanco .= "                     inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                          ";
$sSqlNumpreNumbanco .= "                     inner join arrenumcgm on arrenumcgm.k00_numpre = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                     inner join cgm        on cgm.z01_numcgm        = arrenumcgm.k00_numcgm                   ";
$sSqlNumpreNumbanco .= "                     inner join arretipo   on arretipo.k00_tipo     = arr.k00_tipo                            ";
$sSqlNumpreNumbanco .= "                     inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                            ";
$sSqlNumpreNumbanco .= "                     inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arreinstit.k00_instit = {$iInstit}                              ";
$sSqlNumpreNumbanco .= "                     left  join arrebanco  on arrebanco.k00_numpre  = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arrebanco.k00_numpar  = arr.k00_numpar                          ";
$sSqlNumpreNumbanco .= "                     left  join arrepaga   on arrepaga.k00_numpre   = arr.k00_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arrepaga.k00_numpar   = arr.k00_numpar                          ";
$sSqlNumpreNumbanco .= "               where arrepaga.k00_numpre is null                                                              ";
$sSqlNumpreNumbanco .= "                 and arr.k00_numpre    = {$oGet->numpre}                                                      ";
$sSqlNumpreNumbanco .= "                 and arr.k00_numpar    = {$oGet->numpar}                                                      ";
if ( $oGet->receita != "undefined" )  {
  $sSqlNumpreNumbanco .= "                 and tabrec.k02_codigo = {$oGet->receita}                                                     ";
}
$sSqlNumpreNumbanco .= "               union all                                                                                      ";
$sSqlNumpreNumbanco .= "              select arr.k30_numpre  as numpre,                                                               ";
$sSqlNumpreNumbanco .= "                     arr.k30_numpar  as numpar,                                                               ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_codigo      as codreceita,                                                    ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_descr       as descrreceita,                                                  ";
$sSqlNumpreNumbanco .= "                     cgm.z01_numcgm as numcgm,                                                                ";
$sSqlNumpreNumbanco .= "                     cgm.z01_nome as nome,                                                                    ";
$sSqlNumpreNumbanco .= "                     arr.k30_dtoper  as dtoper,                                                               ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_codigo    as codhist,                                                       ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_descr     as descrhist,                                                     ";
$sSqlNumpreNumbanco .= "                     arr.k30_valor   as valor,                                                                ";
$sSqlNumpreNumbanco .= "                     arr.k30_dtvenc  as dtvenc,                                                               ";
$sSqlNumpreNumbanco .= "                     arr.k30_numtot  as totparc,                                                              ";
$sSqlNumpreNumbanco .= "                     arr.k30_numdig  as digitp,                                                               ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_tipo      as codtipo,                                                       ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_descr     as descrtipo,                                                     ";
$sSqlNumpreNumbanco .= "                     ''                     as tipojm,                                                        ";
$sSqlNumpreNumbanco .= "                     'Prescrito'            as situacao,                                                      ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codbco as codbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codage as codagencia,                                                      ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_numbco as numbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_nbant  as numbancoant                                                      ";
$sSqlNumpreNumbanco .= "                from arreprescr as arr                                                                        ";
$sSqlNumpreNumbanco .= "                     inner join tabrec     on tabrec.k02_codigo     = arr.k30_receit                          ";
$sSqlNumpreNumbanco .= "                     inner join arrenumcgm on arrenumcgm.k00_numpre = arr.k30_numpre                          ";
$sSqlNumpreNumbanco .= "                     inner join cgm        on cgm.z01_numcgm        = arrenumcgm.k00_numcgm                   ";
$sSqlNumpreNumbanco .= "                     inner join arretipo   on arretipo.k00_tipo     = arr.k30_tipo                            ";
$sSqlNumpreNumbanco .= "                     inner join histcalc   on histcalc.k01_codigo   = arr.k30_hist                            ";
$sSqlNumpreNumbanco .= "                     inner join arreinstit on arreinstit.k00_numpre = arr.k30_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arreinstit.k00_instit = {$iInstit}                              ";
$sSqlNumpreNumbanco .= "                     left  join arrebanco  on arrebanco.k00_numpre  = arr.k30_numpre                          ";
$sSqlNumpreNumbanco .= "                                          and arrebanco.k00_numpar  = arr.k30_numpar                          ";
$sSqlNumpreNumbanco .= "               where arr.k30_numpre    = {$oGet->numpre}                                                      ";
$sSqlNumpreNumbanco .= "                 and arr.k30_numpar    = {$oGet->numpar}                                                      ";
$sSqlNumpreNumbanco .= "                 and arr.k30_anulado is false                                                                 ";
if ( $oGet->receita != "undefined" )  {
  $sSqlNumpreNumbanco .= "                 and tabrec.k02_codigo = {$oGet->receita}                                                     ";
}
$sSqlNumpreNumbanco .= "               union all                                                                                      ";
$sSqlNumpreNumbanco .= "              select arr.k00_numpre    as numpre,                                                             ";
$sSqlNumpreNumbanco .= "                     arr.k00_numpar    as numpar,                                                             ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_codigo     as codreceita,                                                     ";
$sSqlNumpreNumbanco .= "                     tabrec.k02_descr      as descrreceita,                                                   ";
$sSqlNumpreNumbanco .= "                     cgm.z01_numcgm as numcgm,                                                                ";
$sSqlNumpreNumbanco .= "                     cgm.z01_nome as nome,                                                                    ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtoper    as dtoper,                                                             ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_codigo   as codhist,                                                        ";
$sSqlNumpreNumbanco .= "                     histcalc.k01_descr    as descrhist,                                                      ";
$sSqlNumpreNumbanco .= "                     arr.k00_valor     as valor,                                                              ";
$sSqlNumpreNumbanco .= "                     arr.k00_dtvenc    as dtvenc,                                                             ";
$sSqlNumpreNumbanco .= "                     arr.k00_numtot    as totparc,                                                            ";
$sSqlNumpreNumbanco .= "                     arr.k00_numdig    as digitp,                                                             ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_tipo     as codtipo,                                                        ";
$sSqlNumpreNumbanco .= "                     arretipo.k00_descr    as descrtipo,                                                      ";
$sSqlNumpreNumbanco .= "                     ''                    as tipojm,                                                         ";
$sSqlNumpreNumbanco .= "                     case                                                                                     ";
$sSqlNumpreNumbanco .= "                       when divold.k10_sequencial is not null                                                 ";
$sSqlNumpreNumbanco .= "                         then 'Importado'                                                                     "; 
$sSqlNumpreNumbanco .= "                       when termoreparc.v08_sequencial is not null                                            ";
$sSqlNumpreNumbanco .= "                         then 'Reparcelado'                                                                   ";
$sSqlNumpreNumbanco .= "                       else 'Parcelado'                                                                       ";
$sSqlNumpreNumbanco .= "                     end as situacao,                                                                         ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codbco as codbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_codage as codagencia,                                                      ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_numbco as numbanco,                                                        ";
$sSqlNumpreNumbanco .= "                     arrebanco.k00_nbant  as numbancoant                                                      ";
$sSqlNumpreNumbanco .= "                from arreold as arr                                                                           ";
$sSqlNumpreNumbanco .= "                     inner join tabrec        on tabrec.k02_codigo            = arr.k00_receit                ";
$sSqlNumpreNumbanco .= "                     inner join arrenumcgm    on arrenumcgm.k00_numpre        = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                     inner join cgm           on cgm.z01_numcgm               = arrenumcgm.k00_numcgm         ";
$sSqlNumpreNumbanco .= "                     inner join arretipo      on arretipo.k00_tipo            = arr.k00_tipo                  ";
$sSqlNumpreNumbanco .= "                     inner join histcalc      on histcalc.k01_codigo          = arr.k00_hist                  ";
$sSqlNumpreNumbanco .= "                     inner join arreinstit    on arreinstit.k00_numpre        = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                                             and arreinstit.k00_instit        = {$iInstit}                    ";
$sSqlNumpreNumbanco .= "                     left  join inicialnumpre on inicialnumpre.v59_numpre     = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                     left  join termoini      on termoini.inicial             = inicialnumpre.v59_inicial     ";
$sSqlNumpreNumbanco .= "                     left  join divida        on divida.v01_numpre            = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                                             and divida.v01_numpar            = arr.k00_numpar                ";
$sSqlNumpreNumbanco .= "                     left  join termodiv      on termodiv.coddiv              = divida.v01_coddiv             ";                           
$sSqlNumpreNumbanco .= "                     left  join arrecant      on arrecant.k00_numpre          = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                                             and arrecant.k00_numpar          = arr.k00_numpar                ";
$sSqlNumpreNumbanco .= "                     left  join termo         on termo.v07_numpre             = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                     left  join termoreparc   on termoreparc.v08_parcelorigem = termo.v07_parcel              ";            
$sSqlNumpreNumbanco .= "                     left  join divold        on divold.k10_numpre            = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                                             and divold.k10_numpar            = arr.k00_numpar                ";
$sSqlNumpreNumbanco .= "                                             and divold.k10_receita           = tabrec.k02_codigo             ";
$sSqlNumpreNumbanco .= "                     left  join arrebanco     on arrebanco.k00_numpre         = arr.k00_numpre                ";
$sSqlNumpreNumbanco .= "                                             and arrebanco.k00_numpar         = arr.k00_numpar                ";
$sSqlNumpreNumbanco .= "               where arrecant.k00_numpre is null                                                              ";
$sSqlNumpreNumbanco .= "                 and ( termodiv.coddiv  is not null                                                           ";
$sSqlNumpreNumbanco .= "                    or termoini.inicial is not null                                                           ";
$sSqlNumpreNumbanco .= "                    or termoreparc.v08_sequencial is not null                                                 ";
$sSqlNumpreNumbanco .= "                    or divold.k10_sequencial is not null )                                                    ";
$sSqlNumpreNumbanco .= "                 and arr.k00_numpre    = {$oGet->numpre}                                                      ";
$sSqlNumpreNumbanco .= "                 and arr.k00_numpar    = {$oGet->numpar}                                                      ";
if ( $oGet->receita != "undefined" )  {
  $sSqlNumpreNumbanco .= "                 and tabrec.k02_codigo = {$oGet->receita}                                                     ";
}
$sSqlNumpreNumbanco .= "           ) as x                                                                                             ";
$sSqlNumpreNumbanco .= "             left join arrejustreg  on arrejustreg.k28_numpre  = x.numpre                                     ";
$sSqlNumpreNumbanco .= "                                   and arrejustreg.k28_numpar  = x.numpar                                     ";
$sSqlNumpreNumbanco .= "                                   and arrejustreg.k28_receita = x.codreceita                                 ";

//die( $sSqlNumpreNumbanco );

$rsNumpreNumbanco = db_query($sSqlNumpreNumbanco);
$iNumRows         = pg_num_rows($rsNumpreNumbanco);
if ($iNumRows > 0) {
	$oNumpreNumBanco = db_utils::fieldsMemory($rsNumpreNumbanco,0);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("classes/infoLancamentoContabil.classe.js, widgets/messageboard.widget.js");
  db_app::load("estilos.css, grid.style.css, tab.style.css");
?>
<style>
.fieldsetCabecalho table td {
  white-space: nowrap;
}

.labels {
 width: 150px;
}

.valorNum {
  width: 80px;
  text-align: right;
  background-color:#FFFFFF
}

.valoresDescr {
  width: 400px;
  background-color:#FFFFFF
}     
</style>
</head>
<body bgcolor="#CCCCCC">
<form name='form1'>
  <fieldset class="fieldsetCabecalho">
    <legend><b>Dados Consulta</b></legend>
      <table border="0" cellspacing="1">
        <tr> 
          <td align="left" title="<?=$Tk00_numpre;?>" class="labels"><?=$Lk00_numpre;?></td>
          <td align="left" class="valoresDescr"><?=$oNumpreNumBanco->numpre;?></td>
          
          <td align="left" title="<?=$Tk02_codigo;?>" class="labels"><?=$Lk02_codigo;?></td>
          <td align="left" class="valorNum"><?=$oNumpreNumBanco->codreceita;?></td>
          <td align="left" class="valoresDescr"><?=$oNumpreNumBanco->descrreceita;?></td>
        </tr>
        <tr> 
          <td align="left" title="<?=$Tk00_numpar;?>" class="labels"><?=$Lk00_numpar;?></td>
          <td align="left" class="valoresDescr"><?=$oNumpreNumBanco->numpar;?></td>
          <td align="left" title="<?=$Tz01_numcgm;?>" class="labels"><?=$Lz01_numcgm;?></td>
          <td align="left" class="valorNum"><?=$oNumpreNumBanco->numcgm;?></td>
          <td align="left" class="valoresDescr"><?=$oNumpreNumBanco->nome;?></td>
        </tr>
        <tr> 
          <td align="left" title="<?=$Tk00_dtoper;?>" class="labels"><b>Data de Operação:</b></td>
          <td align="left" class="valoresDescr"><?=db_formatar($oNumpreNumBanco->dtoper,'d');?></td>
          <td align="left" title="<?=$Tk01_codigo;?>" class="labels"><b>Histórico:</b></td>
          <td align="left" class="valorNum"><?=$oNumpreNumBanco->codhist;?></td>
          <td align="left" class="valoresDescr"><?=$oNumpreNumBanco->descrhist;?></td>
        </tr>
        <tr> 
          <td align="left" title="<?=$Tk00_valor;?>" class="labels"><b>Valor:</b></td>
          <td align="left" class="valoresDescr"><?=db_formatar($oNumpreNumBanco->valor,'p');?></td>
          <td align="left" title="<?=$Tk00_tipo;?>" class="labels"><?=$Lk00_tipo;?></td>
          <td align="left" class="valorNum"><?=$oNumpreNumBanco->codtipo;?></td>
          <td align="left" class="valoresDescr"><?=$oNumpreNumBanco->descrtipo;?></td>
        </tr>
        <tr> 
          <td align="left" title="<?=$Tk00_dtvenc;?>" class="labels"><b>Vencimento:</b></td>
          <td align="left" class="valoresDescr"><?=db_formatar($oNumpreNumBanco->dtvenc,'d');?></td>
          <td align="left" title="<?=$Tk00_numdig;?>" class="labels"><?=$Lk00_numdig;?></td>
          <td align="left" colspan="2" class="valoresDescr"><?=$oNumpreNumBanco->digitp;?></td>
        </tr>
        <tr> 
          <td align="left" title="<?=$Tk00_numtot;?>" class="labels"><?=$Lk00_numtot;?></td>
          <td align="left" class="valoresDescr"><?=$oNumpreNumBanco->totparc;?></td>
          <td align="left" title="" class="labels"><b>Tipo de Juro e Multa:</b></td>
          <td align="left" colspan="2" class="valorNum"><?=$oNumpreNumBanco->tipojm;?></td>
        </tr>
      </table>
  </fieldset> 
  <fieldset style='padding-left:0px'>
    <legend><b>Detalhamento</b></legend>
		<?
		  $oTabDetalhes = new verticalTab("detalhesnumprenumbanco",300);
		  $oTabDetalhes->add("composicao", "Composição",
		                     "func_numprenumbanco002.php?numpar={$oGet->numpar}&numpre={$oGet->numpre}&receita={$oGet->receita}");
		  $oTabDetalhes->show();
		?>
  </fieldset>
</form>
</body>
</html>