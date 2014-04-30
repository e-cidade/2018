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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);


$sqlerro = false;

$ponto       = array("r90"=>"Fixo","r10"=>"Sal�rio","r47"=>"Complementar","r21"=>"Adiantamento","r34"=>"13o. Sal�rio","r19"=>"Rescis�o");
$sigla       = array("pontofx"=>"r90","pontofs"=>"r10","pontocom"=>"r47","pontofa"=>"r21","pontof13"=>"r34","pontofr"=>"r19");
$sigla_ponto = array("r90"=>"pontofx","r10"=>"pontofs","r47"=>"pontocom","r21"=>"pontofa","r34"=>"pontof13","r19"=>"pontofr");

if($ponto2 != $sigla_ponto[$ponto1] || $rh27_rub1 != $rh27_rubric || $ano2 != $ano1 || $mes2 != $mes1){ 
   $clgerasql = new cl_gera_sql_folha;
  
   parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
   //echo "<BR> ".$HTTP_SERVER_VARS['QUERY_STRING'];exit;
   $clgerasql->inner_rub = false;
   $clgerasql->usar_ger = true;
   $clgerasql->usar_cgm = true;
   $clgerasql->usar_rub = true;
   $clgerasql->usar_lot = true;
   $clgerasql->usar_fun = true;
   $clgerasql->usar_res = true;
   $clgerasql->usar_atv = true;
   $clgerasql->usar_pad = true;
   $clgerasql->usar_car = true;
   
   
   
   $whereRESC = " ";
   $andwhere = "";
   
   if(isset($lti) || isset($ltf) || isset($flt)){
     // Se for escolhida alguma lotação
   
     $lotacao = true;
   
     $head5 = "LOTA��ES";
     $orderBY= " r70_estrut,z01_nome,#s#_regist,rh27_rubric";
     $camposFiltrar = ", r70_estrut as codigofiltro, r70_descr as descrifiltro, r70_estrut as estrutfiltro ";
   
     if(isset($lti) && trim($lti) != "" && isset($ltf) && trim($ltf) != ""){
       // Se for por intervalos e vier lotação inicial e final
       $whereRESC.= $andwhere." r70_estrut between '".$lti."' and '".$ltf."' ";
       $andwhere = " and ";
     }else if(isset($lti) && trim($lti) != ""){
       // Se for por intervalos e vier somente lotação inicial
       $whereRESC.= $andwhere." r70_estrut >= '".$lti."' ";
       $andwhere = " and ";
     }else if(isset($ltf) && trim($ltf) != ""){
       // Se for por intervalos e vier somente lotação final
       $whereRESC.= $andwhere." r70_estrut <= '".$ltf."' ";
       $andwhere = " and ";
     }else if(isset($flt) && trim($flt) != ""){
       // Se for por selecionados
       $whereRESC.= $andwhere." r70_estrut in ('".str_replace(",","','",$flt)."') ";
       $andwhere = " and ";
     }
   
   }else if(isset($lci) || isset($lcf) || isset($flc)){
     // Se for escolhido algum local de trabalho
    
     $clgerasql->usar_tra = true;
   
     if(isset($lci) && trim($lci) != "" && isset($lcf) && trim($lcf) != ""){
       // Se for por intervalos e vier local inicial e final
       $whereRESC.= $andwhere." rh55_estrut between '".$lci."' and '".$lcf."' ";
       $andwhere = " and ";
     }else if(isset($lci) && trim($lci) != ""){
       // Se for por intervalos e vier somente local inicial
       $whereRESC.= $andwhere." rh55_estrut >= '".$lci."' ";
       $andwhere = " and ";
     }else if(isset($lcf) && trim($lcf) != ""){
       // Se for por intervalos e vier somente local final
       $whereRESC.= $andwhere." rh55_estrut <= '".$lcf."' ";
       $andwhere = " and ";
     }else if(isset($flc) && trim($flc) != ""){
       // Se for por selecionados
       $whereRESC.= $andwhere." rh55_estrut in ('".str_replace(",","','",$flc)."') ";
       $andwhere = " and ";
     }
   
   }else if(isset($ori) || isset($orf) || isset($for)){
     // Se for escolhido algum órgão
   
     $clgerasql->usar_org = true;
  
     if(isset($ori) && trim($ori) != "" && isset($orf) && trim($orf) != ""){
       // Se for por intervalos e vier órgão inicial e final
       $whereRESC.= $andwhere." o40_orgao between ".$ori." and ".$orf;
       $andwhere = " and ";
     }else if(isset($ori) && trim($ori) != ""){
       // Se for por intervalos e vier somente órgão inicial
       $whereRESC.= $andwhere." o40_orgao >= ".$ori;
       $andwhere = " and ";
     }else if(isset($orf) && trim($orf) != ""){
       // Se for por intervalos e vier somente órgão final
       $whereRESC.= $andwhere." o40_orgao <= ".$orf;
       $andwhere = " and ";
     }else if(isset($for) && trim($for) != ""){
       // Se for por selecionados
       $whereRESC.= $andwhere." o40_orgao in (".$for.") ";
       $andwhere = " and ";
     }
   
   }
   $whereRESC1 =  $whereRESC.$andwhere." #s#_rubric = '".$rh27_rubric."'";
  
   $camposSQL  = "$ano2,                   ";
   $camposSQL .= "$mes2,                   ";
   $camposSQL .= "#s#_regist as r14_regist,"; 
   $camposSQL .= "'{$rh27_rub1}',          ";
   $camposSQL .= "#s#_valor  as r14_valor, "; 
   $camposSQL .= "#s#_quant  as r14_quant, "; 
   $camposSQL .= "#s#_lotac  as r14_lotac, ";
   
   if ( $ponto1 == 'r10' || $ponto1 == 'r90' ){ 
     $camposSQL .= "#s#_datlim as r14_datlim,"; 
   } else if ( $ponto1 == 'r19' ) {
     $camposSQL .= "#s#_tpp as r14_tpp,";
   } else if ( $ponto1 == 'r34' ) {
     
     $camposSQL .= "#s#_media as r14_media,";
     $camposSQL .= "#s#_calc as r14_calc,";
   }
   
   $camposSQL .= "#s#_instit as r14_instit ";
   $sql_dados  = $clgerasql->gerador_sql($ponto1,$ano1,$mes1,null,null,$camposSQL,"",$whereRESC1,db_getsession("DB_instit"));
   $result     = db_query($sql_dados);
   $xxnum      = pg_numrows($result);
   
   if ($xxnum == 0) {
     
      $sqlerro  = true;
      $erro_msg = 'Nao existe a ocorr�ncia da rubrica '.$rh27_rubric.' no ponto '.$ponto[$ponto1].' de origem';
   } else {
      
      $whereRESC1 =  $whereRESC.$andwhere." #s#_rubric = '".$rh27_rub1."'";
      $sql_dados2 = $clgerasql->gerador_sql($sigla[$ponto2],$ano2,$mes2,null,null,"#s#_regist","",$whereRESC1,db_getsession("DB_instit"));
      $result     = db_query($sql_dados2);
      $xxnum      = pg_numrows($result);
      
      if($xxnum > 0){
         if($inserir == 1){
            $sqlerro= true;
            $erro_msg = 'Existe a ocorr�ncia da rubrica '.$rh27_rub1.' no ponto '.$ponto[$ponto1].' de destino \n\n Inserir assim mesmo ?';
         }else{
   
           $sql_dados1 = "delete from $ponto2 where ".$sigla[$ponto2]."_anousu = $ano2 and ".
                                                      $sigla[$ponto2]."_mesusu = $mes2 and ".
                                                      $sigla[$ponto2]."_instit = ".db_getsession("DB_instit")." and ".
                                                      $sigla[$ponto2]."_rubric = '".$rh27_rub1."' and ". 
                                                      $sigla[$ponto2]."_regist in(".$sql_dados2.")";
           //echo "<BR> $sql_dados1"; exit;
           $result_dados = db_query($sql_dados1);
           $sql_dados1 = "insert into $ponto2 ($sql_dados)";
           //echo "<BR> $sql_dados1"; exit;
           $result_dados = db_query($sql_dados1);
         }
      }else{
      
         $sql_dados1 = "insert into $ponto2 ($sql_dados)";
         $result_dados = db_query($sql_dados1);
      }
   }
}else{
   $sqlerro= true;
   $erro_msg = 'Tem que ser diferentes os dados informados no box "Existindo" e no box "Inserir"';
}

if($sqlerro == true){
    echo "
    <script>
      parent.js_erro('$erro_msg');
    </script>
    ";
}else{
    echo "
    <script>
      parent.js_erro('Atualiza��o Realizada com Sucesso');
      parent.js_limpa();
    </script>
    ";
}