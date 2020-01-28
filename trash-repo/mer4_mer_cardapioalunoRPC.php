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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_tipocardapio_classe.php");
include("classes/db_mer_cardapioaluno_classe.php");
include("classes/db_mer_cardapioalunorepet_classe.php");
include("classes/db_mer_cardapioescola_classe.php");
include("classes/db_matricula_classe.php");
include("dbforms/db_funcoes.php");
$clmer_cardapiodia     = new cl_mer_cardapiodia;
$clmer_cardapioaluno   = new cl_mer_cardapioaluno;
$clmer_cardapioalunorepet   = new cl_mer_cardapioalunorepet;
$clmer_tipocardapio   = new cl_mer_tipocardapio;
$clmer_cardapioescola  = new cl_mer_cardapioescola;
$clmatricula           = new cl_matricula;
$escola                = db_getsession("DB_coddepto");
$hoje                  = date("Y-m-d",db_getsession("DB_datausu"));
$oPost                 = db_utils::postMemory($_POST);

if ($oPost->sAction == 'PesquisaEscola') {
	
  $result = $clmer_cardapioescola->sql_record(
             $clmer_cardapioescola->sql_query("",
                                              "me32_i_codigo,ed18_c_nome",
                                              "ed18_c_nome",
                                              "me32_i_tipocardapio = {$oPost->cardapio}
                                               AND me32_i_escola = $escola" 
                                              )
                                              );
  $aResult = db_utils::getColectionByRecord($result, false, false, true);
  $oJson   = new services_json();
  echo $oJson->encode($aResult);
  
}

if ($oPost->sAction == 'PesquisaTurma_e_Refeicao') {

  if ($oPost->escola=="") {

    $oJson    = new services_json();
    echo $oJson->encode(array(array(),array()));
    return false;
    
  }

  $iEscola = db_getsession('DB_coddepto');

  $result = $clmer_tipocardapio->sql_record(
             $clmer_tipocardapio->sql_query("",
                                            "me27_i_ano,(select ed18_i_codigo from escola
                                                         inner join mer_cardapioescola on me32_i_escola = ed18_i_codigo
                                                         where me32_i_codigo = {$oPost->escola}
                                                         and me32_i_tipocardapio = me27_i_codigo) as codescola",
                                            "",
                                            "me27_i_codigo = {$oPost->cardapio}" 
                                           )
                                           );
  db_fieldsmemory($result,0);
  $dataatual = date("Y-m-d",db_getsession("DB_datausu"));
  $horaatual = date("H:i");
  $campos    = "me12_i_codigo,me01_c_nome,me01_f_versao,me12_d_data,me03_c_tipo";
  $restricao = " (me12_d_data < '$dataatual' OR (me12_d_data = '$dataatual' AND me03_c_fim < '$horaatual'))
                  AND me01_i_tipocardapio = {$oPost->cardapio}
                  AND not exists(select * from mer_cardapiodata 
                                  inner join mer_cardapiodiaescola on me37_i_codigo = me13_i_cardapiodiaescola
                                 where me37_i_cardapiodia = me12_i_codigo
                                 and me37_i_cardapioescola = {$oPost->escola}
                                )
                  AND not exists(select * from mer_cardapioturma
                                  inner join turma  on  turma.ed57_i_codigo = mer_cardapioturma.me39_i_turma
                                 where me39_i_cardapiodia = me12_i_codigo
                                 and ed57_i_escola = $iEscola)
                  AND exists(select * from mer_cardapiodiaescola 
                             where me37_i_cardapiodia = me12_i_codigo
                             and me37_i_cardapioescola = {$oPost->escola})                 
                 ";   
  $result1 = $clmer_cardapiodia->sql_record(
              $clmer_cardapiodia->sql_query("",
                                            $campos,
                                            "me12_d_data DESC,me03_i_orden",
                                            " $restricao"
                                           )
                                           );
  $result2 = $clmatricula->sql_record(
              $clmatricula->sql_query("",
                                      "count(*) as qtde,
                                       turma.ed57_i_codigo,
                                       turma.ed57_c_descr,
                                       serie.ed11_c_descr
                                      ",                          
                                      "turma.ed57_c_descr",
                                      "turma.ed57_i_escola = $codescola 
                                       AND calendario.ed52_i_ano = $me27_i_ano
                                       AND ed60_c_situacao = 'MATRICULADO'
                                       AND ed221_i_serie in (select ed11_i_codigo
                                                             from serie
                                                              inner join mer_tpcardapioturma on me28_i_serie = ed11_i_codigo
                                                              inner join mer_cardapioescola on me32_i_codigo = me28_i_cardapioescola
                                                              inner join mer_tipocardapio on me27_i_codigo = me32_i_tipocardapio
                                                             where me27_i_codigo = {$oPost->cardapio}
                                                             and me32_i_codigo = {$oPost->escola}  
                                                            )
                                       GROUP BY 
                                       turma.ed57_i_codigo,
                                       turma.ed57_c_descr,
                                       serie.ed11_c_descr"
                                      ));
                                             
  $aResult1 = db_utils::getColectionByRecord($result1, false, false, true);
  $aResult2 = db_utils::getColectionByRecord($result2, false, false, true);
  $oJson    = new services_json();
  echo $oJson->encode(array($aResult1,$aResult2));
 
}

