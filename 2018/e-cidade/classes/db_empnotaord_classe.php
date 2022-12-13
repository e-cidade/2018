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
//CLASSE DA ENTIDADE empnotaord
class cl_empnotaord { 
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
   var $m72_codordem = 0; 
   var $m72_codnota = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m72_codordem = int8 = Código 
                 m72_codnota = int4 = Nota 
                 ";
   //funcao construtor da classe 
   function cl_empnotaord() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empnotaord"); 
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
       $this->m72_codordem = ($this->m72_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_codordem"]:$this->m72_codordem);
       $this->m72_codnota = ($this->m72_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_codnota"]:$this->m72_codnota);
     }else{
       $this->m72_codordem = ($this->m72_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_codordem"]:$this->m72_codordem);
       $this->m72_codnota = ($this->m72_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_codnota"]:$this->m72_codnota);
     }
   }
   // funcao para inclusao
   function incluir ($m72_codnota,$m72_codordem){ 
      $this->atualizacampos();
       $this->m72_codnota = $m72_codnota; 
       $this->m72_codordem = $m72_codordem; 
     if(($this->m72_codnota == null) || ($this->m72_codnota == "") ){ 
       $this->erro_sql = " Campo m72_codnota nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->m72_codordem == null) || ($this->m72_codordem == "") ){ 
       $this->erro_sql = " Campo m72_codordem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empnotaord(
                                       m72_codordem 
                                      ,m72_codnota 
                       )
                values (
                                $this->m72_codordem 
                               ,$this->m72_codnota 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "nota por codigo da ordem de compra ($this->m72_codnota."-".$this->m72_codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "nota por codigo da ordem de compra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "nota por codigo da ordem de compra ($this->m72_codnota."-".$this->m72_codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codnota."-".$this->m72_codordem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m72_codnota,$this->m72_codordem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6313,'$this->m72_codnota','I')");
       $resac = db_query("insert into db_acountkey values($acount,6312,'$this->m72_codordem','I')");
       $resac = db_query("insert into db_acount values($acount,1029,6312,'','".AddSlashes(pg_result($resaco,0,'m72_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1029,6313,'','".AddSlashes(pg_result($resaco,0,'m72_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m72_codnota=null,$m72_codordem=null) { 
      $this->atualizacampos();
     $sql = " update empnotaord set ";
     $virgula = "";
     if(trim($this->m72_codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m72_codordem"])){ 
       $sql  .= $virgula." m72_codordem = $this->m72_codordem ";
       $virgula = ",";
       if(trim($this->m72_codordem) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m72_codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m72_codnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m72_codnota"])){ 
       $sql  .= $virgula." m72_codnota = $this->m72_codnota ";
       $virgula = ",";
       if(trim($this->m72_codnota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "m72_codnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m72_codnota!=null){
       $sql .= " m72_codnota = $this->m72_codnota";
     }
     if($m72_codordem!=null){
       $sql .= " and  m72_codordem = $this->m72_codordem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m72_codnota,$this->m72_codordem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6313,'$this->m72_codnota','A')");
         $resac = db_query("insert into db_acountkey values($acount,6312,'$this->m72_codordem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m72_codordem"]))
           $resac = db_query("insert into db_acount values($acount,1029,6312,'".AddSlashes(pg_result($resaco,$conresaco,'m72_codordem'))."','$this->m72_codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m72_codnota"]))
           $resac = db_query("insert into db_acount values($acount,1029,6313,'".AddSlashes(pg_result($resaco,$conresaco,'m72_codnota'))."','$this->m72_codnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "nota por codigo da ordem de compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codnota."-".$this->m72_codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "nota por codigo da ordem de compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codnota."-".$this->m72_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codnota."-".$this->m72_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m72_codnota=null,$m72_codordem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m72_codnota,$m72_codordem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6313,'$m72_codnota','E')");
         $resac = db_query("insert into db_acountkey values($acount,6312,'$m72_codordem','E')");
         $resac = db_query("insert into db_acount values($acount,1029,6312,'','".AddSlashes(pg_result($resaco,$iresaco,'m72_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1029,6313,'','".AddSlashes(pg_result($resaco,$iresaco,'m72_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empnotaord
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m72_codnota != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m72_codnota = $m72_codnota ";
        }
        if($m72_codordem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m72_codordem = $m72_codordem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "nota por codigo da ordem de compra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m72_codnota."-".$m72_codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "nota por codigo da ordem de compra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m72_codnota."-".$m72_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m72_codnota."-".$m72_codordem;
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
        $this->erro_sql   = "Record Vazio na Tabela:empnotaord";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m72_codnota=null,$m72_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotaord ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = empnotaord.m72_codnota";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = empnotaord.m72_codordem";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matordem.m51_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m72_codnota!=null ){
         $sql2 .= " where empnotaord.m72_codnota = $m72_codnota "; 
       } 
       if($m72_codordem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empnotaord.m72_codordem = $m72_codordem "; 
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
   function sql_query_elemento ( $m72_codnota=null,$m72_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotaord ";
     $sql .= "      inner join empnota     on  empnota.e69_codnota    = empnotaord.m72_codnota";
     $sql .= "      inner join empnotaele  on  empnota.e69_codnota    = empnotaele.e70_codnota";
     $sql .= "      inner join matordem    on  matordem.m51_codordem  = empnotaord.m72_codordem";
     $sql .= "      inner join db_usuarios on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp  = empnota.e69_numemp";
     $sql .= "      inner join cgm         on  cgm.z01_numcgm         = matordem.m51_numcgm";
     $sql .= "      inner join db_depart   on  db_depart.coddepto     = matordem.m51_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m72_codnota!=null ){
         $sql2 .= " where empnotaord.m72_codnota = $m72_codnota "; 
       } 
       if($m72_codordem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empnotaord.m72_codordem = $m72_codordem "; 
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
   function sql_query_file ( $m72_codnota=null,$m72_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotaord ";
     $sql2 = "";
     if($dbwhere==""){
       if($m72_codnota!=null ){
         $sql2 .= " where empnotaord.m72_codnota = $m72_codnota "; 
       } 
       if($m72_codordem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empnotaord.m72_codordem = $m72_codordem "; 
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

  function sql_matordem ( $m72_codnota=null, $m72_codordem=null, $campos="*", $ordem=null, $dbwhere="") {

    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from empnotaord ";
    $sql .= "      inner join matordem on m51_codordem = m72_codordem";
    $sql2 = "";

    if($dbwhere == "") {

      if($m72_codnota != null ) {
        $sql2 .= " where empnotaord.m72_codnota = $m72_codnota ";
      }

      if($m72_codordem != null ) {

        if($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }

        $sql2 .= " empnotaord.m72_codordem = $m72_codordem ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }
}