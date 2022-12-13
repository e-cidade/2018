<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlib.php");

$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_ender');
$clrotulo->label('z01_compl');
$clrotulo->label('z01_bairro');
$clrotulo->label('z01_munic');
$clrotulo->label('v01_exerc');
$clrotulo->label('v01_proced');
$clrotulo->label('v03_descr');

//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_SERVER_VARS);

$instit = db_getsession("DB_instit");


$head2 = 'POSIÇÃO DE DIVIDA POR EXERCÍCIO/PROCEDÊNCIA';
$head5 = '';
$head6 = '';

$agrupa1 = '';
$agrupa2 = '';

if(isset($exerc)){
  $exercicios    = ' and v01_exerc in ('.str_replace("-",",",$exerc).') ';
  $selexercicios = ' and v01_exerc > '.str_replace("-",",",$exerc).' ';
  $anos          = str_replace("-",",",$exerc);
  $head4         = 'Exercicíos Selecionados: '.$anos;
}else{
  $exercicios = '';
}

// Liga Debug
$DB_DEBUG = false;

//$xdata = ;
$sqlTermoini = ""; 

$head5 = "Cáculo na data :".db_formatar($xdata,'d');

$sqlParcelamentosCorrigidos  = " create temp table w_parcelamentos_corrigidos as                       \n";
$sqlParcelamentosCorrigidos .= " select v07_parcel, v07_numpre,                                        \n";
if ($tiporel == "c") {
  $sqlParcelamentosCorrigidos .= " k22_numcgm as k00_numcgm,                                           \n"; 
}
$sqlParcelamentosCorrigidos .= "         k00_descr,                                                    \n"; 
$sqlParcelamentosCorrigidos .= "         round(sum(k22_vlrhis ),2) as k22_vlrhis,                      \n"; 
$sqlParcelamentosCorrigidos .= "         round(sum(k22_vlrcor ),2) as k22_vlrcor,                      \n"; 
$sqlParcelamentosCorrigidos .= "         round(sum(k22_juros  ),2) as k22_juros,                       \n"; 
$sqlParcelamentosCorrigidos .= "         round(sum(k22_multa  ),2) as k22_multa,                       \n"; 
$sqlParcelamentosCorrigidos .= "         round(sum(valor_total),2) as valor_total                      \n"; 
$sqlParcelamentosCorrigidos .= "    from (                                                             \n";
$sqlParcelamentosCorrigidos .= "           select v07_parcel, v07_numpre,                              \n";

if ($tiporel == "c") {
  $sqlParcelamentosCorrigidos .= "           k22_numcgm,                                               \n";
}

$sqlParcelamentosCorrigidos .= "                  k00_descr as k00_descr,                              \n";
$sqlParcelamentosCorrigidos .= "                  k22_vlrhis as k22_vlrhis,                            \n";
$sqlParcelamentosCorrigidos .= "                  k22_vlrcor as k22_vlrcor,                            \n";
$sqlParcelamentosCorrigidos .= "                  k22_juros as k22_juros,                              \n";
$sqlParcelamentosCorrigidos .= "                  k22_multa as k22_multa,                              \n";
$sqlParcelamentosCorrigidos .= "                  ( k22_vlrcor+k22_juros+k22_multa ) as valor_total    \n";
$sqlParcelamentosCorrigidos .= "              from termo                                               \n";
$sqlParcelamentosCorrigidos .= "                   inner join debitos   on k22_data   = '$xdata'       \n";
$sqlParcelamentosCorrigidos .= "                                       and k22_numpre = v07_numpre     \n";
$sqlParcelamentosCorrigidos .= "                                       and k22_instit = $instit        \n";
$sqlParcelamentosCorrigidos .= "                   inner join arretipo  on k00_tipo   = k22_tipo       \n";
$sqlParcelamentosCorrigidos .= "             where v07_instit = ".db_getsession('DB_instit')."         \n";
$sqlParcelamentosCorrigidos .= "               and k22_data   = '$xdata'                               \n";
//$sqlParcelamentosCorrigidos .= "               and k03_tipo not in (15, 18) ";
$sqlParcelamentosCorrigidos .= "        ) as parcelamentos                                             \n";
$sqlParcelamentosCorrigidos .= "  group by v07_parcel, v07_numpre,                                     \n";
if ($tiporel == "c") {
  $sqlParcelamentosCorrigidos .= "  k22_numcgm,                                                        \n";
}
$sqlParcelamentosCorrigidos .= "           k00_descr                                                    ";

if(!$DB_DEBUG) {
  db_query($sqlParcelamentosCorrigidos); // cria tabela temporaria 
  db_query("create index w_parcelamentos_corrigidos_1_in on w_parcelamentos_corrigidos(v07_parcel)");
} else {
  $sqlParcelamentosCorrigidos .= ";<br><br>create index w_parcelamentos_corrigidos_1_in on w_parcelamentos_corrigidos(v07_parcel);<br><br>";
}

// DIVIDAS DA CERTIDAO
// certidoes de parcelamento de divida
$sqlCertidaoDividas  = "create temp table w_certidao_dividas as                                                                               \n";
$sqlCertidaoDividas .= "  select 1 as tipo,                                                                                                   \n";
$sqlCertidaoDividas .= "         'CERTIDÃO DE PARCELAMENTO DE DIVIDA' as tipo_certidao,                                                       \n";
$sqlCertidaoDividas .= "         certter.v14_certid as certidao,                                                                              \n";
$sqlCertidaoDividas .= "         certter.v14_parcel as parcel,                                                                                \n";
$sqlCertidaoDividas .= "         certter.v14_parcel as parcelori,                                                                             \n";
$sqlCertidaoDividas .= "         0 as inicial,                                                                                                \n";
$sqlCertidaoDividas .= "         divida.*                                                                                                     \n";

