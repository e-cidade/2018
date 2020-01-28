<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhpreponto
class cl_rhpreponto { 
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
   var $rh149_sequencial = 0; 
   var $rh149_instit = 0; 
   var $rh149_regist = 0; 
   var $rh149_rubric = null; 
   var $rh149_valor = 0; 
   var $rh149_quantidade = 0; 
   var $rh149_tipofolha = 0; 
   var $rh149_competencia = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh149_sequencial = int4 = Sequencial da tabela 
                 rh149_instit = int4 = Instituição 
                 rh149_regist = int4 = Matrícula 
                 rh149_rubric = varchar(20) = Rubrica 
                 rh149_valor = float8 = Valor 
                 rh149_quantidade = int4 = Quantidade 
                 rh149_tipofolha = int4 = Tipo de Folha 
                 rh149_competencia = varchar(7) = Competência 
                 ";
   //funcao construtor da classe 
   function cl_rhpreponto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpreponto"); 
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
       $this->rh149_sequencial = ($this->rh149_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_sequencial"]:$this->rh149_sequencial);
       $this->rh149_instit = ($this->rh149_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_instit"]:$this->rh149_instit);
       $this->rh149_regist = ($this->rh149_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_regist"]:$this->rh149_regist);
       $this->rh149_rubric = ($this->rh149_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_rubric"]:$this->rh149_rubric);
       $this->rh149_valor = ($this->rh149_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_valor"]:$this->rh149_valor);
       $this->rh149_quantidade = ($this->rh149_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_quantidade"]:$this->rh149_quantidade);
       $this->rh149_tipofolha = ($this->rh149_tipofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_tipofolha"]:$this->rh149_tipofolha);
       $this->rh149_competencia = ($this->rh149_competencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_competencia"]:$this->rh149_competencia);
     }else{
       $this->rh149_sequencial = ($this->rh149_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh149_sequencial"]:$this->rh149_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh149_sequencial){ 
      $this->atualizacampos();
     if($this->rh149_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh149_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh149_regist == null ){ 
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "rh149_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh149_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh149_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh149_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "rh149_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh149_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "rh149_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh149_tipofolha == null ){ 
       $this->erro_sql = " Campo Tipo de Folha não informado.";
       $this->erro_campo = "rh149_tipofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh149_sequencial == "" || $rh149_sequencial == null ){
       $result = db_query("select nextval('rhpreponto_rh149_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpreponto_rh149_sequencial_seq do campo: rh149_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh149_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpreponto_rh149_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh149_sequencial)){
         $this->erro_sql = " Campo rh149_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh149_sequencial = $rh149_sequencial; 
       }
     }
     if(($this->rh149_sequencial == null) || ($this->rh149_sequencial == "") ){ 
       $this->erro_sql = " Campo rh149_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpreponto(
                                       rh149_sequencial 
                                      ,rh149_instit 
                                      ,rh149_regist 
                                      ,rh149_rubric 
                                      ,rh149_valor 
                                      ,rh149_quantidade 
                                      ,rh149_tipofolha 
                                      ,rh149_competencia 
                       )
                values (
                                $this->rh149_sequencial 
                               ,$this->rh149_instit 
                               ,$this->rh149_regist 
                               ,'$this->rh149_rubric' 
                               ,$this->rh149_valor 
                               ,$this->rh149_quantidade 
                               ,$this->rh149_tipofolha 
                               ,'$this->rh149_competencia' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhpreponto ($this->rh149_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhpreponto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhpreponto ($this->rh149_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh149_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh149_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21107,'$this->rh149_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3766,21107,'','".AddSlashes(pg_result($resaco,0,'rh149_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3766,20923,'','".AddSlashes(pg_result($resaco,0,'rh149_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3766,20924,'','".AddSlashes(pg_result($resaco,0,'rh149_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3766,20925,'','".AddSlashes(pg_result($resaco,0,'rh149_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3766,20926,'','".AddSlashes(pg_result($resaco,0,'rh149_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3766,20927,'','".AddSlashes(pg_result($resaco,0,'rh149_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3766,20928,'','".AddSlashes(pg_result($resaco,0,'rh149_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3766,21132,'','".AddSlashes(pg_result($resaco,0,'rh149_competencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh149_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhpreponto set ";
     $virgula = "";
     if(trim($this->rh149_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_sequencial"])){ 
       $sql  .= $virgula." rh149_sequencial = $this->rh149_sequencial ";
       $virgula = ",";
       if(trim($this->rh149_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "rh149_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh149_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_instit"])){ 
       $sql  .= $virgula." rh149_instit = $this->rh149_instit ";
       $virgula = ",";
       if(trim($this->rh149_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh149_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh149_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_regist"])){ 
       $sql  .= $virgula." rh149_regist = $this->rh149_regist ";
       $virgula = ",";
       if(trim($this->rh149_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "rh149_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh149_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_rubric"])){ 
       $sql  .= $virgula." rh149_rubric = '$this->rh149_rubric' ";
       $virgula = ",";
       if(trim($this->rh149_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh149_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh149_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_valor"])){ 
       $sql  .= $virgula." rh149_valor = $this->rh149_valor ";
       $virgula = ",";
       if(trim($this->rh149_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "rh149_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh149_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_quantidade"])){ 
       $sql  .= $virgula." rh149_quantidade = $this->rh149_quantidade ";
       $virgula = ",";
       if(trim($this->rh149_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "rh149_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh149_tipofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_tipofolha"])){ 
       $sql  .= $virgula." rh149_tipofolha = $this->rh149_tipofolha ";
       $virgula = ",";
       if(trim($this->rh149_tipofolha) == null ){ 
         $this->erro_sql = " Campo Tipo de Folha não informado.";
         $this->erro_campo = "rh149_tipofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh149_competencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh149_competencia"])){ 
       $sql  .= $virgula." rh149_competencia = '$this->rh149_competencia' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh149_sequencial!=null){
       $sql .= " rh149_sequencial = $this->rh149_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh149_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21107,'$this->rh149_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_sequencial"]) || $this->rh149_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3766,21107,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_sequencial'))."','$this->rh149_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_instit"]) || $this->rh149_instit != "")
             $resac = db_query("insert into db_acount values($acount,3766,20923,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_instit'))."','$this->rh149_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_regist"]) || $this->rh149_regist != "")
             $resac = db_query("insert into db_acount values($acount,3766,20924,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_regist'))."','$this->rh149_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_rubric"]) || $this->rh149_rubric != "")
             $resac = db_query("insert into db_acount values($acount,3766,20925,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_rubric'))."','$this->rh149_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_valor"]) || $this->rh149_valor != "")
             $resac = db_query("insert into db_acount values($acount,3766,20926,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_valor'))."','$this->rh149_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_quantidade"]) || $this->rh149_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3766,20927,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_quantidade'))."','$this->rh149_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_tipofolha"]) || $this->rh149_tipofolha != "")
             $resac = db_query("insert into db_acount values($acount,3766,20928,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_tipofolha'))."','$this->rh149_tipofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh149_competencia"]) || $this->rh149_competencia != "")
             $resac = db_query("insert into db_acount values($acount,3766,21132,'".AddSlashes(pg_result($resaco,$conresaco,'rh149_competencia'))."','$this->rh149_competencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhpreponto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh149_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhpreponto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh149_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh149_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh149_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh149_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21107,'$rh149_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3766,21107,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3766,20923,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3766,20924,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3766,20925,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3766,20926,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3766,20927,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3766,20928,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3766,21132,'','".AddSlashes(pg_result($resaco,$iresaco,'rh149_competencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpreponto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh149_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh149_sequencial = $rh149_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhpreponto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh149_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhpreponto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh149_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh149_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpreponto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh149_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpreponto ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpreponto.rh149_instit";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpreponto.rh149_regist";
     $sql .= "      inner join rhtipofolha  on  rhtipofolha.rh142_sequencial = rhpreponto.rh149_tipofolha";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql .= "      inner join rhreajusteparidade  on  rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh149_sequencial)) {
         $sql2 .= " where rhpreponto.rh149_sequencial = $rh149_sequencial "; 
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
   public function sql_query_file ($rh149_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpreponto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh149_sequencial)){
         $sql2 .= " where rhpreponto.rh149_sequencial = $rh149_sequencial "; 
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
