<?
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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_sysregrasacessoip
class cl_db_sysregrasacessoip {
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
   var $db48_idacesso = 0;
   var $db48_ip = null;
   var $db48_tokenpublico = null;
   var $db48_tokenprivado = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db48_idacesso = int4 = Código da Regra 
                 db48_ip = varchar(40) = Máscara do IP 
                 db48_tokenpublico = varchar(64) = Token Publico 
                 db48_tokenprivado = varchar(64) = Token Privado 
                 ";
   //funcao construtor da classe
   function cl_db_sysregrasacessoip() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysregrasacessoip");
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
       $this->db48_idacesso = ($this->db48_idacesso == ""?@$GLOBALS["HTTP_POST_VARS"]["db48_idacesso"]:$this->db48_idacesso);
       $this->db48_ip = ($this->db48_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["db48_ip"]:$this->db48_ip);
       $this->db48_tokenpublico = ($this->db48_tokenpublico == ""?@$GLOBALS["HTTP_POST_VARS"]["db48_tokenpublico"]:$this->db48_tokenpublico);
       $this->db48_tokenprivado = ($this->db48_tokenprivado == ""?@$GLOBALS["HTTP_POST_VARS"]["db48_tokenprivado"]:$this->db48_tokenprivado);
     }else{
       $this->db48_idacesso = ($this->db48_idacesso == ""?@$GLOBALS["HTTP_POST_VARS"]["db48_idacesso"]:$this->db48_idacesso);
     }
   }
   // funcao para Inclusão
   function incluir ($db48_idacesso){
      $this->atualizacampos();
     if($this->db48_ip == null ){
       $this->erro_sql = " Campo Máscara do IP não informado.";
       $this->erro_campo = "db48_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->db48_idacesso = $db48_idacesso;
     if(($this->db48_idacesso == null) || ($this->db48_idacesso == "") ){
       $this->erro_sql = " Campo db48_idacesso não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysregrasacessoip(
                                       db48_idacesso 
                                      ,db48_ip 
                                      ,db48_tokenpublico 
                                      ,db48_tokenprivado 
                       )
                values (
                                $this->db48_idacesso 
                               ,'$this->db48_ip' 
                               ,'$this->db48_tokenpublico' 
                               ,'$this->db48_tokenprivado' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro da Mascara (IP) de Acesso ($this->db48_idacesso) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro da Mascara (IP) de Acesso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro da Mascara (IP) de Acesso ($this->db48_idacesso) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db48_idacesso;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db48_idacesso  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10270,'$this->db48_idacesso','I')");
         $resac = db_query("insert into db_acount values($acount,1774,10270,'','".AddSlashes(pg_result($resaco,0,'db48_idacesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1774,10271,'','".AddSlashes(pg_result($resaco,0,'db48_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1774,1009250,'','".AddSlashes(pg_result($resaco,0,'db48_tokenpublico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1774,1009248,'','".AddSlashes(pg_result($resaco,0,'db48_tokenprivado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($db48_idacesso=null) {
      $this->atualizacampos();
     $sql = " update db_sysregrasacessoip set ";
     $virgula = "";
     if(trim($this->db48_idacesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db48_idacesso"])){
       $sql  .= $virgula." db48_idacesso = $this->db48_idacesso ";
       $virgula = ",";
       if(trim($this->db48_idacesso) == null ){
         $this->erro_sql = " Campo Código da Regra não informado.";
         $this->erro_campo = "db48_idacesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db48_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db48_ip"])){
       $sql  .= $virgula." db48_ip = '$this->db48_ip' ";
       $virgula = ",";
       if(trim($this->db48_ip) == null ){
         $this->erro_sql = " Campo Máscara do IP não informado.";
         $this->erro_campo = "db48_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db48_tokenpublico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db48_tokenpublico"])){
       $sql  .= $virgula." db48_tokenpublico = '$this->db48_tokenpublico' ";
       $virgula = ",";
     }
     if(trim($this->db48_tokenprivado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db48_tokenprivado"])){
       $sql  .= $virgula." db48_tokenprivado = '$this->db48_tokenprivado' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db48_idacesso!=null){
       $sql .= " db48_idacesso = $this->db48_idacesso";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db48_idacesso));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,10270,'$this->db48_idacesso','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db48_idacesso"]) || $this->db48_idacesso != "")
             $resac = db_query("insert into db_acount values($acount,1774,10270,'".AddSlashes(pg_result($resaco,$conresaco,'db48_idacesso'))."','$this->db48_idacesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db48_ip"]) || $this->db48_ip != "")
             $resac = db_query("insert into db_acount values($acount,1774,10271,'".AddSlashes(pg_result($resaco,$conresaco,'db48_ip'))."','$this->db48_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db48_tokenpublico"]) || $this->db48_tokenpublico != "")
             $resac = db_query("insert into db_acount values($acount,1774,1009250,'".AddSlashes(pg_result($resaco,$conresaco,'db48_tokenpublico'))."','$this->db48_tokenpublico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db48_tokenprivado"]) || $this->db48_tokenprivado != "")
             $resac = db_query("insert into db_acount values($acount,1774,1009248,'".AddSlashes(pg_result($resaco,$conresaco,'db48_tokenprivado'))."','$this->db48_tokenprivado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da Mascara (IP) de Acesso não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db48_idacesso;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da Mascara (IP) de Acesso não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db48_idacesso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db48_idacesso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($db48_idacesso=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db48_idacesso));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,10270,'$db48_idacesso','E')");
           $resac  = db_query("insert into db_acount values($acount,1774,10270,'','".AddSlashes(pg_result($resaco,$iresaco,'db48_idacesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1774,10271,'','".AddSlashes(pg_result($resaco,$iresaco,'db48_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1774,1009250,'','".AddSlashes(pg_result($resaco,$iresaco,'db48_tokenpublico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1774,1009248,'','".AddSlashes(pg_result($resaco,$iresaco,'db48_tokenprivado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_sysregrasacessoip
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db48_idacesso)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db48_idacesso = $db48_idacesso ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da Mascara (IP) de Acesso não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db48_idacesso;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da Mascara (IP) de Acesso não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db48_idacesso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$db48_idacesso;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysregrasacessoip";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db48_idacesso=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= "  from db_sysregrasacessoip ";
     $sql .= "      inner join db_sysregrasacesso  on  db_sysregrasacesso.db46_idacesso = db_sysregrasacessoip.db48_idacesso";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_sysregrasacesso.db46_id_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db48_idacesso)) {
         $sql2 .= " where db_sysregrasacessoip.db48_idacesso = $db48_idacesso ";
       }
     } else if (!empty($dbwhere)) {
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
   function sql_query_file ( $db48_idacesso=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= "  from db_sysregrasacessoip ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db48_idacesso)){
         $sql2 .= " where db_sysregrasacessoip.db48_idacesso = $db48_idacesso ";
       }
     } else if (!empty($dbwhere)) {
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