if ($tiporel == "c") {

  $sqlCertidaoDividas .= "       ,(select k00_numcgm                                                                                          \n";
  $sqlCertidaoDividas .= "           from arrenumcgm                                                                                          \n";
  $sqlCertidaoDividas .= "          where k00_numpre = divida.v01_numpre                                                                      \n";
  $sqlCertidaoDividas .= "          limit 1) as k00_numcgm                                                                                    \n";
}

$sqlCertidaoDividas .= "    from certter                                                                                                      \n"; 
$sqlCertidaoDividas .= "         inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel       = certter.v14_parcel            \n";
$sqlCertidaoDividas .= "         inner join termodiv                              on termodiv.parcel          = certter.v14_parcel            \n";
$sqlCertidaoDividas .= "         inner join divida                                on divida.v01_coddiv        = termodiv.coddiv               \n";
$sqlCertidaoDividas .= "                                                         and divida.v01_instit        = ".db_getsession('DB_instit')."\n";
$sqlCertidaoDividas .= "         left  join inicialcert   on inicialcert.v51_certidao = certter.v14_certid                                    \n";
$sqlCertidaoDividas .= "   where certter.v14_certid is null                                                                                   \n";
                         
$sqlCertidaoDividas .= "   union all                                                                                                          \n";

// certidoes de parcelamento de inicial
$sqlCertidaoDividas .= "  select 2 as tipo,                                                                                                   \n";
$sqlCertidaoDividas .= "         'PARCELAMENTO DE INICIAL DE CERTIDAO DO PARCELAMENTO' as tipo_certidao,                                      \n";
$sqlCertidaoDividas .= "         certter.v14_certid as certidao,                                                                              \n";
$sqlCertidaoDividas .= "         debitos.v07_parcel as parcel,                                                                                \n";
$sqlCertidaoDividas .= "         certter.v14_parcel as parcelori,                                                                             \n";
$sqlCertidaoDividas .= "         inicialcert.v51_inicial as inicial,                                                                          \n";
$sqlCertidaoDividas .= "         divida.*                                                                                                     \n";

if ($tiporel == "c") {

  $sqlCertidaoDividas .= "        ,(select k00_numcgm                                                                                         \n";
  $sqlCertidaoDividas .= "            from arrenumcgm                                                                                         \n";
  $sqlCertidaoDividas .= "           where k00_numpre = divida.v01_numpre                                                                     \n"; 
  $sqlCertidaoDividas .= "           limit 1) as k00_numcgm                                                                                   \n";
}

$sqlCertidaoDividas .= "    from termoini                                                                                                     \n"; 
$sqlCertidaoDividas .= "         inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel      = termoini.parcel                \n";
$sqlCertidaoDividas .= "         inner join inicialcert                           on inicialcert.v51_inicial = termoini.inicial               \n";
$sqlCertidaoDividas .= "         inner join certter                               on certter.v14_certid      = inicialcert.v51_certidao       \n";
$sqlCertidaoDividas .= "         inner join termodiv                              on termodiv.parcel         = certter.v14_parcel             \n";
$sqlCertidaoDividas .= "         inner join divida                                on divida.v01_coddiv       = termodiv.coddiv                \n";
$sqlCertidaoDividas .= "                                                         and divida.v01_instit       = ".db_getsession('DB_instit')." \n";

$sqlCertidaoDividas .= "   union all \n";

//
$sqlCertidaoDividas .= "  select 3 as tipo,                                                                                                   \n"; 
$sqlCertidaoDividas .= "         'PARCELAMENTO DE INICIAL DE CERTIDAO DE DIVIDA' as tipo_certidao,                                            \n";
$sqlCertidaoDividas .= "         certdiv.v14_certid as certidao,                                                                              \n";
$sqlCertidaoDividas .= "         termoini.parcel as parcel,                                                                                   \n";
$sqlCertidaoDividas .= "         termoini.parcel as parcelori,                                                                                \n";
$sqlCertidaoDividas .= "         inicialcert.v51_inicial as inicial,                                                                          \n";
$sqlCertidaoDividas .= "         divida.*                                                                                                     \n";

if ($tiporel == "c") {

  $sqlCertidaoDividas .= "        , (select k00_numcgm                                                                                        \n"; 
  $sqlCertidaoDividas .= "             from arrenumcgm                                                                                        \n";
  $sqlCertidaoDividas .= "            where k00_numpre = divida.v01_numpre                                                                    \n";
  $sqlCertidaoDividas .= "            limit 1) as k00_numcgm                                                                                  \n";
}

$sqlCertidaoDividas .= "    from termoini                                                                                                     \n"; 
$sqlCertidaoDividas .= "         inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel      = termoini.parcel                \n";
$sqlCertidaoDividas .= "         inner join inicialcert                           on inicialcert.v51_inicial = termoini.inicial               \n";
$sqlCertidaoDividas .= "         inner join certdiv                               on certdiv.v14_certid      = inicialcert.v51_certidao       \n";
$sqlCertidaoDividas .= "         inner join divida                                on divida.v01_coddiv       = certdiv.v14_coddiv             \n";
$sqlCertidaoDividas .= "                                                         and divida.v01_instit       = ".db_getsession('DB_instit')." \n";

$sqlCertidaoDividas .= "   union all                                                                                                          \n";

$sqlCertidaoDividas .= "  select 4 as tipo, 'CERTIDÃO DO FORO' as tipo_certidao,                                                              \n";
$sqlCertidaoDividas .= "         certdiv.v14_certid as certidao,                                                                              \n";
$sqlCertidaoDividas .= "         0 as parcel,                                                                                                 \n";
$sqlCertidaoDividas .= "         0 as parcelori,                                                                                              \n";
$sqlCertidaoDividas .= "         inicialcert.v51_inicial as inicial,                                                                          \n";
$sqlCertidaoDividas .= "         divida.*                                                                                                     \n";

