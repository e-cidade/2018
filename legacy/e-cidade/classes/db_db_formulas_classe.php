<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE db_formulas
class cl_db_formulas { 
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
   var $db148_sequencial = 0; 
   var $db148_nome = null; 
   var $db148_descricao = null; 
   var $db148_formula = null; 
   var $db148_ambiente = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db148_sequencial = int4 = Sequencial 
                 db148_nome = varchar(40) = Nome 
                 db148_descricao = text = Descrição 
                 db148_formula = text = Fórmula 
                 db148_ambiente = bool = Variável de Ambiente 
                 ";
   //funcao construtor da classe 
   function cl_db_formulas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_formulas"); 
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
       $this->db148_sequencial = ($this->db148_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db148_sequencial"]:$this->db148_sequencial);
       $this->db148_nome = ($this->db148_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["db148_nome"]:$this->db148_nome);
       $this->db148_descricao = ($this->db148_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db148_descricao"]:$this->db148_descricao);
       $this->db148_formula = ($this->db148_formula == ""?@$GLOBALS["HTTP_POST_VARS"]["db148_formula"]:$this->db148_formula);
       $this->db148_ambiente = ($this->db148_ambiente == "f"?@$GLOBALS["HTTP_POST_VARS"]["db148_ambiente"]:$this->db148_ambiente);
     }else{
       $this->db148_sequencial = ($this->db148_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db148_sequencial"]:$this->db148_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db148_sequencial){ 
      $this->atualizacampos();
     if($this->db148_nome == null ){ 
       $this->erro_sql = " Campo Nome não informado.";
       $this->erro_campo = "db148_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db148_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "db148_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db148_formula == null ){ 
       $this->erro_sql = " Campo Fórmula não informado.";
       $this->erro_campo = "db148_formula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db148_ambiente == null ){ 
       $this->erro_sql = " Campo Variável de Ambiente não informado.";
       $this->erro_campo = "db148_ambiente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db148_sequencial == "" || $db148_sequencial == null ){
       $result = db_query("select nextval('db_formulas_db148_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_formulas_db148_sequencial_seq do campo: db148_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db148_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_formulas_db148_sequencial_seq");
       if(empty($this->db148_sequencial)){
       
         if(($result != false) && (pg_result($result,0,0) < $db148_sequencial)){
           $this->erro_sql = " Campo db148_sequencial maior que último número da sequencia.";
           $this->erro_banco = "Sequencia menor que este número.";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }else{
           $this->db148_sequencial = $db148_sequencial; 
         }
       }
     }
     if(($this->db148_sequencial == null) || ($this->db148_sequencial == "") ){ 
       $this->erro_sql = " Campo db148_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_formulas(
                                       db148_sequencial 
                                      ,db148_nome 
                                      ,db148_descricao 
                                      ,db148_formula 
                                      ,db148_ambiente 
                       )
                values (
                                $this->db148_sequencial 
                               ,'$this->db148_nome' 
                               ,'$this->db148_descricao' 
                               ,'$this->db148_formula' 
                               ,'$this->db148_ambiente' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fórmulas  ($this->db148_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fórmulas  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fórmulas  ($this->db148_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db148_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db148_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21208,'$this->db148_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3820,21208,'','".AddSlashes(pg_result($resaco,0,'db148_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3820,21210,'','".AddSlashes(pg_result($resaco,0,'db148_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3820,21211,'','".AddSlashes(pg_result($resaco,0,'db148_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3820,21213,'','".AddSlashes(pg_result($resaco,0,'db148_formula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3820,21215,'','".AddSlashes(pg_result($resaco,0,'db148_ambiente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db148_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_formulas set ";
     $virgula = "";
     if(trim($this->db148_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db148_sequencial"])){ 
       $sql  .= $virgula." db148_sequencial = $this->db148_sequencial ";
       $virgula = ",";
       if(trim($this->db148_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "db148_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db148_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db148_nome"])){ 
       $sql  .= $virgula." db148_nome = '$this->db148_nome' ";
       $virgula = ",";
       if(trim($this->db148_nome) == null ){ 
         $this->erro_sql = " Campo Nome não informado.";
         $this->erro_campo = "db148_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db148_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db148_descricao"])){ 
       $sql  .= $virgula." db148_descricao = '$this->db148_descricao' ";
       $virgula = ",";
       if(trim($this->db148_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "db148_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db148_formula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db148_formula"])){ 
       $sql  .= $virgula." db148_formula = '$this->db148_formula' ";
       $virgula = ",";
       if(trim($this->db148_formula) == null ){ 
         $this->erro_sql = " Campo Fórmula não informado.";
         $this->erro_campo = "db148_formula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db148_ambiente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db148_ambiente"])){ 
       $sql  .= $virgula." db148_ambiente = '$this->db148_ambiente' ";
       $virgula = ",";
       if(trim($this->db148_ambiente) == null ){ 
         $this->erro_sql = " Campo Variável de Ambiente não informado.";
         $this->erro_campo = "db148_ambiente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db148_sequencial!=null){
       $sql .= " db148_sequencial = $this->db148_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db148_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21208,'$this->db148_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db148_sequencial"]) || $this->db148_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3820,21208,'".AddSlashes(pg_result($resaco,$conresaco,'db148_sequencial'))."','$this->db148_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db148_nome"]) || $this->db148_nome != "")
             $resac = db_query("insert into db_acount values($acount,3820,21210,'".AddSlashes(pg_result($resaco,$conresaco,'db148_nome'))."','$this->db148_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db148_descricao"]) || $this->db148_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3820,21211,'".AddSlashes(pg_result($resaco,$conresaco,'db148_descricao'))."','$this->db148_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db148_formula"]) || $this->db148_formula != "")
             $resac = db_query("insert into db_acount values($acount,3820,21213,'".AddSlashes(pg_result($resaco,$conresaco,'db148_formula'))."','$this->db148_formula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db148_ambiente"]) || $this->db148_ambiente != "")
             $resac = db_query("insert into db_acount values($acount,3820,21215,'".AddSlashes(pg_result($resaco,$conresaco,'db148_ambiente'))."','$this->db148_ambiente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fórmulas  não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db148_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fórmulas  não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db148_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db148_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db148_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db148_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21208,'$db148_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3820,21208,'','".AddSlashes(pg_result($resaco,$iresaco,'db148_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3820,21210,'','".AddSlashes(pg_result($resaco,$iresaco,'db148_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3820,21211,'','".AddSlashes(pg_result($resaco,$iresaco,'db148_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3820,21213,'','".AddSlashes(pg_result($resaco,$iresaco,'db148_formula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3820,21215,'','".AddSlashes(pg_result($resaco,$iresaco,'db148_ambiente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_formulas
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db148_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db148_sequencial = $db148_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fórmulas  não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db148_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fórmulas  não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db148_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db148_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_formulas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($db148_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from db_formulas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db148_sequencial)) {
         $sql2 .= " where db_formulas.db148_sequencial = $db148_sequencial "; 
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
   public function sql_query_file ($db148_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_formulas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db148_sequencial)){
         $sql2 .= " where db_formulas.db148_sequencial = $db148_sequencial "; 
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
