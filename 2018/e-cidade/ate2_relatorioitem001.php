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

require('fpdf151/pdf.php');
include("classes/db_tecnico_classe.php");
include("classes/db_clientes_classe.php");
include("classes/db_atendimento_classe.php");
include("classes/db_atenditem_classe.php");
include("dbforms/db_funcoes.php");
$clclientes = new cl_clientes;
$cltecnico = new cl_tecnico;
$clatendimento = new cl_atendimento;
$clatenditem   = new cl_atenditem;
$clrotulo = new rotulocampo;
$clrotulo->label('nome');
$clrotulo->label('at01_codcli');
$clrotulo->label('at02_dataini');
$clrotulo->label('at02_datafim');
if(isset($at05_data_dia) && $at05_data_dia != ""){
  $where = " atenditem.at05_data <= '".$at05_data_ano."-".$at05_data_mes."-".$at05_data_dia."'";
}
$result = $clatenditem->sql_record($clatenditem->sql_query("","","*","","$where"));
$numrows = $clatenditem->numrows;
$pdf = new PDF(); // abre a classe
$head1 = "RELATÓRIO DE ATENDIMENTOS VENCIDOS";
$Letra = 'arial';
$pdf->SetFont($Letra,'B',8);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
if($clatenditem->numrows > 0){
  $posicao = $pdf->getY();
  $pdf->SetFont($Letra,'B',7);
  $pdf->SetFillColor(225);
  $pdf->Cell(20,4,"CÓDIGO: ",1,0,"L",1);
  $pdf->Cell(80,4,"SOLICITADO: ",1,0,"L",1);
  $pdf->Cell(80,4,"EXECUTADO: ",1,1,"L",1);
  $pdf->SetWidths(array(20,80,80));
  for($x=0;$x<$numrows;$x++){
    db_fieldsmemory($result,$x);
    $pdf->SetFont($Letra,'I',6);
    if ( $pdf->GetY() > 240) {
      $pdf->AddPage();
      $pdf->Cell(20,4,"CÓDIGO: ",1,0,"L",1);
      $pdf->Cell(80,4,"SOLICITADO: ",1,0,"L",1);
      $pdf->Cell(80,4,"EXECUTADO: ",1,1,"L",1);
    }
    $pdf->Row(array($at05_seq,$at05_solicitado,$at05_feito),3);
  }
}
$pdf->Ln(2);
$pos  = $pdf->GetY();
$pos1 = $pdf->GetX();
if ( $pdf->GetY() > 240) {
  $pdf->AddPage();
  $pdf->Ln(40);
}
$pdf->output();
?>