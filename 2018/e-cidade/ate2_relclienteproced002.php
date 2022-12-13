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

include("fpdf151/pdf.php");

if($totalizar=="cp"){
  $T= "cliente e procedimento.";
}elseif($totalizar=="c"){
  $T= "cliente.";
}elseif($totalizar=="p"){
  $T= "procedimento.";
}elseif($totalizar=="m"){
  $T= "módulo.";
}

if($ordem2=="d"){
  $O2  = " descendente.";
}else{
  $O2  = " ascendente.";
}
$head2 = "Atual : ".db_formatar($data1,"d")." até ".db_formatar($data2,"d");
$head4 = "Antes : ".db_formatar($data3,"d")." até ".db_formatar($data4,"d");
$head6 = "Totalizar por: ".$T;
$head8 = "Ordem : ".$descricao.$O2;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$primeiro =0;
$alt = 5;
if($totalizar=="cp"){
  $sql = "  select cliente,descrproced,";
}elseif($totalizar=="c"){
  $sql = "  select cliente,";
}elseif($totalizar=="p"){
  $sql = "  select descrproced,";
}elseif($totalizar=="m"){
  $sql = "  select nomemod,";
}

$sql .= "
       sum(duvidas_atu)         as duvidas_atu,
       sum(duvidas_fin_atu)     as duvidas_fin_atu,
       sum(duvidas_antes)       as duvidas_antes,
       sum(duvidas_fin_antes)   as duvidas_fin_antes,
       sum(erros_atu)           as erros_atu,
       sum(erros_fin_atu)       as erros_fin_atu,
       sum(erros_antes)         as erros_antes,
       sum(erros_fin_antes)     as erros_fin_antes,
       sum(melhorias_atu)       as melhorias_atu,
       sum(melhorias_fin_atu)   as melhorias_fin_atu,
       sum(melhorias_antes)     as melhorias_antes,
       sum(melhorias_fin_antes) as melhorias_fin_antes,
       sum(base_atu)            as base_atu,
       sum(base_fin_atu)        as base_fin_atu,
       sum(base_antes)          as base_antes,
       sum(base_fin_antes)      as base_fin_antes,
       sum(duvidas_atu+erros_atu+base_atu+melhorias_atu)                         as total_aten_atu,
       sum(duvidas_antes+erros_antes+base_antes+melhorias_antes)           as total_aten_antes,
       sum(duvidas_fin_atu+erros_fin_atu+base_fin_atu+melhorias_fin_atu)         as total_aten_fin_atu,
       sum(duvidas_fin_antes+erros_fin_antes+base_fin_antes+melhorias_fin_antes) as total_aten_fin_antes
