<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

set_time_limit(0);
include ("libs/db_sql.php");
include ("fpdf151/pdf.php");
include ("classes/db_tabativ_classe.php");
include ("dbforms/db_funcoes.php");
$cltabativ = new cl_tabativ;
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');
$clrotulo->label('q02_inscr');
$clrotulo->label('j01_matric');
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$instit = db_getsession("DB_instit");
//die("filtro = $filtro");
if ($lista == '') {
  
  $sMsg = _M('tributario.notificacoes.cai2_emitelista002.lista_nao_encontrada');
	db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
	exit;
}

$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
db_fieldsmemory(db_query($sqlinst), 0, true);
$sqllista = "select * from lista where k60_codigo = $lista and k60_instit = $instit";
$resultlista = db_query($sqllista);
db_fieldsmemory($resultlista, 0);

if ($agrupar == "s") {
	$k60_tipo = "N";
}

$where_tipo = "";
if ($k60_tipo == 'M') {
  $xcodigo = 'k22_matric';
	$xcodigo1 = 'k22_matric';
	$xrel = "Matric";
  $where_tipo = " and coalesce(k22_matric,0) <> 0 ";
}
elseif ($k60_tipo == 'I') {
	$xcodigo = 'k22_inscr';
	$xcodigo1 = 'k22_inscr';
	$xrel = "Inscr";
  $where_tipo = " and coalesce(k22_inscr,0) <> 0 ";
}
elseif ($k60_tipo == 'N' or $k60_tipo == 'C') {
	if ($agrupar == "s") {
		$xcodigo = 'numcgm';
	  $xcodigo1 = 'numcgm';
	} else {
		$xcodigo = 'k22_numcgm';
	  $xcodigo1 = 'k22_numcgm';
	}
	$xrel = "CGM";
  if ($k60_tipo == 'N') {
    $where_tipo = " and coalesce(k22_numcgm,0) <> 0 ";
  } else {
    $where_tipo  = " and coalesce(k22_numcgm,0) <> 0 ";
    $where_tipo .= " and coalesce(k22_matric,0) =  0 ";
    $where_tipo .= " and coalesce(k22_inscr, 0) =  0 ";
  }
}

if ($ordem == 'a') {
	$xxordem = ' order by z01_nome ';
}
elseif ($ordem == 'n') {
	if ($agrupar == "s") {
		$xxordem = ' order by '.$xcodigo;
	} else {
		$xxordem = ' order by '.$xcodigo1;
	}
}
elseif ($ordem == 'v') {
	$xxordem = ' order by xvalor desc';
}

if ($agrupar == "s") {
	$sql = "select * 
	          from ( select debitos.k22_numcgm as numcgm,
									        cgm.z01_nome,
                          cgm.z01_cgccpf,
									        sum(coalesce(k22_vlrcor, 0) + coalesce(k22_juros,0) + coalesce(k22_multa,0) - coalesce(k22_desconto,0)) as xvalor,
								   	      k63_notifica
		                 from lista 
									 		 	  inner join listadeb on listadeb.k61_codigo = lista.k60_codigo 
											 	  inner join debitos  on listadeb.k61_numpre = debitos.k22_numpre 
												                     and listadeb.k61_numpar = debitos.k22_numpar 
												                     and debitos.k22_data = lista.k60_datadeb 
												                     and debitos.k22_instit = $instit
                                             $where_tipo
												  left join cgm on debitos.k22_numcgm = cgm.z01_numcgm
												  left join listanotifica on k63_numpre =  listadeb.k61_numpre 
												                         and k63_codigo = $lista
	                  where k60_codigo = $lista 
	                    and k60_instit = $instit 
                    group by debitos.k22_numcgm, 
                             cgm.z01_nome,
                             k63_notifica
                 ) as x $xxordem";
} else {
	$sql = "select x.numcgm as numcgm,
                 $xcodigo1,
                 x.z01_nome,
                 x.z01_cgccpf,
                 sum(xvalor) as xvalor,
                 x.k63_notifica
	          from ( select debitos.k22_numcgm as numcgm,
		                      $xcodigo,
		                      cgm.z01_nome,
                          cgm.z01_cgccpf,
									        sum(coalesce(k22_vlrcor, 0) + coalesce(k22_juros,0) + coalesce(k22_multa,0) - coalesce(k22_desconto,0)) as xvalor,
	                        k63_notifica
		                 from lista 
												  inner join listadeb on listadeb.k61_codigo = lista.k60_codigo 
												  inner join debitos  on listadeb.k61_numpre = debitos.k22_numpre 
												                     and listadeb.k61_numpar = debitos.k22_numpar 
												                     and debitos.k22_data    = '$k60_datadeb' 
												                     and debitos.k22_instit  = $instit
                                             $where_tipo
												  left join cgm on debitos.k22_numcgm = cgm.z01_numcgm
												  left join listanotifica on k63_numpre =  listadeb.k61_numpre and k63_codigo = $lista
                	  where k60_codigo = $lista 
                	    and k60_instit = $instit 
                    group by debitos.k22_numcgm,
                             debitos.k22_numpre, 
                             $xcodigo1, 
                             cgm.z01_nome,
                             cgm.z01_cgccpf,
                             k63_notifica
                ) as x 
                group by x.numcgm,
                         $xcodigo1, 
                         x.z01_nome,
                         x.z01_cgccpf,
                         x.k63_notifica
                $xxordem ";
}

