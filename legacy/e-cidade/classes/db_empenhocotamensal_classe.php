<?
//MODULO: empenho
//CLASSE DA ENTIDADE empenhocotamensal
class cl_empenhocotamensal {
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
   var $e05_sequencial = 0;
   var $e05_numemp = 0;
   var $e05_mes = 0;
   var $e05_valor = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e05_sequencial = int4 = Código Sequencial
                 e05_numemp = int4 = Empenho
                 e05_mes = int4 = Mês
                 e05_valor = float8 = Valor da Cota
                 ";
   //funcao construtor da classe
   function cl_empenhocotamensal() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empenhocotamensal");
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
       $this->e05_sequencial = ($this->e05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e05_sequencial"]:$this->e05_sequencial);
       $this->e05_numemp = ($this->e05_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e05_numemp"]:$this->e05_numemp);
       $this->e05_mes = ($this->e05_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e05_mes"]:$this->e05_mes);
       $this->e05_valor = ($this->e05_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e05_valor"]:$this->e05_valor);
     }else{
       $this->e05_sequencial = ($this->e05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e05_sequencial"]:$this->e05_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($e05_sequencial){
      $this->atualizacampos();
     if($this->e05_numemp == null ){
       $this->erro_sql = " Campo Empenho não informado.";
       $this->erro_campo = "e05_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e05_mes == null ){
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "e05_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(empty($this->e05_valor)) {
       $this->e05_valor = "0";
     }
//       $this->erro_sql = " Campo Valor da Cota não informado.";
//       $this->erro_campo = "e05_valor";
//       $this->erro_banco = "";
//       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
//       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
//       $this->erro_status = "0";
//       return false;
//     }
     if($e05_sequencial == "" || $e05_sequencial == null ){
       $result = db_query("select nextval('empenhocotamensal_e05_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empenhocotamensal_e05_sequencial_seq do campo: e05_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e05_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empenhocotamensal_e05_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e05_sequencial)){
         $this->erro_sql = " Campo e05_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e05_sequencial = $e05_sequencial;
       }
     }
     if(($this->e05_sequencial == null) || ($this->e05_sequencial == "") ){
       $this->erro_sql = " Campo e05_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empenhocotamensal(
                                       e05_sequencial
                                      ,e05_numemp
                                      ,e05_mes
                                      ,e05_valor
                       )
                values (
                                $this->e05_sequencial
                               ,$this->e05_numemp
                               ,$this->e05_mes
                               ,$this->e05_valor
                      )";

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cotas Mensais dos Empenhos ($this->e05_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cotas Mensais dos Empenhos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cotas Mensais dos Empenhos ($this->e05_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e05_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e05_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21143,'$this->e05_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3807,21143,'','".AddSlashes(pg_result($resaco,0,'e05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3807,21144,'','".AddSlashes(pg_result($resaco,0,'e05_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3807,21145,'','".AddSlashes(pg_result($resaco,0,'e05_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3807,21146,'','".AddSlashes(pg_result($resaco,0,'e05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($e05_sequencial=null) {
      $this->atualizacampos();
     $sql = " update empenhocotamensal set ";
     $virgula = "";
     if(trim($this->e05_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e05_sequencial"])){
       $sql  .= $virgula." e05_sequencial = $this->e05_sequencial ";
       $virgula = ",";
       if(trim($this->e05_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "e05_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e05_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e05_numemp"])){
       $sql  .= $virgula." e05_numemp = $this->e05_numemp ";
       $virgula = ",";
       if(trim($this->e05_numemp) == null ){
         $this->erro_sql = " Campo Empenho não informado.";
         $this->erro_campo = "e05_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e05_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e05_mes"])){
       $sql  .= $virgula." e05_mes = $this->e05_mes ";
       $virgula = ",";
       if(trim($this->e05_mes) == null ){
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "e05_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e05_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e05_valor"])){
       $sql  .= $virgula." e05_valor = $this->e05_valor ";
       $virgula = ",";
       if(trim($this->e05_valor) == null ){
         $this->erro_sql = " Campo Valor da Cota não informado.";
         $this->erro_campo = "e05_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e05_sequencial!=null){
       $sql .= " e05_sequencial = $this->e05_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e05_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21143,'$this->e05_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e05_sequencial"]) || $this->e05_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3807,21143,'".AddSlashes(pg_result($resaco,$conresaco,'e05_sequencial'))."','$this->e05_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e05_numemp"]) || $this->e05_numemp != "")
             $resac = db_query("insert into db_acount values($acount,3807,21144,'".AddSlashes(pg_result($resaco,$conresaco,'e05_numemp'))."','$this->e05_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e05_mes"]) || $this->e05_mes != "")
             $resac = db_query("insert into db_acount values($acount,3807,21145,'".AddSlashes(pg_result($resaco,$conresaco,'e05_mes'))."','$this->e05_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e05_valor"]) || $this->e05_valor != "")
             $resac = db_query("insert into db_acount values($acount,3807,21146,'".AddSlashes(pg_result($resaco,$conresaco,'e05_valor'))."','$this->e05_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cotas Mensais dos Empenhos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cotas Mensais dos Empenhos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($e05_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e05_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21143,'$e05_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3807,21143,'','".AddSlashes(pg_result($resaco,$iresaco,'e05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3807,21144,'','".AddSlashes(pg_result($resaco,$iresaco,'e05_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3807,21145,'','".AddSlashes(pg_result($resaco,$iresaco,'e05_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3807,21146,'','".AddSlashes(pg_result($resaco,$iresaco,'e05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empenhocotamensal
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e05_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e05_sequencial = $e05_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cotas Mensais dos Empenhos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cotas Mensais dos Empenhos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:empenhocotamensal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($e05_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from empenhocotamensal ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empenhocotamensal.e05_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e05_sequencial)) {
         $sql2 .= " where empenhocotamensal.e05_sequencial = $e05_sequencial ";
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
   // funcao do sql
   public function sql_query_file ($e05_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empenhocotamensal ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e05_sequencial)){
         $sql2 .= " where empenhocotamensal.e05_sequencial = $e05_sequencial ";
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
