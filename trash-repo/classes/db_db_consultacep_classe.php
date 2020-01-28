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


//MODULO: protocolo
//CLASSE DA ENTIDADE db_consultacep
class cl_db_consultacep {
	// cria variaveis de erro 
	var $rotulo = null;
	var $query_sql = null;
	var $numrows = 0;
	var $erro_status = null;
	var $erro_sql = null;
	var $erro_banco = null;
	var $erro_msg = null;
	var $erro_campo = null;
	var $pagina_retorno = null;

	var $propagar = array ();

	// cria variaveis do arquivo 
	var $cep = 0;
	// cria propriedade com as variaveis do arquivo 
	var $campos = "
	                 db10_codigo = int8 = Código 
	                 db10_munic = varchar(40) = Município 
	                 db10_cep = varchar(8) = Cep 
	                 db10_uf = int8 = UF 
	                 ";
	//funcao construtor da classe 
	function cl_db_consultacep() {
		//classes dos rotulos dos campos
		$this->rotulo = new rotulo("db_consultacep");
		$this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
	}
	//funcao erro 
	function erro($mostra, $retorna) {
		if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
			echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
			if ($retorna == true) {
				echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
			}
		}
	}
	// funcao para atualizar campos
	function atualizacampos($exclusao = false) {
		$this->cep = $this->db10_cep;
	}
	//funcao recordset
	function sql_record($sql) {
		$result = @ db_query($sql);
		if ($result == false) {
			$this->numrows = 0;
			$this->erro_banco = str_replace("\n", "", @ pg_last_error());
			$this->erro_sql = "Erro ao selecionar os registros.";
			$this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
			$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
			$this->erro_status = "0";
			return false;
		}
		$this->numrows = pg_numrows($result);
		if ($this->numrows == 0) {
			$this->erro_banco = "";
			$this->erro_sql = "Dados do Grupo nao Encontrado";
			$this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
			$this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
			$this->erro_status = "0";
			return false;
		}
		return $result;
	}
	///pega o cep e faz a mao
	function buscacep($cep = "", $munic = "", $log = "", $numero = "", $funcao_js = "", $domunic = "") {
		if ($domunic != "") {
			$result = $this->sql_record('select * from db_config where codigo = ' . db_getsession("DB_instit"));
			db_fieldsmemory($result, 0);
			$sql = ("select j29_cep as cep,j14_nome as endereco,'' as bairro,j14_codigo as codigorua from ruascep inner join ruas on j14_codigo = j29_codigo where j29_cep = '$cep'");
			$result = $this->sql_record($sql);
			$campos = split("\|", $funcao_js);
			if ($this->numrows > 0) {
				db_fieldsmemory($result, 0);
				echo "<script>" . $campos[0] . "('" . $GLOBALS['cep'] . "','" . $GLOBALS['endereco'] . "','" . $GLOBALS['munic'] . "','" . $GLOBALS['uf'] . "','" . $GLOBALS['bairro'] . "','" . $GLOBALS['codigorua'] . "')</script>";
			} else {
				$camposql = "db10_cep as cep,'' as endereco,db10_munic as municipio, db12_uf as estado";
				$sql = $this->sql_query($cep, $camposql, "", " inner join db_uf on db10_uf = db12_codigo where db10_cep = '$cep' ");
				$result = $this->sql_record($sql);
				if ($this->numrows > 0) {
					db_fieldsmemory($result, 0);
					echo "<script>" . $campos[0] . "('" . $GLOBALS['cep'] . "','" . $GLOBALS['endereco'] . "','" . $GLOBALS['municipio'] . "','" . $GLOBALS['estado'] . "','','')</script>";
				} else {
					echo "<script>" . $campos[0] . "('','CEP inválido para o município','','','')</script>";
				}
			}
			exit;
		}
		elseif ($cep != "") {
			// die("2");
			/*  $camposql = "db10_cep as cep,'' as endereco,db10_munic as municipio, db12_uf as estado";
			  $sql = $this->sql_query($cep,$camposql,""," inner join db_uf on db10_uf = db12_codigo where db10_cep = '$cep' ");
			  $result = $this->sql_record($sql);
			  $campos = split("\|",$funcao_js);*/
			$sql = "select case when ceplogradouros.cp06_cep is null then ceplocalidades.cp05_cepinicial	                           
				                     else ceplogradouros.cp06_cep
						end as cep,       
						ceplocalidades.cp05_localidades, 
				                ceplocalidades.cp05_sigla,
				                ceplogradouros.cp06_logradouro,
						a.cp01_bairro
				         from ceplocalidades 
					        left join ceplogradouros on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade 
						left join cepbairros a on ceplogradouros.cp06_codbairroinicial = a.cp01_codbairro
				         where ceplogradouros.cp06_cep = '$cep'";

			//die($sql."    sql 1");
			$result = $this->sql_record($sql);
			if ($this->numrows == 0) {

				$sql = "select ceplocalidades.cp05_cepinicial as cep,       
							ceplocalidades.cp05_localidades, 
					                ceplocalidades.cp05_sigla
					         from ceplocalidades 
					         where ceplocalidades.cp05_cepinicial = '$cep'";
				$result = $this->sql_record($sql);
				//die($sql."    sql 2");
				db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $this->propagar);
				exit;
			} else {
			//die($sql."    sql 3");
				db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $this->propagar);
				exit;
			}
			/* 
			 if($this->numrows>0){
			db_fieldsmemory($result,0);
			echo "<script>".$campos[0]."('".$GLOBALS['cep']."','".$GLOBALS['endereco']."','".$GLOBALS['municipio']."','".$GLOBALS['estado']."','','')</script>";
			exit;
			 }else{
			$sql = ("select db11_cep as cep,db11_logradouro as endereco,x.db10_munic as municipio,y.db12_uf as estado,db11_bairro as bairro from db_ceplog inner join db_cepmunic x on x.db10_codigo = db11_codigo inner join db_uf y on x.db10_uf = y.db12_codigo where db11_cep = '$cep'");
			$result = $this->sql_record($sql);
			db_fieldsmemory($result,0);
			echo "<script>".$campos[0]."('".$GLOBALS['cep']."','".$GLOBALS['endereco']."','".$GLOBALS['municipio']."','".$GLOBALS['estado']."','".$GLOBALS['bairro']."','')</script>";
			exit;
			 }
			*/
		}
		elseif ($munic != "") {
			if ($log != "") {
				if ($numero != "") {
					if ($numero % 2) {
						$lado = 'I';
					} else {
						$lado = 'P';
					}

					$sql = "select ceplogradouros.cp06_cep as cep,
						                   case when ceplogradouros.cp06_lado = 'I' then 'Lado Impar'
					                                else case when ceplogradouros.cp06_lado = 'P' then 'Lado Par'
									   else case when ceplogradouros.cp06_lado = 'A' then 'Até o fim'
									           else ''
								           	end	  
									end     
							           end as cp06_lado,
						                ceplogradouros.cp06_logradouro,
								ceplocalidades.cp05_localidades,
								ceplogradouros.cp06_numinicial,
								ceplogradouros.cp06_numfinal,
								cepbairros.cp01_bairro,
								ceplocalidades.cp05_sigla
						            from ceplogradouros
							        inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
								inner join cepbairros on ceplogradouros.cp06_codbairroinicial = cp01_codbairro
						            where ceplocalidades.cp05_localidades ilike '$munic%' 
								and  
							        ceplogradouros.cp06_logradouro like '%$log%'
								and ceplogradouros.cp06_numinicial <= $numero
								and  ceplogradouros.cp06_numfinal >= $numero
								and ceplogradouros.cp06_lado = '$lado'
						            UNION
						            select ceplogradouros.cp06_cep as cep,
						                ceplogradouros.cp06_logradouro,
								case when ceplogradouros.cp06_lado = 'I' then 'Lado Impar'
					                             else case when ceplogradouros.cp06_lado = 'P' then 'Lado Par'
					                                  else case when ceplogradouros.cp06_lado = 'A' then 'Até o fim'
									       else ''
								          end		 
					                             end
					                        end as cp06_lado,
								ceplocalidades.cp05_localidades,
								ceplogradouros.cp06_numinicial,
								ceplogradouros.cp06_numfinal,
								cepbairros.cp01_bairro,
								ceplocalidades.cp05_sigla
						            from ceplogradouros
							        inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
								inner join cepbairros on ceplogradouros.cp06_codbairroinicial = cp01_codbairro
						            where ceplocalidades.cp05_localidades ilike '$munic%' 
								and  
							        ceplogradouros.cp06_logradouro like '%$log%'
								and ceplogradouros.cp06_numinicial <= $numero
								and  ceplogradouros.cp06_numfinal >= $numero
								and ceplogradouros.cp06_lado = 'A'";

				} else {
					/*$sql = ("select db11_cep as cep,db11_logradouro as endereco,x.db10_munic as municipio,y.db12_uf as estado,db11_bairro as bairro from db_ceplog inner join db_cepmunic x on x.db10_codigo = db11_codigo inner join db_uf y on x.db10_uf = y.db12_codigo where db10_munic like '$munic%' and (db11_logsemacento like  '$log%' or  db11_logradouro like '$log%') "); */
					$sql = "select ceplogradouros.cp06_cep as cep,
						                ceplogradouros.cp06_logradouro,						                
								case when ceplogradouros.cp06_lado = 'I' then 'Lado Impar'
					                             else case when ceplogradouros.cp06_lado = 'P' then 'Lado Par'
					                                  else case when ceplogradouros.cp06_lado = 'A' then 'Até o fim'
									       else ''
								          end		 
					                             end
					                        end as cp06_lado,
								ceplocalidades.cp05_localidades,
								ceplogradouros.cp06_numinicial,
								ceplogradouros.cp06_numfinal,
								cepbairros.cp01_bairro,
								ceplocalidades.cp05_sigla
						         from ceplogradouros
							        inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
								left join cepbairros on ceplogradouros.cp06_codbairroinicial = cp01_codbairro
								where ceplocalidades.cp05_localidades like '$munic' 
								     and  
								      ceplogradouros.cp06_logradouro like '%$log%'";
				}

				$result = $this->sql_record($sql);
				//die($sql."    sql 4");
				db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $this->propagar);
				exit;
			} else {
				//$camposql = "db10_cep as cep,'' as endereco,db10_munic as municipio, db12_uf as estado";
				//$sql = $this->sql_query($cep,$camposql,""," inner join db_uf on db10_uf = db12_codigo where db10_munic like '$munic%' ");
				/* $sql = ("select db11_cep as cep,db11_logradouro as endereco,x.db10_munic as municipio,y.db12_uf as estado,db11_bairro as bairro from db_ceplog inner join db_cepmunic x on x.db10_codigo = db11_codigo inner join db_uf y on x.db10_uf = y.db12_codigo where db10_munic like '$munic%'"); */
				$sql = "select case when ceplogradouros.cp06_cep is null then ceplocalidades.cp05_cepinicial	                           
					                     else ceplogradouros.cp06_cep
							end as cep,       
							ceplocalidades.cp05_localidades, 
					                ceplocalidades.cp05_sigla,
					                ceplogradouros.cp06_logradouro,
							a.cp01_bairro
					         from ceplocalidades 
						        left join ceplogradouros on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade 
							left join cepbairros a on ceplogradouros.cp06_codbairroinicial = a.cp01_codbairro
					         where ceplocalidades.cp05_localidades ilike '$munic%'";
				//die($sql."   sql 5");
				$result = $this->sql_record($sql);
//				die($sql);
				db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $this->propagar);
				exit;
			}
		}
		elseif ($log != "") {
			//$sql = ("select db11_cep as cep,db11_logradouro as endereco,x.db10_munic as municipio,y.db12_uf as estado,db11_bairro as bairro from db_ceplog inner join db_cepmunic x on x.db10_codigo = db11_codigo inner join db_uf y on x.db10_uf = y.db12_codigo  where db11_logsemacento like  '$log%' or  db11_logradouro like '$log%' ");
			if ($numero != "") {
				if ($numero % 2) {
					$lado = 'I';
				} else {
					$lado = 'P';
				}

				$sql = "select ceplogradouros.cp06_cep as cep,
					                ceplogradouros.cp06_logradouro,
							case when ceplogradouros.cp06_lado = 'I' then 'Lado Impar'
				                             else case when ceplogradouros.cp06_lado = 'P' then 'Lado Par'
				                                  else case when ceplogradouros.cp06_lado = 'A' then 'Até o fim'
								       else ''
								  end	 
				                             end 
				                        end as cp06_lado,
							ceplocalidades.cp05_localidades,
							ceplogradouros.cp06_numinicial,
							ceplogradouros.cp06_numfinal,
							a.cp01_bairro,
							ceplocalidades.cp05_sigla
					           from ceplogradouros
						        inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
							left join cepbairros a on ceplogradouros.cp06_codbairroinicial = a.cp01_codbairro
					           where  ceplogradouros.cp06_logradouro like '%$log%' 
						          and ceplogradouros.cp06_numinicial <= $numero 
							  and  ceplogradouros.cp06_numfinal >= $numero
						          and ceplogradouros.cp06_lado = '$lado' 
					           UNION
						   select ceplogradouros.cp06_cep as cep,
						          ceplogradouros.cp06_logradouro,
							  case when ceplogradouros.cp06_lado = 'I' then 'Lado Impar'
				                               else case when ceplogradouros.cp06_lado = 'P' then 'Lado Par'
				                                    else case when ceplogradouros.cp06_lado = 'A' then 'Até o fim'
								         else ''
							            end		   
				                               end
				                          end as cp06_lado,
							  ceplocalidades.cp05_localidades,
							  ceplogradouros.cp06_numinicial,
							  ceplogradouros.cp06_numfinal,
							  a.cp01_bairro,
							  ceplocalidades.cp05_sigla
						   from ceplogradouros
						          inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
						          left join cepbairros a on ceplogradouros.cp06_codbairroinicial = a.cp01_codbairro
						   where  ceplogradouros.cp06_logradouro like '%$log%'
						          and ceplogradouros.cp06_numinicial <= $numero
						          and  ceplogradouros.cp06_numfinal >= $numero
						          and ceplogradouros.cp06_lado = 'A'";
				//die($sql."   sql 6");
				$result = $this->sql_record($sql);

			} else {
				$sql = "select ceplogradouros.cp06_cep as cep,
					                ceplogradouros.cp06_logradouro,
							case when ceplogradouros.cp06_lado = 'I' then 'Lado Impar'
				                             else case when ceplogradouros.cp06_lado = 'P' then 'Lado Par'
				                                  else case when ceplogradouros.cp06_lado = 'A' then 'Até o fim'
								       else ''
								  end	 
				                             end
				                        end as cp06_lado,
							ceplocalidades.cp05_localidades,
							ceplogradouros.cp06_numinicial,
							ceplogradouros.cp06_numfinal,
							a.cp01_bairro,
							ceplocalidades.cp05_sigla
					         from ceplogradouros
						        inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
							left join cepbairros a on ceplogradouros.cp06_codbairroinicial = a.cp01_codbairro
					         where  ceplogradouros.cp06_logradouro like '%$log%'";
				//die($sql);
				/*$result = $this->sql_record($sql);
				db_lovrot($sql,15,"()","",$funcao_js);*/
				$result = $this->sql_record($sql);
			}
			//die($sql."  sql 7 ");
			db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $this->propagar);
			exit;
		}
	}
	// funcao do sql 
	function sql_query($cep, $campos = "*", $ordem = null, $dbwhere = "") {
		$sql = "select ";
		if ($campos != "*") {
			$campos_sql = split("#", $campos);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i++) {
				$sql .= $virgula . $campos_sql[$i];
				$virgula = ",";
			}
		} else {
			$sql .= $campos;
		}
		$sql .= " from db_cepmunic ";
		$sql2 = "";
		if ($dbwhere == "") {
			if ($cep != null) {
				$sql2 .= " where db_cepmunic.db10_cep = '$cep'";
			}
		} else
			if ($dbwhere != "") {
				$sql2 = " $dbwhere";
			}
		$sql .= $sql2;
		if ($ordem != null) {
			$sql .= " order by ";
			$campos_sql = split("#", $ordem);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i++) {
				$sql .= $virgula . $campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}
}
?>