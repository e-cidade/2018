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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE cadconvenio
class cl_cadconvenio {
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
   var $ar11_sequencial = 0;
   var $ar11_cadtipoconvenio = 0;
   var $ar11_instit = 0;
   var $ar11_nome = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ar11_sequencial = int4 = Sequêncial
                 ar11_cadtipoconvenio = int4 = Tipo de convênio
                 ar11_instit = int4 = Instituição
                 ar11_nome = varchar(50) = Nome
                 ";
   //funcao construtor da classe
   function cl_cadconvenio() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadconvenio");
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
       $this->ar11_sequencial = ($this->ar11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar11_sequencial"]:$this->ar11_sequencial);
       $this->ar11_cadtipoconvenio = ($this->ar11_cadtipoconvenio == ""?@$GLOBALS["HTTP_POST_VARS"]["ar11_cadtipoconvenio"]:$this->ar11_cadtipoconvenio);
       $this->ar11_instit = ($this->ar11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ar11_instit"]:$this->ar11_instit);
       $this->ar11_nome = ($this->ar11_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ar11_nome"]:$this->ar11_nome);
     }else{
       $this->ar11_sequencial = ($this->ar11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar11_sequencial"]:$this->ar11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar11_sequencial){
      $this->atualizacampos();
     if($this->ar11_cadtipoconvenio == null ){
       $this->erro_sql = " Campo Tipo de convênio nao Informado.";
       $this->erro_campo = "ar11_cadtipoconvenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar11_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "ar11_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar11_nome == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "ar11_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar11_sequencial == "" || $ar11_sequencial == null ){
       $result = db_query("select nextval('cadconvenio_ar11_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadconvenio_ar11_sequencial_seq do campo: ar11_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ar11_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from cadconvenio_ar11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar11_sequencial)){
         $this->erro_sql = " Campo ar11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar11_sequencial = $ar11_sequencial;
       }
     }
     if(($this->ar11_sequencial == null) || ($this->ar11_sequencial == "") ){
       $this->erro_sql = " Campo ar11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadconvenio(
                                       ar11_sequencial
                                      ,ar11_cadtipoconvenio
                                      ,ar11_instit
                                      ,ar11_nome
                       )
                values (
                                $this->ar11_sequencial
                               ,$this->ar11_cadtipoconvenio
                               ,$this->ar11_instit
                               ,'$this->ar11_nome'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de convênio ($this->ar11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de convênio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de convênio ($this->ar11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12523,'$this->ar11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2185,12523,'','".AddSlashes(pg_result($resaco,0,'ar11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2185,12524,'','".AddSlashes(pg_result($resaco,0,'ar11_cadtipoconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2185,12525,'','".AddSlashes(pg_result($resaco,0,'ar11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2185,12526,'','".AddSlashes(pg_result($resaco,0,'ar11_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ar11_sequencial=null) {
      $this->atualizacampos();
     $sql = " update cadconvenio set ";
     $virgula = "";
     if(trim($this->ar11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar11_sequencial"])){
       $sql  .= $virgula." ar11_sequencial = $this->ar11_sequencial ";
       $virgula = ",";
       if(trim($this->ar11_sequencial) == null ){
         $this->erro_sql = " Campo Sequêncial nao Informado.";
         $this->erro_campo = "ar11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar11_cadtipoconvenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar11_cadtipoconvenio"])){
       $sql  .= $virgula." ar11_cadtipoconvenio = $this->ar11_cadtipoconvenio ";
       $virgula = ",";
       if(trim($this->ar11_cadtipoconvenio) == null ){
         $this->erro_sql = " Campo Tipo de convênio nao Informado.";
         $this->erro_campo = "ar11_cadtipoconvenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar11_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar11_instit"])){
       $sql  .= $virgula." ar11_instit = $this->ar11_instit ";
       $virgula = ",";
       if(trim($this->ar11_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "ar11_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar11_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar11_nome"])){
       $sql  .= $virgula." ar11_nome = '$this->ar11_nome' ";
       $virgula = ",";
       if(trim($this->ar11_nome) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "ar11_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar11_sequencial!=null){
       $sql .= " ar11_sequencial = $this->ar11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12523,'$this->ar11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar11_sequencial"]) || $this->ar11_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2185,12523,'".AddSlashes(pg_result($resaco,$conresaco,'ar11_sequencial'))."','$this->ar11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar11_cadtipoconvenio"]) || $this->ar11_cadtipoconvenio != "")
           $resac = db_query("insert into db_acount values($acount,2185,12524,'".AddSlashes(pg_result($resaco,$conresaco,'ar11_cadtipoconvenio'))."','$this->ar11_cadtipoconvenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar11_instit"]) || $this->ar11_instit != "")
           $resac = db_query("insert into db_acount values($acount,2185,12525,'".AddSlashes(pg_result($resaco,$conresaco,'ar11_instit'))."','$this->ar11_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar11_nome"]) || $this->ar11_nome != "")
           $resac = db_query("insert into db_acount values($acount,2185,12526,'".AddSlashes(pg_result($resaco,$conresaco,'ar11_nome'))."','$this->ar11_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de convênio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de convênio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ar11_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar11_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12523,'$ar11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2185,12523,'','".AddSlashes(pg_result($resaco,$iresaco,'ar11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2185,12524,'','".AddSlashes(pg_result($resaco,$iresaco,'ar11_cadtipoconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2185,12525,'','".AddSlashes(pg_result($resaco,$iresaco,'ar11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2185,12526,'','".AddSlashes(pg_result($resaco,$iresaco,'ar11_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadconvenio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar11_sequencial = $ar11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de convênio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de convênio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar11_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadconvenio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ar11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cadconvenio ";
     $sql .= "      inner join db_config              on  db_config.codigo = cadconvenio.ar11_instit";
     $sql .= "      inner join cadtipoconvenio        on  cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio";
     $sql .= "      inner join cgm                    on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join cadconveniomodalidade  on  cadconveniomodalidade.ar15_sequencial = cadtipoconvenio.ar12_cadconveniomodalidade";
     $sql .= "       left join db_tipoinstit          on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($ar11_sequencial!=null ){
         $sql2 .= " where cadconvenio.ar11_sequencial = $ar11_sequencial ";
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

  function sql_query_convenio_cobranca($iCodigoConvenio = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = "select {$sCampos} ";
    $sSql .= "  from cadconvenio ";
    $sSql .= "       inner join cadtipoconvenio       on  cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio";
    $sSql .= "       inner join cadconveniomodalidade on  cadconveniomodalidade.ar15_sequencial = cadtipoconvenio.ar12_cadconveniomodalidade";
    $sSql .= "       left  join conveniocobranca      on conveniocobranca.ar13_cadconvenio    =  cadconvenio.ar11_sequencial";


    if (!empty($iCodigoConvenio)) {
      $sWhere = (!empty($sWhere) ? $sWhere . " and " : '') . "cadconvenio.ar11_sequencial = {$iCodigoConvenio}";
    }

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere}";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }

   // funcao do sql
   function sql_query_file ( $ar11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cadconvenio ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar11_sequencial!=null ){
         $sql2 .= " where cadconvenio.ar11_sequencial = $ar11_sequencial ";
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

   function sql_query_arrecad_cobranc ( $ar11_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from cadconvenio ";
     $sql .= "      inner join db_config 		   on db_config.codigo					   =  cadconvenio.ar11_instit     			";
     $sql .= "      left  join convenioarrecadacao on convenioarrecadacao.ar14_cadconvenio =  cadconvenio.ar11_sequencial 			";
     $sql .= "      left  join bancoagencia	a	   on a.db89_sequencial					   =  convenioarrecadacao.ar14_bancoagencia ";
     $sql .= "      left  join conveniocobranca    on conveniocobranca.ar13_cadconvenio    =  cadconvenio.ar11_sequencial 			";
     $sql .= "      left  join bancoagencia	b	   on b.db89_sequencial					   =  conveniocobranca.ar13_bancoagencia 	";


     $sql2 = "";
     if($dbwhere==""){
       if($ar11_sequencial!=null ){
         $sql2 .= " where cadconvenio.ar11_sequencial = $ar11_sequencial ";
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

   function sql_query_arrecadacao( $ar11_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from cadconvenio ";
     $sql .= "      inner join db_config 		   on db_config.codigo					   =  cadconvenio.ar11_instit     			  ";
     $sql .= "      inner join convenioarrecadacao on convenioarrecadacao.ar14_cadconvenio =  cadconvenio.ar11_sequencial 			  ";
     $sql .= "      inner join cadarrecadacao	   on cadarrecadacao.ar16_sequencial       =  convenioarrecadacao.ar14_cadarrecadacao ";
     $sql .= "      inner join bancoagencia	       on bancoagencia.db89_sequencial		   =  convenioarrecadacao.ar14_bancoagencia   ";



     $sql2 = "";
     if($dbwhere==""){
       if($ar11_sequencial!=null ){
         $sql2 .= " where cadconvenio.ar11_sequencial = $ar11_sequencial ";
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

   /**
    * Busca as taxas referente ao codigo do convenio
    *
    * @return string
    */
   function sql_queryTaxasConvenio()
   {
	   	$sSqlTaxas  = "   select taxa.*                                                                ";
	   	$sSqlTaxas .= "     from cadconvenio                                                           ";
	   	$sSqlTaxas .= "          inner join cadconveniogrupotaxa on ar39_cadconvenio = ar11_sequencial ";
	   	$sSqlTaxas .= "          inner join grupotaxa            on ar37_sequencial  = ar39_grupotaxa  ";
	   	$sSqlTaxas .= "          inner join taxa                 on ar36_grupotaxa   = ar37_sequencial ";

	   	return $sSqlTaxas;
   }

   /**
    * Função responsável pela criação da query que consulta os dados de cobrança do convênio
    * @return string
    */
   function sql_queryConvenioCobranca($iSequencialConveio, $sCampos = "*")
   {
     $sSql  = " select {$sCampos}                                                                                     ";
     $sSql .= "   from cadconvenio                                                                                    ";
     $sSql .= "        inner join conveniocobranca on cadconvenio.ar11_sequencial = conveniocobranca.ar13_cadconvenio ";
     $sSql .= "  where ar11_sequencial = {$iSequencialConveio}                                                        ";

     return $sSql;
   }

}
?>
