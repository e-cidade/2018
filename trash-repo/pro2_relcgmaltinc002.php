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
include("libs/db_sql.php");
include("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oGet  = db_utils::postMemory($_GET,0);

$clrotulo = new rotulocampo;
$clrotulo->label('');

$sWhere        = "";
$sWhereCadast  = "";
$sWhereDataAlt = "";
$sAnd          = "";
$sBetween      = "";
$sHeaderTipo   = "Tipo modificação: Todos";
$sHeaderUsuSel = "";
$sHeaderData   = "";

if($oGet->tipo != 'T'){
	
	switch ($oGet->tipo) {
		case "I" :
			   $sHeaderTipo = "Tipo modificação: Inclusões";
			   break;
		case "A" :
			   $sHeaderTipo = "Tipo modificação: Alterações";
			   break;
	}
	
	$sWhere = " {$sAnd} tipo_alt = '{$oGet->tipo}' ";
	$sAnd   = " and ";
}

if(trim($oGet->ususel) != ""){
  
  $sHeaderUsuSel = "Usuários Selecionados: ( ".$oGet->ususel." )";
  $sWhere       .= "{$sAnd} usuario in ($oGet->ususel) ";
  $sAnd          = " and ";
}

if (isset($oGet->data1) && $oGet->data1 != "--") {
	
	if (isset($oGet->data2) && $oGet->data2 != "--") {
		$sHeaderData   = "Data: $oGet->data1 á $oGet->data2";
	  $sBetween      = "between '{$oGet->data1}' and '{$oGet->data2}' ";
	  $sWhereCadast  = "where z01_cadast {$sBetween}";	
	  $sWhereDataAlt = "where z05_data_alt {$sBetween}";
	}
}

if (isset($sWhere) && $sWhere != "") {
	$sWhere = " where {$sWhere} ";
}

$sql  = "  select u.login,                                                                                            ";
$sql .= "         x.*                                                                                                 ";
$sql .= "    from (                                                                                                   ";
$sql .= "           select 'I'::char(1)    as tipo_alt,                                                               ";
$sql .= "                  z01_login       as usuario,                                                                ";
$sql .= "                  z01_cadast      as data,                                                                   ";
$sql .= "                  z01_numcgm      as numcgm,                                                                 ";
$sql .= "                  ''::varchar(14) as cgccpf_ant,                                                             ";
$sql .= "                  z01_cgccpf      as cgccpf,                                                                 ";
$sql .= "                  ''::varchar(40) as nome_ant,                                                               ";
$sql .= "                  z01_nome        as nome,                                                                   ";
$sql .= "                  ''::varchar(80) as ender_ant,                                                              ";
$sql .= "                  z01_ender       as ender,                                                                  ";
$sql .= "                  0::integer      as numero_ant,                                                             ";
$sql .= "                  z01_numero      as numero,                                                                 ";
$sql .= "                  ''::varchar(20) as compl_ant,                                                              ";
$sql .= "                  z01_compl       as compl,                                                                  ";
$sql .= "                  ''::varchar(40) as munic_ant,                                                              ";
$sql .= "                  z01_munic       as munic,                                                                  ";
$sql .= "                  ''::varchar(2)  as uf_ant,                                                                 ";
$sql .= "                  z01_uf          as uf,                                                                     ";
$sql .= "                  ''::varchar(8)  as cep_ant,                                                                ";
$sql .= "                  z01_cep         as cep                                                                     ";
$sql .= "             from cgm                                                                                        ";
$sql .= "                  {$sWhereCadast}                                                                            ";
$sql .= "                                                                                                             ";
$sql .= "            union all                                                                                        ";
$sql .= "                                                                                                             ";
$sql .= "           select z05_tipo_alt    as tipo_alt,                                                               ";
$sql .= "                  z05_login_alt   as usuario,                                                                ";
$sql .= "                  z05_data_alt    as data,                                                                   ";
$sql .= "                  z05_numcgm      as numcgm,                                                                 ";
$sql .= "                  z05_cgccpf      as cgccpf_ant,                                                             ";
$sql .= "                  z01_cgccpf      as cgccpf,                                                                 ";
$sql .= "                  z05_nome        as nome_ant,                                                               ";
$sql .= "                  z01_nome        as nome,                                                                   ";
$sql .= "                  z05_ender       as ender_ant,                                                              ";
$sql .= "                  z01_ender       as ender,                                                                  ";
$sql .= "                  z05_numero      as numero_ant,                                                             ";
$sql .= "                  z01_numero      as numero,                                                                 ";
$sql .= "                  z05_compl       as compl_ant,                                                              ";
$sql .= "                  z01_compl       as compl,                                                                  ";
$sql .= "                  z05_munic       as munic_ant,                                                              ";
$sql .= "                  z01_munic       as munic,                                                                  ";
$sql .= "                  z05_uf          as uf_ant,                                                                 ";
$sql .= "                  z01_uf          as uf,                                                                     ";
$sql .= "                  z05_cep         as cep_ant,                                                                ";
$sql .= "                  z01_cep         as cep                                                                     ";
$sql .= "             from cgmalt                                                                                     ";
$sql .= "                  inner join cgm on z01_numcgm = z05_numcgm                                                  ";
$sql .= "                  {$sWhereDataAlt}                                                                           ";
$sql .= "         ) as x                                                                                              ";
$sql .= "           left join db_usuarios u on u.id_usuario = x.usuario                                               ";
$sql .= "      {$sWhere}                                                                                              ";
$sql .= "order by data, numcgm, tipo_alt desc                                                                         ";

//die($sql);

$result = pg_query($sql);
$linhas = pg_num_rows($result);

if($linhas > 0){
  $head2 = "RELATÓRIO CGM ALTERAÇÃO/INCLUSÃO";
  $head4 = $sHeaderTipo;
  $head5 = $sHeaderData;
  $head6 = $sHeaderUsuSel;
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt = 4;
  $total = 0;
  $p=0;


	for($x = 0; $x < $linhas;$x++){
		
	db_fieldsmemory($result,$x);

    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    	
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      
      //linha 1
      $pdf->cell(45,$alt,"Tipo da operação",1,0,"C",1);
      $pdf->cell(65,$alt,"Data da operação",1,0,"L",1);
      $pdf->cell(120,$alt,"Usuário",1,0,"L",1);
      $pdf->cell(50,$alt,"CGM",1,1,"L",1);
      
      // linha 2
      $pdf->cell(45,$alt,"CPF/CNPJ",1,0,"L",1);
      $pdf->cell(65,$alt,"Nome",1,0,"L",1);
      $pdf->cell(64,$alt,"Endereço",1,0,"L",1);
      $pdf->cell(8,$alt,"Num.",1,0,"L",1);
      $pdf->cell(30,$alt,"Compl.",1,0,"L",1);
      $pdf->cell(45,$alt,"Município",1,0,"L",1);
      $pdf->cell(8,$alt,"UF",1,0,"L",1);
      $pdf->cell(15,$alt,"CEP",1,1,"L",1);
     
      $troca = 0;
    }
   
    if($x % 2 == 0){
	    $corfundo = 250;
	  } else {
	    $corfundo = 230;	
	  }
	  
	  $pdf->SetFillColor($corfundo);

    if($tipo_alt=='A'){
   		$pdf->cell(45,$alt,"Alteração",0,0,"C",1);
    }
    
    if($tipo_alt=='I'){
   		$pdf->cell(45,$alt,"Inclusão",0,0,"C",1);
    }
   
	  $pdf->cell(65,$alt,db_formatar($data,'d'),0,0,"L",1);
   	$pdf->cell(120,$alt,$login,0,0,"L",1);
  	$pdf->cell(50,$alt,$numcgm,0,1,"L",1);
  	
   	// linha 2
   	$pdf->cell(15,$alt,"Atual",0,0,"L",1);
   	$pdf->cell(30,$alt,db_formatar($cgccpf,(strlen($cgccpf)==11?'cpf':'cnpj')),0,0,"L",1);
    $pdf->cell(65,$alt,$nome,0,0,"L",1);
    $pdf->cell(64,$alt,$ender,0,0,"L",1);
    $pdf->cell(8,$alt,$numero,0,0,"L",1);
    $pdf->cell(30,$alt,$compl,0,0,"L",1);
    $pdf->cell(45,$alt,$munic,0,0,"L",1);
    $pdf->cell(8,$alt,$uf,0,0,"L",1);
    $pdf->cell(15,$alt,db_formatar($cep,"cep"),0,1,"L",1);
    
	  //linha 3
		if ($tipo_alt=='A'){
			
			$pdf->cell(15,$alt,"Anterior",0,0,"L",1);
			
			   /*
	          operador ternario
	          (<condicao>?<corpo>:<senao>) 
	       */
			
			  $pdf->cell(30,$alt,db_formatar($cgccpf_ant,(strlen($cgccpf_ant)==11?'cpf':'cnpj')),0,0,"L",1);
		    $pdf->cell(65,$alt,$nome_ant,0,0,"L",1);
		    $pdf->cell(64,$alt,$ender_ant,0,0,"L",1);
		    $pdf->cell(8 ,$alt,$numero_ant,0,0,"L",1);
		    $pdf->cell(30,$alt,$compl_ant,0,0,"L",1);
		    $pdf->cell(45,$alt,$munic_ant,0,0,"L",1);
		    $pdf->cell(8 ,$alt,$uf_ant,0,0,"L",1);
		    $pdf->cell(15,$alt,db_formatar($cep_ant,"cep"),0,1,"L",1);
		}
		
		$total = $total+1;
		
	}
	
   $pdf->ln(5);	
   $pdf->cell(280 ,8,"TOTAL DE REGISTROS ENCONTRADO: ".$total,1,1,"C",1);
   $pdf->Output();
} else {
	db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}
?>