<?
//MODULO: pessoal
//CLASSE DA ENTIDADE baserhcadregime
class cl_baserhcadregime { 
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
   var $rh158_sequencial = 0; 
   var $rh158_regime = 0; 
   var $rh158_ano = 0; 
   var $rh158_mes = 0; 
   var $rh158_instit = 0; 
   var $rh158_basesubstituto = null; 
   var $rh158_basesubstituido = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh158_sequencial = int4 = Sequencial da tabela 
                 rh158_regime = int4 = Regime da Base 
                 rh158_ano = int4 = Ano da Competência 
                 rh158_mes = int4 = Mês da Competência 
                 rh158_instit = int4 = Instituição 
                 rh158_basesubstituto = varchar(4) = Substituto 
                 rh158_basesubstituido = varchar(4) = Substituído 
                 ";
   //funcao construtor da classe 
   function cl_baserhcadregime() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("baserhcadregime"); 
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
       $this->rh158_sequencial = ($this->rh158_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_sequencial"]:$this->rh158_sequencial);
       $this->rh158_regime = ($this->rh158_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_regime"]:$this->rh158_regime);
       $this->rh158_ano = ($this->rh158_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_ano"]:$this->rh158_ano);
       $this->rh158_mes = ($this->rh158_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_mes"]:$this->rh158_mes);
       $this->rh158_instit = ($this->rh158_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_instit"]:$this->rh158_instit);
       $this->rh158_basesubstituto = ($this->rh158_basesubstituto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_basesubstituto"]:$this->rh158_basesubstituto);
       $this->rh158_basesubstituido = ($this->rh158_basesubstituido == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_basesubstituido"]:$this->rh158_basesubstituido);
     }else{
       $this->rh158_sequencial = ($this->rh158_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh158_sequencial"]:$this->rh158_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh158_sequencial){ 
      $this->atualizacampos();
     if($this->rh158_regime == null ){ 
       $this->erro_sql = " Campo Regime da Base não informado.";
       $this->erro_campo = "rh158_regime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh158_ano == null ){ 
       $this->erro_sql = " Campo Ano da Competência não informado.";
       $this->erro_campo = "rh158_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh158_mes == null ){ 
       $this->erro_sql = " Campo Mês da Competência não informado.";
       $this->erro_campo = "rh158_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh158_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh158_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh158_basesubstituto == null ){ 
       $this->erro_sql = " Campo Substituto não informado.";
       $this->erro_campo = "rh158_basesubstituto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh158_basesubstituido == null ){ 
       $this->erro_sql = " Campo Substituído não informado.";
       $this->erro_campo = "rh158_basesubstituido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh158_sequencial == "" || $rh158_sequencial == null ){
       $result = db_query("select nextval('baserhcadregime_rh158_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: baserhcadregime_rh158_sequencial_seq do campo: rh158_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh158_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from baserhcadregime_rh158_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh158_sequencial)){
         $this->erro_sql = " Campo rh158_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh158_sequencial = $rh158_sequencial; 
       }
     }
     if(($this->rh158_sequencial == null) || ($this->rh158_sequencial == "") ){ 
       $this->erro_sql = " Campo rh158_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into baserhcadregime(
                                       rh158_sequencial 
                                      ,rh158_regime 
                                      ,rh158_ano 
                                      ,rh158_mes 
                                      ,rh158_instit 
                                      ,rh158_basesubstituto 
                                      ,rh158_basesubstituido 
                       )
                values (
                                $this->rh158_sequencial 
                               ,$this->rh158_regime 
                               ,$this->rh158_ano 
                               ,$this->rh158_mes 
                               ,$this->rh158_instit 
                               ,'$this->rh158_basesubstituto' 
                               ,'$this->rh158_basesubstituido' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->rh158_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->rh158_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh158_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh158_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21154,'$this->rh158_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3809,21154,'','".AddSlashes(pg_result($resaco,0,'rh158_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3809,21155,'','".AddSlashes(pg_result($resaco,0,'rh158_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3809,21156,'','".AddSlashes(pg_result($resaco,0,'rh158_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3809,21157,'','".AddSlashes(pg_result($resaco,0,'rh158_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3809,21159,'','".AddSlashes(pg_result($resaco,0,'rh158_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3809,21173,'','".AddSlashes(pg_result($resaco,0,'rh158_basesubstituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3809,21174,'','".AddSlashes(pg_result($resaco,0,'rh158_basesubstituido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh158_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update baserhcadregime set ";
     $virgula = "";
     if(trim($this->rh158_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh158_sequencial"])){ 
       $sql  .= $virgula." rh158_sequencial = $this->rh158_sequencial ";
       $virgula = ",";
       if(trim($this->rh158_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "rh158_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh158_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh158_regime"])){ 
       $sql  .= $virgula." rh158_regime = $this->rh158_regime ";
       $virgula = ",";
       if(trim($this->rh158_regime) == null ){ 
         $this->erro_sql = " Campo Regime da Base não informado.";
         $this->erro_campo = "rh158_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh158_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh158_ano"])){ 
       $sql  .= $virgula." rh158_ano = $this->rh158_ano ";
       $virgula = ",";
       if(trim($this->rh158_ano) == null ){ 
         $this->erro_sql = " Campo Ano da Competência não informado.";
         $this->erro_campo = "rh158_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh158_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh158_mes"])){ 
       $sql  .= $virgula." rh158_mes = $this->rh158_mes ";
       $virgula = ",";
       if(trim($this->rh158_mes) == null ){ 
         $this->erro_sql = " Campo Mês da Competência não informado.";
         $this->erro_campo = "rh158_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh158_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh158_instit"])){ 
       $sql  .= $virgula." rh158_instit = $this->rh158_instit ";
       $virgula = ",";
       if(trim($this->rh158_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh158_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh158_basesubstituto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh158_basesubstituto"])){ 
       $sql  .= $virgula." rh158_basesubstituto = '$this->rh158_basesubstituto' ";
       $virgula = ",";
       if(trim($this->rh158_basesubstituto) == null ){ 
         $this->erro_sql = " Campo Substituto não informado.";
         $this->erro_campo = "rh158_basesubstituto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh158_basesubstituido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh158_basesubstituido"])){ 
       $sql  .= $virgula." rh158_basesubstituido = '$this->rh158_basesubstituido' ";
       $virgula = ",";
       if(trim($this->rh158_basesubstituido) == null ){ 
         $this->erro_sql = " Campo Substituído não informado.";
         $this->erro_campo = "rh158_basesubstituido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh158_sequencial!=null){
       $sql .= " rh158_sequencial = $this->rh158_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh158_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21154,'$this->rh158_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh158_sequencial"]) || $this->rh158_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3809,21154,'".AddSlashes(pg_result($resaco,$conresaco,'rh158_sequencial'))."','$this->rh158_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh158_regime"]) || $this->rh158_regime != "")
             $resac = db_query("insert into db_acount values($acount,3809,21155,'".AddSlashes(pg_result($resaco,$conresaco,'rh158_regime'))."','$this->rh158_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh158_ano"]) || $this->rh158_ano != "")
             $resac = db_query("insert into db_acount values($acount,3809,21156,'".AddSlashes(pg_result($resaco,$conresaco,'rh158_ano'))."','$this->rh158_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh158_mes"]) || $this->rh158_mes != "")
             $resac = db_query("insert into db_acount values($acount,3809,21157,'".AddSlashes(pg_result($resaco,$conresaco,'rh158_mes'))."','$this->rh158_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh158_instit"]) || $this->rh158_instit != "")
             $resac = db_query("insert into db_acount values($acount,3809,21159,'".AddSlashes(pg_result($resaco,$conresaco,'rh158_instit'))."','$this->rh158_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh158_basesubstituto"]) || $this->rh158_basesubstituto != "")
             $resac = db_query("insert into db_acount values($acount,3809,21173,'".AddSlashes(pg_result($resaco,$conresaco,'rh158_basesubstituto'))."','$this->rh158_basesubstituto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh158_basesubstituido"]) || $this->rh158_basesubstituido != "")
             $resac = db_query("insert into db_acount values($acount,3809,21174,'".AddSlashes(pg_result($resaco,$conresaco,'rh158_basesubstituido'))."','$this->rh158_basesubstituido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh158_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh158_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh158_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21154,'$rh158_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3809,21154,'','".AddSlashes(pg_result($resaco,$iresaco,'rh158_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3809,21155,'','".AddSlashes(pg_result($resaco,$iresaco,'rh158_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3809,21156,'','".AddSlashes(pg_result($resaco,$iresaco,'rh158_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3809,21157,'','".AddSlashes(pg_result($resaco,$iresaco,'rh158_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3809,21159,'','".AddSlashes(pg_result($resaco,$iresaco,'rh158_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3809,21173,'','".AddSlashes(pg_result($resaco,$iresaco,'rh158_basesubstituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3809,21174,'','".AddSlashes(pg_result($resaco,$iresaco,'rh158_basesubstituido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from baserhcadregime
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh158_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh158_sequencial = $rh158_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh158_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
      
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
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
        $this->erro_sql   = "Record Vazio na Tabela:baserhcadregime";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh158_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from baserhcadregime ";
     $sql .= "      inner join bases substituido  on substituido.r08_anousu  = baserhcadregime.rh158_ano 
                                                 and substituido.r08_mesusu  = baserhcadregime.rh158_mes 
                                                 and substituido.r08_instit  = baserhcadregime.rh158_instit 
                                                 and substituido.r08_codigo  = baserhcadregime.rh158_basesubstituido ";
     $sql .= "      inner join bases substituto   on substituto.r08_anousu   = baserhcadregime.rh158_ano 
                                                 and substituto.r08_mesusu   = baserhcadregime.rh158_mes 
                                                 and substituto.r08_instit   = baserhcadregime.rh158_instit 
                                                 and substituto.r08_codigo   = baserhcadregime.rh158_basesubstituto ";
     $sql .= "      inner join rhcadregime        on rhcadregime.rh52_regime = baserhcadregime.rh158_regime         ";
     $sql .= "      inner join db_config          on db_config.codigo = baserhcadregime.rh158_instit                ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh158_sequencial)) {
         $sql2 .= " where baserhcadregime.rh158_sequencial = $rh158_sequencial "; 
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
   public function sql_query_file ($rh158_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from baserhcadregime ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh158_sequencial)){
         $sql2 .= " where baserhcadregime.rh158_sequencial = $rh158_sequencial "; 
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
