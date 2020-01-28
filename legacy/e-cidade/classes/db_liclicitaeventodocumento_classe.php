<?
//MODULO: licitacao
//CLASSE DA ENTIDADE liclicitaeventodocumento
class cl_liclicitaeventodocumento { 
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
   var $l47_sequencial = 0; 
   var $l47_liclicitaevento = 0; 
   var $l47_nomearquivo = null; 
   var $l47_arquivo = 0; 
   var $l47_tipodocumento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l47_sequencial = int4 = Código 
                 l47_liclicitaevento = int4 = Eventos da Licitação 
                 l47_nomearquivo = varchar(200) = Nome do Arquivo 
                 l47_arquivo = oid = Identificador do Arquivo 
                 l47_tipodocumento = varchar(3) = Tipo de Documento 
                 ";
   //funcao construtor da classe 
   function cl_liclicitaeventodocumento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitaeventodocumento"); 
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
       $this->l47_sequencial = ($this->l47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l47_sequencial"]:$this->l47_sequencial);
       $this->l47_liclicitaevento = ($this->l47_liclicitaevento == ""?@$GLOBALS["HTTP_POST_VARS"]["l47_liclicitaevento"]:$this->l47_liclicitaevento);
       $this->l47_nomearquivo = ($this->l47_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["l47_nomearquivo"]:$this->l47_nomearquivo);
       $this->l47_arquivo = ($this->l47_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["l47_arquivo"]:$this->l47_arquivo);
       $this->l47_tipodocumento = ($this->l47_tipodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["l47_tipodocumento"]:$this->l47_tipodocumento);
     }else{
       $this->l47_sequencial = ($this->l47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l47_sequencial"]:$this->l47_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($l47_sequencial){ 
      $this->atualizacampos();
     if($this->l47_liclicitaevento == null ){ 
       $this->erro_sql = " Campo Eventos da Licitação não informado.";
       $this->erro_campo = "l47_liclicitaevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l47_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo não informado.";
       $this->erro_campo = "l47_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l47_arquivo == null ){ 
       $this->erro_sql = " Campo Identificador do Arquivo não informado.";
       $this->erro_campo = "l47_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l47_tipodocumento == null ){ 
       $this->erro_sql = " Campo Tipo de Documento não informado.";
       $this->erro_campo = "l47_tipodocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l47_sequencial == "" || $l47_sequencial == null ){
       $result = db_query("select nextval('liclicitaeventodocumento_l47_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitaeventodocumento_l47_sequencial_seq do campo: l47_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l47_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitaeventodocumento_l47_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l47_sequencial)){
         $this->erro_sql = " Campo l47_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l47_sequencial = $l47_sequencial; 
       }
     }
     if(($this->l47_sequencial == null) || ($this->l47_sequencial == "") ){ 
       $this->erro_sql = " Campo l47_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitaeventodocumento(
                                       l47_sequencial 
                                      ,l47_liclicitaevento 
                                      ,l47_nomearquivo 
                                      ,l47_arquivo 
                                      ,l47_tipodocumento 
                       )
                values (
                                $this->l47_sequencial 
                               ,$this->l47_liclicitaevento 
                               ,'$this->l47_nomearquivo' 
                               ,$this->l47_arquivo 
                               ,'$this->l47_tipodocumento' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documentos Vinculados ao Evento da Licitação ($this->l47_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documentos Vinculados ao Evento da Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documentos Vinculados ao Evento da Licitação ($this->l47_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l47_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l47_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21749,'$this->l47_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3918,21749,'','".AddSlashes(pg_result($resaco,0,'l47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3918,21750,'','".AddSlashes(pg_result($resaco,0,'l47_liclicitaevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3918,21752,'','".AddSlashes(pg_result($resaco,0,'l47_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3918,21751,'','".AddSlashes(pg_result($resaco,0,'l47_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3918,21753,'','".AddSlashes(pg_result($resaco,0,'l47_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($l47_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liclicitaeventodocumento set ";
     $virgula = "";
     if(trim($this->l47_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l47_sequencial"])){ 
       $sql  .= $virgula." l47_sequencial = $this->l47_sequencial ";
       $virgula = ",";
       if(trim($this->l47_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "l47_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l47_liclicitaevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l47_liclicitaevento"])){ 
       $sql  .= $virgula." l47_liclicitaevento = $this->l47_liclicitaevento ";
       $virgula = ",";
       if(trim($this->l47_liclicitaevento) == null ){ 
         $this->erro_sql = " Campo Eventos da Licitação não informado.";
         $this->erro_campo = "l47_liclicitaevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l47_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l47_nomearquivo"])){ 
       $sql  .= $virgula." l47_nomearquivo = '$this->l47_nomearquivo' ";
       $virgula = ",";
       if(trim($this->l47_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo não informado.";
         $this->erro_campo = "l47_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l47_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l47_arquivo"])){ 
       $sql  .= $virgula." l47_arquivo = $this->l47_arquivo ";
       $virgula = ",";
       if(trim($this->l47_arquivo) == null ){ 
         $this->erro_sql = " Campo Identificador do Arquivo não informado.";
         $this->erro_campo = "l47_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l47_tipodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l47_tipodocumento"])){ 
       $sql  .= $virgula." l47_tipodocumento = '$this->l47_tipodocumento' ";
       $virgula = ",";
       if(trim($this->l47_tipodocumento) == null ){ 
         $this->erro_sql = " Campo Tipo de Documento não informado.";
         $this->erro_campo = "l47_tipodocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l47_sequencial!=null){
       $sql .= " l47_sequencial = $this->l47_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l47_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21749,'$this->l47_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l47_sequencial"]) || $this->l47_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3918,21749,'".AddSlashes(pg_result($resaco,$conresaco,'l47_sequencial'))."','$this->l47_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l47_liclicitaevento"]) || $this->l47_liclicitaevento != "")
             $resac = db_query("insert into db_acount values($acount,3918,21750,'".AddSlashes(pg_result($resaco,$conresaco,'l47_liclicitaevento'))."','$this->l47_liclicitaevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l47_nomearquivo"]) || $this->l47_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,3918,21752,'".AddSlashes(pg_result($resaco,$conresaco,'l47_nomearquivo'))."','$this->l47_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l47_arquivo"]) || $this->l47_arquivo != "")
             $resac = db_query("insert into db_acount values($acount,3918,21751,'".AddSlashes(pg_result($resaco,$conresaco,'l47_arquivo'))."','$this->l47_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l47_tipodocumento"]) || $this->l47_tipodocumento != "")
             $resac = db_query("insert into db_acount values($acount,3918,21753,'".AddSlashes(pg_result($resaco,$conresaco,'l47_tipodocumento'))."','$this->l47_tipodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos Vinculados ao Evento da Licitação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l47_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos Vinculados ao Evento da Licitação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($l47_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l47_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21749,'$l47_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3918,21749,'','".AddSlashes(pg_result($resaco,$iresaco,'l47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3918,21750,'','".AddSlashes(pg_result($resaco,$iresaco,'l47_liclicitaevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3918,21752,'','".AddSlashes(pg_result($resaco,$iresaco,'l47_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3918,21751,'','".AddSlashes(pg_result($resaco,$iresaco,'l47_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3918,21753,'','".AddSlashes(pg_result($resaco,$iresaco,'l47_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from liclicitaeventodocumento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l47_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l47_sequencial = $l47_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos Vinculados ao Evento da Licitação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l47_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos Vinculados ao Evento da Licitação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l47_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitaeventodocumento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($l47_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from liclicitaeventodocumento ";
     $sql .= "      inner join liclicitaevento  on  liclicitaevento.l46_sequencial = liclicitaeventodocumento.l47_liclicitaevento";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = liclicitaevento.l46_cgm";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitaevento.l46_liclicita";
     $sql .= "      inner join liclicitatipoevento  on  liclicitatipoevento.l45_sequencial = liclicitaevento.l46_liclicitatipoevento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l47_sequencial)) {
         $sql2 .= " where liclicitaeventodocumento.l47_sequencial = $l47_sequencial "; 
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
   public function sql_query_file ($l47_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from liclicitaeventodocumento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l47_sequencial)){
         $sql2 .= " where liclicitaeventodocumento.l47_sequencial = $l47_sequencial "; 
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
