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
//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_resultadoitem
class cl_lab_resultadoitem {
   // cria variaveis de erro
   var $rotulo          = null;
   var $query_sql       = null;
   var $numrows         = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status     = null;
   var $erro_sql        = null;
   var $erro_banco      = null;
   var $erro_msg        = null;
   var $erro_campo      = null;
   var $pagina_retorno  = null;
   // cria variaveis do arquivo
   var $la39_i_codigo    = 0;
   var $la39_i_atributo  = 0;
   var $la39_i_resultado = 0;
   var $la39_titulacao   = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 la39_i_codigo = int4 = Código
                 la39_i_atributo = int4 = Atributo
                 la39_i_resultado = int4 = Resultado
                 la39_titulacao = text = Titulação
                 ";
   //funcao construtor da classe
   function cl_lab_resultadoitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_resultadoitem");
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
       $this->la39_i_codigo = ($this->la39_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la39_i_codigo"]:$this->la39_i_codigo);
       $this->la39_i_atributo = ($this->la39_i_atributo == ""?@$GLOBALS["HTTP_POST_VARS"]["la39_i_atributo"]:$this->la39_i_atributo);
       $this->la39_i_resultado = ($this->la39_i_resultado == ""?@$GLOBALS["HTTP_POST_VARS"]["la39_i_resultado"]:$this->la39_i_resultado);
       $this->la39_titulacao = (is_null($this->la39_titulacao) ? @$GLOBALS["HTTP_POST_VARS"]["la39_titulacao"]:$this->la39_titulacao);
     }else{
       $this->la39_i_codigo = ($this->la39_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la39_i_codigo"]:$this->la39_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($la39_i_codigo){
      $this->atualizacampos();
     if($this->la39_i_atributo == null ){
       $this->erro_sql = " Campo Atributo não informado.";
       $this->erro_campo = "la39_i_atributo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la39_i_resultado == null ){
       $this->erro_sql = " Campo Resultado não informado.";
       $this->erro_campo = "la39_i_resultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la39_i_codigo == "" || $la39_i_codigo == null ){
       $result = db_query("select nextval('lab_resultado_la39_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_resultado_la39_i_codigo_seq do campo: la39_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->la39_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from lab_resultado_la39_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la39_i_codigo)){
         $this->erro_sql = " Campo la39_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la39_i_codigo = $la39_i_codigo;
       }
     }
     if(($this->la39_i_codigo == null) || ($this->la39_i_codigo == "") ){
       $this->erro_sql = " Campo la39_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_resultadoitem(
                                       la39_i_codigo
                                      ,la39_i_atributo
                                      ,la39_i_resultado
                                      ,la39_titulacao
                       )
                values (
                                $this->la39_i_codigo
                               ,$this->la39_i_atributo
                               ,$this->la39_i_resultado
                               ,'$this->la39_titulacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultado do atributo referentea ao exame ($this->la39_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultado do atributo referentea ao exame já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultado do atributo referentea ao exame ($this->la39_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->la39_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la39_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16509,'$this->la39_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2897,16509,'','".AddSlashes(pg_result($resaco,0,'la39_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2897,16511,'','".AddSlashes(pg_result($resaco,0,'la39_i_atributo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2897,16619,'','".AddSlashes(pg_result($resaco,0,'la39_i_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2897,1009270,'','".AddSlashes(pg_result($resaco,0,'la39_titulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($la39_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update lab_resultadoitem set ";
     $virgula = "";
     if(trim($this->la39_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la39_i_codigo"])){
       $sql  .= $virgula." la39_i_codigo = $this->la39_i_codigo ";
       $virgula = ",";
       if(trim($this->la39_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la39_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la39_i_atributo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la39_i_atributo"])){
       $sql  .= $virgula." la39_i_atributo = $this->la39_i_atributo ";
       $virgula = ",";
       if(trim($this->la39_i_atributo) == null ){
         $this->erro_sql = " Campo Atributo não informado.";
         $this->erro_campo = "la39_i_atributo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la39_i_resultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la39_i_resultado"])){
       $sql  .= $virgula." la39_i_resultado = $this->la39_i_resultado ";
       $virgula = ",";
       if(trim($this->la39_i_resultado) == null ){
         $this->erro_sql = " Campo Resultado não informado.";
         $this->erro_campo = "la39_i_resultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la39_titulacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la39_titulacao"])){
       $sql  .= $virgula." la39_titulacao = '$this->la39_titulacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la39_i_codigo!=null){
       $sql .= " la39_i_codigo = $this->la39_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la39_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16509,'$this->la39_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la39_i_codigo"]) || $this->la39_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2897,16509,'".AddSlashes(pg_result($resaco,$conresaco,'la39_i_codigo'))."','$this->la39_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la39_i_atributo"]) || $this->la39_i_atributo != "")
             $resac = db_query("insert into db_acount values($acount,2897,16511,'".AddSlashes(pg_result($resaco,$conresaco,'la39_i_atributo'))."','$this->la39_i_atributo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la39_i_resultado"]) || $this->la39_i_resultado != "")
             $resac = db_query("insert into db_acount values($acount,2897,16619,'".AddSlashes(pg_result($resaco,$conresaco,'la39_i_resultado'))."','$this->la39_i_resultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la39_titulacao"]) || $this->la39_titulacao != "")
             $resac = db_query("insert into db_acount values($acount,2897,1009270,'".AddSlashes(pg_result($resaco,$conresaco,'la39_titulacao'))."','$this->la39_titulacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do atributo referentea ao exame não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la39_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do atributo referentea ao exame não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la39_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->la39_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($la39_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la39_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16509,'$la39_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2897,16509,'','".AddSlashes(pg_result($resaco,$iresaco,'la39_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2897,16511,'','".AddSlashes(pg_result($resaco,$iresaco,'la39_i_atributo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2897,16619,'','".AddSlashes(pg_result($resaco,$iresaco,'la39_i_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2897,1009270,'','".AddSlashes(pg_result($resaco,$iresaco,'la39_titulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_resultadoitem
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la39_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la39_i_codigo = $la39_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do atributo referentea ao exame não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la39_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do atributo referentea ao exame não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la39_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$la39_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_resultadoitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($la39_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from lab_resultadoitem ";
     $sql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo = lab_resultadoitem.la39_i_atributo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la39_i_codigo)) {
         $sql2 .= " where lab_resultadoitem.la39_i_codigo = $la39_i_codigo ";
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
   public function sql_query_file ($la39_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lab_resultadoitem ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la39_i_codigo)){
         $sql2 .= " where lab_resultadoitem.la39_i_codigo = $la39_i_codigo ";
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

  function sql_query_resultado_valores ( $la39_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = "select {$campos} ";
    $sql .= " from lab_resultadoitem ";
    $sql .= "      left join lab_resultadonum  on la41_i_result = la39_i_codigo";
    $sql .= "      left join lab_resultadoalfa on la40_i_result = la39_i_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($la39_i_codigo!=null ){
        $sql2 .= " where lab_resultadoitem.la39_i_codigo = $la39_i_codigo ";
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

}
