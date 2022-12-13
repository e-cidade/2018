<?
//MODULO: agua
//CLASSE DA ENTIDADE aguacalc
class cl_aguacalc { 
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
   var $x22_codcalc = 0; 
   var $x22_codconsumo = 0; 
   var $x22_exerc = 0; 
   var $x22_mes = 0; 
   var $x22_matric = 0; 
   var $x22_area = 0; 
   var $x22_numpre = 0; 
   var $x22_manual = null; 
   var $x22_tipo = 0; 
   var $x22_data_dia = null; 
   var $x22_data_mes = null; 
   var $x22_data_ano = null; 
   var $x22_data = null; 
   var $x22_hora = null; 
   var $x22_usuario = 0; 
   var $x22_aguacontrato = 0; 
   var $x22_aguacontratoeconomia = 0; 
   var $x22_responsavelpagamento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x22_codcalc = int4 = Codigo 
                 x22_codconsumo = int4 = Categoria Consumo 
                 x22_exerc = int4 = Ano 
                 x22_mes = int4 = Mes 
                 x22_matric = int4 = Matrícula 
                 x22_area = float4 = Area 
                 x22_numpre = int4 = Numpre 
                 x22_manual = text = Memória 
                 x22_tipo = int4 = Tipo calculo 
                 x22_data = date = Data Alteração 
                 x22_hora = char(5) = Hora Alteração 
                 x22_usuario = int4 = Cod. Usuário 
                 x22_aguacontrato = int4 = Contrato 
                 x22_aguacontratoeconomia = int4 = Economia 
                 x22_responsavelpagamento = int4 = Responsável Pagamento 
                 ";
   //funcao construtor da classe 
   function cl_aguacalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacalc"); 
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
       $this->x22_codcalc = ($this->x22_codcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_codcalc"]:$this->x22_codcalc);
       $this->x22_codconsumo = ($this->x22_codconsumo == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_codconsumo"]:$this->x22_codconsumo);
       $this->x22_exerc = ($this->x22_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_exerc"]:$this->x22_exerc);
       $this->x22_mes = ($this->x22_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_mes"]:$this->x22_mes);
       $this->x22_matric = ($this->x22_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_matric"]:$this->x22_matric);
       $this->x22_area = ($this->x22_area == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_area"]:$this->x22_area);
       $this->x22_numpre = ($this->x22_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_numpre"]:$this->x22_numpre);
       $this->x22_manual = ($this->x22_manual == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_manual"]:$this->x22_manual);
       $this->x22_tipo = ($this->x22_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_tipo"]:$this->x22_tipo);
       if($this->x22_data == ""){
         $this->x22_data_dia = ($this->x22_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_data_dia"]:$this->x22_data_dia);
         $this->x22_data_mes = ($this->x22_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_data_mes"]:$this->x22_data_mes);
         $this->x22_data_ano = ($this->x22_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_data_ano"]:$this->x22_data_ano);
         if($this->x22_data_dia != ""){
            $this->x22_data = $this->x22_data_ano."-".$this->x22_data_mes."-".$this->x22_data_dia;
         }
       }
       $this->x22_hora = ($this->x22_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_hora"]:$this->x22_hora);
       $this->x22_usuario = ($this->x22_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_usuario"]:$this->x22_usuario);
       $this->x22_aguacontrato = ($this->x22_aguacontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_aguacontrato"]:$this->x22_aguacontrato);
       $this->x22_aguacontratoeconomia = ($this->x22_aguacontratoeconomia == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_aguacontratoeconomia"]:$this->x22_aguacontratoeconomia);
       $this->x22_responsavelpagamento = ($this->x22_responsavelpagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_responsavelpagamento"]:$this->x22_responsavelpagamento);
     }else{
       $this->x22_codcalc = ($this->x22_codcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["x22_codcalc"]:$this->x22_codcalc);
     }
   }
   // funcao para Inclusão
   function incluir ($x22_codcalc){ 
      $this->atualizacampos();
     if($this->x22_codconsumo == null ){ 
       $this->x22_codconsumo = "0";
     }
     if($this->x22_exerc == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "x22_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x22_mes == null ){ 
       $this->erro_sql = " Campo Mes não informado.";
       $this->erro_campo = "x22_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x22_matric == null ){ 
       $this->x22_matric = "0";
     }
     if($this->x22_area == null ){ 
       $this->x22_area = "0";
     }
     if($this->x22_numpre == null ){ 
       $this->erro_sql = " Campo Numpre não informado.";
       $this->erro_campo = "x22_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x22_tipo == null ){ 
       $this->erro_sql = " Campo Tipo calculo não informado.";
       $this->erro_campo = "x22_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x22_data == null ){ 
       $this->erro_sql = " Campo Data Alteração não informado.";
       $this->erro_campo = "x22_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x22_hora == null ){ 
       $this->erro_sql = " Campo Hora Alteração não informado.";
       $this->erro_campo = "x22_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x22_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário não informado.";
       $this->erro_campo = "x22_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x22_aguacontratoeconomia == null ){ 
       $this->x22_aguacontratoeconomia = "null";
     }
     if(is_null($this->x22_responsavelpagamento)){ 
       $this->x22_responsavelpagamento = "null";
     }
     if($x22_codcalc == "" || $x22_codcalc == null ){
       $result = db_query("select nextval('aguacalc_x22_codcalc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacalc_x22_codcalc_seq do campo: x22_codcalc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x22_codcalc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacalc_x22_codcalc_seq");
       if(($result != false) && (pg_result($result,0,0) < $x22_codcalc)){
         $this->erro_sql = " Campo x22_codcalc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x22_codcalc = $x22_codcalc; 
       }
     }
     if(($this->x22_codcalc == null) || ($this->x22_codcalc == "") ){ 
       $this->erro_sql = " Campo x22_codcalc não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacalc(
                                       x22_codcalc 
                                      ,x22_codconsumo 
                                      ,x22_exerc 
                                      ,x22_mes 
                                      ,x22_matric 
                                      ,x22_area 
                                      ,x22_numpre 
                                      ,x22_manual 
                                      ,x22_tipo 
                                      ,x22_data 
                                      ,x22_hora 
                                      ,x22_usuario 
                                      ,x22_aguacontrato 
                                      ,x22_aguacontratoeconomia 
                                      ,x22_responsavelpagamento 
                       )
                values (
                                $this->x22_codcalc 
                               ,$this->x22_codconsumo 
                               ,$this->x22_exerc 
                               ,$this->x22_mes 
                               ,$this->x22_matric 
                               ,$this->x22_area 
                               ,$this->x22_numpre 
                               ,'$this->x22_manual' 
                               ,$this->x22_tipo 
                               ,".($this->x22_data == "null" || $this->x22_data == ""?"null":"'".$this->x22_data."'")." 
                               ,'$this->x22_hora' 
                               ,$this->x22_usuario 
                               ,$this->x22_aguacontrato 
                               ,$this->x22_aguacontratoeconomia 
                               ,$this->x22_responsavelpagamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacalc ($this->x22_codcalc) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacalc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacalc ($this->x22_codcalc) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->x22_codcalc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x22_codcalc  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8491,'$this->x22_codcalc','I')");
         $resac = db_query("insert into db_acount values($acount,1443,8491,'','".AddSlashes(pg_result($resaco,0,'x22_codcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,8492,'','".AddSlashes(pg_result($resaco,0,'x22_codconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,8493,'','".AddSlashes(pg_result($resaco,0,'x22_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,8494,'','".AddSlashes(pg_result($resaco,0,'x22_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,8495,'','".AddSlashes(pg_result($resaco,0,'x22_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,8496,'','".AddSlashes(pg_result($resaco,0,'x22_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,8497,'','".AddSlashes(pg_result($resaco,0,'x22_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,8514,'','".AddSlashes(pg_result($resaco,0,'x22_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,16624,'','".AddSlashes(pg_result($resaco,0,'x22_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,16625,'','".AddSlashes(pg_result($resaco,0,'x22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,16626,'','".AddSlashes(pg_result($resaco,0,'x22_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,16627,'','".AddSlashes(pg_result($resaco,0,'x22_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,22252,'','".AddSlashes(pg_result($resaco,0,'x22_aguacontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,22423,'','".AddSlashes(pg_result($resaco,0,'x22_aguacontratoeconomia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1443,1009275,'','".AddSlashes(pg_result($resaco,0,'x22_responsavelpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($x22_codcalc=null) { 
      $this->atualizacampos();
     $sql = " update aguacalc set ";
     $virgula = "";
     if(trim($this->x22_codcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_codcalc"])){ 
       $sql  .= $virgula." x22_codcalc = $this->x22_codcalc ";
       $virgula = ",";
       if(trim($this->x22_codcalc) == null ){ 
         $this->erro_sql = " Campo Codigo não informado.";
         $this->erro_campo = "x22_codcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x22_codconsumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_codconsumo"])){ 
        if(trim($this->x22_codconsumo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x22_codconsumo"])){ 
           $this->x22_codconsumo = "0" ; 
        } 
       $sql  .= $virgula." x22_codconsumo = $this->x22_codconsumo ";
       $virgula = ",";
     }
     if(trim($this->x22_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_exerc"])){ 
       $sql  .= $virgula." x22_exerc = $this->x22_exerc ";
       $virgula = ",";
       if(trim($this->x22_exerc) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "x22_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x22_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_mes"])){ 
       $sql  .= $virgula." x22_mes = $this->x22_mes ";
       $virgula = ",";
       if(trim($this->x22_mes) == null ){ 
         $this->erro_sql = " Campo Mes não informado.";
         $this->erro_campo = "x22_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x22_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_matric"])){ 
        if(trim($this->x22_matric)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x22_matric"])){ 
           $this->x22_matric = "0" ; 
        } 
       $sql  .= $virgula." x22_matric = $this->x22_matric ";
       $virgula = ",";
     }
     if(trim($this->x22_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_area"])){ 
        if(trim($this->x22_area)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x22_area"])){ 
           $this->x22_area = "0" ; 
        } 
       $sql  .= $virgula." x22_area = $this->x22_area ";
       $virgula = ",";
     }
     if(trim($this->x22_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_numpre"])){ 
       $sql  .= $virgula." x22_numpre = $this->x22_numpre ";
       $virgula = ",";
       if(trim($this->x22_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre não informado.";
         $this->erro_campo = "x22_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x22_manual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_manual"])){ 
       $sql  .= $virgula." x22_manual = '$this->x22_manual' ";
       $virgula = ",";
     }
     if(trim($this->x22_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_tipo"])){ 
       $sql  .= $virgula." x22_tipo = $this->x22_tipo ";
       $virgula = ",";
       if(trim($this->x22_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo calculo não informado.";
         $this->erro_campo = "x22_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x22_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x22_data_dia"] !="") ){ 
       $sql  .= $virgula." x22_data = '$this->x22_data' ";
       $virgula = ",";
       if(trim($this->x22_data) == null ){ 
         $this->erro_sql = " Campo Data Alteração não informado.";
         $this->erro_campo = "x22_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x22_data_dia"])){ 
         $sql  .= $virgula." x22_data = null ";
         $virgula = ",";
         if(trim($this->x22_data) == null ){ 
           $this->erro_sql = " Campo Data Alteração não informado.";
           $this->erro_campo = "x22_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x22_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_hora"])){ 
       $sql  .= $virgula." x22_hora = '$this->x22_hora' ";
       $virgula = ",";
       if(trim($this->x22_hora) == null ){ 
         $this->erro_sql = " Campo Hora Alteração não informado.";
         $this->erro_campo = "x22_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x22_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_usuario"])){ 
       $sql  .= $virgula." x22_usuario = $this->x22_usuario ";
       $virgula = ",";
       if(trim($this->x22_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário não informado.";
         $this->erro_campo = "x22_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x22_aguacontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_aguacontrato"])){ 
        if(trim($this->x22_aguacontrato)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x22_aguacontrato"])){ 
           $this->x22_aguacontrato = "0" ; 
        } 
       $sql  .= $virgula." x22_aguacontrato = $this->x22_aguacontrato ";
       $virgula = ",";
     }
     if(trim($this->x22_aguacontratoeconomia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_aguacontratoeconomia"])){ 
        if(trim($this->x22_aguacontratoeconomia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x22_aguacontratoeconomia"])){ 
           $this->x22_aguacontratoeconomia = "0" ; 
        } 
       $sql  .= $virgula." x22_aguacontratoeconomia = $this->x22_aguacontratoeconomia ";
       $virgula = ",";
     }
     if(trim($this->x22_responsavelpagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x22_responsavelpagamento"])){ 
        if(trim($this->x22_responsavelpagamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x22_responsavelpagamento"])){ 
           $this->x22_responsavelpagamento = "0" ; 
        } 
       $sql  .= $virgula." x22_responsavelpagamento = $this->x22_responsavelpagamento ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x22_codcalc!=null){
       $sql .= " x22_codcalc = $this->x22_codcalc";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x22_codcalc));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,8491,'$this->x22_codcalc','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_codcalc"]) || $this->x22_codcalc != "")
             $resac = db_query("insert into db_acount values($acount,1443,8491,'".AddSlashes(pg_result($resaco,$conresaco,'x22_codcalc'))."','$this->x22_codcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_codconsumo"]) || $this->x22_codconsumo != "")
             $resac = db_query("insert into db_acount values($acount,1443,8492,'".AddSlashes(pg_result($resaco,$conresaco,'x22_codconsumo'))."','$this->x22_codconsumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_exerc"]) || $this->x22_exerc != "")
             $resac = db_query("insert into db_acount values($acount,1443,8493,'".AddSlashes(pg_result($resaco,$conresaco,'x22_exerc'))."','$this->x22_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_mes"]) || $this->x22_mes != "")
             $resac = db_query("insert into db_acount values($acount,1443,8494,'".AddSlashes(pg_result($resaco,$conresaco,'x22_mes'))."','$this->x22_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_matric"]) || $this->x22_matric != "")
             $resac = db_query("insert into db_acount values($acount,1443,8495,'".AddSlashes(pg_result($resaco,$conresaco,'x22_matric'))."','$this->x22_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_area"]) || $this->x22_area != "")
             $resac = db_query("insert into db_acount values($acount,1443,8496,'".AddSlashes(pg_result($resaco,$conresaco,'x22_area'))."','$this->x22_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_numpre"]) || $this->x22_numpre != "")
             $resac = db_query("insert into db_acount values($acount,1443,8497,'".AddSlashes(pg_result($resaco,$conresaco,'x22_numpre'))."','$this->x22_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_manual"]) || $this->x22_manual != "")
             $resac = db_query("insert into db_acount values($acount,1443,8514,'".AddSlashes(pg_result($resaco,$conresaco,'x22_manual'))."','$this->x22_manual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_tipo"]) || $this->x22_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1443,16624,'".AddSlashes(pg_result($resaco,$conresaco,'x22_tipo'))."','$this->x22_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_data"]) || $this->x22_data != "")
             $resac = db_query("insert into db_acount values($acount,1443,16625,'".AddSlashes(pg_result($resaco,$conresaco,'x22_data'))."','$this->x22_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_hora"]) || $this->x22_hora != "")
             $resac = db_query("insert into db_acount values($acount,1443,16626,'".AddSlashes(pg_result($resaco,$conresaco,'x22_hora'))."','$this->x22_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_usuario"]) || $this->x22_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1443,16627,'".AddSlashes(pg_result($resaco,$conresaco,'x22_usuario'))."','$this->x22_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_aguacontrato"]) || $this->x22_aguacontrato != "")
             $resac = db_query("insert into db_acount values($acount,1443,22252,'".AddSlashes(pg_result($resaco,$conresaco,'x22_aguacontrato'))."','$this->x22_aguacontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_aguacontratoeconomia"]) || $this->x22_aguacontratoeconomia != "")
             $resac = db_query("insert into db_acount values($acount,1443,22423,'".AddSlashes(pg_result($resaco,$conresaco,'x22_aguacontratoeconomia'))."','$this->x22_aguacontratoeconomia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x22_responsavelpagamento"]) || $this->x22_responsavelpagamento != "")
             $resac = db_query("insert into db_acount values($acount,1443,1009275,'".AddSlashes(pg_result($resaco,$conresaco,'x22_responsavelpagamento'))."','$this->x22_responsavelpagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacalc não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x22_codcalc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "aguacalc não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x22_codcalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->x22_codcalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($x22_codcalc=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($x22_codcalc));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,8491,'$x22_codcalc','E')");
           $resac  = db_query("insert into db_acount values($acount,1443,8491,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_codcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,8492,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_codconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,8493,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,8494,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,8495,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,8496,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,8497,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,8514,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,16624,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,16625,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,16626,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,16627,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,22252,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_aguacontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,22423,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_aguacontratoeconomia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1443,1009275,'','".AddSlashes(pg_result($resaco,$iresaco,'x22_responsavelpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aguacalc
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($x22_codcalc)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " x22_codcalc = $x22_codcalc ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacalc não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x22_codcalc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "aguacalc não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x22_codcalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$x22_codcalc;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($x22_codcalc = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from aguacalc ";
     $sql .= "      inner  join aguabase  on  aguabase.x01_matric = aguacalc.x22_matric";
     $sql .= "      inner  join aguaconsumo  on  aguaconsumo.x19_codconsumo = aguacalc.x22_codconsumo";
     $sql .= "      left  join aguacontrato  on  aguacontrato.x54_sequencial = aguacalc.x22_aguacontrato";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      left  join caracter  on  caracter.j31_codigo = aguaconsumo.x19_caract";
     $sql .= "      left  join aguacontratoeconomia  on  aguacontratoeconomia.x38_sequencial = aguacalc.x22_aguacontratoeconomia";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x22_codcalc)) {
         $sql2 .= " where aguacalc.x22_codcalc = $x22_codcalc "; 
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
   public function sql_query_file ($x22_codcalc = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from aguacalc ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x22_codcalc)){
         $sql2 .= " where aguacalc.x22_codcalc = $x22_codcalc "; 
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
