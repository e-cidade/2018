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

//MODULO: issqn
//CLASSE DA ENTIDADE issplannumpre
class cl_issplannumpre { 
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
   var $q32_sequencial = 0; 
   var $q32_planilha = 0; 
   var $q32_numpre = 0; 
   var $q32_dataop_dia = null; 
   var $q32_dataop_mes = null; 
   var $q32_dataop_ano = null; 
   var $q32_dataop = null; 
   var $q32_horaop = null; 
   var $q32_status = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q32_sequencial = int4 = q32_sequencial 
                 q32_planilha = int4 = q32_planilha 
                 q32_numpre = int4 = q32_numpre 
                 q32_dataop = date = data da opereção 
                 q32_horaop = char(5) = hora da operação 
                 q32_status = int4 = Status 
                 ";
   //funcao construtor da classe 
   function cl_issplannumpre() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issplannumpre"); 
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
       $this->q32_sequencial = ($this->q32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_sequencial"]:$this->q32_sequencial);
       $this->q32_planilha = ($this->q32_planilha == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_planilha"]:$this->q32_planilha);
       $this->q32_numpre = ($this->q32_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_numpre"]:$this->q32_numpre);
       if($this->q32_dataop == ""){
         $this->q32_dataop_dia = ($this->q32_dataop_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_dataop_dia"]:$this->q32_dataop_dia);
         $this->q32_dataop_mes = ($this->q32_dataop_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_dataop_mes"]:$this->q32_dataop_mes);
         $this->q32_dataop_ano = ($this->q32_dataop_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_dataop_ano"]:$this->q32_dataop_ano);
         if($this->q32_dataop_dia != ""){
            $this->q32_dataop = $this->q32_dataop_ano."-".$this->q32_dataop_mes."-".$this->q32_dataop_dia;
         }
       }
       $this->q32_horaop = ($this->q32_horaop == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_horaop"]:$this->q32_horaop);
       $this->q32_status = ($this->q32_status == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_status"]:$this->q32_status);
     }else{
       $this->q32_sequencial = ($this->q32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q32_sequencial"]:$this->q32_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q32_sequencial){ 
      $this->atualizacampos();
     if($this->q32_planilha == null ){ 
       $this->erro_sql = " Campo q32_planilha nao Informado.";
       $this->erro_campo = "q32_planilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q32_numpre == null ){ 
       $this->erro_sql = " Campo q32_numpre nao Informado.";
       $this->erro_campo = "q32_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q32_dataop == null ){ 
       $this->q32_dataop = "null";
     }
     if($this->q32_status == null ){ 
       $this->erro_sql = " Campo Status nao Informado.";
       $this->erro_campo = "q32_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q32_sequencial == "" || $q32_sequencial == null ){
       $result = db_query("select nextval('issplannumpre_q32_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issplannumpre_q32_sequencial_seq do campo: q32_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q32_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issplannumpre_q32_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q32_sequencial)){
         $this->erro_sql = " Campo q32_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q32_sequencial = $q32_sequencial; 
       }
     }
     if(($this->q32_sequencial == null) || ($this->q32_sequencial == "") ){ 
       $this->erro_sql = " Campo q32_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issplannumpre(
                                       q32_sequencial 
                                      ,q32_planilha 
                                      ,q32_numpre 
                                      ,q32_dataop 
                                      ,q32_horaop 
                                      ,q32_status 
                       )
                values (
                                $this->q32_sequencial 
                               ,$this->q32_planilha 
                               ,$this->q32_numpre 
                               ,".($this->q32_dataop == "null" || $this->q32_dataop == ""?"null":"'".$this->q32_dataop."'")." 
                               ,'$this->q32_horaop' 
                               ,$this->q32_status 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "issplannumpre ($this->q32_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "issplannumpre já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "issplannumpre ($this->q32_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q32_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q32_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9199,'$this->q32_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1574,9199,'','".AddSlashes(pg_result($resaco,0,'q32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1574,9200,'','".AddSlashes(pg_result($resaco,0,'q32_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1574,9201,'','".AddSlashes(pg_result($resaco,0,'q32_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1574,9212,'','".AddSlashes(pg_result($resaco,0,'q32_dataop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1574,9213,'','".AddSlashes(pg_result($resaco,0,'q32_horaop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1574,12001,'','".AddSlashes(pg_result($resaco,0,'q32_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q32_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issplannumpre set ";
     $virgula = "";
     if(trim($this->q32_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q32_sequencial"])){ 
       $sql  .= $virgula." q32_sequencial = $this->q32_sequencial ";
       $virgula = ",";
       if(trim($this->q32_sequencial) == null ){ 
         $this->erro_sql = " Campo q32_sequencial nao Informado.";
         $this->erro_campo = "q32_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q32_planilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q32_planilha"])){ 
       $sql  .= $virgula." q32_planilha = $this->q32_planilha ";
       $virgula = ",";
       if(trim($this->q32_planilha) == null ){ 
         $this->erro_sql = " Campo q32_planilha nao Informado.";
         $this->erro_campo = "q32_planilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q32_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q32_numpre"])){ 
       $sql  .= $virgula." q32_numpre = $this->q32_numpre ";
       $virgula = ",";
       if(trim($this->q32_numpre) == null ){ 
         $this->erro_sql = " Campo q32_numpre nao Informado.";
         $this->erro_campo = "q32_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q32_dataop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q32_dataop_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q32_dataop_dia"] !="") ){ 
       $sql  .= $virgula." q32_dataop = '$this->q32_dataop' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q32_dataop_dia"])){ 
         $sql  .= $virgula." q32_dataop = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q32_horaop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q32_horaop"])){ 
       $sql  .= $virgula." q32_horaop = '$this->q32_horaop' ";
       $virgula = ",";
     }
     if(trim($this->q32_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q32_status"])){ 
       $sql  .= $virgula." q32_status = $this->q32_status ";
       $virgula = ",";
       if(trim($this->q32_status) == null ){ 
         $this->erro_sql = " Campo Status nao Informado.";
         $this->erro_campo = "q32_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q32_sequencial!=null){
       $sql .= " q32_sequencial = $this->q32_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q32_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9199,'$this->q32_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q32_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1574,9199,'".AddSlashes(pg_result($resaco,$conresaco,'q32_sequencial'))."','$this->q32_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q32_planilha"]))
           $resac = db_query("insert into db_acount values($acount,1574,9200,'".AddSlashes(pg_result($resaco,$conresaco,'q32_planilha'))."','$this->q32_planilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q32_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1574,9201,'".AddSlashes(pg_result($resaco,$conresaco,'q32_numpre'))."','$this->q32_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q32_dataop"]))
           $resac = db_query("insert into db_acount values($acount,1574,9212,'".AddSlashes(pg_result($resaco,$conresaco,'q32_dataop'))."','$this->q32_dataop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q32_horaop"]))
           $resac = db_query("insert into db_acount values($acount,1574,9213,'".AddSlashes(pg_result($resaco,$conresaco,'q32_horaop'))."','$this->q32_horaop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q32_status"]))
           $resac = db_query("insert into db_acount values($acount,1574,12001,'".AddSlashes(pg_result($resaco,$conresaco,'q32_status'))."','$this->q32_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issplannumpre nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issplannumpre nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q32_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q32_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9199,'$q32_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1574,9199,'','".AddSlashes(pg_result($resaco,$iresaco,'q32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1574,9200,'','".AddSlashes(pg_result($resaco,$iresaco,'q32_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1574,9201,'','".AddSlashes(pg_result($resaco,$iresaco,'q32_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1574,9212,'','".AddSlashes(pg_result($resaco,$iresaco,'q32_dataop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1574,9213,'','".AddSlashes(pg_result($resaco,$iresaco,'q32_horaop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1574,12001,'','".AddSlashes(pg_result($resaco,$iresaco,'q32_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issplannumpre
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q32_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q32_sequencial = $q32_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issplannumpre nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issplannumpre nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q32_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issplannumpre";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q32_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplannumpre ";
     $sql .= "      inner join issplan  on  issplan.q20_planilha = issplannumpre.q32_planilha";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issplan.q20_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q32_sequencial!=null ){
         $sql2 .= " where issplannumpre.q32_sequencial = $q32_sequencial "; 
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
   function sql_query_file ( $q32_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplannumpre ";
     $sql2 = "";
     if($dbwhere==""){
       if($q32_sequencial!=null ){
         $sql2 .= " where issplannumpre.q32_sequencial = $q32_sequencial "; 
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