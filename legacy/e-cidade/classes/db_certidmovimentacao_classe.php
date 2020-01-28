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

//MODULO: divida
//CLASSE DA ENTIDADE certidmovimentacao
class cl_certidmovimentacao {
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
   var $v32_sequencial = 0;
   var $v32_certidcartorio = 0;
   var $v32_datamovimentacao_dia = null;
   var $v32_datamovimentacao_mes = null;
   var $v32_datamovimentacao_ano = null;
   var $v32_datamovimentacao = null;
   var $v32_tipo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v32_sequencial = int4 = C�digo da Movimenta��o
                 v32_certidcartorio = int4 = C�digo Certid�o Cart�rio
                 v32_datamovimentacao = date = Data Movimenta��o
                 v32_tipo = int4 = Tipo Movimenta��o
                 ";
   //funcao construtor da classe
   function cl_certidmovimentacao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidmovimentacao");
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
       $this->v32_sequencial = ($this->v32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v32_sequencial"]:$this->v32_sequencial);
       $this->v32_certidcartorio = ($this->v32_certidcartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["v32_certidcartorio"]:$this->v32_certidcartorio);
       if($this->v32_datamovimentacao == ""){
         $this->v32_datamovimentacao_dia = ($this->v32_datamovimentacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v32_datamovimentacao_dia"]:$this->v32_datamovimentacao_dia);
         $this->v32_datamovimentacao_mes = ($this->v32_datamovimentacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v32_datamovimentacao_mes"]:$this->v32_datamovimentacao_mes);
         $this->v32_datamovimentacao_ano = ($this->v32_datamovimentacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v32_datamovimentacao_ano"]:$this->v32_datamovimentacao_ano);
         if($this->v32_datamovimentacao_dia != ""){
            $this->v32_datamovimentacao = $this->v32_datamovimentacao_ano."-".$this->v32_datamovimentacao_mes."-".$this->v32_datamovimentacao_dia;
         }
       }
       $this->v32_tipo = ($this->v32_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["v32_tipo"]:$this->v32_tipo);
     }else{
       $this->v32_sequencial = ($this->v32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v32_sequencial"]:$this->v32_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($v32_sequencial){
      $this->atualizacampos();
     if($this->v32_certidcartorio == null ){
       $this->erro_sql = " Campo C�digo Certid�o Cart�rio n�o informado.";
       $this->erro_campo = "v32_certidcartorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v32_datamovimentacao == null ){
       $this->erro_sql = " Campo Data Movimenta��o n�o informado.";
       $this->erro_campo = "v32_datamovimentacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v32_tipo == null ){
       $this->erro_sql = " Campo Tipo Movimenta��o n�o informado.";
       $this->erro_campo = "v32_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v32_sequencial == "" || $v32_sequencial == null ){
       $result = db_query("select nextval('certidmovimentacao_v32_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidmovimentacao_v32_sequencial_seq do campo: v32_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v32_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from certidmovimentacao_v32_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v32_sequencial)){
         $this->erro_sql = " Campo v32_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v32_sequencial = $v32_sequencial;
       }
     }
     if(($this->v32_sequencial == null) || ($this->v32_sequencial == "") ){
       $this->erro_sql = " Campo v32_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidmovimentacao(
                                       v32_sequencial
                                      ,v32_certidcartorio
                                      ,v32_datamovimentacao
                                      ,v32_tipo
                       )
                values (
                                $this->v32_sequencial
                               ,$this->v32_certidcartorio
                               ,".($this->v32_datamovimentacao == "null" || $this->v32_datamovimentacao == ""?"null":"'".$this->v32_datamovimentacao."'")."
                               ,$this->v32_tipo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimenta��o de Certid�es ($this->v32_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimenta��o de Certid�es j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimenta��o de Certid�es ($this->v32_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v32_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v32_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21232,'$this->v32_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3824,21232,'','".AddSlashes(pg_result($resaco,0,'v32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3824,21233,'','".AddSlashes(pg_result($resaco,0,'v32_certidcartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3824,21234,'','".AddSlashes(pg_result($resaco,0,'v32_datamovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3824,21235,'','".AddSlashes(pg_result($resaco,0,'v32_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($v32_sequencial=null) {
      $this->atualizacampos();
     $sql = " update certidmovimentacao set ";
     $virgula = "";
     if(trim($this->v32_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v32_sequencial"])){
       $sql  .= $virgula." v32_sequencial = $this->v32_sequencial ";
       $virgula = ",";
       if(trim($this->v32_sequencial) == null ){
         $this->erro_sql = " Campo C�digo da Movimenta��o n�o informado.";
         $this->erro_campo = "v32_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v32_certidcartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v32_certidcartorio"])){
       $sql  .= $virgula." v32_certidcartorio = $this->v32_certidcartorio ";
       $virgula = ",";
       if(trim($this->v32_certidcartorio) == null ){
         $this->erro_sql = " Campo C�digo Certid�o Cart�rio n�o informado.";
         $this->erro_campo = "v32_certidcartorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v32_datamovimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v32_datamovimentacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v32_datamovimentacao_dia"] !="") ){
       $sql  .= $virgula." v32_datamovimentacao = '$this->v32_datamovimentacao' ";
       $virgula = ",";
       if(trim($this->v32_datamovimentacao) == null ){
         $this->erro_sql = " Campo Data Movimenta��o n�o informado.";
         $this->erro_campo = "v32_datamovimentacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v32_datamovimentacao_dia"])){
         $sql  .= $virgula." v32_datamovimentacao = null ";
         $virgula = ",";
         if(trim($this->v32_datamovimentacao) == null ){
           $this->erro_sql = " Campo Data Movimenta��o n�o informado.";
           $this->erro_campo = "v32_datamovimentacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v32_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v32_tipo"])){
       $sql  .= $virgula." v32_tipo = $this->v32_tipo ";
       $virgula = ",";
       if(trim($this->v32_tipo) == null ){
         $this->erro_sql = " Campo Tipo Movimenta��o n�o informado.";
         $this->erro_campo = "v32_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v32_sequencial!=null){
       $sql .= " v32_sequencial = $this->v32_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v32_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21232,'$this->v32_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v32_sequencial"]) || $this->v32_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3824,21232,'".AddSlashes(pg_result($resaco,$conresaco,'v32_sequencial'))."','$this->v32_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v32_certidcartorio"]) || $this->v32_certidcartorio != "")
             $resac = db_query("insert into db_acount values($acount,3824,21233,'".AddSlashes(pg_result($resaco,$conresaco,'v32_certidcartorio'))."','$this->v32_certidcartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v32_datamovimentacao"]) || $this->v32_datamovimentacao != "")
             $resac = db_query("insert into db_acount values($acount,3824,21234,'".AddSlashes(pg_result($resaco,$conresaco,'v32_datamovimentacao'))."','$this->v32_datamovimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v32_tipo"]) || $this->v32_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3824,21235,'".AddSlashes(pg_result($resaco,$conresaco,'v32_tipo'))."','$this->v32_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimenta��o de Certid�es n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v32_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Movimenta��o de Certid�es n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v32_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v32_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($v32_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v32_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21232,'$v32_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3824,21232,'','".AddSlashes(pg_result($resaco,$iresaco,'v32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3824,21233,'','".AddSlashes(pg_result($resaco,$iresaco,'v32_certidcartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3824,21234,'','".AddSlashes(pg_result($resaco,$iresaco,'v32_datamovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3824,21235,'','".AddSlashes(pg_result($resaco,$iresaco,'v32_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from certidmovimentacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v32_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v32_sequencial = $v32_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimenta��o de Certid�es n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v32_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Movimenta��o de Certid�es n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v32_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v32_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidmovimentacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($v32_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from certidmovimentacao ";
     $sql .= "      inner join certidcartorio  on  certidcartorio.v31_sequencial = certidmovimentacao.v32_certidcartorio";
     $sql .= "      inner join certid  on  certid.v13_certid = certidcartorio.v31_certid";
     $sql .= "      inner join cartorio  on  cartorio.v82_sequencial = certidcartorio.v31_cartorio";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v32_sequencial)) {
         $sql2 .= " where certidmovimentacao.v32_sequencial = $v32_sequencial ";
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
   public function sql_query_file ($v32_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from certidmovimentacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v32_sequencial)){
         $sql2 .= " where certidmovimentacao.v32_sequencial = $v32_sequencial ";
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
