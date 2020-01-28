<?
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

db_sel_instit(db_getsession("DB_instit"),"lower(trim(munic)) as d08_carnes");
db_sel_cfpess($anousu,$mesusu,"r11_valor, r11_dtipe, r11_baseipe, r11_altfer");

$minimo = $r11_valor;
$tabela = $r11_dtipe;

$sqlerro = false;

$sql_in = $clrhbasesr->sql_query_file($r11_baseipe,null,"rh33_rubric::char(4)");

$campos = "
           rh14_sequencia,
           rh14_dtvinc ,
           rh14_estado ,
           rh14_dtalt  ,
           rh14_matipe ,
           rh14_valor  ,

           rh02_tbprev ,
           rh30_vinculo,
           rh01_regist ,

           z01_numcgm
          ";

$dbwhere  = " rh14_dtvinc is not null ";
$dbwhere .= " and (rh05_recis is null ";
$dbwhere .= "     or ( extract(year from rh05_recis)= $anousu";
$dbwhere .= "          and extract(month from rh05_recis)= $mesusu))";

$result_clrhipe = $clrhipe->sql_record($clrhipe->sql_query_ipe(null, $campos, "", $dbwhere, $anousu, $mesusu));
if($clrhipe->numrows > 0){
  $clipe->excluir($anousu,$mesusu);
  if($clipe->erro_status == 0){
    $erro_msg = $clipe->erro_msg;
    $sqlerro=true;
  }

  if($sqlerro == false){
    $subpes = $anousu."/".$mesusu;

    for($I=0;$I < $clrhipe->numrows;$I++){
      db_fieldsmemory($result_clrhipe, $I);

      $prov = 0;
      
      if(trim($rh01_regist) != "" && ($rh14_valor == 0 || trim($rh14_valor) == "")){

        /////////////
        // Se for funcionário e o valor do rhipe for igual a zero
        /////////////

        if( $xtipo ==  1){

            if( $subpes < $r11_altfer || db_empty( $r11_altfer )){
              $dbwhere  = " and r31_regist = ".$rh01_regist;
              $dbwhere .= " and r31_rubric in ($sql_in) ";
              $dbwhere .= " and r31_instit = ".db_getsession("DB_instit")." ";
              $campos1 = "select sum(case when r31_pd in (1,3) then r31_valor else r31_valor *-1 end) as r31_valor ";
              $result_selecao = $clgerffer->sql_record($clgerffer->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));
              if($clgerffer->numrows > 0){
                db_fieldsmemory($result_selecao, 0);
                $prov += $r31_valor;
              }
            }

            $dbwhere  = " and r14_regist = ".$rh01_regist;
            $dbwhere .= " and r14_rubric in ($sql_in) ";
            $dbwhere .= " and r14_instit = ".db_getsession("DB_instit")." ";
            $campos1 = "select sum(case when r14_pd in (1,3) then r14_valor else r14_valor *-1 end) as r14_valor ";
            $result_selecao = $clgerfsal->sql_record($clgerfsal->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));
            if($clgerfsal->numrows > 0){
              db_fieldsmemory($result_selecao, 0);
              $prov += $r14_valor;
            }

            $dbwhere  = " and r48_regist = ".$rh01_regist;
            $dbwhere .= " and r48_rubric in ($sql_in) ";
            $dbwhere .= " and r48_instit = ".db_getsession("DB_instit")." ";
            $campos1 = "select sum(case when r48_pd in (1,3) then r48_valor else r48_valor *-1 end) as r48_valor ";
            $result_selecao = $clgerfcom->sql_record($clgerfcom->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));
            if($clgerfcom->numrows > 0){
              db_fieldsmemory($result_selecao, 0);
              $prov += $r48_valor;
            }

            $dbwhere  = " and r20_regist = ".$rh01_regist;
            $dbwhere .= " and r20_rubric in ($sql_in) ";
            $dbwhere .= " and r20_instit = ".db_getsession("DB_instit")." ";
            $campos1 = "select sum(case when r20_pd in (1,3) then r20_valor else r20_valor *-1 end) as r20_valor ";
            $result_selecao = $clgerfres->sql_record($clgerfres->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));
            if($clgerfres->numrows > 0){
              db_fieldsmemory($result_selecao, 0);
              $prov += $r20_valor;
            }

            if( strtolower($rh30_vinculo) != "a" ){
              db_retorno_variaveis($anousu, $mesusu, $rh01_regist);
              if( $prov == 0 && ( $rh14_estado == "22" )){
                $prov = $f007;
              }

              if( $prov == 0 && ( $rh14_estado == "39" )){
                $prov = $f010;
              }

              if( $prov == 0 && ( $rh14_estado != "21" && $rh14_estado != "22" )){
                continue;
              }
          }
        }else{
          $dbwhere  = " and r35_regist = ".$rh01_regist;
          $dbwhere .= " and r35_rubric in ($sql_in) ";
          $dbwhere .= " and r35_instit = ".db_getsession("DB_instit")." ";
          $campos1 = "select sum(case when r35_pd in (1,3) then r35_valor else r35_valor *-1 end) as r35_valor ";
          $result_selecao = $clgerfs13->sql_record($clgerfs13->sql_query_file(null,null,null,null,$campos1,"",$dbwhere));
          if($clgerfs13->numrows > 0){
            db_fieldsmemory($result_selecao, 0);
            $prov += $r35_valor;
          }
        }
      }else{

        /////////////
        // Se não for funcionário ou valor do rhipe for diferente de zero
        /////////////
        $prov = $rh14_valor;

      }

      $clipe->r36_regist = $rh01_regist;
      $clipe->r36_matipe = $rh14_matipe;
      $clipe->r36_numcgm = $z01_numcgm;
      $clipe->r36_dtvinc = $rh14_dtvinc;
      $clipe->r36_estado = $rh14_estado;
      $clipe->r36_dtalt  = $rh14_dtalt;
      if( db_empty($prov) && ($rh14_estado == "21" || $rh14_estado == "22" )){
        $clipe->r36_contr1 = 0;
        $clipe->r36_valorc = $minimo;
      }else if( $prov < $minimo){ 
        $clipe->r36_contr1 = $prov;
        $clipe->r36_valorc = $minimo;
      }else{
        $clipe->r36_contr1 = $prov;
        $clipe->r36_valorc = $prov;
      }

      $clipe->incluir($anousu,$mesusu,$rh14_sequencia);
      if($clipe->erro_status == 0){
         $erro_msg = $clipe->erro_msg;
         $sqlerro = true;
         break;
      }
    }
  }
}
?>