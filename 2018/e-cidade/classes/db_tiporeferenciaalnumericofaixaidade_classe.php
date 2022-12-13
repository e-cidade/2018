<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: laboratorio
//CLASSE DA ENTIDADE tiporeferenciaalnumericofaixaidade
class cl_tiporeferenciaalnumericofaixaidade { 
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
   var $la59_sequencial = 0; 
   var $la59_tiporeferencialnumerico = 0; 
   var $la59_periodoinicial = null; 
   var $la59_periodofinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la59_sequencial = int4 = C�digo 
                 la59_tiporeferencialnumerico = int4 = C�digo da Refer�ncia 
                 la59_periodoinicial = interval Per�odo Inicial de Refer�ncia
                 la59_periodofinal = interval = Periodo Final de Refer�ncia
                 ";
   //funcao construtor da classe 
   function cl_tiporeferenciaalnumericofaixaidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tiporeferenciaalnumericofaixaidade"); 
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
       $this->la59_sequencial = ($this->la59_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la59_sequencial"]:$this->la59_sequencial);
       $this->la59_tiporeferencialnumerico = ($this->la59_tiporeferencialnumerico == ""?@$GLOBALS["HTTP_POST_VARS"]["la59_tiporeferencialnumerico"]:$this->la59_tiporeferencialnumerico);
       $this->la59_periodoinicial = ($this->la59_periodoinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["la59_periodoinicial"]:$this->la59_periodoinicial);
       $this->la59_periodofinal = ($this->la59_periodofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["la59_periodofinal"]:$this->la59_periodofinal);
     }else{
       $this->la59_sequencial = ($this->la59_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la59_sequencial"]:$this->la59_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($la59_sequencial){ 
      $this->atualizacampos();
     if($this->la59_tiporeferencialnumerico == null ){ 
       $this->erro_sql = " Campo C�digo da Refer�ncia n�o informado.";
       $this->erro_campo = "la59_tiporeferencialnumerico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la59_periodoinicial == null ){ 
       $this->la59_periodoinicial = "null";
     }
     if($la59_sequencial == "" || $la59_sequencial == null ){
       $result = db_query("select nextval('tiporeferenciaalnumericofaixaidade_la59_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tiporeferenciaalnumericofaixaidade_la59_sequencial_seq do campo: la59_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la59_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tiporeferenciaalnumericofaixaidade_la59_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $la59_sequencial)){
         $this->erro_sql = " Campo la59_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la59_sequencial = $la59_sequencial; 
       }
     }
     if(($this->la59_sequencial == null) || ($this->la59_sequencial == "") ){ 
       $this->erro_sql = " Campo la59_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tiporeferenciaalnumericofaixaidade(
                                       la59_sequencial 
                                      ,la59_tiporeferencialnumerico 
                                      ,la59_periodoinicial 
                                      ,la59_periodofinal 
                       )
                values (
                                $this->la59_sequencial 
                               ,$this->la59_tiporeferencialnumerico 
                               ,'$this->la59_periodoinicial' 
                               ,'$this->la59_periodofinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Referencia por faixa de idade ($this->la59_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Referencia por faixa de idade j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Referencia por faixa de idade ($this->la59_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la59_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la59_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20485,'$this->la59_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3684,20485,'','".AddSlashes(pg_result($resaco,0,'la59_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3684,20486,'','".AddSlashes(pg_result($resaco,0,'la59_tiporeferencialnumerico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3684,20487,'','".AddSlashes(pg_result($resaco,0,'la59_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3684,20488,'','".AddSlashes(pg_result($resaco,0,'la59_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la59_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tiporeferenciaalnumericofaixaidade set ";
     $virgula = "";
     if(trim($this->la59_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la59_sequencial"])){ 
       $sql  .= $virgula." la59_sequencial = $this->la59_sequencial ";
       $virgula = ",";
       if(trim($this->la59_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "la59_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la59_tiporeferencialnumerico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la59_tiporeferencialnumerico"])){ 
       $sql  .= $virgula." la59_tiporeferencialnumerico = $this->la59_tiporeferencialnumerico ";
       $virgula = ",";
       if(trim($this->la59_tiporeferencialnumerico) == null ){ 
         $this->erro_sql = " Campo C�digo da Refer�ncia n�o informado.";
         $this->erro_campo = "la59_tiporeferencialnumerico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la59_periodoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la59_periodoinicial"])){ 
       $sql  .= $virgula." la59_periodoinicial = '$this->la59_periodoinicial' ";
       $virgula = ",";
     }
     if(trim($this->la59_periodofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la59_periodofinal"])){ 
       $sql  .= $virgula." la59_periodofinal = '$this->la59_periodofinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la59_sequencial!=null){
       $sql .= " la59_sequencial = $this->la59_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la59_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20485,'$this->la59_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la59_sequencial"]) || $this->la59_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3684,20485,'".AddSlashes(pg_result($resaco,$conresaco,'la59_sequencial'))."','$this->la59_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la59_tiporeferencialnumerico"]) || $this->la59_tiporeferencialnumerico != "")
             $resac = db_query("insert into db_acount values($acount,3684,20486,'".AddSlashes(pg_result($resaco,$conresaco,'la59_tiporeferencialnumerico'))."','$this->la59_tiporeferencialnumerico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la59_periodoinicial"]) || $this->la59_periodoinicial != "")
             $resac = db_query("insert into db_acount values($acount,3684,20487,'".AddSlashes(pg_result($resaco,$conresaco,'la59_periodoinicial'))."','$this->la59_periodoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la59_periodofinal"]) || $this->la59_periodofinal != "")
             $resac = db_query("insert into db_acount values($acount,3684,20488,'".AddSlashes(pg_result($resaco,$conresaco,'la59_periodofinal'))."','$this->la59_periodofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Referencia por faixa de idade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la59_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Referencia por faixa de idade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la59_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la59_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la59_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($la59_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20485,'$la59_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3684,20485,'','".AddSlashes(pg_result($resaco,$iresaco,'la59_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3684,20486,'','".AddSlashes(pg_result($resaco,$iresaco,'la59_tiporeferencialnumerico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3684,20487,'','".AddSlashes(pg_result($resaco,$iresaco,'la59_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3684,20488,'','".AddSlashes(pg_result($resaco,$iresaco,'la59_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tiporeferenciaalnumericofaixaidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la59_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la59_sequencial = $la59_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Referencia por faixa de idade nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la59_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Referencia por faixa de idade nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la59_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la59_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:tiporeferenciaalnumericofaixaidade";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la59_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiporeferenciaalnumericofaixaidade ";
     $sql .= "      inner join lab_tiporeferenciaalnumerico  on  lab_tiporeferenciaalnumerico.la30_i_codigo = tiporeferenciaalnumericofaixaidade.la59_tiporeferencialnumerico";
     $sql .= "      left  join lab_valorreferencia  on  lab_valorreferencia.la27_i_codigo = lab_tiporeferenciaalnumerico.la30_i_valorref";
     $sql2 = "";
     if($dbwhere==""){
       if($la59_sequencial!=null ){
         $sql2 .= " where tiporeferenciaalnumericofaixaidade.la59_sequencial = $la59_sequencial "; 
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
   function sql_query_file ( $la59_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiporeferenciaalnumericofaixaidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($la59_sequencial!=null ){
         $sql2 .= " where tiporeferenciaalnumericofaixaidade.la59_sequencial = $la59_sequencial "; 
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