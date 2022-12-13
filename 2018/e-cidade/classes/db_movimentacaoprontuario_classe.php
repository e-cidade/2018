<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE movimentacaoprontuario
class cl_movimentacaoprontuario { 
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
   var $sd102_codigo = 0; 
   var $sd102_prontuarios = 0; 
   var $sd102_db_usuarios = 0; 
   var $sd102_setorambulatorial = 0; 
   var $sd102_data_dia = null; 
   var $sd102_data_mes = null; 
   var $sd102_data_ano = null; 
   var $sd102_data = null; 
   var $sd102_hora = null; 
   var $sd102_situacao = 0; 
   var $sd102_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd102_codigo = int4 = Código 
                 sd102_prontuarios = int4 = Ficha de atendimento 
                 sd102_db_usuarios = int4 = Usuário 
                 sd102_setorambulatorial = int4 = Setor ambulatorial 
                 sd102_data = date = Data 
                 sd102_hora = varchar(5) = Hora 
                 sd102_situacao = int4 = Situação 
                 sd102_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_movimentacaoprontuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("movimentacaoprontuario"); 
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
       $this->sd102_codigo = ($this->sd102_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_codigo"]:$this->sd102_codigo);
       $this->sd102_prontuarios = ($this->sd102_prontuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_prontuarios"]:$this->sd102_prontuarios);
       $this->sd102_db_usuarios = ($this->sd102_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_db_usuarios"]:$this->sd102_db_usuarios);
       $this->sd102_setorambulatorial = ($this->sd102_setorambulatorial == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_setorambulatorial"]:$this->sd102_setorambulatorial);
       if($this->sd102_data == ""){
         $this->sd102_data_dia = ($this->sd102_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_data_dia"]:$this->sd102_data_dia);
         $this->sd102_data_mes = ($this->sd102_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_data_mes"]:$this->sd102_data_mes);
         $this->sd102_data_ano = ($this->sd102_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_data_ano"]:$this->sd102_data_ano);
         if($this->sd102_data_dia != ""){
            $this->sd102_data = $this->sd102_data_ano."-".$this->sd102_data_mes."-".$this->sd102_data_dia;
         }
       }
       $this->sd102_hora = ($this->sd102_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_hora"]:$this->sd102_hora);
       $this->sd102_situacao = ($this->sd102_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_situacao"]:$this->sd102_situacao);
       $this->sd102_observacao = ($this->sd102_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_observacao"]:$this->sd102_observacao);
     }else{
       $this->sd102_codigo = ($this->sd102_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd102_codigo"]:$this->sd102_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd102_codigo){ 
      $this->atualizacampos();
     if($this->sd102_prontuarios == null ){ 
       $this->erro_sql = " Campo Ficha de atendimento não informado.";
       $this->erro_campo = "sd102_prontuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd102_db_usuarios == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "sd102_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd102_setorambulatorial == null ){ 
       $this->erro_sql = " Campo Setor ambulatorial não informado.";
       $this->erro_campo = "sd102_setorambulatorial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd102_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "sd102_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd102_hora == null ){ 
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "sd102_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd102_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "sd102_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd102_codigo == "" || $sd102_codigo == null ){
       $result = db_query("select nextval('movimentacaoprontuario_sd102_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: movimentacaoprontuario_sd102_codigo_seq do campo: sd102_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd102_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from movimentacaoprontuario_sd102_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd102_codigo)){
         $this->erro_sql = " Campo sd102_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd102_codigo = $sd102_codigo; 
       }
     }
     if(($this->sd102_codigo == null) || ($this->sd102_codigo == "") ){ 
       $this->erro_sql = " Campo sd102_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into movimentacaoprontuario(
                                       sd102_codigo 
                                      ,sd102_prontuarios 
                                      ,sd102_db_usuarios 
                                      ,sd102_setorambulatorial 
                                      ,sd102_data 
                                      ,sd102_hora 
                                      ,sd102_situacao 
                                      ,sd102_observacao 
                       )
                values (
                                $this->sd102_codigo 
                               ,$this->sd102_prontuarios 
                               ,$this->sd102_db_usuarios 
                               ,$this->sd102_setorambulatorial 
                               ,".($this->sd102_data == "null" || $this->sd102_data == ""?"null":"'".$this->sd102_data."'")." 
                               ,'$this->sd102_hora' 
                               ,$this->sd102_situacao 
                               ,'$this->sd102_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentação da FAA ($this->sd102_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentação da FAA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentação da FAA ($this->sd102_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd102_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd102_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20944,'$this->sd102_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3773,20944,'','".AddSlashes(pg_result($resaco,0,'sd102_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3773,20945,'','".AddSlashes(pg_result($resaco,0,'sd102_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3773,20946,'','".AddSlashes(pg_result($resaco,0,'sd102_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3773,20947,'','".AddSlashes(pg_result($resaco,0,'sd102_setorambulatorial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3773,20948,'','".AddSlashes(pg_result($resaco,0,'sd102_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3773,20949,'','".AddSlashes(pg_result($resaco,0,'sd102_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3773,20951,'','".AddSlashes(pg_result($resaco,0,'sd102_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3773,20950,'','".AddSlashes(pg_result($resaco,0,'sd102_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd102_codigo=null) { 
      $this->atualizacampos();
     $sql = " update movimentacaoprontuario set ";
     $virgula = "";
     if(trim($this->sd102_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_codigo"])){ 
       $sql  .= $virgula." sd102_codigo = $this->sd102_codigo ";
       $virgula = ",";
       if(trim($this->sd102_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd102_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd102_prontuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_prontuarios"])){ 
       $sql  .= $virgula." sd102_prontuarios = $this->sd102_prontuarios ";
       $virgula = ",";
       if(trim($this->sd102_prontuarios) == null ){ 
         $this->erro_sql = " Campo Ficha de atendimento não informado.";
         $this->erro_campo = "sd102_prontuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd102_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_db_usuarios"])){ 
       $sql  .= $virgula." sd102_db_usuarios = $this->sd102_db_usuarios ";
       $virgula = ",";
       if(trim($this->sd102_db_usuarios) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "sd102_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd102_setorambulatorial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_setorambulatorial"])){ 
       $sql  .= $virgula." sd102_setorambulatorial = $this->sd102_setorambulatorial ";
       $virgula = ",";
       if(trim($this->sd102_setorambulatorial) == null ){ 
         $this->erro_sql = " Campo Setor ambulatorial não informado.";
         $this->erro_campo = "sd102_setorambulatorial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd102_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd102_data_dia"] !="") ){ 
       $sql  .= $virgula." sd102_data = '$this->sd102_data' ";
       $virgula = ",";
       if(trim($this->sd102_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "sd102_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd102_data_dia"])){ 
         $sql  .= $virgula." sd102_data = null ";
         $virgula = ",";
         if(trim($this->sd102_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "sd102_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd102_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_hora"])){ 
       $sql  .= $virgula." sd102_hora = '$this->sd102_hora' ";
       $virgula = ",";
       if(trim($this->sd102_hora) == null ){ 
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "sd102_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd102_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_situacao"])){ 
       $sql  .= $virgula." sd102_situacao = $this->sd102_situacao ";
       $virgula = ",";
       if(trim($this->sd102_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "sd102_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd102_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd102_observacao"])){ 
       $sql  .= $virgula." sd102_observacao = '$this->sd102_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd102_codigo!=null){
       $sql .= " sd102_codigo = $this->sd102_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd102_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20944,'$this->sd102_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_codigo"]) || $this->sd102_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3773,20944,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_codigo'))."','$this->sd102_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_prontuarios"]) || $this->sd102_prontuarios != "")
             $resac = db_query("insert into db_acount values($acount,3773,20945,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_prontuarios'))."','$this->sd102_prontuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_db_usuarios"]) || $this->sd102_db_usuarios != "")
             $resac = db_query("insert into db_acount values($acount,3773,20946,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_db_usuarios'))."','$this->sd102_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_setorambulatorial"]) || $this->sd102_setorambulatorial != "")
             $resac = db_query("insert into db_acount values($acount,3773,20947,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_setorambulatorial'))."','$this->sd102_setorambulatorial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_data"]) || $this->sd102_data != "")
             $resac = db_query("insert into db_acount values($acount,3773,20948,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_data'))."','$this->sd102_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_hora"]) || $this->sd102_hora != "")
             $resac = db_query("insert into db_acount values($acount,3773,20949,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_hora'))."','$this->sd102_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_situacao"]) || $this->sd102_situacao != "")
             $resac = db_query("insert into db_acount values($acount,3773,20951,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_situacao'))."','$this->sd102_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd102_observacao"]) || $this->sd102_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3773,20950,'".AddSlashes(pg_result($resaco,$conresaco,'sd102_observacao'))."','$this->sd102_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação da FAA nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd102_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação da FAA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd102_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd102_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd102_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd102_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20944,'$sd102_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3773,20944,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3773,20945,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3773,20946,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3773,20947,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_setorambulatorial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3773,20948,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3773,20949,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3773,20951,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3773,20950,'','".AddSlashes(pg_result($resaco,$iresaco,'sd102_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from movimentacaoprontuario
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd102_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd102_codigo = $sd102_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação da FAA nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd102_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação da FAA nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd102_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd102_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:movimentacaoprontuario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd102_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from movimentacaoprontuario ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = movimentacaoprontuario.sd102_db_usuarios";
     $sql .= "      inner join setorambulatorial  on  setorambulatorial.sd91_codigo = movimentacaoprontuario.sd102_setorambulatorial";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = movimentacaoprontuario.sd102_prontuarios";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = setorambulatorial.sd91_unidades";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join setorambulatorial  as a on   a.sd91_codigo = prontuarios.sd24_setorambulatorial";
     $sql .= "      inner join unidades  as b on   b.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd102_codigo)) {
         $sql2 .= " where movimentacaoprontuario.sd102_codigo = $sd102_codigo "; 
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
   public function sql_query_file ($sd102_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from movimentacaoprontuario ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd102_codigo)){
         $sql2 .= " where movimentacaoprontuario.sd102_codigo = $sd102_codigo "; 
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