from
(
select";
if($totalizar=="cp"){
  $sql .= "  trim(at01_nomecli) as cliente,
            substr(trim(db_syscadproced.descrproced),1,40)||' ('||upper(trim(nomemod))||')' as descrproced,";
}elseif($totalizar=="c"){
  $sql .= " trim(at01_nomecli) as cliente, ";
}elseif($totalizar=="p"){
  $sql .= " substr(trim(db_syscadproced.descrproced),1,40)||' ('||upper(trim(nomemod))||')' as descrproced,";
}elseif($totalizar=="m"){
  $sql .= " upper(trim(nomemod)) as nomemod,";
}

$sql .="
       at02_codatend as atendimento_antes,
       0 as atendimento_atu,
       at06_datalanc as data_atendimento_antes,
       null as data_atendimento_atu,
       at40_sequencial as tarefa_antes,
       0 as tarefa_atu,
       at02_dataini as ini_atendimento_antes,
       null as ini_atendimento_atu,
       (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = at40_sequencial and at43_progresso = 100) as data_fin_antes,
       null as data_fin_atu,
       case when at40_progresso = 100       then 1 else 0 end as finalizada_antes,
       0 as finalizada_atu,
       case when at54_nome = 'DUVIDAS'      then 1 else 0 end as duvidas_antes,
       0 as duvidas_atu,
       case when at54_nome = 'DUVIDAS' 
                 and at40_progresso = 100   then 1 else 0 end as duvidas_fin_antes,
       0 as duvidas_fin_atu,
       case when at54_nome = 'ERRO'         then 1 else 0 end as erros_antes,
       0 as erros_atu,
       case when at54_nome = 'ERRO' 
                 and at40_progresso = 100   then 1 else 0 end as erros_fin_antes,
       0 as erros_fin_atu,
       case when at54_nome = 'MELHORIAS'    then 1 else 0 end as melhorias_antes,
       0 as melhorias_atu,
       case when at54_nome = 'MELHORIAS' 
                 and at40_progresso = 100   then 1 else 0 end as melhorias_fin_antes,
       0 as melhorias_fin_atu,
       case when at54_nome = 'BASE'    then 1 else 0 end as base_antes,
       0 as base_atu,
       case when at54_nome = 'BASE' 
                 and at40_progresso = 100   then 1 else 0 end as base_fin_antes,
       0 as base_fin_atu
       
from atenditem
     inner join atendimento           on at02_codatend     = at05_codatend
     left  join atendimentolanc       on at06_codatend     = at02_codatend
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

where  (at02_datafim >= '$data3' and at02_datafim<= '$data4')


union 


select";
if($totalizar=="cp"){
  $sql .= " trim(at01_nomecli) as cliente,
           substr(trim(db_syscadproced.descrproced),1,40)||' ('||upper(trim(nomemod))||')' as descrproced,";
}elseif($totalizar=="c"){
  $sql .= "  trim(at01_nomecli) as cliente,";
}elseif($totalizar=="p"){
  $sql .= "   substr(trim(db_syscadproced.descrproced),1,40)||' ('||upper(trim(nomemod))||')' as descrproced,";
}elseif($totalizar=="m"){
  $sql .= " upper(trim(nomemod)) as nomemod,";
}

$sql .="
       0 as atendimento_aantes,
       at02_codatend as atendimento_atu,
       null as data_atendimento_antes,
       at06_datalanc as data_atendimento_atu,
       0 as tarefa_antes,
       at40_sequencial as tarefa_atu,
       null as ini_atendimento_antes,
       at02_dataini as ini_atendimento_atu,
       null as data_fin_antes,
       (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = at40_sequencial and at43_progresso = 100) as data_fin_atu,
       0 as finalizada_antes,
       case when at40_progresso = 100      then 1 else 0 end as finalizada_atu,
       0 as duvidas_antes,
       case when at54_nome = 'DUVIDAS'     then 1 else 0 end as duvidas_atu,
       0 as duvidas_fin_antes,
       case when at54_nome = 'DUVIDAS' 
                 and at40_progresso = 100  then 1 else 0 end as duvidas_fin_atu,
       0 as erros_antes,
       case when at54_nome = 'ERRO'        then 1 else 0 end as erros_atu,
       0 as erros_fin_antes,
       case when at54_nome = 'ERRO' 
                 and at40_progresso = 100  then 1 else 0 end as erros_fin_atu,
       0 as melhorias_antes,
       case when at54_nome = 'MELHORIAS'   then 1 else 0 end as melhorias_atu,
       0 as melhorias_fin_antes,
       case when at54_nome = 'MELHORIAS' 
                 and at40_progresso = 100  then 1 else 0 end as melhorias_fin_atu,
       0 as monitoria_antes,
       case when at54_nome = 'BASE'   then 1 else 0 end as base_atu,
       0 as base_fin_antes,
       case when at54_nome = 'BASE' 
                 and at40_progresso = 100  then 1 else 0 end as base_fin_atu
       
from atenditem
     inner join atendimento           on at02_codatend     = at05_codatend
     inner join atendimentolanc       on at06_codatend     = at02_codatend
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

where  (at02_datafim >= '$data1' and at02_datafim<= '$data2')
) as x ";

