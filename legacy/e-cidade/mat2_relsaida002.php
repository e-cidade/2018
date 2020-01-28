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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("classes/db_matestoque_classe.php");
include ("classes/db_matestoqueitem_classe.php");
include ("classes/db_db_almox_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$clmatestoque     = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$cldb_almox       = new cl_db_almox;

$clrotulo = new rotulocampo;
$clrotulo->label('m60_descr');
$clrotulo->label('descrdepto');

if (isset($listaorgao) && $listaorgao==""){
$ordem_atend = "m40_login,";
}else{
$ordem_atend = "m40_login";
}

if (isset($listaorgao) && $listaorgao==""){
  if (isset($quebra)&&$quebra=="S"){
	    $ordem_atend .= 'm40_depto, m60_descr';
    }else if ($ordem == 'a') {
	    $ordem_atend .= 'm41_codmatmater';
   } else	if ($ordem == 'b') {
    	$ordem_atend .= 'm40_depto, m60_descr';
   } else	if ($ordem == 'c') {
    	$ordem_atend .= 'm60_descr';
   } else  if ($ordem == 'd') {
     $ordem_atend .= "m41_codmatmater,m40_data desc";
  }
}else{
$quebra="N";
$ordem ="a";
}

$txt_where = "";
$dbwhere = "";
$info = "";
$txt_where_atend = "1=1";

if ($listaorgao!= "") {

 $txt_where_atend  .= " and  o40_orgao in ($listaorgao) and o40_anousu=".db_getsession('DB_anousu')." and o40_instit=".db_getsession('DB_instit');
}

if ($listadepart != "") {
	if (isset ($verdepart) and $verdepart == "com") {
		$txt_where_atend  .= " and m40_depto    in ($listadepart)";
	} else {
		$txt_where_atend  .= " and m40_depto    not in ($listadepart)";
	}
} else {

  /*$txt_where_atend .= " and exists( select db_depusu.coddepto
                                      from db_depusu
                                     where db_depusu.coddepto   = db_depart.coddepto 
                                       and db_depusu.id_usuario = ".db_getsession('DB_instit')." limit 1 )";
    */                                   	
}

if ($listamat != "") {
	if (isset ($vermat) and $vermat == "com") {
		$txt_where_atend  .= " and m41_codmatmater in ($listamat)";
	} else {
		$txt_where_atend  .= " and m41_codmatmater not in ($listamat)";
	}
}
if ($listausu != "") {
	if (isset ($verusu) and $verusu == "com") {
		$txt_where_atend   .= " and m40_login in ($listausu)";
	} else {
		$txt_where_atend   .= " and m40_login not in ($listausu)";
	}
}
$sDataIni = implode('-',array_reverse(explode('/',$dataini)));
$sDataFin = implode('-',array_reverse(explode('/',$datafin)));

if (( trim($dataini) != "--") && ( trim($datafin) != "--")) {
 	$txt_where_atend .= " and m40_data between '$sDataIni' and '$sDataFin' ";
  $info  = "De ".$dataini." até ".$datafin;
} else if (trim($dataini) != "--") {
 	$txt_where_atend .= " and m40_data >= '$sDataIni' ";
  $info  = "Apartir de ".$dataini;
} else if (trim($datafin) != "--") { 
 	$txt_where_atend .= " and m40_data <= '$sDataFin' ";
  $info = "Até ".$datafin;
}

if (isset($listausu)&&trim($listausu)!=""&&isset($quebra_usu)&&$quebra_usu=="N"){
     $sql       = "select id_usuario, nome 
                   from db_usuarios 
                   where id_usuario in ($listausu)";
     $resultado = @pg_query($sql);
     $numrows   = 0;
     $numrows   = @pg_numrows($resultado);

     if ($numrows > 0){
          $head6 = "Usuários: ";
     }

     for($i = 0; $i < $numrows; $i++){
          db_fieldsmemory($resultado, $i);
          if ($i > 0){
               $head6 .= "                ";
          }
          $head6 .= $id_usuario." - ".$nome."\n";
     }     
}

$info_listar_serv = "";

if ($listar_serv == "M") {

  $txt_where           .= " and (pc01_servico is false or pc01_servico is null) ";
  $info_listar_serv    .= " LISTAR: SOMENTE MATERIAIS";
  
} else if ($listar_serv == "S") {

  $txt_where           .= " and pc01_servico is true ";
  $info_listar_serv    .= " LISTAR: SOMENTE SERVIÇOS";
  
} else {
  $info_listar_serv = " LISTAR: TODOS";
}
if (isset($centrocusto) && $centrocusto != "") {
  
   $head8 = "Centro de custo : {$centrocusto} - $centrocustodescr";
   $txt_where_atend .= " and cc12_custocriteriorateio = {$centrocusto}";
   
}
$head3 = "Relatório de Saída de Material por Departamento";
$head4 = "Atendimento de Requisição de Material";
$head5 = "$info";
$head7 = "$info_listar_serv";

if($listar_serv == "T") {
	$sql = "select distinct 
	               m41_codigo,
	               m41_codmatmater,
	               m60_descr,
							   m40_depto,
							   descrdepto,
							   m40_data,
							   m40_codigo,
                 m43_quantatend as m41_quant,
							   m70_codigo,
							   m40_login,
							   nome,
                 m45_data,
							   m46_quantdev
            from matrequi
           inner join db_usuarios             on db_usuarios.id_usuario                         = matrequi.m40_login
		       inner join db_depart               on db_depart.coddepto                             = matrequi.m40_depto
                                             and db_depart.instit = ".db_getsession('DB_instit')."
           left  join db_departorg            on db_departorg.db01_coddepto                     = db_depart.coddepto
         	 left  join orcorgao                on orcorgao.o40_orgao                             = db_departorg.db01_orgao
			     inner join matrequiitem            on matrequiitem.m41_codmatrequi                   = matrequi.m40_codigo
           inner join atendrequiitem          on atendrequiitem.m43_codmatrequiitem             = matrequiitem.m41_codigo
	         inner join matmater                on matmater.m60_codmater                          = matrequiitem.m41_codmatmater
           left  join matestoquedev           on matestoquedev.m45_codmatrequi                  = matrequi.m40_codigo
           left  join matestoquedevitem       on matestoquedevitem.m46_codmatrequiitem          = matrequiitem.m41_codigo
			     left  join matestoque              on matestoque.m70_codmatmater                     = matmater.m60_codmater 
					                                   and matestoque.m70_coddepto                        = matrequi.m40_depto						 
           left  join transmater              on transmater.m63_codmatmater                     = matmater.m60_codmater 
           left  join pcmater                 on pcmater.pc01_codmater                          = transmater.m63_codpcmater 			  
							                               and matestoque.m70_coddepto                        = matrequi.m40_depto
					 left  join  	matestoqueinimeiari   on m49_codatendrequiitem                          = m43_codigo
					 left  join  	custoapropria         on m49_codmatestoqueinimei                        = cc12_matestoqueinimei
           where {$txt_where_atend}
	         order by {$ordem_atend}";
} else {
	$sql = "select distinct 
	               m41_codigo,
	               m41_codmatmater,
	               m60_descr,
							   m40_depto,
							   descrdepto,
							   m40_data,
							   m40_codigo,
                 m43_quantatend as m41_quant,
							   m70_codigo,
							   m40_login,
							   nome,
                 m45_data,
							   m46_quantdev
            from matrequi
           inner join db_usuarios             on db_usuarios.id_usuario                         = matrequi.m40_login
		       inner join db_depart               on db_depart.coddepto                             = matrequi.m40_depto
                                             and db_depart.instit = ".db_getsession('DB_instit')."
           left  join db_departorg            on db_departorg.db01_coddepto                     = db_depart.coddepto
           left  join orcorgao                on orcorgao.o40_orgao                             = db_departorg.db01_orgao
			     inner join matrequiitem            on matrequiitem.m41_codmatrequi                   = matrequi.m40_codigo
           inner join atendrequiitem          on atendrequiitem.m43_codmatrequiitem             = matrequiitem.m41_codigo
	         inner join matmater                on matmater.m60_codmater                          = matrequiitem.m41_codmatmater
           left  join matestoquedev           on matestoquedev.m45_codmatrequi                  = matrequi.m40_codigo
           left  join matestoquedevitem       on matestoquedevitem.m46_codmatrequiitem          = matrequiitem.m41_codigo
           left  join matestoque              on matestoque.m70_codmatmater                     = matmater.m60_codmater 
						                                 and matestoque.m70_coddepto                        = matrequi.m40_depto
					 left  join transmater              on transmater.m63_codmatmater                     = matmater.m60_codmater 
				   left  join pcmater                 on pcmater.pc01_codmater                          = transmater.m63_codpcmater 
						 	                               and matestoque.m70_coddepto                        = matrequi.m40_depto
					 left  join matestoqueinimeiari     on m49_codatendrequiitem                          = m43_codigo
					 left  join custoapropria           on m49_codmatestoqueinimei                        = cc12_matestoqueinimei 	
           where {$txt_where_atend} {$txt_where}
					 order by {$ordem_atend}";
}

//$clmatmater ="";
//$result =$clmatmater->sql_record($sql);


$res_saida_atend = @pg_query($sql);
$numrows_atend   = @pg_numrows($res_saida_atend);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca =  1;
$tam   = 75;
$alt   =  4;
$borda =  0;
$total =  0;
$p     =  0;
$valor_depto = 0;
$total_depto = 0;
$quant_depto = 0;
$valor_usu   = 0;
$total_usu   = 0;
$quant_usu   = 0;
$q_est       = 0;
$v_est       = 0;
$depto_ant   = "";
$usua_ant    = "";
$imp=0;

// ATENDIMENTO
for ($x = 0; $x < $numrows_atend; $x ++) {
	db_fieldsmemory($res_saida_atend, $x);
	if (isset($quebra)&&$quebra=="S"){
		if($depto_ant != $m40_depto){
			if($depto_ant!=""){
				$pdf->setfont('arial', 'b', 8);
				$comp=255;
				$bord="T";			
				$pdf->cell($comp, 6, 'TOTAL DE REGISTROS DO DEPART.:  '.$total_depto, $bord, 0, "L", 0);
				$pdf->cell(15, 6, $quant_depto, $bord, 0, "C", 0);			
				$pdf->cell(10, 6, db_formatar($valor_depto,"f"), $bord, 1, "C", 0);			
				$total_depto = 0;
				$quant_depto = 0;
				$valor_depto = 0;
				$imp=1;

				$pdf->AddPage("L");
			}
			$depto_ant=$m40_depto;			
		}
	}	

  if(isset($quebra_usu)&&$quebra_usu=="S"&&strlen($listausu)>0){
		  if($usua_ant!=$m40_login){
			    if($usua_ant!=""){
		          $pdf->setfont('arial', 'b', 8);
				      $comp=255;
				      $bord="T";			
				      $pdf->cell($comp, 6, 'TOTAL DE REGISTROS DO USUÁRIO:  '.$total_usu, $bord, 0, "L", 0);
				      $pdf->cell(15, 6, $quant_usu, $bord, 0, "C", 0);			
				      $pdf->cell(10, 6, db_formatar($valor_usu,"f"), $bord, 1, "C", 0);			
				      $total_usu = 0;
				      $quant_usu = 0;
				      $valor_usu = 0;
				      $imp = 1;

				      $pdf->AddPage("L");
			    }
			    $usua_ant=$m40_login;			
		  }
  }

	if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $imp==1) {
		if ($imp==0) {
		     $pdf->addpage('L');
		}

    if(isset($quebra_usu)&&$quebra_usu=="S"&&strlen($listausu)>0){
		    $pdf->setfont('arial', 'b', 8);
        $pdf->cell(100, ($alt+2), $m40_login." - ".$nome, 0, 1, "L", 0);
    }

		$pdf->setfont('arial', 'b', 7);
		$pdf->cell(10, $alt, 'Material', 1, 0, "C", 1);
		$pdf->cell($tam, $alt, $RLm60_descr, 1, 0, "C", 1);
		$pdf->cell(10, $alt, 'Depart.', 1, 0, "C", 1);
		$pdf->cell(50, $alt, $RLdescrdepto, 1, 0, "C", 1);
		$pdf->cell(50, $alt, "Tipo de operação", 1, 0, "C", 1);
		$pdf->cell(10, $alt, 'Req.', 1, 0, "C", 1);
		$pdf->cell(15, $alt, 'Data', 1, 0, "C", 1);
		$pdf->cell(15, $alt, 'Valor Unit.', 1, 0, "C", 1);
		$pdf->cell(20, $alt, 'Quantidade', 1, 0, "C", 1);
		$pdf->cell(15, $alt, 'Valor Total', 1, 1, "C", 1);
		$troca = 0;
		$imp=0;
		$p=0;
	}	

    $valor_unitario = 0;
	$valor_total    = 0;

        if (isset($m70_codigo)&&trim($m70_codigo)!=""){
             $res_matestoqueitem = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query_file(null,"m71_valor,m71_quant","m71_codlanc desc","m71_codmatestoque = $m70_codigo"));
             if ($clmatestoqueitem->numrows > 0){
                  db_fieldsmemory($res_matestoqueitem,0);
   	     }

             if ($m71_valor == 0 || $m71_quant == 0){
	          continue;
	     }

 	     $valor_unitario = $m71_valor/$m71_quant;
	     $valor_total    = $valor_unitario * $m41_quant;
	} else {
       $dbwhere = "where ";
       if (isset($listamat) && trim($listamat)!=""){
            $dbwhere .= "m70_codmatmater in ($listamat)";
       } else {
            $dbwhere .= "m70_codmatmater = $m41_codmatmater";
       }
       $sql_matestoque = "select distinct m70_codigo, m70_codmatmater, m71_valor, m71_quant
                          from matestoque
                               inner join db_almox         on db_almox.m91_depto                  = matestoque.m70_coddepto 
                               inner join matestoqueitem   on matestoqueitem.m71_codmatestoque    = matestoque.m70_codigo 
                               inner join matestoqueinimei on matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc
                               inner join matestoqueini    on matestoqueinimei.m82_matestoqueini  = matestoqueini.m80_codigo  
                               ".$dbwhere." and m80_codtipo = 17 order by m70_codmatmater";


       $res_matestoque = $clmatestoque->sql_record($sql_matestoque);

//       db_criatabela($res_matestoque);
       if ($clmatestoque->numrows > 0){
            db_fieldsmemory($res_matestoque,0);

            if($m41_quant==0){
                $valor_unitario = 0;
                $valor_total    = 0;
            } else {
                if($m71_quant>0){
    	              $valor_unitario = $m71_valor/$m71_quant;
	                  $valor_total    = $valor_unitario*$m41_quant;
                } else {
                    $valor_unitario = 0;
                    $valor_total    = 0;
                }
            }    
       }
  }

	$pdf->setfont('arial', '', 6);
	$pdf->cell(10, $alt, $m41_codmatmater, $borda, 0, "C", $p);
	$pdf->cell($tam, $alt, substr($m60_descr,0,45), $borda, 0, "L", $p);
	$pdf->cell(10, $alt, $m40_depto, $borda, 0, "C", $p);
	$pdf->cell(50, $alt, substr($descrdepto,0,27), $borda, 0, "L", $p);
	$pdf->cell(50, $alt, "ATENDIMENTO DE REQUISIÇÃO", $borda, 0, "L", $p);
	$pdf->cell(10, $alt, $m40_codigo, $borda, 0, "C", $p);
	$pdf->cell(15, $alt, db_formatar($m40_data,"d"), $borda, 0, "C", $p);
	$pdf->cell(15, $alt, db_formatar($valor_unitario,"f"), $borda, 0, "C", $p);
	$pdf->cell(20, $alt, $m41_quant, $borda, 0, "C", $p);
	$pdf->cell(15, $alt, db_formatar($valor_total,"f"), $borda, 1, "C", $p);
	$total++;

  if (isset($m46_quantdev) && trim(@$m46_quantdev) != ""){

    // Verifica se nao precisa pular de página
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $imp==1) {
      if ($imp==0) {
           $pdf->addpage('L');
      }

      if(isset($quebra_usu)&&$quebra_usu=="S"&&strlen($listausu)>0){
          $pdf->setfont('arial', 'b', 8);
          $pdf->cell(100, ($alt+2), $m40_login." - ".$nome, 0, 1, "L", 0);
      }

      $pdf->setfont('arial', 'b', 7);
      $pdf->cell(10, $alt, 'Material', 1, 0, "C", 1);
      $pdf->cell($tam, $alt, $RLm60_descr, 1, 0, "C", 1);
      $pdf->cell(10, $alt, 'Depart.', 1, 0, "C", 1);
      $pdf->cell(50, $alt, $RLdescrdepto, 1, 0, "C", 1);
      $pdf->cell(50, $alt, "Tipo de operação", 1, 0, "C", 1);
      $pdf->cell(10, $alt, 'Req.', 1, 0, "C", 1);
      $pdf->cell(15, $alt, 'Data', 1, 0, "C", 1);
      $pdf->cell(15, $alt, 'Valor Unit.', 1, 0, "C", 1);
      $pdf->cell(20, $alt, 'Quantidade', 1, 0, "C", 1);
      $pdf->cell(15, $alt, 'Valor Total', 1, 1, "C", 1);
      $troca = 0;
      $imp=0;
      $p=0;
    }	

    // para forcar a deducao na hora de calcular a qtd e valor das saidas
    $m46_quantdev *= -1; 

	  $pdf->cell(10, $alt, $m41_codmatmater, $borda, 0, "C", $p);
    $pdf->cell($tam, $alt, substr($m60_descr,0,45), $borda, 0, "L", $p);  
    $pdf->cell(10, $alt, $m40_depto, $borda, 0, "C", $p);
    $pdf->cell(10, $alt, $m40_codigo, $borda, 0, "C", $p);
	  $pdf->cell(50, $alt, "DEVOLUÇÃO", $borda, 0, "L", $p);
	  $pdf->cell(50, $alt, $descrdepto, $borda, 0, "L", $p);
	  $pdf->cell(15, $alt, db_formatar($m45_data,"d"), $borda, 0, "C", $p);
	  $pdf->cell(15, $alt, db_formatar($valor_unitario,"f"), $borda, 0, "C", $p);
	  $pdf->cell(20, $alt, $m46_quantdev, $borda, 0, "C", $p);
	  $pdf->cell(15, $alt, db_formatar(($m46_quantdev*$valor_unitario),"f"), $borda, 1, "C", $p);

    $q_est += $m46_quantdev;
    $v_est += $m46_quantdev*$valor_unitario;
  }

	$q_est       += $m41_quant;
	$v_est       += $valor_total;
	$total_depto++;
	$quant_depto += $m41_quant;
	$valor_depto += $valor_total;

  $total_usu++;
  $quant_usu += $m41_quant;
  $valor_usu += $valor_total;

	if ($p == 0){
	     $p = 1;
	} else {
	     $p = 0;
	}
		
}

