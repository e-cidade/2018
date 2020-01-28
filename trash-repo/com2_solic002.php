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
include("classes/db_solicita_classe.php");
include("classes/db_solicitatipo_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pctipocompra_classe.php");
include("classes/db_db_depart_classe.php");
$clsolicita     = new cl_solicita;
$clsolicitatipo = new cl_solicitatipo;
$clsolicitem    = new cl_solicitem;
$clpcdotac      = new cl_pcdotac;
$clpctipocompra = new cl_pctipocompra;
$cldb_depart    = new cl_db_depart;
$clrotulo = new rotulocampo;
$clsolicita->rotulo->label();

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$and    = "";
$where  = "";
$info   = "";

$sAcumulador = "";
$sVirgula    = "";

function monta_where($inp="",$par="",$descr_inp=""){
  global $and;
  $param_autoriza = "";
  $where_autorizacao = "";
  if(isset($inp) && trim($inp)!=""){
    if($par == "S"){
      $param_autoriza = " in ";
    }else if($par == "N"){
      $param_autoriza = " not in ";
    }
    $where_autorizacao .= $and.$descr_inp.$param_autoriza." (".$inp.") ";
    $and = " and ";
  }
  return $where_autorizacao;
}

$wsolicita = monta_where(@$inp_depart,@$par_depart ," pc10_depto ");
if(isset($pc10_dataINI_dia) && trim($pc10_dataINI_dia)!="" && isset($pc10_dataINI_mes) && trim($pc10_dataINI_mes)!="" && isset($pc10_dataINI_ano) && trim($pc10_dataINI_ano)!=""){
  $dt_ini = $pc10_dataINI_ano."-".$pc10_dataINI_mes."-".$pc10_dataINI_dia;
}
if(isset($pc10_dataFIM_dia) && trim($pc10_dataFIM_dia)!="" && isset($pc10_dataFIM_mes) && trim($pc10_dataFIM_mes)!="" && isset($pc10_dataFIM_ano) && trim($pc10_dataFIM_ano)!=""){
  $dt_fim = $pc10_dataFIM_ano."-".$pc10_dataFIM_mes."-".$pc10_dataFIM_dia;
}
$msg_head = '';
if(isset($dt_ini) && trim($dt_ini)!="" || isset($dt_fim) && trim($dt_fim)!=""){
  if(isset($dt_ini) && isset($dt_fim)){
    $wsolicita = $wsolicita . $and ." pc10_data between '".$dt_ini."' and '".$dt_fim."' ";
    $msg_head  = "Período de solicitação entre ".db_formatar($dt_ini,"d")." e ".db_formatar($dt_fim,"d");
  }else if(isset($dt_ini)){
    $wsolicita = $wsolicita . $and ." pc10_data >= '".$dt_ini."' ";
    $msg_head  = "Período de solicitação posterior a ".db_formatar($dt_ini,"d");
  }else if(isset($dt_fim)){
    $wsolicita = $wsolicita . $and ." pc10_data <= '". $dt_fim."' ";
    $msg_head  = "Período de solicitação anterior a ".db_formatar($dt_fim,"d");
  }
  $and = " and ";
}
$wtipcompra = monta_where(@$inp_tipcom,@$par_tipcom," pc12_tipo ");
$wmateriais = monta_where($inp_mater ,$par_mater ," pc16_codmater  ");
$wdotacoes  = monta_where($inp_dotac ,$par_dotac ," pc13_coddot ");
if(isset($wsolicita) && trim($wsolicita)!=""){
  $where .= $wsolicita;
}
if(isset($pc10_numeroINI) && $pc10_numeroINI!="" || isset($pc10_numeroFIM) && $pc10_numeroFIM!=""){
  $where_param = "";
  if(isset($pc10_numeroINI) && isset($pc10_numeroFIM)){
    $where_param = " pc10_numero between $pc10_numeroINI and $pc10_numeroFIM ";    
  }else if($pc10_numeroINI){
    $where_param = " pc10_numero >= $pc10_numeroINI ";
  }else if($pc10_numeroFIM){
    $where_param = " pc10_numero <= $pc10_numeroFIM ";
  }
  $where .= $and.$where_param;
}
if(isset($wtipcompra) && trim($wtipcompra)!=""){
  $where .= $wtipcompra;
}
if(isset($wmateriais) && trim($wmateriais)!=""){
  $where .= $wmateriais;
}
if(isset($wdotacoes) && trim($wdotacoes)!=""){
  $where .= $wdotacoes;
}

