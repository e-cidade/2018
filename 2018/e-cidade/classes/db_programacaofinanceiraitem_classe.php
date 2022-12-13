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

//MODULO: caixa
//CLASSE DA ENTIDADE programacaofinanceiraitem
class cl_programacaofinanceiraitem {
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
   var $k175_sequencial = 0;
   var $k175_item = 0;
   var $k175_programaacaofinanceira = 0;
   var $k175_valortotal = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k175_sequencial = int4 = Código 
                 k175_item = int4 = Item 
                 k175_programaacaofinanceira = int4 = Programação Financeira 
                 k175_valortotal = float8 = Valor Total 
                 ";
   //funcao construtor da classe
   function cl_programacaofinanceiraitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("programacaofinanceiraitem");
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
       $this->k175_sequencial = ($this->k175_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k175_sequencial"]:$this->k175_sequencial);
       $this->k175_item = ($this->k175_item == ""?@$GLOBALS["HTTP_POST_VARS"]["k175_item"]:$this->k175_item);
       $this->k175_programaacaofinanceira = ($this->k175_programaacaofinanceira == ""?@$GLOBALS["HTTP_POST_VARS"]["k175_programaacaofinanceira"]:$this->k175_programaacaofinanceira);
       $this->k175_valortotal = ($this->k175_valortotal == ""?@$GLOBALS["HTTP_POST_VARS"]["k175_valortotal"]:$this->k175_valortotal);
     }else{
       $this->k175_sequencial = ($this->k175_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k175_sequencial"]:$this->k175_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($k175_sequencial){
      $this->atualizacampos();
     if($this->k175_item == null ){
       $this->erro_sql = " Campo Item não informado.";
       $this->erro_campo = "k175_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k175_programaacaofinanceira == null ){
       $this->erro_sql = " Campo Programação Financeira não informado.";
       $this->erro_campo = "k175_programaacaofinanceira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k175_valortotal == null ){
       $this->erro_sql = " Campo Valor Total não informado.";
       $this->erro_campo = "k175_valortotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k175_sequencial == "" || $k175_sequencial == null ){
       $result = db_query("select nextval('programacaofinanceiraitem_k175_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: programacaofinanceiraitem_k175_sequencial_seq do campo: k175_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k175_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from programacaofinanceiraitem_k175_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k175_sequencial)){
         $this->erro_sql = " Campo k175_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k175_sequencial = $k175_sequencial;
       }
     }
     if(($this->k175_sequencial == null) || ($this->k175_sequencial == "") ){
       $this->erro_sql = " Campo k175_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into programacaofinanceiraitem(
                                       k175_sequencial 
                                      ,k175_item 
                                      ,k175_programaacaofinanceira 
                                      ,k175_valortotal 
                       )
                values (
                                $this->k175_sequencial 
                               ,$this->k175_item 
                               ,$this->k175_programaacaofinanceira 
                               ,$this->k175_valortotal 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Programação Financeira Item ($this->k175_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Programação Financeira Item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Programação Financeira Item ($this->k175_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k175_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k175_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22412,'$this->k175_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,4034,22412,'','".AddSlashes(pg_result($resaco,0,'k175_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4034,22413,'','".AddSlashes(pg_result($resaco,0,'k175_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4034,22414,'','".AddSlashes(pg_result($resaco,0,'k175_programaacaofinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4034,22415,'','".AddSlashes(pg_result($resaco,0,'k175_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($k175_sequencial=null) {
      $this->atualizacampos();
     $sql = " update programacaofinanceiraitem set ";
     $virgula = "";
     if(trim($this->k175_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k175_sequencial"])){
       $sql  .= $virgula." k175_sequencial = $this->k175_sequencial ";
       $virgula = ",";
       if(trim($this->k175_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "k175_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k175_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k175_item"])){
       $sql  .= $virgula." k175_item = $this->k175_item ";
       $virgula = ",";
       if(trim($this->k175_item) == null ){
         $this->erro_sql = " Campo Item não informado.";
         $this->erro_campo = "k175_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k175_programaacaofinanceira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k175_programaacaofinanceira"])){
       $sql  .= $virgula." k175_programaacaofinanceira = $this->k175_programaacaofinanceira ";
       $virgula = ",";
       if(trim($this->k175_programaacaofinanceira) == null ){
         $this->erro_sql = " Campo Programação Financeira não informado.";
         $this->erro_campo = "k175_programaacaofinanceira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k175_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k175_valortotal"])){
       $sql  .= $virgula." k175_valortotal = $this->k175_valortotal ";
       $virgula = ",";
       if(trim($this->k175_valortotal) == null ){
         $this->erro_sql = " Campo Valor Total não informado.";
         $this->erro_campo = "k175_valortotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k175_sequencial!=null){
       $sql .= " k175_sequencial = $this->k175_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k175_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22412,'$this->k175_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k175_sequencial"]) || $this->k175_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,4034,22412,'".AddSlashes(pg_result($resaco,$conresaco,'k175_sequencial'))."','$this->k175_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k175_item"]) || $this->k175_item != "")
             $resac = db_query("insert into db_acount values($acount,4034,22413,'".AddSlashes(pg_result($resaco,$conresaco,'k175_item'))."','$this->k175_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k175_programaacaofinanceira"]) || $this->k175_programaacaofinanceira != "")
             $resac = db_query("insert into db_acount values($acount,4034,22414,'".AddSlashes(pg_result($resaco,$conresaco,'k175_programaacaofinanceira'))."','$this->k175_programaacaofinanceira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k175_valortotal"]) || $this->k175_valortotal != "")
             $resac = db_query("insert into db_acount values($acount,4034,22415,'".AddSlashes(pg_result($resaco,$conresaco,'k175_valortotal'))."','$this->k175_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Financeira Item não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k175_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Programação Financeira Item não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k175_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k175_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($k175_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k175_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22412,'$k175_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,4034,22412,'','".AddSlashes(pg_result($resaco,$iresaco,'k175_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4034,22413,'','".AddSlashes(pg_result($resaco,$iresaco,'k175_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4034,22414,'','".AddSlashes(pg_result($resaco,$iresaco,'k175_programaacaofinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4034,22415,'','".AddSlashes(pg_result($resaco,$iresaco,'k175_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from programacaofinanceiraitem
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k175_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k175_sequencial = $k175_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Financeira Item não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k175_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Programação Financeira Item não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k175_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k175_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:programacaofinanceiraitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($k175_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from programacaofinanceiraitem ";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = programacaofinanceiraitem.k175_item";
     $sql .= "      inner join programacaofinanceira  on  programacaofinanceira.k117_sequencial = programacaofinanceiraitem.k175_programaacaofinanceira";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = programacaofinanceira.k117_id_usuario";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = programacaofinanceira.k117_conta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k175_sequencial)) {
         $sql2 .= " where programacaofinanceiraitem.k175_sequencial = $k175_sequencial ";
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
   public function sql_query_file ($k175_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from programacaofinanceiraitem ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k175_sequencial)){
         $sql2 .= " where programacaofinanceiraitem.k175_sequencial = $k175_sequencial ";
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
  public function sql_query_parcelas ($k175_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from programacaofinanceiraitem ";
    $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = programacaofinanceiraitem.k175_item";
    $sql .= "      inner join programacaofinanceira  on  programacaofinanceira.k117_sequencial = programacaofinanceiraitem.k175_programaacaofinanceira";
    $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
    $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
    $sql .= "      inner join acordo         on  acordoposicao.ac26_acordo     = acordo.ac16_sequencial";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = programacaofinanceira.k117_id_usuario";
    $sql .= "      inner join programacaofinanceiraparcela on k118_programacaofinanceiraitem = k175_sequencial";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($k175_sequencial)) {
        $sql2 .= " where programacaofinanceiraitem.k175_sequencial = $k175_sequencial ";
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
  public function sql_query_parcelas_lancamento ($k175_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from programacaofinanceiraitem ";
    $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = programacaofinanceiraitem.k175_item";
    $sql .= "      inner join programacaofinanceira  on  programacaofinanceira.k117_sequencial = programacaofinanceiraitem.k175_programaacaofinanceira";
    $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
    $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
    $sql .= "      inner join acordo         on  acordoposicao.ac26_acordo     = acordo.ac16_sequencial";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = programacaofinanceira.k117_id_usuario";
    $sql .= "      inner join programacaofinanceiraparcela on k118_programacaofinanceiraitem = k175_sequencial";
    $sql .= "      left  join  conlancamprogramacaofinanceiraparcela on k118_sequencial = c118_programacaofinanceiraparcela";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($k175_sequencial)) {
        $sql2 .= " where programacaofinanceiraitem.k175_sequencial = $k175_sequencial ";
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
