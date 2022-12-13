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

//MODULO: acordos
//CLASSE DA ENTIDADE acordodocumento
class cl_acordodocumento {
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
   var $ac40_sequencial = 0;
   var $ac40_acordo = 0;
   var $ac40_descricao = null;
   var $ac40_nomearquivo = null;
   var $ac40_arquivo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ac40_sequencial = int4 = Sequencia
                 ac40_acordo = int4 = Acordo
                 ac40_descricao = varchar(100) = Descrição
                 ac40_nomearquivo = varchar(100) = Nome do Arquivo
                 ac40_arquivo = oid = Arquivos
                 ";
   //funcao construtor da classe
   function cl_acordodocumento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordodocumento");
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
       $this->ac40_sequencial = ($this->ac40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac40_sequencial"]:$this->ac40_sequencial);
       $this->ac40_acordo = ($this->ac40_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac40_acordo"]:$this->ac40_acordo);
       $this->ac40_descricao = ($this->ac40_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac40_descricao"]:$this->ac40_descricao);
       $this->ac40_nomearquivo = ($this->ac40_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac40_nomearquivo"]:$this->ac40_nomearquivo);
       $this->ac40_arquivo = ($this->ac40_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac40_arquivo"]:$this->ac40_arquivo);
     }else{
       $this->ac40_sequencial = ($this->ac40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac40_sequencial"]:$this->ac40_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac40_sequencial){
      $this->atualizacampos();
     if($this->ac40_acordo == null ){
       $this->erro_sql = " Campo Acordo nao Informado.";
       $this->erro_campo = "ac40_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac40_descricao == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ac40_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac40_arquivo == null ){
       $this->erro_sql = " Campo Arquivos nao Informado.";
       $this->erro_campo = "ac40_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac40_sequencial == "" || $ac40_sequencial == null ){
       $result = db_query("select nextval('acordodocumento_ac40_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordodocumento_ac40_sequencial_seq do campo: ac40_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ac40_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acordodocumento_ac40_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac40_sequencial)){
         $this->erro_sql = " Campo ac40_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac40_sequencial = $ac40_sequencial;
       }
     }
     if(($this->ac40_sequencial == null) || ($this->ac40_sequencial == "") ){
       $this->erro_sql = " Campo ac40_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordodocumento(
                                       ac40_sequencial
                                      ,ac40_acordo
                                      ,ac40_descricao
                                      ,ac40_nomearquivo
                                      ,ac40_arquivo
                       )
                values (
                                $this->ac40_sequencial
                               ,$this->ac40_acordo
                               ,'$this->ac40_descricao'
                               ,'$this->ac40_nomearquivo'
                               ,$this->ac40_arquivo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documento do Acordo ($this->ac40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documento do Acordo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documento do Acordo ($this->ac40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac40_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac40_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18488,'$this->ac40_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3267,18488,'','".AddSlashes(pg_result($resaco,0,'ac40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3267,18489,'','".AddSlashes(pg_result($resaco,0,'ac40_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3267,18491,'','".AddSlashes(pg_result($resaco,0,'ac40_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3267,18492,'','".AddSlashes(pg_result($resaco,0,'ac40_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3267,18490,'','".AddSlashes(pg_result($resaco,0,'ac40_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ac40_sequencial=null) {
      $this->atualizacampos();
     $sql = " update acordodocumento set ";
     $virgula = "";
     if(trim($this->ac40_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac40_sequencial"])){
       $sql  .= $virgula." ac40_sequencial = $this->ac40_sequencial ";
       $virgula = ",";
       if(trim($this->ac40_sequencial) == null ){
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "ac40_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac40_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac40_acordo"])){
       $sql  .= $virgula." ac40_acordo = $this->ac40_acordo ";
       $virgula = ",";
       if(trim($this->ac40_acordo) == null ){
         $this->erro_sql = " Campo Acordo nao Informado.";
         $this->erro_campo = "ac40_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac40_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac40_descricao"])){
       $sql  .= $virgula." ac40_descricao = '$this->ac40_descricao' ";
       $virgula = ",";
       if(trim($this->ac40_descricao) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ac40_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac40_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac40_nomearquivo"])){
       $sql  .= $virgula." ac40_nomearquivo = '$this->ac40_nomearquivo' ";
       $virgula = ",";
     }
     if(trim($this->ac40_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac40_arquivo"])){
       $sql  .= $virgula." ac40_arquivo = $this->ac40_arquivo ";
       $virgula = ",";
       if(trim($this->ac40_arquivo) == null ){
         $this->erro_sql = " Campo Arquivos nao Informado.";
         $this->erro_campo = "ac40_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac40_sequencial!=null){
       $sql .= " ac40_sequencial = $this->ac40_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac40_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18488,'$this->ac40_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac40_sequencial"]) || $this->ac40_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3267,18488,'".AddSlashes(pg_result($resaco,$conresaco,'ac40_sequencial'))."','$this->ac40_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac40_acordo"]) || $this->ac40_acordo != "")
           $resac = db_query("insert into db_acount values($acount,3267,18489,'".AddSlashes(pg_result($resaco,$conresaco,'ac40_acordo'))."','$this->ac40_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac40_descricao"]) || $this->ac40_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3267,18491,'".AddSlashes(pg_result($resaco,$conresaco,'ac40_descricao'))."','$this->ac40_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac40_nomearquivo"]) || $this->ac40_nomearquivo != "")
           $resac = db_query("insert into db_acount values($acount,3267,18492,'".AddSlashes(pg_result($resaco,$conresaco,'ac40_nomearquivo'))."','$this->ac40_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac40_arquivo"]) || $this->ac40_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,3267,18490,'".AddSlashes(pg_result($resaco,$conresaco,'ac40_arquivo'))."','$this->ac40_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documento do Acordo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documento do Acordo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ac40_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac40_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18488,'$ac40_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3267,18488,'','".AddSlashes(pg_result($resaco,$iresaco,'ac40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3267,18489,'','".AddSlashes(pg_result($resaco,$iresaco,'ac40_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3267,18491,'','".AddSlashes(pg_result($resaco,$iresaco,'ac40_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3267,18492,'','".AddSlashes(pg_result($resaco,$iresaco,'ac40_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3267,18490,'','".AddSlashes(pg_result($resaco,$iresaco,'ac40_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordodocumento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac40_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac40_sequencial = $ac40_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documento do Acordo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documento do Acordo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:acordodocumento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ac40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordodocumento ";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordodocumento.ac40_acordo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac40_sequencial!=null ){
         $sql2 .= " where acordodocumento.ac40_sequencial = $ac40_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  function sql_query_evento($sCampos = "*", $sOrdem = null, $sWhere = null) {

    $sSql  = "select {$sCampos}";
    $sSql .= "  from acordodocumento";
    $sSql .= "       left join acordodocumentoevento on ac57_acordodocumento = ac40_sequencial";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }

   // funcao do sql
   function sql_query_file ( $ac40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordodocumento ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac40_sequencial!=null ){
         $sql2 .= " where acordodocumento.ac40_sequencial = $ac40_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  public function sql_query_documentos_eventos($sCampos = '*', $sWhere = null, $sOrderBy = null) {

    $sSql  = "select {$sCampos} ";
    $sSql .= "from acordodocumento ";
    $sSql .= "    inner join acordodocumentoevento on ac57_acordodocumento  = ac40_sequencial";
    $sSql .= "    inner join acordoevento          on ac57_acordoevento     = ac55_sequencial";
    $sSql .= "    inner join acordo                on ac55_acordo           = ac16_sequencial";
    $sSql .= "    inner join acordoposicao         on ac26_acordo           = ac16_sequencial";
    $sSql .= "    inner join acordoitem            on ac20_acordoposicao    = ac26_sequencial";
    $sSql .= "    left join  acordoencerramentolicitacon on ac16_sequencial = ac58_acordo ";
    $sSql .= "    left join acordoposicaoevento    on  ac56_acordoevento    = ac55_sequencial";

    if ($sWhere) {
      $sSql .= " where {$sWhere} ";
    }

    if ($sOrderBy) {
      $sSql .= " order by {$sOrderBy} ";
    }

    return $sSql;
  }


  /**
   * Retorna os documentos dos acordos para a integração com o portal da transparencia
   *
   * @param  string $sCampos
   * @param  string $sOrdem
   * @param  string $sWhere
   * @return String
   */
  public function sql_query_transparencia($sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = "select {$sCampos} \n";
    $sSql .= "  from acordodocumento                                    \n";
    $sSql .= "       inner join acordo on ac40_acordo = ac16_sequencial \n";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} \n";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }
}
