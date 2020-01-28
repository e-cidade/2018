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
 

require ('fpdf151/pdf.php');
include ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_fieldsmemory($result, 0);


$sql = "select * 
        from atendimento 
             inner join clientes on atendimento.at02_codcli = clientes.at01_codcli 
             inner join tecnico on tecnico.at03_codatend = atendimento.at02_codatend 
             inner join db_usuarios on tecnico.at03_id_usuario = db_usuarios.id_usuario
             inner join tipoatend on atendimento.at02_codtipo = tipoatend.at04_codtipo 
		     where 1=1";

if($cliente != ""){
	$sql .= " and at02_codcli = $cliente";
}

if ($tecnico != ""){
	$sql .= " and at03_id_usuario = $tecnico";
	
}

$dataini= $at02_dataini_ano."-".$at02_dataini_mes."-".$at02_dataini_dia;

if ($dataini != "--"){
	$sql .= "and at02_dataini >= '$dataini'";
}


$datafim = $at02_datafim_ano."-".$at02_datafim_mes."-".$at02_datafim_dia;

if ($datafim != "--"){
	$sql .= "and at02_datafim <= '$datafim'";
}


//echo $sql;exit;


$result = pg_exec($sql);

if($result==false || pg_numrows($result)==0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem atendimentos cadastrados!');
   exit;
}

 
//db_criatabela($result);exit;


//db_fieldsmemory($result,0);

$pdf = new PDF(); // estancia a classe
$head1 = "RELATÓRIO DE ATENDIMENTOS";
$head2 = "";
if(isset($cliente)&& $cliente != ""){
	db_fieldsmemory($result,0);
	$head2 = $at01_nomecli;
    }else{
    $head2 = "TODAS AS PREFEITURAS";	
}
$head3 = "";
if($dataini != "--"){
   $head3 = "PERIODO : " .db_formatar($dataini, 'd');
}
if($datafim != "--"){
 	if($dataini != "--"){
 		$head3 .= " até " .db_formatar($datafim, 'd');
 	}else{
 		$head3 = "periodo : " .db_formatar($datafim, 'd');
 	}
}
$head4 = "";
if(isset($tecnico)&& $tecnico != ""){
	db_fieldsmemory($result,0);
	$head4 = "TÉCNICO : " .$login;
    }else{
    $head4 = "ATENDIMENTOS DE TODOS OS TÉCNICOS";	
}
 
 $pdf->open(); // inicia a geração do documento
 $total_geral = 0; // criação de uma variável para somar o total de registros
 $pdf->settextcolor(0,0,0); // seta a cor do texto como preta
 $pdf->setfillcolor(220); // define a cor de preenchimento
 $pdf->setfont('Arial','B',9); // seta a fonte como arial, bold e tamanho 9
 //$pdf->setautopagebreak(true,10);

$ultatend = 0;
$tamanho = 4;
$numlinha = pg_numrows($result);

for($x=0; $x< $numlinha;$x++){
 db_fieldsmemory($result,$x);
  if (($pdf->gety() > ($pdf->h - 30)) || $x == 0 ){
    $pdf->addpage('L'); // adiciona uma pagina no modo paisagem
    $pdf->cell(25,6,"COD.ATEND.",1,0,"C",1);
    $pdf->cell(25,6,"TIPO ATEND.",1,0,"C",1);
    $pdf->cell(90,6,"CLIENTE",1,0,"C",1); // cria as células para o cabeçalho
    $pdf->cell(40,6,"TECNICO(S)",1,0,"C",1);
    $pdf->cell(25,6,"DATA INICIAL",1,0,"C",1);
    $pdf->Cell(25,6,"HORA INICIAL", 1, 0, "C", 1);
    $pdf->cell(25,6,"DATA FINAL",1,0,"C",1);
    $pdf->Cell(25,6,"HORA FINAL",1, 0, "C", 1);
    $pdf->Ln();
    $pdf->Ln();
  }

  if ($ultatend != $at02_codatend) {  
    $pdf->cell(25,$tamanho,$at02_codatend,1,0,"C",0);
    $pdf->cell(25,$tamanho,$at04_descr,1,0,"C",0);
    $pdf->cell(90,$tamanho,$at01_nomecli,1,0,"C",0);
    $pdf->cell(40,$tamanho,$login,1,0,"C",0);
    $pdf->cell(25,$tamanho,db_formatar($at02_dataini, 'd'),1,0,"C",0);
    $pdf->Cell(25,$tamanho,$at02_horaini, 1, 0, "C", 0);
    $pdf->cell(25,$tamanho,db_formatar($at02_datafim, 'd'),1,0,"C",0);
    $pdf->cell(25,$tamanho,$at02_horafim,1,1,"C",0);
    $pdf->Ln();
  
  
  if(trim($at02_solicitado) != ""){
  	$pdf->multiCell(0,$tamanho,"SOLICITADO", 0, "J", 0, 30);
    $pdf->MultiCell(0,$tamanho,''.$at02_solicitado, 0, "J", 0, 30);
    $pdf->Ln();
    
  } 
  
  if(trim($at02_feito) != ""){ 
  	$pdf->MultiCell(0,$tamanho,"REALIZADO", 0, "J", 0, 30);
  	$pdf->MultiCell(0,$tamanho,''.$at02_feito, 0, "J", 0, 30);
  	$pdf->Ln();
    
  }
  
 
  if (trim($at02_observacao) != ""){
  	$pdf->MultiCell(0,$tamanho,''.$at02_observacao, 0, "J", 0, 30);
    $pdf->Ln();
    
  } 
   
      
  }else{
    $pdf->cell(140,$tamanho,"Outros Técnicos:",1,0,"C",0);
    $pdf->cell(40,$tamanho,$login,1,1,"C",0);
    $pdf->Ln();
    
  }

$ultatend = $at02_codatend;      
        
} 

    
$pdf->Output(); // saída do relatório direto para o browser


?>