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

require ('fpdf151/pdf.php');
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
/*
echo "
data1 = $data1 <br> 
data2= $data2 <br>
motivo= $motivo <br>
cliente=$cliente <br>
tecnico=$tecnico <br>
modulo=$modulo <br>
proced=$proced
";
*/
$where = " Where 1=1 ";
// PERIODO
if((@$data1!="--")&&(@$data2!="--")){
  $where .= " and (at02_datafim >= '$data1' and at02_datafim<= '$data2') ";
}
// MOTIVO
if(@$motivo!=""){
  $where .= " and at54_sequencial in ($motivo) ";
  
}
// CLIENTE
if($cliente!=""){
  $where .= " and at01_codcli in ($cliente) ";
}

// TECNICO
if($tecnico!=""){
  $where .= " and at03_id_usuario in ($tecnico) ";
}

// MODULO
if($modulo!=""){
  $where .= " and at22_modulo in ($modulo) ";
}

// PROCEDIMENTO
if(@$proced!=""){
  $where .= " and at29_syscadproced in ($proced) ";
}

// AREA
if(@$areas!=""){
  $where .= " and at26_sequencial in ($areas) ";
}

//die ($where);
//tarefamotivo     on at55_tarefa          = at40_sequencial
//atendcadarea     on at26_sequencial      = at33_atendcadarea
//tarefasyscadproc
//db_syscadproced
//atendcadarea


$sql="
select case when at44_tarefa is null 
            then at18_tarefa 
            else at44_tarefa 
       end as at44_tarefa,
       at05_codatend as atendimento, 
       at05_seq as item, 
       nome as tec, 
       at01_nomecli as cliente,
       at54_nome as motivo, 
       at02_datafim,
       at05_horaini,
       at05_horafim,
       at05_horafim::time-at05_horaini::time as tempo,
       at05_solicitado, 
       at05_feito, 
       db_sysmodulo.nomemod, 
       db_syscadproced.descrproced,
       at25_descr as area

from atenditem

