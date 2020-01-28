<?
//MODULO: pessoal
//CLASSE DA ENTIDADE lancamentorraloteregistroponto
class cl_lancamentorraloteregistroponto { 
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
   var $rh174_sequencial = 0; 
   var $rh174_lancamentorra = 0; 
   var $rh174_loteregistroponto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh174_sequencial = int4 = Código do Registro 
                 rh174_lancamentorra = int4 = Código 
                 rh174_loteregistroponto = int4 = Código do Lote de Registros do Ponto 
                 ";
   //funcao construtor da classe 
   function cl_lancamentorraloteregistroponto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lancamentorraloteregistroponto"); 
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
       $this->rh174_sequencial = ($this->rh174_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh174_sequencial"]:$this->rh174_sequencial);
       $this->rh174_lancamentorra = ($this->rh174_lancamentorra == ""?@$GLOBALS["HTTP_POST_VARS"]["rh174_lancamentorra"]:$this->rh174_lancamentorra);
       $this->rh174_loteregistroponto = ($this->rh174_loteregistroponto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh174_loteregistroponto"]:$this->rh174_loteregistroponto);
     }else{
       $this->rh174_sequencial = ($this->rh174_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh174_sequencial"]:$this->rh174_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh174_sequencial){ 
      $this->atualizacampos();
     if($this->rh174_lancamentorra == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "rh174_lancamentorra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh174_loteregistroponto == null ){ 
       $this->erro_sql = " Campo Código do Lote de Registros do Ponto não informado.";
       $this->erro_campo = "rh174_loteregistroponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh174_sequencial == "" || $rh174_sequencial == null ){
       $result = db_query("select nextval('lancamentorraloteregistroponto_rh174_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lancamentorraloteregistroponto_rh174_sequencial_seq do campo: rh174_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh174_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lancamentorraloteregistroponto_rh174_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh174_sequencial)){
         $this->erro_sql = " Campo rh174_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh174_sequencial = $rh174_sequencial; 
       }
     }
     if(($this->rh174_sequencial == null) || ($this->rh174_sequencial == "") ){ 
       $this->erro_sql = " Campo rh174_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lancamentorraloteregistroponto(
                                       rh174_sequencial 
                                      ,rh174_lancamentorra 
                                      ,rh174_loteregistroponto 
                       )
                values (
                                $this->rh174_sequencial 
                               ,$this->rh174_lancamentorra 
                               ,$this->rh174_loteregistroponto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamentos RRA do Registro do Ponto ($this->rh174_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamentos RRA do Registro do Ponto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamentos RRA do Registro do Ponto ($this->rh174_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh174_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh174_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21661,'$this->rh174_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3892,21661,'','".AddSlashes(pg_result($resaco,0,'rh174_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3892,21662,'','".AddSlashes(pg_result($resaco,0,'rh174_lancamentorra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3892,21663,'','".AddSlashes(pg_result($resaco,0,'rh174_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh174_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update lancamentorraloteregistroponto set ";
     $virgula = "";
     if(trim($this->rh174_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh174_sequencial"])){ 
       $sql  .= $virgula." rh174_sequencial = $this->rh174_sequencial ";
       $virgula = ",";
       if(trim($this->rh174_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Registro não informado.";
         $this->erro_campo = "rh174_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh174_lancamentorra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh174_lancamentorra"])){ 
       $sql  .= $virgula." rh174_lancamentorra = $this->rh174_lancamentorra ";
       $virgula = ",";
       if(trim($this->rh174_lancamentorra) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh174_lancamentorra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh174_loteregistroponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh174_loteregistroponto"])){ 
       $sql  .= $virgula." rh174_loteregistroponto = $this->rh174_loteregistroponto ";
       $virgula = ",";
       if(trim($this->rh174_loteregistroponto) == null ){ 
         $this->erro_sql = " Campo Código do Lote de Registros do Ponto não informado.";
         $this->erro_campo = "rh174_loteregistroponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh174_sequencial!=null){
       $sql .= " rh174_sequencial = $this->rh174_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh174_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21661,'$this->rh174_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh174_sequencial"]) || $this->rh174_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3892,21661,'".AddSlashes(pg_result($resaco,$conresaco,'rh174_sequencial'))."','$this->rh174_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh174_lancamentorra"]) || $this->rh174_lancamentorra != "")
             $resac = db_query("insert into db_acount values($acount,3892,21662,'".AddSlashes(pg_result($resaco,$conresaco,'rh174_lancamentorra'))."','$this->rh174_lancamentorra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh174_loteregistroponto"]) || $this->rh174_loteregistroponto != "")
             $resac = db_query("insert into db_acount values($acount,3892,21663,'".AddSlashes(pg_result($resaco,$conresaco,'rh174_loteregistroponto'))."','$this->rh174_loteregistroponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos RRA do Registro do Ponto não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh174_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos RRA do Registro do Ponto não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh174_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh174_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh174_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh174_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21661,'$rh174_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3892,21661,'','".AddSlashes(pg_result($resaco,$iresaco,'rh174_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3892,21662,'','".AddSlashes(pg_result($resaco,$iresaco,'rh174_lancamentorra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3892,21663,'','".AddSlashes(pg_result($resaco,$iresaco,'rh174_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lancamentorraloteregistroponto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh174_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh174_sequencial = $rh174_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos RRA do Registro do Ponto não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh174_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos RRA do Registro do Ponto não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh174_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh174_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:lancamentorraloteregistroponto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh174_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from lancamentorraloteregistroponto ";
     $sql .= "      inner join loteregistroponto  on  loteregistroponto.rh155_sequencial = lancamentorraloteregistroponto.rh174_loteregistroponto";
     $sql .= "      inner join lancamentorra  on  lancamentorra.rh173_sequencial = lancamentorraloteregistroponto.rh174_lancamentorra";
     $sql .= "      inner join db_config  on  db_config.codigo = loteregistroponto.rh155_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = loteregistroponto.rh155_usuario";
     $sql .= "      inner join assentamentorra  on  assentamentorra.h83_assenta = lancamentorra.rh173_assentamentorra";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh174_sequencial)) {
         $sql2 .= " where lancamentorraloteregistroponto.rh174_sequencial = $rh174_sequencial "; 
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
   public function sql_query_file ($rh174_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lancamentorraloteregistroponto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh174_sequencial)){
         $sql2 .= " where lancamentorraloteregistroponto.rh174_sequencial = $rh174_sequencial "; 
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
