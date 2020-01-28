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

function db_calcula_dac($d_digitavel){
  $a = 2;
  $tot_x = 0;
  $totv = strlen($d_digitavel)-1;
  for($i=$totv;$i>-1;$i--){
    $total_x = (substr($d_digitavel,$i,1)*$a);
    if( $total_x > 9)
      $total_x = $total_x - 9;
    $tot_x = $tot_x + $total_x;
    if($a == 2)
      $a = 1;
    else
      $a = 2;
  }   
  //** digito do 2o. campo => resto
  $resto = $tot_x - (intval($tot_x / 10) * 10);
  if($resto > 0)
    $resto = 10 - $resto;
  return $resto;
}
function db_barras($banco,$moeda,$valortit,$nossonumero,$codcedente,$agencia="",$carteira="",$dtvenc){
global $linhadigitavel;
global $codigobarras;
if($banco == 104){
   //db_barras(104,9,100,1111111,'8200','0461','00600000037');
   //echo "linha:".$linha_digitavel."<br>";
   //echo "barras".$barras."<br>";
   //  CAIXA ECONOMICA FEDERAL
   //
   //  0   0    1    1    2    2    3    3    4   4
   //  1...5....0....5....0....5....0....5....0...4
   // 
   //  104MDVVVVVVVVVVVVVVNNNNNNNNNNCCCCCCCCCCCCCCC
   //
   //  onde: 104: codigo do banco
   //          M: 2=moeda variavel ou 9=real
   //          D: digito do codigo de barras
   //          V: valor do titulo
   //          N: nosso numero
   //          C: codigo cedente
   //
   //  obs.: o "nosso numero" deve vir sem o digito de controle
   // 
   if($moeda == 2)
      $valortit = "0000000000";
   else 
      if($moeda == 9){
        $valortit = db_sqlformatar($valortit*100,10,'0');

        // $valortit = db_sqlformatar($valortit*100,14,"0");
		// echo $valortit."<br>";
	  }
	   
   if($dtvenc < date("d/m/a",mktime(0,0,0,7,3,2000)))
     $favorecido = "1000";
   else
     $favorecido = db_sqlformatar($dtvenc-date("d/m/a",mktime(0,0,0,10,7,1997)),4,"0");

   $barras = db_sqlformatar($banco,3," ","0") . $moeda . $valortit . substr($nossonumero,0,10) . $agencia . $carteira ;

  $y=4;
  $tot_x=0;
  for($i=0;$i<43;$i++){
     $tot_x = $tot_x + (substr($barras,$i,1) * $y);
     $y = $y - 1;
     if($y < 2)
	    $y = 9;
     }
     $digito = (( intval($tot_x/11)*11)-$tot_x)*-1;
     $digito = 11 - $digito;
     if($digito < 2 || $digito > 9)
        $digito = 1;
   
     if($digito == "0"){
        $digito = "1";
     }
   // nossonumero = 8200031740
   // codigocedente = 00600000094
   // banco = 104
   // moeda = 2
   
   $codigobarras = db_sqlformatar($banco,3,"0") . $moeda . $digito . $valortit . substr($nossonumero,0,10) . "$agencia" . "$carteira" ;
   //$digito1 = db_calcula_mod10($banco.$moeda.substr($barras,20,5));
   //$digito2 = db_calcula_mod10($banco.$moeda.substr($barras,25,10));
   //$digito3 = db_calcula_mod10($banco.$moeda.substr($barras,30,10));

   $digito1 = db_calcula_dac(db_sqlformatar($banco,3,"0").$moeda.substr($barras,18,5));
   $digito2 = db_calcula_dac(substr($barras,23,10));
   $digito3 = db_calcula_dac(substr($barras,33,10));
   //
   if($moeda == 2){
      $linha1  = db_sqlformatar($banco,3,"0") . $moeda . substr($barras,18,5) . $digito1  . 
                 substr($barras,23,10) . $digito2 . substr($barras,33,10) .
                 $digito3 . $digito . "000";
      //
      $linhadigitavel  =  substr($linha1,0,5).".".substr($linha1,5,5)." ".substr($linha1,10,5)
                 .".".substr($linha1,15,6)." ".substr($linha1,21,5).".".substr($linha1,26,6)
                 ."  ".substr($linha1,32,1)."   ".substr($linha1,0,3);
   }else{
      $linha1  = $banco  . $moeda . substr($barras,18,5) . $digito1  .  
                 substr($barras,23,10) . $digito2 . substr($barras,33,10) .
                 $digito3 . $digito . $valortit;
      //
      $linhadigitavel  =  substr($linha1,0,5).".".substr($linha1,5,5)." ".substr($linha1,10,5)
                 .".".substr($linha1,15,6)." ".substr($linha1,21,5).".".substr($linha1,26,6)
                 ."  ".substr($linha1,32,1)." ".$valortit;
   }
   //
}else{
   $barras = "0";
   $linha_digitavel = "0";
}
}
?>