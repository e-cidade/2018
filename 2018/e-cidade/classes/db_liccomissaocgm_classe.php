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

//MODULO: licitação
//CLASSE DA ENTIDADE liccomissaocgm
class cl_liccomissaocgm {
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
   var $l31_codigo = 0;
   var $l31_liccomissao = 0;
   var $l31_numcgm = 0;
   var $l31_tipo = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 l31_codigo = int4 = Código Sequencial
                 l31_liccomissao = int4 = Código Sequencial da comissão
                 l31_numcgm = int4 = Numcgm Participante da Comissão
                 l31_tipo = char(1) = Tipo
                 ";
   //funcao construtor da classe
   function cl_liccomissaocgm() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liccomissaocgm");
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
       $this->l31_codigo = ($this->l31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l31_codigo"]:$this->l31_codigo);
       $this->l31_liccomissao = ($this->l31_liccomissao == ""?@$GLOBALS["HTTP_POST_VARS"]["l31_liccomissao"]:$this->l31_liccomissao);
       $this->l31_numcgm = ($this->l31_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["l31_numcgm"]:$this->l31_numcgm);
       $this->l31_tipo = ($this->l31_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["l31_tipo"]:$this->l31_tipo);
     }else{
       $this->l31_codigo = ($this->l31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l31_codigo"]:$this->l31_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l31_codigo){
      $this->atualizacampos();
     if($this->l31_liccomissao == null ){
       $this->erro_sql = " Campo Código Sequencial da comissão nao Informado.";
       $this->erro_campo = "l31_liccomissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l31_numcgm == null ){
       $this->erro_sql = " Campo Numcgm Participante da Comissão nao Informado.";
       $this->erro_campo = "l31_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l31_tipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "l31_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l31_codigo == "" || $l31_codigo == null ){
       $result = db_query("select nextval('liccomissaocgm_l31_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liccomissaocgm_l31_codigo_seq do campo: l31_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->l31_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from liccomissaocgm_l31_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l31_codigo)){
         $this->erro_sql = " Campo l31_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l31_codigo = $l31_codigo;
       }
     }
     if(($this->l31_codigo == null) || ($this->l31_codigo == "") ){
       $this->erro_sql = " Campo l31_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liccomissaocgm(
                                       l31_codigo
                                      ,l31_liccomissao
                                      ,l31_numcgm
                                      ,l31_tipo
                       )
                values (
                                $this->l31_codigo
                               ,$this->l31_liccomissao
                               ,$this->l31_numcgm
                               ,'$this->l31_tipo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Comissão da Licitação ($this->l31_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Comissão da Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Comissão da Licitação ($this->l31_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l31_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l31_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7905,'$this->l31_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1325,7905,'','".AddSlashes(pg_result($resaco,0,'l31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1325,7906,'','".AddSlashes(pg_result($resaco,0,'l31_liccomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1325,7907,'','".AddSlashes(pg_result($resaco,0,'l31_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1325,7917,'','".AddSlashes(pg_result($resaco,0,'l31_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($l31_codigo=null) {
      $this->atualizacampos();
     $sql = " update liccomissaocgm set ";
     $virgula = "";
     if(trim($this->l31_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l31_codigo"])){
       $sql  .= $virgula." l31_codigo = $this->l31_codigo ";
       $virgula = ",";
       if(trim($this->l31_codigo) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "l31_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l31_liccomissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l31_liccomissao"])){
       $sql  .= $virgula." l31_liccomissao = $this->l31_liccomissao ";
       $virgula = ",";
       if(trim($this->l31_liccomissao) == null ){
         $this->erro_sql = " Campo Código Sequencial da comissão nao Informado.";
         $this->erro_campo = "l31_liccomissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l31_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l31_numcgm"])){
       $sql  .= $virgula." l31_numcgm = $this->l31_numcgm ";
       $virgula = ",";
       if(trim($this->l31_numcgm) == null ){
         $this->erro_sql = " Campo Numcgm Participante da Comissão nao Informado.";
         $this->erro_campo = "l31_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l31_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l31_tipo"])){
       $sql  .= $virgula." l31_tipo = '$this->l31_tipo' ";
       $virgula = ",";
       if(trim($this->l31_tipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "l31_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l31_codigo!=null){
       $sql .= " l31_codigo = $this->l31_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l31_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7905,'$this->l31_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l31_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1325,7905,'".AddSlashes(pg_result($resaco,$conresaco,'l31_codigo'))."','$this->l31_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l31_liccomissao"]))
           $resac = db_query("insert into db_acount values($acount,1325,7906,'".AddSlashes(pg_result($resaco,$conresaco,'l31_liccomissao'))."','$this->l31_liccomissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l31_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1325,7907,'".AddSlashes(pg_result($resaco,$conresaco,'l31_numcgm'))."','$this->l31_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l31_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1325,7917,'".AddSlashes(pg_result($resaco,$conresaco,'l31_tipo'))."','$this->l31_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissão da Licitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l31_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissão da Licitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($l31_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l31_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7905,'$l31_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1325,7905,'','".AddSlashes(pg_result($resaco,$iresaco,'l31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1325,7906,'','".AddSlashes(pg_result($resaco,$iresaco,'l31_liccomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1325,7907,'','".AddSlashes(pg_result($resaco,$iresaco,'l31_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1325,7917,'','".AddSlashes(pg_result($resaco,$iresaco,'l31_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liccomissaocgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l31_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l31_codigo = $l31_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissão da Licitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l31_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissão da Licitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l31_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liccomissaocgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from liccomissaocgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = liccomissaocgm.l31_numcgm";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liccomissaocgm.l31_liccomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($l31_codigo!=null ){
         $sql2 .= " where liccomissaocgm.l31_codigo = $l31_codigo ";
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
   function sql_query_file ( $l31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from liccomissaocgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($l31_codigo!=null ){
         $sql2 .= " where liccomissaocgm.l31_codigo = $l31_codigo ";
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
   * @param  string $sCampos
   * @param  string $sOrdem
   * @param  string $sWhere
   * @return string
   */
  function sql_query_atributos($sCampos = "*", $sOrdem = null, $sWhere = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "  from liccomissaocgm ";
    $sSql .= "       inner join cgm on cgm.z01_numcgm = liccomissaocgm.l31_numcgm ";
    $sSql .= "       inner join liccomissao on liccomissao.l30_codigo = liccomissaocgm.l31_liccomissao ";
    $sSql .= "       left  join liccomissaocgmcadattdinamicovalorgrupo on l31_codigo = l15_liccomissaocgm ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }
}