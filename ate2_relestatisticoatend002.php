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
//db_postmemory($HTTP_SERVER_VARS,2);

$sql = "select  atendimento.*,
          to_timestamp(at02_datafim||' '||at02_horafim,'yyyy-mm-dd hh24:mi')-to_timestamp(at02_dataini||' '||at02_horaini,'yyyy-mm-dd hh24:mi') as intervalo,
          extract (day from to_timestamp(at02_datafim||' '||at02_horafim,'yyyy-mm-dd hh24:mi')-to_timestamp(at02_dataini||' '||at02_horaini,'yyyy-mm-dd hh24:mi'))::integer * 24 * 60 as dia,
          extract (hour from to_timestamp(at02_datafim||' '||at02_horafim,'yyyy-mm-dd hh24:mi')-to_timestamp(at02_dataini||' '||at02_horaini,'yyyy-mm-dd hh24:mi'))::integer * 60 as hora,
          extract (minute from to_timestamp(at02_datafim||' '||at02_horafim,'yyyy-mm-dd hh24:mi')-to_timestamp(at02_dataini||' '||at02_horaini,'yyyy-mm-dd hh24:mi'))::integer as minuto,
          extract (day from to_timestamp(at02_datafim||' '||at02_horafim,'yyyy-mm-dd hh24:mi')-to_timestamp(at02_dataini||' '||at02_horaini,'yyyy-mm-dd hh24:mi'))::integer + 1 as totaldedias
  from atendimento where 1 = 1";

//die($sql);

if($cliente != ""){
	$sql .= " and at02_codcli = $cliente";
}

$dataini= $at02_dataini_ano."-".$at02_dataini_mes."-".$at02_dataini_dia;

if ($dataini != "--"){
	$sql .= " and at02_dataini >= '$dataini'";
}


$datafim = $at02_datafim_ano."-".$at02_datafim_mes."-".$at02_datafim_dia;

if ($datafim != "--"){
	$sql .= " and at02_datafim <= '$datafim'";
} 

$sql = "select 
			 at01_nomecli, 
			 at02_codcli,
			 minutos as total,
		     trunc(minutos/ 60::float8)::float8 as horas,
			 minutos - (trunc(minutos / 60::float8)::float8 * 60) as minutos,
 			 totaldedias
        from (
		select
			 at01_nomecli, 
			 at02_codcli,
			 sum(dia + hora + minuto)::float8 as minutos,
			 sum(totaldedias) as totaldedias 
			 from ($sql)  as x
             inner join clientes on at02_codcli = at01_codcli
             group by at01_nomecli , at02_codcli 
             order by at02_codcli) as y";


$result = pg_exec($sql);

//db_criatabela($result);exit;

//db_criatabela($result);exit;

if($result==false || pg_numrows($result)==0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem atendimentos cadastrados!');
   exit;
}
 

$pdf = new PDF(); // estancia a classe
$head1 = "RELATÓRIO ESTATISTICO DE ATENDIMENTOS";
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
	$head4 = "TÉCNICO : " .$tecnico;
    }else{
    $head4 = "ATENDIMENTOS DE TODOS OS TÉCNICOS";	
}
 
 $pdf->open(); // inicia a geração do documento
 $total_geral = 0; // criação de uma variável para somar o total de registros
 $pdf->settextcolor(0,0,0); // seta a cor do texto como preta
 $pdf->setfillcolor(220); // define a cor de preenchimento
 $pdf->setfont('Arial','B',9); // seta a fonte como arial, bold e tamanho 9
 

$ultatend = 0;
$tamanho = 4;
$numlinha = pg_numrows($result);
$ultatend = 0;


for($x=0; $x< $numlinha;$x++){
  db_fieldsmemory($result,$x);

  if (($pdf->gety() > ($pdf->h - 30)) || $x == 0 ){
    $pdf->addpage('L'); // adiciona uma pagina no modo paisagem
    $pdf->cell(80,6,"CLIENTE",1,0,"C",1);
    $pdf->cell(60,6,"ATENDIMENTO MAIS SOLICITADO",1,0,"C",1);
    $pdf->cell(45,6,"N. TECNICOS ENVOLVIDOS",1,0,"C",1); // cria as células para o cabeçalho
    $pdf->cell(35,6,"N. DIAS EM ATEND",1,0,"C",1);
    $pdf->cell(60,6,"N. DE HORAS DISPENDIDAS",1,0,"C",1);
    $pdf->Ln();
    $pdf->Ln();
  }

$tipo  = "select    at01_nomecli,
                    at01_codcli,
                    at02_codcli,
                    at02_codtipo,
                    at04_descr, 
                    count (at02_codtipo)as matend
         from       atendimento
                    inner join clientes on at01_codcli = at02_codcli
                    inner join tipoatend on at04_codtipo = at02_codtipo
         where      at02_codcli = $at02_codcli";

if ($dataini != "--"){
	$tipo .= " and at02_dataini >= '$dataini'";
}


if ($datafim != "--"){
	$tipo .= " and at02_datafim <= '$datafim'";
} 

$tipo .= " group by  at01_nomecli,
                     at01_codcli,
                     at02_codcli,
                     at02_codtipo,
                     at04_descr
           order by  count(at02_codtipo)desc";

$result2 = pg_exec($tipo);
db_fieldsmemory($result2,0);

//db_criatabela($result);exit;

$tec = "select       distinct at03_id_usuario, login
        from         atendimento
                     inner join tecnico  on at03_codatend = at02_codatend
                     inner join db_usuarios on id_usuario = at03_id_usuario
        where        at02_codcli = $at02_codcli ";


if ($dataini != "--"){
	$tec .= " and at02_dataini >= '$dataini'";
}


if ($datafim != "--"){
	$tec .= " and at02_datafim <= '$datafim'";
} 
	
$result3 = pg_exec($tec);
$tec = pg_numrows($result3);


    $pdf->cell(80,$tamanho,$at01_nomecli,1,0,"C",0);
    $pdf->cell(60,$tamanho,$at04_descr,1,0,"C",0);
    $pdf->cell(45,$tamanho,$tec,1,0,"C",0);
    $pdf->cell(35,$tamanho,$totaldedias,1,0,"C",0);
    $pdf->cell(60,$tamanho,"$horas hora(s) e $minutos minuto(s)",1,0,"C",0);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->cell(40,$tamanho,"Tecnicos Envolvidos:",1,0,"C",0); 
    
$nomestec = "";
$virgula = "";
        
for($y=0; $y< $tec; $y++){
  db_fieldsmemory($result3,$y);
  
  $nomestec .= $virgula.$login;
  $virgula = ",  ";
  
}
     $pdf->cell(145,$tamanho,$nomestec,1,1,"C",0);
     $pdf->Ln();
    
               
} 

    
$pdf->Output(); // saída do relatório direto para o browser

/*
	<?
	function difer_horas($hora1,$hora2){
	$entrada = $hora1;
	$saida = $hora2;
	$hora1 = explode(":",$entrada);
	$hora2 = explode(":",$saida);
	$acumulador1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
	$acumulador2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
	$resultado = $acumulador2 - $acumulador1;
	$hora_ponto = floor($resultado / 3600);
	$resultado = $resultado - ($hora_ponto * 3600);
	$min_ponto = floor($resultado / 60);
	$resultado = $resultado - ($min_ponto * 60);
	$secs_ponto = $resultado;
	return $hora_ponto.":".$min_ponto.":".$secs_ponto;
	
	}
	
	$dif = difer_horas("14:30:00","01:00:00");
	

	echo $dif;
?>

*/
	
?>