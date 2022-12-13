<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

class cl_empprestatip {
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
   var $e44_tipo = 0;
   var $e44_descr = null;
   var $e44_obriga = 0;
   var $e44_naturezaevento = 1;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e44_tipo = int4 = Evento
                 e44_descr = varchar(40) = Descrição do evento
                 e44_obriga = int4 = Obriga
                 e44_naturezaevento = int4 = Natureza do Evento
                 ";
   //funcao construtor da classe
   function cl_empprestatip() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empprestatip");
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
       $this->e44_tipo = ($this->e44_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e44_tipo"]:$this->e44_tipo);
       $this->e44_descr = ($this->e44_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["e44_descr"]:$this->e44_descr);
       $this->e44_obriga = ($this->e44_obriga == ""?@$GLOBALS["HTTP_POST_VARS"]["e44_obriga"]:$this->e44_obriga);
       $this->e44_naturezaevento = ($this->e44_naturezaevento == ""?@$GLOBALS["HTTP_POST_VARS"]["e44_naturezaevento"]:$this->e44_naturezaevento);
     }else{
       $this->e44_tipo = ($this->e44_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e44_tipo"]:$this->e44_tipo);
     }
   }
   // funcao para inclusao
   function incluir ($e44_tipo){
      $this->atualizacampos();
     if($this->e44_descr == null ){
       $this->erro_sql = " Campo Descrição do evento não informado.";
       $this->erro_campo = "e44_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e44_obriga == null ){
       $this->erro_sql = " Campo Obriga não informado.";
       $this->erro_campo = "e44_obriga";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e44_naturezaevento == null ){
       $this->erro_sql = " Campo Natureza do Evento não informado.";
       $this->erro_campo = "e44_naturezaevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e44_tipo == "" || $e44_tipo == null ){
       $result = db_query("select nextval('empprestatip_e44_tipo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empprestatip_e44_tipo_seq do campo: e44_tipo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e44_tipo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empprestatip_e44_tipo_seq");
       if(($result != false) && (pg_result($result,0,0) < $e44_tipo)){
         $this->erro_sql = " Campo e44_tipo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e44_tipo = $e44_tipo;
       }
     }
     if(($this->e44_tipo == null) || ($this->e44_tipo == "") ){
       $this->erro_sql = " Campo e44_tipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empprestatip(
                                       e44_tipo
                                      ,e44_descr
                                      ,e44_obriga
                                      ,e44_naturezaevento
                       )
                values (
                                $this->e44_tipo
                               ,'$this->e44_descr'
                               ,$this->e44_obriga
                               ,$this->e44_naturezaevento
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de prestação ($this->e44_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de prestação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de prestação ($this->e44_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e44_tipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e44_tipo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6355,'$this->e44_tipo','I')");
         $resac = db_query("insert into db_acount values($acount,1038,6355,'','".AddSlashes(pg_result($resaco,0,'e44_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1038,6356,'','".AddSlashes(pg_result($resaco,0,'e44_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1038,6357,'','".AddSlashes(pg_result($resaco,0,'e44_obriga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1038,20881,'','".AddSlashes(pg_result($resaco,0,'e44_naturezaevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($e44_tipo=null) {
      $this->atualizacampos();
     $sql = " update empprestatip set ";
     $virgula = "";
     if(trim($this->e44_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e44_tipo"])){
       $sql  .= $virgula." e44_tipo = $this->e44_tipo ";
       $virgula = ",";
       if(trim($this->e44_tipo) == null ){
         $this->erro_sql = " Campo Evento não informado.";
         $this->erro_campo = "e44_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e44_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e44_descr"])){
       $sql  .= $virgula." e44_descr = '$this->e44_descr' ";
       $virgula = ",";
       if(trim($this->e44_descr) == null ){
         $this->erro_sql = " Campo Descrição do evento não informado.";
         $this->erro_campo = "e44_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e44_obriga)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e44_obriga"])){
       $sql  .= $virgula." e44_obriga = $this->e44_obriga ";
       $virgula = ",";
       if(trim($this->e44_obriga) == null ){
         $this->erro_sql = " Campo Obriga não informado.";
         $this->erro_campo = "e44_obriga";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e44_naturezaevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e44_naturezaevento"])){
       $sql  .= $virgula." e44_naturezaevento = $this->e44_naturezaevento ";
       $virgula = ",";
       if(trim($this->e44_naturezaevento) == null ){
         $this->erro_sql = " Campo Natureza do Evento não informado.";
         $this->erro_campo = "e44_naturezaevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e44_tipo!=null){
       $sql .= " e44_tipo = $this->e44_tipo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e44_tipo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,6355,'$this->e44_tipo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e44_tipo"]) || $this->e44_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1038,6355,'".AddSlashes(pg_result($resaco,$conresaco,'e44_tipo'))."','$this->e44_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e44_descr"]) || $this->e44_descr != "")
             $resac = db_query("insert into db_acount values($acount,1038,6356,'".AddSlashes(pg_result($resaco,$conresaco,'e44_descr'))."','$this->e44_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e44_obriga"]) || $this->e44_obriga != "")
             $resac = db_query("insert into db_acount values($acount,1038,6357,'".AddSlashes(pg_result($resaco,$conresaco,'e44_obriga'))."','$this->e44_obriga',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e44_naturezaevento"]) || $this->e44_naturezaevento != "")
             $resac = db_query("insert into db_acount values($acount,1038,20881,'".AddSlashes(pg_result($resaco,$conresaco,'e44_naturezaevento'))."','$this->e44_naturezaevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de prestação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e44_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de prestação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e44_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e44_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($e44_tipo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e44_tipo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6355,'$e44_tipo','E')");
           $resac  = db_query("insert into db_acount values($acount,1038,6355,'','".AddSlashes(pg_result($resaco,$iresaco,'e44_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1038,6356,'','".AddSlashes(pg_result($resaco,$iresaco,'e44_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1038,6357,'','".AddSlashes(pg_result($resaco,$iresaco,'e44_obriga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1038,20881,'','".AddSlashes(pg_result($resaco,$iresaco,'e44_naturezaevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empprestatip
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e44_tipo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e44_tipo = $e44_tipo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de prestação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e44_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de prestação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e44_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e44_tipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:empprestatip";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($e44_tipo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from empprestatip ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e44_tipo)) {
         $sql2 .= " where empprestatip.e44_tipo = $e44_tipo ";
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
   public function sql_query_file ($e44_tipo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empprestatip ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e44_tipo)){
         $sql2 .= " where empprestatip.e44_tipo = $e44_tipo ";
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
