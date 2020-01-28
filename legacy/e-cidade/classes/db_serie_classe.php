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

//MODULO: Escola
//CLASSE DA ENTIDADE serie
class cl_serie {
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
   var $ed11_i_codigo = 0;
   var $ed11_i_ensino = 0;
   var $ed11_c_descr = null;
   var $ed11_c_abrev = null;
   var $ed11_i_sequencia = 0;
   var $ed11_i_codcenso = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed11_i_codigo = int8 = Código
                 ed11_i_ensino = int8 = Ensino
                 ed11_c_descr = char(20) = Descrição
                 ed11_c_abrev = char(10) = Abreviatura
                 ed11_i_sequencia = int4 = Ordenação
                 ed11_i_codcenso = int4 = Código Censo
                 ";
   //funcao construtor da classe
   function cl_serie() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("serie");
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
       $this->ed11_i_codigo = ($this->ed11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"]:$this->ed11_i_codigo);
       $this->ed11_i_ensino = ($this->ed11_i_ensino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_ensino"]:$this->ed11_i_ensino);
       $this->ed11_c_descr = ($this->ed11_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_c_descr"]:$this->ed11_c_descr);
       $this->ed11_c_abrev = ($this->ed11_c_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_c_abrev"]:$this->ed11_c_abrev);
       $this->ed11_i_sequencia = ($this->ed11_i_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_sequencia"]:$this->ed11_i_sequencia);
       $this->ed11_i_codcenso = ($this->ed11_i_codcenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_codcenso"]:$this->ed11_i_codcenso);
     }else{
       $this->ed11_i_codigo = ($this->ed11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"]:$this->ed11_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed11_i_codigo){
      $this->atualizacampos();
     if($this->ed11_i_ensino == null ){
       $this->erro_sql = " Campo Nível de Ensino não informado.";
       $this->erro_campo = "ed11_i_ensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed11_c_descr == null ){
       $this->erro_sql = " Campo Nome da Etapa não informado.";
       $this->erro_campo = "ed11_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed11_i_sequencia == null ){
       $this->ed11_i_sequencia = "0";
     }
     if($this->ed11_i_codcenso == null ){
       $this->ed11_i_codcenso = "null";
     }
     if($ed11_i_codigo == "" || $ed11_i_codigo == null ){
       $result = db_query("select nextval('serie_ed11_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: serie_ed11_i_codigo_seq do campo: ed11_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed11_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from serie_ed11_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed11_i_codigo)){
         $this->erro_sql = " Campo ed11_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed11_i_codigo = $ed11_i_codigo;
       }
     }
     if(($this->ed11_i_codigo == null) || ($this->ed11_i_codigo == "") ){
       $this->erro_sql = " Campo ed11_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into serie(
                                       ed11_i_codigo
                                      ,ed11_i_ensino
                                      ,ed11_c_descr
                                      ,ed11_c_abrev
                                      ,ed11_i_sequencia
                                      ,ed11_i_codcenso
                       )
                values (
                                $this->ed11_i_codigo
                               ,$this->ed11_i_ensino
                               ,'$this->ed11_c_descr'
                               ,'$this->ed11_c_abrev'
                               ,$this->ed11_i_sequencia
                               ,$this->ed11_i_codcenso
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Séries ($this->ed11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Séries já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Séries ($this->ed11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed11_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008271,'$this->ed11_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010047,1008271,'','".AddSlashes(pg_result($resaco,0,'ed11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010047,1008272,'','".AddSlashes(pg_result($resaco,0,'ed11_i_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010047,1008273,'','".AddSlashes(pg_result($resaco,0,'ed11_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010047,1008274,'','".AddSlashes(pg_result($resaco,0,'ed11_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010047,1008275,'','".AddSlashes(pg_result($resaco,0,'ed11_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010047,13786,'','".AddSlashes(pg_result($resaco,0,'ed11_i_codcenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed11_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update serie set ";
     $virgula = "";
     if(trim($this->ed11_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"])){
       $sql  .= $virgula." ed11_i_codigo = $this->ed11_i_codigo ";
       $virgula = ",";
       if(trim($this->ed11_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed11_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed11_i_ensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_ensino"])){
       $sql  .= $virgula." ed11_i_ensino = $this->ed11_i_ensino ";
       $virgula = ",";
       if(trim($this->ed11_i_ensino) == null ){
         $this->erro_sql = " Campo Nível de Ensino não informado.";
         $this->erro_campo = "ed11_i_ensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed11_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_c_descr"])){
       $sql  .= $virgula." ed11_c_descr = '$this->ed11_c_descr' ";
       $virgula = ",";
       if(trim($this->ed11_c_descr) == null ){
         $this->erro_sql = " Campo Nome da Etapa não informado.";
         $this->erro_campo = "ed11_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed11_c_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_c_abrev"])){
       $sql  .= $virgula." ed11_c_abrev = '$this->ed11_c_abrev' ";
       $virgula = ",";
     }
     if(trim($this->ed11_i_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_sequencia"])){
        if(trim($this->ed11_i_sequencia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_sequencia"])){
           $this->ed11_i_sequencia = "0" ;
        }
       $sql  .= $virgula." ed11_i_sequencia = $this->ed11_i_sequencia ";
       $virgula = ",";
     }
     if(trim($this->ed11_i_codcenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_codcenso"])){
        if(trim($this->ed11_i_codcenso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_codcenso"])){
           $this->ed11_i_codcenso = "0" ;
        }
       $sql  .= $virgula." ed11_i_codcenso = $this->ed11_i_codcenso ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed11_i_codigo!=null){
       $sql .= " ed11_i_codigo = $this->ed11_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed11_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008271,'$this->ed11_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"]) || $this->ed11_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010047,1008271,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_i_codigo'))."','$this->ed11_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_ensino"]) || $this->ed11_i_ensino != "")
             $resac = db_query("insert into db_acount values($acount,1010047,1008272,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_i_ensino'))."','$this->ed11_i_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed11_c_descr"]) || $this->ed11_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,1010047,1008273,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_c_descr'))."','$this->ed11_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed11_c_abrev"]) || $this->ed11_c_abrev != "")
             $resac = db_query("insert into db_acount values($acount,1010047,1008274,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_c_abrev'))."','$this->ed11_c_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_sequencia"]) || $this->ed11_i_sequencia != "")
             $resac = db_query("insert into db_acount values($acount,1010047,1008275,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_i_sequencia'))."','$this->ed11_i_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_codcenso"]) || $this->ed11_i_codcenso != "")
             $resac = db_query("insert into db_acount values($acount,1010047,13786,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_i_codcenso'))."','$this->ed11_i_codcenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Séries nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Séries nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed11_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed11_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008271,'$ed11_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010047,1008271,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010047,1008272,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_i_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010047,1008273,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010047,1008274,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010047,1008275,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010047,13786,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_i_codcenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from serie
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed11_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed11_i_codigo = $ed11_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Séries nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Séries nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed11_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:serie";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed11_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from serie ";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join mediacaodidaticopedagogica  on  mediacaodidaticopedagogica.ed130_codigo = ensino.ed10_mediacaodidaticopedagogica";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed11_i_codigo)) {
         $sql2 .= " where serie.ed11_i_codigo = $ed11_i_codigo ";
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
   public function sql_query_file ($ed11_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from serie ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed11_i_codigo)){
         $sql2 .= " where serie.ed11_i_codigo = $ed11_i_codigo ";
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

   /**
   * SQL para busca do vínculo de uma série a um curso
   */
  function sql_query_curso ( $ed11_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";

    if( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql  .= " from serie ";
    $sql  .= "      inner join ensino     on ensino.ed10_i_codigo      = serie.ed11_i_ensino";
    $sql  .= "      inner join tipoensino on tipoensino.ed36_i_codigo  = ensino.ed10_i_tipoensino";
    $sql  .= "      left  join cursoedu   on cursoedu.ed29_i_ensino    = ensino.ed10_i_codigo";
    $sql2  = "";

    if( $dbwhere == "" ) {

      if( $ed11_i_codigo != null ) {
        $sql2 .= " where serie.ed11_i_codigo = $ed11_i_codigo ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if( $ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
}
   function sql_query_equiv ( $ed11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from serie ";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join tipoensino on tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed11_i_codigo!=null ){
         $sql2 .= " where serie.ed11_i_codigo = $ed11_i_codigo ";
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
   function sql_query_turma ( $ed11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from serie ";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join tipoensino on tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      inner join cursoedu on cursoedu.ed29_i_ensino = ensino.ed10_i_codigo";
     $sql .= "      inner join basemps on basemps.ed34_i_serie = serie.ed11_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed11_i_codigo!=null ){
         $sql2 .= " where serie.ed11_i_codigo = $ed11_i_codigo ";
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
   function sql_query_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from serie " ;
    $sSql .= "      inner join matriculaserie on ed221_i_serie=ed11_i_codigo  " ;
    $sSql .= "      inner join matricula on ed60_i_codigo=ed221_i_matricula " ;
    $sSql .= "      inner join turma on ed57_i_codigo=ed60_i_turma " ;
    $sSql .= "      inner join calendario on ed57_i_calendario=ed52_i_codigo " ;
    $sSql .= "      inner join calendarioescola on ed38_i_calendario = ed52_i_codigo";
    $sSql .= "      inner join ensino on ed10_i_codigo = ed11_i_ensino " ;
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where matriculaserie.ed221_c_origem = 'S' AND matricula.ed60_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where matriculaserie.ed221_c_origem = 'S' AND $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;
  }

}
