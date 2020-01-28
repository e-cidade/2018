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
  retorna todos os dias da semana da data passada. A semana sempre come�a no domingo. 
  Por exemplo: se passada a data 12/01/2011, vai retornar um vetor com as datas come�ando
  no dia 09/01/2011 (domingo) e indo at� dia 15/01/2011 (s�bado).
  */
  if ($iSemana == null) { 
  	
    $dData      = explode('/', $dData);
    // Pego o n�mero do dia da semana. (0 => Domingo, 6 => S�bado)
    $iDiaSemana = date('w', mktime(0, 0, 0, $dData[1], $dData[0], $dData[2]));
    for ($iCont = 0; $iCont < 7; $iCont++) {

      $aSemana[$iCont] = date('d/m/Y', mktime(0, 0, 0, $dData[1], $dData[0] + ($iCont - $iDiaSemana), $dData[2]));

    }

    return $aSemana;
    
  } else { // Retorna um arrays com os dias da semana  $iSemana do ano $iAno, come�ando no domingo e indo at� s�bado
  	
    if ($iAno == null) {
      $iAno = date('Y', db_getsession('DB_datausu'));
    }

    if ($iSemana < 1) { 
      $iAno--;
    }

    $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
    $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
    $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    /* Obtenho o n�mero total de semanas do ano em quest�o e verifico se a semana solicitada � menor ou igual 
       a este n�mero. Se n�o for, vou para o ano seguinte, at�, que o n�mero da semana seja menor ou igual
       ao n�mero total de semanas do ano solicitado
    */
    while ($iSemana > $iTotalSemanasAno) {

      $iSemana -= $iTotalSemanasAno;
      $iAno++; // Vou para o pr�ximo ano
      $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
      $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
      $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    }

     /* Obtenho o n�mero total de semanas do ano anterior ($iAno--) e somo ao n�mero da semana solicitada
        enquanto o  n�mero da semana for menor que 1 
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


    /* Considerando que cada m�s tenha 6 semanas (m�ximo), come�o a busca da semana desejada 
       a partir de um m�s pr�ximo ao que cont�m a semana desejada
    */
    $iMes       = ceil($iSemana / 6); // Se algum dia der problema, d� pra dividir por um n�mero maior (7, 8)
    $iDia       = -6;
    $iSemanaTmp = 0;
    while ($iSemanaTmp != $iSemana) { // Percorro cada semana na busca da semana desejada

      $iDia      += 7; // Vou  para a pr�xima semana
      $iSemanaTmp = date('W', mktime(0, 0, 0, $iMes, $iDia, $iAno));

    }
    /* Se o dia da semana for 0 (domingo), tenho que diminuir pelo menos um 1 dia, pois para a fun��o date,
       quando informado o par�metro W, a semana � contada come�ando em segunda, e na rotina, como come�ando em
       domingo, ent�o, se eu passar um domingo, para a fun��o, este ser� o primeiro dia, enquando que na verdade
       nem faria parte da semana solicitada, pois seria o primeiro dia ap�s o t�rmino da semana (que termina
       em s�bado)
    */
    if (date('w', mktime(0, 0, 0, $iMes, $iDia, $iAno)) == 0) {
      $iDia -= 1; // S�bado, �ltimo dia da semana desejada
    }
    /* J� encontrei um dia da semana desejada, ent�o basta obter os demais dias da semana, usando a mesma fun��o,
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

if ($mes == 0) { // Nenhum m�s selecionado
  exit();
}


/* Monto as semanas do m�s, come�ando da semana que engloba o dia 1� do m�s e indo at� a �ltima semana
   que possua dia(s) do m�s
*/
$iCont   = 1;
$dData   = "01/$mes/".$ed52_i_ano;
do {

  $aSemana = montasemana($dData);
  $aData   = explode('/', $aSemana[0]);
  if ($iCont > 1 && $aData[1] != $mes) { // A semana j� come�a com dias do pr�ximo m�s, ent�o, saio do la�o
    break;
  }
  $aData   = explode('/', $aSemana[6]);
  $iSemana = date('W', mktime(0, 0, 0, $aData[1], $aData[0], $aData[2]));

  /* 30 � um n�mero intermedi�rio, pois esperasse valores entre 46 e 53 para as semanas de dezembro.
     Se o n�mero for menor (geralmente 1), quer dizer que j� � a semana do outro ano, ent�o, somo
     o valor da $iSemana ao valor do m�ximo de semanas do ano
  */
  if ($mes == '12' && $iSemana < 30) {

    $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $ed52_i_ano));
    $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $ed52_i_ano));
    $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;
    $iSemana         += $iTotalSemanasAno;

  }

 /* 30 � um n�mero intermedi�rio, pois esperasse valores entre 1 e 5 para as semanas de dezembro.
     Se o n�mero for maior (geralmente 52 ou 53), quer dizer que � a semana do ano anterior, ent�o,
     diminuo o valor da $iSemana do numero de semanas do ano anterior, resultando um n�mero <= 0, que
     representa a semana do ano anterior em rela��o ao ano corrente
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