if ($tiporel == "c") {

  $sqlCertidaoDividas .= "         , (select k00_numcgm                                                                                       \n";
  $sqlCertidaoDividas .= "              from arrenumcgm                                                                                       \n";
  $sqlCertidaoDividas .= "             where k00_numpre = divida.v01_numpre                                                                   \n";
  $sqlCertidaoDividas .= "             limit 1) as k00_numcgm                                                                                 \n";
}

$sqlCertidaoDividas .= "    from inicialcert                                                                                                  \n";
$sqlCertidaoDividas .= "         left  join termoini      on termoini.inicial        = inicialcert.v51_inicial                                \n";
$sqlCertidaoDividas .= "         inner join certdiv       on certdiv.v14_certid      = inicialcert.v51_certidao                               \n";
$sqlCertidaoDividas .= "         inner join divida        on divida.v01_coddiv       = certdiv.v14_coddiv                                     \n";
$sqlCertidaoDividas .= "                                 and divida.v01_instit       = ".db_getsession('DB_instit')."                         \n";
$sqlCertidaoDividas .= "   where termoini.inicial is null                                                                                     \n";
$sqlCertidaoDividas .= "   union all                                                                                                          \n";

// certidoes de divida normal 
$sqlCertidaoDividas .= "  select 5 as tipo,                                                                                                   \n";
$sqlCertidaoDividas .= "         'CERTIDÃO DE DIVIDA' as tipo_certidao,                                                                       \n";
$sqlCertidaoDividas .= "         certid.v13_certid as certidao,                                                                               \n";
$sqlCertidaoDividas .= "         0 as parcel,                                                                                                 \n";
$sqlCertidaoDividas .= "         0 as parcelori,                                                                                              \n";
$sqlCertidaoDividas .= "         0 as inicial,                                                                                                \n";
$sqlCertidaoDividas .= "         divida.*                                                                                                     \n";

if ($tiporel == "c") {

  $sqlCertidaoDividas .= "       ,(select k00_numcgm                                                                                          \n";
  $sqlCertidaoDividas .= "           from arrenumcgm                                                                                          \n";
  $sqlCertidaoDividas .= "          where k00_numpre = divida.v01_numpre                                                                      \n";
  $sqlCertidaoDividas .= "          limit 1) as k00_numcgm                                                                                    \n";
}

$sqlCertidaoDividas .= "    from certid                                                                                                       \n"; 
$sqlCertidaoDividas .= "         inner join certdiv       on certdiv.v14_certid      = certid.v13_certid                                      \n";
$sqlCertidaoDividas .= "         inner join divida        on divida.v01_coddiv       = certdiv.v14_coddiv                                     \n";
$sqlCertidaoDividas .= "                                 and divida.v01_instit       = ".db_getsession('DB_instit')."                         \n";
$sqlCertidaoDividas .= "         left  join inicialcert   on certdiv.v14_certid      = inicialcert.v51_certidao                               \n";
$sqlCertidaoDividas .= "   where inicialcert.v51_certidao is null                                                                             \n"; 
$sqlCertidaoDividas .= "     and certid.v13_instit = ".db_getsession('DB_instit')."                                                           \n"; 

$sqlIndicesCertidoes1 = " create index w_certidao_dividas_1_in on w_certidao_dividas(inicial); ";
$sqlIndicesCertidoes2 = " create index w_certidao_dividas_2_in on w_certidao_dividas(v01_numpre, v01_numpar);";
if(!$DB_DEBUG) {
  db_query($sqlCertidaoDividas); // cria tabela temporaria
  db_query($sqlIndicesCertidoes1); // cria indice
  db_query($sqlIndicesCertidoes2); // cria indice
} else {
  $sqlCertidaoDividas .= "<br>$sqlIndicesCertidoes1<br>$sqlIndicesCertidoes2<br><br>";
}

$sql = "";

if($DB_DEBUG) {
  $sql .= " create table w_debitos_numpre as                                                                          \n"; 
}                                                                                                                     
                                                                                                                      
// 1º divida ativa                                                                                                    
$sql .= " select v01_exerc,                                                                                           \n";

if($DB_DEBUG) {                                                                                                        
  $sql .= "        numpre,                                                                                            \n"; 
}                                                                                                                      

$sql .= "        k00_descr,                                                                                           \n";
$sql .= "        v01_proced,                                                                                          \n";
$sql .= "        v03_descr,                                                                                           \n"; 
$sql .= "        v03_tributaria,                                                                                      \n"; 

if ($tiporel == "c") {
  $sql .= "        k00_numcgm,                                                                                        \n"; 
}

$sql .= "        round(sum(k22_vlrhis),2)  as k22_vlrhis,                                                             \n"; 
$sql .= "        round(sum(k22_vlrcor),2)  as k22_vlrcor,                                                             \n"; 
$sql .= "        round(sum(k22_juros),2)   as k22_juros,                                                              \n"; 
$sql .= "        round(sum(k22_multa),2)   as k22_multa,                                                              \n"; 
$sql .= "        round(sum(valor_total),2) as valor_total                                                             \n"; 
$sql .= "   from ( select v01_exerc,                                                                                  \n";

if($DB_DEBUG) {                                                                                                        
  $sql .= "                 k22_numpre as numpre,                                                                     \n"; 
} 

$sql .= "                 k00_descr,                                                                                  \n";
$sql .= "                 v01_proced,                                                                                 \n";
$sql .= "                 v03_descr,                                                                                  \n"; 
$sql .= "                 v07_descricao as v03_tributaria,                                                            \n"; 

if ($tiporel == "c") {                                                                                                 
  $sql .= "                 k22_numcgm as k00_numcgm,                                                                 \n"; 
}

