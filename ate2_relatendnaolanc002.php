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
include("classes/db_db_modulos_classe.php");
$total2 = 0;
$cl_db_modulos = new cl_db_modulos;

db_postmemory($HTTP_POST_VARS);

$pdf = new PDF(); // abre a classe
$head1 = "RELATÓRIO DE ATENDIMENTOS NÃO LANÇADOS";
$head2 = "PERÍODO: ".db_formatar($data,'d')." à ".db_formatar($data1,'d');
$Letra = 'arial';
$pdf->SetFont($Letra,'B',8);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

$sql = "select at06_datalanc,nome,count(*) as quant 
		from atendimentolanc 
		left join atendimento on at06_codatend = at02_codatend 
		left join atenditem on at02_codatend = at05_codatend 
		inner join tecnico on at03_codatend=at02_codatend
		inner join db_usuarios on id_usuario =at03_id_usuario 
		where at06_datalanc >= '$data' and at06_datalanc <= '$data1'
		and at02_datafim is null
		group by at06_datalanc,nome 
		order by nome";
$result = pg_query($sql);
$linhas  = pg_num_rows($result);
$pdf->Cell(40,6,"DATA",1,0,"C",1);
$pdf->Cell(80,6,"NOME",1,0,"C",1);
$pdf->Cell(40,6,"QUANT.",1,1,"C",1);
if($linhas>0){
	for($i=0;$i < $linhas;$i++){
		db_fieldsmemory($result,$i);
		$pdf->SetFont($Letra,"",8);
		$pdf->Cell(40,6,db_formatar($at06_datalanc,'d'),1,0,"C",0);
		$pdf->Cell(80,6,$nome,1,0,"L",0);
		$pdf->Cell(40,6,$quant,1,1,"C",0);
	}
}

$pdf->output();

?>