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
$pdf->Setleftmargin(0);
$pdf->SetFont($Letra,'B',11);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->Ln(3);
$pdf->SetFont($Letra,'',9);
if($ag40_tipocons=="pa"){
  $marcado = "X";
  $marcado1 = "  ";
}elseif($ag40_tipocons=="cm"){
  $marcado1 = "X";
  $marcado = "  ";
}  
$pdf->MultiCell(0,6,'PRONTO ATENDIMENTO ('.@$marcado.')',0,"J",0,30);
$pdf->MultiCell(0,6,'CONSULTÓRIO MÉDICO ('.@$marcado1.')',0,"J",0,30);
$pdf->Ln(3);
$marcado = "";
$marcado1 = "";
if($ag40_tipoform=="a")
  $marcado = "X";
elseif($ag40_tipoform=="c")
  $marcado1 = "X";
$pdf->MultiCell(0,6,'ATESTADO ('.@$marcado.')',0,"J",0,30);
$pdf->MultiCell(0,6,'COMPROVANTE ('.@$marcado1.')',0,"J",0,30);
$pdf->Ln(3);
if(isset($ag40_altcid) && $ag40_altcid == "t") {
  $result = pg_exec("select ag40_codcid from atendmedcid where ag40_codigo = $ag40_codigo");
  if(pg_numrows($result) != 0){
    $pdf->MultiCell(0,6,'CID:',0,"J",0,30);
    for($i = 0; $i < pg_numrows($result); $i++){  
      db_fieldsmemory($result,$i);
      $result = pg_exec("select descr from cid10 where codcid = '$ag40_codcid'");
      $pdf->MultiCell(0,6,pg_result($result,0,0),0,"J",0,30);
    }
  }  
}
if(db_getsession("w03_codigo") != ""){
  $result = pg_exec("select w03_nome as nome from depen where w03_codigo = '".str_pad(trim(db_getsession("w03_codigo")),6," ",STR_PAD_LEFT)."'");
}else{
  $result = pg_exec("select c.j01_nome as  nome
                     from cgipa c
    	 	     inner join cadastro cad
						 on cad.w01_numcgi = c.j01_numero
						 where cad.w01_regist = '".str_pad(trim(db_getsession("w01_regist")),6," ",STR_PAD_LEFT)."'");
}
if(pg_numrows($result) > 0)
  db_fieldsmemory($result,0);
  
$result = pg_exec("select ag40_horainiate,ag40_horafimate from atendmed where ag40_codigo = ".db_getsession("COD_atendimento"));
if(pg_numrows($result) > 0)
  db_fieldsmemory($result,0);
$pdf->MultiCell(0,6,'Sr. EMPREGADOR,',0,"J",0,30);
$pdf->MultiCell(0,6,'Comunicamos que o Sr(a) '.$nome.',',0,"J",0,30);
if((int)db_parse_int(substr($ag40_horainiate,0,2)) >= 12)
  $periodo = "tarde";
else
  $periodo = "manhã";	   
if($ag40_taconsulta == "1")
  $AuX = "Consulta";
if($ag40_tacurativo == "1")
  $AuX = "Curativo"; 
if($ag40_tarevisao == "1")
  $AuX = "Revisão"; 
$pdf->MultiCell(0,6,'compareceu no dia de hoje, no período da '.$periodo.',',0,"J",0,30);
$pdf->MultiCell(0,6,'das '.$ag40_horainiate.' às '.$ag40_horafimate.' para '.$AuX.'.',0,"J",0,30);
function extenso($num) {
  $u = Array("zero","um","dois","três","quatro","cinco","seis","sete","oito","nove","dez","onze","doze","treze","quatorze","qunize","dezesseis","dezessete","dezoito","dezenove");
  $d = Array(2 => "vinte",3 => "trinta",4 => "quarenta",5 => "cinquenta",6 => "sessenta",7 => "setenta",8 => "oitenta",9 => "noventa");
  $c = Array(1 => "cento",2 => "duzentos",3 => "trezentos",4 => "quatrocentos",5 => "quinhentos",6 => "seiscentos",7 => "setecentos",8 => "oitocentos",9 => "novecentos");
  if($num > 999 || $num < 0)
    return $num;
  else if($num >= 0 && $num <= 19)
    return $u[$num];
  else if($num > 19 && $num < 100)
    return $num%10==0?$d[$num[0]]:$d[$num[0]]." e ".$u[$num[1]];
  else {
    if($num == 100)
      return "cem";
    else
      return $num%100==0?$c[$num[0]]:$c[$num[0]]." e ".$d[$num[1]]." e ".$u[$num[2]];
  }
}
if($ag40_tipoform == "a") {
  $pdf->MultiCell(0,6,'necessitando '.$ag40_diasatestado.'('.extenso($ag40_diasatestado).') dias de repouso à partir de '.$dataat_dia.'/'.$dataat_mes.'/'.$dataat_ano,0,"J",0,30);
} else 
  $pdf->MultiCell(0,6,'',0,"J",0,30);
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