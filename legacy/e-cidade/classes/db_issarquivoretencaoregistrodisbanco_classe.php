<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
//CLASSE DA ENTIDADE issarquivoretencaoregistrodisbanco
class cl_issarquivoretencaoregistrodisbanco {
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
   var $q94_sequencial = 0;
   var $q94_issarquivoretencaoregistro = 0;
   var $q94_disbanco = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q94_sequencial = int4 = Sequencial
                 q94_issarquivoretencaoregistro = int4 = Código Registro Retencão
                 q94_disbanco = int4 = Código Disbanco
                 ";
   //funcao construtor da classe
   function cl_issarquivoretencaoregistrodisbanco() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issarquivoretencaoregistrodisbanco");
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
       $this->q94_sequencial = ($this->q94_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q94_sequencial"]:$this->q94_sequencial);
       $this->q94_issarquivoretencaoregistro = ($this->q94_issarquivoretencaoregistro == ""?@$GLOBALS["HTTP_POST_VARS"]["q94_issarquivoretencaoregistro"]:$this->q94_issarquivoretencaoregistro);
       $this->q94_disbanco = ($this->q94_disbanco == ""?@$GLOBALS["HTTP_POST_VARS"]["q94_disbanco"]:$this->q94_disbanco);
     }else{
       $this->q94_sequencial = ($this->q94_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q94_sequencial"]:$this->q94_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q94_sequencial){
      $this->atualizacampos();
     if($this->q94_issarquivoretencaoregistro == null ){
       $this->erro_sql = " Campo Código Registro Retencão não informado.";
       $this->erro_campo = "q94_issarquivoretencaoregistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q94_disbanco == null ){
       $this->erro_sql = " Campo Código Disbanco não informado.";
       $this->erro_campo = "q94_disbanco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q94_sequencial == "" || $q94_sequencial == null ){
       $result = db_query("select nextval('issarquivoretencaoregistrodisbanco_q94_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issarquivoretencaoregistrodisbanco_q94_sequencial_seq do campo: q94_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->q94_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from issarquivoretencaoregistrodisbanco_q94_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q94_sequencial)){
         $this->erro_sql = " Campo q94_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q94_sequencial = $q94_sequencial;
       }
     }
     if(($this->q94_sequencial == null) || ($this->q94_sequencial == "") ){
       $this->erro_sql = " Campo q94_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issarquivoretencaoregistrodisbanco(
                                       q94_sequencial
                                      ,q94_issarquivoretencaoregistro
                                      ,q94_disbanco
                       )
                values (
                                $this->q94_sequencial
                               ,$this->q94_issarquivoretencaoregistro
                               ,$this->q94_disbanco
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo de Retenção Disbanco ($this->q94_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo de Retenção Disbanco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo de Retenção Disbanco ($this->q94_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q94_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q94_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21108,'$this->q94_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3799,21108,'','".AddSlashes(pg_result($resaco,0,'q94_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3799,21109,'','".AddSlashes(pg_result($resaco,0,'q94_issarquivoretencaoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3799,21110,'','".AddSlashes(pg_result($resaco,0,'q94_disbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($q94_sequencial=null) {
      $this->atualizacampos();
     $sql = " update issarquivoretencaoregistrodisbanco set ";
     $virgula = "";
     if(trim($this->q94_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q94_sequencial"])){
       $sql  .= $virgula." q94_sequencial = $this->q94_sequencial ";
       $virgula = ",";
       if(trim($this->q94_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "q94_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q94_issarquivoretencaoregistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q94_issarquivoretencaoregistro"])){
       $sql  .= $virgula." q94_issarquivoretencaoregistro = $this->q94_issarquivoretencaoregistro ";
       $virgula = ",";
       if(trim($this->q94_issarquivoretencaoregistro) == null ){
         $this->erro_sql = " Campo Código Registro Retencão não informado.";
         $this->erro_campo = "q94_issarquivoretencaoregistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q94_disbanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q94_disbanco"])){
       $sql  .= $virgula." q94_disbanco = $this->q94_disbanco ";
       $virgula = ",";
       if(trim($this->q94_disbanco) == null ){
         $this->erro_sql = " Campo Código Disbanco não informado.";
         $this->erro_campo = "q94_disbanco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q94_sequencial!=null){
       $sql .= " q94_sequencial = $this->q94_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q94_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21108,'$this->q94_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q94_sequencial"]) || $this->q94_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3799,21108,'".AddSlashes(pg_result($resaco,$conresaco,'q94_sequencial'))."','$this->q94_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q94_issarquivoretencaoregistro"]) || $this->q94_issarquivoretencaoregistro != "")
             $resac = db_query("insert into db_acount values($acount,3799,21109,'".AddSlashes(pg_result($resaco,$conresaco,'q94_issarquivoretencaoregistro'))."','$this->q94_issarquivoretencaoregistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q94_disbanco"]) || $this->q94_disbanco != "")
             $resac = db_query("insert into db_acount values($acount,3799,21110,'".AddSlashes(pg_result($resaco,$conresaco,'q94_disbanco'))."','$this->q94_disbanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Retenção Disbanco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q94_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Retenção Disbanco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q94_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q94_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($q94_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q94_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21108,'$q94_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3799,21108,'','".AddSlashes(pg_result($resaco,$iresaco,'q94_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3799,21109,'','".AddSlashes(pg_result($resaco,$iresaco,'q94_issarquivoretencaoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3799,21110,'','".AddSlashes(pg_result($resaco,$iresaco,'q94_disbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issarquivoretencaoregistrodisbanco
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q94_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q94_sequencial = $q94_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Retenção Disbanco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q94_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Retenção Disbanco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q94_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q94_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issarquivoretencaoregistrodisbanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($q94_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from issarquivoretencaoregistrodisbanco ";
     $sql .= "      inner join disbanco  on  disbanco.idret = issarquivoretencaoregistrodisbanco.q94_disbanco";
     $sql .= "      inner join issarquivoretencaoregistro  on  issarquivoretencaoregistro.q91_sequencial = issarquivoretencaoregistrodisbanco.q94_issarquivoretencaoregistro";
     $sql .= "      inner join db_config  on  db_config.codigo = disbanco.instit";
     $sql .= "      inner join disarq  on  disarq.codret = disbanco.codret";
     $sql .= "      inner join issarquivoretencao  as a on   a.q90_sequencial = issarquivoretencaoregistro.q91_issarquivoretencao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q94_sequencial)) {
         $sql2 .= " where issarquivoretencaoregistrodisbanco.q94_sequencial = $q94_sequencial ";
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
   public function sql_query_file ($q94_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from issarquivoretencaoregistrodisbanco ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q94_sequencial)){
         $sql2 .= " where issarquivoretencaoregistrodisbanco.q94_sequencial = $q94_sequencial ";
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
