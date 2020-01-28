<?
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_calendario_classe.php");
include("classes/db_mer_tipocardapio_classe.php");
$clcalendario = new cl_calendario;
$clmer_tipocardapio = new cl_mer_tipocardapio;
$result_cardapio = $clmer_tipocardapio->sql_record($clmer_tipocardapio->sql_query("",
                                                                                  "me27_i_ano",
                                                                                  "",
                                                                                  "me27_i_codigo = $cardapio"
                                                                                 ));
$ed52_i_ano = pg_result($result_cardapio,0,0);

function montasemana($dData, $iSemana = null, $iAno = null) {

  /* 
  Se for passada somente $dData, ou seja, se $iSemana for null (valor default), 
  retorna todos os dias da semana da data passada. A semana sempre começa no domingo. 
  Por exemplo: se passada a data 12/01/2011, vai retornar um vetor com as datas começando
  no dia 09/01/2011 (domingo) e indo até dia 15/01/2011 (sábado).
  */
  if ($iSemana == null) { 
  	
    $dData      = explode('/', $dData);
    // Pego o número do dia da semana. (0 => Domingo, 6 => Sábado)
    $iDiaSemana = date('w', mktime(0, 0, 0, $dData[1], $dData[0], $dData[2]));
    for ($iCont = 0; $iCont < 7; $iCont++) {

      $aSemana[$iCont] = date('d/m/Y', mktime(0, 0, 0, $dData[1], $dData[0] + ($iCont - $iDiaSemana), $dData[2]));

    }

    return $aSemana;
    
  } else { // Retorna um arrays com os dias da semana  $iSemana do ano $iAno, começando no domingo e indo até sábado
  	
    if ($iAno == null) {
      $iAno = date('Y', db_getsession('DB_datausu'));
    }

    if ($iSemana < 1) { 
      $iAno--;
    }

    $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
    $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
    $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    /* Obtenho o número total de semanas do ano em questão e verifico se a semana solicitada é menor ou igual 
       a este número. Se não for, vou para o ano seguinte, até, que o número da semana seja menor ou igual
       ao número total de semanas do ano solicitado
    */
    while ($iSemana > $iTotalSemanasAno) {

      $iSemana -= $iTotalSemanasAno;
      $iAno++; // Vou para o próximo ano
      $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
      $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
      $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    }

     /* Obtenho o número total de semanas do ano anterior ($iAno--) e somo ao número da semana solicitada
        enquanto o  número da semana for menor que 1 
    */   
    while ($iSemana < 1) {

      $iSemana += $iTotalSemanasAno;
      if ($iSemana > 0) {
        break;
      }
      $iAno--; // Vou para o ano anterior
      $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
      $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
      $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    }


    /* Considerando que cada mês tenha 6 semanas (máximo), começo a busca da semana desejada 
       a partir de um mês próximo ao que contém a semana desejada
    */
    $iMes       = ceil($iSemana / 6); // Se algum dia der problema, dá pra dividir por um número maior (7, 8)
    $iDia       = -6;
    $iSemanaTmp = 0;
    while ($iSemanaTmp != $iSemana) { // Percorro cada semana na busca da semana desejada

      $iDia      += 7; // Vou  para a próxima semana
      $iSemanaTmp = date('W', mktime(0, 0, 0, $iMes, $iDia, $iAno));

    }
    /* Se o dia da semana for 0 (domingo), tenho que diminuir pelo menos um 1 dia, pois para a função date,
       quando informado o parâmetro W, a semana é contada começando em segunda, e na rotina, como começando em
       domingo, então, se eu passar um domingo, para a função, este será o primeiro dia, enquando que na verdade
       nem faria parte da semana solicitada, pois seria o primeiro dia após o término da semana (que termina
       em sábado)
    */
    if (date('w', mktime(0, 0, 0, $iMes, $iDia, $iAno)) == 0) {
      $iDia -= 1; // Sábado, último dia da semana desejada
    }
    /* Já encontrei um dia da semana desejada, então basta obter os demais dias da semana, usando a mesma função,
       passando somente o primeiro argumento */
    return montasemana(date('d/m/Y', mktime(0, 0, 0, $iMes, $iDia, $iAno)));

  }

}

function somardata($data, $dias= 0, $meses = 0, $ano = 0) {
	
  $data     = explode("/", $data);
  $novadata = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,   $data[0] + $dias, $data[2] + $ano));
  return $novadata;
  
}


/* Descomente para debug
for ($iCont = 1; $iCont < 301; $iCont++) {

  echo "Semana $iCont: ";
  var_dump(montasemana('', $iCont, 2010));
  echo "\n";

}
*/

echo "<option value=\"\"></option>";

if ($mes == 0) { // Nenhum mês selecionado
  exit();
}


/* Monto as semanas do mês, começando da semana que engloba o dia 1º do mês e indo até a última semana
   que possua dia(s) do mês
*/
$iCont   = 1;
$dData   = "01/$mes/".$ed52_i_ano;
do {

  $aSemana = montasemana($dData);
  $aData   = explode('/', $aSemana[0]);
  if ($iCont > 1 && $aData[1] != $mes) { // A semana já começa com dias do próximo mês, então, saio do laço
    break;
  }
  $aData   = explode('/', $aSemana[6]);
  $iSemana = date('W', mktime(0, 0, 0, $aData[1], $aData[0], $aData[2]));

  /* 30 é um número intermediário, pois esperasse valores entre 46 e 53 para as semanas de dezembro.
     Se o número for menor (geralmente 1), quer dizer que já é a semana do outro ano, então, somo
     o valor da $iSemana ao valor do máximo de semanas do ano
  */
  if ($mes == '12' && $iSemana < 30) {

    $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $ed52_i_ano));
    $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $ed52_i_ano));
    $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;
    $iSemana         += $iTotalSemanasAno;

  }

 /* 30 é um número intermediário, pois esperasse valores entre 1 e 5 para as semanas de dezembro.
     Se o número for maior (geralmente 52 ou 53), quer dizer que é a semana do ano anterior, então,
     diminuo o valor da $iSemana do numero de semanas do ano anterior, resultando um número <= 0, que
     representa a semana do ano anterior em relação ao ano corrente
  */
  if ($mes == '01' && $iSemana > 30) {

    $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $ed52_i_ano - 1));
    $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $ed52_i_ano - 1));
    $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;
    $iSemana         -= $iTotalSemanasAno;

  }

  echo '<option value="'.$iSemana.'">  '.$iCont.' - de '.$aSemana[0].' &aacute; '.$aSemana[6].'</option>';
  $dData = somardata($aSemana[6], 1); // Somo 1 dia
  $iCont++;

} while (true)
	
?>