if ($oPost->sAction == 'PesquisaAluno') {
	
  $result1 = $clmatricula->sql_record(
              $clmatricula->sql_query("",
                                      "ed60_i_codigo, ed60_matricula, ed47_i_codigo,ed47_v_nome,
                                       (select count(*) from mer_restriitem 
                                         inner join mer_cardapioitem  on  mer_cardapioitem.me07_i_alimento = mer_restriitem.me25_i_alimento
                                         inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapioitem.me07_i_cardapio
                                         inner join mer_cardapiodia  on  mer_cardapiodia.me12_i_cardapio = mer_cardapio.me01_i_codigo
                                         inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio
                                         inner join mer_cardapioescola  on  mer_cardapioescola.me32_i_tipocardapio = mer_tipocardapio.me27_i_codigo
                                         inner join mer_restricao  on  mer_restricao.me24_i_codigo = mer_restriitem.me25_i_restricao
                                        where me24_i_aluno = ed47_i_codigo
                                        and me12_i_codigo = {$oPost->refeicao}
                                       ) as restricao
                                      ",
                                      "ed47_v_nome",
                                      "ed60_c_ativa = 'S' 
                                       AND ed60_c_situacao = 'MATRICULADO' 
                                       AND ed60_i_turma = {$oPost->turma}                                                               
                                       AND not exists(select * from mer_cardapioaluno 
                                                      where me11_i_matricula = ed60_i_codigo 
                                                      and me11_i_cardapiodia = {$oPost->refeicao}
                                                     )"
                                     )
                                     );  
  
  $result2 = $clmer_cardapioaluno->sql_record(
              $clmer_cardapioaluno->sql_query("",
                                              "ed60_i_codigo, ed60_matricula,ed47_i_codigo,ed47_v_nome,
                                               (select count(*) from mer_restriitem 
                                                 inner join mer_cardapioitem  on  mer_cardapioitem.me07_i_alimento = mer_restriitem.me25_i_alimento
                                                 inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapioitem.me07_i_cardapio
                                                 inner join mer_cardapiodia  on  mer_cardapiodia.me12_i_cardapio = mer_cardapio.me01_i_codigo
                                                 inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio
                                                 inner join mer_cardapioescola  on  mer_cardapioescola.me32_i_tipocardapio = mer_tipocardapio.me27_i_codigo
                                                 inner join mer_restricao  on  mer_restricao.me24_i_codigo = mer_restriitem.me25_i_restricao
                                                where me24_i_aluno = ed47_i_codigo
                                                and me12_i_codigo = {$oPost->refeicao}
                                               ) as restricao
                                              ",
                                              "ed47_v_nome",
                                              "me11_i_cardapiodia = {$oPost->refeicao}
                                               AND me11_i_matricula in (select ed60_i_codigo from matricula
                                                                        where ed60_i_turma = {$oPost->turma}  
                                                                       )"
                                             )
                                             );
  $result3 = $clmer_cardapioalunorepet->sql_record(
              $clmer_cardapioalunorepet->sql_query("",
                                                   "me40_i_repeticao",
                                                   "",
                                                   "me40_i_cardapiodia = {$oPost->refeicao}
                                                    and me40_i_turma = {$oPost->turma}"
                                             )
                                             );
                                             
  $aResult1 = db_utils::getColectionByRecord($result1, false, false, true);
  $aResult2 = db_utils::getColectionByRecord($result2, false, false, true);
  $aResult3 = db_utils::getColectionByRecord($result3, false, false, true);  
  $oJson    = new services_json();
  echo $oJson->encode(array($aResult1,$aResult2,$aResult3));
  
}

if ($oPost->sAction == 'VerificaDia') {
	
  $result1 = $clmer_cardapioaluno->sql_record($clmer_cardapioaluno->sql_query("",
                                                                              "ed60_i_codigo, ed60_matricula,ed47_i_codigo,ed47_v_nome",
                                                                              "ed47_v_nome",
                                                                              "me11_i_cardapiodia = {$oPost->cardapiodia} 
                                                                             "));
  $oJson = new services_json();
  echo $oJson->encode(urlencode($clmer_cardapioaluno->numrows));
  
}

if ($oPost->sAction == 'InclusaoCardapiodia') {
	
  db_inicio_transacao();
  $clmer_cardapioaluno->excluir("",
                                " me11_i_cardapiodia = {$oPost->cardapiodia} 
                                  and me11_i_matricula in (select ed60_i_codigo from matricula 
                                                           where ed60_i_turma = {$oPost->turma}
                                                          )");
  if (trim($oPost->cod_alunos) != "") {
  	
    $cod_matricula = explode(",",$oPost->cod_alunos);
    for ($x=0;$x<count($cod_matricula);$x++) {
    	
      $clmer_cardapioaluno->me11_d_data        = date("Y-m-d",db_getsession("DB_datausu"));
      $clmer_cardapioaluno->me11_i_usuario     = db_getsession("DB_id_usuario");
      $clmer_cardapioaluno->me11_i_cardapiodia = $oPost->cardapiodia;
      $clmer_cardapioaluno->me11_i_matricula   = $cod_matricula[$x];
      $clmer_cardapioaluno->incluir(null);
                  
    }
    
  }
  $clmer_cardapioalunorepet->excluir(""," me40_i_cardapiodia = {$oPost->cardapiodia} and me40_i_turma = {$oPost->turma}");
  if ($oPost->repeticao!="" && trim($oPost->cod_alunos) != "") {
        
    $clmer_cardapioalunorepet->me40_i_cardapiodia = $oPost->cardapiodia;
    $clmer_cardapioalunorepet->me40_i_repeticao = $oPost->repeticao;
    $clmer_cardapioalunorepet->me40_i_turma = $oPost->turma;      
    $clmer_cardapioalunorepet->incluir(null);
      
  }
  
  db_fim_transacao();
  $msg_retorno = "Dados salvos com sucesso!";
  $oJson       = new services_json();
  echo $oJson->encode(urlencode($msg_retorno));
 
}
?>