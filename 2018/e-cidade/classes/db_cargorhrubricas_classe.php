<?
//MODULO: pessoal
//CLASSE DA ENTIDADE cargorhrubricas
class cl_cargorhrubricas { 
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
   var $rh176_sequencial = 0; 
   var $rh176_cargo = 0; 
   var $rh176_rubrica = null; 
   var $rh176_instit = 0; 
   var $rh176_quantidade = 0; 
   var $rh176_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh176_sequencial = int4 = Sequencial 
                 rh176_cargo = int4 = Cargo 
                 rh176_rubrica = char(4) = Rubrica 
                 rh176_instit = int4 = Instituição 
                 rh176_quantidade = float8 = Quantidade 
                 rh176_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_cargorhrubricas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cargorhrubricas"); 
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
       $this->rh176_sequencial = ($this->rh176_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh176_sequencial"]:$this->rh176_sequencial);
       $this->rh176_cargo = ($this->rh176_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh176_cargo"]:$this->rh176_cargo);
       $this->rh176_rubrica = ($this->rh176_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh176_rubrica"]:$this->rh176_rubrica);
       $this->rh176_instit = ($this->rh176_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh176_instit"]:$this->rh176_instit);
       $this->rh176_quantidade = ($this->rh176_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh176_quantidade"]:$this->rh176_quantidade);
       $this->rh176_valor = ($this->rh176_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh176_valor"]:$this->rh176_valor);
     }else{
       $this->rh176_sequencial = ($this->rh176_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh176_sequencial"]:$this->rh176_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh176_sequencial){ 
      $this->atualizacampos();
     if($this->rh176_cargo == null ){ 
       $this->erro_sql = " Campo Cargo não informado.";
       $this->erro_campo = "rh176_cargo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh176_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh176_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh176_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh176_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh176_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "rh176_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh176_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "rh176_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh176_sequencial == "" || $rh176_sequencial == null ){
       $result = db_query("select nextval('cargorhrubricas_rh176_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cargorhrubricas_rh176_sequencial_seq do campo: rh176_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh176_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cargorhrubricas_rh176_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh176_sequencial)){
         $this->erro_sql = " Campo rh176_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh176_sequencial = $rh176_sequencial; 
       }
     }
     if(($this->rh176_sequencial == null) || ($this->rh176_sequencial == "") ){ 
       $this->erro_sql = " Campo rh176_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cargorhrubricas(
                                       rh176_sequencial 
                                      ,rh176_cargo 
                                      ,rh176_rubrica 
                                      ,rh176_instit 
                                      ,rh176_quantidade 
                                      ,rh176_valor 
                       )
                values (
                                $this->rh176_sequencial 
                               ,$this->rh176_cargo 
                               ,'$this->rh176_rubrica' 
                               ,$this->rh176_instit 
                               ,$this->rh176_quantidade 
                               ,$this->rh176_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rubricas por cargo ($this->rh176_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rubricas por cargo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rubricas por cargo ($this->rh176_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh176_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh176_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21729,'$this->rh176_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3914,21729,'','".AddSlashes(pg_result($resaco,0,'rh176_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3914,21730,'','".AddSlashes(pg_result($resaco,0,'rh176_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3914,21731,'','".AddSlashes(pg_result($resaco,0,'rh176_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3914,21732,'','".AddSlashes(pg_result($resaco,0,'rh176_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3914,21754,'','".AddSlashes(pg_result($resaco,0,'rh176_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3914,21755,'','".AddSlashes(pg_result($resaco,0,'rh176_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh176_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cargorhrubricas set ";
     $virgula = "";
     if(trim($this->rh176_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh176_sequencial"])){ 
       $sql  .= $virgula." rh176_sequencial = $this->rh176_sequencial ";
       $virgula = ",";
       if(trim($this->rh176_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh176_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh176_cargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh176_cargo"])){ 
       $sql  .= $virgula." rh176_cargo = $this->rh176_cargo ";
       $virgula = ",";
       if(trim($this->rh176_cargo) == null ){ 
         $this->erro_sql = " Campo Cargo não informado.";
         $this->erro_campo = "rh176_cargo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh176_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh176_rubrica"])){ 
       $sql  .= $virgula." rh176_rubrica = '$this->rh176_rubrica' ";
       $virgula = ",";
       if(trim($this->rh176_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh176_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh176_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh176_instit"])){ 
       $sql  .= $virgula." rh176_instit = $this->rh176_instit ";
       $virgula = ",";
       if(trim($this->rh176_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh176_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh176_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh176_quantidade"])){ 
       $sql  .= $virgula." rh176_quantidade = $this->rh176_quantidade ";
       $virgula = ",";
       if(trim($this->rh176_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "rh176_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh176_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh176_valor"])){ 
       $sql  .= $virgula." rh176_valor = $this->rh176_valor ";
       $virgula = ",";
       if(trim($this->rh176_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "rh176_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh176_sequencial!=null){
       $sql .= " rh176_sequencial = $this->rh176_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh176_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21729,'$this->rh176_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh176_sequencial"]) || $this->rh176_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3914,21729,'".AddSlashes(pg_result($resaco,$conresaco,'rh176_sequencial'))."','$this->rh176_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh176_cargo"]) || $this->rh176_cargo != "")
             $resac = db_query("insert into db_acount values($acount,3914,21730,'".AddSlashes(pg_result($resaco,$conresaco,'rh176_cargo'))."','$this->rh176_cargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh176_rubrica"]) || $this->rh176_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3914,21731,'".AddSlashes(pg_result($resaco,$conresaco,'rh176_rubrica'))."','$this->rh176_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh176_instit"]) || $this->rh176_instit != "")
             $resac = db_query("insert into db_acount values($acount,3914,21732,'".AddSlashes(pg_result($resaco,$conresaco,'rh176_instit'))."','$this->rh176_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh176_quantidade"]) || $this->rh176_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3914,21754,'".AddSlashes(pg_result($resaco,$conresaco,'rh176_quantidade'))."','$this->rh176_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh176_valor"]) || $this->rh176_valor != "")
             $resac = db_query("insert into db_acount values($acount,3914,21755,'".AddSlashes(pg_result($resaco,$conresaco,'rh176_valor'))."','$this->rh176_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubricas por cargo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh176_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas por cargo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh176_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh176_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh176_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh176_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21729,'$rh176_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3914,21729,'','".AddSlashes(pg_result($resaco,$iresaco,'rh176_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3914,21730,'','".AddSlashes(pg_result($resaco,$iresaco,'rh176_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3914,21731,'','".AddSlashes(pg_result($resaco,$iresaco,'rh176_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3914,21732,'','".AddSlashes(pg_result($resaco,$iresaco,'rh176_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3914,21754,'','".AddSlashes(pg_result($resaco,$iresaco,'rh176_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3914,21755,'','".AddSlashes(pg_result($resaco,$iresaco,'rh176_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cargorhrubricas
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh176_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh176_sequencial = $rh176_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubricas por cargo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh176_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas por cargo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh176_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh176_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cargorhrubricas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh176_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cargorhrubricas ";
     $sql .= "      inner join db_config  on  db_config.codigo = cargorhrubricas.rh176_instit";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = cargorhrubricas.rh176_rubrica and  rhrubricas.rh27_instit = cargorhrubricas.rh176_instit";
     $sql .= "      inner join rhcargo  on  rhcargo.rh04_codigo = cargorhrubricas.rh176_cargo and  rhcargo.rh04_instit = cargorhrubricas.rh176_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      left  join rhfundamentacaolegal  on  rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh176_sequencial)) {
         $sql2 .= " where cargorhrubricas.rh176_sequencial = $rh176_sequencial "; 
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
   public function sql_query_file ($rh176_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cargorhrubricas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh176_sequencial)){
         $sql2 .= " where cargorhrubricas.rh176_sequencial = $rh176_sequencial "; 
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
