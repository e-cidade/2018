<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhconsignadomovimentoservidor
class cl_rhconsignadomovimentoservidor { 
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
   var $rh152_sequencial = 0; 
   var $rh152_consignadomovimento = 0; 
   var $rh152_regist = null; 
   var $rh152_nome = null; 
   var $rh152_consignadomotivo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh152_sequencial = int4 =  
                 rh152_consignadomovimento = int4 = Movimento 
                 rh152_regist = varchar(10) = Matrícula 
                 rh152_nome = varchar(40) = Nome do Servidor 
                 rh152_consignadomotivo = int4 = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_rhconsignadomovimentoservidor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhconsignadomovimentoservidor"); 
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
       $this->rh152_sequencial = ($this->rh152_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh152_sequencial"]:$this->rh152_sequencial);
       $this->rh152_consignadomovimento = ($this->rh152_consignadomovimento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh152_consignadomovimento"]:$this->rh152_consignadomovimento);
       $this->rh152_regist = ($this->rh152_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh152_regist"]:$this->rh152_regist);
       $this->rh152_nome = ($this->rh152_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["rh152_nome"]:$this->rh152_nome);
       $this->rh152_consignadomotivo = ($this->rh152_consignadomotivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh152_consignadomotivo"]:$this->rh152_consignadomotivo);
     }else{
       $this->rh152_sequencial = ($this->rh152_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh152_sequencial"]:$this->rh152_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh152_sequencial){ 
      $this->atualizacampos();
     if($this->rh152_consignadomovimento == null ){ 
       $this->erro_sql = " Campo Movimento não informado.";
       $this->erro_campo = "rh152_consignadomovimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh152_regist == null ){ 
       $this->rh152_regist = "0";
     }
     if($this->rh152_consignadomotivo == null ){ 
       $this->rh152_consignadomotivo = "null";
     }
     if($rh152_sequencial == "" || $rh152_sequencial == null ){
       $result = db_query("select nextval('rhconsignadomovimentoservidor_rh152_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhconsignadomovimentoservidor_rh152_sequencial_seq do campo: rh152_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh152_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhconsignadomovimentoservidor_rh152_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh152_sequencial)){
         $this->erro_sql = " Campo rh152_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh152_sequencial = $rh152_sequencial; 
       }
     }
     if(($this->rh152_sequencial == null) || ($this->rh152_sequencial == "") ){ 
       $this->erro_sql = " Campo rh152_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhconsignadomovimentoservidor(
                                       rh152_sequencial 
                                      ,rh152_consignadomovimento 
                                      ,rh152_regist 
                                      ,rh152_nome 
                                      ,rh152_consignadomotivo 
                       )
                values (
                                $this->rh152_sequencial 
                               ,$this->rh152_consignadomovimento 
                               ,'$this->rh152_regist' 
                               ,'$this->rh152_nome' 
                               ,$this->rh152_consignadomotivo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhconsignadomovimentoservidor ($this->rh152_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhconsignadomovimentoservidor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhconsignadomovimentoservidor ($this->rh152_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh152_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh152_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21012,'$this->rh152_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3786,21012,'','".AddSlashes(pg_result($resaco,0,'rh152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3786,21013,'','".AddSlashes(pg_result($resaco,0,'rh152_consignadomovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3786,21014,'','".AddSlashes(pg_result($resaco,0,'rh152_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3786,21015,'','".AddSlashes(pg_result($resaco,0,'rh152_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3786,21016,'','".AddSlashes(pg_result($resaco,0,'rh152_consignadomotivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh152_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhconsignadomovimentoservidor set ";
     $virgula = "";
     if(trim($this->rh152_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh152_sequencial"])){ 
       $sql  .= $virgula." rh152_sequencial = $this->rh152_sequencial ";
       $virgula = ",";
       if(trim($this->rh152_sequencial) == null ){ 
         $this->erro_sql = " Campo  não informado.";
         $this->erro_campo = "rh152_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh152_consignadomovimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh152_consignadomovimento"])){ 
       $sql  .= $virgula." rh152_consignadomovimento = $this->rh152_consignadomovimento ";
       $virgula = ",";
       if(trim($this->rh152_consignadomovimento) == null ){ 
         $this->erro_sql = " Campo Movimento não informado.";
         $this->erro_campo = "rh152_consignadomovimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh152_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh152_regist"])){ 
       $sql  .= $virgula." rh152_regist = '$this->rh152_regist' ";
       $virgula = ",";
     }
     if(trim($this->rh152_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh152_nome"])){ 
       $sql  .= $virgula." rh152_nome = '$this->rh152_nome' ";
       $virgula = ",";
     }
     if(trim($this->rh152_consignadomotivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh152_consignadomotivo"])){ 
        if(trim($this->rh152_consignadomotivo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh152_consignadomotivo"])){ 
           $this->rh152_consignadomotivo = "0" ; 
        } 
       $sql  .= $virgula." rh152_consignadomotivo = $this->rh152_consignadomotivo ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh152_sequencial!=null){
       $sql .= " rh152_sequencial = $this->rh152_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh152_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21012,'$this->rh152_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh152_sequencial"]) || $this->rh152_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3786,21012,'".AddSlashes(pg_result($resaco,$conresaco,'rh152_sequencial'))."','$this->rh152_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh152_consignadomovimento"]) || $this->rh152_consignadomovimento != "")
             $resac = db_query("insert into db_acount values($acount,3786,21013,'".AddSlashes(pg_result($resaco,$conresaco,'rh152_consignadomovimento'))."','$this->rh152_consignadomovimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh152_regist"]) || $this->rh152_regist != "")
             $resac = db_query("insert into db_acount values($acount,3786,21014,'".AddSlashes(pg_result($resaco,$conresaco,'rh152_regist'))."','$this->rh152_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh152_nome"]) || $this->rh152_nome != "")
             $resac = db_query("insert into db_acount values($acount,3786,21015,'".AddSlashes(pg_result($resaco,$conresaco,'rh152_nome'))."','$this->rh152_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh152_consignadomotivo"]) || $this->rh152_consignadomotivo != "")
             $resac = db_query("insert into db_acount values($acount,3786,21016,'".AddSlashes(pg_result($resaco,$conresaco,'rh152_consignadomotivo'))."','$this->rh152_consignadomotivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhconsignadomovimentoservidor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh152_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhconsignadomovimentoservidor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh152_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh152_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh152_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh152_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21012,'$rh152_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3786,21012,'','".AddSlashes(pg_result($resaco,$iresaco,'rh152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3786,21013,'','".AddSlashes(pg_result($resaco,$iresaco,'rh152_consignadomovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3786,21014,'','".AddSlashes(pg_result($resaco,$iresaco,'rh152_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3786,21015,'','".AddSlashes(pg_result($resaco,$iresaco,'rh152_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3786,21016,'','".AddSlashes(pg_result($resaco,$iresaco,'rh152_consignadomotivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhconsignadomovimentoservidor
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh152_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh152_sequencial = $rh152_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhconsignadomovimentoservidor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh152_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhconsignadomovimentoservidor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh152_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh152_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhconsignadomovimentoservidor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh152_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhconsignadomovimentoservidor ";
     $sql .= "      inner join rhconsignadomovimento  on  rhconsignadomovimento.rh151_sequencial = rhconsignadomovimentoservidor.rh152_consignadomovimento";
     $sql .= "      left join  rhconsignadomovimentoservidorrubrica  on  rhconsignadomovimentoservidorrubrica.rh153_consignadomovimentoservidor = rhconsignadomovimentoservidor.rh152_sequencial";
     $sql .= "      left  join rhconsignadomotivo  on  rhconsignadomotivo.rh154_sequencial = rhconsignadomovimentoservidor.rh152_consignadomotivo";
     $sql .= "      left  join db_config  on  db_config.codigo = rhconsignadomovimento.rh151_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh152_sequencial)) {
         $sql2 .= " where rhconsignadomovimentoservidor.rh152_sequencial = $rh152_sequencial "; 
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
   public function sql_query_file ($rh152_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhconsignadomovimentoservidor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh152_sequencial)){
         $sql2 .= " where rhconsignadomovimentoservidor.rh152_sequencial = $rh152_sequencial "; 
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