$mostra_aut = false;
if ($situacao=="T"){
     $mostra_aut = true;
} else if ($situacao=="N"){
     if (strlen($where) > 0){
          $where .= " and ";
     }
     $where .= "e55_autori is null";
} else if ($situacao=="A"){
     if (strlen($where) > 0){
          $where .= " and ";
     }
     $where .= "e55_autori is not null";
     $mostra_aut = true;
}

if($ordem == "pc10_numero"){
  $info = "CÓDIGO DAS SOLICITAÇÕES";
}else if ($ordem == "pc10_data"){
  $info = "DATA DE EMISSÃO";
}else if ($ordem == "descrdepto"){
  $info = "DEPARTAMENTO";
}else if ($ordem == "pc50_descr"){
  $info = "TIPO DE COMPRA";
}

if (strlen($where) > 0){
  $where .= " and ";
}
$where .= "pc10_instit = ".db_getsession("DB_instit");

$sCampos  = "pc10_numero,                                                       ";
$sCampos .= "pc16_codmater,                                                     ";
$sCampos .= "pc12_tipo,                                                         ";
$sCampos .= "pc10_data,                                                         ";
$sCampos .= "pc10_depto,                                                        ";
$sCampos .= "descrdepto,                                                        ";
$sCampos .= "pc12_vlrap,                                                        ";
$sCampos .= "pc50_descr,                                                        ";
$sCampos .= "pc11_resum,                                                        ";
$sCampos .= "pc11_codigo,                                                       ";
$sCampos .= "pc01_descrmater,                                                   ";
$sCampos .= "pc11_quant,                                                        ";
$sCampos .= "pc11_vlrun,                                                        ";
$sCampos .= "e55_autori,                                                        ";    
$sCampos .= "e56_coddot,                                                        ";

/*
 * Abaixo são realizados cases para utilizar as informações das dotações das autorizações quando estas existirem
 * A lógica é a seguinte, se existe autorização para o processo de compras do item da solicitação, utilizamos as informações 
 * da dotação desta autorização, do contrário utilizamos as informações da dotação de acordo com o item.
 * 
 * Essa alteração se fez necessária para o funcionamento do relatório quando o item é "quebrado" em mais dotações.
 * @todo Este relatório deverá ser refatorado em tarefa de melhoria. 
 */
$sCampos .= "case                                                               ";
$sCampos .= "  when e56_coddot is not null and e56_coddot = pc13_coddot         ";
$sCampos .= "    then pc13_coddot                                               ";
$sCampos .= "  when e56_coddot is null                                          ";
$sCampos .= "    then pc13_coddot                                               ";
$sCampos .= "  else null                                                        ";
$sCampos .= "end as pc13_coddot,                                                ";
$sCampos .= "case                                                               ";
$sCampos .= "  when e56_coddot is not null and e56_coddot = pc13_coddot         ";
$sCampos .= "    then pc13_anousu                                               ";
$sCampos .= "  when e56_coddot is null                                          ";
$sCampos .= "    then pc13_anousu                                               ";
$sCampos .= "  else null                                                        ";
$sCampos .= "end as pc13_anousu,                                                ";
$sCampos .= "case                                                               ";
$sCampos .= "  when e56_coddot is not null and e56_coddot = pc13_coddot         ";
$sCampos .= "    then pc13_quant                                                ";
$sCampos .= "  when e56_coddot is null                                          ";
$sCampos .= "    then pc13_quant                                                ";
$sCampos .= "  else null                                                        ";
$sCampos .= "end as pc13_quant,                                                 ";
$sCampos .= "case                                                               ";
$sCampos .= "  when e56_coddot is not null and e56_coddot = pc13_coddot         ";
$sCampos .= "    then pc13_valor                                                ";
$sCampos .= "  when e56_coddot is null                                          ";
$sCampos .= "    then pc13_valor                                                ";
$sCampos .= "  else null                                                        ";
$sCampos .= "end as pc13_valor,                                                 ";
/*
 * FIM dos cases 
 */