if($totalizar=="cp"){
  $sql .= "  group by cliente,descrproced";
  $sql .= "  order by cliente,$ordem";
}elseif($totalizar=="c"){
  $sql .= "  group by cliente";
  $sql .= "  order by $ordem";
}elseif($totalizar=="p"){
  $sql .= "  group by descrproced";
  $sql .= "  order by $ordem";
}elseif($totalizar=="m"){
  $sql .= "  group by nomemod ";
  $sql .= "  order by $ordem ";
}


if($ordem2=="d"){
  $sql .= " desc";
}
//die($sql);
$result = pg_exec($sql);
$linhas= pg_num_rows($result);
if($linhas>0){
  if($totalizar=="cp"){
    //totalizador por cliente
    $cliente1="";
    $duvidas_atuT= 0;
    $duvidas_fin_atuT= 0;
    $duvidas_antesT= 0;
    $duvidas_fin_antesT= 0;
    $erros_atuT= 0;
    $erros_fin_atuT= 0;
    $erros_antesT= 0;
    $erros_fin_antesT= 0;
    $melhorias_atuT= 0;
    $melhorias_fin_atuT= 0;
    $melhorias_antesT= 0;
    $melhorias_fin_antesT= 0;
    $base_atuT= 0;
    $base_fin_atuT= 0;
    $base_antesT= 0;
    $base_fin_antesT= 0;
    $total_aten_atuT= 0;
    $total_aten_antesT= 0;
    $total_aten_fin_atuT= 0;
    $total_aten_fin_antesT= 0;
  }
  //totalizador final
    
    $duvidas_atuTT= 0;
    $duvidas_fin_atuTT= 0;
    $duvidas_antesTT= 0;
    $duvidas_fin_antesTT= 0;
    $erros_atuTT= 0;
    $erros_fin_atuTT= 0;
    $erros_antesTT= 0;
    $erros_fin_antesTT= 0;
    $melhorias_atuTT= 0;
    $melhorias_fin_atuTT= 0;
    $melhorias_antesTT= 0;
    $melhorias_fin_antesTT= 0;
    $base_atuTT= 0;
    $base_fin_atuTT= 0;
    $base_antesTT= 0;
    $base_fin_antesTT= 0;
    $total_aten_atuTT= 0;
    $total_aten_antesTT= 0;
    $total_aten_fin_atuTT= 0;
    $total_aten_fin_antesTT= 0;
    
  for($i=0;$i<$linhas;$i++) {
    db_fieldsmemory($result,$i);
    if($pdf->GetY() > ( $pdf->h - 30 )||($primeiro ==0)){
      $primeiro =1;
      //  $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());

      if($totalizar=="cp"){

      }elseif($totalizar=="c"){

      }elseif($totalizar=="p"){

      }
      $pdf->AddPage("L");
      $pdf->SetFont('Arial','B',10);
      $pdf->MultiCell(0,12,"ATENDIMENTOS ",0,"C",0);
      $pdf->SetFont('Arial','B',8);
      if($totalizar=="cp"){
        $pdf->Cell(30,$alt,"","LRT",0,"C",1);
        $pdf->Cell(90,$alt,"","LRT",0,"C",1);
      }else{
        $pdf->Cell(120,$alt,"","LRT",0,"C",1);
      }

      $pdf->Cell(32,$alt,"DÚVIDAS",1,0,"C",1);
      $pdf->Cell(32,$alt,"ERROS",1,0,"C",1);
      $pdf->Cell(32,$alt,"MELHORIAS",1,0,"C",1);
      $pdf->Cell(32,$alt,"MONITORIA",1,0,"C",1);
      $pdf->Cell(32,$alt,"TOTAL",1,0,"C",1);
      $pdf->Ln();

      if($totalizar=="cp"){
        $pdf->Cell(30,$alt,"CLIENTE","LR",0,"C",1);
        $pdf->Cell(90,$alt,"PROCEDIMENTO","LR",0,"C",1);
      }elseif($totalizar=="c"){
        $pdf->Cell(120,$alt,"CLIENTE","LR",0,"C",1);
      }elseif($totalizar=="p"){
        $pdf->Cell(120,$alt,"PROCEDIMENTO","LR",0,"C",1);
      }elseif($totalizar=="m"){
        $pdf->Cell(120,$alt,"MÓDULO","LR",0,"C",1);
      }

      $pdf->Cell(16,$alt,"ATUAL",1,0,"C",1);
      $pdf->Cell(16,$alt,"ANTES",1,0,"C",1);
      $pdf->Cell(16,$alt,"ATUAL",1,0,"C",1);
      $pdf->Cell(16,$alt,"ANTES",1,0,"C",1);
      $pdf->Cell(16,$alt,"ATUAL",1,0,"C",1);
      $pdf->Cell(16,$alt,"ANTES",1,0,"C",1);
      $pdf->Cell(16,$alt,"ATUAL",1,0,"C",1);
      $pdf->Cell(16,$alt,"ANTES",1,0,"C",1);
      $pdf->Cell(16,$alt,"ATUAL",1,0,"C",1);
      $pdf->Cell(16,$alt,"ANTES",1,0,"C",1);
      $pdf->Ln();

      if($totalizar=="cp"){
        $pdf->Cell(30,$alt,"","LRB",0,"C",1);
        $pdf->Cell(90,$alt,"","LRB",0,"C",1);
      }else{
        $pdf->Cell(120,$alt,"","LRB",0,"C",1);
      }

      //duvida
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      //erro
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      // melhorias
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      //monitorias
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      //soma
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);
      $pdf->Cell(8,$alt,"A",1,0,"C",1);
      $pdf->Cell(8,$alt,"F",1,0,"C",1);

      $pdf->Ln();
    }

    $pdf->SetFont('Arial','',7);
    if($totalizar=="cp"){
      if($cliente1 != $cliente){
        if($cliente1 !=""){
          $pdf->SetFont('Arial','B',8);
          //............TOTALIZADOR .............
          $pdf->Cell(120,$alt,$cliente1." resultado","LBT",0,"L",0);
          //duvidas
          $pdf->Cell(8,$alt,"$duvidas_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$duvidas_fin_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$duvidas_antesT",1,0,"C",0);
          $pdf->Cell(8,$alt,"$duvidas_fin_antesT",1,0,"C",0);
          //erro
          $pdf->Cell(8,$alt,"$erros_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$erros_fin_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$erros_antesT",1,0,"C",0);
          $pdf->Cell(8,$alt,"$erros_fin_antesT",1,0,"C",0);
          // melhorias
          $pdf->Cell(8,$alt,"$melhorias_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$melhorias_fin_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$melhorias_antesT",1,0,"C",0);
          $pdf->Cell(8,$alt,"$melhorias_fin_antesT",1,0,"C",0);
          //monitorias
          $pdf->Cell(8,$alt,"$base_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$base_fin_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$base_antesT",1,0,"C",0);
          $pdf->Cell(8,$alt,"$base_fin_antesT",1,0,"C",0);
          //soma
          $pdf->Cell(8,$alt,"$total_aten_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$total_aten_fin_atuT",1,0,"C",1);
          $pdf->Cell(8,$alt,"$total_aten_antesT",1,0,"C",0);
          $pdf->Cell(8,$alt,"$total_aten_fin_antesT",1,0,"C",0);
          $pdf->Ln(10);
          $duvidas_atuT= 0;
          $duvidas_fin_atuT= 0;
          $duvidas_antesT= 0;
          $duvidas_fin_antesT= 0;
          $erros_atuT= 0;
          $erros_fin_atuT= 0;
          $erros_antesT= 0;
          $erros_fin_antesT= 0;
          $melhorias_atuT= 0;
          $melhorias_fin_atuT= 0;
          $melhorias_antesT= 0;
          $melhorias_fin_antesT= 0;
          $base_atuT= 0;
          $base_fin_atuT= 0;
          $base_antesT= 0;
          $base_fin_antesT= 0;
          $total_aten_atuT= 0;
          $total_aten_antesT= 0;
          $total_aten_fin_atuT= 0;
          $total_aten_fin_antesT= 0;

           
        }
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(30,$alt,$cliente,"TL",0,"L",0);
      }else{
        $pdf->Cell(30,$alt,"","L",0,"L",0);
      }
      $pdf->Cell(90,$alt,$descrproced,1,0,"L",0);
    }
    if($totalizar=="p"){
      // $pdf->Cell(30,$alt,$cliente,"LRB",0,"C",1);
      $pdf->Cell(120,$alt,$descrproced,1,0,"L",0);
    }elseif($totalizar=="c"){
      $pdf->Cell(120,$alt,$cliente,1,0,"L",0);
    }elseif($totalizar=="m"){
      $pdf->Cell(120,$alt,$nomemod,1,0,"L",0);
    }
    //duvida
    $pdf->Cell(8,$alt,$duvidas_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$duvidas_fin_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$duvidas_antes,1,0,"C",0);
    $pdf->Cell(8,$alt,$duvidas_fin_antes,1,0,"C",0);
    //erro
    $pdf->Cell(8,$alt,$erros_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$erros_fin_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$erros_antes,1,0,"C",0);
    $pdf->Cell(8,$alt,$erros_fin_antes,1,0,"C",0);
    // melhorias
    $pdf->Cell(8,$alt,$melhorias_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$melhorias_fin_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$melhorias_antes,1,0,"C",0);
    $pdf->Cell(8,$alt,$melhorias_fin_antes,1,0,"C",0);
    //monitorias
    $pdf->Cell(8,$alt,$base_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$base_fin_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$base_antes,1,0,"C",0);
    $pdf->Cell(8,$alt,$base_fin_antes,1,0,"C",0);
    //soma
    $pdf->Cell(8,$alt,$total_aten_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$total_aten_fin_atu,1,0,"C",1);
    $pdf->Cell(8,$alt,$total_aten_antes,1,0,"C",0);
    $pdf->Cell(8,$alt,$total_aten_fin_antes,1,0,"C",0);
    $pdf->Ln();
    if($totalizar=="cp"){
      $cliente1             = $cliente;
      $duvidas_atuT         = $duvidas_atuT + $duvidas_atu;
      $duvidas_fin_atuT     = $duvidas_fin_atuT + $duvidas_fin_atu;
      $duvidas_antesT       = $duvidas_antesT + $duvidas_antes;
      $duvidas_fin_antesT   = $duvidas_fin_antesT+ $duvidas_fin_antes;
      $erros_atuT           = $erros_atuT + $erros_atu;
      $erros_fin_atuT       = $erros_fin_atuT + $erros_fin_atu;
      $erros_antesT         = $erros_antesT + $erros_antes;
      $erros_fin_antesT     = $erros_fin_antesT + $erros_fin_antes;
      $melhorias_atuT       = $melhorias_atuT+$melhorias_atu;
      $melhorias_fin_atuT   = $melhorias_fin_atuT + $melhorias_fin_atu;
      $melhorias_antesT     = $melhorias_antesT + $melhorias_antes;
      $melhorias_fin_antesT = $melhorias_fin_antesT + $melhorias_fin_antes;
      $base_atuT            = $base_atuT + $base_atu;
      $base_fin_atuT        = $base_fin_atuT + $base_fin_atu;
      $base_antesT          = $base_antesT + $base_antes;
      $base_fin_antesT      = $base_fin_antesT + $base_fin_antes;
      $total_aten_atuT      = $total_aten_atuT + $total_aten_atu;
      $total_aten_fin_atuT  = $total_aten_fin_atuT + $total_aten_fin_atu;
      $total_aten_antesT    = $total_aten_antesT + $total_aten_antes;
      $total_aten_fin_antesT= $total_aten_fin_antesT + $total_aten_fin_antes;
    }
    //TOTALIZADOR FINAL
      $duvidas_atuTT         = $duvidas_atuTT + $duvidas_atu;
      $duvidas_fin_atuTT     = $duvidas_fin_atuTT + $duvidas_fin_atu;
      $duvidas_antesTT       = $duvidas_antesTT + $duvidas_antes;
      $duvidas_fin_antesTT   = $duvidas_fin_antesTT+ $duvidas_fin_antes;
      $erros_atuTT           = $erros_atuTT + $erros_atu;
      $erros_fin_atuTT       = $erros_fin_atuTT + $erros_fin_atu;
      $erros_antesTT         = $erros_antesTT + $erros_antes;
      $erros_fin_antesTT     = $erros_fin_antesTT + $erros_fin_antes;
      $melhorias_atuTT       = $melhorias_atuTT+$melhorias_atu;
      $melhorias_fin_atuTT   = $melhorias_fin_atuTT + $melhorias_fin_atu;
      $melhorias_antesTT     = $melhorias_antesTT + $melhorias_antes;
      $melhorias_fin_antesTT = $melhorias_fin_antesTT + $melhorias_fin_antes;
      $base_atuTT            = $base_atuTT + $base_atu;
      $base_fin_atuTT        = $base_fin_atuTT + $base_fin_atu;
      $base_antesTT          = $base_antesTT + $base_antes;
      $base_fin_antesTT      = $base_fin_antesTT + $base_fin_antes;
      $total_aten_atuTT      = $total_aten_atuTT + $total_aten_atu;
      $total_aten_fin_atuTT  = $total_aten_fin_atuTT + $total_aten_fin_atu;
      $total_aten_antesTT    = $total_aten_antesTT + $total_aten_antes;
      $total_aten_fin_antesTT= $total_aten_fin_antesTT + $total_aten_fin_antes;

  }
  $pdf->SetFont('Arial','B',7);
  // tem q colocar o ultimo aki.........
  if($totalizar=="cp"){
    $pdf->SetFont('Arial','B',8);
    //............TOTALIZADOR .............
    $pdf->Cell(120,$alt,$cliente1." resultado","LBT",0,"L",0);
    //duvidas
    $pdf->Cell(8,$alt,"$duvidas_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$duvidas_fin_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$duvidas_antesT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$duvidas_fin_antesT",1,0,"C",0);
    //erro
    $pdf->Cell(8,$alt,"$erros_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$erros_fin_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$erros_antesT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$erros_fin_antesT",1,0,"C",0);
    // melhorias
    $pdf->Cell(8,$alt,"$melhorias_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$melhorias_fin_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$melhorias_antesT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$melhorias_fin_antesT",1,0,"C",0);
    //monitorias
    $pdf->Cell(8,$alt,"$base_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$base_fin_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$base_antesT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$base_fin_antesT",1,0,"C",0);
    //soma
    $pdf->Cell(8,$alt,"$total_aten_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$total_aten_fin_atuT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$total_aten_antesT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$total_aten_fin_antesT",1,0,"C",0);
    $pdf->Ln(10);
  }
    $pdf->Ln();
    $pdf->SetFont('Arial','B',8);
    //............TOTALIZADOR FINAL.............
    $pdf->Cell(120,$alt,"TOTAL",1,0,"L",0);
    //duvidas
    $pdf->Cell(8,$alt,"$duvidas_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$duvidas_fin_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$duvidas_antesTT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$duvidas_fin_antesTT",1,0,"C",0);
    //erro
    $pdf->Cell(8,$alt,"$erros_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$erros_fin_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$erros_antesTT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$erros_fin_antesTT",1,0,"C",0);
    // melhorias
    $pdf->Cell(8,$alt,"$melhorias_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$melhorias_fin_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$melhorias_antesTT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$melhorias_fin_antesTT",1,0,"C",0);
    //monitorias
    $pdf->Cell(8,$alt,"$base_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$base_fin_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$base_antesTT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$base_fin_antesTT",1,0,"C",0);
    //soma
    $pdf->Cell(8,$alt,"$total_aten_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$total_aten_fin_atuTT",1,0,"C",1);
    $pdf->Cell(8,$alt,"$total_aten_antesTT",1,0,"C",0);
    $pdf->Cell(8,$alt,"$total_aten_fin_antesTT",1,0,"C",0);
    $pdf->Ln(10);
  
}

$pdf->Output();

?>