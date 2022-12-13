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

//MODULO: meioambiente
//CLASSE DA ENTIDADE condicionantetipolicenca
class cl_condicionantetipolicenca {
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
   var $am17_sequencial = 0;
   var $am17_condicionante = 0;
   var $am17_tipolicenca = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am17_sequencial = int4 = Sequencial
                 am17_condicionante = int4 = Código Condicionante
                 am17_tipolicenca = int4 = Código Tipo Licença
                 ";
   //funcao construtor da classe
   function cl_condicionantetipolicenca() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("condicionantetipolicenca");
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
       $this->am17_sequencial = ($this->am17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am17_sequencial"]:$this->am17_sequencial);
       $this->am17_condicionante = ($this->am17_condicionante == ""?@$GLOBALS["HTTP_POST_VARS"]["am17_condicionante"]:$this->am17_condicionante);
       $this->am17_tipolicenca = ($this->am17_tipolicenca == ""?@$GLOBALS["HTTP_POST_VARS"]["am17_tipolicenca"]:$this->am17_tipolicenca);
     }else{
       $this->am17_sequencial = ($this->am17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am17_sequencial"]:$this->am17_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($am17_sequencial){
      $this->atualizacampos();
     if($this->am17_condicionante == null ){
       $this->erro_sql = " Campo Código Condicionante não informado.";
       $this->erro_campo = "am17_condicionante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am17_tipolicenca == null ){
       $this->erro_sql = " Campo Código Tipo Licença não informado.";
       $this->erro_campo = "am17_tipolicenca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am17_sequencial == "" || $am17_sequencial == null ){
       $result = db_query("select nextval('condicionantetipolicenca_am17_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: condicionantetipolicenca_am17_sequencial_seq do campo: am17_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am17_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from condicionantetipolicenca_am17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am17_sequencial)){
         $this->erro_sql = " Campo am17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am17_sequencial = $am17_sequencial;
       }
     }
     if(($this->am17_sequencial == null) || ($this->am17_sequencial == "") ){
       $this->erro_sql = " Campo am17_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into condicionantetipolicenca(
                                       am17_sequencial
                                      ,am17_condicionante
                                      ,am17_tipolicenca
                       )
                values (
                                $this->am17_sequencial
                               ,$this->am17_condicionante
                               ,$this->am17_tipolicenca
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Licença das Condicionantes ($this->am17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Licença das Condicionantes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Licença das Condicionantes ($this->am17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am17_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21301,'$this->am17_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3838,21301,'','".AddSlashes(pg_result($resaco,0,'am17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3838,21302,'','".AddSlashes(pg_result($resaco,0,'am17_condicionante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3838,21303,'','".AddSlashes(pg_result($resaco,0,'am17_tipolicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am17_sequencial=null) {
      $this->atualizacampos();
     $sql = " update condicionantetipolicenca set ";
     $virgula = "";
     if(trim($this->am17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am17_sequencial"])){
       $sql  .= $virgula." am17_sequencial = $this->am17_sequencial ";
       $virgula = ",";
       if(trim($this->am17_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "am17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am17_condicionante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am17_condicionante"])){
       $sql  .= $virgula." am17_condicionante = $this->am17_condicionante ";
       $virgula = ",";
       if(trim($this->am17_condicionante) == null ){
         $this->erro_sql = " Campo Código Condicionante não informado.";
         $this->erro_campo = "am17_condicionante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am17_tipolicenca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am17_tipolicenca"])){
       $sql  .= $virgula." am17_tipolicenca = $this->am17_tipolicenca ";
       $virgula = ",";
       if(trim($this->am17_tipolicenca) == null ){
         $this->erro_sql = " Campo Código Tipo Licença não informado.";
         $this->erro_campo = "am17_tipolicenca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am17_sequencial!=null){
       $sql .= " am17_sequencial = $this->am17_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am17_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21301,'$this->am17_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am17_sequencial"]) || $this->am17_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3838,21301,'".AddSlashes(pg_result($resaco,$conresaco,'am17_sequencial'))."','$this->am17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am17_condicionante"]) || $this->am17_condicionante != "")
             $resac = db_query("insert into db_acount values($acount,3838,21302,'".AddSlashes(pg_result($resaco,$conresaco,'am17_condicionante'))."','$this->am17_condicionante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am17_tipolicenca"]) || $this->am17_tipolicenca != "")
             $resac = db_query("insert into db_acount values($acount,3838,21303,'".AddSlashes(pg_result($resaco,$conresaco,'am17_tipolicenca'))."','$this->am17_tipolicenca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Licença das Condicionantes não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Licença das Condicionantes não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am17_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am17_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21301,'$am17_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3838,21301,'','".AddSlashes(pg_result($resaco,$iresaco,'am17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3838,21302,'','".AddSlashes(pg_result($resaco,$iresaco,'am17_condicionante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3838,21303,'','".AddSlashes(pg_result($resaco,$iresaco,'am17_tipolicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from condicionantetipolicenca
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am17_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am17_sequencial = $am17_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Licença das Condicionantes não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Licença das Condicionantes não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:condicionantetipolicenca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am17_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from condicionantetipolicenca ";
     $sql .= "      inner join tipolicenca  on  tipolicenca.am09_sequencial = condicionantetipolicenca.am17_tipolicenca";
     $sql .= "      inner join condicionante  on  condicionante.am10_sequencial = condicionantetipolicenca.am17_condicionante";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am17_sequencial)) {
         $sql2 .= " where condicionantetipolicenca.am17_sequencial = $am17_sequencial ";
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
   public function sql_query_file ($am17_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from condicionantetipolicenca ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am17_sequencial)){
         $sql2 .= " where condicionantetipolicenca.am17_sequencial = $am17_sequencial ";
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