$sCampos .= "(select sum(y.pc11_vlrun*y.pc11_quant)                             ";
$sCampos .= "   from solicitem as y                                             ";
$sCampos .= "  where y.pc11_numero = pc10_numero) as tot_sol,                   ";
$sCampos .= "(select sum(z.pc13_valor)                                          ";
$sCampos .= "   from pcdotac   as z                                             ";
$sCampos .= "        inner join solicitem as x on z.pc13_codigo = x.pc11_codigo ";
$sCampos .= "  where x.pc11_numero = pc10_numero) as tot_dot                    ";

$sSql = $clsolicita->sql_query_rel(null,"distinct ".$sCampos,$ordem.",e55_autori", $where);
$sSqlDados  = "select *                              "; 
$sSqlDados .= "  from ($sSql) as dados               "; 
$sSqlDados .= " where case                           "; 
$sSqlDados .= "         when e56_coddot is null      ";
$sSqlDados .= "           then true                  ";
$sSqlDados .= "         else pc13_coddot is not null "; 
$sSqlDados .= "       end                            ";
$result_solicita = $clsolicita->sql_record($sSqlDados);
$numrows = $clsolicita->numrows;

//db_criatabela($result_solicita);


if($numrows==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontradas solicitações com os dados informados.');
}

$head3 = "SOLICITAÇÃO DE COMPRA";
$head6 = "ORDEM DE SELEÇÃO POR ".$info;
$head7 = $msg_head;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt = 5;
$total = 0;
$c = 0;
//    $pdf->addpage("L");
$pc10_numero_ant   = null;
$pc11_codigo_ant   = null;
$valortotal        = "";
$sSomar            = "";

$aDepartamentosListados = array();
$aItensListados         = array();
$aTipoCompraListadas    = array();
$aDotacaoListadas       = array();

$aDepart     = array();
$aItens      = array();
$aDotacao    = array();
$aTipoCompra = array();

$tDep        = 0;
$tMat        = 0;
$tDot        = 0;
$tTipoCompra = 0;

