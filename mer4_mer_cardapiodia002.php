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

//MODULO: educação
require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_tprefeicao_classe.php");
include("classes/db_mer_tipocardapio_classe.php");
include("classes/db_diasemana_classe.php");
include("classes/db_feriado_classe.php");
include("dbforms/db_funcoes.php");
$clmer_cardapiodia  = new cl_mer_cardapiodia;
$clmer_cardapiodata = new cl_mer_cardapiodata;
$clmer_cardapio     = new cl_mer_cardapio;
$clmer_tprefeicao   = new cl_mer_tprefeicao;
$clmer_tipocardapio = new cl_mer_tipocardapio;
$cldiasemana        = new cl_diasemana;
$clferiado          = new cl_feriado;
$escola             = db_getsession("DB_coddepto");
$db_botao1          = false;
?>
<html>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/prototype.js"></script>
<style>
.cabec{
 text-align: left;
 font-size: 10;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
</style>
<?

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

function semanasigla($data,$comp = 0,$num = 7) {
  if ($num == 7) {
	$data = explode("/", $data);
	$fator=date("w", mktime(0,0,0,$data[1],$data[0],$data[2]));
  } else {
    $fator=$num;
  }
  $sigla="N/A";
  switch ($fator) {
    case 0:
    	
      if ($comp == 0) {
      	$sigla = "D";
      } else{
      	$sigla = "Domigo";
      }
      break;
      
    case 1:
    	
      if ($comp == 0) {
      	$sigla = "S";
      } else {
      	$sigla = "Segunda";
      }
      break;
      
    case 2:
    	
      if ($comp == 0) {
      	$sigla = "T";
      } else {
      	$sigla = "Terça";
      }
      break;
      
    case 3:
    	
      if ($comp == 0) {
      	$sigla = "Q";
      } else {
      	$sigla = "Quarta";
      }
      break;
      
    case 4:
    	
      if ($comp == 0) {
      	$sigl = "Q";
      } else { 
      	$sigla = "Quinta";
      }
      break;
      
    case 5:
    	
      if ($comp == 0) {
      	$sigla = "S";
      } else {
      	$sigla = "Sexta";
      }
      break;
      
    case 6:
    	
      if ($comp == 0) {
      	$sigla = "S";
      } else {
        $sigla = "Sabado";
      }
      break;
      
  }
  return $sigla;
}

function numerosemana($data,$ano = "0") {
	
  if ($ano=="0") {
  	
    $data      = explode("/",$data);
    $timestamp = mktime(0, 0, 0, $data[1], $data[0], $data[2]);
    
   } else {
   	
     $data      = date("t/m/Y", mktime(0, 0, 0, 2, 1, $ano)); 
     $data      = explode("/",$data);
     $timestamp = mktime(0, 0, 0, $data[1], $data[0], $data[2]);
     
   }
   return date("W", $timestamp);
}

function quantsemana($mes,$ano = "0") {
	
  if ($ano=="0") {
    $ano=date("Y",db_getsession("DB_datausu"));
  }
  $fator     = 0;
  $semana[7] = "01/$mes/$ano";
  do {
  	
	$fator++;
	$weeke = date("w", mktime(0,0,0,substr($semana[7],3,2),substr($semana[7],0,2),substr($semana[7],6,4)));
	for ($s=0;$s<8;$s++) {
	  $semana[$s]=date("d/m/Y", mktime(0,0,0,
	                                   substr($semana[7],3,2),
	                                   (substr($semana[7],0,2))+($s+2-($weeke+1)), 
	                                   substr($semana[7],6,4)
	                                  )
	                  );
    }
  } while (substr($semana[7],3,2) == $mes);
  return $fator;
}

function somardata($data, $dias= 0, $meses = 0, $ano = 0) {
	
  $data     = explode("/", $data);
  $novadata = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,   $data[0] + $dias, $data[2] + $ano) );
  return $novadata;
  
}
?>
<body>
<form name="form1" method="post">
<?
if (isset($semana)) {?>

  <center>
   Refeição
   <select name="refeicao" id="refeicao" value="0">
    <option value="0">::Selecione>></option>
    <?$result=$clmer_cardapio->sql_record($clmer_cardapio->sql_query_lista());
     for ($x=0;$x<$clmer_cardapio->numrows;$x++) {
     	
       db_fieldsmemory($result,$x);
       echo "<option value=\"$me01_i_codigo\">$me01_c_nome</option>";
       
     }
    ?>
   </select>
   <br><br>
    <table cellspacing="0" cellpading="0" border="1" bordercolor="#000000">
     <tr class='cabec'>
      <td><center>Refeição<br>Horario<center></td>
     <?
     if ($diasemana=='8') {
     	
       $resultdias = $cldiasemana->sql_record(
                                              $cldiasemana->sql_query_rh("",
                                                                         "ed32_i_codigo,ed32_c_descr",
                                                                         "ed32_i_codigo",
                                                                         " ed04_c_letivo = 'S' 
                                                                           AND ed04_i_escola = $escola"
                                                                        )
                                             );
       $calibra = (pg_result($resultdias,0,0)-1);
       $calibra2 = pg_result($resultdias,(pg_num_rows($resultdias)-1),0);
       
     } else {
     	
       $calibra  = $diasemana;
       $calibra2 = $diasemana+1;
       
     }
     $semana=montasemana("",$semana+1,$calendario);
     for ($dia=$calibra;$dia<$calibra2;$dia++) {
     	
       $sigla = semanasigla("",1,$dia);
       $d1    = substr($semana[$dia],0,5);
       echo "<td><center>$sigla<br><b>$d1</b></center></td>";
       
     }
     ?>
     </tr>
     <?
     $campos = " me03_i_codigo,me03_c_tipo,me03_c_inicio,me03_c_fim ";
     $result_tiporefeicao=$clmer_tprefeicao->sql_record(
                                                        $clmer_tprefeicao->sql_query("",
                                                                                     $campos,
                                                                                     "me03_i_orden",
                                                                                     " me03_i_escola=$escola"
                                                                                    )
                                                       );
     for ($y=0;$y<$clmer_tprefeicao->numrows;$y++) {
     	
       db_fieldsmemory($result_tiporefeicao,$y);
       ?>
     <tr>
	  <td width="80" height="15" class='cabec'><center><?=$me03_c_tipo?><br>
	                                                   <?=$me03_c_inicio?> - <?=$me03_c_fim?></center></td>
	   <?
       $d1=$semana[$calibra];
       for ($dia=$calibra;$dia<$calibra2;$dia++) {
       	
         $d2=substr($d1,6,4)."-".substr($d1,3,2)."-".substr($d1,0,2);
         $campos = " me12_i_codigo,me01_i_codigo,me01_c_nome " ;
         $sWhere = " me12_d_data='$d2' AND me12_i_tprefeicao=$me03_i_codigo "; 
         $sWhere = " AND me12_i_escola=$escola AND me12_i_cardapiotipo=$cardapio";
         $result2 = $clmer_cardapiodia->sql_record(
                                                   $clmer_cardapiodia->sql_query_horario("",
                                                                                         $campos,
                                                                                         "",
                                                                                         $sWhere
                                                                                      )
                                                );
         $quadro   = "Q".$dia."_".$me03_i_codigo; // <- y = tiporefeicao
         $blokeado = "";
         if ($clmer_cardapiodia->numrows==0) {
         	
           //verificar se o dia é um dia letivo valido ou se é feriado
           $resultferiado=$clferiado->sql_record(
                                                 $clferiado->sql_query("",
                                                                       "*",
                                                                       "",
                                                                       " ed54_d_data='$d2' and ed54_c_dialetivo='N'"
                                                                      )
                                                );
           if ($clferiado->numrows==0) {
           	
             if (substr($d1,3,2)==$mes) {
             	
               $estado  = "disponivel";
               $nome    = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
               $quadro .= "_0";
               
             } else {
             	
               $estado   = "Blokeado";
               $nome     = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
               $blokeado = " disabled ";
               $quadro  .= "_0";
               
             }
           } else {
           	
             db_fieldsmemory($resultferiado,0);
             $estado   = "indisponivel";
             $nome     = substr($ed54_c_descr,0,11);
             $blokeado = " disabled ";
             $quadro  .= "_0";
             
           }
         } else {
         	
           db_fieldsmemory($result2,0);
           //verificar se ja não foi dado baixa no horario
           $resustdata=$clmer_cardapiodata->sql_record(
                                                       $clmer_cardapiodata->sql_query("",
                                                                                      "*",
                                                                                      "",
                                                                                      "me37_i_cardapiodia = $me12_i_codigo"
                                                                                     )
                                                      );
           if ($clmer_cardapiodata->numrows!=0) {
           	
             $estado   = "Ocupado";
             $nome     = substr($me01_c_nome,0,11);
             $quadro  .= "_$me01_i_codigo";
             $blokeado = " disabled ";
             
           } else {
           	
             $estado  = "Ocupado";
             $nome    = substr($me01_c_nome,0,11);
             $quadro .= "_$me01_i_codigo";
             
           }
         }
         ?>
         <td align="center" width="80" height="15">
           <input type="button" style="font: 4px " name="<?=$quadro?>" id="<?=$quadro?>" 
                  value="<?=$nome?>" <?=$blokeado?> 
                  onclick="js_botaohora(this.name);">
	     </td>
         <?
         $d1=somardata($d1,1);
       }
       ?>
   </tr>
   <?}?>
 </table>
</center>
<center>
 <br>
 <br><br>
</center>
<?
} else {
  //Grade mensal
}?>
  <center>
   <br>
   <input name="lista_include" id="lista_include" type="hidden" valor="">
   <input name="lista_exclude" id="lista_exclude" type="hidden" valor="">
   <br><br>
  </center>
 </form>