$sql .= "                 round(sum(k22_vlrhis),2) as k22_vlrhis,                                                     \n"; 
$sql .= "                 round(sum(k22_vlrcor),2) as k22_vlrcor,                                                     \n"; 
$sql .= "                 round(sum(k22_juros),2)  as k22_juros,                                                      \n"; 
$sql .= "                 round(sum(k22_multa),2)  as k22_multa,                                                      \n"; 
$sql .= "                 round(sum(k22_vlrcor+k22_juros+k22_multa),2) as valor_total                                 \n"; 
$sql .= "            from debitos                                                                                     \n"; 
$sql .= "                 inner join divida     on divida.v01_numpre = k22_numpre                                     \n";
$sql .= "                                      and divida.v01_numpar = k22_numpar                                     \n"; 
$sql .= "                                      and divida.v01_instit = ".db_getsession('DB_instit')."                 \n";
$sql .= "                 inner join proced     on v03_codigo        = v01_proced                                     \n"; 
$sql .= "                 inner join tipoproced on v07_sequencial    = v03_tributaria                                 \n";
$sql .= "                 inner join arretipo   on arretipo.k00_tipo = k22_tipo                                       \n"; 
$sql .= "           where arretipo.k03_tipo = 5                                                                       \n";

$sql .= "             and k22_data = '".$xdata."'  and k22_instit = $instit                                           \n"; 
$sql .= "           group by v01_exerc,                                                                               \n";

if($DB_DEBUG) {                                                                                                        
  $sql .= "                    k22_numpre,                                                                            \n";
}

$sql .= "                    k00_descr,                                                                               \n";
$sql .= "                    v01_proced,                                                                              \n";
$sql .= "                    v03_descr,                                                                               \n";
$sql .= "                    v03_tributaria,                                                                          \n";
$sql .= "                    v07_descricao                                                                            \n";
if ($tiporel == "c") {                                                                                                 
  $sql .= "           , k22_numcgm                                                                                    \n"; 
}                                                                                                                     
                                                                                                                      
// 2º parcelamentos e reparcelamentos de divida
$sql .= " \n";                                                                        
$sql .= "          union all                                                                                          \n";
$sql .= " \n";
 
$sql .= "          select v01_exerc,                                                                                  \n";
if($DB_DEBUG) {                                                                                                       
  $sql .= "                 v07_numpre as numpre,                                                                     \n"; 
}                                                                                                                     
$sql .= "                 k00_descr,                                                                                  \n"; 
$sql .= "                 v01_proced,                                                                                 \n"; 
$sql .= "                 v03_descr,                                                                                  \n"; 
$sql .= "                 v07_descricao as v03_tributaria,                                                            \n"; 
if ($tiporel == "c") {                                                                                                
  $sql .= "                 k00_numcgm as k22_numcgm,                                                                 \n"; 
}                                                                                                                     
                                                                                                                      
$sql .= "                 sum( round( (divida.v01_vlrhis/rr.total*100)/100  * k22_vlrhis::float  ,2)) as k22_vlrhis,  \n"; 
$sql .= "                 sum( round( (divida.v01_vlrhis/rr.total*100)/100  * k22_vlrcor::float  ,2)) as k22_vlrcor,  \n"; 
$sql .= "                 sum( round( (divida.v01_vlrhis/rr.total*100)/100  * k22_juros::float   ,2)) as k22_juros,   \n"; 
$sql .= "                 sum( round( (divida.v01_vlrhis/rr.total*100)/100  * k22_multa::float   ,2)) as k22_multa,   \n"; 
$sql .= "                 sum( round( (divida.v01_vlrhis/rr.total*100)/100  * valor_total::float ,2)) as valor_total  \n"; 
$sql .= "            from termodiv                                                                                    \n"; 
                                                                                                                      
// Junta dividas do termo                                                                                             
$sql .= "                 inner join divida      on v01_coddiv        = termodiv.coddiv                               \n"; 
$sql .= "                                       and divida.v01_instit = ".db_getsession('DB_instit')."                \n";
$sql .= "                 inner join proced      on v01_proced        = v03_codigo                                    \n";
$sql .= "                 inner join tipoproced  on v07_sequencial    = v03_tributaria                                \n";
                                                                                                                      
                                                                                                                      
// Parcelamentos na Debitos                                                                                           
$sql .= "                 inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel = termodiv.parcel    \n";
                                                                                                                      
// Somatorio do Valor total das Dividas do Termo                                                                      
$sql .= "                 inner join ( select parcel,                                                                 \n";
$sql .= "                                     sum(coalesce(v01_vlrhis,0)) as total                                    \n";
$sql .= "                                from termodiv \n"; 
$sql .= "                                     inner join divida  on v01_coddiv        = termodiv.coddiv               \n"; 
$sql .= "                                                       and divida.v01_instit = ".db_getsession('DB_instit')."\n";
$sql .= "                               where divida.v01_instit = ".db_getsession('DB_instit')."                      \n"; 
$sql .= "                               group by parcel ) rr on rr.parcel = termodiv.parcel                           \n"; 

$sql .= "           group by v01_exerc,                                                                               \n";
if($DB_DEBUG) {                                                                                                        
  $sql .= "                    v07_numpre,                                                                            \n";
}                                                                                                                      
$sql .= "                    k00_descr,                                                                               \n";
$sql .= "                    v01_proced,                                                                              \n";
$sql .= "                    v03_descr,                                                                               \n";
$sql .= "                    v07_descricao                                                                            \n"; 
if ($tiporel == "c") {
  $sql .= "              , k00_numcgm                                                                                 \n"; 
}

// 4º certidao/inicial foro ( parcelamento )
$sql .= " \n";
$sql .= "           union all                                                                                         \n";
$sql .= " \n";

$sql .= "          select inicialcert.v01_exerc,                                                                      \n";
if($DB_DEBUG) {                                                                                                       
  $sql .= "                 v07_numpre as numpre,                                                                     \n";
}                                                                                                                     
$sql .= "                 k00_descr,                                                                                  \n";
$sql .= "                 inicialcert.v01_proced,                                                                     \n";
$sql .= "                 v03_descr,                                                                                  \n";
$sql .= "                 v03_tributaria,                                                                             \n";
if ($tiporel == "c") {                                                                                                
  $sql .= "        k00_numcgm,                                                                                        \n"; 
}

