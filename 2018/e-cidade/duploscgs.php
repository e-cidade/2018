<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("model/configuracao/TraceLog.model.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conn.php"));

// Funcao para dar Echo dos Logs - retorna o TimeStamp
function db_logduplos($sLog = "") {
	//
	$aDataHora = getdate ();
	
	$sOutputLog = sprintf ( "\n[%02d/%02d/%04d %02d:%02d:%02d] %s", $aDataHora ["mday"], $aDataHora ["mon"], $aDataHora ["year"], $aDataHora ["hours"], $aDataHora ["minutes"], $aDataHora ["seconds"], $sLog );
	echo $sOutputLog;
	
	return $aDataHora;
}
$isTeste = (strtoupper ( $argv [1] ) == "TESTE");

if ($isTeste) {
	db_logduplos ( "" );
	db_logduplos ( ">>>>>> MODO DE TESTE. Não executará COMMIT ao final do processamento! <<<<<<" );
	db_logduplos ( "" );
}

// time utilizado para monitoria pelo zabbix
db_logduplos(time());

$aDataHoraInicial = db_logduplos ( "Iniciando Execucao do Duplos.php - 3 segundos... se quiser cancelar CTRL+C" );
db_logduplos ( "Configuracoes: BASE: $DB_BASE  SERVIDOR: $DB_SERVIDOR  PORTA: $DB_PORTA  USUARIO: $DB_USUARIO" );
sleep ( 3 );

if (! ($conn = @pg_connect ( "host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA" ))) {
	db_logduplos ( "Erro ao conectar com a base de dados" );
	exit(1);
}

db_logduplos ( "Inicializando Sessao do e-cidade na Base de Dados..." );
$sqlsessao = "select fc_startsession();";
$resultsessao = db_query ( $conn, $sqlsessao ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );

$sqlsessao = "select fc_putsession('DB_instit', cast((select codigo from db_config where prefeitura is true limit 1) as text));";
$resultsessao = db_query ( $conn, $sqlsessao ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );

db_logduplos("Dividindo agendamentos múltiplos em agendamentos individuais...");
$sqldivide  = "select fc_divide_agendamento_duploscgs(s127_i_codigo) ";
$sqldivide .= "  from sau_cgscorreto ";
$sqldivide .= "       join sau_cgserrado on s128_i_codigo = s127_i_codigo ";
$sqldivide .= " where s127_b_proc is false ";
$sqldivide .= " group by s127_i_codigo ";
$sqldivide .= "having count(*) > 1 ";
$resultdivide = db_query($conn, $sqldivide);




$mostra = 0;

$sql_correto = "select * from sau_cgscorreto where s127_b_proc is false order by s127_d_data, s127_c_hora, s127_i_codigo";
$result_correto = db_query ( $sql_correto ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );

