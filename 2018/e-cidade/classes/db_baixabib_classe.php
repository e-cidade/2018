<?
//MODULO: biblioteca
//CLASSE DA ENTIDADE baixabib
class cl_baixabib { 
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
   var $bi08_codigo = 0; 
   var $bi08_descr = null; 
   var $bi08_exemplar = 0; 
   var $bi08_inclusao_dia = null; 
   var $bi08_inclusao_mes = null; 
   var $bi08_inclusao_ano = null; 
   var $bi08_inclusao = null; 
   var $bi08_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi08_codigo = int8 = Código 
                 bi08_descr = text = Descrição da Baixa 
                 bi08_exemplar = int8 = Exemplar 
                 bi08_inclusao = date = Data da Baixa 
                 bi08_usuario = int8 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_baixabib() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("baixabib"); 
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
       $this->bi08_codigo = ($this->bi08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_codigo"]:$this->bi08_codigo);
       $this->bi08_descr = ($this->bi08_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_descr"]:$this->bi08_descr);
       $this->bi08_exemplar = ($this->bi08_exemplar == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_exemplar"]:$this->bi08_exemplar);
       if($this->bi08_inclusao == ""){
         $this->bi08_inclusao_dia = ($this->bi08_inclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_inclusao_dia"]:$this->bi08_inclusao_dia);
         $this->bi08_inclusao_mes = ($this->bi08_inclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_inclusao_mes"]:$this->bi08_inclusao_mes);
         $this->bi08_inclusao_ano = ($this->bi08_inclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_inclusao_ano"]:$this->bi08_inclusao_ano);
         if($this->bi08_inclusao_dia != ""){
            $this->bi08_inclusao = $this->bi08_inclusao_ano."-".$this->bi08_inclusao_mes."-".$this->bi08_inclusao_dia;
         }
       }
       $this->bi08_usuario = ($this->bi08_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_usuario"]:$this->bi08_usuario);
     }else{
       $this->bi08_codigo = ($this->bi08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi08_codigo"]:$this->bi08_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($bi08_codigo){ 
      $this->atualizacampos();
     if($this->bi08_descr == null ){ 
       $this->erro_sql = " Campo Descrição da Baixa não informado.";
       $this->erro_campo = "bi08_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi08_exemplar == null ){ 
       $this->erro_sql = " Campo Exemplar não informado.";
       $this->erro_campo = "bi08_exemplar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi08_inclusao == null ){ 
       $this->erro_sql = " Campo Data da Baixa não informado.";
       $this->erro_campo = "bi08_inclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi08_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "bi08_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi08_codigo == "" || $bi08_codigo == null ){
       $result = db_query("select nextval('baixabib_bi08_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: baixabib_bi08_codigo_seq do campo: bi08_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi08_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from baixabib_bi08_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi08_codigo)){
         $this->erro_sql = " Campo bi08_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi08_codigo = $bi08_codigo; 
       }
     }
     if(($this->bi08_codigo == null) || ($this->bi08_codigo == "") ){ 
       $this->erro_sql = " Campo bi08_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into baixabib(
                                       bi08_codigo 
                                      ,bi08_descr 
                                      ,bi08_exemplar 
                                      ,bi08_inclusao 
                                      ,bi08_usuario 
                       )
                values (
                                $this->bi08_codigo 
                               ,'$this->bi08_descr' 
                               ,$this->bi08_exemplar 
                               ,".($this->bi08_inclusao == "null" || $this->bi08_inclusao == ""?"null":"'".$this->bi08_inclusao."'")." 
                               ,$this->bi08_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Baixa de exemplares do acervo ($this->bi08_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Baixa de exemplares do acervo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Baixa de exemplares do acervo ($this->bi08_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi08_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi08_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008133,'$this->bi08_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1008016,1008133,'','".AddSlashes(pg_result($resaco,0,'bi08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008016,1008134,'','".AddSlashes(pg_result($resaco,0,'bi08_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008016,1008136,'','".AddSlashes(pg_result($resaco,0,'bi08_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008016,1008188,'','".AddSlashes(pg_result($resaco,0,'bi08_inclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008016,1008937,'','".AddSlashes(pg_result($resaco,0,'bi08_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($bi08_codigo=null) { 
      $this->atualizacampos();
     $sql = " update baixabib set ";
     $virgula = "";
     if(trim($this->bi08_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi08_codigo"])){ 
       $sql  .= $virgula." bi08_codigo = $this->bi08_codigo ";
       $virgula = ",";
       if(trim($this->bi08_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "bi08_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi08_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi08_descr"])){ 
       $sql  .= $virgula." bi08_descr = '$this->bi08_descr' ";
       $virgula = ",";
       if(trim($this->bi08_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da Baixa não informado.";
         $this->erro_campo = "bi08_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi08_exemplar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi08_exemplar"])){ 
       $sql  .= $virgula." bi08_exemplar = $this->bi08_exemplar ";
       $virgula = ",";
       if(trim($this->bi08_exemplar) == null ){ 
         $this->erro_sql = " Campo Exemplar não informado.";
         $this->erro_campo = "bi08_exemplar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi08_inclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi08_inclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi08_inclusao_dia"] !="") ){ 
       $sql  .= $virgula." bi08_inclusao = '$this->bi08_inclusao' ";
       $virgula = ",";
       if(trim($this->bi08_inclusao) == null ){ 
         $this->erro_sql = " Campo Data da Baixa não informado.";
         $this->erro_campo = "bi08_inclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi08_inclusao_dia"])){ 
         $sql  .= $virgula." bi08_inclusao = null ";
         $virgula = ",";
         if(trim($this->bi08_inclusao) == null ){ 
           $this->erro_sql = " Campo Data da Baixa não informado.";
           $this->erro_campo = "bi08_inclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi08_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi08_usuario"])){ 
       $sql  .= $virgula." bi08_usuario = $this->bi08_usuario ";
       $virgula = ",";
       if(trim($this->bi08_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "bi08_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi08_codigo!=null){
       $sql .= " bi08_codigo = $this->bi08_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi08_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008133,'$this->bi08_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi08_codigo"]) || $this->bi08_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1008016,1008133,'".AddSlashes(pg_result($resaco,$conresaco,'bi08_codigo'))."','$this->bi08_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi08_descr"]) || $this->bi08_descr != "")
             $resac = db_query("insert into db_acount values($acount,1008016,1008134,'".AddSlashes(pg_result($resaco,$conresaco,'bi08_descr'))."','$this->bi08_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi08_exemplar"]) || $this->bi08_exemplar != "")
             $resac = db_query("insert into db_acount values($acount,1008016,1008136,'".AddSlashes(pg_result($resaco,$conresaco,'bi08_exemplar'))."','$this->bi08_exemplar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi08_inclusao"]) || $this->bi08_inclusao != "")
             $resac = db_query("insert into db_acount values($acount,1008016,1008188,'".AddSlashes(pg_result($resaco,$conresaco,'bi08_inclusao'))."','$this->bi08_inclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi08_usuario"]) || $this->bi08_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1008016,1008937,'".AddSlashes(pg_result($resaco,$conresaco,'bi08_usuario'))."','$this->bi08_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa de exemplares do acervo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Baixa de exemplares do acervo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($bi08_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($bi08_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008133,'$bi08_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1008016,1008133,'','".AddSlashes(pg_result($resaco,$iresaco,'bi08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008016,1008134,'','".AddSlashes(pg_result($resaco,$iresaco,'bi08_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008016,1008136,'','".AddSlashes(pg_result($resaco,$iresaco,'bi08_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008016,1008188,'','".AddSlashes(pg_result($resaco,$iresaco,'bi08_inclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008016,1008937,'','".AddSlashes(pg_result($resaco,$iresaco,'bi08_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from baixabib
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($bi08_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " bi08_codigo = $bi08_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa de exemplares do acervo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Baixa de exemplares do acervo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi08_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:baixabib";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($bi08_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from baixabib ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = baixabib.bi08_usuario";
     $sql .= "      inner join exemplar  on  exemplar.bi23_codigo = baixabib.bi08_exemplar";
     $sql .= "      inner join aquisicao  on  aquisicao.bi04_codigo = exemplar.bi23_aquisicao";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = exemplar.bi23_acervo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi08_codigo)) {
         $sql2 .= " where baixabib.bi08_codigo = $bi08_codigo "; 
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
   public function sql_query_file ($bi08_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from baixabib ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi08_codigo)){
         $sql2 .= " where baixabib.bi08_codigo = $bi08_codigo "; 
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

   function sql_query_baixa_acervo ( $bi08_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from baixabib ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = baixabib.bi08_usuario";
    $sql .= "      inner join exemplar  on  exemplar.bi23_codigo = baixabib.bi08_exemplar";
    $sql .= "      inner join acervo  on  acervo.bi06_seq = exemplar.bi23_acervo";
    $sql .= "      left  join colecaoacervo  on  colecaoacervo.bi29_sequencial = acervo.bi06_colecaoacervo";
    $sql2 = "";
    if($dbwhere==""){
      if($bi08_codigo!=null ){
        $sql2 .= " where baixa.bi08_codigo = $bi08_codigo ";
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
