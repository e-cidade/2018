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


require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");

global $cfpess,$subpes, $db21_codcli ;

$subpes = db_anofolha().'/'.db_mesfolha();

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_")); 

db_postmemory($HTTP_POST_VARS);

$subpes = db_anofolha().'/'.db_mesfolha();

$r11_sald13 = ($opcao==1?'0':'1');
$matriz1 = array();
$matriz2 = array();
$matriz1[1] = "r11_sald13";
$matriz2[1] = (db_boolean($r11_sald13)== true? 't': 'f');

db_update( "cfpess", $matriz1, $matriz2, bb_condicaosubpes("r11_") );

$sql = "select rh01_regist as r01_regist,
               rh02_hrsmen as r01_hrsmen, 
               trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,  
               rh01_numcgm as r01_numcgm, 
               rh03_regime as r01_regime, 
           		 rh02_tbprev as r01_tbprev,
           		 rh01_admiss as r01_admiss,
           		 rh05_recis  as r01_recis
        from rhpessoal
             inner join rhpessoalmov  on  rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
             inner join rhlota        on  r70_codigo                = rh02_lota
                                     and  r70_instit                = rh02_instit
    	       left  join rhpespadrao   on  rhpespadrao.rh03_seqpes   = rhpessoalmov.rh02_seqpes 
	           left  join rhpesrescisao on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
        where rh05_seqpes is null";
$result = pg_query($sql);

for($x=0;$x<pg_numrows($result);$x++){
  db_fieldsmemory($result,$x);

  $situa_func = situacao_funcionario($pessoal[$Ipessoal]["r01_regist"]);

  $meses_admissao = 0;

  if( db_year($r01_admiss) == db_val(db_substr($subpes,1,4))){
     $xdat = $r01_admiss;
     if( db_day($xdat) > 16 && db_year($r01_admiss) == db_year($datp13)){
        $meses_admissao = db_month($pessoal[$Ipessoal]["r01_admiss"]);
     }else{
        $meses_admissao = db_month($pessoal[$Ipessoal]["r01_admiss"]) - 1;
     }
  }else{
     $xdat = db_ctod("01/01/".db_substr(db_dtoc($datp13),7,4));
     $nm13 = db_month($datp13);
  }
  $meses_afastado = calcula_afastamentos();
}

function calcula_afastamentos(){
 global $afasta,$pessoal,$subpes,$cfpess,$Ipessoal;

//echo "<BR> subpes --> $subpes"; 

$retorno = 0;
$sem_retorno = 0;

$condicaoaux = " and r45_regist =". db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] )." order by r45_regist, r45_dtafas desc"  ;
if( db_selectmax("afasta", "select * from afasta ".bb_condicaosubpes("r45_").$condicaoaux )){

   for($Iafasta=0;$Iafasta<count($afasta);$Iafasta++){
      if( db_str($afasta[$Iafasta]["r45_situac"],1) == "3" && $pessoal[$Ipessoal]["r01_tbprev"] != $cfpess[0]["r11_tbprev"] ) {
         continue;
      }
      
      if( !db_empty($afasta[$Iafasta]["r45_dtreto"])){
         if( db_year($afasta[$Iafasta]["r45_dtreto"]) < db_val(db_substr($subpes,1,4)) ){
            break;
         }
      }else{
         $sem_retorno = 1;
         $mes = 12;
      }
      if( db_year($afasta[$Iafasta]["r45_dtreto"]) > db_val(db_substr($subpes,1,4))){
         $mes = 12;
      }else{
         if( !db_empty($afasta[$Iafasta]["r45_dtreto"])){
            $mes = db_month($afasta[$Iafasta]["r45_dtreto"]);
         }
      }
      if( db_val(db_substr($subpes,-2)) < $mes ){
         $mes = db_val(db_substr($subpes,-2));
         $mes++;
         $ano = (db_empty($afasta[$Iafasta]["r45_dtreto"])?db_val(db_substr($subpes,1,4)):db_year($afasta[$Iafasta]["r45_dtreto"]));
         if( $mes > 12){
            $mes = 1;
            $ano++;
         }

         // ultimo dia do mes anterior 
         $dias_mes = db_day(date("Y-m-d",db_mktime(db_ctod("01/".db_str($mes,2,0,"0")."/".db_str($ano,4,0,"0"))) - 86400));
         $mes--;

         $diasmes = ndias( db_str($mes,2,0,"0")."/".db_str($ano,4,0,"0") );

      }else{
         $ano = ( (db_empty($afasta[$Iafasta]["r45_dtreto"]) || db_year($afasta[$Iafasta]["r45_dtreto"]) > db_val( db_substr($subpes,1,4)) ) ?db_val(db_substr($subpes,1,4))
                   :db_year($afasta[$Iafasta]["r45_dtreto"]));
         $datafinal = ( (db_empty($afasta[$Iafasta]["r45_dtreto"]) || db_year($afasta[$Iafasta]["r45_dtreto"]) > db_val( db_substr($subpes,1,4)) ) 
                   ?db_ctod( "31/12/".db_substr($subpes,1,4) ):$afasta[$Iafasta]["r45_dtreto"]);

         // retorna o nr de dias trabalhados no mes????
         $dias_mes = db_datedif($datafinal,db_ctod("01/".db_str($mes,2,0,"0")."/".db_str($ano,4,0,"0")));

         $diasmes = ndias(db_str(db_month($datafinal),2,0,"0")."/".db_str(db_year($datafinal),4,0,"0") );

      }
      
      $metade_trabalhado = bcdiv($diasmes,2,0);

      if( $diasmes - $dias_mes < $metade_trabalhado){
         $retorno += 1;
      }
      
      if( db_month($afasta[$Iafasta]["r45_dtafas"]) == $mes && db_year($afasta[$Iafasta]["r45_dtafas"]) == $ano){
         
         if( $diasmes - $dias_mes < $metade_trabalhado ){
            $dias_mes = $dias_mes - ( db_day($afasta[$Iafasta]["r45_dtafas"]) - 1 );
            
            if( $diasmes - $dias_mes >= $metade_trabalhado){
               $retorno--;
            }
         }
      }else{
         $mes--;
         
         for($Imes=$mes; $Imes >= 1;$Imes--){
            if( db_mktime($afasta[$Iafasta]["r45_dtafas"])  < db_mktime(db_ctod("01/".db_str($Imes,2,0,"0")."/".db_substr($subpes,1,4))) ){
               $retorno += 1;
            }else{
               $dias_mes = db_datedif(db_ctod("01/".db_str($Imes,2,0,"0")."/".db_str($ano,4)),$afasta[$Iafasta]["r45_dtafas"]) - 1;
               
               if( $diasmes - $dias_mes < $metade_trabalhado){
                  $retorno += 1;
               }
               break;
            }
         }
      }
   }

   if( $retorno < 0){
       $retorno = 0;
   }

 }
   if( $sem_retorno == 1 && $retorno > 0){
     $retorno += 1;
   }
  return $retorno;
}



?>