if ($numrows_atend == 0) {
    // echo $numrows_atend . " " . $numrows_manual; exit;
     if ($numrows_manual > 0) {
          // NDA
     } else {
          db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
     }	  
}

if (isset($quebra)&&$quebra=="S"){
     $pdf->setfont('arial', 'b', 8);
     $comp=255;
     $bord="T";			
     $pdf->cell($comp, 6, 'TOTAL DE REGISTROS DO DEPART.:  '.$total_depto, $bord, 0, "L", 0);
     $pdf->cell(15, 6, $quant_depto, $bord, 0, "C", 0);			
     $pdf->cell(10, 6, db_formatar($valor_depto,"f"), $bord, 1, "C", 0);			
}

if(isset($quebra_usu)&&$quebra_usu=="S"&&strlen($listausu)>0){
    $pdf->setfont('arial', 'b', 8);
    $comp=255;
    $bord="T";			
    $pdf->cell($comp, 6, 'TOTAL DE REGISTROS DO USUÁRIO:  '.$total_usu, $bord, 0, "L", 0);
    $pdf->cell(15, 6, $quant_usu, $bord, 0, "C", 0);			
    $pdf->cell(10, 6, db_formatar($valor_usu,"f"), $bord, 1, "C", 0);			
}

if (isset($quebra)&&$quebra=="N"){
     $pdf->setfont('arial', 'b', 8);
     $comp=236.5;
     $bord=1;
     $pdf->cell($comp, 6, 'TOTAL DE REGISTROS :  '.$total, $bord, 0, "L", 0);
     $pdf->cell(20, 6, $q_est, $bord, 0, "R", 0);
     $pdf->cell(25, 6, db_formatar($v_est,"f"), $bord, 1, "R", 0);
}    

$pdf->Output();
?>