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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamdoc
class cl_conlancamdoc {
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
   var $c71_codlan = 0;
   var $c71_coddoc = 0;
   var $c71_data_dia = null;
   var $c71_data_mes = null;
   var $c71_data_ano = null;
   var $c71_data = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c71_codlan = int4 = Código Lançamento
                 c71_coddoc = int4 = Código
                 c71_data = date = Data
                 ";
   //funcao construtor da classe
   function cl_conlancamdoc() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamdoc");
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
       $this->c71_codlan = ($this->c71_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_codlan"]:$this->c71_codlan);
       $this->c71_coddoc = ($this->c71_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_coddoc"]:$this->c71_coddoc);
       if($this->c71_data == ""){
         $this->c71_data_dia = ($this->c71_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_data_dia"]:$this->c71_data_dia);
         $this->c71_data_mes = ($this->c71_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_data_mes"]:$this->c71_data_mes);
         $this->c71_data_ano = ($this->c71_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_data_ano"]:$this->c71_data_ano);
         if($this->c71_data_dia != ""){
            $this->c71_data = $this->c71_data_ano."-".$this->c71_data_mes."-".$this->c71_data_dia;
         }
       }
     }else{
       $this->c71_codlan = ($this->c71_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c71_codlan"]:$this->c71_codlan);
     }
   }
   // funcao para inclusao
   function incluir ($c71_codlan){
      $this->atualizacampos();
     if($this->c71_coddoc == null ){
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "c71_coddoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c71_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c71_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c71_codlan = $c71_codlan;
     if(($this->c71_codlan == null) || ($this->c71_codlan == "") ){
       $this->erro_sql = " Campo c71_codlan nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamdoc(
                                       c71_codlan
                                      ,c71_coddoc
                                      ,c71_data
                       )
                values (
                                $this->c71_codlan
                               ,$this->c71_coddoc
                               ,".($this->c71_data == "null" || $this->c71_data == ""?"null":"'".$this->c71_data."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documento Automático Lançamento ($this->c71_codlan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documento Automático Lançamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documento Automático Lançamento ($this->c71_codlan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c71_codlan));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5213,'$this->c71_codlan','I')");
         $resac = db_query("insert into db_acount values($acount,764,5213,'','".AddSlashes(pg_result($resaco,0,'c71_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,764,5214,'','".AddSlashes(pg_result($resaco,0,'c71_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,764,5898,'','".AddSlashes(pg_result($resaco,0,'c71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c71_codlan=null) {
      $this->atualizacampos();
     $sql = " update conlancamdoc set ";
     $virgula = "";
     if(trim($this->c71_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c71_codlan"])){
       $sql  .= $virgula." c71_codlan = $this->c71_codlan ";
       $virgula = ",";
       if(trim($this->c71_codlan) == null ){
         $this->erro_sql = " Campo Código Lançamento nao Informado.";
         $this->erro_campo = "c71_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c71_coddoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c71_coddoc"])){
       $sql  .= $virgula." c71_coddoc = $this->c71_coddoc ";
       $virgula = ",";
       if(trim($this->c71_coddoc) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "c71_coddoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c71_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c71_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c71_data_dia"] !="") ){
       $sql  .= $virgula." c71_data = '$this->c71_data' ";
       $virgula = ",";
       if(trim($this->c71_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c71_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["c71_data_dia"])){
         $sql  .= $virgula." c71_data = null ";
         $virgula = ",";
         if(trim($this->c71_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c71_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($c71_codlan!=null){
       $sql .= " c71_codlan = $this->c71_codlan";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c71_codlan));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5213,'$this->c71_codlan','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c71_codlan"]))
             $resac = db_query("insert into db_acount values($acount,764,5213,'".AddSlashes(pg_result($resaco,$conresaco,'c71_codlan'))."','$this->c71_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c71_coddoc"]))
             $resac = db_query("insert into db_acount values($acount,764,5214,'".AddSlashes(pg_result($resaco,$conresaco,'c71_coddoc'))."','$this->c71_coddoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c71_data"]))
             $resac = db_query("insert into db_acount values($acount,764,5898,'".AddSlashes(pg_result($resaco,$conresaco,'c71_data'))."','$this->c71_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documento Automático Lançamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documento Automático Lançamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c71_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c71_codlan=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($c71_codlan));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5213,'$c71_codlan','E')");
           $resac = db_query("insert into db_acount values($acount,764,5213,'','".AddSlashes(pg_result($resaco,$iresaco,'c71_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,764,5214,'','".AddSlashes(pg_result($resaco,$iresaco,'c71_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,764,5898,'','".AddSlashes(pg_result($resaco,$iresaco,'c71_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamdoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c71_codlan != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c71_codlan = $c71_codlan ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documento Automático Lançamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c71_codlan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documento Automático Lançamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c71_codlan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c71_codlan;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamdoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamdoc ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
     $sql2 = "";
     if($dbwhere==""){
       if($c71_codlan!=null ){
         $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
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
   function sql_query_file ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamdoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($c71_codlan!=null ){
         $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
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
   function sql_query_process ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamdoc ";
     $sql .= "      inner join conhistdoc    on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
     $sql .= "      inner join conlancam     on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
     $sql .= "      inner join conlancamemp  on  conlancamemp.c75_codlan = conlancamdoc.c71_codlan";
     $sql .= "      inner join empempenho    on  empempenho.e60_numcgm = conlancamemp.c75_numemp";
     $sql .= "      inner join cgm           on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left  join conlancamord  on  conlancamord.c80_codlan = conlancamdoc.c71_codlan";
     $sql2 = "";
if($dbwhere==""){
       if($c71_codlan!=null ){
         $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
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

  function sql_query_reduzidos ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $iAnoSessao = db_getsession("DB_anousu");
    $sql .= " from conlancamdoc ";
    $sql .= "      inner join conlancam     on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
    $sql .= "      inner join conhistdoc    on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
    $sql .= "      inner join conlancamval  on  conlancamval.c69_codlan = conlancam.c70_codlan";
    $sql .= "      inner join conplanoreduz as conta_debito  on conta_debito.c61_reduz = conlancamval.c69_debito";
    $sql .= "                                               and conta_debito.c61_anousu = {$iAnoSessao}";
    $sql .= "      inner join conplanoreduz as conta_credito on conta_credito.c61_reduz = conlancamval.c69_credito";
    $sql .= "                                               and conta_credito.c61_anousu = {$iAnoSessao}";
    $sql2 = "";
    if($dbwhere==""){
      if($c71_codlan!=null ){
        $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
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
   * query até a conlancam emp, para verificar na funcao de RP
   * @param string $c71_codlan
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  
  function sql_queryEmpenhoRP ( $c71_codlan=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conlancamdoc ";
    $sql .= "      inner join conlancam     on  conlancam.c70_codlan = conlancamdoc.c71_codlan";
    $sql .= "      inner join conhistdoc    on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
    $sql .= "      inner join conlancamemp  on  conlancam.c70_codlan = conlancamemp.c75_codlan";
    $sql2 = "";
    if($dbwhere==""){
      if($c71_codlan!=null ){
        $sql2 .= " where conlancamdoc.c71_codlan = $c71_codlan ";
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