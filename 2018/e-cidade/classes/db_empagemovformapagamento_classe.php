<?
//MODULO: empenho
//CLASSE DA ENTIDADE empagemovformapagamento
class cl_empagemovformapagamento {
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
   var $e07_sequencial = 0;
   var $e07_formatransmissao = 0;
   var $e07_empagemov = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e07_sequencial = int4 = Código 
                 e07_formatransmissao = int4 = Forma de Transmissão 
                 e07_empagemov = int4 = Código do Movimento 
                 ";
   //funcao construtor da classe
   function cl_empagemovformapagamento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagemovformapagamento");
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
       $this->e07_sequencial = ($this->e07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e07_sequencial"]:$this->e07_sequencial);
       $this->e07_formatransmissao = ($this->e07_formatransmissao == ""?@$GLOBALS["HTTP_POST_VARS"]["e07_formatransmissao"]:$this->e07_formatransmissao);
       $this->e07_empagemov = ($this->e07_empagemov == ""?@$GLOBALS["HTTP_POST_VARS"]["e07_empagemov"]:$this->e07_empagemov);
     }else{
       $this->e07_sequencial = ($this->e07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e07_sequencial"]:$this->e07_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($e07_sequencial){
      $this->atualizacampos();
     if($this->e07_formatransmissao == null ){
       $this->erro_sql = " Campo Forma de Transmissão não informado.";
       $this->erro_campo = "e07_formatransmissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e07_empagemov == null ){
       $this->erro_sql = " Campo Código do Movimento não informado.";
       $this->erro_campo = "e07_empagemov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e07_sequencial == "" || $e07_sequencial == null ){
       $result = db_query("select nextval('empagemovformapagamento_e07_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empagemovformapagamento_e07_sequencial_seq do campo: e07_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e07_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empagemovformapagamento_e07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e07_sequencial)){
         $this->erro_sql = " Campo e07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e07_sequencial = $e07_sequencial;
       }
     }
     if(($this->e07_sequencial == null) || ($this->e07_sequencial == "") ){
       $this->erro_sql = " Campo e07_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagemovformapagamento(
                                       e07_sequencial 
                                      ,e07_formatransmissao 
                                      ,e07_empagemov 
                       )
                values (
                                $this->e07_sequencial 
                               ,$this->e07_formatransmissao 
                               ,$this->e07_empagemov 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "empagemovformapagamento ($this->e07_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "empagemovformapagamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "empagemovformapagamento ($this->e07_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e07_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21951,'$this->e07_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3952,21951,'','".AddSlashes(pg_result($resaco,0,'e07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3952,21953,'','".AddSlashes(pg_result($resaco,0,'e07_formatransmissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3952,21952,'','".AddSlashes(pg_result($resaco,0,'e07_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($e07_sequencial=null) {
      $this->atualizacampos();
     $sql = " update empagemovformapagamento set ";
     $virgula = "";
     if(trim($this->e07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e07_sequencial"])){
       $sql  .= $virgula." e07_sequencial = $this->e07_sequencial ";
       $virgula = ",";
       if(trim($this->e07_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "e07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e07_formatransmissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e07_formatransmissao"])){
       $sql  .= $virgula." e07_formatransmissao = $this->e07_formatransmissao ";
       $virgula = ",";
       if(trim($this->e07_formatransmissao) == null ){
         $this->erro_sql = " Campo Forma de Transmissão não informado.";
         $this->erro_campo = "e07_formatransmissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e07_empagemov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e07_empagemov"])){
       $sql  .= $virgula." e07_empagemov = $this->e07_empagemov ";
       $virgula = ",";
       if(trim($this->e07_empagemov) == null ){
         $this->erro_sql = " Campo Código do Movimento não informado.";
         $this->erro_campo = "e07_empagemov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e07_sequencial!=null){
       $sql .= " e07_sequencial = $this->e07_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e07_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21951,'$this->e07_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e07_sequencial"]) || $this->e07_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3952,21951,'".AddSlashes(pg_result($resaco,$conresaco,'e07_sequencial'))."','$this->e07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e07_formatransmissao"]) || $this->e07_formatransmissao != "")
             $resac = db_query("insert into db_acount values($acount,3952,21953,'".AddSlashes(pg_result($resaco,$conresaco,'e07_formatransmissao'))."','$this->e07_formatransmissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e07_empagemov"]) || $this->e07_empagemov != "")
             $resac = db_query("insert into db_acount values($acount,3952,21952,'".AddSlashes(pg_result($resaco,$conresaco,'e07_empagemov'))."','$this->e07_empagemov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empagemovformapagamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "empagemovformapagamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($e07_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e07_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21951,'$e07_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3952,21951,'','".AddSlashes(pg_result($resaco,$iresaco,'e07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3952,21953,'','".AddSlashes(pg_result($resaco,$iresaco,'e07_formatransmissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3952,21952,'','".AddSlashes(pg_result($resaco,$iresaco,'e07_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empagemovformapagamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e07_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e07_sequencial = $e07_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empagemovformapagamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "empagemovformapagamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empagemovformapagamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($e07_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from empagemovformapagamento ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagemovformapagamento.e07_empagemov";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e07_sequencial)) {
         $sql2 .= " where empagemovformapagamento.e07_sequencial = $e07_sequencial ";
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
   public function sql_query_file ($e07_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empagemovformapagamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e07_sequencial)){
         $sql2 .= " where empagemovformapagamento.e07_sequencial = $e07_sequencial ";
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
