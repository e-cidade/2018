<?
//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancaminscrestosapagar
class cl_conlancaminscrestosapagar {
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
   var $c108_sequencial = 0;
   var $c108_codlan = 0;
   var $c108_inscricaorestosapagar = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c108_sequencial = int4 = Sequencial
                 c108_codlan = int4 = C�digo Lan�amento
                 c108_inscricaorestosapagar = int4 = Sequencial
                 ";
   //funcao construtor da classe
   function cl_conlancaminscrestosapagar() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancaminscrestosapagar");
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
       $this->c108_sequencial = ($this->c108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c108_sequencial"]:$this->c108_sequencial);
       $this->c108_codlan = ($this->c108_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c108_codlan"]:$this->c108_codlan);
       $this->c108_inscricaorestosapagar = ($this->c108_inscricaorestosapagar == ""?@$GLOBALS["HTTP_POST_VARS"]["c108_inscricaorestosapagar"]:$this->c108_inscricaorestosapagar);
     }else{
       $this->c108_sequencial = ($this->c108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c108_sequencial"]:$this->c108_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($c108_sequencial){
      $this->atualizacampos();
     if($this->c108_codlan == null ){
       $this->erro_sql = " Campo C�digo Lan�amento n�o informado.";
       $this->erro_campo = "c108_codlan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c108_inscricaorestosapagar == null ){
       $this->erro_sql = " Campo Inscri��o Restos a Pagar n�o informado.";
       $this->erro_campo = "c108_inscricaorestosapagar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c108_sequencial == "" || $c108_sequencial == null ){
       $result = db_query("select nextval('conlancaminscrestosapagar_c108_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancaminscrestosapagar_c108_sequencial_seq do campo: c108_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c108_sequencial = pg_result($result,0,0);
     }else{

       $result = db_query("select last_value from conlancaminscrestosapagar_c108_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c108_sequencial)){
         $this->erro_sql = " Campo c108_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c108_sequencial = $c108_sequencial;
       }
     }


     if(($this->c108_sequencial == null) || ($this->c108_sequencial == "") ){
       $this->erro_sql = " Campo c108_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancaminscrestosapagar(
                                       c108_sequencial
                                      ,c108_codlan
                                      ,c108_inscricaorestosapagar
                       )
                values (
                                $this->c108_sequencial
                               ,$this->c108_codlan
                               ,$this->c108_inscricaorestosapagar
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->c108_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->c108_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c108_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c108_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19505,'$this->c108_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3467,19505,'','".AddSlashes(pg_result($resaco,0,'c108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3467,19506,'','".AddSlashes(pg_result($resaco,0,'c108_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3467,19507,'','".AddSlashes(pg_result($resaco,0,'c108_inscricaorestosapagar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($c108_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conlancaminscrestosapagar set ";
     $virgula = "";
     if(trim($this->c108_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c108_sequencial"])){
       $sql  .= $virgula." c108_sequencial = $this->c108_sequencial ";
       $virgula = ",";
       if(trim($this->c108_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial n�o informado.";
         $this->erro_campo = "c108_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c108_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c108_codlan"])){
       $sql  .= $virgula." c108_codlan = $this->c108_codlan ";
       $virgula = ",";
       if(trim($this->c108_codlan) == null ){
         $this->erro_sql = " Campo C�digo Lan�amento n�o informado.";
         $this->erro_campo = "c108_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c108_inscricaorestosapagar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c108_inscricaorestosapagar"])){
       $sql  .= $virgula." c108_inscricaorestosapagar = $this->c108_inscricaorestosapagar ";
       $virgula = ",";
       if(trim($this->c108_inscricaorestosapagar) == null ){
         $this->erro_sql = " Campo Sequencial n�o informado.";
         $this->erro_campo = "c108_inscricaorestosapagar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c108_sequencial!=null){
       $sql .= " c108_sequencial = $this->c108_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c108_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19505,'$this->c108_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c108_sequencial"]) || $this->c108_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3467,19505,'".AddSlashes(pg_result($resaco,$conresaco,'c108_sequencial'))."','$this->c108_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c108_codlan"]) || $this->c108_codlan != "")
             $resac = db_query("insert into db_acount values($acount,3467,19506,'".AddSlashes(pg_result($resaco,$conresaco,'c108_codlan'))."','$this->c108_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c108_inscricaorestosapagar"]) || $this->c108_inscricaorestosapagar != "")
             $resac = db_query("insert into db_acount values($acount,3467,19507,'".AddSlashes(pg_result($resaco,$conresaco,'c108_inscricaorestosapagar'))."','$this->c108_inscricaorestosapagar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c108_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($c108_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c108_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19505,'$c108_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3467,19505,'','".AddSlashes(pg_result($resaco,$iresaco,'c108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3467,19506,'','".AddSlashes(pg_result($resaco,$iresaco,'c108_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3467,19507,'','".AddSlashes(pg_result($resaco,$iresaco,'c108_inscricaorestosapagar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancaminscrestosapagar
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c108_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c108_sequencial = $c108_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c108_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:conlancaminscrestosapagar";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($c108_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from conlancaminscrestosapagar ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancaminscrestosapagar.c108_codlan";
     $sql .= "      inner join conlancamdoc  on  conlancamdoc.c71_codlan = conlancam.c70_codlan";
     $sql .= "      inner join inscricaorestosapagar  on  inscricaorestosapagar.c107_sequencial = conlancaminscrestosapagar.c108_inscricaorestosapagar";
     $sql .= "      inner join db_config  on  db_config.codigo = inscricaorestosapagar.c107_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaorestosapagar.c107_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c108_sequencial)) {
         $sql2 .= " where conlancaminscrestosapagar.c108_sequencial = $c108_sequencial ";
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
   public function sql_query_file ($c108_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from conlancaminscrestosapagar ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c108_sequencial)){
         $sql2 .= " where conlancaminscrestosapagar.c108_sequencial = $c108_sequencial ";
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
