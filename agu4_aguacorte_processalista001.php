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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_aguacorte_classe.php");
include("classes/db_aguacortemat_classe.php");
include("classes/db_aguacortematmov_classe.php");
include("classes/db_aguacortematnumpre_classe.php");
include("classes/db_aguacortetipodebito_classe.php");
include("classes/db_aguabasecar_classe.php");

$claguacorte = new cl_aguacorte;
$claguacortemat = new cl_aguacortemat;
$claguacortematmov = new cl_aguacortematmov;
$claguacortematnumpre = new cl_aguacortematnumpre;
$claguacortetipodebito = new cl_aguacortetipodebito;
$claguabasecar = new cl_aguabasecar;
$instit = db_getsession("DB_instit");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.x01_matric.focus();" >

<br>
<br>

<?

db_criatermometro('termometro', 'Concluido...', 'blue', 1);
flush();

/***
 *
 * Rotina que Gera Lista de Corte com base nos criterios cadastrados
 *
 */
	if(empty($x40_codcorte)) {
		echo '<script>alert("E necessario selecionar um Procedimento de Corte!");</script>';
	} else {
		//
		// Inicio do procedimento de Geracao da Lista de Corte
		//

		$sql_data = "	select k22_data as data_geracao 
						from debitos 
						where k22_instit = $instit 
						order by k22_data desc limit 1";

		$res = db_query($sql_data);
		db_fieldsmemory($res, 0);

		// Busca Criterios de Selecao Por Tipo de Debito
		$sql = $claguacortetipodebito->sql_query(null,"*",null,"x40_codcorte=$x40_codcorte");

		$res = db_query($sql); 
		$qtd = pg_numrows($res);

		$sql_proc = "select k22_matric, sum(k22_total) as k22_total from ( ";

		$sql_matric = array();

		// Monta SQL para buscar matriculas para corte
		for ($i = 0; $i < $qtd; $i ++) {
            db_atutermometro($i, $qtd, 'termometro');

			db_fieldsmemory($res, $i);

			// Verifica se foi passado valor minimo global,
			// ignorando valores minimos por tipo de debito
			$val = floatval($x40_vlrminimo);
			if( empty($val) ) {
			  	$val = floatval($x45_vlrminimo);
				$valor_minimo = empty($val)?'0':$x45_vlrminimo;
			} else {
			  	$valor_minimo = '0';
			}

			if( $i > 0 ) {
				$sql_proc .= " union ";
			}

//      if ($k03_tipo == 6 or $k03_tipo == 16 or $k03_tipo == 13) {
//        $sql_quant_numpar = "distinct k22_numpre || k22_numpar";
//      } else {
        $sql_quant_numpar = "distinct case when k03_tipo = 6 or k03_tipo = 16 or k03_tipo = 13 then k22_numpre || k22_numpar else extract (year from k22_dtvenc) || extract (month from k22_dtvenc) || k22_tipo end";
//      }

			$sql_proc .= "
				select  k22_matric,
				 	      k22_tipo,
				        count($sql_quant_numpar) as k22_numpar,
				        sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as k22_total
				from    debitos 
        inner   join arretipo on debitos.k22_tipo = arretipo.k00_tipo
				where 	k22_tipo = $x45_tipo  and k22_instit = $instit 
          and exists (select 1 from arrecad where k00_numpre = k22_numpre and k00_numpar = k22_numpar and k00_receit = k22_receit and k00_valor > 0) "; 

			$sql_insere = "
				insert into aguacortematnumpre ( 
					 	x44_codcortematnumpre, 
						x44_codcortemat, 
						x44_numpre, 
						x44_numpar, 
						x44_receit, 
						x44_dtvenc, 
						x44_tipo, 
						x44_vlrhis, 
						x44_vlrcor, 
						x44_juros, 
						x44_multa, 
						x44_desconto ) 
				 select nextval('aguacortematnumpre_x44_codcortematnumpre_seq'), 
				 		%codcortemat%, 
						k22_numpre, 
						k22_numpar, 
						k22_receit, 
						k22_dtvenc, 
						k22_tipo, 
						k22_vlrhis, 
						k22_vlrcor, 
						k22_juros, 
						k22_multa, 
						k22_desconto 
				 from   debitos 
				 where  k22_tipo = $x45_tipo  and k22_instit = $instit
          and exists (select 1 from arrecad where k00_numpre = k22_numpre and k00_numpar = k22_numpar and k00_receit = k22_receit and k00_valor > 0) "; 

			// Se for Divida Ativa (Tipo 5 - CadTipo)
			if($k03_tipo == 5) {
				$anoini = intval($x40_anoini);
				$anofim = intval($x40_anofim);
				if( !empty($anoini) && empty($anofim) ) {
					$sql_proc   .= "and  k22_exerc >= $x40_anoini ";
					$sql_insere .= "and  k22_exerc >= $x40_anoini ";
				} else if( empty($anoini) && !empty($anofim) ) {
					$sql_proc   .= "and  k22_exerc <= $x40_anofim ";
					$sql_insere .= "and  k22_exerc <= $x40_anofim ";
				} else if( !empty($anoini) && !empty($anofim) ) {
					$sql_proc   .= "and  k22_exerc between $x40_anoini and $x40_anofim ";
					$sql_insere .= "and  k22_exerc between $x40_anoini and $x40_anofim ";
				}
			}

			$sql_proc .= "
				and     k22_data = '$data_geracao'
				and     k22_dtvenc < '$x45_dtvenc' ";

			$sql_insere .= "
				 and    k22_data = '$data_geracao'
				 and    k22_dtvenc < '$x45_dtvenc' ";

			if( !empty($x45_dtopini) && empty($x45_dtopfim) ) {
				$sql_proc   .= "and  k22_dtoper >= '$x45_dtopini' ";
				$sql_insere .= "and  k22_dtoper >= '$x45_dtopini' ";
			} else if( empty($x45_dtopini) && !empty($x45_dtopfim) ) {
				$sql_proc   .= "and  k22_dtoper <= '$x45_dtopfim' ";
				$sql_insere .= "and  k22_dtoper <= '$x45_dtopfim' ";
			} else if( !empty($x45_dtopini) && !empty($x45_dtopfim) ) {
				$sql_proc   .= "and  k22_dtoper between '$x45_dtopini' and '$x45_dtopfim' ";
				$sql_insere .= "and  k22_dtoper between '$x45_dtopini' and '$x45_dtopfim' ";
				
			}
				
			$sql_proc .= "
				group by k22_matric, k22_tipo 
				having count($sql_quant_numpar) >= $x45_parcelas 
				and    sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) > 0";

			$val = floatval($valor_minimo);
			if(!empty($val)) {
				$sql_proc .= "and     sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) >= $valor_minimo ";
			}
			
			$sql_matric[] = $sql_insere;
						
		}
		$sql_proc .= " ) as lista_corte ";

		$_rua			= intval($x40_rua);
    $_zona		= intval($x40_zona);
		$_entrega = intval($x40_entrega);

    $val2 = intval($x40_zona);

		if(!empty($_rua) or !empty($_zona) or !empty($_entrega)) {

			$sql_proc .= "inner join aguabase  on x01_matric = k22_matric ";
      
      if(!empty($_rua)) {
        $sql_proc .= "                    and x01_codrua = $x40_rua ";
      }
      
      if(!empty($_zona)) {
        $sql_proc .= "                    and x01_zona = $x40_zona ";
      }

      if(!empty($_entrega)) {
        $sql_proc .= "                    and x01_entrega = $x40_entrega ";
      }
      
      if ($x40_tipomatricula == 2){
      	$sql_proc .= " where fc_agua_tipoimovel(x01_matric) = 0 ";
      }else if ($x40_tipomatricula == 3){
      	$sql_proc .= " where fc_agua_tipoimovel(x01_matric) = 1 ";
      }

		}else {
			
			$sql_proc .= "inner join aguabase  on x01_matric = k22_matric ";
			
			if ($x40_tipomatricula == 2){

				$sql_proc .= " where fc_agua_tipoimovel(x01_matric) = 0 ";
      
			}else if ($x40_tipomatricula == 3){

				$sql_proc .= " where fc_agua_tipoimovel(x01_matric) = 1 ";
      
			}

		}

