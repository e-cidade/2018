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

//MODULO: escola
//CLASSE DA ENTIDADE turmaacmatricula
class cl_turmaacmatricula {
	// cria variaveis de erro
	var $rotulo     = null;
	var $query_sql  = null;
	var $numrows    = 0;
	var $numrows_incluir = 0;
	var $numrows_alterar = 0;
	var $numrows_excluir = 0;
	var $erro_status= null;
	var $erro_sql   = null;
	var $erro_banco = null;
	var $erro_msg   = null;
	var $erro_campo = null;
	var $pagina_retorno = null;
	// cria variaveis do arquivo
	var $ed269_i_codigo = 0;
	var $ed269_i_turmaac = 0;
	var $ed269_d_data_dia = null;
	var $ed269_d_data_mes = null;
	var $ed269_d_data_ano = null;
	var $ed269_d_data = null;
	var $ed269_aluno = 0;
	var $iTipoAtendimento = 0;
	var $iQuantidadeDeNecessidade = 0;
	var $iCodigoAluno = null;
	// cria propriedade com as variaveis do arquivo
	var $campos = "
			ed269_i_codigo = int8 = Código
			ed269_i_turmaac = int8 = Turma
			ed269_d_data = date = Data de Inclusão
			ed269_aluno = int8 = Código do Aluno
			";
	//funcao construtor da classe
	function cl_turmaacmatricula() {
		//classes dos rotulos dos campos
		$this->rotulo = new rotulo("turmaacmatricula");
		$this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
	}
	//funcao erro
	function erro($mostra,$retorna) {
		if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
			echo "<script>alert(\"".$this->erro_msg."\");</script>";
			if($retorna==true){
				echo "<script>location.href='".$this->pagina_retorno."'</script>";
			}
		}
	}
	// funcao para atualizar campos
	function atualizacampos($exclusao=false) {
		if($exclusao==false){
			$this->ed269_i_codigo = ($this->ed269_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed269_i_codigo"]:$this->ed269_i_codigo);
			$this->ed269_i_turmaac = ($this->ed269_i_turmaac == ""?@$GLOBALS["HTTP_POST_VARS"]["ed269_i_turmaac"]:$this->ed269_i_turmaac);
			if($this->ed269_d_data == ""){
				$this->ed269_d_data_dia = ($this->ed269_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed269_d_data_dia"]:$this->ed269_d_data_dia);
				$this->ed269_d_data_mes = ($this->ed269_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed269_d_data_mes"]:$this->ed269_d_data_mes);
				$this->ed269_d_data_ano = ($this->ed269_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed269_d_data_ano"]:$this->ed269_d_data_ano);
				if($this->ed269_d_data_dia != ""){
					$this->ed269_d_data = $this->ed269_d_data_ano."-".$this->ed269_d_data_mes."-".$this->ed269_d_data_dia;
				}
			}
			$this->ed269_aluno = ($this->ed269_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed269_aluno"]:$this->ed269_aluno);
		}else{
			$this->ed269_i_codigo = ($this->ed269_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed269_i_codigo"]:$this->ed269_i_codigo);
		}
	}
	// funcao para inclusao
	function incluir ($ed269_i_codigo){
		$this->atualizacampos();
		if($this->ed269_i_turmaac == null ){
			$this->erro_sql = " Campo Turma nao Informado.";
			$this->erro_campo = "ed269_i_turmaac";
			$this->erro_banco = "";
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			return false;
		}
		if($this->ed269_d_data == null ){
			$this->erro_sql = " Campo Data de Inclusão não Informado.";
			$this->erro_campo = "ed269_d_data_dia";
			$this->erro_banco = "";
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			return false;
		}

		if($this->ed269_aluno != null ){

			$result = $this->sql_record("SELECT ed47_i_codigo FROM aluno WHERE ed47_i_codigo = {$this->ed269_aluno}");
			if(($result==false)||($this->numrows==0)){
				$this->erro_sql    = " Campo Código do Aluno Inválido.";
				$this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql."\\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "0";
				return false;
			}

			/* Se o tipo de atendimento for 5 o aluno em questão deve possuir uma necessidade especial cadastrada */
			$result = db_query("SELECT ed268_i_tipoatend FROM turmaac WHERE ed268_i_codigo = {$this->ed269_i_turmaac}");
			$iTipoAtendimento = pg_result($result,0,0);
			if($iTipoAtendimento == 5){

				$result = db_query("SELECT COUNT(ed214_i_aluno) FROM alunonecessidade WHERE ed214_i_aluno = {$this->ed269_aluno}");
				$iQuantidadeDeNecessidade = pg_result($result,0,0);
				if($iQuantidadeDeNecessidade == 0){
					$this->erro_sql = " Aluno Informado não possui Nenhuma Necessidade Especial Cadastrada.";
					$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
					$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
					$this->erro_status = "0";
					return false;
				}
			}
		}

		if($this->ed269_aluno == null ){
			$this->erro_sql = " Campo Código do Aluno não Informado.";
			$this->erro_campo = "ed269_aluno";
			$this->erro_banco = "";
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			return false;
		}
		if($ed269_i_codigo == "" || $ed269_i_codigo == null ){
			$result = db_query("select nextval('turmaacmatricula_ed269_i_codigo_seq')");
			if($result==false){
				$this->erro_banco = str_replace("\n","",@pg_last_error());
				$this->erro_sql   = "Verifique o cadastro da sequencia: turmaacmatricula_ed269_i_codigo_seq do campo: ed269_i_codigo";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "0";
				return false;
			}
			$this->ed269_i_codigo = pg_result($result,0,0);
		}else{
			$result = db_query("select last_value from turmaacmatricula_ed269_i_codigo_seq");
			if(($result != false) && (pg_result($result,0,0) < $ed269_i_codigo)){
				$this->erro_sql = " Campo ed269_i_codigo maior que último número da sequencia.";
				$this->erro_banco = "Sequencia menor que este número.";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "0";
				return false;
			}else{
				$this->ed269_i_codigo = $ed269_i_codigo;
			}
		}
		if(($this->ed269_i_codigo == null) || ($this->ed269_i_codigo == "") ){
			$this->erro_sql = " Campo ed269_i_codigo nao declarado.";
			$this->erro_banco = "Chave Primaria zerada.";
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			return false;
		}
		$sql = "insert into turmaacmatricula(
																					ed269_i_codigo
																					,ed269_i_turmaac
																					,ed269_d_data
																					,ed269_aluno
																				)
																 values (
																					$this->ed269_i_codigo
																					,$this->ed269_i_turmaac
																					,".($this->ed269_d_data == "null" || $this->ed269_d_data == ""?"null":"'".$this->ed269_d_data."'")."
																					,$this->ed269_aluno
																				)";
		$result = db_query($sql);
		if($result==false){
			$this->erro_banco = str_replace("\n","",@pg_last_error());
			if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
				$this->erro_sql   = "Matrículas de Turmas com Ativ. Comp. ($this->ed269_i_codigo) nao Incluído. Inclusao Abortada.";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_banco = "Matrículas de Turmas com Ativ. Comp. já Cadastrado";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			}else{
				$this->erro_sql   = "Matrículas de Turmas com Ativ. Comp. ($this->ed269_i_codigo) nao Incluído. Inclusao Abortada.";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			}
			$this->erro_status = "0";
			$this->numrows_incluir= 0;
			return false;
		}
		$this->erro_banco = "";
		$this->erro_sql = "Inclusao efetuada com Sucesso\\n";
		$this->erro_sql .= "Valores : ".$this->ed269_i_codigo;
		$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
		$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
		$this->erro_status = "1";
		$this->numrows_incluir= pg_affected_rows($result);
		$resaco = $this->sql_record($this->sql_query_file($this->ed269_i_codigo));
		if(($resaco!=false)||($this->numrows!=0)){
			$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
			$acount = pg_result($resac,0,0);
			$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
			$resac = db_query("insert into db_acountkey values($acount,13836,'$this->ed269_i_codigo','I')");
			$resac = db_query("insert into db_acount values($acount,2417,13836,'','".AddSlashes(pg_result($resaco,0,'ed269_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
			$resac = db_query("insert into db_acount values($acount,2417,13837,'','".AddSlashes(pg_result($resaco,0,'ed269_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
			$resac = db_query("insert into db_acount values($acount,2417,13839,'','".AddSlashes(pg_result($resaco,0,'ed269_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
			$resac = db_query("insert into db_acount values($acount,2417,19741,'','".AddSlashes(pg_result($resaco,0,'ed269_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
		}
		return true;
	}
	// funcao para alteracao
	function alterar ($ed269_i_codigo=null) {
		$this->atualizacampos();
		$sql = " update turmaacmatricula set ";
		$virgula = "";
		if(trim($this->ed269_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed269_i_codigo"])){
			$sql  .= $virgula." ed269_i_codigo = $this->ed269_i_codigo ";
			$virgula = ",";
			if(trim($this->ed269_i_codigo) == null ){
				$this->erro_sql = " Campo Código não Informado.";
				$this->erro_campo = "ed269_i_codigo";
				$this->erro_banco = "";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "0";
				return false;
			}
		}
		if(trim($this->ed269_i_turmaac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed269_i_turmaac"])){
			$sql  .= $virgula." ed269_i_turmaac = $this->ed269_i_turmaac ";
			$virgula = ",";
			if(trim($this->ed269_i_turmaac) == null ){
				$this->erro_sql = " Campo Turma não Informado.";
				$this->erro_campo = "ed269_i_turmaac";
				$this->erro_banco = "";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "0";
				return false;
			}
		}
		if(trim($this->ed269_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed269_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed269_d_data_dia"] !="") ){
			$sql  .= $virgula." ed269_d_data = '$this->ed269_d_data' ";
			$virgula = ",";
			if(trim($this->ed269_d_data) == null ){
				$this->erro_sql = " Campo Data de Inclusão não Informado.";
				$this->erro_campo = "ed269_d_data_dia";
				$this->erro_banco = "";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "0";
				return false;
			}
		}     else{
			if(isset($GLOBALS["HTTP_POST_VARS"]["ed269_d_data_dia"])){
				$sql  .= $virgula." ed269_d_data = null ";
				$virgula = ",";
				if(trim($this->ed269_d_data) == null ){
					$this->erro_sql = " Campo Data de Inclusão não Informado.";
					$this->erro_campo = "ed269_d_data_dia";
					$this->erro_banco = "";
					$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
					$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
					$this->erro_status = "0";
					return false;
				}
			}
		}
		if(trim($this->ed269_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed269_aluno"])){
			$sql  .= $virgula." ed269_aluno = $this->ed269_aluno ";
			$virgula = ",";
			if(trim($this->ed269_aluno) == null ){
				$this->erro_sql = " Campo Código do Aluno não Informado.";
				$this->erro_campo = "ed269_aluno";
				$this->erro_banco = "";
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "0";
				return false;
			}
		}
		$sql .= " where ";
		if($ed269_i_codigo!=null){
			$sql .= " ed269_i_codigo = $this->ed269_i_codigo";
		}
		$resaco = $this->sql_record($this->sql_query_file($this->ed269_i_codigo));
		if($this->numrows>0){
			for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
				$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
				$acount = pg_result($resac,0,0);
				$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
				$resac = db_query("insert into db_acountkey values($acount,13836,'$this->ed269_i_codigo','A')");
				if(isset($GLOBALS["HTTP_POST_VARS"]["ed269_i_codigo"]) || $this->ed269_i_codigo != "")
					$resac = db_query("insert into db_acount values($acount,2417,13836,'".AddSlashes(pg_result($resaco,$conresaco,'ed269_i_codigo'))."','$this->ed269_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
				if(isset($GLOBALS["HTTP_POST_VARS"]["ed269_i_turmaac"]) || $this->ed269_i_turmaac != "")
					$resac = db_query("insert into db_acount values($acount,2417,13837,'".AddSlashes(pg_result($resaco,$conresaco,'ed269_i_turmaac'))."','$this->ed269_i_turmaac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
				if(isset($GLOBALS["HTTP_POST_VARS"]["ed269_d_data"]) || $this->ed269_d_data != "")
					$resac = db_query("insert into db_acount values($acount,2417,13839,'".AddSlashes(pg_result($resaco,$conresaco,'ed269_d_data'))."','$this->ed269_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
				if(isset($GLOBALS["HTTP_POST_VARS"]["ed269_aluno"]) || $this->ed269_aluno != "")
					$resac = db_query("insert into db_acount values($acount,2417,19741,'".AddSlashes(pg_result($resaco,$conresaco,'ed269_aluno'))."','$this->ed269_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
			}
		}
		$result = db_query($sql);
		if($result==false){
			$this->erro_banco = str_replace("\n","",@pg_last_error());
			$this->erro_sql   = "Matrículas de Turmas com Ativ. Comp. nao Alterado. Alteracao Abortada.\\n";
			$this->erro_sql .= "Valores : ".$this->ed269_i_codigo;
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			$this->numrows_alterar = 0;
			return false;
		}else{
			if(pg_affected_rows($result)==0){
				$this->erro_banco = "";
				$this->erro_sql = "Matrículas de Turmas com Ativ. Comp. nao foi Alterado. Alteracao Executada.\\n";
				$this->erro_sql .= "Valores : ".$this->ed269_i_codigo;
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "1";
				$this->numrows_alterar = 0;
				return true;
			}else{
				$this->erro_banco = "";
				$this->erro_sql = "Alteração efetuada com Sucesso\\n";
				$this->erro_sql .= "Valores : ".$this->ed269_i_codigo;
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "1";
				$this->numrows_alterar = pg_affected_rows($result);
				return true;
			}
		}
	}
	// funcao para exclusao
	function excluir ($ed269_i_codigo=null,$dbwhere=null) {
		if($dbwhere==null || $dbwhere==""){
			$resaco = $this->sql_record($this->sql_query_file($ed269_i_codigo));
		}else{
			$resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
		}
		if(($resaco!=false)||($this->numrows!=0)){
			for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
				$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
				$acount = pg_result($resac,0,0);
				$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
				$resac = db_query("insert into db_acountkey values($acount,13836,'$ed269_i_codigo','E')");
				$resac = db_query("insert into db_acount values($acount,2417,13836,'','".AddSlashes(pg_result($resaco,$iresaco,'ed269_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
				$resac = db_query("insert into db_acount values($acount,2417,13837,'','".AddSlashes(pg_result($resaco,$iresaco,'ed269_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
				$resac = db_query("insert into db_acount values($acount,2417,13839,'','".AddSlashes(pg_result($resaco,$iresaco,'ed269_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
				$resac = db_query("insert into db_acount values($acount,2417,19741,'','".AddSlashes(pg_result($resaco,$iresaco,'ed269_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
			}
		}
		$sql = " delete from turmaacmatricula
				where ";
		$sql2 = "";
		if($dbwhere==null || $dbwhere ==""){
			if($ed269_i_codigo != ""){
				if($sql2!=""){
					$sql2 .= " and ";
				}
				$sql2 .= " ed269_i_codigo = $ed269_i_codigo ";
			}
		}else{
			$sql2 = $dbwhere;
		}
		$result = db_query($sql.$sql2);
		if($result==false){
			$this->erro_banco = str_replace("\n","",@pg_last_error());
			$this->erro_sql   = "Matrículas de Turmas com Ativ. Comp. nao Excluído. Exclusão Abortada.\\n";
			$this->erro_sql .= "Valores : ".$ed269_i_codigo;
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			$this->numrows_excluir = 0;
			return false;
		}else{
			if(pg_affected_rows($result)==0){
				$this->erro_banco = "";
				$this->erro_sql = "Matrículas de Turmas com Ativ. Comp. nao Encontrado. Exclusão não Efetuada.\\n";
				$this->erro_sql .= "Valores : ".$ed269_i_codigo;
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "1";
				$this->numrows_excluir = 0;
				return true;
			}else{
				$this->erro_banco = "";
				$this->erro_sql = "Exclusão efetuada com Sucesso\\n";
				$this->erro_sql .= "Valores : ".$ed269_i_codigo;
				$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
				$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
				$this->erro_status = "1";
				$this->numrows_excluir = pg_affected_rows($result);
				return true;
			}
		}
	}
	// funcao do recordset
	function sql_record($sql) {
		$result = db_query($sql);
		if($result==false){
			$this->numrows    = 0;
			$this->erro_banco = str_replace("\n","",@pg_last_error());
			$this->erro_sql   = "Erro ao selecionar os registros.";
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			return false;
		}
		$this->numrows = pg_numrows($result);
		if($this->numrows==0){
			$this->erro_banco = "";
			$this->erro_sql   = "Record Vazio na Tabela:turmaacmatricula";
			$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
			$this->erro_status = "0";
			return false;
		}
		return $result;
	}
	// funcao do sql
	function sql_query ( $ed269_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
		$sql = "select ";
		if($campos != "*" ){
			$campos_sql = split("#",$campos);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}else{
			$sql .= $campos;
		}
		$sql .= " from turmaacmatricula ";
		$sql .= "      inner join turmaac          on  turmaac.ed268_i_codigo         = turmaacmatricula.ed269_i_turmaac";
		$sql .= "      inner join aluno  	         on  aluno.ed47_i_codigo            = turmaacmatricula.ed269_aluno";
		$sql .= "      inner join escola           on  escola.ed18_i_codigo           = turmaac.ed268_i_escola";
		$sql .= "      inner join turno            on  turno.ed15_i_codigo            = turmaac.ed268_i_turno";
		$sql .= "      left  join sala             on  sala.ed16_i_codigo 		        = turmaac.ed268_i_sala";
		$sql .= "      inner join calendario       on  calendario.ed52_i_codigo       = turmaac.ed268_i_calendario";
		$sql .= "      inner join pais  			     on  pais.ed228_i_codigo 			      = aluno.ed47_i_pais";
		$sql .= "      left  join censouf  		     on  censouf.ed260_i_codigo         = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
		$sql .= "      left  join censomunic       on  censomunic.ed261_i_codigo      = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat";
		$sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg ";
		$sql .= "      left  join alunonecessidade on  alunonecessidade.ed214_i_aluno = aluno.ed47_i_codigo";
		$sql2 = "";
		if($dbwhere==""){
			if($ed269_i_codigo!=null ){
				$sql2 .= " where turmaacmatricula.ed269_i_codigo = $ed269_i_codigo ";
			}
		}else if($dbwhere != ""){
			$sql2 = " where $dbwhere";
		}
		$sql .= $sql2;
		if($ordem != null ){
			$sql .= " order by ";
			$campos_sql = split("#",$ordem);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}
	// funcao do sql
	function sql_query_file ( $ed269_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
		$sql = "select ";
		if($campos != "*" ){
			$campos_sql = split("#",$campos);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}else{
			$sql .= $campos;
		}
		$sql .= " from turmaacmatricula ";
		$sql2 = "";
		if($dbwhere==""){
			if($ed269_i_codigo!=null ){
				$sql2 .= " where turmaacmatricula.ed269_i_codigo = $ed269_i_codigo ";
			}
		}else if($dbwhere != ""){
			$sql2 = " where $dbwhere";
		}
		$sql .= $sql2;
		if($ordem != null ){
			$sql .= " order by ";
			$campos_sql = split("#",$ordem);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}

	function sql_query_censo ( $ed269_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

	  $sql = "select ";
		if($campos != "*" ){
			$campos_sql = split("#",$campos);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}else{
			$sql .= $campos;
		}
		$sql .= " from turmaacmatricula ";
		$sql .= "      inner join turmaac          on turmaac.ed268_i_codigo          = turmaacmatricula.ed269_i_turmaac";
		$sql .= "      inner join matricula        on matricula.ed60_i_aluno          = turmaacmatricula.ed269_aluno";
		$sql .= "      inner join aluno            on aluno.ed47_i_codigo             = turmaacmatricula.ed269_aluno";
		$sql .= "      inner join escola           on escola.ed18_i_codigo            = turmaac.ed268_i_escola";
		$sql .= "      inner join turno            on turno.ed15_i_codigo             = turmaac.ed268_i_turno";
		$sql .= "      left  join sala             on sala.ed16_i_codigo              = turmaac.ed268_i_sala";
		$sql .= "      inner join calendario       on calendario.ed52_i_codigo        = turmaac.ed268_i_calendario";
		$sql .= "      inner join pais             on pais.ed228_i_codigo             = aluno.ed47_i_pais";
		$sql .= "      left  join censouf          on censouf.ed260_i_codigo          = aluno.ed47_i_censoufcert";
		$sql .= "                                 and censouf.ed260_i_codigo          = aluno.ed47_i_censoufnat";
		$sql .= "                                 and censouf.ed260_i_codigo          = aluno.ed47_i_censoufident";
		$sql .= "                                 and censouf.ed260_i_codigo          = aluno.ed47_i_censoufend";
		$sql .= "      left  join censomunic       on censomunic.ed261_i_codigo       = aluno.ed47_i_censomunicend";
		$sql .= "                                 and censomunic.ed261_i_codigo       = aluno.ed47_i_censomuniccert";
		$sql .= "                                 and censomunic.ed261_i_codigo       = aluno.ed47_i_censomunicnat";
		$sql .= "      left  join censoorgemissrg  on censoorgemissrg.ed132_i_codigo  = aluno.ed47_i_censoorgemissrg";
		$sql .= "      left  join turnoreferente   on turnoreferente.ed231_i_turno    = turno.ed15_i_codigo";
		$sql .= "      left  join alunonecessidade on alunonecessidade.ed214_i_aluno  = aluno.ed47_i_codigo";
		$sql2 = "";
		if($dbwhere==""){
			if($ed269_i_codigo!=null ){
				$sql2 .= " where turmaacmatricula.ed269_i_codigo = $ed269_i_codigo ";
			}
		}else if($dbwhere != ""){
			$sql2 = " where $dbwhere";
		}
		$sql .= $sql2;
		if($ordem != null ){
			$sql .= " order by ";
			$campos_sql = split("#",$ordem);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}

	public function sql_query_turma ($ed269_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

	  $sql = "select ";

	  if ($campos != "*" ) {

	    $campos_sql = split("#",$campos);
	    $virgula    = "";

	    for ( $i = 0; $i < sizeof($campos_sql); $i++ ) {

	      $sql     .= $virgula.$campos_sql[$i];
	      $virgula  = ",";
	    }
	  } else {
	    $sql .= $campos;
	  }
	  $sql .= " from turmaacmatricula ";
	  $sql .= "      inner join turmaac    on turmaac.ed268_i_codigo   = turmaacmatricula.ed269_i_turmaac";
	  $sql .= "      inner join turno      on turno.ed15_i_codigo      = turmaac.ed268_i_turno";
	  $sql .= "      inner join calendario on calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
	  $sql .= "      inner join aluno      on aluno.ed47_i_codigo      = turmaacmatricula.ed269_aluno";
	  $sql2 = "";

	  if ( $dbwhere == "") {

	    if ( $ed269_i_codigo != null ) {
	      $sql2 .= " where turmaacmatricula.ed269_i_codigo = $ed269_i_codigo ";
	    }
	  } else if ( $dbwhere != "" ) {
	    $sql2 = " where $dbwhere";
	  }

	  $sql .= $sql2;
	  if ( $ordem != null ) {

	    $sql        .= " order by ";
	    $campos_sql  = split("#",$ordem);
	    $virgula     = "";

	    for ( $i = 0; $i < sizeof($campos_sql); $i++ ) {

	      $sql     .= $virgula.$campos_sql[$i];
	      $virgula  = ",";
	    }
	  }
	  return $sql;
	}


  public function queryDadosAlunoCenso( $ed269_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql  = " select {$campos} ";
    $sql .= "  from turmaacmatricula ";
    $sql .= " join turmaac               on turmaac.ed268_i_codigo         = turmaacmatricula.ed269_i_turmaac";
    $sql .= " join escola                on escola.ed18_i_codigo           = turmaac.ed268_i_escola";
    $sql .= " left join sala             on sala.ed16_i_codigo             = turmaac.ed268_i_sala";
    $sql .= " join calendario            on calendario.ed52_i_codigo       = turmaac.ed268_i_calendario";
    $sql .= " join turno                 on turno.ed15_i_codigo            = turmaac.ed268_i_turno";
    $sql .= " left join turnoreferente   on turnoreferente.ed231_i_turno   = turno.ed15_i_codigo";
    $sql .= " join aluno                 on aluno.ed47_i_codigo            = turmaacmatricula.ed269_aluno";
    $sql .= " join pais                  on pais.ed228_i_codigo            = aluno.ed47_i_pais";
    $sql .= " left join censouf          on censouf.ed260_i_codigo         = aluno.ed47_i_censoufcert";
    $sql .= "                           and censouf.ed260_i_codigo         = aluno.ed47_i_censoufnat";
    $sql .= "                           and censouf.ed260_i_codigo         = aluno.ed47_i_censoufident";
    $sql .= "                           and censouf.ed260_i_codigo         = aluno.ed47_i_censoufend";
    $sql .= " left join censomunic       on censomunic.ed261_i_codigo      = aluno.ed47_i_censomunicend";
    $sql .= "                           and censomunic.ed261_i_codigo      = aluno.ed47_i_censomuniccert";
    $sql .= "                           and censomunic.ed261_i_codigo      = aluno.ed47_i_censomunicnat";
    $sql .= " left join censoorgemissrg  on censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
    $sql .= " left join censocartorio    on censocartorio.ed291_i_codigo   = aluno.ed47_i_censocartorio";
    $sql .= " left join alunonecessidade on alunonecessidade.ed214_i_aluno = aluno.ed47_i_codigo";
    $sql2 = "";

    if (empty($dbwhere)) {

      if (!empty($ed269_i_codigo)){
        $sql2 .= " where turmaacmatricula.ed269_i_codigo = = $ed269_i_codigo ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }
}
?>