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
//CLASSE DA ENTIDADE censoetapaturmacenso
class cl_censoetapaturmacenso {
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
   var $ed134_codigo = 0;
   var $ed134_turmacenso = 0;
   var $ed134_censoetapa = 0;
   var $ed134_ano = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed134_codigo = int4 = C�digo
                 ed134_turmacenso = int4 = Turma censo
                 ed134_censoetapa = int4 = Etapa Censo
                 ed134_ano = int4 = Ano
                 ";
   //funcao construtor da classe
   function cl_censoetapaturmacenso() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("censoetapaturmacenso");
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
       $this->ed134_codigo = ($this->ed134_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_codigo"]:$this->ed134_codigo);
       $this->ed134_turmacenso = ($this->ed134_turmacenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_turmacenso"]:$this->ed134_turmacenso);
       $this->ed134_censoetapa = ($this->ed134_censoetapa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_censoetapa"]:$this->ed134_censoetapa);
       $this->ed134_ano = ($this->ed134_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_ano"]:$this->ed134_ano);
     }else{
       $this->ed134_codigo = ($this->ed134_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_codigo"]:$this->ed134_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed134_codigo){
      $this->atualizacampos();
     if($this->ed134_turmacenso == null ){
       $this->erro_sql = " Campo Turma censo n�o informado.";
       $this->erro_campo = "ed134_turmacenso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed134_censoetapa == null ){
       $this->erro_sql = " Campo Etapa Censo n�o informado.";
       $this->erro_campo = "ed134_censoetapa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed134_ano == null ){
       $this->erro_sql = " Campo Ano n�o informado.";
       $this->erro_campo = "ed134_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed134_codigo == "" || $ed134_codigo == null ){
       $result = db_query("select nextval('censoetapaturmacenso_ed134_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: censoetapaturmacenso_ed134_codigo_seq do campo: ed134_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed134_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from censoetapaturmacenso_ed134_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed134_codigo)){
         $this->erro_sql = " Campo ed134_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed134_codigo = $ed134_codigo;
       }
     }
     if(($this->ed134_codigo == null) || ($this->ed134_codigo == "") ){
       $this->erro_sql = " Campo ed134_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censoetapaturmacenso(
                                       ed134_codigo
                                      ,ed134_turmacenso
                                      ,ed134_censoetapa
                                      ,ed134_ano
                       )
                values (
                                $this->ed134_codigo
                               ,$this->ed134_turmacenso
                               ,$this->ed134_censoetapa
                               ,$this->ed134_ano
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Censo etapa turma censo ($this->ed134_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Censo etapa turma censo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Censo etapa turma censo ($this->ed134_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed134_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21071,'$this->ed134_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3796,21071,'','".AddSlashes(pg_result($resaco,0,'ed134_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3796,21072,'','".AddSlashes(pg_result($resaco,0,'ed134_turmacenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3796,21073,'','".AddSlashes(pg_result($resaco,0,'ed134_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3796,21074,'','".AddSlashes(pg_result($resaco,0,'ed134_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed134_codigo=null) {
      $this->atualizacampos();
     $sql = " update censoetapaturmacenso set ";
     $virgula = "";
     if(trim($this->ed134_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_codigo"])){
       $sql  .= $virgula." ed134_codigo = $this->ed134_codigo ";
       $virgula = ",";
       if(trim($this->ed134_codigo) == null ){
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "ed134_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed134_turmacenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_turmacenso"])){
       $sql  .= $virgula." ed134_turmacenso = $this->ed134_turmacenso ";
       $virgula = ",";
       if(trim($this->ed134_turmacenso) == null ){
         $this->erro_sql = " Campo Turma censo n�o informado.";
         $this->erro_campo = "ed134_turmacenso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed134_censoetapa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_censoetapa"])){
       $sql  .= $virgula." ed134_censoetapa = $this->ed134_censoetapa ";
       $virgula = ",";
       if(trim($this->ed134_censoetapa) == null ){
         $this->erro_sql = " Campo Etapa Censo n�o informado.";
         $this->erro_campo = "ed134_censoetapa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed134_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_ano"])){
       $sql  .= $virgula." ed134_ano = $this->ed134_ano ";
       $virgula = ",";
       if(trim($this->ed134_ano) == null ){
         $this->erro_sql = " Campo Ano n�o informado.";
         $this->erro_campo = "ed134_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed134_codigo!=null){
       $sql .= " ed134_codigo = $this->ed134_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed134_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21071,'$this->ed134_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed134_codigo"]) || $this->ed134_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3796,21071,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_codigo'))."','$this->ed134_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed134_turmacenso"]) || $this->ed134_turmacenso != "")
             $resac = db_query("insert into db_acount values($acount,3796,21072,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_turmacenso'))."','$this->ed134_turmacenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed134_censoetapa"]) || $this->ed134_censoetapa != "")
             $resac = db_query("insert into db_acount values($acount,3796,21073,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_censoetapa'))."','$this->ed134_censoetapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed134_ano"]) || $this->ed134_ano != "")
             $resac = db_query("insert into db_acount values($acount,3796,21074,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_ano'))."','$this->ed134_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Censo etapa turma censo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Censo etapa turma censo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed134_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed134_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21071,'$ed134_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3796,21071,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3796,21072,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_turmacenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3796,21073,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3796,21074,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from censoetapaturmacenso
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed134_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed134_codigo = $ed134_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Censo etapa turma censo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed134_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Censo etapa turma censo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed134_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed134_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:censoetapaturmacenso";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed134_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from censoetapaturmacenso ";
     $sql .= "      inner join censoetapa  on  censoetapa.ed266_i_codigo   = censoetapaturmacenso.ed134_censoetapa ";
     $sql .= "                            and  censoetapa.ed266_ano        = censoetapaturmacenso.ed134_ano";
     $sql .= "      inner join turmacenso  on  turmacenso.ed342_sequencial = censoetapaturmacenso.ed134_turmacenso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed134_codigo)) {
         $sql2 .= " where censoetapaturmacenso.ed134_codigo = $ed134_codigo ";
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
   public function sql_query_file ($ed134_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from censoetapaturmacenso ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed134_codigo)){
         $sql2 .= " where censoetapaturmacenso.ed134_codigo = $ed134_codigo ";
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
