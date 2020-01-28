<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE prontuariosmotivoalta
class cl_prontuariosmotivoalta { 
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
   var $sd25_codigo = 0; 
   var $sd25_motivoalta = 0; 
   var $sd25_prontuarios = 0; 
   var $sd25_data_dia = null; 
   var $sd25_data_mes = null; 
   var $sd25_data_ano = null; 
   var $sd25_data = null; 
   var $sd25_hora = null; 
   var $sd25_db_usuarios = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd25_codigo = int4 = Código 
                 sd25_motivoalta = int4 = Motivo de alta 
                 sd25_prontuarios = int4 = Prontuarios 
                 sd25_data = date = Data 
                 sd25_hora = varchar(5) = Hora 
                 sd25_db_usuarios = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_prontuariosmotivoalta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontuariosmotivoalta"); 
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
       $this->sd25_codigo = ($this->sd25_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_codigo"]:$this->sd25_codigo);
       $this->sd25_motivoalta = ($this->sd25_motivoalta == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_motivoalta"]:$this->sd25_motivoalta);
       $this->sd25_prontuarios = ($this->sd25_prontuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_prontuarios"]:$this->sd25_prontuarios);
       if($this->sd25_data == ""){
         $this->sd25_data_dia = ($this->sd25_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_data_dia"]:$this->sd25_data_dia);
         $this->sd25_data_mes = ($this->sd25_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_data_mes"]:$this->sd25_data_mes);
         $this->sd25_data_ano = ($this->sd25_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_data_ano"]:$this->sd25_data_ano);
         if($this->sd25_data_dia != ""){
            $this->sd25_data = $this->sd25_data_ano."-".$this->sd25_data_mes."-".$this->sd25_data_dia;
         }
       }
       $this->sd25_hora = ($this->sd25_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_hora"]:$this->sd25_hora);
       $this->sd25_db_usuarios = ($this->sd25_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_db_usuarios"]:$this->sd25_db_usuarios);
     }else{
       $this->sd25_codigo = ($this->sd25_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_codigo"]:$this->sd25_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd25_codigo){ 
      $this->atualizacampos();
     if($this->sd25_motivoalta == null ){ 
       $this->erro_sql = " Campo Motivo de alta não informado.";
       $this->erro_campo = "sd25_motivoalta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd25_prontuarios == null ){ 
       $this->erro_sql = " Campo Prontuarios não informado.";
       $this->erro_campo = "sd25_prontuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd25_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "sd25_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd25_hora == null ){ 
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "sd25_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd25_db_usuarios == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "sd25_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd25_codigo == "" || $sd25_codigo == null ){
       $result = db_query("select nextval('prontuariosmotivoalta_sd25_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontuariosmotivoalta_sd25_codigo_seq do campo: sd25_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd25_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontuariosmotivoalta_sd25_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd25_codigo)){
         $this->erro_sql = " Campo sd25_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd25_codigo = $sd25_codigo; 
       }
     }
     if(($this->sd25_codigo == null) || ($this->sd25_codigo == "") ){ 
       $this->erro_sql = " Campo sd25_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontuariosmotivoalta(
                                       sd25_codigo 
                                      ,sd25_motivoalta 
                                      ,sd25_prontuarios 
                                      ,sd25_data 
                                      ,sd25_hora 
                                      ,sd25_db_usuarios 
                       )
                values (
                                $this->sd25_codigo 
                               ,$this->sd25_motivoalta 
                               ,$this->sd25_prontuarios 
                               ,".($this->sd25_data == "null" || $this->sd25_data == ""?"null":"'".$this->sd25_data."'")." 
                               ,'$this->sd25_hora' 
                               ,$this->sd25_db_usuarios 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alta de prontuarios ($this->sd25_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alta de prontuarios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alta de prontuarios ($this->sd25_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd25_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20907,'$this->sd25_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3762,20907,'','".AddSlashes(pg_result($resaco,0,'sd25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3762,20908,'','".AddSlashes(pg_result($resaco,0,'sd25_motivoalta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3762,20909,'','".AddSlashes(pg_result($resaco,0,'sd25_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3762,20910,'','".AddSlashes(pg_result($resaco,0,'sd25_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3762,20912,'','".AddSlashes(pg_result($resaco,0,'sd25_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3762,20913,'','".AddSlashes(pg_result($resaco,0,'sd25_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd25_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontuariosmotivoalta set ";
     $virgula = "";
     if(trim($this->sd25_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_codigo"])){ 
       $sql  .= $virgula." sd25_codigo = $this->sd25_codigo ";
       $virgula = ",";
       if(trim($this->sd25_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd25_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd25_motivoalta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_motivoalta"])){ 
       $sql  .= $virgula." sd25_motivoalta = $this->sd25_motivoalta ";
       $virgula = ",";
       if(trim($this->sd25_motivoalta) == null ){ 
         $this->erro_sql = " Campo Motivo de alta não informado.";
         $this->erro_campo = "sd25_motivoalta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd25_prontuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_prontuarios"])){ 
       $sql  .= $virgula." sd25_prontuarios = $this->sd25_prontuarios ";
       $virgula = ",";
       if(trim($this->sd25_prontuarios) == null ){ 
         $this->erro_sql = " Campo Prontuarios não informado.";
         $this->erro_campo = "sd25_prontuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd25_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd25_data_dia"] !="") ){ 
       $sql  .= $virgula." sd25_data = '$this->sd25_data' ";
       $virgula = ",";
       if(trim($this->sd25_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "sd25_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd25_data_dia"])){ 
         $sql  .= $virgula." sd25_data = null ";
         $virgula = ",";
         if(trim($this->sd25_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "sd25_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd25_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_hora"])){ 
       $sql  .= $virgula." sd25_hora = '$this->sd25_hora' ";
       $virgula = ",";
       if(trim($this->sd25_hora) == null ){ 
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "sd25_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd25_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_db_usuarios"])){ 
       $sql  .= $virgula." sd25_db_usuarios = $this->sd25_db_usuarios ";
       $virgula = ",";
       if(trim($this->sd25_db_usuarios) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "sd25_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd25_codigo!=null){
       $sql .= " sd25_codigo = $this->sd25_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd25_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20907,'$this->sd25_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd25_codigo"]) || $this->sd25_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3762,20907,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_codigo'))."','$this->sd25_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd25_motivoalta"]) || $this->sd25_motivoalta != "")
             $resac = db_query("insert into db_acount values($acount,3762,20908,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_motivoalta'))."','$this->sd25_motivoalta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd25_prontuarios"]) || $this->sd25_prontuarios != "")
             $resac = db_query("insert into db_acount values($acount,3762,20909,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_prontuarios'))."','$this->sd25_prontuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd25_data"]) || $this->sd25_data != "")
             $resac = db_query("insert into db_acount values($acount,3762,20910,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_data'))."','$this->sd25_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd25_hora"]) || $this->sd25_hora != "")
             $resac = db_query("insert into db_acount values($acount,3762,20912,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_hora'))."','$this->sd25_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd25_db_usuarios"]) || $this->sd25_db_usuarios != "")
             $resac = db_query("insert into db_acount values($acount,3762,20913,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_db_usuarios'))."','$this->sd25_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alta de prontuarios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alta de prontuarios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd25_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd25_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20907,'$sd25_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3762,20907,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3762,20908,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_motivoalta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3762,20909,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3762,20910,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3762,20912,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3762,20913,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from prontuariosmotivoalta
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd25_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd25_codigo = $sd25_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alta de prontuarios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd25_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alta de prontuarios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd25_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd25_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontuariosmotivoalta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd25_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from prontuariosmotivoalta ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontuariosmotivoalta.sd25_db_usuarios";
     $sql .= "      inner join motivoalta  on  motivoalta.sd01_codigo = prontuariosmotivoalta.sd25_motivoalta";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontuariosmotivoalta.sd25_prontuarios";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd25_codigo)) {
         $sql2 .= " where prontuariosmotivoalta.sd25_codigo = $sd25_codigo "; 
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
   public function sql_query_file ($sd25_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from prontuariosmotivoalta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd25_codigo)){
         $sql2 .= " where prontuariosmotivoalta.sd25_codigo = $sd25_codigo "; 
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
