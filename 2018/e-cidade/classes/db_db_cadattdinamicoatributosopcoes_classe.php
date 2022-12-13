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
class cl_db_cadattdinamicoatributosopcoes {
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
   var $db18_sequencial = 0;
   var $db18_cadattdinamicoatributos = 0;
   var $db18_opcao = null;
   var $db18_valor = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db18_sequencial = int4 = Código
                 db18_cadattdinamicoatributos = int4 = Atributo
                 db18_opcao = varchar(50) = Opção
                 db18_valor = varchar(200) = Valor
                 ";
   //funcao construtor da classe
   function cl_db_cadattdinamicoatributosopcoes() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cadattdinamicoatributosopcoes");
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
       $this->db18_sequencial = ($this->db18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db18_sequencial"]:$this->db18_sequencial);
       $this->db18_cadattdinamicoatributos = ($this->db18_cadattdinamicoatributos == ""?@$GLOBALS["HTTP_POST_VARS"]["db18_cadattdinamicoatributos"]:$this->db18_cadattdinamicoatributos);
       $this->db18_opcao = ($this->db18_opcao == ""?@$GLOBALS["HTTP_POST_VARS"]["db18_opcao"]:$this->db18_opcao);
       $this->db18_valor = ($this->db18_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["db18_valor"]:$this->db18_valor);
     }else{
       $this->db18_sequencial = ($this->db18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db18_sequencial"]:$this->db18_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db18_sequencial){
      $this->atualizacampos();
     if($this->db18_cadattdinamicoatributos == null ){
       $this->erro_sql = " Campo Atributo não informado.";
       $this->erro_campo = "db18_cadattdinamicoatributos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db18_opcao == null ){
       $this->erro_sql = " Campo Opção não informado.";
       $this->erro_campo = "db18_opcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db18_valor == null ){
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "db18_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db18_sequencial == "" || $db18_sequencial == null ){
       $result = db_query("select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cadattdinamicoatributosopcoes_db18_sequencial_seq do campo: db18_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->db18_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from db_cadattdinamicoatributosopcoes_db18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db18_sequencial)){
         $this->erro_sql = " Campo db18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db18_sequencial = $db18_sequencial;
       }
     }
     if(($this->db18_sequencial == null) || ($this->db18_sequencial == "") ){
       $this->erro_sql = " Campo db18_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cadattdinamicoatributosopcoes(
                                       db18_sequencial
                                      ,db18_cadattdinamicoatributos
                                      ,db18_opcao
                                      ,db18_valor
                       )
                values (
                                $this->db18_sequencial
                               ,$this->db18_cadattdinamicoatributos
                               ,'$this->db18_opcao'
                               ,'$this->db18_valor'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Opções dos Atributos Dinâmicos ($this->db18_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Opções dos Atributos Dinâmicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Opções dos Atributos Dinâmicos ($this->db18_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db18_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21713,'$this->db18_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3902,21713,'','".AddSlashes(pg_result($resaco,0,'db18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3902,21714,'','".AddSlashes(pg_result($resaco,0,'db18_cadattdinamicoatributos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3902,21715,'','".AddSlashes(pg_result($resaco,0,'db18_opcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3902,21716,'','".AddSlashes(pg_result($resaco,0,'db18_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($db18_sequencial=null) {
      $this->atualizacampos();
     $sql = " update db_cadattdinamicoatributosopcoes set ";
     $virgula = "";
     if(trim($this->db18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db18_sequencial"])){
       $sql  .= $virgula." db18_sequencial = $this->db18_sequencial ";
       $virgula = ",";
       if(trim($this->db18_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "db18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db18_cadattdinamicoatributos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db18_cadattdinamicoatributos"])){
       $sql  .= $virgula." db18_cadattdinamicoatributos = $this->db18_cadattdinamicoatributos ";
       $virgula = ",";
       if(trim($this->db18_cadattdinamicoatributos) == null ){
         $this->erro_sql = " Campo Atributo não informado.";
         $this->erro_campo = "db18_cadattdinamicoatributos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db18_opcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db18_opcao"])){
       $sql  .= $virgula." db18_opcao = '$this->db18_opcao' ";
       $virgula = ",";
       if(trim($this->db18_opcao) == null ){
         $this->erro_sql = " Campo Opção não informado.";
         $this->erro_campo = "db18_opcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db18_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db18_valor"])){
       $sql  .= $virgula." db18_valor = '$this->db18_valor' ";
       $virgula = ",";
       if(trim($this->db18_valor) == null ){
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "db18_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db18_sequencial!=null){
       $sql .= " db18_sequencial = $this->db18_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db18_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21713,'$this->db18_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db18_sequencial"]) || $this->db18_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3902,21713,'".AddSlashes(pg_result($resaco,$conresaco,'db18_sequencial'))."','$this->db18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db18_cadattdinamicoatributos"]) || $this->db18_cadattdinamicoatributos != "")
             $resac = db_query("insert into db_acount values($acount,3902,21714,'".AddSlashes(pg_result($resaco,$conresaco,'db18_cadattdinamicoatributos'))."','$this->db18_cadattdinamicoatributos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db18_opcao"]) || $this->db18_opcao != "")
             $resac = db_query("insert into db_acount values($acount,3902,21715,'".AddSlashes(pg_result($resaco,$conresaco,'db18_opcao'))."','$this->db18_opcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db18_valor"]) || $this->db18_valor != "")
             $resac = db_query("insert into db_acount values($acount,3902,21716,'".AddSlashes(pg_result($resaco,$conresaco,'db18_valor'))."','$this->db18_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Opções dos Atributos Dinâmicos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Opções dos Atributos Dinâmicos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($db18_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db18_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21713,'$db18_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3902,21713,'','".AddSlashes(pg_result($resaco,$iresaco,'db18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3902,21714,'','".AddSlashes(pg_result($resaco,$iresaco,'db18_cadattdinamicoatributos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3902,21715,'','".AddSlashes(pg_result($resaco,$iresaco,'db18_opcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3902,21716,'','".AddSlashes(pg_result($resaco,$iresaco,'db18_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_cadattdinamicoatributosopcoes
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db18_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db18_sequencial = $db18_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Opções dos Atributos Dinâmicos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Opções dos Atributos Dinâmicos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_cadattdinamicoatributosopcoes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($db18_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from db_cadattdinamicoatributosopcoes ";
     $sql .= "      inner join db_cadattdinamicoatributos  on  db_cadattdinamicoatributos.db109_sequencial = db_cadattdinamicoatributosopcoes.db18_cadattdinamicoatributos";
     $sql .= "      inner join db_cadattdinamico  on  db_cadattdinamico.db118_sequencial = db_cadattdinamicoatributos.db109_db_cadattdinamico";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db18_sequencial)) {
         $sql2 .= " where db_cadattdinamicoatributosopcoes.db18_sequencial = $db18_sequencial ";
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
   public function sql_query_file ($db18_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_cadattdinamicoatributosopcoes ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db18_sequencial)){
         $sql2 .= " where db_cadattdinamicoatributosopcoes.db18_sequencial = $db18_sequencial ";
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
