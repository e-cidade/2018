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

//MODULO: ISSQN
//CLASSE DA ENTIDADE meiprocessareg
class cl_meiprocessareg { 
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
   var $q112_sequencial = 0; 
   var $q112_meiprocessa = 0; 
   var $q112_meiimportameireg = 0; 
   var $q112_tipoprocessa = 0; 
   var $q112_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q112_sequencial = int4 = Sequencial 
                 q112_meiprocessa = int4 = Código de Processamento do MEI 
                 q112_meiimportameireg = int4 = Registro de Importação do MEI 
                 q112_tipoprocessa = int4 = Tipo de Processamento MEI 
                 q112_motivo = text = Motivo de Descarte do Registro 
                 ";
   //funcao construtor da classe 
   function cl_meiprocessareg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiprocessareg"); 
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
       $this->q112_sequencial = ($this->q112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q112_sequencial"]:$this->q112_sequencial);
       $this->q112_meiprocessa = ($this->q112_meiprocessa == ""?@$GLOBALS["HTTP_POST_VARS"]["q112_meiprocessa"]:$this->q112_meiprocessa);
       $this->q112_meiimportameireg = ($this->q112_meiimportameireg == ""?@$GLOBALS["HTTP_POST_VARS"]["q112_meiimportameireg"]:$this->q112_meiimportameireg);
       $this->q112_tipoprocessa = ($this->q112_tipoprocessa == ""?@$GLOBALS["HTTP_POST_VARS"]["q112_tipoprocessa"]:$this->q112_tipoprocessa);
       $this->q112_motivo = ($this->q112_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q112_motivo"]:$this->q112_motivo);
     }else{
       $this->q112_sequencial = ($this->q112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q112_sequencial"]:$this->q112_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q112_sequencial){ 
      $this->atualizacampos();
     if($this->q112_meiprocessa == null ){ 
       $this->erro_sql = " Campo Código de Processamento do MEI nao Informado.";
       $this->erro_campo = "q112_meiprocessa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q112_meiimportameireg == null ){ 
       $this->erro_sql = " Campo Registro de Importação do MEI nao Informado.";
       $this->erro_campo = "q112_meiimportameireg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q112_tipoprocessa == null ){ 
       $this->erro_sql = " Campo Tipo de Processamento MEI nao Informado.";
       $this->erro_campo = "q112_tipoprocessa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q112_sequencial == "" || $q112_sequencial == null ){
       $result = db_query("select nextval('meiprocessareg_q112_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiprocessareg_q112_sequencial_seq do campo: q112_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q112_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiprocessareg_q112_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q112_sequencial)){
         $this->erro_sql = " Campo q112_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q112_sequencial = $q112_sequencial; 
       }
     }
     if(($this->q112_sequencial == null) || ($this->q112_sequencial == "") ){ 
       $this->erro_sql = " Campo q112_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiprocessareg(
                                       q112_sequencial 
                                      ,q112_meiprocessa 
                                      ,q112_meiimportameireg 
                                      ,q112_tipoprocessa 
                                      ,q112_motivo 
                       )
                values (
                                $this->q112_sequencial 
                               ,$this->q112_meiprocessa 
                               ,$this->q112_meiimportameireg 
                               ,$this->q112_tipoprocessa 
                               ,'$this->q112_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro de Processamento do MEI ($this->q112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro de Processamento do MEI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro de Processamento do MEI ($this->q112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q112_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q112_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16635,'$this->q112_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2924,16635,'','".AddSlashes(pg_result($resaco,0,'q112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2924,16636,'','".AddSlashes(pg_result($resaco,0,'q112_meiprocessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2924,16637,'','".AddSlashes(pg_result($resaco,0,'q112_meiimportameireg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2924,16638,'','".AddSlashes(pg_result($resaco,0,'q112_tipoprocessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2924,16639,'','".AddSlashes(pg_result($resaco,0,'q112_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q112_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiprocessareg set ";
     $virgula = "";
     if(trim($this->q112_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q112_sequencial"])){ 
       $sql  .= $virgula." q112_sequencial = $this->q112_sequencial ";
       $virgula = ",";
       if(trim($this->q112_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q112_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q112_meiprocessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q112_meiprocessa"])){ 
       $sql  .= $virgula." q112_meiprocessa = $this->q112_meiprocessa ";
       $virgula = ",";
       if(trim($this->q112_meiprocessa) == null ){ 
         $this->erro_sql = " Campo Código de Processamento do MEI nao Informado.";
         $this->erro_campo = "q112_meiprocessa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q112_meiimportameireg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q112_meiimportameireg"])){ 
       $sql  .= $virgula." q112_meiimportameireg = $this->q112_meiimportameireg ";
       $virgula = ",";
       if(trim($this->q112_meiimportameireg) == null ){ 
         $this->erro_sql = " Campo Registro de Importação do MEI nao Informado.";
         $this->erro_campo = "q112_meiimportameireg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q112_tipoprocessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q112_tipoprocessa"])){ 
       $sql  .= $virgula." q112_tipoprocessa = $this->q112_tipoprocessa ";
       $virgula = ",";
       if(trim($this->q112_tipoprocessa) == null ){ 
         $this->erro_sql = " Campo Tipo de Processamento MEI nao Informado.";
         $this->erro_campo = "q112_tipoprocessa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q112_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q112_motivo"])){ 
       $sql  .= $virgula." q112_motivo = '$this->q112_motivo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q112_sequencial!=null){
       $sql .= " q112_sequencial = $this->q112_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q112_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16635,'$this->q112_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q112_sequencial"]) || $this->q112_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2924,16635,'".AddSlashes(pg_result($resaco,$conresaco,'q112_sequencial'))."','$this->q112_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q112_meiprocessa"]) || $this->q112_meiprocessa != "")
           $resac = db_query("insert into db_acount values($acount,2924,16636,'".AddSlashes(pg_result($resaco,$conresaco,'q112_meiprocessa'))."','$this->q112_meiprocessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q112_meiimportameireg"]) || $this->q112_meiimportameireg != "")
           $resac = db_query("insert into db_acount values($acount,2924,16637,'".AddSlashes(pg_result($resaco,$conresaco,'q112_meiimportameireg'))."','$this->q112_meiimportameireg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q112_tipoprocessa"]) || $this->q112_tipoprocessa != "")
           $resac = db_query("insert into db_acount values($acount,2924,16638,'".AddSlashes(pg_result($resaco,$conresaco,'q112_tipoprocessa'))."','$this->q112_tipoprocessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q112_motivo"]) || $this->q112_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2924,16639,'".AddSlashes(pg_result($resaco,$conresaco,'q112_motivo'))."','$this->q112_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Processamento do MEI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Processamento do MEI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q112_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q112_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16635,'$q112_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2924,16635,'','".AddSlashes(pg_result($resaco,$iresaco,'q112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2924,16636,'','".AddSlashes(pg_result($resaco,$iresaco,'q112_meiprocessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2924,16637,'','".AddSlashes(pg_result($resaco,$iresaco,'q112_meiimportameireg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2924,16638,'','".AddSlashes(pg_result($resaco,$iresaco,'q112_tipoprocessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2924,16639,'','".AddSlashes(pg_result($resaco,$iresaco,'q112_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiprocessareg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q112_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q112_sequencial = $q112_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Processamento do MEI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Processamento do MEI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q112_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiprocessareg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiprocessareg ";
     $sql .= "      inner join meiimportameireg  on  meiimportameireg.q111_sequencial = meiprocessareg.q112_meiimportameireg";
     $sql .= "      inner join meiprocessa  on  meiprocessa.q113_sequencial = meiprocessareg.q112_meiprocessa";
     $sql .= "      inner join meievento  on  meievento.q101_sequencial = meiimportameireg.q111_meievento";
     $sql .= "      inner join meiimportamei  on  meiimportamei.q105_sequencial = meiimportameireg.q111_meiimportamei";
     $sql .= "      left  join meiimportameiregatividade  on  meiimportameiregatividade.q106_sequencial = meiimportameireg.q111_meiimportameiregatividade";
     $sql .= "      left  join meiimportameiregempresa  on  meiimportameiregempresa.q107_sequencial = meiimportameireg.q111_meiimportameiregempresa";
     $sql .= "      left  join meiimportameiregresponsavel  on  meiimportameiregresponsavel.q108_sequencial = meiimportameireg.q111_meiimportameiregresponsavel";
     $sql .= "      left  join meiimportameiregcontador  on  meiimportameiregcontador.q109_sequencial = meiimportameireg.q111_meiimportameiregcontador";
     $sql2 = "";
     if($dbwhere==""){
       if($q112_sequencial!=null ){
         $sql2 .= " where meiprocessareg.q112_sequencial = $q112_sequencial "; 
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
   function sql_query_file ( $q112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiprocessareg ";
     $sql2 = "";
     if($dbwhere==""){
       if($q112_sequencial!=null ){
         $sql2 .= " where meiprocessareg.q112_sequencial = $q112_sequencial "; 
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