$sql .= "                 sum( round( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_vlrhis ::float,2) ) as k22_vlrhis,\n";
$sql .= "                 sum( round( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_vlrcor ::float,2) ) as k22_vlrcor,\n";
$sql .= "                 sum( round( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_juros  ::float,2) ) as k22_juros, \n";
$sql .= "                 sum( round( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * k22_multa  ::float,2) ) as k22_multa, \n";
$sql .= "                 sum( round( ( inicialcert.v01_valor /parctotal.totalparc*100 )/100 * valor_total::float,2) ) as valor_total\n";
$sql .= "            from termoini                                                                                         \n";
$sql .= "                 inner join w_parcelamentos_corrigidos as debitos on debitos.v07_parcel = termoini.parcel         \n";
$sql .= "                 inner join ( select v01_exerc,                                                                   \n";
$sql .= "                                     v01_proced,                                                                  \n";
$sql .= "                                     inicial,                                                                     \n";
$sql .= "                                     parcel,                                                                      \n";
$sql .= "                                     v07_descricao as v03_tributaria,                                             \n";
$sql .= "                                     v03_descr,                                                                   \n";
$sql .= "                                     sum(v01_vlrhis) as v01_valor                                                 \n";
$sql .= "                                from w_certidao_dividas                                                           \n";
$sql .= "                                     inner join proced     on v03_codigo     = v01_proced                         \n";
$sql .= "                                     inner join tipoproced on v07_sequencial = v03_tributaria                     \n";
$sql .= "                               group by v01_exerc,                                                                \n";
$sql .= "                                        v01_proced,                                                               \n";
$sql .= "                                        inicial,                                                                  \n";
$sql .= "                                        parcel,                                                                   \n";
$sql .= "                                        v07_descricao,                                                            \n";
$sql .= "                                        v03_descr                                                                 \n";
$sql .= "                            ) as inicialcert                      on termoini.inicial       = inicialcert.inicial \n";
$sql .= "                                                                 and termoini.parcel        = inicialcert.parcel  \n";
$sql .= "                 inner join ( select parcel,                                                                      \n";
$sql .= "                                     sum(v01_vlrhis) as totalparc                                                 \n";
$sql .= "                                from w_certidao_dividas                                                           \n";
$sql .= "                               group by parcel ) as parctotal     on termoini.parcel        = parctotal.parcel    \n";

$sql .= "           group by inicialcert.v01_exerc,                                                                        \n";
                                                                                                                           
if($DB_DEBUG) {                                                                                                            
  $sql .= "                    v07_numpre,                                                                                 \n";
}                                                                                                                          
                                                                                                                           
$sql .= "                    k00_descr,                                                                                    \n";
$sql .= "                    inicialcert.v01_proced,                                                                       \n";
$sql .= "                    v03_descr,                                                                                    \n";
$sql .= "                    v03_tributaria                                                                                \n";

if ($tiporel == "c") {
  $sql .= "          , k00_numcgm \n"; 
}

// 3º certidao de divida 
$sql .= " \n";
$sql .= "           union all                                                                                       \n";
$sql .= " \n"; 
$sql .= "          select v01_exerc,                                                                                \n";
if($DB_DEBUG) {                                                                                                     
  $sql .= "                 k22_numpre as numpre,                                                                   \n";
}                                                                                                                   
$sql .= "                 k00_descr as k00_descr,                                                                   \n";
$sql .= "                 v01_proced,                                                                               \n";
$sql .= "                 v03_descr,                                                                                \n"; 
$sql .= "                 v07_descricao as v03_tributaria,                                                          \n"; 
if ($tiporel == "c") {                                                                                              
  $sql .= "                 k22_numcgm as k00_numcgm,                                                               \n"; 
}                                                                                                                   
$sql .= "                 round(sum(k22_vlrhis),2) as k22_vlrhis,                                                   \n"; 
$sql .= "                 round(sum(k22_vlrcor),2) as k22_vlrcor,                                                   \n"; 
$sql .= "                 round(sum(k22_juros),2) as k22_juros,                                                     \n"; 
$sql .= "                 round(sum(k22_multa),2) as k22_multa,                                                     \n"; 
$sql .= "                 round(sum(k22_vlrcor+k22_juros+k22_multa),2) as valor_total                               \n"; 
$sql .= "            from debitos                                                                                   \n";
$sql .= "                 inner join (select distinct                                                               \n";
$sql .= "                                    v01_exerc,                                                             \n";
$sql .= "                                    v01_proced,                                                            \n";
$sql .= "                                    v01_numpre,                                                            \n";
$sql .= "                                    v01_numpar                                                             \n";
$sql .= "                               from w_certidao_dividas                                                     \n";
$sql .= "                            ) as origem_divida on origem_divida.v01_numpre = debitos.k22_numpre            \n";
$sql .= "                                              and origem_divida.v01_numpar = debitos.k22_numpar            \n";
$sql .= "                 inner join proced     on v01_proced        = v03_codigo                                   \n"; 
$sql .= "                 inner join tipoproced on v07_sequencial    = v03_tributaria                               \n";
$sql .= "                 inner join arretipo   on arretipo.k00_tipo = k22_tipo                                     \n"; 
$sql .= "           where 1=1                                                                                       \n"; 
$sql .= "             and k22_instit = $instit                                                                      \n";

