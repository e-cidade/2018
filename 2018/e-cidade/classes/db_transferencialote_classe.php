<?
//MODULO: escola
//CLASSE DA ENTIDADE transferencialote
class cl_transferencialote {
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
   var $ed137_sequencial = 0;
   var $ed137_escolaorigem = 0;
   var $ed137_usuario = 0;
   var $ed137_escolarede = 'f';
   var $ed137_escola = 0;
   var $ed137_data = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed137_sequencial = int4 = Código
                 ed137_escolaorigem = int4 = Escola de Origem
                 ed137_usuario = int4 = Usuário
                 ed137_escolarede = bool = Tipo de Escola
                 ed137_escola = int4 = Escola
                 ed137_data = varchar(20) = Data
                 ";
   //funcao construtor da classe
   function cl_transferencialote() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("transferencialote");
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
       $this->ed137_sequencial = ($this->ed137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed137_sequencial"]:$this->ed137_sequencial);
       $this->ed137_escolaorigem = ($this->ed137_escolaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed137_escolaorigem"]:$this->ed137_escolaorigem);
       $this->ed137_usuario = ($this->ed137_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed137_usuario"]:$this->ed137_usuario);
       $this->ed137_escolarede = ($this->ed137_escolarede == ""?@$GLOBALS["HTTP_POST_VARS"]["ed137_escolarede"]:$this->ed137_escolarede);
       $this->ed137_escola = ($this->ed137_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed137_escola"]:$this->ed137_escola);
       $this->ed137_data = ($this->ed137_data == ""?@$GLOBALS["HTTP_POST_VARS"]["ed137_data"]:$this->ed137_data);
     }else{
       $this->ed137_sequencial = ($this->ed137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed137_sequencial"]:$this->ed137_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed137_sequencial){

      $this->atualizacampos();
     if($this->ed137_escolaorigem == null ){
       $this->erro_sql = " Campo Escola de Origem não informado.";
       $this->erro_campo = "ed137_escolaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed137_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "ed137_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->ed137_escolarede == null ){
       $this->erro_sql = " Campo Tipo de Escola não informado.";
       $this->erro_campo = "ed137_escolarede";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed137_escola == null ){
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed137_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed137_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "ed137_data";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed137_sequencial == "" || $ed137_sequencial == null ){
       $result = db_query("select nextval('transferencialote_ed137_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: transferencialote_ed137_sequencial_seq do campo: ed137_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed137_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from transferencialote_ed137_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed137_sequencial)){
         $this->erro_sql = " Campo ed137_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed137_sequencial = $ed137_sequencial;
       }
     }
     if(($this->ed137_sequencial == null) || ($this->ed137_sequencial == "") ){
       $this->erro_sql = " Campo ed137_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into transferencialote(
                                       ed137_sequencial
                                      ,ed137_escolaorigem
                                      ,ed137_usuario
                                      ,ed137_escolarede
                                      ,ed137_escola
                                      ,ed137_data
                       )
                values (
                                $this->ed137_sequencial
                               ,$this->ed137_escolaorigem
                               ,$this->ed137_usuario
                               ,'$this->ed137_escolarede'
                               ,$this->ed137_escola
                               ,'$this->ed137_data'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Transferência em Lote ($this->ed137_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Transferência em Lote já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Transferência em Lote ($this->ed137_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed137_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed137_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22013,'$this->ed137_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3961,22013,'','".AddSlashes(pg_result($resaco,0,'ed137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3961,22014,'','".AddSlashes(pg_result($resaco,0,'ed137_escolaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3961,22015,'','".AddSlashes(pg_result($resaco,0,'ed137_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3961,22016,'','".AddSlashes(pg_result($resaco,0,'ed137_escolarede'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3961,22017,'','".AddSlashes(pg_result($resaco,0,'ed137_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3961,22018,'','".AddSlashes(pg_result($resaco,0,'ed137_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed137_sequencial=null) {
      $this->atualizacampos();
     $sql = " update transferencialote set ";
     $virgula = "";
     if(trim($this->ed137_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed137_sequencial"])){
       $sql  .= $virgula." ed137_sequencial = $this->ed137_sequencial ";
       $virgula = ",";
       if(trim($this->ed137_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed137_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed137_escolaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed137_escolaorigem"])){
       $sql  .= $virgula." ed137_escolaorigem = $this->ed137_escolaorigem ";
       $virgula = ",";
       if(trim($this->ed137_escolaorigem) == null ){
         $this->erro_sql = " Campo Escola de Origem não informado.";
         $this->erro_campo = "ed137_escolaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed137_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed137_usuario"])){
       $sql  .= $virgula." ed137_usuario = $this->ed137_usuario ";
       $virgula = ",";
       if(trim($this->ed137_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "ed137_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed137_escolarede)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed137_escolarede"])){
       $sql  .= $virgula." ed137_escolarede = '$this->ed137_escolarede' ";
       $virgula = ",";
       if(trim($this->ed137_escolarede) == null ){
         $this->erro_sql = " Campo Tipo de Escola não informado.";
         $this->erro_campo = "ed137_escolarede";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed137_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed137_escola"])){
       $sql  .= $virgula." ed137_escola = $this->ed137_escola ";
       $virgula = ",";
       if(trim($this->ed137_escola) == null ){
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed137_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed137_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed137_data"])){
       $sql  .= $virgula." ed137_data = '$this->ed137_data' ";
       $virgula = ",";
       if(trim($this->ed137_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "ed137_data";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed137_sequencial!=null){
       $sql .= " ed137_sequencial = $this->ed137_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed137_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22013,'$this->ed137_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed137_sequencial"]) || $this->ed137_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3961,22013,'".AddSlashes(pg_result($resaco,$conresaco,'ed137_sequencial'))."','$this->ed137_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed137_escolaorigem"]) || $this->ed137_escolaorigem != "")
             $resac = db_query("insert into db_acount values($acount,3961,22014,'".AddSlashes(pg_result($resaco,$conresaco,'ed137_escolaorigem'))."','$this->ed137_escolaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed137_usuario"]) || $this->ed137_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3961,22015,'".AddSlashes(pg_result($resaco,$conresaco,'ed137_usuario'))."','$this->ed137_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed137_escolarede"]) || $this->ed137_escolarede != "")
             $resac = db_query("insert into db_acount values($acount,3961,22016,'".AddSlashes(pg_result($resaco,$conresaco,'ed137_escolarede'))."','$this->ed137_escolarede',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed137_escola"]) || $this->ed137_escola != "")
             $resac = db_query("insert into db_acount values($acount,3961,22017,'".AddSlashes(pg_result($resaco,$conresaco,'ed137_escola'))."','$this->ed137_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed137_data"]) || $this->ed137_data != "")
             $resac = db_query("insert into db_acount values($acount,3961,22018,'".AddSlashes(pg_result($resaco,$conresaco,'ed137_data'))."','$this->ed137_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência em Lote não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Transferência em Lote não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed137_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed137_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22013,'$ed137_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3961,22013,'','".AddSlashes(pg_result($resaco,$iresaco,'ed137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3961,22014,'','".AddSlashes(pg_result($resaco,$iresaco,'ed137_escolaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3961,22015,'','".AddSlashes(pg_result($resaco,$iresaco,'ed137_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3961,22016,'','".AddSlashes(pg_result($resaco,$iresaco,'ed137_escolarede'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3961,22017,'','".AddSlashes(pg_result($resaco,$iresaco,'ed137_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3961,22018,'','".AddSlashes(pg_result($resaco,$iresaco,'ed137_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from transferencialote
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed137_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed137_sequencial = $ed137_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência em Lote não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Transferência em Lote não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed137_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:transferencialote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed137_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from transferencialote ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = transferencialote.ed137_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = transferencialote.ed137_escolaorigem";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed137_sequencial)) {
         $sql2 .= " where transferencialote.ed137_sequencial = $ed137_sequencial ";
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
   public function sql_query_file ($ed137_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from transferencialote ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed137_sequencial)){
         $sql2 .= " where transferencialote.ed137_sequencial = $ed137_sequencial ";
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
