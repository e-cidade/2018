<?php
//MODULO: escola
//CLASSE DA ENTIDADE tipohoratrabalho
class cl_tipohoratrabalho { 
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
   var $ed128_codigo = 0; 
   var $ed128_descricao = null; 
   var $ed128_abreviatura = null; 
   var $ed128_tipoefetividade = 0; 
   var $ed128_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed128_codigo = int4 = Código 
                 ed128_descricao = varchar(70) = Descrição 
                 ed128_abreviatura = varchar(10) = Abreviatura 
                 ed128_tipoefetividade = int4 = Efetividade 
                 ed128_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_tipohoratrabalho() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipohoratrabalho"); 
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
       $this->ed128_codigo = ($this->ed128_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_codigo"]:$this->ed128_codigo);
       $this->ed128_descricao = ($this->ed128_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_descricao"]:$this->ed128_descricao);
       $this->ed128_abreviatura = ($this->ed128_abreviatura == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_abreviatura"]:$this->ed128_abreviatura);
       $this->ed128_tipoefetividade = ($this->ed128_tipoefetividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_tipoefetividade"]:$this->ed128_tipoefetividade);
       $this->ed128_ativo = ($this->ed128_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed128_ativo"]:$this->ed128_ativo);
     }else{
       $this->ed128_codigo = ($this->ed128_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_codigo"]:$this->ed128_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed128_codigo){ 
      $this->atualizacampos();
     if($this->ed128_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "ed128_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed128_abreviatura == null ){ 
       $this->erro_sql = " Campo Abreviatura não informado.";
       $this->erro_campo = "ed128_abreviatura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed128_tipoefetividade == null ){ 
       $this->erro_sql = " Campo Efetividade não informado.";
       $this->erro_campo = "ed128_tipoefetividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed128_ativo == null ){ 
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "ed128_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed128_codigo == "" || $ed128_codigo == null ){
       $result = db_query("select nextval('tipohoratrabalho_ed128_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipohoratrabalho_ed128_codigo_seq do campo: ed128_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed128_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipohoratrabalho_ed128_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed128_codigo)){
         $this->erro_sql = " Campo ed128_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed128_codigo = $ed128_codigo; 
       }
     }
     if(($this->ed128_codigo == null) || ($this->ed128_codigo == "") ){ 
       $this->erro_sql = " Campo ed128_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipohoratrabalho(
                                       ed128_codigo 
                                      ,ed128_descricao 
                                      ,ed128_abreviatura 
                                      ,ed128_tipoefetividade 
                                      ,ed128_ativo 
                       )
                values (
                                $this->ed128_codigo 
                               ,'$this->ed128_descricao' 
                               ,'$this->ed128_abreviatura' 
                               ,$this->ed128_tipoefetividade 
                               ,'$this->ed128_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de hora de trabalho ($this->ed128_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de hora de trabalho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de hora de trabalho ($this->ed128_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed128_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21027,'$this->ed128_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3789,21027,'','".AddSlashes(pg_result($resaco,0,'ed128_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3789,21028,'','".AddSlashes(pg_result($resaco,0,'ed128_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3789,21029,'','".AddSlashes(pg_result($resaco,0,'ed128_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3789,21030,'','".AddSlashes(pg_result($resaco,0,'ed128_tipoefetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3789,21031,'','".AddSlashes(pg_result($resaco,0,'ed128_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed128_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tipohoratrabalho set ";
     $virgula = "";
     if(trim($this->ed128_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_codigo"])){ 
       $sql  .= $virgula." ed128_codigo = $this->ed128_codigo ";
       $virgula = ",";
       if(trim($this->ed128_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed128_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed128_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_descricao"])){ 
       $sql  .= $virgula." ed128_descricao = '$this->ed128_descricao' ";
       $virgula = ",";
       if(trim($this->ed128_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "ed128_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed128_abreviatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_abreviatura"])){ 
       $sql  .= $virgula." ed128_abreviatura = '$this->ed128_abreviatura' ";
       $virgula = ",";
       if(trim($this->ed128_abreviatura) == null ){ 
         $this->erro_sql = " Campo Abreviatura não informado.";
         $this->erro_campo = "ed128_abreviatura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed128_tipoefetividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_tipoefetividade"])){ 
       $sql  .= $virgula." ed128_tipoefetividade = $this->ed128_tipoefetividade ";
       $virgula = ",";
       if(trim($this->ed128_tipoefetividade) == null ){ 
         $this->erro_sql = " Campo Efetividade não informado.";
         $this->erro_campo = "ed128_tipoefetividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed128_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_ativo"])){ 
       $sql  .= $virgula." ed128_ativo = '$this->ed128_ativo' ";
       $virgula = ",";
       if(trim($this->ed128_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "ed128_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed128_codigo!=null){
       $sql .= " ed128_codigo = $this->ed128_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed128_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21027,'$this->ed128_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed128_codigo"]) || $this->ed128_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3789,21027,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_codigo'))."','$this->ed128_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed128_descricao"]) || $this->ed128_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3789,21028,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_descricao'))."','$this->ed128_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed128_abreviatura"]) || $this->ed128_abreviatura != "")
             $resac = db_query("insert into db_acount values($acount,3789,21029,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_abreviatura'))."','$this->ed128_abreviatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed128_tipoefetividade"]) || $this->ed128_tipoefetividade != "")
             $resac = db_query("insert into db_acount values($acount,3789,21030,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_tipoefetividade'))."','$this->ed128_tipoefetividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed128_ativo"]) || $this->ed128_ativo != "")
             $resac = db_query("insert into db_acount values($acount,3789,21031,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_ativo'))."','$this->ed128_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de hora de trabalho não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de hora de trabalho não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed128_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed128_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21027,'$ed128_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3789,21027,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3789,21028,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3789,21029,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3789,21030,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_tipoefetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3789,21031,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tipohoratrabalho
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed128_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed128_codigo = $ed128_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de hora de trabalho não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed128_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de hora de trabalho não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed128_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed128_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipohoratrabalho";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed128_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from tipohoratrabalho ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed128_codigo)) {
         $sql2 .= " where tipohoratrabalho.ed128_codigo = $ed128_codigo "; 
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
   public function sql_query_file ($ed128_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from tipohoratrabalho ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed128_codigo)){
         $sql2 .= " where tipohoratrabalho.ed128_codigo = $ed128_codigo "; 
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

  public function sql_query_vinculos( $ed128_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

     $sql  = "select {$campos} ";
     $sql .= "  from tipohoratrabalho ";
     $sql .= "       left join agendaatividade   on ed129_tipohoratrabalho = ed128_codigo";
     $sql .= "       left join rechumanohoradisp on ed33_tipohoratrabalho  = ed128_codigo";
     $sql .= "       left join relacaotrabalho   on ed23_tipohoratrabalho  = ed128_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed128_codigo)){
         $sql2 .= " where tipohoratrabalho.ed128_codigo = $ed128_codigo ";
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