inner join atendimento           on at02_codatend     = at05_codatend
left  join tarefaitem            on at44_atenditem    = at05_seq
left  join tarefa                on at44_tarefa       = at40_sequencial
left  join tecnico               on at03_codatend     = at05_codatend
left  join db_usuarios           on id_usuario        = at03_id_usuario
inner join clientes              on at01_codcli       = at02_codcli
inner join atenditemmotivo       on at34_atenditem    = at05_seq
inner join tarefacadmotivo       on at54_sequencial   = at34_tarefacadmotivo
inner join atenditemmod          on at22_atenditem    = at05_seq
inner join db_sysmodulo          on at22_modulo       = db_sysmodulo.codmod
inner join atenditemsyscadproced on at29_atenditem    = at05_seq
inner join db_syscadproced       on at29_syscadproced = db_syscadproced.codproced
left  join tarefasyscadproced    on at37_tarefa       = at40_sequencial
left  join atendarea             on at02_codatend     = codarea         
left  join atendcadarea          on codarea           = at26_sequencial
left  join atenditemtarefa       on at18_atenditem    = at05_seq
$where
order by at02_datafim, nome, at05_horaini
";
//die($sql);
$result = pg_query($sql) or die($sql);
$linhas  = pg_num_rows($result);
if($linhas>0){
  $total = 0;
  $pdf = new PDF(); // abre a classe
  $head1 = "RELATÓRIO DE CONFERENCIA DE ATENDIMENTOS";
  $head2 = "PERÍODO: ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
  $Letra = 'arial';
  $pdf->SetFont($Letra,'B',7);
  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(210);
  
  $totalpormodulo  = array();
  $totalpormotivo  = array();
  $totalporcliente = array();
  $totalporproced  = array();
  $totalportecnico = array();
  $totalporarea    = array();
  
  $array_totays = array();
  
  $array_totays[0][0] = "MÓDULO";
  $array_totays[0][1] = "totalpormodulo";
  $array_totays[0][2] = "nomemod";
  
  $array_totays[1][0] = "MOTIVO";
  $array_totays[1][1] = "totalpormotivo";
  $array_totays[1][2] = "motivo";
  
  $array_totays[2][0] = "CLIENTE";
  $array_totays[2][1] = "totalporcliente";
  $array_totays[2][2] = "cliente";
  
  $array_totays[3][0] = "PROCEDIMENTO";
  $array_totays[3][1] = "totalporproced";
  $array_totays[3][2] = "descrproced";
  
  $array_totays[4][0] = "TÉCNICO";
  $array_totays[4][1] = "totalportecnico";
  $array_totays[4][2] = "tec";

  $array_totays[5][0] = "ÁREA";
  $array_totays[5][1] = "totalporarea";
  $array_totays[5][2] = "area";
  
  //at44_tarefa ,at05_codatend as atendimento, at05_seq as item, nome as tec, at01_nomecli as cliente,at54_nome as motivo, at02_datafim,at05_solicitado, at05_feito
  if($tipo <> 2 ){
    $pdf->AddPage(); // adiciona uma pagina
    $pdf->Cell(11,5,"TAREFA",1,0,"C",1);
    $pdf->Cell(11,5,"ATEND.",1,0,"C",1);
    $pdf->Cell(20,5,"TÉCNICO",1,0,"L",1);
    $pdf->Cell(25,5,"CLIENTE",1,0,"L",1);
    $pdf->Cell(23,5,"MOTIVO",1,0,"L",1);
    $pdf->Cell(30,5,"MODULO",1,0,"L",1);
    $pdf->Cell(25,5,"ÁREA",1,0,"L",1);
    $pdf->Cell(14,5,"DATA",1,0,"L",1);
    $pdf->Cell(10,5,"INI",1,0,"L",1);
    $pdf->Cell(10,5,"FIM",1,0,"L",1);
    $pdf->Cell(11,5,"TEMPO",1,0,"L",1);
    $pdf->Ln();
    $pdf->Cell(190,5,"SOLICITADO",1,1,"L",1);
    $pdf->Cell(190,5,"FEITO",1,1,"L",1);
  }
  if($linhas>0){
    for($i=0;$i < $linhas;$i++){
      db_fieldsmemory($result,$i);
      
      /*
      if($i % 2 == 0){
        $corfundo = 235;
      }else{
        $corfundo = 250;	
      }
      */
      if($tipo <> 2 ){
         $pdf->SetFillColor(235);
         $pdf->SetFont($Letra,"",7);
         $pdf->Cell(11,5,$at44_tarefa,1,0,"C",1);
         $pdf->Cell(11,5,$atendimento,1,0,"C",1);
         $pdf->Cell(20,5,substr("$tec", 0, 11),1,0,"L",1);
         $pdf->Cell(25,5,substr("$cliente", 0, 16),1,0,"L",1);
         $pdf->Cell(23,5,substr("$motivo", 0, 14),1,0,"L",1);
         $pdf->Cell(30,5,substr(strtoupper($nomemod), 0, 16),1,0,"L",1);
         $pdf->Cell(25,5,substr("$area", 0, 16),1,0,"L",1);
         $pdf->Cell(14,5,db_formatar($at02_datafim,'d'),1,0,"L",1);
         $pdf->Cell(10,5,$at05_horaini,1,0,"L",1);
         $pdf->Cell(10,5,$at05_horafim,1,0,"L",1);
         $pdf->Cell(11,5,$tempo,1,0,"L",1);
         $pdf->Ln();

         $pdf->MultiCell(190,5,$at05_solicitado,1,"L",0);
         $pdf->MultiCell(190,5,$at05_feito,1,"L",0);
         $total = $total +1;
      }
      // total por modulo
      if (!isset($totalpormodulo[$nomemod][0])) {
        $totalpormodulo[$nomemod][0] = 1;
      } else {
        $totalpormodulo[$nomemod][0] += 1;
      }
      
      // total por motivo
      if (!isset($totalpormotivo[$motivo][0])) {
        $totalpormotivo[$motivo][0] = 1;
      } else {
        $totalpormotivo[$motivo][0] += 1;
      }
      
      // total por cliente
      if (!isset($totalporcliente[$cliente][0])) {
        $totalporcliente[$cliente][0] = 1;
      } else {
        $totalporcliente[$cliente][0] += 1;
      }
      
      // total por procedimento
      if (!isset($totalporproced[$descrproced][0])) {
        $totalporproced[$descrproced][0] = 1;
      } else {
        $totalporproced[$descrproced][0] += 1;
      }
      
      // total por tecnico
      if (!isset($totalportecnico[$tec][0])) {
        $totalportecnico[$tec][0] = 1;
      } else {
        $totalportecnico[$tec][0] += 1;
      }

      // total por area
      if (!isset($totalporarea[$area][0])) {
        $totalporarea[$area][0] = 1;
      } else {
        $totalporarea[$area][0] += 1;
      }
      
    }
    if($tipo <> 2 ){
      $pdf->Cell(190,5,"TOTAL = ".$total ,1,0,"R",1);
    }
  }
  if($tipo <> 3){
     for ($reg=0; $reg < sizeof($array_totays); $reg++) {
       
       $pdf->AddPage();
       $pdf->cell(80,5,"TOTAL POR " . $array_totays[$reg][0],1,1,"C",1);
       $pdf->ln(2);
       
       $pdf->cell(60,5,$array_totays[$reg][0],1,0,"L",1);
       $pdf->cell(20,5,"QUANT",1,1,"C",1);
       
     	array_multisort($$array_totays[$reg][1], SORT_DESC);
     	
       $total_quant=0;
       foreach ($$array_totays[$reg][1] as $k => $v) {
         $pdf->cell(60,5,$k,0,0,"L",0);
         $pdf->cell(20,5,db_formatar($v[0], 'f'),0,1,"R",0);
         $total_quant+=$v[0];
       }
       $pdf->cell(60,5,"TOTAL",1,0,"L",1);
       $pdf->cell(20,5,db_formatar($total_quant, 'f'),1,1,"R",1);
       
     }
  } 
  $pdf->output();
  
  
}else{
  db_msgbox("Nenhum registro encontrado.");
}

?>