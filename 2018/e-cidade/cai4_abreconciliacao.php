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
	include("libs/db_usuariosonline.php");
	include("dbforms/db_funcoes.php");
	include("classes/db_concilia_classe.php");
	include("classes/db_conciliapendcorrente_classe.php");
	include("classes/db_conciliapendextrato_classe.php");
	include("classes/db_corrente_classe.php");
	include("classes/db_extratolinha_classe.php");

	$clcorrente             = new cl_corrente;
	$clextratolinha         = new cl_extratolinha;
	$clconcilia             = new cl_concilia;
	$clconciliapendcorrente = new cl_conciliapendcorrente;
	$clconciliapendextrato  = new cl_conciliapendextrato;

	$sqlerro = false;
	$erromsg = "";

	db_postmemory($HTTP_POST_VARS);
  
	// verifica se ja nao existe uma conciliacao aberta para a conta selecionada //
	$rsVerificaConcilacao = $clconcilia->sql_record($clconcilia->sql_query_file(null,"*",null," k68_contabancaria = $conta and k68_conciliastatus = 1 "));
	if ($clconcilia->numrows > 0) {
		//die("2|||Ja existe uma conciliacao aberta para esta conta, salve esta conciliacao antes de passar para a proxima ");		
	}

  $sWhereReduz  = " select c61_reduz ";
  $sWhereReduz .= "   from contabancaria ";
  $sWhereReduz .= "        inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial ";
  $sWhereReduz .= "        inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon ";
  $sWhereReduz .= "                                        and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu ";
  $sWhereReduz .= "                                        and conplanoreduz.c61_anousu = ".db_getsession('DB_anousu');
  $sWhereReduz .= "                                        and conplanoreduz.c61_instit = ".db_getsession('DB_instit');
  $sWhereReduz .= "  where contabancaria.db83_sequencial = {$conta} ";

	// select somando o valor total do corrente 
	$rsTotalCorrente = $clcorrente->sql_record($clcorrente->sql_query_file(null,
                                                                         null,
                                                                         null,
                                                                         " coalesce(sum(k12_valor),0) as totalcorrente ",
                                                                         null,
                                                                         " k12_data = '".$data."' and k12_conta in ($sWhereReduz) "));
	if ($clcorrente->numrows > 0) {
		db_fieldsmemory($rsTotalCorrente,0);	
	}

	// select somando o valor total do extrato 
	$rsTotalExtrato = $clextratolinha->sql_record($clextratolinha->sql_query_file(null,
                                                                                " coalesce(sum(k86_valor),0) as totalextrato ",
                                                                                null,
                                                                                " k86_data = '".$data."' and k86_contabancaria = $conta "));
	if ($clextratolinha->numrows > 0){
		db_fieldsmemory($rsTotalExtrato,0);	
	}

	db_inicio_transacao();

	$clconcilia->k68_data           = $data;
	$clconcilia->k68_contabancaria  = $conta;
	$clconcilia->k68_saldoextrato   = $totalextrato;
	$clconcilia->k68_saldocorrente  = $totalcorrente;
	$clconcilia->k68_conciliastatus = 1;
	$clconcilia->incluir(null);
	$erromsg = $clconcilia->erro_msg;
	if($clconcilia->erro_status == 0){
		$sqlerro = true;
	}

	// select na corrente por data e conta e for duplicando as pendencias de conciliacoes anteriores para essa em questao 
	$sqlPendCorrente  = " select conciliapendcorrente.* ";
	$sqlPendCorrente .= "	  from concilia ";
	$sqlPendCorrente .= "	       inner join conciliapendcorrente on k89_concilia = k68_sequencial ";
	$sqlPendCorrente .= "	 where k68_data = (select k68_data 
                                             from concilia 
                                            where k68_data < '".$data."' 
                                              and k68_contabancaria = $conta 
                                            order by k68_data 
                                             desc limit 1)";
	$sqlPendCorrente .= "	   and k68_contabancaria = ".$conta ; 

	$rsCorrente = $clcorrente->sql_record($sqlPendCorrente);
	$intNumrows = $clcorrente->numrows;
	for($i = 0; $i < $intNumrows; $i++ ){
		db_fieldsmemory($rsCorrente,$i);	
		$clconciliapendcorrente->k89_concilia       = $clconcilia->k68_sequencial;
		$clconciliapendcorrente->k89_id             = $k89_id;
		$clconciliapendcorrente->k89_data           = $k89_data;
		$clconciliapendcorrente->k89_autent         = $k89_autent;
		$clconciliapendcorrente->k89_justificativa  = $k89_justificativa;
		$clconciliapendcorrente->k89_conciliaorigem = 1;	
		$clconciliapendcorrente->incluir(null);
		if($clconciliapendcorrente->erro_status == 0){
			$erromsg = $clconciliapendcorrente->erro_msg;
			$sqlerro = true;
			break;
		}
	}

	// mesma coisa que o for a cima porem com as pendencias de extrato 
	$sqlPendExtrato  = " select conciliapendextrato.* ";
	$sqlPendExtrato .= "	 from concilia ";
	$sqlPendExtrato .= "		    inner join conciliapendextrato on k88_concilia = k68_sequencial ";
	$sqlPendExtrato .= "	where k68_data  = ( select k68_data "; 
  $sqlPendExtrato .= "                        from concilia "; 
  $sqlPendExtrato .= "                       where k68_data < '".$data."' "; 
  $sqlPendExtrato .= "                         and k68_contabancaria = $conta "; 
  $sqlPendExtrato .= "                       order by k68_data  ";
  $sqlPendExtrato .= "                        desc limit 1 ) ";
  $sqlPendExtrato .= "	  and k68_contabancaria = ".$conta ;
	$rsExtrato = $clextratolinha->sql_record($sqlPendExtrato);
	$intNumrowsextrato = $clextratolinha->numrows;
	for($i = 0; $i < $intNumrowsextrato; $i++ ){
		db_fieldsmemory($rsExtrato,$i);	
		$clconciliapendextrato->k88_extratolinha   = $k88_extratolinha;
		$clconciliapendextrato->k88_concilia       = $clconcilia->k68_sequencial;
		$clconciliapendextrato->k88_conciliaorigem = 1;
		$clconciliapendextrato->k88_justificativa  = $k88_justificativa;
		$clconciliapendextrato->incluir(null);
		if($clconciliapendextrato->erro_status == 0){
			$erromsg = $clconciliapendextrato->erro_msg;
			$sqlerro = true;
			break;
		}
	}

	db_fim_transacao($sqlerro);

	if($sqlerro){
		echo "2|||".$erromsg;
	}else{
		echo "1|||Processamento concluido com sucesso.|||".$clconcilia->k68_sequencial;
	}

?>