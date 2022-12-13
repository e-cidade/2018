<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE orcreservaaut
class cl_orcreservaaut { 
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
   var $o83_codres = 0; 
   var $o83_autori = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o83_codres = int8 = Código 
                 o83_autori = int4 = Número da Autorização 
                 ";
   //funcao construtor da classe 
   function cl_orcreservaaut() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcreservaaut"); 
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
       $this->o83_codres = ($this->o83_codres == ""?@$GLOBALS["HTTP_POST_VARS"]["o83_codres"]:$this->o83_codres);
       $this->o83_autori = ($this->o83_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["o83_autori"]:$this->o83_autori);
     }else{
       $this->o83_codres = ($this->o83_codres == ""?@$GLOBALS["HTTP_POST_VARS"]["o83_codres"]:$this->o83_codres);
     }
   }
   // funcao para inclusao
   function incluir ($o83_codres){ 
      $this->atualizacampos();
     if($this->o83_autori == null ){ 
       $this->erro_sql = " Campo Número da Autorização nao Informado.";
       $this->erro_campo = "o83_autori";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o83_codres = $o83_codres; 
     if(($this->o83_codres == null) || ($this->o83_codres == "") ){ 
       $this->erro_sql = " Campo o83_codres nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcreservaaut(
                                       o83_codres 
                                      ,o83_autori 
                       )
                values (
                                $this->o83_codres 
                               ,$this->o83_autori 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reserva Autorização ($this->o83_codres) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reserva Autorização já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reserva Autorização ($this->o83_codres) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o83_codres;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o83_codres));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5370,'$this->o83_codres','I')");
       $resac = db_query("insert into db_acount values($acount,783,5370,'','".AddSlashes(pg_result($resaco,0,'o83_codres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,783,5371,'','".AddSlashes(pg_result($resaco,0,'o83_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o83_codres=null) { 
      $this->atualizacampos();
     $sql = " update orcreservaaut set ";
     $virgula = "";
     if(trim($this->o83_codres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o83_codres"])){ 
       $sql  .= $virgula." o83_codres = $this->o83_codres ";
       $virgula = ",";
       if(trim($this->o83_codres) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o83_codres";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o83_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o83_autori"])){ 
       $sql  .= $virgula." o83_autori = $this->o83_autori ";
       $virgula = ",";
       if(trim($this->o83_autori) == null ){ 
         $this->erro_sql = " Campo Número da Autorização nao Informado.";
         $this->erro_campo = "o83_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o83_codres!=null){
       $sql .= " o83_codres = $this->o83_codres";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o83_codres));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5370,'$this->o83_codres','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o83_codres"]))
           $resac = db_query("insert into db_acount values($acount,783,5370,'".AddSlashes(pg_result($resaco,$conresaco,'o83_codres'))."','$this->o83_codres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o83_autori"]))
           $resac = db_query("insert into db_acount values($acount,783,5371,'".AddSlashes(pg_result($resaco,$conresaco,'o83_autori'))."','$this->o83_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reserva Autorização nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o83_codres;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reserva Autorização nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o83_codres;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o83_codres;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o83_codres=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o83_codres));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5370,'$o83_codres','E')");
         $resac = db_query("insert into db_acount values($acount,783,5370,'','".AddSlashes(pg_result($resaco,$iresaco,'o83_codres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,783,5371,'','".AddSlashes(pg_result($resaco,$iresaco,'o83_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcreservaaut
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o83_codres != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o83_codres = $o83_codres ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reserva Autorização nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o83_codres;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reserva Autorização nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o83_codres;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o83_codres;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcreservaaut";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query_orcreserva ( $o83_codres=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreservaaut ";
     $sql .= "      inner join orcreserva  on  orcreserva.o80_codres = orcreservaaut.o83_codres";
     $sql2 = "";
     if($dbwhere==""){
       if($o83_codres!=null ){
         $sql2 .= " where orcreservaaut.o83_codres = $o83_codres "; 
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
   function sql_query ( $o83_codres=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreservaaut ";
     $sql .= "      inner join orcreserva            on orcreserva.o80_codres           = orcreservaaut.o83_codres";
     $sql .= "      inner join empautoriza           on empautoriza.e54_autori          = orcreservaaut.o83_autori";
     $sql .= "      inner join empautitem            on empautitem.e55_autori           = empautoriza.e54_autori";
     $sql .= "      inner join orcdotacao            on orcdotacao.o58_anousu           = orcreserva.o80_anousu";  
     $sql .= "                                      and orcdotacao.o58_coddot           = orcreserva.o80_coddot";
     $sql .= "      inner join cgm                   on cgm.z01_numcgm                  = empautoriza.e54_numcgm";
     $sql .= "      inner join db_config             on db_config.codigo                = empautoriza.e54_instit"; 
     $sql .= "                                      and empautoriza.e54_instit          = ".db_getsession("DB_instit");
     $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario          = empautoriza.e54_login";
     $sql .= "      inner join pctipocompra          on pctipocompra.pc50_codcom        = empautoriza.e54_codcom";
     $sql .= "      left  join empempaut             on empautoriza.e54_autori          = empempaut.e61_autori";
     $sql .= "      left  join empempenho            on empempenho.e60_numemp           = empempaut.e61_numemp";
     $sql .= "      left  join empautitempcprocitem  on empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "                                      and empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "      left  join pcprocitem            on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      left  join solicitem             on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql2 = "";
     if($dbwhere==""){
       if($o83_codres!=null ){
         $sql2 .= " where orcreservaaut.o83_codres = $o83_codres "; 
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

   function sql_query_file ( $o83_codres=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreservaaut ";
     $sql2 = "";
     if($dbwhere==""){
       if($o83_codres!=null ){
         $sql2 .= " where orcreservaaut.o83_codres = $o83_codres "; 
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