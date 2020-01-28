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
class cl_db_cadattdinamicosysarquivo {
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
   var $db17_sequencial = 0;
   var $db17_sysarquivo = 0;
   var $db17_cadattdinamico = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db17_sequencial = int4 = Código
                 db17_sysarquivo = int4 = Código Tabela
                 db17_cadattdinamico = int4 = Código Atributos
                 ";
   //funcao construtor da classe
   function cl_db_cadattdinamicosysarquivo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cadattdinamicosysarquivo");
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
       $this->db17_sequencial = ($this->db17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db17_sequencial"]:$this->db17_sequencial);
       $this->db17_sysarquivo = ($this->db17_sysarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["db17_sysarquivo"]:$this->db17_sysarquivo);
       $this->db17_cadattdinamico = ($this->db17_cadattdinamico == ""?@$GLOBALS["HTTP_POST_VARS"]["db17_cadattdinamico"]:$this->db17_cadattdinamico);
     }else{
       $this->db17_sequencial = ($this->db17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db17_sequencial"]:$this->db17_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db17_sequencial){
      $this->atualizacampos();
     if($this->db17_sysarquivo == null ){
       $this->erro_sql = " Campo Código Tabela não informado.";
       $this->erro_campo = "db17_sysarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db17_cadattdinamico == null ){
       $this->erro_sql = " Campo Código Atributos não informado.";
       $this->erro_campo = "db17_cadattdinamico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db17_sequencial == "" || $db17_sequencial == null ){
       $result = db_query("select nextval('db_cadattdinamicosysarquivo_db17_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cadattdinamicosysarquivo_db17_sequencial_seq do campo: db17_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->db17_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from db_cadattdinamicosysarquivo_db17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db17_sequencial)){
         $this->erro_sql = " Campo db17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db17_sequencial = $db17_sequencial;
       }
     }
     if(($this->db17_sequencial == null) || ($this->db17_sequencial == "") ){
       $this->erro_sql = " Campo db17_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cadattdinamicosysarquivo(
                                       db17_sequencial
                                      ,db17_sysarquivo
                                      ,db17_cadattdinamico
                       )
                values (
                                $this->db17_sequencial
                               ,$this->db17_sysarquivo
                               ,$this->db17_cadattdinamico
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atributos Dinâmicos Sysarquivo ($this->db17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atributos Dinâmicos Sysarquivo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atributos Dinâmicos Sysarquivo ($this->db17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db17_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21706,'$this->db17_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3900,21706,'','".AddSlashes(pg_result($resaco,0,'db17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3900,21707,'','".AddSlashes(pg_result($resaco,0,'db17_sysarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3900,21708,'','".AddSlashes(pg_result($resaco,0,'db17_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($db17_sequencial=null) {
      $this->atualizacampos();
     $sql = " update db_cadattdinamicosysarquivo set ";
     $virgula = "";
     if(trim($this->db17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db17_sequencial"])){
       $sql  .= $virgula." db17_sequencial = $this->db17_sequencial ";
       $virgula = ",";
       if(trim($this->db17_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "db17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db17_sysarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db17_sysarquivo"])){
       $sql  .= $virgula." db17_sysarquivo = $this->db17_sysarquivo ";
       $virgula = ",";
       if(trim($this->db17_sysarquivo) == null ){
         $this->erro_sql = " Campo Código Tabela não informado.";
         $this->erro_campo = "db17_sysarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db17_cadattdinamico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db17_cadattdinamico"])){
       $sql  .= $virgula." db17_cadattdinamico = $this->db17_cadattdinamico ";
       $virgula = ",";
       if(trim($this->db17_cadattdinamico) == null ){
         $this->erro_sql = " Campo Código Atributos não informado.";
         $this->erro_campo = "db17_cadattdinamico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db17_sequencial!=null){
       $sql .= " db17_sequencial = $this->db17_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db17_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21706,'$this->db17_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db17_sequencial"]) || $this->db17_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3900,21706,'".AddSlashes(pg_result($resaco,$conresaco,'db17_sequencial'))."','$this->db17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db17_sysarquivo"]) || $this->db17_sysarquivo != "")
             $resac = db_query("insert into db_acount values($acount,3900,21707,'".AddSlashes(pg_result($resaco,$conresaco,'db17_sysarquivo'))."','$this->db17_sysarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db17_cadattdinamico"]) || $this->db17_cadattdinamico != "")
             $resac = db_query("insert into db_acount values($acount,3900,21708,'".AddSlashes(pg_result($resaco,$conresaco,'db17_cadattdinamico'))."','$this->db17_cadattdinamico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atributos Dinâmicos Sysarquivo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Atributos Dinâmicos Sysarquivo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($db17_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db17_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21706,'$db17_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3900,21706,'','".AddSlashes(pg_result($resaco,$iresaco,'db17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3900,21707,'','".AddSlashes(pg_result($resaco,$iresaco,'db17_sysarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3900,21708,'','".AddSlashes(pg_result($resaco,$iresaco,'db17_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_cadattdinamicosysarquivo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db17_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db17_sequencial = $db17_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atributos Dinâmicos Sysarquivo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Atributos Dinâmicos Sysarquivo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_cadattdinamicosysarquivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($db17_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from db_cadattdinamicosysarquivo ";
     $sql .= "      inner join db_sysarquivo  on  db_sysarquivo.codarq = db_cadattdinamicosysarquivo.db17_sysarquivo";
     $sql .= "      inner join db_cadattdinamico  on  db_cadattdinamico.db118_sequencial = db_cadattdinamicosysarquivo.db17_cadattdinamico";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db17_sequencial)) {
         $sql2 .= " where db_cadattdinamicosysarquivo.db17_sequencial = $db17_sequencial ";
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
   public function sql_query_file ($db17_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_cadattdinamicosysarquivo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db17_sequencial)){
         $sql2 .= " where db_cadattdinamicosysarquivo.db17_sequencial = $db17_sequencial ";
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
