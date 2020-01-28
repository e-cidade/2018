<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

function db_calculo_dac ( $codigo_a_calcular  ) {
   $tamanho_codigo = strlen($codigo_a_calcular) ;  
   echo $codigo_a_calcular;
   echo "<br>";
   echo $tamanho_codigo;
   echo "<br>";
   for ( $i = 0; $i < $tamanho_codigo; $i ++ ){
       $m_barras[$i] = substr($codigo_a_calcular,$i,1) + 0 ;
   echo $m_barras[$i];
   echo "<br>";
   }
   for ( $i = $tamanho_codigo ; $i > -1 ; $i -- ) {
     if ( $i = $tamanho_codigo  ) {
	   $m_produto[$i] = 2;
     }else{
	   if ( $m_produto[$i+1] = 2 ) {
	     $m_produto[$i] = 1;
	   }else{
	     $m_produto[$i] = 2;
	   }
     }
   }
   $resultado = 0;
   for ( $i = 0; $i < $tamanho_codigo ; $i ++ ) {
       $m_resultado[$i] = sqlformat( round( $m_barras[$i] * $m_produto[$i],2),2,"0");
       $resultado = $resultado + substr($m_resultado[$i],0,1) + substr( $m_resultado[$i],1,1);
   }
   $digito = $resultado % 10 ;
   $digito = substr($digito,0,1);
   if ( (!empty($digito)) and ($digito <> 0) ) {
      $digito = 10 - $digito;
   }
   return sqlformat($digito,1);
}


function db_febraban ( $codigo ) {
/*
*
*  ESTA FUNCAO GERA CODIGO DE BARRAS
*
*  PARA CONVENIO COM O BANCO
*/
   global $codigo_barras_febraban , $linha_digitavel;
   $dac_10  = db_calculo_dac( $codigo );
   $codigo_barras_febraban = substr($codigo,0,1) . substr($codigo,1,1) . substr($codigo,2,1) . $dac_10 . substr($codigo,4);
   $linha_digitavel        = substr($codigo_barras_febraban,0,11) . db_calculo_dac(substr($codigo_barras_febraban,0,11)) . 
                             substr($codigo_barras_febraban,11,11) . db_calculo_dac(substr($codigo_barras_febraban,11,11)) . 
                             substr($codigo_barras_febraban,22,11) . db_calculo_dac(substr($codigo_barras_febraban,22,11)) . 
                             substr($codigo_barras_febraban,33,11) . db_calculo_dac(substr($codigo_barras_febraban,33,11)) ;
return linha_digitavel;
}

?>