$sql .= "             and k22_data = '".$xdata."'                                                                   \n"; 
$sql .= "           group by v01_exerc,                                                                             \n";
if($DB_DEBUG) {
  $sql .= "                    k22_numpre,                                                                          \n";
}                                                                                                                   
$sql .= "                    k00_descr,                                                                             \n";
$sql .= "                    v01_proced,                                                                            \n";
$sql .= "                    v03_descr,                                                                             \n";
$sql .= "                    v07_descricao";
if ($tiporel == "c") {
  $sql .= "              , k22_numcgm";
}                                                                                                                   
$sql .= " ) as posdivexerc                                                                                            ";       
$sql .= " where 1 = 1                                                                                                \n";
if ($procedencias != "") {                                                                                          
  $sql .= " and v01_proced in ($procedencias)                                                                       \n";
} 

$sql .= " $exercicios                                                                                               \n";

$sql .= "  group by v01_exerc,                                                                                      \n";
if($DB_DEBUG) {                                                                                                     
  $sql .= "           numpre,                                                                                       \n"; 
}                                                                                                                   
$sql .= "           k00_descr,                                                                                      \n";
$sql .= "           v01_proced,                                                                                     \n";
$sql .= "           v03_descr,                                                                                      \n"; 
$sql .= "           v03_tributaria                                                                                  \n";
if ($tiporel == "c") {                                                                                              
  $sql .= "          , k00_numcgm                                                                                   \n";
}                                                                                                                   
                                                                                                                    
$sql .= "  order by v01_exerc,                                                                                      \n";
$sql .= "           k00_descr,                                                                                      \n";
$sql .= "           v01_proced,                                                                                     \n";
$sql .= "           v03_descr,                                                                                      \n";
$sql .= "           v03_tributaria                                                                                  \n";

if($DB_DEBUG) {
  $strDebug = "begin; <br> $sqlTermoini <br><br>$sqlParcelamentosCorrigidos <br><br>$sqlCertidaoDividas <br><br>$sql";
  die("<pre>".$strDebug);
}

$result = db_query($sql) or die("FALHA: <br>$sql");

if(pg_numrows($result)==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dividas em aberto. ('.$exerc.').');
}

