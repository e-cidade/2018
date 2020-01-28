<?
//MODULO: empenho
//CLASSE DA ENTIDADE classificacaocredores
class cl_classificacaocredores { 
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
   var $cc30_codigo = 0; 
   var $cc30_descricao = null; 
   var $cc30_contagemdias = 0; 
   var $cc30_diasvencimento = 0; 
   var $cc30_valorinicial = 0; 
   var $cc30_valorfinal = 0; 
   var $cc30_dispensa = 'f'; 
   var $cc30_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc30_codigo = int4 = Código 
                 cc30_descricao = varchar(100) = Descrição 
                 cc30_contagemdias = int4 = Vencimento em Dias 
                 cc30_diasvencimento = int4 = Quantidade de Dias para o Vencimento 
                 cc30_valorinicial = float4 = Valor Inicial 
                 cc30_valorfinal = float4 = Valor Final 
                 cc30_dispensa = bool = Lista do Tipo Dispensa 
                 cc30_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_classificacaocredores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classificacaocredores"); 
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
       $this->cc30_codigo = ($this->cc30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_codigo"]:$this->cc30_codigo);
       $this->cc30_descricao = ($this->cc30_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_descricao"]:$this->cc30_descricao);
       $this->cc30_contagemdias = ($this->cc30_contagemdias == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_contagemdias"]:$this->cc30_contagemdias);
       $this->cc30_diasvencimento = ($this->cc30_diasvencimento == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_diasvencimento"]:$this->cc30_diasvencimento);
       $this->cc30_valorinicial = ($this->cc30_valorinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_valorinicial"]:$this->cc30_valorinicial);
       $this->cc30_valorfinal = ($this->cc30_valorfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_valorfinal"]:$this->cc30_valorfinal);
       $this->cc30_dispensa = ($this->cc30_dispensa == "f"?@$GLOBALS["HTTP_POST_VARS"]["cc30_dispensa"]:$this->cc30_dispensa);
       $this->cc30_ordem = ($this->cc30_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_ordem"]:$this->cc30_ordem);
     }else{
       $this->cc30_codigo = ($this->cc30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cc30_codigo"]:$this->cc30_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($cc30_codigo){ 
      // $this->atualizacampos();
     if($this->cc30_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "cc30_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc30_dispensa == null ){ 
       $this->erro_sql = " Campo Lista do Tipo Dispensa não informado.";
       $this->erro_campo = "cc30_dispensa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc30_ordem == null ){ 
       $this->erro_sql = " Campo Ordem não informado.";
       $this->erro_campo = "cc30_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc30_codigo == "" || $cc30_codigo == null ){
       $result = db_query("select nextval('classificacaocredores_cc30_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classificacaocredores_cc30_codigo_seq do campo: cc30_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc30_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from classificacaocredores_cc30_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc30_codigo)){
         $this->erro_sql = " Campo cc30_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc30_codigo = $cc30_codigo; 
       }
     }
     if(($this->cc30_codigo == null) || ($this->cc30_codigo == "") ){ 
       $this->erro_sql = " Campo cc30_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classificacaocredores(
                                       cc30_codigo 
                                      ,cc30_descricao 
                                      ,cc30_contagemdias 
                                      ,cc30_diasvencimento 
                                      ,cc30_valorinicial 
                                      ,cc30_valorfinal 
                                      ,cc30_dispensa 
                                      ,cc30_ordem 
                       )
                values (
                                $this->cc30_codigo 
                               ,'$this->cc30_descricao' 
                               ,$this->cc30_contagemdias 
                               ,$this->cc30_diasvencimento 
                               ,$this->cc30_valorinicial 
                               ,$this->cc30_valorfinal 
                               ,'$this->cc30_dispensa' 
                               ,$this->cc30_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Classificação de Credores ($this->cc30_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Classificação de Credores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Classificação de Credores ($this->cc30_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc30_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc30_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21597,'$this->cc30_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3878,21597,'','".AddSlashes(pg_result($resaco,0,'cc30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3878,21598,'','".AddSlashes(pg_result($resaco,0,'cc30_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3878,21895,'','".AddSlashes(pg_result($resaco,0,'cc30_contagemdias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3878,21896,'','".AddSlashes(pg_result($resaco,0,'cc30_diasvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3878,21897,'','".AddSlashes(pg_result($resaco,0,'cc30_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3878,21898,'','".AddSlashes(pg_result($resaco,0,'cc30_valorfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3878,21899,'','".AddSlashes(pg_result($resaco,0,'cc30_dispensa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3878,21900,'','".AddSlashes(pg_result($resaco,0,'cc30_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($cc30_codigo=null) { 
      // $this->atualizacampos();
     $sql = " update classificacaocredores set ";
     $virgula = "";
     if(trim($this->cc30_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_codigo"])){ 
       $sql  .= $virgula." cc30_codigo = $this->cc30_codigo ";
       $virgula = ",";
       if(trim($this->cc30_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "cc30_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc30_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_descricao"])){ 
       $sql  .= $virgula." cc30_descricao = '$this->cc30_descricao' ";
       $virgula = ",";
       if(trim($this->cc30_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "cc30_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc30_contagemdias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_contagemdias"])){ 
        if(trim($this->cc30_contagemdias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["cc30_contagemdias"])){ 
           $this->cc30_contagemdias = "0" ; 
        } 
       $sql  .= $virgula." cc30_contagemdias = $this->cc30_contagemdias ";
       $virgula = ",";
     }
     if(trim($this->cc30_diasvencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_diasvencimento"])){ 
        if(trim($this->cc30_diasvencimento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["cc30_diasvencimento"])){ 
           $this->cc30_diasvencimento = "0" ; 
        } 
       $sql  .= $virgula." cc30_diasvencimento = $this->cc30_diasvencimento ";
       $virgula = ",";
     }
     if(trim($this->cc30_valorinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_valorinicial"])){ 
        if(trim($this->cc30_valorinicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["cc30_valorinicial"])){ 
           $this->cc30_valorinicial = "0" ; 
        } 
       $sql  .= $virgula." cc30_valorinicial = $this->cc30_valorinicial ";
       $virgula = ",";
     }
     if(trim($this->cc30_valorfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_valorfinal"])){ 
        if(trim($this->cc30_valorfinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["cc30_valorfinal"])){ 
           $this->cc30_valorfinal = "0" ; 
        } 
       $sql  .= $virgula." cc30_valorfinal = $this->cc30_valorfinal ";
       $virgula = ",";
     }
     if(trim($this->cc30_dispensa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_dispensa"])){ 
       $sql  .= $virgula." cc30_dispensa = '$this->cc30_dispensa' ";
       $virgula = ",";
       if(trim($this->cc30_dispensa) == null ){ 
         $this->erro_sql = " Campo Lista do Tipo Dispensa não informado.";
         $this->erro_campo = "cc30_dispensa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc30_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc30_ordem"])){ 
       $sql  .= $virgula." cc30_ordem = $this->cc30_ordem ";
       $virgula = ",";
       if(trim($this->cc30_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem não informado.";
         $this->erro_campo = "cc30_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc30_codigo!=null){
       $sql .= " cc30_codigo = $this->cc30_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc30_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21597,'$this->cc30_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_codigo"]) || $this->cc30_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3878,21597,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_codigo'))."','$this->cc30_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_descricao"]) || $this->cc30_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3878,21598,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_descricao'))."','$this->cc30_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_contagemdias"]) || $this->cc30_contagemdias != "")
             $resac = db_query("insert into db_acount values($acount,3878,21895,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_contagemdias'))."','$this->cc30_contagemdias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_diasvencimento"]) || $this->cc30_diasvencimento != "")
             $resac = db_query("insert into db_acount values($acount,3878,21896,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_diasvencimento'))."','$this->cc30_diasvencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_valorinicial"]) || $this->cc30_valorinicial != "")
             $resac = db_query("insert into db_acount values($acount,3878,21897,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_valorinicial'))."','$this->cc30_valorinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_valorfinal"]) || $this->cc30_valorfinal != "")
             $resac = db_query("insert into db_acount values($acount,3878,21898,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_valorfinal'))."','$this->cc30_valorfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_dispensa"]) || $this->cc30_dispensa != "")
             $resac = db_query("insert into db_acount values($acount,3878,21899,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_dispensa'))."','$this->cc30_dispensa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc30_ordem"]) || $this->cc30_ordem != "")
             $resac = db_query("insert into db_acount values($acount,3878,21900,'".AddSlashes(pg_result($resaco,$conresaco,'cc30_ordem'))."','$this->cc30_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Classificação de Credores não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Classificação de Credores não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($cc30_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($cc30_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21597,'$cc30_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3878,21597,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3878,21598,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3878,21895,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_contagemdias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3878,21896,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_diasvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3878,21897,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3878,21898,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_valorfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3878,21899,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_dispensa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3878,21900,'','".AddSlashes(pg_result($resaco,$iresaco,'cc30_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from classificacaocredores
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($cc30_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " cc30_codigo = $cc30_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Classificação de Credores não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Classificação de Credores não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc30_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:classificacaocredores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($cc30_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from classificacaocredores ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc30_codigo)) {
         $sql2 .= " where classificacaocredores.cc30_codigo = $cc30_codigo "; 
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
   public function sql_query_file ($cc30_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from classificacaocredores ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc30_codigo)){
         $sql2 .= " where classificacaocredores.cc30_codigo = $cc30_codigo "; 
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

  public function sql_query_geral($sCampos = "*", $sWhere = null, $sOrder = null) {

    $sql  = "select {$sCampos}";
    $sql .= "  from classificacaocredores ";
    $sql .= "       left join classificacaocredoreselemento   on classificacaocredoreselemento.cc32_classificacaocredores = classificacaocredores.cc30_codigo ";
    $sql .= "       left join conplanoorcamento               on conplanoorcamento.c60_codcon = classificacaocredoreselemento.cc32_codcon ";
    $sql .= "                                                and conplanoorcamento.c60_anousu = classificacaocredoreselemento.cc32_anousu ";
    $sql .= "       left join classificacaocredoresevento     on classificacaocredoresevento.cc35_classificacaocredores = classificacaocredores.cc30_codigo ";
    $sql .= "       left join classificacaocredoresrecurso    on classificacaocredoresrecurso.cc33_classificacaocredores = classificacaocredores.cc30_codigo ";
    $sql .= "       left join classificacaocredorestipocompra on classificacaocredorestipocompra.cc34_classificacaocredores = classificacaocredores.cc30_codigo ";

    if (!empty($sWhere)) {
      $sql .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sql .= " order by {$sOrder} ";
    }
     return $sql;
  }

}