</body>
<script type="text/javascript">
function js_edit_lista(op,quadro,listax){
	
  tem        = false;
  nova_lista = "";
  valor      = quadro.substr(1);     
  valor2     = valor.split('_');
  valor      = valor2[0]+'_'+valor2[1];
  //define lista a ser tratada
  if(listax==1){
	  
    lista2 = document.form1.lista_include.value;
    lista  = lista2.split('Q');
    
  } else{
    lista = document.form1.lista_exclude.value.split('Q');
  }
  //Percorre a lista verificando e montando uma nova lista no caso de exclusão
  for(x=1;x<lista.length;x++){
	  
    codernada = lista[x].split('_');   
    codernada = codernada[0]+'_'+codernada[1];
    if (codernada==valor) {
      tem=true;
    } else{
      nova_lista=nova_lista+'Q'+codernada+'_'+valor2[2];
    }
  }
  //aplica as modificações inclusão ou exclusão dependendo da opção
  if ((op==1) && (tem==false)) {
	  
    if (listax == 1) {
         
      lista = document.form1.lista_include.value+'Q'+valor2[0]+'_'+valor2[1]+'_'+document.form1.refeicao.value;
      document.form1.lista_include.value=lista;
      
    } else {
        
      lista = document.form1.lista_exclude.value+'Q'+valor2[0]+'_'+valor2[1]+'_'+document.form1.refeicao.value;
      document.form1.lista_exclude.value=lista;
      
    }
  } else {
	  
    if (op == 2) {
        
      if (listax==1){
        document.form1.lista_include.value=nova_lista;
      } else {
        document.form1.lista_exclude.value=nova_lista;
      }
    }
  }
  return tem;
}

