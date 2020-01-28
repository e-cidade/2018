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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE txossoariojazigo
class cl_txossoariojazigo {
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
   var $cm30_i_codigo = 0;
   var $cm30_i_ossoariojazigo = 0;
   var $cm30_i_itenserv = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm30_i_codigo = int4 = Taxa Ossário/ Jazigo
                 cm30_i_ossoariojazigo = int4 = Código Ossário/ Jazigo
                 cm30_i_itenserv = int4 = Código Item Serviço
                 ";
   //funcao construtor da classe
   function cl_txossoariojazigo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("txossoariojazigo");
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
       $this->cm30_i_codigo = ($this->cm30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm30_i_codigo"]:$this->cm30_i_codigo);
       $this->cm30_i_ossoariojazigo = ($this->cm30_i_ossoariojazigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm30_i_ossoariojazigo"]:$this->cm30_i_ossoariojazigo);
       $this->cm30_i_itenserv = ($this->cm30_i_itenserv == ""?@$GLOBALS["HTTP_POST_VARS"]["cm30_i_itenserv"]:$this->cm30_i_itenserv);
     }else{
       $this->cm30_i_codigo = ($this->cm30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm30_i_codigo"]:$this->cm30_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm30_i_codigo){
      $this->atualizacampos();
     if($this->cm30_i_ossoariojazigo == null ){
       $this->erro_sql = " Campo Código Ossário/ Jazigo nao Informado.";
       $this->erro_campo = "cm30_i_ossoariojazigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm30_i_itenserv == null ){
       $this->erro_sql = " Campo Código Item Serviço nao Informado.";
       $this->erro_campo = "cm30_i_itenserv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm30_i_codigo == "" || $cm30_i_codigo == null ){
       $result = db_query("select nextval('txossoariojazigo_cm30_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: txossoariojazigo_cm30_i_codigo_seq do campo: cm30_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm30_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from txossoariojazigo_cm30_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm30_i_codigo)){
         $this->erro_sql = " Campo cm30_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm30_i_codigo = $cm30_i_codigo;
       }
     }
     if(($this->cm30_i_codigo == null) || ($this->cm30_i_codigo == "") ){
       $this->erro_sql = " Campo cm30_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into txossoariojazigo(
                                       cm30_i_codigo
                                      ,cm30_i_ossoariojazigo
                                      ,cm30_i_itenserv
                       )
                values (
                                $this->cm30_i_codigo
                               ,$this->cm30_i_ossoariojazigo
                               ,$this->cm30_i_itenserv
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Taxa dos Ossários/ Jazigo ($this->cm30_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Taxa dos Ossários/ Jazigo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Taxa dos Ossários/ Jazigo ($this->cm30_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm30_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm30_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10449,'$this->cm30_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1807,10449,'','".AddSlashes(pg_result($resaco,0,'cm30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1807,10455,'','".AddSlashes(pg_result($resaco,0,'cm30_i_ossoariojazigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1807,10457,'','".AddSlashes(pg_result($resaco,0,'cm30_i_itenserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm30_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update txossoariojazigo set ";
     $virgula = "";
     if(trim($this->cm30_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm30_i_codigo"])){
       $sql  .= $virgula." cm30_i_codigo = $this->cm30_i_codigo ";
       $virgula = ",";
       if(trim($this->cm30_i_codigo) == null ){
         $this->erro_sql = " Campo Taxa Ossário/ Jazigo nao Informado.";
         $this->erro_campo = "cm30_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm30_i_ossoariojazigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm30_i_ossoariojazigo"])){
       $sql  .= $virgula." cm30_i_ossoariojazigo = $this->cm30_i_ossoariojazigo ";
       $virgula = ",";
       if(trim($this->cm30_i_ossoariojazigo) == null ){
         $this->erro_sql = " Campo Código Ossário/ Jazigo nao Informado.";
         $this->erro_campo = "cm30_i_ossoariojazigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm30_i_itenserv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm30_i_itenserv"])){
       $sql  .= $virgula." cm30_i_itenserv = $this->cm30_i_itenserv ";
       $virgula = ",";
       if(trim($this->cm30_i_itenserv) == null ){
         $this->erro_sql = " Campo Código Item Serviço nao Informado.";
         $this->erro_campo = "cm30_i_itenserv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cm30_i_codigo!=null){
       $sql .= " cm30_i_codigo = $this->cm30_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm30_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10449,'$this->cm30_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm30_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1807,10449,'".AddSlashes(pg_result($resaco,$conresaco,'cm30_i_codigo'))."','$this->cm30_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm30_i_ossoariojazigo"]))
           $resac = db_query("insert into db_acount values($acount,1807,10455,'".AddSlashes(pg_result($resaco,$conresaco,'cm30_i_ossoariojazigo'))."','$this->cm30_i_ossoariojazigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm30_i_itenserv"]))
           $resac = db_query("insert into db_acount values($acount,1807,10457,'".AddSlashes(pg_result($resaco,$conresaco,'cm30_i_itenserv'))."','$this->cm30_i_itenserv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Taxa dos Ossários/ Jazigo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Taxa dos Ossários/ Jazigo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm30_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm30_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10449,'$cm30_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1807,10449,'','".AddSlashes(pg_result($resaco,$iresaco,'cm30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1807,10455,'','".AddSlashes(pg_result($resaco,$iresaco,'cm30_i_ossoariojazigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1807,10457,'','".AddSlashes(pg_result($resaco,$iresaco,'cm30_i_itenserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from txossoariojazigo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm30_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm30_i_codigo = $cm30_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Taxa dos Ossários/ Jazigo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Taxa dos Ossários/ Jazigo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm30_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:txossoariojazigo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm30_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from txossoariojazigo ";
     $sql .= "      inner join itenserv  on  itenserv.cm10_i_codigo = txossoariojazigo.cm30_i_itenserv";
     $sql .= "      inner join ossoariojazigo  on  ossoariojazigo.cm25_i_codigo = txossoariojazigo.cm30_i_ossoariojazigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itenserv.cm10_i_usuario";
     $sql .= "      inner join taxaserv  on  taxaserv.cm11_i_codigo = itenserv.cm10_i_taxaserv";
     $sql .= "      inner join lotecemit  on  lotecemit.cm23_i_codigo = ossoariojazigo.cm25_i_lotecemit";
     $sql2 = "";
     if($dbwhere==""){
       if($cm30_i_codigo!=null ){
         $sql2 .= " where txossoariojazigo.cm30_i_codigo = $cm30_i_codigo ";
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
   function sql_query_file ( $cm30_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from txossoariojazigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm30_i_codigo!=null ){
         $sql2 .= " where txossoariojazigo.cm30_i_codigo = $cm30_i_codigo ";
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