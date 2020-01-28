<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE descartemedicamento
class cl_descartemedicamento { 
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
   var $sd107_sequencial = 0; 
   var $sd107_medicamento = 0; 
   var $sd107_quantidade = 0; 
   var $sd107_motivo = null; 
   var $sd107_data_dia = null; 
   var $sd107_data_mes = null; 
   var $sd107_data_ano = null; 
   var $sd107_data = null; 
   var $sd107_hora = null; 
   var $sd107_usuario = 0; 
   var $sd107_db_depart = 0; 
   var $sd107_quantidadetotal = 0; 
   var $sd107_unidadesaida = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd107_sequencial = int4 = Código Sequencial 
                 sd107_medicamento = int4 = Medicamento 
                 sd107_quantidade = float8 = Quantidade 
                 sd107_motivo = text = Motivo 
                 sd107_data = date = Data 
                 sd107_hora = char(8) = Hora 
                 sd107_usuario = int4 = Usuário 
                 sd107_db_depart = int4 = Departamento 
                 sd107_quantidadetotal = float4 = Conteúdo Medicamento 
                 sd107_unidadesaida = int4 = Unidade de Saída 
                 ";
   //funcao construtor da classe 
   function cl_descartemedicamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("descartemedicamento"); 
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
       $this->sd107_sequencial = ($this->sd107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_sequencial"]:$this->sd107_sequencial);
       $this->sd107_medicamento = ($this->sd107_medicamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_medicamento"]:$this->sd107_medicamento);
       $this->sd107_quantidade = ($this->sd107_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_quantidade"]:$this->sd107_quantidade);
       $this->sd107_motivo = ($this->sd107_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_motivo"]:$this->sd107_motivo);
       if($this->sd107_data == ""){
         $this->sd107_data_dia = ($this->sd107_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_data_dia"]:$this->sd107_data_dia);
         $this->sd107_data_mes = ($this->sd107_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_data_mes"]:$this->sd107_data_mes);
         $this->sd107_data_ano = ($this->sd107_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_data_ano"]:$this->sd107_data_ano);
         if($this->sd107_data_dia != ""){
            $this->sd107_data = $this->sd107_data_ano."-".$this->sd107_data_mes."-".$this->sd107_data_dia;
         }
       }
       $this->sd107_hora = ($this->sd107_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_hora"]:$this->sd107_hora);
       $this->sd107_usuario = ($this->sd107_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_usuario"]:$this->sd107_usuario);
       $this->sd107_db_depart = ($this->sd107_db_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_db_depart"]:$this->sd107_db_depart);
       $this->sd107_quantidadetotal = ($this->sd107_quantidadetotal == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_quantidadetotal"]:$this->sd107_quantidadetotal);
       $this->sd107_unidadesaida = ($this->sd107_unidadesaida == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_unidadesaida"]:$this->sd107_unidadesaida);
     }else{
       $this->sd107_sequencial = ($this->sd107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sd107_sequencial"]:$this->sd107_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($sd107_sequencial){ 
      $this->atualizacampos();
     if($this->sd107_medicamento == null ){ 
       $this->erro_sql = " Campo Medicamento não informado.";
       $this->erro_campo = "sd107_medicamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "sd107_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_motivo == null ){ 
       $this->erro_sql = " Campo Motivo não informado.";
       $this->erro_campo = "sd107_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "sd107_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_hora == null ){ 
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "sd107_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "sd107_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_db_depart == null ){ 
       $this->erro_sql = " Campo Departamento não informado.";
       $this->erro_campo = "sd107_db_depart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_quantidadetotal == null ){ 
       $this->erro_sql = " Campo Conteúdo Medicamento não informado.";
       $this->erro_campo = "sd107_quantidadetotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd107_unidadesaida == null ){ 
       $this->erro_sql = " Campo Unidade de Saída não informado.";
       $this->erro_campo = "sd107_unidadesaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd107_sequencial == "" || $sd107_sequencial == null ){
       $result = db_query("select nextval('descartemedicamento_sd107_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: descartemedicamento_sd107_sequencial_seq do campo: sd107_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd107_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from descartemedicamento_sd107_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd107_sequencial)){
         $this->erro_sql = " Campo sd107_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd107_sequencial = $sd107_sequencial; 
       }
     }
     if(($this->sd107_sequencial == null) || ($this->sd107_sequencial == "") ){ 
       $this->erro_sql = " Campo sd107_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into descartemedicamento(
                                       sd107_sequencial 
                                      ,sd107_medicamento 
                                      ,sd107_quantidade 
                                      ,sd107_motivo 
                                      ,sd107_data 
                                      ,sd107_hora 
                                      ,sd107_usuario 
                                      ,sd107_db_depart 
                                      ,sd107_quantidadetotal 
                                      ,sd107_unidadesaida 
                       )
                values (
                                $this->sd107_sequencial 
                               ,$this->sd107_medicamento 
                               ,$this->sd107_quantidade 
                               ,'$this->sd107_motivo' 
                               ,".($this->sd107_data == "null" || $this->sd107_data == ""?"null":"'".$this->sd107_data."'")." 
                               ,'$this->sd107_hora' 
                               ,$this->sd107_usuario 
                               ,$this->sd107_db_depart 
                               ,$this->sd107_quantidadetotal 
                               ,$this->sd107_unidadesaida 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Descarte de Medicamentos ($this->sd107_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Descarte de Medicamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Descarte de Medicamentos ($this->sd107_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd107_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd107_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21339,'$this->sd107_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3843,21339,'','".AddSlashes(pg_result($resaco,0,'sd107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21340,'','".AddSlashes(pg_result($resaco,0,'sd107_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21341,'','".AddSlashes(pg_result($resaco,0,'sd107_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21342,'','".AddSlashes(pg_result($resaco,0,'sd107_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21343,'','".AddSlashes(pg_result($resaco,0,'sd107_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21344,'','".AddSlashes(pg_result($resaco,0,'sd107_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21345,'','".AddSlashes(pg_result($resaco,0,'sd107_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21346,'','".AddSlashes(pg_result($resaco,0,'sd107_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21348,'','".AddSlashes(pg_result($resaco,0,'sd107_quantidadetotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3843,21347,'','".AddSlashes(pg_result($resaco,0,'sd107_unidadesaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd107_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update descartemedicamento set ";
     $virgula = "";
     if(trim($this->sd107_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_sequencial"])){ 
       $sql  .= $virgula." sd107_sequencial = $this->sd107_sequencial ";
       $virgula = ",";
       if(trim($this->sd107_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "sd107_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_medicamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_medicamento"])){ 
       $sql  .= $virgula." sd107_medicamento = $this->sd107_medicamento ";
       $virgula = ",";
       if(trim($this->sd107_medicamento) == null ){ 
         $this->erro_sql = " Campo Medicamento não informado.";
         $this->erro_campo = "sd107_medicamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_quantidade"])){ 
       $sql  .= $virgula." sd107_quantidade = $this->sd107_quantidade ";
       $virgula = ",";
       if(trim($this->sd107_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "sd107_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_motivo"])){ 
       $sql  .= $virgula." sd107_motivo = '$this->sd107_motivo' ";
       $virgula = ",";
       if(trim($this->sd107_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo não informado.";
         $this->erro_campo = "sd107_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd107_data_dia"] !="") ){ 
       $sql  .= $virgula." sd107_data = '$this->sd107_data' ";
       $virgula = ",";
       if(trim($this->sd107_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "sd107_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd107_data_dia"])){ 
         $sql  .= $virgula." sd107_data = null ";
         $virgula = ",";
         if(trim($this->sd107_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "sd107_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd107_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_hora"])){ 
       $sql  .= $virgula." sd107_hora = '$this->sd107_hora' ";
       $virgula = ",";
       if(trim($this->sd107_hora) == null ){ 
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "sd107_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_usuario"])){ 
       $sql  .= $virgula." sd107_usuario = $this->sd107_usuario ";
       $virgula = ",";
       if(trim($this->sd107_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "sd107_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_db_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_db_depart"])){ 
       $sql  .= $virgula." sd107_db_depart = $this->sd107_db_depart ";
       $virgula = ",";
       if(trim($this->sd107_db_depart) == null ){ 
         $this->erro_sql = " Campo Departamento não informado.";
         $this->erro_campo = "sd107_db_depart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_quantidadetotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_quantidadetotal"])){ 
       $sql  .= $virgula." sd107_quantidadetotal = $this->sd107_quantidadetotal ";
       $virgula = ",";
       if(trim($this->sd107_quantidadetotal) == null ){ 
         $this->erro_sql = " Campo Conteúdo Medicamento não informado.";
         $this->erro_campo = "sd107_quantidadetotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd107_unidadesaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd107_unidadesaida"])){ 
       $sql  .= $virgula." sd107_unidadesaida = $this->sd107_unidadesaida ";
       $virgula = ",";
       if(trim($this->sd107_unidadesaida) == null ){ 
         $this->erro_sql = " Campo Unidade de Saída não informado.";
         $this->erro_campo = "sd107_unidadesaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd107_sequencial!=null){
       $sql .= " sd107_sequencial = $this->sd107_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd107_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21339,'$this->sd107_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_sequencial"]) || $this->sd107_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3843,21339,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_sequencial'))."','$this->sd107_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_medicamento"]) || $this->sd107_medicamento != "")
             $resac = db_query("insert into db_acount values($acount,3843,21340,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_medicamento'))."','$this->sd107_medicamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_quantidade"]) || $this->sd107_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3843,21341,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_quantidade'))."','$this->sd107_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_motivo"]) || $this->sd107_motivo != "")
             $resac = db_query("insert into db_acount values($acount,3843,21342,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_motivo'))."','$this->sd107_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_data"]) || $this->sd107_data != "")
             $resac = db_query("insert into db_acount values($acount,3843,21343,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_data'))."','$this->sd107_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_hora"]) || $this->sd107_hora != "")
             $resac = db_query("insert into db_acount values($acount,3843,21344,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_hora'))."','$this->sd107_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_usuario"]) || $this->sd107_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3843,21345,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_usuario'))."','$this->sd107_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_db_depart"]) || $this->sd107_db_depart != "")
             $resac = db_query("insert into db_acount values($acount,3843,21346,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_db_depart'))."','$this->sd107_db_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_quantidadetotal"]) || $this->sd107_quantidadetotal != "")
             $resac = db_query("insert into db_acount values($acount,3843,21348,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_quantidadetotal'))."','$this->sd107_quantidadetotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd107_unidadesaida"]) || $this->sd107_unidadesaida != "")
             $resac = db_query("insert into db_acount values($acount,3843,21347,'".AddSlashes(pg_result($resaco,$conresaco,'sd107_unidadesaida'))."','$this->sd107_unidadesaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descarte de Medicamentos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Descarte de Medicamentos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd107_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd107_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21339,'$sd107_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3843,21339,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21340,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21341,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21342,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21343,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21344,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21345,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21346,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21348,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_quantidadetotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3843,21347,'','".AddSlashes(pg_result($resaco,$iresaco,'sd107_unidadesaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from descartemedicamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd107_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd107_sequencial = $sd107_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descarte de Medicamentos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Descarte de Medicamentos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd107_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:descartemedicamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd107_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from descartemedicamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = descartemedicamento.sd107_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = descartemedicamento.sd107_db_depart";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = descartemedicamento.sd107_unidadesaida";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = descartemedicamento.sd107_medicamento";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      inner join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      left  join far_medanvisa  on  far_medanvisa.fa14_i_codigo = far_matersaude.fa01_i_medanvisa";
     $sql .= "      left  join far_prescricaomed  on  far_prescricaomed.fa31_i_codigo = far_matersaude.fa01_i_prescricaomed";
     $sql .= "      left  join far_laboratoriomed  on  far_laboratoriomed.fa32_i_codigo = far_matersaude.fa01_i_laboratoriomed";
     $sql .= "      left  join far_formafarmaceuticamed  on  far_formafarmaceuticamed.fa33_i_codigo = far_matersaude.fa01_i_formafarmaceuticamed";
     $sql .= "      left  join far_medreferenciamed  on  far_medreferenciamed.fa34_i_codigo = far_matersaude.fa01_i_medrefemed";
     $sql .= "      left  join far_listacontroladomed  on  far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed";
     $sql .= "      left  join far_classeterapeuticamed  on  far_classeterapeuticamed.fa36_i_codigo = far_matersaude.fa01_i_classemed";
     $sql .= "      left  join far_concentracaomed  on  far_concentracaomed.fa37_i_codigo = far_matersaude.fa01_i_concentracaomed";
     $sql .= "      inner join far_medicamentohiperdia  on  far_medicamentohiperdia.fa43_i_codigo = far_matersaude.fa01_i_medhiperdia";
     $sql .= "      left  join medicamentos  on  medicamentos.fa58_codigo = far_matersaude.fa01_medicamentos";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd107_sequencial)) {
         $sql2 .= " where descartemedicamento.sd107_sequencial = $sd107_sequencial "; 
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
   public function sql_query_file ($sd107_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from descartemedicamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd107_sequencial)){
         $sql2 .= " where descartemedicamento.sd107_sequencial = $sd107_sequencial "; 
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

  /**
   * Retorna os dados do descarte
   * @param null   $sd107_sequencial
   * @param string $campos
   * @param null   $ordem
   * @param string $dbwhere
   * @return string
   */
  public function sql_query_dados_descarte ($sd107_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from descartemedicamento ";
    $sql .= "      inner join db_usuarios     on  db_usuarios.id_usuario = descartemedicamento.sd107_usuario";
    $sql .= "      inner join db_depart       on  db_depart.coddepto = descartemedicamento.sd107_db_depart";
    $sql .= "      inner join matunid         on  matunid.m61_codmatunid = descartemedicamento.sd107_unidadesaida";
    $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = descartemedicamento.sd107_medicamento";
    $sql .= "      inner join db_config       on  db_config.codigo = db_depart.instit";
    $sql .= "      inner join matmater        on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($sd107_sequencial)) {
        $sql2 .= " where descartemedicamento.sd107_sequencial = $sd107_sequencial ";
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
