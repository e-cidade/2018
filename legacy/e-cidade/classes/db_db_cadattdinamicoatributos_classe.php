<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE db_cadattdinamicoatributos
class cl_db_cadattdinamicoatributos {
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
   var $db109_sequencial = 0;
   var $db109_db_cadattdinamico = 0;
   var $db109_codcam = 0;
   var $db109_descricao = null;
   var $db109_valordefault = null;
   var $db109_tipo = 0;
   var $db109_nome = null;
   var $db109_obrigatorio = 'f';
   var $db109_ativo = 't';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db109_sequencial = int4 = Código Sequencial 
                 db109_db_cadattdinamico = int4 = Código Atributo Dinâmico 
                 db109_codcam = int4 = Código Campo 
                 db109_descricao = varchar(100) = Descrição 
                 db109_valordefault = varchar(100) = Valor Default 
                 db109_tipo = int4 = Tipo 
                 db109_nome = varchar(100) = Nome do Campo 
                 db109_obrigatorio = bool = Preenchimento Obrigatório 
                 db109_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe
   function cl_db_cadattdinamicoatributos() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cadattdinamicoatributos");
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
       $this->db109_sequencial = ($this->db109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_sequencial"]:$this->db109_sequencial);
       $this->db109_db_cadattdinamico = ($this->db109_db_cadattdinamico == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_db_cadattdinamico"]:$this->db109_db_cadattdinamico);
       $this->db109_codcam = ($this->db109_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_codcam"]:$this->db109_codcam);
       $this->db109_descricao = ($this->db109_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_descricao"]:$this->db109_descricao);
       $this->db109_valordefault = ($this->db109_valordefault == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_valordefault"]:$this->db109_valordefault);
       $this->db109_tipo = ($this->db109_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_tipo"]:$this->db109_tipo);
       $this->db109_nome = ($this->db109_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_nome"]:$this->db109_nome);
       $this->db109_obrigatorio = ($this->db109_obrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["db109_obrigatorio"]:$this->db109_obrigatorio);
       $this->db109_ativo = ($this->db109_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["db109_ativo"]:$this->db109_ativo);
     }else{
       $this->db109_sequencial = ($this->db109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db109_sequencial"]:$this->db109_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db109_sequencial){
      $this->atualizacampos();
     if($this->db109_db_cadattdinamico == null ){
       $this->erro_sql = " Campo Código Atributo Dinâmico não informado.";
       $this->erro_campo = "db109_db_cadattdinamico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db109_codcam == null ){
       $this->db109_codcam = 'null';
     }
     if($this->db109_descricao == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "db109_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db109_tipo == null ){
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "db109_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db109_obrigatorio == null ){
       $this->db109_obrigatorio = "false";
     }
     if($this->db109_ativo == null ){
       $this->db109_ativo = "t";
     }
     if($db109_sequencial == "" || $db109_sequencial == null ){
       $result = db_query("select nextval('db_cadattdinamicoatributos_db109_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cadattdinamicoatributos_db109_sequencial_seq do campo: db109_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->db109_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from db_cadattdinamicoatributos_db109_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db109_sequencial)){
         $this->erro_sql = " Campo db109_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db109_sequencial = $db109_sequencial;
       }
     }
     if(($this->db109_sequencial == null) || ($this->db109_sequencial == "") ){
       $this->erro_sql = " Campo db109_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cadattdinamicoatributos(
                                       db109_sequencial 
                                      ,db109_db_cadattdinamico 
                                      ,db109_codcam 
                                      ,db109_descricao 
                                      ,db109_valordefault 
                                      ,db109_tipo 
                                      ,db109_nome 
                                      ,db109_obrigatorio 
                                      ,db109_ativo 
                       )
                values (
                                $this->db109_sequencial 
                               ,$this->db109_db_cadattdinamico 
                               ,$this->db109_codcam 
                               ,'$this->db109_descricao' 
                               ,'$this->db109_valordefault' 
                               ,$this->db109_tipo 
                               ,'$this->db109_nome' 
                               ,'$this->db109_obrigatorio' 
                               ,'$this->db109_ativo' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "db_cadattdinamicoatributos ($this->db109_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "db_cadattdinamicoatributos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "db_cadattdinamicoatributos ($this->db109_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db109_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17885,'$this->db109_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3163,17885,'','".AddSlashes(pg_result($resaco,0,'db109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17886,'','".AddSlashes(pg_result($resaco,0,'db109_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17887,'','".AddSlashes(pg_result($resaco,0,'db109_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17888,'','".AddSlashes(pg_result($resaco,0,'db109_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17889,'','".AddSlashes(pg_result($resaco,0,'db109_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,17890,'','".AddSlashes(pg_result($resaco,0,'db109_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,21709,'','".AddSlashes(pg_result($resaco,0,'db109_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,1009287,'','".AddSlashes(pg_result($resaco,0,'db109_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3163,1009310,'','".AddSlashes(pg_result($resaco,0,'db109_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($db109_sequencial=null) {
      $this->atualizacampos();
     $sql = " update db_cadattdinamicoatributos set ";
     $virgula = "";
     if(trim($this->db109_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_sequencial"])){
       $sql  .= $virgula." db109_sequencial = $this->db109_sequencial ";
       $virgula = ",";
       if(trim($this->db109_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "db109_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db109_db_cadattdinamico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_db_cadattdinamico"])){
       $sql  .= $virgula." db109_db_cadattdinamico = $this->db109_db_cadattdinamico ";
       $virgula = ",";
       if(trim($this->db109_db_cadattdinamico) == null ){
         $this->erro_sql = " Campo Código Atributo Dinâmico não informado.";
         $this->erro_campo = "db109_db_cadattdinamico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db109_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_codcam"])){
       $sql  .= $virgula." db109_codcam = $this->db109_codcam ";
       $virgula = ",";
     }
     if(trim($this->db109_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_descricao"])){
       $sql  .= $virgula." db109_descricao = '$this->db109_descricao' ";
       $virgula = ",";
       if(trim($this->db109_descricao) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "db109_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db109_valordefault)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_valordefault"])){
       $sql  .= $virgula." db109_valordefault = '$this->db109_valordefault' ";
       $virgula = ",";
     }
     if(trim($this->db109_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_tipo"])){
       $sql  .= $virgula." db109_tipo = $this->db109_tipo ";
       $virgula = ",";
       if(trim($this->db109_tipo) == null ){
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "db109_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db109_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_nome"])){
       $sql  .= $virgula." db109_nome = '$this->db109_nome' ";
       $virgula = ",";
     }
     if(trim($this->db109_obrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_obrigatorio"])){
       $sql  .= $virgula." db109_obrigatorio = '$this->db109_obrigatorio' ";
       $virgula = ",";
     }
     if(trim($this->db109_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db109_ativo"])){
       $sql  .= $virgula." db109_ativo = '$this->db109_ativo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db109_sequencial!=null){
       $sql .= " db109_sequencial = $this->db109_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db109_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17885,'$this->db109_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_sequencial"]) || $this->db109_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3163,17885,'".AddSlashes(pg_result($resaco,$conresaco,'db109_sequencial'))."','$this->db109_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_db_cadattdinamico"]) || $this->db109_db_cadattdinamico != "")
             $resac = db_query("insert into db_acount values($acount,3163,17886,'".AddSlashes(pg_result($resaco,$conresaco,'db109_db_cadattdinamico'))."','$this->db109_db_cadattdinamico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_codcam"]) || $this->db109_codcam != "")
             $resac = db_query("insert into db_acount values($acount,3163,17887,'".AddSlashes(pg_result($resaco,$conresaco,'db109_codcam'))."','$this->db109_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_descricao"]) || $this->db109_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3163,17888,'".AddSlashes(pg_result($resaco,$conresaco,'db109_descricao'))."','$this->db109_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_valordefault"]) || $this->db109_valordefault != "")
             $resac = db_query("insert into db_acount values($acount,3163,17889,'".AddSlashes(pg_result($resaco,$conresaco,'db109_valordefault'))."','$this->db109_valordefault',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_tipo"]) || $this->db109_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3163,17890,'".AddSlashes(pg_result($resaco,$conresaco,'db109_tipo'))."','$this->db109_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_nome"]) || $this->db109_nome != "")
             $resac = db_query("insert into db_acount values($acount,3163,21709,'".AddSlashes(pg_result($resaco,$conresaco,'db109_nome'))."','$this->db109_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_obrigatorio"]) || $this->db109_obrigatorio != "")
             $resac = db_query("insert into db_acount values($acount,3163,1009287,'".AddSlashes(pg_result($resaco,$conresaco,'db109_obrigatorio'))."','$this->db109_obrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db109_ativo"]) || $this->db109_ativo != "")
             $resac = db_query("insert into db_acount values($acount,3163,1009310,'".AddSlashes(pg_result($resaco,$conresaco,'db109_ativo'))."','$this->db109_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_cadattdinamicoatributos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "db_cadattdinamicoatributos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($db109_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db109_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,17885,'$db109_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3163,17885,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,17886,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,17887,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,17888,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,17889,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,17890,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,21709,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,1009287,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3163,1009310,'','".AddSlashes(pg_result($resaco,$iresaco,'db109_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_cadattdinamicoatributos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db109_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db109_sequencial = $db109_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_cadattdinamicoatributos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "db_cadattdinamicoatributos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$db109_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_cadattdinamicoatributos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($db109_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from db_cadattdinamicoatributos ";
     $sql .= "      inner join db_cadattdinamico  on  db_cadattdinamico.db118_sequencial = db_cadattdinamicoatributos.db109_db_cadattdinamico";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db109_sequencial)) {
         $sql2 .= " where db_cadattdinamicoatributos.db109_sequencial = $db109_sequencial ";
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
   public function sql_query_file ($db109_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_cadattdinamicoatributos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db109_sequencial)){
         $sql2 .= " where db_cadattdinamicoatributos.db109_sequencial = $db109_sequencial ";
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