function js_botaohora(quadro) {
	
  mod=quadro.split('_');
  if (mod[2] == '0') {
	  
    if (document.form1.refeicao.value!=0){
          
      resultado=js_edit_lista(1,quadro,1);
      if (resultado==false){
          
        refeicao                              = document.form1.refeicao.options[document.form1.refeicao.selectedIndex].text
        document.getElementById(quadro).value = refeicao.substr(0,9);
        document.getElementById(quadro).name  = mod[0]+'_'+mod[1]+'_'+document.form1.refeicao.value; 
        document.getElementById(quadro).id    = mod[0]+'_'+mod[1]+'_'+document.form1.refeicao.value;
        
      }
    } else{
      alert('Selecione uma refeição!');
    } 
  } else {
	  
    if (confirm('Deseja excluir?')) {
        
           //excluida lista de inclusão se tiver
      resultado=js_edit_lista(2,quadro,1);
           //inclui na lista de exclusão
      resultado=js_edit_lista(1,quadro,2);
      if (resultado == false) {
             
        document.getElementById(quadro).value = '               ';
        document.getElementById(quadro).name  = mod[0]+'_'+mod[1]+'_0'; 
        document.getElementById(quadro).id    = mod[0]+'_'+mod[1]+'_0';
        
      }
    }
  }
}  
</script>
</html>