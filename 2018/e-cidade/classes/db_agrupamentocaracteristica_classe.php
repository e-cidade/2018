<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: cadastro
//CLASSE DA ENTIDADE agrupamentocaracteristica
class cl_agrupamentocaracteristica {
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
   var $j139_sequencial = 0;
   var $j139_anousu = 0;
   var $j139_agrupamentocaracteristicavalor = 0;
   var $j139_caracter = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 j139_sequencial = int4 = Característica
                 j139_anousu = int4 = Exercício
                 j139_agrupamentocaracteristicavalor = int4 = Agrupamento
                 j139_caracter = int4 = Característica
                 ";
   //funcao construtor da classe
   function cl_agrupamentocaracteristica() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agrupamentocaracteristica");
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
       $this->j139_sequencial = ($this->j139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j139_sequencial"]:$this->j139_sequencial);
       $this->j139_anousu = ($this->j139_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j139_anousu"]:$this->j139_anousu);
       $this->j139_agrupamentocaracteristicavalor = ($this->j139_agrupamentocaracteristicavalor == ""?@$GLOBALS["HTTP_POST_VARS"]["j139_agrupamentocaracteristicavalor"]:$this->j139_agrupamentocaracteristicavalor);
       $this->j139_caracter = ($this->j139_caracter == ""?@$GLOBALS["HTTP_POST_VARS"]["j139_caracter"]:$this->j139_caracter);
     }else{
       $this->j139_sequencial = ($this->j139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j139_sequencial"]:$this->j139_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j139_sequencial){
      $this->atualizacampos();
     if($this->j139_anousu == null ){
       $this->erro_sql = " Campo Exercício não informado.";
       $this->erro_campo = "j139_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j139_agrupamentocaracteristicavalor == null ){
       $this->j139_agrupamentocaracteristicavalor = "0";
     }
     if($this->j139_caracter == null ){
       $this->erro_sql   = " Campo Característica não informado.";
       $this->erro_campo = "j139_caracter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j139_sequencial == "" || $j139_sequencial == null ){
       $result = db_query("select nextval('agrupamentocaracteristica_j139_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agrupamentocaracteristica_j139_sequencial_seq do campo: j139_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->j139_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from agrupamentocaracteristica_j139_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j139_sequencial)){
         $this->erro_sql = " Campo j139_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j139_sequencial = $j139_sequencial;
       }
     }
     if(($this->j139_sequencial == null) || ($this->j139_sequencial == "") ){
       $this->erro_sql = " Campo j139_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agrupamentocaracteristica(
                                       j139_sequencial
                                      ,j139_anousu
                                      ,j139_agrupamentocaracteristicavalor
                                      ,j139_caracter
                       )
                values (
                                $this->j139_sequencial
                               ,$this->j139_anousu
                               ,$this->j139_agrupamentocaracteristicavalor
                               ,$this->j139_caracter
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Características ($this->j139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Características já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Características ($this->j139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j139_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j139_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20933,'$this->j139_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3770,20933,'','".AddSlashes(pg_result($resaco,0,'j139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3770,20934,'','".AddSlashes(pg_result($resaco,0,'j139_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3770,20935,'','".AddSlashes(pg_result($resaco,0,'j139_agrupamentocaracteristicavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3770,20936,'','".AddSlashes(pg_result($resaco,0,'j139_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($j139_sequencial=null) {
      $this->atualizacampos();
     $sql = " update agrupamentocaracteristica set ";
     $virgula = "";
     if(trim($this->j139_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j139_sequencial"])){
       $sql  .= $virgula." j139_sequencial = $this->j139_sequencial ";
       $virgula = ",";
       if(trim($this->j139_sequencial) == null ){
         $this->erro_sql = " Campo Característica não informado.";
         $this->erro_campo = "j139_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j139_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j139_anousu"])){
       $sql  .= $virgula." j139_anousu = $this->j139_anousu ";
       $virgula = ",";
       if(trim($this->j139_anousu) == null ){
         $this->erro_sql = " Campo Exercício não informado.";
         $this->erro_campo = "j139_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j139_agrupamentocaracteristicavalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j139_agrupamentocaracteristicavalor"])){
        if(trim($this->j139_agrupamentocaracteristicavalor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j139_agrupamentocaracteristicavalor"])){
           $this->j139_agrupamentocaracteristicavalor = "0" ;
        }
       $sql  .= $virgula." j139_agrupamentocaracteristicavalor = $this->j139_agrupamentocaracteristicavalor ";
       $virgula = ",";
     }
     if(trim($this->j139_caracter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j139_caracter"])){
       $sql  .= $virgula." j139_caracter = $this->j139_caracter ";
       $virgula = ",";
       if(trim($this->j139_caracter) == null ){
         $this->erro_sql = " Campo Característica não informado.";
         $this->erro_campo = "j139_caracter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j139_sequencial!=null){
       $sql .= " j139_sequencial = $this->j139_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j139_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20933,'$this->j139_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j139_sequencial"]) || $this->j139_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3770,20933,'".AddSlashes(pg_result($resaco,$conresaco,'j139_sequencial'))."','$this->j139_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j139_anousu"]) || $this->j139_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3770,20934,'".AddSlashes(pg_result($resaco,$conresaco,'j139_anousu'))."','$this->j139_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j139_agrupamentocaracteristicavalor"]) || $this->j139_agrupamentocaracteristicavalor != "")
             $resac = db_query("insert into db_acount values($acount,3770,20935,'".AddSlashes(pg_result($resaco,$conresaco,'j139_agrupamentocaracteristicavalor'))."','$this->j139_agrupamentocaracteristicavalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j139_caracter"]) || $this->j139_caracter != "")
             $resac = db_query("insert into db_acount values($acount,3770,20936,'".AddSlashes(pg_result($resaco,$conresaco,'j139_caracter'))."','$this->j139_caracter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Características nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Características nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($j139_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($j139_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20933,'$j139_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3770,20933,'','".AddSlashes(pg_result($resaco,$iresaco,'j139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3770,20934,'','".AddSlashes(pg_result($resaco,$iresaco,'j139_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3770,20935,'','".AddSlashes(pg_result($resaco,$iresaco,'j139_agrupamentocaracteristicavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3770,20936,'','".AddSlashes(pg_result($resaco,$iresaco,'j139_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from agrupamentocaracteristica
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j139_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j139_sequencial = $j139_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Características nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Características nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j139_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:agrupamentocaracteristica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($j139_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from agrupamentocaracteristica ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = agrupamentocaracteristica.j139_caracter";
     $sql .= "      left  join agrupamentocaracteristicavalor  on  agrupamentocaracteristicavalor.j140_sequencial = agrupamentocaracteristica.j139_agrupamentocaracteristicavalor";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j139_sequencial)) {
         $sql2 .= " where agrupamentocaracteristica.j139_sequencial = $j139_sequencial ";
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
   public function sql_query_file ($j139_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from agrupamentocaracteristica ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j139_sequencial)){
         $sql2 .= " where agrupamentocaracteristica.j139_sequencial = $j139_sequencial ";
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
