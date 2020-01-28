<?
//MODULO: pessoal
//CLASSE DA ENTIDADE lancamentorrapensionista
class cl_lancamentorrapensionista { 
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
   var $rh201_sequencial = 0; 
   var $rh201_lancamentorra = 0; 
   var $rh201_numcgm = 0; 
   var $rh201_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh201_sequencial = int4 = Código Sequencial 
                 rh201_lancamentorra = int4 = Lançamento 
                 rh201_numcgm = int4 = Cgm 
                 rh201_valor = float4 = Valor do Pensionista 
                 ";
   //funcao construtor da classe 
   function cl_lancamentorrapensionista() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lancamentorrapensionista"); 
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
       $this->rh201_sequencial = ($this->rh201_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh201_sequencial"]:$this->rh201_sequencial);
       $this->rh201_lancamentorra = ($this->rh201_lancamentorra == ""?@$GLOBALS["HTTP_POST_VARS"]["rh201_lancamentorra"]:$this->rh201_lancamentorra);
       $this->rh201_numcgm = ($this->rh201_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh201_numcgm"]:$this->rh201_numcgm);
       $this->rh201_valor = ($this->rh201_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh201_valor"]:$this->rh201_valor);
     }else{
       $this->rh201_sequencial = ($this->rh201_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh201_sequencial"]:$this->rh201_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh201_sequencial){ 
      $this->atualizacampos();
     if($this->rh201_lancamentorra == null ){ 
       $this->erro_sql = " Campo Lançamento não informado.";
       $this->erro_campo = "rh201_lancamentorra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh201_numcgm == null ){ 
       $this->erro_sql = " Campo Cgm não informado.";
       $this->erro_campo = "rh201_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh201_valor == null ){ 
       $this->erro_sql = " Campo Valor do Pensionista não informado.";
       $this->erro_campo = "rh201_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh201_sequencial == "" || $rh201_sequencial == null ){
       $result = db_query("select nextval('lancamentorrapensionista_rh201_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lancamentorrapensionista_rh201_sequencial_seq do campo: rh201_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh201_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lancamentorrapensionista_rh201_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh201_sequencial)){
         $this->erro_sql = " Campo rh201_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh201_sequencial = $rh201_sequencial; 
       }
     }
     if(($this->rh201_sequencial == null) || ($this->rh201_sequencial == "") ){ 
       $this->erro_sql = " Campo rh201_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lancamentorrapensionista(
                                       rh201_sequencial 
                                      ,rh201_lancamentorra 
                                      ,rh201_numcgm 
                                      ,rh201_valor 
                       )
                values (
                                $this->rh201_sequencial 
                               ,$this->rh201_lancamentorra 
                               ,$this->rh201_numcgm 
                               ,$this->rh201_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pensionistas do RRA ($this->rh201_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pensionistas do RRA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pensionistas do RRA ($this->rh201_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh201_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh201_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22307,'$this->rh201_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,4018,22307,'','".AddSlashes(pg_result($resaco,0,'rh201_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4018,22308,'','".AddSlashes(pg_result($resaco,0,'rh201_lancamentorra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4018,22309,'','".AddSlashes(pg_result($resaco,0,'rh201_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4018,22310,'','".AddSlashes(pg_result($resaco,0,'rh201_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh201_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update lancamentorrapensionista set ";
     $virgula = "";
     if(trim($this->rh201_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh201_sequencial"])){ 
       $sql  .= $virgula." rh201_sequencial = $this->rh201_sequencial ";
       $virgula = ",";
       if(trim($this->rh201_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "rh201_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh201_lancamentorra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh201_lancamentorra"])){ 
       $sql  .= $virgula." rh201_lancamentorra = $this->rh201_lancamentorra ";
       $virgula = ",";
       if(trim($this->rh201_lancamentorra) == null ){ 
         $this->erro_sql = " Campo Lançamento não informado.";
         $this->erro_campo = "rh201_lancamentorra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh201_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh201_numcgm"])){ 
       $sql  .= $virgula." rh201_numcgm = $this->rh201_numcgm ";
       $virgula = ",";
       if(trim($this->rh201_numcgm) == null ){ 
         $this->erro_sql = " Campo Cgm não informado.";
         $this->erro_campo = "rh201_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh201_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh201_valor"])){ 
       $sql  .= $virgula." rh201_valor = $this->rh201_valor ";
       $virgula = ",";
       if(trim($this->rh201_valor) == null ){ 
         $this->erro_sql = " Campo Valor do Pensionista não informado.";
         $this->erro_campo = "rh201_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh201_sequencial!=null){
       $sql .= " rh201_sequencial = $this->rh201_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh201_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22307,'$this->rh201_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh201_sequencial"]) || $this->rh201_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,4018,22307,'".AddSlashes(pg_result($resaco,$conresaco,'rh201_sequencial'))."','$this->rh201_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh201_lancamentorra"]) || $this->rh201_lancamentorra != "")
             $resac = db_query("insert into db_acount values($acount,4018,22308,'".AddSlashes(pg_result($resaco,$conresaco,'rh201_lancamentorra'))."','$this->rh201_lancamentorra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh201_numcgm"]) || $this->rh201_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,4018,22309,'".AddSlashes(pg_result($resaco,$conresaco,'rh201_numcgm'))."','$this->rh201_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh201_valor"]) || $this->rh201_valor != "")
             $resac = db_query("insert into db_acount values($acount,4018,22310,'".AddSlashes(pg_result($resaco,$conresaco,'rh201_valor'))."','$this->rh201_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pensionistas do RRA não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh201_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Pensionistas do RRA não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh201_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh201_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh201_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh201_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22307,'$rh201_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,4018,22307,'','".AddSlashes(pg_result($resaco,$iresaco,'rh201_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4018,22308,'','".AddSlashes(pg_result($resaco,$iresaco,'rh201_lancamentorra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4018,22309,'','".AddSlashes(pg_result($resaco,$iresaco,'rh201_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4018,22310,'','".AddSlashes(pg_result($resaco,$iresaco,'rh201_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lancamentorrapensionista
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh201_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh201_sequencial = $rh201_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pensionistas do RRA não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh201_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Pensionistas do RRA não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh201_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh201_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:lancamentorrapensionista";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh201_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from lancamentorrapensionista ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = lancamentorrapensionista.rh201_numcgm";
     $sql .= "      inner join lancamentorra  on  lancamentorra.rh173_sequencial = lancamentorrapensionista.rh201_lancamentorra";
     $sql .= "      inner join assentamentorra  on  assentamentorra.h83_assenta = lancamentorra.rh173_assentamentorra";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh201_sequencial)) {
         $sql2 .= " where lancamentorrapensionista.rh201_sequencial = $rh201_sequencial "; 
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
   public function sql_query_file ($rh201_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lancamentorrapensionista ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh201_sequencial)){
         $sql2 .= " where lancamentorrapensionista.rh201_sequencial = $rh201_sequencial "; 
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

  /**
   * @param $iMatricula
   * @param $iMes
   * @param $iAno
   * @return string
   */
  public function sql_query_dirf($iMatricula, $iMes, $iAno) {

    $sSqlDirf  = "select rh201_numcgm,rh201_valor, rh155_mes, rh155_ano";
    $sSqlDirf .= "  from lancamentorrapensionista ";
    $sSqlDirf .= "       inner join pessoal.lancamentorra on rh201_lancamentorra = rh173_sequencial ";
    $sSqlDirf .= "       inner join recursoshumanos.assentamentorra on rh173_assentamentorra = h83_assenta";
    $sSqlDirf .= "       inner join recursoshumanos.assenta         on h16_codigo            = h83_assenta";
    $sSqlDirf .= "       inner join lancamentorraloteregistroponto on rh173_sequencial = rh174_lancamentorra ";
    $sSqlDirf .= "       inner join loteregistroponto on rh155_sequencial = rh174_loteregistroponto ";
    $sSqlDirf .= " where rh155_situacao='C'";
    $sSqlDirf .= " and h16_regist = {$iMatricula} ";
    $sSqlDirf .= " and rh155_mes   = {$iMes} ";
    $sSqlDirf .= " and rh155_ano   = {$iAno} ";
    return $sSqlDirf;
  }
}
