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
//CLASSE DA ENTIDADE db_sistemaexterno
class cl_db_sistemaexterno {
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
   var $db124_sequencial = 0;
   var $db124_descricao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db124_sequencial = int4 = Código
                 db124_descricao = varchar(50) = Sistema Externo
                 ";
   //funcao construtor da classe
   function cl_db_sistemaexterno() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sistemaexterno");
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
       $this->db124_sequencial = ($this->db124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db124_sequencial"]:$this->db124_sequencial);
       $this->db124_descricao = ($this->db124_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db124_descricao"]:$this->db124_descricao);
     }else{
       $this->db124_sequencial = ($this->db124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db124_sequencial"]:$this->db124_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db124_sequencial){
      $this->atualizacampos();
     if($this->db124_descricao == null ){
       $this->erro_sql = " Campo Sistema Externo nao Informado.";
       $this->erro_campo = "db124_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->db124_sequencial = $db124_sequencial;
     if(($this->db124_sequencial == null) || ($this->db124_sequencial == "") ){
       $this->erro_sql = " Campo db124_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sistemaexterno(
                                       db124_sequencial
                                      ,db124_descricao
                       )
                values (
                                $this->db124_sequencial
                               ,'$this->db124_descricao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "db_sistemaexterno ($this->db124_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "db_sistemaexterno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "db_sistemaexterno ($this->db124_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db124_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db124_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18596,'$this->db124_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3291,18596,'','".AddSlashes(pg_result($resaco,0,'db124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3291,18597,'','".AddSlashes(pg_result($resaco,0,'db124_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($db124_sequencial=null) {
      $this->atualizacampos();
     $sql = " update db_sistemaexterno set ";
     $virgula = "";
     if(trim($this->db124_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db124_sequencial"])){
       $sql  .= $virgula." db124_sequencial = $this->db124_sequencial ";
       $virgula = ",";
       if(trim($this->db124_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db124_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db124_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db124_descricao"])){
       $sql  .= $virgula." db124_descricao = '$this->db124_descricao' ";
       $virgula = ",";
       if(trim($this->db124_descricao) == null ){
         $this->erro_sql = " Campo Sistema Externo nao Informado.";
         $this->erro_campo = "db124_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db124_sequencial!=null){
       $sql .= " db124_sequencial = $this->db124_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db124_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18596,'$this->db124_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db124_sequencial"]) || $this->db124_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3291,18596,'".AddSlashes(pg_result($resaco,$conresaco,'db124_sequencial'))."','$this->db124_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db124_descricao"]) || $this->db124_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3291,18597,'".AddSlashes(pg_result($resaco,$conresaco,'db124_descricao'))."','$this->db124_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_sistemaexterno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db124_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_sistemaexterno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($db124_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db124_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18596,'$db124_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3291,18596,'','".AddSlashes(pg_result($resaco,$iresaco,'db124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3291,18597,'','".AddSlashes(pg_result($resaco,$iresaco,'db124_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sistemaexterno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db124_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db124_sequencial = $db124_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_sistemaexterno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db124_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_sistemaexterno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db124_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sistemaexterno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $db124_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_sistemaexterno ";
     $sql2 = "";
     if($dbwhere==""){
       if($db124_sequencial!=null ){
         $sql2 .= " where db_sistemaexterno.db124_sequencial = $db124_sequencial ";
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
   // funcao do sql
   function sql_query_file ( $db124_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_sistemaexterno ";
     $sql2 = "";
     if($dbwhere==""){
       if($db124_sequencial!=null ){
         $sql2 .= " where db_sistemaexterno.db124_sequencial = $db124_sequencial ";
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
       * função que retrnara o codigo do sistema externo do municipio
       *
       * @param $iCodigoSistema integer : codigo do sistema, na tabela db_sistemaexterno (sinpas = 1)
       * @param $iNumCgm        integer : numero do cgm para buscar o codigo do municipio
       *
       */
      function getCodigoSistemaExternoMunic($iCodigoSistema, $iNumCgm){

        $sSqlSistema  = "select db125_codigosistema::integer from cgm                                                  \n";
        $sSqlSistema .= "  left join cgmendereco               on z01_numcgm          = z07_numcgm                     \n";
        $sSqlSistema .= "  left join endereco                  on z07_endereco        = db76_sequencial                \n";
        $sSqlSistema .= "  left join cadenderlocal             on db75_sequencial     = db76_cadenderlocal             \n";
        $sSqlSistema .= "  left join cadenderbairrocadenderrua on db87_sequencial     = db75_cadenderbairrocadenderrua \n";
        $sSqlSistema .= "  left join cadenderbairro            on db87_cadenderbairro = db73_sequencial                \n";
        $sSqlSistema .= "  left join cadendermunicipio         on db72_sequencial     = db73_cadendermunicipio         \n";
        $sSqlSistema .= "  left join cadenderestado            on db71_sequencial     = db72_cadenderestado            \n";
        $sSqlSistema .= "  left join cadendermunicipiosistema  on db72_sequencial     = db125_cadendermunicipio        \n";
        $sSqlSistema .= "                                     and db125_db_sistemaexterno = {$iCodigoSistema}          \n";
        $sSqlSistema .= "  where z01_numcgm = {$iNumCgm}                                                               \n";
        $sSqlSistema .= "    and db125_codigosistema is not null                                                       \n";
        $sSqlSistema .= "  limit 1                                                                                     \n";
        $rsSistema      = db_query($sSqlSistema);

        $oCodigoSistema = db_utils::fieldsMemory($rsSistema, 0);
        $iCodigoSistema = $oCodigoSistema->db125_codigosistema;

        return $iCodigoSistema;
      }

}