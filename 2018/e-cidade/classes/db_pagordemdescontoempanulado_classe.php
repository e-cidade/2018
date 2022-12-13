<?
//MODULO: empenho
//CLASSE DA ENTIDADE pagordemdescontoempanulado
class cl_pagordemdescontoempanulado {
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
   var $e06_sequencial = 0;
   var $e06_empanulado = 0;
   var $e06_pagordemdesconto = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e06_sequencial = int4 = Sequencial
                 e06_empanulado = int4 = Anulação de Empenho
                 e06_pagordemdesconto = int4 = Desconto da Ordem de Pagamento
                 ";
   //funcao construtor da classe
   function cl_pagordemdescontoempanulado() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pagordemdescontoempanulado");
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
       $this->e06_sequencial = ($this->e06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e06_sequencial"]:$this->e06_sequencial);
       $this->e06_empanulado = ($this->e06_empanulado == ""?@$GLOBALS["HTTP_POST_VARS"]["e06_empanulado"]:$this->e06_empanulado);
       $this->e06_pagordemdesconto = ($this->e06_pagordemdesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["e06_pagordemdesconto"]:$this->e06_pagordemdesconto);
     }else{
       $this->e06_empanulado = ($this->e06_empanulado == ""?@$GLOBALS["HTTP_POST_VARS"]["e06_empanulado"]:$this->e06_empanulado);
     }
   }
   // funcao para Inclusão
   function incluir ($e06_sequencial){
      $this->atualizacampos();
     if($this->e06_pagordemdesconto == null ){
       $this->erro_sql = " Campo Desconto da Ordem de Pagamento não informado.";
       $this->erro_campo = "e06_pagordemdesconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(empty($e06_sequencial)){
       $result = db_query("select nextval('pagordemdescontoempanulado_e06_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pagordemdescontoempanulado_e06_sequencial_seq do campo: e06_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e06_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pagordemdescontoempanulado_e06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e06_sequencial)){
         $this->erro_sql = " Campo e06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e06_sequencial = $e06_sequencial;
       }
     }
     if(($this->e06_empanulado == null) || ($this->e06_empanulado == "") ){
       $this->erro_sql = " Campo e06_empanulado não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pagordemdescontoempanulado(
                                       e06_sequencial
                                      ,e06_empanulado
                                      ,e06_pagordemdesconto
                       )
                values (
                                $this->e06_sequencial
                               ,$this->e06_empanulado
                               ,$this->e06_pagordemdesconto
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pagordemdescontoempanulado ($this->e06_empanulado) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pagordemdescontoempanulado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pagordemdescontoempanulado ($this->e06_empanulado) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e06_empanulado;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e06_empanulado  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21516,'$this->e06_empanulado','I')");
         $resac = db_query("insert into db_acount values($acount,3865,21514,'','".AddSlashes(pg_result($resaco,0,'e06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3865,21516,'','".AddSlashes(pg_result($resaco,0,'e06_empanulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3865,21515,'','".AddSlashes(pg_result($resaco,0,'e06_pagordemdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($e06_empanulado=null) {
      $this->atualizacampos();
     $sql = " update pagordemdescontoempanulado set ";
     $virgula = "";
     if(trim($this->e06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e06_sequencial"])){
       $sql  .= $virgula." e06_sequencial = $this->e06_sequencial ";
       $virgula = ",";
       if(trim($this->e06_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "e06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e06_empanulado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e06_empanulado"])){
       $sql  .= $virgula." e06_empanulado = $this->e06_empanulado ";
       $virgula = ",";
       if(trim($this->e06_empanulado) == null ){
         $this->erro_sql = " Campo Anulação de Empenho não informado.";
         $this->erro_campo = "e06_empanulado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e06_pagordemdesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e06_pagordemdesconto"])){
       $sql  .= $virgula." e06_pagordemdesconto = $this->e06_pagordemdesconto ";
       $virgula = ",";
       if(trim($this->e06_pagordemdesconto) == null ){
         $this->erro_sql = " Campo Desconto da Ordem de Pagamento não informado.";
         $this->erro_campo = "e06_pagordemdesconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e06_empanulado!=null){
       $sql .= " e06_empanulado = $this->e06_empanulado";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e06_empanulado));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21516,'$this->e06_empanulado','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e06_sequencial"]) || $this->e06_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3865,21514,'".AddSlashes(pg_result($resaco,$conresaco,'e06_sequencial'))."','$this->e06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e06_empanulado"]) || $this->e06_empanulado != "")
             $resac = db_query("insert into db_acount values($acount,3865,21516,'".AddSlashes(pg_result($resaco,$conresaco,'e06_empanulado'))."','$this->e06_empanulado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e06_pagordemdesconto"]) || $this->e06_pagordemdesconto != "")
             $resac = db_query("insert into db_acount values($acount,3865,21515,'".AddSlashes(pg_result($resaco,$conresaco,'e06_pagordemdesconto'))."','$this->e06_pagordemdesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pagordemdescontoempanulado não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e06_empanulado;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pagordemdescontoempanulado não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e06_empanulado;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e06_empanulado;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($e06_empanulado=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e06_empanulado));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21516,'$e06_empanulado','E')");
           $resac  = db_query("insert into db_acount values($acount,3865,21514,'','".AddSlashes(pg_result($resaco,$iresaco,'e06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3865,21516,'','".AddSlashes(pg_result($resaco,$iresaco,'e06_empanulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3865,21515,'','".AddSlashes(pg_result($resaco,$iresaco,'e06_pagordemdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pagordemdescontoempanulado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e06_empanulado)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e06_empanulado = $e06_empanulado ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pagordemdescontoempanulado não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e06_empanulado;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pagordemdescontoempanulado não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e06_empanulado;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e06_empanulado;
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
        $this->erro_sql   = "Record Vazio na Tabela:pagordemdescontoempanulado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($e06_empanulado = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from pagordemdescontoempanulado ";
     $sql .= "      inner join empanulado  on  empanulado.e94_codanu = pagordemdescontoempanulado.e06_empanulado";
     $sql .= "      inner join pagordemdesconto  on  pagordemdesconto.e34_sequencial = pagordemdescontoempanulado.e06_pagordemdesconto";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empanulado.e94_numemp";
     $sql .= "      left  join empanuladotipo  on  empanuladotipo.e38_sequencial = empanulado.e94_empanuladotipo";
     $sql .= "      inner join pagordem  as a on   a.e50_codord = pagordemdesconto.e34_codord";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e06_empanulado)) {
         $sql2 .= " where pagordemdescontoempanulado.e06_empanulado = $e06_empanulado ";
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
   public function sql_query_file ($e06_empanulado = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pagordemdescontoempanulado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e06_empanulado)){
         $sql2 .= " where pagordemdescontoempanulado.e06_empanulado = $e06_empanulado ";
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

  public function sql_query_itens_empenho($sCampos = "*", $sWhere) {

    $sSql  = "   select {$sCampos} ";
    $sSql .= "     from pagordemdescontoempanulado ";
    $sSql .= "          inner join pagordemdesconto on pagordemdesconto.e34_sequencial = pagordemdescontoempanulado.e06_pagordemdesconto ";
    $sSql .= "          inner join empanulado       on empanulado.e94_codanu           = pagordemdescontoempanulado.e06_empanulado ";
    $sSql .= "          inner join empanuladoitem   on empanuladoitem.e37_empanulado   = empanulado.e94_codanu ";
    $sSql .= "          inner join empempitem       on empempitem.e62_sequencial       = empanuladoitem.e37_empempitem ";
    $sSql .= "          inner join empempenho       on empempenho.e60_numemp           = empempitem.e62_numemp ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

}
