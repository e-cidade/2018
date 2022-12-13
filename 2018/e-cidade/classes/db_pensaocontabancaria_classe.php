<?
//MODULO: pessoal
//CLASSE DA ENTIDADE pensaocontabancaria
class cl_pensaocontabancaria { 
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
   var $rh139_sequencial = 0; 
   var $rh139_regist = 0; 
   var $rh139_numcgm = 0; 
   var $rh139_anousu = 0; 
   var $rh139_mesusu = 0; 
   var $rh139_contabancaria = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh139_sequencial = int4 = Sequencial 
                 rh139_regist = int4 = Regist 
                 rh139_numcgm = int4 = Número CGM 
                 rh139_anousu = int4 = Ano 
                 rh139_mesusu = int4 = Mês 
                 rh139_contabancaria = int4 = Conta Bancária 
                 ";
   //funcao construtor da classe 
   function cl_pensaocontabancaria() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pensaocontabancaria"); 
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
       $this->rh139_sequencial = ($this->rh139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh139_sequencial"]:$this->rh139_sequencial);
       $this->rh139_regist = ($this->rh139_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh139_regist"]:$this->rh139_regist);
       $this->rh139_numcgm = ($this->rh139_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh139_numcgm"]:$this->rh139_numcgm);
       $this->rh139_anousu = ($this->rh139_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh139_anousu"]:$this->rh139_anousu);
       $this->rh139_mesusu = ($this->rh139_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh139_mesusu"]:$this->rh139_mesusu);
       $this->rh139_contabancaria = ($this->rh139_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["rh139_contabancaria"]:$this->rh139_contabancaria);
     }else{
       $this->rh139_sequencial = ($this->rh139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh139_sequencial"]:$this->rh139_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh139_sequencial){ 
      $this->atualizacampos();
     if($this->rh139_regist == null ){ 
       $this->erro_sql = " Campo Regist não informado.";
       $this->erro_campo = "rh139_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh139_numcgm == null ){ 
       $this->erro_sql = " Campo Número CGM não informado.";
       $this->erro_campo = "rh139_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh139_anousu == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh139_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh139_mesusu == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "rh139_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh139_contabancaria == null ){ 
       $this->erro_sql = " Campo Conta Bancária não informado.";
       $this->erro_campo = "rh139_contabancaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh139_sequencial == "" || $rh139_sequencial == null ){
       $result = db_query("select nextval('pensaocontabancaria_rh139_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pensaocontabancaria_rh139_sequencial_seq do campo: rh139_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh139_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pensaocontabancaria_rh139_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh139_sequencial)){
         $this->erro_sql = " Campo rh139_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh139_sequencial = $rh139_sequencial; 
       }
     }
     if(($this->rh139_sequencial == null) || ($this->rh139_sequencial == "") ){ 
       $this->erro_sql = " Campo rh139_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pensaocontabancaria(
                                       rh139_sequencial 
                                      ,rh139_regist 
                                      ,rh139_numcgm 
                                      ,rh139_anousu 
                                      ,rh139_mesusu 
                                      ,rh139_contabancaria 
                       )
                values (
                                $this->rh139_sequencial 
                               ,$this->rh139_regist 
                               ,$this->rh139_numcgm 
                               ,$this->rh139_anousu 
                               ,$this->rh139_mesusu 
                               ,$this->rh139_contabancaria 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pensaocontabancaria ($this->rh139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pensaocontabancaria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pensaocontabancaria ($this->rh139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh139_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh139_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20671,'$this->rh139_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3721,20671,'','".AddSlashes(pg_result($resaco,0,'rh139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3721,20672,'','".AddSlashes(pg_result($resaco,0,'rh139_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3721,20673,'','".AddSlashes(pg_result($resaco,0,'rh139_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3721,20675,'','".AddSlashes(pg_result($resaco,0,'rh139_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3721,20676,'','".AddSlashes(pg_result($resaco,0,'rh139_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3721,20677,'','".AddSlashes(pg_result($resaco,0,'rh139_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh139_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pensaocontabancaria set ";
     $virgula = "";
     if(trim($this->rh139_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh139_sequencial"])){ 
       $sql  .= $virgula." rh139_sequencial = $this->rh139_sequencial ";
       $virgula = ",";
       if(trim($this->rh139_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh139_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh139_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh139_regist"])){ 
       $sql  .= $virgula." rh139_regist = $this->rh139_regist ";
       $virgula = ",";
       if(trim($this->rh139_regist) == null ){ 
         $this->erro_sql = " Campo Regist não informado.";
         $this->erro_campo = "rh139_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh139_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh139_numcgm"])){ 
       $sql  .= $virgula." rh139_numcgm = $this->rh139_numcgm ";
       $virgula = ",";
       if(trim($this->rh139_numcgm) == null ){ 
         $this->erro_sql = " Campo Número CGM não informado.";
         $this->erro_campo = "rh139_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh139_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh139_anousu"])){ 
       $sql  .= $virgula." rh139_anousu = $this->rh139_anousu ";
       $virgula = ",";
       if(trim($this->rh139_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh139_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh139_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh139_mesusu"])){ 
       $sql  .= $virgula." rh139_mesusu = $this->rh139_mesusu ";
       $virgula = ",";
       if(trim($this->rh139_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "rh139_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh139_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh139_contabancaria"])){ 
       $sql  .= $virgula." rh139_contabancaria = $this->rh139_contabancaria ";
       $virgula = ",";
       if(trim($this->rh139_contabancaria) == null ){ 
         $this->erro_sql = " Campo Conta Bancária não informado.";
         $this->erro_campo = "rh139_contabancaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh139_sequencial!=null){
       $sql .= " rh139_sequencial = $this->rh139_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh139_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20671,'$this->rh139_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh139_sequencial"]) || $this->rh139_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3721,20671,'".AddSlashes(pg_result($resaco,$conresaco,'rh139_sequencial'))."','$this->rh139_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh139_regist"]) || $this->rh139_regist != "")
             $resac = db_query("insert into db_acount values($acount,3721,20672,'".AddSlashes(pg_result($resaco,$conresaco,'rh139_regist'))."','$this->rh139_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh139_numcgm"]) || $this->rh139_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,3721,20673,'".AddSlashes(pg_result($resaco,$conresaco,'rh139_numcgm'))."','$this->rh139_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh139_anousu"]) || $this->rh139_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3721,20675,'".AddSlashes(pg_result($resaco,$conresaco,'rh139_anousu'))."','$this->rh139_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh139_mesusu"]) || $this->rh139_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,3721,20676,'".AddSlashes(pg_result($resaco,$conresaco,'rh139_mesusu'))."','$this->rh139_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh139_contabancaria"]) || $this->rh139_contabancaria != "")
             $resac = db_query("insert into db_acount values($acount,3721,20677,'".AddSlashes(pg_result($resaco,$conresaco,'rh139_contabancaria'))."','$this->rh139_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pensaocontabancaria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pensaocontabancaria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh139_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh139_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20671,'$rh139_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3721,20671,'','".AddSlashes(pg_result($resaco,$iresaco,'rh139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3721,20672,'','".AddSlashes(pg_result($resaco,$iresaco,'rh139_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3721,20673,'','".AddSlashes(pg_result($resaco,$iresaco,'rh139_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3721,20675,'','".AddSlashes(pg_result($resaco,$iresaco,'rh139_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3721,20676,'','".AddSlashes(pg_result($resaco,$iresaco,'rh139_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3721,20677,'','".AddSlashes(pg_result($resaco,$iresaco,'rh139_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pensaocontabancaria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh139_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh139_sequencial = $rh139_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pensaocontabancaria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pensaocontabancaria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh139_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pensaocontabancaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pensaocontabancaria ";
     $sql .= "      inner join pensao  on  pensao.r52_anousu = pensaocontabancaria.rh139_anousu and  pensao.r52_mesusu = pensaocontabancaria.rh139_mesusu and  pensao.r52_regist = pensaocontabancaria.rh139_regist and  pensao.r52_numcgm = pensaocontabancaria.rh139_numcgm";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = pensaocontabancaria.rh139_contabancaria";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pensao.r52_numcgm";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pensao.r52_anousu and  pessoal.r01_mesusu = pensao.r52_mesusu and  pessoal.r01_regist = pensao.r52_regist";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql2 = "";
     if($dbwhere==""){
       if($rh139_sequencial!=null ){
         $sql2 .= " where pensaocontabancaria.rh139_sequencial = $rh139_sequencial "; 
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
   function sql_query_file ( $rh139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pensaocontabancaria ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh139_sequencial!=null ){
         $sql2 .= " where pensaocontabancaria.rh139_sequencial = $rh139_sequencial "; 
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
