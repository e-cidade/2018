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

//MODULO: biblioteca
//CLASSE DA ENTIDADE emprestimoacervo
class cl_emprestimoacervo {
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
   var $bi19_codigo = 0;
   var $bi19_emprestimo = 0;
   var $bi19_exemplar = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 bi19_codigo = int8 = Código
                 bi19_emprestimo = int8 = Empréstimo
                 bi19_exemplar = int8 = Exemplar
                 ";
   //funcao construtor da classe
   function cl_emprestimoacervo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("emprestimoacervo");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        if($this->emite=="true"){
         echo "<script>
               alert(\"".$this->erro_msg."\");
               jan = window.open('bib2_emprestimo002.php?emp=".$this->bi19_emprestimo."&tipo=0','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
               jan.moveTo(0,0);
              </script>
             ";
        }else{
         echo "<script>
               alert(\"".$this->erro_msg."\");
              </script>
             ";
        }
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->bi19_codigo = ($this->bi19_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi19_codigo"]:$this->bi19_codigo);
       $this->bi19_emprestimo = ($this->bi19_emprestimo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi19_emprestimo"]:$this->bi19_emprestimo);
       $this->bi19_exemplar = ($this->bi19_exemplar == ""?@$GLOBALS["HTTP_POST_VARS"]["bi19_exemplar"]:$this->bi19_exemplar);
     }else{
       $this->bi19_codigo = ($this->bi19_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi19_codigo"]:$this->bi19_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi19_codigo){
      $this->atualizacampos();
     if($this->bi19_emprestimo == null ){
       $this->erro_sql = " Campo Empréstimo nao Informado.";
       $this->erro_campo = "bi19_emprestimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi19_exemplar == null ){
       $this->erro_sql = " Campo Exemplar nao Informado.";
       $this->erro_campo = "bi19_exemplar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi19_codigo == "" || $bi19_codigo == null ){
       $result = db_query("select nextval('emprestimoacervo_bi19_codigo_se')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: emprestimoacervo_bi19_codigo_se do campo: bi19_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->bi19_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from emprestimoacervo_bi19_codigo_se");
       if(($result != false) && (pg_result($result,0,0) < $bi19_codigo)){
         $this->erro_sql = " Campo bi19_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi19_codigo = $bi19_codigo;
       }
     }
     if(($this->bi19_codigo == null) || ($this->bi19_codigo == "") ){
       $this->erro_sql = " Campo bi19_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into emprestimoacervo(
                                       bi19_codigo
                                      ,bi19_emprestimo
                                      ,bi19_exemplar
                       )
                values (
                                $this->bi19_codigo
                               ,$this->bi19_emprestimo
                               ,$this->bi19_exemplar
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empréstimo Acervo ($this->bi19_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empréstimo Acervo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empréstimo Acervo ($this->bi19_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi19_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi19_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008141,'$this->bi19_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1008024,1008141,'','".AddSlashes(pg_result($resaco,0,'bi19_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008024,1008144,'','".AddSlashes(pg_result($resaco,0,'bi19_emprestimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008024,1008145,'','".AddSlashes(pg_result($resaco,0,'bi19_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($bi19_codigo=null) {
      $this->atualizacampos();
     $sql = " update emprestimoacervo set ";
     $virgula = "";
     if(trim($this->bi19_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi19_codigo"])){
       $sql  .= $virgula." bi19_codigo = $this->bi19_codigo ";
       $virgula = ",";
       if(trim($this->bi19_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi19_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi19_emprestimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi19_emprestimo"])){
       $sql  .= $virgula." bi19_emprestimo = $this->bi19_emprestimo ";
       $virgula = ",";
       if(trim($this->bi19_emprestimo) == null ){
         $this->erro_sql = " Campo Empréstimo nao Informado.";
         $this->erro_campo = "bi19_emprestimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi19_exemplar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi19_exemplar"])){
       $sql  .= $virgula." bi19_exemplar = $this->bi19_exemplar ";
       $virgula = ",";
       if(trim($this->bi19_exemplar) == null ){
         $this->erro_sql = " Campo Exemplar nao Informado.";
         $this->erro_campo = "bi19_exemplar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi19_codigo!=null){
       $sql .= " bi19_codigo = $this->bi19_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi19_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008141,'$this->bi19_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi19_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1008024,1008141,'".AddSlashes(pg_result($resaco,$conresaco,'bi19_codigo'))."','$this->bi19_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi19_emprestimo"]))
           $resac = db_query("insert into db_acount values($acount,1008024,1008144,'".AddSlashes(pg_result($resaco,$conresaco,'bi19_emprestimo'))."','$this->bi19_emprestimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi19_exemplar"]))
           $resac = db_query("insert into db_acount values($acount,1008024,1008145,'".AddSlashes(pg_result($resaco,$conresaco,'bi19_exemplar'))."','$this->bi19_exemplar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empréstimo Acervo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi19_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empréstimo Acervo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($bi19_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi19_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008141,'$bi19_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1008024,1008141,'','".AddSlashes(pg_result($resaco,$iresaco,'bi19_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008024,1008144,'','".AddSlashes(pg_result($resaco,$iresaco,'bi19_emprestimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008024,1008145,'','".AddSlashes(pg_result($resaco,$iresaco,'bi19_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from emprestimoacervo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi19_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi19_codigo = $bi19_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empréstimo Acervo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi19_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empréstimo Acervo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi19_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:emprestimoacervo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi19_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from emprestimoacervo ";
     $sql .= "      inner join emprestimo  on  emprestimo.bi18_codigo = emprestimoacervo.bi19_emprestimo";
     $sql .= "      inner join exemplar  on  exemplar.bi23_codigo = emprestimoacervo.bi19_exemplar";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = emprestimo.bi18_usuario";
     $sql .= "      inner join carteira  on  carteira.bi16_codigo = emprestimo.bi18_carteira";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = exemplar.bi23_acervo";
     $sql2 = "";
     if($dbwhere==""){
       if($bi19_codigo!=null ){
         $sql2 .= " where emprestimoacervo.bi19_codigo = $bi19_codigo ";
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
   function sql_query_file ( $bi19_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from emprestimoacervo ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi19_codigo!=null ){
         $sql2 .= " where emprestimoacervo.bi19_codigo = $bi19_codigo ";
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


  function sql_query_emprestimos_acervo_com_autor ( $bi19_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from emprestimoacervo ";
    $sql .= "      inner join emprestimo      on emprestimo.bi18_codigo      = emprestimoacervo.bi19_emprestimo";
    $sql .= "      inner join exemplar        on exemplar.bi23_codigo        = emprestimoacervo.bi19_exemplar";
    $sql .= "      inner join carteira        on carteira.bi16_codigo        = emprestimo.bi18_carteira";
    $sql .= "      inner join acervo          on acervo.bi06_seq             = exemplar.bi23_acervo";
    $sql .= "      inner join leitorcategoria on leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria";
    $sql .= "      inner join biblioteca      on acervo.bi06_biblioteca      = biblioteca.bi17_codigo";
    $sql .= "      left  join devolucaoacervo on bi21_codigo                 = bi19_codigo";
    $sql .= "      left  join autoracervo     on autoracervo.bi21_acervo     = bi06_seq";
    $sql .= "      left  join autor           on autoracervo.bi21_autor      = bi01_codigo";

    $sql2 = "";

    if($dbwhere==""){
      if($bi19_codigo!=null ){
        $sql2 .= " where emprestimoacervo.bi19_codigo = $bi19_codigo ";
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