for($i = 0; $i < $numrows;$i++){
  db_fieldsmemory($result_solicita,$i);
  $pass = false;
  if($pdf->gety() > $pdf->h - 32 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    $pdf->cell(22,$alt,"Solicitação",1,0,"C",1);
    $pdf->cell(22,$alt,"Emissão",1,0,"C",1);
    $pdf->cell(91,$alt,"Departamento",1,0,"C",1);
    $pdf->cell(105,$alt,"Tipo",1,0,"C",1);
    $pdf->cell(31,$alt,"Val. Aprox",1,1,"C",1);

    if ($mostrar_itens=="S"){	
         if ($mostra_aut == false){
              $pdf->cell(84,$alt,"",0,0,"C",0);
         } else {
              $pdf->cell(62,$alt,"",0,0,"C",0);
              $pdf->cell(22,$alt,"Aut.",1,0,"C",1);
         }

         $pdf->cell(22,$alt,"Item",1,0,"C",1);
         $pdf->cell(90,$alt,"Material",1,0,"C",1);
         $pdf->cell(22,$alt,"Quantidade",1,0,"C",1);
         $pdf->cell(22,$alt,"Val. Unit.",1,0,"C",1);
         $pdf->cell(31,$alt,"Total",1,1,"C",1);
    
         $pdf->cell(152,$alt,"",0,0,"C",0);
         $pdf->cell(22,$alt,"Dotação",1,0,"C",1);
         $pdf->cell(22,$alt,"Ano",1,0,"C",1);
         $pdf->cell(22,$alt,"Quantidade",1,0,"C",1);
         $pdf->cell(22,$alt,"Val. Unit",1,0,"C",1);
         $pdf->cell(31,$alt,"Total",1,1,"C",1);
    }	 

    $troca=0;
    $pass = true;
    $sSomar = true;
  }

  //Mostra os dados da Solicitação
  if($pc10_numero_ant!=$pc10_numero){
  	
  	if($pass==false){
      $pdf->cell(271,2,'',"T",1,"L",0);
    }
    
    $pdf->setfont('arial','B',8);
    $pdf->cell(22, $alt,$pc10_numero                  ,0,0,"C",0);
    $pdf->cell(22, $alt,$pc10_data                    ,0,0,"C",0);
    $pdf->cell(91, $alt,$descrdepto                   ,0,0,"L",0);
    $pdf->cell(105,$alt,$pc50_descr                   ,0,0,"L",0);
    $pdf->cell(31, $alt,db_formatar($tot_sol,'f')     ,0,1,"R",0);
    $total++;
    
  }
   
 
  //Mostra os dados dos itens da solicitação
  if ($pc11_codigo_ant!=$pc11_codigo || $e55_autori_ant!=$e55_autori){
     $pdf->setfont('arial','B',7);
     $valor_tot = db_formatar($pc11_quant*$pc11_vlrun,'f');
    
     if ($mostrar_itens=="S"){
	     if ($mostra_aut == false){
           $pdf->cell(84,$alt,"",0,0,"C",0);
       } else {
           $pdf->cell(62,$alt,"",0,0,"C",0);
           $pdf->cell(22,$alt,$e55_autori,0,0,"C",0);
       }
       $pdf->cell(22,$alt,$pc11_codigo,0,0,"C",0);
       if (!isset($pc01_descrmater) || (isset($pc01_descrmater) && $pc01_descrmater=="")) {
         $pc01_descrmater = $pc11_resum;
       }
       $pdf->cell(90,$alt,substr($pc01_descrmater,0,50) ,0,0,"L",0);
       $pdf->cell(22,$alt,$pc11_quant                   ,0,0,"C",0);
       $pdf->cell(22,$alt,$pc11_vlrun                   ,0,0,"R",0);
       $pdf->cell(31,$alt,$valor_tot                    ,0,1,"R",0);
       
     }	 
  }
  
  $pdf->setfont('arial','',7);
  
  //Mostra as dotações da solicitação
  if (isset($pc13_coddot) && $pc13_coddot!="" && $mostrar_itens=="S"){
    if(isset($pc13_quant) && $pc13_quant!=0){
      $valor_uni = db_formatar($pc13_valor/$pc13_quant,'f');
    }
    
    $pdf->cell(152, $alt, "",                            0,0,"C",0);
    $pdf->cell(22,  $alt, $pc13_coddot,                  0,0,"C",0);
    $pdf->cell(22,  $alt, $pc13_anousu,                  0,0,"C",0);
    $pdf->cell(22,  $alt, $pc13_quant,                   0,0,"C",0);
    $pdf->cell(22,  $alt, $valor_uni,                    0,0,"R",0);
    $pdf->cell(31,  $alt, db_formatar($pc13_valor,'f'),  0,1,"R",0);
        
  }

    //Insere os dados no array de totalização para o Departamento
    if (!in_array($pc10_depto,$aDepartamentosListados) ) {
      //Se o departamento não está presente no array, insere os seus dados 
      $aDepart[$pc10_depto]['cod']   = $pc10_depto;
    	$aDepart[$pc10_depto]['descr'] = $descrdepto;
      $aDepart[$pc10_depto]['valor'] = $pc13_valor; 
      
      $aDepartamentosListados[] = $pc10_depto; 
    } else {
    	$aDepart[$pc10_depto]['valor'] += $pc13_valor;
    }  

    //Insere os dados no array de totalização para o Material
    if (!in_array($pc11_codigo,$aItensListados) ) {
      
    //Se o departamjento não está presente no array, insere os seus dados 
      $aItens[$pc11_codigo]['cod']   = $pc11_codigo;
      $aItens[$pc11_codigo]['descr'] = $pc01_descrmater;
      $aItens[$pc11_codigo]['valor'] = $pc13_valor;

      $aItensListados[] = $pc11_codigo;
    } else {
    	$aItens[$pc11_codigo]['valor'] += $pc13_valor;
    }

    //Insere os dados no array de totalização para o Tipo de Compra
    if (!in_array($pc12_tipo,$aTipoCompraListadas) ) {
      
      //Se o tipo de compra não está presente no array, insere os seus dados 
      $aTipoCompra[$pc12_tipo]['cod']   = $pc12_tipo;
    	$aTipoCompra[$pc12_tipo]['descr'] = $pc50_descr;
      $aTipoCompra[$pc12_tipo]['valor'] = $pc13_valor; 
      
      $aTipoCompraListadas[] = $pc12_tipo;
    } else {
    	$aTipoCompra[$pc12_tipo]['valor'] += $pc13_valor;
    }    
  
    //Insere os dados no array de totalização para a Dotação
    if (!in_array($pc13_coddot,$aDotacaoListadas) ) {
      
      //Se a dotação não está presente no array, insere os seus dados 
      $aDotacao[$pc13_coddot]['cod']   = $pc13_coddot;
      $aDotacao[$pc13_coddot]['ano']   = $pc13_anousu;
      $aDotacao[$pc13_coddot]['valor'] = $pc13_valor;
      
      $aDotacaoListadas[] = $pc13_coddot;
      
    } else {
    	
    	$aDotacao[$pc13_coddot]['valor'] += $pc13_valor;
    	
    }  

  $pc10_numero_ant = $pc10_numero;
  $pc11_codigo_ant = $pc11_codigo;
  $e55_autori_ant  = $e55_autori; 
  $valortotal += $pc13_valor;
  
}

