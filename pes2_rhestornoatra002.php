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

include("fpdf151/scpdf.php");
include("libs/db_sql.php");
include("classes/db_rhpagocor_classe.php");
$clrhpagocor = new cl_rhpagocor;
db_postmemory($HTTP_POST_VARS);

if(trim($movimento) == ""){
  db_redireciona('db_erros.php?fechar=true&db_erro=Informe um movimento para gerar o recibo.');
}

$result_dados = $clrhpagocor->sql_record($clrhpagocor->sql_query_atraso(null,"rh58_codigo, rh58_valor, rh58_data, rh01_regist, z01_nome, rh57_ano, rh57_mes ",""," rh58_codigo = ".$movimento));
$numrows_dados = $clrhpagocor->numrows;
if($numrows_dados == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Verifique o movimento informado. Nenhum registro para gerar recibo.');
}

db_sel_instit();

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$regist_ant = 0;
$total = 0;
$qtdlinhas = 0;
$altura = 0;

db_fieldsmemory($result_dados, 0);
$rh57_mes = db_formatar($rh57_mes,"s","0",2,"e",0);

$pdf->AddPage();
$altura = 40;

$pdf->Image("imagens/files/$logo",93,$altura,25);

$pdf->sety($altura + 40);
$pdf->setfont('Arial','B',15);
$pdf->Multicell(0,8,$nomeinst,0,"C",0); 

$pdf->sety($altura + 50);
$pdf->setfont('Arial','B',14);
$pdf->Multicell(0,8,"SECRETARIA DA FAZENDA - SeFaz",0,"C",0); 

$pdf->sety($altura + 65);
$pdf->setfont('Arial','B',12);
$pdf->Multicell(0,8,"RECIBO",0,"C",0); 

$pdf->sety($altura + 80);
$pdf->setfont('Arial','B',10);
$pdf->Multicell(0,8,"Recebi o saldo de salário referente ao mês: ".$rh57_mes." / ".$rh57_ano,0,"C",0);


$pdf->sety($altura + 120);
$pdf->setfont('Arial','B',10);
$pdf->Multicell(0,8,"Valor do salário: ".db_formatar($rh58_valor,"f"),0,"C",0);

$pdf->sety($altura + 200);
$pdf->setfont('Arial','B',8);
$pdf->cell(80,4,"",0,0,"R",0);
$pdf->Multicell(0,4,"$munic, ".db_subdata($rh58_data,"d")." de ".db_mes(db_subdata($rh58_data,"m"))." de ".db_subdata($rh58_data,"a"),0,"C",0);

$pdf->sety($altura + 215);
$pdf->setfont('Arial','B',8);
$pdf->cell(80,4,"",0,0,"R",0);
$pdf->Multicell(0,4,$z01_nome,0,"C",0);

$pdf->sety($altura + 219);
$pdf->setfont('Arial','B',8);
$pdf->cell(80,4,"",0,0,"R",0);
$pdf->Multicell(0,4,"MATRÍCULA: ".$rh01_regist,0,"C",0);

$pdf->sety($altura + 223);
$pdf->setfont('Arial','B',8);
$pdf->cell(80,4,"",0,0,"R",0);
$pdf->Multicell(0,4,"PROCESSAMENTO: ".$rh58_codigo,0,"C",0);

$pdf->Output();
?>