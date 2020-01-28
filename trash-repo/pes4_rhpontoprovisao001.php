<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_libpessoal.php");
include("pes4_avaliaferiasrescisao.php");
include("pes4_gerafolha003.php");
include("pes4_gerafolha004.php");

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
      include("forms/db_frmrhpontoprovisao.php");
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

if(isset($processar) && $processar == "Processar"){
   global $pessoal, $Ipessoal,$cfpess;
   global $db21_codcli , $rubricas, $pontofx, $matriz1, $matriz2, $subpes;
   
   global $m_rubr, $m_tipo, $m_media , $m_valor , $m_quant, $qten , $vlrn, $r01_taviso,$subpes_original;
   
   global $datainicio, $datafim, $max, $gerfsal, $gerffer, $gerfcom, $rescisao, $qmeses;
   
   global $r30_perai,$ponto,$r30_peraf, $r30_faltas, $r30_peri,$r30_perf;
  
  
   global $ns13,$lotacaoatual,$anousu,$mesusu;

   $subpes = db_str($anousu,4)."/".db_str($mesusu,2,0,"0");
   $subpes_original = $subpes;   

   global $db_config;
   db_selectmax("db_config","select db21_codcli , cgc from db_config where codigo = ".db_getsession("DB_instit"));
  
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
        
   $sql = " select rh01_regist as r01_regist,
                   rh01_numcgm as r01_numcgm, 
                   rh01_admiss as r01_admiss, 
                   rh02_tbprev as r01_tbprev,
                   rh30_regime as r01_regime, 
                   rh02_hrsmen as r01_hrsmen,
                   trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac " ; 
  if($tipoger == "F"){
     $sql .= "     ,case when r30_perai is null then rh01_admiss else r30_peraf+1 end as r30_perai,
                   (rh02_anousu::char(4)||'-'||lpad(rh02_mesusu::char(2),2,'0')||'-'||ndias($anousu,$mesusu)::char(2)) as r30_peraf ";
  }           
     $sql .= " from rhpessoalmov
               inner join rhlota       on  r70_codigo = rh02_lota
                                      and  r70_instit = rh02_instit
               inner join rhpessoal    on rh01_regist = rh02_regist
               left outer join cgm     on z01_numcgm = rh01_numcgm
               left join rhregime      on rh30_codreg = rhpessoalmov.rh02_codreg
                                      and rh30_instit = rhpessoalmov.rh02_instit
               left join rhpesrescisao on rh05_seqpes = rhpessoalmov.rh02_seqpes ";
             
  if($tipoger == "F"){
      $sql .= "left join
                ( select distinct on (r30_regist ) r30_regist,r30_perai,r30_peraf
                  from cadferia
                  where r30_anousu= $anousu
                    and r30_mesusu= $mesusu
                    and (r30_ndias = r30_dias1 + r30_dias2 + r30_abono)
                  order by r30_regist, r30_perai desc )
                as cadf on r30_regist = rh01_regist ";
  }             
  $sql .= " where rh02_anousu = $anousu
              and rh02_mesusu = $mesusu
              and rh02_instit = ".DB_getsession("DB_instit")." 
              and rh05_recis is null ";
   
  
  if($tipoger == "F"){
     $sql .= " and rh30_vinculo = 'A'";
     $condicaoaux = " where r91_anousu = $anousu 
                      and   r91_mesusu = $mesusu 
                      and   r91_instit = ".DB_getsession("DB_instit");
     db_delete( "pontoprovfe", $condicaoaux );

     $condicaoaux = " where r93_anousu = $anousu 
                      and   r93_mesusu = $mesusu 
                      and   r93_instit = ".DB_getsession("DB_instit");
     db_delete( "gerfprovfer", $condicaoaux );
  }else{
     $condicaoaux = " where r92_anousu = $anousu 
                      and   r92_mesusu = $mesusu 
                      and   r92_instit = ".DB_getsession("DB_instit");
     db_delete( "pontoprovf13", $condicaoaux );
     $condicaoaux = " where r94_anousu = $anousu 
                      and   r94_mesusu = $mesusu 
                      and   r94_instit = ".DB_getsession("DB_instit");
     db_delete( "gerfprovs13", $condicaoaux );
  }
  global $pessoal,$cadferia,$prevfer13;
  if( db_selectmax( "prevfer13", $sql)){
	   for($Iprevfer13=0;$Iprevfer13<count($prevfer13);$Iprevfer13++){

        db_atutermometro($Iprevfer13,count($prevfer13),'calculo_folha1',1);

        $matric       = $prevfer13[$Iprevfer13]["r01_regist"];
        $lotacaoatual = $prevfer13[$Iprevfer13]["r01_lotac"] ;

        db_selectmax( "pessoal", $sql." and rh02_regist = $matric");

    //echo "<BR> matric --> $matric r30_perai --> $r30_perai r30_peraf --> $r30_peraf  lotacaoatual --> $lotacaoatual";    
        if($tipoger == "F"){

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
          	if( !db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes("r30_").$condicaoaux )){
	             if( $cadferia[0]["r30_ndias"] > ($cadferia[0]["r30_dias1"] + $cadferia[0]["r30_dias2"] + $cadferia[0]["r30_abono"]) ){
	               $dias_diferenca_ferias = $cadferia[0]["r30_ndias"] - ($cadferia[0]["r30_dias1"] + $cadferia[0]["r30_dias2"] + $cadferia[0]["r30_abono"] );
	               $tipoferias = "D";
	            }
	          }
	    
          	if( strtolower($tipoferias) != "d"){
	            if( db_substr(db_dtoc($datainicio),1,2) > "28" && db_substr(db_dtoc($datainicio),4,2) == "02"){
	              $dataconsiderar = "28/02/";
	            }else{
	              $dataconsiderar = db_substr(db_dtoc($datainicio),1,6);
	            }
        //echo "<BR> 1 datafim --> $datafim";
	      // echo "<BR> ".db_ctod($dataconsiderar.db_str((db_year($datainicio)+1),4,0,"0"));
	            $datafim = date("Y-m-d",db_mktime(db_ctod($dataconsiderar.db_str((db_year($datainicio)+1),4,0,"0"))) - 86400);
	            if( db_mktime($datafim) > db_mktime($datarescisao)){
	              $datafim = $datarescisao       ;
	            }
	          }else{
         	    $datafim = $r30_peraf;
	          }
        //echo "<BR> datafim --> $datafim";exit;
          	$qtdvencidas = 0;
	    			while (db_mktime($datainicio) < db_mktime($datarescisao)){
               //echo "<BR> datarescisao 1.1 --> $datarescisao";
               //echo "<BR> datainicio   1.1 --> $datainicio";
               //echo "<BR> datafim      1.1 --> $datafim";
	    			   $lancarferias = true;
	    			   if( strtolower($tipoferias) != "d"){
	    			      if( bcdiv(db_datedif($datafim,$datainicio),30,0) == 12){
	    				       $tipoferias = "V";
          
                 //echo "<BR> tipoferias 1.1 --> $tipoferias";
	    			      }else{
	    				       $tipoferias = "P";
                 //echo "<BR> tipoferias 1.2 --> $tipoferias";
	    			      }
	    			   }
	    			   if( strtolower($tipoferias) == "d"){
	    			      $tipoferias = " ";
	    			   }
	    			   // Paga ferias Vencidas
	    			   if( strtolower($tipoferias) == "v"){
	    			      if( afas_periodo_aquisitivo( $datainicio,$datafim ) <= 180){
                     //echo "<BR> afas_periodo_aquisitivo é menor que 180";
	    	      			  ferias_para_rescisao( $datainicio, $datafim, $tipoferias,"r91");
	    			      }else{
	    				        $lancarferias = false;
	    			      }
	    			      $qtdvencidas += 1;
	    			   }
	    			   // Paga ferias Proporcional
	    			   if( strtolower($tipoferias) == "p"){
	    			      ferias_para_rescisao( $datainicio, $datafim, $tipoferias,"r91");
	    			   }
	    			   $datainicio = date("Y-m-d",(db_mktime($datafim) + 86400));
	    			   $datafim = date("Y-m-d",db_mktime(db_ctod(db_substr(db_dtoc($datainicio),1,6).db_str((db_year($datainicio)+1),4,0,"0"))) - 86400);
               //echo "<BR> datainicio   1.2 --> $datainicio";
               //echo "<BR> datafim      1.2 --> $datafim";
	    			   if( db_mktime($datafim) > db_mktime($datarescisao)){
	    			      $datafim = $datarescisao;
                 //echo "<BR> datafim      1.3 --> $datafim";
	    			   }
	    			}
            $subpes = $subpes_original;

        }else{
           // limpa o ponto
     

           $subpes = $anousu."/".$mesusu;
           $datafim = $anousu."-".$mesusu."-".ndias($mesusu."/".$anousu);
           //echo "<BR> subpes --> $subpes datafim --> $datafim";
           gera_13_salario($datafim,"r92");
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
      
  pes4_geracalculo003();
  //exit;
  //echo "<BR> antes do fim db_fim_transacao()";
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