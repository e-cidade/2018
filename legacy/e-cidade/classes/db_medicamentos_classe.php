<?
//MODULO: farmacia
//CLASSE DA ENTIDADE medicamentos
class cl_medicamentos { 
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
   var $fa58_codigo = 0; 
   var $fa58_catmat = null; 
   var $fa58_descricao = null; 
   var $fa58_concentracao = null; 
   var $fa58_formafarmaceutica = null; 
   var $fa58_volume = null; 
   var $fa58_unidadefornecimento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa58_codigo = int4 = Código 
                 fa58_catmat = varchar(20) = CATMAT 
                 fa58_descricao = text = Descrição 
                 fa58_concentracao = varchar(40) = Concentração 
                 fa58_formafarmaceutica = varchar(40) = Forma Farmacêutica 
                 fa58_volume = varchar(40) = Volume 
                 fa58_unidadefornecimento = varchar(40) = Unid. de Fornecimento 
                 ";
   //funcao construtor da classe 
   function cl_medicamentos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("medicamentos"); 
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
       $this->fa58_codigo = ($this->fa58_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_codigo"]:$this->fa58_codigo);
       $this->fa58_catmat = ($this->fa58_catmat == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_catmat"]:$this->fa58_catmat);
       $this->fa58_descricao = ($this->fa58_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_descricao"]:$this->fa58_descricao);
       $this->fa58_concentracao = ($this->fa58_concentracao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_concentracao"]:$this->fa58_concentracao);
       $this->fa58_formafarmaceutica = ($this->fa58_formafarmaceutica == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_formafarmaceutica"]:$this->fa58_formafarmaceutica);
       $this->fa58_volume = ($this->fa58_volume == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_volume"]:$this->fa58_volume);
       $this->fa58_unidadefornecimento = ($this->fa58_unidadefornecimento == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_unidadefornecimento"]:$this->fa58_unidadefornecimento);
     }else{
       $this->fa58_codigo = ($this->fa58_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa58_codigo"]:$this->fa58_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($fa58_codigo){ 
      $this->atualizacampos();
     if($this->fa58_catmat == null ){ 
       $this->erro_sql = " Campo CATMAT não informado.";
       $this->erro_campo = "fa58_catmat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa58_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "fa58_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa58_codigo == "" || $fa58_codigo == null ){
       $result = db_query("select nextval('medicamentos_fa58_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: medicamentos_fa58_codigo_seq do campo: fa58_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa58_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from medicamentos_fa58_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa58_codigo)){
         $this->erro_sql = " Campo fa58_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa58_codigo = $fa58_codigo; 
       }
     }
     if(($this->fa58_codigo == null) || ($this->fa58_codigo == "") ){ 
       $this->erro_sql = " Campo fa58_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into medicamentos(
                                       fa58_codigo 
                                      ,fa58_catmat 
                                      ,fa58_descricao 
                                      ,fa58_concentracao 
                                      ,fa58_formafarmaceutica 
                                      ,fa58_volume 
                                      ,fa58_unidadefornecimento 
                       )
                values (
                                $this->fa58_codigo 
                               ,'$this->fa58_catmat' 
                               ,'$this->fa58_descricao' 
                               ,'$this->fa58_concentracao' 
                               ,'$this->fa58_formafarmaceutica' 
                               ,'$this->fa58_volume' 
                               ,'$this->fa58_unidadefornecimento' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "medicamentos ($this->fa58_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "medicamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "medicamentos ($this->fa58_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa58_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa58_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21286,'$this->fa58_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3836,21286,'','".AddSlashes(pg_result($resaco,0,'fa58_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3836,21287,'','".AddSlashes(pg_result($resaco,0,'fa58_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3836,21288,'','".AddSlashes(pg_result($resaco,0,'fa58_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3836,21289,'','".AddSlashes(pg_result($resaco,0,'fa58_concentracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3836,21290,'','".AddSlashes(pg_result($resaco,0,'fa58_formafarmaceutica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3836,21291,'','".AddSlashes(pg_result($resaco,0,'fa58_volume'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3836,21292,'','".AddSlashes(pg_result($resaco,0,'fa58_unidadefornecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($fa58_codigo=null) { 
      $this->atualizacampos();
     $sql = " update medicamentos set ";
     $virgula = "";
     if(trim($this->fa58_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa58_codigo"])){ 
       $sql  .= $virgula." fa58_codigo = $this->fa58_codigo ";
       $virgula = ",";
       if(trim($this->fa58_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "fa58_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa58_catmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa58_catmat"])){ 
       $sql  .= $virgula." fa58_catmat = '$this->fa58_catmat' ";
       $virgula = ",";
       if(trim($this->fa58_catmat) == null ){ 
         $this->erro_sql = " Campo CATMAT não informado.";
         $this->erro_campo = "fa58_catmat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa58_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa58_descricao"])){ 
       $sql  .= $virgula." fa58_descricao = '$this->fa58_descricao' ";
       $virgula = ",";
       if(trim($this->fa58_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "fa58_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa58_concentracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa58_concentracao"])){ 
       $sql  .= $virgula." fa58_concentracao = '$this->fa58_concentracao' ";
       $virgula = ",";
     }
     if(trim($this->fa58_formafarmaceutica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa58_formafarmaceutica"])){ 
       $sql  .= $virgula." fa58_formafarmaceutica = '$this->fa58_formafarmaceutica' ";
       $virgula = ",";
     }
     if(trim($this->fa58_volume)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa58_volume"])){ 
       $sql  .= $virgula." fa58_volume = '$this->fa58_volume' ";
       $virgula = ",";
     }
     if(trim($this->fa58_unidadefornecimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa58_unidadefornecimento"])){ 
       $sql  .= $virgula." fa58_unidadefornecimento = '$this->fa58_unidadefornecimento' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($fa58_codigo!=null){
       $sql .= " fa58_codigo = $this->fa58_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa58_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21286,'$this->fa58_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa58_codigo"]) || $this->fa58_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3836,21286,'".AddSlashes(pg_result($resaco,$conresaco,'fa58_codigo'))."','$this->fa58_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa58_catmat"]) || $this->fa58_catmat != "")
             $resac = db_query("insert into db_acount values($acount,3836,21287,'".AddSlashes(pg_result($resaco,$conresaco,'fa58_catmat'))."','$this->fa58_catmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa58_descricao"]) || $this->fa58_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3836,21288,'".AddSlashes(pg_result($resaco,$conresaco,'fa58_descricao'))."','$this->fa58_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa58_concentracao"]) || $this->fa58_concentracao != "")
             $resac = db_query("insert into db_acount values($acount,3836,21289,'".AddSlashes(pg_result($resaco,$conresaco,'fa58_concentracao'))."','$this->fa58_concentracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa58_formafarmaceutica"]) || $this->fa58_formafarmaceutica != "")
             $resac = db_query("insert into db_acount values($acount,3836,21290,'".AddSlashes(pg_result($resaco,$conresaco,'fa58_formafarmaceutica'))."','$this->fa58_formafarmaceutica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa58_volume"]) || $this->fa58_volume != "")
             $resac = db_query("insert into db_acount values($acount,3836,21291,'".AddSlashes(pg_result($resaco,$conresaco,'fa58_volume'))."','$this->fa58_volume',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa58_unidadefornecimento"]) || $this->fa58_unidadefornecimento != "")
             $resac = db_query("insert into db_acount values($acount,3836,21292,'".AddSlashes(pg_result($resaco,$conresaco,'fa58_unidadefornecimento'))."','$this->fa58_unidadefornecimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "medicamentos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa58_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "medicamentos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa58_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa58_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($fa58_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa58_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21286,'$fa58_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3836,21286,'','".AddSlashes(pg_result($resaco,$iresaco,'fa58_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3836,21287,'','".AddSlashes(pg_result($resaco,$iresaco,'fa58_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3836,21288,'','".AddSlashes(pg_result($resaco,$iresaco,'fa58_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3836,21289,'','".AddSlashes(pg_result($resaco,$iresaco,'fa58_concentracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3836,21290,'','".AddSlashes(pg_result($resaco,$iresaco,'fa58_formafarmaceutica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3836,21291,'','".AddSlashes(pg_result($resaco,$iresaco,'fa58_volume'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3836,21292,'','".AddSlashes(pg_result($resaco,$iresaco,'fa58_unidadefornecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from medicamentos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa58_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa58_codigo = $fa58_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "medicamentos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa58_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "medicamentos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa58_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa58_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:medicamentos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($fa58_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from medicamentos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa58_codigo)) {
         $sql2 .= " where medicamentos.fa58_codigo = $fa58_codigo "; 
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
   public function sql_query_file ($fa58_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from medicamentos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa58_codigo)){
         $sql2 .= " where medicamentos.fa58_codigo = $fa58_codigo "; 
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