$tTipoCompra += $valortotal;
$tMat        += $valortotal;
$tDep        += $valortotal;
$tDot        += $valortotal;

$pdf->setfont('arial','b',8);
$pdf->cell(240,$alt+2,'Valor total',"T",0,"R",1);
$pdf->cell(31,$alt+2,db_formatar($valortotal,"f"),"T",1,"R",1);
$pdf->cell(271,$alt,'TOTAL DE SOLICITAÇÕES  :  '.$total,"T",1,"L",0);

if(isset($totdepar)){
    $c = 0;	
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    $pdf->cell(150,$alt,"TOTALIZAÇÃO POR DEPARTAMENTO",1,1,"C",1);
    $pdf->cell(25,$alt,"Código",1,0,"C",1);
    $pdf->cell(100,$alt,"Departamento",1,0,"C",1);
    $pdf->cell(25,$alt,"Valor",1,1,"C",1);
    $pdf->ln($alt);
  
    foreach ($aDepart as $iDepart => $aValores) { 
    	
      if($iDepart == ""){	
           $pdf->cell(25,$alt,  $aValores['cod']                        ,0,0,"C",$c);
           $pdf->cell(100,$alt, $aValores['descr']                      ,0,0,"L",$c);
           $pdf->cell(25,$alt,  db_formatar($aValores['valor'],"f")     ,0,1,"R",$c);
      } else {
           $pdf->cell(25,$alt,  $aValores['cod']                        ,0,0,"C",$c);
           $pdf->cell(100,$alt, $aValores['descr']                      ,0,0,"L",$c);
           $pdf->cell(25,$alt,  db_formatar($aValores['valor'],"f")     ,0,1,"R",$c);      	
      }
    }
      
    $pdf->ln($alt);
    $pdf->cell(125,$alt,"Total:"             ,"T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($tDep,"f"),"T",1,"R",1);	
}

if(isset($tottipco)){
	  $c = 0;
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    $pdf->cell(150,$alt,"TOTALIZAÇÃO POR TIPO DE COMPRA",1,1,"C",1);
    $pdf->cell(25,$alt,"Código",1,0,"C",1);
    $pdf->cell(100,$alt,"Tipo de compra",1,0,"C",1);
    $pdf->cell(25,$alt,"Valor",1,1,"C",1);
    $pdf->ln($alt);

    foreach ($aTipoCompra as $iTipoCompra => $aValores) {    
        if ($pc12_tipo==""){
          $pdf->cell(25,$alt,  "0",                                   0,0,"C",$c);
          $pdf->cell(100,$alt, "Tipo de compra não informado",        0,0,"L",$c);
          $pdf->cell(25,$alt,  db_formatar($aValores['valor'],"f"),   0,1,"R",$c);        	
        } else {
          $pdf->cell(25,$alt,  $aValores['cod'],                      0,0,"C",$c);
          $pdf->cell(100,$alt, $aValores['descr'],                    0,0,"L",$c);
          $pdf->cell(25,$alt,  db_formatar($aValores['valor'],"f"),   0,1,"R",$c);        	
        } 
    }  
    
    $pdf->ln($alt);
    $pdf->cell(125,$alt,"Total:"                     ,"T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($tTipoCompra,"f"),"T",1,"R",1);

}

if(isset($totmater)){
	$c = 0;
	$pdf->addpage("L");
  $pdf->setfont('arial','b',8);
  $pdf->cell(150,$alt,"TOTALIZAÇÃO POR MATERIAIS",1,1,"C",1);
  $pdf->cell(25,$alt,"Código",1,0,"C",1);
  $pdf->cell(100,$alt,"Material",1,0,"C",1);
  $pdf->cell(25,$alt,"Valor",1,1,"C",1);
  $pdf->ln($alt);
   
  foreach ($aItens as $iItens => $aValores) {
    $pdf->cell(25,  $alt, $aValores['cod'],                    0,0,"C",$c);
    $pdf->cell(100, $alt, $aValores['descr'],                  0,0,"L",$c);
    $pdf->cell(25,  $alt, db_formatar($aValores['valor'],"f"), 0,1,"R",$c); 
  }
  
  $pdf->ln($alt);
  $pdf->cell(125,$alt, "Total:"              , "T",0,"R",1);
  $pdf->cell(25, $alt, db_formatar($tMat,"f"), "T",1,"R",1);
	
}

if (isset($totdotac)) {
	 $c = 0;
   $pdf->addpage("L");
   $pdf->setfont('arial','b',8);
   $pdf->cell(150,$alt,"TOTALIZAÇÃO POR DOTAÇÕES",1,1,"C",1);
   $pdf->cell(25,$alt,"Código",1,0,"C",1);
   $pdf->cell(100,$alt,"Ano",1,0,"C",1);
   $pdf->cell(25,$alt,"Valor",1,1,"C",1);
   $pdf->ln($alt);
   
   foreach ($aDotacao as $iDotacao => $aValores) {
      $pdf->cell(25,  $alt, $aValores['cod'],                    0,0,"C",$c);
      $pdf->cell(100, $alt, $aValores['ano'],                    0,0,"L",$c);
      $pdf->cell(25,  $alt, db_formatar($aValores['valor'],"f"), 0,1,"R",$c); 
   }
	 
   $pdf->ln($alt);
   $pdf->cell(125,$alt,"Total:"             ,"T",0,"R",1);
   $pdf->cell(25,$alt,db_formatar($tDot,"f"),"T",1,"R",1);
}
$pdf->Output();
?>