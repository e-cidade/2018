<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhdirfgeracaopessoalpensionista
class cl_rhdirfgeracaopessoalpensionista { 
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
   var $rh202_sequencial = 0; 
   var $rh202_numcgm = 0; 
   var $rh202_rhdirfgeracaopessoal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh202_sequencial = int4 = Código 
                 rh202_numcgm = int4 = Cgm 
                 rh202_rhdirfgeracaopessoal = int4 = Geração dados pessoal 
                 ";
   //funcao construtor da classe 
   function cl_rhdirfgeracaopessoalpensionista() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdirfgeracaopessoalpensionista"); 
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
       $this->rh202_sequencial = ($this->rh202_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh202_sequencial"]:$this->rh202_sequencial);
       $this->rh202_numcgm = ($this->rh202_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh202_numcgm"]:$this->rh202_numcgm);
       $this->rh202_rhdirfgeracaopessoal = ($this->rh202_rhdirfgeracaopessoal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh202_rhdirfgeracaopessoal"]:$this->rh202_rhdirfgeracaopessoal);
     }else{
       $this->rh202_sequencial = ($this->rh202_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh202_sequencial"]:$this->rh202_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh202_sequencial){ 
      $this->atualizacampos();
     if($this->rh202_numcgm == null ){ 
       $this->erro_sql = " Campo Cgm não informado.";
       $this->erro_campo = "rh202_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh202_rhdirfgeracaopessoal == null ){ 
       $this->erro_sql = " Campo Geração dados pessoal não informado.";
       $this->erro_campo = "rh202_rhdirfgeracaopessoal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh202_sequencial == "" || $rh202_sequencial == null ){
       $result = db_query("select nextval('rhdirfgeracaopessoalpensionista_rh202_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdirfgeracaopessoalpensionista_rh202_sequencial_seq do campo: rh202_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh202_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdirfgeracaopessoalpensionista_rh202_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh202_sequencial)){
         $this->erro_sql = " Campo rh202_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh202_sequencial = $rh202_sequencial; 
       }
     }
     if(($this->rh202_sequencial == null) || ($this->rh202_sequencial == "") ){ 
       $this->erro_sql = " Campo rh202_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdirfgeracaopessoalpensionista(
                                       rh202_sequencial 
                                      ,rh202_numcgm 
                                      ,rh202_rhdirfgeracaopessoal 
                       )
                values (
                                $this->rh202_sequencial 
                               ,$this->rh202_numcgm 
                               ,$this->rh202_rhdirfgeracaopessoal 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valor do Pensionista na Dirf ($this->rh202_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valor do Pensionista na Dirf já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valor do Pensionista na Dirf ($this->rh202_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh202_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh202_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22311,'$this->rh202_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,4019,22311,'','".AddSlashes(pg_result($resaco,0,'rh202_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4019,22313,'','".AddSlashes(pg_result($resaco,0,'rh202_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4019,22315,'','".AddSlashes(pg_result($resaco,0,'rh202_rhdirfgeracaopessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh202_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdirfgeracaopessoalpensionista set ";
     $virgula = "";
     if(trim($this->rh202_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh202_sequencial"])){ 
       $sql  .= $virgula." rh202_sequencial = $this->rh202_sequencial ";
       $virgula = ",";
       if(trim($this->rh202_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh202_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh202_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh202_numcgm"])){ 
       $sql  .= $virgula." rh202_numcgm = $this->rh202_numcgm ";
       $virgula = ",";
       if(trim($this->rh202_numcgm) == null ){ 
         $this->erro_sql = " Campo Cgm não informado.";
         $this->erro_campo = "rh202_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh202_rhdirfgeracaopessoal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh202_rhdirfgeracaopessoal"])){ 
       $sql  .= $virgula." rh202_rhdirfgeracaopessoal = $this->rh202_rhdirfgeracaopessoal ";
       $virgula = ",";
       if(trim($this->rh202_rhdirfgeracaopessoal) == null ){ 
         $this->erro_sql = " Campo Geração dados pessoal não informado.";
         $this->erro_campo = "rh202_rhdirfgeracaopessoal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh202_sequencial!=null){
       $sql .= " rh202_sequencial = $this->rh202_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh202_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22311,'$this->rh202_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh202_sequencial"]) || $this->rh202_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,4019,22311,'".AddSlashes(pg_result($resaco,$conresaco,'rh202_sequencial'))."','$this->rh202_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh202_numcgm"]) || $this->rh202_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,4019,22313,'".AddSlashes(pg_result($resaco,$conresaco,'rh202_numcgm'))."','$this->rh202_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh202_rhdirfgeracaopessoal"]) || $this->rh202_rhdirfgeracaopessoal != "")
             $resac = db_query("insert into db_acount values($acount,4019,22315,'".AddSlashes(pg_result($resaco,$conresaco,'rh202_rhdirfgeracaopessoal'))."','$this->rh202_rhdirfgeracaopessoal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valor do Pensionista na Dirf não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh202_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valor do Pensionista na Dirf não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh202_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh202_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh202_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh202_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22311,'$rh202_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,4019,22311,'','".AddSlashes(pg_result($resaco,$iresaco,'rh202_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4019,22313,'','".AddSlashes(pg_result($resaco,$iresaco,'rh202_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4019,22315,'','".AddSlashes(pg_result($resaco,$iresaco,'rh202_rhdirfgeracaopessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhdirfgeracaopessoalpensionista
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh202_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh202_sequencial = $rh202_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valor do Pensionista na Dirf não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh202_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valor do Pensionista na Dirf não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh202_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh202_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdirfgeracaopessoalpensionista";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh202_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhdirfgeracaopessoalpensionista ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhdirfgeracaopessoalpensionista.rh202_numcgm";
     $sql .= "      inner join rhdirfgeracaodadospessoal  on  rhdirfgeracaodadospessoal.rh96_sequencial = rhdirfgeracaopessoalpensionista.rh202_rhdirfgeracaopessoal";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm";
     $sql .= "      inner join rhdirfgeracao  as a on   a.rh95_sequencial = rhdirfgeracaodadospessoal.rh96_rhdirfgeracao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh202_sequencial)) {
         $sql2 .= " where rhdirfgeracaopessoalpensionista.rh202_sequencial = $rh202_sequencial "; 
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
   public function sql_query_file ($rh202_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhdirfgeracaopessoalpensionista ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh202_sequencial)){
         $sql2 .= " where rhdirfgeracaopessoalpensionista.rh202_sequencial = $rh202_sequencial "; 
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

  public function sql_query_valor_pensao($sCampos = '*', $sWhere = null, $sGroup = null, $sOrder = 'null') {


    $sSql = "select {$sCampos} ";
    $sSql .= "from pensao ";
    $sSql .= "    inner join rhpessoal on rh01_regist                      = r52_regist    ";
    $sSql .= "    inner join rhdirfgeracaodadospessoal on rh01_numcgm      = rh96_numcgm ";
    $sSql .= "    inner join rhdirfgeracao             on  rh95_sequencial = rh96_rhdirfgeracao";
    $sSql .= "    left join rhhistoricopensao on rh145_pensao              = r52_sequencial ";
    $sSql .= "    left join rhfolhapagamento  on rh145_rhfolhapagamento    = rh141_sequencial ";
    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    if (!empty($sGroup)) {
      $sSql .= " group by {$sGroup} ";
    }
    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }

  public function sql_query_dados_pensionista($sCampos = '*', $sWhere = null, $sGroup = null, $sOrder = null) {


    $sSql  = "select {$sCampos} ";
    $sSql .= "  from rhdirfgeracaopessoalpensionista ";
    $sSql .= "       inner join rhdirfgeracaodadospessoal on rh96_sequencial = rh202_rhdirfgeracaopessoal";
    $sSql .= "       inner join rhdirfgeracao             on rh95_sequencial = rh96_rhdirfgeracao";
    $sSql .= "       inner join rhpessoal                 on rh96_numcgm     = rh01_numcgm";
    $sSql .= "       inner join pensao                    on r52_regist      = rh01_regist";
    $sSql .= "                                           and r52_numcgm      = rh202_numcgm";
    $sSql .= "                                           and r52_mesusu = fc_mesfolha(rh01_instit)";
    $sSql .= "                                           and r52_anousu = fc_anofolha(rh01_instit)";
    $sSql .= "       inner join protocolo.cgm             on z01_numcgm = rh202_numcgm";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    if (!empty($sGroup)) {
      $sSql .= " group by {$sGroup} ";
    }
    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }
}