//die($sql);

$result = db_query($sql);

if ($tipo == 'p') {

	$head1 = 'LISTAS PARA NOTIFICAÇÕES';
	$head3 = 'LISTA N'.chr(176).' : '.$k60_codigo.' - '.$k60_descr;
	$head5 = 'TIPO : '.@$xrel;
	$head7 = 'DATA DO CÁLCULO : '.db_formatar($k60_datadeb, 'd');
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->addpage();
	if (isset ($k60_tipo) && $k60_tipo == 'I') {
		$pdf->setfillcolor(235);
		$pdf->setfont('arial', 'b', 10);
		$pdf->cell(15, 5, "Inscr", 1, 0, "c", 1);
		$pdf->cell(30, 5, "CPF/CNPJ", 1, 0, "c", 1);
		$pdf->cell(15, 5, 'Notif', 1, 0, "C", 1);
		$pdf->cell(60, 5, $RLz01_nome, 1, 0, "c", 1);
		$pdf->cell(50, 5, "Atividade Principal", 1, 0, "c", 1);
		if (isset ($comvalor) && $comvalor == 's') {
			$pdf->cell(20, 5, 'Valor', 1, 1, "c", 1);
		} else {
			$pdf->cell(20, 5, '', 0, 1, "c", 0);
		}
	} else {
		$pdf->setfillcolor(235);
		$pdf->setfont('arial', 'b', 10);
		$pdf->cell(15, 5, @$xrel, 1, 0, "c", 1);
		$pdf->cell(30, 5, "CPF/CNPJ", 1, 0, "c", 1);
		$pdf->cell(15, 5, 'Notif', 1, 0, "C", 1);
		$pdf->cell(100, 5, $RLz01_nome, 1, 0, "c", 1);
		if (isset ($comvalor) && $comvalor == 's') {
			$pdf->cell(25, 5, 'Valor', 1, 1, "c", 1);
		} else {
			$pdf->cell(25, 5, '', 0, 1, "c", 0);
		}
	}
	$pdf->setfont('arial', '', 8);
	$total = 0;
	$totalval = 0;
	$valor = 0;
	$xnumcgm = "";
	for ($x = 0; $x < pg_numrows($result); $x ++) {
		db_fieldsmemory($result, $x);
		$cgm = $numcgm;
		// if($x == 0)
		// $xnumcgm = $cgm;

		$xnumcgm = $numcgm;
		$xcod = $$xcodigo1;
		$xnome = $z01_nome;

    if ( strlen(trim($z01_cgccpf)) == 14 ) {
      $z01_cgccpf = db_formatar($z01_cgccpf,'cnpj');
    } elseif ( strlen(trim($z01_cgccpf)) == 11 ) {
      $z01_cgccpf = db_formatar($z01_cgccpf,'cpf');
    }

		if ($pdf->gety() > $pdf->h - 35) {
			$pdf->addpage();
			$pdf->setfont('arial', 'b', 8);
			if (isset ($k60_tipo) && $k60_tipo == 'I') {
				$pdf->cell(15, 5, "Inscr", 1, 0, "c", 1);
		    $pdf->cell(30, 5, "CPF/CNPJ", 1, 0, "c", 1);
				$pdf->cell(15, 5, 'Notif', 1, 0, "C", 1);
				$pdf->cell(60, 5, $RLz01_nome, 1, 0, "c", 1);
				$pdf->cell(50, 5, "Atividade Principal", 1, 0, "c", 1);
				if (isset ($comvalor) && $comvalor == 's') {
					$pdf->cell(20, 5, 'Valor', 1, 1, "c", 1);
				} else {
					$pdf->cell(20, 5, '', 0, 1, "c", 0);
				}
			} else {
				$pdf->cell(15, 5, $xrel, 1, 0, "c", 1);
		    $pdf->cell(30, 5, "CPF/CNPJ", 1, 0, "c", 1);
				$pdf->cell(15, 5, 'Notif', 1, 0, "C", 1);
				$pdf->cell(100, 5, $RLz01_nome, 1, 0, "c", 1);
				if (isset ($comvalor) && $comvalor == 's') {
					$pdf->cell(25, 5, 'Valor', 1, 1, "c", 1);
				} else {
					$pdf->cell(25, 5, '', 0, 1, "c", 0);
				}
			}
			$pdf->setfont('arial', '', 8);
		}
		//  if($cgm != $xnumcgm){
		if (isset ($k60_tipo) && $k60_tipo == 'I') {
			$q03_descr = "";
			$result_princ = $cltabativ->sql_record($cltabativ->sql_query_princ($xcod));
			if ($cltabativ->numrows>0){
				db_fieldsmemory($result_princ,0);
			}

      if ( strlen(trim($z01_cgccpf)) == 14 ) {
        $z01_cgccpf = db_formatar($z01_cgccpf,'cnpj');
      } elseif ( strlen(trim($z01_cgccpf)) == 11 ) {
        $z01_cgccpf = db_formatar($z01_cgccpf,'cpf');
      }

			$pdf->cell(15, 5, $xcod, 0, 0, "C", 0);
			$pdf->cell(30, 5, $z01_cgccpf, 0, 0, "L", 0);
			$pdf->cell(15, 5, substr(@ $k63_notifica, 0, 50), 0, 0, "C", 0);
			$pdf->cell(60, 5, substr($xnome,0,33), 0, 0, "L", 0);
			$pdf->cell(50, 5, substr($q03_descr, 0, 28), 0, 0, "L", 0);
			if (isset ($comvalor) && $comvalor == 's') {
				$pdf->cell(20, 5, db_formatar($xvalor, 'f'), 0, 1, "R", 0);
			} else {
				$pdf->cell(20, 5, '', 0, 1, "R", 0);
			}
		} else {
			$pdf->cell(15, 5, $xcod, 0, 0, "C", 0);
			$pdf->cell(30, 5, $z01_cgccpf, 0, 0, "L", 0);
			$pdf->cell(15, 5, @ $k63_notifica, 0, 0, "C", 0);
			$pdf->cell(100, 5, $xnome, 0, 0, "L", 0);
			if (isset ($comvalor) && $comvalor == 's') {
				$pdf->cell(25, 5, db_formatar($xvalor, 'f'), 0, 1, "R", 0);
			} else {
				$pdf->cell(25, 5, '', 0, 1, "R", 0);
			}
		}
		$total += 1;
		$valor = 0;
		//  }
		$totalval += $xvalor;
		//  $valor += $xvalor; 
		//  $xnumcgm = $numcgm;
		//  $xcod = $$xcodigo;
		//  $xnome = $z01_nome;
	}
	if (isset ($comvalor) && $comvalor == 's') {
		if (isset ($k60_tipo) && $k60_tipo == 'I') {
		  $pdf->cell(165, 05, 'Total de registros:   '.$total, 1, 0, "c", 1);
    } else {
		  $pdf->cell(160, 05, 'Total de registros:   '.$total, 1, 0, "c", 1);
    }
		$pdf->cell(25, 05, db_formatar($totalval, 'f'), 1, 1, "R", 1);
	} else {
		$pdf->cell(160, 05, '', 0, 0, "c", 0);
		$pdf->cell(25, 05, '', 0, 1, "R", 0);
	}

	//include("fpdf151/geraarquivo.php");
	if(($filtro=='s')&&($tipo=='p')){
		$sqlfiltro = "select k60_codigo,login,nome,k60_filtros from lista inner join db_usuarios on id_usuario = k60_usuario where k60_codigo = $lista and k60_instit = $instit";
		$resultfiltro = db_query($sqlfiltro);
		$linhasfiltro = pg_num_rows($resultfiltro);
		if ($linhasfiltro>0){
			db_fieldsmemory($resultfiltro, 0);
			if ($k60_filtros!=""){
				$pdf->addpage();
				$pdf->setfillcolor(235);
				$pdf->setfont('arial', 'b', 10);
				$pdf->cell(190, 05, 'FILTROS DA LISTA '. $lista, 0, 1, "C", 1);
				$pdf->ln(5);
				$pdf->cell(190, 05, 'Login do Usuário: '. $login, 0, 1, "L", 0);
				$pdf->cell(190, 05, 'Nome do Usuário: '. $nome, 0, 1, "L", 0);
				$pdf->ln(5);
				$pdf->cell(190, 05, 'Filtro da lista', 1, 1, "C", 0);
				$pdf->setfont('arial', '', 10);
				$filtro2 = str_replace ( "#", "\n",$k60_filtros );
				$pdf->Multicell(190, 05, $filtro2, 1, 1, "L", 0);
				//Codigo, Login e Nome do Usuário
				
			}
		}

	}
			
	$pdf->Output();

} else {

	$clabre_arquivo = new cl_abre_arquivo("/tmp/lista-$lista.txt");
	$sep = "#";

	if ($clabre_arquivo->arquivo != false) {

		fputs($clabre_arquivo->arquivo, $xrel.$sep);
		fputs($clabre_arquivo->arquivo, 'CPF/CNPJ'.$sep);
		fputs($clabre_arquivo->arquivo, 'Notificação'.$sep);
		fputs($clabre_arquivo->arquivo, $RLz01_nome.$sep);
		if (isset ($comvalor) && $comvalor == 's') {
			fputs($clabre_arquivo->arquivo, 'Valor'.$sep);
		}
		fputs($clabre_arquivo->arquivo, "\n");

		for ($x = 0; $x < pg_numrows($result); $x ++) {
			db_fieldsmemory($result, $x);
			$cgm = $numcgm;

			$xnumcgm = $numcgm;
			$xcod = $$xcodigo;
			$xnome = $z01_nome;

			fputs($clabre_arquivo->arquivo, $xcod.$sep);
			fputs($clabre_arquivo->arquivo, $z01_cgccpf.$sep);
			fputs($clabre_arquivo->arquivo, @ $k63_notifica.$sep);
			fputs($clabre_arquivo->arquivo, $xnome.$sep);
			if (isset ($comvalor) && $comvalor == 's') {
				fputs($clabre_arquivo->arquivo, db_formatar($xvalor, 'f').$sep);
			}
			fputs($clabre_arquivo->arquivo, "\n");

			$total += 1;
			$valor = 0;
			$totalval += $xvalor;

		}

		fclose($clabre_arquivo->arquivo);
		$erro = true;
		$descricao_erro = "Carnes gerados com sucesso!";

		echo "<script>jan = window.open('db_download.php?arquivo=".$clabre_arquivo->nomearq."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		        jan.moveTo(0,0);</script>";
	}

}