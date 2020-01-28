<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("fpdf151/pdf.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_funcao');
$clrotulo->label('r37_descr');

$oRequest   = db_utils::postmemory($_REQUEST);
$iInstit    = db_getsession("DB_instit");
$sWhere     = '';
$sFiltro    = '';
$sIntervalo = '';
$iAnoUsu    = $ano;
$iMesUsu    = $mes;

/**
 * $tipo > l - Lotação
 * 				 o - Secretaria
 * 				 c - Cargo
 * 
 * $func > lista servidor 't' ou 'f'
 * 
 * $lShowRemuneracao > imprimir remuneração 't' ou 'f'
 * 
 * $lShowEndereco > mostrar endereço 't' ou 'f'
 */

if(isset($colunas) && $colunas != ''){
  $sWhere .= " and rh02_codreg in (".$colunas.") ";
}

if($tipo == 'c'){
  $head3 = "RELATÓRIO DE CARGOS";
  if($ordem == 'a'){
    $sCampos = " rh37_funcao as quebra2, rh37_descr as quebra1 , rh37_vagas, rh37_funcao||' - '|| rh37_descr as imprime ";
    $sOrder  = ' order by rh37_descr,z01_nome';
  }else{
    $sCampos = " rh37_funcao as quebra1, rh37_descr as quebra2 , rh37_vagas , rh37_funcao||' - '|| rh37_descr as imprime ";
    $sOrder  = ' order by rh37_funcao,z01_nome';
  }
 if(isset($cai) && trim($cai) != "" && isset($caf) && trim($caf) != ""){
    // Se for por intervalos e vier lotação inicial e final
    $sWhere     .= " and rh37_funcao between '".$cai."' and '".$caf."' ";
    $sIntervalo .= " DE ".$cai." A ".$caf;
  }else if(isset($cai) && trim($cai) != ""){
    // Se for por intervalos e vier somente lotação inicial
    $sWhere    .= " and rh37_funcao >= '".$cai."' ";
    $sIntervalo.= " SUPERIORES A ".$cai;
  }else if(isset($caf) && trim($caf) != ""){
    // Se for por intervalos e vier somente lotação final
    $sWhere    .= " and rh37_funcao <= '".$caf."' ";
    $sIntervalo.= " INFERIORES A ".$caf;
  }else if(isset($fca) && trim($fca) != ""){
    // Se for por selecionados
    $sWhere  .= " and rh37_funcao in ('".str_replace(",","','",$fca)."') ";
    $sFiltro .= " SELECIONADAS";
  }
}elseif($tipo == 'l'){
  $head3  = "RELATÓRIO DE LOTAÇÕES";
  if($ordem == 'a'){
    $sCampos = " r70_estrut as quebra2, r70_descr as quebra1 , r70_estrut||' - '||r70_descr as imprime ";
    $sOrder = ' order by r70_descr,z01_nome';
  }else{
    $sCampos = " r70_estrut as quebra1, r70_descr as quebra2 , r70_estrut||' - '||r70_descr as imprime ";
    $sOrder = ' order by r70_estrut,z01_nome';
  }
  if(isset($lti) && trim($lti) != "" && isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier local inicial e final
    $sWhere    .= " and r70_estrut between '".$lti."' and '".$ltf."' ";
    $sIntervalo.= " DE ".$lti." A ".$ltf;
  }else if(isset($lti) && trim($lti) != ""){
    // Se for por intervalos e vier somente local inicial
    $sWhere    .= " and r70_estrut >= '".$lti."' ";
    $sIntervalo.= " SUPERIORES A ".$lti;
  }else if(isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier somente local final
    $sWhere    .= " and r70_estrut <= '".$ltf."' ";
    $sIntervalo.= " INFERIORES A ".$ltf;
  }else if(isset($flt) && trim($flt) != ""){
    // Se for por selecionados
    $sWhere  .= " and r70_estrut in ('".str_replace(",","','",$flt)."') ";
    $sFiltro .= " SELECIONADOS";
  }

}else{
  $head3 = "RELATÓRIO DE SECRETARIAS";
  if($ordem == 'a'){
    $sCampos = " o40_descr as quebra1, o40_orgao as quebra2 , o40_orgao||' - '||o40_descr as imprime ";
    $sOrder = ' order by o40_descr,z01_nome';
  }else{
    $sCampos = " o40_descr as quebra2, o40_orgao as quebra1, o40_orgao||' - '||o40_descr as imprime ";
    $sOrder = ' order by o40_orgao,z01_nome';
  }
  if(isset($ori) && trim($ori) != "" && isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier órgão inicial e final
    $sWhere    .= " and o40_orgao between ".$ori." and ".$orf;
    $sIntervalo.= " DE ".$ori." A ".$orf;
  }else if(isset($ori) && trim($ori) != ""){
    // Se for por intervalos e vier somente órgão inicial
    $sWhere .= " and o40_orgao >= ".$ori;
    $head5.= " SUPERIORES A ".$ori;
  }else if(isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier somente órgão final
    $sWhere    .= " and o40_orgao <= ".$orf;
    $sIntervalo.= " INFERIORES A ".$orf;
  }else if(isset($for) && trim($for) != ""){
    // Se for por selecionados
    $sWhere  .= " and o40_orgao in (".$for.") ";
    $sFiltro .= " SELECIONADOS";
  }
}

/**
 * Busca Seleção
 */
$sWhereSelecao = '';
if( !empty( $oRequest->selecao ) ) {
  $oDaoSelecao   = new cl_selecao();
  $sWhereSelecao = $oDaoSelecao->getCondicaoSelecao($oRequest->selecao);
}

/**
 * Caso seja selecionada selação sera o unico criterio do where
 */
if( !empty( $sWhereSelecao ) ){
	$sWhere = ' and ' . $sWhereSelecao;
}else{
	$head3 .= $sFiltro;
	$head3 .= $sIntervalo;
}

/**
 * Buscamos a query do relatório
 */
$oDaoRhpessoalmov  = new cl_rhpessoalmov();
$sSqlRhpessoalmov  = $oDaoRhpessoalmov->sql_servidorCargoLotacaoSecretarias( $sCampos, $sWhere, $sOrder, $iInstit, $iAnoUsu, $iMesUsu );
$rsDAORhpessoalmov = db_query($sSqlRhpessoalmov);
if ( pg_numrows($rsDAORhpessoalmov) == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários no período de '.$iMesUsu.' / '.$iAnoUsu);
}

$pdf    = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$func    = 0;
$func_c  = 0;
$tot_c   = 0;
$total   = 0;
$troca 	 = 1;
$alt 		 = 4;
$funcao  = '';
$head5   = "PERÍODO : ".$iMesUsu." / ".$iAnoUsu;

for( $iRegistro = 0; $iRegistro < pg_numrows($rsDAORhpessoalmov); $iRegistro++ ){
	
  db_fieldsmemory( $rsDAORhpessoalmov, $iRegistro );
  
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  	
    if($lShowEndereco){
      $pdf->addpage("L");
    }else{
      $pdf->addpage();
    }
      
    if($funcion == 't'){
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      if($lShowEndereco == 't'){
      	
        $pdf->cell(60,$alt,'NOME' ,1,0,"C",1);
        $pdf->cell(20,$alt,'ADMISSÃO',1,0,"C",1);
        
        if($tipo == 'o'){
          $pdf->cell(50,$alt,'FUNÇÃO'   ,1,0,"C",1);
          $pdf->cell(45,$alt,'ENDEREÇO' ,1,0,"C",1);
          $pdf->cell(40,$alt,'BAIRRO'   ,1,0,"C",1);
          $pdf->cell(25,$alt,'MUNICÍPIO',1,0,"C",1);
          $pdf->cell(07,$alt,'UF'		    ,1,0,"C",1);
          $pdf->cell(20,$alt,'CEP'		  ,1,1,"C",1);
        }else{
          $pdf->cell(45,$alt,'ENDEREÇO' ,1,0,"C",1);
          $pdf->cell(45,$alt,'BAIRRO'   ,1,0,"C",1);
          $pdf->cell(45,$alt,'MUNICÍPIO',1,0,"C",1);
          $pdf->cell(10,$alt,'UF'		    ,1,0,"C",1);
          $pdf->cell(20,$alt,'CEP'		  ,1,1,"C",1);
        }
      
      } else {
      
      	$pdf->cell(60,$alt,'NOME'	      ,1,0,"C",1);
        $pdf->cell(20,$alt,'C.HORÁRIA'  ,1,0,"C",1);
        if($lShowRemuneracao == 't'){
          if($tipo == 'l'){
            $pdf->cell(80,$alt,'CARGO'	,1,0,"C",1);
          }else{
            $pdf->cell(80,$alt,'LOTAÇÃO',1,0,"C",1);
          }
          $pdf->cell(30,$alt,'REMUNERAÇÃO',1,1,"C",1);
        }else{
          $pdf->cell(20,$alt,'ADMISSÃO'    ,1,0,"C",1);
          $pdf->cell(80,$alt,'LOTAÇÃO'  ,1,0,"C",1);
          $pdf->cell(60,$alt,'CARGO'    ,1,1,"C",1);
        } 
      }
    }

    $troca = 0;
  }
   
  if ( $funcao != $quebra1 ){
  	
   if($funcao != ''){
     $pdf->ln(1);
     $pdf->cell(75,$alt,'Total: '.$func_c,0,0,"L",0);
	   $func_c = 0;
	   $tot_c  = 0;
	   
     if ( $quebrar == 's' ){
     	
       $pdf->ln(1);
       if($lShowEndereco){
         $pdf->addpage("L");
       }else{
         $pdf->addpage();
       }
         
       if($funcion == 't'){
         $pdf->setfont('arial','b',8);
         $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
         
         if($lShowEndereco == 't'){
         	
           $pdf->cell(60,$alt,'NOME'    ,1,0,"C",1);
           $pdf->cell(20,$alt,'ADMISSÃO',1,0,"C",1);
           
           if($tipo == 'o'){
             $pdf->cell(50,$alt,'FUNÇÃO'   ,1,0,"C",1);
             $pdf->cell(50,$alt,'ENDEREÇO' ,1,0,"C",1);
             $pdf->cell(40,$alt,'BAIRRO'   ,1,0,"C",1);
             $pdf->cell(25,$alt,'MUNICÍPIO',1,0,"C",1);
             $pdf->cell(07,$alt,'UF'	     ,1,0,"C",1);
             $pdf->cell(15,$alt,'CEP'	     ,1,1,"C",1);
           }else{
             $pdf->cell(50,$alt,'ENDEREÇO' ,1,0,"C",1);
             $pdf->cell(45,$alt,'BAIRRO'   ,1,0,"C",1);
             $pdf->cell(45,$alt,'MUNICÍPIO',1,0,"C",1);
             $pdf->cell(10,$alt,'UF'	     ,1,0,"C",1);
             $pdf->cell(15,$alt,'CEP'	     ,1,1,"C",1);
           }
            
         }else{
         	
            $pdf->cell(60,$alt,'NOME'	     ,1,0,"C",1);
            $pdf->cell(20,$alt,'C.HORÁRIA' ,1,0,"C",1);
            if($lShowRemuneracao == 't'){

               if($tipo == 'l'){
                 $pdf->cell(80,$alt,'CARGO'	   ,1,0,"C",1);
               }else{
                 $pdf->cell(80,$alt,'LOTAÇÃO'	 ,1,0,"C",1);
               }
               $pdf->cell(30,$alt,'REMUNERAÇÃO',1,1,"C",1);
            }else{
               $pdf->cell(20,$alt,'ADMISSÃO'   ,1,0,"C",1);
               $pdf->cell(80,$alt,'LOTAÇÃO'    ,1,0,"C",1);
               $pdf->cell(60,$alt,'CARGO'      ,1,1,"C",1);
            }
         }
       } 
     }
   }
   
   $pdf->setfont('arial','b',9);
   $pdf->ln(6);
   
   if($tipo == 'f'){
     $pdf->cell(100,$alt,$imprime.'    Vagas: '.$rh37_vagas,0,1,"L",1);
   }else{
     $pdf->cell(95,$alt,$imprime,0,1,"L",1);
   }
   
   $funcao = $quebra1;
  }
  
  if($funcion == 't'){
  	
    $pdf->setfont('arial','',7);
    $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
    
     if($lShowEndereco == 't'){
       $pdf->cell(60,$alt,$z01_nome					   				 ,0,0,"L",0);
       $pdf->cell(20,$alt,db_formatar($rh01_admiss,'d'),0,0,"C",0);
       
       if($tipo == 'o'){
         $pdf->cell(50,$alt,$rh37_descr ,0,0,"L",0);
         $pdf->cell(45,$alt,substr($endereco,0,25),0,0,"L",0);
         $pdf->cell(40,$alt,$z01_bairro ,0,0,"L",0);
         $pdf->cell(25,$alt,$z01_munic  ,0,0,"L",0);
         $pdf->cell(07,$alt,$z01_uf		  ,0,0,"C",0);
         $pdf->cell(20,$alt,db_formatar($z01_cep,'cep')	  ,0,1,"C",0);
       }else{
         $pdf->cell(45,$alt,substr($endereco,0,25),0,0,"L",0);
         $pdf->cell(45,$alt,$z01_bairro ,0,0,"L",0);
         $pdf->cell(45,$alt,$z01_munic  ,0,0,"L",0);
         $pdf->cell(10,$alt,$z01_uf     ,0,0,"C",0);
         $pdf->cell(20,$alt,db_formatar($z01_cep,'cep') ,0,1,"C",0);
       }
       
     }else{
     	
       $pdf->cell(60,$alt,$z01_nome   ,0,0,"L",0);
       $pdf->cell(20,$alt,$rh02_hrsmen,0,0,"C",0);
       
       if($lShowRemuneracao == 't'){
         if($tipo == 'l'){
           $pdf->cell(80,$alt, $rh37_descr ,0,0,"L",0);
         }else{
           $pdf->cell(80,$alt,$cod_lota.' - '.$descr_lota ,0,0,"L",0);
         }
         $pdf->cell(30,$alt,db_formatar($r02_valor,'f'),0,1,"R",0);
       }else{
         $pdf->cell(20,$alt,db_formatar($rh01_admiss,'d'),0,0,"C",0);
         $pdf->cell(80,$alt,$cod_lota.' - '.$descr_lota	 ,0,0,"L",0);
         $pdf->cell(60,$alt,$rh37_descr					 ,0,1,"L",0);
       }
     }
   }
   $func   += 1;
   $func_c += 1;
}

$pdf->ln(1);
$pdf->cell(115,$alt,'Total: '.$func_c,0,0,"L",0);

$pdf->ln(5);
$pdf->cell(115,$alt,'Total da Geral: '.$func,0,0,"L",0);

$pdf->Output();