<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE classificacaorisco
class cl_classificacaorisco { 
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
   var $sd78_codigo = 0; 
   var $sd78_descricao = null; 
   var $sd78_peso = 0; 
   var $sd78_labelcor = null; 
   var $sd78_cor = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd78_codigo = int4 = Código 
                 sd78_descricao = varchar(40) = Decrição 
                 sd78_peso = int4 = Peso 
                 sd78_labelcor = varchar(10) = Descrição da cor 
                 sd78_cor = varchar(7) = Cor 
                 ";
   //funcao construtor da classe 
   function cl_classificacaorisco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classificacaorisco"); 
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
       $this->sd78_codigo = ($this->sd78_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd78_codigo"]:$this->sd78_codigo);
       $this->sd78_descricao = ($this->sd78_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd78_descricao"]:$this->sd78_descricao);
       $this->sd78_peso = ($this->sd78_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["sd78_peso"]:$this->sd78_peso);
       $this->sd78_labelcor = ($this->sd78_labelcor == ""?@$GLOBALS["HTTP_POST_VARS"]["sd78_labelcor"]:$this->sd78_labelcor);
       $this->sd78_cor = ($this->sd78_cor == ""?@$GLOBALS["HTTP_POST_VARS"]["sd78_cor"]:$this->sd78_cor);
     }else{
       $this->sd78_codigo = ($this->sd78_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd78_codigo"]:$this->sd78_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd78_codigo){ 
      $this->atualizacampos();
     if($this->sd78_descricao == null ){ 
       $this->erro_sql = " Campo Decrição não informado.";
       $this->erro_campo = "sd78_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd78_peso == null ){ 
       $this->erro_sql = " Campo Peso não informado.";
       $this->erro_campo = "sd78_peso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd78_labelcor == null ){ 
       $this->erro_sql = " Campo Descrição da cor não informado.";
       $this->erro_campo = "sd78_labelcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd78_cor == null ){ 
       $this->erro_sql = " Campo Cor não informado.";
       $this->erro_campo = "sd78_cor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd78_codigo == "" || $sd78_codigo == null ){
       $result = db_query("select nextval('classificacaorisco_sd78_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classificacaorisco_sd78_codigo_seq do campo: sd78_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd78_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from classificacaorisco_sd78_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd78_codigo)){
         $this->erro_sql = " Campo sd78_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd78_codigo = $sd78_codigo; 
       }
     }
     if(($this->sd78_codigo == null) || ($this->sd78_codigo == "") ){ 
       $this->erro_sql = " Campo sd78_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classificacaorisco(
                                       sd78_codigo 
                                      ,sd78_descricao 
                                      ,sd78_peso 
                                      ,sd78_labelcor 
                                      ,sd78_cor 
                       )
                values (
                                $this->sd78_codigo 
                               ,'$this->sd78_descricao' 
                               ,$this->sd78_peso 
                               ,'$this->sd78_labelcor' 
                               ,'$this->sd78_cor' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Classificação de Risco ($this->sd78_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Classificação de Risco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Classificação de Risco ($this->sd78_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd78_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd78_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20914,'$this->sd78_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3763,20914,'','".AddSlashes(pg_result($resaco,0,'sd78_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3763,20915,'','".AddSlashes(pg_result($resaco,0,'sd78_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3763,20917,'','".AddSlashes(pg_result($resaco,0,'sd78_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3763,20916,'','".AddSlashes(pg_result($resaco,0,'sd78_labelcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3763,20918,'','".AddSlashes(pg_result($resaco,0,'sd78_cor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd78_codigo=null) { 
      $this->atualizacampos();
     $sql = " update classificacaorisco set ";
     $virgula = "";
     if(trim($this->sd78_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd78_codigo"])){ 
       $sql  .= $virgula." sd78_codigo = $this->sd78_codigo ";
       $virgula = ",";
       if(trim($this->sd78_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd78_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd78_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd78_descricao"])){ 
       $sql  .= $virgula." sd78_descricao = '$this->sd78_descricao' ";
       $virgula = ",";
       if(trim($this->sd78_descricao) == null ){ 
         $this->erro_sql = " Campo Decrição não informado.";
         $this->erro_campo = "sd78_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd78_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd78_peso"])){ 
       $sql  .= $virgula." sd78_peso = $this->sd78_peso ";
       $virgula = ",";
       if(trim($this->sd78_peso) == null ){ 
         $this->erro_sql = " Campo Peso não informado.";
         $this->erro_campo = "sd78_peso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd78_labelcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd78_labelcor"])){ 
       $sql  .= $virgula." sd78_labelcor = '$this->sd78_labelcor' ";
       $virgula = ",";
       if(trim($this->sd78_labelcor) == null ){ 
         $this->erro_sql = " Campo Descrição da cor não informado.";
         $this->erro_campo = "sd78_labelcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd78_cor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd78_cor"])){ 
       $sql  .= $virgula." sd78_cor = '$this->sd78_cor' ";
       $virgula = ",";
       if(trim($this->sd78_cor) == null ){ 
         $this->erro_sql = " Campo Cor não informado.";
         $this->erro_campo = "sd78_cor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd78_codigo!=null){
       $sql .= " sd78_codigo = $this->sd78_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd78_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20914,'$this->sd78_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd78_codigo"]) || $this->sd78_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3763,20914,'".AddSlashes(pg_result($resaco,$conresaco,'sd78_codigo'))."','$this->sd78_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd78_descricao"]) || $this->sd78_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3763,20915,'".AddSlashes(pg_result($resaco,$conresaco,'sd78_descricao'))."','$this->sd78_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd78_peso"]) || $this->sd78_peso != "")
             $resac = db_query("insert into db_acount values($acount,3763,20917,'".AddSlashes(pg_result($resaco,$conresaco,'sd78_peso'))."','$this->sd78_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd78_labelcor"]) || $this->sd78_labelcor != "")
             $resac = db_query("insert into db_acount values($acount,3763,20916,'".AddSlashes(pg_result($resaco,$conresaco,'sd78_labelcor'))."','$this->sd78_labelcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd78_cor"]) || $this->sd78_cor != "")
             $resac = db_query("insert into db_acount values($acount,3763,20918,'".AddSlashes(pg_result($resaco,$conresaco,'sd78_cor'))."','$this->sd78_cor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Classificação de Risco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd78_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Classificação de Risco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd78_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd78_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd78_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd78_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20914,'$sd78_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3763,20914,'','".AddSlashes(pg_result($resaco,$iresaco,'sd78_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3763,20915,'','".AddSlashes(pg_result($resaco,$iresaco,'sd78_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3763,20917,'','".AddSlashes(pg_result($resaco,$iresaco,'sd78_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3763,20916,'','".AddSlashes(pg_result($resaco,$iresaco,'sd78_labelcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3763,20918,'','".AddSlashes(pg_result($resaco,$iresaco,'sd78_cor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from classificacaorisco
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd78_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd78_codigo = $sd78_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Classificação de Risco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd78_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Classificação de Risco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd78_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd78_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:classificacaorisco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd78_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from classificacaorisco ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd78_codigo)) {
         $sql2 .= " where classificacaorisco.sd78_codigo = $sd78_codigo "; 
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
   public function sql_query_file ($sd78_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from classificacaorisco ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd78_codigo)){
         $sql2 .= " where classificacaorisco.sd78_codigo = $sd78_codigo "; 
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
