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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("pes4_avaliaferiasrescisao.php"));
include(modification("pes4_gerafolha003.php"));
include(modification("pes4_gerafolha004.php"));

db_postmemory($HTTP_POST_VARS);

$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" style="top:15px" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
      include(modification("forms/db_frmrhpontoprovisao.php"));
      if(isset($processar) && $processar == "Processar"){
        db_criatermometro('calculo_folha1','Concluido...','blue',1,'Efetuando a geracao do Ponto ...');
        db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Calculo do Ponto ...');
      }
    ?>
    </center>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?

if(isset($processar) && $processar == "Processar" || isset($db_debug) ) {
   global $pessoal, $Ipessoal,$cfpess;
   global $d08_carnes, $db21_codcli, $rubricas, $pontofx, $matriz1, $matriz2, $subpes;
   global $m_rubr, $m_tipo, $m_media , $m_valor , $m_quant, $qten , $vlrn, $r01_taviso,$subpes_original;
   global $datainicio, $datafim, $max, $gerfsal, $gerffer, $gerfcom, $rescisao, $qmeses;
   global $r30_perai,$ponto,$r30_peraf, $r30_faltas, $r30_peri,$r30_perf;
   global $ns13,$lotacaoatual,$anousu,$mesusu;
   global $db_config;
   global $db_debug;
   global $pessoal,$cadferia,$prevfer13;
   
   if (isset($db_debug)) {
   	$db_debug = true;
   }
      
   $subpes = db_str($anousu,4)."/".db_str($mesusu,2,0,"0");
   $subpes_original = $subpes;   

   db_selectmax("db_config","select lower(trim(munic)) as d08_carnes , cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit"));
   
   if(trim($db_config[0]["cgc"]) == "90940172000138"){
      $d08_carnes = "daeb";
   }else{
      $d08_carnes = $db_config[0]["d08_carnes"];
   }
   $d08_carnes = strtolower(trim($d08_carnes)); 
   $db21_codcli = $db_config[0]["db21_codcli"];
   
   db_selectmax("cfpess","select * from cfpess where r11_anousu=".db_substr($subpes,1,4)." and r11_mesusu=".db_substr($subpes,-2)." and r11_instit = ".db_getsession("DB_instit") );
   
   $cfpess[0]["r11_fersal"] = 'f' ;
   $cfpess[0]["r11_recalc"] = false;
   $cfpess[0]["r11_mes13"]  = $mesusu;
   
   $m_rubr = array();
   $m_quant= array();
   $m_valor= array();
   $m_media= array();
   $m_tipo = array();
   $qten   = array();
   $vlrn   = array();
   $nsaldo = 30; 
        
   $sql  = " select rh01_regist as r01_regist,                                    \n";
   $sql .= "        rh01_numcgm as r01_numcgm,                                    \n";
   $sql .= "        rh01_admiss as r01_admiss,                                    \n";
   $sql .= "        rh02_tbprev as r01_tbprev,                                    \n";
   $sql .= "        rh30_regime as r01_regime,                                    \n";
   $sql .= "        rh02_hrsmen as r01_hrsmen,                                    \n";
   $sql .= "        trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac                  \n"; 
   if($tipoger == "F"){
     $sql .= "     ,case when r30_perai is null then rh01_admiss else r30_peraf+1 end as r30_perai,                                 \n";
     $sql .= "     (rh02_anousu::char(4)||'-'||lpad(rh02_mesusu::char(2),2,'0')||'-'||ndias($anousu,$mesusu)::char(2)) as r30_peraf \n"; 
   }           
   $sql .= " from rhpessoalmov                                                    \n";
   $sql .= " inner join rhlota       on  r70_codigo = rh02_lota                   \n";
   $sql .= "                        and  r70_instit = rh02_instit                 \n";
   $sql .= " inner join rhpessoal    on rh01_regist = rh02_regist                 \n";
   $sql .= " left outer join cgm     on z01_numcgm = rh01_numcgm                  \n";
   $sql .= " left join rhregime      on rh30_codreg = rhpessoalmov.rh02_codreg    \n";
   $sql .= "                        and rh30_instit = rhpessoalmov.rh02_instit    \n";
   $sql .= " left join rhpesrescisao on rh05_seqpes = rhpessoalmov.rh02_seqpes    \n";
           
   if ($tipoger == "F") {
     $sql .= "left join                                                           \n";
     $sql .= "  ( select distinct on (r30_regist ) r30_regist,r30_perai,r30_peraf \n";
     $sql .= "    from cadferia                                                   \n";
     $sql .= "    where r30_anousu= $anousu                                       \n";
     $sql .= "      and r30_mesusu= $mesusu                                       \n";
     $sql .= "      and (r30_ndias = r30_dias1 + r30_dias2 + r30_abono)           \n";
     $sql .= "    order by r30_regist, r30_perai desc )                           \n";
     $sql .= "  as cadf on r30_regist = rh01_regist                               \n";
   }
                
   $sql .= " where rh02_anousu = $anousu                                          \n";
   $sql .= "   and rh02_mesusu = $mesusu                                          \n";
   $sql .= "   and rh02_instit = ".DB_getsession("DB_instit"); 
   $sql .= "   and rh05_recis is null                                             \n"; 
   //$sql .= "   and rh02_regist = 334 \n";
   
  
   if ($tipoger == "F") {
   	
      $sql .= " and rh30_vinculo = 'A'";
      $condicaoaux = " where r91_anousu = $anousu 
                       and   r91_mesusu = $mesusu 
                       and   r91_instit = ".DB_getsession("DB_instit");
      if ($db_debug) {
      	echo "Excluindo dados da tabela pontoprovfe quando $condicaoaux <br>";
      }
      db_delete( "pontoprovfe", $condicaoaux );
   
      $condicaoaux = " where r93_anousu = $anousu 
                       and   r93_mesusu = $mesusu 
                       and   r93_instit = ".DB_getsession("DB_instit");
      if ($db_debug) {
      	echo "Excluindo dados da tabela gerfprovfer quando $condicaoaux<br>";
      }
      db_delete( "gerfprovfer", $condicaoaux );
      
   } else { 
   	
      $condicaoaux = " where r92_anousu = $anousu 
                       and   r92_mesusu = $mesusu 
                       and   r92_instit = ".DB_getsession("DB_instit");
      if ($db_debug) {
      	echo "Excluindo dados da tabela pontoprovf13 quando $condicaoaux<br>";
      }      
      db_delete( "pontoprovf13", $condicaoaux );
      
      $condicaoaux = " where r94_anousu = $anousu 
                       and   r94_mesusu = $mesusu 
                       and   r94_instit = ".DB_getsession("DB_instit");
      if ($db_debug) {
      	echo "Excluindo dados da tabela gerfprovs13 quando $condicaoaux<br>";
      }      
      db_delete( "gerfprovs13", $condicaoaux );
      
   }
  
  if ($db_debug) {
  	echo "Buscando dados para processamento... <br>";
  	echo "SQL: <pre>{$sql}</pre><br>";
  }
  
  if ( db_selectmax( "prevfer13", $sql)) {
  	
	for ($Iprevfer13=0;$Iprevfer13<count($prevfer13);$Iprevfer13++) {

        db_atutermometro($Iprevfer13,count($prevfer13),'calculo_folha1',1);

        $matric       = $prevfer13[$Iprevfer13]["r01_regist"];
        $lotacaoatual = $prevfer13[$Iprevfer13]["r01_lotac"] ;

        db_selectmax( "pessoal", $sql." and rh02_regist = $matric");

        if ($db_debug) {
          echo "Processando matricula $matric  <br>"; 
          echo "r30_perai    --> $r30_perai    <br>";
          echo "r30_peraf    --> $r30_peraf    <br>";
          echo "lotacaoatual --> $lotacaoatual <br>";
          echo "tipoger      --> $tipoger      <br><br>";
        } 
        
        if ($tipoger == "F") {

           $r30_perai    = $prevfer13[$Iprevfer13]["r30_perai"] ;
           $datainicio   = $prevfer13[$Iprevfer13]["r30_perai"] ;
           $r30_peraf    = $prevfer13[$Iprevfer13]["r30_peraf"] ;
      
            // limpa o ponto
            $max = 0;
            
            // Paga ferias vencidas ou Paga ferias Proporcionais
            $datarescisao = $anousu."-".$mesusu."-".ndias($mesusu."/".$anousu);
            
            //echo "<BR> datarescisao --> $datarescisao";
	        $tipoferias = " ";
	        $dias_diferenca_ferias = 0;
	        $condicaoaux =  " and r30_regist = ".db_sqlformat( $matric );
           	$condicaoaux .= " order by r30_perai desc";
           	
           	if ($db_debug) {
           	  echo "Buscando cadferia quando ".bb_condicaosubpes("r30_").$condicaoaux."<br>";	
           	}
           	if ( !db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes("r30_").$condicaoaux )) {
          		
          	   if ($db_debug) {
          	     echo "não encontrou cadferia... <br>";	
          	     echo "calculando diferença de férias <br>";
          	   }
          	   	
	           if ( $cadferia[0]["r30_ndias"] > ($cadferia[0]["r30_dias1"] + $cadferia[0]["r30_dias2"] + $cadferia[0]["r30_abono"]) ){
	              $dias_diferenca_ferias = $cadferia[0]["r30_ndias"] - ($cadferia[0]["r30_dias1"] + $cadferia[0]["r30_dias2"] + $cadferia[0]["r30_abono"] );
	              if ($db_debug) {
	                echo " dias_diferenca_ferias = r30_ndias - (r30_dias1 + r30_dias2 + r30_abono) = ".$cadferia[0]["r30_ndias"]." - (".$cadferia[0]["r30_dias1"]." + ".$cadferia[0]["r30_dias2"]." + ".$cadferia[0]["r30_abono"].")<br>";
	                echo " dias_diferenca_ferias = {$dias_diferenca_ferias} <br>";
	                echo " tipoferias = D <br>";
	              }
	              $tipoferias = "D";
	           }
	        }
	        
	        if ($db_debug) {
	          echo "<br>";
	          echo "Verificando a data final(datafim) considerar... <br>";	
	        }
          	if ( strtolower($tipoferias) != "d") {
          		
	          if( db_substr(db_dtoc($datainicio),1,2) > "28" && db_substr(db_dtoc($datainicio),4,2) == "02"){
	            $dataconsiderar = "28/02/";
	          }else{
	            $dataconsiderar = db_substr(db_dtoc($datainicio),1,6);
	          }
	          
	          $datafim = date("Y-m-d",db_mktime(db_ctod($dataconsiderar.db_str((db_year($datainicio)+1),4,0,"0"))) - 86400);
	          if( db_mktime($datafim) > db_mktime($datarescisao)) {
	            $datafim = $datarescisao       ;
	          }
	            
	        } else {
         	  $datafim = $r30_peraf;
	        }
	        if ($db_debug) {
	        	echo "data final (datafim): $datafim<br><br>";
	        	echo "<br>-----------------------------------------------------------------------------------------------------<br>";
	        	echo "iniciando loop enquanto a data de inicio ($datainicio) for menor que a data de rescisão($datarescisao)...<br>";
	        }	        
	        
	        
          	$qtdvencidas = 0;
	    	while (db_mktime($datainicio) < db_mktime($datarescisao)) {
	    		
	    	  if ($db_debug) {
	    	  	echo "<br>";
                echo "datarescisao 1.1 --> $datarescisao <br>";
                echo "datainicio   1.1 --> $datainicio <br>";
                echo "datafim      1.1 --> $datafim <br><br>";
	    	  }
	    	   	
	    	  $lancarferias = true;
	    	  if ( strtolower($tipoferias) != "d") {
	    	  	
	    		if (bcdiv(db_datedif($datafim,$datainicio),30,0) == 12) {
	    		  $tipoferias = "V";
	    		  if ($db_debug) {
                    echo "tipoferias 1.1 --> $tipoferias <br>";
	    		  }
                } else {
                  $tipoferias = "P";
                  if ($db_debug) {
                    echo "tipoferias 1.2 --> $tipoferias <br>";
                  }  
	    	    }
	    	    
	    	  }
	    	  
	    	  if ( strtolower($tipoferias) == "d") {
                $tipoferias = " ";
              }
              
	    	  // Paga ferias Vencidas
              if ( strtolower($tipoferias) == "v") {
              	
              	if ($db_debug) {
              		echo "Pagando ferias vencidas... <br>";
              	}
              	if ( afas_periodo_aquisitivo( $datainicio,$datafim ) <= 180){
              		
                   if ($db_debug) {
                   	echo "afas_periodo_aquisitivo({$datainicio},{$datafim}) menor que 180 <br><br>";
                   	echo "-----------------------------------------------------------------------------------------------------<br>";
                   	echo "1 - Chamando função ferias_para_rescisao( $datainicio, $datafim, $tipoferias,'r91')... <br>"; 
                   }
                   ferias_para_rescisao( $datainicio, $datafim, $tipoferias,"r91");
                   if ($db_debug) {
                   	echo "1- Fim do processamento da função ferias_para_rescisao...<br>";
                   	echo "-----------------------------------------------------------------------------------------------------<br><br>";
                   }
                   
                } else {
	    		  $lancarferias = false;
	    	    }
	    	    
	    		$qtdvencidas += 1;
              }
              
	    	  // Paga ferias Proporcional
	    	  if ( strtolower($tipoferias) == "p") {
	    	  	
	    	  	if ($db_debug) {
	    	  	  echo "Pagando ferias proporcionalmente...<br><br>";
	    	  	  echo "-----------------------------------------------------------------------------------------------------<br>";
	    	  	  echo "2 - chamando função ferias_para_rescisao( $datainicio, $datafim, $tipoferias,'r91')... <br>";
	    	  	}	    	  	
	    		ferias_para_rescisao( $datainicio, $datafim, $tipoferias,"r91");
	    		if ($db_debug) {
	    		  echo "2 - Fim do processamento da função ferias_para_rescisao...<br>";
	    		  echo "-----------------------------------------------------------------------------------------------------<br><br>";
	    		}	    		
	    		
	    	  }
	    	  
              $datainicio = date("Y-m-d",(db_mktime($datafim) + 86400));
              $datafim = date("Y-m-d",db_mktime(db_ctod(db_substr(db_dtoc($datainicio),1,6).db_str((db_year($datainicio)+1),4,0,"0"))) - 86400);
              
              if ($db_debug) {
               echo "datainicio   1.2 --> $datainicio <br>";
               echo "datafim      1.2 --> $datafim <br>";
               echo "datafim($datafim) maior que datarescisao($datarescisao), datafim passa a ser $datarescisao... <br>";
              }
	    	  if ( db_mktime($datafim) > db_mktime($datarescisao)) {
	    		$datafim = $datarescisao;
	    	  }
	    	  
	    	}
	    	
	    	if ($db_debug) {
	    	echo "<br>Fim do loop... <br>";	
	    	echo "-----------------------------------------------------------------------------------------------------<br>";
	    	}
	    	
            $subpes = $subpes_original;

        } else {
        	
           // limpa o ponto
           $subpes = $anousu."/".$mesusu;
           $datafim = $anousu."-".$mesusu."-".ndias($mesusu."/".$anousu);
           //echo "<BR> subpes --> $subpes datafim --> $datafim";
           
           if ($db_debug) {
           	 echo "<br>chamando a função gera_13_salario($datafim,'r92')... <br>";
           }
           gera_13_salario($datafim,"r92");
           if ($db_debug) {
           	echo "fim do processamento da função gera_13_salario($datafim,'r92')... <br><br>";
           }
        }
    }
           
  }
  
  db_inicio_transacao();

  global $r110_lotaci, $r110_lotacf, $r110_regisi, $r110_regisf,$opcao_gml,$opcao_geral,$faixa_lotac,$faixa_regis;
  global $lotacao_faixa;
  global $diversos;

  $DB_instit = DB_getsession("DB_instit");
  $opcao_gml = 'g';
  $opcao_filtro = "0";

  db_selectmax( "diversos", "select * from pesdiver ".bb_condicaosubpes( "r07_" ));
  $separa = "global ";
  $quais_diversos = "";
  for($Idiversos=0;$Idiversos<count($diversos);$Idiversos++){
	    $codigo = $diversos[$Idiversos]["r07_codigo"];
	    $quais_diversos .= $separa.'$'.$codigo;	   
	    $separa = ",";
	 	 
	    global $$codigo;
	    eval('$$codigo = '.$diversos[$Idiversos]["r07_valor"].";");
  }
  $quais_diversos .= ';';	   
  global $ajusta;
  $ajusta = false ;
  if($tipoger == "F"){
     $opcao_geral = 11;
  }else{
     $opcao_geral = 12;
  }
  global $carregarubricas_geral,$carregarubricas;
      
  $carregarubricas_geral = array();
      
  db_selectmax("carregarubricas","select * from rhrubricas where rh27_instit = $DB_instit order by rh27_rubric" );
      
  for($Icarregar=0;$Icarregar<count($carregarubricas);$Icarregar++){
	 
	   $r10_pd  = $carregarubricas[$Icarregar]["rh27_pd"] == 1?true:false;
	   $formula = $carregarubricas[$Icarregar]["rh27_form"];
	   if( db_empty($formula)){
	      if( $r10_pd){
	         $r10_form = "+";
	      }else{
	         $r10_form = "-";
	      }
	   }else{
	     $r10_form = '('.trim($formula).')';
	     if( $r10_pd){
	        $r10_form = "+".$r10_form;
	     }else{
	        $r10_form = "-".$r10_form;
	     }
     }
	   $r10_form = str_replace('D','$D',$r10_form);
	   $r10_form = str_replace('F','$F',$r10_form);
	   $carregarubricas_geral[$carregarubricas[$Icarregar]["rh27_rubric"]] = $r10_form;
  }
      
  if ($db_debug) {
  	echo "<br>-----------------------------------------------------------------------------------------------------<br>";
  	echo "<br>Chamando a função pes4_geracalculo003()... <br>";
  }
  pes4_geracalculo003();
  
  
  if ($db_debug) {
  	echo " <br><br> ";
  	echo " <b>Fim do Calculo com Debug. <br>";
  	echo " Calculo não foi gravado na base.</b> ";
  	db_fim_transacao(true);
  	exit;
  }
  
  flush();
  db_fim_transacao();
  flush();
  db_msgbox("Calculo efetuado com sucesso !"); 
  echo "<script> document.form1.submit()</script>";
}
?>
<script>
js_tabulacaoforms("form1","incluir",true,1,"incluir",true);
</script>