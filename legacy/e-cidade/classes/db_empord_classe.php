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

//MODULO: empenho
//CLASSE DA ENTIDADE empord
class cl_empord {
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
   var $e82_codmov = 0;
   var $e82_codord = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e82_codmov = int4 = Movimento 
                 e82_codord = int4 = Ordem 
                 ";
   //funcao construtor da classe
   function cl_empord() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empord");
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
       $this->e82_codmov = ($this->e82_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e82_codmov"]:$this->e82_codmov);
       $this->e82_codord = ($this->e82_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e82_codord"]:$this->e82_codord);
     }else{
       $this->e82_codmov = ($this->e82_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e82_codmov"]:$this->e82_codmov);
       $this->e82_codord = ($this->e82_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e82_codord"]:$this->e82_codord);
     }
   }
   // funcao para inclusao
   function incluir ($e82_codmov,$e82_codord){
      $this->atualizacampos();
       $this->e82_codmov = $e82_codmov;
       $this->e82_codord = $e82_codord;
     if(($this->e82_codmov == null) || ($this->e82_codmov == "") ){
       $this->erro_sql = " Campo e82_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e82_codord == null) || ($this->e82_codord == "") ){
       $this->erro_sql = " Campo e82_codord nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empord(
                                       e82_codmov 
                                      ,e82_codord 
                       )
                values (
                                $this->e82_codmov 
                               ,$this->e82_codord 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Moviemto ordem ($this->e82_codmov."-".$this->e82_codord) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Moviemto ordem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Moviemto ordem ($this->e82_codmov."-".$this->e82_codord) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e82_codmov."-".$this->e82_codord;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e82_codmov,$this->e82_codord));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6177,'$this->e82_codmov','I')");
       $resac = db_query("insert into db_acountkey values($acount,6178,'$this->e82_codord','I')");
       $resac = db_query("insert into db_acount values($acount,996,6177,'','".AddSlashes(pg_result($resaco,0,'e82_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,996,6178,'','".AddSlashes(pg_result($resaco,0,'e82_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e82_codmov=null,$e82_codord=null) {
      $this->atualizacampos();
     $sql = " update empord set ";
     $virgula = "";
     if(trim($this->e82_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e82_codmov"])){
       $sql  .= $virgula." e82_codmov = $this->e82_codmov ";
       $virgula = ",";
       if(trim($this->e82_codmov) == null ){
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "e82_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e82_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e82_codord"])){
       $sql  .= $virgula." e82_codord = $this->e82_codord ";
       $virgula = ",";
       if(trim($this->e82_codord) == null ){
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "e82_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e82_codmov!=null){
       $sql .= " e82_codmov = $this->e82_codmov";
     }
     if($e82_codord!=null){
       $sql .= " and  e82_codord = $this->e82_codord";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e82_codmov,$this->e82_codord));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6177,'$this->e82_codmov','A')");
         $resac = db_query("insert into db_acountkey values($acount,6178,'$this->e82_codord','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e82_codmov"]))
           $resac = db_query("insert into db_acount values($acount,996,6177,'".AddSlashes(pg_result($resaco,$conresaco,'e82_codmov'))."','$this->e82_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e82_codord"]))
           $resac = db_query("insert into db_acount values($acount,996,6178,'".AddSlashes(pg_result($resaco,$conresaco,'e82_codord'))."','$this->e82_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Moviemto ordem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e82_codmov."-".$this->e82_codord;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Moviemto ordem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e82_codmov."-".$this->e82_codord;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e82_codmov."-".$this->e82_codord;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e82_codmov=null,$e82_codord=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e82_codmov,$e82_codord));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6177,'$e82_codmov','E')");
         $resac = db_query("insert into db_acountkey values($acount,6178,'$e82_codord','E')");
         $resac = db_query("insert into db_acount values($acount,996,6177,'','".AddSlashes(pg_result($resaco,$iresaco,'e82_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,996,6178,'','".AddSlashes(pg_result($resaco,$iresaco,'e82_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empord
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e82_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e82_codmov = $e82_codmov ";
        }
        if($e82_codord != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e82_codord = $e82_codord ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Moviemto ordem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e82_codmov."-".$e82_codord;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Moviemto ordem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e82_codmov."-".$e82_codord;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e82_codmov."-".$e82_codord;
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
        $this->erro_sql   = "Record Vazio na Tabela:empord";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e82_codmov=null,$e82_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empord ";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = empord.e82_codord";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empord.e82_codmov";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql .= "      inner join empempenho  as a on   a.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  as b on   b.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if($dbwhere==""){
       if($e82_codmov!=null ){
         $sql2 .= " where empord.e82_codmov = $e82_codmov ";
       }
       if($e82_codord!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empord.e82_codord = $e82_codord ";
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
   function sql_query_corempagemov( $e82_codmov=null,$e82_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empord ";
     $sql .= "      inner join empagemov    on empagemov.e81_codmov    = empord.e82_codmov ";
     $sql .= "      left  join corempagemov on corempagemov.k12_codmov = empagemov.e81_codmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($e82_codmov!=null ){
         $sql2 .= " where empord.e82_codmov = $e82_codmov ";
       }
       if($e82_codord!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empord.e82_codord = $e82_codord ";
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
   function sql_query_file ( $e82_codmov=null,$e82_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empord ";
     $sql2 = "";
     if($dbwhere==""){
       if($e82_codmov!=null ){
         $sql2 .= " where empord.e82_codmov = $e82_codmov ";
       }
       if($e82_codord!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empord.e82_codord = $e82_codord ";
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
	 * Metodo para buscar arquivo ou cheque gerados para o movimento
	 * - Usado para bloquear desconto na rotina nota de liquidacao - desconto
	 *
	 * @param integer $iOrdem
	 * @param string $sCampos
	 * @access public
	 * @return string
	 */
	public function sql_query_validaDescontoMovimento($iOrdem, $sCampos = null) {

		if ( $sCampos == null ) {
			$sCampos = 'e90_cancelado, e91_ativo';
		}

	  $sSql  = "select $sCampos                                              \n";
	  $sSql .= "  from empord                                                \n";
    $sSql .= "       inner join empagemov     on e81_codmov = e82_codmov   \n";
    $sSql .= "       left  join empageconf    on e86_codmov = e81_codmov   \n";
	  $sSql .= "       left join empageconfgera on e82_codmov = e90_codmov   \n";
	  $sSql .= "       left join empageconfche  on e82_codmov = e91_codmov   \n";
	  $sSql .= " where e82_codord = {$iOrdem}                                \n";
	  $sSql .= "   and e81_cancelado is null                                 \n";
    $sSql .= "   and e90_cancelado is null                                 \n";
    $sSql .= "   and e86_codmov    is null                                 \n";
    $sSql .= "   and e91_codmov    is null                                 \n";


	  return $sSql;
	}


  public function sql_query_movimento_ordem($sCampos = "*", $sWhere = null, $sOrder = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from empord ";
    $sSql .= "        inner join empagemov on empagemov.e81_codmov = empord.e82_codmov ";
    $sSql .= "        inner join pagordem  on pagordem.e50_codord  = empord.e82_codord ";
    $sSql .= "        inner join pagordemele on pagordemele.e53_codord = pagordem.e50_codord ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }

}
?>