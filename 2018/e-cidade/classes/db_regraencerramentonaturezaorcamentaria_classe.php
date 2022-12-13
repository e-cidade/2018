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

//MODULO: contabilidade
class cl_regraencerramentonaturezaorcamentaria {
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
   var $c117_sequencial = 0;
   var $c117_anousu = 0;
   var $c117_instit = 0;
   var $c117_contadevedora = null;
   var $c117_contacredora = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c117_sequencial = int4 = Sequencial
                 c117_anousu = int4 = Ano
                 c117_instit = int4 = Instituição
                 c117_contadevedora = varchar(15) = Conta Devedora
                 c117_contacredora = varchar(15) = Conta Credora
                 ";
   //funcao construtor da classe
   function cl_regraencerramentonaturezaorcamentaria() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regraencerramentonaturezaorcamentaria");
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
       $this->c117_sequencial = ($this->c117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c117_sequencial"]:$this->c117_sequencial);
       $this->c117_anousu = ($this->c117_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c117_anousu"]:$this->c117_anousu);
       $this->c117_instit = ($this->c117_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c117_instit"]:$this->c117_instit);
       $this->c117_contadevedora = ($this->c117_contadevedora == ""?@$GLOBALS["HTTP_POST_VARS"]["c117_contadevedora"]:$this->c117_contadevedora);
       $this->c117_contacredora = ($this->c117_contacredora == ""?@$GLOBALS["HTTP_POST_VARS"]["c117_contacredora"]:$this->c117_contacredora);
     }else{
       $this->c117_sequencial = ($this->c117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c117_sequencial"]:$this->c117_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c117_sequencial){
      $this->atualizacampos();
     if($this->c117_anousu == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "c117_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c117_instit == null ){
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "c117_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c117_contadevedora == null ){
       $this->erro_sql = " Campo Conta Devedora não informado.";
       $this->erro_campo = "c117_contadevedora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c117_contacredora == null ){
       $this->erro_sql = " Campo Conta Credora não informado.";
       $this->erro_campo = "c117_contacredora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c117_sequencial == "" || $c117_sequencial == null ){
       $result = db_query("select nextval('regraencerramentonaturezaorcamentaria_c117_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regraencerramentonaturezaorcamentaria_c117_sequencial_seq do campo: c117_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c117_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from regraencerramentonaturezaorcamentaria_c117_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c117_sequencial)){
         $this->erro_sql = " Campo c117_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c117_sequencial = $c117_sequencial;
       }
     }
     if(($this->c117_sequencial == null) || ($this->c117_sequencial == "") ){
       $this->erro_sql = " Campo c117_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regraencerramentonaturezaorcamentaria(
                                       c117_sequencial
                                      ,c117_anousu
                                      ,c117_instit
                                      ,c117_contadevedora
                                      ,c117_contacredora
                       )
                values (
                                $this->c117_sequencial
                               ,$this->c117_anousu
                               ,$this->c117_instit
                               ,'$this->c117_contadevedora'
                               ,'$this->c117_contacredora'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regra Encerramento Natureza Orçamentária ($this->c117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regra Encerramento Natureza Orçamentária já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regra Encerramento Natureza Orçamentária ($this->c117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c117_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c117_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20874,'$this->c117_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3756,20874,'','".AddSlashes(pg_result($resaco,0,'c117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3756,20875,'','".AddSlashes(pg_result($resaco,0,'c117_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3756,20876,'','".AddSlashes(pg_result($resaco,0,'c117_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3756,20877,'','".AddSlashes(pg_result($resaco,0,'c117_contadevedora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3756,20878,'','".AddSlashes(pg_result($resaco,0,'c117_contacredora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($c117_sequencial=null) {
      $this->atualizacampos();
     $sql = " update regraencerramentonaturezaorcamentaria set ";
     $virgula = "";
     if(trim($this->c117_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c117_sequencial"])){
       $sql  .= $virgula." c117_sequencial = $this->c117_sequencial ";
       $virgula = ",";
       if(trim($this->c117_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "c117_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c117_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c117_anousu"])){
       $sql  .= $virgula." c117_anousu = $this->c117_anousu ";
       $virgula = ",";
       if(trim($this->c117_anousu) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "c117_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c117_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c117_instit"])){
       $sql  .= $virgula." c117_instit = $this->c117_instit ";
       $virgula = ",";
       if(trim($this->c117_instit) == null ){
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "c117_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c117_contadevedora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c117_contadevedora"])){
       $sql  .= $virgula." c117_contadevedora = '$this->c117_contadevedora' ";
       $virgula = ",";
       if(trim($this->c117_contadevedora) == null ){
         $this->erro_sql = " Campo Conta Devedora não informado.";
         $this->erro_campo = "c117_contadevedora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c117_contacredora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c117_contacredora"])){
       $sql  .= $virgula." c117_contacredora = '$this->c117_contacredora' ";
       $virgula = ",";
       if(trim($this->c117_contacredora) == null ){
         $this->erro_sql = " Campo Conta Credora não informado.";
         $this->erro_campo = "c117_contacredora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c117_sequencial!=null){
       $sql .= " c117_sequencial = $this->c117_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c117_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20874,'$this->c117_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c117_sequencial"]) || $this->c117_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3756,20874,'".AddSlashes(pg_result($resaco,$conresaco,'c117_sequencial'))."','$this->c117_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c117_anousu"]) || $this->c117_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3756,20875,'".AddSlashes(pg_result($resaco,$conresaco,'c117_anousu'))."','$this->c117_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c117_instit"]) || $this->c117_instit != "")
             $resac = db_query("insert into db_acount values($acount,3756,20876,'".AddSlashes(pg_result($resaco,$conresaco,'c117_instit'))."','$this->c117_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c117_contadevedora"]) || $this->c117_contadevedora != "")
             $resac = db_query("insert into db_acount values($acount,3756,20877,'".AddSlashes(pg_result($resaco,$conresaco,'c117_contadevedora'))."','$this->c117_contadevedora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c117_contacredora"]) || $this->c117_contacredora != "")
             $resac = db_query("insert into db_acount values($acount,3756,20878,'".AddSlashes(pg_result($resaco,$conresaco,'c117_contacredora'))."','$this->c117_contacredora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regra Encerramento Natureza Orçamentária nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Regra Encerramento Natureza Orçamentária nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($c117_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c117_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20874,'$c117_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3756,20874,'','".AddSlashes(pg_result($resaco,$iresaco,'c117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3756,20875,'','".AddSlashes(pg_result($resaco,$iresaco,'c117_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3756,20876,'','".AddSlashes(pg_result($resaco,$iresaco,'c117_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3756,20877,'','".AddSlashes(pg_result($resaco,$iresaco,'c117_contadevedora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3756,20878,'','".AddSlashes(pg_result($resaco,$iresaco,'c117_contacredora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from regraencerramentonaturezaorcamentaria
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c117_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c117_sequencial = $c117_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regra Encerramento Natureza Orçamentária nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Regra Encerramento Natureza Orçamentária nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c117_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:regraencerramentonaturezaorcamentaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($c117_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from regraencerramentonaturezaorcamentaria ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c117_sequencial)) {
         $sql2 .= " where regraencerramentonaturezaorcamentaria.c117_sequencial = $c117_sequencial ";
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
   public function sql_query_file ($c117_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from regraencerramentonaturezaorcamentaria ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c117_sequencial)){
         $sql2 .= " where regraencerramentonaturezaorcamentaria.c117_sequencial = $c117_sequencial ";
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
