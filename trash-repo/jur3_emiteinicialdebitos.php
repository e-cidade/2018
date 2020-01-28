<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
if (isset($oGet->certidao) and ($oGet->certidao != '') && $oGet->sTipoConsulta == "debitos") {                                                  
  
  $iInstituicao = db_getsession('DB_instit');
  
  $sSql   = "select x.k00_numpre,                                                                 \n";
  $sSql  .= "       x.k00_numpar,                                                                 \n";
  $sSql  .= "       x.k00_receit || ' - ' || tabrec.k02_descr as k00_receit,                      \n";
  $sSql  .= "       sum(x.k00_valor) as k00_valor,                                                \n";
  $sSql  .= "       x.k00_dtvenc as dl_Data_de_Vencimento,                                        \n";
  $sSql  .= "       x.debito_origem  as dl_Origem                                                 \n";
  $sSql  .= "  from (                                                                             \n";
  $sSql  .= "        select arrecad.*,                                                            \n";
  $sSql  .= "               'Parcelamento na Certid�o'         as debito_origem                   \n";
  $sSql  .= "          from certid                                                                \n";
  $sSql  .= "         inner join certter    on certter.v14_certid    = certid.v13_certid          \n";
  $sSql  .= "         inner join termo      on termo.v07_parcel      = certter.v14_parcel         \n";
  $sSql  .= "         inner join arrecad    on arrecad.k00_numpre    = termo.v07_numpre           \n";
  $sSql  .= "         where certid.v13_certid = {$certidao}                                       \n";
  $sSql  .= "                                                                                     \n";
  $sSql  .= "         union                                                                       \n";
  $sSql  .= "                                                                                     \n";
  $sSql  .= "        select arrecad.*,                                                            \n";
  $sSql  .= "               'Divida na Certid�o'               as debito_origem                   \n";
  $sSql  .= "          from certid                                                                \n";
  $sSql  .= "               inner join certdiv    on certdiv.v14_certid    = certid.v13_certid    \n";
  $sSql  .= "               inner join divida     on divida.v01_coddiv     = certdiv.v14_coddiv   \n";
  $sSql  .= "               inner join arrecad    on arrecad.k00_numpre    = divida.v01_numpre    \n";
  $sSql  .= "                                    and arrecad.k00_numpar    = divida.v01_numpar    \n";
  $sSql  .= "         where certid.v13_certid = {$certidao}                                       \n";
  $sSql  .= "         union                                                                       \n";
  $sSql  .= "                                                                                     \n";
  $sSql  .= "        select arrecad.*,                                                            \n";
  $sSql  .= "               'Parcelamento Anulado da Certid�o' as debito_origem                   \n";
  $sSql  .= "          from acertid                                                               \n";
  $sSql  .= "               inner join acertter   on acertter.v14_certid   = acertid.v15_certid   \n";
  $sSql  .= "               inner join termo      on termo.v07_parcel      = acertter.v14_parcel  \n";
  $sSql  .= "               inner join arrecad    on arrecad.k00_numpre    = termo.v07_numpre     \n";
  $sSql  .= "         where acertid.v15_certid = {$certidao}                                      \n";
  $sSql  .= "                                                                                     \n";
  $sSql  .= "         union                                                                       \n";
  $sSql  .= "                                                                                     \n";
  $sSql  .= "        select arrecad.*,                                                            \n";
  $sSql  .= "               'Divida Anulada da Certid�o'       as debito_origem                   \n";
  $sSql  .= "          from acertid                                                               \n";
  $sSql  .= "               inner join acertdiv   on acertdiv.v14_certid   = acertid.v15_certid   \n";
  $sSql  .= "               inner join divida     on divida.v01_coddiv     = acertdiv.v14_coddiv  \n";
  $sSql  .= "               inner join arrecad    on arrecad.k00_numpre    = divida.v01_numpre    \n";
  $sSql  .= "                                    and arrecad.k00_numpar    = divida.v01_numpar    \n";
  $sSql  .= "         where acertid.v15_certid = {$certidao}                                      \n";
  $sSql  .= "                                                                                     \n";
  $sSql  .= "        ) as x                                                                       \n";
  $sSql  .= "          inner join arreinstit on arreinstit.k00_numpre = x.k00_numpre              \n";
  $sSql  .= "          inner join tabrec     on tabrec.k02_codigo     = x.k00_receit              \n";
  $sSql  .= "  where arreinstit.k00_instit = {$iInstituicao}                                      \n";
  $sSql  .= "  group by x.k00_numpre,                                                             \n";
  $sSql  .= "           x.k00_numpar,                                                             \n";
  $sSql  .= "           x.k00_receit,                                                             \n";
  $sSql  .= "           x.k00_dtvenc,                                                             \n";
  $sSql  .= "           x.debito_origem,                                                          \n";
  $sSql  .= "           tabrec.k02_descr                                                          \n";
  $sSql  .= "  order by x.debito_origem,                                                          \n";
  $sSql  .= "           x.k00_numpre,                                                             \n";
  $sSql  .= "           x.k00_numpar,                                                             \n";
  $sSql  .= "           x.k00_receit                                                              \n";
                   
  
}
if (isset($oGet->certidao) and ($oGet->certidao != '') && $oGet->sTipoConsulta == "movimentacao") {
  
  $sSql = " select numero_certidao as \"dl_N�mero da Certid�o\",   ";
  $sSql.= "        data_movimento  as \"dl_Data do Movimento\",    ";
  $sSql.= "        instituicao     as \"dl_Institui��o\",          ";
  $sSql.= "        tipo_movimento  as \"dl_Tipo de Movimenta��o\", ";
  $sSql.= "        observacoes     as \"dl_Observa��es\"           ";
  $sSql.= "   from (                                               ";
  $sSql.= " select v13_certid                 as numero_certidao,  ";
  $sSql.= "        v13_dtemis                 as data_movimento,   ";
  $sSql.= "        v13_instit                 as instituicao,      ";
  $sSql.= "        '1 - Cria��o da Certidao'  as tipo_movimento,   ";
  $sSql.= "        ''                         as observacoes       ";
  $sSql.= "   from certid                                          ";
  $sSql.= "  where v13_certid = $certidao                          ";
  $sSql.= "                                                        ";
  $sSql.= "  union                                                 ";
  $sSql.= "                                                        ";
  $sSql.= "                                                        ";
  $sSql.= "  select v15_codigo                as numero_certidao,  ";
  $sSql.= "         v15_data                  as data_movimento,   ";
  $sSql.= "         v15_instit                as instituicao,      ";
  $sSql.= "         case when v15_parcial is false                 ";
  $sSql.= "              then '3 - Anula��o da Certid�o'           ";
  $sSql.= "              else '2 - Anula��o Parcial da Certid�o'   ";
  $sSql.= "         end                       as tipo_movimento,   ";
  $sSql.= "        v15_observacao             as observacoes       ";
  $sSql.= "    from acertid                                        ";
  $sSql.= "   where v15_certid = $certidao) as x                   ";
  $sSql.= "   order by tipo_movimento,  data_movimento             ";
}
  
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css"); ?>
</head>
<body>
<form name="form1" method="post">
  <?php 
    $funcao_js='';
    db_lovrot($sSql,15,"()","",$funcao_js);
  ?>
  </form>
</body>     
</html>