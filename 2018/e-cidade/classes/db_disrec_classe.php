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

//MODULO: caixa
//CLASSE DA ENTIDADE disrec
class cl_disrec {
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
   var $codcla = 0;
   var $k00_receit = 0;
   var $vlrrec = 0;
   var $idret = 0;
   var $instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 codcla = int4 = CodCla
                 k00_receit = int4 = Receita
                 vlrrec = float8 = Valor receita
                 idret = int4 = Cód. Ret.
                 instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_disrec() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("disrec");
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
       $this->codcla = ($this->codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["codcla"]:$this->codcla);
       $this->k00_receit = ($this->k00_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_receit"]:$this->k00_receit);
       $this->vlrrec = ($this->vlrrec == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrrec"]:$this->vlrrec);
       $this->idret = ($this->idret == ""?@$GLOBALS["HTTP_POST_VARS"]["idret"]:$this->idret);
       $this->instit = ($this->instit == ""?@$GLOBALS["HTTP_POST_VARS"]["instit"]:$this->instit);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->codcla == null ){
       $this->erro_sql = " Campo CodCla nao Informado.";
       $this->erro_campo = "codcla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_receit == null ){
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k00_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrrec == null ){
       $this->erro_sql = " Campo Valor receita nao Informado.";
       $this->erro_campo = "vlrrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->idret == null ){
       $this->erro_sql = " Campo Cód. Ret. nao Informado.";
       $this->erro_campo = "idret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into disrec(
                                       codcla
                                      ,k00_receit
                                      ,vlrrec
                                      ,idret
                                      ,instit
                       )
                values (
                                $this->codcla
                               ,$this->k00_receit
                               ,$this->vlrrec
                               ,$this->idret
                               ,$this->instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Descricao das receitas () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Descricao das receitas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Descricao das receitas () nao Incluído. Inclusao Abortada.";
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
   function alterar ( $oid=null ) {
      $this->atualizacampos();
     $sql = " update disrec set ";
     $virgula = "";
     if(trim($this->codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcla"])){
       $sql  .= $virgula." codcla = $this->codcla ";
       $virgula = ",";
       if(trim($this->codcla) == null ){
         $this->erro_sql = " Campo CodCla nao Informado.";
         $this->erro_campo = "codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_receit"])){
       $sql  .= $virgula." k00_receit = $this->k00_receit ";
       $virgula = ",";
       if(trim($this->k00_receit) == null ){
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k00_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vlrrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrrec"])){
       $sql  .= $virgula." vlrrec = $this->vlrrec ";
       $virgula = ",";
       if(trim($this->vlrrec) == null ){
         $this->erro_sql = " Campo Valor receita nao Informado.";
         $this->erro_campo = "vlrrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->idret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["idret"])){
       $sql  .= $virgula." idret = $this->idret ";
       $virgula = ",";
       if(trim($this->idret) == null ){
         $this->erro_sql = " Campo Cód. Ret. nao Informado.";
         $this->erro_campo = "idret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["instit"])){
       $sql  .= $virgula." instit = $this->instit ";
       $virgula = ",";
       if(trim($this->instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descricao das receitas nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descricao das receitas nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from disrec
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
       $this->erro_sql   = "Descricao das receitas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descricao das receitas nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:disrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $oid = null,$campos="disrec.oid,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from disrec ";
     $sql .= "      inner join db_config  on  db_config.codigo = disrec.instit";
     $sql .= "      inner join discla  on  discla.codcla = disrec.codcla";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_config  on  db_config.codigo = discla.instit";
     $sql .= "      inner join disarq  on  disarq.codret = discla.codret";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where disrec.oid = '$oid'";
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
     $sql .= " from disrec ";
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

  function sql_query_receitas_autenticadas ($oid = null,$campos="disrec.oid,*",$ordem=null,$dbwhere="") {


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

    $sql .= "  from corcla ";
    $sql .= "       inner join discla         on discla.codcla   = corcla.k12_codcla";
    $sql .= "       inner join disrec         on disrec.codcla   = discla.codcla";
    $sql .= "       inner join arreidret      on arreidret.idret = disrec.idret ";

    $sql .= "       inner join cornump        on corcla.k12_id = cornump.k12_id ";
    $sql .= "                                and corcla.k12_data = cornump.k12_data ";
    $sql .= "                                and corcla.k12_autent = cornump.k12_autent ";
    $sql .= "                                and disrec.k00_receit  = cornump.k12_receit ";
    $sql .= "       inner join corrente       on cornump.k12_id = corrente.k12_id ";
    $sql .= "                                and cornump.k12_data = corrente.k12_data ";
    $sql .= "                                and cornump.k12_autent = corrente.k12_autent ";
    $sql .= "       inner join tabrec         on cornump.k12_receit = tabrec.k02_codigo ";
    $sql .= "       inner join taborc         on tabrec.k02_codigo = taborc.k02_codigo ";
    $sql .= "       inner join orcreceita     on taborc.k02_codrec = orcreceita.o70_codrec ";
    $sql .= "                                and taborc.k02_anousu = orcreceita.o70_anousu ";
    $sql .= " where not exists (select * ";
    $sql .= "                     from empprestarecibo";
    $sql .= "                    where empprestarecibo.e170_numpre = arreidret.k00_numpre  ";
    $sql .= "                      and empprestarecibo.e170_numpar = arreidret.k00_numpar )";

    $sql2 = "";
    if($dbwhere==""){
      if( $oid != "" && $oid != null){
        $sql2 = " and disrec.oid = '$oid'";
      }
    }else if($dbwhere != ""){
      $sql2 = " and $dbwhere";
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

  function sql_query_receitas_autenticadas_desconto ($oid = null,$campos="disrec.oid,*",$ordem=null,$dbwhere="") {
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

    $sql .= " from corcla";
    $sql .= "      inner join cornumpdesconto cornump on corcla.k12_id      = cornump.k12_id";
    $sql .= "                           and corcla.k12_data    = cornump.k12_data";
    $sql .= "                           and corcla.k12_autent  = cornump.k12_autent";
    $sql .= "      inner join corrente   on corcla.k12_id      = corrente.k12_id";
    $sql .= "                           and corcla.k12_data    = corrente.k12_data";
    $sql .= "                           and corcla.k12_autent  = corrente.k12_autent";
    $sql .= "      inner join tabrec     on cornump.k12_receit = tabrec.k02_codigo";
    $sql .= "      inner join taborc     on tabrec.k02_codigo  = taborc.k02_codigo";
    $sql .= "      inner join orcreceita on taborc.k02_codrec  = orcreceita.o70_codrec";
    $sql .= "                           and taborc.k02_anousu  = orcreceita.o70_anousu";
    $sql2 = "";
    if($dbwhere==""){
      if( $oid != "" && $oid != null){
        $sql2 = " where disrec.oid = '$oid'";
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

  function sql_query_receita_extra ($oid = null,$campos="disrec.oid,*",$ordem=null,$dbwhere="") {
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

    $sql .= " from corcla";
    $sql .= "      inner join discla         on discla.codcla   = corcla.k12_codcla";
    $sql .= "      inner join disrec         on disrec.codcla   = discla.codcla";
    $sql .= "      inner join arreidret      on arreidret.idret = disrec.idret ";

    $sql .= "      inner join cornump    on corcla.k12_id      = cornump.k12_id";
    $sql .= "                           and corcla.k12_data    = cornump.k12_data";
    $sql .= "                           and corcla.k12_autent  = cornump.k12_autent";
    $sql .= "                           and disrec.k00_receit  = cornump.k12_receit";
    $sql .= "      inner join corrente   on corcla.k12_id      = corrente.k12_id";
    $sql .= "                           and corcla.k12_data    = corrente.k12_data";
    $sql .= "                           and corcla.k12_autent  = corrente.k12_autent";
    $sql .= "      inner join tabrec     on cornump.k12_receit = tabrec.k02_codigo";
    $sql .= "      inner join tabplan    on tabplan.k02_codigo       = tabrec.k02_codigo       ";
    $sql .= "                           and tabplan.k02_anousu       = {$iAnoSessao}           ";
    $sql .= "      inner join conplanoexe     on corrente.k12_conta       = conplanoexe.c62_reduz   ";
    $sql .= "                                and conplanoexe.c62_anousu   = {$iAnoSessao}           ";
    $sql .= "      inner join conplanoreduz   on conplanoexe.c62_reduz    = conplanoreduz.c61_reduz ";
    $sql .= "                                and conplanoreduz.c61_anousu = conplanoexe.c62_anousu  ";
    $sql .= " where not exists (select * ";
    $sql .= "                     from empprestarecibo";
    $sql .= "                    where empprestarecibo.e170_numpre = arreidret.k00_numpre  ";
    $sql .= "                      and empprestarecibo.e170_numpar = arreidret.k00_numpar )";


    $sql2 = "";
    if($dbwhere==""){
      if( $oid != "" && $oid != null){
        $sql2 = " and disrec.oid = '$oid'";
      }
    }else if($dbwhere != ""){
      $sql2 = " and $dbwhere";
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


  function sql_query_receita_extra_prestacao_conta ($oid = null,$campos="disrec.oid,*",$ordem=null,$dbwhere="") {
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

    $iAnoSessao   = db_getsession("DB_anousu");
    $iInstituicao = db_getsession("DB_instit");

    $sql .= " from corcla";
    $sql .= "      inner join discla     on discla.codcla      = corcla.k12_codcla";
    $sql .= "      inner join disrec     on disrec.codcla      = discla.codcla";
    $sql .= "      inner join arreidret  on arreidret.idret    = disrec.idret ";

    $sql .= "      inner join empprestarecibo on empprestarecibo.e170_numpre = arreidret.k00_numpre";
    $sql .= "                                and empprestarecibo.e170_numpar  = arreidret.k00_numpar";
    $sql .= "      inner join emppresta       on emppresta.e45_sequencial = empprestarecibo.e170_emppresta";

    $sql .= "      inner join cornump    on corcla.k12_id      = cornump.k12_id";
    $sql .= "                           and corcla.k12_data    = cornump.k12_data";
    $sql .= "                           and corcla.k12_autent  = cornump.k12_autent";
    $sql .= "                           and disrec.k00_receit  = cornump.k12_receit";
    $sql .= "      inner join corrente   on cornump.k12_id      = corrente.k12_id";
    $sql .= "                           and cornump.k12_data    = corrente.k12_data";
    $sql .= "                           and cornump.k12_autent  = corrente.k12_autent";
    $sql .= "      inner join tabrec     on cornump.k12_receit = tabrec.k02_codigo";
    $sql .= "      inner join tabplan    on tabplan.k02_codigo       = tabrec.k02_codigo       ";
    $sql .= "                           and tabplan.k02_anousu       = {$iAnoSessao}           ";
    $sql .= "      inner join conplanoexe     on corrente.k12_conta       = conplanoexe.c62_reduz   ";
    $sql .= "                                and conplanoexe.c62_anousu   = {$iAnoSessao}           ";
    $sql .= "      inner join conplanoreduz   on conplanoexe.c62_reduz    = conplanoreduz.c61_reduz ";
    $sql .= "                                and conplanoreduz.c61_anousu = conplanoexe.c62_anousu  ";

    $sql2 = "";
    if($dbwhere==""){
      if( $oid != "" && $oid != null){
        $sql2 = " where disrec.oid = '$oid'";
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

function sql_query_prestacao_conta ($oid = null,$campos="disrec.oid,*",$ordem=null,$dbwhere="") {
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

    $sql .= " from corcla";
    $sql .= "      inner join discla     on discla.codcla      = corcla.k12_codcla";
    $sql .= "      inner join disrec     on disrec.codcla      = discla.codcla";
    $sql .= "      inner join arreidret  on arreidret.idret    = disrec.idret ";

    $sql .= "      inner join empprestarecibo on empprestarecibo.e170_numpre = arreidret.k00_numpre";
    $sql .= "                               and empprestarecibo.e170_numpar  = arreidret.k00_numpar";

    $sql .= "      inner join emppresta       on emppresta.e45_sequencial    = empprestarecibo.e170_emppresta";

    $sql .= "      inner join cornump    on corcla.k12_id      = cornump.k12_id";
    $sql .= "                           and corcla.k12_data    = cornump.k12_data";
    $sql .= "                           and corcla.k12_autent  = cornump.k12_autent";
    $sql .= "                           and disrec.k00_receit  = cornump.k12_receit";

    $sql .= "      inner join corrente   on cornump.k12_id      = corrente.k12_id";
    $sql .= "                           and cornump.k12_data    = corrente.k12_data";
    $sql .= "                           and cornump.k12_autent  = corrente.k12_autent";

    $sql .= "      inner join tabrec     on cornump.k12_receit = tabrec.k02_codigo";
    $sql .= "      inner join taborc     on tabrec.k02_codigo  = taborc.k02_codigo";
    $sql .= "      inner join orcreceita on taborc.k02_codrec  = orcreceita.o70_codrec";
    $sql .= "                           and taborc.k02_anousu  = orcreceita.o70_anousu";


    $sql2 = "";
    if($dbwhere==""){
      if( $oid != "" && $oid != null){
        $sql2 = " where disrec.oid = '$oid'";
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