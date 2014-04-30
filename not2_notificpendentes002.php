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

set_time_limit(0);
include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_notificacao_classe.php");

//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
db_postmemory($HTTP_SERVER_VARS);

$clnotificacao = new cl_notificacao;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");
$clnotificacao->rotulo->label();

$instit = db_getsession("DB_instit");

$sqllista = "select k60_tipo from lista where k60_codigo = ".$lista." and k60_instit = $instit";
$resultlista = db_query($sqllista);
db_fieldsmemory($resultlista,0);
if ($k60_tipo == 'M'){
   $xcodigo  = 'k55_matric';
   $xrel = $RLj01_matric;
   $xinner = "inner join notimatric on k55_notifica = k50_notifica
              inner join iptubase   on j01_matric   = k55_matric
              inner join cgm        on j01_numcgm   = z01_numcgm";
}elseif($k60_tipo == 'I'){
   $xcodigo = 'k56_inscr';
   $xrel    = $RLq02_inscr;
   $xinner  = "inner join notiinscr  on k56_notifica = k50_notifica
               inner join issbase    on q02_inscr    = k56_inscr
               inner join cgm        on q02_numcgm   = z01_numcgm";
}elseif($k60_tipo == 'N'){
   $xcodigo = 'k57_numcgm';
   $xrel    = $RLz01_numcgm;
   $xinner  = "inner join notinumcgm on k57_notifica = k50_notifica
               inner join cgm        on k57_numcgm   = z01_numcgm";
}
/*
  $sql = "select * from ("; 
  $sql .= " select $xcodigo, ";
  $sql .= "	       k50_obs, ";
  $sql .= "	       z01_nome, ";
  $sql .= "	       k59_descr, ";
  $sql .= "	       k00_numpre,";
  $sql .= "	       k00_numpar,";
  $sql .= "	       k00_dtoper, ";
  $sql .= "	       substr( v03_descr,1, 20) as descricao, ";
  $sql .= "	       v01_exerc as ano";
  $sql .= "	  from notificacao ";
  $sql .= "	       $xinner";
  $sql .= "	       inner join listanotifica on k63_notifica = k50_notifica ";
  $sql .= "	       inner join noticonf      on k54_notifica = k50_notifica ";
  $sql .= "	       inner join notisitu      on k59_codigo   = k54_codigo ";
  $sql .= "	       inner join divida        on v01_numpre   = k63_numpre ";
  $sql .= "	       inner join proced        on v03_codigo   = v01_proced ";
  $sql .= "	       inner join arrecad       on k00_numpre   = v01_numpre ";
  $sql .= "	                               and k00_numpar   = v01_numpar ";
  $sql .= "        inner join cadtipo       on k03_tipo     = k00_tipo ";
  $sql .= "	 where k63_codigo = $lista";
  $sql .= "	   and k54_codigo = $situ";
  $sql .= "	   and k03_tipo   = 5 ";
  $sql .= "union ";
*/

  $sql = " select $xcodigo, ";
  $sql .= "	       k50_obs, ";
  $sql .= "	       z01_nome, ";
  $sql .= "	       k59_descr, ";
//  $sql .= "	       k00_numpre,";
$sql .= "	       k63_numpre";
//  $sql .= "	       0 as k00_numpar,";
//  $sql .= "	       k00_numpar,";
//  $sql .= "	       k00_dtoper, ";
//  $sql .= "	       substr( 'PARCELAMENTO', 1, 20 ) as descricao,"; // arretipo
//  $sql .= "	       substr( k00_descr, 1, 20 ) as descricao,"; // arretipo
 // $sql .= "	       extract( year from k00_dtoper ) as ano";
  $sql .= "	  from notificacao ";
  $sql .= "        $xinner";
  $sql .= "	       inner join listanotifica on k63_notifica = k50_notifica ";
  $sql .= "	       inner join noticonf      on k54_notifica = k50_notifica ";
  $sql .= "	       inner join notisitu      on k59_codigo   = k54_codigo ";
//  $sql .= "	       inner join arrecad       on k00_numpre   = k63_numpre ";
//  $sql .= "	                               and k00_numpar   = v01_numpar ";
//  $sql .= "	       inner join arretipo      on arretipo.k00_tipo = arrecad.k00_tipo ";
//  $sql .= "        inner join cadtipo       on cadtipo.k03_tipo     = k00_tipo ";
  $sql .= "	where k63_codigo = $lista ";
  $sql .= "   and k54_codigo = $situ ";
  $sql .= "   and k50_instit = $instit";
//  $sql .= "	   and k03_tipo = 6 ) xx ";
  $sql .= " order by z01_nome,";
  $sql .= "          $xcodigo,";
  $sql .= "          k63_numpre";

//$sql .= "      limit 5 ";

//die($sql);

$result = $clnotificacao->sql_record($sql);
$rows1 = $clnotificacao->numrows;
if($rows1 == 0){
  
   $sMsg = _M('tributario.notificacoes.not2_notificpendentes002.sem_registro');
   db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
   exit;
}
db_fieldsmemory($result,0);
$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages();
$head3   = "Notificações";
$head4   = "Situação: ".$k59_descr;
$head6   = $k50_obs;

