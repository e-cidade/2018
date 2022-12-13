<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: juridico
//CLASSE DA ENTIDADE cartorio
class cl_cartorio {
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
   var $v82_sequencial = 0;
   var $v82_descricao = null;
   var $v82_numcgm = 0;
   var $v82_obs = null;
   var $v82_extrajudicial = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v82_sequencial = int4 = Sequencial
                 v82_descricao = varchar(50) = Descrição
                 v82_numcgm = int4 = Cgm
                 v82_obs = text = Observação
                 v82_extrajudicial = bool = Extrajudicial
                 ";
   //funcao construtor da classe
   function cl_cartorio() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cartorio");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."?v82_extrajudicial=".@$GLOBALS["HTTP_POST_VARS"]["v82_extrajudicial"]."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->v82_sequencial = ($this->v82_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v82_sequencial"]:$this->v82_sequencial);
       $this->v82_descricao = ($this->v82_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["v82_descricao"]:$this->v82_descricao);
       $this->v82_numcgm = ($this->v82_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["v82_numcgm"]:$this->v82_numcgm);
       $this->v82_obs = ($this->v82_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["v82_obs"]:$this->v82_obs);
       $this->v82_extrajudicial = ($this->v82_extrajudicial == "f"?@$GLOBALS["HTTP_POST_VARS"]["v82_extrajudicial"]:$this->v82_extrajudicial);
     }else{
       $this->v82_sequencial = ($this->v82_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v82_sequencial"]:$this->v82_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($v82_sequencial){
      $this->atualizacampos();
     if($this->v82_descricao == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "v82_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v82_numcgm == null ){
       $this->erro_sql = " Campo Cgm não informado.";
       $this->erro_campo = "v82_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v82_extrajudicial == null ){
       $this->v82_extrajudicial = "f";
     }
     if($v82_sequencial == "" || $v82_sequencial == null ){
       $result = db_query("select nextval('cartorio_v82_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cartorio_v82_sequencial_seq do campo: v82_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v82_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from cartorio_v82_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v82_sequencial)){
         $this->erro_sql = " Campo v82_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v82_sequencial = $v82_sequencial;
       }
     }
     if(($this->v82_sequencial == null) || ($this->v82_sequencial == "") ){
       $this->erro_sql = " Campo v82_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cartorio(
                                       v82_sequencial
                                      ,v82_descricao
                                      ,v82_numcgm
                                      ,v82_obs
                                      ,v82_extrajudicial
                       )
                values (
                                $this->v82_sequencial
                               ,'$this->v82_descricao'
                               ,$this->v82_numcgm
                               ,'$this->v82_obs'
                               ,'$this->v82_extrajudicial'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cartorio ($this->v82_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cartorio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cartorio ($this->v82_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v82_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v82_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18151,'$this->v82_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3207,18151,'','".AddSlashes(pg_result($resaco,0,'v82_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3207,18153,'','".AddSlashes(pg_result($resaco,0,'v82_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3207,18152,'','".AddSlashes(pg_result($resaco,0,'v82_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3207,18154,'','".AddSlashes(pg_result($resaco,0,'v82_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3207,21216,'','".AddSlashes(pg_result($resaco,0,'v82_extrajudicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($v82_sequencial=null) {
      $this->atualizacampos();
     $sql = " update cartorio set ";
     $virgula = "";
     if(trim($this->v82_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v82_sequencial"])){
       $sql  .= $virgula." v82_sequencial = $this->v82_sequencial ";
       $virgula = ",";
       if(trim($this->v82_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "v82_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v82_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v82_descricao"])){
       $sql  .= $virgula." v82_descricao = '$this->v82_descricao' ";
       $virgula = ",";
       if(trim($this->v82_descricao) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "v82_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v82_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v82_numcgm"])){
       $sql  .= $virgula." v82_numcgm = $this->v82_numcgm ";
       $virgula = ",";
       if(trim($this->v82_numcgm) == null ){
         $this->erro_sql = " Campo Cgm não informado.";
         $this->erro_campo = "v82_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v82_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v82_obs"])){
       $sql  .= $virgula." v82_obs = '$this->v82_obs' ";
       $virgula = ",";
     }
     if(trim($this->v82_extrajudicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v82_extrajudicial"])){
       $sql  .= $virgula." v82_extrajudicial = '$this->v82_extrajudicial' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($v82_sequencial!=null){
       $sql .= " v82_sequencial = $this->v82_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v82_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18151,'$this->v82_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v82_sequencial"]) || $this->v82_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3207,18151,'".AddSlashes(pg_result($resaco,$conresaco,'v82_sequencial'))."','$this->v82_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v82_descricao"]) || $this->v82_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3207,18153,'".AddSlashes(pg_result($resaco,$conresaco,'v82_descricao'))."','$this->v82_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v82_numcgm"]) || $this->v82_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,3207,18152,'".AddSlashes(pg_result($resaco,$conresaco,'v82_numcgm'))."','$this->v82_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v82_obs"]) || $this->v82_obs != "")
             $resac = db_query("insert into db_acount values($acount,3207,18154,'".AddSlashes(pg_result($resaco,$conresaco,'v82_obs'))."','$this->v82_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v82_extrajudicial"]) || $this->v82_extrajudicial != "")
             $resac = db_query("insert into db_acount values($acount,3207,21216,'".AddSlashes(pg_result($resaco,$conresaco,'v82_extrajudicial'))."','$this->v82_extrajudicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cartorio não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v82_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cartorio não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($v82_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v82_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18151,'$v82_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3207,18151,'','".AddSlashes(pg_result($resaco,$iresaco,'v82_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3207,18153,'','".AddSlashes(pg_result($resaco,$iresaco,'v82_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3207,18152,'','".AddSlashes(pg_result($resaco,$iresaco,'v82_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3207,18154,'','".AddSlashes(pg_result($resaco,$iresaco,'v82_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3207,21216,'','".AddSlashes(pg_result($resaco,$iresaco,'v82_extrajudicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cartorio
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v82_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v82_sequencial = $v82_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cartorio não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v82_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cartorio não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v82_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cartorio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($v82_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from cartorio ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cartorio.v82_numcgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v82_sequencial)) {
         $sql2 .= " where cartorio.v82_sequencial = $v82_sequencial ";
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
   public function sql_query_file ($v82_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cartorio ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v82_sequencial)){
         $sql2 .= " where cartorio.v82_sequencial = $v82_sequencial ";
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
