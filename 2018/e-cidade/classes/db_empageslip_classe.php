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
//CLASSE DA ENTIDADE empageslip
class cl_empageslip {
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
   var $e89_codmov = 0;
   var $e89_codigo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e89_codmov = int4 = Movimento
                 e89_codigo = int4 = Código Slip
                 ";
   //funcao construtor da classe
   function cl_empageslip() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empageslip");
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
       $this->e89_codmov = ($this->e89_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e89_codmov"]:$this->e89_codmov);
       $this->e89_codigo = ($this->e89_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["e89_codigo"]:$this->e89_codigo);
     }else{
       $this->e89_codmov = ($this->e89_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e89_codmov"]:$this->e89_codmov);
       $this->e89_codigo = ($this->e89_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["e89_codigo"]:$this->e89_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($e89_codmov,$e89_codigo){
      $this->atualizacampos();
       $this->e89_codmov = $e89_codmov;
       $this->e89_codigo = $e89_codigo;
     if(($this->e89_codmov == null) || ($this->e89_codmov == "") ){
       $this->erro_sql = " Campo e89_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e89_codigo == null) || ($this->e89_codigo == "") ){
       $this->erro_sql = " Campo e89_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empageslip(
                                       e89_codmov
                                      ,e89_codigo
                       )
                values (
                                $this->e89_codmov
                               ,$this->e89_codigo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Slips de agenda ($this->e89_codmov."-".$this->e89_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Slips de agenda já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Slips de agenda ($this->e89_codmov."-".$this->e89_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e89_codmov."-".$this->e89_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e89_codmov,$this->e89_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6214,'$this->e89_codmov','I')");
       $resac = db_query("insert into db_acountkey values($acount,6215,'$this->e89_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1006,6214,'','".AddSlashes(pg_result($resaco,0,'e89_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006,6215,'','".AddSlashes(pg_result($resaco,0,'e89_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e89_codmov=null,$e89_codigo=null) {
      $this->atualizacampos();
     $sql = " update empageslip set ";
     $virgula = "";
     if(trim($this->e89_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e89_codmov"])){
       $sql  .= $virgula." e89_codmov = $this->e89_codmov ";
       $virgula = ",";
       if(trim($this->e89_codmov) == null ){
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "e89_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e89_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e89_codigo"])){
       $sql  .= $virgula." e89_codigo = $this->e89_codigo ";
       $virgula = ",";
       if(trim($this->e89_codigo) == null ){
         $this->erro_sql = " Campo Código Slip nao Informado.";
         $this->erro_campo = "e89_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e89_codmov!=null){
       $sql .= " e89_codmov = $this->e89_codmov";
     }
     if($e89_codigo!=null){
       $sql .= " and  e89_codigo = $this->e89_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e89_codmov,$this->e89_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6214,'$this->e89_codmov','A')");
         $resac = db_query("insert into db_acountkey values($acount,6215,'$this->e89_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e89_codmov"]))
           $resac = db_query("insert into db_acount values($acount,1006,6214,'".AddSlashes(pg_result($resaco,$conresaco,'e89_codmov'))."','$this->e89_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e89_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1006,6215,'".AddSlashes(pg_result($resaco,$conresaco,'e89_codigo'))."','$this->e89_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slips de agenda nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e89_codmov."-".$this->e89_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Slips de agenda nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e89_codmov."-".$this->e89_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e89_codmov."-".$this->e89_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e89_codmov=null,$e89_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e89_codmov,$e89_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6214,'$e89_codmov','E')");
         $resac = db_query("insert into db_acountkey values($acount,6215,'$e89_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1006,6214,'','".AddSlashes(pg_result($resaco,$iresaco,'e89_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006,6215,'','".AddSlashes(pg_result($resaco,$iresaco,'e89_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empageslip
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e89_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e89_codmov = $e89_codmov ";
        }
        if($e89_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e89_codigo = $e89_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slips de agenda nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e89_codmov."-".$e89_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Slips de agenda nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e89_codmov."-".$e89_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e89_codmov."-".$e89_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:empageslip";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query_conf ( $e89_codmov=null,$e89_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageslip ";
     $sql .= "      inner join empagemov     on empagemov.e81_codmov     = empageslip.e89_codmov";
     $sql .= "      inner join empage   b    on b.e80_codage             = empagemov.e81_codage";
     $sql .= "      inner join empageconf    on e86_codmov               = e81_codmov";
     $sql .= "      inner join empagepag     on e85_codmov               = empagemov.e81_codmov";
     $sql .= "      inner join empagetipo    on empagetipo.e83_codtipo   = empagepag.e85_codtipo";
     $sql .= "      inner join slip s        on e89_codigo               = s.k17_codigo";
     $sql .= "      left  join empageconfche on empageconfche.e91_codmov = e86_codmov and e91_ativo is true ";
     $sql .= "	    left join slipnum o      on o.k17_codigo             = s.k17_codigo";
     $sql .= "	    left join cgm            on z01_numcgm               = o.k17_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($e89_codmov!=null ){
         $sql2 .= " where empageslip.e89_codmov = $e89_codmov ";
       }
       if($e89_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageslip.e89_codigo = $e89_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= (trim($sql2)!=""?" and ":" where ") . " k17_instit = " . db_getsession("DB_instit");
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
   function sql_query_credito ( $conta=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageslip ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageslip.e89_codmov";
     $sql .= "      inner join empage  as b on   b.e80_codage = empagemov.e81_codage";
     $sql .= "      inner join slip  on e89_codigo = slip.k17_codigo and slip.k17_autent is null";
     $sql .= " where slip.k17_credito = $conta";
     $sql .= " and k17_instit = " . db_getsession("DB_instit");
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
   function sql_query_descr ( $e89_codmov=null,$e89_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageslip ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageslip.e89_codmov";
     $sql .= "      inner join empage   b on   b.e80_codage = empagemov.e81_codage";
     $sql .= "      inner join empagepag   on   e85_codmov = empagemov.e81_codmov";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join slip s on   e89_codigo = s.k17_codigo";
     $sql .= "      left  join emphist on s.k17_hist = e40_codhist";
     $sql .= "	    inner join conplanoreduz x on x.c61_reduz = s.k17_debito and x.c61_anousu = ".db_getsession("DB_anousu");
     $sql .= "	    inner join conplano z on z.c60_codcon = x.c61_codcon and z.c60_anousu = x.c61_anousu ";
     $sql .= "	    left join slipnum o on o.k17_codigo = s.k17_codigo";
     $sql .= "	    left join cgm on z01_numcgm = o.k17_numcgm";
     $sql .= "	    left join  empageconf on e86_codmov=e81_codmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($e89_codmov!=null ){
         $sql2 .= " where empageslip.e89_codmov = $e89_codmov ";
       }
       if($e89_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageslip.e89_codigo = $e89_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k17_instit = " . db_getsession("DB_instit");
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

   function sql_query_configura ( $e89_codmov=null,$e89_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from empageslip ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageslip.e89_codmov";
     $sql .= "      inner join empage   b on   b.e80_codage = empagemov.e81_codage";
     $sql .= "	    left join  empagemovforma on e81_codmov=e97_codmov ";
     $sql .= "      left join empagepag   on   e85_codmov = empagemov.e81_codmov";
     $sql .= "      left join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join slip s on   e89_codigo = s.k17_codigo";
     $sql .= "      inner join sliptipooperacaovinculo on sliptipooperacaovinculo.k153_slip = s.k17_codigo ";
     $sql .= "      inner join sliptipooperacao on sliptipooperacao.k152_sequencial = sliptipooperacaovinculo.k153_slipoperacaotipo ";
     $sql .= "      left  join emphist on s.k17_hist = e40_codhist";
     $sql .= "	    inner join conplanoreduz x on x.c61_reduz = s.k17_debito and x.c61_anousu = ".db_getsession("DB_anousu");
     $sql .= "	    inner join conplano z on z.c60_codcon = x.c61_codcon and z.c60_anousu = x.c61_anousu ";
     $sql .= "	    inner join slipnum o on o.k17_codigo = s.k17_codigo";
     $sql .= "	    left join conplanoreduz ctapag on ctapag.c61_reduz = s.k17_credito and ctapag.c61_anousu = ".db_getsession("DB_anousu");
     $sql .= "	    left join cgm on z01_numcgm = o.k17_numcgm";
     $sql .= "	    left join  empageconf on e86_codmov=e81_codmov ";
     $sql .= "	    left join  empageconfche on e81_codmov=e91_codmov and e91_ativo is true";
     $sql .= "	    left join  empageconfgera on e81_codmov=e90_codmov ";
     $sql .= "	                              and e90_cancelado is false ";
     $sql .= "      left join empagenotasordem on e81_codmov  = e43_empagemov     ";
     $sql .= "      left join empageordem      on e43_ordempagamento = e42_sequencial ";
     $sql .= "      left join empagemovconta   on empageslip.e89_codmov  = empagemovconta.e98_codmov";
     $sql .= "      left join pcfornecon       on pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco";
     $sql .= "      left join slipprocesso on s.k17_codigo = k145_slip";

     $sql2 = "";
     if($dbwhere==""){
       if($e89_codmov!=null ){
         $sql2 .= " where empageslip.e89_codmov = $e89_codmov ";
       }
       if($e89_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageslip.e89_codigo = $e89_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k17_instit = " . db_getsession("DB_instit");
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

  function sql_query_txtbanco ( $e89_codmov=null,$campos="*",$ordem=null,$dbwhere=""){

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

     $sql .= " from empageslip ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageslip.e89_codmov";
     $sql .= "      inner join empage   b on   b.e80_codage = empagemov.e81_codage";
     $sql .= "	    inner join  empageconf on e86_codmov=e81_codmov ";
     $sql .= "	    inner join  empagemovforma on e81_codmov=e97_codmov ";
     $sql .= "      inner  join empagepag   on   e85_codmov = empagemov.e81_codmov";
     $sql .= "      inner  join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      inner join slip s on   e89_codigo = s.k17_codigo";
     $sql .= "	    inner join conplanoreduz pag on pag.c61_reduz = e83_conta and pag.c61_anousu = ".db_getsession("DB_anousu");
     $sql .= "	    inner join conplano conpag on conpag.c60_codcon = pag.c61_codcon and conpag.c60_anousu = pag.c61_anousu ";
     $sql .= "	    inner join conplanoconta on conpag.c60_codcon = conplanoconta.c63_codcon and conpag.c60_anousu = conplanoconta.c63_anousu ";
     $sql .= "      inner join conplanocontabancaria  on conplanocontabancaria.c56_codcon = conplanoconta.c63_codcon
                                                     and conplanocontabancaria.c56_anousu = conplanoconta.c63_anousu";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial =  conplanocontabancaria.c56_contabancaria ";
     $sql .= "	    inner join orctiporec on pag.c61_codigo = o15_codigo";
     $sql .= "      left  join emphist on s.k17_hist = e40_codhist";
     $sql .= "	    inner join slipnum o on o.k17_codigo = s.k17_codigo";
     $sql .= "	    left join cgm on z01_numcgm = o.k17_numcgm";
     $sql .= "	    left join  empageconfgera on e81_codmov=e90_codmov ";
     $sql .= "      left join empagemovconta on empagemovconta.e98_codmov = empagemov.e81_codmov ";
     $sql .= "      left join pcfornecon on pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco";
     $sql .= "	    left join conplanoreduz cre on cre.c61_reduz   = k17_debito and cre.c61_anousu = ".db_getsession("DB_anousu");
     $sql .= "	    left join conplano concre on concre.c60_codcon = cre.c61_codcon and concre.c60_anousu = cre.c61_anousu ";
     $sql .= "	    left join conplanoconta descrconta on concre.c60_codcon = descrconta.c63_codcon and concre.c60_anousu = descrconta.c63_anousu ";
     $sql .= "      left  join empagemovtipotransmissao on  e25_empagemov                = empagemov.e81_codmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($e89_codmov!=null ){
         $sql2 .= " where empageslip.e89_codmov = $e89_codmov ";
       }
       if($e89_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageslip.e89_codigo = $e89_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k17_instit = " . db_getsession("DB_instit");
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
   function sql_query_file ( $e89_codmov=null,$e89_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageslip ";
     $sql2 = "";
     if($dbwhere==""){
       if($e89_codmov!=null ){
         $sql2 .= " where empageslip.e89_codmov = $e89_codmov ";
       }
       if($e89_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageslip.e89_codigo = $e89_codigo ";
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
   function sql_query_slip ( $e89_codmov=null,$e89_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empageslip ";
     $sql .= "      inner join empagemov on e81_codmov = e89_codmov ";
     $sql .= "      inner join empage    on e80_codage = e81_codage ";
     $sql .= "      inner join slip      on k17_codigo = e89_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($e89_codmov!=null ){
         $sql2 .= " where empageslip.e89_codmov = $e89_codmov ";
       }
       if($e89_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empageslip.e89_codigo = $e89_codigo ";
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

  function sql_query_agenda_pagamento($e89_codmov=null,$e89_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";

    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {

      $sql .= $campos;
    }

    $sql .= " from empageslip ";
    $sql .= "       inner join empagemov      on e89_codmov   = e81_codmov";
    $sql .= "       inner join empagemovforma on e81_codmov   = e97_codmov";
    $sql .= "       inner join empageforma    on e97_codforma = e96_codigo";
    $sql .= "       left  join empageconfche  on e81_codmov   = e91_codmov and e91_ativo is true";
    $sql .= "       left  join empageconfgera on e81_codmov   = e90_codmov";


    $sql2 = "";
    if($dbwhere==""){
      if($e89_codmov!=null ){
        $sql2 .= " where empageslip.e89_codmov = $e89_codmov ";
      }
      if($e89_codigo!=null ){
      if($sql2!=""){
      $sql2 .= " and ";
      }else{
        $sql2 .= " where ";
      }
        $sql2 .= " empageslip.e89_codigo = $e89_codigo ";
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