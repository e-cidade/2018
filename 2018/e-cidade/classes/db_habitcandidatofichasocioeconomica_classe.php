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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitcandidatofichasocioeconomica
class cl_habitcandidatofichasocioeconomica { 
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
   var $ht11_sequencial = 0; 
   var $ht11_candidato = 0; 
   var $ht11_habitfichasocioeconomica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht11_sequencial = int4 = Sequencial 
                 ht11_candidato = int4 = Candidato 
                 ht11_habitfichasocioeconomica = int4 = Ficha Sócio Econônica 
                 ";
   //funcao construtor da classe 
   function cl_habitcandidatofichasocioeconomica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitcandidatofichasocioeconomica"); 
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
       $this->ht11_sequencial = ($this->ht11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht11_sequencial"]:$this->ht11_sequencial);
       $this->ht11_candidato = ($this->ht11_candidato == ""?@$GLOBALS["HTTP_POST_VARS"]["ht11_candidato"]:$this->ht11_candidato);
       $this->ht11_habitfichasocioeconomica = ($this->ht11_habitfichasocioeconomica == ""?@$GLOBALS["HTTP_POST_VARS"]["ht11_habitfichasocioeconomica"]:$this->ht11_habitfichasocioeconomica);
     }else{
       $this->ht11_sequencial = ($this->ht11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht11_sequencial"]:$this->ht11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht11_sequencial){ 
      $this->atualizacampos();
     if($this->ht11_candidato == null ){ 
       $this->erro_sql = " Campo Candidato nao Informado.";
       $this->erro_campo = "ht11_candidato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht11_habitfichasocioeconomica == null ){ 
       $this->erro_sql = " Campo Ficha Sócio Econônica nao Informado.";
       $this->erro_campo = "ht11_habitfichasocioeconomica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht11_sequencial == "" || $ht11_sequencial == null ){
       $result = db_query("select nextval('habitcandidatofichasocioeconomica_ht11_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitcandidatofichasocioeconomica_ht11_sequencial_seq do campo: ht11_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht11_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitcandidatofichasocioeconomica_ht11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht11_sequencial)){
         $this->erro_sql = " Campo ht11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht11_sequencial = $ht11_sequencial; 
       }
     }
     if(($this->ht11_sequencial == null) || ($this->ht11_sequencial == "") ){ 
       $this->erro_sql = " Campo ht11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitcandidatofichasocioeconomica(
                                       ht11_sequencial 
                                      ,ht11_candidato 
                                      ,ht11_habitfichasocioeconomica 
                       )
                values (
                                $this->ht11_sequencial 
                               ,$this->ht11_candidato 
                               ,$this->ht11_habitfichasocioeconomica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ficha Socio Economica do Candidato da Habitação ($this->ht11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ficha Socio Economica do Candidato da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ficha Socio Economica do Candidato da Habitação ($this->ht11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16991,'$this->ht11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2999,16991,'','".AddSlashes(pg_result($resaco,0,'ht11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2999,16993,'','".AddSlashes(pg_result($resaco,0,'ht11_candidato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2999,16992,'','".AddSlashes(pg_result($resaco,0,'ht11_habitfichasocioeconomica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht11_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitcandidatofichasocioeconomica set ";
     $virgula = "";
     if(trim($this->ht11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht11_sequencial"])){ 
       $sql  .= $virgula." ht11_sequencial = $this->ht11_sequencial ";
       $virgula = ",";
       if(trim($this->ht11_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht11_candidato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht11_candidato"])){ 
       $sql  .= $virgula." ht11_candidato = $this->ht11_candidato ";
       $virgula = ",";
       if(trim($this->ht11_candidato) == null ){ 
         $this->erro_sql = " Campo Candidato nao Informado.";
         $this->erro_campo = "ht11_candidato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht11_habitfichasocioeconomica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht11_habitfichasocioeconomica"])){ 
       $sql  .= $virgula." ht11_habitfichasocioeconomica = $this->ht11_habitfichasocioeconomica ";
       $virgula = ",";
       if(trim($this->ht11_habitfichasocioeconomica) == null ){ 
         $this->erro_sql = " Campo Ficha Sócio Econônica nao Informado.";
         $this->erro_campo = "ht11_habitfichasocioeconomica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht11_sequencial!=null){
       $sql .= " ht11_sequencial = $this->ht11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16991,'$this->ht11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht11_sequencial"]) || $this->ht11_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2999,16991,'".AddSlashes(pg_result($resaco,$conresaco,'ht11_sequencial'))."','$this->ht11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht11_candidato"]) || $this->ht11_candidato != "")
           $resac = db_query("insert into db_acount values($acount,2999,16993,'".AddSlashes(pg_result($resaco,$conresaco,'ht11_candidato'))."','$this->ht11_candidato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht11_habitfichasocioeconomica"]) || $this->ht11_habitfichasocioeconomica != "")
           $resac = db_query("insert into db_acount values($acount,2999,16992,'".AddSlashes(pg_result($resaco,$conresaco,'ht11_habitfichasocioeconomica'))."','$this->ht11_habitfichasocioeconomica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ficha Socio Economica do Candidato da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ficha Socio Economica do Candidato da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht11_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht11_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16991,'$ht11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2999,16991,'','".AddSlashes(pg_result($resaco,$iresaco,'ht11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2999,16993,'','".AddSlashes(pg_result($resaco,$iresaco,'ht11_candidato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2999,16992,'','".AddSlashes(pg_result($resaco,$iresaco,'ht11_habitfichasocioeconomica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitcandidatofichasocioeconomica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht11_sequencial = $ht11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ficha Socio Economica do Candidato da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ficha Socio Economica do Candidato da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht11_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitcandidatofichasocioeconomica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitcandidatofichasocioeconomica ";
     $sql .= "      inner join habitcandidato  on  habitcandidato.ht10_sequencial = habitcandidatofichasocioeconomica.ht11_candidato";
     $sql .= "      inner join habitfichasocioeconomica  on  habitfichasocioeconomica.ht12_sequencial = habitcandidatofichasocioeconomica.ht11_habitfichasocioeconomica";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = habitcandidato.ht10_numcgm";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = habitfichasocioeconomica.ht12_avaliacaogruporesposta";
     $sql2 = "";
     if($dbwhere==""){
       if($ht11_sequencial!=null ){
         $sql2 .= " where habitcandidatofichasocioeconomica.ht11_sequencial = $ht11_sequencial "; 
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
   function sql_query_file ( $ht11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitcandidatofichasocioeconomica ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht11_sequencial!=null ){
         $sql2 .= " where habitcandidatofichasocioeconomica.ht11_sequencial = $ht11_sequencial "; 
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