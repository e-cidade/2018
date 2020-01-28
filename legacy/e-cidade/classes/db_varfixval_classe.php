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

//MODULO: issqn
//CLASSE DA ENTIDADE varfixval
class cl_varfixval {
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
   var $q34_codigo = 0;
   var $q34_numpar = 0;
   var $q34_mes = 0;
   var $q34_ano = 0;
   var $q34_valor = 0;
   var $q34_inflat = null;
   var $q34_dtval_dia = null;
   var $q34_dtval_mes = null;
   var $q34_dtval_ano = null;
   var $q34_dtval = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q34_codigo = int4 = Código
                 q34_numpar = int4 = Parcela
                 q34_mes = int4 = Mês
                 q34_ano = int4 = ano
                 q34_valor = float8 = valor
                 q34_inflat = varchar(5) = inflator
                 q34_dtval = date = data do valor
                 ";
   //funcao construtor da classe
   function cl_varfixval() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("varfixval");
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
       $this->q34_codigo = ($this->q34_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_codigo"]:$this->q34_codigo);
       $this->q34_numpar = ($this->q34_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_numpar"]:$this->q34_numpar);
       $this->q34_mes = ($this->q34_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_mes"]:$this->q34_mes);
       $this->q34_ano = ($this->q34_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_ano"]:$this->q34_ano);
       $this->q34_valor = ($this->q34_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_valor"]:$this->q34_valor);
       $this->q34_inflat = ($this->q34_inflat == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_inflat"]:$this->q34_inflat);
       if($this->q34_dtval == ""){
         $this->q34_dtval_dia = ($this->q34_dtval_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_dtval_dia"]:$this->q34_dtval_dia);
         $this->q34_dtval_mes = ($this->q34_dtval_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_dtval_mes"]:$this->q34_dtval_mes);
         $this->q34_dtval_ano = ($this->q34_dtval_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q34_dtval_ano"]:$this->q34_dtval_ano);
         if($this->q34_dtval_dia != ""){
            $this->q34_dtval = $this->q34_dtval_ano."-".$this->q34_dtval_mes."-".$this->q34_dtval_dia;
         }
       }
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->q34_codigo == null ){
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "q34_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q34_numpar == null ){
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "q34_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q34_mes == null ){
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "q34_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q34_ano == null ){
       $this->erro_sql = " Campo ano nao Informado.";
       $this->erro_campo = "q34_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q34_valor == null ){
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "q34_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q34_inflat == null ){
       $this->erro_sql = " Campo inflator nao Informado.";
       $this->erro_campo = "q34_inflat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q34_dtval == null ){
       $this->erro_sql = " Campo data do valor nao Informado.";
       $this->erro_campo = "q34_dtval_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into varfixval(
                                       q34_codigo
                                      ,q34_numpar
                                      ,q34_mes
                                      ,q34_ano
                                      ,q34_valor
                                      ,q34_inflat
                                      ,q34_dtval
                       )
                values (
                                $this->q34_codigo
                               ,$this->q34_numpar
                               ,$this->q34_mes
                               ,$this->q34_ano
                               ,$this->q34_valor
                               ,'$this->q34_inflat'
                               ,".($this->q34_dtval == "null" || $this->q34_dtval == ""?"null":"'".$this->q34_dtval."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   }
   // funcao para alteracao
   function alterar($oid = null, $sWhere = null) {
      $this->atualizacampos();
     $sql = " update varfixval set ";
     $virgula = "";
     if(trim($this->q34_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q34_codigo"])){
       $sql  .= $virgula." q34_codigo = $this->q34_codigo ";
       $virgula = ",";
       if(trim($this->q34_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q34_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q34_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q34_numpar"])){
       $sql  .= $virgula." q34_numpar = $this->q34_numpar ";
       $virgula = ",";
       if(trim($this->q34_numpar) == null ){
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "q34_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q34_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q34_mes"])){
       $sql  .= $virgula." q34_mes = $this->q34_mes ";
       $virgula = ",";
       if(trim($this->q34_mes) == null ){
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "q34_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q34_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q34_ano"])){
       $sql  .= $virgula." q34_ano = $this->q34_ano ";
       $virgula = ",";
       if(trim($this->q34_ano) == null ){
         $this->erro_sql = " Campo ano nao Informado.";
         $this->erro_campo = "q34_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q34_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q34_valor"])){
       $sql  .= $virgula." q34_valor = $this->q34_valor ";
       $virgula = ",";
       if(trim($this->q34_valor) == null ){
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "q34_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q34_inflat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q34_inflat"])){
       $sql  .= $virgula." q34_inflat = '$this->q34_inflat' ";
       $virgula = ",";
       if(trim($this->q34_inflat) == null ){
         $this->erro_sql = " Campo inflator nao Informado.";
         $this->erro_campo = "q34_inflat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q34_dtval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q34_dtval_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q34_dtval_dia"] !="") ){
       $sql  .= $virgula." q34_dtval = '$this->q34_dtval' ";
       $virgula = ",";
       if(trim($this->q34_dtval) == null ){
         $this->erro_sql = " Campo data do valor nao Informado.";
         $this->erro_campo = "q34_dtval_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["q34_dtval_dia"])){
         $sql  .= $virgula." q34_dtval = null ";
         $virgula = ",";
         if(trim($this->q34_dtval) == null ){
           $this->erro_sql = " Campo data do valor nao Informado.";
           $this->erro_campo = "q34_dtval_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";

     if (!empty($oid)) {
      $sql .= " oid = '$oid' ";
     } else {
      $sql .= $sWhere;
     }

      $result = db_query($sql);

     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ( $oid=null ,$dbwhere=null) {
     $sql = " delete from varfixval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:varfixval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="varfixval.oid,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from varfixval ";
     $sql .= "      inner join varfix  on  varfix.q33_codigo = varfixval.q34_codigo";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = varfixval.q34_inflat";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = varfix.q33_inscr";
     $sql .= "      inner join tipcalc  on  tipcalc.q81_codigo = varfix.q33_tipcalc";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where varfixval.oid = '$oid'";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from varfixval ";
     $sql2 = "";
     if($dbwhere==""){
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