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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcfontes
class cl_orcfontes {
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
   var $o57_codfon = 0;
   var $o57_anousu = 0;
   var $o57_fonte = null;
   var $o57_descr = null;
   var $o57_finali = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o57_codfon = int4 = Código Fonte
                 o57_anousu = int4 = Exercício
                 o57_fonte = varchar(15) = Fonte da Receita
                 o57_descr = varchar(50) = Descrição
                 o57_finali = text = Finalidade
                 ";
   //funcao construtor da classe
   function cl_orcfontes() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcfontes");
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
       $this->o57_codfon = ($this->o57_codfon == ""?@$GLOBALS["HTTP_POST_VARS"]["o57_codfon"]:$this->o57_codfon);
       $this->o57_anousu = ($this->o57_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o57_anousu"]:$this->o57_anousu);
       $this->o57_fonte = ($this->o57_fonte == ""?@$GLOBALS["HTTP_POST_VARS"]["o57_fonte"]:$this->o57_fonte);
       $this->o57_descr = ($this->o57_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o57_descr"]:$this->o57_descr);
       $this->o57_finali = ($this->o57_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o57_finali"]:$this->o57_finali);
     }else{
       $this->o57_codfon = ($this->o57_codfon == ""?@$GLOBALS["HTTP_POST_VARS"]["o57_codfon"]:$this->o57_codfon);
       $this->o57_anousu = ($this->o57_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o57_anousu"]:$this->o57_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($o57_codfon,$o57_anousu){
      $this->atualizacampos();
     if($this->o57_fonte == null ){
       $this->erro_sql = " Campo Fonte da Receita nao Informado.";
       $this->erro_campo = "o57_fonte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o57_descr == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o57_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o57_codfon = $o57_codfon;
       $this->o57_anousu = $o57_anousu;
     if(($this->o57_codfon == null) || ($this->o57_codfon == "") ){
       $this->erro_sql = " Campo o57_codfon nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o57_anousu == null) || ($this->o57_anousu == "") ){
       $this->erro_sql = " Campo o57_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcfontes(
                                       o57_codfon
                                      ,o57_anousu
                                      ,o57_fonte
                                      ,o57_descr
                                      ,o57_finali
                       )
                values (
                                $this->o57_codfon
                               ,$this->o57_anousu
                               ,'$this->o57_fonte'
                               ,'$this->o57_descr'
                               ,'$this->o57_finali'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fontes da Receita ($this->o57_codfon."-".$this->o57_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fontes da Receita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fontes da Receita ($this->o57_codfon."-".$this->o57_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o57_codfon."-".$this->o57_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o57_codfon,$this->o57_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5275,'$this->o57_codfon','I')");
       $resac = db_query("insert into db_acountkey values($acount,8062,'$this->o57_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,755,5275,'','".AddSlashes(pg_result($resaco,0,'o57_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,755,8062,'','".AddSlashes(pg_result($resaco,0,'o57_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,755,5272,'','".AddSlashes(pg_result($resaco,0,'o57_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,755,5273,'','".AddSlashes(pg_result($resaco,0,'o57_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,755,5274,'','".AddSlashes(pg_result($resaco,0,'o57_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o57_codfon=null,$o57_anousu=null) {
      $this->atualizacampos();
     $sql = " update orcfontes set ";
     $virgula = "";
     if(trim($this->o57_codfon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o57_codfon"])){
       $sql  .= $virgula." o57_codfon = $this->o57_codfon ";
       $virgula = ",";
       if(trim($this->o57_codfon) == null ){
         $this->erro_sql = " Campo Código Fonte nao Informado.";
         $this->erro_campo = "o57_codfon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o57_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o57_anousu"])){
       $sql  .= $virgula." o57_anousu = $this->o57_anousu ";
       $virgula = ",";
       if(trim($this->o57_anousu) == null ){
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o57_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o57_fonte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o57_fonte"])){
       $sql  .= $virgula." o57_fonte = '$this->o57_fonte' ";
       $virgula = ",";
       if(trim($this->o57_fonte) == null ){
         $this->erro_sql = " Campo Fonte da Receita nao Informado.";
         $this->erro_campo = "o57_fonte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o57_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o57_descr"])){
       $sql  .= $virgula." o57_descr = '$this->o57_descr' ";
       $virgula = ",";
       if(trim($this->o57_descr) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o57_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($this->o57_finali) || isset($GLOBALS["HTTP_POST_VARS"]["o57_finali"])){
       $sql  .= $virgula." o57_finali = '$this->o57_finali' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o57_codfon!=null){
       $sql .= " o57_codfon = $this->o57_codfon";
     }
     if($o57_anousu!=null){
       $sql .= " and  o57_anousu = $this->o57_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o57_codfon,$this->o57_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5275,'$this->o57_codfon','A')");
         $resac = db_query("insert into db_acountkey values($acount,8062,'$this->o57_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o57_codfon"]))
           $resac = db_query("insert into db_acount values($acount,755,5275,'".AddSlashes(pg_result($resaco,$conresaco,'o57_codfon'))."','$this->o57_codfon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o57_anousu"]))
           $resac = db_query("insert into db_acount values($acount,755,8062,'".AddSlashes(pg_result($resaco,$conresaco,'o57_anousu'))."','$this->o57_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o57_fonte"]))
           $resac = db_query("insert into db_acount values($acount,755,5272,'".AddSlashes(pg_result($resaco,$conresaco,'o57_fonte'))."','$this->o57_fonte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o57_descr"]))
           $resac = db_query("insert into db_acount values($acount,755,5273,'".AddSlashes(pg_result($resaco,$conresaco,'o57_descr'))."','$this->o57_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o57_finali"]))
           $resac = db_query("insert into db_acount values($acount,755,5274,'".AddSlashes(pg_result($resaco,$conresaco,'o57_finali'))."','$this->o57_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fontes da Receita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o57_codfon."-".$this->o57_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fontes da Receita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o57_codfon."-".$this->o57_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o57_codfon."-".$this->o57_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o57_codfon=null,$o57_anousu=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o57_codfon,$o57_anousu));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5275,'$o57_codfon','E')");
         $resac = db_query("insert into db_acountkey values($acount,8062,'$o57_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,755,5275,'','".AddSlashes(pg_result($resaco,$iresaco,'o57_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,755,8062,'','".AddSlashes(pg_result($resaco,$iresaco,'o57_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,755,5272,'','".AddSlashes(pg_result($resaco,$iresaco,'o57_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,755,5273,'','".AddSlashes(pg_result($resaco,$iresaco,'o57_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,755,5274,'','".AddSlashes(pg_result($resaco,$iresaco,'o57_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcfontes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o57_codfon != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o57_codfon = $o57_codfon ";
        }
        if($o57_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o57_anousu = $o57_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fontes da Receita nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o57_codfon."-".$o57_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fontes da Receita nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o57_codfon."-".$o57_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o57_codfon."-".$o57_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcfontes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o57_codfon=null,$o57_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcfontes ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = orcfontes.o57_codfon and  conplano.c60_anousu = orcfontes.o57_anousu";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join conclass  as a on   a.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  as b on   b.c52_codsis = conplano.c60_codsis";
     $sql2 = "";
     if($dbwhere==""){
       if($o57_codfon!=null ){
         $sql2 .= " where orcfontes.o57_codfon = $o57_codfon ";
       }
       if($o57_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcfontes.o57_anousu = $o57_anousu ";
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
     return analiseQueryPlanoOrcamento($sql);
  }
   function sql_query_file ( $o57_codfon=null,$o57_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcfontes ";
     $sql2 = "";
     if($dbwhere==""){
       if($o57_codfon!=null ){
         $sql2 .= " where orcfontes.o57_codfon = $o57_codfon ";
       }
       if($o57_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcfontes.o57_anousu = $o57_anousu ";
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
   function sql_query_previsao ( $o57_codfon=null,$o57_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcfontes ";
     $sql .= "      inner join conplano        on conplano.c60_codcon = orcfontes.o57_codfon and ";
     $sql .= "                                    conplano.c60_anousu = orcfontes.o57_anousu";
     $sql .= "      inner join conclass        on conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema      on consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join conclass   as a on a.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema as b on b.c52_codsis = conplano.c60_codsis";
     $sql .= "       left join orcreceita on orcreceita.o70_anousu  = conplano.c60_anousu ";
     $sql .= "                            and orcreceita.o70_codfon = conplano.c60_codcon ";
     $sql .= "                            and orcreceita.o70_instit = ".db_getsession("DB_instit");
     $sql2 = "";
     if($dbwhere==""){
       if($o57_codfon!=null ){
         $sql2 .= " where orcfontes.o57_codfon = $o57_codfon ";
       }
       if($o57_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcfontes.o57_anousu = $o57_anousu ";
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
     return analiseQueryPlanoOrcamento($sql);
  }
   function db_verifica_fonte_exclusao($elemento,$anousu=null){
   	if ($anousu==null){
   	     $anousu = db_getsessoin("DB_anousu");
   	}
    $nivel = db_le_mae_rec($elemento,true);
    $cod_mae = db_le_mae_rec($elemento,false);
   if($nivel==9){
      return true;
    }
    if($nivel==8){
      $codigo = substr($elemento,0,11);
      $where="substr(o57_fonte,1,11)='$codigo' and substr(o57_fonte,12,2)<>'00' ";
    }
    if($nivel==7){
      $codigo = substr($elemento,0,7);
      $where="substr(o57_fonte,1,9)='$codigo' and substr(o57_fonte,10,4)<>'0000' ";
    }
    if($nivel==6){
      $codigo = substr($elemento,0,7);
      $where="substr(o57_fonte,1,7)='$codigo' and substr(o57_fonte,8,6)<>'000000' ";
    }
    if($nivel==5){
      $codigo = substr($elemento,0,5);
      $where="substr(o57_fonte,1,5)='$codigo' and substr(o57_fonte,6,8)<>'00000000' ";
    }
    if($nivel==4){
      $codigo = substr($elemento,0,4);
      $where="substr(o57_fonte,1,4)='$codigo' and substr(o57_fonte,5,9)<>'000000000' ";
    }
    if($nivel==3){
      $codigo = substr($elemento,0,3);
      $where="substr(o57_fonte,1,3)='$codigo' and substr(o57_fonte,4,10)<>'0000000000' ";
    }
    if($nivel==2){
      $codigo = substr($elemento,0,2);
      $where="substr(o57_fonte,1,2)='$codigo' and substr(o57_fonte,3,11)<>'00000000000' ";
    }
    if($nivel==1){
      $codigo = substr($elemento,0,1);
      $where="substr(o57_fonte,1,1)='$codigo' and substr(o57_fonte,2,11)<>'00000000000' ";
    }
    $result= $this->sql_record($this->sql_query_file("","","o57_fonte",""," o57_anousu=$anousu and ".$where));
    if($this->numrows>0){
      $this->erro_msg = 'Exclusão abortada. Existe uma conta de nível inferior cadastrada!';
      return false;
    }
    $this->erro_msg = 'Fonte com permissão de exclusão!';
    return true;
  }



   function db_verifica_fonte($elemento,$anousu=null){
   	if ($anousu==null){
   		  $anousu = db_getsesson("DB_anousu");
   	}
    $nivel = db_le_mae_rec($elemento,true);
    if($nivel == 1){
      return true;
    }
    $cod_mae = db_le_mae_rec($elemento,false);
    $this->sql_record($this->sql_query_file("",null,"o57_fonte",""," o57_anousu=$anousu and  o57_fonte='$cod_mae'"));
    if($this->numrows<1){
      $this->erro_msg = 'Inclusão abortada. Fonte acima não encontrado!';
      return false;
    }
   if($nivel==10){
      return true;
    }
    if($nivel==9){
      $codigo = substr($elemento,0,11)."00";
      $where="substr(o57_fonte,1,13)='$codigo' and substr(o57_fonte,14,2)<>'00' ";
    }
    if($nivel==8){
      $codigo = substr($elemento,0,9)."00";
      $where="substr(o57_fonte,1,11)='$codigo' and substr(o57_fonte,12,2)<>'0000' ";
    }
    if($nivel==7){
      $codigo = substr($elemento,0,7)."00";
      $where="substr(o57_fonte,1,9)='$codigo' and substr(o57_fonte,10,4)<>'000000' ";
    }
    if($nivel==6){
      $codigo = substr($elemento,0,5)."00";
      $where="substr(o57_fonte,1,7)='$codigo' and substr(o57_fonte,8,6)<>'00000000' ";
    }
  if($nivel==5){
      $codigo = substr($elemento,0,4)."0";
      $where="substr(o57_fonte,1,5)='$codigo' and substr(o57_fonte,6,8)<>'0000000000' ";
    }
    if($nivel==4){
      $codigo = substr($elemento,0,3)."0";
      $where="substr(o57_fonte,1,4)='$codigo' and substr(o57_fonte,5,9)<>'00000000000' ";
    }
    if($nivel==3){
      $codigo = substr($elemento,0,2)."0";
      $where="substr(o57_fonte,1,3)='$codigo' and substr(o57_fonte,4,10)<>'000000000000' ";
    }
    if($nivel==2){
      $codigo = substr($elemento,0,1)."0";
      $where="substr(o57_fonte,1,2)='$codigo' and substr(o57_fonte,3,11)<>'0000000000000' ";
    }
    $result= $this->sql_record($this->sql_query_file("","","o57_fonte",""," o57_anousu = $anousu and ".$where));
    if($this->numrows>0){
      $this->erro_msg = 'Inclusão abortada. Existe uma conta de nível inferior cadastrada!';
      return false;
    }
    $this->erro_msg = 'Fonte válida!';
    return true;
  }

  function sql_query_desdobramento ( $o57_codfon=null,$o57_anousu=null,$campos="*",$ordem=null,$dbwhere="", $sInstits=''){

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
     if (empty($sInstits)) {

       $sInstits  = db_getsession("DB_instit");
     }
     $sql .= " from orcfontes ";
     $sql .= "      inner join conplano        on conplano.c60_codcon = orcfontes.o57_codfon and ";
     $sql .= "                                    conplano.c60_anousu = orcfontes.o57_anousu";
     $sql .= "      inner join conclass        on conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema      on consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join conclass   as a on a.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema as b on b.c52_codsis = conplano.c60_codsis";
     $sql .= "      left  join conplanoreduz   on conplanoreduz.c61_codcon = conplano.c60_codcon and ";
     $sql .= "                                    conplanoreduz.c61_anousu = conplano.c60_anousu and";
     $sql .= "                                    conplanoreduz.c61_instit in({$sInstits})";
     $sql .= "      left join orctiporec      on o15_codigo = c61_codigo";
     $sql .= "      left join orcfontesdes    on o60_codfon = o57_codfon ";
     $sql .= "                               and o60_anousu = o57_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($o57_codfon!=null ){
         $sql2 .= " where orcfontes.o57_codfon = $o57_codfon ";
       }
       if($o57_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcfontes.o57_anousu = $o57_anousu ";
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
     return analiseQueryPlanoOrcamento($sql);
  }

  public function sql_query_fonte_receita($sCampos = '*', $sOrdem = null, $sWhere = null) {

    $sSql  = "select {$sCampos} ";
    $sSql .= "  from orcfontes ";
    $sSql .= "       inner join orcreceita on o57_codfon = o70_codfon ";
    $sSql .= "                            and o57_anousu = o70_anousu ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }

}