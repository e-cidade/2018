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
//CLASSE DA ENTIDADE issarquivoretencaoregistroissvar
class cl_issarquivoretencaoregistroissvar {
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
   var $q146_sequencial = 0;
   var $q146_issvar = 0;
   var $q146_issarquivoretencaoregistro = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q146_sequencial = int4 = Sequencial
                 q146_issvar = int4 = IssVar
                 q146_issarquivoretencaoregistro = int4 = C�digo Registro Retenc�o
                 ";
   //funcao construtor da classe
   function cl_issarquivoretencaoregistroissvar() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issarquivoretencaoregistroissvar");
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
       $this->q146_sequencial = ($this->q146_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q146_sequencial"]:$this->q146_sequencial);
       $this->q146_issvar = ($this->q146_issvar == ""?@$GLOBALS["HTTP_POST_VARS"]["q146_issvar"]:$this->q146_issvar);
       $this->q146_issarquivoretencaoregistro = ($this->q146_issarquivoretencaoregistro == ""?@$GLOBALS["HTTP_POST_VARS"]["q146_issarquivoretencaoregistro"]:$this->q146_issarquivoretencaoregistro);
     }else{
       $this->q146_sequencial = ($this->q146_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q146_sequencial"]:$this->q146_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($q146_sequencial){
      $this->atualizacampos();
     if($this->q146_issvar == null ){
       $this->erro_sql = " Campo IssVar n�o informado.";
       $this->erro_campo = "q146_issvar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q146_issarquivoretencaoregistro == null ){
       $this->erro_sql = " Campo C�digo Registro Retenc�o n�o informado.";
       $this->erro_campo = "q146_issarquivoretencaoregistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q146_sequencial == "" || $q146_sequencial == null ){
       $result = db_query("select nextval('issarquivoretencaoregistroissvar_q146_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issarquivoretencaoregistroissvar_q146_sequencial_seq do campo: q146_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->q146_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from issarquivoretencaoregistroissvar_q146_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q146_sequencial)){
         $this->erro_sql = " Campo q146_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q146_sequencial = $q146_sequencial;
       }
     }
     if(($this->q146_sequencial == null) || ($this->q146_sequencial == "") ){
       $this->erro_sql = " Campo q146_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issarquivoretencaoregistroissvar(
                                       q146_sequencial
                                      ,q146_issvar
                                      ,q146_issarquivoretencaoregistro
                       )
                values (
                                $this->q146_sequencial
                               ,$this->q146_issvar
                               ,$this->q146_issarquivoretencaoregistro
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo de Reten��o ISSVar ($this->q146_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo de Reten��o ISSVar j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo de Reten��o ISSVar ($this->q146_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q146_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q146_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21137,'$this->q146_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3806,21137,'','".AddSlashes(pg_result($resaco,0,'q146_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3806,21138,'','".AddSlashes(pg_result($resaco,0,'q146_issvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3806,21139,'','".AddSlashes(pg_result($resaco,0,'q146_issarquivoretencaoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($q146_sequencial=null) {
      $this->atualizacampos();
     $sql = " update issarquivoretencaoregistroissvar set ";
     $virgula = "";
     if(trim($this->q146_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q146_sequencial"])){
       $sql  .= $virgula." q146_sequencial = $this->q146_sequencial ";
       $virgula = ",";
       if(trim($this->q146_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial n�o informado.";
         $this->erro_campo = "q146_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q146_issvar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q146_issvar"])){
       $sql  .= $virgula." q146_issvar = $this->q146_issvar ";
       $virgula = ",";
       if(trim($this->q146_issvar) == null ){
         $this->erro_sql = " Campo IssVar n�o informado.";
         $this->erro_campo = "q146_issvar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q146_issarquivoretencaoregistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q146_issarquivoretencaoregistro"])){
       $sql  .= $virgula." q146_issarquivoretencaoregistro = $this->q146_issarquivoretencaoregistro ";
       $virgula = ",";
       if(trim($this->q146_issarquivoretencaoregistro) == null ){
         $this->erro_sql = " Campo C�digo Registro Retenc�o n�o informado.";
         $this->erro_campo = "q146_issarquivoretencaoregistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q146_sequencial!=null){
       $sql .= " q146_sequencial = $this->q146_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q146_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21137,'$this->q146_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q146_sequencial"]) || $this->q146_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3806,21137,'".AddSlashes(pg_result($resaco,$conresaco,'q146_sequencial'))."','$this->q146_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q146_issvar"]) || $this->q146_issvar != "")
             $resac = db_query("insert into db_acount values($acount,3806,21138,'".AddSlashes(pg_result($resaco,$conresaco,'q146_issvar'))."','$this->q146_issvar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q146_issarquivoretencaoregistro"]) || $this->q146_issarquivoretencaoregistro != "")
             $resac = db_query("insert into db_acount values($acount,3806,21139,'".AddSlashes(pg_result($resaco,$conresaco,'q146_issarquivoretencaoregistro'))."','$this->q146_issarquivoretencaoregistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Reten��o ISSVar n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q146_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Reten��o ISSVar n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q146_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q146_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($q146_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q146_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21137,'$q146_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3806,21137,'','".AddSlashes(pg_result($resaco,$iresaco,'q146_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3806,21138,'','".AddSlashes(pg_result($resaco,$iresaco,'q146_issvar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3806,21139,'','".AddSlashes(pg_result($resaco,$iresaco,'q146_issarquivoretencaoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issarquivoretencaoregistroissvar
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q146_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q146_sequencial = $q146_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Reten��o ISSVar n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q146_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Reten��o ISSVar n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q146_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q146_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issarquivoretencaoregistroissvar";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($q146_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from issarquivoretencaoregistroissvar ";
     $sql .= "      inner join issvar  on  issvar.q05_codigo = issarquivoretencaoregistroissvar.q146_issvar";
     $sql .= "      inner join issarquivoretencaoregistro  on  issarquivoretencaoregistro.q91_sequencial = issarquivoretencaoregistroissvar.q146_issarquivoretencaoregistro";
     $sql .= "      inner join issarquivoretencao  as a on   a.q90_sequencial = issarquivoretencaoregistro.q91_issarquivoretencao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q146_sequencial)) {
         $sql2 .= " where issarquivoretencaoregistroissvar.q146_sequencial = $q146_sequencial ";
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
   public function sql_query_file ($q146_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from issarquivoretencaoregistroissvar ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q146_sequencial)){
         $sql2 .= " where issarquivoretencaoregistroissvar.q146_sequencial = $q146_sequencial ";
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
