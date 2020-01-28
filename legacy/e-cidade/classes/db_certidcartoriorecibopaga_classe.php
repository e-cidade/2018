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
//CLASSE DA ENTIDADE certidcartoriorecibopaga
class cl_certidcartoriorecibopaga {
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
   var $v33_sequencial = 0;
   var $v33_certidcartorio = 0;
   var $v33_numnov = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v33_sequencial = int4 = Código do Recibo da Certidão
                 v33_certidcartorio = int4 = Código Certidão Cartório
                 v33_numnov = int4 = Numpre do Recibo da Certidão
                 ";
   //funcao construtor da classe
   function cl_certidcartoriorecibopaga() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidcartoriorecibopaga");
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
       $this->v33_sequencial = ($this->v33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v33_sequencial"]:$this->v33_sequencial);
       $this->v33_certidcartorio = ($this->v33_certidcartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["v33_certidcartorio"]:$this->v33_certidcartorio);
       $this->v33_numnov = ($this->v33_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["v33_numnov"]:$this->v33_numnov);
     }else{
       $this->v33_sequencial = ($this->v33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v33_sequencial"]:$this->v33_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($v33_sequencial){
      $this->atualizacampos();
     if($this->v33_certidcartorio == null ){
       $this->erro_sql = " Campo Código Certidão Cartório não informado.";
       $this->erro_campo = "v33_certidcartorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v33_numnov == null ){
       $this->erro_sql = " Campo Numpre do Recibo da Certidão não informado.";
       $this->erro_campo = "v33_numnov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v33_sequencial == "" || $v33_sequencial == null ){
       $result = db_query("select nextval('certidcartoriorecibopaga_v33_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidcartoriorecibopaga_v33_sequencial_seq do campo: v33_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v33_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from certidcartoriorecibopaga_v33_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v33_sequencial)){
         $this->erro_sql = " Campo v33_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v33_sequencial = $v33_sequencial;
       }
     }
     if(($this->v33_sequencial == null) || ($this->v33_sequencial == "") ){
       $this->erro_sql = " Campo v33_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidcartoriorecibopaga(
                                       v33_sequencial
                                      ,v33_certidcartorio
                                      ,v33_numnov
                       )
                values (
                                $this->v33_sequencial
                               ,$this->v33_certidcartorio
                               ,$this->v33_numnov
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Recibo da Certidão ($this->v33_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Recibo da Certidão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Recibo da Certidão ($this->v33_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v33_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v33_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21244,'$this->v33_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3828,21244,'','".AddSlashes(pg_result($resaco,0,'v33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3828,21245,'','".AddSlashes(pg_result($resaco,0,'v33_certidcartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3828,21248,'','".AddSlashes(pg_result($resaco,0,'v33_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($v33_sequencial=null) {
      $this->atualizacampos();
     $sql = " update certidcartoriorecibopaga set ";
     $virgula = "";
     if(trim($this->v33_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v33_sequencial"])){
       $sql  .= $virgula." v33_sequencial = $this->v33_sequencial ";
       $virgula = ",";
       if(trim($this->v33_sequencial) == null ){
         $this->erro_sql = " Campo Código do Recibo da Certidão não informado.";
         $this->erro_campo = "v33_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v33_certidcartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v33_certidcartorio"])){
       $sql  .= $virgula." v33_certidcartorio = $this->v33_certidcartorio ";
       $virgula = ",";
       if(trim($this->v33_certidcartorio) == null ){
         $this->erro_sql = " Campo Código Certidão Cartório não informado.";
         $this->erro_campo = "v33_certidcartorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v33_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v33_numnov"])){
       $sql  .= $virgula." v33_numnov = $this->v33_numnov ";
       $virgula = ",";
       if(trim($this->v33_numnov) == null ){
         $this->erro_sql = " Campo Numpre do Recibo da Certidão não informado.";
         $this->erro_campo = "v33_numnov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v33_sequencial!=null){
       $sql .= " v33_sequencial = $this->v33_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v33_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21244,'$this->v33_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v33_sequencial"]) || $this->v33_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3828,21244,'".AddSlashes(pg_result($resaco,$conresaco,'v33_sequencial'))."','$this->v33_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v33_certidcartorio"]) || $this->v33_certidcartorio != "")
             $resac = db_query("insert into db_acount values($acount,3828,21245,'".AddSlashes(pg_result($resaco,$conresaco,'v33_certidcartorio'))."','$this->v33_certidcartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v33_numnov"]) || $this->v33_numnov != "")
             $resac = db_query("insert into db_acount values($acount,3828,21248,'".AddSlashes(pg_result($resaco,$conresaco,'v33_numnov'))."','$this->v33_numnov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibo da Certidão não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Recibo da Certidão não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($v33_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v33_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21244,'$v33_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3828,21244,'','".AddSlashes(pg_result($resaco,$iresaco,'v33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3828,21245,'','".AddSlashes(pg_result($resaco,$iresaco,'v33_certidcartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3828,21248,'','".AddSlashes(pg_result($resaco,$iresaco,'v33_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from certidcartoriorecibopaga
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v33_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v33_sequencial = $v33_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibo da Certidão não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Recibo da Certidão não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v33_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidcartoriorecibopaga";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($v33_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from certidcartoriorecibopaga ";
     $sql .= "      inner join certidcartorio  on  certidcartorio.v31_sequencial = certidcartoriorecibopaga.v33_certidcartorio";
     $sql .= "      inner join certid  on  certid.v13_certid = certidcartorio.v31_certid";
     $sql .= "      inner join cartorio  on  cartorio.v82_sequencial = certidcartorio.v31_cartorio";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v33_sequencial)) {
         $sql2 .= " where certidcartoriorecibopaga.v33_sequencial = $v33_sequencial ";
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
   public function sql_query_file ($v33_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from certidcartoriorecibopaga ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v33_sequencial)){
         $sql2 .= " where certidcartoriorecibopaga.v33_sequencial = $v33_sequencial ";
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
