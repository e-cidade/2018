<?php
/**
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

//MODULO: issqn
//CLASSE DA ENTIDADE issarquivoretencaodisarq
class cl_issarquivoretencaodisarq {
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
   var $q145_sequencial = 0;
   var $q145_issarquivoretencao = 0;
   var $q145_disarq = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q145_sequencial = int4 = Sequencial
                 q145_issarquivoretencao = int4 = C�digo Arquivo de Reten��o
                 q145_disarq = int4 = Disarq
                 ";
   //funcao construtor da classe
   function cl_issarquivoretencaodisarq() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issarquivoretencaodisarq");
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
       $this->q145_sequencial = ($this->q145_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q145_sequencial"]:$this->q145_sequencial);
       $this->q145_issarquivoretencao = ($this->q145_issarquivoretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["q145_issarquivoretencao"]:$this->q145_issarquivoretencao);
       $this->q145_disarq = ($this->q145_disarq == ""?@$GLOBALS["HTTP_POST_VARS"]["q145_disarq"]:$this->q145_disarq);
     }else{
       $this->q145_sequencial = ($this->q145_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q145_sequencial"]:$this->q145_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($q145_sequencial){
      $this->atualizacampos();
     if($this->q145_issarquivoretencao == null ){
       $this->erro_sql = " Campo C�digo Arquivo de Reten��o n�o informado.";
       $this->erro_campo = "q145_issarquivoretencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q145_disarq == null ){
       $this->erro_sql = " Campo Disarq n�o informado.";
       $this->erro_campo = "q145_disarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q145_sequencial == "" || $q145_sequencial == null ){
       $result = db_query("select nextval('issarquivoretencaodisarq_q145_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issarquivoretencaodisarq_q145_sequencial_seq do campo: q145_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->q145_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from issarquivoretencaodisarq_q145_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q145_sequencial)){
         $this->erro_sql = " Campo q145_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q145_sequencial = $q145_sequencial;
       }
     }
     if(($this->q145_sequencial == null) || ($this->q145_sequencial == "") ){
       $this->erro_sql = " Campo q145_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issarquivoretencaodisarq(
                                       q145_sequencial
                                      ,q145_issarquivoretencao
                                      ,q145_disarq
                       )
                values (
                                $this->q145_sequencial
                               ,$this->q145_issarquivoretencao
                               ,$this->q145_disarq
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo de Reten��o DisArq ($this->q145_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo de Reten��o DisArq j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo de Reten��o DisArq ($this->q145_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q145_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q145_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21133,'$this->q145_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3805,21133,'','".AddSlashes(pg_result($resaco,0,'q145_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3805,21134,'','".AddSlashes(pg_result($resaco,0,'q145_issarquivoretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3805,21135,'','".AddSlashes(pg_result($resaco,0,'q145_disarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($q145_sequencial=null) {
      $this->atualizacampos();
     $sql = " update issarquivoretencaodisarq set ";
     $virgula = "";
     if(trim($this->q145_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q145_sequencial"])){
       $sql  .= $virgula." q145_sequencial = $this->q145_sequencial ";
       $virgula = ",";
       if(trim($this->q145_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial n�o informado.";
         $this->erro_campo = "q145_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q145_issarquivoretencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q145_issarquivoretencao"])){
       $sql  .= $virgula." q145_issarquivoretencao = $this->q145_issarquivoretencao ";
       $virgula = ",";
       if(trim($this->q145_issarquivoretencao) == null ){
         $this->erro_sql = " Campo C�digo Arquivo de Reten��o n�o informado.";
         $this->erro_campo = "q145_issarquivoretencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q145_disarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q145_disarq"])){
       $sql  .= $virgula." q145_disarq = $this->q145_disarq ";
       $virgula = ",";
       if(trim($this->q145_disarq) == null ){
         $this->erro_sql = " Campo Disarq n�o informado.";
         $this->erro_campo = "q145_disarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q145_sequencial!=null){
       $sql .= " q145_sequencial = $this->q145_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q145_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21133,'$this->q145_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q145_sequencial"]) || $this->q145_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3805,21133,'".AddSlashes(pg_result($resaco,$conresaco,'q145_sequencial'))."','$this->q145_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q145_issarquivoretencao"]) || $this->q145_issarquivoretencao != "")
             $resac = db_query("insert into db_acount values($acount,3805,21134,'".AddSlashes(pg_result($resaco,$conresaco,'q145_issarquivoretencao'))."','$this->q145_issarquivoretencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q145_disarq"]) || $this->q145_disarq != "")
             $resac = db_query("insert into db_acount values($acount,3805,21135,'".AddSlashes(pg_result($resaco,$conresaco,'q145_disarq'))."','$this->q145_disarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Reten��o DisArq n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q145_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Reten��o DisArq n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q145_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q145_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($q145_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q145_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21133,'$q145_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3805,21133,'','".AddSlashes(pg_result($resaco,$iresaco,'q145_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3805,21134,'','".AddSlashes(pg_result($resaco,$iresaco,'q145_issarquivoretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3805,21135,'','".AddSlashes(pg_result($resaco,$iresaco,'q145_disarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issarquivoretencaodisarq
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q145_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q145_sequencial = $q145_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Reten��o DisArq n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q145_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Reten��o DisArq n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q145_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q145_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:issarquivoretencaodisarq";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($q145_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from issarquivoretencaodisarq ";
     $sql .= "      inner join disarq  on  disarq.codret = issarquivoretencaodisarq.q145_disarq";
     $sql .= "      inner join issarquivoretencao  on  issarquivoretencao.q90_sequencial = issarquivoretencaodisarq.q145_issarquivoretencao";
     $sql .= "      inner join db_config  on  db_config.codigo = disarq.instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = disarq.id_usuario";
     $sql .= "      inner join saltes  on  saltes.k13_conta = disarq.k00_conta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q145_sequencial)) {
         $sql2 .= " where issarquivoretencaodisarq.q145_sequencial = $q145_sequencial ";
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
   public function sql_query_file ($q145_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from issarquivoretencaodisarq ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q145_sequencial)){
         $sql2 .= " where issarquivoretencaodisarq.q145_sequencial = $q145_sequencial ";
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
