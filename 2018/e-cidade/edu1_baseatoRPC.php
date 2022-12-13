<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_serie_classe.php");
include("dbforms/db_funcoes.php");
$clserie = new cl_serie;
$escola = db_getsession("DB_coddepto");

$oPost = db_utils::postMemory($_POST);

if($oPost->sAction == 'PesquisaSerie') {
	
  $sql1="SELECT a.ed11_i_sequencia as seq, b.ed11_i_sequencia as seqs from baseserie 
         inner join serie as a on a.ed11_i_codigo = baseserie. ed87_i_serieinicial  
         inner join serie as b on b.ed11_i_codigo = baseserie. ed87_i_seriefinal where ed87_i_codigo= {$oPost->base}";
  $result1 = pg_query($sql1);
  $inicial = pg_result($result1,0,0);
  $final = pg_result($result1,0,1);
  //die($sql1);
  $sql = "SELECT ed11_i_codigo,ed11_i_sequencia,
                ed11_c_descr,
                case when (select ed279_i_codigo from baseatoserie inner join baseato on ed278_i_codigo = ed279_i_baseato where ed279_i_serie = ed11_i_codigo and ed278_i_escolabase = {$oPost->codbaseescola}) > 0
                 then 'S' else 'N' end as temoutro,
                case when (select ed279_i_codigo from baseatoserie inner join baseato on ed278_i_codigo = ed279_i_baseato where ed279_i_serie = ed11_i_codigo and ed278_i_escolabase = {$oPost->codbaseescola}) > 0
                 then (select '(Ato Legal n° '||ed05_c_numero||')' from baseatoserie inner join baseato on ed278_i_codigo = ed279_i_baseato inner join atolegal on ed05_i_codigo = ed278_i_atolegal where ed279_i_serie = ed11_i_codigo and ed278_i_escolabase = {$oPost->codbaseescola})
                 else '' end as descr_temoutro
         FROM serie
          inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino
          inner join cursoedu on cursoedu.ed29_i_ensino = ensino.ed10_i_codigo
          inner join base on base.ed31_i_curso = cursoedu.ed29_i_codigo
         WHERE ed31_i_codigo= {$oPost->base} and ed11_i_sequencia >= $inicial and ed11_i_sequencia <= $final
         ORDER BY ed11_i_sequencia
        ";
         //die($sql);
 $result_serie = pg_query($sql);
 $aResult = db_utils::getColectionByRecord($result_serie, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode($aResult);
}
if($oPost->sAction == 'PesquisaSerieIncluida') {
   $sql2="SELECT a.ed11_i_sequencia as seq, b.ed11_i_sequencia as seqs from baseserie 
         inner join serie as a on a.ed11_i_codigo = baseserie. ed87_i_serieinicial  
         inner join serie as b on b.ed11_i_codigo = baseserie. ed87_i_seriefinal where ed87_i_codigo= {$oPost->base}";
  $result2 = pg_query($sql2);
  $seqinicial = pg_result($result2,0,0);
  $seqfinal = pg_result($result2,0,1);

  $sql = "SELECT ed11_i_codigo,ed11_i_sequencia,
                ed11_c_descr,
                case when (select ed279_i_codigo from baseatoserie inner join baseato on ed278_i_codigo = ed279_i_baseato where ed279_i_serie = ed11_i_codigo and ed278_i_escolabase = {$oPost->codbaseescola} and ed279_i_baseato != {$oPost->baseato} ) > 0
                 then 'S' else 'N' end as temoutro,
                case when (select ed278_i_codigo from baseatoserie inner join baseato on ed278_i_codigo = ed279_i_baseato where ed279_i_serie = ed11_i_codigo and ed278_i_escolabase = {$oPost->codbaseescola} and ed279_i_baseato != {$oPost->baseato}) > 0
                 then (select '(Ato Legal n° '||ed05_c_numero||')' from baseatoserie inner join baseato on ed278_i_codigo = ed279_i_baseato inner join atolegal on ed05_i_codigo = ed278_i_atolegal where ed279_i_serie = ed11_i_codigo and ed278_i_escolabase = {$oPost->codbaseescola} and ed279_i_baseato != {$oPost->baseato})
                 else '' end as descr_temoutro,
                case when (select ed279_i_codigo from baseatoserie where ed279_i_serie = ed11_i_codigo and ed279_i_baseato = {$oPost->baseato}) > 0
                 then 'S' else 'N' end as temregistro
         FROM serie
          inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino
          inner join cursoedu on cursoedu.ed29_i_ensino = ensino.ed10_i_codigo
          inner join base on base.ed31_i_curso = cursoedu.ed29_i_codigo
         WHERE ed31_i_codigo = {$oPost->base} and ed11_i_sequencia >= $seqinicial and ed11_i_sequencia <= $seqfinal
         ORDER BY ed11_i_sequencia
        ";
         //die($sql);
 $result_serie = pg_query($sql);
 $aResult = db_utils::getColectionByRecord($result_serie, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode($aResult);
}

?>