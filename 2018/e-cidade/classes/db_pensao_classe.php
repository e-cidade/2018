<?
//MODULO: pessoal
//CLASSE DA ENTIDADE pensao
class cl_pensao { 
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
   var $r52_anousu = 0; 
   var $r52_mesusu = 0; 
   var $r52_regist = 0; 
   var $r52_formul = null; 
   var $r52_perc = 0; 
   var $r52_numcgm = 0; 
   var $r52_codbco = null; 
   var $r52_codage = null; 
   var $r52_conta = null; 
   var $r52_vlrpen = 0; 
   var $r52_dtincl_dia = null; 
   var $r52_dtincl_mes = null; 
   var $r52_dtincl_ano = null; 
   var $r52_dtincl = null; 
   var $r52_pag13 = 'f'; 
   var $r52_pagfer = 'f'; 
   var $r52_pagcom = 'f'; 
   var $r52_valor = 0; 
   var $r52_valcom = 0; 
   var $r52_val13 = 0; 
   var $r52_limite_dia = null; 
   var $r52_limite_mes = null; 
   var $r52_limite_ano = null; 
   var $r52_limite = null; 
   var $r52_dvagencia = null; 
   var $r52_dvconta = null; 
   var $r52_valfer = 0; 
   var $r52_valres = 0; 
   var $r52_pagres = 'f'; 
   var $r52_adiantamento13 = 'f'; 
   var $r52_percadiantamento13 = 0; 
   var $r52_sequencial = 0; 
   var $r52_pagasuplementar = 'f'; 
   var $r52_valorsuplementar = 0; 
   var $r52_relacaodependencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r52_anousu = int4 = Ano 
                 r52_mesusu = int4 = Mês 
                 r52_regist = int4 = Código do Servidor 
                 r52_formul = char(200) = Fórmula 
                 r52_perc = float8 = Percentual 
                 r52_numcgm = int4 = CGM 
                 r52_codbco = char(3) = Banco 
                 r52_codage = char(5) = Agência 
                 r52_conta = char(15) = Conta Corrente 
                 r52_vlrpen = float8 = valor da pensao 
                 r52_dtincl = date = Data 
                 r52_pag13 = bool = 13o. Salário 
                 r52_pagfer = bool = Férias 
                 r52_pagcom = bool = Complementar 
                 r52_valor = float8 = Valor 
                 r52_valcom = float8 = valor da pensao da complementa 
                 r52_val13 = float8 = Valor 
                 r52_limite = date = Data Limite 
                 r52_dvagencia = char(2) = DV da agência 
                 r52_dvconta = char(2) = DV da conta 
                 r52_valfer = float4 = Pensao Ferias 
                 r52_valres = float8 = Rescisão 
                 r52_pagres = bool = Rescisão 
                 r52_adiantamento13 = bool = Calcula no Adiantamento 13º 
                 r52_percadiantamento13 = float8 = Percentual do adiantamento 13º 
                 r52_sequencial = int4 = Sequencial 
                 r52_pagasuplementar = bool = Suplementar 
                 r52_valorsuplementar = float8 = Valor da Suplementar 
                 r52_relacaodependencia = int4 = Relação de Dependência 
                 ";
   //funcao construtor da classe 
   function cl_pensao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pensao"); 
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
       $this->r52_anousu = ($this->r52_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_anousu"]:$this->r52_anousu);
       $this->r52_mesusu = ($this->r52_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_mesusu"]:$this->r52_mesusu);
       $this->r52_regist = ($this->r52_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_regist"]:$this->r52_regist);
       $this->r52_formul = ($this->r52_formul == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_formul"]:$this->r52_formul);
       $this->r52_perc = ($this->r52_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_perc"]:$this->r52_perc);
       $this->r52_numcgm = ($this->r52_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_numcgm"]:$this->r52_numcgm);
       $this->r52_codbco = ($this->r52_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_codbco"]:$this->r52_codbco);
       $this->r52_codage = ($this->r52_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_codage"]:$this->r52_codage);
       $this->r52_conta = ($this->r52_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_conta"]:$this->r52_conta);
       $this->r52_vlrpen = ($this->r52_vlrpen == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_vlrpen"]:$this->r52_vlrpen);
       if($this->r52_dtincl == ""){
         $this->r52_dtincl_dia = ($this->r52_dtincl_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_dtincl_dia"]:$this->r52_dtincl_dia);
         $this->r52_dtincl_mes = ($this->r52_dtincl_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_dtincl_mes"]:$this->r52_dtincl_mes);
         $this->r52_dtincl_ano = ($this->r52_dtincl_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_dtincl_ano"]:$this->r52_dtincl_ano);
         if($this->r52_dtincl_dia != ""){
            $this->r52_dtincl = $this->r52_dtincl_ano."-".$this->r52_dtincl_mes."-".$this->r52_dtincl_dia;
         }
       }
       $this->r52_pag13 = ($this->r52_pag13 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r52_pag13"]:$this->r52_pag13);
       $this->r52_pagfer = ($this->r52_pagfer == "f"?@$GLOBALS["HTTP_POST_VARS"]["r52_pagfer"]:$this->r52_pagfer);
       $this->r52_pagcom = ($this->r52_pagcom == "f"?@$GLOBALS["HTTP_POST_VARS"]["r52_pagcom"]:$this->r52_pagcom);
       $this->r52_valor = ($this->r52_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_valor"]:$this->r52_valor);
       $this->r52_valcom = ($this->r52_valcom == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_valcom"]:$this->r52_valcom);
       $this->r52_val13 = ($this->r52_val13 == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_val13"]:$this->r52_val13);
       if($this->r52_limite == ""){
         $this->r52_limite_dia = ($this->r52_limite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_limite_dia"]:$this->r52_limite_dia);
         $this->r52_limite_mes = ($this->r52_limite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_limite_mes"]:$this->r52_limite_mes);
         $this->r52_limite_ano = ($this->r52_limite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_limite_ano"]:$this->r52_limite_ano);
         if($this->r52_limite_dia != ""){
            $this->r52_limite = $this->r52_limite_ano."-".$this->r52_limite_mes."-".$this->r52_limite_dia;
         }
       }
       $this->r52_dvagencia = ($this->r52_dvagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_dvagencia"]:$this->r52_dvagencia);
       $this->r52_dvconta = ($this->r52_dvconta == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_dvconta"]:$this->r52_dvconta);
       $this->r52_valfer = ($this->r52_valfer == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_valfer"]:$this->r52_valfer);
       $this->r52_valres = ($this->r52_valres == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_valres"]:$this->r52_valres);
       $this->r52_pagres = ($this->r52_pagres == "f"?@$GLOBALS["HTTP_POST_VARS"]["r52_pagres"]:$this->r52_pagres);
       $this->r52_adiantamento13 = ($this->r52_adiantamento13 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r52_adiantamento13"]:$this->r52_adiantamento13);
       $this->r52_percadiantamento13 = ($this->r52_percadiantamento13 == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_percadiantamento13"]:$this->r52_percadiantamento13);
       $this->r52_sequencial = ($this->r52_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_sequencial"]:$this->r52_sequencial);
       $this->r52_pagasuplementar = ($this->r52_pagasuplementar == "f"?@$GLOBALS["HTTP_POST_VARS"]["r52_pagasuplementar"]:$this->r52_pagasuplementar);
       $this->r52_valorsuplementar = ($this->r52_valorsuplementar == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_valorsuplementar"]:$this->r52_valorsuplementar);
       $this->r52_relacaodependencia = ($this->r52_relacaodependencia == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_relacaodependencia"]:$this->r52_relacaodependencia);
     }else{
       $this->r52_sequencial = ($this->r52_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r52_sequencial"]:$this->r52_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($r52_anousu, $r52_mesusu, $r52_regist, $r52_numcgm){

      $this->atualizacampos();

      $this->r52_anousu = $r52_anousu;
      $this->r52_mesusu = $r52_mesusu;
      $this->r52_regist = $r52_regist;
      $this->r52_numcgm = $r52_numcgm;

     if($this->r52_anousu == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "r52_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_mesusu == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "r52_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_regist == null ){ 
       $this->erro_sql = " Campo Código do Servidor não informado.";
       $this->erro_campo = "r52_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_perc == null ){ 
       $this->r52_perc = "0";
     }
     if($this->r52_numcgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "r52_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_vlrpen == null ){ 
       $this->r52_vlrpen = "0";
     }
     if($this->r52_dtincl == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "r52_dtincl_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_pag13 == null ){ 
       $this->erro_sql = " Campo 13o. Salário não informado.";
       $this->erro_campo = "r52_pag13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_pagfer == null ){ 
       $this->erro_sql = " Campo Férias não informado.";
       $this->erro_campo = "r52_pagfer";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_pagcom == null ){ 
       $this->erro_sql = " Campo Complementar não informado.";
       $this->erro_campo = "r52_pagcom";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_valor == null ){ 
       $this->r52_valor = "0";
     }
     if($this->r52_valcom == null ){ 
       $this->r52_valcom = "0";
     }
     if($this->r52_val13 == null ){ 
       $this->r52_val13 = "0";
     }
     if($this->r52_valorsuplementar == null ){
       $this->r52_valorsuplementar = "0";
     }
     if($this->r52_limite == null ){
       $this->r52_limite = "null";
     }
     if($this->r52_valfer == null ){ 
       $this->erro_sql = " Campo Pensao Ferias não informado.";
       $this->erro_campo = "r52_valfer";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_valres == null ){ 
       $this->r52_valres = "0";
     }
     if($this->r52_pagres == null ){ 
       $this->erro_sql = " Campo Rescisão não informado.";
       $this->erro_campo = "r52_pagres";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_adiantamento13 == null ){ 
       $this->erro_sql = " Campo Calcula no Adiantamento 13º não informado.";
       $this->erro_campo = "r52_adiantamento13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_percadiantamento13 == null ){ 
       $this->erro_sql = " Campo Percentual do adiantamento 13º não informado.";
       $this->erro_campo = "r52_percadiantamento13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_pagasuplementar == null ){ 
       $this->erro_sql = " Campo Paga na Suplementar não informado.";
       $this->erro_campo = "r52_pagasuplementar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_valorsuplementar == null ){ 
       $this->erro_sql = " Campo Valor da Suplementar não informado.";
       $this->erro_campo = "r52_valorsuplementar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r52_relacaodependencia == null ){ 
       $this->r52_relacaodependencia = "0";
     }
     if ($this->r52_sequencial == "" || $this->r52_sequencial == null ) {

       $result = db_query("select nextval('pensao_r52_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pensao_r52_sequencial_seq do campo: r52_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->r52_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pensao_r52_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $this->r52_sequencial)){
         $this->erro_sql = " Campo r52_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->r52_sequencial = $this->r52_sequencial;
       }
     }
     if(($this->r52_sequencial == null) || ($this->r52_sequencial == "") ){ 
       $this->erro_sql = " Campo r52_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pensao(
                                       r52_anousu 
                                      ,r52_mesusu 
                                      ,r52_regist 
                                      ,r52_formul 
                                      ,r52_perc 
                                      ,r52_numcgm 
                                      ,r52_codbco 
                                      ,r52_codage 
                                      ,r52_conta 
                                      ,r52_vlrpen 
                                      ,r52_dtincl 
                                      ,r52_pag13 
                                      ,r52_pagfer 
                                      ,r52_pagcom 
                                      ,r52_valor 
                                      ,r52_valcom 
                                      ,r52_val13 
                                      ,r52_limite 
                                      ,r52_dvagencia 
                                      ,r52_dvconta 
                                      ,r52_valfer 
                                      ,r52_valres 
                                      ,r52_pagres 
                                      ,r52_adiantamento13 
                                      ,r52_percadiantamento13 
                                      ,r52_sequencial 
                                      ,r52_pagasuplementar 
                                      ,r52_valorsuplementar 
                                      ,r52_relacaodependencia 
                       )
                values (
                                $this->r52_anousu 
                               ,$this->r52_mesusu 
                               ,$this->r52_regist 
                               ,'$this->r52_formul' 
                               ,$this->r52_perc 
                               ,$this->r52_numcgm 
                               ,'$this->r52_codbco' 
                               ,'$this->r52_codage' 
                               ,'$this->r52_conta' 
                               ,$this->r52_vlrpen 
                               ,".($this->r52_dtincl == "null" || $this->r52_dtincl == ""?"null":"'".$this->r52_dtincl."'")." 
                               ,'$this->r52_pag13' 
                               ,'$this->r52_pagfer' 
                               ,'$this->r52_pagcom' 
                               ,$this->r52_valor 
                               ,$this->r52_valcom 
                               ,$this->r52_val13 
                               ,".($this->r52_limite == "null" || $this->r52_limite == ""?"null":"'".$this->r52_limite."'")." 
                               ,'$this->r52_dvagencia' 
                               ,'$this->r52_dvconta' 
                               ,$this->r52_valfer 
                               ,$this->r52_valres 
                               ,'$this->r52_pagres' 
                               ,'$this->r52_adiantamento13' 
                               ,$this->r52_percadiantamento13 
                               ,$this->r52_sequencial 
                               ,'$this->r52_pagasuplementar' 
                               ,$this->r52_valorsuplementar 
                               ,$this->r52_relacaodependencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pensoes Alimenticias ($this->r52_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pensoes Alimenticias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pensoes Alimenticias ($this->r52_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->r52_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r52_anousu, $this->r52_mesusu, $this->r52_regist, $this->r52_numcgm));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20827,'$this->r52_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,570,4113,'','".AddSlashes(pg_result($resaco,0,'r52_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4114,'','".AddSlashes(pg_result($resaco,0,'r52_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4115,'','".AddSlashes(pg_result($resaco,0,'r52_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4116,'','".AddSlashes(pg_result($resaco,0,'r52_formul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4117,'','".AddSlashes(pg_result($resaco,0,'r52_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4118,'','".AddSlashes(pg_result($resaco,0,'r52_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4119,'','".AddSlashes(pg_result($resaco,0,'r52_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4120,'','".AddSlashes(pg_result($resaco,0,'r52_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4121,'','".AddSlashes(pg_result($resaco,0,'r52_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4122,'','".AddSlashes(pg_result($resaco,0,'r52_vlrpen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4123,'','".AddSlashes(pg_result($resaco,0,'r52_dtincl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4124,'','".AddSlashes(pg_result($resaco,0,'r52_pag13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4125,'','".AddSlashes(pg_result($resaco,0,'r52_pagfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4126,'','".AddSlashes(pg_result($resaco,0,'r52_pagcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4127,'','".AddSlashes(pg_result($resaco,0,'r52_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4128,'','".AddSlashes(pg_result($resaco,0,'r52_valcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4129,'','".AddSlashes(pg_result($resaco,0,'r52_val13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,4602,'','".AddSlashes(pg_result($resaco,0,'r52_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,7777,'','".AddSlashes(pg_result($resaco,0,'r52_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,7776,'','".AddSlashes(pg_result($resaco,0,'r52_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,10034,'','".AddSlashes(pg_result($resaco,0,'r52_valfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,11914,'','".AddSlashes(pg_result($resaco,0,'r52_valres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,11913,'','".AddSlashes(pg_result($resaco,0,'r52_pagres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,17334,'','".AddSlashes(pg_result($resaco,0,'r52_adiantamento13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,17335,'','".AddSlashes(pg_result($resaco,0,'r52_percadiantamento13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,20827,'','".AddSlashes(pg_result($resaco,0,'r52_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,20828,'','".AddSlashes(pg_result($resaco,0,'r52_pagasuplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,20829,'','".AddSlashes(pg_result($resaco,0,'r52_valorsuplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,570,22304,'','".AddSlashes(pg_result($resaco,0,'r52_relacaodependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
  function alterar($r52_anousu = null, $r52_mesusu = null, $r52_regist = null, $r52_numcgm = null) {
      $this->atualizacampos();
     $sql = " update pensao set ";
     $virgula = "";
     if(trim($this->r52_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_anousu"])){ 
       $sql  .= $virgula." r52_anousu = $this->r52_anousu ";
       $virgula = ",";
       if(trim($this->r52_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "r52_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_mesusu"])){ 
       $sql  .= $virgula." r52_mesusu = $this->r52_mesusu ";
       $virgula = ",";
       if(trim($this->r52_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "r52_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_regist"])){ 
       $sql  .= $virgula." r52_regist = $this->r52_regist ";
       $virgula = ",";
       if(trim($this->r52_regist) == null ){ 
         $this->erro_sql = " Campo Código do Servidor não informado.";
         $this->erro_campo = "r52_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_formul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_formul"])){ 
       $sql  .= $virgula." r52_formul = '$this->r52_formul' ";
       $virgula = ",";
     }
     if(trim($this->r52_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_perc"])){ 
        if(trim($this->r52_perc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r52_perc"])){ 
           $this->r52_perc = "0" ; 
        } 
       $sql  .= $virgula." r52_perc = $this->r52_perc ";
       $virgula = ",";
     }
     if(trim($this->r52_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_numcgm"])){ 
       $sql  .= $virgula." r52_numcgm = $this->r52_numcgm ";
       $virgula = ",";
       if(trim($this->r52_numcgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "r52_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_codbco"])){ 
       $sql  .= $virgula." r52_codbco = '$this->r52_codbco' ";
       $virgula = ",";
     }
     if(trim($this->r52_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_codage"])){ 
       $sql  .= $virgula." r52_codage = '$this->r52_codage' ";
       $virgula = ",";
     }
     if(trim($this->r52_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_conta"])){ 
       $sql  .= $virgula." r52_conta = '$this->r52_conta' ";
       $virgula = ",";
     }
     if(trim($this->r52_vlrpen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_vlrpen"])){ 
        if(trim($this->r52_vlrpen)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r52_vlrpen"])){ 
           $this->r52_vlrpen = "0" ; 
        } 
       $sql  .= $virgula." r52_vlrpen = $this->r52_vlrpen ";
       $virgula = ",";
     }
     if(trim($this->r52_dtincl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_dtincl_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r52_dtincl_dia"] !="") ){ 
       $sql  .= $virgula." r52_dtincl = '$this->r52_dtincl' ";
       $virgula = ",";
       if(trim($this->r52_dtincl) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "r52_dtincl_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r52_dtincl_dia"])){ 
         $sql  .= $virgula." r52_dtincl = null ";
         $virgula = ",";
         if(trim($this->r52_dtincl) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "r52_dtincl_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r52_pag13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_pag13"])){ 
       $sql  .= $virgula." r52_pag13 = '$this->r52_pag13' ";
       $virgula = ",";
       if(trim($this->r52_pag13) == null ){ 
         $this->erro_sql = " Campo 13o. Salário não informado.";
         $this->erro_campo = "r52_pag13";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_pagfer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_pagfer"])){ 
       $sql  .= $virgula." r52_pagfer = '$this->r52_pagfer' ";
       $virgula = ",";
       if(trim($this->r52_pagfer) == null ){ 
         $this->erro_sql = " Campo Férias não informado.";
         $this->erro_campo = "r52_pagfer";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_pagcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_pagcom"])){ 
       $sql  .= $virgula." r52_pagcom = '$this->r52_pagcom' ";
       $virgula = ",";
       if(trim($this->r52_pagcom) == null ){ 
         $this->erro_sql = " Campo Complementar não informado.";
         $this->erro_campo = "r52_pagcom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_valor"])){ 
        if(trim($this->r52_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r52_valor"])){ 
           $this->r52_valor = "0" ; 
        } 
       $sql  .= $virgula." r52_valor = $this->r52_valor ";
       $virgula = ",";
     }
     if(trim($this->r52_valcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_valcom"])){ 
        if(trim($this->r52_valcom)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r52_valcom"])){ 
           $this->r52_valcom = "0" ; 
        } 
       $sql  .= $virgula." r52_valcom = $this->r52_valcom ";
       $virgula = ",";
     }
     if(trim($this->r52_val13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_val13"])){ 
        if(trim($this->r52_val13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r52_val13"])){ 
           $this->r52_val13 = "0" ; 
        } 
       $sql  .= $virgula." r52_val13 = $this->r52_val13 ";
       $virgula = ",";
     }
     if(trim($this->r52_limite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_limite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r52_limite_dia"] !="") ){ 
       $sql  .= $virgula." r52_limite = '$this->r52_limite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r52_limite_dia"])){ 
         $sql  .= $virgula." r52_limite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r52_dvagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_dvagencia"])){ 
       $sql  .= $virgula." r52_dvagencia = '$this->r52_dvagencia' ";
       $virgula = ",";
     }
     if(trim($this->r52_dvconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_dvconta"])){ 
       $sql  .= $virgula." r52_dvconta = '$this->r52_dvconta' ";
       $virgula = ",";
     }
     if(trim($this->r52_valfer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_valfer"])){ 
       $sql  .= $virgula." r52_valfer = $this->r52_valfer ";
       $virgula = ",";
       if(trim($this->r52_valfer) == null ){ 
         $this->erro_sql = " Campo Pensao Ferias não informado.";
         $this->erro_campo = "r52_valfer";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_valres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_valres"])){ 
        if(trim($this->r52_valres)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r52_valres"])){ 
           $this->r52_valres = "0" ; 
        } 
       $sql  .= $virgula." r52_valres = $this->r52_valres ";
       $virgula = ",";
     }
     if(trim($this->r52_pagres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_pagres"])){ 
       $sql  .= $virgula." r52_pagres = '$this->r52_pagres' ";
       $virgula = ",";
       if(trim($this->r52_pagres) == null ){ 
         $this->erro_sql = " Campo Rescisão não informado.";
         $this->erro_campo = "r52_pagres";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_adiantamento13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_adiantamento13"])){ 
       $sql  .= $virgula." r52_adiantamento13 = '$this->r52_adiantamento13' ";
       $virgula = ",";
       if(trim($this->r52_adiantamento13) == null ){ 
         $this->erro_sql = " Campo Calcula no Adiantamento 13º não informado.";
         $this->erro_campo = "r52_adiantamento13";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_percadiantamento13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_percadiantamento13"])){ 
       $sql  .= $virgula." r52_percadiantamento13 = $this->r52_percadiantamento13 ";
       $virgula = ",";
       if(trim($this->r52_percadiantamento13) == null ){ 
         $this->erro_sql = " Campo Percentual do adiantamento 13º não informado.";
         $this->erro_campo = "r52_percadiantamento13";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_sequencial"])){ 
       $sql  .= $virgula." r52_sequencial = $this->r52_sequencial ";
       $virgula = ",";
       if(trim($this->r52_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "r52_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_pagasuplementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_pagasuplementar"])){ 
       $sql  .= $virgula." r52_pagasuplementar = '$this->r52_pagasuplementar' ";
       $virgula = ",";
       if(trim($this->r52_pagasuplementar) == null ){ 
         $this->erro_sql = " Campo Paga na Suplementar não informado.";
         $this->erro_campo = "r52_pagasuplementar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_valorsuplementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_valorsuplementar"])){ 
       $sql  .= $virgula." r52_valorsuplementar = $this->r52_valorsuplementar ";
       $virgula = ",";
       if(trim($this->r52_valorsuplementar) == null ){ 
         $this->erro_sql = " Campo Valor da Suplementar não informado.";
         $this->erro_campo = "r52_valorsuplementar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r52_relacaodependencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r52_relacaodependencia"])){ 
        if(trim($this->r52_relacaodependencia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r52_relacaodependencia"])){ 
           $this->r52_relacaodependencia = "0" ; 
        } 
       $sql  .= $virgula." r52_relacaodependencia = $this->r52_relacaodependencia ";
       $virgula = ",";
     }
     $sql .= " where ";
    if ($r52_anousu != null) {
      $sql .= " r52_anousu = $this->r52_anousu";
    }
    if ($r52_mesusu != null) {
      $sql .= " and  r52_mesusu = $this->r52_mesusu";
    }
    if ($r52_regist != null) {
      $sql .= " and  r52_regist = $this->r52_regist";
    }
    if ($r52_numcgm != null) {
      $sql .= " and  r52_numcgm = $this->r52_numcgm";
    }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r52_anousu, $this->r52_mesusu, $this->r52_regist, $this->r52_numcgm));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20827,'$this->r52_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_anousu"]) || $this->r52_anousu != "")
             $resac = db_query("insert into db_acount values($acount,570,4113,'".AddSlashes(pg_result($resaco,$conresaco,'r52_anousu'))."','$this->r52_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_mesusu"]) || $this->r52_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,570,4114,'".AddSlashes(pg_result($resaco,$conresaco,'r52_mesusu'))."','$this->r52_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_regist"]) || $this->r52_regist != "")
             $resac = db_query("insert into db_acount values($acount,570,4115,'".AddSlashes(pg_result($resaco,$conresaco,'r52_regist'))."','$this->r52_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_formul"]) || $this->r52_formul != "")
             $resac = db_query("insert into db_acount values($acount,570,4116,'".AddSlashes(pg_result($resaco,$conresaco,'r52_formul'))."','$this->r52_formul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_perc"]) || $this->r52_perc != "")
             $resac = db_query("insert into db_acount values($acount,570,4117,'".AddSlashes(pg_result($resaco,$conresaco,'r52_perc'))."','$this->r52_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_numcgm"]) || $this->r52_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,570,4118,'".AddSlashes(pg_result($resaco,$conresaco,'r52_numcgm'))."','$this->r52_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_codbco"]) || $this->r52_codbco != "")
             $resac = db_query("insert into db_acount values($acount,570,4119,'".AddSlashes(pg_result($resaco,$conresaco,'r52_codbco'))."','$this->r52_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_codage"]) || $this->r52_codage != "")
             $resac = db_query("insert into db_acount values($acount,570,4120,'".AddSlashes(pg_result($resaco,$conresaco,'r52_codage'))."','$this->r52_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_conta"]) || $this->r52_conta != "")
             $resac = db_query("insert into db_acount values($acount,570,4121,'".AddSlashes(pg_result($resaco,$conresaco,'r52_conta'))."','$this->r52_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_vlrpen"]) || $this->r52_vlrpen != "")
             $resac = db_query("insert into db_acount values($acount,570,4122,'".AddSlashes(pg_result($resaco,$conresaco,'r52_vlrpen'))."','$this->r52_vlrpen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_dtincl"]) || $this->r52_dtincl != "")
             $resac = db_query("insert into db_acount values($acount,570,4123,'".AddSlashes(pg_result($resaco,$conresaco,'r52_dtincl'))."','$this->r52_dtincl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_pag13"]) || $this->r52_pag13 != "")
             $resac = db_query("insert into db_acount values($acount,570,4124,'".AddSlashes(pg_result($resaco,$conresaco,'r52_pag13'))."','$this->r52_pag13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_pagfer"]) || $this->r52_pagfer != "")
             $resac = db_query("insert into db_acount values($acount,570,4125,'".AddSlashes(pg_result($resaco,$conresaco,'r52_pagfer'))."','$this->r52_pagfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_pagcom"]) || $this->r52_pagcom != "")
             $resac = db_query("insert into db_acount values($acount,570,4126,'".AddSlashes(pg_result($resaco,$conresaco,'r52_pagcom'))."','$this->r52_pagcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_valor"]) || $this->r52_valor != "")
             $resac = db_query("insert into db_acount values($acount,570,4127,'".AddSlashes(pg_result($resaco,$conresaco,'r52_valor'))."','$this->r52_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_valcom"]) || $this->r52_valcom != "")
             $resac = db_query("insert into db_acount values($acount,570,4128,'".AddSlashes(pg_result($resaco,$conresaco,'r52_valcom'))."','$this->r52_valcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_val13"]) || $this->r52_val13 != "")
             $resac = db_query("insert into db_acount values($acount,570,4129,'".AddSlashes(pg_result($resaco,$conresaco,'r52_val13'))."','$this->r52_val13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_limite"]) || $this->r52_limite != "")
             $resac = db_query("insert into db_acount values($acount,570,4602,'".AddSlashes(pg_result($resaco,$conresaco,'r52_limite'))."','$this->r52_limite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_dvagencia"]) || $this->r52_dvagencia != "")
             $resac = db_query("insert into db_acount values($acount,570,7777,'".AddSlashes(pg_result($resaco,$conresaco,'r52_dvagencia'))."','$this->r52_dvagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_dvconta"]) || $this->r52_dvconta != "")
             $resac = db_query("insert into db_acount values($acount,570,7776,'".AddSlashes(pg_result($resaco,$conresaco,'r52_dvconta'))."','$this->r52_dvconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_valfer"]) || $this->r52_valfer != "")
             $resac = db_query("insert into db_acount values($acount,570,10034,'".AddSlashes(pg_result($resaco,$conresaco,'r52_valfer'))."','$this->r52_valfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_valres"]) || $this->r52_valres != "")
             $resac = db_query("insert into db_acount values($acount,570,11914,'".AddSlashes(pg_result($resaco,$conresaco,'r52_valres'))."','$this->r52_valres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_pagres"]) || $this->r52_pagres != "")
             $resac = db_query("insert into db_acount values($acount,570,11913,'".AddSlashes(pg_result($resaco,$conresaco,'r52_pagres'))."','$this->r52_pagres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_adiantamento13"]) || $this->r52_adiantamento13 != "")
             $resac = db_query("insert into db_acount values($acount,570,17334,'".AddSlashes(pg_result($resaco,$conresaco,'r52_adiantamento13'))."','$this->r52_adiantamento13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_percadiantamento13"]) || $this->r52_percadiantamento13 != "")
             $resac = db_query("insert into db_acount values($acount,570,17335,'".AddSlashes(pg_result($resaco,$conresaco,'r52_percadiantamento13'))."','$this->r52_percadiantamento13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_sequencial"]) || $this->r52_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,570,20827,'".AddSlashes(pg_result($resaco,$conresaco,'r52_sequencial'))."','$this->r52_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_pagasuplementar"]) || $this->r52_pagasuplementar != "")
             $resac = db_query("insert into db_acount values($acount,570,20828,'".AddSlashes(pg_result($resaco,$conresaco,'r52_pagasuplementar'))."','$this->r52_pagasuplementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_valorsuplementar"]) || $this->r52_valorsuplementar != "")
             $resac = db_query("insert into db_acount values($acount,570,20829,'".AddSlashes(pg_result($resaco,$conresaco,'r52_valorsuplementar'))."','$this->r52_valorsuplementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r52_relacaodependencia"]) || $this->r52_relacaodependencia != "")
             $resac = db_query("insert into db_acount values($acount,570,22304,'".AddSlashes(pg_result($resaco,$conresaco,'r52_relacaodependencia'))."','$this->r52_relacaodependencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pensoes Alimenticias não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r52_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Pensoes Alimenticias não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->r52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
  function excluir($r52_anousu = null, $r52_mesusu = null, $r52_regist = null, $r52_numcgm = null, $dbwhere = null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($r52_anousu, $r52_mesusu, $r52_regist, $r52_numcgm));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null, null, null, null, "*", null, $dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20827,".AddSlashes(pg_result($resaco,$iresaco,'r52_sequencial')).",'E')");
           $resac  = db_query("insert into db_acount values($acount,570,4113,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4114,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4115,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4116,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_formul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4117,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4118,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4119,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4120,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4121,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4122,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_vlrpen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4123,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_dtincl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4124,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_pag13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4125,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_pagfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4126,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_pagcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4127,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4128,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_valcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4129,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_val13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,4602,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,7777,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,7776,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,10034,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_valfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,11914,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_valres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,11913,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_pagres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,17334,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_adiantamento13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,17335,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_percadiantamento13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,20827,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,20828,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_pagasuplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,20829,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_valorsuplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,570,22304,'','".AddSlashes(pg_result($resaco,$iresaco,'r52_relacaodependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pensao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
         if ($r52_anousu != "") {
          if ($sql2 != "") {
            $sql2 .= " and ";
          }
          $sql2 .= " r52_anousu = $r52_anousu ";
        }
        if ($r52_mesusu != "") {
          if ($sql2 != "") {
            $sql2 .= " and ";
          }
          $sql2 .= " r52_mesusu = $r52_mesusu ";
        }
        if ($r52_regist != "") {
          if ($sql2 != "") {
            $sql2 .= " and ";
          }
          $sql2 .= " r52_regist = $r52_regist ";
        }
        if ($r52_numcgm != "") {
          if ($sql2 != "") {
            $sql2 .= " and ";
          }
          $sql2 .= " r52_numcgm = $r52_numcgm ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pensoes Alimenticias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->r52_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Pensoes Alimenticias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->r52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r52_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pensao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
  function atualiza_incluir() {

    $this->incluir($this->r52_anousu, $this->r52_mesusu, $this->r52_regist, $this->r52_numcgm);
  }
  function sql_query($r52_anousu = null, $r52_mesusu = null, $r52_regist = null, $r52_numcgm = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pensao ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = pensao.r52_numcgm";
    $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pensao.r52_anousu and  pessoal.r01_mesusu = pensao.r52_mesusu and  pessoal.r01_regist = pensao.r52_regist";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
    $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
    $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
    $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
    $sql .= "      inner join db_config  as a on   a.codigo = pessoal.r01_instit";
    $sql .= "      inner join funcao  as b on   b.r37_anousu = pessoal.r01_anousu and   b.r37_mesusu = pessoal.r01_mesusu and   b.r37_funcao = pessoal.r01_funcao";
    $sql .= "      inner join lotacao  as c on   c.r13_anousu = pessoal.r01_anousu and   c.r13_mesusu = pessoal.r01_mesusu and   c.r13_codigo = pessoal.r01_lotac";
    $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
    $sql .= "      inner join db_config  as d on   d.codigo = pessoal.r01_instit";
    $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu and   d.r37_mesusu = pessoal.r01_mesusu and   d.r37_funcao = pessoal.r01_funcao";
    $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
    $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($r52_anousu != null) {
        $sql2 .= " where pensao.r52_anousu = $r52_anousu ";
      }
      if ($r52_mesusu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_mesusu = $r52_mesusu ";
      }
      if ($r52_regist != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_regist = $r52_regist ";
      }
      if ($r52_numcgm != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_numcgm = $r52_numcgm ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_dados($r52_anousu = null, $r52_mesusu = null, $r52_regist = null, $r52_numcgm = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pensao ";
    $sql .= "      inner join cgm             on cgm.z01_numcgm                 = pensao.r52_numcgm     ";
    $sql .= "      inner join rhpessoal       on rhpessoal.rh01_regist          = pensao.r52_regist     ";
    $sql .= "      inner join cgm a           on a.z01_numcgm                   = rhpessoal.rh01_numcgm ";
    $sql .= "      left  join db_bancos       on db_bancos.db90_codban::char(3) = pensao.r52_codbco     ";
    $sql .= "      left  join pensaoretencao  on pensaoretencao.rh77_numcgm     = pensao.r52_numcgm     ";
    $sql .= "                                and pensaoretencao.rh77_regist     = pensao.r52_regist     ";
    $sql .= "                                and pensaoretencao.rh77_anousu     = pensao.r52_anousu     ";
    $sql .= "                                and pensaoretencao.rh77_mesusu     = pensao.r52_mesusu     ";
    $sql .= "      left  join retencaotiporec on retencaotiporec.e21_sequencial = pensaoretencao.rh77_retencaotiporec ";
    $sql .= "      inner join rhpessoalmov on rhpessoalmov.rh02_anousu = pensao.r52_anousu              ";
    $sql .= "                                and rhpessoalmov.rh02_mesusu = pensao.r52_mesusu           ";
    $sql .= "                                and rhpessoalmov.rh02_regist = pensao.r52_regist           ";
    $sql .= "      left  join pensaocontabancaria on pensaocontabancaria.rh139_anousu = pensao.r52_anousu ";
    $sql .= "                                    and pensaocontabancaria.rh139_mesusu = pensao.r52_mesusu ";
    $sql .= "                                    and pensaocontabancaria.rh139_regist = pensao.r52_regist ";
    $sql .= "                                    and pensaocontabancaria.rh139_numcgm = pensao.r52_numcgm";


    $sql2 = "";
    if ($dbwhere == "") {
      if ($r52_anousu != null) {
        $sql2 .= " where pensao.r52_anousu = $r52_anousu ";
      }
      if ($r52_mesusu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_mesusu = $r52_mesusu ";
      }
      if ($r52_regist != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_regist = $r52_regist ";
      }
      if ($r52_numcgm != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_numcgm = $r52_numcgm ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_file($r52_anousu = null, $r52_mesusu = null, $r52_regist = null, $r52_numcgm = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pensao ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($r52_anousu != null) {
        $sql2 .= " where pensao.r52_anousu = $r52_anousu ";
      }
      if ($r52_mesusu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_mesusu = $r52_mesusu ";
      }
      if ($r52_regist != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_regist = $r52_regist ";
      }
      if ($r52_numcgm != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_numcgm = $r52_numcgm ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_gerarqbag($r52_anousu = null, $r52_mesusu = null, $r52_regist = null, $r52_numcgm = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pensao                                                                                             \n";
    $sql .= "      inner join rhpessoalmov on rhpessoalmov.rh02_anousu  = pensao.r52_anousu                           \n";
    $sql .= "                             and rhpessoalmov.rh02_mesusu = pensao.r52_mesusu                            \n";
    $sql .= "                             and rhpessoalmov.rh02_regist = pensao.r52_regist                            \n";
    $sql .= "                             and rhpessoalmov.rh02_instit =  " . db_getsession("DB_instit") . "          \n";
    $sql .= "      inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist                    \n";
    $sql .= "      inner join cgm          on cgm.z01_numcgm            = pensao.r52_numcgm                           \n";
    $sql .= "      inner join cgm func     on func.z01_numcgm           = rhpessoal.rh01_numcgm                       \n";
    $sql .= "      inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota                      \n";
    $sql .= "                              and rhlota.r70_instit        = rhpessoalmov.rh02_instit                    \n";
    $sql .= "      inner join folha        on folha.r38_regist          = pensao.r52_regist                           \n";
    $sql .= "      left  join pensaocontabancaria on pensaocontabancaria.rh139_anousu = pensao.r52_anousu             \n";
    $sql .= "                                    and pensaocontabancaria.rh139_mesusu = pensao.r52_mesusu             \n";
    $sql .= "                                    and pensaocontabancaria.rh139_regist = pensao.r52_regist             \n";
    $sql .= "                                    and pensaocontabancaria.rh139_numcgm = pensao.r52_numcgm             \n";
    $sql .= "      left  join contabancaria on contabancaria.db83_sequencial = pensaocontabancaria.rh139_contabancaria\n";
    $sql .= "      left  join bancoagencia  on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia         \n";

    $sql2 = "";
    if ($dbwhere == "") {
      if ($r52_anousu != null) {
        $sql2 .= " where pensao.r52_anousu = $r52_anousu ";
      }
      if ($r52_mesusu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_mesusu = $r52_mesusu ";
      }
      if ($r52_regist != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_regist = $r52_regist ";
      }
      if ($r52_numcgm != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " pensao.r52_numcgm = $r52_numcgm ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula . $campos_sql [$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_pensao_rescisao($r52_anousu = null, $r52_mesusu = null, $r52_regist = null, $r52_numcgm = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql = "select {$sCampos}";

    $sSql .= " from pensao                                                                           ";
    $sSql .= "      inner join rhpessoalmov on pensao.r52_anousu = rhpessoalmov.rh02_anousu          ";
    $sSql .= "                             and pensao.r52_mesusu = rhpessoalmov.rh02_mesusu          ";
    $sSql .= "                             and pensao.r52_regist = rhpessoalmov.rh02_regist          ";
    $sSql .= "      left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes  ";

    $sSqlAuxiliar = "";

    if (empty($sWhere)) {

      if ($r52_anousu != null) {
        $sSqlAuxiliar .= " where pensao.r52_anousu = {$r52_anousu} ";
      }

      if ($r52_mesusu != null) {

        $sSqlAuxiliar .= (!empty($sSqlAuxiliar) ? " and " : " where ");
        $sSqlAuxiliar .= " pensao.r52_mesusu = {$r52_mesusu} ";
      }

      if ($r52_regist != null) {

        $sSqlAuxiliar .= (!empty($sSqlAuxiliar) ? " and " : " where ");
        $sSqlAuxiliar .= " pensao.r52_regist = {$r52_regist} ";
      }

      if ($r52_numcgm != null) {

        $sSqlAuxiliar .= (!empty($sSqlAuxiliar) ? " and " : " where ");
        $sSqlAuxiliar .= " pensao.r52_numcgm = {$r52_numcgm} ";
      }
    } else {
      $sSqlAuxiliar = " where {$sWhere}";
    }

    $sSql .= $sSqlAuxiliar;

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
  }

  function getSequencial( $r52_regist, $r52_numcgm, $r52_anousu = null, $r52_mesusu = null ) {

    if ( empty($r52_anousu) ) {
      $r52_anousu = DBPessoal::getAnoFolha();
    }

    if ( empty($r52_mesusu) ) {
      $r52_mesusu = DBPessoal::getMesFolha();
    }

    $rsSql = $this->sql_record(
      $this->sql_query_file( $r52_anousu, $r52_mesusu, $r52_regist, $r52_numcgm, "r52_sequencial")
    );

    if ( $this->erro_status == "0" ) {
      return null;
    }

    return db_utils::fieldsMemory($rsSql, 0)->r52_sequencial;
  }

}
