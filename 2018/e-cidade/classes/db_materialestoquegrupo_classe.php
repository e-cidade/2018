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

//MODULO: material
//CLASSE DA ENTIDADE materialestoquegrupo
class cl_materialestoquegrupo {
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
   var $m65_sequencial = 0;
   var $m65_db_estruturavalor = 0;
   var $m65_ativo = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m65_sequencial = int4 = Código Sequencial
                 m65_db_estruturavalor = int4 = Código da Estrutura
                 m65_ativo = bool = Grupo Ativo
                 ";
   //funcao construtor da classe
   function cl_materialestoquegrupo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("materialestoquegrupo");
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
       $this->m65_sequencial = ($this->m65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m65_sequencial"]:$this->m65_sequencial);
       $this->m65_db_estruturavalor = ($this->m65_db_estruturavalor == ""?@$GLOBALS["HTTP_POST_VARS"]["m65_db_estruturavalor"]:$this->m65_db_estruturavalor);
       $this->m65_ativo = ($this->m65_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["m65_ativo"]:$this->m65_ativo);
     }else{
       $this->m65_sequencial = ($this->m65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m65_sequencial"]:$this->m65_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m65_sequencial){
      $this->atualizacampos();
     if($this->m65_db_estruturavalor == null ){
       $this->erro_sql = " Campo Código da Estrutura nao Informado.";
       $this->erro_campo = "m65_db_estruturavalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m65_ativo == null ){
       $this->m65_ativo = "false";
     }
     if($m65_sequencial == "" || $m65_sequencial == null ){
       $result = db_query("select nextval('materialestoquegrupo_m65_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: materialestoquegrupo_m65_sequencial_seq do campo: m65_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m65_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from materialestoquegrupo_m65_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m65_sequencial)){
         $this->erro_sql = " Campo m65_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m65_sequencial = $m65_sequencial;
       }
     }
     if(($this->m65_sequencial == null) || ($this->m65_sequencial == "") ){
       $this->erro_sql = " Campo m65_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into materialestoquegrupo(
                                       m65_sequencial
                                      ,m65_db_estruturavalor
                                      ,m65_ativo
                       )
                values (
                                $this->m65_sequencial
                               ,$this->m65_db_estruturavalor
                               ,'$this->m65_ativo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "grupos de Materiais ($this->m65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "grupos de Materiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "grupos de Materiais ($this->m65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m65_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m65_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17969,'$this->m65_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3174,17969,'','".AddSlashes(pg_result($resaco,0,'m65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3174,17970,'','".AddSlashes(pg_result($resaco,0,'m65_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3174,17971,'','".AddSlashes(pg_result($resaco,0,'m65_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m65_sequencial=null) {
      $this->atualizacampos();
     $sql = " update materialestoquegrupo set ";
     $virgula = "";
     if(trim($this->m65_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m65_sequencial"])){
       $sql  .= $virgula." m65_sequencial = $this->m65_sequencial ";
       $virgula = ",";
       if(trim($this->m65_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "m65_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m65_db_estruturavalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m65_db_estruturavalor"])){
       $sql  .= $virgula." m65_db_estruturavalor = $this->m65_db_estruturavalor ";
       $virgula = ",";
       if(trim($this->m65_db_estruturavalor) == null ){
         $this->erro_sql = " Campo Código da Estrutura nao Informado.";
         $this->erro_campo = "m65_db_estruturavalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m65_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m65_ativo"])){
       $sql  .= $virgula." m65_ativo = '$this->m65_ativo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m65_sequencial!=null){
       $sql .= " m65_sequencial = $this->m65_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m65_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17969,'$this->m65_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m65_sequencial"]) || $this->m65_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3174,17969,'".AddSlashes(pg_result($resaco,$conresaco,'m65_sequencial'))."','$this->m65_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m65_db_estruturavalor"]) || $this->m65_db_estruturavalor != "")
           $resac = db_query("insert into db_acount values($acount,3174,17970,'".AddSlashes(pg_result($resaco,$conresaco,'m65_db_estruturavalor'))."','$this->m65_db_estruturavalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m65_ativo"]) || $this->m65_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3174,17971,'".AddSlashes(pg_result($resaco,$conresaco,'m65_ativo'))."','$this->m65_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "grupos de Materiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "grupos de Materiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m65_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m65_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17969,'$m65_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3174,17969,'','".AddSlashes(pg_result($resaco,$iresaco,'m65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3174,17970,'','".AddSlashes(pg_result($resaco,$iresaco,'m65_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3174,17971,'','".AddSlashes(pg_result($resaco,$iresaco,'m65_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from materialestoquegrupo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m65_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m65_sequencial = $m65_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "grupos de Materiais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "grupos de Materiais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m65_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:materialestoquegrupo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $m65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from materialestoquegrupo ";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = materialestoquegrupo.m65_db_estruturavalor";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = db_estruturavalor.db121_db_estrutura";
     $sql2 = "";
     if($dbwhere==""){
       if($m65_sequencial!=null ){
         $sql2 .= " where materialestoquegrupo.m65_sequencial = $m65_sequencial ";
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
   function sql_query_file ( $m65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from materialestoquegrupo ";
     $sql2 = "";
     if($dbwhere==""){
       if($m65_sequencial!=null ){
         $sql2 .= " where materialestoquegrupo.m65_sequencial = $m65_sequencial ";
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

  function sql_query_conta ( $m65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from materialestoquegrupo ";
     $sql .= "      inner join db_estruturavalor          on db121_sequencial         = m65_db_estruturavalor                     ";
     $sql .= "      inner join matparam                   on db121_db_estrutura       = m90_db_estrutura                          ";
     $sql .= "      inner join db_estrutura               on db77_codestrut           = db121_db_estrutura                        ";
     $sql .= "      left  join materialestoquegrupoconta  on m66_materialestoquegrupo = m65_sequencial                            ";
     $sql .= "      left  join conplano                   on m66_codcon               = c60_codcon                                ";
     $sql .= "                                           and m66_anousu               = c60_anousu                                ";
     $sql2 = "";
     if($dbwhere==""){
       if($m65_sequencial!=null ){
         $sql2 .= " where materialestoquegrupo.m65_sequencial = $m65_sequencial ";
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



  function sql_query_contaVPD ( $m65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from materialestoquegrupo ";
  	$sql .= "      inner join db_estruturavalor          on db121_sequencial         = m65_db_estruturavalor                     ";
  	$sql .= "      inner join matparam                   on db121_db_estrutura       = m90_db_estrutura                          ";
  	$sql .= "      inner join db_estrutura               on db77_codestrut           = db121_db_estrutura                        ";
  	$sql .= "      left  join materialestoquegrupoconta  on m66_materialestoquegrupo = m65_sequencial                            ";
  	$sql .= "      left  join conplano                   on m66_codcon               = c60_codcon                                ";
  	$sql .= "                                           and m66_anousu               = c60_anousu                                ";
  	$sql .= "      left  join materialestoquegrupoconta as materialvpd  on materialvpd.m66_materialestoquegrupo = m65_sequencial ";
  	$sql .= "      left  join conplano                  as conplanovpd  on conplanovpd.c60_codcon = materialvpd.m66_codconvpd    ";
  	$sql .= "                                          and materialvpd.m66_anousu = conplanovpd.c60_anousu                       ";

  	$sql2 = "";
  	if($dbwhere==""){
  		if($m65_sequencial!=null ){
  			$sql2 .= " where materialestoquegrupo.m65_sequencial = $m65_sequencial ";
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

  function sql_query_grupoitem ( $m65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

     $sql = "select ";
     $sql .= $campos;

     $sql .= " from materialestoquegrupo ";
     $sql .= "      inner join matmatermaterialestoquegrupo on matmatermaterialestoquegrupo.m68_materialestoquegrupo = materialestoquegrupo.m65_sequencial ";
     $sql .= "      inner join matmater                     on matmater.m60_codmater            = matmatermaterialestoquegrupo.m68_matmater ";
     $sql .= "      inner join matestoque                   on matestoque.m70_codmatmater       = matmater.m60_codmater ";
     $sql .= "      inner join matestoqueitem               on matestoqueitem.m71_codmatestoque = matestoque.m70_codigo ";

     $sql2 = "";
     if($dbwhere==""){
       if($m65_sequencial!=null ){
         $sql2 .= " where materialestoquegrupo.m65_sequencial = $m65_sequencial ";
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

  function sql_query_conta_ano ($m65_sequencial,  $ano, $campos="*",$ordem=null){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = explode("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from materialestoquegrupo ";
    $sql .= "      inner join db_estruturavalor          on db121_sequencial         = m65_db_estruturavalor                     ";
    $sql .= "      inner join matparam                   on db121_db_estrutura       = m90_db_estrutura                          ";
    $sql .= "      inner join db_estrutura               on db77_codestrut           = db121_db_estrutura                        ";
    $sql .= "      left  join materialestoquegrupoconta  on m66_materialestoquegrupo = m65_sequencial                            ";
    $sql .= "                                           and m66_anousu               = {$ano}                            ";
    $sql .= "      left  join conplano                   on c60_codcon = m66_codcon ";
    $sql .= "                                           and c60_anousu  = {$ano}     ";
    $sql2 = "";
    $sql2 = " where m65_sequencial = $m65_sequencial";

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
}
?>