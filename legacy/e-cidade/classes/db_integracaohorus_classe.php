<?
//MODULO: farmacia
//CLASSE DA ENTIDADE integracaohorus
class cl_integracaohorus { 
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
   var $fa59_codigo = 0; 
   var $fa59_usuario = 0; 
   var $fa59_mesreferente = 0; 
   var $fa59_anoreferente = 0; 
   var $fa59_tipoarquivo = 0; 
   var $fa59_situacaohorus = 0; 
   var $fa59_db_depart = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa59_codigo = int4 = Código 
                 fa59_usuario = int4 = Usuário 
                 fa59_mesreferente = int4 = Mês 
                 fa59_anoreferente = int4 = Ano 
                 fa59_tipoarquivo = int4 = Arquivo 
                 fa59_situacaohorus = int4 = Situação Hórus 
                 fa59_db_depart = int4 = Departamento 
                 ";
   //funcao construtor da classe 
   function cl_integracaohorus() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("integracaohorus"); 
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
       $this->fa59_codigo = ($this->fa59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_codigo"]:$this->fa59_codigo);
       $this->fa59_usuario = ($this->fa59_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_usuario"]:$this->fa59_usuario);
       $this->fa59_mesreferente = ($this->fa59_mesreferente == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_mesreferente"]:$this->fa59_mesreferente);
       $this->fa59_anoreferente = ($this->fa59_anoreferente == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_anoreferente"]:$this->fa59_anoreferente);
       $this->fa59_tipoarquivo = ($this->fa59_tipoarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_tipoarquivo"]:$this->fa59_tipoarquivo);
       $this->fa59_situacaohorus = ($this->fa59_situacaohorus == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_situacaohorus"]:$this->fa59_situacaohorus);
       $this->fa59_db_depart = ($this->fa59_db_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_db_depart"]:$this->fa59_db_depart);
     }else{
       $this->fa59_codigo = ($this->fa59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa59_codigo"]:$this->fa59_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($fa59_codigo){ 
      $this->atualizacampos();
     if($this->fa59_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "fa59_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa59_mesreferente == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "fa59_mesreferente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa59_anoreferente == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "fa59_anoreferente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa59_tipoarquivo == null ){ 
       $this->erro_sql = " Campo Arquivo não informado.";
       $this->erro_campo = "fa59_tipoarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa59_situacaohorus == null ){ 
       $this->erro_sql = " Campo Situação Hórus não informado.";
       $this->erro_campo = "fa59_situacaohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa59_db_depart == null ){ 
       $this->erro_sql = " Campo Departamento não informado.";
       $this->erro_campo = "fa59_db_depart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa59_codigo == "" || $fa59_codigo == null ){
       $result = db_query("select nextval('integracaohorus_fa59_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: integracaohorus_fa59_codigo_seq do campo: fa59_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa59_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from integracaohorus_fa59_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa59_codigo)){
         $this->erro_sql = " Campo fa59_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa59_codigo = $fa59_codigo; 
       }
     }
     if(($this->fa59_codigo == null) || ($this->fa59_codigo == "") ){ 
       $this->erro_sql = " Campo fa59_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into integracaohorus(
                                       fa59_codigo 
                                      ,fa59_usuario 
                                      ,fa59_mesreferente 
                                      ,fa59_anoreferente 
                                      ,fa59_tipoarquivo 
                                      ,fa59_situacaohorus 
                                      ,fa59_db_depart 
                       )
                values (
                                $this->fa59_codigo 
                               ,$this->fa59_usuario 
                               ,$this->fa59_mesreferente 
                               ,$this->fa59_anoreferente 
                               ,$this->fa59_tipoarquivo 
                               ,$this->fa59_situacaohorus 
                               ,$this->fa59_db_depart 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "integracaohorus ($this->fa59_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "integracaohorus já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "integracaohorus ($this->fa59_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa59_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa59_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21293,'$this->fa59_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3837,21293,'','".AddSlashes(pg_result($resaco,0,'fa59_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3837,21294,'','".AddSlashes(pg_result($resaco,0,'fa59_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3837,21296,'','".AddSlashes(pg_result($resaco,0,'fa59_mesreferente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3837,21299,'','".AddSlashes(pg_result($resaco,0,'fa59_anoreferente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3837,21297,'','".AddSlashes(pg_result($resaco,0,'fa59_tipoarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3837,21519,'','".AddSlashes(pg_result($resaco,0,'fa59_situacaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3837,21579,'','".AddSlashes(pg_result($resaco,0,'fa59_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($fa59_codigo=null) { 
      $this->atualizacampos();
     $sql = " update integracaohorus set ";
     $virgula = "";
     if(trim($this->fa59_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa59_codigo"])){ 
       $sql  .= $virgula." fa59_codigo = $this->fa59_codigo ";
       $virgula = ",";
       if(trim($this->fa59_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "fa59_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa59_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa59_usuario"])){ 
       $sql  .= $virgula." fa59_usuario = $this->fa59_usuario ";
       $virgula = ",";
       if(trim($this->fa59_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "fa59_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa59_mesreferente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa59_mesreferente"])){ 
       $sql  .= $virgula." fa59_mesreferente = $this->fa59_mesreferente ";
       $virgula = ",";
       if(trim($this->fa59_mesreferente) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "fa59_mesreferente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa59_anoreferente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa59_anoreferente"])){ 
       $sql  .= $virgula." fa59_anoreferente = $this->fa59_anoreferente ";
       $virgula = ",";
       if(trim($this->fa59_anoreferente) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "fa59_anoreferente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa59_tipoarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa59_tipoarquivo"])){ 
       $sql  .= $virgula." fa59_tipoarquivo = $this->fa59_tipoarquivo ";
       $virgula = ",";
       if(trim($this->fa59_tipoarquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo não informado.";
         $this->erro_campo = "fa59_tipoarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa59_situacaohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa59_situacaohorus"])){ 
       $sql  .= $virgula." fa59_situacaohorus = $this->fa59_situacaohorus ";
       $virgula = ",";
       if(trim($this->fa59_situacaohorus) == null ){ 
         $this->erro_sql = " Campo Situação Hórus não informado.";
         $this->erro_campo = "fa59_situacaohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa59_db_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa59_db_depart"])){ 
       $sql  .= $virgula." fa59_db_depart = $this->fa59_db_depart ";
       $virgula = ",";
       if(trim($this->fa59_db_depart) == null ){ 
         $this->erro_sql = " Campo Departamento não informado.";
         $this->erro_campo = "fa59_db_depart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa59_codigo!=null){
       $sql .= " fa59_codigo = $this->fa59_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa59_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21293,'$this->fa59_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa59_codigo"]) || $this->fa59_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3837,21293,'".AddSlashes(pg_result($resaco,$conresaco,'fa59_codigo'))."','$this->fa59_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa59_usuario"]) || $this->fa59_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3837,21294,'".AddSlashes(pg_result($resaco,$conresaco,'fa59_usuario'))."','$this->fa59_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa59_mesreferente"]) || $this->fa59_mesreferente != "")
             $resac = db_query("insert into db_acount values($acount,3837,21296,'".AddSlashes(pg_result($resaco,$conresaco,'fa59_mesreferente'))."','$this->fa59_mesreferente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa59_anoreferente"]) || $this->fa59_anoreferente != "")
             $resac = db_query("insert into db_acount values($acount,3837,21299,'".AddSlashes(pg_result($resaco,$conresaco,'fa59_anoreferente'))."','$this->fa59_anoreferente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa59_tipoarquivo"]) || $this->fa59_tipoarquivo != "")
             $resac = db_query("insert into db_acount values($acount,3837,21297,'".AddSlashes(pg_result($resaco,$conresaco,'fa59_tipoarquivo'))."','$this->fa59_tipoarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa59_situacaohorus"]) || $this->fa59_situacaohorus != "")
             $resac = db_query("insert into db_acount values($acount,3837,21519,'".AddSlashes(pg_result($resaco,$conresaco,'fa59_situacaohorus'))."','$this->fa59_situacaohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa59_db_depart"]) || $this->fa59_db_depart != "")
             $resac = db_query("insert into db_acount values($acount,3837,21579,'".AddSlashes(pg_result($resaco,$conresaco,'fa59_db_depart'))."','$this->fa59_db_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "integracaohorus não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa59_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "integracaohorus não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($fa59_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa59_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21293,'$fa59_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3837,21293,'','".AddSlashes(pg_result($resaco,$iresaco,'fa59_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3837,21294,'','".AddSlashes(pg_result($resaco,$iresaco,'fa59_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3837,21296,'','".AddSlashes(pg_result($resaco,$iresaco,'fa59_mesreferente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3837,21299,'','".AddSlashes(pg_result($resaco,$iresaco,'fa59_anoreferente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3837,21297,'','".AddSlashes(pg_result($resaco,$iresaco,'fa59_tipoarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3837,21519,'','".AddSlashes(pg_result($resaco,$iresaco,'fa59_situacaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3837,21579,'','".AddSlashes(pg_result($resaco,$iresaco,'fa59_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from integracaohorus
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa59_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa59_codigo = $fa59_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "integracaohorus não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa59_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "integracaohorus não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa59_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa59_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:integracaohorus";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($fa59_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from integracaohorus ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = integracaohorus.fa59_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = integracaohorus.fa59_db_depart";
     $sql .= "      inner join situacaohorus  on  situacaohorus.fa60_sequencial = integracaohorus.fa59_situacaohorus";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa59_codigo)) {
         $sql2 .= " where integracaohorus.fa59_codigo = $fa59_codigo "; 
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
   public function sql_query_file ($fa59_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from integracaohorus ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa59_codigo)){
         $sql2 .= " where integracaohorus.fa59_codigo = $fa59_codigo "; 
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

  public function sql_query_integracao_envio ($fa59_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from integracaohorus ";
    $sql .= "      left join integracaohorusenvio on fa64_integracaohorus = fa59_codigo";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($fa59_codigo)) {
        $sql2 .= " where integracaohorus.fa59_codigo = $fa59_codigo ";
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
