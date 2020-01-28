<?
//MODULO: escola
//CLASSE DA ENTIDADE rechumanomovimentacao
class cl_rechumanomovimentacao { 
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
   var $ed118_sequencial = 0; 
   var $ed118_escola = 0; 
   var $ed118_rechumano = 0; 
   var $ed118_usuario = 0; 
   var $ed118_data_dia = null; 
   var $ed118_data_mes = null; 
   var $ed118_data_ano = null; 
   var $ed118_data = null; 
   var $ed118_hora = null; 
   var $ed118_resumo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed118_sequencial = int4 = Código 
                 ed118_escola = int4 = Escola 
                 ed118_rechumano = int4 = Rec. Humano 
                 ed118_usuario = int4 = Usuário 
                 ed118_data = date = Data 
                 ed118_hora = varchar(5) = Hora 
                 ed118_resumo = text = Resumo 
                 ";
   //funcao construtor da classe 
   function cl_rechumanomovimentacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumanomovimentacao"); 
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
       $this->ed118_sequencial = ($this->ed118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_sequencial"]:$this->ed118_sequencial);
       $this->ed118_escola = ($this->ed118_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_escola"]:$this->ed118_escola);
       $this->ed118_rechumano = ($this->ed118_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_rechumano"]:$this->ed118_rechumano);
       $this->ed118_usuario = ($this->ed118_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_usuario"]:$this->ed118_usuario);
       if($this->ed118_data == ""){
         $this->ed118_data_dia = ($this->ed118_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_data_dia"]:$this->ed118_data_dia);
         $this->ed118_data_mes = ($this->ed118_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_data_mes"]:$this->ed118_data_mes);
         $this->ed118_data_ano = ($this->ed118_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_data_ano"]:$this->ed118_data_ano);
         if($this->ed118_data_dia != ""){
            $this->ed118_data = $this->ed118_data_ano."-".$this->ed118_data_mes."-".$this->ed118_data_dia;
         }
       }
       $this->ed118_hora = ($this->ed118_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_hora"]:$this->ed118_hora);
       $this->ed118_resumo = ($this->ed118_resumo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_resumo"]:$this->ed118_resumo);
     }else{
       $this->ed118_sequencial = ($this->ed118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed118_sequencial"]:$this->ed118_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed118_sequencial){ 
      $this->atualizacampos();
     if($this->ed118_escola == null ){ 
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed118_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed118_rechumano == null ){ 
       $this->erro_sql = " Campo Rec. Humano não informado.";
       $this->erro_campo = "ed118_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed118_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "ed118_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed118_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "ed118_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed118_hora == null ){ 
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "ed118_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed118_resumo == null ){ 
       $this->erro_sql = " Campo Resumo não informado.";
       $this->erro_campo = "ed118_resumo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed118_sequencial == "" || $ed118_sequencial == null ){
       $result = db_query("select nextval('rechumanomovimentacao_ed118_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumanomovimentacao_ed118_sequencial_seq do campo: ed118_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed118_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rechumanomovimentacao_ed118_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed118_sequencial)){
         $this->erro_sql = " Campo ed118_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed118_sequencial = $ed118_sequencial; 
       }
     }
     if(($this->ed118_sequencial == null) || ($this->ed118_sequencial == "") ){ 
       $this->erro_sql = " Campo ed118_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumanomovimentacao(
                                       ed118_sequencial 
                                      ,ed118_escola 
                                      ,ed118_rechumano 
                                      ,ed118_usuario 
                                      ,ed118_data 
                                      ,ed118_hora 
                                      ,ed118_resumo 
                       )
                values (
                                $this->ed118_sequencial 
                               ,$this->ed118_escola 
                               ,$this->ed118_rechumano 
                               ,$this->ed118_usuario 
                               ,".($this->ed118_data == "null" || $this->ed118_data == ""?"null":"'".$this->ed118_data."'")." 
                               ,'$this->ed118_hora' 
                               ,'$this->ed118_resumo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rechumanomovimentacao ($this->ed118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rechumanomovimentacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rechumanomovimentacao ($this->ed118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed118_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed118_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20560,'$this->ed118_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3699,20560,'','".AddSlashes(pg_result($resaco,0,'ed118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3699,20561,'','".AddSlashes(pg_result($resaco,0,'ed118_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3699,20562,'','".AddSlashes(pg_result($resaco,0,'ed118_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3699,20563,'','".AddSlashes(pg_result($resaco,0,'ed118_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3699,20564,'','".AddSlashes(pg_result($resaco,0,'ed118_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3699,20565,'','".AddSlashes(pg_result($resaco,0,'ed118_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3699,20566,'','".AddSlashes(pg_result($resaco,0,'ed118_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed118_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rechumanomovimentacao set ";
     $virgula = "";
     if(trim($this->ed118_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed118_sequencial"])){ 
       $sql  .= $virgula." ed118_sequencial = $this->ed118_sequencial ";
       $virgula = ",";
       if(trim($this->ed118_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed118_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed118_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed118_escola"])){ 
       $sql  .= $virgula." ed118_escola = $this->ed118_escola ";
       $virgula = ",";
       if(trim($this->ed118_escola) == null ){ 
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed118_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed118_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed118_rechumano"])){ 
       $sql  .= $virgula." ed118_rechumano = $this->ed118_rechumano ";
       $virgula = ",";
       if(trim($this->ed118_rechumano) == null ){ 
         $this->erro_sql = " Campo Rec. Humano não informado.";
         $this->erro_campo = "ed118_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed118_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed118_usuario"])){ 
       $sql  .= $virgula." ed118_usuario = $this->ed118_usuario ";
       $virgula = ",";
       if(trim($this->ed118_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "ed118_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed118_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed118_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed118_data_dia"] !="") ){ 
       $sql  .= $virgula." ed118_data = '$this->ed118_data' ";
       $virgula = ",";
       if(trim($this->ed118_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "ed118_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_data_dia"])){ 
         $sql  .= $virgula." ed118_data = null ";
         $virgula = ",";
         if(trim($this->ed118_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "ed118_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed118_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed118_hora"])){ 
       $sql  .= $virgula." ed118_hora = '$this->ed118_hora' ";
       $virgula = ",";
       if(trim($this->ed118_hora) == null ){ 
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "ed118_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed118_resumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed118_resumo"])){ 
       $sql  .= $virgula." ed118_resumo = '$this->ed118_resumo' ";
       $virgula = ",";
       if(trim($this->ed118_resumo) == null ){ 
         $this->erro_sql = " Campo Resumo não informado.";
         $this->erro_campo = "ed118_resumo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed118_sequencial!=null){
       $sql .= " ed118_sequencial = $this->ed118_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed118_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20560,'$this->ed118_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_sequencial"]) || $this->ed118_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3699,20560,'".AddSlashes(pg_result($resaco,$conresaco,'ed118_sequencial'))."','$this->ed118_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_escola"]) || $this->ed118_escola != "")
             $resac = db_query("insert into db_acount values($acount,3699,20561,'".AddSlashes(pg_result($resaco,$conresaco,'ed118_escola'))."','$this->ed118_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_rechumano"]) || $this->ed118_rechumano != "")
             $resac = db_query("insert into db_acount values($acount,3699,20562,'".AddSlashes(pg_result($resaco,$conresaco,'ed118_rechumano'))."','$this->ed118_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_usuario"]) || $this->ed118_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3699,20563,'".AddSlashes(pg_result($resaco,$conresaco,'ed118_usuario'))."','$this->ed118_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_data"]) || $this->ed118_data != "")
             $resac = db_query("insert into db_acount values($acount,3699,20564,'".AddSlashes(pg_result($resaco,$conresaco,'ed118_data'))."','$this->ed118_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_hora"]) || $this->ed118_hora != "")
             $resac = db_query("insert into db_acount values($acount,3699,20565,'".AddSlashes(pg_result($resaco,$conresaco,'ed118_hora'))."','$this->ed118_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed118_resumo"]) || $this->ed118_resumo != "")
             $resac = db_query("insert into db_acount values($acount,3699,20566,'".AddSlashes(pg_result($resaco,$conresaco,'ed118_resumo'))."','$this->ed118_resumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rechumanomovimentacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rechumanomovimentacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed118_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed118_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20560,'$ed118_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3699,20560,'','".AddSlashes(pg_result($resaco,$iresaco,'ed118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3699,20561,'','".AddSlashes(pg_result($resaco,$iresaco,'ed118_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3699,20562,'','".AddSlashes(pg_result($resaco,$iresaco,'ed118_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3699,20563,'','".AddSlashes(pg_result($resaco,$iresaco,'ed118_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3699,20564,'','".AddSlashes(pg_result($resaco,$iresaco,'ed118_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3699,20565,'','".AddSlashes(pg_result($resaco,$iresaco,'ed118_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3699,20566,'','".AddSlashes(pg_result($resaco,$iresaco,'ed118_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rechumanomovimentacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed118_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed118_sequencial = $ed118_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rechumanomovimentacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rechumanomovimentacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed118_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rechumanomovimentacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rechumanomovimentacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rechumanomovimentacao.ed118_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanomovimentacao.ed118_escola";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = rechumanomovimentacao.ed118_rechumano";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join   on  .ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  as a on   a.ed260_i_codigo = rechumano.ed20_i_censoufcert and   a.ed260_i_codigo = rechumano.ed20_i_censoufender and   a.ed260_i_codigo = rechumano.ed20_i_censoufnat and   a.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  as b on   b.ed261_i_codigo = rechumano.ed20_i_censomunicnat and   b. = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as c on   c.ed20_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed118_sequencial!=null ){
         $sql2 .= " where rechumanomovimentacao.ed118_sequencial = $ed118_sequencial "; 
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
   function sql_query_file ( $ed118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rechumanomovimentacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed118_sequencial!=null ){
         $sql2 .= " where rechumanomovimentacao.ed118_sequencial = $ed118_sequencial "; 
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
