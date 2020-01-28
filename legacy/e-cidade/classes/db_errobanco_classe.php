<?
//MODULO: empenho
//CLASSE DA ENTIDADE errobanco
class cl_errobanco { 
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
   var $e92_sequencia = 0; 
   var $e92_coderro = null; 
   var $e92_descrerro = null; 
   var $e92_processa = 'f'; 
   var $e92_empagetipotransmissao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e92_sequencia = int4 = Sequencia 
                 e92_coderro = varchar(2) = Código 
                 e92_descrerro = varchar(120) = Descrição 
                 e92_processa = bool = Processado 
                 e92_empagetipotransmissao = int4 = Tipo de Transmissão 
                 ";
   //funcao construtor da classe 
   function cl_errobanco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("errobanco"); 
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
       $this->e92_sequencia = ($this->e92_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["e92_sequencia"]:$this->e92_sequencia);
       $this->e92_coderro = ($this->e92_coderro == ""?@$GLOBALS["HTTP_POST_VARS"]["e92_coderro"]:$this->e92_coderro);
       $this->e92_descrerro = ($this->e92_descrerro == ""?@$GLOBALS["HTTP_POST_VARS"]["e92_descrerro"]:$this->e92_descrerro);
       $this->e92_processa = ($this->e92_processa == "f"?@$GLOBALS["HTTP_POST_VARS"]["e92_processa"]:$this->e92_processa);
       $this->e92_empagetipotransmissao = ($this->e92_empagetipotransmissao == ""?@$GLOBALS["HTTP_POST_VARS"]["e92_empagetipotransmissao"]:$this->e92_empagetipotransmissao);
     }else{
       $this->e92_sequencia = ($this->e92_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["e92_sequencia"]:$this->e92_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($e92_sequencia){ 
      $this->atualizacampos();
     if($this->e92_coderro == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "e92_coderro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e92_descrerro == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "e92_descrerro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e92_processa == null ){ 
       $this->erro_sql = " Campo Processado não informado.";
       $this->erro_campo = "e92_processa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e92_empagetipotransmissao == null ){ 
       $this->erro_sql = " Campo Tipo de Transmissão não informado.";
       $this->erro_campo = "e92_empagetipotransmissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e92_sequencia == "" || $e92_sequencia == null ){
       $result = db_query("select nextval('errobanco_e92_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: errobanco_e92_sequencia_seq do campo: e92_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e92_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from errobanco_e92_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $e92_sequencia)){
         $this->erro_sql = " Campo e92_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e92_sequencia = $e92_sequencia; 
       }
     }
     if(($this->e92_sequencia == null) || ($this->e92_sequencia == "") ){ 
       $this->erro_sql = " Campo e92_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into errobanco(
                                       e92_sequencia 
                                      ,e92_coderro 
                                      ,e92_descrerro 
                                      ,e92_processa 
                                      ,e92_empagetipotransmissao 
                       )
                values (
                                $this->e92_sequencia 
                               ,'$this->e92_coderro' 
                               ,'$this->e92_descrerro' 
                               ,'$this->e92_processa' 
                               ,$this->e92_empagetipotransmissao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "errobanco ($this->e92_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "errobanco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "errobanco ($this->e92_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e92_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e92_sequencia  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6254,'$this->e92_sequencia','I')");
         $resac = db_query("insert into db_acount values($acount,1014,6254,'','".AddSlashes(pg_result($resaco,0,'e92_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1014,6253,'','".AddSlashes(pg_result($resaco,0,'e92_coderro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1014,6255,'','".AddSlashes(pg_result($resaco,0,'e92_descrerro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1014,7269,'','".AddSlashes(pg_result($resaco,0,'e92_processa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1014,20690,'','".AddSlashes(pg_result($resaco,0,'e92_empagetipotransmissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e92_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update errobanco set ";
     $virgula = "";
     if(trim($this->e92_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e92_sequencia"])){ 
       $sql  .= $virgula." e92_sequencia = $this->e92_sequencia ";
       $virgula = ",";
       if(trim($this->e92_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencia não informado.";
         $this->erro_campo = "e92_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e92_coderro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e92_coderro"])){ 
       $sql  .= $virgula." e92_coderro = '$this->e92_coderro' ";
       $virgula = ",";
       if(trim($this->e92_coderro) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "e92_coderro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e92_descrerro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e92_descrerro"])){ 
       $sql  .= $virgula." e92_descrerro = '$this->e92_descrerro' ";
       $virgula = ",";
       if(trim($this->e92_descrerro) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "e92_descrerro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e92_processa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e92_processa"])){ 
       $sql  .= $virgula." e92_processa = '$this->e92_processa' ";
       $virgula = ",";
       if(trim($this->e92_processa) == null ){ 
         $this->erro_sql = " Campo Processado não informado.";
         $this->erro_campo = "e92_processa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e92_empagetipotransmissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e92_empagetipotransmissao"])){ 
       $sql  .= $virgula." e92_empagetipotransmissao = $this->e92_empagetipotransmissao ";
       $virgula = ",";
       if(trim($this->e92_empagetipotransmissao) == null ){ 
         $this->erro_sql = " Campo Tipo de Transmissão não informado.";
         $this->erro_campo = "e92_empagetipotransmissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e92_sequencia!=null){
       $sql .= " e92_sequencia = $this->e92_sequencia";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e92_sequencia));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,6254,'$this->e92_sequencia','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e92_sequencia"]) || $this->e92_sequencia != "")
             $resac = db_query("insert into db_acount values($acount,1014,6254,'".AddSlashes(pg_result($resaco,$conresaco,'e92_sequencia'))."','$this->e92_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e92_coderro"]) || $this->e92_coderro != "")
             $resac = db_query("insert into db_acount values($acount,1014,6253,'".AddSlashes(pg_result($resaco,$conresaco,'e92_coderro'))."','$this->e92_coderro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e92_descrerro"]) || $this->e92_descrerro != "")
             $resac = db_query("insert into db_acount values($acount,1014,6255,'".AddSlashes(pg_result($resaco,$conresaco,'e92_descrerro'))."','$this->e92_descrerro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e92_processa"]) || $this->e92_processa != "")
             $resac = db_query("insert into db_acount values($acount,1014,7269,'".AddSlashes(pg_result($resaco,$conresaco,'e92_processa'))."','$this->e92_processa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e92_empagetipotransmissao"]) || $this->e92_empagetipotransmissao != "")
             $resac = db_query("insert into db_acount values($acount,1014,20690,'".AddSlashes(pg_result($resaco,$conresaco,'e92_empagetipotransmissao'))."','$this->e92_empagetipotransmissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "errobanco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e92_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "errobanco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e92_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e92_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e92_sequencia=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($e92_sequencia));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6254,'$e92_sequencia','E')");
           $resac  = db_query("insert into db_acount values($acount,1014,6254,'','".AddSlashes(pg_result($resaco,$iresaco,'e92_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1014,6253,'','".AddSlashes(pg_result($resaco,$iresaco,'e92_coderro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1014,6255,'','".AddSlashes(pg_result($resaco,$iresaco,'e92_descrerro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1014,7269,'','".AddSlashes(pg_result($resaco,$iresaco,'e92_processa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1014,20690,'','".AddSlashes(pg_result($resaco,$iresaco,'e92_empagetipotransmissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from errobanco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e92_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e92_sequencia = $e92_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "errobanco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e92_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "errobanco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e92_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e92_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:errobanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e92_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from errobanco ";
     $sql .= "      inner join empagetipotransmissao  on  empagetipotransmissao.e57_sequencial = errobanco.e92_empagetipotransmissao";
     $sql2 = "";
     if($dbwhere==""){
       if($e92_sequencia!=null ){
         $sql2 .= " where errobanco.e92_sequencia = $e92_sequencia "; 
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
   function sql_query_file ( $e92_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from errobanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($e92_sequencia!=null ){
         $sql2 .= " where errobanco.e92_sequencia = $e92_sequencia "; 
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
   function sql_query_banco ( $e92_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from errobanco ";
     $sql .= "      inner join db_errobanco on db_errobanco.e78_errobanco = errobanco.e92_sequencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($e92_sequencia!=null ){
         $sql2 .= " where errobanco.e92_sequencia = $e92_sequencia "; 
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
}
?>
