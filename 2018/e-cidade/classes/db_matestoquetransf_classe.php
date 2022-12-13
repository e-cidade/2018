<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: material
//CLASSE DA ENTIDADE matestoquetransf
class cl_matestoquetransf {
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
   var $m83_matestoqueini = 0;
   var $m83_coddepto = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m83_matestoqueini = int8 = Lançamento
                 m83_coddepto = int4 = Depart.
                 ";
   //funcao construtor da classe
   function cl_matestoquetransf() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoquetransf");
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
       $this->m83_matestoqueini = ($this->m83_matestoqueini == ""?@$GLOBALS["HTTP_POST_VARS"]["m83_matestoqueini"]:$this->m83_matestoqueini);
       $this->m83_coddepto = ($this->m83_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["m83_coddepto"]:$this->m83_coddepto);
     }else{
       $this->m83_matestoqueini = ($this->m83_matestoqueini == ""?@$GLOBALS["HTTP_POST_VARS"]["m83_matestoqueini"]:$this->m83_matestoqueini);
     }
   }
   // funcao para inclusao
   function incluir ($m83_matestoqueini){
      $this->atualizacampos();
     if($this->m83_coddepto == null ){
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "m83_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m83_matestoqueini = $m83_matestoqueini;
     if(($this->m83_matestoqueini == null) || ($this->m83_matestoqueini == "") ){
       $this->erro_sql = " Campo m83_matestoqueini nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoquetransf(
                                       m83_matestoqueini
                                      ,m83_coddepto
                       )
                values (
                                $this->m83_matestoqueini
                               ,$this->m83_coddepto
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Departamento de destino da transferência ($this->m83_matestoqueini) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Departamento de destino da transferência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Departamento de destino da transferência ($this->m83_matestoqueini) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m83_matestoqueini;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m83_matestoqueini));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6933,'$this->m83_matestoqueini','I')");
       $resac = db_query("insert into db_acount values($acount,1142,6933,'','".AddSlashes(pg_result($resaco,0,'m83_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1142,6934,'','".AddSlashes(pg_result($resaco,0,'m83_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m83_matestoqueini=null) {
      $this->atualizacampos();
     $sql = " update matestoquetransf set ";
     $virgula = "";
     if(trim($this->m83_matestoqueini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m83_matestoqueini"])){
       $sql  .= $virgula." m83_matestoqueini = $this->m83_matestoqueini ";
       $virgula = ",";
       if(trim($this->m83_matestoqueini) == null ){
         $this->erro_sql = " Campo Lançamento nao Informado.";
         $this->erro_campo = "m83_matestoqueini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m83_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m83_coddepto"])){
       $sql  .= $virgula." m83_coddepto = $this->m83_coddepto ";
       $virgula = ",";
       if(trim($this->m83_coddepto) == null ){
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "m83_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m83_matestoqueini!=null){
       $sql .= " m83_matestoqueini = $this->m83_matestoqueini";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m83_matestoqueini));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6933,'$this->m83_matestoqueini','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m83_matestoqueini"]))
           $resac = db_query("insert into db_acount values($acount,1142,6933,'".AddSlashes(pg_result($resaco,$conresaco,'m83_matestoqueini'))."','$this->m83_matestoqueini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m83_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,1142,6934,'".AddSlashes(pg_result($resaco,$conresaco,'m83_coddepto'))."','$this->m83_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Departamento de destino da transferência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m83_matestoqueini;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Departamento de destino da transferência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m83_matestoqueini;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m83_matestoqueini;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m83_matestoqueini=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m83_matestoqueini));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6933,'$m83_matestoqueini','E')");
         $resac = db_query("insert into db_acount values($acount,1142,6933,'','".AddSlashes(pg_result($resaco,$iresaco,'m83_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1142,6934,'','".AddSlashes(pg_result($resaco,$iresaco,'m83_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoquetransf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m83_matestoqueini != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m83_matestoqueini = $m83_matestoqueini ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Departamento de destino da transferência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m83_matestoqueini;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Departamento de destino da transferência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m83_matestoqueini;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m83_matestoqueini;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoquetransf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m83_matestoqueini=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoquetransf ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoquetransf.m83_coddepto";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoquetransf.m83_matestoqueini";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart a on a.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueini.m80_matestoqueitem";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($m83_matestoqueini!=null ){
         $sql2 .= " where matestoquetransf.m83_matestoqueini = $m83_matestoqueini ";
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
   function sql_query_file ( $m83_matestoqueini=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoquetransf ";
     $sql2 = "";
     if($dbwhere==""){
       if($m83_matestoqueini!=null ){
         $sql2 .= " where matestoquetransf.m83_matestoqueini = $m83_matestoqueini ";
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
   function sql_query_inill ( $m83_matestoqueini=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoquetransf ";
     $sql .= "      inner join db_depart a  on  a.coddepto = matestoquetransf.m83_coddepto";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoquetransf.m83_matestoqueini";
     $sql .= "      inner join db_depart   on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join db_usuarios   on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "      inner join matestoqueinimeipm  on matestoqueinimeipm.m89_matestoqueinimei = matestoqueinimei.m82_codigo";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matmater    on  matmater.m60_codmater = matestoque.m70_codmatmater";
     $sql .= "      left  join matestoqueinil  on  matestoqueinil.m86_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "      left  join matestoqueinill  on  matestoqueinill.m87_matestoqueinil = matestoqueinil.m86_codigo";
     $sql .= "      left  join matestoqueini b on  b.m80_codigo = matestoqueinill.m87_matestoqueini";
     $sql2 = "";
     if($dbwhere==""){
       if($m83_matestoqueini!=null ){
         $sql2 .= " where matestoquetransf.m83_matestoqueini = $m83_matestoqueini ";
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
   function sql_query_mater ( $m83_matestoqueini=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoquetransf ";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoquetransf.m83_matestoqueini";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m83_matestoqueini!=null ){
         $sql2 .= " where matestoquetransf.m83_matestoqueini = $m83_matestoqueini ";
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