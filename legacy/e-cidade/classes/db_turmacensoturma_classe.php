<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: escola
//CLASSE DA ENTIDADE turmacensoturma
class cl_turmacensoturma {
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
   var $ed343_sequencia = 0;
   var $ed343_turmacenso = 0;
   var $ed343_turma = 0;
   var $ed343_principal = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed343_sequencia = int4 = Código
                 ed343_turmacenso = int4 = Turma Censo
                 ed343_turma = int4 = Turma
                 ed343_principal = bool = Principal
                 ";
   //funcao construtor da classe
   function cl_turmacensoturma() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmacensoturma");
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
       $this->ed343_sequencia = ($this->ed343_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed343_sequencia"]:$this->ed343_sequencia);
       $this->ed343_turmacenso = ($this->ed343_turmacenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed343_turmacenso"]:$this->ed343_turmacenso);
       $this->ed343_turma = ($this->ed343_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed343_turma"]:$this->ed343_turma);
       $this->ed343_principal = ($this->ed343_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed343_principal"]:$this->ed343_principal);
     }else{
       $this->ed343_sequencia = ($this->ed343_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed343_sequencia"]:$this->ed343_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($ed343_sequencia){
      $this->atualizacampos();
     if($this->ed343_turmacenso == null ){
       $this->erro_sql = " Campo Turma Censo não informado.";
       $this->erro_campo = "ed343_turmacenso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed343_turma == null ){
       $this->erro_sql = " Campo Turma não informado.";
       $this->erro_campo = "ed343_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed343_principal == null ){
       $this->erro_sql = " Campo Principal não informado.";
       $this->erro_campo = "ed343_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed343_sequencia == "" || $ed343_sequencia == null ){
       $result = db_query("select nextval('turmacensoturma_ed343_sequencia_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmacensoturma_ed343_sequencia_seq do campo: ed343_sequencia";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed343_sequencia = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from turmacensoturma_ed343_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed343_sequencia)){
         $this->erro_sql = " Campo ed343_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed343_sequencia = $ed343_sequencia;
       }
     }
     if(($this->ed343_sequencia == null) || ($this->ed343_sequencia == "") ){
       $this->erro_sql = " Campo ed343_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmacensoturma(
                                       ed343_sequencia
                                      ,ed343_turmacenso
                                      ,ed343_turma
                                      ,ed343_principal
                       )
                values (
                                $this->ed343_sequencia
                               ,$this->ed343_turmacenso
                               ,$this->ed343_turma
                               ,'$this->ed343_principal'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Turma censo turma ($this->ed343_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Turma censo turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Turma censo turma ($this->ed343_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed343_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed343_sequencia  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20584,'$this->ed343_sequencia','I')");
         $resac = db_query("insert into db_acount values($acount,3704,20584,'','".AddSlashes(pg_result($resaco,0,'ed343_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3704,20586,'','".AddSlashes(pg_result($resaco,0,'ed343_turmacenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3704,20587,'','".AddSlashes(pg_result($resaco,0,'ed343_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3704,20588,'','".AddSlashes(pg_result($resaco,0,'ed343_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed343_sequencia=null) {
      $this->atualizacampos();
     $sql = " update turmacensoturma set ";
     $virgula = "";
     if(trim($this->ed343_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed343_sequencia"])){
       $sql  .= $virgula." ed343_sequencia = $this->ed343_sequencia ";
       $virgula = ",";
       if(trim($this->ed343_sequencia) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed343_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed343_turmacenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed343_turmacenso"])){
       $sql  .= $virgula." ed343_turmacenso = $this->ed343_turmacenso ";
       $virgula = ",";
       if(trim($this->ed343_turmacenso) == null ){
         $this->erro_sql = " Campo Turma Censo não informado.";
         $this->erro_campo = "ed343_turmacenso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed343_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed343_turma"])){
       $sql  .= $virgula." ed343_turma = $this->ed343_turma ";
       $virgula = ",";
       if(trim($this->ed343_turma) == null ){
         $this->erro_sql = " Campo Turma não informado.";
         $this->erro_campo = "ed343_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed343_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed343_principal"])){
       $sql  .= $virgula." ed343_principal = '$this->ed343_principal' ";
       $virgula = ",";
       if(trim($this->ed343_principal) == null ){
         $this->erro_sql = " Campo Principal não informado.";
         $this->erro_campo = "ed343_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed343_sequencia!=null){
       $sql .= " ed343_sequencia = $this->ed343_sequencia";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed343_sequencia));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20584,'$this->ed343_sequencia','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed343_sequencia"]) || $this->ed343_sequencia != "")
             $resac = db_query("insert into db_acount values($acount,3704,20584,'".AddSlashes(pg_result($resaco,$conresaco,'ed343_sequencia'))."','$this->ed343_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed343_turmacenso"]) || $this->ed343_turmacenso != "")
             $resac = db_query("insert into db_acount values($acount,3704,20586,'".AddSlashes(pg_result($resaco,$conresaco,'ed343_turmacenso'))."','$this->ed343_turmacenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed343_turma"]) || $this->ed343_turma != "")
             $resac = db_query("insert into db_acount values($acount,3704,20587,'".AddSlashes(pg_result($resaco,$conresaco,'ed343_turma'))."','$this->ed343_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed343_principal"]) || $this->ed343_principal != "")
             $resac = db_query("insert into db_acount values($acount,3704,20588,'".AddSlashes(pg_result($resaco,$conresaco,'ed343_principal'))."','$this->ed343_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turma censo turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed343_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Turma censo turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed343_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed343_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed343_sequencia=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed343_sequencia));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20584,'$ed343_sequencia','E')");
           $resac  = db_query("insert into db_acount values($acount,3704,20584,'','".AddSlashes(pg_result($resaco,$iresaco,'ed343_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3704,20586,'','".AddSlashes(pg_result($resaco,$iresaco,'ed343_turmacenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3704,20587,'','".AddSlashes(pg_result($resaco,$iresaco,'ed343_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3704,20588,'','".AddSlashes(pg_result($resaco,$iresaco,'ed343_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from turmacensoturma
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed343_sequencia)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed343_sequencia = $ed343_sequencia ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turma censo turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed343_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Turma censo turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed343_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed343_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmacensoturma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   /**
    * @deprecated
    * @see sql_query2
    */
   public function sql_query ($ed343_sequencia = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from turmacensoturma ";
     $sql .= "      inner join turmacenso  on  turmacenso.ed342_sequencial = turmacensoturma.ed343_turmacenso";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmacensoturma.ed343_turma";
     $sql .= "      inner join censoetapa  on  censoetapa.ed266_i_codigo = turmacenso.ed342_censoetapa";
     $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join censoetapa  as a on   a.ed266_i_codigo = turma.ed57_i_censoetapa";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed343_sequencia)) {
         $sql2 .= " where turmacensoturma.ed343_sequencia = $ed343_sequencia ";
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

  public function sql_query2 ($ed343_sequencia = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "  select {$campos}";
     $sql .= "    from turmacensoturma ";
     $sql .= "   inner join turmacenso           on turmacenso.ed342_sequencial = turmacensoturma.ed343_turmacenso ";
     $sql .= "   inner join turma                on turma.ed57_i_codigo = turmacensoturma.ed343_turma ";
     $sql .= "   inner join censoetapaturmacenso on censoetapaturmacenso.ed134_turmacenso = turmacenso.ed342_sequencial ";
     $sql .= "   inner join censoetapa           on censoetapa.ed266_i_codigo = censoetapaturmacenso.ed134_censoetapa ";
     $sql .= "                                  and censoetapa.ed266_ano      = censoetapaturmacenso.ed134_ano ";
     $sql .= "   left  join censocursoprofiss    on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss ";
     $sql .= "   inner join escola               on  escola.ed18_i_codigo = turma.ed57_i_escola ";
     $sql .= "   inner join turno                on  turno.ed15_i_codigo = turma.ed57_i_turno ";
     $sql .= "   inner join sala                 on  sala.ed16_i_codigo = turma.ed57_i_sala ";
     $sql .= "   inner join calendario           on  calendario.ed52_i_codigo = turma.ed57_i_calendario ";
     $sql .= "   inner join base                 on  base.ed31_i_codigo = turma.ed57_i_base ";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed343_sequencia)) {
         $sql2 .= " where turmacensoturma.ed343_sequencia = $ed343_sequencia ";
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
   public function sql_query_file ($ed343_sequencia = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from turmacensoturma ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed343_sequencia)){
         $sql2 .= " where turmacensoturma.ed343_sequencia = $ed343_sequencia ";
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
