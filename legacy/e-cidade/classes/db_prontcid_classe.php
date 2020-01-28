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

//MODULO: saude
//CLASSE DA ENTIDADE prontcid
class cl_prontcid {
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
   var $sd55_i_codigo = 0;
   var $sd55_i_prontuario = 0;
   var $sd55_i_cid = 0;
   var $sd55_b_principal = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd55_i_codigo = int4 = Código
                 sd55_i_prontuario = int4 = Prontuário
                 sd55_i_cid = int4 = CID
                 sd55_b_principal = bool = CID Principal
                 ";
   //funcao construtor da classe
   function cl_prontcid() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontcid");
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
       $this->sd55_i_codigo = ($this->sd55_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd55_i_codigo"]:$this->sd55_i_codigo);
       $this->sd55_i_prontuario = ($this->sd55_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd55_i_prontuario"]:$this->sd55_i_prontuario);
       $this->sd55_i_cid = ($this->sd55_i_cid == ""?@$GLOBALS["HTTP_POST_VARS"]["sd55_i_cid"]:$this->sd55_i_cid);
       $this->sd55_b_principal = ($this->sd55_b_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["sd55_b_principal"]:$this->sd55_b_principal);
     }else{
       $this->sd55_i_codigo = ($this->sd55_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd55_i_codigo"]:$this->sd55_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd55_i_codigo){
      $this->atualizacampos();
     if($this->sd55_i_prontuario == null ){
       $this->erro_sql = " Campo Prontuário nao Informado.";
       $this->erro_campo = "sd55_i_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd55_i_cid == null ){
       $this->erro_sql = " Campo CID nao Informado.";
       $this->erro_campo = "sd55_i_cid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd55_b_principal == null ){
       $this->erro_sql = " Campo CID Principal nao Informado.";
       $this->erro_campo = "sd55_b_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd55_i_codigo == "" || $sd55_i_codigo == null ){
       $result = db_query("select nextval('prontcid_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontcid_codigo_seq do campo: sd55_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd55_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from prontcid_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd55_i_codigo)){
         $this->erro_sql = " Campo sd55_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd55_i_codigo = $sd55_i_codigo;
       }
     }
     if(($this->sd55_i_codigo == null) || ($this->sd55_i_codigo == "") ){
       $this->erro_sql = " Campo sd55_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontcid(
                                       sd55_i_codigo
                                      ,sd55_i_prontuario
                                      ,sd55_i_cid
                                      ,sd55_b_principal
                       )
                values (
                                $this->sd55_i_codigo
                               ,$this->sd55_i_prontuario
                               ,$this->sd55_i_cid
                               ,'$this->sd55_b_principal'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prontuário CID ($this->sd55_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prontuário CID já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prontuário CID ($this->sd55_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd55_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd55_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12197,'$this->sd55_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2118,12197,'','".AddSlashes(pg_result($resaco,0,'sd55_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2118,12198,'','".AddSlashes(pg_result($resaco,0,'sd55_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2118,12199,'','".AddSlashes(pg_result($resaco,0,'sd55_i_cid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2118,12216,'','".AddSlashes(pg_result($resaco,0,'sd55_b_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($sd55_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update prontcid set ";
     $virgula = "";
     if(trim($this->sd55_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd55_i_codigo"])){
       $sql  .= $virgula." sd55_i_codigo = $this->sd55_i_codigo ";
       $virgula = ",";
       if(trim($this->sd55_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd55_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd55_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd55_i_prontuario"])){
       $sql  .= $virgula." sd55_i_prontuario = $this->sd55_i_prontuario ";
       $virgula = ",";
       if(trim($this->sd55_i_prontuario) == null ){
         $this->erro_sql = " Campo Prontuário nao Informado.";
         $this->erro_campo = "sd55_i_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd55_i_cid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd55_i_cid"])){
       $sql  .= $virgula." sd55_i_cid = $this->sd55_i_cid ";
       $virgula = ",";
       if(trim($this->sd55_i_cid) == null ){
         $this->erro_sql = " Campo CID nao Informado.";
         $this->erro_campo = "sd55_i_cid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd55_b_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd55_b_principal"])){
       $sql  .= $virgula." sd55_b_principal = '$this->sd55_b_principal' ";
       $virgula = ",";
       if(trim($this->sd55_b_principal) == null ){
         $this->erro_sql = " Campo CID Principal nao Informado.";
         $this->erro_campo = "sd55_b_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd55_i_codigo!=null){
       $sql .= " sd55_i_codigo = $this->sd55_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd55_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12197,'$this->sd55_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd55_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2118,12197,'".AddSlashes(pg_result($resaco,$conresaco,'sd55_i_codigo'))."','$this->sd55_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd55_i_prontuario"]))
           $resac = db_query("insert into db_acount values($acount,2118,12198,'".AddSlashes(pg_result($resaco,$conresaco,'sd55_i_prontuario'))."','$this->sd55_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd55_i_cid"]))
           $resac = db_query("insert into db_acount values($acount,2118,12199,'".AddSlashes(pg_result($resaco,$conresaco,'sd55_i_cid'))."','$this->sd55_i_cid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd55_b_principal"]))
           $resac = db_query("insert into db_acount values($acount,2118,12216,'".AddSlashes(pg_result($resaco,$conresaco,'sd55_b_principal'))."','$this->sd55_b_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuário CID nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd55_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuário CID nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd55_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd55_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($sd55_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd55_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12197,'$sd55_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2118,12197,'','".AddSlashes(pg_result($resaco,$iresaco,'sd55_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2118,12198,'','".AddSlashes(pg_result($resaco,$iresaco,'sd55_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2118,12199,'','".AddSlashes(pg_result($resaco,$iresaco,'sd55_i_cid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2118,12216,'','".AddSlashes(pg_result($resaco,$iresaco,'sd55_b_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prontcid
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd55_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd55_i_codigo = $sd55_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuário CID nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd55_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuário CID nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd55_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd55_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:prontcid";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd55_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from prontcid ";
     $sql .= "      inner join sau_cid  on  sau_cid.sd70_i_codigo = prontcid.sd55_i_cid";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontcid.sd55_i_prontuario";
     $sql2 = "";
     if($dbwhere==""){
       if($sd55_i_codigo!=null ){
         $sql2 .= " where prontcid.sd55_i_codigo = $sd55_i_codigo ";
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
   function sql_query_file ( $sd55_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from prontcid ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd55_i_codigo!=null ){
         $sql2 .= " where prontcid.sd55_i_codigo = $sd55_i_codigo ";
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
?>