//$xcodigo = @$k60_tipo=="M"?@$k55_matric:@$k60_tipo=="I"?@$k56_inscr:@$k57_numcgm;
if($k60_tipo == "M"){
  $xcodigo = $k55_matric;
}else if ($k60_tipo =="I"){
  $xcodigo = $k56_inscr;
}else{
  $xcodigo = $k57_numcgm;
}
$pri          = true;
$flag         = 0;
$tot          = 0;
$tvlrhis      = 0;
$tvlrcor      = 0;
$tvlrjuros    = 0;
$tvlrmulta    = 0;
$tvlrdesconto = 0;
$ttotal       = 0;
$anos         = "";
$proced       = "";
$vir          = "";
for($i=0;$i<$rows1;$i++){
  
  db_fieldsmemory($result,$i);
//die($k60_tipo);
  if($k60_tipo == "M"){
    $xcodigo = $k55_matric;
  }else if ($k60_tipo =="I"){
    $xcodigo = $k56_inscr;
  }else{
    $xcodigo = $k57_numcgm;
  }

// $xcodigo = ($k60_tipo=="M"?$k55_matric:$k60_tipo=="I"?$k56_inscr:$k57_numcgm);

  $result_deb = debitos_numpre($k63_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,"k00_numpre");
  if($result_deb){
    $rowsdeb    = pg_num_rows($result_deb); 
  }else{
    $rowsdeb    = 0; 
  }
// $ano        = '';
// $descricao  = '';
// echo $rowsdeb."<br><br>";
// db_criatabela($result_deb);
  if(!isset($rowsdeb) || $rowsdeb == 0 ){
      continue;
  }     
  for($x = 0;$x < $rowsdeb; $x++){
    db_fieldsmemory($result_deb,$x);
    $sqlArrecad =  " select substr( k00_descr, 1, 20 ) as descricao, "; 
    $sqlArrecad .= "        extract( year from k00_dtoper ) as ano ";
    $sqlArrecad .= "   from arrecad ";
    $sqlArrecad .= "      inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
    $sqlArrecad .= " where k00_numpre = $k63_numpre limit 1 ";
    $rsArrecad  = db_query($sqlArrecad);
    $rowsarrec  = pg_num_rows($rsArrecad); 
    if($rowsarrec <> 0 ){
      db_fieldsmemory($rsArrecad,0);        
    }


    if(($pdf->gety() > $pdf->h -30)||$pri==true){
      $pdf->addpage();
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',5);
      $pdf->cell(18,2,$xrel,1,0,"L",1);
      $pdf->cell(91,2,$RLz01_nome,1,0,"L",1);
      $pdf->cell(17,2,"Valor",1,0,"L",1);
      $pdf->cell(17,2,"Corrigido",1,0,"L",1);
      $pdf->cell(15,2,"Juro",1,0,"L",1);
      $pdf->cell(15,2,"Multa",1,0,"L",1);
      $pdf->cell(17,2,"Total",1,1,"L",1);
      $pri = false;
   }

   if($flag != $xcodigo){
     if( $tvlrhis != 0 ){
       $pdf->cell(109,2,"TOTAL",1,0,"R",0);
       $pdf->cell(17,2,$tvlrhis,1,0,"R",0);
       $pdf->cell(17,2,$tvlrcor,1,0,"R",0);
       $pdf->cell(15,2,$tvlrjuros,1,0,"R",0);
       $pdf->cell(15,2,$tvlrmulta,1,0,"R",0);
       $pdf->cell(17,2,$ttotal,1,1,"R",0);
       $tvlrhis      = 0;
       $tvlrcor      = 0;
       $tvlrjuros    = 0;
       $tvlrmulta    = 0;
       $tvlrdesconto = 0;
       $ttotal       = 0;
     }
     $flag = $xcodigo;
     $pdf->setfont('arial','b',5);
     $pdf->cell(18,2,$flag,0,0,"L",0);
     $pdf->cell(91,2,$z01_nome,0,1,"L",0);
     $pdf->setfont('arial','',5);
     $tot++;
   }
    $pdf->cell(18,2,$ano,0,0,"R",0);
    $pdf->cell(91,2,$descricao,0,0,"L",0);
    $pdf->cell(17,2,$vlrhis,0,0,"R",0);
    $pdf->cell(17,2,$vlrcor,0,0,"R",0);
    $pdf->cell(15,2,$vlrjuros,0,0,"R",0);
    $pdf->cell(15,2,$vlrmulta,0,0,"R",0);
    $pdf->cell(17,2,$total,0,1,"R",0);

    $tvlrhis    += $vlrhis;
    $tvlrcor    += $vlrcor;
    $tvlrjuros  += $vlrjuros;
    $tvlrmulta  += $vlrmulta;
    $ttotal     += $total;
  }
  //  flush();
}
if( $tvlrhis != 0 ){
  $pdf->cell(109,2,"TOTAL",1,0,"R",0);
  $pdf->cell(17,2,$tvlrhis,1,0,"R",0);
  $pdf->cell(17,2,$tvlrcor,1,0,"R",0);
  $pdf->cell(15,2,$tvlrjuros,1,0,"R",0);
  $pdf->cell(15,2,$tvlrmulta,1,0,"R",0);
  $pdf->cell(17,2,$ttotal,1,1,"R",0);
  $tvlrhis      = 0;
  $tvlrcor      = 0;
  $tvlrjuros    = 0;
  $tvlrmulta    = 0;
  $tvlrdesconto = 0;
  $ttotal       = 0;
}

$pdf->setfont('arial','b',3);
$pdf->cell(190,2,"Total de Registros: ".$tot,1,1,"L",1);
$pdf->Output();
?>