if ($tipo == 1) {

  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(220);
  $pdf->SetFont('Arial','B',7);

  $pag          = 1;
  $totalreg     = 0;
  $totalhis     = 0;
  $totalcor     = 0;
  $totaljur     = 0;
  $totalmul     = 0;
  $totalval     = 0;
  $tot_trib     = 0;
  $tot_nao_trib = 0;
  $int_ano      = 0;
  $int_ano2     = 0;
  $str_tipo     = "";

  for ($x = 0 ; $x < pg_numrows($result); $x++) {
    db_fieldsmemory($result,$x);
    if (($pdf->gety() > $pdf->h - 30) || $pag == 1 ) {
      $pdf->addpage( ($tiporel == "c"?"L":"") );
      $pag = 0;
    }
    if ($int_ano != $v01_exerc ) {
      if ($int_ano != 0 && $int_ano != $v01_exerc ) {
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(15,7,'','T',0,"C",0);
        $pdf->Cell(15,7,'','T',0,"C",0);
        $pdf->Cell(60,7,'','T',0,"L",0);
        if ($tiporel == "c") {
          $pdf->Cell(10,7,'','T',0,"L",0);
          $pdf->Cell(80,7,'','T',0,"L",0);
        }
        $pdf->cell(20,7,db_formatar($totalhis,'f'),'T',0,"R",0);
        $pdf->cell(20,7,db_formatar($totalcor,'f'),'T',0,"R",0);
        $pdf->cell(20,7,db_formatar($totaljur,'f'),'T',0,"R",0);
        $pdf->cell(20,7,db_formatar($totalmul,'f'),'T',0,"R",0);
        $pdf->cell(20,7,db_formatar($totalval,'f'),'T',1,"R",0);
        
        $pdf->cell(75,5,'DÍVIDA ATIVA NÃO TRIBUTÁRIA:',1,0,"R",0);
        $pdf->cell(20,5,db_formatar($tot_nao_trib,'f'),1,1,"R",0);
        $pdf->cell(75,5,'DÍVIDA ATIVA TRIBUTÁRIA:',1,0,"R",0);
        $pdf->cell(20,5,db_formatar($tot_trib,'f'),1,1,"R",0);
        $pdf->Cell(15,7,'','T',1,"C",0);
        
        $totalhis = 0;
        $totalcor = 0;
        $totaljur = 0;
        $totalmul = 0;
        $totalval = 0;
        $tot_trib  = 0;
        $tot_nao_trib = 0;
      }
      $int_ano = $v01_exerc;
      $pdf->Cell(80, 5, 'valores referentes ao ano de '.$v01_exerc, 0, 1, "C", 1 );
      $pdf->Cell(15,5,'',1,0,"C",1);
      $pdf->Cell(15,5,$RLv01_proced,1,0,"C",1);
      $pdf->Cell(60,5,$RLv03_descr,1,0,"C",1);
      if ($tiporel == "c") {
        $pdf->cell(10,5,'CGM',1,0,"R",1);
        $pdf->cell(80,5,'NOME',1,0,"R",1);
      }
      $pdf->cell(20,5,'Historico',1,0,"R",1);
      $pdf->cell(20,5,'Corrigido',1,0,"R",1);
      $pdf->cell(20,5,'Juros',1,0,"R",1);
      $pdf->cell(20,5,'Multa',1,0,"R",1);
      $pdf->cell(20,5,'Total',1,1,"R",1);
    }

    if ( $str_tipo != $k00_descr || $int_ano2 != $v01_exerc ) {
      $int_ano2 = $v01_exerc;
      $pdf->Cell(15,4,'','T',0,"C",0);
      $pdf->Cell(50,4, $k00_descr, 1, 1, "C", 1 );
      $str_tipo = $k00_descr;
    }

    $pdf->Cell(15,5,$v03_tributaria,0,0,"L",0);
    $pdf->Cell(15,5,$v01_proced,0,0,"R",0);
    $pdf->Cell(60,5,$v03_descr,0,0,"L",0);
    if ($tiporel == "c") {
      $sqlnome = "select z01_nome from cgm where z01_numcgm = $k00_numcgm";
      $resultnome = db_query($sqlnome) or die($sqlnome);
      if (pg_numrows($resultnome) > 0) {
        db_fieldsmemory($resultnome,0);
      } else {
        $z01_nome = "";
      }
      $pdf->Cell(10,5,$k00_numcgm,0,0,"L",0);
      $pdf->Cell(80,5,$z01_nome,0,0,"L",0);
    }
    $pdf->cell(20,5,db_formatar($k22_vlrhis,'f'),0,0,"R",0);
    $pdf->cell(20,5,db_formatar($k22_vlrcor,'f'),0,0,"R",0);
    $pdf->cell(20,5,db_formatar($k22_juros,'f'),0,0,"R",0);
    $pdf->cell(20,5,db_formatar($k22_multa,'f'),0,0,"R",0);
    $pdf->cell(20,5,db_formatar($valor_total,'f'),0,1,"R",0);
    $totalreg += 1;
    $totalhis += $k22_vlrhis;
    $totalcor += $k22_vlrcor;
    $totaljur += $k22_juros;
    $totalmul += $k22_multa;
    $totalval += $valor_total;
    
    $int_arr = 0;
    $boo_encontro = false;
    if ($totalreg > 1 ) {
      for ($int_arr=0; $int_arr < count($arr_proced ); $int_arr++) {
        if ($arr_proced[ $int_arr ][0] == $v01_proced." - ".$v03_descr ) {
          $boo_encontro = true;
          break;
        }
      }
    }
    if (( $boo_encontro == false and $int_arr > 0 ) or $totalreg == 1) {
      if ($totalreg > 1) {
        $int_arr = count($arr_proced );
      }
      $arr_proced[ $int_arr ][0] = "";
      $arr_proced[ $int_arr ][1] = "";
      $arr_proced[ $int_arr ][2] = 0;
      $arr_proced[ $int_arr ][3] = 0;
      $arr_proced[ $int_arr ][4] = 0;
      $arr_proced[ $int_arr ][5] = 0;
      $arr_proced[ $int_arr ][6] = 0;
      $arr_proced[ $int_arr ][7] = 0;
      $arr_proced[ $int_arr ][8] = 0;
    }
    $arr_proced[ $int_arr ][0] = $v01_proced." - ".$v03_descr;
    $arr_proced[ $int_arr ][1] = $v03_tributaria;
    $arr_proced[ $int_arr ][2] += $k22_vlrhis;
    $arr_proced[ $int_arr ][3] += $k22_vlrcor;
    $arr_proced[ $int_arr ][4] += $k22_juros;
    $arr_proced[ $int_arr ][5] += $k22_multa;
    $arr_proced[ $int_arr ][6] += $valor_total;
    $arr_proced[ $int_arr ][7] += 0;
    $arr_proced[ $int_arr ][8] += 0;

    if (substr($v03_tributaria,0,1) == "T" ) {
      $tot_trib += $valor_total;
      $arr_proced[ $int_arr ][7] += $valor_total;
    } else {
      $tot_nao_trib += $valor_total;
      $arr_proced[ $int_arr ][8] += $valor_total;
    }
  }

  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(15,7,'','T',0,"C",0);
  $pdf->Cell(15,7,'','T',0,"C",0);
  $pdf->Cell(60,7,'','T',0,"L",0);
  if ($tiporel == "c") {
    $pdf->Cell(10,7,'','T',0,"L",0);
    $pdf->Cell(80,7,'','T',0,"L",0);
  }
  $pdf->cell(20,7,db_formatar($totalhis,'f'),'T',0,"R",0);
  $pdf->cell(20,7,db_formatar($totalcor,'f'),'T',0,"R",0);
  $pdf->cell(20,7,db_formatar($totaljur,'f'),'T',0,"R",0);
  $pdf->cell(20,7,db_formatar($totalmul,'f'),'T',0,"R",0);
  $pdf->cell(20,7,db_formatar($totalval,'f'),'T',1,"R",0);

  $pdf->cell(75,5,'DÍVIDA ATIVA NÃO TRIBUTÁRIA:',1,0,"R",0);
  $pdf->cell(20,5,db_formatar($tot_nao_trib,'f'),1,1,"R",0);
  $pdf->cell(75,5,'DÍVIDA ATIVA TRIBUTÁRIA:',1,0,"R",0);
  $pdf->cell(20,5,db_formatar($tot_trib,'f'),1,1,"R",0);
  $pdf->Cell(15,7,'','T',1,"C",0);

  $pdf->addpage( ($tiporel == "c"?"L":"") );
  $pdf->Cell(190, 5, 'RESUMO', 0, 1, "C", 0 );
  $pdf->Cell(25,5,'',1,0,"C",1);
  $pdf->Cell(60,5,$RLv03_descr,1,0,"C",1);
  $pdf->cell(20,5,'Historico',1,0,"R",1);
  $pdf->cell(20,5,'Corrigido',1,0,"R",1);
  $pdf->cell(20,5,'Juros',1,0,"R",1);
  $pdf->cell(20,5,'Multa',1,0,"R",1);
  $pdf->cell(20,5,'Total',1,1,"R",1);

  $totalhis1 = 0;
  $totalcor1 = 0;
  $totaljur1 = 0;
  $totalmul1 = 0;
  $totalval1 = 0;

  $totalhis2 = 0;
  $totalcor2 = 0;
  $totaljur2 = 0;
  $totalmul2 = 0;
  $totalval2 = 0;

  $tot_trib = 0;
  $tot_nao_trib = 0;
  
  for( $int_arr=0; $int_arr < count( $arr_proced ); $int_arr++){
      $pdf->Cell(25,5,$arr_proced[ $int_arr ][1],0,0,"L",0);
      $pdf->Cell(60,5,$arr_proced[ $int_arr ][0],0,0,"L",0);
      $pdf->cell(20,5,db_formatar($arr_proced[ $int_arr ][2],'f'),0,0,"R",0);
      $pdf->cell(20,5,db_formatar($arr_proced[ $int_arr ][3],'f'),0,0,"R",0);
      $pdf->cell(20,5,db_formatar($arr_proced[ $int_arr ][4],'f'),0,0,"R",0);
      $pdf->cell(20,5,db_formatar($arr_proced[ $int_arr ][5],'f'),0,0,"R",0);
      $pdf->cell(20,5,db_formatar($arr_proced[ $int_arr ][6],'f'),0,1,"R",0);
      
      if( substr($arr_proced[ $int_arr ][1],0,1) == "T" ){
          $totalhis1 += $arr_proced[ $int_arr ][2];
          $totalcor1 += $arr_proced[ $int_arr ][3];
          $totaljur1 += $arr_proced[ $int_arr ][4];
          $totalmul1 += $arr_proced[ $int_arr ][5];
          $totalval1 += $arr_proced[ $int_arr ][6];
      } else {
          $totalhis2 += $arr_proced[ $int_arr ][2];
          $totalcor2 += $arr_proced[ $int_arr ][3];
          $totaljur2 += $arr_proced[ $int_arr ][4];
          $totalmul2 += $arr_proced[ $int_arr ][5];
          $totalval2 += $arr_proced[ $int_arr ][6];
      }
      $tot_trib += $arr_proced[ $int_arr ][7];
      $tot_nao_trib += $arr_proced[ $int_arr ][8];
  }
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(90,5,'DÍVIDA ATIVA TRIBUTÁRIA:','T',0,"L",0);
  $pdf->cell(20,5,db_formatar($totalhis1,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totalcor1,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totaljur1,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totalmul1,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totalval1,'f'),'T',1,"R",0);

  $pdf->Cell(90,5,'DÍVIDA ATIVA NÃO TRIBUTÁRIA:',0,0,"L",0);
  $pdf->cell(20,5,db_formatar($totalhis2,'f'),0,0,"R",0);
  $pdf->cell(20,5,db_formatar($totalcor2,'f'),0,0,"R",0);
  $pdf->cell(20,5,db_formatar($totaljur2,'f'),0,0,"R",0);
  $pdf->cell(20,5,db_formatar($totalmul2,'f'),0,0,"R",0);
  $pdf->cell(20,5,db_formatar($totalval2,'f'),0,1,"R",0);

  $pdf->Cell(90,5,'TOTAL:','T',0,"L",0);
  $pdf->cell(20,5,db_formatar($totalhis1+$totalhis2,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totalcor1+$totalcor2,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totaljur1+$totaljur2,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totalmul1+$totalmul2,'f'),'T',0,"R",0);
  $pdf->cell(20,5,db_formatar($totalval1+$totalval2,'f'),'T',1,"R",0);

  $pdf->Output();

} else {

  $nomedoarquivo = "/tmp/divida_exercicio_procedencia_" . date("Y-m-d_His",db_getsession("DB_datausu")) . ".txt";

  $erro = false;
  $descricao_erro = false;
  set_time_limit(0);
  $clabre_arquivo = new cl_abre_arquivo($nomedoarquivo);

  if ($clabre_arquivo->arquivo != false) {

    fputs($clabre_arquivo->arquivo, "exercicio;");
    fputs($clabre_arquivo->arquivo, "descricao_tipo_debito;");
    fputs($clabre_arquivo->arquivo, "codigo_procedencia;");
    fputs($clabre_arquivo->arquivo, "descricao_procedencia;");
    fputs($clabre_arquivo->arquivo, "categoria;");
    if ($tiporel == "c") {
      fputs($clabre_arquivo->arquivo, "cgm;");
    }
    fputs($clabre_arquivo->arquivo, "valor_historico;");
    fputs($clabre_arquivo->arquivo, "valor_corrigido;");
    fputs($clabre_arquivo->arquivo, "valor_juros;");
    fputs($clabre_arquivo->arquivo, "valor_multa;");
    fputs($clabre_arquivo->arquivo, "valor_total;");
    fputs($clabre_arquivo->arquivo, "\n");

  }


  for ($x = 0 ; $x < pg_numrows($result); $x++) {
    db_fieldsmemory($result,$x);

    fputs($clabre_arquivo->arquivo, trim( $v01_exerc ) . ";");
    fputs($clabre_arquivo->arquivo, trim( $k00_descr ) . ";");
    fputs($clabre_arquivo->arquivo, trim( $v01_proced) . ";");
    fputs($clabre_arquivo->arquivo, trim( $v03_descr) . ";");
    fputs($clabre_arquivo->arquivo, trim( $v03_tributaria) . ";");

    if ($tiporel == "c") {

      $sqlnome = "select z01_nome from cgm where z01_numcgm = $k00_numcgm";
      $resultnome = db_query($sqlnome) or die($sqlnome);
      if (pg_numrows($resultnome) > 0) {
        db_fieldsmemory($resultnome,0);
      } else {
        $z01_nome = "";
      }

      fputs($clabre_arquivo->arquivo, trim( $k00_numcgm) . ";");
      fputs($clabre_arquivo->arquivo, trim( $z01_nome) . ";");

    }

    fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_vlrhis,'f')).";");
    fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_vlrcor,'f')).";");
    fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_juros,'f')).";");
    fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_multa,'f')).";");
    fputs($clabre_arquivo->arquivo, trim(db_formatar($valor_total,'f')).";");

    fputs($clabre_arquivo->arquivo, "\n");

  }

  $descricao_erro = "Arquivo $nomedoarquivo gerado com sucesso.";

  fclose($clabre_arquivo->arquivo);

  if (isset($local) or 1==1) {
    echo "<script>jan = window.open('db_download.php?arquivo=" . $clabre_arquivo->nomearq . "','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
    echo "jan.moveTo(0,0);</script>";
  }

  db_msgbox($descricao_erro);

}

?>