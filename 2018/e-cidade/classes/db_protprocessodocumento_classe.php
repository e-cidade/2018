<?
//MODULO: protocolo
//CLASSE DA ENTIDADE protprocessodocumento
class cl_protprocessodocumento { 
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
   var $p01_sequencial = 0; 
   var $p01_protprocesso = 0; 
   var $p01_descricao = null; 
   var $p01_documento = 0; 
   var $p01_nomedocumento = null; 
   var $p01_usuario = 0; 
   var $p01_data_dia = null; 
   var $p01_data_mes = null; 
   var $p01_data_ano = null; 
   var $p01_data = null; 
   var $p01_procandamint = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p01_sequencial = int4 = Sequencial de Documentos 
                 p01_protprocesso = int4 = Número de Controle 
                 p01_descricao = varchar(200) = Descrição 
                 p01_documento = oid = Documento 
                 p01_nomedocumento = varchar(255) = Nome do documento 
                 p01_usuario = int8 = Usuário 
                 p01_data = date = Data 
                 p01_procandamint = int8 = procandamint 
                 ";
   //funcao construtor da classe 
   function cl_protprocessodocumento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("protprocessodocumento"); 
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
       $this->p01_sequencial = ($this->p01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_sequencial"]:$this->p01_sequencial);
       $this->p01_protprocesso = ($this->p01_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_protprocesso"]:$this->p01_protprocesso);
       $this->p01_descricao = ($this->p01_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_descricao"]:$this->p01_descricao);
       $this->p01_documento = ($this->p01_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_documento"]:$this->p01_documento);
       $this->p01_nomedocumento = ($this->p01_nomedocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_nomedocumento"]:$this->p01_nomedocumento);
       $this->p01_usuario = ($this->p01_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_usuario"]:$this->p01_usuario);
       if($this->p01_data == ""){
         $this->p01_data_dia = ($this->p01_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_data_dia"]:$this->p01_data_dia);
         $this->p01_data_mes = ($this->p01_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_data_mes"]:$this->p01_data_mes);
         $this->p01_data_ano = ($this->p01_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_data_ano"]:$this->p01_data_ano);
         if($this->p01_data_dia != ""){
            $this->p01_data = $this->p01_data_ano."-".$this->p01_data_mes."-".$this->p01_data_dia;
         }
       }
       $this->p01_procandamint = ($this->p01_procandamint == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_procandamint"]:$this->p01_procandamint);
     }else{
       $this->p01_sequencial = ($this->p01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p01_sequencial"]:$this->p01_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($p01_sequencial){ 
      $this->atualizacampos();
     if($this->p01_protprocesso == null ){ 
       $this->erro_sql = " Campo Número de Controle não informado.";
       $this->erro_campo = "p01_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p01_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "p01_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p01_documento == null ){ 
       $this->erro_sql = " Campo Documento não informado.";
       $this->erro_campo = "p01_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p01_nomedocumento == null ){ 
       $this->erro_sql = " Campo Nome do documento não informado.";
       $this->erro_campo = "p01_nomedocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p01_usuario == null ){ 
       $this->p01_usuario = "0";
     }
     if($this->p01_data == null ){ 
       $this->p01_data = "null";
     }
     if($this->p01_procandamint == null ){ 
       $this->p01_procandamint = "0";
     }
     if($p01_sequencial == "" || $p01_sequencial == null ){
       $result = db_query("select nextval('protprocessodocumento_p01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: protprocessodocumento_p01_sequencial_seq do campo: p01_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from protprocessodocumento_p01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $p01_sequencial)){
         $this->erro_sql = " Campo p01_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p01_sequencial = $p01_sequencial; 
       }
     }
     if(($this->p01_sequencial == null) || ($this->p01_sequencial == "") ){ 
       $this->erro_sql = " Campo p01_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into protprocessodocumento(
                                       p01_sequencial 
                                      ,p01_protprocesso 
                                      ,p01_descricao 
                                      ,p01_documento 
                                      ,p01_nomedocumento 
                                      ,p01_usuario 
                                      ,p01_data 
                                      ,p01_procandamint 
                       )
                values (
                                $this->p01_sequencial 
                               ,$this->p01_protprocesso 
                               ,'$this->p01_descricao' 
                               ,$this->p01_documento 
                               ,'$this->p01_nomedocumento' 
                               ,$this->p01_usuario 
                               ,".($this->p01_data == "null" || $this->p01_data == ""?"null":"'".$this->p01_data."'")." 
                               ,$this->p01_procandamint 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "protprocessodocumento ($this->p01_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "protprocessodocumento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "protprocessodocumento ($this->p01_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->p01_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p01_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20296,'$this->p01_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3649,20296,'','".AddSlashes(pg_result($resaco,0,'p01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3649,20297,'','".AddSlashes(pg_result($resaco,0,'p01_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3649,20298,'','".AddSlashes(pg_result($resaco,0,'p01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3649,20299,'','".AddSlashes(pg_result($resaco,0,'p01_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3649,20302,'','".AddSlashes(pg_result($resaco,0,'p01_nomedocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3649,1009504,'','".AddSlashes(pg_result($resaco,0,'p01_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3649,1009503,'','".AddSlashes(pg_result($resaco,0,'p01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3649,1009502,'','".AddSlashes(pg_result($resaco,0,'p01_procandamint'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($p01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update protprocessodocumento set ";
     $virgula = "";
     if(trim($this->p01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_sequencial"])){ 
       $sql  .= $virgula." p01_sequencial = $this->p01_sequencial ";
       $virgula = ",";
       if(trim($this->p01_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial de Documentos não informado.";
         $this->erro_campo = "p01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p01_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_protprocesso"])){ 
       $sql  .= $virgula." p01_protprocesso = $this->p01_protprocesso ";
       $virgula = ",";
       if(trim($this->p01_protprocesso) == null ){ 
         $this->erro_sql = " Campo Número de Controle não informado.";
         $this->erro_campo = "p01_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p01_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_descricao"])){ 
       $sql  .= $virgula." p01_descricao = '$this->p01_descricao' ";
       $virgula = ",";
       if(trim($this->p01_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "p01_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p01_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_documento"])){ 
       $sql  .= $virgula." p01_documento = $this->p01_documento ";
       $virgula = ",";
       if(trim($this->p01_documento) == null ){ 
         $this->erro_sql = " Campo Documento não informado.";
         $this->erro_campo = "p01_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p01_nomedocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_nomedocumento"])){ 
       $sql  .= $virgula." p01_nomedocumento = '$this->p01_nomedocumento' ";
       $virgula = ",";
       if(trim($this->p01_nomedocumento) == null ){ 
         $this->erro_sql = " Campo Nome do documento não informado.";
         $this->erro_campo = "p01_nomedocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p01_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_usuario"])){ 
        if(trim($this->p01_usuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["p01_usuario"])){ 
           $this->p01_usuario = "0" ; 
        } 
       $sql  .= $virgula." p01_usuario = $this->p01_usuario ";
       $virgula = ",";
     }
     if(trim($this->p01_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p01_data_dia"] !="") ){ 
       $sql  .= $virgula." p01_data = '$this->p01_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p01_data_dia"])){ 
         $sql  .= $virgula." p01_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->p01_procandamint)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p01_procandamint"])){ 
        if(trim($this->p01_procandamint)=="" && isset($GLOBALS["HTTP_POST_VARS"]["p01_procandamint"])){ 
           $this->p01_procandamint = "0" ; 
        } 
       $sql  .= $virgula." p01_procandamint = $this->p01_procandamint ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($p01_sequencial!=null){
       $sql .= " p01_sequencial = $this->p01_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p01_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20296,'$this->p01_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_sequencial"]) || $this->p01_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3649,20296,'".AddSlashes(pg_result($resaco,$conresaco,'p01_sequencial'))."','$this->p01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_protprocesso"]) || $this->p01_protprocesso != "")
             $resac = db_query("insert into db_acount values($acount,3649,20297,'".AddSlashes(pg_result($resaco,$conresaco,'p01_protprocesso'))."','$this->p01_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_descricao"]) || $this->p01_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3649,20298,'".AddSlashes(pg_result($resaco,$conresaco,'p01_descricao'))."','$this->p01_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_documento"]) || $this->p01_documento != "")
             $resac = db_query("insert into db_acount values($acount,3649,20299,'".AddSlashes(pg_result($resaco,$conresaco,'p01_documento'))."','$this->p01_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_nomedocumento"]) || $this->p01_nomedocumento != "")
             $resac = db_query("insert into db_acount values($acount,3649,20302,'".AddSlashes(pg_result($resaco,$conresaco,'p01_nomedocumento'))."','$this->p01_nomedocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_usuario"]) || $this->p01_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3649,1009504,'".AddSlashes(pg_result($resaco,$conresaco,'p01_usuario'))."','$this->p01_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_data"]) || $this->p01_data != "")
             $resac = db_query("insert into db_acount values($acount,3649,1009503,'".AddSlashes(pg_result($resaco,$conresaco,'p01_data'))."','$this->p01_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p01_procandamint"]) || $this->p01_procandamint != "")
             $resac = db_query("insert into db_acount values($acount,3649,1009502,'".AddSlashes(pg_result($resaco,$conresaco,'p01_procandamint'))."','$this->p01_procandamint',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "protprocessodocumento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "protprocessodocumento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->p01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($p01_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($p01_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20296,'$p01_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3649,20296,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3649,20297,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3649,20298,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3649,20299,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3649,20302,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_nomedocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3649,1009504,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3649,1009503,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3649,1009502,'','".AddSlashes(pg_result($resaco,$iresaco,'p01_procandamint'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from protprocessodocumento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($p01_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " p01_sequencial = $p01_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "protprocessodocumento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "protprocessodocumento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$p01_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:protprocessodocumento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($p01_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from protprocessodocumento ";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = protprocessodocumento.p01_usuario";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = protprocessodocumento.p01_protprocesso";
     $sql .= "      left  join procandamint  on  procandamint.p78_sequencial = protprocessodocumento.p01_procandamint";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = procandamint.p78_usuario";
     $sql .= "      inner join procandam  as a on   a.p61_codandam = procandamint.p78_codandam";
     $sql .= "      inner join tipodespacho  on  tipodespacho.p100_sequencial = procandamint.p78_tipodespacho";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p01_sequencial)) {
         $sql2 .= " where protprocessodocumento.p01_sequencial = $p01_sequencial "; 
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
   public function sql_query_documento_usuario($p01_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") 
   {    

     $sql  = "select {$campos}";
     $sql .= "  from protprocessodocumento ";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = protprocessodocumento.p01_usuario";
     $sql2 = "";
     
     if (empty($dbwhere)) {
       if (!empty($p01_sequencial)) {
         $sql2 .= " where protprocessodocumento.p01_sequencial = $p01_sequencial "; 
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
  public function sql_query_file ($p01_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from protprocessodocumento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p01_sequencial)){
         $sql2 .= " where protprocessodocumento.p01_sequencial = $p01_sequencial "; 
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