//		if(!empty($val)) {
//			$sql_proc .= "inner join iptumatzonaentrega  on j86_iptucadzonaentrega = $x40_entrega ";
//			$sql_proc .= "                              and j86_matric = lista_corte.k22_matric ";
//		}

		//$val = intval($x40_rua);
		//if(!empty($val)) {
		//	$sql_proc .= "inner join aguabase on x01_codrua = $x40_rua ";
		//}

		$sql_proc .= "group by k22_matric ";
		
		$val = floatval($x40_vlrminimo);
		if(!empty($val)) {
			$sql_proc .= "having sum(k22_total) > $x40_vlrminimo and sum(k22_total) > 0 ";
		}

		//debug
		//$sql_proc .= " limit 5 ";
		//die( $sql_proc );
		//var_dump($sql_matric);
    //die();
		//echo "<br><br>";
		//echo $sql_proc;exit;

		$res_proc = db_query($sql_proc);
		$qtd2 = pg_numrows($res_proc);

		if($qtd2==0) {
		    $gerou = false;
			db_msgbox("Nenhum registro encontrado com base nos criterios selecionados");
			echo "<script>  parent.db_iframe1.hide();	  </script>";
           // db_redireciona("agu4_aguacorte_processalista.php?acao=$acao");
		}

		db_inicio_transacao();

		$insere1 = true;
		$insere2 = true;
		$salto = $qtd2/100;
		$avanco = 0;
		$x = 1;
		//termo("Gerando Lista de Corte", 1, $qtd2);
		//while($row1 = pg_fetch_row($res_proc)) {
    for($y=0; $y<$qtd2; $y++) {
      db_atutermometro($y, $qtd2, 'termometro');
      
      // Carrega campos pra um array
      $row1 = pg_fetch_row($res_proc, $y);

			$x++;

			// Verifica Caracteristica de NAO LIBERAR o Corte...
			$claguabasecar->sql_record($claguabasecar->sql_query($row1[0], 5307));

			// Se procedimento de corte nao finalizado OU caracteristica de nao liberacao para o corte
      $sqlregra    = $claguacortemat->sql_query_ultimaregra($row1[0]);
      $resultregra = $claguacortemat->sql_record($sqlregra);
      if($claguacortemat->numrows > 0 ) {
        db_fieldsmemory($resultregra, 0);
  			if($x43_regra<>2 || $claguabasecar->numrows>0) {
	  			// processa proxima matricula
		  		continue;
			  }
      }
	
			// Insere em AGUACORTEMAT
			$claguacortemat->x41_matric = $row1[0];
			$claguacortemat->x41_codcorte = $x45_codcorte;
			$claguacortemat->x41_dtprazo_dia = $x41_dtprazo_dia;
			$claguacortemat->x41_dtprazo_mes = $x41_dtprazo_mes;
			$claguacortemat->x41_dtprazo_ano = $x41_dtprazo_ano;
			$insere1 = ($insere1 && $claguacortemat->incluir(null));

			// Insere em AGUACORTEMATMOV
			$claguacortematmov->x42_codsituacao = $x40_codsituacao;
			$claguacortematmov->x42_codcortemat = $claguacortemat->x41_codcortemat;
			$claguacortematmov->x42_data_dia = date("d", db_getsession("DB_datausu"));
			$claguacortematmov->x42_data_mes = date("m", db_getsession("DB_datausu"));
			$claguacortematmov->x42_data_ano = date("Y", db_getsession("DB_datausu"));
			$claguacortematmov->x42_usuario = db_getsession("DB_id_usuario");
			$claguacortematmov->x42_historico = "Debito R$ " . db_formatar($row1[1], "f");
			$insere1 = ($insere1 && $claguacortematmov->incluir(null));
			
			foreach($sql_matric as $sql_matric_insere) {

				$sql_matric_insere .= "and  k22_matric = {$row1[0]}";

				$sql_matric_insere = str_replace(array("%codcortemat%"), array($claguacortemat->x41_codcortemat), $sql_matric_insere);

				//die($sql_matric_insere);

				$res_matric_insere = db_query($sql_matric_insere);
				$insere1 = ($insere1 && $res_matric_insere);
				
				//if(!$insere1) {
				//  echo pg_last_error($res_matric_insere) . "<br>";
				//  die($sql_matric_insere);
				//}
			}
			
		}
	
		db_fim_transacao(!$insere1);
		if( @$gerou != false){
			if($insere1 ) {
				db_msgbox("Processamento concluido com SUCESSO!");
			} else {
				db_msgbox("Processamento concluido com ERRO! Contate CPD.");
			}
		}
		//echo "<br><br>".$sql_matric;
		
	}
?>
</body>
</html>