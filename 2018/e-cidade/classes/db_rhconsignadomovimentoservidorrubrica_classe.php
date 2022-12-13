<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhconsignadomovimentoservidorrubrica
class cl_rhconsignadomovimentoservidorrubrica { 
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
   var $rh153_sequencial = 0; 
   var $rh153_consignadomovimentoservidor = 0; 
   var $rh153_instit = 0; 
   var $rh153_rubrica = 0; 
   var $rh153_valordescontar = null; 
   var $rh153_valordescontado = null; 
   var $rh153_parcela = null; 
   var $rh153_totalparcelas = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh153_sequencial = int4 =  
                 rh153_consignadomovimentoservidor = int4 = Movimento do Servidor 
                 rh153_instit = int4 = Instituição 
                 rh153_rubrica = int4 = Rubrica 
                 rh153_valordescontar = varchar(10) = Valor da Parcela 
                 rh153_valordescontado = varchar(10) = Valor Descontado 
                 rh153_parcela = varchar(3) = Número da Parcela 
                 rh153_totalparcelas = varchar(3) = Total de Parcelas 
                 ";
   //funcao construtor da classe 
   function cl_rhconsignadomovimentoservidorrubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhconsignadomovimentoservidorrubrica"); 
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
       $this->rh153_sequencial = ($this->rh153_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_sequencial"]:$this->rh153_sequencial);
       $this->rh153_consignadomovimentoservidor = ($this->rh153_consignadomovimentoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_consignadomovimentoservidor"]:$this->rh153_consignadomovimentoservidor);
       $this->rh153_instit = ($this->rh153_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_instit"]:$this->rh153_instit);
       $this->rh153_rubrica = ($this->rh153_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_rubrica"]:$this->rh153_rubrica);
       $this->rh153_valordescontar = ($this->rh153_valordescontar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_valordescontar"]:$this->rh153_valordescontar);
       $this->rh153_valordescontado = ($this->rh153_valordescontado == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_valordescontado"]:$this->rh153_valordescontado);
       $this->rh153_parcela = ($this->rh153_parcela == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_parcela"]:$this->rh153_parcela);
       $this->rh153_totalparcelas = ($this->rh153_totalparcelas == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_totalparcelas"]:$this->rh153_totalparcelas);
     }else{
       $this->rh153_sequencial = ($this->rh153_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh153_sequencial"]:$this->rh153_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh153_sequencial){ 
      $this->atualizacampos();
     if($this->rh153_consignadomovimentoservidor == null ){ 
       $this->erro_sql = " Campo Movimento do Servidor não informado.";
       $this->erro_campo = "rh153_consignadomovimentoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh153_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh153_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh153_rubrica == null ){ 
       $this->rh153_rubrica = "null";
     }
     if($this->rh153_valordescontar == null ){ 
       $this->rh153_valordescontar = "null";
     }
     if($this->rh153_valordescontado == null ){ 
       $this->rh153_valordescontado = "null";
     }
     if($this->rh153_parcela == null ){ 
       $this->rh153_parcela = "null";
     }
     if($this->rh153_totalparcelas == null ){ 
       $this->rh153_totalparcelas = "null";
     }
     if($rh153_sequencial == "" || $rh153_sequencial == null ){
       $result = db_query("select nextval('rhconsignadomovimentoservidorrubrica_rh153_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhconsignadomovimentoservidorrubrica_rh153_sequencial_seq do campo: rh153_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh153_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhconsignadomovimentoservidorrubrica_rh153_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh153_sequencial)){
         $this->erro_sql = " Campo rh153_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh153_sequencial = $rh153_sequencial; 
       }
     }
     if(($this->rh153_sequencial == null) || ($this->rh153_sequencial == "") ){ 
       $this->erro_sql = " Campo rh153_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhconsignadomovimentoservidorrubrica(
                                       rh153_sequencial 
                                      ,rh153_consignadomovimentoservidor 
                                      ,rh153_instit 
                                      ,rh153_rubrica 
                                      ,rh153_valordescontar 
                                      ,rh153_valordescontado 
                                      ,rh153_parcela 
                                      ,rh153_totalparcelas 
                       )
                values (
                                $this->rh153_sequencial 
                               ,$this->rh153_consignadomovimentoservidor 
                               ,$this->rh153_instit 
                               ,'$this->rh153_rubrica' 
                               ,'$this->rh153_valordescontar' 
                               ,'$this->rh153_valordescontado' 
                               ,'$this->rh153_parcela' 
                               ,'$this->rh153_totalparcelas' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhconsignadomovimentoservidorrubrica ($this->rh153_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhconsignadomovimentoservidorrubrica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhconsignadomovimentoservidorrubrica ($this->rh153_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh153_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh153_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21017,'$this->rh153_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3787,21017,'','".AddSlashes(pg_result($resaco,0,'rh153_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3787,21018,'','".AddSlashes(pg_result($resaco,0,'rh153_consignadomovimentoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3787,21020,'','".AddSlashes(pg_result($resaco,0,'rh153_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3787,21019,'','".AddSlashes(pg_result($resaco,0,'rh153_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3787,21021,'','".AddSlashes(pg_result($resaco,0,'rh153_valordescontar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3787,21022,'','".AddSlashes(pg_result($resaco,0,'rh153_valordescontado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3787,21023,'','".AddSlashes(pg_result($resaco,0,'rh153_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3787,21024,'','".AddSlashes(pg_result($resaco,0,'rh153_totalparcelas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh153_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhconsignadomovimentoservidorrubrica set ";
     $virgula = "";
     if(trim($this->rh153_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_sequencial"])){ 
       $sql  .= $virgula." rh153_sequencial = $this->rh153_sequencial ";
       $virgula = ",";
       if(trim($this->rh153_sequencial) == null ){ 
         $this->erro_sql = " Campo  não informado.";
         $this->erro_campo = "rh153_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh153_consignadomovimentoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_consignadomovimentoservidor"])){ 
       $sql  .= $virgula." rh153_consignadomovimentoservidor = $this->rh153_consignadomovimentoservidor ";
       $virgula = ",";
       if(trim($this->rh153_consignadomovimentoservidor) == null ){ 
         $this->erro_sql = " Campo Movimento do Servidor não informado.";
         $this->erro_campo = "rh153_consignadomovimentoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh153_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_instit"])){ 
       $sql  .= $virgula." rh153_instit = $this->rh153_instit ";
       $virgula = ",";
       if(trim($this->rh153_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh153_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh153_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_rubrica"])){ 
        if(trim($this->rh153_rubrica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh153_rubrica"])){ 
           $this->rh153_rubrica = "0" ; 
        } 
       $sql  .= $virgula." rh153_rubrica = '$this->rh153_rubrica' ";
       $virgula = ",";
     }
     if(trim($this->rh153_valordescontar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_valordescontar"])){ 
       $sql  .= $virgula." rh153_valordescontar = '$this->rh153_valordescontar' ";
       $virgula = ",";
     }
     if(trim($this->rh153_valordescontado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_valordescontado"])){ 
       $sql  .= $virgula." rh153_valordescontado = '$this->rh153_valordescontado' ";
       $virgula = ",";
     }
     if(trim($this->rh153_parcela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_parcela"])){ 
       $sql  .= $virgula." rh153_parcela = '$this->rh153_parcela' ";
       $virgula = ",";
     }
     if(trim($this->rh153_totalparcelas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh153_totalparcelas"])){ 
       $sql  .= $virgula." rh153_totalparcelas = '$this->rh153_totalparcelas' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh153_sequencial!=null){
       $sql .= " rh153_sequencial = $this->rh153_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh153_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21017,'$this->rh153_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_sequencial"]) || $this->rh153_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3787,21017,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_sequencial'))."','$this->rh153_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_consignadomovimentoservidor"]) || $this->rh153_consignadomovimentoservidor != "")
             $resac = db_query("insert into db_acount values($acount,3787,21018,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_consignadomovimentoservidor'))."','$this->rh153_consignadomovimentoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_instit"]) || $this->rh153_instit != "")
             $resac = db_query("insert into db_acount values($acount,3787,21020,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_instit'))."','$this->rh153_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_rubrica"]) || $this->rh153_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3787,21019,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_rubrica'))."','$this->rh153_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_valordescontar"]) || $this->rh153_valordescontar != "")
             $resac = db_query("insert into db_acount values($acount,3787,21021,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_valordescontar'))."','$this->rh153_valordescontar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_valordescontado"]) || $this->rh153_valordescontado != "")
             $resac = db_query("insert into db_acount values($acount,3787,21022,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_valordescontado'))."','$this->rh153_valordescontado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_parcela"]) || $this->rh153_parcela != "")
             $resac = db_query("insert into db_acount values($acount,3787,21023,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_parcela'))."','$this->rh153_parcela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh153_totalparcelas"]) || $this->rh153_totalparcelas != "")
             $resac = db_query("insert into db_acount values($acount,3787,21024,'".AddSlashes(pg_result($resaco,$conresaco,'rh153_totalparcelas'))."','$this->rh153_totalparcelas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhconsignadomovimentoservidorrubrica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh153_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhconsignadomovimentoservidorrubrica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh153_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh153_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh153_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh153_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21017,'$rh153_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3787,21017,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3787,21018,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_consignadomovimentoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3787,21020,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3787,21019,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3787,21021,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_valordescontar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3787,21022,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_valordescontado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3787,21023,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3787,21024,'','".AddSlashes(pg_result($resaco,$iresaco,'rh153_totalparcelas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhconsignadomovimentoservidorrubrica
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh153_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh153_sequencial = $rh153_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhconsignadomovimentoservidorrubrica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh153_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhconsignadomovimentoservidorrubrica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh153_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh153_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhconsignadomovimentoservidorrubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh153_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhconsignadomovimentoservidorrubrica ";
     $sql .= "      inner join rhconsignadomovimentoservidorrubrica  on  rhconsignadomovimentoservidorrubrica.rh153_sequencial = rhconsignadomovimentoservidorrubrica.rh153_consignadomovimentoservidor";
     $sql .= "      inner join rhconsignadomovimentoservidorrubrica  on  rhconsignadomovimentoservidorrubrica.rh153_sequencial = rhconsignadomovimentoservidorrubrica.rh153_consignadomovimentoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh153_sequencial)) {
         $sql2 .= " where rhconsignadomovimentoservidorrubrica.rh153_sequencial = $rh153_sequencial "; 
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
   public function sql_query_file ($rh153_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhconsignadomovimentoservidorrubrica ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh153_sequencial)){
         $sql2 .= " where rhconsignadomovimentoservidorrubrica.rh153_sequencial = $rh153_sequencial "; 
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
