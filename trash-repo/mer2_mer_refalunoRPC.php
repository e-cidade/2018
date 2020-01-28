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
include("classes/db_mer_tipocardapio_classe.php");
include("classes/db_mer_cardapioaluno_classe.php");
include("classes/db_mer_cardapioturma_classe.php");
include("classes/db_matricula_classe.php");
include("dbforms/db_funcoes.php");
$clmer_tipocardapio    = new cl_mer_tipocardapio;
$clmer_cardapioaluno   = new cl_mer_cardapioaluno;
$clmer_cardapioturma   = new cl_mer_cardapioturma;
$clmatricula           = new cl_matricula;
$escola                = db_getsession("DB_coddepto");
$hoje                  = date("Y-m-d",db_getsession("DB_datausu"));
$oPost                 = db_utils::postMemory($_POST);

if ($oPost->sAction == 'PesquisaRefeicao') {
	
  if ($oPost->lancamento=="A") {
  	
    $result1 = $clmer_cardapioaluno->sql_record(
               $clmer_cardapioaluno->sql_query("",
                                               "DISTINCT me12_i_codigo,me01_c_nome,me01_f_versao,to_char(me12_d_data,'DD/MM/YYYY') as me12_d_data,me03_c_tipo",
                                               "me12_d_data DESC",
                                               "me01_i_tipocardapio = {$oPost->cardapio}
                                                and exists(select * from mer_cardapiodiaescola
                                                            inner join mer_cardapioescola on me32_i_codigo = me37_i_cardapioescola
                                                           where me37_i_cardapiodia = me12_i_codigo
                                                           and me32_i_escola = $escola
                                                          ) 
                                               " 
                                              )
                                              );
  	
  } else {
  	
    $result1 = $clmer_cardapioturma->sql_record(
               $clmer_cardapioturma->sql_query("",
                                               "DISTINCT me12_i_codigo,me01_c_nome,me01_f_versao,to_char(me12_d_data,'DD/MM/YYYY') as me12_d_data,me03_c_tipo",
                                               "me12_d_data DESC",
                                               "me01_i_tipocardapio = {$oPost->cardapio}
                                                and exists(select * from mer_cardapiodiaescola
                                                            inner join mer_cardapioescola on me32_i_codigo = me37_i_cardapioescola
                                                           where me37_i_cardapiodia = me12_i_codigo
                                                           and me32_i_escola = $escola
                                                          ) 
                                               " 
                                              )
                                              );
  	
  }
  $aResult1 = db_utils::getColectionByRecord($result1, false, false, true);
  $oJson    = new services_json();
  echo $oJson->encode($aResult1);
  
}
if ($oPost->sAction == 'PesquisaTurma') {
    
  if ($oPost->lancamento=="A") {
    
    $condicao2 = " exists(select * from mer_cardapioaluno 
                          where me11_i_matricula = ed60_i_codigo
                          and me11_i_cardapiodia = $oPost->refeicao)";                                              
    
  } else {
    
    $condicao2 = " exists(select * from mer_cardapioturma 
                          where me39_i_turma = turma.ed57_i_codigo
                          and me39_i_cardapiodia = $oPost->refeicao)";
    
  }
  $result = $clmer_tipocardapio->sql_record(
             $clmer_tipocardapio->sql_query("",
                                            "me27_i_ano,(select me32_i_codigo from mer_cardapioescola
                                                         where me32_i_escola = $escola
                                                         and me32_i_tipocardapio = me27_i_codigo) as codescola",
                                            "",
                                            "me27_i_codigo = {$oPost->cardapio}" 
                                           )
                                           );
  db_fieldsmemory($result,0);
  $result2 = $clmatricula->sql_record(
              $clmatricula->sql_query("",
                                      "count(*) as qtde,
                                       turma.ed57_i_codigo,
                                       turma.ed57_c_descr,
                                       serie.ed11_c_descr
                                      ",                          
                                      "turma.ed57_c_descr",
                                      "turma.ed57_i_escola = $escola 
                                       AND calendario.ed52_i_ano = $me27_i_ano
                                       AND ed60_c_situacao = 'MATRICULADO'
                                       AND ed221_i_serie in (select ed11_i_codigo
                                                             from serie
                                                              inner join mer_tpcardapioturma on me28_i_serie = ed11_i_codigo
                                                              inner join mer_cardapioescola on me32_i_codigo = me28_i_cardapioescola
                                                              inner join mer_tipocardapio on me27_i_codigo = me32_i_tipocardapio
                                                              inner join mer_cardapio on me01_i_tipocardapio = me27_i_codigo
                                                             where me01_i_codigo = {$oPost->cardapio}
                                                             and me32_i_codigo = $codescola  
                                                            )
                                       AND $condicao2                 
                                       GROUP BY 
                                       turma.ed57_i_codigo,
                                       turma.ed57_c_descr,
                                       serie.ed11_c_descr"
                                      ));
  $aResult2 = db_utils::getColectionByRecord($result2, false, false, true);
  $oJson    = new services_json();
  echo $oJson->encode($aResult2);
  
}
?>