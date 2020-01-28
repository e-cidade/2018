<?php
//MODULO: esocial
//CLASSE DA ENTIDADE avaliacaogruporespostacgm
class cl_avaliacaogruporespostacgm {
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
   var $eso03_sequencial = 0;
   var $eso03_avaliacaogruporesposta = 0;
   var $eso03_cgm = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 eso03_sequencial = int4 = Código
                 eso03_avaliacaogruporesposta = int4 = Grupo Resposta
                 eso03_cgm = int4 = CGM
                 ";
   //funcao construtor da classe
   function cl_avaliacaogruporespostacgm() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaogruporespostacgm");
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
       $this->eso03_sequencial = ($this->eso03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso03_sequencial"]:$this->eso03_sequencial);
       $this->eso03_avaliacaogruporesposta = ($this->eso03_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["eso03_avaliacaogruporesposta"]:$this->eso03_avaliacaogruporesposta);
       $this->eso03_cgm = ($this->eso03_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["eso03_cgm"]:$this->eso03_cgm);
     }else{
       $this->eso03_sequencial = ($this->eso03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso03_sequencial"]:$this->eso03_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($eso03_sequencial){
      $this->atualizacampos();
     if($this->eso03_avaliacaogruporesposta == null ){
       $this->erro_sql = " Campo Grupo Resposta não informado.";
       $this->erro_campo = "eso03_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso03_cgm == null ){
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "eso03_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso03_sequencial == "" || $eso03_sequencial == null ){
       $result = db_query("select nextval('avaliacaogruporespostacgm_eso03_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogruporespostacgm_eso03_sequencial_seq do campo: eso03_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->eso03_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from avaliacaogruporespostacgm_eso03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso03_sequencial)){
         $this->erro_sql = " Campo eso03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso03_sequencial = $eso03_sequencial;
       }
     }
     if(($this->eso03_sequencial == null) || ($this->eso03_sequencial == "") ){
       $this->erro_sql = " Campo eso03_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogruporespostacgm(
                                       eso03_sequencial
                                      ,eso03_avaliacaogruporesposta
                                      ,eso03_cgm
                       )
                values (
                                $this->eso03_sequencial
                               ,$this->eso03_avaliacaogruporesposta
                               ,$this->eso03_cgm
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo entre eSocial e cgm ($this->eso03_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo entre eSocial e cgm já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo entre eSocial e cgm ($this->eso03_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->eso03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso03_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21904,'$this->eso03_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3943,21904,'','".AddSlashes(pg_result($resaco,0,'eso03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3943,21905,'','".AddSlashes(pg_result($resaco,0,'eso03_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3943,21906,'','".AddSlashes(pg_result($resaco,0,'eso03_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($eso03_sequencial=null) {
      $this->atualizacampos();
     $sql = " update avaliacaogruporespostacgm set ";
     $virgula = "";
     if(trim($this->eso03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso03_sequencial"])){
       $sql  .= $virgula." eso03_sequencial = $this->eso03_sequencial ";
       $virgula = ",";
       if(trim($this->eso03_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "eso03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso03_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso03_avaliacaogruporesposta"])){
       $sql  .= $virgula." eso03_avaliacaogruporesposta = $this->eso03_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->eso03_avaliacaogruporesposta) == null ){
         $this->erro_sql = " Campo Grupo Resposta não informado.";
         $this->erro_campo = "eso03_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso03_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso03_cgm"])){
       $sql  .= $virgula." eso03_cgm = $this->eso03_cgm ";
       $virgula = ",";
       if(trim($this->eso03_cgm) == null ){
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "eso03_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($eso03_sequencial!=null){
       $sql .= " eso03_sequencial = $this->eso03_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso03_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21904,'$this->eso03_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso03_sequencial"]) || $this->eso03_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3943,21904,'".AddSlashes(pg_result($resaco,$conresaco,'eso03_sequencial'))."','$this->eso03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso03_avaliacaogruporesposta"]) || $this->eso03_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,3943,21905,'".AddSlashes(pg_result($resaco,$conresaco,'eso03_avaliacaogruporesposta'))."','$this->eso03_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso03_cgm"]) || $this->eso03_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3943,21906,'".AddSlashes(pg_result($resaco,$conresaco,'eso03_cgm'))."','$this->eso03_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo entre eSocial e cgm não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo entre eSocial e cgm não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->eso03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($eso03_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso03_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21904,'$eso03_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3943,21904,'','".AddSlashes(pg_result($resaco,$iresaco,'eso03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3943,21905,'','".AddSlashes(pg_result($resaco,$iresaco,'eso03_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3943,21906,'','".AddSlashes(pg_result($resaco,$iresaco,'eso03_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogruporespostacgm
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso03_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso03_sequencial = $eso03_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo entre eSocial e cgm não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo entre eSocial e cgm não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$eso03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporespostacgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($eso03_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostacgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostacgm.eso03_cgm";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostacgm.eso03_avaliacaogruporesposta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso03_sequencial)) {
         $sql2 .= " where avaliacaogruporespostacgm.eso03_sequencial = $eso03_sequencial ";
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
   public function sql_query_file ($eso03_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaogruporespostacgm ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso03_sequencial)){
         $sql2 .= " where avaliacaogruporespostacgm.eso03_sequencial = $eso03_sequencial ";
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

  public function buscaRespostasPorPergunta($iCodigoPergunta = null, $iCgm = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from avaliacaogruporespostacgm ";
    $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso03_avaliacaogruporesposta";
    $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
    $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
    $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
    $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";
    $sql2 = "";
    if (empty($dbwhere)) {

      $sql2 .=" where ";
      $aWhere = array();

      if (!empty($iCodigoPergunta)) {
        $aWhere[] = " db103_sequencial = {$iCodigoPergunta} ";
      }
      if(!empty($iCgm)){
        $aWhere[] = "eso03_cgm = {$iCgm}";
        // $aWhere[] = "eso03_avaliacaogruporesposta = (select max(eso03_avaliacaogruporesposta) from avaliacaogruporespostacgm where eso03_cgm = {$iCgm})";
        $aWhere[] = "eso03_avaliacaogruporesposta = (select max(eso03_avaliacaogruporesposta)
                        from avaliacaogruporespostacgm
                          inner join avaliacaogruporesposta on db107_sequencial = eso03_avaliacaogruporesposta
                          inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                          inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta
                          inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao
                          inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta
                      where db103_sequencial = {$iCodigoPergunta} and eso03_cgm = {$iCgm})";
      }
      $sql2 .= implode("and ", $aWhere);

    } else if (!empty($dbwhere)) {
      $sql2 = " where {$dbwhere}";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

    public function sql_avaliacao_preenchida( $eso03_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "" )
    {
        $sql  = "select {$campos} ";
        $sql .= "  from avaliacaogruporespostacgm ";
        $sql .= "  join avaliacaogruporesposta on db107_sequencial = eso03_avaliacaogruporesposta ";
        $sql .= "  join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  join avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso03_sequencial)){
                $sql2 .= " where avaliacaogruporespostacgm.eso03_sequencial = {$eso03_sequencial} ";
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