for($record_correto = 0; $record_correto < pg_numrows ( $result_correto ); $record_correto ++) {
	db_fieldsmemory ( $result_correto, $record_correto );
	db_logduplos ( "\n\n\n\n\n\n" );
	db_logduplos ( " processando cgs correto: " . $s127_i_numcgs . " - codigo: $s127_i_codigo - $record_correto/" . pg_numrows ( $result_correto ) . "..." );
	
	// Grava usuario que agendou o duplos na sessao
	$sqlsessao = "select fc_putsession('DB_id_usuario', '$s127_i_login');";
	$resultsessao = db_query ( $conn, $sqlsessao );
	
	$result = db_query ( "begin;" );
	
	$sql_errado = "select * from sau_cgserrado where s128_i_codigo = $s127_i_codigo";
	$result_errado = db_query ( $sql_errado ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
	
	for($record_errado = 0; $record_errado < pg_numrows ( $result_errado ); $record_errado ++) {
		db_fieldsmemory ( $result_errado, $record_errado );
		db_logduplos ( "            cgs errado.: " . $s128_i_numcgs . " - codigo: $s127_i_codigo..." );
		sleep ( 3 );
		
		$v_log = "";
		
		$v_cgscerto = $s127_i_numcgs;
		$v_sau_cgserrado = $s128_i_numcgs;
		
		if ($v_cgscerto > 0) {
			/*
			 * 2354, - sau_config
			 * 2712, - sau_cgscorreto
			 * 2713, - sau_cgserrado
			 * 2715, - sau_cgserradolog
			 * 1010142, - cgs
			 * 1010143, - cgs_cgm 
			 * 1010154, - cgs_undalt 
			 * 1010144 - cgs_und
			 */
			
			$sql1 = "select db_syscampodep.codcam, ";
			$sql1 .= "       rtrim(db_sysarquivo.nomearq)::varchar(40) as nomearq, ";
			$sql1 .= "       db_sysarqcamp.codarq, ";
			$sql1 .= "       db_syscampo.nomecam ";
			$sql1 .= "  from db_syscampodep ";
			$sql1 .= "       inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampodep.codcam ";
			$sql1 .= "       inner join db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq ";
			$sql1 .= "       inner join db_syscampo   on db_syscampo.codcam   = db_sysarqcamp.codcam ";
			$sql1 .= " where db_syscampodep.codcampai = 1008839 ";
			//$sql1 .= "   and db_syscampodep.codcam not in (5153,5159,216,7872,8213,8195) ";
			$sql1 .= "   and db_sysarqcamp.codarq not in (2354,2712,2713,2715,1010142,1010143,1010154,1010144)  ";
			$sql1 .= "   and exists(select column_name ";
			$sql1 .= "                from information_schema.columns ";
			$sql1 .= "               where table_name  = trim(db_sysarquivo.nomearq) ";
			$sql1 .= "                 and column_name = trim(db_syscampo.nomecam)) ";
			$sql1 .= "union ";
			$sql1 .= "select db_syscampo.codcam, ";
			$sql1 .= "       rtrim(db_sysarquivo.nomearq)::varchar(40) as nomearq, ";
			$sql1 .= "       db_sysarqcamp.codarq, ";
			$sql1 .= "       db_syscampo.nomecam ";
			$sql1 .= "  from db_syscampo ";
			$sql1 .= "	     inner join db_sysarqcamp on db_syscampo.codcam = db_sysarqcamp.codcam ";
			$sql1 .= "       inner join db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq ";
			$sql1 .= " where nomecam like '%cgs%' ";
			$sql1 .= "   and db_sysarqcamp.codarq not in (2354,2712,2713,2715,1010142,1010143,1010154,1010144)  ";
			//$sql1 .= "   and db_sysarqcamp.codcam not in (5153,5159,216,7872,8213,8195)";
			$sql1 .= "   and exists(select column_name ";
			$sql1 .= "                from information_schema.columns ";
			$sql1 .= "               where table_name  = trim(db_sysarquivo.nomearq) ";
			$sql1 .= "                 and column_name = trim(db_syscampo.nomecam)) ";

			/*
       * Verifica cgs's processados como corretos que estão hoje como errados 
       * Se encontrar algum para esse processamento, altera o cgs correto do processamento para o cgs correto atual.  
       */
			$sSqlsau_cgscorreto = "select s127_i_codigo as codcorreto_ant,
                                z01_i_cgsund as numsau_cgscorreto_ant,
                                z01_v_nome   as nomecorreto_ant
                           from sau_cgscorreto 
                          inner join cgs_und       on z01_i_cgsund = s127_i_numcgs
                          inner join sau_cgserrado on s127_i_codigo = s128_i_codigo 
                          where s127_i_numcgs = $v_sau_cgserrado";
			
			$rssau_cgscorreto = db_query ( $sSqlsau_cgscorreto ) or die ( $sSqlsau_cgscorreto." \n ".pg_ErrorMessage () );
			if (pg_numrows ( $rssau_cgscorreto ) > 0) {
				db_fieldsmemory ( $rssau_cgscorreto, 0 );
				db_logduplos ( "processando tabela sau_cgscorreto" );
				
				$sql_corretoant = "update sau_cgscorreto set s127_i_numcgs = $v_cgscerto where s127_i_codigo = $codcorreto_ant";
				$query_corretoant = db_query ( $sql_corretoant ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
			
			}
			
			$result_campos = db_query ( $sql1 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
			
			for($record_campos = 0; $record_campos < pg_numrows ( $result_campos ); $record_campos ++) {
				db_fieldsmemory ( $result_campos, $record_campos );
				
				$v_log .= "processando tabela $nomearq";
				db_logduplos ( "processando tabela $nomearq - codigo: $codarq" );
				
				// ver se tabela existe no banco...
				$sql2 = "select relname ";
				$sql2 .= "	from pg_class ";
				$sql2 .= "	     left join pg_index on relfilenode = indexrelid ";
				$sql2 .= " where relname not like 'pg_%' ";
				$sql2 .= "   and relkind = 'r' ";
				$sql2 .= "   and relname = '$nomearq' order by relname";
				$result_relname = db_query ( $sql2 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
				
				// certificar que esta habilitado
				$v_comando = "alter table $nomearq enable trigger all";
				$sql = db_query ( $v_comando ) or die ( db_logduplos ( "sql: $v_comando \n " . pg_ErrorMessage () ) );
				
				if (pg_numrows ( $result_relname ) == 0) {
					$v_log .= "          tabela $nomearq nao encontrada no banco...\n";
					if (($codarq == 343 or $codarq == 959) and $mostra == 1) {
						db_logduplos ( "... tabela $nomearq nao encontrada no banco..." );
					}
				} else {
					$v_log .= "          tabela $nomearq encontrada no banco...\n";
					db_fieldsmemory ( $result_relname, 0 );
					
					$v_comando = "select * from $nomearq where ";
					$v_contador = 1;
					
					$sql30 = "select count(*) as v_quantpk ";
					$sql30 .= "  from db_sysprikey ";
					$sql30 .= "  inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam ";
					$sql30 .= "  where db_sysprikey.codarq = $codarq";
					$result_quantpk = db_query ( $sql30 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
					db_fieldsmemory ( $result_quantpk, 0 );
					
					$sql3 = "select ";
					$sql3 .= "	rtrim(db_syscampo.nomecam) as v_nomepk ";
					$sql3 .= "  from db_sysprikey ";
					$sql3 .= "  inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam ";
					$sql3 .= " where db_sysprikey.codarq = $codarq and ";
					$sql3 .= "	db_sysprikey.codcam = $codcam";
					$result_pk = db_query ( $sql3 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
					
					if (pg_numrows ( $result_pk ) > 0 and $v_quantpk >= 1) {
						
						db_logduplos ( "   11 - achou pk em " . $nomearq );
						
						db_fieldsmemory ( $result_pk, 0 );
						$v_comando = $v_comando . $v_nomepk . " = " . $v_sau_cgserrado;
						
						$result_comando = db_query ( $v_comando ) or die ( db_logduplos ( "sql: $v_comando \n " . pg_ErrorMessage () ) );
						db_logduplos ( "executando comando: $v_comando - " . (pg_numrows ( $result_comando ) > 0 ? "encontrou " . pg_numrows ( $result_comando ) . " registros" : "nao encontrou nenhum registro") );
						
						$v_log .= "executando comando: $v_comando - " . (pg_numrows ( $result_comando ) > 0 ? "encontrou " . pg_numrows ( $result_comando ) . " registros" : "nao encontrou nenhum registro" . "\n");
						
						if (pg_numrows ( $result_comando ) > 0) {
							
							for($record_conteudo = 0; $record_conteudo < pg_numrows ( $result_comando ); $record_conteudo ++) {
								db_fieldsmemory ( $result_comando, $record_conteudo );
								
								$v_campos = "select * from " . $nomearq . " where " . $v_nomepk . " = " . $v_cgscerto;
								
								$sql4 = "select rtrim(db_syscampo.nomecam) as nomecam_pk, conteudo as conteudo_pk ";
								$sql4 .= "	from db_sysprikey ";
								$sql4 .= "	inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam and ";
								$sql4 .= "	db_sysprikey.codcam <> $codcam ";
								$sql4 .= "where db_sysprikey.codarq = $codarq";
								if (($codarq == 343 or $codarq == 959 or $codarq == 66) and $mostra == 1) {
									db_logduplos ( "sql4: $sql4" );
								}
								$result_nomecam_pk = db_query ( $sql4 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
								
								$campos_pk = $v_sau_cgserrado;
								
								$executar = '$' . 'GLOBALS["HTTP_POST_VARS"]["' . $v_nomepk . '"]=' . $v_sau_cgserrado . ';';
								eval ( $executar );
								
								$v_campos2 = "";
								for($record_nomecam_pk = 0; $record_nomecam_pk < pg_numrows ( $result_nomecam_pk ); $record_nomecam_pk ++) {
									db_fieldsmemory ( $result_nomecam_pk, $record_nomecam_pk );
									$v_campos2 .= " and $nomecam_pk = " . (strpos ( "-" . $conteudo_pk, "char" ) > 0 ? "'" . $$nomecam_pk . "'" : $$nomecam_pk);
									$campos_pk .= ", " . (strpos ( "-" . $conteudo_pk, "char" ) > 0 ? "'" . $$nomecam_pk . "'" : $$nomecam_pk);
								}
								
								db_logduplos ( "     1 - " . $v_campos . $v_campos2 );
								$result_campos2 = db_query ( $v_campos . $v_campos2 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
								
								if (pg_numrows ( $result_campos2 ) > 0) {
									$sql55 = "select * from $nomearq where $nomecam = $v_sau_cgserrado " . $v_campos2;
									
									$result55 = db_query ( $sql55 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
									if (pg_numrows ( $result55 ) > 0) {
										
										$sql5 = "delete from $nomearq where $nomecam = $v_sau_cgserrado " . $v_campos2;
										echo $nomearq . "\n";
									} else {
										
										$sql5 = "";
									}
								
								} else {
									
									db_logduplos ( "       222" );
									
									$sql5 = "update $nomearq set $nomecam = $v_cgscerto where $nomecam = $v_sau_cgserrado " . $v_campos2;
								
								}
								db_logduplos ( "sql5 = $sql5" );
								
								if (substr ( $sql5, 0, 6 ) == "delete") {
									//
									db_logduplos ( "      5 - $sql5" );
									if ($sql5 != "") {
										$v_log .= $sql5 . "\n";
										$result = db_query ( $sql5 ) or die ( db_logduplos ( "sql: $sql5 \n " . pg_ErrorMessage () ) );
									}
								
								}
								
								//echo "entrou aqui...se não for advog e nem cadescrito";
								db_logduplos ( "entrou aqui...se não for advog e nem cadescrito" );
								//echo "       222\n";
								db_logduplos ( "       222" );
								$sql5 = "update $nomearq set $nomecam = $v_cgscerto where $nomecam = $v_sau_cgserrado " . $v_campos2;
								//echo "      5 - $sql5\n";
								

								db_logduplos ( "      5 - $sql5" );
								if ($sql5 != "") {
									$v_log .= $sql5 . "\n";
									$result = db_query ( $sql5 ) or die ( db_logduplos ( "sql: $sql5 \n " . pg_ErrorMessage () ) );
								}
							
							}
						
						}
					
					} else {
						
						db_logduplos ( "   11 - nao achou pk em " . $nomearq );
						$sql9 = "select $nomecam from $nomearq where $nomecam = $v_sau_cgserrado";
						//	    echo "   12 - " . $sql9 . "\n";
						$result9 = db_query ( $sql9 ) or die ( db_logduplos ( "sql: $sql9 \n" . pg_ErrorMessage () ) );
						$v_log .= "comando executado: $sql9 - " . (pg_numrows ( $result9 ) > 0 ? "encontrou " . pg_numrows ( $result9 ) . " registros" : "nao encontrou nenhum registro");
						
						if (pg_numrows ( $result9 ) > 0) {
							
							$sql9 = "update $nomearq set $nomecam = $v_cgscerto where $nomecam = " . $v_sau_cgserrado;
							db_logduplos ( "     12 - " . $sql9 );
							
							$result9 = db_query ( $sql9 ) or die ( db_logduplos ( "\nsql: $sql9\n" . pg_ErrorMessage () ) );
							if (pg_affected_rows ( $result9 ) == 0) {
								db_logduplos ( "erro ao dar update na tabela $nomearq..." );
								db_logduplos ( "comando: $sql9" );
								exit (1);
							}
							
							if ($result9 == false) {
								db_logduplos ( "erro: $sql9" );
								exit (1);
							}
						}
					
					}
				
				}
				
				$v_comando = "alter table $nomearq enable trigger all";
				$sql = db_query ( $v_comando ) or die ( db_logduplos ( "sql: $v_comando \n " . pg_ErrorMessage () ) );
			
			}
		
		}
		
		//************  inclui este pedaço para incluir na cgsalt ************
		$sqlcgsalt = " insert into ambulatorial.cgs_undalt(
                                      z33_i_seq,
                                      z33_i_cgsund  , z33_v_cgccpf  , z33_v_nome    , z33_v_ender   ,
                                      z33_i_numero  , z33_v_compl   , z33_v_bairro  , z33_v_munic   ,
                                      z33_v_uf      , z33_v_cep     , z33_d_cadast  , z33_v_telef   ,
                                      z33_v_ident   , z30_i_login   , z33_v_telcel  , z33_v_email   ,
                                      z33_d_nasc    , z33_v_sexo    , z33_v_tipoalt , z33_i_loginalt,
                                      z33_v_rotina )  
                   select 
                          nextval('cgs_undalt_z33_i_seq') as z33_i_seq,
                          z01_i_cgsund  , z01_v_cgccpf  , z01_v_nome    , z01_v_ender   ,
                          z01_i_numero  , z01_v_compl   , z01_v_bairro  , z01_v_munic   ,
                          z01_v_uf      , z01_v_cep     , z01_d_cadast  , z01_v_telef   ,
                          z01_v_ident   , z01_i_login   , z01_v_telcel  , z01_v_email   ,
                          z01_d_nasc    , z01_v_sexo    , 'E' , '$s127_i_login',
                          'DUPLOS'                          
                  from cgs_und
                  where z01_i_cgsund = $v_sau_cgserrado";
		
		$v_log .= $sqlcgsalt;
		$result = db_query ( $sqlcgsalt ) or die ( db_logduplos ( "sql: aqui11111111111111 $sqlcgsalt\n" . pg_ErrorMessage () ) );
		//echo "**** incluiu $v_sau_cgserrado na cgsalt ****";
		db_logduplos ( "**** incluiu $v_sau_cgserrado na cgsalt ****" );
		//die("$sqlcgsalt");
		//*********************************
		$sql6 = "delete from cgs_und where z01_i_cgsund = $v_sau_cgserrado";
		$v_log .= $sql6;
		$result = db_query ( $sql6 ) or die ( db_logduplos ( "sql: aquiiiiiiiiiiii $sql6\n" . pg_ErrorMessage () ) );
		
		$sql6 = "delete from cgs where z01_i_numcgs = $v_sau_cgserrado";
		$v_log .= $sql6;
		$result = db_query ( $sql6 ) or die ( db_logduplos ( "sql: $sql6\n" . pg_ErrorMessage () ) );
		
		$v_lognew = addSlashes ( $v_log );
		$sql7 = "insert into sau_cgserradolog values ($s127_i_codigo, $s128_i_numcgs, '$v_lognew');";
		$result = db_query ( $sql7 ) or die ( db_logduplos ( $sql7 . "---- sql: " . pg_ErrorMessage () ) );
	
	}
	$sql8 = "update sau_cgscorreto set s127_b_proc = true where s127_i_codigo = $s127_i_codigo";
	$result = db_query ( $sql8 ) or die ( db_logduplos ( "sql: " . pg_ErrorMessage () ) );
	
	if (! $isTeste) {
		$result = db_query ( "commit;" );
	} else {
		db_logduplos ( "" );
		db_logduplos ( ">>>>>> MODO DE TESTE. Efetuando ROLLBACK na transação! <<<<<<" );
		db_logduplos ( "" );
		
		$result = db_query ( "rollback;" );
	}

} //fim for


//echo "ok...\n";
db_logduplos ( "" );
db_logduplos ( "Processamento concluido com sucesso...\n" );
db_logduplos ( "" );

exit(0);

function db_fieldsmemory1($recordset, $indice, $formatar = "", $mostravar = false) {
	$fm_numfields = pg_numfields ( $recordset );
	for($i = 0; $i < $fm_numfields; $i ++) {
		$matriz [$i] = pg_fieldname ( $recordset, $i );
		global $$matriz [$i];
		$aux = trim ( pg_result ( $recordset, $indice, $matriz [$i] ) );
		if (! empty ( $formatar )) {
			switch (pg_fieldtype ( $recordset, $i )) {
				case "float8" :
				case "float4" :
				case "float" :
					$$matriz [$i] = number_format ( $aux, 2, ".", "" );
					if ($mostravar == true) {
						echo $matriz [$i] . "->" . $$matriz [$i] . "<br>";
					}
					break;
				case "date" :
					if ($aux != "") {
						$data = split ( "-", $aux );
						$$matriz [$i] = $data [2] . "/" . $data [1] . "/" . $data [0];
					} else {
						$$matriz [$i] = "";
					}
					if ($mostravar == true) {
						echo $matriz [$i] . "->" . $$matriz [$i] . "<br>";
					}
					break;
				default :
					$$matriz [$i] = $aux;
					if ($mostravar == true) {
						echo $matriz [$i] . "->" . $$matriz [$i] . "<br>";
					}
					break;
			}
		} else
			switch (pg_fieldtype ( $recordset, $i )) {
				case "date" :
					$datav = split ( "-", $aux );
					$split_data = $matriz [$i] . "_dia";
					global $$split_data;
					$$split_data = @$datav [2];
					if ($mostravar == true) {
						echo $split_data . "->" . $$split_data . "<br";
					}
					$split_data = $matriz [$i] . "_mes";
					global $$split_data;
					$$split_data = @$datav [1];
					if ($mostravar == true) {
						echo $split_data . "->" . $$split_data . "<br>";
					}
					$split_data = $matriz [$i] . "_ano";
					global $$split_data;
					$$split_data = @$datav [0];
					if ($mostravar == true) {
						echo $split_data . "->" . $$split_data . "<br>";
					}
					$$matriz [$i] = $aux;
					if ($mostravar == true) {
						echo $matriz [$i] . "->" . $$matriz [$i] . "<br>";
					}
					break;
				default :
					$$matriz [$i] = $aux;
					if ($mostravar == true) {
						echo $matriz [$i] . "->" . $$matriz [$i] . "<br>";
					}
					break;
			}
	}
}
?>
