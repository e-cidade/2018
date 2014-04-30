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

require('fpdf151/pdfipa.php');
$result = pg_exec("select *,to_char(ag40_dataatestado,'DD') as dataat_dia,to_char(ag40_dataatestado,'MM') as dataat_mes,to_char(ag40_dataatestado,'YYYY') as dataat_ano,to_char(ag40_data,'DD') as ag40_data_dia,to_char(ag40_data,'MM') as ag40_data_mes,to_char(ag40_data,'YYYY') as ag40_data_ano from atendmed where ag40_codigo = $ag40_codigo");
if(pg_numrows($result) > 0)
db_fieldsmemory($result,0);
$pdf = new PDF(); // abre a classe
$Letra = 'arial';
$pdf->SetFont($Letra,'B',11);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->Ln(3);
$pdf->SetFont($Letra,'',9);
if(db_getsession("w03_codigo") != "")
  $result = pg_exec("select w03_nome as nome from depen where w03_codigo = '".str_pad(trim(db_getsession("w03_codigo")),6," ",STR_PAD_LEFT)."'");
else {
  $result = pg_exec("select c.j01_nome as  nome
                     from cgipa c
  			 inner join cadastro cad
				 on cad.w01_numcgi = c.j01_numero
					 where cad.w01_regist = '".str_pad(trim(db_getsession("w01_regist")),6," ",STR_PAD_LEFT)."'");
}
if(pg_numrows($result) > 0)
  db_fieldsmemory($result,0);
$pdf->MultiCell(0,6,'Nome:'.@$nome,0,"J",0,30);
if(trim($ag40_recint) != "") { 
$pdf->MultiCell(0,6,'Uso Interno:',0,"J",0,30);
$pdf->MultiCell(0,6,$ag40_recint,0,"J",0,30);
//str_replace("\n","<br>",$ag40_recint) 
}
$pdf->Ln(10);
if(trim($ag40_recext)) { 
$pdf->MultiCell(0,6,'Uso Externo:',0,"J",0,30);
$pdf->MultiCell(0,6,$ag40_recext,0,"J",0,30);
//str_replace("\n","<br>",$ag40_recext) 
}
$pdf->Ln(50);
$result = pg_exec("select aa01_nome,aa01_creme from medicos where aa01_codlog = ".db_getsession("DB_id_usuario"));
$cremers = @pg_result($result,0,1);
$nomemed = @pg_result($result,0,0);
$mes = array(1 => "janeiro",2 => "fevereiro",3 => "março",4 => "abril",5 => "maio",6 => "junho",7 => "julho",8 => "agosto",9 => "setembro",10 => "outubro",11 => "novembro",12 => "dezembro");
$pdf->Text(20,170,'Campo Bom, '.date("d").' de '.$mes[date("n")].' de '.date("Y"));
$pdf->Text(20,175,$nomemed);
$pdf->Text(20,180,'Cremers: '.$cremers);
$pdf->Ln(5);
$pdf->output();
?>